<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FrenchSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // subject_id => [theme_name, slug, units[]]
        $classes = [

            // ══════════════════════════════════════════════════════════════
            // FRENCH CLASS 1 — subject_id=14 — "Parlons Français" Cosmos/Longhorn
            // 8 Unités d'Apprentissage, 32 semaines
            // ══════════════════════════════════════════════════════════════
            14 => [
                [
                    'theme' => ['name' => 'La Maison',        'slug' => 'fr-maison-c1',        'order' => 1],
                    'units' => [
                        [
                            'name' => 'Unit 1 - La maison', 'slug' => 'fr-la-maison-c1', 'order' => 1,
                            'lessons' => [
                                [
                                    'name' => 'Les pièces de la maison', 'slug' => 'fr-pieces-maison-c1',
                                    'type' => 'listening', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Les pièces — Drill oral',
                                            'instructions' => 'Écoute et répète le nom de chaque pièce de la maison.',
                                            'category' => 'oral_drill', 'difficulty' => 'easy', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🏠',
                                                'items' => [
                                                    ['text' => 'le salon',    'audio_hint' => 'salon'],
                                                    ['text' => 'la cuisine',  'audio_hint' => 'cuisine'],
                                                    ['text' => 'la chambre',  'audio_hint' => 'chambre'],
                                                    ['text' => 'la salle de bain', 'audio_hint' => 'salle-de-bain'],
                                                    ['text' => 'la porte',    'audio_hint' => 'porte'],
                                                    ['text' => 'la fenêtre',  'audio_hint' => 'fenetre'],
                                                    ['text' => 'le toit',     'audio_hint' => 'toit'],
                                                    ['text' => 'la cour',     'audio_hint' => 'cour'],
                                                ],
                                            ]),
                                        ],
                                        [
                                            'title' => 'Les meubles de la maison',
                                            'instructions' => 'Choisis la bonne réponse.',
                                            'category' => 'quiz', 'difficulty' => 'easy', 'estimated_minutes' => 10,
                                            'content' => json_encode([
                                                'type' => 'mcq', 'illustration' => '🛋️',
                                                'questions' => [
                                                    ['text' => 'Où dort-on dans une maison ?',          'options' => ['la cuisine', 'la chambre', 'le salon', 'la cour'],      'answer' => 1],
                                                    ['text' => 'Où prépare-t-on les repas ?',           'options' => ['la chambre', 'la salle de bain', 'la cuisine', 'le toit'], 'answer' => 2],
                                                    ['text' => 'Quel meuble trouve-t-on dans le salon ?', 'options' => ['le lit', 'le canapé', 'la baignoire', 'le réfrigérateur'], 'answer' => 1],
                                                    ['text' => 'Qu\'est-ce qu\'une fenêtre ?',          'options' => ['une ouverture dans le mur', 'un meuble', 'une pièce', 'un toit'], 'answer' => 0],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                                [
                                    'name' => 'La famille', 'slug' => 'fr-famille-c1',
                                    'type' => 'speaking', 'order' => 2,
                                    'exercises' => [
                                        [
                                            'title' => 'Les membres de la famille',
                                            'instructions' => 'Écoute et répète chaque membre de la famille.',
                                            'category' => 'oral_drill', 'difficulty' => 'easy', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '👨‍👩‍👧‍👦',
                                                'items' => [
                                                    ['text' => 'le père',       'audio_hint' => 'pere'],
                                                    ['text' => 'la mère',       'audio_hint' => 'mere'],
                                                    ['text' => 'le frère',      'audio_hint' => 'frere'],
                                                    ['text' => 'la sœur',       'audio_hint' => 'soeur'],
                                                    ['text' => 'le grand-père', 'audio_hint' => 'grand-pere'],
                                                    ['text' => 'la grand-mère', 'audio_hint' => 'grand-mere'],
                                                    ['text' => 'l\'oncle',      'audio_hint' => 'oncle'],
                                                    ['text' => 'la tante',      'audio_hint' => 'tante'],
                                                ],
                                            ]),
                                        ],
                                        [
                                            'title' => 'La famille — Complète',
                                            'instructions' => 'Complète chaque phrase.',
                                            'category' => 'revision', 'difficulty' => 'easy', 'estimated_minutes' => 10,
                                            'content' => json_encode([
                                                'type' => 'fill_in', 'illustration' => '👨‍👩‍👧',
                                                'sentences' => [
                                                    ['text' => 'Le ___ et la mère sont les parents.',       'answer' => 'père'],
                                                    ['text' => 'La sœur de mon père est ma ___.',           'answer' => 'tante'],
                                                    ['text' => 'Le frère de ma mère est mon ___.',          'answer' => 'oncle'],
                                                    ['text' => 'Les parents de mon père sont mes ___.',     'answer' => 'grands-parents'],
                                                    ['text' => 'J\'ai un frère et une ___.',                'answer' => 'sœur'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Le Village / La Ville', 'slug' => 'fr-village-c1', 'order' => 2],
                    'units' => [
                        [
                            'name' => 'Unit 2 - Le village et la ville', 'slug' => 'fr-village-ville-c1', 'order' => 2,
                            'lessons' => [
                                [
                                    'name' => 'Mon village', 'slug' => 'fr-mon-village-c1',
                                    'type' => 'listening', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Le village — Vocabulaire',
                                            'instructions' => 'Écoute et répète les mots du village.',
                                            'category' => 'oral_drill', 'difficulty' => 'easy', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🏘️',
                                                'items' => [
                                                    ['text' => 'le village',   'audio_hint' => 'village'],
                                                    ['text' => 'la rue',       'audio_hint' => 'rue'],
                                                    ['text' => 'le marché',    'audio_hint' => 'marche'],
                                                    ['text' => 'l\'église',    'audio_hint' => 'eglise'],
                                                    ['text' => 'la mosquée',   'audio_hint' => 'mosquee'],
                                                    ['text' => 'le chef',      'audio_hint' => 'chef'],
                                                    ['text' => 'le quartier',  'audio_hint' => 'quartier'],
                                                    ['text' => 'la ville',     'audio_hint' => 'ville'],
                                                ],
                                            ]),
                                        ],
                                        [
                                            'title' => 'Village ou ville ?',
                                            'instructions' => 'Choisis la bonne réponse.',
                                            'category' => 'quiz', 'difficulty' => 'easy', 'estimated_minutes' => 10,
                                            'content' => json_encode([
                                                'type' => 'mcq', 'illustration' => '🌆',
                                                'questions' => [
                                                    ['text' => 'Où achète-t-on des légumes ?',         'options' => ['à l\'école', 'au marché', 'à l\'église', 'au bureau'], 'answer' => 1],
                                                    ['text' => 'Qui dirige un village traditionnel ?', 'options' => ['le professeur', 'le médecin', 'le chef', 'le policier'], 'answer' => 2],
                                                    ['text' => 'Comment appelle-t-on les gens d\'un village ?', 'options' => ['des citadins', 'des villageois', 'des touristes', 'des étrangers'], 'answer' => 1],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => "L'École", 'slug' => 'fr-ecole-c1', 'order' => 3],
                    'units' => [
                        [
                            'name' => "Unit 3 - L'école", 'slug' => 'fr-lecole-c1', 'order' => 3,
                            'lessons' => [
                                [
                                    'name' => "L'environnement de l'école", 'slug' => 'fr-env-ecole-c1',
                                    'type' => 'listening', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => "L'école — Vocabulaire",
                                            'instructions' => "Écoute et répète les mots de l'école.",
                                            'category' => 'oral_drill', 'difficulty' => 'easy', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🏫',
                                                'items' => [
                                                    ['text' => 'la salle de classe', 'audio_hint' => 'salle-classe'],
                                                    ['text' => 'le tableau',         'audio_hint' => 'tableau'],
                                                    ['text' => 'le cahier',          'audio_hint' => 'cahier'],
                                                    ['text' => 'le stylo',           'audio_hint' => 'stylo'],
                                                    ['text' => 'le sac',             'audio_hint' => 'sac'],
                                                    ['text' => 'le maître',          'audio_hint' => 'maitre'],
                                                    ['text' => 'l\'élève',           'audio_hint' => 'eleve'],
                                                    ['text' => 'la cour de récréation', 'audio_hint' => 'cour-recreation'],
                                                ],
                                            ]),
                                        ],
                                        [
                                            'title' => 'À l\'école — Complète',
                                            'instructions' => 'Complète avec le bon mot.',
                                            'category' => 'revision', 'difficulty' => 'easy', 'estimated_minutes' => 10,
                                            'content' => json_encode([
                                                'type' => 'fill_in', 'illustration' => '✏️',
                                                'sentences' => [
                                                    ['text' => 'On écrit avec un ___.',                      'answer' => 'stylo'],
                                                    ['text' => 'Le ___ explique la leçon au tableau.',       'answer' => 'maître'],
                                                    ['text' => 'Les enfants jouent dans la ___ de récréation.', 'answer' => 'cour'],
                                                    ['text' => 'J\'écris mes devoirs dans mon ___.',         'answer' => 'cahier'],
                                                    ['text' => 'Je range mes affaires dans mon ___.',        'answer' => 'sac'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Les Métiers', 'slug' => 'fr-metiers-c1', 'order' => 4],
                    'units' => [
                        [
                            'name' => 'Unit 4 - Les métiers', 'slug' => 'fr-les-metiers-c1', 'order' => 4,
                            'lessons' => [
                                [
                                    'name' => 'Les métiers du village', 'slug' => 'fr-metiers-village-c1',
                                    'type' => 'listening', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Les métiers — Drill oral',
                                            'instructions' => 'Écoute et répète chaque métier.',
                                            'category' => 'oral_drill', 'difficulty' => 'easy', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '👷',
                                                'items' => [
                                                    ['text' => 'le médecin',      'audio_hint' => 'medecin'],
                                                    ['text' => 'l\'infirmier',    'audio_hint' => 'infirmier'],
                                                    ['text' => 'le professeur',   'audio_hint' => 'professeur'],
                                                    ['text' => 'le fermier',      'audio_hint' => 'fermier'],
                                                    ['text' => 'le menuisier',    'audio_hint' => 'menuisier'],
                                                    ['text' => 'la couturière',   'audio_hint' => 'couturiere'],
                                                    ['text' => 'le policier',     'audio_hint' => 'policier'],
                                                    ['text' => 'le conducteur',   'audio_hint' => 'conducteur'],
                                                ],
                                            ]),
                                        ],
                                        [
                                            'title' => 'Quel métier ?',
                                            'instructions' => 'Choisis le bon métier.',
                                            'category' => 'quiz', 'difficulty' => 'easy', 'estimated_minutes' => 10,
                                            'content' => json_encode([
                                                'type' => 'mcq', 'illustration' => '🩺',
                                                'questions' => [
                                                    ['text' => 'Qui soigne les malades ?',          'options' => ['le fermier', 'le médecin', 'le menuisier', 'le conducteur'], 'answer' => 1],
                                                    ['text' => 'Qui enseigne à l\'école ?',         'options' => ['le policier', 'le médecin', 'le professeur', 'le fermier'],  'answer' => 2],
                                                    ['text' => 'Qui cultive la terre ?',            'options' => ['le fermier', 'le couturier', 'le médecin', 'le policier'],   'answer' => 0],
                                                    ['text' => 'Qui conduit un taxi ?',             'options' => ['le menuisier', 'le conducteur', 'l\'infirmier', 'le fermier'], 'answer' => 1],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Les Voyages', 'slug' => 'fr-voyages-c1', 'order' => 5],
                    'units' => [
                        [
                            'name' => 'Unit 5 - Les voyages', 'slug' => 'fr-les-voyages-c1', 'order' => 5,
                            'lessons' => [
                                [
                                    'name' => 'Les moyens de transport', 'slug' => 'fr-transport-c1',
                                    'type' => 'listening', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Les transports — Drill oral',
                                            'instructions' => 'Écoute et répète chaque moyen de transport.',
                                            'category' => 'oral_drill', 'difficulty' => 'easy', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🚗',
                                                'items' => [
                                                    ['text' => 'la voiture',   'audio_hint' => 'voiture'],
                                                    ['text' => 'le bus',       'audio_hint' => 'bus'],
                                                    ['text' => 'le train',     'audio_hint' => 'train'],
                                                    ['text' => 'l\'avion',     'audio_hint' => 'avion'],
                                                    ['text' => 'la moto',      'audio_hint' => 'moto'],
                                                    ['text' => 'le vélo',      'audio_hint' => 'velo'],
                                                    ['text' => 'la pirogue',   'audio_hint' => 'pirogue'],
                                                    ['text' => 'à pied',       'audio_hint' => 'a-pied'],
                                                ],
                                            ]),
                                        ],
                                        [
                                            'title' => 'Comment voyager ?',
                                            'instructions' => 'Complète avec le bon moyen de transport.',
                                            'category' => 'revision', 'difficulty' => 'easy', 'estimated_minutes' => 10,
                                            'content' => json_encode([
                                                'type' => 'fill_in', 'illustration' => '✈️',
                                                'sentences' => [
                                                    ['text' => 'On voyage dans les airs en ___.',       'answer' => 'avion'],
                                                    ['text' => 'On traverse la rivière en ___.',        'answer' => 'pirogue'],
                                                    ['text' => 'Le ___ roule sur des rails.',           'answer' => 'train'],
                                                    ['text' => 'Je vais à l\'école à ___.',             'answer' => 'pied'],
                                                    ['text' => 'Mon père conduit une ___.',             'answer' => 'voiture'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Les Jeux', 'slug' => 'fr-jeux-c1', 'order' => 6],
                    'units' => [
                        [
                            'name' => 'Unit 6 - Les jeux', 'slug' => 'fr-les-jeux-c1', 'order' => 6,
                            'lessons' => [
                                [
                                    'name' => 'Les jeux et les loisirs', 'slug' => 'fr-jeux-loisirs-c1',
                                    'type' => 'speaking', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Les jeux — Vocabulaire',
                                            'instructions' => 'Écoute et répète chaque activité.',
                                            'category' => 'oral_drill', 'difficulty' => 'easy', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '⚽',
                                                'items' => [
                                                    ['text' => 'jouer au football',  'audio_hint' => 'football'],
                                                    ['text' => 'jouer à la corde',   'audio_hint' => 'corde'],
                                                    ['text' => 'chanter',            'audio_hint' => 'chanter'],
                                                    ['text' => 'danser',             'audio_hint' => 'danser'],
                                                    ['text' => 'courir',             'audio_hint' => 'courir'],
                                                    ['text' => 'nager',              'audio_hint' => 'nager'],
                                                    ['text' => 'lire',               'audio_hint' => 'lire'],
                                                    ['text' => 'dessiner',           'audio_hint' => 'dessiner'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Les Traditions', 'slug' => 'fr-traditions-c1', 'order' => 7],
                    'units' => [
                        [
                            'name' => 'Unit 7 - Les traditions', 'slug' => 'fr-les-traditions-c1', 'order' => 7,
                            'lessons' => [
                                [
                                    'name' => 'Les habits traditionnels', 'slug' => 'fr-habits-trad-c1',
                                    'type' => 'listening', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Les traditions — Drill oral',
                                            'instructions' => 'Écoute et répète ces mots sur les traditions.',
                                            'category' => 'oral_drill', 'difficulty' => 'easy', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🥁',
                                                'items' => [
                                                    ['text' => 'le tam-tam',         'audio_hint' => 'tam-tam'],
                                                    ['text' => 'la danse traditionnelle', 'audio_hint' => 'danse-trad'],
                                                    ['text' => 'le repas traditionnel',   'audio_hint' => 'repas-trad'],
                                                    ['text' => 'le ndolé',           'audio_hint' => 'ndole'],
                                                    ['text' => 'le koki',            'audio_hint' => 'koki'],
                                                    ['text' => 'le mbongo tchobi',   'audio_hint' => 'mbongo'],
                                                    ['text' => 'le costume',         'audio_hint' => 'costume'],
                                                    ['text' => 'la fête',            'audio_hint' => 'fete'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'La Communication', 'slug' => 'fr-communication-c1', 'order' => 8],
                    'units' => [
                        [
                            'name' => 'Unit 8 - La communication', 'slug' => 'fr-la-comm-c1', 'order' => 8,
                            'lessons' => [
                                [
                                    'name' => 'Les outils de communication', 'slug' => 'fr-outils-comm-c1',
                                    'type' => 'listening', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'La communication — Drill oral',
                                            'instructions' => 'Écoute et répète les outils de communication.',
                                            'category' => 'oral_drill', 'difficulty' => 'easy', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '📱',
                                                'items' => [
                                                    ['text' => 'le téléphone',     'audio_hint' => 'telephone'],
                                                    ['text' => 'la radio',         'audio_hint' => 'radio'],
                                                    ['text' => 'la télévision',    'audio_hint' => 'television'],
                                                    ['text' => 'la lettre',        'audio_hint' => 'lettre'],
                                                    ['text' => 'le journal',       'audio_hint' => 'journal'],
                                                    ['text' => 'l\'ordinateur',    'audio_hint' => 'ordinateur'],
                                                    ['text' => 'le tam-tam',       'audio_hint' => 'tam-tam'],
                                                    ['text' => 'la voix',          'audio_hint' => 'voix'],
                                                ],
                                            ]),
                                        ],
                                        [
                                            'title' => 'Communiquer — Quiz',
                                            'instructions' => 'Choisis le bon outil de communication.',
                                            'category' => 'quiz', 'difficulty' => 'easy', 'estimated_minutes' => 10,
                                            'content' => json_encode([
                                                'type' => 'mcq', 'illustration' => '📺',
                                                'questions' => [
                                                    ['text' => 'On écoute la musique sur la ___.',          'options' => ['lettre', 'radio', 'voiture', 'fenêtre'],         'answer' => 1],
                                                    ['text' => 'On envoie des messages avec le ___.',       'options' => ['tam-tam', 'cahier', 'téléphone', 'marché'],       'answer' => 2],
                                                    ['text' => 'Quel outil traditionnel communique par sons ?', 'options' => ['la télévision', 'le tam-tam', 'le journal', 'la radio'], 'answer' => 1],
                                                    ['text' => 'On lit les nouvelles dans le ___.',         'options' => ['téléphone', 'stylo', 'journal', 'cahier'],        'answer' => 2],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // ══════════════════════════════════════════════════════════════
            // FRENCH CLASS 4 — subject_id=32 — "French Class 4" Afric'éduc
            // ══════════════════════════════════════════════════════════════
            32 => [
                [
                    'theme' => ['name' => 'La Maison', 'slug' => 'fr-maison-c4', 'order' => 1],
                    'units' => [
                        [
                            'name' => 'UA1 - La maison et les présentations', 'slug' => 'fr-maison-pres-c4', 'order' => 1,
                            'lessons' => [
                                [
                                    'name' => 'Se présenter', 'slug' => 'fr-se-presenter-c4',
                                    'type' => 'speaking', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Se présenter — Drill',
                                            'instructions' => 'Écoute et répète ces phrases de présentation.',
                                            'category' => 'oral_drill', 'difficulty' => 'medium', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🙋',
                                                'items' => [
                                                    ['text' => 'Je m\'appelle Mark.',          'audio_hint' => 'je-mappelle'],
                                                    ['text' => 'J\'ai neuf ans.',              'audio_hint' => 'jai-neuf-ans'],
                                                    ['text' => 'Je suis élève en Class 4.',    'audio_hint' => 'je-suis-eleve'],
                                                    ['text' => 'J\'habite à Yaoundé.',         'audio_hint' => 'jhabite-yaounde'],
                                                    ['text' => 'Ma famille est grande.',       'audio_hint' => 'ma-famille'],
                                                    ['text' => 'Mon père est médecin.',        'audio_hint' => 'mon-pere'],
                                                    ['text' => 'Ma mère est enseignante.',     'audio_hint' => 'ma-mere'],
                                                ],
                                            ]),
                                        ],
                                        [
                                            'title' => 'La maison — Grammaire (indicatif présent)',
                                            'instructions' => 'Complète avec la bonne forme du verbe.',
                                            'category' => 'revision', 'difficulty' => 'medium', 'estimated_minutes' => 12,
                                            'content' => json_encode([
                                                'type' => 'fill_in', 'illustration' => '🏠',
                                                'sentences' => [
                                                    ['text' => 'Je ___ dans une grande maison. (habiter)',  'answer' => 'habite'],
                                                    ['text' => 'Nous ___ le repas ensemble. (prendre)',     'answer' => 'prenons'],
                                                    ['text' => 'Ma sœur ___ dans le salon. (regarder)',     'answer' => 'regarde'],
                                                    ['text' => 'Les enfants ___ dans la cour. (jouer)',     'answer' => 'jouent'],
                                                    ['text' => 'Mon père ___ tôt le matin. (partir)',       'answer' => 'part'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Le Village / La Ville', 'slug' => 'fr-village-c4', 'order' => 2],
                    'units' => [
                        [
                            'name' => 'UA2 - Le village et la ville', 'slug' => 'fr-village-ville-c4', 'order' => 2,
                            'lessons' => [
                                [
                                    'name' => 'La vie en ville', 'slug' => 'fr-vie-ville-c4',
                                    'type' => 'reading', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'La ville — Vocabulaire avancé',
                                            'instructions' => 'Écoute et répète ces mots de la ville.',
                                            'category' => 'oral_drill', 'difficulty' => 'medium', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🌆',
                                                'items' => [
                                                    ['text' => 'la mairie',          'audio_hint' => 'mairie'],
                                                    ['text' => 'l\'hôpital',         'audio_hint' => 'hopital'],
                                                    ['text' => 'la pharmacie',       'audio_hint' => 'pharmacie'],
                                                    ['text' => 'le commissariat',    'audio_hint' => 'commissariat'],
                                                    ['text' => 'la banque',          'audio_hint' => 'banque'],
                                                    ['text' => 'le supermarché',     'audio_hint' => 'supermarche'],
                                                    ['text' => 'la gare routière',   'audio_hint' => 'gare-routiere'],
                                                ],
                                            ]),
                                        ],
                                        [
                                            'title' => 'En ville — Quiz',
                                            'instructions' => 'Choisis le bon endroit.',
                                            'category' => 'quiz', 'difficulty' => 'medium', 'estimated_minutes' => 12,
                                            'content' => json_encode([
                                                'type' => 'mcq', 'illustration' => '🏥',
                                                'questions' => [
                                                    ['text' => 'Où va-t-on chercher ses médicaments ?',    'options' => ['la mairie', 'la pharmacie', 'la banque', 'la gare'],       'answer' => 1],
                                                    ['text' => 'Où dépose-t-on son argent ?',              'options' => ['l\'hôpital', 'l\'école', 'la banque', 'la pharmacie'],     'answer' => 2],
                                                    ['text' => 'Où prend-on le bus pour voyager ?',       'options' => ['la mairie', 'la banque', 'la gare routière', 'le marché'], 'answer' => 2],
                                                    ['text' => 'Où va-t-on pour consulter un médecin ?',  'options' => ['la banque', 'l\'hôpital', 'la mairie', 'la gare'],          'answer' => 1],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => "L'École", 'slug' => 'fr-ecole-c4', 'order' => 3],
                    'units' => [
                        [
                            'name' => "UA3 - L'école", 'slug' => 'fr-lecole-c4', 'order' => 3,
                            'lessons' => [
                                [
                                    'name' => "Exprimer ses opinions", 'slug' => 'fr-opinions-c4',
                                    'type' => 'speaking', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => "Exprimer une opinion — Drill",
                                            'instructions' => "Écoute et répète ces expressions d'opinion.",
                                            'category' => 'oral_drill', 'difficulty' => 'medium', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '💬',
                                                'items' => [
                                                    ['text' => 'Je pense que c\'est bien.',        'audio_hint' => 'je-pense'],
                                                    ['text' => 'À mon avis, c\'est important.',    'audio_hint' => 'a-mon-avis'],
                                                    ['text' => 'Je crois que tu as raison.',       'audio_hint' => 'je-crois'],
                                                    ['text' => 'Il me semble que c\'est vrai.',    'audio_hint' => 'il-me-semble'],
                                                    ['text' => 'Je ne suis pas d\'accord.',        'audio_hint' => 'pas-accord'],
                                                    ['text' => 'Tout à fait !',                    'audio_hint' => 'tout-a-fait'],
                                                ],
                                            ]),
                                        ],
                                        [
                                            'title' => 'Grammaire — Les synonymes',
                                            'instructions' => 'Choisis le synonyme correct.',
                                            'category' => 'quiz', 'difficulty' => 'medium', 'estimated_minutes' => 12,
                                            'content' => json_encode([
                                                'type' => 'mcq', 'illustration' => '📚',
                                                'questions' => [
                                                    ['text' => 'Quel est le synonyme de "content" ?',    'options' => ['triste', 'heureux', 'fatigué', 'méchant'],   'answer' => 1],
                                                    ['text' => 'Quel est le synonyme de "rapide" ?',     'options' => ['lent', 'grand', 'vite', 'petit'],            'answer' => 2],
                                                    ['text' => 'Quel est le synonyme de "commencer" ?',  'options' => ['finir', 'débuter', 'arrêter', 'perdre'],     'answer' => 1],
                                                    ['text' => 'Quel est le synonyme de "difficile" ?',  'options' => ['facile', 'compliqué', 'simple', 'léger'],    'answer' => 1],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Les Métiers', 'slug' => 'fr-metiers-c4', 'order' => 4],
                    'units' => [
                        [
                            'name' => 'UA4 - Les métiers', 'slug' => 'fr-les-metiers-c4', 'order' => 4,
                            'lessons' => [
                                [
                                    'name' => 'Les métiers et les outils', 'slug' => 'fr-metiers-outils-c4',
                                    'type' => 'listening', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Les métiers — Drill avancé',
                                            'instructions' => 'Écoute et répète ces professions.',
                                            'category' => 'oral_drill', 'difficulty' => 'medium', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '⚕️',
                                                'items' => [
                                                    ['text' => 'l\'avocat',         'audio_hint' => 'avocat'],
                                                    ['text' => 'l\'ingénieur',      'audio_hint' => 'ingenieur'],
                                                    ['text' => 'le journaliste',    'audio_hint' => 'journaliste'],
                                                    ['text' => 'le comptable',      'audio_hint' => 'comptable'],
                                                    ['text' => 'l\'architecte',     'audio_hint' => 'architecte'],
                                                    ['text' => 'le menuisier',      'audio_hint' => 'menuisier'],
                                                    ['text' => 'le soudeur',        'audio_hint' => 'soudeur'],
                                                ],
                                            ]),
                                        ],
                                        [
                                            'title' => 'Grammaire — Futur simple',
                                            'instructions' => 'Complète au futur simple.',
                                            'category' => 'revision', 'difficulty' => 'hard', 'estimated_minutes' => 15,
                                            'content' => json_encode([
                                                'type' => 'fill_in', 'illustration' => '🔮',
                                                'sentences' => [
                                                    ['text' => 'Plus tard, je ___ médecin. (être)',          'answer' => 'serai'],
                                                    ['text' => 'Il ___ à Douala demain. (partir)',           'answer' => 'partira'],
                                                    ['text' => 'Nous ___ notre maison. (construire)',        'answer' => 'construirons'],
                                                    ['text' => 'Tu ___ avocat. (devenir)',                   'answer' => 'deviendras'],
                                                    ['text' => 'Elles ___ dans cette école. (travailler)',   'answer' => 'travailleront'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Les Voyages', 'slug' => 'fr-voyages-c4', 'order' => 5],
                    'units' => [
                        [
                            'name' => 'UA5 - Les voyages et achats', 'slug' => 'fr-voyages-achats-c4', 'order' => 5,
                            'lessons' => [
                                [
                                    'name' => 'Voyager au Cameroun', 'slug' => 'fr-voyager-cameroun-c4',
                                    'type' => 'reading', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Les voyages — Vocabulaire',
                                            'instructions' => 'Écoute et répète ces mots de voyage.',
                                            'category' => 'oral_drill', 'difficulty' => 'medium', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🗺️',
                                                'items' => [
                                                    ['text' => 'le billet',       'audio_hint' => 'billet'],
                                                    ['text' => 'la valise',       'audio_hint' => 'valise'],
                                                    ['text' => 'le passeport',    'audio_hint' => 'passeport'],
                                                    ['text' => 'l\'itinéraire',   'audio_hint' => 'itineraire'],
                                                    ['text' => 'la frontière',    'audio_hint' => 'frontiere'],
                                                    ['text' => 'le guide',        'audio_hint' => 'guide'],
                                                    ['text' => 'la douane',       'audio_hint' => 'douane'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Les Biens et Services', 'slug' => 'fr-biens-c4', 'order' => 6],
                    'units' => [
                        [
                            'name' => 'UA6 - Les biens et services', 'slug' => 'fr-biens-services-c4', 'order' => 6,
                            'lessons' => [
                                [
                                    'name' => 'Au marché', 'slug' => 'fr-au-marche-c4',
                                    'type' => 'speaking', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Au marché — Drill',
                                            'instructions' => 'Écoute et répète ces phrases pour faire ses courses.',
                                            'category' => 'oral_drill', 'difficulty' => 'medium', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🛒',
                                                'items' => [
                                                    ['text' => 'Combien coûte ce sac ?',           'audio_hint' => 'combien-coute'],
                                                    ['text' => 'Je voudrais un kilo de tomates.',  'audio_hint' => 'je-voudrais'],
                                                    ['text' => 'C\'est trop cher !',               'audio_hint' => 'trop-cher'],
                                                    ['text' => 'Faites-moi un bon prix.',          'audio_hint' => 'bon-prix'],
                                                    ['text' => 'Je prends deux morceaux.',         'audio_hint' => 'deux-morceaux'],
                                                    ['text' => 'Voici la monnaie.',                'audio_hint' => 'monnaie'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Les Jeux et Loisirs', 'slug' => 'fr-jeux-c4', 'order' => 7],
                    'units' => [
                        [
                            'name' => 'UA7 - Les jeux et loisirs', 'slug' => 'fr-jeux-loisirs-c4', 'order' => 7,
                            'lessons' => [
                                [
                                    'name' => 'Les sports et loisirs', 'slug' => 'fr-sports-loisirs-c4',
                                    'type' => 'speaking', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Les loisirs — Drill',
                                            'instructions' => 'Écoute et répète ces activités de loisirs.',
                                            'category' => 'oral_drill', 'difficulty' => 'medium', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🎮',
                                                'items' => [
                                                    ['text' => 'la natation',           'audio_hint' => 'natation'],
                                                    ['text' => 'le basketball',         'audio_hint' => 'basketball'],
                                                    ['text' => 'la lecture',            'audio_hint' => 'lecture'],
                                                    ['text' => 'la peinture',           'audio_hint' => 'peinture'],
                                                    ['text' => 'la musique',            'audio_hint' => 'musique'],
                                                    ['text' => 'le cinéma',             'audio_hint' => 'cinema'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'La Communication', 'slug' => 'fr-communication-c4', 'order' => 8],
                    'units' => [
                        [
                            'name' => 'UA8 - La communication', 'slug' => 'fr-comm-c4', 'order' => 8,
                            'lessons' => [
                                [
                                    'name' => 'Les médias', 'slug' => 'fr-medias-c4',
                                    'type' => 'listening', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'La communication — Grammaire',
                                            'instructions' => 'Complète avec le bon mot.',
                                            'category' => 'revision', 'difficulty' => 'hard', 'estimated_minutes' => 12,
                                            'content' => json_encode([
                                                'type' => 'fill_in', 'illustration' => '📡',
                                                'sentences' => [
                                                    ['text' => 'Un ___ est une personne qui reporte les nouvelles.',   'answer' => 'journaliste'],
                                                    ['text' => 'On envoie un ___ pour donner une information écrite.', 'answer' => 'message'],
                                                    ['text' => 'La ___ diffuse des programmes pour tout le monde.',    'answer' => 'télévision'],
                                                    ['text' => 'Avec l\'internet, on peut ___ avec le monde entier.',  'answer' => 'communiquer'],
                                                    ['text' => 'Une ___ contient des informations sur l\'actualité.',  'answer' => 'lettre'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // ══════════════════════════════════════════════════════════════
            // FRENCH CLASS 5 — subject_id=38 — "French Class 5" Afric'éduc
            // ══════════════════════════════════════════════════════════════
            38 => [
                [
                    'theme' => ['name' => 'La Nature', 'slug' => 'fr-nature-c5', 'order' => 1],
                    'units' => [
                        [
                            'name' => 'Unité 01 - La nature', 'slug' => 'fr-la-nature-c5', 'order' => 1,
                            'lessons' => [
                                [
                                    'name' => 'La flore et la faune', 'slug' => 'fr-flore-faune-c5',
                                    'type' => 'reading', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'La nature — Vocabulaire',
                                            'instructions' => 'Écoute et répète ces mots de la nature.',
                                            'category' => 'oral_drill', 'difficulty' => 'medium', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🌿',
                                                'items' => [
                                                    ['text' => 'la forêt tropicale',  'audio_hint' => 'foret-tropicale'],
                                                    ['text' => 'la savane',           'audio_hint' => 'savane'],
                                                    ['text' => 'le fleuve',           'audio_hint' => 'fleuve'],
                                                    ['text' => 'la montagne',         'audio_hint' => 'montagne'],
                                                    ['text' => 'la faune',            'audio_hint' => 'faune'],
                                                    ['text' => 'la flore',            'audio_hint' => 'flore'],
                                                    ['text' => 'l\'espèce protégée',  'audio_hint' => 'espece-protegee'],
                                                ],
                                            ]),
                                        ],
                                        [
                                            'title' => 'La nature du Cameroun — Quiz',
                                            'instructions' => 'Réponds aux questions sur la nature du Cameroun.',
                                            'category' => 'quiz', 'difficulty' => 'medium', 'estimated_minutes' => 12,
                                            'content' => json_encode([
                                                'type' => 'mcq', 'illustration' => '🦁',
                                                'questions' => [
                                                    ['text' => 'Quelle est la plus haute montagne du Cameroun ?', 'options' => ['Mont Manengouba', 'Mont Cameroun', 'Mont Mandara', 'Mont Bamboutos'], 'answer' => 1],
                                                    ['text' => 'Comment appelle-t-on l\'ensemble des animaux d\'une région ?', 'options' => ['la flore', 'la faune', 'la savane', 'la forêt'], 'answer' => 1],
                                                    ['text' => 'Quelle zone couvre le nord du Cameroun ?',        'options' => ['la forêt', 'la savane', 'la mer', 'la montagne'], 'answer' => 1],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Le Village / La Ville', 'slug' => 'fr-village-c5', 'order' => 2],
                    'units' => [
                        [
                            'name' => 'Unité 02 - Le village et la ville', 'slug' => 'fr-village-ville-c5', 'order' => 2,
                            'lessons' => [
                                [
                                    'name' => 'L\'organisation du village', 'slug' => 'fr-org-village-c5',
                                    'type' => 'reading', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Le village — Grammaire (accord)',
                                            'instructions' => 'Complète avec le bon accord.',
                                            'category' => 'revision', 'difficulty' => 'hard', 'estimated_minutes' => 15,
                                            'content' => json_encode([
                                                'type' => 'fill_in', 'illustration' => '🌄',
                                                'sentences' => [
                                                    ['text' => 'Les maisons du village sont ___ (grand).',      'answer' => 'grandes'],
                                                    ['text' => 'La route est ___ (long).',                      'answer' => 'longue'],
                                                    ['text' => 'Les enfants sont ___ (actif).',                 'answer' => 'actifs'],
                                                    ['text' => 'La femme ___ (travailler) au champ.',           'answer' => 'travaille'],
                                                    ['text' => 'Ces hommes sont très ___ (courageux).',         'answer' => 'courageux'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => "L'École", 'slug' => 'fr-ecole-c5', 'order' => 3],
                    'units' => [
                        [
                            'name' => "Unité 03 - L'école", 'slug' => 'fr-lecole-c5', 'order' => 3,
                            'lessons' => [
                                [
                                    'name' => "La vie scolaire", 'slug' => 'fr-vie-scolaire-c5',
                                    'type' => 'speaking', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => "L'école — Drill oral",
                                            'instructions' => "Écoute et répète ces expressions scolaires.",
                                            'category' => 'oral_drill', 'difficulty' => 'medium', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🎓',
                                                'items' => [
                                                    ['text' => 'J\'ai bien réussi mon examen.',       'audio_hint' => 'bien-reussi'],
                                                    ['text' => 'Je dois réviser mes leçons.',         'audio_hint' => 'reviser'],
                                                    ['text' => 'Pouvez-vous répéter s\'il vous plaît ?', 'audio_hint' => 'repeter'],
                                                    ['text' => 'Je ne comprends pas cette leçon.',    'audio_hint' => 'pas-comprendre'],
                                                    ['text' => 'Puis-je aller au tableau ?',          'audio_hint' => 'tableau'],
                                                    ['text' => 'Merci pour votre explication.',       'audio_hint' => 'merci-explication'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Les Métiers', 'slug' => 'fr-metiers-c5', 'order' => 4],
                    'units' => [
                        [
                            'name' => 'Unité 04 - Les métiers', 'slug' => 'fr-metiers-c5-u4', 'order' => 4,
                            'lessons' => [
                                [
                                    'name' => 'Les professions avancées', 'slug' => 'fr-professions-c5',
                                    'type' => 'listening', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Les professions — Quiz',
                                            'instructions' => 'Choisis la bonne réponse.',
                                            'category' => 'quiz', 'difficulty' => 'hard', 'estimated_minutes' => 12,
                                            'content' => json_encode([
                                                'type' => 'mcq', 'illustration' => '💼',
                                                'questions' => [
                                                    ['text' => 'Qui défend les accusés devant un tribunal ?', 'options' => ['le médecin', 'l\'avocat', 'le comptable', 'le journaliste'], 'answer' => 1],
                                                    ['text' => 'Qui conçoit les bâtiments ?',                 'options' => ['le plombier', 'le menuisier', 'l\'architecte', 'le peintre'], 'answer' => 2],
                                                    ['text' => 'Quel est le féminin de "instituteur" ?',      'options' => ['instituteure', 'institutrice', 'instituteuse', 'institutoure'], 'answer' => 1],
                                                    ['text' => 'Quel est le masculin de "infirmière" ?',      'options' => ['infirmier', 'infirmierx', 'infirmeurs', 'infirmeure'],         'answer' => 0],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Les Voyages', 'slug' => 'fr-voyages-c5', 'order' => 5],
                    'units' => [
                        [
                            'name' => 'Unité 05 - Les voyages', 'slug' => 'fr-voyages-c5-u5', 'order' => 5,
                            'lessons' => [
                                [
                                    'name' => 'Voyager en Afrique', 'slug' => 'fr-voyager-afrique-c5',
                                    'type' => 'reading', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Les transports — Grammaire',
                                            'instructions' => 'Complète avec le bon mot.',
                                            'category' => 'revision', 'difficulty' => 'hard', 'estimated_minutes' => 12,
                                            'content' => json_encode([
                                                'type' => 'fill_in', 'illustration' => '✈️',
                                                'sentences' => [
                                                    ['text' => 'On ___ en avion pour aller à l\'étranger. (voyager)', 'answer' => 'voyage'],
                                                    ['text' => 'Il faut un ___ pour voyager hors du Cameroun.',       'answer' => 'passeport'],
                                                    ['text' => 'Le train ___ de Yaoundé à Douala. (partir)',          'answer' => 'part'],
                                                    ['text' => 'J\'ai acheté un ___ pour le car.',                    'answer' => 'billet'],
                                                    ['text' => 'La ___ vérifie les bagages à l\'aéroport.',           'answer' => 'douane'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'La Santé', 'slug' => 'fr-sante-c5', 'order' => 6],
                    'units' => [
                        [
                            'name' => 'Unité 06 - La santé', 'slug' => 'fr-la-sante-c5', 'order' => 6,
                            'lessons' => [
                                [
                                    'name' => 'La santé et les maladies', 'slug' => 'fr-sante-maladies-c5',
                                    'type' => 'reading', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'La santé — Vocabulaire',
                                            'instructions' => 'Écoute et répète ces mots de santé.',
                                            'category' => 'oral_drill', 'difficulty' => 'medium', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🏥',
                                                'items' => [
                                                    ['text' => 'la maladie',          'audio_hint' => 'maladie'],
                                                    ['text' => 'le médicament',       'audio_hint' => 'medicament'],
                                                    ['text' => 'la fièvre',           'audio_hint' => 'fievre'],
                                                    ['text' => 'la vaccination',      'audio_hint' => 'vaccination'],
                                                    ['text' => 'l\'hygiène',          'audio_hint' => 'hygiene'],
                                                    ['text' => 'le paludisme',        'audio_hint' => 'paludisme'],
                                                    ['text' => 'se laver les mains',  'audio_hint' => 'laver-mains'],
                                                ],
                                            ]),
                                        ],
                                        [
                                            'title' => 'La santé — Quiz',
                                            'instructions' => 'Choisis la bonne réponse.',
                                            'category' => 'quiz', 'difficulty' => 'medium', 'estimated_minutes' => 12,
                                            'content' => json_encode([
                                                'type' => 'mcq', 'illustration' => '💊',
                                                'questions' => [
                                                    ['text' => 'Quelle maladie est transmise par le moustique au Cameroun ?', 'options' => ['le choléra', 'le paludisme', 'la grippe', 'la rougeole'], 'answer' => 1],
                                                    ['text' => 'Pourquoi faut-il se laver les mains ?',   'options' => ['pour être beau', 'pour éviter les microbes', 'pour sentir bon', 'pour grandir'], 'answer' => 1],
                                                    ['text' => 'Où va-t-on quand on est très malade ?',   'options' => ['au marché', 'à l\'école', 'à l\'hôpital', 'au stade'],      'answer' => 2],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'Les Sports et Loisirs', 'slug' => 'fr-sports-c5', 'order' => 7],
                    'units' => [
                        [
                            'name' => 'Unité 07 - Les sports et loisirs', 'slug' => 'fr-sports-loisirs-c5', 'order' => 7,
                            'lessons' => [
                                [
                                    'name' => 'Les sports collectifs', 'slug' => 'fr-sports-collectifs-c5',
                                    'type' => 'speaking', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'Les sports — Drill oral',
                                            'instructions' => 'Écoute et répète ces sports.',
                                            'category' => 'oral_drill', 'difficulty' => 'medium', 'estimated_minutes' => 8,
                                            'content' => json_encode([
                                                'type' => 'oral_drill', 'illustration' => '🏆',
                                                'items' => [
                                                    ['text' => 'le football',       'audio_hint' => 'football'],
                                                    ['text' => 'le basketball',     'audio_hint' => 'basketball'],
                                                    ['text' => 'le volleyball',     'audio_hint' => 'volleyball'],
                                                    ['text' => 'la natation',       'audio_hint' => 'natation'],
                                                    ['text' => 'l\'athlétisme',     'audio_hint' => 'athletisme'],
                                                    ['text' => 'le judo',           'audio_hint' => 'judo'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'theme' => ['name' => 'La Communication', 'slug' => 'fr-communication-c5', 'order' => 8],
                    'units' => [
                        [
                            'name' => 'Unité 08 - La communication', 'slug' => 'fr-comm-c5', 'order' => 8,
                            'lessons' => [
                                [
                                    'name' => 'Les sources d\'information', 'slug' => 'fr-sources-info-c5',
                                    'type' => 'reading', 'order' => 1,
                                    'exercises' => [
                                        [
                                            'title' => 'La communication — Grammaire avancée',
                                            'instructions' => 'Complète ces phrases sur la communication.',
                                            'category' => 'revision', 'difficulty' => 'hard', 'estimated_minutes' => 15,
                                            'content' => json_encode([
                                                'type' => 'fill_in', 'illustration' => '🌐',
                                                'sentences' => [
                                                    ['text' => 'L\'___ permet d\'échanger des informations dans le monde entier.',  'answer' => 'internet'],
                                                    ['text' => 'Un ___ est un texte court envoyé par téléphone.',                   'answer' => 'SMS'],
                                                    ['text' => 'Les ___ sociaux connectent des millions de personnes.',             'answer' => 'réseaux'],
                                                    ['text' => 'Le journaliste ___ les informations importantes. (diffuser)',       'answer' => 'diffuse'],
                                                    ['text' => 'La ___ donne des nouvelles en images et en sons.',                  'answer' => 'télévision'],
                                                ],
                                            ]),
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // ══════════════════════════════════════════════════════════════════
        // INSERT
        // ══════════════════════════════════════════════════════════════════
        $totalThemes    = 0;
        $totalUnits     = 0;
        $totalLessons   = 0;
        $totalExercises = 0;

        foreach ($classes as $subjectId => $themes) {
            foreach ($themes as $themeData) {
                $td = $themeData['theme'];

                $existingTheme = DB::table('integrated_themes')->where('slug', $td['slug'])->first();
                if ($existingTheme) {
                    $themeId = $existingTheme->id;
                } else {
                    $themeId = DB::table('integrated_themes')->insertGetId([
                        'subject_id'  => $subjectId,
                        'name'        => $td['name'],
                        'slug'        => $td['slug'],
                        'description' => null,
                        'order'       => $td['order'],
                        'is_active'   => true,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ]);
                    $totalThemes++;
                }

                foreach ($themeData['units'] as $unitData) {
                    $lessons = $unitData['lessons'];
                    unset($unitData['lessons']);

                    $existingUnit = DB::table('units')->where('slug', $unitData['slug'])->first();
                    if ($existingUnit) {
                        $unitId = $existingUnit->id;
                    } else {
                        $unitId = DB::table('units')->insertGetId(array_merge($unitData, [
                            'integrated_theme_id' => $themeId,
                            'description'         => null,
                            'estimated_weeks'     => 2,
                            'is_active'           => true,
                            'created_at'          => $now,
                            'updated_at'          => $now,
                        ]));
                        $totalUnits++;
                    }

                    foreach ($lessons as $lessonData) {
                        $exercises = $lessonData['exercises'];
                        unset($lessonData['exercises']);

                        $existingLesson = DB::table('lessons')->where('slug', $lessonData['slug'])->first();
                        if ($existingLesson) {
                            $lessonId = $existingLesson->id;
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
                            $totalLessons++;
                        }

                        foreach ($exercises as $ex) {
                            $exists = DB::table('exercises')
                                ->where('lesson_id', $lessonId)
                                ->where('title', $ex['title'])
                                ->exists();
                            if (!$exists) {
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
            }
        }

        $this->command->info("═══════════════════════════════════════════════════");
        $this->command->info("French seeded: {$totalThemes} themes, {$totalUnits} units, {$totalLessons} lessons, {$totalExercises} exercises.");
        $this->command->info("═══════════════════════════════════════════════════");
    }
}
