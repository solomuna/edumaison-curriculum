<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeCalculationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->timeC1();
        $this->timeC2();
        $this->timeC3();
        $this->timeC4();
        $this->timeC5();
        $this->timeC6();
        $this->command->info('✅ Time calculations seeded for C1-C6');
    }

    private function lid(int $sid): ?int
    {
        return DB::table('lessons')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)
            ->value('lessons.id');
    }

    private function mcq(int $lid, string $title, string $ill, string $q, array $opts, int $ans): void
    {
        DB::table('exercises')->insert([
            'lesson_id'=>$lid,'title'=>$title,'category'=>'mathematics',
            'content'=>json_encode(['type'=>'mcq','illustration'=>$ill,
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert([
            'lesson_id'=>$lid,'title'=>$title,'category'=>'mathematics',
            'content'=>json_encode(['type'=>'true_false','illustration'=>'⏰','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
    }

    // ── C1 (subject 13) — basic units ────────────────────────────────────
    private function timeC1(): void
    {
        $id = $this->lid(13); if (!$id) return;

        $this->mcq($id,'Minutes in 1 hour','🕐','How many minutes are in 1 hour?',['30','45','60','100'],2);
        $this->mcq($id,'Hours in 1 day','☀️','How many hours are in 1 day?',['12','20','24','30'],2);
        $this->mcq($id,'Days in 1 week','📅','How many days are in 1 week?',['5','6','7','8'],2);
        $this->tf($id,'Time fact','There are 60 seconds in one minute.',true);
        $this->tf($id,'Days fact','A week has 8 days.',false);

        $this->command->info('   Time C1: 5 exercises');
    }

    // ── C2 (subject 19) — multiplied units ───────────────────────────────
    private function timeC2(): void
    {
        $id = $this->lid(19); if (!$id) return;

        $this->mcq($id,'Minutes in 2 hours','🕐','How many minutes are in 2 hours?',['60','90','120','150'],2);
        $this->mcq($id,'Hours in 2 days','☀️','How many hours are in 2 days?',['24','36','48','72'],2);
        $this->mcq($id,'Days in 2 weeks','📅','How many days are in 2 weeks?',['7','10','14','21'],2);
        $this->mcq($id,'Months in 1 year','📅','How many months are in 1 year?',['10','11','12','13'],2);
        $this->mcq($id,'Seconds in 2 minutes','⏱️','How many seconds are in 2 minutes?',['60','100','120','180'],2);
        $this->tf($id,'Leap year','A leap year has 366 days.',true);
        $this->tf($id,'February','February always has 28 days.',false);

        $this->command->info('   Time C2: 7 exercises');
    }

    // ── C3 (subject 25) — conversion + calendar ──────────────────────────
    private function timeC3(): void
    {
        $id = $this->lid(25); if (!$id) return;

        $this->mcq($id,'Minutes in 3 hours','🕐','How many minutes are in 3 hours?',['120','150','180','240'],2);
        $this->mcq($id,'Hours in 3 days','☀️','How many hours are in 3 days?',['48','60','72','96'],2);
        $this->mcq($id,'Days in 3 weeks','📅','How many days are in 3 weeks?',['14','18','21','28'],2);
        $this->mcq($id,'Weeks in 1 year','📅','How many weeks are in 1 year?',['48','50','52','54'],2);
        $this->mcq($id,'Half hour','⏰','How many minutes in half an hour?',['15','20','30','45'],2);
        $this->mcq($id,'Quarter hour','⏰','How many minutes in a quarter of an hour?',['10','15','20','30'],1);
        $this->tf($id,'Calendar fact','There are 4 weeks in every month.',false);
        $this->tf($id,'Year fact','There are 365 days in an ordinary year.',true);

        $this->command->info('   Time C3: 8 exercises');
    }

    // ── C4 (subject 31) — duration + arrival + am/pm ─────────────────────
    private function timeC4(): void
    {
        $id = $this->lid(31); if (!$id) return;

        $this->mcq($id,'Minutes in 4 hours','🕐','How many minutes are in 4 hours?',['180','200','240','300'],2);
        $this->mcq($id,'Hours in 4 days','☀️','How many hours are in 4 days?',['72','84','96','108'],2);

        // Duration problems
        $this->mcq($id,'Lesson duration','⏰',
            'A lesson starts at 8:30 am and ends at 10:00 am. How long is the lesson?',
            ['1 hour','1 hour 30 minutes','2 hours','45 minutes'],1);
        $this->mcq($id,'Arrival time','🚶',
            'You leave home at 7:15 am and walk for 45 minutes. What time do you arrive?',
            ['7:45 am','8:00 am','8:15 am','8:30 am'],1);
        $this->mcq($id,'Match duration','⚽',
            'A football match starts at 3:00 pm and lasts 90 minutes. What time does it end?',
            ['4:00 pm','4:15 pm','4:30 pm','5:00 pm'],2);
        $this->mcq($id,'End time','📚',
            'School ends at 1:30 pm. If you study for 2 hours after school, at what time do you finish?',
            ['2:30 pm','3:00 pm','3:30 pm','4:00 pm'],2);
        $this->mcq($id,'Days in April','📅','How many days are in April?',
            ['28','29','30','31'],2);
        $this->tf($id,'AM PM fact','12:00 pm is noon (midday).',true);
        $this->tf($id,'Duration','60 minutes = 1 hour.',true);

        $this->command->info('   Time C4: 9 exercises');
    }

    // ── C5 (subject 37) — speed/distance/time + world time ───────────────
    private function timeC5(): void
    {
        $id = $this->lid(37); if (!$id) return;

        $this->mcq($id,'Minutes in 5 hours','🕐','How many minutes are in 5 hours?',['240','280','300','360'],2);
        $this->mcq($id,'Hours in 5 days','☀️','How many hours are in 5 days?',['100','110','120','130'],2);

        // Speed / distance / time
        $this->mcq($id,'Speed formula','🚗','Speed = Distance ÷ ___.',['Speed','Height','Time','Weight'],2);
        $this->mcq($id,'Speed calculation','🏃',
            'A car travels 120 km in 2 hours. What is its speed?',
            ['40 km/h','50 km/h','60 km/h','70 km/h'],2);
        $this->mcq($id,'Distance calculation','🚌',
            'A bus travels at 50 km/h for 3 hours. How far does it travel?',
            ['100 km','120 km','150 km','200 km'],2);
        $this->mcq($id,'Time calculation','✈️',
            'A plane travels 600 km at 300 km/h. How long does the journey take?',
            ['1 hour','2 hours','3 hours','4 hours'],1);

        // World time zones
        $this->mcq($id,'Cameroon time zone','🌍','Cameroon is in the ___ time zone.',
            ['GMT+0','GMT+1','GMT+2','GMT+3'],1);
        $this->mcq($id,'Time zone difference','🌍',
            'London (GMT+0) and Yaoundé (GMT+1) — when it is 10:00 am in London, it is ___ in Yaoundé.',
            ['9:00 am','10:00 am','11:00 am','12:00 pm'],2);
        $this->tf($id,'Time zones fact','Countries east of GMT have positive time zones.',true);

        $this->command->info('   Time C5: 9 exercises');
    }

    // ── C6 (subject 43) — compound duration + international time ─────────
    private function timeC6(): void
    {
        $id = $this->lid(43); if (!$id) return;

        $this->mcq($id,'Minutes in 6 hours','🕐','How many minutes are in 6 hours?',['300','320','360','400'],2);
        $this->mcq($id,'Hours in 1 week','☀️','How many hours are in 1 week?',['148','158','168','178'],2);

        // Advanced calculations
        $this->mcq($id,'Complex duration','⏰',
            'A journey starts at 9:45 am and ends at 2:15 pm. How long is the journey?',
            ['3 hours 30 minutes','4 hours','4 hours 30 minutes','5 hours'],2);
        $this->mcq($id,'Compound time','✈️',
            'A flight from Yaoundé (GMT+1) to London (GMT+0) takes 6 hours. If you depart at 10:00 am Yaoundé time, what time do you arrive in London?',
            ['3:00 pm','4:00 pm','5:00 pm','6:00 pm'],0);
        $this->mcq($id,'Speed word problem','🚂',
            'A train travels 480 km at 80 km/h. How many hours does the journey take?',
            ['4','5','6','8'],2);
        $this->mcq($id,'Seconds in 1 hour','⏱️','How many seconds are in 1 hour?',
            ['360','600','3600','6000'],2);
        $this->tf($id,'Leap year rule','A year divisible by 4 is usually a leap year.',true);
        $this->tf($id,'Time fact','There are 24 hours in a day and 7 days in a week.',true);

        $this->command->info('   Time C6: 8 exercises');
    }
}
