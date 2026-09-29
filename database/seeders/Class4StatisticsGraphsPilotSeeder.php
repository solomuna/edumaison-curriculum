<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4StatisticsGraphsPilotSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $subjectId = DB::table('subjects')
                ->join('levels', 'subjects.level_id', '=', 'levels.id')
                ->where('levels.name', 'Class 4')
                ->where('levels.education_subsystem', 'anglophone')
                ->where('subjects.name', 'Mathematics')
                ->value('subjects.id');

            $lessonId = DB::table('lessons')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('integrated_themes.subject_id', $subjectId)
                ->where('integrated_themes.slug', 'statistics-and-graphs')
                ->where('lessons.slug', 'statistics-and-graphs')
                ->value('lessons.id');

            $documentId = DB::table('curriculum_documents')
                ->where('sha256', '1921b9d044f23849c1532f403021e0a8d23a46123d4b225b8b083b827e3fdd1c')
                ->value('id');

            if (! $subjectId || ! $lessonId || ! $documentId) {
                throw new \RuntimeException('Class 4 Statistics and Graphs foundation is incomplete.');
            }

            $competencyName = 'Represent and interpret data on graphs and grids';
            $competencyId = DB::table('school_competencies')->where([
                'subject_id' => $subjectId,
                'name' => $competencyName,
            ])->value('id');

            $competencyValues = [
                'curriculum_document_id' => $documentId,
                'source_pages' => '52',
                'verification_status' => 'verified_source',
                'description' => 'Collect, rank and tally data, locate points on a grid, and read or interpret graphs.',
                'order' => 5,
                'is_active' => true,
                'updated_at' => now(),
            ];

            if ($competencyId) {
                DB::table('school_competencies')->where('id', $competencyId)->update($competencyValues);
            } else {
                $competencyId = DB::table('school_competencies')->insertGetId($competencyValues + [
                    'subject_id' => $subjectId,
                    'name' => $competencyName,
                    'created_at' => now(),
                ]);
            }

            $exercises = [
                [
                    'title' => 'Count tally marks',
                    'instructions' => 'Count the tally marks and choose the correct total.',
                    'content' => ['type' => 'mcq', 'questions' => [[
                        'text' => 'A class records votes as ||||/ |||. How many votes are shown?',
                        'options' => ['5', '7', '8', '10'], 'answer' => 2,
                        'explanation' => 'The crossed group represents 5 tallies, then 3 more: 5 + 3 = 8.',
                    ]]],
                ],
                [
                    'title' => 'Choose the correct tally',
                    'instructions' => 'Choose the tally representation for the given number.',
                    'content' => ['type' => 'mcq', 'questions' => [[
                        'text' => 'Which tally correctly represents 12 objects?',
                        'options' => ['||||/ ||||/ ||', '||||/ ||||', '||||/ |||', '||||/ ||||/ ||||/'], 'answer' => 0,
                        'explanation' => 'Two complete groups of 5 and 2 extra marks make 12.',
                    ]]],
                ],
                [
                    'title' => 'Rank collected data',
                    'instructions' => 'Arrange the data from the smallest value to the largest.',
                    'content' => ['type' => 'mcq', 'questions' => [[
                        'text' => 'Four groups collected 18, 7, 25 and 12 bottle tops. Which is the ascending order?',
                        'options' => ['25, 18, 12, 7', '7, 12, 18, 25', '7, 18, 12, 25', '12, 7, 18, 25'], 'answer' => 1,
                        'explanation' => 'Ascending means from the smallest value to the largest: 7, 12, 18, 25.',
                    ]]],
                ],
                [
                    'title' => 'Read a pictograph',
                    'instructions' => 'Use the key to interpret the pictograph.',
                    'content' => ['type' => 'mcq', 'questions' => [[
                        'text' => 'Key: ● = 2 books. Amina has ● ● ● ●. How many books does she have?',
                        'options' => ['4', '6', '8', '10'], 'answer' => 2,
                        'explanation' => 'There are 4 symbols and each symbol represents 2 books: 4 × 2 = 8.',
                    ]]],
                ],
                [
                    'title' => 'Locate a point on a grid',
                    'instructions' => 'Read the horizontal coordinate first, then the vertical coordinate.',
                    'content' => ['type' => 'mcq', 'questions' => [[
                        'text' => 'A tree is at grid point (3, 2). Which direction do you read first?',
                        'options' => ['3 steps across', '2 steps across', '3 steps up', '2 steps down'], 'answer' => 0,
                        'explanation' => 'In (3, 2), read 3 across first, then 2 up.',
                    ]]],
                ],
                [
                    'title' => 'Plan a data collection',
                    'instructions' => 'Choose the most suitable way to collect class data.',
                    'content' => ['type' => 'mcq', 'questions' => [[
                        'text' => 'You want to know the favourite fruit of every learner in your class. What should you do first?',
                        'options' => ['Guess the answer', 'Ask each learner and tally the answers', 'Ask only one friend', 'Copy another class'], 'answer' => 1,
                        'explanation' => 'Ask every learner the same question and tally each answer to collect reliable class data.',
                    ]]],
                ],
            ];

            $exerciseIds = [];
            foreach ($exercises as $index => $exercise) {
                $id = DB::table('exercises')->where([
                    'lesson_id' => $lessonId,
                    'title' => $exercise['title'],
                ])->value('id');
                $values = [
                    'instructions' => $exercise['instructions'],
                    'category' => 'mathematics',
                    'difficulty' => $index < 2 ? 'easy' : 'medium',
                    'estimated_minutes' => 4,
                    'content' => json_encode($exercise['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'is_active' => true,
                    'updated_at' => now(),
                ];
                if ($id) {
                    DB::table('exercises')->where('id', $id)->update($values);
                } else {
                    $id = DB::table('exercises')->insertGetId($values + [
                        'lesson_id' => $lessonId,
                        'title' => $exercise['title'],
                        'created_at' => now(),
                    ]);
                }
                $exerciseIds[] = $id;
            }

            $existingGraphId = DB::table('exercises')->where('id', 1253)->where('is_active', true)->value('id');
            if ($existingGraphId) {
                $exerciseIds[] = $existingGraphId;
            }

            foreach ($exerciseIds as $exerciseId) {
                DB::table('exercise_school_competency')->updateOrInsert([
                    'exercise_id' => $exerciseId,
                    'school_competency_id' => $competencyId,
                ]);
            }
        });
    }
}
