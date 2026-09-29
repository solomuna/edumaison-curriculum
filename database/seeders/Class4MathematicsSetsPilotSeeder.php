<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4MathematicsSetsPilotSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $subject = DB::table('subjects')
                ->join('levels', 'subjects.level_id', '=', 'levels.id')
                ->where('levels.name', 'Class 4')
                ->where('levels.education_subsystem', 'anglophone')
                ->where('subjects.name', 'Mathematics')
                ->select('subjects.id')
                ->firstOrFail();

            $document = DB::table('curriculum_documents')
                ->where('sha256', '1921b9d044f23849c1532f403021e0a8d23a46123d4b225b8b083b827e3fdd1c')
                ->firstOrFail();

            $themeId = DB::table('integrated_themes')->where([
                'subject_id' => $subject->id,
                'slug' => 'sets-and-logic',
            ])->value('id');

            if (! $themeId) {
                $themeId = DB::table('integrated_themes')->insertGetId([
                    'subject_id' => $subject->id,
                    'name' => 'Sets and Logic',
                    'slug' => 'sets-and-logic',
                    'description' => 'Classify objects and numbers, then describe relationships between sets.',
                    'order' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $unitId = DB::table('units')->where([
                'integrated_theme_id' => $themeId,
                'slug' => 'sets-and-logic-class-4',
            ])->value('id');

            if (! $unitId) {
                $unitId = DB::table('units')->insertGetId([
                    'integrated_theme_id' => $themeId,
                    'name' => 'Sets and Logic',
                    'slug' => 'sets-and-logic-class-4',
                    'description' => 'Equal and equivalent sets, intersection and union of sets.',
                    'summary' => 'Use set symbols and diagrams to classify elements and solve problems.',
                    'order' => 1,
                    'estimated_weeks' => 2,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $lessonId = DB::table('lessons')->where([
                'unit_id' => $unitId,
                'slug' => 'intersection-and-union-of-sets',
            ])->value('id');

            if (! $lessonId) {
                $lessonId = DB::table('lessons')->insertGetId([
                    'unit_id' => $unitId,
                    'name' => 'Intersection and Union of Sets',
                    'slug' => 'intersection-and-union-of-sets',
                    'description' => 'Place elements in Venn diagrams and identify intersections and unions.',
                    'content' => 'An intersection contains elements shared by both sets. A union contains every element from both sets.',
                    'order' => 1,
                    'estimated_minutes' => 30,
                    'type' => 'mathematics',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $competencyId = DB::table('school_competencies')->where([
                'subject_id' => $subject->id,
                'name' => 'Describe intersection and union of sets',
            ])->value('id');

            $competencyValues = [
                'curriculum_document_id' => $document->id,
                'source_pages' => '50',
                'verification_status' => 'verified_source',
                'description' => 'Distinguish equal and equivalent sets, describe intersection and union, and use set symbols and diagrams.',
                'order' => 1,
                'is_active' => true,
                'updated_at' => now(),
            ];

            if ($competencyId) {
                DB::table('school_competencies')->where('id', $competencyId)->update($competencyValues);
            } else {
                $competencyId = DB::table('school_competencies')->insertGetId($competencyValues + [
                    'subject_id' => $subject->id,
                    'name' => 'Describe intersection and union of sets',
                    'created_at' => now(),
                ]);
            }

            $contents = [
                3104 => [
                    'title' => 'Odd numbers and numbers less than 10',
                    'instructions' => 'Place each number in the correct part of the Venn diagram.',
                    'content' => [
                        'type' => 'venn_diagram',
                        'question' => 'Set A = numbers less than 10. Set B = odd numbers. Place each number correctly.',
                        'labelA' => 'Less than 10',
                        'labelB' => 'Odd numbers',
                        'setA' => ['2', '4', '6', '8'],
                        'setB' => ['11', '13'],
                        'intersection' => ['1', '3', '5', '7', '9'],
                        'items' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '11', '13'],
                    ],
                ],
                3105 => [
                    'title' => 'Numbers less than 5 and even numbers',
                    'instructions' => 'Place each number in the correct part of the Venn diagram.',
                    'content' => [
                        'type' => 'venn_diagram',
                        'question' => 'Set A = numbers less than 5. Set B = even numbers. Place each number correctly.',
                        'labelA' => 'Less than 5',
                        'labelB' => 'Even numbers',
                        'setA' => ['1', '3'],
                        'setB' => ['6', '8', '10'],
                        'intersection' => ['2', '4'],
                        'items' => ['1', '2', '3', '4', '6', '8', '10'],
                    ],
                ],
                3106 => [
                    'title' => 'Factors of 12 and even numbers below 10',
                    'instructions' => 'Place each number in the correct part of the Venn diagram.',
                    'content' => [
                        'type' => 'venn_diagram',
                        'question' => 'Set A = factors of 12. Set B = even numbers below 10. Place each number correctly.',
                        'labelA' => 'Factors of 12',
                        'labelB' => 'Even and below 10',
                        'setA' => ['1', '3', '12'],
                        'setB' => ['8'],
                        'intersection' => ['2', '4', '6'],
                        'items' => ['1', '2', '3', '4', '6', '8', '12'],
                    ],
                ],
            ];

            foreach ($contents as $exerciseId => $exercise) {
                DB::table('exercises')->where('id', $exerciseId)->update([
                    'lesson_id' => $lessonId,
                    'title' => $exercise['title'],
                    'instructions' => $exercise['instructions'],
                    'category' => 'mathematics',
                    'content' => json_encode($exercise['content'], JSON_UNESCAPED_UNICODE),
                    'is_active' => true,
                    'updated_at' => now(),
                ]);

                DB::table('exercise_school_competency')->updateOrInsert([
                    'exercise_id' => $exerciseId,
                    'school_competency_id' => $competencyId,
                ]);
            }
        });
    }
}
