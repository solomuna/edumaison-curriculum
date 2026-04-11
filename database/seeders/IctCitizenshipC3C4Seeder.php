<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IctCitizenshipC3C4Seeder extends Seeder
{
    public function run(): void
    {
        $this->ictC3();
        $this->ictC4();
        $this->citizenshipC3();
        $this->citizenshipC4();
        $this->command->info('✅ ICT and Citizenship C3/C4 seeded');
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

    private function mcq(int $lid, string $title, string $cat, string $ill, string $q, array $opts, int $ans): void
    {
        DB::table('exercises')->insert([
            'lesson_id'=>$lid,'title'=>$title,'category'=>$cat,
            'content'=>json_encode(['type'=>'mcq','illustration'=>$ill,
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
    }

    private function tf(int $lid, string $title, string $cat, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert([
            'lesson_id'=>$lid,'title'=>$title,'category'=>$cat,
            'content'=>json_encode(['type'=>'true_false','illustration'=>'💡','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
    }

    private function match(int $lid, string $title, string $cat, string $ill, string $q, array $pairs): void
    {
        DB::table('exercises')->insert([
            'lesson_id'=>$lid,'title'=>$title,'category'=>$cat,
            'content'=>json_encode(['type'=>'match_pairs','illustration'=>$ill,'question'=>$q,'pairs'=>$pairs]),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
    }

    // ── ICT C3 (subject_id = 28) ──────────────────────────────────────────
    private function ictC3(): void
    {
        $sid = 28;
        $l1 = $this->mkLesson($sid,'Computer Basics','Computer Components','Parts of a Computer');
        $this->mcq($l1,'Monitor','ict','🖥️','The screen of a computer is called a ___.',['keyboard','monitor','mouse','printer'],1);
        $this->mcq($l1,'Keyboard','ict','⌨️','We use the ___ to type letters and numbers.',['mouse','monitor','keyboard','speaker'],2);
        $this->match($l1,'Match parts','ict','💻','Match each part to what it does.',[
            ['word'=>'Mouse','image'=>'🖱️'],['word'=>'Keyboard','image'=>'⌨️'],
            ['word'=>'Monitor','image'=>'🖥️'],['word'=>'Printer','image'=>'🖨️'],
        ]);
        $this->tf($l1,'Computer fact','ict','A computer can store information.',true);
        $this->tf($l1,'Mouse fact','ict','We use the mouse to type words.',false);

        $l2 = $this->mkLesson($sid,'Computer Basics','Software','Operating Systems and Programs');
        $this->mcq($l2,'Windows OS','ict','💻','Windows is an example of ___.',['a game','an operating system','a virus','a browser'],1);
        $this->mcq($l2,'Word processor','ict','📄','Which program is used to write a letter?',['Paint','Calculator','Word processor','Antivirus'],2);
        $this->tf($l2,'Software fact','ict','Software is the physical part of a computer.',false);

        $l3 = $this->mkLesson($sid,'Internet','Web Browsers','Browsing the Internet');
        $this->mcq($l3,'Web browser','ict','🌐','Which of these is a web browser?',['Microsoft Word','Google Chrome','Notepad','Calculator'],1);
        $this->mcq($l3,'Internet','ict','🌐','The internet is used to ___.',['cook food','find information','wash clothes','build houses'],1);
        $this->tf($l3,'Internet safety','ict','You should never share your password online.',true);

        $this->command->info('   ICT C3: 11 exercises');
    }

    // ── ICT C4 (subject_id = 34) ──────────────────────────────────────────
    private function ictC4(): void
    {
        $sid = 34;
        $l1 = $this->mkLesson($sid,'Computer Systems','Hardware','Input and Output Devices');
        $this->mcq($l1,'Input device','ict','⌨️','Which is an INPUT device?',['Monitor','Printer','Speaker','Keyboard'],3);
        $this->mcq($l1,'Output device','ict','🖨️','Which is an OUTPUT device?',['Keyboard','Mouse','Printer','Scanner'],2);
        $this->match($l1,'Input vs Output','ict','💻','Sort these devices.',[
            ['word'=>'Keyboard','image'=>'⌨️'],['word'=>'Monitor','image'=>'🖥️'],
            ['word'=>'Mouse','image'=>'🖱️'],['word'=>'Printer','image'=>'🖨️'],
        ]);
        $this->tf($l1,'Scanner fact','ict','A scanner is an input device.',true);
        $this->tf($l1,'Monitor fact','ict','A monitor is an input device.',false);

        $l2 = $this->mkLesson($sid,'Productivity','Word Processing','Formatting Documents');
        $this->mcq($l2,'Bold text','ict','📄','Which shortcut makes text bold?',['Ctrl+I','Ctrl+B','Ctrl+U','Ctrl+S'],1);
        $this->mcq($l2,'Save file','ict','💾','To save your work you press ___.',['Ctrl+P','Ctrl+C','Ctrl+S','Ctrl+V'],2);
        $this->mcq($l2,'Copy paste','ict','📋','To paste copied text you press ___.',['Ctrl+X','Ctrl+C','Ctrl+V','Ctrl+Z'],2);
        $this->tf($l2,'Formatting fact','ict','You can change the font size in a word processor.',true);

        $l3 = $this->mkLesson($sid,'Internet','Social Media','Using Social Media Safely');
        $this->mcq($l3,'Social media','ict','📱','Which is an example of social media?',['Microsoft Word','Facebook','Google Chrome','Windows'],1);
        $this->tf($l3,'Online safety','ict','It is safe to meet someone you only know online.',false);
        $this->tf($l3,'Privacy fact','ict','You should keep your personal information private online.',true);
        $this->mcq($l3,'Health and ICT','ict','👀','To protect your eyes when using a computer, you should ___.',
            ['sit closer to the screen','take regular breaks','use the computer in the dark','never blink'],1);

        $this->command->info('   ICT C4: 13 exercises');
    }

    // ── CITIZENSHIP C3 (subject_id = 29) ─────────────────────────────────
    private function citizenshipC3(): void
    {
        $sid = 29;
        $l1 = $this->mkLesson($sid,'National Identity','National Symbols','Emblems of Cameroon');
        $this->mcq($l1,'National flag','revision','🇨🇲','How many colours are on the Cameroon flag?',['2','3','4','5'],1);
        $this->mcq($l1,'National anthem','revision','🎵','The national anthem is sung to show ___.',['sadness','love for one\'s country','anger','hunger'],1);
        $this->tf($l1,'Flag fact','revision','The Cameroon flag has a star on it.',true);
        $this->mcq($l1,'Capital','revision','🏙️','What is the capital of Cameroon?',['Douala','Bafoussam','Yaoundé','Limbe'],2);

        $l2 = $this->mkLesson($sid,'Values','Universal Values','Respect and Honesty');
        $this->mcq($l2,'Respect','revision','🤝','Respecting others means ___.',
            ['fighting with them','treating them well','ignoring them','laughing at them'],1);
        $this->tf($l2,'Honesty','revision','Honesty means telling the truth.',true);
        $this->mcq($l2,'Rules','revision','📋','Rules help us to ___.',
            ['cause problems','live together peacefully','do whatever we want','hurt others'],1);

        $l3 = $this->mkLesson($sid,'Rights','Children\'s Rights','Right to Education');
        $this->mcq($l3,'Right to education','revision','📚','Every child has the right to ___.',
            ['work in a factory','go to school','stay at home','drive a car'],1);
        $this->tf($l3,'Rights fact','revision','Children have the right to be protected from harm.',true);
        $this->mcq($l3,'UNICEF','revision','🌍','UNICEF is an organisation that protects ___.',
            ['animals','trees','children','soldiers'],2);

        $this->command->info('   Citizenship C3: 10 exercises');
    }

    // ── CITIZENSHIP C4 (subject_id = 35) ─────────────────────────────────
    private function citizenshipC4(): void
    {
        $sid = 35;
        $l1 = $this->mkLesson($sid,'History','Cameroon History','German Colonisation');
        $this->mcq($l1,'German colonisation','revision','🏛️','Which country first colonised Cameroon?',
            ['France','Britain','Germany','Portugal'],2);
        $this->mcq($l1,'Resistance leaders','revision','👤','Who was a Cameroonian resistance leader against colonisation?',
            ['Napoleon','Manga Bell','Mandela','Nkrumah'],1);
        $this->tf($l1,'History fact','revision','Cameroon was a German colony called Kamerun.',true);

        $l2 = $this->mkLesson($sid,'Government','Institutions','Local Government');
        $this->mcq($l2,'Mayor role','revision','🏛️','The mayor is the head of ___.',
            ['the country','the region','the council','the school'],2);
        $this->mcq($l2,'Elections','revision','🗳️','Citizens vote in elections to choose their ___.',
            ['teachers','leaders','doctors','friends'],1);
        $this->tf($l2,'Government fact','revision','The President is the head of state in Cameroon.',true);

        $l3 = $this->mkLesson($sid,'Peace','Peace and Security','Living in Peace');
        $this->mcq($l3,'Conflict resolution','revision','🤝','The best way to solve a problem with a friend is ___.',
            ['fighting','talking calmly','ignoring them','telling everyone'],1);
        $this->tf($l3,'Peace fact','revision','Peace means living without war or violence.',true);
        $this->mcq($l3,'UNESCO','revision','🌍','UNESCO promotes ___.',
            ['war','education and culture','sports only','business'],1);
        $this->mcq($l3,'Tolerance','revision','💛','Tolerance means ___.',
            ['accepting differences','hating others','fighting','ignoring others'],0);

        $this->command->info('   Citizenship C4: 10 exercises');
    }
}
