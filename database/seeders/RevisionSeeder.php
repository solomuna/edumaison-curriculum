<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RevisionSeeder extends Seeder
{
    public function run(): void
    {
        $this->mathsC1();
        $this->mathsC2();
        $this->mathsC3();
        $this->mathsC4();
        $this->englishC1();
        $this->englishC2();
        $this->englishC3();
        $this->englishC4();
        $this->frenchC4();
        $this->command->info('✅ Révision générale C1→C4 seedée');
    }

    private function lid(int $sid): ?int
    {
        return DB::table('lessons')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)
            ->value('lessons.id');
    }

    private function ins(int $lid, string $title, string $cat, array $data): void
    {
        DB::table('exercises')->insert([
            'lesson_id'  => $lid,
            'title'      => $title,
            'category'   => $cat,
            'content'    => json_encode($data),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function mcq(int $lid, string $title, string $cat, string $ill, string $q, array $opts, int $ans): void
    {
        $this->ins($lid, $title, $cat, [
            'type' => 'mcq', 'illustration' => $ill,
            'questions' => [['text' => $q, 'options' => $opts, 'answer' => $ans]],
        ]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        $this->ins($lid, $title, 'revision', [
            'type' => 'true_false', 'illustration' => '🔍',
            'statement' => $stmt, 'answer' => $ans,
        ]);
    }

    private function fi(int $lid, string $title, string $cat, string $ill, array $sentences): void
    {
        $this->ins($lid, $title, $cat, [
            'type' => 'fill_in', 'illustration' => $ill,
            'sentences' => $sentences,
        ]);
    }

    // ─── MATHS C1 (subject 13) ───────────────────────────────────────────
    private function mathsC1(): void
    {
        $id = $this->lid(13); if (!$id) return;

        $this->mcq($id,'Count the apples','mathematics','🍎','How many apples are there? 🍎🍎🍎',['2','3','4','5'],1);
        $this->mcq($id,'Add numbers','mathematics','➕','What is 3 + 4?',['5','6','7','8'],2);
        $this->mcq($id,'Subtract','mathematics','➖','What is 8 - 3?',['3','4','5','6'],2);
        $this->mcq($id,'Compare numbers','mathematics','🔢','Which number is bigger?',['5','9','3','7'],1);
        $this->mcq($id,'Shapes','mathematics','🔷','Which shape has 3 sides?',['Circle','Square','Triangle','Rectangle'],2);
        $this->mcq($id,'Money C1','mathematics','💰','You have 50F and spend 20F. How much is left?',['10F','20F','30F','40F'],2);
        $this->tf($id,'Even number','2 is an even number.',true);
        $this->tf($id,'Bigger number','10 is smaller than 8.',false);
        $this->tf($id,'Addition','5 + 5 = 10.',true);
        $this->mcq($id,'Number sequence','mathematics','🔢','What comes after 7?',['6','8','9','10'],1);
    }

    // ─── MATHS C2 (subject 19) ───────────────────────────────────────────
    private function mathsC2(): void
    {
        $id = $this->lid(19); if (!$id) return;

        $this->mcq($id,'Add to 20','mathematics','➕','What is 12 + 7?',['17','18','19','20'],2);
        $this->mcq($id,'Subtract to 20','mathematics','➖','What is 18 - 6?',['10','11','12','13'],2);
        $this->mcq($id,'Multiply by 2','mathematics','✖️','What is 4 × 2?',['6','7','8','9'],2);
        $this->mcq($id,'Half','mathematics','½','What is half of 10?',['3','4','5','6'],2);
        $this->mcq($id,'Fractions','mathematics','🍕','Which fraction is bigger?',['1/4','1/2','1/8','1/3'],1);
        $this->mcq($id,'Money C2','mathematics','💰','You have 200F. A book costs 150F. Change?',['30F','40F','50F','60F'],2);
        $this->mcq($id,'Tens & Units','mathematics','🔢','What is the value of 3 in 35?',['3','13','30','300'],2);
        $this->tf($id,'Multiply','3 × 4 = 12.',true);
        $this->tf($id,'Fraction','1/2 is bigger than 1/4.',true);
        $this->tf($id,'Subtraction','20 - 9 = 12.',false);
        $this->mcq($id,'2D Shapes','mathematics','🔷','A rectangle has ___ sides.',['2','3','4','5'],2);
        $this->mcq($id,'Time','mathematics','🕐','How many minutes in one hour?',['30','45','60','90'],2);
    }

    // ─── MATHS C3 (subject 25) ───────────────────────────────────────────
    private function mathsC3(): void
    {
        $id = $this->lid(25); if (!$id) return;

        $this->mcq($id,'BODMAS','mathematics','🧮','What is 2 + 3 × 4?',['20','14','24','12'],1);
        $this->mcq($id,'HCF','mathematics','🔢','What is the HCF of 12 and 8?',['2','3','4','6'],2);
        $this->mcq($id,'Fractions add','mathematics','➕','What is 1/4 + 1/4?',['1/8','1/2','2/4','1/3'],1);
        $this->mcq($id,'Metric length','mathematics','📏','How many cm in 1 metre?',['10','100','1000','10000'],1);
        $this->mcq($id,'Perimeter','mathematics','📐','Perimeter of a square with side 5cm?',['10cm','15cm','20cm','25cm'],2);
        $this->mcq($id,'Sets','mathematics','⭕','A = {1,2,3} B = {3,4,5}. What is A∩B?',['{}','{3}','{1,2,3,4,5}','{1,2}'],1);
        $this->tf($id,'BODMAS rule','In BODMAS, multiplication comes before addition.',true);
        $this->tf($id,'Sets','A set can have repeated elements.',false);
        $this->mcq($id,'Money C3','mathematics','💰','Cost 750F, paid 1000F. Change?',['150F','200F','250F','300F'],2);
    }

    // ─── MATHS C4 (subject 31) ───────────────────────────────────────────
    private function mathsC4(): void
    {
        $id = $this->lid(31); if (!$id) return;

        $this->mcq($id,'LCM','mathematics','🔢','What is the LCM of 4 and 6?',['8','10','12','16'],2);
        $this->mcq($id,'Fractions subtract','mathematics','➖','What is 3/4 - 1/4?',['1/4','2/4','1/2','3/8'],1);
        $this->mcq($id,'Decimal','mathematics','💯','What is 0.5 as a fraction?',['1/5','1/4','1/2','1/3'],2);
        $this->mcq($id,'Area rectangle','mathematics','📐','Area of rectangle 6cm × 4cm?',['10cm²','20cm²','24cm²','28cm²'],2);
        $this->mcq($id,'Angles','mathematics','📐','A right angle measures ___.',['45°','60°','90°','180°'],2);
        $this->mcq($id,'Venn diagram','mathematics','⭕','P = {2,4,6,8} Q = {4,8,12}. P∩Q = ?',['{}','{4,8}','{2,6,12}','{2,4,6,8,12}'],1);
        $this->mcq($id,'Speed','mathematics','🏃','Distance 60km, time 2hrs. Speed?',['20km/h','30km/h','40km/h','50km/h'],1);
        $this->mcq($id,'Profit','mathematics','💰','Bought at 800F, sold at 1000F. Profit?',['100F','150F','200F','250F'],2);
        $this->tf($id,'Square','A square is a special rectangle.',true);
        $this->tf($id,'LCM','The LCM of 3 and 5 is 15.',true);
        $this->tf($id,'Parallel lines','Parallel lines meet at a point.',false);
        $this->mcq($id,'Calendar','mathematics','📅','How many days in a leap year?',['364','365','366','367'],2);
    }

    // ─── ENGLISH C1 (subject 12) ─────────────────────────────────────────
    private function englishC1(): void
    {
        $id = $this->lid(12); if (!$id) return;

        $this->ins($id,'Animals match','vocabulary',[
            'type'=>'match_pairs','illustration'=>'🐾',
            'question'=>'Match each animal to its image.',
            'pairs'=>[['word'=>'Cat','image'=>'🐱'],['word'=>'Dog','image'=>'🐕'],['word'=>'Bird','image'=>'🐦'],['word'=>'Fish','image'=>'🐟']],
        ]);
        $this->mcq($id,'Greetings','vocabulary','👋','What do you say in the morning?',['Good night','Good morning','Good evening','Goodbye'],1);
        $this->mcq($id,'Colours','vocabulary','🎨','The sky is ___.',['red','green','blue','yellow'],2);
        $this->mcq($id,'Numbers word','vocabulary','🔢','How do you write 5 in words?',['four','five','six','seven'],1);
        $this->fi($id,'Complete the sentence','reading','🏠',[
            ['text'=>'I live in a ___.','answer'=>'house'],
            ['text'=>'I go to ___ every day.','answer'=>'school'],
        ]);
        $this->mcq($id,'Body parts','vocabulary','👁️','We use our ___ to see.',['ears','nose','eyes','mouth'],2);
        $this->mcq($id,'Opposites','vocabulary','↔️','The opposite of big is ___.',['tall','small','fat','long'],1);
        $this->tf($id,'True or false','A cat is an animal.',true);
        $this->tf($id,'True or false','We eat with our ears.',false);
        $this->ins($id,'Order the words','reading',[
            'type'=>'sentence_order','illustration'=>'✏️',
            'question'=>'Put the words in order.',
            'words'=>['is','my','This','home'],'answer'=>['This','is','my','home'],
        ]);
    }

    // ─── ENGLISH C2 (subject 18) ─────────────────────────────────────────
    private function englishC2(): void
    {
        $id = $this->lid(18); if (!$id) return;

        $this->mcq($id,'Articles','reading','📖','___ elephant is big.',['A','An','The','Some'],1);
        $this->mcq($id,'Plural','vocabulary','🔢','More than one dog = ___.',['dog','dogs','dogges','doge'],1);
        $this->mcq($id,'Verb to be','reading','✏️','She ___ a teacher.',['am','is','are','be'],1);
        $this->fi($id,'Fill in','reading','🏫',[
            ['text'=>'The ___ is red.','answer'=>'book'],
            ['text'=>'He ___ to school.','answer'=>'goes'],
        ]);
        $this->mcq($id,'Pronouns','reading','👤','Instead of "John", we say ___.',['she','it','he','they'],2);
        $this->ins($id,'Match body parts','vocabulary',[
            'type'=>'match_pairs','illustration'=>'👁️',
            'question'=>'Match the body parts.',
            'pairs'=>[['word'=>'Eye','image'=>'👁️'],['word'=>'Ear','image'=>'👂'],['word'=>'Nose','image'=>'👃'],['word'=>'Hand','image'=>'✋']],
        ]);
        $this->mcq($id,'Adjectives','vocabulary','📝','The ___ cat sat on the mat.',['running','big','quickly','under'],1);
        $this->tf($id,'Grammar','A noun is the name of a person, place or thing.',true);
        $this->tf($id,'Grammar','Verbs describe how something looks.',false);
        $this->ins($id,'Make a sentence','reading',[
            'type'=>'sentence_order','illustration'=>'✏️',
            'question'=>'Make a correct sentence.',
            'words'=>['going','are','We','to','school'],'answer'=>['We','are','going','to','school'],
        ]);
    }

    // ─── ENGLISH C3 (subject 24) ─────────────────────────────────────────
    private function englishC3(): void
    {
        $id = $this->lid(24); if (!$id) return;

        $this->mcq($id,'Comparative adjective','reading','📝','John is ___ than Peter. (tall)',['more tall','taller','tallest','most tall'],1);
        $this->mcq($id,'Past tense','reading','⏰','Yesterday, she ___ to school.',['go','goes','went','going'],2);
        $this->mcq($id,'Prepositions','vocabulary','📦','The ball is ___ the box.',['on','in','at','of'],0);
        $this->fi($id,'Fill in the blank','reading','📖',[
            ['text'=>'She ___ her homework every day.','answer'=>'does'],
            ['text'=>'The children ___ in the playground.','answer'=>'play'],
        ]);
        $this->mcq($id,'Conjunctions','reading','🔗','I like mangoes ___ I do not like pawpaw.',['and','but','or','so'],1);
        $this->tf($id,'Grammar','An adjective describes a noun.',true);
        $this->tf($id,'Grammar','"Quickly" is an adjective.',false);
        $this->mcq($id,'Synonyms','vocabulary','📚','Another word for "happy" is ___.',['sad','angry','glad','tired'],2);
    }

    // ─── ENGLISH C4 (subject 30) ─────────────────────────────────────────
    private function englishC4(): void
    {
        $id = $this->lid(30); if (!$id) return;

        $this->mcq($id,'Relative pronoun','reading','📝','The girl ___ won the prize is my sister.',['who','which','what','where'],0);
        $this->mcq($id,'Present perfect','reading','⏰','She ___ already eaten.',['has','have','had','is'],0);
        $this->mcq($id,'Conditional','reading','🔀','If it rains, we ___ stay inside.',['will','would','shall','should'],0);
        $this->fi($id,'Fill in C4','reading','📖',[
            ['text'=>'He is the tallest boy ___ the class.','answer'=>'in'],
            ['text'=>'Neither John ___ Peter came.','answer'=>'nor'],
        ]);
        $this->mcq($id,'Superlative','reading','🏆','Mount Cameroon is the ___ mountain in West Africa.',['high','higher','highest','most high'],2);
        $this->tf($id,'Tenses','The present perfect uses "has/have + past participle".',true);
        $this->tf($id,'Punctuation','A question mark is used at the end of a statement.',false);
        $this->ins($id,'Build a sentence','reading',[
            'type'=>'sentence_order','illustration'=>'✏️',
            'question'=>'Make a correct sentence.',
            'words'=>['hardest','is','the','Mathematics','subject'],'answer'=>['Mathematics','is','the','hardest','subject'],
        ]);
    }

    // ─── FRENCH C4 (subject 32) ──────────────────────────────────────────
    private function frenchC4(): void
    {
        $id = $this->lid(32); if (!$id) return;

        $this->mcq($id,'Genre des noms','revision','📝','Le ___ est dans la maison. (chat)',['la','le','les','des'],1);
        $this->mcq($id,'Pluriel','revision','🔢','Des chats = ___ chats',['un','le','les','des'],2);
        $this->mcq($id,'Verbe être','revision','✏️','Nous ___ des élèves.',['est','suis','sommes','êtes'],2);
        $this->fi($id,'Complète','revision','📖',[
            ['text'=>'Je ___ à l\'école chaque jour.','answer'=>'vais'],
            ['text'=>'Il ___ son devoir le soir.','answer'=>'fait'],
        ]);
        $this->mcq($id,'Adjectif','revision','📝','La fille est ___.', ['grand','grande','grands','grandes'],1);
        $this->tf($id,'Grammaire','Le verbe s\'accorde avec le sujet.',true);
        $this->tf($id,'Vocabulaire','"Rapide" est le contraire de "lent".',true);
        $this->tf($id,'Grammaire','Un adverbe décrit un nom.',false);
    }
}
