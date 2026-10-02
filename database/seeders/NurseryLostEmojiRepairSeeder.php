<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Répare 4 exercices de mathématiques Nursery 1 dont les emojis (objets à
 * compter) ont été perdus à l'enregistrement : chaque emoji est devenu « ? »
 * (emoji court : ⭐ ⚽ ➕) ou « ?? » (emoji long : 🍎 🐱 🎈). Les quantités sont
 * déduites des bonnes réponses, qui sont inchangées.
 *
 * N'agit que si l'exercice est encore abîmé (titre et texte d'origine
 * attendus) : relancer le seeder ne change rien.
 *
 *   php artisan db:seed --class=NurseryLostEmojiRepairSeeder
 */
class NurseryLostEmojiRepairSeeder extends Seeder
{
    /** id => [titre attendu, texte abîmé attendu, contenu réparé] */
    private function repairs(): array
    {
        return [
            2650 => ['How many?', 'How many apples? ??????', [
                'type' => 'multiple_choice',
                'questions' => [
                    ['question' => 'How many apples? 🍎🍎🍎', 'options' => ['1', '2', '3', '4'], 'answer' => 2],
                    ['question' => 'How many stars? ⭐⭐', 'options' => ['1', '2', '3', '4'], 'answer' => 1],
                    ['question' => 'How many cats? 🐱🐱🐱🐱', 'options' => ['2', '3', '4', '5'], 'answer' => 2],
                ],
            ]],
            2651 => ['Count the balls', 'How many balls? ?????', [
                'type' => 'multiple_choice',
                'illustration' => '⚽',
                'questions' => [
                    ['question' => 'How many balls? ⚽⚽⚽⚽⚽', 'options' => ['3', '4', '5', '6'], 'answer' => 2],
                    ['question' => 'How many balloons? 🎈🎈', 'options' => ['1', '2', '3', '4'], 'answer' => 1],
                ],
            ]],
            2654 => ['Match numbers', '????????', [
                'type' => 'match_pairs',
                'question' => 'Match the number to the objects.',
                'illustration' => '📐',
                'pairs' => [
                    ['word' => '1', 'image' => '🍌'],
                    ['word' => '2', 'image' => '🐟🐟'],
                    ['word' => '3', 'image' => '⭐⭐⭐'],
                    ['word' => '4', 'image' => '🍎🍎🍎🍎'],
                ],
            ]],
            2659 => ['Add with pictures', '?? + ?????? = ?', [
                'type' => 'multiple_choice',
                'illustration' => '➕',
                'questions' => [
                    ['question' => '🍎 + 🍎 = ?', 'options' => ['1', '2', '3', '4'], 'answer' => 1],
                    ['question' => '⭐ + ⭐⭐ = ?', 'options' => ['2', '3', '4', '5'], 'answer' => 1],
                    ['question' => '🍊 + 🍊🍊🍊 = ?', 'options' => ['3', '4', '5', '6'], 'answer' => 1],
                ],
            ]],
        ];
    }

    public function run(): void
    {
        DB::transaction(function (): void {
            foreach ($this->repairs() as $id => [$title, $brokenText, $content]) {
                $exercise = DB::table('exercises')->where('id', $id)->first(['id', 'title', 'content']);
                $current = (string) ($exercise->content ?? '');
                if (! $exercise || $exercise->title !== $title || ! str_contains($current, $brokenText)) {
                    $this->command?->line("Exercice {$id} : déjà réparé ou différent, ignoré.");
                    continue;
                }
                DB::table('exercises')->where('id', $id)->update([
                    'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
                    'updated_at' => now(),
                ]);
                $this->command?->info("Exercice {$id} réparé.");
            }
        });
    }
}
