<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class3HandwritingAlignmentPilotSeeder extends Seeder
{
    private const DOCUMENT_SHA256 = '1921b9d044f23849c1532f403021e0a8d23a46123d4b225b8b083b827e3fdd1c';

    public function run(): void
    {
        DB::transaction(function (): void {
            $documentId = DB::table('curriculum_documents')
                ->where('sha256', self::DOCUMENT_SHA256)
                ->where('verification_status', 'verified_source')
                ->value('id');
            if (! $documentId) {
                throw new \RuntimeException('The verified MINEDUB Level II document was not found.');
            }

            $subjectId = DB::table('subjects')
                ->join('levels', 'subjects.level_id', '=', 'levels.id')
                ->where('levels.name', 'Class 3')
                ->where('levels.education_subsystem', 'anglophone')
                ->where('subjects.name', 'Handwriting')
                ->value('subjects.id');
            if (! $subjectId) {
                throw new \RuntimeException('The Class 3 Handwriting subject was not found.');
            }

            $allIds = [1694, 1695, 1696, 1697, 1698, 1699, 1700, 1701, 1702, 1725, 1726, 1727, 1728, 1729, 1730];
            $activeIds = [1698, 1699, 1700, 1701, 1729];
            $inactiveIds = array_values(array_diff($allIds, $activeIds));
            $rows = DB::table('exercises')->whereIn('id', $allIds)->get()->keyBy('id');
            if ($rows->count() !== count($allIds)) {
                throw new \RuntimeException('The complete Class 3 Handwriting exercise set was not found.');
            }
            if (DB::table('exercise_attempts')->whereIn('exercise_id', $allIds)->exists()) {
                throw new \RuntimeException('A Class 3 Handwriting exercise now has attempt history; alignment stopped.');
            }
            $this->assertExerciseSubjects($allIds, (int) $subjectId);
            $this->assertLessonSubject(315, (int) $subjectId);

            $allowedTitles = [
                1694 => ['Cursive lowercase a-e'],
                1695 => ['Cursive lowercase f-j'],
                1696 => ['Cursive lowercase k-p'],
                1697 => ['Cursive lowercase q-z'],
                1698 => ['Trace cursive words', 'My Morning'],
                1699 => ['Trace more cursive words', 'Our Garden'],
                1700 => ['Copy sentence 1', 'At the Market'],
                1701 => ['Copy sentence 2', 'A Rainy Day'],
                1702 => ['Handwriting rule C3'],
                1725 => ['Script lowercase a-e'],
                1726 => ['Script lowercase f-j'],
                1727 => ['Script lowercase k-p'],
                1728 => ['Script lowercase q-z'],
                1729 => ['Trace script words', 'Our Class Library'],
                1730 => ['Trace more script words'],
            ];
            foreach ($allowedTitles as $exerciseId => $titles) {
                if (! in_array($rows[$exerciseId]->title, $titles, true)) {
                    throw new \RuntimeException("Exercise {$exerciseId} title has changed; alignment stopped.");
                }
            }

            $activities = [
                1698 => [
                    'title' => 'My Morning',
                    'prompts' => [
                        'Amina wakes up early.',
                        'My family eats together.',
                        'I pack my school bag.',
                        'We walk to school.',
                        'Our day begins well.',
                    ],
                ],
                1699 => [
                    'title' => 'Our Garden',
                    'prompts' => [
                        'Our garden is green.',
                        'Beans grow near the fence.',
                        'I water them each morning.',
                        'Birds sing in the trees.',
                        'We keep the garden clean.',
                    ],
                ],
                1700 => [
                    'title' => 'At the Market',
                    'prompts' => [
                        'The market opens early.',
                        'Mama buys fresh tomatoes.',
                        'I carry the small basket.',
                        'We greet our neighbours.',
                        'Then we walk home.',
                    ],
                ],
                1701 => [
                    'title' => 'A Rainy Day',
                    'prompts' => [
                        'Rain falls on the roof.',
                        'Children close the windows.',
                        'The road becomes wet.',
                        'We read inside the house.',
                        'Soon the sky is bright.',
                    ],
                ],
                1729 => [
                    'title' => 'Our Class Library',
                    'prompts' => [
                        'Our class has a library.',
                        'I choose a short story.',
                        'My friend reads with me.',
                        'We return the books neatly.',
                        'Reading helps us learn.',
                    ],
                ],
            ];

            foreach ($activities as $exerciseId => $activity) {
                DB::table('exercises')->where('id', $exerciseId)->update([
                    'lesson_id' => 315,
                    'title' => $activity['title'],
                    'instructions' => 'Copy each sentence in neat upright joined writing. Keep your letters even and leave clear spaces.',
                    'category' => 'handwriting',
                    'difficulty' => 'medium',
                    'estimated_minutes' => 10,
                    'content' => json_encode([
                        'type' => 'handwriting',
                        'practice_mode' => 'copy',
                        'guide_style' => 'print', // pas d'écriture cursive
                        'prompts' => $activity['prompts'],
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
                    'is_active' => true,
                    'updated_at' => now(),
                ]);
            }

            DB::table('exercises')->whereIn('id', $inactiveIds)->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);

            $this->alignHierarchy();
            foreach ([313, 314, 325, 326] as $lessonId) {
                $this->deactivateEmptyHierarchy($lessonId);
            }

            $competencyId = $this->upsertCompetency(
                (int) $subjectId,
                (int) $documentId,
                'MINEDUB-L2-HWR-C3-UPRIGHT-JOINT',
                'Copy short texts in upright joint script',
                'Copy short texts of at least five different sentences legibly and consistently in upright joint script, while showing readiness to write.',
                '45',
                1
            );

            $subjectCompetencyIds = DB::table('school_competencies')
                ->where('subject_id', $subjectId)
                ->pluck('id');
            if ($subjectCompetencyIds->isNotEmpty()) {
                DB::table('exercise_school_competency')
                    ->whereIn('exercise_id', $allIds)
                    ->whereIn('school_competency_id', $subjectCompetencyIds)
                    ->delete();
            }
            foreach ($activeIds as $exerciseId) {
                DB::table('exercise_school_competency')->updateOrInsert([
                    'exercise_id' => $exerciseId,
                    'school_competency_id' => $competencyId,
                ]);
            }
        });
    }

    private function assertExerciseSubjects(array $exerciseIds, int $subjectId): void
    {
        $rows = DB::table('exercises')
            ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->whereIn('exercises.id', $exerciseIds)
            ->get(['exercises.id', 'integrated_themes.subject_id']);
        if ($rows->count() !== count($exerciseIds)
            || $rows->contains(fn (object $row): bool => (int) $row->subject_id !== $subjectId)) {
            throw new \RuntimeException('A Class 3 Handwriting exercise is outside the expected subject.');
        }
    }

    private function assertLessonSubject(int $lessonId, int $subjectId): void
    {
        $exists = DB::table('lessons')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->where('lessons.id', $lessonId)
            ->where('integrated_themes.subject_id', $subjectId)
            ->exists();
        if (! $exists) {
            throw new \RuntimeException("Lesson {$lessonId} is not owned by Class 3 Handwriting.");
        }
    }

    private function alignHierarchy(): void
    {
        $path = DB::table('lessons')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->where('lessons.id', 315)
            ->first(['lessons.unit_id', 'units.integrated_theme_id']);
        if (! $path) {
            throw new \RuntimeException('The target Class 3 Handwriting hierarchy was not found.');
        }

        DB::table('integrated_themes')->where('id', $path->integrated_theme_id)->update([
            'name' => 'Upright Joint Script',
            'description' => 'Copy short, familiar texts in a clear and consistent joined handwriting style.',
            'is_active' => true,
            'updated_at' => now(),
        ]);
        DB::table('units')->where('id', $path->unit_id)->update([
            'name' => 'Copying Short Texts',
            'description' => 'Five-sentence texts based on familiar home, school and community contexts.',
            'summary' => 'Read each model sentence, then copy it independently on the writing lines.',
            'is_active' => true,
            'updated_at' => now(),
        ]);
        DB::table('lessons')->where('id', 315)->update([
            'name' => 'Five-sentence Texts',
            'description' => 'Copy five different sentences in upright joint script with even letters and clear word spaces.',
            'content' => 'Class 3 Handwriting aligned to the official MINEDUB Level II curriculum, page 45.',
            'type' => 'writing',
            'is_active' => true,
            'updated_at' => now(),
        ]);
    }

    private function deactivateEmptyHierarchy(int $lessonId): void
    {
        $path = DB::table('lessons')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->where('lessons.id', $lessonId)
            ->first(['lessons.unit_id', 'units.integrated_theme_id']);
        if (! $path) {
            throw new \RuntimeException("Handwriting lesson {$lessonId} was not found.");
        }
        if (DB::table('exercises')->where('lesson_id', $lessonId)->where('is_active', true)->exists()) {
            throw new \RuntimeException("Handwriting lesson {$lessonId} is not empty.");
        }

        DB::table('lessons')->where('id', $lessonId)->update(['is_active' => false, 'updated_at' => now()]);
        $unitHasActiveExercises = DB::table('exercises')
            ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
            ->where('lessons.unit_id', $path->unit_id)
            ->where('exercises.is_active', true)
            ->exists();
        if (! $unitHasActiveExercises) {
            DB::table('units')->where('id', $path->unit_id)->update(['is_active' => false, 'updated_at' => now()]);
        }

        $themeHasActiveExercises = DB::table('exercises')
            ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->where('units.integrated_theme_id', $path->integrated_theme_id)
            ->where('exercises.is_active', true)
            ->exists();
        if (! $themeHasActiveExercises) {
            DB::table('integrated_themes')->where('id', $path->integrated_theme_id)->update(['is_active' => false, 'updated_at' => now()]);
        }
    }

    private function upsertCompetency(
        int $subjectId,
        int $documentId,
        string $code,
        string $name,
        string $description,
        string $pages,
        int $order
    ): int {
        $id = DB::table('school_competencies')
            ->where('subject_id', $subjectId)
            ->where('official_code', $code)
            ->value('id');
        $values = [
            'curriculum_document_id' => $documentId,
            'name' => $name,
            'description' => $description,
            'source_pages' => $pages,
            'verification_status' => 'verified_source',
            'order' => $order,
            'is_active' => true,
            'updated_at' => now(),
        ];
        if ($id) {
            DB::table('school_competencies')->where('id', $id)->update($values);
            return (int) $id;
        }
        return (int) DB::table('school_competencies')->insertGetId($values + [
            'subject_id' => $subjectId,
            'official_code' => $code,
            'created_at' => now(),
        ]);
    }
}
