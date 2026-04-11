<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetsMoneyfractionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->setsC1(); $this->setsC2();
        $this->setsC3(); $this->setsC4();
        $this->setsC5(); $this->setsC6();
        $this->moneyC1(); $this->moneyC2();
        $this->moneyC3(); $this->moneyC4();
        $this->fractionsC2(); $this->fractionsC3();
        $this->fractionsC4(); $this->fractionsC5();
        $this->fractionsC6();
        $this->command->info('✅ Sets, Money and Fractions seeded C1-C6');
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
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'mathematics',
            'content'=>json_encode(['type'=>'mcq','illustration'=>$ill,
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'mathematics',
            'content'=>json_encode(['type'=>'true_false','illustration'=>'🔢','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    // ═══ SETS ═════════════════════════════════════════════════════════════

    private function setsC1(): void
    {
        $id = $this->lid(13); if (!$id) return;
        $this->mcq($id,'What is a set','⭕','A set is a ___ of objects.',['single','collection','colour','size'],1);
        $this->mcq($id,'Count set members','⭕','How many members in {cat, dog, bird}?',['1','2','3','4'],2);
        $this->tf($id,'Set fact','A set is a group of things that belong together.',true);
        $this->command->info('   Sets C1: 3');
    }

    private function setsC2(): void
    {
        $id = $this->lid(19); if (!$id) return;
        $this->mcq($id,'Empty set','⭕','A set with no members is called an ___ set.',['full','equal','empty','large'],2);
        $this->mcq($id,'Venn diagram','⭕','A Venn diagram uses ___ to show sets.',['squares','triangles','circles','lines'],2);
        $this->mcq($id,'Set symbol','⭕','The symbol ∈ means ___.',['is not in','is equal to','is a member of','is greater than'],2);
        $this->tf($id,'Empty set symbol','The empty set is written as {} or ∅.',true);
        $this->command->info('   Sets C2: 4');
    }

    private function setsC3(): void
    {
        $id = $this->lid(25); if (!$id) return;
        $this->mcq($id,'Intersection','⭕','A={1,2,3} B={2,3,4}. A∩B = ___.',['{}','{1,4}','{2,3}','{1,2,3,4}'],2);
        $this->mcq($id,'Union','⭕','A={1,2} B={3,4}. A∪B = ___.',['{}','{1,2}','{3,4}','{1,2,3,4}'],3);
        $this->mcq($id,'Subset','⭕','If A={1,2} and B={1,2,3}, then A is a ___ of B.',['union','intersection','subset','complement'],2);
        $this->tf($id,'Intersection fact','The intersection contains elements in BOTH sets.',true);
        $this->tf($id,'Union fact','The union contains ALL elements from both sets.',true);
        $this->mcq($id,'Disjoint sets','⭕','A={1,2} B={3,4}. A∩B = ___.',['{}','{1,2,3,4}','{1,2}','{3,4}'],0);
        $this->command->info('   Sets C3: 6');
    }

    private function setsC4(): void
    {
        $id = $this->lid(31); if (!$id) return;
        $this->mcq($id,'Cardinal number','⭕','The number of elements in a set is called the ___ number.',['prime','cardinal','ordinal','natural'],1);
        $this->mcq($id,'Equal sets','⭕','A={1,2,3} B={3,1,2}. These sets are ___.',['different','disjoint','equal','equivalent only'],2);
        $this->mcq($id,'HCF using sets','⭕','Factors of 12 = {1,2,3,4,6,12}. Factors of 8 = {1,2,4,8}. HCF = ___.',['2','4','6','8'],1);
        $this->mcq($id,'LCM using sets','⭕','Multiples of 4 = {4,8,12,16...}. Multiples of 6 = {6,12,18...}. LCM = ___.',['4','6','12','24'],2);
        $this->tf($id,'HCF fact','HCF is the largest number that divides both numbers exactly.',true);
        $this->command->info('   Sets C4: 5');
    }

    private function setsC5(): void
    {
        $id = $this->lid(37); if (!$id) return;
        $this->mcq($id,'Universal set','⭕','The set that contains all elements under consideration is the ___ set.',['empty','union','universal','intersection'],2);
        $this->mcq($id,'Complement','⭕','The complement of set A contains elements ___ in A.',['only','also','not','always'],2);
        $this->mcq($id,'Finite set','⭕','A set with a countable number of elements is a ___ set.',['infinite','empty','finite','universal'],2);
        $this->tf($id,'Infinite set','The set of all counting numbers is an infinite set.',true);
        $this->mcq($id,'Venn problem','⭕','In a class of 30: 20 like Maths, 15 like Science, 10 like both. How many like Maths only?',['5','10','15','20'],1);
        $this->command->info('   Sets C5: 5');
    }

    private function setsC6(): void
    {
        $id = $this->lid(43); if (!$id) return;
        $this->mcq($id,'Set builder notation','⭕','A = {x : x is an even number less than 10} = ___.',['{}','{2,4,6,8}','{1,3,5,7,9}','{2,4,6,8,10}'],1);
        $this->mcq($id,'Equivalent sets','⭕','Sets with the same number of elements are ___ sets.',['equal','empty','equivalent','universal'],2);
        $this->mcq($id,'Venn 3 sets','⭕','30 students: 15 play football, 12 play basketball, 5 play both. How many play football only?',['5','10','12','15'],1);
        $this->tf($id,'Subset symbol','A ⊂ B means A is a proper subset of B.',true);
        $this->command->info('   Sets C6: 4');
    }

    // ═══ MONEY ════════════════════════════════════════════════════════════

    private function moneyC1(): void
    {
        $id = $this->lid(13); if (!$id) return;
        $this->mcq($id,'Money C1 — change','💰','You have 50F and spend 20F. Change = ___.',['10F','20F','30F','40F'],2);
        $this->mcq($id,'Money C1 — total','💰','A pencil costs 25F and a book costs 50F. Total = ___.',['55F','65F','75F','85F'],2);
        $this->tf($id,'Money fact','We use money to buy things.',true);
        $this->command->info('   Money C1: 3');
    }

    private function moneyC2(): void
    {
        $id = $this->lid(19); if (!$id) return;
        $this->mcq($id,'Money C2 — change','💰','You have 200F and spend 130F. Change = ___.',['50F','60F','70F','80F'],2);
        $this->mcq($id,'Money C2 — total','💰','3 oranges at 25F each. Total = ___.',['55F','65F','75F','85F'],2);
        $this->mcq($id,'Money C2 — compare','💰','Which is more — 150F or 200F?',['150F','200F','Equal','Cannot tell'],1);
        $this->command->info('   Money C2: 3');
    }

    private function moneyC3(): void
    {
        $id = $this->lid(25); if (!$id) return;
        $this->mcq($id,'Shopping bill','💰','Bread 200F, milk 350F, sugar 450F. Total bill = ___.',['800F','900F','1000F','1100F'],2);
        $this->mcq($id,'Change from 1000F','💰','Bill = 750F. Change from 1000F = ___.',['150F','200F','250F','300F'],2);
        $this->mcq($id,'Price per unit','💰','6 eggs cost 600F. Price per egg = ___.',['50F','75F','100F','150F'],2);
        $this->mcq($id,'Total cost','💰','4 notebooks at 250F each. Total = ___.',['750F','900F','1000F','1250F'],2);
        $this->tf($id,'Budget fact','A budget helps you plan how to spend your money.',true);
        $this->command->info('   Money C3: 5');
    }

    private function moneyC4(): void
    {
        $id = $this->lid(31); if (!$id) return;
        $this->mcq($id,'Profit','💰','Cost price 2000F, selling price 2500F. Profit = ___.',['300F','400F','500F','600F'],2);
        $this->mcq($id,'Loss','💰','Cost price 3000F, selling price 2600F. Loss = ___.',['200F','300F','400F','500F'],2);
        $this->mcq($id,'Profit percentage','💰','Cost 1000F, profit 200F. Profit % = ___.',['10%','15%','20%','25%'],2);
        $this->mcq($id,'Shopping discount','💰','Shirt costs 5000F with 10% discount. Sale price = ___.',['4000F','4200F','4500F','4800F'],2);
        $this->mcq($id,'VAT calculation','💰','Item costs 2000F + 19.25% tax. Total = ___.',['2192.50F','2250F','2385F','2500F'],2);
        $this->tf($id,'Profit formula','Profit = Selling Price − Cost Price.',true);
        $this->command->info('   Money C4: 6');
    }

    // ═══ FRACTIONS ════════════════════════════════════════════════════════

    private function fractionsC2(): void
    {
        $id = $this->lid(19); if (!$id) return;
        $this->mcq($id,'Half','½','What is half of 8?',['2','3','4','5'],2);
        $this->mcq($id,'Quarter','¼','What is a quarter of 12?',['2','3','4','5'],1);
        $this->mcq($id,'Fraction name','½','In ¾, the number 3 is the ___.',['denominator','divisor','numerator','integer'],2);
        $this->tf($id,'Fraction fact','½ means 1 out of 2 equal parts.',true);
        $this->command->info('   Fractions C2: 4');
    }

    private function fractionsC3(): void
    {
        $id = $this->lid(25); if (!$id) return;
        $this->mcq($id,'Equivalent fractions','½','Which fraction is equal to ½?',['1/4','2/4','3/4','4/4'],1);
        $this->mcq($id,'Compare fractions','½','Which is bigger — ¾ or ½?',['½','¾','Equal','Cannot tell'],1);
        $this->mcq($id,'Add fractions same denominator','➕','¼ + ¼ = ___.',['1/8','1/4','2/4','2/8'],2);
        $this->mcq($id,'Simplify fraction','½','Simplify 4/8.',['1/4','1/2','3/4','2/4'],1);
        $this->mcq($id,'Fraction of a number','½','¾ of 24 = ___.',['12','16','18','20'],2);
        $this->tf($id,'Equivalent fractions','2/4 = 1/2.',true);
        $this->command->info('   Fractions C3: 6');
    }

    private function fractionsC4(): void
    {
        $id = $this->lid(31); if (!$id) return;
        $this->mcq($id,'Improper fraction','½','Which is an improper fraction?',['¾','½','5/3','2/5'],2);
        $this->mcq($id,'Mixed number','½','Convert 7/4 to a mixed number.',['1¼','1½','1¾','2¼'],2);
        $this->mcq($id,'Add unlike fractions','➕','½ + ¼ = ___.',['2/6','3/4','2/4','1/8'],1);
        $this->mcq($id,'Subtract fractions','➖','¾ − ¼ = ___.',['1/4','2/4','3/8','4/8'],1);
        $this->mcq($id,'Multiply fractions','✖️','½ × ¾ = ___.',['3/8','3/4','6/8','2/4'],0);
        $this->mcq($id,'HCF for fractions','½','HCF of 12 and 18 = ___.',['3','4','6','9'],2);
        $this->tf($id,'Improper fraction','In an improper fraction, the numerator is greater than the denominator.',true);
        $this->command->info('   Fractions C4: 7');
    }

    private function fractionsC5(): void
    {
        $id = $this->lid(37); if (!$id) return;
        $this->mcq($id,'Divide fractions','➗','½ ÷ ¼ = ___.',['1/8','2/1','1/2','2/4'],1);
        $this->mcq($id,'Fraction to decimal','½','½ as a decimal = ___.',['0.25','0.5','0.75','1.5'],1);
        $this->mcq($id,'Decimal to fraction','💯','0.75 as a fraction = ___.',['½','¼','¾','1/5'],2);
        $this->mcq($id,'Percentage','%','½ as a percentage = ___.',['25%','50%','75%','100%'],1);
        $this->mcq($id,'Fraction word problem','½','A journey is 120km. You have done ¾. Distance left = ___.',['20km','30km','40km','50km'],1);
        $this->tf($id,'Fraction decimal','0.5 = ½.',true);
        $this->command->info('   Fractions C5: 6');
    }

    private function fractionsC6(): void
    {
        $id = $this->lid(43); if (!$id) return;
        $this->mcq($id,'Complex fraction','½','(½ + ¾) − ¼ = ___.',['3/4','1','5/4','3/8'],1);
        $this->mcq($id,'Fraction proportion','½','If 3/5 of a class are girls and there are 30 students, how many are girls?',['12','15','18','20'],2);
        $this->mcq($id,'Direct proportion','📊','If 4 pens cost 200F, 10 pens cost ___.',['400F','450F','500F','550F'],2);
        $this->mcq($id,'Inverse proportion','📊','5 workers finish in 12 days. 10 workers finish in ___ days.',['3','6','8','24'],1);
        $this->mcq($id,'Compound proportion','📊','2 workers build 4 walls in 6 days. 3 workers build 6 walls in ___ days.',['4','6','8','9'],1);
        $this->tf($id,'Direct proportion','In direct proportion, when one quantity increases, the other increases too.',true);
        $this->tf($id,'Inverse proportion','In inverse proportion, when one quantity doubles, the other halves.',true);
        $this->command->info('   Fractions C6: 7');
    }
}
