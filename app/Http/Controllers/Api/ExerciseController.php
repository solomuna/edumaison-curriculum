<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\ExerciseAttempt;
use App\Models\PronunciationAttempt;
use App\Models\Child;
use App\Models\ChildLearningPack;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Services\NationalLanguageProfileService;
use App\Support\FamilyContext;

class ExerciseController extends Controller
{
    public function forChild(Request $request, int $childId)
    {
        $levelId = (int) $request->query('level_id', 0);
        $householdId = (int) (FamilyContext::householdId($request) ?? 0);
        abort_unless($householdId > 0, 403);

        $query = Exercise::with(['lesson.unit.integratedTheme.subject'])
            ->where('is_active', true);
        app(NationalLanguageProfileService::class)
            ->restrictExerciseQuery($query, $childId, $householdId);

        if ($levelId) {
            // Révision générale : tous les niveaux <= level_id du child
            $query->whereHas('lesson.unit.integratedTheme.subject', function ($q) use ($levelId) {
                $q->where('level_id', '<=', $levelId);
            });
        }

        $exercises = $query
            ->inRandomOrder()
            ->limit(100)
            ->get()
            ->map(fn($e) => [
                'id'           => $e->id,
                'title'        => $e->title,
                'instructions' => $e->instructions,
                'category'     => $e->category,
                'difficulty'   => $e->difficulty,
                'subject'      => $e->lesson?->unit?->integratedTheme?->subject?->name ?? 'General',
                'level_id'     => $e->lesson?->unit?->integratedTheme?->subject?->level_id,
                'content'      => is_array($e->content) ? $e->content : json_decode($e->content, true),
            ]);

        return response()->json($exercises);
    }

    public function forSubject(Request $request, int $childId, int $subjectId)
    {
        $householdId = (int) (FamilyContext::householdId($request) ?? 0);
        abort_unless($householdId > 0, 403);
        $exercises = Exercise::with(['lesson.unit.integratedTheme.subject'])
            ->where('is_active', true)
            ->whereHas('lesson.unit.integratedTheme', function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            });
        app(NationalLanguageProfileService::class)
            ->restrictExerciseQuery($exercises, $childId, $householdId);
        $exercises = $exercises
            ->get()
            ->map(fn($e) => [
                'id'           => $e->id,
                'title'        => $e->title,
                'instructions' => $e->instructions,
                'category'     => $e->category,
                'difficulty'   => $e->difficulty,
                'subject'      => $e->lesson?->unit?->integratedTheme?->subject?->name ?? 'General',
                'level_id'     => $e->lesson?->unit?->integratedTheme?->subject?->level_id,
                'content'      => is_array($e->content) ? $e->content : json_decode($e->content, true),
            ]);

        return response()->json($exercises);
    }

    public function attempt(Request $request)
    {
        $validated = $request->validate([
            'child_id'           => 'required|integer|exists:children,id',
            'exercise_id'        => 'required|integer|exists:exercises,id',
            'score'              => 'nullable|integer|min:0|max:100',
            'status'             => 'nullable|in:completed,incomplete,skipped',
            'verification_status'=> 'nullable|in:auto_checked,pending_review,practice_only,client_checked',
            'answers'            => 'nullable|array',
            'answers.items.*.audio_data_url' => 'nullable|string|max:4000000',
            'answers.items.*.assessment_token' => 'nullable|uuid',
            'evidence'           => 'nullable|array',
            'duration_seconds'   => 'nullable|integer|min:0|max:86400',
        ]);
        $validated = $this->withSubmittedAnswers($validated, $request);

        $exercise = Exercise::query()->findOrFail($validated['exercise_id']);
        $householdId = (int) (FamilyContext::householdId($request) ?? 0);
        abort_unless($householdId > 0, 403);
        app(NationalLanguageProfileService::class)->assertExerciseAvailable(
            (int) $exercise->id,
            (int) $validated['child_id'],
            $householdId
        );
        $content = is_array($exercise->content) ? $exercise->content : json_decode($exercise->content, true) ?? [];
        $type = (string) ($content['type'] ?? '');
        if ($type === 'oral_drill') {
            $validated = $this->resolveSpeakingAssessments($validated, $exercise, $content);
        }
        [$score, $verificationStatus, $answers, $evidence] = $this->verifiedAttemptData(
            $type,
            $content,
            $validated,
        );

        $audioPaths = [];
        if ($type === 'oral_drill') {
            foreach (($validated['answers']['items'] ?? []) as $index => $submittedItem) {
                $dataUrl = trim((string) ($submittedItem['audio_data_url'] ?? ''));
                if ($dataUrl !== '') $audioPaths[$index] = $this->storeSpeakingAudio($dataUrl, (int) $validated['child_id']);
            }
        }

        try {
            $attempt = DB::transaction(function () use ($validated, $score, $verificationStatus, $answers, $evidence, $type, $audioPaths) {
                $schoolYear = SchoolYear::where('is_current', true)->first();
                $attempt = ExerciseAttempt::create([
                    'child_id' => $validated['child_id'],
                    'exercise_id' => $validated['exercise_id'],
                    'school_year_id' => $schoolYear?->id ?? 1,
                    'score' => $score,
                    'max_score' => 100,
                    'duration_seconds' => $validated['duration_seconds'] ?? null,
                    'status' => $validated['status'] ?? 'completed',
                    'verification_status' => $verificationStatus,
                    'answers' => $answers,
                    'evidence' => $evidence,
                    'attempted_at' => now(),
                ]);

                if ($type === 'oral_drill') {
                    foreach (($answers['items'] ?? []) as $index => $item) {
                        PronunciationAttempt::create([
                            'child_id' => $validated['child_id'],
                            'exercise_id' => $validated['exercise_id'],
                            'exercise_attempt_id' => $attempt->id,
                            'target_text' => $item['target'],
                            'recorded_audio_path' => $audioPaths[$index] ?? null,
                            'overall_score' => $item['score'],
                            'fluency_score' => $item['assessment']['fluency_score'] ?? null,
                            'prosody_score' => null,
                            'pronunciation_score' => null,
                            'feedback_json' => [
                                'transcript' => $item['transcript'],
                                'assessment_method' => $item['method'],
                                'automatic_assessment' => $item['assessment'] ?? null,
                                'pronunciation_verified' => $item['method'] === 'pronunciation_assessment',
                            ],
                            'attempted_at' => now(),
                        ]);
                    }
                }

                $this->syncLearningPackProgress((int) $validated['child_id'], (int) $validated['exercise_id']);
                return $attempt;
            });
        } catch (\Throwable $exception) {
            foreach ($audioPaths as $path) Storage::disk('local')->delete($path);
            throw $exception;
        }

        return response()->json([
            'success' => true,
            'attempt_id' => $attempt->id,
            'score' => $attempt->score,
            'verification_status' => $attempt->verification_status,
        ]);
    }

    /**
     * validated() ne conserve, sous « answers », que les clés visées par les
     * règles « answers.items.*.audio_data_url / assessment_token » : il effaçait
     * les réponses du QCM, du texte à trous et de la dictée (422 « A response is
     * required… ») et la transcription de l'oral. Les règles restent appliquées ;
     * la notation lit les réponses telles qu'envoyées.
     */
    private function withSubmittedAnswers(array $validated, Request $request): array
    {
        $answers = $request->input('answers');
        $validated['answers'] = is_array($answers) ? $answers : null;

        return $validated;
    }

    private function verifiedAttemptData(string $type, array $content, array $validated): array
    {
        $answers = $validated['answers'] ?? [];
        $evidence = $validated['evidence'] ?? [];

        if (in_array($type, ['mcq', 'multiple_choice'], true)) {
            $questions = collect($content['questions'] ?? []);
            if ($questions->isEmpty() && isset($content['question'], $content['options'])) {
                $questions = collect([[
                    'question' => $content['question'],
                    'options' => $content['options'],
                    'answer' => $content['answer'] ?? 0,
                ]]);
            }
            $submitted = collect($answers['items'] ?? [])->keyBy(fn ($item) => (int) ($item['question_index'] ?? -1));
            if ($questions->isEmpty() || $questions->count() !== $submitted->count()) {
                throw ValidationException::withMessages(['answers.items' => 'A response is required for every question.']);
            }
            $cleanItems = $questions->map(function ($question, $index) use ($submitted) {
                $entry = (array) $submitted->get($index, []);
                $selected = (int) ($entry['selected_index'] ?? -1);
                $options = array_values((array) ($question['options'] ?? []));
                $answer = $question['answer'] ?? 0;
                $correctIndex = is_numeric($answer) ? (int) $answer : array_search($answer, $options, true);
                if ($selected < 0 || $selected >= count($options) || $correctIndex === false) {
                    throw ValidationException::withMessages(['answers.items' => 'An answer index is invalid.']);
                }
                return [
                    'question_index' => $index,
                    'selected_index' => $selected,
                    'correct' => $selected === $correctIndex,
                ];
            });
            $score = (int) round($cleanItems->where('correct', true)->count() / $questions->count() * 100);
            return [$score, 'auto_checked', ['items' => $cleanItems->all()], ['method' => 'server_answer_key']];
        }

        if ($type === 'fill_in') {
            $items = collect($content['items'] ?? $content['sentences'] ?? (isset($content['sentence']) ? [$content] : []))->values();
            $submitted = collect($answers['items'] ?? [])->map(fn ($answer) => trim((string) $answer))->values();
            if ($items->isEmpty() || $items->count() !== $submitted->count()) {
                throw ValidationException::withMessages(['answers.items' => 'A response is required for every blank.']);
            }
            $cleanItems = $items->map(function ($item, $index) use ($submitted) {
                $accepted = collect([(string) ($item['answer'] ?? '')])
                    ->merge($item['alternatives'] ?? [])
                    ->map(fn ($answer) => $this->exactAnswer((string) $answer));
                $response = $this->exactAnswer($submitted[$index]);
                return ['index' => $index, 'response' => $submitted[$index], 'correct' => $accepted->contains($response)];
            });
            $score = (int) round($cleanItems->where('correct', true)->count() / $items->count() * 100);
            return [$score, 'auto_checked', ['items' => $cleanItems->all()], ['method' => 'server_answer_key']];
        }

        if ($type === 'true_false') {
            if (! array_key_exists('selected', $answers) || ! is_bool($answers['selected'])) {
                throw ValidationException::withMessages(['answers.selected' => 'A true or false response is required.']);
            }
            $correct = $answers['selected'] === (bool) ($content['answer'] ?? false);
            return [$correct ? 100 : 0, 'auto_checked', [
                'selected' => $answers['selected'],
                'correct' => $correct,
            ], ['method' => 'server_answer_key']];
        }

        if ($type === 'match_pairs') {
            $expected = collect($content['pairs'] ?? [])->map(function ($pair) {
                if (is_array($pair) && array_is_list($pair)) return ['left' => (string) ($pair[0] ?? ''), 'right' => (string) ($pair[1] ?? '')];
                return [
                    'left' => (string) ($pair['word'] ?? $pair['left'] ?? ''),
                    'right' => (string) ($pair['image'] ?? $pair['right'] ?? $pair['definition'] ?? ''),
                ];
            })->values();
            $submitted = collect($answers['pairs'] ?? [])->map(fn ($pair) => [
                'left' => (string) ($pair['left'] ?? ''),
                'right' => (string) ($pair['right'] ?? ''),
            ])->values();
            if ($expected->isEmpty() || $expected->count() !== $submitted->count()) {
                throw ValidationException::withMessages(['answers.pairs' => 'Every item must be paired.']);
            }
            // Comparaison en multiensemble : un même mot à gauche peut apparaître
            // plusieurs fois (« I am … », « I am … ») avec des fins différentes.
            $key = fn ($pair) => $pair['left']."\u{1F}".$pair['right'];
            $correct = $expected->map($key)->sort()->values()->all() === $submitted->map($key)->sort()->values()->all();
            return [$correct ? 100 : 0, 'auto_checked', ['pairs' => $submitted->all(), 'correct' => $correct], ['method' => 'server_answer_key']];
        }

        if ($type === 'sentence_order') {
            $submitted = array_values(array_map('strval', $answers['words'] ?? []));
            $expected = $content['answer'] ?? $content['correct'] ?? [];
            if (is_string($expected)) $expected = preg_split('/\s+/u', trim($expected), -1, PREG_SPLIT_NO_EMPTY);
            $expected = array_values(array_map('strval', (array) $expected));
            if ($submitted === []) {
                throw ValidationException::withMessages(['answers.words' => 'The ordered sentence is required.']);
            }
            $correct = $submitted === $expected;
            return [$correct ? 100 : 0, 'auto_checked', ['words' => $submitted, 'correct' => $correct], ['method' => 'server_answer_key']];
        }

        if (in_array($type, ['clock_reading', 'geometry'], true)) {
            $options = array_values((array) ($content['options'] ?? []));
            if ($options === [] && ($answers['practice_only'] ?? false) === true) {
                return [0, 'practice_only', ['practice_only' => true], ['method' => 'practice_only']];
            }

            $answer = $content['answer'] ?? null;
            $correctIndex = is_numeric($answer) ? (int) $answer : array_search($answer, $options, true);
            $selected = $answers['selected_index'] ?? null;
            if (! is_int($selected)
                || $selected < 0
                || $selected >= count($options)
                || $correctIndex === false
                || $correctIndex < 0
                || $correctIndex >= count($options)) {
                throw ValidationException::withMessages(['answers.selected_index' => 'A valid option is required.']);
            }

            $correct = $selected === $correctIndex;
            return [$correct ? 100 : 0, 'auto_checked', [
                'selected_index' => $selected,
                'correct' => $correct,
            ], ['method' => 'server_answer_key']];
        }

        if ($type === 'number_line') {
            $options = collect($content['options'] ?? [])->map(fn ($value) => (int) $value)->values();
            $selected = $answers['selected_value'] ?? null;
            $expected = $content['answer'] ?? null;
            if ($expected === null) {
                $expected = (int) ($content['start'] ?? 0)
                    + collect($content['jumps'] ?? [])->sum(fn ($jump) => (int) $jump);
            }
            if (! is_numeric($selected) || $options->isEmpty() || ! $options->contains((int) $selected)) {
                throw ValidationException::withMessages(['answers.selected_value' => 'A valid number-line option is required.']);
            }

            $selected = (int) $selected;
            $correct = $selected === (int) $expected;
            return [$correct ? 100 : 0, 'auto_checked', [
                'selected_value' => $selected,
                'correct' => $correct,
            ], ['method' => 'server_answer_key']];
        }

        if ($type === 'venn_diagram') {
            $setA = array_values(array_map('strval', (array) ($content['setA'] ?? [])));
            $setB = array_values(array_map('strval', (array) ($content['setB'] ?? [])));
            $items = array_values(array_map(
                'strval',
                (array) ($content['items'] ?? array_values(array_unique(array_merge($setA, $setB)))),
            ));
            $placements = (array) ($answers['placements'] ?? []);
            if ($items === [] || count($placements) !== count($items)) {
                throw ValidationException::withMessages(['answers.placements' => 'Every Venn item must be placed.']);
            }

            $cleanPlacements = [];
            $correct = true;
            foreach ($items as $item) {
                $zone = $placements[$item] ?? null;
                if (! in_array($zone, ['A', 'B', 'AB'], true)) {
                    throw ValidationException::withMessages(['answers.placements' => 'A Venn placement is invalid.']);
                }
                $inA = in_array($item, $setA, true);
                $inB = in_array($item, $setB, true);
                $expectedZone = $inA && $inB ? 'AB' : ($inA ? 'A' : ($inB ? 'B' : null));
                if ($expectedZone === null) {
                    throw ValidationException::withMessages(['answers.placements' => 'A Venn item does not belong to either set.']);
                }
                $cleanPlacements[$item] = $zone;
                $correct = $correct && $zone === $expectedZone;
            }

            return [$correct ? 100 : 0, 'auto_checked', [
                'placements' => $cleanPlacements,
                'correct' => $correct,
            ], ['method' => 'server_answer_key']];
        }

        if ($type === 'dictation') {
            $expected = collect($content['items'] ?? [])->pluck('text')->map(fn ($text) => (string) $text)->values();
            $submitted = collect($answers['items'] ?? [])->map(fn ($text) => (string) $text)->values();
            if ($expected->isEmpty() || $expected->count() !== $submitted->count()) {
                throw ValidationException::withMessages(['answers.items' => 'A response is required for every dictation item.']);
            }
            $maxReplays = max(1, min(5, (int) ($content['max_replays'] ?? 3)));
            $replays = collect($evidence['replays'] ?? [])->map(fn ($count) => (int) $count)->values();
            if ($replays->count() !== $expected->count() || $replays->contains(fn ($count) => $count < 1 || $count > $maxReplays)) {
                throw ValidationException::withMessages(['evidence.replays' => 'Each dictation item must be listened to within the replay limit.']);
            }
            $breakdown = $expected->map(fn ($text, $index) => $this->dictationScore($text, $submitted[$index]));
            $score = (int) round($breakdown->avg('score'));
            return [$score, 'auto_checked', ['items' => $submitted->all()], [
                'method' => 'dictation_words_spelling_mechanics',
                'item_scores' => $breakdown->pluck('score')->all(),
                'item_breakdown' => $breakdown->all(),
                'replays' => $replays->all(),
            ]];
        }

        if ($type === 'oral_drill') {
            $expected = collect($content['items'] ?? [])->pluck('text')->map(fn ($text) => (string) $text)->values();
            $submitted = collect($answers['items'] ?? [])->values();
            if ($expected->isEmpty() || $expected->count() !== $submitted->count()) {
                throw ValidationException::withMessages(['answers.items' => 'A response or an explicit skip is required for every speaking item.']);
            }
            // Comme Duolingo : une phrase dite est notée par la correspondance des
            // mots reconnus (transcription du navigateur, tolérante aux accents).
            // La prononciation n'est jugée qu'avec l'évaluation serveur (Azure).
            // Seule une phrase sautée (sans transcription) laisse la tentative
            // en simple entraînement.
            $practiceOnly = false;
            $pronunciationVerified = true;
            $cleanItems = $expected->map(function ($target, $index) use ($submitted, &$practiceOnly, &$pronunciationVerified) {
                $entry = (array) $submitted[$index];
                $assessment = is_array($entry['_server_assessment'] ?? null)
                    ? $this->sanitizeSpeakingAssessment($entry['_server_assessment'])
                    : null;
                $transcript = trim((string) ($assessment['transcript'] ?? $entry['transcript'] ?? ''));
                $method = $assessment ? 'pronunciation_assessment' : ($transcript === '' ? 'practice_only' : 'speech_transcript');
                $practiceOnly = $practiceOnly || $method === 'practice_only';
                $pronunciationVerified = $pronunciationVerified && $method === 'pronunciation_assessment';
                return [
                    'target' => $target,
                    'transcript' => $transcript,
                    'score' => $assessment['pronunciation_score'] ?? ($transcript === '' ? 0 : $this->textSimilarity($target, $transcript)),
                    'method' => $method,
                    'assessment' => $assessment,
                ];
            });
            return [
                (int) round($cleanItems->avg('score')),
                $practiceOnly ? 'practice_only' : 'auto_checked',
                ['items' => $cleanItems->all()],
                [
                    'method' => $practiceOnly ? 'speaking_practice' : ($pronunciationVerified ? 'pronunciation_assessment' : 'speech_transcript_match'),
                    'pronunciation_verified' => ! $practiceOnly && $pronunciationVerified,
                    'item_assessments' => $cleanItems->pluck('assessment')->all(),
                ],
            ];
        }

        if ($type === 'handwriting') {
            $samples = collect($answers['samples'] ?? [])->values();
            $practiceMode = in_array(($content['practice_mode'] ?? 'trace'), ['trace', 'copy'], true)
                ? (string) ($content['practice_mode'] ?? 'trace')
                : 'trace';
            $expectedPrompts = collect($content['prompts'] ?? (isset($content['word']) ? [$content['word']] : (isset($content['letter']) ? [$content['letter']] : ['Write here'])))
                ->map(fn ($prompt) => (string) $prompt)
                ->values();
            if ($samples->count() !== $expectedPrompts->count()) {
                throw ValidationException::withMessages(['answers.samples' => 'A handwriting sample is required for every prompt.']);
            }
            $saved = $samples->map(function ($sample, $index) use ($validated, $expectedPrompts, $practiceMode) {
                $sample = (array) $sample;
                $prompt = (string) ($sample['prompt'] ?? '');
                if ($prompt !== $expectedPrompts[$index]) {
                    throw ValidationException::withMessages(['answers.samples' => 'A handwriting prompt does not match the exercise.']);
                }
                $traceSummary = $this->analyzeHandwritingTrace((array) ($sample['strokes'] ?? []));
                if ($practiceMode === 'copy') {
                    $this->assertCopyingTraceHasEnoughInk($traceSummary, $prompt);
                }
                $submittedFeedback = (array) ($sample['trace_feedback'] ?? []);
                $feedbackStatus = (string) ($submittedFeedback['status'] ?? 'needs_help');
                if (! in_array($feedbackStatus, ['good', 'needs_help'], true)) $feedbackStatus = 'needs_help';
                $traceFeedback = [
                    'score' => max(0, min(100, (int) ($submittedFeedback['score'] ?? 0))),
                    'precision' => max(0, min(100, (int) ($submittedFeedback['precision'] ?? 0))),
                    'coverage' => max(0, min(100, (int) ($submittedFeedback['coverage'] ?? 0))),
                    'status' => $feedbackStatus,
                    'client_feedback_only' => true,
                ];
                $path = $this->storeHandwritingImage((string) ($sample['image_data_url'] ?? ''), (int) $validated['child_id']);
                return [
                    'prompt' => $prompt,
                    'stroke_count' => $traceSummary['stroke_count'],
                    'point_count' => $traceSummary['point_count'],
                    'drawn_distance' => (int) round($traceSummary['normalized_distance'] * 1000),
                    'trace_summary' => $traceSummary,
                    'trace_feedback' => $traceFeedback,
                    'image_path' => $path,
                ];
            });
            return [null, 'pending_review', ['samples' => $saved->all()], [
                'method' => $practiceMode === 'copy'
                    ? 'handwriting_copy_capture_and_parent_review'
                    : 'handwriting_trace_capture_and_parent_review',
                'trace_version' => 2,
                'input_structure_verified' => true,
                'client_feedback_only' => true,
                'practice_mode' => $practiceMode,
                'sample_count' => $saved->count(),
            ]];
        }

        if ($type === 'written_response') {
            $text = trim((string) ($answers['text'] ?? ''));
            $normalized = $this->normalizeText($text);
            $words = array_values(array_filter(explode(' ', $normalized)));
            $wordCount = count($words);
            $minimum = max(1, (int) ($content['min_words'] ?? 12));
            if ($wordCount < $minimum) {
                throw ValidationException::withMessages(['answers.text' => "The written response must contain at least {$minimum} words."]);
            }

            $requiredSentences = max(0, (int) ($content['required_sentences'] ?? 0));
            preg_match_all('/[^.!?]+[.!?]+/u', $text, $sentenceMatches);
            $sentenceCount = count($sentenceMatches[0] ?? []);
            if ($requiredSentences > 0 && $sentenceCount < $requiredSentences) {
                throw ValidationException::withMessages([
                    'answers.text' => "Write {$requiredSentences} complete sentences and finish each one with punctuation.",
                ]);
            }

            $requiredTerms = collect($content['required_any_terms'] ?? [])
                ->map(fn ($term) => $this->normalizeText((string) $term))
                ->filter()
                ->values();
            if ($requiredTerms->isNotEmpty() && ! collect($words)->contains(fn ($word) => $requiredTerms->contains($word))) {
                throw ValidationException::withMessages([
                    'answers.text' => 'Use at least one family word from the lesson.',
                ]);
            }

            $acceptedWords = collect($content['accepted_words'] ?? [])
                ->map(fn ($word) => $this->normalizeText((string) $word))
                ->filter()
                ->flip();
            $minimumRecognizedRatio = max(0, min(1, (float) ($content['min_recognized_ratio'] ?? 0)));
            $maximumUnrecognized = max(0, (int) ($content['max_unrecognized_words'] ?? PHP_INT_MAX));
            $unrecognized = $acceptedWords->isEmpty()
                ? []
                : collect($words)->reject(fn ($word) => $acceptedWords->has($word))->values()->all();
            $recognizedCount = $wordCount - count($unrecognized);
            $recognizedRatio = $wordCount > 0 ? $recognizedCount / $wordCount : 0;
            if ($acceptedWords->isNotEmpty()
                && ($recognizedRatio < $minimumRecognizedRatio || count($unrecognized) > $maximumUnrecognized)) {
                throw ValidationException::withMessages([
                    'answers.text' => 'Use clear English words from the lesson. Check invented or mixed-language words.',
                ]);
            }

            return [null, 'pending_review', ['text' => $text], [
                'method' => 'written_response_precheck_and_parent_review',
                'word_count' => $wordCount,
                'sentence_count' => $sentenceCount,
                'recognized_word_count' => $recognizedCount,
                'review_rubric' => ['relevance', 'sentence_structure', 'spelling', 'punctuation'],
            ]];
        }

        return [
            $validated['score'] ?? 0,
            'client_checked',
            $answers ?: null,
            $evidence ?: null,
        ];
    }

    private function storeHandwritingImage(string $dataUrl, int $childId): string
    {
        if (! preg_match('#^data:image/(webp|png|jpeg);base64,([A-Za-z0-9+/=]+)$#', $dataUrl, $matches)) {
            throw ValidationException::withMessages(['answers.samples' => 'The handwriting image is invalid.']);
        }
        $binary = base64_decode($matches[2], true);
        if ($binary === false || strlen($binary) > 1_500_000) {
            throw ValidationException::withMessages(['answers.samples' => 'The handwriting image is too large.']);
        }
        $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
        $path = "handwriting-attempts/{$childId}/".Str::uuid().".{$extension}";
        Storage::disk('local')->put($path, $binary);
        return $path;
    }

    private function analyzeHandwritingTrace(array $strokes): array
    {
        if ($strokes === [] || count($strokes) > 64) {
            throw ValidationException::withMessages(['answers.samples' => 'Each handwriting sample must contain a valid pen trace.']);
        }

        $strokeCount = 0;
        $pointCount = 0;
        $distance = 0.0;
        $minimumX = 1.0;
        $minimumY = 1.0;
        $maximumX = 0.0;
        $maximumY = 0.0;
        $strokeIntervals = [];
        foreach ($strokes as $stroke) {
            if (! is_array($stroke) || $stroke === [] || count($stroke) > 800) {
                throw ValidationException::withMessages(['answers.samples' => 'A handwriting stroke is invalid or too large.']);
            }
            $strokeCount++;
            $previous = null;
            $strokeMinimumX = 1.0;
            $strokeMaximumX = 0.0;
            $strokeMinimumY = 1.0;
            $strokeMaximumY = 0.0;
            $strokePointCount = 0;
            foreach ($stroke as $point) {
                if (! is_array($point)
                    || ! is_numeric($point['x'] ?? null)
                    || ! is_numeric($point['y'] ?? null)
                    || ! is_numeric($point['t'] ?? null)) {
                    throw ValidationException::withMessages(['answers.samples' => 'A handwriting point is invalid.']);
                }
                $x = (float) $point['x'];
                $y = (float) $point['y'];
                $time = (int) $point['t'];
                if (! is_finite($x) || ! is_finite($y) || $x < 0 || $x > 1 || $y < 0 || $y > 1 || $time < 0 || $time > 600_000) {
                    throw ValidationException::withMessages(['answers.samples' => 'A handwriting point is outside the drawing area.']);
                }
                $pointCount++;
                $strokePointCount++;
                if ($pointCount > 8_000) {
                    throw ValidationException::withMessages(['answers.samples' => 'The handwriting trace contains too many points.']);
                }
                $minimumX = min($minimumX, $x);
                $minimumY = min($minimumY, $y);
                $maximumX = max($maximumX, $x);
                $maximumY = max($maximumY, $y);
                $strokeMinimumX = min($strokeMinimumX, $x);
                $strokeMaximumX = max($strokeMaximumX, $x);
                $strokeMinimumY = min($strokeMinimumY, $y);
                $strokeMaximumY = max($strokeMaximumY, $y);
                if ($previous !== null) $distance += hypot($x - $previous[0], $y - $previous[1]);
                $previous = [$x, $y];
            }
            if ($strokePointCount >= 2) {
                $strokeIntervals[] = [
                    'start' => $strokeMinimumX,
                    'end' => $strokeMaximumX,
                    'top' => $strokeMinimumY,
                    'bottom' => $strokeMaximumY,
                    'points' => $strokePointCount,
                ];
            }
        }

        $spanX = $maximumX - $minimumX;
        $spanY = $maximumY - $minimumY;
        if ($pointCount < 6 || $distance < 0.08 || max($spanX, $spanY) < 0.05) {
            throw ValidationException::withMessages(['answers.samples' => 'Each handwriting sample must contain a complete pen trace.']);
        }

        return [
            'version' => 2,
            'stroke_count' => $strokeCount,
            'point_count' => $pointCount,
            'normalized_distance' => round($distance, 4),
            'word_group_count' => $this->countHorizontalInkGroups($strokeIntervals),
            'bounds' => [
                'x' => round($minimumX, 4),
                'y' => round($minimumY, 4),
                'width' => round($spanX, 4),
                'height' => round($spanY, 4),
            ],
        ];
    }

    private function countHorizontalInkGroups(array $intervals): int
    {
        $intervals = array_values(array_filter($intervals, fn (array $interval) => $interval['end'] - $interval['start'] <= 0.45));
        usort($intervals, fn (array $left, array $right) => $left['start'] <=> $right['start']);
        $groups = [];
        foreach ($intervals as $interval) {
            $lastIndex = count($groups) - 1;
            if ($lastIndex < 0 || $interval['start'] - $groups[$lastIndex]['end'] > 0.035) {
                $groups[] = $interval;
                continue;
            }
            $groups[$lastIndex]['end'] = max($groups[$lastIndex]['end'], $interval['end']);
            $groups[$lastIndex]['top'] = min($groups[$lastIndex]['top'], $interval['top']);
            $groups[$lastIndex]['bottom'] = max($groups[$lastIndex]['bottom'], $interval['bottom']);
            $groups[$lastIndex]['points'] += $interval['points'];
        }

        return count(array_filter($groups, fn (array $group) => $group['end'] - $group['start'] >= 0.012 || $group['points'] >= 6));
    }

    private function assertCopyingTraceHasEnoughInk(array $traceSummary, string $prompt): void
    {
        $characterCount = mb_strlen((string) preg_replace('/\s+/u', '', $prompt));
        $minimumPoints = max(24, min(100, $characterCount * 3));
        $minimumDistance = max(0.8, min(6.0, $characterCount * 0.14));
        $minimumWidth = max(0.58, min(0.78, 0.44 + $characterCount * 0.015));
        $expectedWords = count(preg_split('/\s+/u', trim($prompt), -1, PREG_SPLIT_NO_EMPTY));
        $bounds = (array) ($traceSummary['bounds'] ?? []);
        $width = (float) ($bounds['width'] ?? 0);
        $height = (float) ($bounds['height'] ?? 0);

        if ((int) ($traceSummary['word_group_count'] ?? 0) < $expectedWords
            || (int) ($traceSummary['point_count'] ?? 0) < $minimumPoints
            || (float) ($traceSummary['normalized_distance'] ?? 0) < $minimumDistance
            || $width < $minimumWidth
            || $height < 0.03
            || $height > 0.9) {
            throw ValidationException::withMessages([
                'answers.samples' => 'Each copied sentence must contain enough handwriting across the writing lines for parent review.',
            ]);
        }
    }

    private function storeSpeakingAudio(string $dataUrl, int $childId): string
    {
        $child = Child::query()->with('household')->findOrFail($childId);
        $household = $child->household;
        if (! $household?->speaking_audio_consent_at) {
            throw ValidationException::withMessages(['answers.items' => 'Parent consent is required before storing speaking audio.']);
        }
        $expired = PronunciationAttempt::query()
            ->whereHas('child', fn ($query) => $query->where('household_id', $household->id))
            ->whereNotNull('recorded_audio_path')
            ->where('attempted_at', '<', now()->subDays(max(7, (int) $household->speaking_audio_retention_days)))
            ->get();
        foreach ($expired as $oldAttempt) {
            Storage::disk('local')->delete($oldAttempt->recorded_audio_path);
            $oldAttempt->update(['recorded_audio_path' => null]);
        }
        if (! preg_match('#^data:audio/(wav|webm|ogg|mp4|mpeg)(?:;codecs=[^;,]+)?;base64,([A-Za-z0-9+/=]+)$#', $dataUrl, $matches)) {
            throw ValidationException::withMessages(['answers.items' => 'The speaking audio format is invalid.']);
        }
        $binary = base64_decode($matches[2], true);
        if ($binary === false || strlen($binary) > 2_500_000) {
            throw ValidationException::withMessages(['answers.items' => 'The speaking audio is too large.']);
        }
        $extension = $matches[1] === 'mpeg' ? 'mp3' : $matches[1];
        $path = "speaking-attempts/{$childId}/".Str::uuid().".{$extension}";
        Storage::disk('local')->put($path, $binary);
        return $path;
    }

    private function resolveSpeakingAssessments(array $validated, Exercise $exercise, array $content): array
    {
        $items = $validated['answers']['items'] ?? [];
        foreach ($items as $index => &$item) {
            $token = trim((string) ($item['assessment_token'] ?? ''));
            if ($token === '') continue;
            $cached = Cache::pull("speaking-assessment:{$token}");
            $target = trim((string) ($content['items'][$index]['text'] ?? ''));
            if (! is_array($cached)
                || (int) ($cached['child_id'] ?? 0) !== (int) $validated['child_id']
                || (int) ($cached['exercise_id'] ?? 0) !== (int) $exercise->id
                || (int) ($cached['item_index'] ?? -1) !== $index
                || ! hash_equals((string) ($cached['target_hash'] ?? ''), hash('sha256', $target))) {
                continue;
            }
            $item['_server_assessment'] = $cached['assessment'] ?? null;
        }
        unset($item);
        $validated['answers']['items'] = $items;
        return $validated;
    }

    private function sanitizeSpeakingAssessment(array $assessment): array
    {
        return [
            'method' => 'azure_pronunciation_assessment',
            'transcript' => trim((string) ($assessment['transcript'] ?? '')),
            'pronunciation_score' => max(0, min(100, (int) ($assessment['pronunciation_score'] ?? 0))),
            'accuracy_score' => max(0, min(100, (int) ($assessment['accuracy_score'] ?? 0))),
            'fluency_score' => isset($assessment['fluency_score']) ? max(0, min(100, (int) $assessment['fluency_score'])) : null,
            'completeness_score' => isset($assessment['completeness_score']) ? max(0, min(100, (int) $assessment['completeness_score'])) : null,
            'words' => collect($assessment['words'] ?? [])->take(30)->map(fn ($word) => [
                'word' => Str::limit(trim((string) ($word['word'] ?? '')), 60, ''),
                'accuracy' => max(0, min(100, (int) ($word['accuracy'] ?? 0))),
                'error_type' => Str::limit((string) ($word['error_type'] ?? 'None'), 40, ''),
            ])->filter(fn ($word) => $word['word'] !== '')->values()->all(),
        ];
    }

    /**
     * Texte à trous : la réponse doit être exacte (majuscules, accents et
     * ponctuation comptent, comme dans le système anglophone : « Goodbye! »).
     * Seuls les espaces superflus et la forme de l'apostrophe sont tolérés.
     */
    private function exactAnswer(string $value): string
    {
        if (class_exists(\Normalizer::class)) {
            $value = \Normalizer::normalize($value, \Normalizer::FORM_C) ?: $value;
        }
        $value = str_replace(["\u{2019}", "\u{2018}", "\u{02BC}", '`'], "'", $value);

        return trim((string) preg_replace('/\s+/u', ' ', $value));
    }

    private function normalizeText(string $value): string
    {
        $value = Str::ascii(Str::lower($value));
        $value = preg_replace("/[^a-z0-9'\\s]/", ' ', $value);
        return trim((string) preg_replace('/\s+/', ' ', $value));
    }

    private function textSimilarity(string $expected, string $actual): int
    {
        $left = $this->normalizeText($expected);
        $right = $this->normalizeText($actual);
        if ($left === $right) return 100;
        if ($left !== '' && $right !== '' && (str_contains($right, $left) || str_contains($left, $right))) return 90;
        return $this->wordAccuracy($left, $right);
    }

    private function wordAccuracy(string $expected, string $actual): int
    {
        $left = array_values(array_filter(explode(' ', $this->normalizeText($expected))));
        $right = array_values(array_filter(explode(' ', $this->normalizeText($actual))));
        if ($left === []) return 0;
        $rows = array_fill(0, count($left) + 1, array_fill(0, count($right) + 1, 0));
        for ($i = 0; $i <= count($left); $i++) $rows[$i][0] = $i;
        for ($j = 0; $j <= count($right); $j++) $rows[0][$j] = $j;
        for ($i = 1; $i <= count($left); $i++) {
            for ($j = 1; $j <= count($right); $j++) {
                $rows[$i][$j] = min(
                    $rows[$i - 1][$j] + 1,
                    $rows[$i][$j - 1] + 1,
                    $rows[$i - 1][$j - 1] + ($left[$i - 1] === $right[$j - 1] ? 0 : 1),
                );
            }
        }
        $distance = $rows[count($left)][count($right)];
        return max(0, (int) round((1 - $distance / max(count($left), count($right))) * 100));
    }

    private function dictationScore(string $expected, string $actual): array
    {
        $wordScore = $this->strictWordAccuracy($expected, $actual);
        $spellingScore = $this->unicodeCharacterAccuracy(
            $this->normalizeDictationText($expected),
            $this->normalizeDictationText($actual),
        );
        $capitalizationScore = $this->initialCapitalizationMatches($expected, $actual) ? 100 : 0;
        $punctuationScore = $this->punctuationSequence($expected) === $this->punctuationSequence($actual) ? 100 : 0;

        return [
            'score' => (int) round($wordScore * 0.60 + $spellingScore * 0.25 + $capitalizationScore * 0.05 + $punctuationScore * 0.10),
            'words' => $wordScore,
            'spelling' => $spellingScore,
            'capitalization' => $capitalizationScore,
            'punctuation' => $punctuationScore,
        ];
    }

    private function strictWordAccuracy(string $expected, string $actual): int
    {
        $words = fn (string $value) => array_values(array_filter(preg_split('/\s+/u', $this->normalizeDictationText($value), -1, PREG_SPLIT_NO_EMPTY)));
        $left = $words($expected);
        $right = $words($actual);
        if ($left === []) return 0;
        return $this->sequenceAccuracy($left, $right);
    }

    private function unicodeCharacterAccuracy(string $expected, string $actual): int
    {
        $left = preg_split('//u', str_replace(' ', '', $expected), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $right = preg_split('//u', str_replace(' ', '', $actual), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if ($left === []) return 0;
        return $this->sequenceAccuracy($left, $right);
    }

    private function sequenceAccuracy(array $left, array $right): int
    {
        $rows = array_fill(0, count($left) + 1, array_fill(0, count($right) + 1, 0));
        for ($i = 0; $i <= count($left); $i++) $rows[$i][0] = $i;
        for ($j = 0; $j <= count($right); $j++) $rows[0][$j] = $j;
        for ($i = 1; $i <= count($left); $i++) {
            for ($j = 1; $j <= count($right); $j++) {
                $rows[$i][$j] = min(
                    $rows[$i - 1][$j] + 1,
                    $rows[$i][$j - 1] + 1,
                    $rows[$i - 1][$j - 1] + ($left[$i - 1] === $right[$j - 1] ? 0 : 1),
                );
            }
        }
        return max(0, (int) round((1 - $rows[count($left)][count($right)] / max(count($left), count($right))) * 100));
    }

    private function normalizeDictationText(string $value): string
    {
        $value = str_replace(['’', '‘'], "'", $value);
        $value = Str::lower($value);
        $value = preg_replace("/[^\p{L}\p{N}'\s]/u", ' ', $value);
        return trim((string) preg_replace('/\s+/u', ' ', $value));
    }

    private function initialCapitalizationMatches(string $expected, string $actual): bool
    {
        preg_match('/^\s*(\p{L})/u', $expected, $expectedMatch);
        preg_match('/^\s*(\p{L})/u', $actual, $actualMatch);
        if (! isset($expectedMatch[1], $actualMatch[1])) return false;
        return ($expectedMatch[1] === Str::upper($expectedMatch[1])) === ($actualMatch[1] === Str::upper($actualMatch[1]));
    }

    private function punctuationSequence(string $value): array
    {
        preg_match_all('/[.,!?;:]/u', $value, $matches);
        return $matches[0] ?? [];
    }

    private function syncLearningPackProgress(int $childId, int $exerciseId): void
    {
        $schoolYearId = SchoolYear::where('is_current', true)->value('id');
        $assignments = ChildLearningPack::query()
            ->where('child_id', $childId)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->whereHas('learningPack.exercises', fn ($query) => $query->whereKey($exerciseId))
            ->with(['learningPack.exercises' => fn ($query) => $query->where('exercises.is_active', true)])
            ->get();

        foreach ($assignments as $assignment) {
            $requiredIds = $assignment->learningPack->exercises
                ->filter(fn ($exercise) => (bool) $exercise->pivot->is_required)
                ->pluck('id');
            if ($requiredIds->isEmpty()) continue;

            $completedCount = ExerciseAttempt::query()
                ->where('child_id', $childId)
                ->whereIn('exercise_id', $requiredIds)
                ->when($schoolYearId, fn ($query) => $query->where('school_year_id', $schoolYearId))
                ->where('attempted_at', '>=', $assignment->created_at)
                ->distinct('exercise_id')
                ->count('exercise_id');
            $isCompleted = $completedCount >= $requiredIds->count();

            $assignment->update([
                'status' => $isCompleted ? 'completed' : 'in_progress',
                'started_at' => $assignment->started_at ?? now(),
                'completed_at' => $isCompleted ? now() : null,
            ]);
        }
    }
}
