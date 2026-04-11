<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MissingUnitsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $structure = [

            // ══════════════════════════════════════════════════════════════
            // CLASS 2 — Unit 10 : Our Environment (theme 8, English C2)
            // ══════════════════════════════════════════════════════════════
            [
                'unit_id' => 10,
                'lessons' => [
                    [
                        'name'  => 'The World Around Us',
                        'slug'  => 'world-around-us-c2',
                        'type'  => 'listening',
                        'order' => 1,
                        'exercises' => [
                            [
                                'title'             => 'Environment Words Drill',
                                'instructions'      => 'Listen and repeat each word about our environment.',
                                'category'          => 'oral_drill',
                                'difficulty'        => 'easy',
                                'estimated_minutes' => 8,
                                'content'           => json_encode([
                                    'type'  => 'oral_drill',
                                    'items' => [
                                        ['text' => 'forest',  'audio_hint' => 'forest'],
                                        ['text' => 'river',   'audio_hint' => 'river'],
                                        ['text' => 'mountain','audio_hint' => 'mountain'],
                                        ['text' => 'farm',    'audio_hint' => 'farm'],
                                        ['text' => 'village', 'audio_hint' => 'village'],
                                        ['text' => 'garden',  'audio_hint' => 'garden'],
                                        ['text' => 'sky',     'audio_hint' => 'sky'],
                                        ['text' => 'soil',    'audio_hint' => 'soil'],
                                    ],
                                ]),
                            ],
                            [
                                'title'             => 'Our Environment Quiz',
                                'instructions'      => 'Choose the correct answer.',
                                'category'          => 'quiz',
                                'difficulty'        => 'easy',
                                'estimated_minutes' => 10,
                                'content'           => json_encode([
                                    'type'      => 'mcq',
                                    'questions' => [
                                        ['text' => 'Where do fish live?',                'options' => ['forest', 'river', 'mountain', 'sky'],          'answer' => 1],
                                        ['text' => 'What do trees give us?',             'options' => ['stones', 'water', 'oxygen', 'fire'],            'answer' => 2],
                                        ['text' => 'Which is NOT part of nature?',       'options' => ['tree', 'river', 'car', 'mountain'],             'answer' => 2],
                                        ['text' => 'Where do farmers grow food?',        'options' => ['river', 'sky', 'farm', 'road'],                 'answer' => 2],
                                        ['text' => 'What should we NOT do to rivers?',   'options' => ['swim in them', 'drink from them', 'throw rubbish in them', 'fish in them'], 'answer' => 2],
                                    ],
                                ]),
                            ],
                        ],
                    ],
                    [
                        'name'  => 'Caring for Our Environment',
                        'slug'  => 'caring-environment-c2',
                        'type'  => 'speaking',
                        'order' => 2,
                        'exercises' => [
                            [
                                'title'             => 'Good and Bad Actions',
                                'instructions'      => 'Fill in the blank with the correct word.',
                                'category'          => 'revision',
                                'difficulty'        => 'easy',
                                'estimated_minutes' => 10,
                                'content'           => json_encode([
                                    'type'      => 'fill_in',
                                    'sentences' => [
                                        ['text' => 'We should ___ trees to keep the environment clean.',  'answer' => 'plant'],
                                        ['text' => 'We should not ___ rubbish in the river.',            'answer' => 'throw'],
                                        ['text' => 'We ___ water so we do not waste it.',                'answer' => 'save'],
                                        ['text' => 'Animals live in a ___ in the forest.',               'answer' => 'habitat'],
                                        ['text' => 'The sun, wind and rain are parts of our ___.',       'answer' => 'environment'],
                                    ],
                                ]),
                            ],
                        ],
                    ],
                ],
            ],

            // ══════════════════════════════════════════════════════════════
            // CLASS 2 — Unit 11 : How We Travel (theme 9, English C2)
            // ══════════════════════════════════════════════════════════════
            [
                'unit_id' => 11,
                'lessons' => [
                    [
                        'name'  => 'Transport Vocabulary',
                        'slug'  => 'transport-vocab-c2',
                        'type'  => 'listening',
                        'order' => 1,
                        'exercises' => [
                            [
                                'title'             => 'Transport Words Drill',
                                'instructions'      => 'Listen and repeat each transport word.',
                                'category'          => 'oral_drill',
                                'difficulty'        => 'easy',
                                'estimated_minutes' => 8,
                                'content'           => json_encode([
                                    'type'  => 'oral_drill',
                                    'items' => [
                                        ['text' => 'car',       'audio_hint' => 'car'],
                                        ['text' => 'bus',       'audio_hint' => 'bus'],
                                        ['text' => 'bicycle',   'audio_hint' => 'bicycle'],
                                        ['text' => 'motorbike', 'audio_hint' => 'motorbike'],
                                        ['text' => 'aeroplane', 'audio_hint' => 'aeroplane'],
                                        ['text' => 'boat',      'audio_hint' => 'boat'],
                                        ['text' => 'train',     'audio_hint' => 'train'],
                                        ['text' => 'canoe',     'audio_hint' => 'canoe'],
                                    ],
                                ]),
                            ],
                            [
                                'title'             => 'Transport Quiz',
                                'instructions'      => 'Choose the correct answer.',
                                'category'          => 'quiz',
                                'difficulty'        => 'easy',
                                'estimated_minutes' => 10,
                                'content'           => json_encode([
                                    'type'      => 'mcq',
                                    'questions' => [
                                        ['text' => 'Which transport flies in the sky?',       'options' => ['boat', 'bus', 'aeroplane', 'bicycle'],      'answer' => 2],
                                        ['text' => 'Which transport moves on water?',         'options' => ['car', 'boat', 'motorbike', 'bicycle'],       'answer' => 1],
                                        ['text' => 'Which transport has two wheels?',         'options' => ['bus', 'car', 'bicycle', 'train'],            'answer' => 2],
                                        ['text' => 'What do we call the person who drives a bus?', 'options' => ['pilot', 'captain', 'driver', 'rider'], 'answer' => 2],
                                        ['text' => 'Which is the safest way to cross the road?', 'options' => ['run fast', 'use a pedestrian crossing', 'close your eyes', 'jump'], 'answer' => 1],
                                    ],
                                ]),
                            ],
                        ],
                    ],
                    [
                        'name'  => 'Road Safety',
                        'slug'  => 'road-safety-c2',
                        'type'  => 'reading',
                        'order' => 2,
                        'exercises' => [
                            [
                                'title'             => 'Road Safety Fill-in',
                                'instructions'      => 'Complete each sentence about road safety.',
                                'category'          => 'revision',
                                'difficulty'        => 'easy',
                                'estimated_minutes' => 10,
                                'content'           => json_encode([
                                    'type'      => 'fill_in',
                                    'sentences' => [
                                        ['text' => 'Always ___ left and right before crossing the road.', 'answer' => 'look'],
                                        ['text' => 'We cross the road at a ___ crossing.',               'answer' => 'pedestrian'],
                                        ['text' => 'A red traffic light means ___.',                     'answer' => 'stop'],
                                        ['text' => 'A green traffic light means ___.',                   'answer' => 'go'],
                                        ['text' => 'We should wear a ___ when riding a bicycle.',        'answer' => 'helmet'],
                                    ],
                                ]),
                            ],
                        ],
                    ],
                ],
            ],

            // ══════════════════════════════════════════════════════════════
            // CLASS 3 — Unit 34 : Numbers to 1000 (theme 28, Maths C3)
            // ══════════════════════════════════════════════════════════════
            [
                'unit_id' => 34,
                'lessons' => [
                    [
                        'name'  => 'Counting to 1000',
                        'slug'  => 'counting-1000-c3',
                        'type'  => 'mathematics',
                        'order' => 1,
                        'exercises' => [
                            [
                                'title'             => 'Place Value Drill',
                                'instructions'      => 'Listen and repeat — place value words.',
                                'category'          => 'oral_drill',
                                'difficulty'        => 'easy',
                                'estimated_minutes' => 6,
                                'content'           => json_encode([
                                    'type'  => 'oral_drill',
                                    'items' => [
                                        ['text' => 'hundreds — the third digit from the right', 'audio_hint' => 'hundreds'],
                                        ['text' => 'tens — the second digit from the right',    'audio_hint' => 'tens'],
                                        ['text' => 'units — the first digit from the right',    'audio_hint' => 'units'],
                                        ['text' => '365 — three hundred and sixty-five',         'audio_hint' => 'three-sixty-five'],
                                        ['text' => '708 — seven hundred and eight',              'audio_hint' => 'seven-oh-eight'],
                                        ['text' => '1000 — one thousand',                        'audio_hint' => 'one-thousand'],
                                    ],
                                ]),
                            ],
                            [
                                'title'             => 'Numbers to 1000 Quiz',
                                'instructions'      => 'Choose the correct answer.',
                                'category'          => 'quiz',
                                'difficulty'        => 'medium',
                                'estimated_minutes' => 12,
                                'content'           => json_encode([
                                    'type'      => 'mcq',
                                    'questions' => [
                                        ['text' => 'What is the value of the digit 4 in 403?',       'options' => ['4', '40', '400', '4000'],         'answer' => 2],
                                        ['text' => 'Write 600 + 50 + 7 as a number.',                'options' => ['567', '657', '675', '765'],         'answer' => 1],
                                        ['text' => 'Which number comes between 299 and 301?',        'options' => ['298', '300', '302', '310'],         'answer' => 1],
                                        ['text' => 'Round 374 to the nearest hundred.',              'options' => ['300', '370', '400', '380'],         'answer' => 0],
                                        ['text' => 'What is 500 + 200 + 60 + 3?',                   'options' => ['753', '763', '756', '736'],         'answer' => 1],
                                    ],
                                ]),
                            ],
                            [
                                'title'             => 'Number Writing',
                                'instructions'      => 'Fill in the missing number.',
                                'category'          => 'revision',
                                'difficulty'        => 'medium',
                                'estimated_minutes' => 10,
                                'content'           => json_encode([
                                    'type'      => 'fill_in',
                                    'sentences' => [
                                        ['text' => '400 + 30 + 5 = ___',       'answer' => '435'],
                                        ['text' => '200 + ___ + 9 = 289',      'answer' => '80'],
                                        ['text' => '___ hundred and forty = 640', 'answer' => 'six'],
                                        ['text' => '999 + 1 = ___',            'answer' => '1000'],
                                        ['text' => 'Half of 1000 = ___',       'answer' => '500'],
                                    ],
                                ]),
                            ],
                        ],
                    ],
                ],
            ],

            // ══════════════════════════════════════════════════════════════
            // CLASS 4 — Unit 36 : Geometry (theme 33, Maths C4)
            // ══════════════════════════════════════════════════════════════
            [
                'unit_id' => 36,
                'lessons' => [
                    [
                        'name'  => 'Shapes and Angles',
                        'slug'  => 'shapes-angles-c4',
                        'type'  => 'mathematics',
                        'order' => 1,
                        'exercises' => [
                            [
                                'title'             => 'Geometry Vocabulary',
                                'instructions'      => 'Listen and repeat these geometry words.',
                                'category'          => 'oral_drill',
                                'difficulty'        => 'easy',
                                'estimated_minutes' => 6,
                                'content'           => json_encode([
                                    'type'  => 'oral_drill',
                                    'items' => [
                                        ['text' => 'angle — the space between two lines that meet',  'audio_hint' => 'angle'],
                                        ['text' => 'right angle — an angle of exactly 90 degrees',  'audio_hint' => 'right-angle'],
                                        ['text' => 'perimeter — the distance around a shape',        'audio_hint' => 'perimeter'],
                                        ['text' => 'area — the space inside a shape',                'audio_hint' => 'area'],
                                        ['text' => 'parallel — lines that never meet',               'audio_hint' => 'parallel'],
                                        ['text' => 'symmetry — equal halves on both sides',          'audio_hint' => 'symmetry'],
                                    ],
                                ]),
                            ],
                            [
                                'title'             => 'Shapes Quiz',
                                'instructions'      => 'Choose the correct answer about shapes and angles.',
                                'category'          => 'quiz',
                                'difficulty'        => 'medium',
                                'estimated_minutes' => 12,
                                'content'           => json_encode([
                                    'type'      => 'mcq',
                                    'questions' => [
                                        ['text' => 'How many sides does a hexagon have?',             'options' => ['4', '5', '6', '7'],                'answer' => 2],
                                        ['text' => 'What is the perimeter of a square with side 5 cm?', 'options' => ['10 cm', '15 cm', '20 cm', '25 cm'], 'answer' => 2],
                                        ['text' => 'A right angle measures exactly:',                 'options' => ['45°', '60°', '90°', '180°'],       'answer' => 2],
                                        ['text' => 'The area of a rectangle 6 cm × 4 cm is:',        'options' => ['20 cm²', '24 cm²', '28 cm²', '10 cm²'], 'answer' => 1],
                                        ['text' => 'Which shape has no corners?',                     'options' => ['square', 'triangle', 'circle', 'rectangle'], 'answer' => 2],
                                    ],
                                ]),
                            ],
                            [
                                'title'             => 'Perimeter and Area',
                                'instructions'      => 'Calculate and fill in the answer.',
                                'category'          => 'revision',
                                'difficulty'        => 'hard',
                                'estimated_minutes' => 15,
                                'content'           => json_encode([
                                    'type'      => 'fill_in',
                                    'sentences' => [
                                        ['text' => 'Perimeter of a square with side 8 cm = ___ cm',      'answer' => '32'],
                                        ['text' => 'Area of a rectangle 7 cm × 3 cm = ___ cm²',         'answer' => '21'],
                                        ['text' => 'A triangle has ___ sides.',                          'answer' => '3'],
                                        ['text' => 'Area of a square with side 9 cm = ___ cm²',         'answer' => '81'],
                                        ['text' => 'Perimeter of a rectangle 10 cm × 4 cm = ___ cm',    'answer' => '28'],
                                    ],
                                ]),
                            ],
                        ],
                    ],
                ],
            ],

            // ══════════════════════════════════════════════════════════════
            // CLASS 4 — Unit 37 : Large Numbers (theme 31, Maths C4)
            // ══════════════════════════════════════════════════════════════
            [
                'unit_id' => 37,
                'lessons' => [
                    [
                        'name'  => 'Numbers to 100 000',
                        'slug'  => 'numbers-100000-c4',
                        'type'  => 'mathematics',
                        'order' => 1,
                        'exercises' => [
                            [
                                'title'             => 'Large Numbers Drill',
                                'instructions'      => 'Listen and repeat these large numbers.',
                                'category'          => 'oral_drill',
                                'difficulty'        => 'easy',
                                'estimated_minutes' => 6,
                                'content'           => json_encode([
                                    'type'  => 'oral_drill',
                                    'items' => [
                                        ['text' => '10 000 — ten thousand',            'audio_hint' => 'ten-thousand'],
                                        ['text' => '25 000 — twenty-five thousand',    'audio_hint' => 'twenty-five-thousand'],
                                        ['text' => '50 000 — fifty thousand',          'audio_hint' => 'fifty-thousand'],
                                        ['text' => '100 000 — one hundred thousand',   'audio_hint' => 'hundred-thousand'],
                                        ['text' => '1 000 000 — one million',          'audio_hint' => 'one-million'],
                                    ],
                                ]),
                            ],
                            [
                                'title'             => 'Large Numbers Quiz',
                                'instructions'      => 'Choose the correct answer.',
                                'category'          => 'quiz',
                                'difficulty'        => 'medium',
                                'estimated_minutes' => 12,
                                'content'           => json_encode([
                                    'type'      => 'mcq',
                                    'questions' => [
                                        ['text' => 'What is the value of the digit 3 in 35 742?',     'options' => ['3', '300', '3000', '30 000'],       'answer' => 3],
                                        ['text' => 'Write in figures: forty-seven thousand, two hundred and six.', 'options' => ['4 726', '47 206', '47 260', '470 206'], 'answer' => 1],
                                        ['text' => 'Round 64 380 to the nearest thousand.',           'options' => ['60 000', '64 000', '65 000', '70 000'], 'answer' => 1],
                                        ['text' => 'Which is the largest number?',                    'options' => ['8 999', '9 001', '10 000', '9 999'], 'answer' => 2],
                                        ['text' => '50 000 + 6 000 + 400 + 20 + 3 = ?',             'options' => ['56 423', '56 234', '54 623', '56 243'], 'answer' => 0],
                                    ],
                                ]),
                            ],
                            [
                                'title'             => 'Large Numbers Fill-in',
                                'instructions'      => 'Fill in the missing value.',
                                'category'          => 'revision',
                                'difficulty'        => 'medium',
                                'estimated_minutes' => 12,
                                'content'           => json_encode([
                                    'type'      => 'fill_in',
                                    'sentences' => [
                                        ['text' => '20 000 + 5 000 + 300 + 40 + 7 = ___',         'answer' => '25347'],
                                        ['text' => 'The digit 8 in 18 456 stands for ___.',        'answer' => '8000'],
                                        ['text' => '99 999 + 1 = ___',                             'answer' => '100000'],
                                        ['text' => 'Half of 10 000 = ___',                         'answer' => '5000'],
                                        ['text' => '45 000 rounded to nearest 10 000 = ___',       'answer' => '50000'],
                                    ],
                                ]),
                            ],
                        ],
                    ],
                ],
            ],

            // ══════════════════════════════════════════════════════════════
            // CLASS 5 — Unit 40 : Problem Solving (theme 36, Maths C5)
            // ══════════════════════════════════════════════════════════════
            [
                'unit_id' => 40,
                'lessons' => [
                    [
                        'name'  => 'Word Problems',
                        'slug'  => 'word-problems-c5',
                        'type'  => 'mathematics',
                        'order' => 1,
                        'exercises' => [
                            [
                                'title'             => 'Problem Solving Steps',
                                'instructions'      => 'Listen and repeat the steps for solving word problems.',
                                'category'          => 'oral_drill',
                                'difficulty'        => 'easy',
                                'estimated_minutes' => 6,
                                'content'           => json_encode([
                                    'type'  => 'oral_drill',
                                    'items' => [
                                        ['text' => 'Step 1 — Read the problem carefully',           'audio_hint' => 'step-one'],
                                        ['text' => 'Step 2 — Identify what is given',               'audio_hint' => 'step-two'],
                                        ['text' => 'Step 3 — Identify what is asked',               'audio_hint' => 'step-three'],
                                        ['text' => 'Step 4 — Choose the right operation',           'audio_hint' => 'step-four'],
                                        ['text' => 'Step 5 — Solve and check your answer',          'audio_hint' => 'step-five'],
                                    ],
                                ]),
                            ],
                            [
                                'title'             => 'Word Problems Quiz',
                                'instructions'      => 'Read each problem and choose the correct answer.',
                                'category'          => 'quiz',
                                'difficulty'        => 'hard',
                                'estimated_minutes' => 18,
                                'content'           => json_encode([
                                    'type'      => 'mcq',
                                    'questions' => [
                                        ['text' => 'A farmer has 1 250 mangoes. He sells 480. How many remain?',               'options' => ['770', '780', '680', '870'],          'answer' => 0],
                                        ['text' => 'A school has 45 classes with 38 pupils each. Total pupils?',               'options' => ['1 620', '1 710', '1 620', '1 800'],  'answer' => 1],
                                        ['text' => 'A bag of rice costs 3 500 FCFA. What do 6 bags cost?',                     'options' => ['18 000 FCFA', '21 000 FCFA', '20 500 FCFA', '19 000 FCFA'], 'answer' => 1],
                                        ['text' => 'Mum shared 2 400 FCFA equally among her 4 children. Each child got:',     'options' => ['500 FCFA', '600 FCFA', '700 FCFA', '800 FCFA'], 'answer' => 1],
                                        ['text' => 'A tank had 500 litres. 175 litres were used. 65 litres added. Now it has:','options' => ['320 litres', '360 litres', '390 litres', '400 litres'], 'answer' => 2],
                                        ['text' => 'Mark scored 72%, 65% and 81% in three tests. His average score is:',      'options' => ['70%', '71%', '72%', '73%'],          'answer' => 2],
                                    ],
                                ]),
                            ],
                            [
                                'title'             => 'Problem Solving Fill-in',
                                'instructions'      => 'Write the correct answer for each word problem.',
                                'category'          => 'revision',
                                'difficulty'        => 'hard',
                                'estimated_minutes' => 15,
                                'content'           => json_encode([
                                    'type'      => 'fill_in',
                                    'sentences' => [
                                        ['text' => 'A book costs 1 200 FCFA. Paul has 5 000 FCFA. His change = ___ FCFA.',   'answer' => '3800'],
                                        ['text' => 'There are 360 pupils. 45% are boys. Number of boys = ___.',              'answer' => '162'],
                                        ['text' => 'A rectangle is 12 cm long and 7 cm wide. Its area = ___ cm².',          'answer' => '84'],
                                        ['text' => 'Train leaves at 08:45 and arrives at 11:20. Journey time = ___ hours ___ minutes.', 'answer' => '2 hours 35 minutes'],
                                        ['text' => 'Share 3 600 FCFA in ratio 1:2. The larger share = ___ FCFA.',           'answer' => '2400'],
                                    ],
                                ]),
                            ],
                        ],
                    ],
                ],
            ],

        ];

        // ══════════════════════════════════════════════════════════════════
        // INSERT
        // ══════════════════════════════════════════════════════════════════
        $totalLessons   = 0;
        $totalExercises = 0;

        foreach ($structure as $unitData) {
            $unitId  = $unitData['unit_id'];
            $lessons = $unitData['lessons'];

            foreach ($lessons as $lessonData) {
                $exercises = $lessonData['exercises'];
                unset($lessonData['exercises']);

                $existing = DB::table('lessons')->where('slug', $lessonData['slug'])->first();
                if ($existing) {
                    $lessonId = $existing->id;
                    $this->command->info("Lesson exists: {$lessonData['name']}");
                } else {
                    $lessonId = DB::table('lessons')->insertGetId(array_merge($lessonData, [
                        'unit_id'           => $unitId,
                        'description'       => null,
                        'content'           => null,
                        'estimated_minutes' => collect($exercises)->sum('estimated_minutes'),
                        'is_active'         => true,
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ]));
                    $this->command->info("Lesson created: {$lessonData['name']} (id={$lessonId})");
                    $totalLessons++;
                }

                foreach ($exercises as $ex) {
                    $exExists = DB::table('exercises')
                        ->where('lesson_id', $lessonId)
                        ->where('title', $ex['title'])
                        ->exists();

                    if (!$exExists) {
                        DB::table('exercises')->insert(array_merge($ex, [
                            'lesson_id'     => $lessonId,
                            'home_skill_id' => null,
                            'is_active'     => true,
                            'created_at'    => $now,
                            'updated_at'    => $now,
                        ]));
                        $totalExercises++;
                    }
                }
            }
        }

        $this->command->info("═══════════════════════════════════════════════");
        $this->command->info("Missing units seeded: {$totalLessons} lessons, {$totalExercises} exercises.");
        $this->command->info("═══════════════════════════════════════════════");
    }
}
