<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\ExerciseController;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Illuminate\Validation\ValidationException;
use App\Services\Speech\PronunciationAssessmentService;

class LanguageAttemptVerificationTest extends TestCase
{
    private function verify(string $type, array $content, array $validated): array
    {
        $method = new ReflectionMethod(ExerciseController::class, 'verifiedAttemptData');
        $method->setAccessible(true);
        return $method->invoke(new ExerciseController(), $type, $content, $validated);
    }

    private function analyzeHandwriting(array $strokes): array
    {
        $method = new ReflectionMethod(ExerciseController::class, 'analyzeHandwritingTrace');
        $method->setAccessible(true);
        return $method->invoke(new ExerciseController(), $strokes);
    }

    public function test_mcq_score_is_recomputed_from_the_server_answer_key(): void
    {
        [$score, $status, $answers, $evidence] = $this->verify('multiple_choice', [
            'type' => 'multiple_choice',
            'questions' => [[
                'question' => 'Where did Ambe go?',
                'options' => ['School', 'Market'],
                'answer' => 1,
            ]],
        ], [
            'score' => 100,
            'answers' => ['items' => [['question_index' => 0, 'selected_index' => 0]]],
        ]);

        $this->assertSame(0, $score);
        $this->assertSame('auto_checked', $status);
        $this->assertFalse($answers['items'][0]['correct']);
        $this->assertSame('server_answer_key', $evidence['method']);
    }

    public function test_clock_score_ignores_the_score_claimed_by_the_client(): void
    {
        [$score, $status, $answers] = $this->verify('clock_reading', [
            'type' => 'clock_reading',
            'options' => ['3:00', '6:00'],
            'answer' => 1,
        ], [
            'score' => 100,
            'answers' => ['selected_index' => 0],
        ]);

        $this->assertSame(0, $score);
        $this->assertSame('auto_checked', $status);
        $this->assertFalse($answers['correct']);

        [$score, , $answers] = $this->verify('clock_reading', [
            'type' => 'clock_reading', 'options' => ['3:00', '6:00'], 'answer' => 1,
        ], ['score' => 0, 'answers' => ['selected_index' => 1]]);
        $this->assertSame(100, $score);
        $this->assertTrue($answers['correct']);
    }

    public function test_geometry_score_is_recomputed_from_the_selected_option(): void
    {
        [$score, $status, $answers] = $this->verify('geometry', [
            'type' => 'geometry',
            'options' => ['Triangle', 'Square'],
            'answer' => 0,
        ], [
            'score' => 100,
            'answers' => ['selected_index' => 1],
        ]);

        $this->assertSame(0, $score);
        $this->assertSame('auto_checked', $status);
        $this->assertFalse($answers['correct']);

        [$score, , $answers] = $this->verify('geometry', [
            'type' => 'geometry', 'options' => ['Triangle', 'Square'], 'answer' => 0,
        ], ['score' => 0, 'answers' => ['selected_index' => 0]]);
        $this->assertSame(100, $score);
        $this->assertTrue($answers['correct']);
    }

    public function test_number_line_score_is_recomputed_from_the_selected_value(): void
    {
        [$score, $status, $answers] = $this->verify('number_line', [
            'type' => 'number_line',
            'start' => 3,
            'jumps' => [4],
            'options' => [6, 7, 8],
            'answer' => 7,
        ], [
            'score' => 100,
            'answers' => ['selected_value' => 8],
        ]);

        $this->assertSame(0, $score);
        $this->assertSame('auto_checked', $status);
        $this->assertFalse($answers['correct']);

        [$score, , $answers] = $this->verify('number_line', [
            'type' => 'number_line', 'start' => 3, 'jumps' => [4], 'options' => [6, 7, 8], 'answer' => 7,
        ], ['score' => 0, 'answers' => ['selected_value' => 7]]);
        $this->assertSame(100, $score);
        $this->assertTrue($answers['correct']);
    }

    public function test_venn_score_is_recomputed_from_every_placement(): void
    {
        [$score, $status, $answers] = $this->verify('venn_diagram', [
            'type' => 'venn_diagram',
            'setA' => ['2', '4'],
            'setB' => ['3', '4'],
            'items' => ['2', '3', '4'],
            'intersection' => ['4'],
        ], [
            'score' => 100,
            'answers' => ['placements' => ['2' => 'A', '3' => 'B', '4' => 'A']],
        ]);

        $this->assertSame(0, $score);
        $this->assertSame('auto_checked', $status);
        $this->assertFalse($answers['correct']);

        [$score, , $answers] = $this->verify('venn_diagram', [
            'type' => 'venn_diagram', 'setA' => ['2', '4'], 'setB' => ['3', '4'], 'items' => ['2', '3', '4'], 'intersection' => ['4'],
        ], ['score' => 0, 'answers' => ['placements' => ['2' => 'A', '3' => 'B', '4' => 'AB']]]);
        $this->assertSame(100, $score);
        $this->assertTrue($answers['correct']);
    }

    public function test_dictation_is_scored_by_word_distance(): void
    {
        [$score, $status] = $this->verify('dictation', [
            'type' => 'dictation',
            'items' => [['text' => 'The children walk to school.']],
        ], [
            'score' => 0,
            'answers' => ['items' => ['The children walk to school']],
            'evidence' => ['replays' => [2]],
        ]);

        $this->assertSame(100, $score);
        $this->assertSame('auto_checked', $status);
    }

    public function test_speaking_transcript_does_not_claim_pronunciation_verification(): void
    {
        [$score, $status, $answers, $evidence] = $this->verify('oral_drill', [
            'type' => 'oral_drill',
            'items' => [['text' => 'Good morning']],
        ], [
            'answers' => ['items' => [['transcript' => 'Good morning']]],
        ]);

        $this->assertSame(100, $score);
        $this->assertSame('practice_only', $status);
        $this->assertSame('speech_transcript', $answers['items'][0]['method']);
        $this->assertFalse($evidence['pronunciation_verified']);
    }

    public function test_server_pronunciation_assessment_is_used_instead_of_client_score(): void
    {
        [$score, $status, $answers, $evidence] = $this->verify('oral_drill', [
            'type' => 'oral_drill',
            'items' => [['text' => 'Good morning']],
        ], [
            'score' => 100,
            'answers' => ['items' => [[
                'transcript' => 'client supplied text',
                '_server_assessment' => [
                    'transcript' => 'Good morning.',
                    'pronunciation_score' => 73,
                    'accuracy_score' => 76,
                    'fluency_score' => 68,
                    'completeness_score' => 100,
                    'words' => [['word' => 'morning', 'accuracy' => 54, 'error_type' => 'Mispronunciation']],
                ],
            ]]],
        ]);

        $this->assertSame(73, $score);
        $this->assertSame('auto_checked', $status);
        $this->assertSame('pronunciation_assessment', $answers['items'][0]['method']);
        $this->assertSame('Good morning.', $answers['items'][0]['transcript']);
        $this->assertTrue($evidence['pronunciation_verified']);
    }

    public function test_pronunciation_provider_result_is_reduced_to_safe_feedback(): void
    {
        $result = (new PronunciationAssessmentService())->normalizeResult([
            'RecognitionStatus' => 'Success',
            'DisplayText' => 'Good morning.',
            'NBest' => [[
                'PronunciationAssessment' => [
                    'AccuracyScore' => 81.4,
                    'FluencyScore' => 72.2,
                    'CompletenessScore' => 100,
                    'PronScore' => 79.6,
                ],
                'Words' => [[
                    'Word' => 'morning',
                    'PronunciationAssessment' => ['AccuracyScore' => 58.4, 'ErrorType' => 'Mispronunciation'],
                ]],
            ]],
        ]);

        $this->assertSame(80, $result['pronunciation_score']);
        $this->assertSame(81, $result['accuracy_score']);
        $this->assertSame(72, $result['fluency_score']);
        $this->assertSame(58, $result['words'][0]['accuracy']);
        $this->assertArrayNotHasKey('NBest', $result);
    }

    public function test_written_response_stays_pending_review_without_a_score(): void
    {
        [$score, $status, $answers] = $this->verify('written_response', [
            'type' => 'written_response',
            'min_words' => 5,
        ], [
            'score' => 100,
            'answers' => ['text' => 'This is my short school paragraph.'],
        ]);

        $this->assertNull($score);
        $this->assertSame('pending_review', $status);
        $this->assertSame('This is my short school paragraph.', $answers['text']);
    }

    public function test_handwriting_trace_summary_is_recomputed_from_points(): void
    {
        $summary = $this->analyzeHandwriting([[
            ['x' => 0.10, 'y' => 0.20, 't' => 0],
            ['x' => 0.18, 'y' => 0.28, 't' => 20],
            ['x' => 0.26, 'y' => 0.36, 't' => 40],
            ['x' => 0.34, 'y' => 0.44, 't' => 60],
            ['x' => 0.42, 'y' => 0.52, 't' => 80],
            ['x' => 0.50, 'y' => 0.60, 't' => 100],
        ]]);

        $this->assertSame(2, $summary['version']);
        $this->assertSame(1, $summary['stroke_count']);
        $this->assertSame(6, $summary['point_count']);
        $this->assertEqualsWithDelta(0.5657, $summary['normalized_distance'], 0.0001);
        $this->assertSame(0.4, $summary['bounds']['width']);
        $this->assertSame(0.4, $summary['bounds']['height']);
    }

    public function test_handwriting_rejects_a_dot_claimed_as_a_complete_trace(): void
    {
        $this->expectException(ValidationException::class);
        $this->analyzeHandwriting([[
            ['x' => 0.5, 'y' => 0.5, 't' => 0],
            ['x' => 0.501, 'y' => 0.501, 't' => 10],
            ['x' => 0.502, 'y' => 0.502, 't' => 20],
            ['x' => 0.503, 'y' => 0.503, 't' => 30],
            ['x' => 0.504, 'y' => 0.504, 't' => 40],
            ['x' => 0.505, 'y' => 0.505, 't' => 50],
        ]]);
    }

    public function test_handwriting_rejects_points_outside_the_canvas(): void
    {
        $this->expectException(ValidationException::class);
        $this->analyzeHandwriting([[
            ['x' => -0.1, 'y' => 0.2, 't' => 0],
            ['x' => 0.2, 'y' => 0.3, 't' => 20],
            ['x' => 0.3, 'y' => 0.4, 't' => 40],
            ['x' => 0.4, 'y' => 0.5, 't' => 60],
            ['x' => 0.5, 'y' => 0.6, 't' => 80],
            ['x' => 0.6, 'y' => 0.7, 't' => 100],
        ]]);
    }

    public function test_class_one_written_response_rejects_gibberish(): void
    {
        $this->expectException(ValidationException::class);

        $this->verify('written_response', [
            'type' => 'written_response',
            'min_words' => 6,
            'required_sentences' => 2,
            'required_any_terms' => ['family', 'mother', 'father'],
            'accepted_words' => ['my', 'mother', 'father', 'is', 'kind', 'and'],
            'min_recognized_ratio' => 0.7,
            'max_unrecognized_words' => 2,
        ], [
            'answers' => ['text' => 'im dk,sd sd and je suis'],
        ]);
    }

    public function test_class_one_written_response_accepts_clear_relevant_sentences_for_parent_review(): void
    {
        [$score, $status, , $evidence] = $this->verify('written_response', [
            'type' => 'written_response',
            'min_words' => 6,
            'required_sentences' => 2,
            'required_any_terms' => ['family', 'mother', 'father'],
            'accepted_words' => ['my', 'mother', 'father', 'is', 'kind', 'and'],
            'min_recognized_ratio' => 0.7,
            'max_unrecognized_words' => 2,
        ], [
            'answers' => ['text' => 'My mother is kind. My father is kind.'],
        ]);

        $this->assertNull($score);
        $this->assertSame('pending_review', $status);
        $this->assertSame(2, $evidence['sentence_count']);
        $this->assertSame('written_response_precheck_and_parent_review', $evidence['method']);
    }
}
