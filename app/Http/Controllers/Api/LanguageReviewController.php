<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExerciseAttempt;
use App\Models\Household;
use App\Models\PronunciationAttempt;
use App\Support\FamilyContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Speech\PronunciationAssessmentService;

class LanguageReviewController extends Controller
{
    public function settings(Request $request, PronunciationAssessmentService $speech)
    {
        $household = $this->household($request, false);
        return response()->json([
            'speaking_audio_enabled' => (bool) $household->speaking_audio_consent_at,
            'speaking_analysis_enabled' => (bool) $household->speaking_analysis_consent_at && $speech->available(),
            'speaking_analysis_consented' => (bool) $household->speaking_analysis_consent_at,
            'speaking_analysis_available' => $speech->available(),
            'retention_days' => (int) ($household->speaking_audio_retention_days ?: 30),
        ]);
    }

    public function updateSettings(Request $request, PronunciationAssessmentService $speech)
    {
        $household = $this->household($request, true);
        $data = $request->validate([
            'speaking_audio_enabled' => ['required', 'boolean'],
            'speaking_analysis_enabled' => ['required', 'boolean'],
            'retention_days' => ['required', 'integer', 'min:7', 'max:90'],
            'delete_existing_audio' => ['sometimes', 'boolean'],
        ]);
        if ($data['speaking_analysis_enabled'] && ! $speech->available()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'speaking_analysis_enabled' => 'Automatic pronunciation analysis is not configured.',
            ]);
        }

        $household->update([
            'speaking_audio_consent_at' => $data['speaking_audio_enabled'] ? ($household->speaking_audio_consent_at ?? now()) : null,
            'speaking_analysis_consent_at' => $data['speaking_analysis_enabled'] ? ($household->speaking_analysis_consent_at ?? now()) : null,
            'speaking_audio_retention_days' => $data['retention_days'],
        ]);

        if (! $data['speaking_audio_enabled'] && ($data['delete_existing_audio'] ?? false)) {
            $this->deleteHouseholdAudio($household->id);
        } else {
            $this->deleteExpiredAudio($household);
        }

        return $this->settings($request, $speech);
    }

    public function index(Request $request, PronunciationAssessmentService $speech)
    {
        $household = $this->household($request, true);
        $this->deleteExpiredAudio($household);

        $pendingWriting = ExerciseAttempt::query()
            ->with(['child:id,first_name,last_name,household_id', 'exercise:id,title,content'])
            ->whereHas('child', fn ($query) => $query->where('household_id', $household->id))
            ->where('verification_status', 'pending_review')
            ->latest('attempted_at')
            ->limit(100)
            ->get()
            ->map(function (ExerciseAttempt $attempt) {
                $content = $attempt->exercise?->content ?? [];
                $type = (string) ($content['type'] ?? '');
                if (! in_array($type, ['handwriting', 'written_response'], true)) return null;
                $answers = $attempt->answers ?? [];
                return [
                    'id' => $attempt->id,
                    'child_name' => trim($attempt->child->first_name.' '.$attempt->child->last_name),
                    'exercise_title' => $attempt->exercise?->title ?? 'Writing activity',
                    'type' => $type,
                    'text' => $answers['text'] ?? null,
                    'samples' => collect($answers['samples'] ?? [])->values()->map(fn ($sample, $index) => [
                        'prompt' => $sample['prompt'] ?? '',
                        'media_url' => "/api/parent/language-reviews/{$attempt->id}/writing/{$index}",
                        'trace_feedback' => $sample['trace_feedback'] ?? null,
                        'trace_summary' => $sample['trace_summary'] ?? null,
                    ])->all(),
                    'attempted_at' => $attempt->attempted_at,
                ];
            })
            ->filter()
            ->values();

        $speaking = PronunciationAttempt::query()
            ->with(['child:id,first_name,last_name,household_id', 'exercise:id,title'])
            ->whereHas('child', fn ($query) => $query->where('household_id', $household->id))
            ->whereNotNull('recorded_audio_path')
            ->latest('attempted_at')
            ->limit(100)
            ->get()
            ->map(function (PronunciationAttempt $attempt) {
                $automatic = $attempt->feedback_json['automatic_assessment'] ?? null;
                return [
                'id' => $attempt->id,
                'child_name' => trim($attempt->child->first_name.' '.$attempt->child->last_name),
                'exercise_title' => $attempt->exercise?->title ?? 'Speaking activity',
                'target_text' => $attempt->target_text,
                'transcript' => $attempt->feedback_json['transcript'] ?? '',
                'transcript_score' => $automatic || $attempt->overall_score === null ? null : (int) round($attempt->overall_score),
                'automatic_assessment' => $automatic,
                'pronunciation_score' => $attempt->pronunciation_score === null ? null : (int) round($attempt->pronunciation_score),
                'parent_feedback' => $attempt->feedback_json['parent_feedback'] ?? null,
                'audio_url' => "/api/parent/pronunciation-reviews/{$attempt->id}/audio",
                'attempted_at' => $attempt->attempted_at,
                ];
            });

        return response()->json([
            'settings' => [
                'speaking_audio_enabled' => (bool) $household->speaking_audio_consent_at,
                'speaking_analysis_enabled' => (bool) $household->speaking_analysis_consent_at && $speech->available(),
                'speaking_analysis_consented' => (bool) $household->speaking_analysis_consent_at,
                'speaking_analysis_available' => $speech->available(),
                'retention_days' => (int) ($household->speaking_audio_retention_days ?: 30),
            ],
            'pending_writing' => $pendingWriting,
            'speaking' => $speaking,
        ]);
    }

    public function writingMedia(Request $request, ExerciseAttempt $attempt, int $sampleIndex)
    {
        $this->ownedAttempt($request, $attempt);
        $sample = collect($attempt->answers['samples'] ?? [])->values()->get($sampleIndex);
        abort_unless(is_array($sample) && isset($sample['image_path']), 404);
        return $this->privateFile((string) $sample['image_path']);
    }

    public function reviewWriting(Request $request, ExerciseAttempt $attempt)
    {
        $this->ownedAttempt($request, $attempt);
        $content = $attempt->exercise?->content ?? [];
        $type = (string) ($content['type'] ?? '');
        abort_unless(in_array($type, ['handwriting', 'written_response'], true), 404);
        abort_unless($attempt->verification_status === 'pending_review', 409, 'This production has already been reviewed.');
        $data = $request->validate([
            'score' => ['required', 'integer', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:1000'],
            'rubric' => ['nullable', 'array'],
            'rubric.*' => ['integer', 'min:0', 'max:4'],
        ]);
        $evidence = $attempt->evidence ?? [];
        $evidence['parent_review'] = [
            'reviewer_id' => $request->user()->id,
            'reviewed_at' => now()->toIso8601String(),
            'feedback' => trim((string) ($data['feedback'] ?? '')),
            'rubric' => $data['rubric'] ?? [],
        ];
        $attempt->update([
            'score' => $data['score'],
            'verification_status' => 'parent_verified',
            'evidence' => $evidence,
        ]);
        return response()->json(['success' => true, 'score' => $attempt->score, 'verification_status' => $attempt->verification_status]);
    }

    public function pronunciationAudio(Request $request, PronunciationAttempt $attempt)
    {
        $this->ownedPronunciation($request, $attempt);
        abort_unless($attempt->recorded_audio_path, 404);
        return $this->privateFile($attempt->recorded_audio_path);
    }

    public function reviewPronunciation(Request $request, PronunciationAttempt $attempt)
    {
        $this->ownedPronunciation($request, $attempt);
        abort_unless($attempt->recorded_audio_path, 409, 'This recording is no longer available.');
        $data = $request->validate([
            'score' => ['required', 'integer', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ]);
        $feedback = $attempt->feedback_json ?? [];
        $feedback['parent_feedback'] = trim((string) ($data['feedback'] ?? ''));
        $feedback['parent_reviewer_id'] = $request->user()->id;
        $feedback['parent_reviewed_at'] = now()->toIso8601String();
        $feedback['pronunciation_verified'] = true;
        $attempt->update(['pronunciation_score' => $data['score'], 'feedback_json' => $feedback]);
        $this->syncPronunciationScore($attempt->exercise_attempt_id);
        return response()->json(['success' => true, 'pronunciation_score' => (int) $attempt->pronunciation_score]);
    }

    public function deletePronunciationAudio(Request $request, PronunciationAttempt $attempt)
    {
        $this->ownedPronunciation($request, $attempt);
        if ($attempt->recorded_audio_path) Storage::disk('local')->delete($attempt->recorded_audio_path);
        $feedback = $attempt->feedback_json ?? [];
        $feedback['audio_deleted_at'] = now()->toIso8601String();
        $feedback['audio_deleted_by'] = $request->user()->id;
        $feedback['pronunciation_verified'] = false;
        $attempt->update(['recorded_audio_path' => null, 'pronunciation_score' => null, 'feedback_json' => $feedback]);
        if ($attempt->exercise_attempt_id) {
            $status = PronunciationAttempt::query()
                ->where('exercise_attempt_id', $attempt->exercise_attempt_id)
                ->whereIn('feedback_json->assessment_method', ['practice_only', 'speech_transcript'])
                ->exists() ? 'practice_only' : 'auto_checked';
            ExerciseAttempt::query()->whereKey($attempt->exercise_attempt_id)->update(['verification_status' => $status]);
        }
        return response()->json(['success' => true]);
    }

    private function syncPronunciationScore(?int $exerciseAttemptId): void
    {
        if (! $exerciseAttemptId) return;
        $items = PronunciationAttempt::query()->where('exercise_attempt_id', $exerciseAttemptId)->get();
        if ($items->isEmpty() || $items->contains(fn ($item) => $item->pronunciation_score === null)) return;
        $attempt = ExerciseAttempt::query()->find($exerciseAttemptId);
        if (! $attempt) return;
        $evidence = $attempt->evidence ?? [];
        $evidence['parent_pronunciation_review'] = ['completed_at' => now()->toIso8601String(), 'item_count' => $items->count()];
        $attempt->update([
            'score' => (int) round($items->avg('pronunciation_score')),
            'verification_status' => 'parent_verified',
            'evidence' => $evidence,
        ]);
    }

    private function privateFile(string $path)
    {
        abort_unless(Storage::disk('local')->exists($path), 404);
        return response()->file(Storage::disk('local')->path($path), [
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function household(Request $request, bool $requireAccount): Household
    {
        $householdId = (int) (FamilyContext::householdId($request) ?? 0);
        abort_unless($householdId > 0, 401);
        if ($requireAccount) {
            abort_unless($request->user()?->households()->whereKey($householdId)->wherePivot('is_active', true)->exists(), 403);
        }
        return Household::query()->findOrFail($householdId);
    }

    private function ownedAttempt(Request $request, ExerciseAttempt $attempt): void
    {
        $household = $this->household($request, true);
        abort_unless($attempt->child()->where('household_id', $household->id)->exists(), 404);
    }

    private function ownedPronunciation(Request $request, PronunciationAttempt $attempt): void
    {
        $household = $this->household($request, true);
        abort_unless($attempt->child()->where('household_id', $household->id)->exists(), 404);
    }

    private function deleteExpiredAudio(Household $household): void
    {
        $attempts = PronunciationAttempt::query()
            ->whereHas('child', fn ($query) => $query->where('household_id', $household->id))
            ->whereNotNull('recorded_audio_path')
            ->where('attempted_at', '<', now()->subDays(max(7, (int) $household->speaking_audio_retention_days)))
            ->get();
        $this->deleteAudioAttempts($attempts);
    }

    private function deleteHouseholdAudio(int $householdId): void
    {
        $attempts = PronunciationAttempt::query()
            ->whereHas('child', fn ($query) => $query->where('household_id', $householdId))
            ->whereNotNull('recorded_audio_path')
            ->get();
        $this->deleteAudioAttempts($attempts);
    }

    private function deleteAudioAttempts($attempts): void
    {
        DB::transaction(function () use ($attempts) {
            foreach ($attempts as $attempt) {
                Storage::disk('local')->delete($attempt->recorded_audio_path);
                $attempt->update(['recorded_audio_path' => null, 'pronunciation_score' => null]);
            }
        });
    }
}
