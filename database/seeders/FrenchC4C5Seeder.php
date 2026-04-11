<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrenchC4C5Seeder extends Seeder
{
    public function run(): void
    {
        $this->frenchC4();
        $this->frenchC5();
        $this->command->info('✅ French C4 and C5 reinforced');
    }

    private function lid(int $sid): ?int
    {
        return DB::table('lessons')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)->value('lessons.id');
    }

    private function mcq(int $lid, string $title, string $q, array $opts, int $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'revision',
            'content'=>json_encode(['type'=>'mcq','illustration'=>'📝',
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'revision',
            'content'=>json_encode(['type'=>'true_false','illustration'=>'📚',
                'statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function fi(int $lid, string $title, array $sentences): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'reading',
            'content'=>json_encode(['type'=>'fill_in','illustration'=>'✏️','sentences'=>$sentences]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    // ── FRENCH C4 (subject 32) — renforcement ────────────────────────────
    private function frenchC4(): void
    {
        $id = $this->lid(32); if (!$id) return;

        // Grammaire — COD/COI
        $this->mcq($id,'COD C4','Dans "Je mange une pomme", "une pomme" est le ___.',
            ['sujet','COD','COI','attribut'],1);
        $this->mcq($id,'COI C4','Dans "Elle parle à son ami", "à son ami" est le ___.',
            ['sujet','COD','COI','complément circonstanciel'],2);
        $this->tf($id,'COD fait','Le COD répond à la question "quoi ?" ou "qui ?" après le verbe.',true);

        // Conjugaison — futur simple
        $this->mcq($id,'Futur simple','Demain, elle ___ (aller) à l\'école.',
            ['va','ira','allait','est allée'],1);
        $this->mcq($id,'Futur nous','Nous ___ (finir) nos devoirs ce soir.',
            ['finissons','finissions','finirons','avons fini'],2);
        $this->fi($id,'Conjugue au futur simple',[
            ['text'=>'Il ___ (venir) demain.','answer'=>'viendra'],
            ['text'=>'Vous ___ (partir) à 8h.','answer'=>'partirez'],
            ['text'=>'Je ___ (faire) mes devoirs.','answer'=>'ferai'],
        ]);
        $this->tf($id,'Futur simple fait','Au futur simple, on ajoute les terminaisons -ai, -as, -a, -ons, -ez, -ont.',true);

        // Vocabulaire
        $this->mcq($id,'Synonyme C4','Un synonyme de "rapide" est ___.',
            ['lent','vite','grand','petit'],1);
        $this->mcq($id,'Antonyme C4','L\'antonyme de "fort" est ___.',
            ['puissant','faible','grand','dur'],1);
        $this->mcq($id,'Famille de mots C4','Quel mot appartient à la famille de "jardin" ?',
            ['jardiner','maison','école','voiture'],0);

        // Expression écrite
        $this->mcq($id,'Type de texte C4','Un texte qui explique comment faire quelque chose est un texte ___.',
            ['narratif','descriptif','injonctif','argumentatif'],2);
        $this->fi($id,'Complète les phrases C4',[
            ['text'=>'Les enfants ___ (jouer) dans la cour.','answer'=>'jouent'],
            ['text'=>'Ma mère ___ (préparer) le repas chaque soir.','answer'=>'prépare'],
            ['text'=>'Nous ___ (étudier) pour l\'examen.','answer'=>'étudions'],
        ]);
        $this->mcq($id,'Ponctuation C4','Quelle ponctuation termine une question ?',
            ['.',',','!','?'],3);
        $this->mcq($id,'Majuscule C4','On met une majuscule au début d\'une ___ et aux noms propres.',
            ['mot','phrase','paragraphe','ligne'],1);

        $this->command->info('   French C4: 15 exercises added');
    }

    // ── FRENCH C5 (subject 38) — renforcement ────────────────────────────
    private function frenchC5(): void
    {
        $id = $this->lid(38); if (!$id) return;

        // Grammaire — pronoms relatifs
        $this->mcq($id,'Pronom relatif C5','Le livre ___ j\'ai lu est intéressant.',
            ['qui','que','dont','où'],1);
        $this->mcq($id,'Pronom relatif qui','L\'élève ___ a répondu s\'appelle Marc.',
            ['que','dont','qui','où'],2);
        $this->tf($id,'Pronoms relatifs','On utilise "qui" pour le sujet et "que" pour le complément.',true);

        // Conjugaison — imparfait
        $this->mcq($id,'Imparfait C5','Quand j\'étais petit, je ___ souvent au parc.',
            ['joue','jouerai','jouais','ai joué'],2);
        $this->mcq($id,'Imparfait vs passé composé C5',
            '"Elle ___ (lire) quand il est entré." Quel temps convient ?',
            ['a lu','lira','lisait','lit'],2);
        $this->fi($id,'Conjugue à l\'imparfait',[
            ['text'=>'Il ___ (manger) du riz chaque jour.','answer'=>'mangeait'],
            ['text'=>'Nous ___ (aller) à l\'école à pied.','answer'=>'allions'],
            ['text'=>'Elle ___ (être) très gentille.','answer'=>'était'],
        ]);

        // Vocabulaire avancé
        $this->mcq($id,'Préfixe C5','Le préfixe "dé-" dans "défaire" signifie ___.',
            ['à nouveau','contraire/annuler','avant','très'],1);
        $this->mcq($id,'Suffixe C5','Le suffixe "-eur" dans "chanteur" désigne ___.',
            ['une action','une qualité','une personne qui fait l\'action','un lieu'],2);
        $this->mcq($id,'Homophones C5','"Ou" et "où" sont des homophones. "Où" indique ___.',
            ['un choix','un lieu','une conjonction','une négation'],1);

        // Expression écrite — lettre
        $this->mcq($id,'Lettre formelle C5','Une lettre formelle commence par ___.',
            ['Cher ami,','Salut,','Monsieur / Madame,','Bonjour tout le monde,'],2);
        $this->mcq($id,'Lettre formelle fin C5','Une lettre formelle se termine par ___.',
            ['Bisous','Veuillez agréer mes salutations distinguées','À bientôt','Ciao'],1);
        $this->fi($id,'Complète la lettre formelle',[
            ['text'=>'Je me permets de vous écrire au sujet ___ votre annonce.','answer'=>'de'],
            ['text'=>'Dans l\'attente de votre ___, je vous salue.','answer'=>'réponse'],
        ]);
        $this->mcq($id,'Discours direct C5','Transforme en discours indirect : "Je suis fatigué" dit-il.',
            ['Il dit qu\'il est fatigué.','Il dit qu\'il était fatigué.','Il a dit il est fatigué.','Il dit qu\'il sera fatigué.'],0);
        $this->tf($id,'Discours indirect C5','Au discours indirect, on supprime les guillemets.',true);

        $this->command->info('   French C5: 15 exercises added');
    }
}
