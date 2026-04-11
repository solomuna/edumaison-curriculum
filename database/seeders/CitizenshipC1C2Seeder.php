<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitizenshipC1C2Seeder extends Seeder
{
    public function run(): void
    {
        $sidC1 = $this->mkSubject(5, 'Citizenship', 'citizenship-c1', '🏛️');
        $sidC2 = $this->mkSubject(6, 'Citizenship', 'citizenship-c2', '🏛️');

        $this->citizenshipC1($sidC1);
        $this->citizenshipC2($sidC2);

        $this->command->info('✅ Citizenship C1 and C2 seeded');
        $this->command->info('   Subject IDs: C1='.$sidC1.' C2='.$sidC2);
    }

    private function mkSubject(int $levelId, string $name, string $slug, string $icon): int
    {
        $existing = DB::table('subjects')->where('level_id',$levelId)->where('name',$name)->value('id');
        if ($existing) return $existing;
        return DB::table('subjects')->insertGetId([
            'level_id'=>$levelId,'name'=>$name,'slug'=>$slug,
            'icon'=>$icon,'is_active'=>true,
            'created_at'=>now(),'updated_at'=>now(),
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
            'lesson_id'=>$lid,'title'=>$title,'category'=>'revision',
            'content'=>json_encode(['type'=>'mcq','illustration'=>$ill,
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert([
            'lesson_id'=>$lid,'title'=>$title,'category'=>'revision',
            'content'=>json_encode(['type'=>'true_false','illustration'=>'🏛️',
                'statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
    }

    // ── CITIZENSHIP C1 ────────────────────────────────────────────────────
    private function citizenshipC1(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'National Symbols','Our Flag','The Cameroon Flag');
        $this->mcq($l1,'Flag colours','🇨🇲','The Cameroon flag has ___ colours.',
            ['2','3','4','5'],1);
        $this->mcq($l1,'Flag star','⭐','The star on the Cameroon flag is ___.',
            ['red','blue','green','yellow'],3);
        $this->tf($l1,'Flag fact','The Cameroon flag has green, red and yellow colours.',true);
        $this->mcq($l1,'National anthem','🎵','We sing the national anthem to show love for ___.',
            ['our friends','our school','our country','our food'],2);

        $l2 = $this->mkLesson($sid,'Values','Good Behaviour','Respect and Obedience');
        $this->mcq($l2,'Respect','🤝','We should ___ our teachers and parents.',
            ['fight','respect','ignore','shout at'],1);
        $this->tf($l2,'Obedience','We should obey the rules at school.',true);
        $this->mcq($l2,'Honesty','💛','Being honest means ___.',
            ['telling lies','stealing','telling the truth','fighting'],2);
        $this->tf($l2,'Kindness','We should be kind to everyone.',true);

        $l3 = $this->mkLesson($sid,'Rights','Children\'s Rights','Right to Education');
        $this->mcq($l3,'Right to school','📚','Every child has the right to ___.',
            ['work in a market','go to school','drive a car','cook food'],1);
        $this->tf($l3,'Protection right','Every child has the right to be safe.',true);
        $this->mcq($l3,'Rights','👶','Who protects children\'s rights?',
            ['Police only','UNICEF','Soldiers','No one'],1);

        $l4 = $this->mkLesson($sid,'Peace','Living Together','Peace at Home and School');
        $this->mcq($l4,'Peace','🕊️','Peace means living without ___.',
            ['food','water','fighting','school'],2);
        $this->tf($l4,'Peace fact','Sharing with others helps us live in peace.',true);
        $this->mcq($l4,'Conflict','🤝','When we have a problem with a friend, we should ___.',
            ['fight','talk calmly','run away','cry'],1);

        $this->command->info('   Citizenship C1: 14 exercises');
    }

    // ── CITIZENSHIP C2 ────────────────────────────────────────────────────
    private function citizenshipC2(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'National Identity','Our Country','Cameroon');
        $this->mcq($l1,'Capital city','🏙️','The capital of Cameroon is ___.',
            ['Douala','Bafoussam','Yaoundé','Limbe'],2);
        $this->mcq($l1,'Official languages','🗣️','Cameroon has ___ official languages.',
            ['1','2','3','4'],1);
        $this->tf($l1,'Cameroon fact','Cameroon is in Africa.',true);
        $this->mcq($l1,'National day','📅','Cameroon\'s National Day is on ___.',
            ['January 1st','May 20th','December 25th','July 4th'],1);

        $l2 = $this->mkLesson($sid,'Rules','Rules and Regulations','Rules at School and Home');
        $this->mcq($l2,'School rules','📋','School rules help us to ___.',
            ['fight','learn and be safe','play all day','miss school'],1);
        $this->tf($l2,'Rules fact','Rules are made to protect us.',true);
        $this->mcq($l2,'Authority','👩‍🏫','At school, we obey ___.',
            ['only our friends','only older students','our teachers and headmaster','no one'],2);
        $this->tf($l2,'Home rules','We have rules at home too.',true);

        $l3 = $this->mkLesson($sid,'Rights','Rights and Duties','Our Rights and Duties');
        $this->mcq($l3,'Child rights','👶','Children have the right to ___.',
            ['work in factories','go to school and be safe','drive cars','stay home all day'],1);
        $this->tf($l3,'Duties','We have duties as well as rights.',true);
        $this->mcq($l3,'Duty','🧹','Keeping our classroom clean is our ___.',
            ['right','choice','duty','game'],2);

        $l4 = $this->mkLesson($sid,'Community','Our Community','Working Together');
        $this->mcq($l4,'Community','🏘️','A community is a group of people who ___.',
            ['fight each other','live and work together','never talk','eat together only'],1);
        $this->tf($l4,'Community fact','We should help each other in our community.',true);
        $this->mcq($l4,'Tolerance','💛','Tolerance means accepting people who are ___.',
            ['exactly like us','different from us','richer than us','younger than us'],1);

        $this->command->info('   Citizenship C2: 14 exercises');
    }
}
