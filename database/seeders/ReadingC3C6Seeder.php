<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReadingC3C6Seeder extends Seeder
{
    public function run(): void
    {
        foreach ([7=>3, 8=>4, 9=>5, 10=>6] as $levelId=>$class) {
            $sid = $this->mkSubject($levelId, 'Reading', 'reading-c'.$class);
            $this->{'readingC'.$class}($sid);
        }
        $this->command->info('✅ Reading C3-C6 seeded');
    }

    private function mkSubject(int $levelId, string $name, string $slug): int
    {
        $existing = DB::table('subjects')->where('level_id',$levelId)->where('name',$name)->value('id');
        if ($existing) return $existing;
        return DB::table('subjects')->insertGetId([
            'level_id'=>$levelId,'name'=>$name,'slug'=>$slug,
            'icon'=>'📖','is_active'=>true,'created_at'=>now(),'updated_at'=>now(),
        ]);
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

    private function mcq(int $lid, string $title, string $q, array $opts, int $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'reading',
            'content'=>json_encode(['type'=>'mcq','illustration'=>'📖',
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'reading',
            'content'=>json_encode(['type'=>'true_false','illustration'=>'📖','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function fi(int $lid, string $title, array $sentences): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'reading',
            'content'=>json_encode(['type'=>'fill_in','illustration'=>'✏️','sentences'=>$sentences]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    // ── READING C3 ────────────────────────────────────────────────────────
    private function readingC3(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Comprehension','Short Passages','Animals');
        $this->mcq($l1,'Comprehension C3-1',
            'The cat sat on the mat. It was a big black cat. It had green eyes and a long tail. What colour were the cat\'s eyes?',
            ['Blue','Brown','Green','Yellow'],2);
        $this->mcq($l1,'Comprehension C3-2',
            'Ambe went to the market with his mother. They bought tomatoes, onions and fish. How many items did they buy?',
            ['2','3','4','5'],1);
        $this->tf($l1,'Reading C3','In the passage above, Ambe went to the market alone.',false);

        $l2 = $this->mkLesson($sid,'Vocabulary','Word Study','Synonyms and Antonyms');
        $this->mcq($l2,'Synonym C3','A word that means the same as "fast" is ___.',['slow','quick','heavy','tall'],1);
        $this->mcq($l2,'Antonym C3','The opposite of "clean" is ___.',['bright','shiny','dirty','new'],2);
        $this->mcq($l2,'Word in context C3','She was very ___ when she lost her bag. (sad)',['happy','worried','excited','angry'],1);

        $l3 = $this->mkLesson($sid,'Grammar in Reading','Parts of Speech','Nouns and Verbs');
        $this->mcq($l3,'Noun in sentence','In "The tall tree fell down", which is a noun?',['tall','fell','tree','down'],2);
        $this->mcq($l3,'Verb in sentence','In "She quickly ran to school", which is a verb?',['quickly','She','school','ran'],3);
        $this->fi($l3,'Fill in with correct word',[
            ['text'=>'The children ___ playing football.','answer'=>'are'],
            ['text'=>'She ___ a letter to her friend.','answer'=>'wrote'],
            ['text'=>'They ___ to school every morning.','answer'=>'walk'],
        ]);

        $this->command->info('   Reading C3: 9 exercises');
    }

    // ── READING C4 ────────────────────────────────────────────────────────
    private function readingC4(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Comprehension','Passages','People and Places');
        $this->mcq($l1,'Comprehension C4-1',
            'Yaoundé is the capital city of Cameroon. It is located in the Centre Region. The city has many schools, hospitals and government buildings. What region is Yaoundé in?',
            ['Coastal','Western','Centre','Northern'],2);
        $this->mcq($l1,'Comprehension C4-2',
            'The farmer woke up at 5am. He fed his animals, then went to his farm. He worked for six hours. At what time did the farmer wake up?',
            ['4am','5am','6am','7am'],1);
        $this->mcq($l1,'Main idea C4',
            'Which best describes the main idea of a passage about taking care of the environment?',
            ['Animals are dangerous','We should protect nature','Schools need more books','Children should play outside'],1);
        $this->tf($l1,'Inference C4','If someone "left in a hurry", it means they left slowly.',false);

        $l2 = $this->mkLesson($sid,'Vocabulary','Advanced Words','Compound Words and Idioms');
        $this->mcq($l2,'Compound word C4','"Sunshine" is made from ___ words.',['3','1','2','4'],2);
        $this->mcq($l2,'Idiom meaning C4','"Break a leg" means ___.',['hurt yourself','good luck','run fast','stop walking'],1);
        $this->mcq($l2,'Prefix C4','"Re-" in "rewrite" means ___.',['not','again','before','after'],1);
        $this->fi($l2,'Use the word correctly',[
            ['text'=>'Please ___ (write again) your essay neatly.','answer'=>'rewrite'],
            ['text'=>'The thief was ___ (not honest).','answer'=>'dishonest'],
        ]);

        $l3 = $this->mkLesson($sid,'Grammar','Tenses','Past, Present and Future');
        $this->mcq($l3,'Tense identification','She ___ her homework before dinner. (past)',['does','will do','did','do'],2);
        $this->mcq($l3,'Future tense','Tomorrow, he ___ visit his grandmother.',['visited','visits','will visit','visiting'],2);

        $this->command->info('   Reading C4: 10 exercises');
    }

    // ── READING C5 ────────────────────────────────────────────────────────
    private function readingC5(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Comprehension','Extended Passages','Science and Nature');
        $this->mcq($l1,'Comprehension C5-1',
            'The water cycle is the continuous movement of water through the environment. Water evaporates from oceans and lakes, rises as vapour, forms clouds, and falls as rain or snow. What is the first step in the water cycle described?',
            ['Rainfall','Cloud formation','Evaporation','Snow'],2);
        $this->mcq($l1,'Comprehension C5-2',
            'Scientists believe that climate change is caused mainly by human activities such as burning fossil fuels. This releases greenhouse gases that trap heat in the atmosphere. According to the passage, what causes climate change?',
            ['Natural events only','Human activities mainly','Animals only','Wind patterns'],1);
        $this->mcq($l1,'Author\'s purpose C5','A passage that tells you how to make a recipe has the purpose of ___.',['entertaining','persuading','informing/instructing','arguing'],2);
        $this->tf($l1,'Inference C5','If a character "sighed deeply", they are probably sad or frustrated.',true);

        $l2 = $this->mkLesson($sid,'Vocabulary','Advanced Vocabulary','Context Clues');
        $this->mcq($l2,'Context clues C5',
            'The scientist was tenacious; despite many failures, she never gave up. "Tenacious" means ___.',
            ['lazy','determined','careless','shy'],1);
        $this->mcq($l2,'Figurative language C5','"The sun was a golden coin in the sky" is an example of ___.',
            ['simile','onomatopoeia','metaphor','alliteration'],2);
        $this->mcq($l2,'Word roots C5','The word "transport" contains the root "trans" meaning ___.',
            ['under','across','above','before'],1);

        $l3 = $this->mkLesson($sid,'Grammar','Complex Sentences','Clauses');
        $this->mcq($l3,'Subordinate clause C5',
            'In "Although it was raining, she went out", which part is the subordinate clause?',
            ['she went out','Although it was raining','went out','it was'],1);
        $this->mcq($l3,'Relative clause C5',
            '"The book which I read was interesting." What type of clause is "which I read"?',
            ['main clause','adverbial clause','relative clause','noun clause'],2);
        $this->fi($l3,'Complete with correct clause connector',[
            ['text'=>'___ he was tired, he finished his work.','answer'=>'Although'],
            ['text'=>'She waited ___ he arrived.','answer'=>'until'],
        ]);

        $this->command->info('   Reading C5: 9 exercises');
    }

    // ── READING C6 ────────────────────────────────────────────────────────
    private function readingC6(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Comprehension','FSLC-style Passages','Formal Comprehension');
        $this->mcq($l1,'Comprehension C6-1',
            'Education is the key to national development. A country with well-educated citizens can solve its own problems and compete globally. Therefore, governments must invest heavily in education. What is the main argument of this passage?',
            ['Education is expensive','Education leads to national development','Governments have no money','Citizens should not study'],1);
        $this->mcq($l1,'Comprehension C6-2',
            'Despite the challenges of poverty and lack of resources, many African nations have made remarkable progress in science and technology. The word "remarkable" in this context means ___.',
            ['ordinary','disappointing','noteworthy and impressive','unknown'],2);
        $this->mcq($l1,'Tone of passage C6',
            'A passage that uses words like "must", "should" and "it is vital" has a ___ tone.',
            ['humorous','persuasive/argumentative','sad','neutral'],1);
        $this->tf($l1,'FSLC preparation','Reading comprehension questions often ask about main ideas, vocabulary and inference.',true);

        $l2 = $this->mkLesson($sid,'Literature','Poetry and Prose','Analysing Texts');
        $this->mcq($l2,'Stanza in poetry','A stanza in a poem is similar to a ___ in prose.',['word','sentence','paragraph','chapter'],2);
        $this->mcq($l2,'Rhyme scheme','In a poem where lines 1 and 3 rhyme, and lines 2 and 4 rhyme, the rhyme scheme is ___.',
            ['AABB','ABAB','ABBA','AAAA'],1);
        $this->mcq($l2,'Narrative voice','When the narrator says "I", the story is told in the ___ person.',
            ['second','third','first','fourth'],2);

        $l3 = $this->mkLesson($sid,'Writing Skills','Essay Types','Types of Writing');
        $this->mcq($l3,'Expository writing','An expository essay aims to ___.',
            ['tell a story','express feelings','explain and inform','persuade the reader'],2);
        $this->mcq($l3,'Argumentative essay','An argumentative essay must include ___.',
            ['only facts','a clear position and supporting evidence','only opinions','a story'],1);
        $this->fi($l3,'FSLC writing prompts',[
            ['text'=>'Begin your story: "It was a dark and stormy night when suddenly ___"','answer'=>'I heard a knock at the door'],
            ['text'=>'A formal letter ends with "Yours ___" or "Yours sincerely"','answer'=>'faithfully'],
        ]);

        $this->command->info('   Reading C6: 10 exercises');
    }
}
