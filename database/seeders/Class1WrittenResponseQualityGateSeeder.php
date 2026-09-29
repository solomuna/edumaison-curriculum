<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class1WrittenResponseQualityGateSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $exercise = DB::table('exercises')->where('id', 3157)->lockForUpdate()->first();
            if (! $exercise || $exercise->lesson_id !== 10 || $exercise->title !== 'My Family: Two Sentences') {
                throw new \RuntimeException('The English Class 1 written-response pilot was not found.');
            }

            $content = json_decode($exercise->content, true, flags: JSON_THROW_ON_ERROR);
            if (($content['type'] ?? null) !== 'written_response'
                || ($content['prompt'] ?? null) !== 'Write two short sentences about your family.'
                || ($content['min_words'] ?? null) !== 6) {
                throw new \RuntimeException('The English Class 1 written-response definition has changed unexpectedly.');
            }

            $qualityRules = [
                'required_sentences' => 2,
                'required_any_terms' => [
                    'family', 'mother', 'mum', 'mummy', 'father', 'dad', 'daddy',
                    'sister', 'brother', 'grandmother', 'grandfather', 'aunt', 'uncle', 'cousin',
                ],
                'accepted_words' => [
                    'a', 'am', 'and', 'are', 'at', 'aunt', 'beautiful', 'big', 'brother',
                    'called', 'can', 'cooks', 'cousin', 'dad', 'daddy', 'father', 'family',
                    'funny', 'girl', 'good', 'grandfather', 'grandmother', 'happy', 'has', 'have',
                    'he', 'helps', 'home', 'i', "i'm", 'in', 'is', 'kind', 'little', 'live',
                    'lives', 'love', 'loves', 'me', 'mother', 'mum', 'mummy', 'my', 'name',
                    'nice', 'old', 'our', 'play', 'plays', 'reads', 'she', 'sister', 'small',
                    'strong', 'tall', 'teacher', 'the', 'they', 'to', 'together', 'two', 'uncle',
                    'very', 'we', 'with', 'works', 'young',
                ],
                'min_recognized_ratio' => 0.7,
                'max_unrecognized_words' => 2,
            ];

            foreach ($qualityRules as $key => $value) {
                if (array_key_exists($key, $content) && $content[$key] !== $value) {
                    throw new \RuntimeException("The written-response quality rule {$key} has changed unexpectedly.");
                }
                $content[$key] = $value;
            }

            DB::table('exercises')->where('id', 3157)->update([
                'content' => json_encode(
                    $content,
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
                ),
                'updated_at' => now(),
            ]);

            $attempt = DB::table('exercise_attempts')->where('id', 2392)->lockForUpdate()->first();
            if (! $attempt) {
                return;
            }

            $answers = json_decode($attempt->answers, true, flags: JSON_THROW_ON_ERROR);
            $submittedText = (string) ($answers['text'] ?? '');
            if ($attempt->exercise_id !== 3157
                || $attempt->child_id !== 1
                || hash('sha256', $submittedText) !== '8e405489886881c322130f6445291f52577bd90f0ace089722c304de97682e0c') {
                throw new \RuntimeException('Attempt 2392 no longer matches the reported gibberish submission.');
            }

            if ($attempt->status === 'incomplete' && $attempt->verification_status === 'practice_only') {
                return;
            }
            if ($attempt->status !== 'completed' || $attempt->verification_status !== 'pending_review') {
                throw new \RuntimeException('Attempt 2392 has already moved to an unexpected review state.');
            }

            $evidence = json_decode($attempt->evidence ?: '{}', true, flags: JSON_THROW_ON_ERROR);
            $evidence['quality_audit'] = [
                'result' => 'rejected',
                'reason' => 'failed_written_response_precheck',
                'audited_at' => now()->toIso8601String(),
            ];
            DB::table('exercise_attempts')->where('id', 2392)->update([
                'status' => 'incomplete',
                'verification_status' => 'practice_only',
                'evidence' => json_encode(
                    $evidence,
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
                ),
                'updated_at' => now(),
            ]);
        });
    }
}
