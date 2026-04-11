<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasurementSeeder extends Seeder
{
    public function run(): void
    {
        $this->measureC1();
        $this->measureC2();
        $this->measureC3();
        $this->measureC4();
        $this->measureC5();
        $this->measureC6();
        $this->command->info('✅ Measurement exercises seeded C1-C6');
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
            'content'=>json_encode(['type'=>'true_false','illustration'=>'📏','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    // ── C1 (subject 13) — natural and basic units ─────────────────────────
    private function measureC1(): void
    {
        $id = $this->lid(13); if (!$id) return;

        $this->mcq($id,'Longer object','📏','Which is longer — 1 metre or 1 centimetre?',['1 centimetre','1 metre','They are equal','Cannot tell'],1);
        $this->mcq($id,'Heavier object','⚖️','Which is heavier — 1 kg or 1 g?',['1 gram','1 kilogram','They are equal','Cannot tell'],1);
        $this->mcq($id,'More liquid','💧','Which holds more — 1 litre or 1 millilitre?',['1 millilitre','1 litre','They are equal','Cannot tell'],1);
        $this->mcq($id,'Length tool','📐','Which tool do we use to measure length?',['Scale','Ruler','Cup','Clock'],1);
        $this->mcq($id,'Mass tool','⚖️','Which tool do we use to measure mass?',['Ruler','Thermometer','Scale/balance','Measuring cup'],2);
        $this->tf($id,'Length fact','A metre is longer than a centimetre.',true);
        $this->tf($id,'Mass fact','A kilogram is heavier than a gram.',true);

        $this->command->info('   Measurement C1: 7 exercises');
    }

    // ── C2 (subject 19) — cm/m, g/kg, ml/l ──────────────────────────────
    private function measureC2(): void
    {
        $id = $this->lid(19); if (!$id) return;

        // Length
        $this->mcq($id,'cm in 1 m','📏','How many centimetres are in 1 metre?',['10','50','100','1000'],2);
        $this->mcq($id,'Convert m to cm','📏','2 metres = ___ centimetres.',['20','100','200','2000'],2);
        $this->mcq($id,'Convert cm to m','📏','300 cm = ___ metres.',['3','30','300','3000'],0);

        // Mass
        $this->mcq($id,'g in 1 kg','⚖️','How many grams are in 1 kilogram?',['10','100','500','1000'],3);
        $this->mcq($id,'Convert kg to g','⚖️','2 kg = ___ grams.',['20','200','2000','20000'],2);
        $this->mcq($id,'Convert g to kg','⚖️','5000 g = ___ kg.',['5','50','500','5000'],0);

        // Capacity
        $this->mcq($id,'ml in 1 l','💧','How many millilitres are in 1 litre?',['10','100','500','1000'],3);
        $this->mcq($id,'Convert l to ml','💧','3 litres = ___ ml.',['30','300','3000','30000'],2);

        $this->tf($id,'Metre fact','100 cm = 1 m.',true);
        $this->tf($id,'Gram fact','1000 g = 1 kg.',true);

        $this->command->info('   Measurement C2: 10 exercises');
    }

    // ── C3 (subject 25) — mm/cm/dm/m/km, cl/dl, full metric ─────────────
    private function measureC3(): void
    {
        $id = $this->lid(25); if (!$id) return;

        // Length — full chain
        $this->mcq($id,'mm in 1 cm','📏','How many millimetres are in 1 cm?',['5','10','100','1000'],1);
        $this->mcq($id,'cm in 1 dm','📏','How many centimetres are in 1 decimetre (dm)?',['5','10','100','1000'],1);
        $this->mcq($id,'m in 1 km','📏','How many metres are in 1 kilometre?',['10','100','500','1000'],3);
        $this->mcq($id,'Convert km to m','🏃','3 km = ___ metres.',['30','300','3000','30000'],2);
        $this->mcq($id,'Convert m to cm','📏','5 m = ___ cm.',['5','50','500','5000'],2);
        $this->mcq($id,'Convert cm to mm','📏','4 cm = ___ mm.',['4','40','400','4000'],1);
        $this->mcq($id,'Mixed length','📏','Which is the correct order smallest to largest?',
            ['km, m, dm, cm, mm','mm, cm, dm, m, km','cm, mm, m, dm, km','m, km, cm, mm, dm'],1);

        // Capacity — cl/dl
        $this->mcq($id,'cl in 1 dl','💧','How many centilitres (cl) in 1 dl?',['5','10','100','1000'],1);
        $this->mcq($id,'dl in 1 l','💧','How many decilitres (dl) in 1 litre?',['5','10','100','1000'],1);
        $this->mcq($id,'Convert l to dl','💧','2 litres = ___ dl.',['2','20','200','2000'],1);

        // Mass
        $this->mcq($id,'Convert g to mg','⚖️','1 g = ___ milligrams (mg).',['10','100','1000','10000'],2);
        $this->tf($id,'Metric chain','The metric system is based on powers of 10.',true);
        $this->tf($id,'Kilometre','1 km = 1000 m.',true);

        $this->command->info('   Measurement C3: 13 exercises');
    }

    // ── C4 (subject 31) — conversions + perimeter + area ─────────────────
    private function measureC4(): void
    {
        $id = $this->lid(31); if (!$id) return;

        // Conversions
        $this->mcq($id,'Convert 2.5 km to m','📏','2.5 km = ___ metres.',['25','250','2500','25000'],2);
        $this->mcq($id,'Convert 3500 m to km','📏','3500 m = ___ km.',['3.5','35','350','3500'],0);
        $this->mcq($id,'Convert 1.5 kg to g','⚖️','1.5 kg = ___ grams.',['15','150','1500','15000'],2);
        $this->mcq($id,'Convert 250 cl to l','💧','250 cl = ___ litres.',['0.25','2.5','25','250'],1);
        $this->mcq($id,'Convert 0.75 l to ml','💧','0.75 l = ___ ml.',['7.5','75','750','7500'],2);

        // Perimeter
        $this->mcq($id,'Perimeter of square','📐','Perimeter of a square with side 6 cm?',['12 cm','18 cm','24 cm','36 cm'],2);
        $this->mcq($id,'Perimeter of rectangle','📐','Perimeter of rectangle 8cm × 5cm?',['13 cm','26 cm','40 cm','80 cm'],1);
        $this->mcq($id,'Perimeter of triangle','📐','Perimeter of triangle with sides 3cm, 4cm, 5cm?',['10 cm','11 cm','12 cm','13 cm'],2);

        // Area
        $this->mcq($id,'Area of square','📐','Area of a square with side 5 cm?',['10 cm²','20 cm²','25 cm²','30 cm²'],2);
        $this->mcq($id,'Area of rectangle','📐','Area of rectangle 7cm × 4cm?',['11 cm²','22 cm²','28 cm²','32 cm²'],2);
        $this->tf($id,'Perimeter vs Area','Perimeter is the distance around a shape.',true);
        $this->tf($id,'Area formula','Area of rectangle = length × width.',true);
        $this->mcq($id,'Units of area','📐','Area is measured in ___.',['cm','cm²','cm³','m'],1);

        $this->command->info('   Measurement C4: 13 exercises');
    }

    // ── C5 (subject 37) — areas + compound + money ───────────────────────
    private function measureC5(): void
    {
        $id = $this->lid(37); if (!$id) return;

        // Area — more shapes
        $this->mcq($id,'Area of triangle','📐','Area of triangle = ½ × base × ___.',['width','perimeter','height','length'],2);
        $this->mcq($id,'Area triangle calc','📐','Area of triangle with base 8cm and height 5cm?',['13 cm²','20 cm²','40 cm²','80 cm²'],1);
        $this->mcq($id,'Larger unit m²','📐','1 m² = ___ cm².',['100','1000','10000','100000'],2);

        // Compound measures
        $this->mcq($id,'Compound convert','📏','A field is 1.2 km long. How many metres is that?',['12','120','1200','12000'],2);
        $this->mcq($id,'Mixed mass','⚖️','A bag weighs 2 kg 500 g. Total in grams?',['250','2500','25000','250000'],1);
        $this->mcq($id,'Mixed capacity','💧','A bottle holds 1 l 250 ml. Total in ml?',['125','1250','12500','125000'],1);

        // Money (C5 — foreign currency + profit/loss)
        $this->mcq($id,'Profit calculation','💰','Bought at 2500F, sold at 3000F. Profit?',['250F','500F','750F','1000F'],1);
        $this->mcq($id,'Loss calculation','💰','Bought at 4000F, sold at 3500F. Loss?',['250F','500F','750F','1000F'],1);
        $this->mcq($id,'Discount','💰','Original price 5000F, discount 20%. Sale price?',['3000F','4000F','4500F','5000F'],1);
        $this->tf($id,'Profit formula','Profit = Selling price − Cost price.',true);
        $this->tf($id,'Loss formula','Loss happens when selling price is less than cost price.',true);

        $this->command->info('   Measurement C5: 11 exercises');
    }

    // ── C6 (subject 43) — full metric + area/perimeter advanced ─────────
    private function measureC6(): void
    {
        $id = $this->lid(43); if (!$id) return;

        // Advanced conversions
        $this->mcq($id,'Convert 3.25 km','📏','3.25 km = ___ m.',['325','3025','3250','32500'],2);
        $this->mcq($id,'Convert 4750 m','📏','4750 m = ___ km.',['4.075','4.75','47.5','475'],1);
        $this->mcq($id,'Convert 2.4 kg','⚖️','2.4 kg = ___ g.',['24','240','2400','24000'],2);
        $this->mcq($id,'Convert 3.5 l','💧','3.5 l = ___ ml.',['35','350','3500','35000'],2);

        // Perimeter and area advanced
        $this->mcq($id,'Area of parallelogram','📐','Area of parallelogram = base × ___.',['side','height','perimeter','width'],1);
        $this->mcq($id,'Perimeter of circle','📐','The perimeter of a circle is called the ___.',['area','diameter','circumference','radius'],2);
        $this->mcq($id,'Area of circle','📐','Area of circle = π × r². If r = 7cm, area ≈ ___ (π≈3.14)',['21.98 cm²','43.96 cm²','153.86 cm²','307.72 cm²'],2);

        // Money C6
        $this->mcq($id,'Simple interest','💰','Simple interest = Principal × Rate × ___.',['100','Time','Principal','Rate'],1);
        $this->mcq($id,'Interest calculation','💰','Interest on 10000F at 5% for 2 years?',['500F','750F','1000F','2000F'],2);
        $this->mcq($id,'Exchange rate','💰','1 USD = 600F. How much is 5 USD in FCFA?',['1200F','2400F','3000F','6000F'],2);
        $this->tf($id,'Simple interest formula','Simple Interest = P × R × T ÷ 100.',true);
        $this->tf($id,'Area unit','Area is always measured in square units.',true);

        $this->command->info('   Measurement C6: 12 exercises');
    }
}
