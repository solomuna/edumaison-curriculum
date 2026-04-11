<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HandwritingC3C6Seeder extends Seeder
{
    public function run(): void
    {
        foreach ([7=>3, 8=>4, 9=>5, 10=>6] as $levelId=>$class) {
            $sid = $this->mkSubject($levelId, 'Handwriting', 'handwriting-c'.$class);
            $this->{'hwC'.$class}($sid);
        }
        $this->command->info('✅ Handwriting C3-C6 seeded');
    }

    private function mkSubject(int $levelId, string $name, string $slug): int
    {
        $existing = DB::table('subjects')->where('level_id',$levelId)->where('name',$name)->value('id');
        if ($existing) return $existing;
        return DB::table('subjects')->insertGetId([
            'level_id'=>$levelId,'name'=>$name,'slug'=>$slug,
            'icon'=>'✍️','is_active'=>true,'created_at'=>now(),'updated_at'=>now(),
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

    private function hw(int $lid, string $title, array $prompts): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'handwriting',
            'content'=>json_encode(['type'=>'handwriting','illustration'=>'✍️','prompts'=>$prompts]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function mcq(int $lid, string $title, string $q, array $opts, int $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'handwriting',
            'content'=>json_encode(['type'=>'mcq','illustration'=>'✏️',
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function hwC3(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Script Writing','Joining Letters','Script Print Letters');
        $this->hw($l1,'Script lowercase a-e',['a','b','c','d','e']);
        $this->hw($l1,'Script lowercase f-j',['f','g','h','i','j']);
        $this->hw($l1,'Script lowercase k-p',['k','l','m','n','o','p']);
        $this->hw($l1,'Script lowercase q-z',['q','r','s','t','u','v','w','x','y','z']);

        $l2 = $this->mkLesson($sid,'Words','Script Words','Common Words');
        $this->hw($l2,'Trace script words',['school','friend','mother','father','teacher']);
        $this->hw($l2,'Trace more script words',['Cameroon','Africa','family','garden','market']);

        $l3 = $this->mkLesson($sid,'Sentences','Writing Sentences','Simple Sentences');
        $this->hw($l3,'Copy sentence 1',['I go to school every day.']);
        $this->hw($l3,'Copy sentence 2',['My mother loves me very much.']);
        $this->mcq($l3,'Handwriting rule C3','Which is correct for good handwriting?',
            ['Write very small','Leave no spaces between words','Sit up straight and hold pen correctly','Write with your left hand only'],2);

        $this->command->info('   Handwriting C3: 9 exercises');
    }

    private function hwC4(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Script Writing','Script Writing','Script Print Letters');
        $this->hw($l1,'Trace script letters — an, at, am',['an','at','am','and','ant']);
        $this->hw($l1,'Trace script letters — in, is, it',['in','is','it','its','into']);
        $this->hw($l1,'Trace script letters — on, of, or',['on','of','or','old','open']);

        $l2 = $this->mkLesson($sid,'Words','Vocabulary in Writing','Subject-related Words');
        $this->hw($l2,'Science words',['water','plant','animal','energy','nature']);
        $this->hw($l2,'Maths words',['number','fraction','measure','circle','square']);

        $l3 = $this->mkLesson($sid,'Sentences','Paragraph Writing','Short Paragraphs');
        $this->hw($l3,'Copy paragraph 1',['The market is a busy place. People buy and sell all kinds of things.']);
        $this->hw($l3,'Copy paragraph 2',['Cameroon is a beautiful country. It has forests, mountains and rivers.']);
        $this->mcq($l3,'Paragraph spacing C4','When starting a new paragraph, you should ___.',
            ['Write on the same line','Start from the margin','Indent or leave a line space','Use capital letters only'],2);

        $this->command->info('   Handwriting C4: 8 exercises');
    }

    private function hwC5(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Script Writing','Script Writing','Fluent Writing');
        $this->hw($l1,'Capital script A-F',['A','B','C','D','E','F']);
        $this->hw($l1,'Capital script G-M',['G','H','I','J','K','L','M']);
        $this->hw($l1,'Capital script N-Z',['N','O','P','Q','R','S','T','U','V','W','X','Y','Z']);

        $l2 = $this->mkLesson($sid,'Composition','Writing Skills','Letter and Essay Writing');
        $this->hw($l2,'Copy formal letter opening',['Dear Sir/Madam,','I am writing to inform you that...']);
        $this->hw($l2,'Copy essay introduction',['Cameroon is a country in Central Africa. It is home to over 20 million people.']);

        $l3 = $this->mkLesson($sid,'Punctuation','Correct Punctuation','Marks and Signs');
        $this->hw($l3,'Copy punctuation examples',['She said, "I am happy."','Where are you going?','What a beautiful day!']);
        $this->mcq($l3,'Punctuation C5','Which punctuation mark ends a question?',
            ['.','!','?',','],2);

        $this->command->info('   Handwriting C5: 7 exercises');
    }

    private function hwC6(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'FSLC Preparation','Exam Writing','Neat and Legible Writing');
        $this->hw($l1,'Copy FSLC-style sentence',['Education is the most powerful weapon we can use to change the world.']);
        $this->hw($l1,'Copy formal letter ending',['Yours faithfully,','Yours sincerely,']);
        $this->hw($l1,'Copy proverb',['A stitch in time saves nine.','Actions speak louder than words.']);

        $l2 = $this->mkLesson($sid,'Advanced Writing','Speed and Neatness','Writing for Exams');
        $this->hw($l2,'Copy essay paragraph',
            ['Cameroon, often called "Africa in Miniature", is a country of remarkable diversity. It has rainforests in the south, savannah in the north, and mountains in the west.']);
        $this->hw($l2,'Copy scientific text',
            ['Water is essential for life. The water cycle involves evaporation, condensation and precipitation.']);

        $l3 = $this->mkLesson($sid,'Presentation','Writing Quality','Margins and Layout');
        $this->mcq($l3,'Exam writing C6','In an exam, your writing should be ___.',
            ['as fast as possible only','neat, legible and well-organised','very large','in capital letters only'],1);
        $this->mcq($l3,'Margin C6','Why do we leave a margin when writing?',
            ['To waste paper','For the examiner to write comments','For decoration','No reason'],1);

        $this->command->info('   Handwriting C6: 7 exercises');
    }
}
