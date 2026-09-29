<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class3ReadingAlignmentPilotSeeder extends Seeder
{
    private const DOCUMENT_SHA256 = '1921b9d044f23849c1532f403021e0a8d23a46123d4b225b8b083b827e3fdd1c';

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

            $subjects = collect(['Reading', 'English', 'Science and Technology'])
                ->mapWithKeys(fn (string $name): array => [
                    $name => DB::table('subjects')
                        ->join('levels', 'subjects.level_id', '=', 'levels.id')
                        ->where('levels.name', 'Class 3')
                        ->where('levels.education_subsystem', 'anglophone')
                        ->where('subjects.name', $name)
                        ->value('subjects.id'),
                ]);
            if ($subjects->contains(fn ($id): bool => ! $id)) {
                throw new \RuntimeException('A required Class 3 subject was not found.');
            }

            $comprehensionIds = [1655, 1656, 2367, 2368, 2372, 3147, 3148];
            $duplicateId = 1657;
            $vocabularyIds = [1658, 1659, 1660, 2375, 2376, 2377, 2378, 2379, 2380, 2381, 2382, 2383, 2384];
            $grammarIds = [1661, 1662, 1663, 2385, 2386, 2387, 2388, 2389, 2390, 2391, 2392, 2393, 2394];
            $scienceIds = [2369, 2370, 2371, 2373, 2374];
            $allIds = collect($comprehensionIds)
                ->push($duplicateId)
                ->concat($vocabularyIds)
                ->concat($grammarIds)
                ->concat($scienceIds)
                ->sort()
                ->values();

            if ($allIds->count() !== 39 || $allIds->unique()->count() !== 39) {
                throw new \RuntimeException('The Class 3 Reading alignment set is invalid.');
            }
            $rows = DB::table('exercises')->whereIn('id', $allIds)->get()->keyBy('id');
            if ($rows->count() !== 39) {
                throw new \RuntimeException('The complete Class 3 Reading exercise set was not found.');
            }
            if (DB::table('exercise_attempts')->whereIn('exercise_id', $allIds)->exists()) {
                throw new \RuntimeException('A Class 3 Reading exercise now has attempt history; alignment stopped.');
            }
            $unexpectedInactive = $rows->filter(
                fn (object $row): bool => (int) $row->id !== $duplicateId && ! $row->is_active
            );
            if ($unexpectedInactive->isNotEmpty()) {
                throw new \RuntimeException('A Class 3 Reading alignment exercise is unexpectedly inactive.');
            }

            $this->assertExerciseSubjects($comprehensionIds, [$subjects['Reading']]);
            $this->assertExerciseSubjects([$duplicateId], [$subjects['Reading']]);
            $this->assertExerciseSubjects($vocabularyIds, [$subjects['Reading'], $subjects['English']]);
            $this->assertExerciseSubjects($grammarIds, [$subjects['Reading'], $subjects['English']]);
            $this->assertExerciseSubjects($scienceIds, [$subjects['Reading'], $subjects['Science and Technology']]);

            $this->assertLessonSubject(301, $subjects['Reading']);
            $this->assertLessonSubject(302, $subjects['Reading']);
            $this->assertLessonSubject(303, $subjects['Reading']);
            $this->assertLessonSubject(382, $subjects['English']);
            $this->assertLessonSubject(383, $subjects['English']);
            $this->assertLessonSubject(191, $subjects['Science and Technology']);

            $this->normaliseComprehensionExercises();

            DB::table('exercises')->whereIn('id', $comprehensionIds)->update([
                'lesson_id' => 301,
                'is_active' => true,
                'updated_at' => now(),
            ]);
            DB::table('exercises')->where('id', $duplicateId)->update([
                'lesson_id' => 301,
                'is_active' => false,
                'updated_at' => now(),
            ]);
            DB::table('exercises')->whereIn('id', $vocabularyIds)->update([
                'lesson_id' => 383,
                'is_active' => true,
                'updated_at' => now(),
            ]);
            DB::table('exercises')->whereIn('id', $grammarIds)->update([
                'lesson_id' => 382,
                'is_active' => true,
                'updated_at' => now(),
            ]);
            DB::table('exercises')->whereIn('id', $scienceIds)->update([
                'lesson_id' => 191,
                'is_active' => true,
                'updated_at' => now(),
            ]);

            $this->alignReadingHierarchy();
            $this->deactivateEmptyHierarchy(302);
            $this->deactivateEmptyHierarchy(303);

            $readingCompetencies = [
                ['MINEDUB-L2-READ-C3-ALOUD', 'Read words, pictures and short texts aloud', 'Read sight words, multisyllabic words, pictures and short texts audibly while respecting intonation.', '44', []],
                ['MINEDUB-L2-READ-C3-NUMBERS', 'Read and match numbers from 101 to 500', 'Read numbers from 101 to 500 in words and match figures to their corresponding words.', '44', []],
                ['MINEDUB-L2-READ-C3-COMPREHENSION', 'Read texts silently and answer questions', 'Read short stories and simple descriptions silently, retrieve information and answer comprehension questions.', '44', $comprehensionIds],
                ['MINEDUB-L2-READ-C3-LITERATURE', 'Respond to stories, prose, cartoons and rhymes', 'Read picture adventure stories and short texts, take a character point of view and deduce a moral lesson.', '44', []],
            ];

            $readingCompetencyIds = DB::table('school_competencies')
                ->where('subject_id', $subjects['Reading'])
                ->pluck('id');
            if ($readingCompetencyIds->isNotEmpty()) {
                DB::table('exercise_school_competency')
                    ->whereIn('exercise_id', $allIds)
                    ->whereIn('school_competency_id', $readingCompetencyIds)
                    ->delete();
            }
            foreach ($readingCompetencies as $order => [$code, $name, $description, $pages, $exerciseIds]) {
                $competencyId = $this->upsertCompetency(
                    $subjects['Reading'],
                    $documentId,
                    $code,
                    $name,
                    $description,
                    $pages,
                    $order + 1
                );
                $this->linkExercises($competencyId, $exerciseIds);
            }

            $englishCompetencies = [
                ['MINEDUB-L2-ENG-C3-NOUNS-VERBS', 'Identify and use nouns and verbs in sentences', 'Identify common and proper nouns, distinguish verbs and use nouns and verbs in simple sentences.', '46', $grammarIds, 4],
                ['MINEDUB-L2-ENG-C3-VOCABULARY', 'Use vocabulary, synonyms and opposites in context', 'Use a variety of words and choose correct synonyms and opposites in familiar contexts.', '46, 48', $vocabularyIds, 5],
            ];
            foreach ($englishCompetencies as [$code, $name, $description, $pages, $exerciseIds, $order]) {
                $competencyId = $this->upsertCompetency(
                    $subjects['English'],
                    $documentId,
                    $code,
                    $name,
                    $description,
                    $pages,
                    $order
                );
                $this->linkExercises($competencyId, $exerciseIds);
            }

            $scienceCompetencyId = DB::table('school_competencies')
                ->where('subject_id', $subjects['Science and Technology'])
                ->where('official_code', 'MINEDUB-L2-SCI-C3-ENV-LIVING')
                ->where('verification_status', 'verified_source')
                ->value('id');
            if (! $scienceCompetencyId) {
                throw new \RuntimeException('The verified Class 3 animals competency was not found.');
            }
            $this->linkExercises($scienceCompetencyId, [2370, 2371, 2373, 2374]);
        });
    }

    private function assertExerciseSubjects(array $exerciseIds, array $allowedSubjectIds): void
    {
        $rows = DB::table('exercises')
            ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->whereIn('exercises.id', $exerciseIds)
            ->get(['exercises.id', 'integrated_themes.subject_id']);
        if ($rows->count() !== count($exerciseIds)
            || $rows->contains(fn (object $row): bool => ! in_array((int) $row->subject_id, $allowedSubjectIds, true))) {
            throw new \RuntimeException('A Class 3 Reading exercise is outside its expected source or target subject.');
        }
    }

    private function assertLessonSubject(int $lessonId, int $subjectId): void
    {
        $exists = DB::table('lessons')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->where('lessons.id', $lessonId)
            ->where('integrated_themes.subject_id', $subjectId)
            ->exists();
        if (! $exists) {
            throw new \RuntimeException("Lesson {$lessonId} is not owned by the expected subject.");
        }
    }

    private function normaliseComprehensionExercises(): void
    {
        $updates = [
            1655 => [
                'allowed_titles' => ['Comprehension C3-1', "The Cat's Green Eyes"],
                'title' => "The Cat's Green Eyes",
                'content' => [
                    'type' => 'multiple_choice',
                    'passage' => 'The cat sat on the mat. It was a big black cat. It had green eyes and a long tail.',
                    'questions' => [[
                        'question' => "What colour were the cat's eyes?",
                        'options' => ['Blue', 'Brown', 'Green', 'Yellow'],
                        'answer' => 2,
                    ]],
                ],
            ],
            1656 => [
                'allowed_titles' => ['Comprehension C3-2', 'Ambe at the Market'],
                'title' => 'Ambe at the Market',
                'content' => [
                    'type' => 'multiple_choice',
                    'passage' => 'Ambe went to the market with his mother. They bought tomatoes, onions and fish. Ambe carried the small basket on their way home.',
                    'questions' => [
                        ['question' => 'How many different items did they buy?', 'options' => ['2', '3', '4', '5'], 'answer' => 1],
                        ['question' => 'Did Ambe go to the market alone?', 'options' => ['Yes, he went alone', 'No, he went with his mother'], 'answer' => 1],
                    ],
                ],
            ],
        ];

        foreach ($updates as $exerciseId => $update) {
            $currentTitle = DB::table('exercises')->where('id', $exerciseId)->value('title');
            if (! in_array($currentTitle, $update['allowed_titles'], true)) {
                throw new \RuntimeException("Exercise {$exerciseId} title has changed.");
            }
            DB::table('exercises')->where('id', $exerciseId)->update([
                'title' => $update['title'],
                'instructions' => 'Read the passage and answer every question.',
                'category' => 'reading',
                'content' => json_encode($update['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
                'updated_at' => now(),
            ]);
        }

        $shortTitles = [
            2367 => ["Read: \"The elephant is the largest land animal. It uses its trunk to pick up food and water. Elephants live in herds and protect each other.\" What does the elephant use its trunk for?", "The Elephant's Trunk"],
            2368 => ["Read: \"Frogs are amphibians. They can live both on land and in water. Frogs lay their eggs in water. Tadpoles hatch from the eggs and slowly grow into frogs.\" Where do frogs lay their eggs?", 'Where Frogs Lay Their Eggs'],
            2372 => ["Read: \"The honeybee collects nectar from flowers to make honey. As it moves from flower to flower, it carries pollen and helps plants reproduce. Without bees, many plants could not survive.\" Why are bees important for plants?", 'Why Bees Matter'],
        ];
        foreach ($shortTitles as $exerciseId => [$original, $desired]) {
            $currentTitle = DB::table('exercises')->where('id', $exerciseId)->value('title');
            if (! in_array($currentTitle, [$original, $desired], true)) {
                throw new \RuntimeException("Exercise {$exerciseId} title has changed.");
            }
            DB::table('exercises')->where('id', $exerciseId)->update([
                'title' => $desired,
                'updated_at' => now(),
            ]);
        }
    }

    private function alignReadingHierarchy(): void
    {
        $path = DB::table('lessons')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->where('lessons.id', 301)
            ->first(['lessons.unit_id', 'units.integrated_theme_id']);
        if (! $path) {
            throw new \RuntimeException('The Class 3 Reading comprehension hierarchy was not found.');
        }

        DB::table('integrated_themes')->where('id', $path->integrated_theme_id)->update([
            'name' => 'Reading Comprehension',
            'description' => 'Read short stories and simple descriptions silently and answer questions.',
            'is_active' => true,
            'updated_at' => now(),
        ]);
        DB::table('units')->where('id', $path->unit_id)->update([
            'name' => 'Short Stories and Simple Descriptions',
            'description' => 'Literal information, vocabulary in context and simple inference from age-appropriate texts.',
            'summary' => 'Read the complete text, return to the passage for evidence and answer every question.',
            'is_active' => true,
            'updated_at' => now(),
        ]);
        DB::table('lessons')->where('id', 301)->update([
            'name' => 'Read Silently and Answer Questions',
            'description' => 'Short Class 3 passages followed by server-checked comprehension questions.',
            'content' => 'Reading comprehension aligned to the official MINEDUB Level II curriculum, page 44.',
            'type' => 'reading',
            'is_active' => true,
            'updated_at' => now(),
        ]);
    }

    private function deactivateEmptyHierarchy(int $lessonId): void
    {
        $path = DB::table('lessons')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->where('lessons.id', $lessonId)
            ->first(['lessons.unit_id', 'units.integrated_theme_id']);
        if (! $path) {
            throw new \RuntimeException("Reading lesson {$lessonId} was not found.");
        }
        if (DB::table('exercises')->where('lesson_id', $lessonId)->where('is_active', true)->exists()) {
            throw new \RuntimeException("Reading lesson {$lessonId} is not empty.");
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

        $themeHasActiveExercises = DB::table('exercises')
            ->join('lessons', 'exercises.lesson_id', '=', 'lessons.id')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->where('units.integrated_theme_id', $path->integrated_theme_id)
            ->where('exercises.is_active', true)
            ->exists();
        if (! $themeHasActiveExercises) {
            DB::table('integrated_themes')->where('id', $path->integrated_theme_id)->update(['is_active' => false, 'updated_at' => now()]);
        }
    }

    private function upsertCompetency(
        int $subjectId,
        int $documentId,
        string $code,
        string $name,
        string $description,
        string $pages,
        int $order
    ): int {
        $id = DB::table('school_competencies')
            ->where('subject_id', $subjectId)
            ->where('official_code', $code)
            ->value('id');
        $values = [
            'curriculum_document_id' => $documentId,
            'name' => $name,
            'description' => $description,
            'source_pages' => $pages,
            'verification_status' => 'verified_source',
            'order' => $order,
            'is_active' => true,
            'updated_at' => now(),
        ];
        if ($id) {
            DB::table('school_competencies')->where('id', $id)->update($values);
            return (int) $id;
        }
        return (int) DB::table('school_competencies')->insertGetId($values + [
            'subject_id' => $subjectId,
            'official_code' => $code,
            'created_at' => now(),
        ]);
    }

    private function linkExercises(int $competencyId, array $exerciseIds): void
    {
        foreach ($exerciseIds as $exerciseId) {
            DB::table('exercise_school_competency')->updateOrInsert([
                'exercise_id' => $exerciseId,
                'school_competency_id' => $competencyId,
            ]);
        }
    }
}
