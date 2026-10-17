<?php

namespace Database\Seeders;

/**
 * Lot 2 de l'audit Class 3, 5 et 6 (docs/audits/2026-10-17-class-3-5-6.md) :
 * phrases à trous où une réponse juste était refusée.
 *
 * - Réponses justes ajoutées aux alternatives (« Though » pour « Although »,
 *   « till », « 6 » pour « six », « 3 800 » écrit comme dans l'énoncé…).
 * - Phrases ambiguës précisées (verbe entre parenthèses, détail manquant).
 * - Règle « tout compte » respectée : une majuscule manquante en début de
 *   phrase reste refusée ; « six » en début de phrase devient « Six ».
 *
 * Même mécanisme que AuditClass356ContentFixSeeder : chaque correction
 * n'est appliquée que si la valeur trouvée à l'audit est toujours là.
 *
 *   php artisan db:seed --class=AuditClass356FillInSeeder --force
 */
class AuditClass356FillInSeeder extends AuditClass356ContentFixSeeder
{
    /** [id, chemin dans content, valeur trouvée à l'audit, valeur corrigée] */
    protected function fixes(): array
    {
        return [
            [93, 'sentences.2.answer', 'six', 'Six'],
            [491, 'sentences.1.alternatives', null, ['sommes parties']],
            [1474, 'sentences.0.text', 'The letter ___ (write) by the teacher.', 'The letter ___ (write) by the teacher yesterday.'],
            [1221, 'sentences.0.text', 'Despite the rain, she ___ to school on time.', 'Despite the rain, she ___ (come) to school on time.'],
            [2110, 'items.0.alternatives', null, ['tip-off']],
            [2110, 'items.1.alternatives', null, ['hat trick']],
            [2809, 'alternatives', null, ['compost', 'fertiliser', 'fertilizer']],
            [2333, 'sentence', '___ is the contamination of food by harmful bacteria during preparation.', '___ is the spread of harmful bacteria from one food or surface to another, for example from raw meat to salad.'],
            [2333, 'alternatives', ['cross contamination', 'food contamination'], ['cross contamination']],
            [140, 'sentences.0.alternatives', null, ['Internet']],
            [140, 'sentences.1.alternatives', null, ['texto']],
            [102, 'sentences.0.alternatives', null, ['3 800']],
            [102, 'sentences.4.alternatives', null, ['2 400']],
            [1683, 'sentences.0.alternatives', null, ['Though', 'Even though']],
            [1683, 'sentences.1.alternatives', null, ['till']],
            [1861, 'items.1.alternatives', null, ['Douala']],
            [1873, 'items.0.sentence', 'The ___ River is one of the major rivers in Cameroon.', 'The ___ River is the longest river in Cameroon.'],
            [1873, 'items.1.alternatives', null, ['6']],
            [2010, 'items.0.alternatives', null, ['4']],
            [2010, 'items.1.sentence', 'The wettest location near Mount Cameroon is ___.', 'The highest mountain in Cameroon is Mount ___.'],
            [2010, 'items.1.answer', 'Debundscha', 'Cameroon'],
            [1874, 'items.1.sentence', 'The ___ region has the most fertile land for farming.', 'The ___ region, around Bafoussam, is known for its fertile volcanic soils.'],
            [2000, 'items.1.alternatives', null, ['take', 'seize']],
            [2092, 'items.0.alternatives', null, ['opera']],
            [73, 'sentences.2.text', 'People choose their leaders by going to ___ .', 'People choose their leaders by going to ___.'],
            [73, 'sentences.3.text', 'A person who belongs to a country is a ___ .', 'A person who belongs to a country is a ___.'],
            [73, 'sentences.4.alternatives', null, ['taxes']],
            [76, 'sentences.0.alternatives', null, ['conserve']],
            [76, 'sentences.5.alternatives', null, ['environment', 'habitat']],
            [1482, 'sentences.0.alternatives', null, ['Though', 'Even though']],
            [2452, 'alternatives', ['MP', 'representative', 'politician'], ['MP', 'representative', 'politician', 'member of parliament']],
            [1993, 'items.0.sentence', 'On utilise ___ devant les mots masculins commençant par une voyelle.', 'Devant un nom commençant par une voyelle ou un h muet, « le » et « la » deviennent ___.'],
            [2030, 'items.0.alternatives', null, ['Ahidjo', 'Amadou Ahidjo']],
            [2039, 'items.0.sentence', 'The Amazon River flows through ___.', 'The Amazon River flows mainly through ___.'],
            [2049, 'items.1.sentence', 'Human rights are ___, meaning everyone is born with them.', 'Human rights are ___, meaning they belong to everyone, everywhere.'],
        ];
    }
}
