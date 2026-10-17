<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Corrige les 40 erreurs bloquantes relevées par l'audit Class 3, 5 et 6 du
 * 17/10/2026 (docs/audits/2026-10-17-class-3-5-6.md) : clés fausses, faits
 * erronés, réponses impossibles à taper, options enregistrées en texte JSON
 * abîmé.
 *
 * Chaque correction indique la valeur trouvée à l'audit : elle n'est appliquée
 * que si cette valeur est toujours là (relancer le seeder ne change rien, et un
 * exercice modifié entre-temps est laissé tel quel). L'avant et l'après de
 * chaque correction sont affichés.
 *
 *   php artisan db:seed --class=AuditClass356ContentFixSeeder --force
 */
class AuditClass356ContentFixSeeder extends Seeder
{
    /** [id, chemin dans content, valeur trouvée à l'audit, valeur corrigée] */
    private function fixes(): array
    {
        $headSentence = 'The head of the family who makes important decisions is called the family _____.';
        $headFixed = 'The person who makes important decisions for the family is called the family ___.';
        $mvetQuestion = 'Which instrument is associated with the Baka people of Cameroon?';
        $mvetFixed = 'Which instrument is associated with the Beti and Fang peoples of Cameroon?';

        return [
            // Clés fausses (mathématiques)
            [92, 'questions.3.answer', 0, 2],                      // 374 → 400
            [1276, 'questions.0.answer', 0, 1],                    // 4 + 3² × (8 ÷ 4) − 1 = 21
            [1834, 'questions.0.answer', 1, 2],                    // 8 740 → 9 000
            [1837, 'questions.0.answer', 1, 2],                    // 45 600 → 46 000
            [1838, 'questions.0.answer', 0, 3],                    // 67 400 → 70 000
            [1841, 'questions.0.answer', 1, 2],                    // 3.47 → 3.5
            [1843, 'questions.0.answer', 1, 2],                    // 5.436 → 5.44
            [1845, 'questions.0.answer', 0, 1],                    // 4865 → 4900
            // Deux bonnes réponses : le doublon devient un distracteur faux
            [178, 'questions.0.options.2', '2/4', '2/8'],
            [1286, 'questions.0.options.3', '2 × 2 × 3', '2 × 3 × 3'],
            [1295, 'questions.0.options.2', '0.12', '0.012'],
            [219, 'questions.0.options.1', 'in', 'to'],
            // Clés fausses (instruction civique, histoire, arts, français)
            [1884, 'answer', true, false],                         // l'étoile du drapeau n'est pas verte
            [1886, 'answer', true, false],                         // mandat présidentiel : 7 ans
            [1879, 'questions.0.answer', 3, 0],
            [1879, 'questions.0.options.2', 'Chant de Ralliement', 'Song of the Savannah'],
            [1879, 'questions.0.options.3', 'All of the above', 'Land of Glory'],
            [1880, 'questions.0.question', 'What is the head of government in Cameroon?', 'Who is the Head of State of Cameroon?'],
            [1880, 'questions.0.explanation', 'The President is the HEAD OF STATE and head of government in Cameroon.', 'The President is the Head of State. The Prime Minister is the Head of Government.'],
            [1925, 'questions.0.answer', 1, 2],                    // Union africaine : 2002
            [1906, 'questions.0.question', "The 1960s is called the 'Year of Africa' because:", "1960 is called the 'Year of Africa' because:"],
            [2088, 'questions.0.question', 'Which art movement is known for its bold colours and simplified forms?', 'Which art movement shows objects with geometric shapes, seen from several sides at once?'],
            [496, 'questions.0.answer', 3, 1],                     // Il était déjà parti
            [2153, 'questions.0.answer', 3, 1],
            [1782, 'questions.0.explanation', "Discours indirect: 'Je suis fatigue' devient 'il a dit qu'il etait fatigue'.", "Le verbe « dit » est au présent : le temps ne change pas. « Je suis fatigué », dit-il → Il dit qu'il est fatigué. (Au passé : il a dit qu'il était fatigué.)"],
            [2047, 'pairs.0.left', 'China', 'India'],
            [2047, 'pairs.3', ['left' => 'Tokyo', 'right' => 'Largest city by population'], ['left' => 'Nile', 'right' => 'Longest river in Africa']],
            // Mvet : instrument des Beti et des Fang, pas des Baka
            [3014, 'questions.3.question', $mvetQuestion, $mvetFixed],
            [3036, 'questions.3.question', $mvetQuestion, $mvetFixed],
            // Options enregistrées en texte JSON abîmé (guillemet au lieu d'apostrophe)
            [2082, 'questions.0.options', '["The camera brand","Light","The photographer"s age","The size of photo"]', ['The camera brand', 'Light', "The photographer's age", 'The size of the photo']],
            [1938, 'questions.0.options', '["cameroon","city","teacher","book"]', ['Cameroon', 'city', 'teacher', 'book']],
            [1987, 'questions.0.options', '["j\"ai allé","je suis allé","j\"allais","j\"irai"]', ["j'ai allé", 'je suis allé', "j'allais", "j'irai"]],
            [1997, 'questions.0.options', '["un point","une virgule","un point d\"exclamation","un point d\"interrogation"]', ['un point', 'une virgule', "un point d'exclamation", "un point d'interrogation"]],
            // Réponses impossibles à taper (« Ti/Si », « head/elder »)
            [2974, 'answer', 'Ti/Si', 'Ti'], [2974, 'alternatives', null, ['Si']],
            [3018, 'answer', 'Ti/Si', 'Ti'], [3018, 'alternatives', null, ['Si']],
            [3040, 'answer', 'Ti/Si', 'Ti'], [3040, 'alternatives', null, ['Si']],
            [2875, 'answer', 'head/elder', 'head'], [2875, 'alternatives', null, ['elder']], [2875, 'sentence', $headSentence, $headFixed],
            [2917, 'answer', 'head/elder', 'head'], [2917, 'alternatives', null, ['elder']], [2917, 'sentence', $headSentence, $headFixed],
            [2938, 'answer', 'head/elder', 'head'], [2938, 'alternatives', null, ['elder']], [2938, 'sentence', $headSentence, $headFixed],
            // Trous ambigus : un seul trou, un indice de verbe
            [102, 'sentences.3.text', 'Train leaves at 08:45 and arrives at 11:20. Journey time = ___ hours ___ minutes.', 'Train leaves at 08:45 and arrives at 11:20. Journey time = 2 hours ___ minutes.'],
            [102, 'sentences.3.answer', '2 hours 35 minutes', '35'],
            [220, 'sentences.1.text', 'The children ___ in the playground.', 'The children ___ (play) in the playground every day.'],
            [1663, 'sentences.0.text', 'The children ___ playing football.', 'Look! The children ___ (be) playing football now.'],
            [1663, 'sentences.1.text', 'She ___ a letter to her friend.', 'Yesterday, she ___ (write) a letter to her friend.'],
            [1663, 'sentences.2.text', 'They ___ to school every morning.', 'They ___ (walk) to school every morning.'],
            [2074, 'items.0.sentence', 'Drawing from memory is called drawing from ___.', 'Drawing things from your own mind, not from a model, is called drawing from ___.'],
            // Suite d'histoire libre : impossible à noter exactement, l'item est retiré
            [1693, 'sentences', [
                ['text' => 'Begin your story: "It was a dark and stormy night when suddenly ___"', 'answer' => 'I heard a knock at the door'],
                ['text' => 'A formal letter ends with "Yours ___" or "Yours sincerely"', 'answer' => 'faithfully'],
            ], [
                ['text' => 'A formal letter ends with "Yours ___" or "Yours sincerely"', 'answer' => 'faithfully'],
            ]],
        ];
    }

    public function run(): void
    {
        $applied = 0;
        $skipped = 0;
        DB::transaction(function () use (&$applied, &$skipped): void {
            foreach ($this->fixes() as [$id, $path, $from, $to]) {
                $raw = DB::table('exercises')->where('id', $id)->value('content');
                $content = is_string($raw) ? json_decode($raw, true) : (array) $raw;
                if (! is_array($content) || Arr::get($content, $path) !== $from) {
                    $this->command?->line("Exercice {$id} [{$path}] : déjà corrigé ou différent, ignoré.");
                    $skipped++;
                    continue;
                }
                Arr::set($content, $path, $to);
                DB::table('exercises')->where('id', $id)->update([
                    'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
                    'updated_at' => now(),
                ]);
                $show = fn ($value) => mb_strimwidth(json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 0, 90, '…');
                $this->command?->info("Exercice {$id} [{$path}] : {$show($from)} → {$show($to)}");
                $applied++;
            }
        });
        $this->command?->line("{$applied} correction(s) appliquée(s), {$skipped} ignorée(s).");
    }
}
