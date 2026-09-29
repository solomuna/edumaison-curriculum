<?php

namespace Database\Seeders;

use App\Models\Exercise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class1WrittenResponsePilotSeeder extends Seeder
{
    private const DOCUMENT_SHA256 = '38a7c7eddea5ede2bc05bf071716e7067fd9fb987cad2d43fd33a9e294baf957';
    private const DOCUMENT_PATH = 'curricula/cameroon-primary-english-level-1-class-1-2.pdf';

    public function run(): void
    {
        $documentPath = storage_path('app/private/'.self::DOCUMENT_PATH);
        if (! is_file($documentPath) || hash_file('sha256', $documentPath) !== self::DOCUMENT_SHA256) {
            throw new \RuntimeException('The verified Level I curriculum PDF is missing or has an unexpected checksum.');
        }

        DB::transaction(function (): void {
            $documentId = DB::table('curriculum_documents')
                ->where('sha256', self::DOCUMENT_SHA256)
                ->where('verification_status', 'verified_source')
                ->value('id');
            if (! $documentId) {
                throw new \RuntimeException('The verified MINEDUB Level I curriculum document was not found.');
            }

            $subjectId = DB::table('subjects')
                ->join('levels', 'subjects.level_id', '=', 'levels.id')
                ->where('levels.name', 'Class 1')
                ->where('levels.education_subsystem', 'anglophone')
                ->where('subjects.name', 'English')
                ->value('subjects.id');
            if (! $subjectId) {
                throw new \RuntimeException('English Class 1 was not found.');
            }

            $lessonId = 10;
            $lessonBelongsToSubject = DB::table('lessons')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('lessons.id', $lessonId)
                ->where('integrated_themes.subject_id', $subjectId)
                ->exists();
            if (! $lessonBelongsToSubject) {
                throw new \RuntimeException('The My Family Tree lesson no longer belongs to English Class 1.');
            }

            $competencyId = DB::table('school_competencies')
                ->where('subject_id', $subjectId)
                ->where('curriculum_document_id', $documentId)
                ->where('name', 'Writing words and short simple sentences')
                ->where('source_pages', '44')
                ->where('verification_status', 'verified_source')
                ->where('is_active', true)
                ->value('id');
            if (! $competencyId) {
                throw new \RuntimeException('The verified English Class 1 writing competency was not found.');
            }

            $definition = [
                'lesson_id' => $lessonId,
                'title' => 'My Family: Two Sentences',
                'instructions' => 'Type two short sentences. A parent will read them with you.',
                'category' => 'writing',
                'difficulty' => 'easy',
                'estimated_minutes' => 8,
                'content' => [
                    'type' => 'written_response',
                    'prompt' => 'Write two short sentences about your family.',
                    'min_words' => 6,
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
                    'checklist' => [
                        'Name at least one family member.',
                        'Start each sentence with a capital letter.',
                        'Finish each sentence with a full stop.',
                    ],
                ],
            ];

            $exercise = Exercise::query()->firstOrNew([
                'lesson_id' => $definition['lesson_id'],
                'title' => $definition['title'],
            ]);
            $expected = $definition;
            $expected['content'] = $definition['content'];

            if ($exercise->exists && $exercise->attempts()->exists()) {
                foreach ($expected as $field => $value) {
                    if ($exercise->{$field} !== $value) {
                        throw new \RuntimeException("Exercise {$exercise->id} has attempts and no longer matches the written-response pilot.");
                    }
                }
                if (! $exercise->is_active) {
                    throw new \RuntimeException("Exercise {$exercise->id} has attempts but is inactive.");
                }
            } else {
                $exercise->fill($definition);
                $exercise->is_active = true;
                $exercise->save();
            }

            $exercise->schoolCompetencies()->syncWithoutDetaching([$competencyId]);
        });
    }
}
