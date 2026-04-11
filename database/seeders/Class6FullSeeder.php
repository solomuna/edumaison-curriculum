<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Class6FullSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ══════════════════════════════════════════════════════════════════
        // STEP 1 — Create missing Maths C6 units (themes 37, 38, 39)
        // ══════════════════════════════════════════════════════════════════
        $mathsUnits = [
            ['integrated_theme_id' => 37, 'name' => 'Unit 4 - Advanced Numbers', 'slug' => 'advanced-numbers-c6', 'order' => 4, 'estimated_weeks' => 2],
            ['integrated_theme_id' => 38, 'name' => 'Unit 5 - Statistics',        'slug' => 'statistics-c6',        'order' => 5, 'estimated_weeks' => 2],
            ['integrated_theme_id' => 39, 'name' => 'Unit 6 - Algebra Intro',     'slug' => 'algebra-intro-c6',     'order' => 6, 'estimated_weeks' => 2],
        ];

        $unitIds = [
            'english' => ['geo' => 21, 'citizen' => 22, 'nature' => 23],
        ];

        foreach ($mathsUnits as $u) {
            $existing = DB::table('units')->where('slug', $u['slug'])->first();
            if (!$existing) {
                $id = DB::table('units')->insertGetId(array_merge($u, [
                    'description'    => null,
                    'is_active'      => true,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]));
                $this->command->info("Unit created: {$u['name']} (id={$id})");
            } else {
                $id = $existing->id;
                $this->command->info("Unit exists: {$u['name']} (id={$id})");
            }

            if ($u['integrated_theme_id'] === 37) $unitIds['maths']['advanced']   = $id;
            if ($u['integrated_theme_id'] === 38) $unitIds['maths']['statistics'] = $id;
            if ($u['integrated_theme_id'] === 39) $unitIds['maths']['algebra']    = $id;
        }

        // ══════════════════════════════════════════════════════════════════
        // STEP 2 — Create lessons + exercises
        // ══════════════════════════════════════════════════════════════════
        $structure = [

            // ── ENGLISH : Unit 1 — World Geography (unit_id=21) ──────────
            [
                'unit_id'    => $unitIds['english']['geo'],
                'name'       => 'Continents and Oceans',
                'slug'       => 'continents-oceans-c6',
                'type'       => 'listening',
                'order'      => 1,
                'exercises'  => [
                    [
                        'title'             => 'Continents Drill',
                        'instructions'      => 'Listen and repeat the name of each continent.',
                        'category'          => 'oral_drill',
                        'difficulty'        => 'easy',
                        'estimated_minutes' => 8,
                        'content'           => json_encode([
                            'type'  => 'oral_drill',
                            'items' => [
                                ['text' => 'Africa',        'audio_hint' => 'africa'],
                                ['text' => 'Asia',          'audio_hint' => 'asia'],
                                ['text' => 'Europe',        'audio_hint' => 'europe'],
                                ['text' => 'North America', 'audio_hint' => 'north-america'],
                                ['text' => 'South America', 'audio_hint' => 'south-america'],
                                ['text' => 'Australia',     'audio_hint' => 'australia'],
                                ['text' => 'Antarctica',    'audio_hint' => 'antarctica'],
                            ],
                        ]),
                    ],
                    [
                        'title'             => 'Continents and Oceans Quiz',
                        'instructions'      => 'Choose the correct answer for each question.',
                        'category'          => 'quiz',
                        'difficulty'        => 'medium',
                        'estimated_minutes' => 12,
                        'content'           => json_encode([
                            'type'      => 'mcq',
                            'questions' => [
                                ['text' => 'How many continents are there on Earth?',             'options' => ['5', '6', '7', '8'],                                                                              'answer' => 2],
                                ['text' => 'The largest ocean in the world is the:',              'options' => ['Indian Ocean', 'Atlantic Ocean', 'Arctic Ocean', 'Pacific Ocean'],                               'answer' => 3],
                                ['text' => 'Which is the smallest continent?',                    'options' => ['Europe', 'Australia', 'Antarctica', 'South America'],                                           'answer' => 1],
                                ['text' => 'Which continent is Cameroon located in?',             'options' => ['Asia', 'Europe', 'Africa', 'South America'],                                                    'answer' => 2],
                                ['text' => 'The equator divides the Earth into:',                 'options' => ['East and West', 'North and South', 'Top and Bottom', 'Left and Right'],                        'answer' => 1],
                                ['text' => 'Which ocean lies between Africa and South America?',  'options' => ['Pacific', 'Indian', 'Atlantic', 'Arctic'],                                                     'answer' => 2],
                            ],
                        ]),
                    ],
                ],
            ],

            [
                'unit_id'    => $unitIds['english']['geo'],
                'name'       => 'Countries and Capitals',
                'slug'       => 'countries-capitals-c6',
                'type'       => 'listening',
                'order'      => 2,
                'exercises'  => [
                    [
                        'title'             => 'African Capitals Drill',
                        'instructions'      => 'Listen and repeat each country and its capital.',
                        'category'          => 'oral_drill',
                        'difficulty'        => 'easy',
                        'estimated_minutes' => 8,
                        'content'           => json_encode([
                            'type'  => 'oral_drill',
                            'items' => [
                                ['text' => 'Cameroon — Yaoundé',        'audio_hint' => 'cameroon-yaounde'],
                                ['text' => 'Nigeria — Abuja',           'audio_hint' => 'nigeria-abuja'],
                                ['text' => 'Ghana — Accra',             'audio_hint' => 'ghana-accra'],
                                ['text' => 'Kenya — Nairobi',           'audio_hint' => 'kenya-nairobi'],
                                ['text' => 'South Africa — Pretoria',   'audio_hint' => 'southafrica-pretoria'],
                                ['text' => 'Egypt — Cairo',             'audio_hint' => 'egypt-cairo'],
                            ],
                        ]),
                    ],
                    [
                        'title'             => 'Match the Capital',
                        'instructions'      => 'Fill in the capital city of each country.',
                        'category'          => 'revision',
                        'difficulty'        => 'medium',
                        'estimated_minutes' => 10,
                        'content'           => json_encode([
                            'type'      => 'fill_in',
                            'sentences' => [
                                ['text' => 'The capital of Cameroon is ___.', 'answer' => 'Yaoundé'],
                                ['text' => 'The capital of Nigeria is ___.',  'answer' => 'Abuja'],
                                ['text' => 'The capital of France is ___.',   'answer' => 'Paris'],
                                ['text' => 'The capital of Ghana is ___.',    'answer' => 'Accra'],
                                ['text' => 'The capital of Kenya is ___.',    'answer' => 'Nairobi'],
                                ['text' => 'The capital of China is ___.',    'answer' => 'Beijing'],
                            ],
                        ]),
                    ],
                ],
            ],

            // ── ENGLISH : Unit 2 — Being a Citizen (unit_id=22) ──────────
            [
                'unit_id'    => $unitIds['english']['citizen'],
                'name'       => 'Rights and Responsibilities',
                'slug'       => 'rights-responsibilities-c6',
                'type'       => 'reading',
                'order'      => 1,
                'exercises'  => [
                    [
                        'title'             => 'Rights of the Child',
                        'instructions'      => 'Listen and repeat each right every child has.',
                        'category'          => 'oral_drill',
                        'difficulty'        => 'easy',
                        'estimated_minutes' => 8,
                        'content'           => json_encode([
                            'type'  => 'oral_drill',
                            'items' => [
                                ['text' => 'Right to education',  'audio_hint' => 'right-education'],
                                ['text' => 'Right to health',     'audio_hint' => 'right-health'],
                                ['text' => 'Right to protection', 'audio_hint' => 'right-protection'],
                                ['text' => 'Right to play',       'audio_hint' => 'right-play'],
                                ['text' => 'Right to a name',     'audio_hint' => 'right-name'],
                                ['text' => 'Right to food',       'audio_hint' => 'right-food'],
                            ],
                        ]),
                    ],
                    [
                        'title'             => 'Good Citizenship Quiz',
                        'instructions'      => 'Choose the best answer about citizenship and civic duties.',
                        'category'          => 'quiz',
                        'difficulty'        => 'medium',
                        'estimated_minutes' => 12,
                        'content'           => json_encode([
                            'type'      => 'mcq',
                            'questions' => [
                                ['text' => 'A good citizen should:', 'options' => ['Ignore community problems', 'Respect the laws of the country', 'Avoid paying taxes', 'Litter in public places'], 'answer' => 1],
                                ['text' => 'What is the purpose of a constitution?', 'options' => ['To punish criminals', 'To collect taxes', 'To set the fundamental laws of a country', 'To manage the army'], 'answer' => 2],
                                ['text' => 'Which of these is a duty, NOT a right?', 'options' => ['Freedom of speech', 'Right to education', 'Paying taxes', 'Right to vote'], 'answer' => 2],
                                ['text' => 'The head of state of Cameroon is the:', 'options' => ['Prime Minister', 'President', 'Governor', 'Mayor'], 'answer' => 1],
                                ['text' => 'Why do we pay taxes?', 'options' => ['To make the government rich', 'To fund public services like schools and roads', 'It is optional', 'To pay soldiers'], 'answer' => 1],
                            ],
                        ]),
                    ],
                ],
            ],

            [
                'unit_id'    => $unitIds['english']['citizen'],
                'name'       => 'Community Leaders',
                'slug'       => 'community-leaders-c6',
                'type'       => 'listening',
                'order'      => 2,
                'exercises'  => [
                    [
                        'title'             => 'Civic Vocabulary',
                        'instructions'      => 'Fill in the blank with the correct civic word.',
                        'category'          => 'revision',
                        'difficulty'        => 'medium',
                        'estimated_minutes' => 10,
                        'content'           => json_encode([
                            'type'      => 'fill_in',
                            'sentences' => [
                                ['text' => 'The person who leads a town is called a ___.',            'answer' => 'mayor'],
                                ['text' => 'The set of laws that govern a country is the ___.',       'answer' => 'constitution'],
                                ['text' => 'People choose their leaders by going to ___ .',           'answer' => 'vote'],
                                ['text' => 'A person who belongs to a country is a ___ .',            'answer' => 'citizen'],
                                ['text' => 'The money collected by the government from people is ___. ', 'answer' => 'tax'],
                            ],
                        ]),
                    ],
                ],
            ],

            // ── ENGLISH : Unit 3 — Protecting Nature (unit_id=23) ────────
            [
                'unit_id'    => $unitIds['english']['nature'],
                'name'       => 'Environment Vocabulary',
                'slug'       => 'environment-vocab-c6',
                'type'       => 'listening',
                'order'      => 1,
                'exercises'  => [
                    [
                        'title'             => 'Environment Words Drill',
                        'instructions'      => 'Listen and repeat each environment word.',
                        'category'          => 'oral_drill',
                        'difficulty'        => 'easy',
                        'estimated_minutes' => 8,
                        'content'           => json_encode([
                            'type'  => 'oral_drill',
                            'items' => [
                                ['text' => 'deforestation — cutting down forests',          'audio_hint' => 'deforestation'],
                                ['text' => 'pollution — making air or water dirty',         'audio_hint' => 'pollution'],
                                ['text' => 'conservation — protecting natural resources',   'audio_hint' => 'conservation'],
                                ['text' => 'biodiversity — variety of life on Earth',       'audio_hint' => 'biodiversity'],
                                ['text' => 'erosion — wearing away of soil',                'audio_hint' => 'erosion'],
                                ['text' => 'ecosystem — community of living things',        'audio_hint' => 'ecosystem'],
                                ['text' => 'renewable — energy that can be replaced',       'audio_hint' => 'renewable'],
                            ],
                        ]),
                    ],
                    [
                        'title'             => 'Nature Protection Quiz',
                        'instructions'      => 'Choose the correct answer.',
                        'category'          => 'quiz',
                        'difficulty'        => 'medium',
                        'estimated_minutes' => 12,
                        'content'           => json_encode([
                            'type'      => 'mcq',
                            'questions' => [
                                ['text' => 'Which of these is a renewable source of energy?',          'options' => ['Coal', 'Petroleum', 'Solar energy', 'Natural gas'],                  'answer' => 2],
                                ['text' => 'What causes deforestation?',                                'options' => ['Planting trees', 'Cutting down forests', 'Watering plants', 'Rain'], 'answer' => 1],
                                ['text' => 'Soil erosion can be prevented by:',                        'options' => ['Cutting trees', 'Overgrazing', 'Planting more trees', 'Burning grass'], 'answer' => 2],
                                ['text' => 'Which gas do plants absorb to make food?',                 'options' => ['Oxygen', 'Nitrogen', 'Carbon dioxide', 'Hydrogen'],                   'answer' => 2],
                                ['text' => 'What does biodiversity mean?',                             'options' => ['Only animals in a forest', 'All living things in the sea', 'The variety of life on Earth', 'Only plants in a region'], 'answer' => 2],
                            ],
                        ]),
                    ],
                ],
            ],

            [
                'unit_id'    => $unitIds['english']['nature'],
                'name'       => 'Actions for the Planet',
                'slug'       => 'actions-planet-c6',
                'type'       => 'writing',
                'order'      => 2,
                'exercises'  => [
                    [
                        'title'             => 'Green Actions Fill-in',
                        'instructions'      => 'Complete each sentence about protecting our planet.',
                        'category'          => 'revision',
                        'difficulty'        => 'medium',
                        'estimated_minutes' => 10,
                        'content'           => json_encode([
                            'type'      => 'fill_in',
                            'sentences' => [
                                ['text' => 'We should ___ water to avoid waste.',              'answer' => 'save'],
                                ['text' => 'Throwing rubbish in the river causes ___.',        'answer' => 'pollution'],
                                ['text' => 'Planting trees helps prevent soil ___.',           'answer' => 'erosion'],
                                ['text' => 'Solar panels use energy from the ___.',            'answer' => 'sun'],
                                ['text' => 'Recycling helps to ___ waste.',                    'answer' => 'reduce'],
                                ['text' => 'Animals and plants need a healthy ___ to survive.','answer' => 'ecosystem'],
                            ],
                        ]),
                    ],
                ],
            ],

            // ── MATHS : Unit 4 — Advanced Numbers ────────────────────────
            [
                'unit_id'    => $unitIds['maths']['advanced'],
                'name'       => 'Ratios and Proportions',
                'slug'       => 'ratios-proportions-c6',
                'type'       => 'mathematics',
                'order'      => 1,
                'exercises'  => [
                    [
                        'title'             => 'Ratio Vocabulary',
                        'instructions'      => 'Listen and repeat these key maths terms.',
                        'category'          => 'oral_drill',
                        'difficulty'        => 'easy',
                        'estimated_minutes' => 6,
                        'content'           => json_encode([
                            'type'  => 'oral_drill',
                            'items' => [
                                ['text' => 'ratio — the comparison of two quantities',        'audio_hint' => 'ratio'],
                                ['text' => 'proportion — two equal ratios',                   'audio_hint' => 'proportion'],
                                ['text' => 'simplify — reduce to the lowest form',            'audio_hint' => 'simplify'],
                                ['text' => 'equivalent — equal in value',                     'audio_hint' => 'equivalent'],
                                ['text' => 'numerator — the top number of a fraction',        'audio_hint' => 'numerator'],
                                ['text' => 'denominator — the bottom number of a fraction',   'audio_hint' => 'denominator'],
                            ],
                        ]),
                    ],
                    [
                        'title'             => 'Ratios Quiz',
                        'instructions'      => 'Solve each ratio and proportion problem.',
                        'category'          => 'quiz',
                        'difficulty'        => 'medium',
                        'estimated_minutes' => 15,
                        'content'           => json_encode([
                            'type'      => 'mcq',
                            'questions' => [
                                ['text' => 'A class has 12 girls and 18 boys. What is the ratio of girls to boys in simplest form?', 'options' => ['2:3', '3:2', '12:18', '1:2'], 'answer' => 0],
                                ['text' => 'If 5 pens cost 750 FCFA, how much do 8 pens cost?',                                       'options' => ['1000 FCFA', '1200 FCFA', '1500 FCFA', '900 FCFA'], 'answer' => 1],
                                ['text' => 'Divide 240 in the ratio 3:5.',                                                            'options' => ['60 and 180', '90 and 150', '80 and 160', '120 and 120'], 'answer' => 1],
                                ['text' => 'A car travels 240 km in 4 hours. How far in 7 hours at the same speed?',                  'options' => ['360 km', '400 km', '420 km', '480 km'], 'answer' => 2],
                                ['text' => 'The ratio 15:25 in its simplest form is:',                                                'options' => ['5:8', '3:5', '3:4', '2:5'], 'answer' => 1],
                            ],
                        ]),
                    ],
                ],
            ],

            [
                'unit_id'    => $unitIds['maths']['advanced'],
                'name'       => 'Percentages Advanced',
                'slug'       => 'percentages-advanced-c6',
                'type'       => 'mathematics',
                'order'      => 2,
                'exercises'  => [
                    [
                        'title'             => 'Percentage Calculations',
                        'instructions'      => 'Fill in the correct answer for each percentage problem.',
                        'category'          => 'revision',
                        'difficulty'        => 'hard',
                        'estimated_minutes' => 15,
                        'content'           => json_encode([
                            'type'      => 'fill_in',
                            'sentences' => [
                                ['text' => '20% of 350 = ___',                                               'answer' => '70'],
                                ['text' => 'A shirt costs 5000 FCFA. After 15% discount it costs ___ FCFA.', 'answer' => '4250'],
                                ['text' => '45 out of 60 = ___%',                                            'answer' => '75'],
                                ['text' => 'Increase 800 by 25% = ___',                                      'answer' => '1000'],
                                ['text' => '120 is ___% of 400.',                                            'answer' => '30'],
                                ['text' => 'A man earns 80000 FCFA. He saves 35%. He saves ___ FCFA.',       'answer' => '28000'],
                            ],
                        ]),
                    ],
                ],
            ],

            // ── MATHS : Unit 5 — Statistics ──────────────────────────────
            [
                'unit_id'    => $unitIds['maths']['statistics'],
                'name'       => 'Averages and Data',
                'slug'       => 'averages-data-c6',
                'type'       => 'mathematics',
                'order'      => 1,
                'exercises'  => [
                    [
                        'title'             => 'Statistics Vocabulary',
                        'instructions'      => 'Listen and repeat these statistics words.',
                        'category'          => 'oral_drill',
                        'difficulty'        => 'easy',
                        'estimated_minutes' => 6,
                        'content'           => json_encode([
                            'type'  => 'oral_drill',
                            'items' => [
                                ['text' => 'mean — the sum divided by the count',       'audio_hint' => 'mean'],
                                ['text' => 'median — the middle value in a data set',   'audio_hint' => 'median'],
                                ['text' => 'mode — the value that appears most often',  'audio_hint' => 'mode'],
                                ['text' => 'range — the difference between highest and lowest', 'audio_hint' => 'range'],
                                ['text' => 'data — facts and numbers collected',        'audio_hint' => 'data'],
                            ],
                        ]),
                    ],
                    [
                        'title'             => 'Mean, Median, Mode Quiz',
                        'instructions'      => 'Choose the correct answer for each statistics question.',
                        'category'          => 'quiz',
                        'difficulty'        => 'hard',
                        'estimated_minutes' => 15,
                        'content'           => json_encode([
                            'type'      => 'mcq',
                            'questions' => [
                                ['text' => 'The marks of 5 pupils are: 60, 72, 55, 80, 63. What is the mean?', 'options' => ['64', '66', '68', '70'], 'answer' => 1],
                                ['text' => 'What is the median of: 3, 7, 9, 12, 15?',                          'options' => ['7', '9', '12', '3'],   'answer' => 1],
                                ['text' => 'What is the mode of: 4, 6, 4, 8, 9, 4, 6?',                       'options' => ['6', '4', '8', '9'],    'answer' => 1],
                                ['text' => 'The range of 5, 12, 7, 20, 3 is:',                                 'options' => ['15', '17', '20', '10'], 'answer' => 1],
                                ['text' => 'The average of 4 numbers is 25. Three of them are 20, 30, 28. The fourth is:', 'options' => ['20', '22', '24', '25'], 'answer' => 1],
                            ],
                        ]),
                    ],
                ],
            ],

            // ── MATHS : Unit 6 — Algebra Intro ───────────────────────────
            [
                'unit_id'    => $unitIds['maths']['algebra'],
                'name'       => 'Unknowns and Equations',
                'slug'       => 'unknowns-equations-c6',
                'type'       => 'mathematics',
                'order'      => 1,
                'exercises'  => [
                    [
                        'title'             => 'Algebra Vocabulary',
                        'instructions'      => 'Listen and repeat these algebra terms.',
                        'category'          => 'oral_drill',
                        'difficulty'        => 'easy',
                        'estimated_minutes' => 6,
                        'content'           => json_encode([
                            'type'  => 'oral_drill',
                            'items' => [
                                ['text' => 'unknown — a letter that represents a missing number', 'audio_hint' => 'unknown'],
                                ['text' => 'equation — a statement that two things are equal',    'audio_hint' => 'equation'],
                                ['text' => 'variable — a letter used instead of a number',        'audio_hint' => 'variable'],
                                ['text' => 'expression — a group of numbers and letters',         'audio_hint' => 'expression'],
                                ['text' => 'solve — find the value of the unknown',               'audio_hint' => 'solve'],
                            ],
                        ]),
                    ],
                    [
                        'title'             => 'Find the Unknown',
                        'instructions'      => 'Fill in the value of x or n in each equation.',
                        'category'          => 'revision',
                        'difficulty'        => 'medium',
                        'estimated_minutes' => 15,
                        'content'           => json_encode([
                            'type'      => 'fill_in',
                            'sentences' => [
                                ['text' => 'x + 7 = 15, so x = ___',   'answer' => '8'],
                                ['text' => 'n - 4 = 11, so n = ___',   'answer' => '15'],
                                ['text' => '3 × x = 24, so x = ___',   'answer' => '8'],
                                ['text' => 'n ÷ 5 = 6, so n = ___',    'answer' => '30'],
                                ['text' => '2x + 3 = 13, so x = ___',  'answer' => '5'],
                                ['text' => '4n - 2 = 22, so n = ___',  'answer' => '6'],
                            ],
                        ]),
                    ],
                    [
                        'title'             => 'Simple Equations Quiz',
                        'instructions'      => 'Choose the correct value of the unknown.',
                        'category'          => 'quiz',
                        'difficulty'        => 'hard',
                        'estimated_minutes' => 12,
                        'content'           => json_encode([
                            'type'      => 'mcq',
                            'questions' => [
                                ['text' => 'If x + 9 = 20, what is x?',      'options' => ['9', '11', '12', '10'],  'answer' => 1],
                                ['text' => 'If 5n = 45, what is n?',          'options' => ['7', '8', '9', '10'],   'answer' => 2],
                                ['text' => 'If 2x - 1 = 9, what is x?',      'options' => ['3', '4', '5', '6'],    'answer' => 2],
                                ['text' => 'If n ÷ 4 = 7, what is n?',       'options' => ['21', '24', '28', '32'], 'answer' => 2],
                                ['text' => 'Which equation means "a number plus 6 equals 14"?', 'options' => ['n - 6 = 14', 'n + 6 = 14', '6n = 14', 'n ÷ 6 = 14'], 'answer' => 1],
                            ],
                        ]),
                    ],
                ],
            ],

        ];

        // ══════════════════════════════════════════════════════════════════
        // STEP 3 — Insert lessons and exercises
        // ══════════════════════════════════════════════════════════════════
        $totalLessons   = 0;
        $totalExercises = 0;

        foreach ($structure as $lessonData) {
            $exercises = $lessonData['exercises'];
            unset($lessonData['exercises']);

            // Skip if lesson already exists
            $existing = DB::table('lessons')->where('slug', $lessonData['slug'])->first();
            if ($existing) {
                $lessonId = $existing->id;
                $this->command->info("Lesson exists: {$lessonData['name']} (id={$lessonId})");
            } else {
                $lessonId = DB::table('lessons')->insertGetId(array_merge($lessonData, [
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

            foreach ($exercises as $order => $ex) {
                // Skip if exercise already exists for this lesson
                $exExists = DB::table('exercises')
                    ->where('lesson_id', $lessonId)
                    ->where('title', $ex['title'])
                    ->exists();

                if (!$exExists) {
                    DB::table('exercises')->insert(array_merge($ex, [
                        'lesson_id'    => $lessonId,
                        'home_skill_id'=> null,
                        'is_active'    => true,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ]));
                    $totalExercises++;
                }
            }
        }

        $this->command->info("═══════════════════════════════════════════");
        $this->command->info("Class 6 seeded: {$totalLessons} lessons, {$totalExercises} exercises.");
        $this->command->info("═══════════════════════════════════════════");
    }
}
