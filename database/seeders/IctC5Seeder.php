<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IctC5Seeder extends Seeder
{
    public function run(): void
    {
        $this->ictC5();
        $this->command->info('✅ ICT C5 seeded');
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

    private function ictC5(): void
    {
        $sid = 40;

        // Computer Systems
        $l1 = $this->mkLesson($sid,'Computer Systems','Hardware and Storage','Storage Devices');
        $this->mcq($l1,'USB drive','💾','A USB flash drive is used to ___.',
            ['print documents','store and transfer files','connect to internet','take photos'],1);
        $this->mcq($l1,'Hard disk','🖥️','Which storage device holds the most data?',
            ['USB flash drive','Memory card','Hard disk','Floppy disk'],2);
        $this->tf($l1,'RAM fact','RAM stores data temporarily while the computer is on.',true);
        $this->mcq($l1,'Computer ports','🔌','A USB port is used to ___.',
            ['display images','connect devices','store files','type text'],1);

        // Word Processing C5
        $l2 = $this->mkLesson($sid,'Productivity Tools','Word Processing','Tables and Formatting');
        $this->mcq($l2,'Insert table','📄','To insert a table in a document, you go to ___.',
            ['File menu','Insert menu','View menu','Format menu'],1);
        $this->mcq($l2,'Page orientation','📄','Landscape orientation means the page is ___.',
            ['taller than wide','wider than tall','square','circular'],1);
        $this->tf($l2,'Print preview','Print Preview shows how a document will look when printed.',true);
        $this->mcq($l2,'Undo','↩️','To undo an action in a word processor, you press ___.',
            ['Ctrl+Y','Ctrl+Z','Ctrl+X','Ctrl+P'],1);

        // PowerPoint C5
        $l3 = $this->mkLesson($sid,'Productivity Tools','Presentations','PowerPoint Basics');
        $this->mcq($l3,'Slide show','📽️','In PowerPoint, each page is called a ___.',
            ['document','sheet','slide','page'],2);
        $this->mcq($l3,'Insert image','🖼️','To add a picture to a slide, you use ___.',
            ['File > Open','Insert > Picture','View > Slide','Format > Font'],1);
        $this->tf($l3,'PowerPoint fact','You can add animations to slides in PowerPoint.',true);

        // Spreadsheets C5
        $l4 = $this->mkLesson($sid,'Productivity Tools','Spreadsheets','Data and Graphs');
        $this->mcq($l4,'Spreadsheet cell','📊','In a spreadsheet, the intersection of a row and column is called a ___.',
            ['page','cell','table','box'],1);
        $this->mcq($l4,'SUM formula','📊','Which formula adds up numbers in Excel?',
            ['=ADD()','=TOTAL()','=SUM()','=PLUS()'],2);
        $this->tf($l4,'Chart fact','A spreadsheet can create charts from data.',true);
        $this->mcq($l4,'Sort data','📊','To arrange data from A to Z, you use ___.',
            ['Filter','Sort','Format','Insert'],1);

        // Internet C5
        $l5 = $this->mkLesson($sid,'Internet','Email','Creating and Sending Emails');
        $this->mcq($l5,'Email address','📧','An email address always contains ___.',
            ['a phone number','the @ symbol','a password','a website'],1);
        $this->mcq($l5,'Subject line','📧','The subject line in an email describes ___.',
            ['the sender','the receiver','what the email is about','the date'],2);
        $this->tf($l5,'Email safety','You should open email attachments from unknown senders.',false);
        $this->mcq($l5,'CC in email','📧','CC in an email means ___.',
            ['Copy Code','Carbon Copy','Computer Copy','Cyber Copy'],1);

        // Ethics C5
        $l6 = $this->mkLesson($sid,'Ethics and Safety','ICT Ethics','Copyright and Responsible Use');
        $this->mcq($l6,'Copyright','©️','Copyright means you cannot use someone\'s work without ___.',
            ['paying a lot','their permission','going to school','a computer'],1);
        $this->tf($l6,'Virus protection','Installing antivirus software protects your computer.',true);
        $this->mcq($l6,'Screen time','👀','Looking at a screen for too long can cause ___.',
            ['stronger eyes','eye strain','better vision','no problems'],1);

        // Scratch C5
        $l7 = $this->mkLesson($sid,'Computational Thinking','Scratch','Motion and Colours');
        $this->mcq($l7,'Scratch sprite','🐱','In Scratch, the character you programme is called a ___.',
            ['block','sprite','loop','script'],1);
        $this->mcq($l7,'Motion block','🔧','To make a sprite move in Scratch, you use a ___ block.',
            ['Sound','Motion','Looks','Events'],1);
        $this->tf($l7,'Scratch fact','In Scratch, you can change the colour of a sprite.',true);

        $this->command->info('   ICT C5 (subject 40): '.DB::table('exercises')
            ->join('lessons','exercises.lesson_id','=','lessons.id')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)->count().' exercises');
    }
}
