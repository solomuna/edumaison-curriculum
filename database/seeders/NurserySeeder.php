<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NurserySeeder extends Seeder
{
    public function run(): void
    {
        // Pre-Nursery (level_id = 1 or 2 — check DB)
        $this->preNurseryEnglish(1);
        $this->preNurseryMaths(2);

        // Nursery 1
        $this->nursery1English(4);
        $this->nursery1Maths(5);
        $this->nursery1French(6);

        // Nursery 2
        $this->nursery2English(8);
        $this->nursery2Maths(9);
        $this->nursery2French(10);

        $this->command->info('✅ Pre-Nursery and Nursery seeded');
    }

    private function mkLesson(int $sid, string $theme, string $unit, string $lesson): int
    {
        $ts = strtolower(preg_replace('/[^a-z0-9]+/i','-',$theme)).'-'.$sid;
        $tid = DB::table('integrated_themes')->where('slug',$ts)->value('id');
        if (!$tid) $tid = DB::table('integrated_themes')->insertGetId([
            'subject_id'=>$sid,'name'=>$theme,'slug'=>$ts,'created_at'=>now(),'updated_at'=>now()]);
        $us = strtolower(preg_replace('/[^a-z0-9]+/i','-',$unit)).'-'.$tid;
        $uid = DB::table('units')->where('slug',$us)->value('id');
        if (!$uid) $uid = DB::table('units')->insertGetId([
            'integrated_theme_id'=>$tid,'name'=>$unit,'slug'=>$us,'created_at'=>now(),'updated_at'=>now()]);
        $ls = strtolower(preg_replace('/[^a-z0-9]+/i','-',$lesson)).'-'.$uid;
        $lid = DB::table('lessons')->where('slug',$ls)->value('id');
        if (!$lid) $lid = DB::table('lessons')->insertGetId([
            'unit_id'=>$uid,'name'=>$lesson,'slug'=>$ls,'type'=>'mixed','created_at'=>now(),'updated_at'=>now()]);
        return $lid;
    }

    private function mcq(int $lid, string $title, string $cat, string $ill, string $q, array $opts, int $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>$cat,
            'content'=>json_encode(['type'=>'mcq','illustration'=>$ill,
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function tf(int $lid, string $title, string $cat, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>$cat,
            'content'=>json_encode(['type'=>'true_false','illustration'=>'⭐','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function oral(int $lid, string $title, string $cat, string $ill, array $items): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>$cat,
            'content'=>json_encode(['type'=>'oral_drill','illustration'=>$ill,'items'=>$items]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function match(int $lid, string $title, string $cat, string $ill, string $q, array $pairs): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>$cat,
            'content'=>json_encode(['type'=>'match_pairs','illustration'=>$ill,'question'=>$q,'pairs'=>$pairs]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    // ── PRE-NURSERY ENGLISH (subject 1) ──────────────────────────────────
    private function preNurseryEnglish(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Letters','Alphabet','Letters A-E');
        $this->oral($l1,'Say the letters A-E','reading','🔤',[
            ['text'=>'A — Apple 🍎','audio_hint'=>'A'],
            ['text'=>'B — Ball ⚽','audio_hint'=>'B'],
            ['text'=>'C — Cat 🐱','audio_hint'=>'C'],
            ['text'=>'D — Dog 🐶','audio_hint'=>'D'],
            ['text'=>'E — Egg 🥚','audio_hint'=>'E'],
        ]);
        $this->mcq($l1,'Letter A','reading','🍎','A is for ___.',['Ball','Apple','Cat','Dog'],1);
        $this->mcq($l1,'Letter B','reading','⚽','B is for ___.',['Apple','Egg','Ball','Dog'],2);
        $this->mcq($l1,'Letter C','reading','🐱','C is for ___.',['Dog','Cat','Apple','Egg'],1);

        $l2 = $this->mkLesson($sid,'Letters','Alphabet','Letters F-J');
        $this->oral($l2,'Say the letters F-J','reading','🔤',[
            ['text'=>'F — Fish 🐟','audio_hint'=>'F'],
            ['text'=>'G — Goat 🐐','audio_hint'=>'G'],
            ['text'=>'H — Hat 🎩','audio_hint'=>'H'],
            ['text'=>'I — Insect 🐛','audio_hint'=>'I'],
            ['text'=>'J — Juice 🧃','audio_hint'=>'J'],
        ]);
        $this->mcq($l2,'Letter F','reading','🐟','F is for ___.',['Goat','Hat','Fish','Juice'],2);
        $this->mcq($l2,'Letter H','reading','🎩','H is for ___.',['Fish','Hat','Goat','Insect'],1);

        $l3 = $this->mkLesson($sid,'Colours','Basic Colours','Colours');
        $this->mcq($l3,'Red colour','reading','🔴','Which is the colour RED?',['🔵 Blue','🟡 Yellow','🔴 Red','🟢 Green'],2);
        $this->mcq($l3,'Blue colour','reading','🔵','Which is the colour BLUE?',['🔴 Red','🔵 Blue','🟡 Yellow','🟢 Green'],1);
        $this->oral($l3,'Name the colours','reading','🌈',[
            ['text'=>'Red — like an apple 🍎','audio_hint'=>'red'],
            ['text'=>'Yellow — like the sun ☀️','audio_hint'=>'yellow'],
            ['text'=>'Blue — like the sky 🌤️','audio_hint'=>'blue'],
            ['text'=>'Green — like grass 🌿','audio_hint'=>'green'],
        ]);

        $l4 = $this->mkLesson($sid,'Body Parts','My Body','Parts of the Body');
        $this->mcq($l4,'Eyes','reading','👁️','We use our ___ to see.',['nose','ears','eyes','mouth'],2);
        $this->mcq($l4,'Ears','reading','👂','We use our ___ to hear.',['eyes','ears','nose','hands'],1);
        $this->match($l4,'Match body parts','reading','🧍','Match each part to what it does.',[
            ['word'=>'Eyes','image'=>'👁️'],
            ['word'=>'Ears','image'=>'👂'],
            ['word'=>'Nose','image'=>'👃'],
            ['word'=>'Mouth','image'=>'👄'],
        ]);

        $this->command->info('   Pre-Nursery English: 13 exercises');
    }

    // ── PRE-NURSERY MATHS (subject 2) ────────────────────────────────────
    private function preNurseryMaths(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Numbers','Counting','Numbers 1-5');
        $this->oral($l1,'Count 1 to 5','mathematics','🔢',[
            ['text'=>'1 — one ☝️','audio_hint'=>'one'],
            ['text'=>'2 — two ✌️','audio_hint'=>'two'],
            ['text'=>'3 — three 🤟','audio_hint'=>'three'],
            ['text'=>'4 — four 🖐️','audio_hint'=>'four'],
            ['text'=>'5 — five 🖐️','audio_hint'=>'five'],
        ]);
        $this->mcq($l1,'Count apples','mathematics','🍎','How many apples? 🍎🍎🍎',['1','2','3','4'],2);
        $this->mcq($l1,'Count balls','mathematics','⚽','How many balls? ⚽⚽',['1','2','3','4'],1);
        $this->mcq($l1,'Count stars','mathematics','⭐','How many stars? ⭐⭐⭐⭐',['2','3','4','5'],2);

        $l2 = $this->mkLesson($sid,'Numbers','Counting','Numbers 6-10');
        $this->oral($l2,'Count 6 to 10','mathematics','🔢',[
            ['text'=>'6 — six 🎲','audio_hint'=>'six'],
            ['text'=>'7 — seven 🎯','audio_hint'=>'seven'],
            ['text'=>'8 — eight 🎱','audio_hint'=>'eight'],
            ['text'=>'9 — nine 🔮','audio_hint'=>'nine'],
            ['text'=>'10 — ten 🎳','audio_hint'=>'ten'],
        ]);
        $this->mcq($l2,'Which is more','mathematics','🍭','Which has MORE? 🍭🍭🍭 or 🍭🍭',['🍭🍭','🍭🍭🍭','Equal','Cannot tell'],1);
        $this->mcq($l2,'Which is less','mathematics','🍎','Which has LESS? 🍎🍎🍎🍎 or 🍎🍎',['🍎🍎🍎🍎','🍎🍎','Equal','Cannot tell'],1);

        $l3 = $this->mkLesson($sid,'Shapes','Basic Shapes','Circle and Square');
        $this->mcq($l3,'Circle','mathematics','⭕','Which shape has no corners?',['Square','Triangle','Circle','Rectangle'],2);
        $this->mcq($l3,'Square','mathematics','🟦','A square has ___ sides.',['2','3','4','5'],2);
        $this->tf($l3,'Shape fact','mathematics','A circle is round.',true);

        $this->command->info('   Pre-Nursery Maths: 11 exercises');
    }

    // ── NURSERY 1 ENGLISH (subject 4) ────────────────────────────────────
    private function nursery1English(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Phonics','Alphabet','Letters A-M');
        $this->oral($l1,'Alphabet A-M','reading','🔤',[
            ['text'=>'A-B-C-D-E','audio_hint'=>'A to E'],
            ['text'=>'F-G-H-I-J','audio_hint'=>'F to J'],
            ['text'=>'K-L-M','audio_hint'=>'K to M'],
        ]);
        $this->mcq($l1,'Letter sounds','reading','📖','What sound does "A" make?',['/b/','/a/','/c/','/d/'],1);
        $this->mcq($l1,'First letter','reading','📖','Which letter starts the word "Mango"?',['N','L','M','P'],2);

        $l2 = $this->mkLesson($sid,'Words','Simple Words','CVC Words');
        $this->oral($l2,'Read CVC words','reading','📖',[
            ['text'=>'cat 🐱','audio_hint'=>'c-a-t'],
            ['text'=>'dog 🐶','audio_hint'=>'d-o-g'],
            ['text'=>'sun ☀️','audio_hint'=>'s-u-n'],
            ['text'=>'hat 🎩','audio_hint'=>'h-a-t'],
            ['text'=>'pen ✏️','audio_hint'=>'p-e-n'],
        ]);
        $this->mcq($l2,'CVC word','reading','📖','"c-a-t" spells ___.',['car','cup','cat','cut'],2);
        $this->mcq($l2,'Beginning sound','reading','📖','What sound does "dog" start with?',['/b/','/c/','/d/','/f/'],2);

        $l3 = $this->mkLesson($sid,'Sentences','Simple Sentences','I am, I have');
        $this->oral($l3,'Simple sentences','reading','💬',[
            ['text'=>'I am a child.','audio_hint'=>'statement'],
            ['text'=>'I have a ball.','audio_hint'=>'statement'],
            ['text'=>'She is my friend.','audio_hint'=>'statement'],
        ]);
        $this->mcq($l3,'Complete sentence','reading','📝','___ is my mother.',['He','She','It','They'],1);
        $this->tf($l3,'Sentence fact','reading','A sentence begins with a capital letter.',true);

        $this->command->info('   Nursery 1 English: 10 exercises');
    }

    // ── NURSERY 1 MATHS (subject 5) ──────────────────────────────────────
    private function nursery1Maths(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Numbers','Counting','Numbers 1-10');
        $this->mcq($l1,'Count to 5','mathematics','🔢','What comes after 4?',['3','5','6','2'],1);
        $this->mcq($l1,'Count to 10','mathematics','🔢','What comes after 9?',['8','11','10','7'],2);
        $this->mcq($l1,'Missing number','mathematics','🔢','2, 3, ___, 5',['1','4','6','7'],1);

        $l2 = $this->mkLesson($sid,'Numbers','Addition','Simple Addition');
        $this->mcq($l2,'Add 1+1','mathematics','➕','1 + 1 = ___',['1','2','3','4'],1);
        $this->mcq($l2,'Add 2+2','mathematics','➕','2 + 2 = ___',['3','4','5','6'],1);
        $this->mcq($l2,'Add 3+2','mathematics','➕','3 + 2 = ___',['4','5','6','7'],1);
        $this->tf($l2,'Addition fact','mathematics','Adding means putting numbers together to get more.',true);

        $l3 = $this->mkLesson($sid,'Shapes','2D Shapes','Basic Shapes');
        $this->mcq($l3,'Triangle sides','mathematics','🔺','A triangle has ___ sides.',['2','3','4','5'],1);
        $this->mcq($l3,'Circle corners','mathematics','⭕','A circle has ___ corners.',['0','2','3','4'],0);
        $this->tf($l3,'Shape fact','mathematics','A square has 4 equal sides.',true);

        $this->command->info('   Nursery 1 Maths: 10 exercises');
    }

    // ── NURSERY 1 FRENCH (subject 6) ─────────────────────────────────────
    private function nursery1French(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Vocabulaire','Les animaux','Les animaux domestiques');
        $this->oral($l1,'Les animaux','revision','🐱',[
            ['text'=>'le chat 🐱','audio_hint'=>'chat'],
            ['text'=>'le chien 🐶','audio_hint'=>'chien'],
            ['text'=>'la vache 🐄','audio_hint'=>'vache'],
            ['text'=>'le coq 🐓','audio_hint'=>'coq'],
        ]);
        $this->mcq($l1,'Animal domestique','revision','🐄','Lequel est un animal domestique?',['Lion','Éléphant','Vache','Crocodile'],2);
        $this->mcq($l1,'La vache','revision','🐄','La vache donne du ___.',['jus','lait','pain','eau'],1);

        $l2 = $this->mkLesson($sid,'Vocabulaire','Les couleurs','Les couleurs de base');
        $this->oral($l2,'Les couleurs','revision','🌈',[
            ['text'=>'rouge 🔴','audio_hint'=>'rouge'],
            ['text'=>'bleu 🔵','audio_hint'=>'bleu'],
            ['text'=>'jaune 🟡','audio_hint'=>'jaune'],
            ['text'=>'vert 🟢','audio_hint'=>'vert'],
        ]);
        $this->mcq($l2,'Couleur rouge','revision','🔴','Quelle couleur est la pomme?',['Bleu','Vert','Rouge','Jaune'],2);
        $this->mcq($l2,'Couleur jaune','revision','🌞','Le soleil est ___.',['rouge','vert','bleu','jaune'],3);
        $this->tf($l2,'Couleur fait','revision','Le ciel est bleu.',true);

        $this->command->info('   Nursery 1 French: 8 exercises');
    }

    // ── NURSERY 2 ENGLISH (subject 8) ────────────────────────────────────
    private function nursery2English(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Phonics','Letters and Sounds','Vowels and Consonants');
        $this->mcq($l1,'Vowels','reading','📖','Which is a vowel?',['B','C','A','D'],2);
        $this->mcq($l1,'Count vowels','reading','📖','How many vowels are in the alphabet?',['3','4','5','6'],2);
        $this->oral($l1,'Vowel sounds','reading','🔤',[
            ['text'=>'A — /æ/ as in Apple','audio_hint'=>'A'],
            ['text'=>'E — /ɛ/ as in Egg','audio_hint'=>'E'],
            ['text'=>'I — /ɪ/ as in Insect','audio_hint'=>'I'],
            ['text'=>'O — /ɒ/ as in Orange','audio_hint'=>'O'],
            ['text'=>'U — /ʌ/ as in Umbrella','audio_hint'=>'U'],
        ]);

        $l2 = $this->mkLesson($sid,'Words','Sight Words','Reading Simple Words');
        $this->oral($l2,'Read sight words','reading','📚',[
            ['text'=>'I, me, my, we, you','audio_hint'=>'pronouns'],
            ['text'=>'the, a, an, is, are','audio_hint'=>'articles and verbs'],
            ['text'=>'big, small, red, blue','audio_hint'=>'adjectives'],
        ]);
        $this->mcq($l2,'Word recognition','reading','📖','Which word means the opposite of big?',['Large','Tall','Small','Heavy'],2);
        $this->mcq($l2,'Simple reading','reading','📖','The ___ is hot. (sun)',['moon','sun','rain','cloud'],1);

        $l3 = $this->mkLesson($sid,'Sentences','Simple Sentences','Describing People');
        $this->mcq($l3,'Subject of sentence','reading','📝','In "The dog runs fast", what is the subject?',['runs','fast','The dog','quickly'],2);
        $this->tf($l3,'Sentence ends','reading','A sentence ends with a full stop, question mark or exclamation mark.',true);
        $this->mcq($l3,'Question words','reading','❓','Which word asks about a place?',['Who','What','Where','When'],2);

        $this->command->info('   Nursery 2 English: 10 exercises');
    }

    // ── NURSERY 2 MATHS (subject 9) ──────────────────────────────────────
    private function nursery2Maths(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Numbers','Counting','Numbers 1-20');
        $this->mcq($l1,'Count to 20','mathematics','🔢','What comes after 15?',['14','16','17','18'],1);
        $this->mcq($l1,'Missing number 2','mathematics','🔢','10, 12, ___, 16',['13','14','15','18'],1);
        $this->mcq($l1,'Biggest number','mathematics','🔢','Which is the biggest? 7, 12, 5, 9',['7','12','5','9'],1);

        $l2 = $this->mkLesson($sid,'Operations','Basic Arithmetic','Addition and Subtraction');
        $this->mcq($l2,'Addition N2','mathematics','➕','5 + 3 = ___',['6','7','8','9'],2);
        $this->mcq($l2,'Subtraction','mathematics','➖','8 − 3 = ___',['4','5','6','7'],1);
        $this->mcq($l2,'Word problem','mathematics','🍎','You have 6 mangoes and eat 2. How many are left?',['3','4','5','6'],1);
        $this->tf($l2,'Subtraction fact','mathematics','Subtraction means taking away.',true);

        $l3 = $this->mkLesson($sid,'Measurement','Size','Big and Small, Long and Short');
        $this->mcq($l3,'Compare size','mathematics','📏','A bus is ___ than a bicycle.',['smaller','shorter','bigger','lighter'],2);
        $this->mcq($l3,'Compare length','mathematics','📏','A river is ___ than a pond.',['shorter','longer','smaller','heavier'],1);
        $this->tf($l3,'Size comparison','mathematics','An elephant is bigger than a mouse.',true);

        $this->command->info('   Nursery 2 Maths: 10 exercises');
    }

    // ── NURSERY 2 FRENCH (subject 10) ────────────────────────────────────
    private function nursery2French(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Vocabulaire','La famille','Les membres de la famille');
        $this->oral($l1,'La famille','revision','👨‍👩‍👧',[
            ['text'=>'le père 👨','audio_hint'=>'père'],
            ['text'=>'la mère 👩','audio_hint'=>'mère'],
            ['text'=>'le frère 👦','audio_hint'=>'frère'],
            ['text'=>'la sœur 👧','audio_hint'=>'sœur'],
        ]);
        $this->mcq($l1,'La mère','revision','👩','La ___ est la femme du père.',['sœur','mère','tante','grand-mère'],1);
        $this->mcq($l1,'Le père','revision','👨','Le ___ est le chef de famille.',['frère','oncle','père','cousin'],2);

        $l2 = $this->mkLesson($sid,'Grammaire','Articles','Les articles définis');
        $this->mcq($l2,'Article le/la','revision','📝','"___ chat dort." (masculin)',['La','Le','Les','Un'],1);
        $this->mcq($l2,'Article la','revision','📝','"___ poule mange." (féminin)',['Le','Les','La','Un'],2);
        $this->oral($l2,'Articles le et la','revision','📝',[
            ['text'=>'le garçon (masculin)','audio_hint'=>'le'],
            ['text'=>'la fille (féminin)','audio_hint'=>'la'],
            ['text'=>'les enfants (pluriel)','audio_hint'=>'les'],
        ]);
        $this->mcq($l2,'Genre du nom','revision','📝','"soleil" est ___.',['féminin','neutre','masculin','pluriel'],2);
        $this->tf($l2,'Article fait','revision','On utilise "le" pour les noms masculins.',true);

        $this->command->info('   Nursery 2 French: 8 exercises');
    }
}
