<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewEnginesSampleSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMatchPairs();
        $this->seedSentenceOrder();
        $this->seedTrueFalse();
        $this->seedClockReading();
        $this->command->info('✅ Exercices nouveaux moteurs seedés');
    }

    private function getLessonId(int $subjectId): ?int
    {
        return DB::table('lessons')
            ->join('units', 'lessons.unit_id', '=', 'units.id')
            ->join('integrated_themes', 'units.integrated_theme_id', '=', 'integrated_themes.id')
            ->where('integrated_themes.subject_id', $subjectId)
            ->value('lessons.id');
    }

    private function ins(int $lessonId, string $title, string $category, array $content): void
    {
        DB::table('exercises')->insert([
            'lesson_id'  => $lessonId,
            'title'      => $title,
            'category'   => $category,
            'content'    => json_encode($content),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function seedMatchPairs(): void
    {
        $id = $this->getLessonId(12); // English C1
        if (!$id) { $this->command->warn('Lesson English C1 introuvable'); return; }

        $this->ins($id, 'Match animals', 'vocabulary', [
            'type' => 'match_pairs', 'illustration' => '🐾',
            'question' => 'Relie chaque mot à son image.',
            'pairs' => [
                ['word' => 'Cat',  'image' => '🐱'],
                ['word' => 'Dog',  'image' => '🐕'],
                ['word' => 'Bird', 'image' => '🐦'],
                ['word' => 'Fish', 'image' => '🐟'],
            ],
        ]);

        $id3 = $this->getLessonId(24); // English C3
        if ($id3) {
            $this->ins($id3, 'Match body parts', 'vocabulary', [
                'type' => 'match_pairs', 'illustration' => '👁️',
                'question' => 'Match the body parts.',
                'pairs' => [
                    ['word' => 'Eye',  'image' => '👁️'],
                    ['word' => 'Ear',  'image' => '👂'],
                    ['word' => 'Nose', 'image' => '👃'],
                    ['word' => 'Hand', 'image' => '✋'],
                ],
            ]);
        }
    }

    private function seedSentenceOrder(): void
    {
        $id = $this->getLessonId(18); // English C2
        if (!$id) { $this->command->warn('Lesson English C2 introuvable'); return; }

        $sentences = [
            ['words' => ['is','The','on','cat','the','mat'],  'answer' => ['The','cat','is','on','the','mat']],
            ['words' => ['big','a','is','dog','The'],         'answer' => ['The','dog','is','a','big']],
            ['words' => ['my','This','home','is'],            'answer' => ['This','is','my','home']],
        ];
        foreach ($sentences as $s) {
            $this->ins($id, 'Put words in order', 'reading', [
                'type' => 'sentence_order', 'illustration' => '✏️',
                'question' => 'Remets les mots dans le bon ordre.',
                'words'  => $s['words'],
                'answer' => $s['answer'],
            ]);
        }
    }

    private function seedTrueFalse(): void
    {
        $id = $this->getLessonId(15); // Science C1
        if (!$id) { $this->command->warn('Lesson Science C1 introuvable'); return; }

        $questions = [
            ['statement' => 'A fish lives in water.',        'answer' => true],
            ['statement' => 'Birds have four legs.',         'answer' => false],
            ['statement' => 'We use our eyes to see.',       'answer' => true],
            ['statement' => 'Plants need sunlight to grow.', 'answer' => true],
            ['statement' => 'A dog is a wild animal.',       'answer' => false],
        ];
        foreach ($questions as $q) {
            $this->ins($id, 'True or False', 'science', [
                'type' => 'true_false', 'illustration' => '🔬',
                'statement' => $q['statement'],
                'answer'    => $q['answer'],
            ]);
        }
    }

    private function seedClockReading(): void
    {
        $id = $this->getLessonId(25); // Maths C3
        if (!$id) { $this->command->warn('Lesson Maths C3 introuvable'); return; }

        $clocks = [
            ['h' =>  3, 'm' =>  0, 'opts' => ['3:00','4:00','2:00','3:30'], 'ans' => 0],
            ['h' =>  6, 'm' => 30, 'opts' => ['6:00','6:30','7:30','5:30'], 'ans' => 1],
            ['h' =>  9, 'm' => 15, 'opts' => ['9:15','9:45','3:45','9:00'], 'ans' => 0],
            ['h' => 12, 'm' =>  0, 'opts' => ['11:00','6:00','12:00','1:00'],'ans' => 2],
            ['h' =>  7, 'm' => 30, 'opts' => ['7:00','7:30','8:30','6:30'], 'ans' => 1],
        ];
        foreach ($clocks as $c) {
            $this->ins($id, sprintf('What time is it? (%d:%02d)', $c['h'], $c['m']), 'mathematics', [
                'type'     => 'clock_reading', 'illustration' => '🕐',
                'question' => 'What time does the clock show?',
                'hours'    => $c['h'], 'minutes' => $c['m'],
                'options'  => $c['opts'], 'answer' => $c['ans'],
            ]);
        }
    }
}
