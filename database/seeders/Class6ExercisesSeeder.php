<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Class6ExercisesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $levelId = 10; // Class 6

        $subjects = DB::table('subjects')->whereIn('slug', [
            'english', 'mathematics', 'science', 'social-studies', 'civics'
        ])->pluck('id', 'slug');

        $exercises = [

            // ── ENGLISH ─────────────────────────────────────────────────────────
            [
                'title'       => 'Synonyms Challenge',
                'type'        => 'mcq',
                'subject_id'  => $subjects['english'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 2,
                'instruction' => 'Choose the word closest in meaning to the word in bold.',
                'data'        => json_encode([
                    'questions' => [
                        [
                            'text'    => 'The work was **arduous**.',
                            'options' => ['easy', 'difficult', 'boring', 'quick'],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'She gave a **concise** explanation.',
                            'options' => ['long', 'confusing', 'brief', 'loud'],
                            'answer'  => 2,
                        ],
                        [
                            'text'    => 'He was **furious** about the result.',
                            'options' => ['happy', 'sad', 'tired', 'very angry'],
                            'answer'  => 3,
                        ],
                        [
                            'text'    => 'The path was **narrow**.',
                            'options' => ['wide', 'thin', 'long', 'straight'],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'They decided to **abandon** the old house.',
                            'options' => ['build', 'paint', 'leave behind', 'buy'],
                            'answer'  => 2,
                        ],
                    ],
                ]),
            ],

            [
                'title'       => 'Verb Tenses — Past, Present, Future',
                'type'        => 'fill_in',
                'subject_id'  => $subjects['english'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 2,
                'instruction' => 'Fill in the blank with the correct tense of the verb in brackets.',
                'data'        => json_encode([
                    'sentences' => [
                        ['text' => 'She ___ to school every day. (go)',        'answer' => 'goes'],
                        ['text' => 'They ___ football yesterday. (play)',       'answer' => 'played'],
                        ['text' => 'He ___ his homework tomorrow. (finish)',    'answer' => 'will finish'],
                        ['text' => 'We ___ dinner when it rained. (eat)',       'answer' => 'were eating'],
                        ['text' => 'The bird ___ away this morning. (fly)',     'answer' => 'flew'],
                        ['text' => 'I ___ English since I was five. (learn)',   'answer' => 'have been learning'],
                    ],
                ]),
            ],

            [
                'title'       => 'Antonyms Drill',
                'type'        => 'oral_drill',
                'subject_id'  => $subjects['english'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 1,
                'instruction' => 'Listen to the word. Say its opposite!',
                'data'        => json_encode([
                    'items' => [
                        ['prompt' => 'ancient',   'answer' => 'modern'],
                        ['prompt' => 'courage',   'answer' => 'cowardice'],
                        ['prompt' => 'victory',   'answer' => 'defeat'],
                        ['prompt' => 'generous',  'answer' => 'selfish'],
                        ['prompt' => 'expand',    'answer' => 'contract'],
                        ['prompt' => 'interior',  'answer' => 'exterior'],
                        ['prompt' => 'borrow',    'answer' => 'lend'],
                        ['prompt' => 'innocent',  'answer' => 'guilty'],
                    ],
                ]),
            ],

            [
                'title'       => 'Sentence Correction',
                'type'        => 'mcq',
                'subject_id'  => $subjects['english'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 3,
                'instruction' => 'Choose the correctly written sentence.',
                'data'        => json_encode([
                    'questions' => [
                        [
                            'text'    => 'Which sentence is correct?',
                            'options' => [
                                'She don\'t like mangoes.',
                                'She doesn\'t like mangoes.',
                                'She not like mangoes.',
                                'She didn\'t likes mangoes.',
                            ],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'Which sentence is correct?',
                            'options' => [
                                'Neither of the boys are ready.',
                                'Neither of the boys is ready.',
                                'Neither of the boys were ready.',
                                'Neither of the boys be ready.',
                            ],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'Which sentence is correct?',
                            'options' => [
                                'He goes to the market yesterday.',
                                'He gone to the market yesterday.',
                                'He went to the market yesterday.',
                                'He go to the market yesterday.',
                            ],
                            'answer'  => 2,
                        ],
                    ],
                ]),
            ],

            // ── MATHEMATICS ─────────────────────────────────────────────────────
            [
                'title'       => 'Ratios and Proportions',
                'type'        => 'mcq',
                'subject_id'  => $subjects['mathematics'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 2,
                'instruction' => 'Solve the ratio and proportion problems.',
                'data'        => json_encode([
                    'questions' => [
                        [
                            'text'    => 'A class has 12 girls and 18 boys. What is the ratio of girls to boys in its simplest form?',
                            'options' => ['2:3', '3:2', '12:18', '1:2'],
                            'answer'  => 0,
                        ],
                        [
                            'text'    => 'If 5 pens cost 750 FCFA, how much do 8 pens cost?',
                            'options' => ['1000 FCFA', '1200 FCFA', '1500 FCFA', '900 FCFA'],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'Divide 240 in the ratio 3:5.',
                            'options' => ['60 and 180', '90 and 150', '80 and 160', '120 and 120'],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'If a car travels 240 km in 4 hours, how far will it travel in 7 hours at the same speed?',
                            'options' => ['360 km', '400 km', '420 km', '480 km'],
                            'answer'  => 2,
                        ],
                    ],
                ]),
            ],

            [
                'title'       => 'Averages',
                'type'        => 'mcq',
                'subject_id'  => $subjects['mathematics'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 2,
                'instruction' => 'Calculate the average (mean) in each problem.',
                'data'        => json_encode([
                    'questions' => [
                        [
                            'text'    => 'The marks of 5 students are: 60, 72, 55, 80, 63. What is the average?',
                            'options' => ['64', '66', '68', '70'],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'The average of 4 numbers is 25. Three of them are 20, 30, and 28. What is the fourth?',
                            'options' => ['20', '22', '24', '25'],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'A farmer harvests 120 kg, 145 kg, and 95 kg over 3 days. What is the daily average?',
                            'options' => ['110 kg', '120 kg', '130 kg', '115 kg'],
                            'answer'  => 1,
                        ],
                    ],
                ]),
            ],

            [
                'title'       => 'Volume and Capacity',
                'type'        => 'mcq',
                'subject_id'  => $subjects['mathematics'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 3,
                'instruction' => 'Solve problems about volume and capacity.',
                'data'        => json_encode([
                    'questions' => [
                        [
                            'text'    => 'A rectangular box is 10 cm long, 5 cm wide and 4 cm high. What is its volume?',
                            'options' => ['150 cm³', '200 cm³', '180 cm³', '220 cm³'],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'A tank holds 1,500 litres. How many 250 ml bottles can be filled from it?',
                            'options' => ['500', '4000', '6000', '1500'],
                            'answer'  => 2,
                        ],
                        [
                            'text'    => 'A cube has a side length of 6 cm. What is its volume?',
                            'options' => ['36 cm³', '108 cm³', '216 cm³', '180 cm³'],
                            'answer'  => 2,
                        ],
                    ],
                ]),
            ],

            [
                'title'       => 'Percentages — Advanced',
                'type'        => 'fill_in',
                'subject_id'  => $subjects['mathematics'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 3,
                'instruction' => 'Calculate and write the answer.',
                'data'        => json_encode([
                    'sentences' => [
                        ['text' => '20% of 350 = ___',                                          'answer' => '70'],
                        ['text' => 'A shirt costs 5000 FCFA. After 15% discount, it costs ___ FCFA.', 'answer' => '4250'],
                        ['text' => '45 out of 60 = ___% ',                                      'answer' => '75'],
                        ['text' => 'Increase 800 by 25% = ___',                                 'answer' => '1000'],
                        ['text' => '120 is ___% of 400.',                                        'answer' => '30'],
                    ],
                ]),
            ],

            // ── SCIENCE ─────────────────────────────────────────────────────────
            [
                'title'       => 'The Human Body Systems',
                'type'        => 'mcq',
                'subject_id'  => $subjects['science'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 2,
                'instruction' => 'Answer questions about the major body systems.',
                'data'        => json_encode([
                    'questions' => [
                        [
                            'text'    => 'Which organ pumps blood around the body?',
                            'options' => ['lungs', 'brain', 'heart', 'liver'],
                            'answer'  => 2,
                        ],
                        [
                            'text'    => 'What is the main function of the respiratory system?',
                            'options' => [
                                'Digest food',
                                'Exchange oxygen and carbon dioxide',
                                'Pump blood',
                                'Filter waste',
                            ],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'The skeleton is made up of how many bones in an adult?',
                            'options' => ['186', '206', '226', '256'],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'Which system controls all body activities?',
                            'options' => ['Digestive', 'Circulatory', 'Nervous', 'Excretory'],
                            'answer'  => 2,
                        ],
                        [
                            'text'    => 'Where does digestion begin?',
                            'options' => ['Stomach', 'Small intestine', 'Mouth', 'Liver'],
                            'answer'  => 2,
                        ],
                    ],
                ]),
            ],

            [
                'title'       => 'Energy Sources',
                'type'        => 'mcq',
                'subject_id'  => $subjects['science'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 2,
                'instruction' => 'Answer questions about different sources of energy.',
                'data'        => json_encode([
                    'questions' => [
                        [
                            'text'    => 'Which of these is a renewable source of energy?',
                            'options' => ['Coal', 'Petroleum', 'Solar energy', 'Natural gas'],
                            'answer'  => 2,
                        ],
                        [
                            'text'    => 'The sun\'s energy reaches us in the form of:',
                            'options' => ['Sound', 'Light and heat', 'Water', 'Wind'],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'Which gas do plants use to make food using sunlight?',
                            'options' => ['Oxygen', 'Nitrogen', 'Carbon dioxide', 'Hydrogen'],
                            'answer'  => 2,
                        ],
                        [
                            'text'    => 'A non-renewable source of energy is:',
                            'options' => ['Wind', 'Biogas', 'Oil', 'Hydro'],
                            'answer'  => 2,
                        ],
                    ],
                ]),
            ],

            [
                'title'       => 'Environment & Conservation',
                'type'        => 'oral_drill',
                'subject_id'  => $subjects['science'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 1,
                'instruction' => 'Listen and repeat — key environment vocabulary!',
                'data'        => json_encode([
                    'items' => [
                        ['prompt' => 'deforestation',  'answer' => 'cutting down forests'],
                        ['prompt' => 'pollution',      'answer' => 'making air, water or land dirty'],
                        ['prompt' => 'conservation',   'answer' => 'protecting natural resources'],
                        ['prompt' => 'biodiversity',   'answer' => 'variety of life on Earth'],
                        ['prompt' => 'erosion',        'answer' => 'wearing away of soil by wind or water'],
                        ['prompt' => 'ecosystem',      'answer' => 'community of living things and their environment'],
                    ],
                ]),
            ],

            // ── SOCIAL STUDIES ──────────────────────────────────────────────────
            [
                'title'       => 'Cameroon — Geography & Economy',
                'type'        => 'mcq',
                'subject_id'  => $subjects['social-studies'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 2,
                'instruction' => 'Answer questions about Cameroon\'s geography and economy.',
                'data'        => json_encode([
                    'questions' => [
                        [
                            'text'    => 'What is the capital city of Cameroon?',
                            'options' => ['Douala', 'Yaoundé', 'Bafoussam', 'Garoua'],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'Mount Cameroon is located in which region?',
                            'options' => ['Centre', 'West', 'South West', 'North West'],
                            'answer'  => 2,
                        ],
                        [
                            'text'    => 'Which is Cameroon\'s largest and most important port city?',
                            'options' => ['Yaoundé', 'Limbe', 'Douala', 'Kribi'],
                            'answer'  => 2,
                        ],
                        [
                            'text'    => 'Cameroon is sometimes called "Africa in miniature" because:',
                            'options' => [
                                'It is the smallest country in Africa',
                                'It has almost every type of landscape found in Africa',
                                'It is in the middle of Africa',
                                'It has the most people in Africa',
                            ],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'Which cash crop is Cameroon\'s main export product?',
                            'options' => ['Rice', 'Maize', 'Cocoa', 'Millet'],
                            'answer'  => 2,
                        ],
                    ],
                ]),
            ],

            [
                'title'       => 'World Geography — Continents & Oceans',
                'type'        => 'mcq',
                'subject_id'  => $subjects['social-studies'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 2,
                'instruction' => 'Answer questions about continents and oceans of the world.',
                'data'        => json_encode([
                    'questions' => [
                        [
                            'text'    => 'How many continents are there on Earth?',
                            'options' => ['5', '6', '7', '8'],
                            'answer'  => 2,
                        ],
                        [
                            'text'    => 'The largest ocean in the world is the:',
                            'options' => ['Indian Ocean', 'Atlantic Ocean', 'Arctic Ocean', 'Pacific Ocean'],
                            'answer'  => 3,
                        ],
                        [
                            'text'    => 'Which continent is the largest by area?',
                            'options' => ['Africa', 'Asia', 'America', 'Europe'],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'The equator passes through which African country?',
                            'options' => ['Nigeria', 'Ghana', 'Cameroon', 'South Africa'],
                            'answer'  => 2,
                        ],
                    ],
                ]),
            ],

            // ── CIVICS ──────────────────────────────────────────────────────────
            [
                'title'       => 'Good Citizenship',
                'type'        => 'mcq',
                'subject_id'  => $subjects['civics'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 1,
                'instruction' => 'Choose the best answer about citizenship and civic duties.',
                'data'        => json_encode([
                    'questions' => [
                        [
                            'text'    => 'A good citizen should:',
                            'options' => [
                                'Ignore community problems',
                                'Respect the laws of the country',
                                'Avoid paying taxes',
                                'Litter in public places',
                            ],
                            'answer'  => 1,
                        ],
                        [
                            'text'    => 'What is the purpose of a constitution?',
                            'options' => [
                                'To punish criminals',
                                'To collect taxes',
                                'To set the fundamental laws of the country',
                                'To manage the army',
                            ],
                            'answer'  => 2,
                        ],
                        [
                            'text'    => 'Which of these is a duty, NOT a right?',
                            'options' => [
                                'Freedom of speech',
                                'Right to education',
                                'Paying taxes',
                                'Right to vote',
                            ],
                            'answer'  => 2,
                        ],
                        [
                            'text'    => 'The head of state of Cameroon is the:',
                            'options' => ['Prime Minister', 'President', 'Governor', 'Minister'],
                            'answer'  => 1,
                        ],
                    ],
                ]),
            ],

            [
                'title'       => 'Rights of the Child',
                'type'        => 'oral_drill',
                'subject_id'  => $subjects['civics'] ?? null,
                'level_id'    => $levelId,
                'difficulty'  => 1,
                'instruction' => 'Listen and repeat — key rights every child has!',
                'data'        => json_encode([
                    'items' => [
                        ['prompt' => 'Right to education',    'answer' => 'Every child must have access to school.'],
                        ['prompt' => 'Right to health',       'answer' => 'Every child deserves medical care.'],
                        ['prompt' => 'Right to protection',   'answer' => 'No child should be abused or exploited.'],
                        ['prompt' => 'Right to play',         'answer' => 'Children need time to rest and play.'],
                        ['prompt' => 'Right to a name',       'answer' => 'Every child must be registered at birth.'],
                        ['prompt' => 'Right to food',         'answer' => 'No child should go hungry.'],
                    ],
                ]),
            ],

        ];

        // Ensure order and timestamps
        foreach ($exercises as $index => $ex) {
            DB::table('exercises')->insert(array_merge($ex, [
                'order'      => $index + 1,
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $count = count($exercises);
        $this->command->info("Class 6: {$count} exercises seeded successfully.");
    }
}
