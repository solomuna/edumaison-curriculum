<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatisticsNumbersSeeder extends Seeder
{
    public function run(): void
    {
        $this->statisticsC1(); $this->statisticsC2();
        $this->statisticsC3(); $this->statisticsC4();
        $this->statisticsC5(); $this->statisticsC6();
        $this->bodmasC3(); $this->bodmasC4(); $this->bodmasC5();
        $this->primesC4(); $this->primesC5();
        $this->decimalsC4(); $this->decimalsC5(); $this->decimalsC6();
        $this->numberBasesC5(); $this->numberBasesC6();
        $this->command->info('✅ Statistics, BODMAS, Primes, Decimals, Number Bases seeded');
    }

    private function lid(int $sid): ?int
    {
        return DB::table('lessons')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)->value('lessons.id');
    }

    private function mcq(int $lid, string $title, string $ill, string $q, array $opts, int $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'mathematics',
            'content'=>json_encode(['type'=>'mcq','illustration'=>$ill,
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'mathematics',
            'content'=>json_encode(['type'=>'true_false','illustration'=>'📊','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    // ═══ STATISTICS ═══════════════════════════════════════════════════════

    private function statisticsC1(): void
    {
        $id = $this->lid(13); if (!$id) return;
        $this->mcq($id,'Tally marks C1','📊','How many does |||| represent?',['3','4','5','6'],1);
        $this->mcq($id,'Count objects','📊','There are 3 apples and 2 oranges. How many fruits in total?',['4','5','6','7'],1);
        $this->tf($id,'Data fact','We collect data to count and compare things.',true);
        $this->command->info('   Statistics C1: 3');
    }

    private function statisticsC2(): void
    {
        $id = $this->lid(19); if (!$id) return;
        $this->mcq($id,'Tally marks C2','📊','How many does |||| | represent?',['4','5','6','7'],1);
        $this->mcq($id,'Most popular','📊','Colours: Red=5, Blue=3, Green=7. Which is most popular?',['Red','Blue','Green','Equal'],2);
        $this->mcq($id,'Least popular','📊','Colours: Red=5, Blue=3, Green=7. Which is least popular?',['Red','Blue','Green','Equal'],1);
        $this->tf($id,'Tally fact','A group of 5 tallies is shown as |||| with a line through.',true);
        $this->command->info('   Statistics C2: 4');
    }

    private function statisticsC3(): void
    {
        $id = $this->lid(25); if (!$id) return;
        $this->mcq($id,'Read bar chart','📊','A bar chart shows: Maths=8, English=6, Science=9. Which subject has the highest score?',['Maths','English','Science','Equal'],2);
        $this->mcq($id,'Frequency table','📊','In a frequency table, the word "frequency" means ___.',['colour','size','how many times','which one'],2);
        $this->mcq($id,'Pictogram','📊','In a pictogram, each picture = 2 items. 4 pictures = ___ items.',['2','4','6','8'],3);
        $this->mcq($id,'Data collection','📊','A survey asks 10 students their favourite fruit. This is an example of ___.',['experiment','data collection','calculation','measurement'],1);
        $this->tf($id,'Bar chart fact','In a bar chart, taller bars represent larger values.',true);
        $this->tf($id,'Pictogram fact','In a pictogram, each symbol represents a fixed number of items.',true);
        $this->command->info('   Statistics C3: 6');
    }

    private function statisticsC4(): void
    {
        $id = $this->lid(31); if (!$id) return;
        $this->mcq($id,'Mean (average)','📊','The mean of 4, 6, 8 = ___.',['5','6','7','8'],1);
        $this->mcq($id,'Mode','📊','Mode of {3, 5, 3, 7, 3, 5} = ___.',['3','5','7','6'],0);
        $this->mcq($id,'Median','📊','Median of {2, 4, 6, 8, 10} = ___.',['4','5','6','7'],2);
        $this->mcq($id,'Range','📊','Range of {3, 7, 12, 5} = ___.',['7','8','9','10'],2);
        $this->mcq($id,'Interpret graph','📊','A line graph shows temperature rising from 20°C to 30°C. By how much did it rise?',['5°C','8°C','10°C','15°C'],2);
        $this->tf($id,'Mean formula','Mean = sum of all values ÷ number of values.',true);
        $this->tf($id,'Mode fact','The mode is the value that appears most often.',true);
        $this->command->info('   Statistics C4: 7');
    }

    private function statisticsC5(): void
    {
        $id = $this->lid(37); if (!$id) return;
        $this->mcq($id,'Mean calculation','📊','Mean of {10, 20, 30, 40, 50} = ___.',['25','30','35','40'],1);
        $this->mcq($id,'Median odd','📊','Median of {3, 5, 7, 9, 11} = ___.',['5','7','9','6'],1);
        $this->mcq($id,'Median even','📊','Median of {4, 6, 8, 10} = ___.',['6','7','8','5'],1);
        $this->mcq($id,'Probability intro','📊','A coin is tossed. Probability of getting heads = ___.',['1/4','1/3','1/2','3/4'],2);
        $this->mcq($id,'Mapping','📊','A mapping shows how one set relates to another. It uses ___.',['numbers only','arrows','colours','letters'],1);
        $this->tf($id,'Probability fact','Probability is always between 0 and 1.',true);
        $this->command->info('   Statistics C5: 6');
    }

    private function statisticsC6(): void
    {
        $id = $this->lid(43); if (!$id) return;
        $this->mcq($id,'Pie chart','📊','In a pie chart, the whole circle = ___ degrees.',['90°','180°','270°','360°'],3);
        $this->mcq($id,'Pie chart sector','📊','In a pie chart of 60 students, 15 like Maths. The angle for Maths = ___.',['60°','90°','120°','150°'],1);
        $this->mcq($id,'Scatter graph','📊','A scatter graph shows the ___ between two sets of data.',['colour','relationship','age','height'],1);
        $this->mcq($id,'Frequency polygon','📊','A frequency polygon connects the ___ of bars in a histogram.',['corners','bottoms','midpoints','tops'],2);
        $this->tf($id,'Pie chart fact','The angles in a pie chart must add up to 360°.',true);
        $this->tf($id,'Statistics use','Statistics helps us analyse and interpret data.',true);
        $this->command->info('   Statistics C6: 6');
    }

    // ═══ BODMAS REINFORCEMENT ══════════════════════════════════════════════

    private function bodmasC3(): void
    {
        $id = $this->lid(25); if (!$id) return;
        $this->mcq($id,'BODMAS order','🧮','BODMAS: what does the D stand for?',['Data','Division','Decimal','Distance'],1);
        $this->mcq($id,'BODMAS C3 — brackets','🧮','(3 + 4) × 2 = ___.',['10','14','11','13'],1);
        $this->mcq($id,'BODMAS C3 — multiply first','🧮','3 + 4 × 2 = ___.',['14','11','10','13'],1);
        $this->tf($id,'BODMAS rule','In BODMAS, multiplication is done before addition.',true);
        $this->command->info('   BODMAS C3: 4');
    }

    private function bodmasC4(): void
    {
        $id = $this->lid(31); if (!$id) return;
        $this->mcq($id,'BODMAS C4 — mixed','🧮','20 ÷ 4 + 3 × 2 = ___.',['8','10','11','13'],2);
        $this->mcq($id,'BODMAS C4 — brackets','🧮','(10 − 4) × (2 + 3) = ___.',['20','25','30','35'],2);
        $this->mcq($id,'BODMAS C4 — exponent','🧮','2³ + 4 × 3 = ___.',['20','24','26','28'],0);
        $this->tf($id,'BODMAS exponent','In BODMAS, "O" stands for Order (powers/exponents).',true);
        $this->command->info('   BODMAS C4: 4');
    }

    private function bodmasC5(): void
    {
        $id = $this->lid(37); if (!$id) return;
        $this->mcq($id,'BODMAS C5 complex','🧮','4 + 3² × (8 ÷ 4) − 1 = ___.',['20','21','22','23'],0);
        $this->mcq($id,'BODMAS C5 division','🧮','100 ÷ 5² + 3 × 4 = ___.',['12','14','16','18'],2);
        $this->tf($id,'BODMAS complete','BODMAS stands for Brackets, Order, Division, Multiplication, Addition, Subtraction.',true);
        $this->command->info('   BODMAS C5: 3');
    }

    // ═══ PRIME NUMBERS ════════════════════════════════════════════════════

    private function primesC4(): void
    {
        $id = $this->lid(31); if (!$id) return;
        $this->mcq($id,'Prime number def','🔢','A prime number has exactly ___ factors.',['1','2','3','4'],1);
        $this->mcq($id,'Is 7 prime?','🔢','Is 7 a prime number?',['No','Yes','Maybe','Cannot tell'],1);
        $this->mcq($id,'Is 9 prime?','🔢','Is 9 a prime number?',['Yes, it is prime','No, 9 = 3 × 3','Yes, 9 has 2 factors','Cannot tell'],1);
        $this->mcq($id,'Primes 1-10','🔢','Which list contains ONLY prime numbers?',
            ['{2,3,4,5}','{2,3,5,7}','{1,3,5,7}','{3,5,7,9}'],1);
        $this->mcq($id,'Smallest prime','🔢','What is the smallest prime number?',['1','2','3','5'],1);
        $this->tf($id,'Prime fact','1 is NOT a prime number.',true);
        $this->tf($id,'Even prime','2 is the only even prime number.',true);
        $this->command->info('   Primes C4: 7');
    }

    private function primesC5(): void
    {
        $id = $this->lid(37); if (!$id) return;
        $this->mcq($id,'Prime factorisation','🔢','Express 12 as a product of prime factors.',['2 × 6','2² × 3','3 × 4','2 × 2 × 3'],1);
        $this->mcq($id,'Prime between 20-30','🔢','Which are prime numbers between 20 and 30?',
            ['{21,23}','{23,29}','{23,27}','{21,29}'],1);
        $this->mcq($id,'Composite number','🔢','A composite number has ___ than 2 factors.',['fewer','exactly','more','less'],2);
        $this->tf($id,'Composite fact','All even numbers greater than 2 are composite.',true);
        $this->command->info('   Primes C5: 4');
    }

    // ═══ DECIMALS REINFORCEMENT ════════════════════════════════════════════

    private function decimalsC4(): void
    {
        $id = $this->lid(31); if (!$id) return;
        $this->mcq($id,'Decimal place value','💯','In 3.47, the digit 4 is in the ___ place.',['tens','units','tenths','hundredths'],2);
        $this->mcq($id,'Add decimals','💯','1.5 + 2.3 = ___.',['3.5','3.7','3.8','4.0'],2);
        $this->mcq($id,'Subtract decimals','💯','5.8 − 2.3 = ___.',['3.4','3.5','3.6','3.7'],1);
        $this->mcq($id,'Compare decimals','💯','Which is bigger — 0.7 or 0.65?',['0.65','0.7','Equal','Cannot tell'],1);
        $this->tf($id,'Decimal fact','0.1 is the same as 1/10.',true);
        $this->command->info('   Decimals C4: 5');
    }

    private function decimalsC5(): void
    {
        $id = $this->lid(37); if (!$id) return;
        $this->mcq($id,'Multiply decimals','💯','0.4 × 0.3 = ___.',['0.07','0.12','0.12','1.2'],1);
        $this->mcq($id,'Divide decimals','💯','1.2 ÷ 0.4 = ___.',['0.3','3','30','0.03'],1);
        $this->mcq($id,'Round to 1dp','💯','Round 3.47 to 1 decimal place.',['3.4','3.5','3.0','4.0'],1);
        $this->mcq($id,'Decimal to percentage','%','0.35 as a percentage = ___.',['3.5%','35%','350%','0.35%'],1);
        $this->tf($id,'Rounding rule','If the next digit is 5 or more, round up.',true);
        $this->command->info('   Decimals C5: 5');
    }

    private function decimalsC6(): void
    {
        $id = $this->lid(43); if (!$id) return;
        $this->mcq($id,'Standard form','💯','3500 in standard form = ___.',['3.5 × 10²','3.5 × 10³','35 × 10²','0.35 × 10⁴'],1);
        $this->mcq($id,'Recurring decimal','💯','1/3 as a decimal = ___.',['0.3','0.33','0.333...','0.003'],2);
        $this->mcq($id,'Significant figures','💯','Round 0.004567 to 2 significant figures.',['0.0045','0.0046','0.005','0.46'],1);
        $this->tf($id,'Standard form fact','Standard form is a way to write very large or small numbers.',true);
        $this->command->info('   Decimals C6: 4');
    }

    // ═══ NUMBER BASES ══════════════════════════════════════════════════════

    private function numberBasesC5(): void
    {
        $id = $this->lid(37); if (!$id) return;
        $this->mcq($id,'Base 2 intro','🔢','In base 2, we only use the digits ___.',['0 and 1','0,1,2','0 to 9','1 to 9'],0);
        $this->mcq($id,'Convert to base 10','🔢','101₂ in base 10 = ___.',['3','5','7','9'],1);
        $this->mcq($id,'Convert to base 2','🔢','5 in base 2 = ___.',['100','101','110','111'],1);
        $this->tf($id,'Binary fact','Computers use base 2 (binary) system.',true);
        $this->command->info('   Number bases C5: 4');
    }

    private function numberBasesC6(): void
    {
        $id = $this->lid(43); if (!$id) return;
        $this->mcq($id,'Base 8 (octal)','🔢','In base 8, the largest single digit is ___.',['6','7','8','9'],1);
        $this->mcq($id,'Convert 14 to base 2','🔢','14 in base 2 = ___.',['1100','1110','1010','1111'],1);
        $this->mcq($id,'Convert 1101₂ to base 10','🔢','1101₂ in base 10 = ___.',['11','12','13','14'],2);
        $this->mcq($id,'Add in base 2','🔢','11₂ + 10₂ = ___.',['100₂','101₂','110₂','111₂'],1);
        $this->tf($id,'Number base fact','In base 10 we use digits 0-9.',true);
        $this->command->info('   Number bases C6: 5');
    }
}
