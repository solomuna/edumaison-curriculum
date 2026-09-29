<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class3EnglishAlignmentPilotSeeder extends Seeder
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
                ->where('levels.name', 'Class 3')
                ->where('levels.education_subsystem', 'anglophone')
                ->where('subjects.name', 'English')
                ->value('subjects.id');
            if (! $subjectId) {
                throw new \RuntimeException('Class 3 English was not found.');
            }

            $duplicateGroups = [
                [217, 300, 383],
                [218, 301, 384],
                [219, 302, 385],
                [220, 303, 386],
                [221, 304, 387],
                [222, 305, 388],
                [223, 306, 389],
                [224, 307, 390, 1450],
            ];
            $duplicateIds = collect($duplicateGroups)->flatten()->values();
            $rows = DB::table('exercises')->whereIn('id', $duplicateIds)->get()->keyBy('id');
            if ($rows->count() !== $duplicateIds->count()) {
                throw new \RuntimeException('The complete Class 3 English duplicate set was not found.');
            }
            $attemptedDuplicates = DB::table('exercise_attempts')->whereIn('exercise_id', $duplicateIds)->count();
            if ($attemptedDuplicates !== 0) {
                throw new \RuntimeException('A Class 3 English duplicate now has attempt history; cleanup stopped.');
            }

            $deactivateIds = [];
            foreach ($duplicateGroups as $group) {
                $canonical = json_encode(json_decode($rows[$group[0]]->content, true, flags: JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
                foreach (array_slice($group, 1) as $duplicateId) {
                    $candidate = json_encode(json_decode($rows[$duplicateId]->content, true, flags: JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
                    if ($candidate !== $canonical) {
                        throw new \RuntimeException("Exercise {$duplicateId} no longer matches its canonical copy.");
                    }
                    $deactivateIds[] = $duplicateId;
                }
            }
            DB::table('exercises')->whereIn('id', $deactivateIds)->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);

            $grammarLessonId = 382;
            $writingLessonId = 385;
            $targetLessonCount = DB::table('lessons')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->whereIn('lessons.id', [$grammarLessonId, $writingLessonId])
                ->count();
            if ($targetLessonCount !== 2) {
                throw new \RuntimeException('The Class 3 English target lessons were not found.');
            }

            $movedExerciseIds = [219, 220, 1444, 1447, 1454];
            $ownedMovedIds = DB::table('exercises')
                ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->whereIn('exercises.id', $movedExerciseIds)
                ->pluck('exercises.id')
                ->unique();
            if ($ownedMovedIds->count() !== count($movedExerciseIds)) {
                throw new \RuntimeException('The complete Class 3 English lesson-move set was not found.');
            }

            DB::table('exercises')->whereIn('id', [219, 220, 1444, 1447])->update([
                'lesson_id' => $grammarLessonId,
                'updated_at' => now(),
            ]);
            DB::table('exercises')->where('id', 1454)->update([
                'lesson_id' => $writingLessonId,
                'updated_at' => now(),
            ]);

            $competencyLinks = [
                'Listening and speaking in familiar contexts' => [40, 42, 44],
                'Fluent reading and comprehension' => [1453],
                'Legible and coherent writing' => [1454],
            ];
            $sourcePages = [
                'Listening and speaking in familiar contexts' => '25-26, 41-43',
                'Fluent reading and comprehension' => '25-26, 44',
                'Legible and coherent writing' => '25-26, 45-49',
            ];
            $descriptions = [
                'Listening and speaking in familiar contexts' => 'Understand spoken information, respond appropriately and communicate ideas or feelings clearly.',
                'Fluent reading and comprehension' => 'Read suitable texts fluently and retrieve or interpret relevant information.',
                'Legible and coherent writing' => 'Produce legible, coherent writing that communicates ideas, feelings or information.',
            ];

            $order = 1;
            foreach ($competencyLinks as $name => $exerciseIds) {
                $competencyId = DB::table('school_competencies')
                    ->where('subject_id', $subjectId)
                    ->where('name', $name)
                    ->value('id');
                $values = [
                    'curriculum_document_id' => $documentId,
                    'name' => $name,
                    'description' => $descriptions[$name],
                    'source_pages' => $sourcePages[$name],
                    'verification_status' => 'verified_source',
                    'order' => $order++,
                    'is_active' => true,
                    'updated_at' => now(),
                ];
                if ($competencyId) {
                    DB::table('school_competencies')->where('id', $competencyId)->update($values);
                } else {
                    $competencyId = DB::table('school_competencies')->insertGetId($values + [
                        'subject_id' => $subjectId,
                        'created_at' => now(),
                    ]);
                }

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
