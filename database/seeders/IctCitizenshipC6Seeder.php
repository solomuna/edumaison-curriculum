<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IctCitizenshipC6Seeder extends Seeder
{
    public function run(): void
    {
        $this->ictC6();
        $this->citizenshipC6();
        $this->command->info('✅ ICT C6 and Citizenship C6 seeded');
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

    // ── ICT C6 (subject_id = 46) ──────────────────────────────────────────
    private function ictC6(): void
    {
        $sid = 46;

        // Computer Systems
        $l1 = $this->mkLesson($sid,'Computer Systems','Hardware and OS','Operating Systems');
        $this->mcq($l1,'Operating Systems','ict','💻','Which of these is an operating system?',['Microsoft Word','Windows','Google Chrome','Photoshop'],1);
        $this->mcq($l1,'Linux vs Windows','ict','🖥️','Which operating system is free and open-source?',['Windows','macOS','Linux','Android'],2);
        $this->match($l1,'Match OS to device','ict','📱','Match each OS to its device type.',[
            ['word'=>'Windows','image'=>'🖥️'],['word'=>'Android','image'=>'📱'],
            ['word'=>'macOS','image'=>'💻'],['word'=>'Linux','image'=>'🐧'],
        ]);
        $this->mcq($l1,'Storage devices','ict','💾','Which stores the most data?',['USB flash drive','Memory card','Hard disk drive','Floppy disk'],2);
        $this->tf($l1,'RAM fact','ict','RAM is permanent storage.',false);
        $this->tf($l1,'OS fact','ict','An operating system manages computer hardware and software.',true);

        // Productivity Tools
        $l2 = $this->mkLesson($sid,'Productivity Tools','Office Applications','Word Processing and Spreadsheets');
        $this->mcq($l2,'Word processing','ict','📄','Which feature makes text bold in a word processor?',['Ctrl+I','Ctrl+U','Ctrl+B','Ctrl+S'],2);
        $this->mcq($l2,'Spreadsheets','ict','📊','In a spreadsheet, data is organised in rows and ___.',['pages','slides','columns','paragraphs'],2);
        $this->mcq($l2,'PowerPoint','ict','📽️','Which software is used to create presentations?',['Microsoft Word','Microsoft Excel','Microsoft PowerPoint','Microsoft Access'],2);
        $this->tf($l2,'Spreadsheet fact','ict','A spreadsheet can perform calculations automatically.',true);
        $this->mcq($l2,'File management','ict','📁','To save a file with a new name, you use ___.',['Save','Save As','Open','Close'],1);

        // Internet & Communication
        $l3 = $this->mkLesson($sid,'Internet and Communication','Online Communication','Email and Internet Safety');
        $this->mcq($l3,'Email attachment','ict','📧','To send a document with an email, you use ___.',['CC','BCC','Forward','Attach file'],3);
        $this->mcq($l3,'Search engines','ict','🔍','Which is a search engine?',['Facebook','Twitter','Google','WhatsApp'],2);
        $this->tf($l3,'Internet safety','ict','You should share your password with your best friend.',false);
        $this->tf($l3,'Email fact','ict','CC means Carbon Copy in email.',true);
        $this->mcq($l3,'Social media safety','ict','🛡️','What should you do if a stranger contacts you online?',
            ['Share your address','Tell a trusted adult','Ignore it','Accept their request'],1);

        // Ethics & Safety
        $l4 = $this->mkLesson($sid,'Ethics and Safety','ICT Ethics','Copyright and Cybercrime');
        $this->mcq($l4,'Copyright','ict','©️','Copyright protects ___.',['only music','only books','creative works','public information'],2);
        $this->mcq($l4,'Plagiarism','ict','📝','Using someone else\'s work without credit is ___.',['research','sharing','plagiarism','copying'],2);
        $this->tf($l4,'Cybercrime fact','ict','Sending spam emails is a form of cybercrime.',true);
        $this->mcq($l4,'Computer virus','ict','🦠','What protects a computer from viruses?',['Firewall only','Antivirus software','Screen protector','Password'],1);
        $this->tf($l4,'Hacking','ict','Hacking into someone\'s account without permission is illegal.',true);

        // Computational Thinking
        $l5 = $this->mkLesson($sid,'Computational Thinking','Scratch Programming','Variables and Debugging');
        $this->mcq($l5,'Variables in Scratch','ict','🔧','In Scratch, a variable is used to ___.',['draw shapes','store data','play sounds','change colour'],1);
        $this->mcq($l5,'Debugging','ict','🐛','Finding and fixing errors in a program is called ___.',['coding','debugging','compiling','running'],1);
        $this->tf($l5,'Algorithm fact','ict','An algorithm is a step-by-step set of instructions.',true);
        $this->mcq($l5,'Conditional in Scratch','ict','🔀','An "if-then" block in Scratch is an example of ___.',['loop','variable','conditional statement','event'],2);

        $this->command->info('   ICT C6: '.DB::table('exercises')
            ->join('lessons','exercises.lesson_id','=','lessons.id')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)->count().' exercises');
    }

    // ── CITIZENSHIP C6 (subject_id = 47) ─────────────────────────────────
    private function citizenshipC6(): void
    {
        $sid = 47;

        // National Symbols
        $l1 = $this->mkLesson($sid,'National Identity','National Symbols','Emblems of Cameroon');
        $this->mcq($l1,'National flag','revision','🇨🇲','What are the colours of the Cameroon flag?',
            ['Red, white, blue','Green, red, yellow','Blue, white, red','Green, white, red'],1);
        $this->mcq($l1,'National motto','revision','🏛️','What is Cameroon\'s national motto?',
            ['Unity, Work, Progress','Peace, Work, Fatherland','Freedom, Justice, Peace','Work, Unity, Peace'],1);
        $this->tf($l1,'Flag fact','revision','The star on Cameroon\'s flag is gold.',true);
        $this->mcq($l1,'Capital city','revision','🏙️','What is the political capital of Cameroon?',
            ['Douala','Bafoussam','Yaoundé','Garoua'],2);

        // State Institutions
        $l2 = $this->mkLesson($sid,'Government','State Institutions','Branches of Government');
        $this->mcq($l2,'President role','revision','👤','Who is the head of state in Cameroon?',
            ['The Prime Minister','The President of the Republic','The Mayor','The Governor'],1);
        $this->mcq($l2,'National Assembly','revision','🏛️','The National Assembly is part of ___.',
            ['Executive power','Judicial power','Legislative power','Military power'],2);
        $this->tf($l2,'Government fact','revision','Cameroon has a bicameral parliament.',true);
        $this->mcq($l2,'Elections','revision','🗳️','How often are presidential elections held in Cameroon?',
            ['Every 3 years','Every 5 years','Every 7 years','Every 10 years'],2);

        // Rights and Duties
        $l3 = $this->mkLesson($sid,'Rights and Duties','Citizenship','Rights and Responsibilities');
        $this->mcq($l3,'Children rights','revision','👶','Which organisation protects children\'s rights worldwide?',
            ['UNESCO','WHO','UNICEF','FAO'],2);
        $this->tf($l3,'Rights fact','revision','Every child has the right to education.',true);
        $this->tf($l3,'Duties fact','revision','Paying taxes is a duty of citizens.',true);
        $this->mcq($l3,'Child trafficking','revision','⚠️','Child trafficking is ___.',
            ['acceptable in some cultures','a crime everywhere','only illegal in some countries','not a serious problem'],1);

        // Peace and Conflict
        $l4 = $this->mkLesson($sid,'Peace Education','Conflict Resolution','Managing Conflicts');
        $this->mcq($l4,'Conflict resolution','revision','🤝','The best way to resolve a conflict is through ___.',
            ['fighting','dialogue','ignoring','revenge'],1);
        $this->tf($l4,'Peace fact','revision','Tolerance means accepting others despite differences.',true);
        $this->mcq($l4,'International orgs','revision','🌍','Which organisation works for world peace?',
            ['FIFA','UNESCO','UN','WHO'],2);

        // History C6
        $l5 = $this->mkLesson($sid,'History','Cameroon History','Independence and Reunification');
        $this->mcq($l5,'Independence','revision','📅','When did Cameroon gain independence from France?',
            ['1958','1960','1961','1963'],1);
        $this->mcq($l5,'Reunification','revision','🤝','Southern Cameroons reunified with French Cameroon in ___.',
            ['1960','1961','1962','1963'],1);
        $this->tf($l5,'Bilingualism','revision','Cameroon is officially a bilingual country (French and English).',true);
        $this->mcq($l5,'German colonisation','revision','🏴','Which country first colonised Cameroon?',
            ['France','Britain','Germany','Portugal'],2);

        $this->command->info('   Citizenship C6: '.DB::table('exercises')
            ->join('lessons','exercises.lesson_id','=','lessons.id')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)->count().' exercises');
    }
}
