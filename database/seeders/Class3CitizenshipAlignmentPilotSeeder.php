<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class3CitizenshipAlignmentPilotSeeder extends Seeder
{
    private const DOCUMENT_SHA256 = '1921b9d044f23849c1532f403021e0a8d23a46123d4b225b8b083b827e3fdd1c';

    private const CORE_EXERCISE_IDS = [735, 736, 737, 738, 739, 740, 741, 742, 743, 744];

    private const MOVED_THEMES = [
        'rights-and-duties-54' => [1340, 1341, 1342],
        'moral-education-54' => [2832, 2833, 2834, 2835, 2836],
        'human-rights-and-peace-54' => [2837, 2838, 2839, 2840, 2841, 2842],
        'civics-and-governance-54' => [2843, 2844, 2845, 2846, 2847, 2848],
    ];

    private const ALIGNED_THEME_ADDITIONS = [
        'rights-and-duties-54' => [],
        'moral-education-54' => [740, 741],
        'human-rights-and-peace-54' => [742, 743, 744],
        'civics-and-governance-54' => [738, 739],
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

            $subjects = collect(['Citizenship', 'Social Studies'])
                ->mapWithKeys(fn (string $name): array => [
                    $name => DB::table('subjects')
                        ->join('levels', 'subjects.level_id', '=', 'levels.id')
                        ->where('levels.name', 'Class 3')
                        ->where('levels.education_subsystem', 'anglophone')
                        ->where('subjects.name', $name)
                        ->value('subjects.id'),
                ]);
            if ($subjects->contains(fn ($id): bool => ! $id)) {
                throw new \RuntimeException('The Class 3 Citizenship/Social Studies subject pair was not found.');
            }

            $this->assertCoreExerciseState((int) $subjects['Citizenship']);
            $this->moveCitizenshipThemes(
                (int) $subjects['Social Studies'],
                (int) $subjects['Citizenship']
            );

            $rulesLessonId = $this->upsertRulesAndAuthoritiesLesson((int) $subjects['Citizenship']);
            $this->rewriteCoreExercises($rulesLessonId);
            $this->removeMisleadingImage(1342, '/storage/images/edu/rights.png');
            $this->removeMisleadingImage(2838, '/storage/images/edu/rights.png');

            foreach ([158, 159] as $lessonId) {
                $this->deactivateEmptyLessonHierarchy($lessonId);
            }

            $competencies = [
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-CIVICS-EMBLEMS',
                    'name' => 'National emblems',
                    'description' => 'Explain the importance of national emblems, sing the National Anthem and practise love for the nation.',
                    'source_pages' => '73',
                    'order' => 1,
                    'exercise_ids' => [735, 736, 737],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-CIVICS-RULES',
                    'name' => 'Rules and regulations',
                    'description' => 'Follow rules and regulations at home, at school, in the community and in the state.',
                    'source_pages' => '73',
                    'order' => 2,
                    'exercise_ids' => [738],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-CIVICS-INSTITUTIONS',
                    'name' => 'State institutions and personalities',
                    'description' => 'Identify state and local authorities and explain administrative, traditional and religious structures.',
                    'source_pages' => '74',
                    'order' => 3,
                    'exercise_ids' => [739],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-CIVICS-ELECTIONS',
                    'name' => 'Elections and fair decisions',
                    'description' => 'Describe types of elections, explain the electoral process and cherish fair decisions.',
                    'source_pages' => '74',
                    'order' => 4,
                    'exercise_ids' => [1340, 2843, 2844, 2845],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-CIVICS-INTERNATIONAL',
                    'name' => 'National and international institutions',
                    'description' => 'Recognise national and international institutions that assist schools and explain how they assist others.',
                    'source_pages' => '74',
                    'order' => 5,
                    'exercise_ids' => [2846, 2847, 2848],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-MORAL-VALUES',
                    'name' => 'Universal values and responsible citizenship',
                    'description' => 'Practise simple etiquette, explain ethical values and promote responsible citizenship.',
                    'source_pages' => '75',
                    'order' => 6,
                    'exercise_ids' => [740, 2832, 2833, 2834],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-MORAL-COMMON-GOOD',
                    'name' => 'The common good',
                    'description' => 'Identify public property, use community property appropriately and volunteer for the family and community.',
                    'source_pages' => '75',
                    'order' => 7,
                    'exercise_ids' => [741, 2835, 2836],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-HUMAN-RIGHTS',
                    'name' => "Children's rights and duties",
                    'description' => "Explain a child's rights and duties and denounce acts of abuse.",
                    'source_pages' => '76',
                    'order' => 8,
                    'exercise_ids' => [742, 743, 1342, 2837, 2838, 2839],
                ],
                [
                    'official_code' => 'MINEDUB-L2-SOC-C3-PEACE-SECURITY',
                    'name' => 'Peace and security',
                    'description' => 'Identify people and institutions that promote peace and security and assist peaceful living at school and at home.',
                    'source_pages' => '76',
                    'order' => 9,
                    'exercise_ids' => [744, 2840, 2841, 2842],
                ],
            ];

            $allExerciseIds = collect(self::CORE_EXERCISE_IDS)
                ->concat(collect(self::MOVED_THEMES)->flatten())
                ->unique()
                ->sort()
                ->values();
            if ($allExerciseIds->count() !== 30) {
                throw new \RuntimeException('The Citizenship alignment exercise set is invalid.');
            }
            $this->assertExerciseOwnership($allExerciseIds->all(), (int) $subjects['Citizenship']);

            $competencyIds = [];
            foreach ($competencies as $competency) {
                $exerciseIds = $competency['exercise_ids'];
                unset($competency['exercise_ids']);
                $competencyId = $this->upsertCompetency(
                    (int) $subjects['Citizenship'],
                    (int) $subjects['Social Studies'],
                    (int) $documentId,
                    $competency
                );
                $competencyIds[$competency['official_code']] = $competencyId;
                $competency['exercise_ids'] = $exerciseIds;
            }

            DB::table('exercise_school_competency')
                ->whereIn('exercise_id', $allExerciseIds)
                ->whereIn('school_competency_id', array_values($competencyIds))
                ->delete();

            foreach ($competencies as $competency) {
                $competencyId = $competencyIds[$competency['official_code']];
                foreach ($competency['exercise_ids'] as $exerciseId) {
                    DB::table('exercise_school_competency')->insert([
                        'exercise_id' => $exerciseId,
                        'school_competency_id' => $competencyId,
                    ]);
                }
            }
        });
    }

    private function assertCoreExerciseState(int $citizenshipSubjectId): void
    {
        $rows = DB::table('exercises')
            ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->whereIn('exercises.id', self::CORE_EXERCISE_IDS)
            ->get(['exercises.id', 'exercises.is_active', 'integrated_themes.subject_id']);
        if ($rows->count() !== count(self::CORE_EXERCISE_IDS)
            || $rows->contains(fn (object $row): bool => ! $row->is_active || (int) $row->subject_id !== $citizenshipSubjectId)) {
            throw new \RuntimeException('The complete active Class 3 Citizenship core set was not found.');
        }
        if (DB::table('exercise_attempts')->whereIn('exercise_id', self::CORE_EXERCISE_IDS)->exists()) {
            throw new \RuntimeException('A core Citizenship exercise now has attempt history; content rewrite stopped.');
        }
    }

    private function moveCitizenshipThemes(int $socialStudiesSubjectId, int $citizenshipSubjectId): void
    {
        foreach (self::MOVED_THEMES as $slug => $expectedExerciseIds) {
            $theme = DB::table('integrated_themes')->where('slug', $slug)->first(['id', 'subject_id']);
            if (! $theme || ! in_array((int) $theme->subject_id, [$socialStudiesSubjectId, $citizenshipSubjectId], true)) {
                throw new \RuntimeException("Theme {$slug} is outside its expected subject.");
            }

            $actualExerciseIds = DB::table('exercises')
                ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
                ->join('units', 'lessons.unit_id', '=', 'units.id')
                ->where('units.integrated_theme_id', $theme->id)
                ->where('exercises.is_active', true)
                ->orderBy('exercises.id')
                ->pluck('exercises.id')
                ->map(fn ($id): int => (int) $id)
                ->all();
            sort($expectedExerciseIds);
            $alignedExerciseIds = array_merge(
                $expectedExerciseIds,
                self::ALIGNED_THEME_ADDITIONS[$slug] ?? []
            );
            sort($alignedExerciseIds);
            if ($actualExerciseIds !== $expectedExerciseIds && $actualExerciseIds !== $alignedExerciseIds) {
                throw new \RuntimeException("Theme {$slug} no longer owns its expected active exercise set.");
            }

            DB::table('integrated_themes')->where('id', $theme->id)->update([
                'subject_id' => $citizenshipSubjectId,
                'updated_at' => now(),
            ]);
        }
    }

    private function upsertRulesAndAuthoritiesLesson(int $citizenshipSubjectId): int
    {
        $themeId = DB::table('integrated_themes')
            ->where('subject_id', $citizenshipSubjectId)
            ->where('slug', 'civics-and-governance-54')
            ->value('id');
        if (! $themeId) {
            throw new \RuntimeException('The moved Civics and Governance theme was not found.');
        }

        $unitId = DB::table('units')
            ->where('integrated_theme_id', $themeId)
            ->where('slug', 'rules-and-authorities-class-3')
            ->value('id');
        $unitValues = [
            'name' => 'Rules and Authorities',
            'description' => 'Rules at home, school and in the community, and the authorities who serve these places.',
            'summary' => 'Follow rules and recognise administrative, traditional and religious authorities.',
            'order' => 1,
            'estimated_weeks' => 1,
            'is_active' => true,
            'updated_at' => now(),
        ];
        if ($unitId) {
            DB::table('units')->where('id', $unitId)->update($unitValues);
        } else {
            $unitId = DB::table('units')->insertGetId($unitValues + [
                'integrated_theme_id' => $themeId,
                'slug' => 'rules-and-authorities-class-3',
                'created_at' => now(),
            ]);
        }

        $lessonId = DB::table('lessons')
            ->where('unit_id', $unitId)
            ->where('slug', 'following-rules-and-respecting-authorities-class-3')
            ->value('id');
        $lessonValues = [
            'name' => 'Following Rules and Respecting Authorities',
            'description' => 'Choose responsible actions and identify authorities in familiar settings.',
            'content' => 'Civics practice aligned to the official MINEDUB Level II curriculum.',
            'order' => 1,
            'estimated_minutes' => 25,
            'type' => 'mixed',
            'is_active' => true,
            'updated_at' => now(),
        ];
        if ($lessonId) {
            DB::table('lessons')->where('id', $lessonId)->update($lessonValues);
            return (int) $lessonId;
        }

        return (int) DB::table('lessons')->insertGetId($lessonValues + [
            'unit_id' => $unitId,
            'slug' => 'following-rules-and-respecting-authorities-class-3',
            'created_at' => now(),
        ]);
    }

    private function rewriteCoreExercises(int $rulesLessonId): void
    {
        $updates = [
            735 => [
                'allowed_titles' => ['National flag', 'Why National Emblems Matter'],
                'title' => 'Why National Emblems Matter',
                'lesson_id' => 157,
                'content' => [
                    'type' => 'mcq',
                    'image_url' => '/storage/images/edu/flag.png',
                    'questions' => [
                        ['text' => 'Why are national emblems important?', 'options' => ['They represent the nation and unite its people', 'They belong to one family', 'They are only decorations', 'They replace school rules'], 'answer' => 0],
                        ['text' => 'Which description matches the Cameroon flag?', 'options' => ['Green, red and yellow with a yellow star', 'Blue and white with two stars', 'Red and black with no star', 'Green and white with a moon'], 'answer' => 0],
                    ],
                    'illustration' => '🇨🇲',
                ],
            ],
            736 => [
                'allowed_titles' => ['National anthem', 'Respect the National Anthem'],
                'title' => 'Respect the National Anthem',
                'lesson_id' => 157,
                'content' => [
                    'type' => 'mcq',
                    'questions' => [
                        ['text' => 'What should you do when the National Anthem begins at school?', 'options' => ['Stand respectfully and sing when asked', 'Keep talking loudly', 'Run out of the assembly', 'Laugh at other learners'], 'answer' => 0],
                        ['text' => 'Singing the National Anthem respectfully shows ___.', 'options' => ['love for the nation', 'fear of the flag', 'interest in sport only', 'a wish to leave school'], 'answer' => 0],
                    ],
                    'illustration' => '🇨🇲',
                ],
            ],
            737 => [
                'allowed_titles' => ['Flag fact', 'The Seal and Motto'],
                'title' => 'The Seal and Motto',
                'lesson_id' => 157,
                'content' => [
                    'type' => 'mcq',
                    'questions' => [
                        ['text' => "What is Cameroon's national motto?", 'options' => ['Peace - Work - Fatherland', 'Unity - Freedom - Justice', 'Work - Peace - School', 'Liberty - Equality - Unity'], 'answer' => 0],
                        ['text' => 'What is the national seal?', 'options' => ['An official emblem used by the state', 'A classroom timetable', 'A traditional dance', 'A football trophy'], 'answer' => 0],
                    ],
                    'illustration' => '🇨🇲',
                ],
            ],
            738 => [
                'allowed_titles' => ['Capital', 'Follow the Rules'],
                'title' => 'Follow the Rules',
                'lesson_id' => $rulesLessonId,
                'content' => [
                    'type' => 'mcq',
                    'questions' => [
                        ['text' => 'The bell rings for class. What is the responsible action?', 'options' => ["Enter calmly and follow the teacher's instructions", 'Keep playing until someone shouts', 'Push others at the door', 'Hide from the teacher'], 'answer' => 0],
                        ['text' => 'Why do homes, schools and communities have rules?', 'options' => ['To keep people safe and organised', 'To stop everyone from helping', 'To let one person do anything', 'To make every activity difficult'], 'answer' => 0],
                    ],
                    'illustration' => '📋',
                ],
            ],
            739 => [
                'allowed_titles' => ['Respect', 'Authorities in the Community'],
                'title' => 'Authorities in the Community',
                'lesson_id' => $rulesLessonId,
                'content' => [
                    'type' => 'mcq',
                    'questions' => [
                        ['text' => 'Who is a traditional authority in many Cameroonian communities?', 'options' => ['A chief or fon', 'A football captain', 'A shop customer', 'A school prefect only'], 'answer' => 0],
                        ['text' => 'How should learners treat responsible authorities?', 'options' => ['Listen respectfully and follow lawful instructions', 'Insult them', 'Ignore every instruction', 'Spread false stories about them'], 'answer' => 0],
                    ],
                    'illustration' => '🏛️',
                ],
            ],
            740 => [
                'allowed_titles' => ['Honesty', 'Greetings and Apologies'],
                'title' => 'Greetings and Apologies',
                'lesson_id' => 435,
                'content' => [
                    'type' => 'mcq',
                    'questions' => [
                        ['text' => 'You bump into a classmate by mistake. What should you say?', 'options' => ['I am sorry', 'Move away', 'It is your fault', 'Do not speak to me'], 'answer' => 0],
                        ['text' => 'You meet an elder in the morning. What should you do?', 'options' => ['Greet the elder politely', 'Turn away silently', 'Laugh and run', 'Demand a gift'], 'answer' => 0],
                    ],
                    'illustration' => '👋',
                ],
            ],
            741 => [
                'allowed_titles' => ['Rules', 'Care for Public Property'],
                'title' => 'Care for Public Property',
                'lesson_id' => 436,
                'content' => [
                    'type' => 'mcq',
                    'questions' => [
                        ['text' => 'Which item is public property that learners should protect?', 'options' => ['A classroom desk', "One learner's private toy", 'A family toothbrush', 'A personal birthday card'], 'answer' => 0],
                        ['text' => 'Which action helps the common good?', 'options' => ['Volunteering to clean the school compound', 'Breaking a community tap', 'Writing on library books', 'Throwing rubbish on the road'], 'answer' => 0],
                    ],
                    'illustration' => '🏫',
                ],
            ],
            742 => [
                'allowed_titles' => ['Right to education', "Children's Rights"],
                'title' => "Children's Rights",
                'lesson_id' => 437,
                'content' => [
                    'type' => 'mcq',
                    'questions' => [
                        ['text' => 'Which pair contains two rights of every child?', 'options' => ['Education and protection from abuse', 'Driving and voting', 'Owning a factory and a car', 'Skipping school and damaging property'], 'answer' => 0],
                        ['text' => 'What should a child do when someone threatens or abuses them?', 'options' => ['Tell a trusted adult or responsible authority', 'Stay silent forever', 'Threaten a younger child', 'Hide the problem from everyone'], 'answer' => 0],
                    ],
                    'illustration' => '🛡️',
                ],
            ],
            743 => [
                'allowed_titles' => ['Rights fact', "Children's Duties"],
                'title' => "Children's Duties",
                'lesson_id' => 437,
                'content' => [
                    'type' => 'mcq',
                    'questions' => [
                        ['text' => 'Which action is a duty of a learner?', 'options' => ['Respect the rights of others and protect school property', 'Prevent others from learning', 'Damage books after reading', 'Ignore every fair rule'], 'answer' => 0],
                        ['text' => 'The right to education comes with the duty to ___.', 'options' => ['attend school and learn responsibly', 'keep other children out of class', 'refuse every assignment', 'destroy classroom materials'], 'answer' => 0],
                    ],
                    'illustration' => '✅',
                ],
            ],
            744 => [
                'allowed_titles' => ['UNICEF', 'Peace and Safety'],
                'title' => 'Peace and Safety',
                'lesson_id' => 438,
                'content' => [
                    'type' => 'mcq',
                    'questions' => [
                        ['text' => 'What is the best first step when two classmates disagree?', 'options' => ['Speak calmly and listen to each other', 'Start a fight', 'Invite others to insult them', 'Damage their belongings'], 'answer' => 0],
                        ['text' => 'What should you do when you notice a serious danger at school?', 'options' => ['Report it to a trusted adult or responsible authority', 'Hide it from everyone', 'Encourage others to go closer', 'Make the danger worse'], 'answer' => 0],
                    ],
                    'illustration' => '🕊️',
                ],
            ],
        ];

        foreach ($updates as $exerciseId => $update) {
            $row = DB::table('exercises')->where('id', $exerciseId)->first(['title']);
            if (! $row || ! in_array($row->title, $update['allowed_titles'], true)) {
                throw new \RuntimeException("Exercise {$exerciseId} title has changed.");
            }
            DB::table('exercises')->where('id', $exerciseId)->update([
                'lesson_id' => $update['lesson_id'],
                'title' => $update['title'],
                'instructions' => 'Choose the best answer for every question.',
                'category' => 'revision',
                'difficulty' => 'easy',
                'estimated_minutes' => 5,
                'content' => json_encode($update['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
                'is_active' => true,
                'updated_at' => now(),
            ]);
        }
    }

    private function removeMisleadingImage(int $exerciseId, string $expectedImage): void
    {
        $row = DB::table('exercises')->where('id', $exerciseId)->first(['content']);
        if (! $row) {
            throw new \RuntimeException("Exercise {$exerciseId} was not found.");
        }
        $content = json_decode((string) $row->content, true, flags: JSON_THROW_ON_ERROR);
        $currentImage = $content['image_url'] ?? null;
        if ($currentImage !== null && $currentImage !== $expectedImage) {
            throw new \RuntimeException("Exercise {$exerciseId} references an unexpected image.");
        }
        if ($currentImage === null) {
            return;
        }
        unset($content['image_url']);
        DB::table('exercises')->where('id', $exerciseId)->update([
            'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            'updated_at' => now(),
        ]);
    }

    private function deactivateEmptyLessonHierarchy(int $lessonId): void
    {
        if (DB::table('exercises')->where('lesson_id', $lessonId)->where('is_active', true)->exists()) {
            return;
        }

        $lesson = DB::table('lessons')->where('id', $lessonId)->first(['unit_id']);
        if (! $lesson) {
            return;
        }
        DB::table('lessons')->where('id', $lessonId)->update(['is_active' => false, 'updated_at' => now()]);

        if (DB::table('lessons')->where('unit_id', $lesson->unit_id)->where('is_active', true)->exists()) {
            return;
        }
        $unit = DB::table('units')->where('id', $lesson->unit_id)->first(['integrated_theme_id']);
        if (! $unit) {
            return;
        }
        DB::table('units')->where('id', $lesson->unit_id)->update(['is_active' => false, 'updated_at' => now()]);

        if (! DB::table('units')->where('integrated_theme_id', $unit->integrated_theme_id)->where('is_active', true)->exists()) {
            DB::table('integrated_themes')->where('id', $unit->integrated_theme_id)->update(['is_active' => false, 'updated_at' => now()]);
        }
    }

    private function assertExerciseOwnership(array $exerciseIds, int $subjectId): void
    {
        $rows = DB::table('exercises')
            ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->whereIn('exercises.id', $exerciseIds)
            ->where('exercises.is_active', true)
            ->get(['exercises.id', 'integrated_themes.subject_id']);
        if ($rows->count() !== count($exerciseIds)
            || $rows->contains(fn (object $row): bool => (int) $row->subject_id !== $subjectId)) {
            throw new \RuntimeException('The final Citizenship exercise set is incomplete or cross-subject.');
        }
    }

    private function upsertCompetency(
        int $citizenshipSubjectId,
        int $socialStudiesSubjectId,
        int $documentId,
        array $competency
    ): int {
        $matches = DB::table('school_competencies')
            ->where('official_code', $competency['official_code'])
            ->get(['id', 'subject_id']);
        if ($matches->count() > 1) {
            throw new \RuntimeException("Competency {$competency['official_code']} is duplicated.");
        }
        if ($matches->isNotEmpty()
            && ! in_array((int) $matches->first()->subject_id, [$citizenshipSubjectId, $socialStudiesSubjectId], true)) {
            throw new \RuntimeException("Competency {$competency['official_code']} belongs to an unexpected subject.");
        }

        $values = [
            'subject_id' => $citizenshipSubjectId,
            'curriculum_document_id' => $documentId,
            'name' => $competency['name'],
            'description' => $competency['description'],
            'official_code' => $competency['official_code'],
            'source_pages' => $competency['source_pages'],
            'verification_status' => 'verified_source',
            'order' => $competency['order'],
            'is_active' => true,
            'updated_at' => now(),
        ];
        if ($matches->isNotEmpty()) {
            $id = (int) $matches->first()->id;
            DB::table('school_competencies')->where('id', $id)->update($values);
            return $id;
        }

        return (int) DB::table('school_competencies')->insertGetId($values + [
            'created_at' => now(),
        ]);
    }
}
