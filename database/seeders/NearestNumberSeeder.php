<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NearestNumberSeeder extends Seeder
{
    public function run(): void
    {
        $this->nearestC3(); // nearest 10
        $this->nearestC4(); // nearest 10, 100
        $this->nearestC5(); // nearest 10, 100, 1000
        $this->nearestC6(); // nearest 10, 100, 1000, decimal
        $this->command->info('✅ Nearest number exercises seeded C3-C6');
    }

    private function lid(int $sid): ?int
    {
        return DB::table('lessons')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)->value('lessons.id');
    }

    private function mcq(int $lid, string $title, string $q, array $opts, int $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'mathematics',
            'content'=>json_encode(['type'=>'mcq','illustration'=>'🔢',
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'mathematics',
            'content'=>json_encode(['type'=>'true_false','illustration'=>'🔢',
                'statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    // ── C3 (subject 25) — nearest 10 ────────────────────────────────────
    private function nearestC3(): void
    {
        $id = $this->lid(25); if (!$id) return;

        $this->mcq($id,'Round to nearest 10 — 34','Round 34 to the nearest 10.',['20','30','40','50'],1);
        $this->mcq($id,'Round to nearest 10 — 47','Round 47 to the nearest 10.',['30','40','50','60'],2);
        $this->mcq($id,'Round to nearest 10 — 75','Round 75 to the nearest 10.',['60','70','80','90'],2);
        $this->mcq($id,'Round to nearest 10 — 82','Round 82 to the nearest 10.',['70','80','90','100'],1);
        $this->mcq($id,'Round to nearest 10 — 55','Round 55 to the nearest 10.',['50','60','55','65'],1);
        $this->tf($id,'Rounding rule — 5','When the units digit is 5 or more, we round up.',true);
        $this->tf($id,'Rounding rule — 4','When the units digit is 4 or less, we round down.',true);
        $this->mcq($id,'Nearest 10 — word problem','A farmer has 67 mangoes. To the nearest 10, this is approximately ___.',['60','70','65','80'],1);

        $this->command->info('   Nearest number C3: 8 exercises');
    }

    // ── C4 (subject 31) — nearest 10 and 100 ────────────────────────────
    private function nearestC4(): void
    {
        $id = $this->lid(31); if (!$id) return;

        // Nearest 10
        $this->mcq($id,'Round to nearest 10 — 136','Round 136 to the nearest 10.',['120','130','140','150'],1);
        $this->mcq($id,'Round to nearest 10 — 285','Round 285 to the nearest 10.',['270','280','290','300'],2);

        // Nearest 100
        $this->mcq($id,'Round to nearest 100 — 347','Round 347 to the nearest 100.',['200','300','400','500'],1);
        $this->mcq($id,'Round to nearest 100 — 650','Round 650 to the nearest 100.',['500','600','700','800'],2);
        $this->mcq($id,'Round to nearest 100 — 782','Round 782 to the nearest 100.',['600','700','800','900'],2);
        $this->mcq($id,'Round to nearest 100 — 450','Round 450 to the nearest 100.',['300','400','500','600'],2);
        $this->tf($id,'Nearest 100 rule','To round to the nearest 100, look at the tens digit.',true);
        $this->mcq($id,'Nearest 100 — word problem','A school has 438 pupils. To the nearest 100, that is approximately ___.',['300','400','500','600'],1);
        $this->mcq($id,'Compare nearest 10 and 100','Round 673 to the nearest 10, then to the nearest 100.',['670 and 600','670 and 700','680 and 700','680 and 600'],1);

        $this->command->info('   Nearest number C4: 9 exercises');
    }

    // ── C5 (subject 37) — nearest 10, 100, 1000 ─────────────────────────
    private function nearestC5(): void
    {
        $id = $this->lid(37); if (!$id) return;

        // Nearest 10
        $this->mcq($id,'Round to nearest 10 — 3467','Round 3467 to the nearest 10.',['3460','3470','3480','3500'],1);

        // Nearest 100
        $this->mcq($id,'Round to nearest 100 — 4 650','Round 4650 to the nearest 100.',['4500','4600','4700','4800'],2);
        $this->mcq($id,'Round to nearest 100 — 8 349','Round 8349 to the nearest 100.',['8200','8300','8400','8500'],1);

        // Nearest 1000
        $this->mcq($id,'Round to nearest 1000 — 3500','Round 3500 to the nearest 1000.',['2000','3000','4000','5000'],2);
        $this->mcq($id,'Round to nearest 1000 — 7 249','Round 7249 to the nearest 1000.',['6000','7000','8000','9000'],1);
        $this->mcq($id,'Round to nearest 1000 — 12 600','Round 12 600 to the nearest 1000.',['11000','12000','13000','14000'],2);
        $this->tf($id,'Nearest 1000 rule','To round to the nearest 1000, look at the hundreds digit.',true);
        $this->mcq($id,'Nearest 1000 — word problem','A factory produced 8 740 bottles. To the nearest 1000, that is approximately ___.',['7000','8000','9000','10000'],1);

        // Estimation using rounding
        $this->mcq($id,'Estimate using rounding','Estimate 487 + 312 by rounding to the nearest 100.',['700','800','900','1000'],1);
        $this->mcq($id,'Estimate product','Estimate 49 × 21 by rounding to nearest 10.',['900','1000','1100','1200'],1);

        $this->command->info('   Nearest number C5: 10 exercises');
    }

    // ── C6 (subject 43) — nearest 10/100/1000 + decimals ────────────────
    private function nearestC6(): void
    {
        $id = $this->lid(43); if (!$id) return;

        // Nearest 1000 and 10000
        $this->mcq($id,'Round to nearest 1000 — 45 600','Round 45 600 to the nearest 1000.',['44000','45000','46000','47000'],1);
        $this->mcq($id,'Round to nearest 10000 — 67 400','Round 67 400 to the nearest 10 000.',['60000','65000','67000','70000'],0);

        // Decimal rounding
        $this->mcq($id,'Round to nearest whole number — 7.6','Round 7.6 to the nearest whole number.',['6','7','8','9'],2);
        $this->mcq($id,'Round to nearest whole number — 12.3','Round 12.3 to the nearest whole number.',['11','12','13','14'],1);
        $this->mcq($id,'Round to 1 decimal place — 3.47','Round 3.47 to 1 decimal place.',['3.3','3.4','3.5','3.6'],1);
        $this->mcq($id,'Round to 1 decimal place — 8.75','Round 8.75 to 1 decimal place.',['8.6','8.7','8.8','8.9'],2);
        $this->mcq($id,'Round to 2 decimal places — 5.436','Round 5.436 to 2 decimal places.',['5.42','5.43','5.44','5.45'],1);

        // Significant figures
        $this->mcq($id,'1 significant figure — 347','Round 347 to 1 significant figure.',['300','340','350','400'],0);
        $this->mcq($id,'2 significant figures — 4865','Round 4865 to 2 significant figures.',['4800','4900','5000','4860'],0);

        $this->tf($id,'Rounding decimals','To round to 1 decimal place, look at the second decimal digit.',true);

        $this->command->info('   Nearest number C6: 10 exercises');
    }
}
