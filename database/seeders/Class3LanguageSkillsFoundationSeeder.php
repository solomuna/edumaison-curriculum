<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class3LanguageSkillsFoundationSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $englishSubjectId = DB::table('subjects')
                ->join('levels', 'subjects.level_id', '=', 'levels.id')
                ->where('levels.name', 'Class 3')
                ->where('levels.education_subsystem', 'anglophone')
                ->where('subjects.name', 'English')
                ->value('subjects.id');
            if (! $englishSubjectId) {
                throw new \RuntimeException('Class 3 English was not found.');
            }

            $writingLessonId = DB::table('lessons')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
                ->where('lessons.id', 385)
                ->where('integrated_themes.subject_id', $englishSubjectId)
                ->value('lessons.id');
            $readingLessonId = DB::table('exercises')->where('id', 2367)->value('lesson_id');
            if (! $writingLessonId || ! $readingLessonId) {
                throw new \RuntimeException('The Class 3 language target lessons were not found.');
            }

            if (DB::table('exercise_attempts')->where('exercise_id', 1657)->exists()) {
                throw new \RuntimeException('Reading exercise 1657 now has attempts; its missing passage requires manual review.');
            }
            DB::table('exercises')->where('id', 1657)->update([
                'title' => 'Ambe Goes to the Market',
                'instructions' => 'Read the passage and answer the question.',
                'category' => 'reading',
                'content' => json_encode([
                    'type' => 'multiple_choice',
                    'passage' => 'Ambe went to the market with his mother. They bought tomatoes, onions and fish. Ambe carried the small basket on their way home.',
                    'questions' => [[
                        'question' => 'Did Ambe go to the market alone?',
                        'text' => 'Did Ambe go to the market alone?',
                        'options' => ['Yes, he went alone', 'No, he went with his mother'],
                        'answer' => 1,
                    ]],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'updated_at' => now(),
            ]);

            $exercises = [
                [
                    'lesson_id' => $readingLessonId,
                    'title' => 'Reading Comprehension: The School Garden',
                    'instructions' => 'Read the passage carefully and answer every question.',
                    'category' => 'reading',
                    'difficulty' => 'easy',
                    'estimated_minutes' => 8,
                    'content' => [
                        'type' => 'multiple_choice',
                        'passage' => 'On Saturday morning, Neba and his classmates worked in the school garden. They removed weeds, watered the tomato plants and planted bean seeds. Their teacher showed them how to leave enough space between the seeds. Before going home, the children cleaned the tools and stored them in a dry room.',
                        'questions' => [
                            ['question' => 'When did the children work in the garden?', 'options' => ['Friday evening', 'Saturday morning', 'Sunday afternoon', 'Monday morning'], 'answer' => 1],
                            ['question' => 'Which seeds did they plant?', 'options' => ['Maize seeds', 'Pumpkin seeds', 'Bean seeds', 'Rice seeds'], 'answer' => 2],
                            ['question' => 'Why did the teacher ask them to leave space?', 'options' => ['So the seeds had room to grow', 'So the tools stayed clean', 'So they could go home early', 'So the weeds would grow'], 'answer' => 0],
                            ['question' => 'What did the children do last?', 'options' => ['Removed weeds', 'Watered tomatoes', 'Planted beans', 'Cleaned and stored the tools'], 'answer' => 3],
                        ],
                    ],
                ],
                [
                    'lesson_id' => $readingLessonId,
                    'title' => 'Reading Comprehension: The Rainy Journey',
                    'instructions' => 'Read the passage carefully and answer every question.',
                    'category' => 'reading',
                    'difficulty' => 'medium',
                    'estimated_minutes' => 8,
                    'content' => [
                        'type' => 'multiple_choice',
                        'passage' => 'Muna was walking home when dark clouds covered the sky. She opened her umbrella, but the wind turned it inside out. A shopkeeper invited her to wait under his veranda. After the heavy rain became a light drizzle, Muna thanked him and continued home carefully because the road was slippery.',
                        'questions' => [
                            ['question' => 'What happened to Muna\'s umbrella?', 'options' => ['She lost it', 'The wind turned it inside out', 'The rain tore it', 'She gave it away'], 'answer' => 1],
                            ['question' => 'Where did Muna wait?', 'options' => ['Under a tree', 'Inside a bus', 'Under a shop veranda', 'At school'], 'answer' => 2],
                            ['question' => 'What does drizzle mean in this passage?', 'options' => ['Very light rain', 'Strong wind', 'Bright sunshine', 'Thick dust'], 'answer' => 0],
                            ['question' => 'Why did Muna walk carefully?', 'options' => ['She was carrying glass', 'It was already dark', 'The road was slippery', 'She had no shoes'], 'answer' => 2],
                        ],
                    ],
                ],
                [
                    'lesson_id' => $writingLessonId,
                    'title' => 'Dictation: Everyday Sentences',
                    'instructions' => 'Listen carefully and write each sentence exactly as you hear it.',
                    'category' => 'listening',
                    'difficulty' => 'easy',
                    'estimated_minutes' => 8,
                    'content' => [
                        'type' => 'dictation',
                        'language' => 'en-GB',
                        'max_replays' => 3,
                        'items' => [
                            ['text' => 'The children walk to school every morning.'],
                            ['text' => 'My mother bought tomatoes at the market.'],
                            ['text' => 'We cleaned the classroom before the lesson.'],
                        ],
                    ],
                ],
                [
                    'lesson_id' => $writingLessonId,
                    'title' => 'Dictation: A Rainy Afternoon',
                    'instructions' => 'Listen carefully and write each sentence exactly as you hear it.',
                    'category' => 'listening',
                    'difficulty' => 'medium',
                    'estimated_minutes' => 10,
                    'content' => [
                        'type' => 'dictation',
                        'language' => 'en-GB',
                        'max_replays' => 3,
                        'items' => [
                            ['text' => 'Dark clouds gathered above our village.'],
                            ['text' => 'The rain filled the small river quickly.'],
                            ['text' => 'After the storm, we saw a bright rainbow.'],
                        ],
                    ],
                ],
                [
                    'lesson_id' => $writingLessonId,
                    'title' => 'Write About Your School Day',
                    'instructions' => 'Write a short paragraph in complete sentences.',
                    'category' => 'writing',
                    'difficulty' => 'easy',
                    'estimated_minutes' => 12,
                    'content' => [
                        'type' => 'written_response',
                        'prompt' => 'What do you do from the time you arrive at school until you return home?',
                        'min_words' => 30,
                        'checklist' => ['Use complete sentences.', 'Put the events in order.', 'Use capital letters and punctuation.'],
                    ],
                ],
                [
                    'lesson_id' => $writingLessonId,
                    'title' => 'Describe a Busy Market',
                    'instructions' => 'Write a clear description using complete sentences.',
                    'category' => 'writing',
                    'difficulty' => 'medium',
                    'estimated_minutes' => 12,
                    'content' => [
                        'type' => 'written_response',
                        'prompt' => 'Imagine a busy market. Describe what you can see, hear and smell.',
                        'min_words' => 35,
                        'checklist' => ['Describe at least three details.', 'Use suitable describing words.', 'Check spelling and punctuation.'],
                    ],
                ],
            ];

            $writingExerciseIds = [];
            foreach ($exercises as $exercise) {
                $id = DB::table('exercises')->where([
                    'lesson_id' => $exercise['lesson_id'],
                    'title' => $exercise['title'],
                ])->value('id');
                $values = [
                    'instructions' => $exercise['instructions'],
                    'category' => $exercise['category'],
                    'difficulty' => $exercise['difficulty'],
                    'estimated_minutes' => $exercise['estimated_minutes'],
                    'content' => json_encode($exercise['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'is_active' => true,
                    'updated_at' => now(),
                ];
                if ($id) {
                    DB::table('exercises')->where('id', $id)->update($values);
                } else {
                    $id = DB::table('exercises')->insertGetId($values + [
                        'lesson_id' => $exercise['lesson_id'],
                        'title' => $exercise['title'],
                        'created_at' => now(),
                    ]);
                }
                if (($exercise['content']['type'] ?? '') === 'written_response') {
                    $writingExerciseIds[] = $id;
                }
            }

            $writingCompetencyId = DB::table('school_competencies')
                ->where('subject_id', $englishSubjectId)
                ->where('name', 'Legible and coherent writing')
                ->value('id');
            if (! $writingCompetencyId) {
                throw new \RuntimeException('The verified Class 3 English writing competency was not found.');
            }
            foreach ($writingExerciseIds as $exerciseId) {
                DB::table('exercise_school_competency')->updateOrInsert([
                    'exercise_id' => $exerciseId,
                    'school_competency_id' => $writingCompetencyId,
                ]);
            }
        });
    }
}
