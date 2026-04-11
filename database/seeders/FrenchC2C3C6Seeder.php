<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrenchC2C3C6Seeder extends Seeder
{
    public function run(): void
    {
        $this->frenchC2();
        $this->frenchC3();
        $this->frenchC6();
        $this->command->info('✅ French C2, C3, C6 seedés');
    }

    private function mkLesson(int $sid, string $themeName, string $unitName, string $lessonName): int
    {
        $themeSlug = strtolower(preg_replace('/[^a-z0-9]+/i','-',$themeName)).'-'.$sid;
        $tid = DB::table('integrated_themes')->where('slug',$themeSlug)->value('id');
        if (!$tid) {
            $tid = DB::table('integrated_themes')->insertGetId([
                'subject_id' => $sid, 'name' => $themeName,
                'slug' => $themeSlug, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        $unitSlug = strtolower(preg_replace('/[^a-z0-9]+/i','-',$unitName)).'-'.$tid;
        $uid = DB::table('units')->where('slug',$unitSlug)->value('id');
        if (!$uid) {
            $uid = DB::table('units')->insertGetId([
                'integrated_theme_id' => $tid, 'name' => $unitName,
                'slug' => $unitSlug, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        $lessonSlug = strtolower(preg_replace('/[^a-z0-9]+/i','-',$lessonName)).'-'.$uid;
        $lid = DB::table('lessons')->where('slug',$lessonSlug)->value('id');
        if (!$lid) {
            $lid = DB::table('lessons')->insertGetId([
                'unit_id' => $uid, 'name' => $lessonName,
                'slug' => $lessonSlug, 'type' => 'mixed',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        return $lid;
    }

    private function mcq(int $lid, string $title, string $q, array $opts, int $ans, string $ill='📝'): void
    {
        DB::table('exercises')->insert([
            'lesson_id' => $lid, 'title' => $title, 'category' => 'revision',
            'content' => json_encode(['type'=>'mcq','illustration'=>$ill,
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert([
            'lesson_id' => $lid, 'title' => $title, 'category' => 'revision',
            'content' => json_encode(['type'=>'true_false','illustration'=>'📚',
                'statement'=>$stmt,'answer'=>$ans]),
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function fi(int $lid, string $title, array $sentences): void
    {
        DB::table('exercises')->insert([
            'lesson_id' => $lid, 'title' => $title, 'category' => 'reading',
            'content' => json_encode(['type'=>'fill_in','illustration'=>'✏️',
                'sentences'=>$sentences]),
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    // ── FRENCH C2 (subject_id=20, level_id=6) ────────────────────────────
    private function frenchC2(): void
    {
        $sid = 20;

        $l1 = $this->mkLesson($sid,'Grammaire','Les noms et pronoms','Genre et nombre');
        $this->mcq($l1,'Genre du nom','Le ___ est dans la maison. (chat)',['la','le','les','un'],1,'🐱');
        $this->mcq($l1,'Pluriel','Des chats = ___ chats',['un','le','les','des'],2);
        $this->tf($l1,'Genre','Le mot "maison" est féminin.',true);
        $this->fi($l1,'Complète avec le bon article',[
            ['text'=>'___ soleil brille.','answer'=>'Le'],
            ['text'=>'___ fleur est belle.','answer'=>'La'],
        ]);

        $l2 = $this->mkLesson($sid,'Grammaire','Les verbes','Conjugaison présent');
        $this->mcq($l2,'Verbe être','Nous ___ des élèves.',['est','suis','sommes','êtes'],2);
        $this->mcq($l2,'Verbe avoir','Elle ___ un livre.',['ai','as','a','avons'],2);
        $this->tf($l2,'Conjugaison','Je suis, tu es, il est — verbe être au présent.',true);
        $this->fi($l2,'Conjugue le verbe',[
            ['text'=>'Il ___ (manger) du pain.','answer'=>'mange'],
            ['text'=>'Nous ___ (aller) à l\'école.','answer'=>'allons'],
        ]);

        $l3 = $this->mkLesson($sid,'Vocabulaire','Mots et sens','Contraires et synonymes');
        $this->mcq($l3,'Contraire','Le contraire de "grand" est ___.',['gros','petit','long','fort'],1,'↔️');
        $this->mcq($l3,'Synonyme','Un synonyme de "content" est ___.',['triste','heureux','fâché','fatigué'],1);
        $this->tf($l3,'Vocabulaire','"Rapide" et "lent" sont des contraires.',true);

        $this->command->info('   French C2 : 12 exercices');
    }

    // ── FRENCH C3 (subject_id=26, level_id=7) ────────────────────────────
    private function frenchC3(): void
    {
        $sid = 26;

        $l1 = $this->mkLesson($sid,'Grammaire','Groupes nominaux','Déterminants et adjectifs');
        $this->mcq($l1,'Adjectif qualificatif','La fille ___ chante bien.',['grand','grande','grands','grandes'],1,'📝');
        $this->mcq($l1,'GN','Dans "le petit garçon", "petit" est ___.',
            ['un verbe','un nom','un adjectif','un pronom'],2);
        $this->tf($l1,'Adjectif','Un adjectif qualificatif s\'accorde avec le nom.',true);
        $this->fi($l1,'Accorde l\'adjectif',[
            ['text'=>'Une robe ___ (beau).','answer'=>'belle'],
            ['text'=>'Des enfants ___ (gentil).','answer'=>'gentils'],
        ]);

        $l2 = $this->mkLesson($sid,'Grammaire','Les verbes','Passé composé');
        $this->mcq($l2,'Passé composé','Elle ___ ses devoirs hier.',
            ['fait','a fait','faisait','fera'],1,'⏰');
        $this->mcq($l2,'Auxiliaire','Le passé composé utilise "avoir" ou ___.',
            ['être','aller','faire','dire'],1);
        $this->tf($l2,'Passé composé','Au passé composé, on utilise un auxiliaire + participe passé.',true);
        $this->fi($l2,'Mets au passé composé',[
            ['text'=>'Il ___ (manger) une mangue.','answer'=>'a mangé'],
            ['text'=>'Nous ___ (partir) tôt.','answer'=>'sommes partis'],
        ]);

        $l3 = $this->mkLesson($sid,'Expression écrite','Écrire des textes','La description');
        $this->mcq($l3,'Type de texte','Un texte qui décrit une personne ou un lieu est ___.',
            ['narratif','descriptif','argumentatif','informatif'],1,'📖');
        $this->tf($l3,'Description','Un texte descriptif donne des détails sur l\'apparence.',true);
        $this->mcq($l3,'COD','Dans "Je mange une pomme", "une pomme" est ___.',
            ['le sujet','le COD','le COI','l\'attribut'],1);

        $this->command->info('   French C3 : 11 exercices');
    }

    // ── FRENCH C6 (subject_id=44, level_id=10) ───────────────────────────
    private function frenchC6(): void
    {
        $sid = 44;

        $l1 = $this->mkLesson($sid,'Grammaire','Temps du passé','Imparfait et plus-que-parfait');
        $this->mcq($l1,'Imparfait','Quand j\'étais petit, je ___ souvent au parc.',
            ['jouais','joue','jouerai','ai joué'],0,'⏳');
        $this->mcq($l1,'Plus-que-parfait','Il ___ déjà parti quand nous sommes arrivés.',
            ['est','était','a','avait'],3);
        $this->tf($l1,'Imparfait','L\'imparfait exprime une action habituelle dans le passé.',true);
        $this->fi($l1,'Conjugue à l\'imparfait',[
            ['text'=>'Elle ___ (chanter) tous les soirs.','answer'=>'chantait'],
            ['text'=>'Nous ___ (finir) toujours nos devoirs.','answer'=>'finissions'],
        ]);

        $l2 = $this->mkLesson($sid,'Grammaire','Accords','Accord du participe passé');
        $this->mcq($l2,'Accord PP','Les lettres qu\'il a ___ sont arrivées.',
            ['écrit','écrite','écrits','écrites'],3,'✉️');
        $this->mcq($l2,'Accord sujet-verbe','Les enfants ___ dans la cour.',
            ['joue','jouent','jouons','jouez'],1);
        $this->tf($l2,'Accord PP','Le participe passé s\'accorde avec le COD placé avant.',true);

        $l3 = $this->mkLesson($sid,'Expression écrite','Types de textes','Texte argumentatif');
        $this->mcq($l3,'Texte argumentatif','Un texte argumentatif cherche à ___.',
            ['décrire','raconter','convaincre','expliquer'],2,'💬');
        $this->mcq($l3,'Connecteurs','Pour opposer deux idées on utilise ___.',
            ['donc','car','mais','ainsi'],2);
        $this->tf($l3,'Argumentation','Un argument doit être appuyé par un exemple.',true);
        $this->fi($l3,'Complète avec le bon connecteur',[
            ['text'=>'Il est tard, ___ je dois partir.','answer'=>'donc'],
            ['text'=>'J\'aime le foot ___ pas le basket.','answer'=>'mais'],
        ]);

        $this->command->info('   French C6 : 11 exercices');
    }
}
