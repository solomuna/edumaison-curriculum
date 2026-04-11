<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IctC1C2Seeder extends Seeder
{
    public function run(): void
    {
        // Create ICT subjects for C1 and C2 if not exist
        $sidC1 = $this->mkSubject(5, 'ICT', 'ict-c1', '💻');
        $sidC2 = $this->mkSubject(6, 'ICT', 'ict-c2', '💻');

        $this->ictC1($sidC1);
        $this->ictC2($sidC2);

        $this->command->info('✅ ICT C1 and C2 seeded');
        $this->command->info('   Subject IDs: C1='.$sidC1.' C2='.$sidC2);
    }

    private function mkSubject(int $levelId, string $name, string $slug, string $icon): int
    {
        $existing = DB::table('subjects')->where('level_id',$levelId)->where('name',$name)->value('id');
        if ($existing) return $existing;
        return DB::table('subjects')->insertGetId([
            'level_id'   => $levelId,
            'name'       => $name,
            'slug'       => $slug,
            'icon'       => $icon,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function mkLesson(int $sid, string $themeName, string $unitName, string $lessonName): int
    {
        $ts = strtolower(preg_replace('/[^a-z0-9]+/i','-',$themeName)).'-'.$sid;
        $tid = DB::table('integrated_themes')->where('slug',$ts)->value('id');
        if (!$tid) {
            $tid = DB::table('integrated_themes')->insertGetId([
                'subject_id'=>$sid,'name'=>$themeName,'slug'=>$ts,
                'created_at'=>now(),'updated_at'=>now(),
            ]);
        }
        $us = strtolower(preg_replace('/[^a-z0-9]+/i','-',$unitName)).'-'.$tid;
        $uid = DB::table('units')->where('slug',$us)->value('id');
        if (!$uid) {
            $uid = DB::table('units')->insertGetId([
                'integrated_theme_id'=>$tid,'name'=>$unitName,'slug'=>$us,
                'created_at'=>now(),'updated_at'=>now(),
            ]);
        }
        $ls = strtolower(preg_replace('/[^a-z0-9]+/i','-',$lessonName)).'-'.$uid;
        $lid = DB::table('lessons')->where('slug',$ls)->value('id');
        if (!$lid) {
            $lid = DB::table('lessons')->insertGetId([
                'unit_id'=>$uid,'name'=>$lessonName,'slug'=>$ls,'type'=>'mixed',
                'created_at'=>now(),'updated_at'=>now(),
            ]);
        }
        return $lid;
    }

    private function mcq(int $lid, string $title, string $ill, string $q, array $opts, int $ans): void
    {
        DB::table('exercises')->insert([
            'lesson_id'=>$lid,'title'=>$title,'category'=>'ict',
            'content'=>json_encode(['type'=>'mcq','illustration'=>$ill,
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert([
            'lesson_id'=>$lid,'title'=>$title,'category'=>'ict',
            'content'=>json_encode(['type'=>'true_false','illustration'=>'💡',
                'statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
    }

    private function match(int $lid, string $title, string $ill, string $q, array $pairs): void
    {
        DB::table('exercises')->insert([
            'lesson_id'=>$lid,'title'=>$title,'category'=>'ict',
            'content'=>json_encode(['type'=>'match_pairs','illustration'=>$ill,
                'question'=>$q,'pairs'=>$pairs]),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
    }

    // ── ICT C1 ────────────────────────────────────────────────────────────
    private function ictC1(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Computer Basics','Parts of a Computer','Identifying Computer Parts');
        $this->mcq($l1,'The screen','🖥️','The part of the computer we look at is the ___.',
            ['keyboard','mouse','monitor','printer'],2);
        $this->mcq($l1,'The keyboard','⌨️','We use the ___ to type.',
            ['mouse','monitor','keyboard','speaker'],2);
        $this->mcq($l1,'The mouse','🖱️','We use the mouse to ___.',
            ['type letters','point and click','print','listen to music'],1);
        $this->tf($l1,'Computer fact','A computer can help us learn.',true);
        $this->tf($l1,'Mouse fact','We use the keyboard to move the arrow on screen.',false);
        $this->match($l1,'Match computer parts','💻','Match each part to its picture.',[
            ['word'=>'Monitor','image'=>'🖥️'],
            ['word'=>'Keyboard','image'=>'⌨️'],
            ['word'=>'Mouse','image'=>'🖱️'],
            ['word'=>'Printer','image'=>'🖨️'],
        ]);

        $l2 = $this->mkLesson($sid,'Computer Basics','Using a Computer','Turning On a Computer');
        $this->mcq($l2,'Power button','🔘','To turn on a computer, you press the ___ button.',
            ['delete','power','enter','space'],1);
        $this->mcq($l2,'Information sources','📻','Which of these gives us information?',
            ['Chair','Radio','Table','Bed'],1);
        $this->tf($l2,'Safety','We should eat and drink near the computer.',false);
        $this->tf($l2,'Computer use','A computer can be used to draw pictures.',true);

        $l3 = $this->mkLesson($sid,'ICT Tools','Communication Tools','Traditional and Modern Tools');
        $this->mcq($l3,'Phone','📱','We use a mobile phone to ___.',
            ['cook food','make calls','wash clothes','build houses'],1);
        $this->mcq($l3,'TV remote','📺','A television remote is used to ___.',
            ['type letters','control the TV','print pictures','take photos'],1);
        $this->tf($l3,'ICT tools','A telephone is an ICT tool.',true);

        $this->command->info('   ICT C1: 13 exercises');
    }

    // ── ICT C2 ────────────────────────────────────────────────────────────
    private function ictC2(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Computer Basics','Computer Components','Input and Output');
        $this->mcq($l1,'Input device','⌨️','The keyboard is an ___ device.',
            ['output','input','storage','processing'],1);
        $this->mcq($l1,'Output device','🖨️','The printer is an ___ device.',
            ['input','storage','output','processing'],2);
        $this->match($l1,'Input or Output','💻','Match each device to its type.',[
            ['word'=>'Keyboard','image'=>'⌨️'],
            ['word'=>'Monitor','image'=>'🖥️'],
            ['word'=>'Mouse','image'=>'🖱️'],
            ['word'=>'Speaker','image'=>'🔊'],
        ]);
        $this->tf($l1,'Keyboard fact','The keyboard is an output device.',false);
        $this->tf($l1,'Speaker fact','A speaker produces sound output.',true);

        $l2 = $this->mkLesson($sid,'Computer Basics','Using a Computer','Mouse and Keyboard Skills');
        $this->mcq($l2,'Double click','🖱️','To open a program, you ___ on its icon.',
            ['single click','double click','right click','drag'],1);
        $this->mcq($l2,'Spacebar','⌨️','The spacebar is used to ___.',
            ['delete text','add a space','press enter','make letters capital'],1);
        $this->mcq($l2,'Enter key','⌨️','The Enter key is used to ___.',
            ['add a space','delete a letter','start a new line','make text bold'],2);
        $this->tf($l2,'Caps Lock','Caps Lock makes all letters uppercase.',true);

        $l3 = $this->mkLesson($sid,'ICT and Society','Information Sources','Finding Information');
        $this->mcq($l3,'Internet','🌐','The internet helps us to ___ information.',
            ['hide','cook','find','destroy'],2);
        $this->mcq($l3,'Safety online','🛡️','Online, you should never share your ___.',
            ['favourite colour','home address','name','school name'],1);
        $this->tf($l3,'Algorithmic thinking','An algorithm tells a computer what to do step by step.',true);
        $this->mcq($l3,'ICT in school','📚','Computers in school help us to ___.',
            ['play all day','learn and research','watch movies only','sleep'],1);

        $this->command->info('   ICT C2: 13 exercises');
    }
}
