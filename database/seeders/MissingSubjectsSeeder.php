<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissingSubjectsSeeder extends Seeder
{
    public function run(): void
    {
        $this->scienceC1();
        $this->readingC1();
        $this->readingC2();
        $this->handwritingC1();
        $this->handwritingC2();
        $this->citizenshipC5();
        $this->englishC5();
        $this->englishC6();
        $this->command->info('✅ All missing subjects seeded');
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

    private function lid(int $sid): ?int
    {
        return DB::table('lessons')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)->value('lessons.id');
    }

    // signature: lid, title, cat, ill, question, options, answer
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
            'content'=>json_encode(['type'=>'true_false','illustration'=>'💡','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function fi(int $lid, string $title, string $cat, array $sentences): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>$cat,
            'content'=>json_encode(['type'=>'fill_in','illustration'=>'✏️','sentences'=>$sentences]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function hw(int $lid, string $title, array $prompts): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'handwriting',
            'content'=>json_encode(['type'=>'handwriting','illustration'=>'✍️','prompts'=>$prompts]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function oral(int $lid, string $title, string $cat, string $ill, array $items): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>$cat,
            'content'=>json_encode(['type'=>'oral_drill','illustration'=>$ill,'items'=>$items]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function scienceC1(): void
    {
        $sid = 15;
        $l1 = $this->mkLesson($sid,'Health Education','The Human Body','Body Parts');
        $this->mcq($l1,'Body parts count','science','🧍','How many eyes do we have?',['1','2','3','4'],1);
        $this->mcq($l1,'Body parts function','science','👁️','We use our eyes to ___.',['hear','see','smell','taste'],1);
        $this->mcq($l1,'Hygiene','science','🧼','We wash our hands with ___.',['juice','mud','soap and water','sand'],2);
        $this->tf($l1,'Body fact','science','We have two ears.',true);
        $this->tf($l1,'Hygiene fact','science','We should wash our hands before eating.',true);

        $l2 = $this->mkLesson($sid,'Health Education','Senses','The Five Senses');
        $this->oral($l2,'Name the senses','science','👁️',[
            ['text'=>'We see with our eyes.','audio_hint'=>'sight'],
            ['text'=>'We hear with our ears.','audio_hint'=>'hearing'],
            ['text'=>'We smell with our nose.','audio_hint'=>'smell'],
            ['text'=>'We taste with our tongue.','audio_hint'=>'taste'],
            ['text'=>'We touch with our skin.','audio_hint'=>'touch'],
        ]);
        $this->mcq($l2,'Match sense to organ','science','👂','Which organ do we use for hearing?',['Eye','Nose','Ear','Tongue'],2);

        $l3 = $this->mkLesson($sid,'Environmental Science','Animals','Domestic Animals');
        $this->mcq($l3,'Domestic animals','science','🐄','Which animal gives us milk?',['Lion','Cow','Eagle','Snake'],1);
        $this->mcq($l3,'Animal homes','science','🏠','A dog lives in a ___.',['nest','hole','kennel','hive'],2);
        $this->tf($l3,'Animals fact','science','A hen lays eggs.',true);
        $this->tf($l3,'Wild animals','science','A lion is a domestic animal.',false);

        $l4 = $this->mkLesson($sid,'Environmental Science','Plants','Plants Around Us');
        $this->mcq($l4,'Plant parts','science','🌱','Which part of the plant is underground?',['Leaf','Stem','Root','Flower'],2);
        $this->mcq($l4,'Plant needs','science','☀️','Plants need ___ to grow.',['sand','sunlight and water','stones','noise'],1);
        $this->tf($l4,'Plants fact','science','Plants give us oxygen.',true);

        $l5 = $this->mkLesson($sid,'Technology','Machines','Simple Machines');
        $this->mcq($l5,'Machine example','science','⚙️','Which is a machine?',['Stone','Bicycle','Tree','Cloth'],1);
        $this->tf($l5,'Electricity safety','science','We should never touch electrical sockets with wet hands.',true);

        $this->command->info('   Science C1: 15 exercises');
    }

    private function readingC1(): void
    {
        $sid = 16;
        $l1 = $this->mkLesson($sid,'Reading','Phonics','Letter Sounds');
        $this->oral($l1,'Letter sounds A-E','reading','📖',[
            ['text'=>'A is for Apple','audio_hint'=>'/ae/'],
            ['text'=>'B is for Ball','audio_hint'=>'/b/'],
            ['text'=>'C is for Cat','audio_hint'=>'/k/'],
            ['text'=>'D is for Dog','audio_hint'=>'/d/'],
            ['text'=>'E is for Egg','audio_hint'=>'/e/'],
        ]);
        $this->oral($l1,'Letter sounds F-J','reading','📖',[
            ['text'=>'F is for Fish','audio_hint'=>'/f/'],
            ['text'=>'G is for Goat','audio_hint'=>'/g/'],
            ['text'=>'H is for House','audio_hint'=>'/h/'],
            ['text'=>'I is for Insect','audio_hint'=>'/i/'],
            ['text'=>'J is for Jug','audio_hint'=>'/j/'],
        ]);
        $this->mcq($l1,'Word begins with B','reading','📖','Which word begins with the letter B?',['Apple','Ball','Cat','Dog'],1);
        $this->mcq($l1,'Word ends with T','reading','📖','Which word ends with the letter T?',['Dog','Cat','Ball','Fish'],1);

        $l2 = $this->mkLesson($sid,'Reading','Sight Words','Common Words');
        $this->oral($l2,'Sight words 1','reading','📚',[
            ['text'=>'the','audio_hint'=>'/the/'],
            ['text'=>'a','audio_hint'=>'/a/'],
            ['text'=>'is','audio_hint'=>'/is/'],
            ['text'=>'and','audio_hint'=>'/and/'],
            ['text'=>'in','audio_hint'=>'/in/'],
        ]);
        $this->fi($l2,'Complete the sentence','reading',[
            ['text'=>'___ cat is black.','answer'=>'The'],
            ['text'=>'I have ___ dog.','answer'=>'a'],
            ['text'=>'She ___ my friend.','answer'=>'is'],
        ]);

        $l3 = $this->mkLesson($sid,'Reading','Comprehension','Short Texts');
        $this->mcq($l3,'Reading comprehension 1','reading','📖',
            'Tom has a red ball. He plays with it every day. What colour is Tom\'s ball?',
            ['Blue','Green','Red','Yellow'],2);
        $this->mcq($l3,'Reading comprehension 2','reading','📖',
            'Binta lives on a farm. She has three goats and two cows. How many animals does Binta have?',
            ['3','4','5','6'],2);

        $this->command->info('   Reading C1: 9 exercises');
    }

    private function readingC2(): void
    {
        $sid = 22;
        $l1 = $this->mkLesson($sid,'Reading','Word Building','Syllables and Words');
        $this->mcq($l1,'Syllables','reading','📖','How many syllables in "elephant"?',['1','2','3','4'],2);
        $this->mcq($l1,'Word families','reading','📖','Which word belongs to the -at family?',['Dog','Cat','Run','Big'],1);
        $this->oral($l1,'Read sight words','reading','📚',[
            ['text'=>'The dog is big.','audio_hint'=>'simple sentence'],
            ['text'=>'A cat sat on a mat.','audio_hint'=>'-at family'],
            ['text'=>'She can run fast.','audio_hint'=>'action words'],
        ]);

        $l2 = $this->mkLesson($sid,'Reading','Comprehension','Short Stories');
        $this->mcq($l2,'Story comprehension 1','reading','📖',
            'Mary goes to school every morning. She carries a red bag. Her teacher is called Mrs. Fon. What does Mary carry to school?',
            ['A book','A red bag','A blue bag','A pencil'],1);
        $this->mcq($l2,'Story comprehension 2','reading','📖',
            'Ambe has a garden. He grows tomatoes, onions and carrots. He sells them at the market. What does Ambe grow?',
            ['Only tomatoes','Flowers','Tomatoes, onions and carrots','Rice'],2);
        $this->fi($l2,'Fill in the missing word','reading',[
            ['text'=>'The sun ___ in the east.','answer'=>'rises'],
            ['text'=>'We ___ to school by bus.','answer'=>'go'],
            ['text'=>'Birds ___ in the sky.','answer'=>'fly'],
        ]);
        $this->mcq($l2,'Find the opposite','reading','📖','The opposite of "hot" is ___.',['warm','cold','big','fast'],1);
        $this->mcq($l2,'Rhyming words','reading','📖','Which word rhymes with "cat"?',['Dog','Bird','Mat','Sun'],2);

        $this->command->info('   Reading C2: 8 exercises');
    }

    private function handwritingC1(): void
    {
        $sid = 17;
        $l1 = $this->mkLesson($sid,'Handwriting','Letters','Capital Letters A-M');
        $this->hw($l1,'Trace capital letters A-E',['A','B','C','D','E']);
        $this->hw($l1,'Trace capital letters F-J',['F','G','H','I','J']);
        $this->hw($l1,'Trace capital letters K-M',['K','L','M']);

        $l2 = $this->mkLesson($sid,'Handwriting','Letters','Capital Letters N-Z');
        $this->hw($l2,'Trace capital letters N-R',['N','O','P','Q','R']);
        $this->hw($l2,'Trace capital letters S-Z',['S','T','U','V','W','X','Y','Z']);

        $l3 = $this->mkLesson($sid,'Handwriting','Words','Simple Words');
        $this->hw($l3,'Trace simple words',['cat','dog','sun','hat','pen']);
        $this->hw($l3,'Trace more words',['bed','cup','map','box','fan']);

        $this->command->info('   Handwriting C1: 7 exercises');
    }

    private function handwritingC2(): void
    {
        $sid = 23;
        $l1 = $this->mkLesson($sid,'Handwriting','Sentences','Writing Sentences');
        $this->hw($l1,'Trace lowercase a-e',['a','b','c','d','e']);
        $this->hw($l1,'Trace lowercase f-j',['f','g','h','i','j']);
        $this->hw($l1,'Trace lowercase k-p',['k','l','m','n','o','p']);

        $l2 = $this->mkLesson($sid,'Handwriting','Words','Two-syllable Words');
        $this->hw($l2,'Trace two-syllable words',['mango','paper','pencil','monkey','basket']);
        $this->hw($l2,'Trace sentences',['I go to school.','The sun is hot.','We love our country.']);

        $this->command->info('   Handwriting C2: 5 exercises');
    }

    private function citizenshipC5(): void
    {
        $sid = 41;
        $l1 = $this->mkLesson($sid,'History','World History','Ancient Civilisations');
        $this->mcq($l1,'Ancient Egypt','revision','🏛️','Ancient Egypt is famous for its ___.',['skyscrapers','pyramids','submarines','satellites'],1);
        $this->mcq($l1,'Early man','revision','🦴','Early man used ___ to make tools.',['plastic','stone','metal','wood'],1);
        $this->tf($l1,'History fact','revision','BC means Before Christ.',true);

        $l2 = $this->mkLesson($sid,'Geography','Cameroon Geography','Regions of Cameroon');
        $this->mcq($l2,'Regions of Cameroon','revision','🗺️','How many regions does Cameroon have?',['8','10','12','14'],1);
        $this->mcq($l2,'Largest city','revision','🏙️','The largest city in Cameroon is ___.',['Yaounde','Bafoussam','Douala','Garoua'],2);
        $this->tf($l2,'Geography fact','revision','Cameroon is called "Africa in Miniature".',true);

        $l3 = $this->mkLesson($sid,'Civics','Rights and Duties','Child Rights');
        $this->mcq($l3,'Child trafficking','revision','⚠️','Child trafficking is ___.',['legal','helpful','a serious crime','acceptable'],2);
        $this->mcq($l3,'UNICEF role','revision','🌍','UNICEF mainly protects ___.',['soldiers','animals','children','buildings'],2);
        $this->tf($l3,'Rights fact','revision','Every child has the right to a name and nationality.',true);

        $l4 = $this->mkLesson($sid,'Peace','Conflict Resolution','Peace Building');
        $this->mcq($l4,'Conflict resolution','revision','🤝','The best way to solve a conflict is through ___.',['fighting','ignoring','dialogue','revenge'],2);
        $this->tf($l4,'Peace fact','revision','Tolerance means accepting others despite differences.',true);
        $this->mcq($l4,'UN','revision','🌍','The United Nations was created to promote ___.',['war','trade only','world peace','sports'],2);

        $this->command->info('   Citizenship C5: 12 exercises');
    }

    private function englishC5(): void
    {
        $sid = 36;
        $id = $this->lid($sid); if (!$id) return;

        $this->mcq($id,'Present perfect','reading','⏰','She ___ already finished her homework.',['have','has','had','is'],1);
        $this->mcq($id,'Passive voice','reading','📝','The book ___ written by a famous author.',['is','was','were','are'],1);
        $this->mcq($id,'Relative pronoun','reading','📝','The girl ___ won the prize is my friend.',['which','what','who','where'],2);
        $this->mcq($id,'Conditional','reading','🔀','If it rains, we ___ stay inside.',['will','would','shall','should'],0);
        $this->mcq($id,'Reported speech','reading','💬','He said that he ___ tired.',['is','was','were','be'],1);
        $this->mcq($id,'Prefix un-','vocabulary','📚','"Unhappy" means ___.',['very happy','not happy','quite happy','always happy'],1);
        $this->mcq($id,'Suffix -ful','vocabulary','📚','"Careful" means ___.',['without care','full of care','too much care','no care'],1);
        $this->mcq($id,'Antonym','vocabulary','↔️','The opposite of "ancient" is ___.',['old','modern','big','small'],1);
        $this->fi($id,'Fill in C5','reading',[
            ['text'=>'Despite the rain, she ___ to school on time.','answer'=>'came'],
            ['text'=>'He is taller ___ his brother.','answer'=>'than'],
            ['text'=>'Neither John ___ Mary was present.','answer'=>'nor'],
        ]);
        $this->tf($id,'Grammar C5','reading','The present perfect tense uses "has" or "have" + past participle.',true);
        $this->tf($id,'Vocabulary C5','reading','A prefix is added at the beginning of a word.',true);

        $this->command->info('   English C5: 11 exercises added');
    }

    private function englishC6(): void
    {
        $sid = 42;
        $id = $this->lid($sid); if (!$id) return;

        $this->mcq($id,'Past perfect','reading','⏰','By the time I arrived, she ___ already left.',['has','have','had','was'],2);
        $this->mcq($id,'Future perfect','reading','⏰','By tomorrow, he ___ finished the project.',['will have','would have','shall','has'],0);
        $this->mcq($id,'Subjunctive','reading','📝','I wish I ___ taller.',['am','is','were','was'],2);
        $this->mcq($id,'Inversion','reading','📝','"Rarely ___ she make a mistake." Choose the correct form.',['does','do','did','has'],0);
        $this->mcq($id,'Gerund vs infinitive','reading','📝','She enjoys ___ to music.',['listen','to listen','listening','listened'],2);
        $this->mcq($id,'Synonym for eloquent','vocabulary','📚','Another word for "eloquent" is ___.',['silent','fluent','rude','shy'],1);
        $this->mcq($id,'Compound adjective','vocabulary','📚','"A well-known author" — "well-known" is a ___.',['noun','verb','compound adjective','adverb'],2);
        $this->mcq($id,'Types of noun','reading','📝','Which is an abstract noun?',['Table','Dog','Happiness','School'],2);
        $this->mcq($id,'Reflexive pronoun','reading','📝','She did it ___.',['herself','himself','itself','yourself'],0);
        $this->mcq($id,'Punctuation','reading','📝','Which punctuation ends a question?',['.',',','!','?'],3);
        $this->tf($id,'Past perfect C6','reading','The past perfect uses "had" + past participle.',true);
        $this->tf($id,'Abstract noun','reading','An abstract noun names a feeling or idea, not a physical object.',true);

        $this->command->info('   English C6: 12 exercises added');
    }
}
