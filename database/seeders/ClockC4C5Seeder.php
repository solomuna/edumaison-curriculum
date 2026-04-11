<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClockC4C5Seeder extends Seeder
{
    public function run(): void
    {
        $this->clockC4();
        $this->clockC5();
        $this->command->info('✅ Clock exercises C4 and C5 seeded');
    }

    private function lid(int $sid): ?int
    {
        return DB::table('lessons')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)
            ->value('lessons.id');
    }

    private function clock(int $lid, int $h, int $m, array $opts, int $ans, string $q = 'What time does the clock show?'): void
    {
        DB::table('exercises')->insert([
            'lesson_id'  => $lid,
            'title'      => sprintf('What time is it? (%d:%02d)', $h, $m),
            'category'   => 'mathematics',
            'content'    => json_encode([
                'type'        => 'clock_reading',
                'illustration'=> '🕐',
                'question'    => $q,
                'hours'       => $h,
                'minutes'     => $m,
                'options'     => $opts,
                'answer'      => $ans,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // ── CLOCK C4 (subject 31) — am/pm + quarter past/to ─────────────────
    private function clockC4(): void
    {
        $id = $this->lid(31); if (!$id) return;

        // Basic reading with am/pm
        $this->clock($id, 8, 0,  ['8:00 am','8:00 pm','7:00 am','9:00 am'], 0, 'It is morning. What time is it?');
        $this->clock($id, 3, 0,  ['3:00 am','3:00 pm','2:00 pm','4:00 pm'], 1, 'It is afternoon. What time is it?');
        $this->clock($id, 10, 30, ['10:00','10:30','11:30','9:30'], 1);
        $this->clock($id, 6, 15,  ['6:15','6:45','5:15','7:15'], 0, 'What is "quarter past 6"?');
        $this->clock($id, 5, 45,  ['5:45','6:15','5:15','6:45'], 0, 'What is "quarter to 6"?');
        $this->clock($id, 2, 30,  ['2:00','2:30','3:30','1:30'], 1, 'What is "half past 2"?');
        $this->clock($id, 11, 0,  ['10:00','11:00','12:00','1:00'], 1);
        $this->clock($id, 4, 45,  ['4:45','5:15','4:15','5:45'], 0);

        // MCQ about time concepts
        DB::table('exercises')->insert([
            'lesson_id'  => $id,
            'title'      => 'AM or PM',
            'category'   => 'mathematics',
            'content'    => json_encode([
                'type' => 'mcq', 'illustration' => '🕐',
                'questions' => [
                    ['text' => 'School starts at 7:30 ___.',
                     'options' => ['am','pm','noon','midnight'], 'answer' => 0],
                ]
            ]),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('exercises')->insert([
            'lesson_id'  => $id,
            'title'      => 'Minutes in an hour',
            'category'   => 'mathematics',
            'content'    => json_encode([
                'type' => 'mcq', 'illustration' => '⏱️',
                'questions' => [
                    ['text' => 'How many minutes are in one hour?',
                     'options' => ['30','45','60','90'], 'answer' => 2],
                ]
            ]),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('exercises')->insert([
            'lesson_id'  => $id,
            'title'      => 'Quarter past and to',
            'category'   => 'mathematics',
            'content'    => json_encode([
                'type' => 'true_false', 'illustration' => '🕐',
                'statement' => 'Quarter past 3 means 3:15.',
                'answer' => true,
            ]),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->command->info('   Clock C4: 11 exercises');
    }

    // ── CLOCK C5 (subject 37) — speed/distance/time + world time zones ───
    private function clockC5(): void
    {
        $id = $this->lid(37); if (!$id) return;

        // Advanced clock reading
        $this->clock($id, 10, 45, ['10:45','11:15','10:15','9:45'], 0);
        $this->clock($id, 7,  20, ['7:20','7:40','6:20','8:20'], 0);
        $this->clock($id, 1,  55, ['1:55','2:05','1:05','2:55'], 0, 'What time is shown? (5 minutes to 2)');
        $this->clock($id, 11, 35, ['11:35','12:25','11:25','12:35'], 0);

        // Time calculations
        $mcqs = [
            ['Duration','⏱️','A film starts at 2:00 pm and lasts 2 hours. It ends at ___.',
             ['3:00 pm','4:00 pm','5:00 pm','6:00 pm'],1],
            ['Time difference','🌍','London is 1 hour behind Paris. When it is 3pm in Paris, it is ___ in London.',
             ['2:00 pm','4:00 pm','3:00 pm','1:00 pm'],0],
            ['Speed formula','🏃','Distance = Speed × ___.',
             ['Distance','Height','Time','Weight'],2],
            ['Speed calculation','🚗','A car travels 60km in 2 hours. Its speed is ___.',
             ['20 km/h','30 km/h','60 km/h','120 km/h'],1],
            ['World time zones','🌍','Cameroon is in the ___ time zone.',
             ['GMT+0','GMT+1','GMT+2','GMT+3'],1],
            ['Seconds','⏱️','How many seconds are in one minute?',
             ['30','45','60','100'],2],
            ['Days in a year','📅','A leap year has ___ days.',
             ['364','365','366','367'],2],
        ];

        foreach ($mcqs as [$title,$ill,$q,$opts,$ans]) {
            DB::table('exercises')->insert([
                'lesson_id'  => $id,
                'title'      => $title,
                'category'   => 'mathematics',
                'content'    => json_encode([
                    'type'=>'mcq','illustration'=>$ill,
                    'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]
                ]),
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $this->command->info('   Clock C5: 11 exercises');
    }
}
