<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class3NationalLanguagesAlignmentPilotSeeder extends Seeder
{
    private const DOCUMENT_SHA256 = '1921b9d044f23849c1532f403021e0a8d23a46123d4b225b8b083b827e3fdd1c';

    private const ALL_EXERCISE_IDS = [
        1397, 1398, 1399, 1400, 1401, 1402, 1403, 1404, 1405, 1406, 1407,
        2872, 2873, 2874, 2875, 2876, 2877, 2878, 2879, 2880, 2881, 2882,
        2883, 2884, 2885, 2886, 2887, 2888, 2889, 2890, 2891, 2892,
    ];

    private const ACTIVE_EXERCISE_IDS = [
        1397, 1398, 1399, 1400, 1401, 1402, 1403, 1404, 1405, 1406, 1407,
        2872, 2873, 2874, 2875, 2876, 2877, 2878, 2879, 2880,
    ];

    private const ORIGINAL_TITLES = [
        1397 => 'Traditional ruler',
        1398 => 'Fon',
        1399 => 'Chief fact',
        1400 => 'Lamido',
        1401 => 'Proverbs',
        1402 => 'Oral tradition',
        1403 => 'Folklore fact',
        1404 => 'Griots',
        1405 => 'Balafon',
        1406 => 'Bikutsi',
        1407 => 'Music fact',
        2872 => 'Family Structure C3',
        2873 => 'Family Values C3',
        2874 => 'Extended Family C3',
        2875 => 'Family Roles C3',
        2876 => 'Respecting Elders C3',
        2877 => 'Elders C3',
        2878 => 'Greeting Elders C3',
        2879 => 'Types of Marriage C3',
        2880 => 'Marriage Types C3',
    ];

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
                ->where('subjects.name', 'National Languages and Cultures')
                ->value('subjects.id');
            if (! $subjectId) {
                throw new \RuntimeException('Class 3 National Languages and Cultures was not found.');
            }

            $this->assertExerciseState((int) $subjectId);
            $this->assertHierarchyOwnership((int) $subjectId);
            $this->alignHierarchy();

            foreach ($this->exerciseUpdates() as $exerciseId => $update) {
                $this->updateExercise($exerciseId, $update);
            }

            $inactiveIds = array_values(array_diff(self::ALL_EXERCISE_IDS, self::ACTIVE_EXERCISE_IDS));
            DB::table('exercises')->whereIn('id', $inactiveIds)->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);
            $this->removeImageReferences(self::ALL_EXERCISE_IDS);

            foreach ([450, 451, 452, 453, 454] as $lessonId) {
                $this->deactivateEmptyLessonHierarchy($lessonId);
            }

            $competencies = $this->competencies();
            $competencyIds = [];
            foreach ($competencies as $competency) {
                $competencyIds[$competency['official_code']] = $this->upsertCompetency(
                    (int) $subjectId,
                    (int) $documentId,
                    $competency
                );
            }

            DB::table('exercise_school_competency')
                ->whereIn('exercise_id', self::ALL_EXERCISE_IDS)
                ->whereIn('school_competency_id', array_values($competencyIds))
                ->delete();

            foreach ($competencies as $competency) {
                foreach ($competency['exercise_ids'] as $exerciseId) {
                    DB::table('exercise_school_competency')->insert([
                        'exercise_id' => $exerciseId,
                        'school_competency_id' => $competencyIds[$competency['official_code']],
                    ]);
                }
            }
        });
    }

    private function assertExerciseState(int $subjectId): void
    {
        $rows = DB::table('exercises')
            ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->whereIn('exercises.id', self::ALL_EXERCISE_IDS)
            ->orderBy('exercises.id')
            ->get(['exercises.id', 'exercises.is_active', 'integrated_themes.subject_id']);
        if ($rows->count() !== count(self::ALL_EXERCISE_IDS)
            || $rows->contains(fn (object $row): bool => (int) $row->subject_id !== $subjectId)) {
            throw new \RuntimeException('The complete Class 3 National Languages exercise set was not found.');
        }

        $activeIds = $rows->where('is_active', true)
            ->pluck('id')
            ->map(fn ($id): int => (int) $id)
            ->values()
            ->all();
        $initialIds = self::ALL_EXERCISE_IDS;
        $alignedIds = self::ACTIVE_EXERCISE_IDS;
        sort($initialIds);
        sort($alignedIds);
        if ($activeIds !== $initialIds && $activeIds !== $alignedIds) {
            throw new \RuntimeException('The active Class 3 National Languages set has changed unexpectedly.');
        }

        if (DB::table('exercise_attempts')->whereIn('exercise_id', self::ALL_EXERCISE_IDS)->exists()) {
            throw new \RuntimeException('A Class 3 National Languages exercise now has attempt history; alignment stopped.');
        }
    }

    private function assertHierarchyOwnership(int $subjectId): void
    {
        $expected = [
            243 => [162, 195],
            244 => [163, 196],
            245 => [164, 197],
            449 => [268, 381],
            450 => [268, 381],
            451 => [268, 382],
            452 => [268, 382],
            453 => [268, 383],
            454 => [268, 383],
        ];
        $rows = DB::table('lessons')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->whereIn('lessons.id', array_keys($expected))
            ->get([
                'lessons.id',
                'units.id as unit_id',
                'integrated_themes.id as theme_id',
                'integrated_themes.subject_id',
            ])
            ->keyBy('id');
        if ($rows->count() !== count($expected)) {
            throw new \RuntimeException('The expected National Languages hierarchy is incomplete.');
        }
        foreach ($expected as $lessonId => [$themeId, $unitId]) {
            $row = $rows[$lessonId];
            if ((int) $row->subject_id !== $subjectId
                || (int) $row->theme_id !== $themeId
                || (int) $row->unit_id !== $unitId) {
                throw new \RuntimeException("Lesson {$lessonId} is outside its expected hierarchy.");
            }
        }
    }

    private function alignHierarchy(): void
    {
        $themes = [
            162 => ['Language Foundations', 'Discover national languages, community language diversity and the role of the GACL.'],
            163 => ['Elements of Culture', 'Identify palaces, lamidats, traditional rulers, dress and marriage customs without treating one custom as universal.'],
            164 => ['Polite Communication and Life Events', 'Prepare respectful listening, requests and descriptions of birth, marriage and enthronement.'],
            268 => ['Oral Traditions and Performance', 'Explore stories, folktales, songs, proverbs and short cultural sketches.'],
        ];
        foreach ($themes as $id => [$name, $description]) {
            DB::table('integrated_themes')->where('id', $id)->update([
                'name' => $name,
                'description' => $description,
                'is_active' => true,
                'updated_at' => now(),
            ]);
        }

        $units = [
            195 => ['Languages and Family Words', 'Languages around the learner, the GACL and nuclear-family vocabulary.', 'Prepare with a trusted adult before practising in the family language.'],
            196 => ['Palaces, Rulers, Dress and Marriage', 'Cultural elements vary across the communities of Cameroon.', 'Observe, compare and respect cultural differences.'],
            197 => ['Politeness and Important Events', 'Respectful exchanges and descriptions of important life events.', 'Listen, take turns and use respectful words.'],
            381 => ['Stories, Songs and Sketches', 'Oral traditions and cultural performance.', 'Find the lesson in stories and understand how oral traditions are preserved.'],
        ];
        foreach ($units as $id => [$name, $description, $summary]) {
            DB::table('units')->where('id', $id)->update([
                'name' => $name,
                'description' => $description,
                'summary' => $summary,
                'is_active' => true,
                'updated_at' => now(),
            ]);
        }

        $lessons = [
            243 => ['Languages Around Us', 'Recognise language diversity and prepare to learn family-language words.', 'National-language foundations aligned to MINEDUB Level II page 85.'],
            244 => ['Elements of Culture', 'Identify cultural places, leaders, dress and marriage customs.', 'Culture identification aligned to MINEDUB Level II page 85.'],
            245 => ['Listen, Speak and Describe', 'Prepare polite exchanges and descriptions of important life events.', 'Communication preparation aligned to MINEDUB Level II page 86.'],
            449 => ['Oral Traditions and Sketches', 'Explore folktales, stories, songs, proverbs and acting.', 'Oral-tradition preparation aligned to MINEDUB Level II page 86.'],
        ];
        foreach ($lessons as $id => [$name, $description, $content]) {
            DB::table('lessons')->where('id', $id)->update([
                'name' => $name,
                'description' => $description,
                'content' => $content,
                'type' => 'mixed',
                'is_active' => true,
                'updated_at' => now(),
            ]);
        }
    }

    private function exerciseUpdates(): array
    {
        return [
            1397 => $this->exercise('Languages Around Us', 243, 'mcq', [
                'type' => 'mcq',
                'questions' => [
                    ['text' => 'What is a national language?', 'options' => ['A language rooted in a community and its culture', 'A secret code used by one child', 'Only English or French', 'A language used only on television'], 'answer' => 0],
                    ['text' => 'Who can help you identify the national language spoken in your family?', 'options' => ['A trusted family or community speaker', 'Only a foreign visitor', 'Nobody', 'A sports referee'], 'answer' => 0],
                ],
            ]),
            1398 => $this->exercise('Language Diversity', 243, 'true_false', [
                'type' => 'true_false',
                'statement' => 'Different communities in Cameroon may speak different national languages.',
                'answer' => true,
            ]),
            1399 => $this->exercise('Respect Every Language', 243, 'mcq', [
                'type' => 'mcq',
                'questions' => [
                    ['text' => 'What should you do when another learner speaks a different national language?', 'options' => ['Listen respectfully and show interest', 'Laugh at the learner', 'Say only your language matters', 'Interrupt every sentence'], 'answer' => 0],
                    ['text' => 'What is the safest way to learn a word in your family language?', 'options' => ['Ask a trusted fluent speaker and repeat carefully', 'Invent a word and call it correct', 'Copy an unrelated internet word', 'Guess without listening'], 'answer' => 0],
                ],
            ]),
            1400 => $this->exercise('The GACL', 243, 'mcq', [
                'type' => 'mcq',
                'questions' => [
                    ['text' => 'What does GACL mean?', 'options' => ['General Alphabet of Cameroonian Languages', 'Global Academy of Cultural Lessons', 'General Association of Classroom Leaders', 'Grammar and Culture List'], 'answer' => 0],
                    ['text' => 'Why is the GACL useful?', 'options' => ['It helps represent sounds when Cameroonian languages are written', 'It replaces every national language', 'It makes every language use the same words', 'It is a list of traditional rulers'], 'answer' => 0],
                ],
            ]),
            2872 => $this->exercise('Nuclear Family Words', 243, 'mcq', [
                'type' => 'mcq',
                'questions' => [
                    ['text' => 'Which group is a nuclear family?', 'options' => ['Parents and their children', 'Every person in the village', 'Only cousins', 'All market sellers'], 'answer' => 0],
                    ['text' => 'Before writing family words in a national language, what should you do?', 'options' => ['Listen to a fluent speaker and check the sounds', 'Translate by guessing', 'Use random letters', 'Copy English words unchanged'], 'answer' => 0],
                ],
            ]),
            1401 => $this->exercise('Palaces and Lamidats', 244, 'match_pairs', [
                'type' => 'match_pairs',
                'question' => 'Match each cultural element to its description.',
                'pairs' => [
                    ['Palace', 'Residence or official place of a traditional ruler'],
                    ['Lamidat', 'Traditional domain associated with a Lamido'],
                    ['Traditional attire', 'Clothing connected to a community and occasion'],
                    ['Traditional marriage', 'Marriage ceremony guided by community customs'],
                ],
            ]),
            1402 => $this->exercise('Traditional Rulers', 244, 'mcq', [
                'type' => 'mcq',
                'questions' => [
                    ['text' => 'Which names may be used for traditional rulers in Cameroon?', 'options' => ['Chief, fon or lamido, depending on the community', 'Only mayor', 'Only governor', 'Only head teacher'], 'answer' => 0],
                    ['text' => 'Why must we avoid using one title for every community?', 'options' => ['Titles and customs differ across communities', 'Every ruler has the same title', 'Titles have no meaning', 'Only cities have leaders'], 'answer' => 0],
                ],
            ]),
            1403 => $this->exercise('Traditional Dressing', 244, 'true_false', [
                'type' => 'true_false',
                'statement' => 'Traditional clothing can vary by community, region and ceremony.',
                'answer' => true,
            ]),
            1404 => $this->exercise('Traditional Marriage Ceremonies', 244, 'mcq', [
                'type' => 'mcq',
                'questions' => [
                    ['text' => 'What is a respectful way to learn about a traditional marriage ceremony?', 'options' => ['Ask community members and observe without judging', 'Assume every community follows one ceremony', 'Mock unfamiliar clothing', 'Invent rules for another community'], 'answer' => 0],
                    ['text' => 'Which statement is accurate?', 'options' => ['Marriage customs and symbols can differ between communities', 'Every Cameroonian marriage is identical', 'Traditional marriage never involves families', 'Only one region has marriage customs'], 'answer' => 0],
                ],
            ]),
            2873 => $this->exercise('Culture Is Diverse', 244, 'mcq', [
                'type' => 'mcq',
                'questions' => [
                    ['text' => 'Cameroon has many cultural communities. What does this mean?', 'options' => ['Customs may differ and should be described carefully', 'One custom represents everybody', 'Differences should be hidden', 'Only one culture is correct'], 'answer' => 0],
                    ['text' => 'Which sentence avoids a harmful generalisation?', 'options' => ['In some communities, people greet elders in a special way', 'All Cameroonians greet in exactly the same way', 'Every traditional ruler has the same title', 'Every marriage ceremony uses the same objects'], 'answer' => 0],
                ],
            ]),
            1405 => $this->exercise('Polite Responses', 245, 'match_pairs', [
                'type' => 'match_pairs',
                'question' => 'Match each situation to a polite response.',
                'pairs' => [
                    ['Someone greets you', 'Reply to the greeting'],
                    ['You make a request', 'Use a respectful request form'],
                    ['You make a mistake', 'Apologise sincerely'],
                    ['Someone helps you', 'Thank the person'],
                ],
            ]),
            1406 => $this->exercise('Listen and Take Turns', 245, 'mcq', [
                'type' => 'mcq',
                'questions' => [
                    ['text' => 'What should you do while another person is speaking?', 'options' => ['Listen attentively without interrupting', 'Speak louder than the person', 'Turn away and laugh', 'Change the subject immediately'], 'answer' => 0],
                    ['text' => 'When is it your turn to speak?', 'options' => ['After the other speaker finishes or invites you', 'While the other person is answering', 'Whenever you can shout', 'Only after leaving the room'], 'answer' => 0],
                ],
            ]),
            1407 => $this->exercise('Make a Respectful Request', 245, 'sentence_order', [
                'type' => 'sentence_order',
                'question' => 'Put the words in order to make a respectful request.',
                'words' => ['Please', 'may', 'I', 'join', 'the', 'conversation?'],
                'answer' => ['Please', 'may', 'I', 'join', 'the', 'conversation?'],
            ]),
            2874 => $this->exercise('Birth, Marriage and Enthronement', 245, 'match_pairs', [
                'type' => 'match_pairs',
                'question' => 'Match each life event to its meaning.',
                'pairs' => [
                    ['Birth', 'A child joins the family and community'],
                    ['Marriage', 'Families recognise a union according to their customs'],
                    ['Enthronement', 'A traditional ruler is installed according to community customs'],
                ],
            ]),
            2875 => $this->exercise('Describe a Life Event', 245, 'mcq', [
                'type' => 'mcq',
                'questions' => [
                    ['text' => 'What makes a description of a cultural event useful?', 'options' => ['It explains what happened, who took part and what it meant', 'It insults unfamiliar customs', 'It says every community is the same', 'It leaves out the event'], 'answer' => 0],
                    ['text' => 'How can a learner check a description of a family ceremony?', 'options' => ['Ask a trusted adult who knows the ceremony', 'Rely only on a guess', 'Copy an unrelated story', 'Add details that never happened'], 'answer' => 0],
                ],
            ]),
            2876 => $this->exercise('Stories, Folktales and Songs', 449, 'match_pairs', [
                'type' => 'match_pairs',
                'question' => 'Match each oral tradition to its description.',
                'pairs' => [
                    ['Story', 'A spoken account of events'],
                    ['Folktale', 'A traditional story that may teach a lesson'],
                    ['Song', 'Words performed with melody and rhythm'],
                    ['Proverb', 'A short saying that carries wisdom'],
                ],
            ]),
            2877 => $this->exercise('The Lesson in a Folktale', 449, 'mcq', [
                'type' => 'mcq',
                'questions' => [[
                    'text' => 'A child in a folktale refuses to share water. Later, nobody shares with the child. What lesson fits best?',
                    'options' => ['Treat others as you want to be treated', 'Never help anyone', 'Hide all water', 'Only strong people deserve care'],
                    'answer' => 0,
                ]],
            ]),
            2878 => $this->exercise('Act a Short Sketch', 449, 'mcq', [
                'type' => 'mcq',
                'questions' => [
                    ['text' => 'What should actors do before performing a short sketch?', 'options' => ['Know their roles and the order of the scene', 'All speak at the same time', 'Ignore the theme', 'Change every role during the scene'], 'answer' => 0],
                    ['text' => 'Which action shows respect during a cultural sketch?', 'options' => ['Represent people and customs carefully', 'Mock accents and clothing', 'Invent insults', 'Interrupt every actor'], 'answer' => 0],
                ],
            ]),
            2879 => $this->exercise('Keepers of Oral Tradition', 449, 'mcq', [
                'type' => 'mcq',
                'questions' => [
                    ['text' => 'Who may help preserve oral traditions?', 'options' => ['Elders, storytellers, singers and other community speakers', 'Only television presenters', 'Only visitors', 'Nobody'], 'answer' => 0],
                    ['text' => 'How can a learner help preserve a family story?', 'options' => ['Listen carefully, ask permission and retell it accurately', 'Change every detail', 'Laugh while the speaker talks', 'Claim the story belongs to every community'], 'answer' => 0],
                ],
            ]),
            2880 => $this->exercise('Show Love for Culture', 449, 'true_false', [
                'type' => 'true_false',
                'statement' => 'Listening to fluent speakers, learning respectful words and sharing stories carefully can help preserve culture.',
                'answer' => true,
            ]),
        ];
    }

    private function exercise(string $title, int $lessonId, string $contentType, array $content): array
    {
        $category = match ($contentType) {
            'mcq', 'true_false' => 'quiz',
            'match_pairs', 'sentence_order' => 'revision',
            default => throw new \InvalidArgumentException("Unsupported National Languages content type: {$contentType}"),
        };

        return [
            'title' => $title,
            'lesson_id' => $lessonId,
            'instructions' => 'Read carefully and complete every step.',
            'category' => $category,
            'difficulty' => 'easy',
            'estimated_minutes' => 8,
            'content' => $content,
        ];
    }

    private function updateExercise(int $exerciseId, array $update): void
    {
        $currentTitle = DB::table('exercises')->where('id', $exerciseId)->value('title');
        $allowedTitles = [self::ORIGINAL_TITLES[$exerciseId], $update['title']];
        if (! in_array($currentTitle, $allowedTitles, true)) {
            throw new \RuntimeException("Exercise {$exerciseId} title has changed.");
        }

        DB::table('exercises')->where('id', $exerciseId)->update([
            'lesson_id' => $update['lesson_id'],
            'title' => $update['title'],
            'instructions' => $update['instructions'],
            'category' => $update['category'],
            'difficulty' => $update['difficulty'],
            'estimated_minutes' => $update['estimated_minutes'],
            'content' => json_encode($update['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            'is_active' => true,
            'updated_at' => now(),
        ]);
    }

    private function removeImageReferences(array $exerciseIds): void
    {
        $rows = DB::table('exercises')->whereIn('id', $exerciseIds)->get(['id', 'content']);
        foreach ($rows as $row) {
            $content = json_decode((string) $row->content, true, flags: JSON_THROW_ON_ERROR);
            if (! array_key_exists('image_url', $content)) {
                continue;
            }
            unset($content['image_url']);
            DB::table('exercises')->where('id', $row->id)->update([
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
                'updated_at' => now(),
            ]);
        }
    }

    private function deactivateEmptyLessonHierarchy(int $lessonId): void
    {
        $path = DB::table('lessons')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->where('lessons.id', $lessonId)
            ->first(['lessons.unit_id', 'units.integrated_theme_id']);
        if (! $path) {
            throw new \RuntimeException("Lesson {$lessonId} was not found.");
        }
        if (DB::table('exercises')->where('lesson_id', $lessonId)->where('is_active', true)->exists()) {
            throw new \RuntimeException("Lesson {$lessonId} still has active exercises.");
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
    }

    private function competencies(): array
    {
        return [
            $this->competency('MINEDUB-L2-NLC-C3-LS-LANGUAGES', 'Name and identify national languages', 'Name the learner\'s national language and identify other languages spoken in the community or subdivision.', '85', 1, []),
            $this->competency('MINEDUB-L2-NLC-C3-LS-CULTURE', 'Identify elements of culture', 'Identify palaces, lamidats, traditional rulers, dressing and marriage as cultural elements while respecting differences between communities.', '85', 2, [1401, 1402, 1403, 1404, 2873]),
            $this->competency('MINEDUB-L2-NLC-C3-LS-FAMILY-GACL', 'Use GACL sounds in nuclear-family words', 'Identify GACL letters and sounds in nuclear-family words, replace syllables and complete missing syllables in the selected national language.', '85', 3, []),
            $this->competency('MINEDUB-L2-NLC-C3-LS-POLITE', 'Use polite forms in conversation', 'Use appropriate responses and requests, address people appropriately, listen attentively and speak in turns with suitable intonation.', '86', 4, []),
            $this->competency('MINEDUB-L2-NLC-C3-LS-LIFE-EVENTS', 'Describe significant life events', 'Use appropriate words to describe birth, marriage and enthronement, draw lessons from these events and show love for culture.', '86', 5, [2874, 2875]),
            $this->competency('MINEDUB-L2-NLC-C3-LS-SKETCHES', 'Act short cultural sketches', 'Act roles and perform short sketches based on familiar integrated learning themes.', '86', 6, []),
            $this->competency('MINEDUB-L2-NLC-C3-LS-ORAL-TRADITIONS', 'Perform oral traditions', 'Recount short stories, sing songs, narrate folktales and develop eloquence in the selected national language.', '86', 7, []),
            $this->competency('MINEDUB-L2-NLC-C3-READ-WORDS', 'Read multisyllabic words', 'Read simple words of two or more syllables correctly in the selected national language.', '87', 8, []),
            $this->competency('MINEDUB-L2-NLC-C3-READ-MESSAGES-NUMBERS', 'Read messages, pictures and numbers 1 to 30', 'Read short messages of at least two sentences, describe pictures and read numbers from 1 to 30.', '87', 9, []),
            $this->competency('MINEDUB-L2-NLC-C3-WRITE-MESSAGES', 'Write short messages', 'Write coherent short messages of at least two sentences in the selected national language.', '87', 10, []),
            $this->competency('MINEDUB-L2-NLC-C3-WRITE-WORDS-NUMBERS', 'Write words and numbers 1 to 30', 'Write words of two or more syllables and numbers from 1 to 30 correctly in the selected national language.', '87', 11, []),
            $this->competency('MINEDUB-L2-NLC-C3-GRAMMAR', 'Use nouns, verbs and adjectives', 'Use nouns, verbs and adjectives correctly in short messages of at least two sentences.', '88', 12, []),
            $this->competency('MINEDUB-L2-NLC-C3-DIALOGUE', 'Sustain a conversation', 'Handle a dialogue or conversation in the selected national language.', '88', 13, []),
            $this->competency('MINEDUB-L2-NLC-C3-SOUNDS-VOCABULARY', 'Recognise sounds and use vocabulary', 'Read simple words correctly and use new words from familiar themes in conversation.', '88', 14, []),
        ];
    }

    private function competency(
        string $code,
        string $name,
        string $description,
        string $pages,
        int $order,
        array $exerciseIds
    ): array {
        return [
            'official_code' => $code,
            'name' => $name,
            'description' => $description,
            'source_pages' => $pages,
            'order' => $order,
            'exercise_ids' => $exerciseIds,
        ];
    }

    private function upsertCompetency(int $subjectId, int $documentId, array $competency): int
    {
        unset($competency['exercise_ids']);
        $id = DB::table('school_competencies')
            ->where('subject_id', $subjectId)
            ->where('official_code', $competency['official_code'])
            ->value('id');
        $values = $competency + [
            'curriculum_document_id' => $documentId,
            'verification_status' => 'verified_source',
            'is_active' => true,
            'updated_at' => now(),
        ];
        if ($id) {
            DB::table('school_competencies')->where('id', $id)->update($values);
            return (int) $id;
        }

        return (int) DB::table('school_competencies')->insertGetId($values + [
            'subject_id' => $subjectId,
            'created_at' => now(),
        ]);
    }
}
