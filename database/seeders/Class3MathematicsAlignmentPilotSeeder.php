<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class3MathematicsAlignmentPilotSeeder extends Seeder
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
                ->where('subjects.name', 'Mathematics')
                ->value('subjects.id');
            if (! $subjectId) {
                throw new \RuntimeException('Class 3 Mathematics was not found.');
            }

            $duplicateGroups = [
                [176, 259, 342],
                [177, 260, 343],
                [178, 261, 344],
                [179, 262, 345],
                [180, 263, 346],
                [181, 264, 347],
                [182, 265, 348],
                [183, 266, 349],
                [184, 267, 350],
            ];
            $duplicateIds = collect($duplicateGroups)->flatten()->values();
            $rows = DB::table('exercises')->whereIn('id', $duplicateIds)->get()->keyBy('id');
            if ($rows->count() !== $duplicateIds->count()) {
                throw new \RuntimeException('The complete Class 3 Mathematics duplicate set was not found.');
            }

            $deactivateIds = [];
            foreach ($duplicateGroups as $group) {
                $canonical = $this->canonicalContent($rows[$group[0]]->content);
                foreach (array_slice($group, 1) as $duplicateId) {
                    if ($this->canonicalContent($rows[$duplicateId]->content) !== $canonical) {
                        throw new \RuntimeException("Exercise {$duplicateId} no longer matches its canonical copy.");
                    }
                    $deactivateIds[] = $duplicateId;
                }
            }

            if (DB::table('exercise_attempts')->whereIn('exercise_id', $deactivateIds)->exists()) {
                throw new \RuntimeException('A redundant Class 3 Mathematics copy now has attempt history; cleanup stopped.');
            }

            DB::table('exercises')->whereIn('id', $deactivateIds)->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);

            $targetLessons = [
                'numbers' => 369,
                'geometry' => 371,
                'measurement' => 372,
                'sets_money_statistics' => 373,
            ];
            $targetLessonCount = DB::table('lessons')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->whereIn('lessons.id', array_values($targetLessons))
                ->count();
            if ($targetLessonCount !== count($targetLessons)) {
                throw new \RuntimeException('The Class 3 Mathematics target lessons were not found.');
            }

            $moves = [
                $targetLessons['geometry'] => [416, 417, 623],
                $targetLessons['measurement'] => [942, 943, 944, 945, 946, 947, 948, 949, 1004],
                $targetLessons['sets_money_statistics'] => [407, 408, 1049, 1050, 1052, 1053, 1075, 1079, 1243, 1245, 1246, 1247, 1248],
            ];
            $movedExerciseIds = collect($moves)->flatten()->unique()->values();
            $ownedMovedIds = DB::table('exercises')
                ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->where('exercises.is_active', true)
                ->whereIn('exercises.id', $movedExerciseIds)
                ->pluck('exercises.id')
                ->unique();
            if ($ownedMovedIds->count() !== $movedExerciseIds->count()) {
                throw new \RuntimeException('The complete Class 3 Mathematics lesson-move set was not found.');
            }

            foreach ($moves as $lessonId => $exerciseIds) {
                DB::table('exercises')->whereIn('id', $exerciseIds)->update([
                    'lesson_id' => $lessonId,
                    'updated_at' => now(),
                ]);
            }

            $competencies = [
                [
                    'official_code' => 'MINEDUB-L2-MATH-C3-SETS-LOGIC',
                    'name' => 'Sets and logic',
                    'description' => 'Describe universal sets, subsets, finite and infinite sets, equal and equivalent sets, membership and intersection.',
                    'source_pages' => '50',
                    'order' => 1,
                    'exercise_ids' => [181, 183, 407, 408, 1049, 1051, 1052, 1054],
                ],
                [
                    'official_code' => 'MINEDUB-L2-MATH-C3-NUMBERS-OPERATIONS',
                    'name' => 'Numbers and operations up to 500',
                    'description' => 'Read, compare and calculate with numbers up to 500, including multiplication, division and addition or subtraction of fractions.',
                    'source_pages' => '50',
                    'order' => 2,
                    'exercise_ids' => [46, 47, 48, 178, 1092],
                ],
                [
                    'official_code' => 'MINEDUB-L2-MATH-C3-MEASUREMENT',
                    'name' => 'Measurement, calendar, time and money',
                    'description' => 'Use metric units, calendars and clocks, and solve money problems up to 1,000 francs.',
                    'source_pages' => '51',
                    'order' => 3,
                    'exercise_ids' => [179, 184, 426, 427, 428, 429, 430, 942, 943, 944, 945, 946, 947, 948, 949, 993, 994, 995, 996, 997, 998, 999, 1000, 1001, 1002, 1004, 1005, 1075, 1076, 1077, 1078, 1079],
                ],
                [
                    'official_code' => 'MINEDUB-L2-MATH-C3-GEOMETRY-SPACE',
                    'name' => 'Geometry and space',
                    'description' => 'Locate points on a number line up to 30 and identify circles and three- or four-dimensional shapes.',
                    'source_pages' => '52',
                    'order' => 4,
                    'exercise_ids' => [416, 417, 623],
                ],
                [
                    'official_code' => 'MINEDUB-L2-MATH-C3-STATISTICS-GRAPHS',
                    'name' => 'Statistics and graphs',
                    'description' => 'Represent and order data, tally values, and use maps or grids to locate and interpret information.',
                    'source_pages' => '52',
                    'order' => 5,
                    'exercise_ids' => [1243, 1244, 1245, 1246, 1247, 1248],
                ],
            ];

            $allExerciseIds = collect($competencies)->pluck('exercise_ids')->flatten()->unique()->values();
            $ownedExerciseIds = DB::table('exercises')
                ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->where('exercises.is_active', true)
                ->whereIn('exercises.id', $allExerciseIds)
                ->pluck('exercises.id')
                ->unique();
            if ($ownedExerciseIds->count() !== $allExerciseIds->count()) {
                throw new \RuntimeException('The complete Class 3 Mathematics competency-link set was not found.');
            }

            foreach ($competencies as $competency) {
                $exerciseIds = $competency['exercise_ids'];
                unset($competency['exercise_ids']);
                $competencyId = DB::table('school_competencies')
                    ->where('subject_id', $subjectId)
                    ->where('official_code', $competency['official_code'])
                    ->value('id');
                $values = $competency + [
                    'curriculum_document_id' => $documentId,
                    'verification_status' => 'verified_source',
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

    private function canonicalContent(string $content): string
    {
        return json_encode(
            json_decode($content, true, flags: JSON_THROW_ON_ERROR),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
        );
    }
}
