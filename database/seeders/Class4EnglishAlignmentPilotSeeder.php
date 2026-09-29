<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4EnglishAlignmentPilotSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $documentId = DB::table('curriculum_documents')
                ->where('sha256', '1921b9d044f23849c1532f403021e0a8d23a46123d4b225b8b083b827e3fdd1c')
                ->where('verification_status', 'verified_source')
                ->value('id');
            if (! $documentId) {
                throw new \RuntimeException('The verified MINEDUB Level II document was not found.');
            }

            $subjectId = DB::table('subjects')
                ->join('levels', 'subjects.level_id', '=', 'levels.id')
                ->where('levels.name', 'Class 4')
                ->where('levels.education_subsystem', 'anglophone')
                ->where('subjects.name', 'English')
                ->value('subjects.id');
            if (! $subjectId) {
                throw new \RuntimeException('Class 4 English was not found.');
            }

            $lessonIds = DB::table('lessons')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->whereIn('lessons.id', [344, 347])
                ->pluck('lessons.id')
                ->all();
            if (count($lessonIds) !== 2) {
                throw new \RuntimeException('The Class 4 English target lessons were not found.');
            }

            $grammarExerciseIds = [
                391, 392, 393, 394, 395,
                1455, 1456, 1457, 1458, 1459, 1460, 1461, 1462, 1463, 1464,
            ];
            $writingExerciseIds = [397, 398, 1469];
            $allMovedIds = array_merge($grammarExerciseIds, $writingExerciseIds);

            $ownedExerciseIds = DB::table('exercises')
                ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->whereIn('exercises.id', $allMovedIds)
                ->pluck('exercises.id')
                ->all();
            if (count($ownedExerciseIds) !== count($allMovedIds)) {
                throw new \RuntimeException('The complete Class 4 English alignment set was not found.');
            }

            DB::table('exercises')->whereIn('id', $grammarExerciseIds)->update([
                'lesson_id' => 344,
                'updated_at' => now(),
            ]);
            DB::table('exercises')->whereIn('id', $writingExerciseIds)->update([
                'lesson_id' => 347,
                'updated_at' => now(),
            ]);

            $competencyLinks = [
                'Listening and speaking in familiar contexts' => [49, 51, 53],
                'Fluent reading and comprehension' => [1468],
                'Legible and coherent writing' => [397, 398, 1469],
            ];

            foreach ($competencyLinks as $competencyName => $exerciseIds) {
                $competencyId = DB::table('school_competencies')
                    ->where('subject_id', $subjectId)
                    ->where('curriculum_document_id', $documentId)
                    ->where('name', $competencyName)
                    ->where('verification_status', 'verified_source')
                    ->value('id');
                if (! $competencyId) {
                    throw new \RuntimeException("Verified competency not found: {$competencyName}");
                }

                DB::table('school_competencies')->where('id', $competencyId)->update([
                    'is_active' => true,
                    'updated_at' => now(),
                ]);

                foreach ($exerciseIds as $exerciseId) {
                    DB::table('exercise_school_competency')->updateOrInsert([
                        'exercise_id' => $exerciseId,
                        'school_competency_id' => $competencyId,
                    ]);
                }
            }
        });
    }
}
