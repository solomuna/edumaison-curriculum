<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarlaC5ExplanationsSeeder extends Seeder
{
    public function run(): void
    {
        $updated = 0;

        $ex = DB::table('exercises')->where('id',1475)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Benevolent = kind and generous. Ben = good (Latin). A benevolent person is good-hearted.';
                DB::table('exercises')->where('id',1475)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1218)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Un- = not. Unhappy = not happy. Other examples: unkind, unsafe, unclear.';
                DB::table('exercises')->where('id',1218)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1219)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '-ful = full of. Careful = full of care. Other: hopeful, helpful, beautiful.';
                DB::table('exercises')->where('id',1219)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1220)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Ancient = very old. The opposite is modern or new.';
                DB::table('exercises')->where('id',1220)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1473)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '\'Although it rained\' cannot stand alone as a sentence. It depends on the main clause = subordinate clause.';
                DB::table('exercises')->where('id',1473)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1213)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Present perfect uses \'has/have + past participle\'. She HAS already finished.';
                DB::table('exercises')->where('id',1213)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1214)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Passive voice: subject receives the action. \'The book WAS written\' = passive.';
                DB::table('exercises')->where('id',1214)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1215)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Use \'who\' for people. \'The girl WHO won\' — who refers to a person.';
                DB::table('exercises')->where('id',1215)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1216)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Zero conditional: if + present, will + verb. If it rains, we WILL stay inside.';
                DB::table('exercises')->where('id',1216)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1217)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Reported speech: change present to past. \'I am\' becomes \'he was\'.';
                DB::table('exercises')->where('id',1217)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1470)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Must = obligation by law. You MUST wear a seatbelt — it is required.';
                DB::table('exercises')->where('id',1470)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1471)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Passive: was/were + past participle. The cake WAS BAKED — baked by Mum.';
                DB::table('exercises')->where('id',1471)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1472)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Reported speech: \'I am\' becomes \'he was\'. He said that he WAS hungry.';
                DB::table('exercises')->where('id',1472)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1260)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A mapping uses arrows to show how elements of one set relate to another.';
                DB::table('exercises')->where('id',1260)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1021)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 m = 100 cm. So 1 m² = 100 × 100 = 10,000 cm².';
                DB::table('exercises')->where('id',1021)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',928)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 minute = 60 seconds. Fundamental time conversion.';
                DB::table('exercises')->where('id',928)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1061)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The complement of A contains elements NOT in A but in the universal set.';
                DB::table('exercises')->where('id',1061)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',929)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Leap year = 366 days. Normal year = 365. Leap year every 4 years (e.g. 2024).';
                DB::table('exercises')->where('id',929)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1836)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '49 ≈ 50, 21 ≈ 20. Estimate: 50 × 20 = 1000.';
                DB::table('exercises')->where('id',1836)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',959)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '5 hours × 60 minutes = 300 minutes.';
                DB::table('exercises')->where('id',959)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',960)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '5 days × 24 hours = 120 hours.';
                DB::table('exercises')->where('id',960)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',101)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1250 - 480 = 770 mangoes remain.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = '45 × 38 = 1710 pupils total.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = '3500 × 6 = 21,000 FCFA.';
                if (isset($c['questions'][3])) $c['questions'][3]['explanation'] = '2400 ÷ 4 = 600 FCFA each.';
                if (isset($c['questions'][4])) $c['questions'][4]['explanation'] = '500 - 175 + 65 = 390 litres.';
                if (isset($c['questions'][5])) $c['questions'][5]['explanation'] = 'Average = (72 + 65 + 81) ÷ 3 = 218 ÷ 3 = 72.67 ≈ 73%.';
                DB::table('exercises')->where('id',101)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1827)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3467: units digit=7 ≥ 5, round up → 3470.';
                DB::table('exercises')->where('id',1827)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1276)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'BODMAS: 3²=9 first. Then 8÷4=2. Then 9×2=18. Then 4+18-1=21.';
                DB::table('exercises')->where('id',1276)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1297)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3.47 to 1dp: look at 2nd decimal (7 ≥ 5) → round up → 3.5.';
                DB::table('exercises')->where('id',1297)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1288)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Composite number = more than 2 factors. Examples: 4, 6, 8, 9, 10.';
                DB::table('exercises')->where('id',1288)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1287)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Primes between 20-30: 23 and 29. Check: neither divisible by 2,3,5,7.';
                DB::table('exercises')->where('id',1287)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1304)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Base 2 (binary) uses only 0 and 1. All numbers expressed as powers of 2.';
                DB::table('exercises')->where('id',1304)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1277)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '5²=25 first. 100÷25=4. Then 3×4=12. Then 4+12=16.';
                DB::table('exercises')->where('id',1277)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1286)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '12 = 2 × 6 = 2 × 2 × 3. Prime factors: 2² × 3.';
                DB::table('exercises')->where('id',1286)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1835)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '487 ≈ 500, 312 ≈ 300. Estimate: 500 + 300 = 800.';
                DB::table('exercises')->where('id',1835)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1834)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '8740: digit in hundreds=7 ≥ 5 → round up → 9000.';
                DB::table('exercises')->where('id',1834)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1832)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '12600: digit in hundreds=6 ≥ 5 → round up → 13000.';
                DB::table('exercises')->where('id',1832)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1831)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '7249: digit in hundreds=2 < 5 → round down → 7000.';
                DB::table('exercises')->where('id',1831)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1830)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3500: digit in hundreds=5 → round up → 4000.';
                DB::table('exercises')->where('id',1830)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1829)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '8349: digit in tens=4 < 5 → round down → 8300.';
                DB::table('exercises')->where('id',1829)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1828)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '4650: digit in tens=5 → round up → 4700.';
                DB::table('exercises')->where('id',1828)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1103)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Dividing fractions: flip the second and multiply. ½ ÷ ¼ = ½ × 4/1 = 4/2 = 2.';
                DB::table('exercises')->where('id',1103)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1104)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '½ = 1 ÷ 2 = 0.5. Always divide numerator by denominator.';
                DB::table('exercises')->where('id',1104)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1105)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '0.75 = 75/100 = 3/4. Simplify by dividing by 25.';
                DB::table('exercises')->where('id',1105)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1295)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '0.4 × 0.3: multiply 4×3=12. Two decimal places total → 0.12.';
                DB::table('exercises')->where('id',1295)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1296)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1.2 ÷ 0.4: multiply both by 10 → 12 ÷ 4 = 3.';
                DB::table('exercises')->where('id',1296)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1298)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '0.35 × 100 = 35%. Multiply decimal by 100 to get percentage.';
                DB::table('exercises')->where('id',1298)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1106)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '½ × 100 = 50%.';
                DB::table('exercises')->where('id',1106)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1107)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Done: ¾ × 120 = 90 km. Remaining: 120 - 90 = 30 km.';
                DB::table('exercises')->where('id',1107)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1019)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Area of triangle = ½ × base × HEIGHT. Always needs base and height.';
                DB::table('exercises')->where('id',1019)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1064)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Only Maths: 20-10=10. Only Science: 15-10=5. Both: 10. Total=10+5+10=25. Like neither: 30-25=5.';
                DB::table('exercises')->where('id',1064)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1020)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Area = ½ × 8 × 5 = ½ × 40 = 20 cm².';
                DB::table('exercises')->where('id',1020)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',966)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'GMT+1 is 1 hour ahead of GMT+0. London 10:00 → Yaounde 11:00.';
                DB::table('exercises')->where('id',966)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1023)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '2 kg = 2000 g. 2000 + 500 = 2500 g.';
                DB::table('exercises')->where('id',1023)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1022)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1.2 km × 1000 = 1200 metres.';
                DB::table('exercises')->where('id',1022)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1024)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 l = 1000 ml. 1000 + 250 = 1250 ml.';
                DB::table('exercises')->where('id',1024)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',962)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Speed = Distance ÷ Time = 120 ÷ 2 = 60 km/h.';
                DB::table('exercises')->where('id',962)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',961)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Speed = Distance ÷ TIME. Remember: D=S×T, S=D/T, T=D/S.';
                DB::table('exercises')->where('id',961)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',923)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '2:00 pm + 2 hours = 4:00 pm.';
                DB::table('exercises')->where('id',923)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1305)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '101₂ = 1×4 + 0×2 + 1×1 = 4+0+1 = 5 in base 10.';
                DB::table('exercises')->where('id',1305)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1306)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '5 = 4+1 = 1×2² + 0×2¹ + 1×2⁰ = 101₂.';
                DB::table('exercises')->where('id',1306)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',924)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'London is 1 hour behind. Paris 3pm → London 2pm.';
                DB::table('exercises')->where('id',924)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',925)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Distance = Speed × TIME. If S=60 and T=2, then D=120 km.';
                DB::table('exercises')->where('id',925)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',926)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Speed = Distance ÷ Time = 60 ÷ 2 = 30 km/h.';
                DB::table('exercises')->where('id',926)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',927)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon is in GMT+1 (West Africa Time, WAT).';
                DB::table('exercises')->where('id',927)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',963)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Distance = Speed × Time = 50 × 3 = 150 km.';
                DB::table('exercises')->where('id',963)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',964)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Time = Distance ÷ Speed = 600 ÷ 300 = 2 hours.';
                DB::table('exercises')->where('id',964)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',965)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon is GMT+1 (West Africa Time).';
                DB::table('exercises')->where('id',965)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1259)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Probability = favourable outcomes ÷ total outcomes = 1÷2 = ½.';
                DB::table('exercises')->where('id',1259)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1257)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Ordered: 3,5,7,9,11. Middle value (3rd of 5) = 7.';
                DB::table('exercises')->where('id',1257)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1025)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Profit = Selling price - Cost price = 3000 - 2500 = 500 FCFA.';
                DB::table('exercises')->where('id',1025)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1256)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Mean = sum ÷ count = (10+20+30+40+50) ÷ 5 = 150 ÷ 5 = 30.';
                DB::table('exercises')->where('id',1256)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1026)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Loss = Cost price - Selling price = 4000 - 3500 = 500 FCFA.';
                DB::table('exercises')->where('id',1026)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1027)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '20% of 5000 = 1000. Sale price = 5000 - 1000 = 4000 FCFA.';
                DB::table('exercises')->where('id',1027)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1060)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The universal set (U or E) contains ALL elements being considered.';
                DB::table('exercises')->where('id',1060)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1062)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Finite set = countable elements. Example: {1,2,3} has 3 elements.';
                DB::table('exercises')->where('id',1062)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1258)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Even count: take two middle values. {4,6,8,10}: middle = 6 and 8. Median = (6+8)/2 = 7.';
                DB::table('exercises')->where('id',1258)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',132)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Le mont Cameroun (4095 m) est le plus haut sommet du Cameroun.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = 'L\'ensemble des animaux d\'une region s\'appelle la faune.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = 'Le Sahel couvre le nord du Cameroun — region seche et semi-desertique.';
                DB::table('exercises')->where('id',132)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1776)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'De- signifie annuler ou faire l\'inverse. Defaire = annuler ce qui etait fait.';
                DB::table('exercises')->where('id',1776)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1777)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '-eur designe une personne qui fait une action. Chanteur = celui qui chante.';
                DB::table('exercises')->where('id',1777)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1778)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '\'Ou\' = choix (tu viens ou tu pars). \'Ou\' avec accent = lieu ou temps (ou habites-tu?).';
                DB::table('exercises')->where('id',1778)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1779)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Lettre formelle: Monsieur, Madame ou Cher/Chere selon le destinataire.';
                DB::table('exercises')->where('id',1779)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1780)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Formule de politesse: \'Veuillez agreer, Monsieur, l\'expression de mes salutations distinguees.\'';
                DB::table('exercises')->where('id',1780)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1782)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Discours indirect: \'Je suis fatigue\' devient \'il a dit qu\'il etait fatigue\'.';
                DB::table('exercises')->where('id',1782)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1770)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '\'Que\' s\'utilise quand le pronom est COD (objet direct). Le livre QUE j\'ai lu.';
                DB::table('exercises')->where('id',1770)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1771)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '\'Qui\' s\'utilise quand le pronom est sujet. L\'eleve QUI a repondu.';
                DB::table('exercises')->where('id',1771)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1773)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Imparfait pour habitude dans le passe. Je JOUAIS souvent = j\'avais l\'habitude.';
                DB::table('exercises')->where('id',1773)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1774)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Imparfait pour action en cours interrompue. Elle LISAIT quand il est entre.';
                DB::table('exercises')->where('id',1774)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',135)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'L\'avocat defend les accuses devant le tribunal.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = 'L\'architecte congoit les batiments et les structures.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = 'Le feminin de instituteur = institutrice.';
                if (isset($c['questions'][3])) $c['questions'][3]['explanation'] = 'Le masculin de infirmiere = infirmier.';
                DB::table('exercises')->where('id',135)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',138)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Le paludisme (malaria) est transmis par la piqure de moustique.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = 'Se laver les mains elimine les germes et previent les maladies.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = 'On va a l\'hopital ou au centre de sante quand on est tres malade.';
                DB::table('exercises')->where('id',138)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',455)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The lungs are the organs used for breathing. They take in oxygen and release CO₂.';
                DB::table('exercises')->where('id',455)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',453)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The heart pumps BLOOD around the body through arteries and veins.';
                DB::table('exercises')->where('id',453)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',452)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Digestion begins in the MOUTH where teeth and saliva break down food.';
                DB::table('exercises')->where('id',452)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',458)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Diabetes is non-contagious — it cannot spread from person to person.';
                DB::table('exercises')->where('id',458)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',460)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Vaccines train the immune system to recognise and FIGHT specific diseases.';
                DB::table('exercises')->where('id',460)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',467)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Energy from the sun = SOLAR energy. It can power solar panels.';
                DB::table('exercises')->where('id',467)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',468)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Solar energy is renewable — the sun never runs out!';
                DB::table('exercises')->where('id',468)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',470)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Metals like copper conduct electricity. Used in wires and circuits.';
                DB::table('exercises')->where('id',470)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',471)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Rubber and plastic are insulators — they block electricity flow. Used on wire coatings.';
                DB::table('exercises')->where('id',471)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',465)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Pushing = PUSH force. Pulling = pull force. Both are types of contact force.';
                DB::table('exercises')->where('id',465)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',464)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Friction SLOWS DOWN motion. It acts opposite to the direction of movement.';
                DB::table('exercises')->where('id',464)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',456)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Puberty = transition from child to ADULT. Physical and emotional changes occur.';
                DB::table('exercises')->where('id',456)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',461)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Evaporation = liquid water turns to water VAPOUR when heated by the sun.';
                DB::table('exercises')->where('id',461)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',462)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'RAIN (precipitation) falls from clouds as part of the water cycle.';
                DB::table('exercises')->where('id',462)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',770)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Every email address contains @ (at sign). Format: name@domain.com';
                DB::table('exercises')->where('id',770)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',773)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'CC = Carbon Copy. The person in CC receives a copy of the email.';
                DB::table('exercises')->where('id',773)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',771)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The subject line briefly describes what the email is about.';
                DB::table('exercises')->where('id',771)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',755)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A USB flash drive stores and transfers files between computers.';
                DB::table('exercises')->where('id',755)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',756)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Hard disk drive (HDD) holds the most data — typically 500GB to several TB.';
                DB::table('exercises')->where('id',756)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',758)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'USB port connects external devices: mouse, keyboard, flash drive, phone.';
                DB::table('exercises')->where('id',758)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',776)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Too much screen time can cause eye strain, headaches and sleep problems.';
                DB::table('exercises')->where('id',776)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',774)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Copyright: you need the owner\'s PERMISSION to use their creative work.';
                DB::table('exercises')->where('id',774)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',763)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In PowerPoint, each page is called a SLIDE.';
                DB::table('exercises')->where('id',763)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',764)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Insert > Picture (or Images) to add a picture to a slide.';
                DB::table('exercises')->where('id',764)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',777)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In Scratch, the character you programme is called a SPRITE.';
                DB::table('exercises')->where('id',777)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',778)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Motion blocks (blue) control movement in Scratch. Use \'move steps\'.';
                DB::table('exercises')->where('id',778)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',767)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '=SUM(range) adds numbers. Example: =SUM(A1:A10).';
                DB::table('exercises')->where('id',767)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',766)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cell = intersection of a row and column. Example: B3 = column B, row 3.';
                DB::table('exercises')->where('id',766)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',769)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Sort A to Z = ascending alphabetical order. Use Data > Sort.';
                DB::table('exercises')->where('id',769)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',760)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Landscape = page is WIDER than it is tall. Good for tables and charts.';
                DB::table('exercises')->where('id',760)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',759)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Insert > Table to add a table in Word or Google Docs.';
                DB::table('exercises')->where('id',759)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',762)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Ctrl+Z = Undo. Always works in Word, Excel, PowerPoint!';
                DB::table('exercises')->where('id',762)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1204)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon has 10 regions. Each has a capital city and a governor.';
                DB::table('exercises')->where('id',1204)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1205)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Douala is the largest and most populated city in Cameroon (economic capital).';
                DB::table('exercises')->where('id',1205)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1210)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The best conflict resolution is dialogue — talking peacefully to find solutions.';
                DB::table('exercises')->where('id',1210)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1212)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The UN was created to promote international PEACE and cooperation.';
                DB::table('exercises')->where('id',1212)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1207)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Child trafficking = illegal. It violates children\'s rights and is a serious crime.';
                DB::table('exercises')->where('id',1207)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1208)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'UNICEF protects the rights and welfare of CHILDREN worldwide.';
                DB::table('exercises')->where('id',1208)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1201)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Ancient Egypt is famous for its PYRAMIDS — tombs built for pharaohs.';
                DB::table('exercises')->where('id',1201)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1202)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Early man used STONE to make tools (Stone Age). Later came bronze and iron.';
                DB::table('exercises')->where('id',1202)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1354)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The Mali Empire was rich in GOLD. Mansa Musa was famous for his gold.';
                DB::table('exercises')->where('id',1354)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1355)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Great Zimbabwe is in ZIMBABWE — a stone city built between 11th-15th centuries.';
                DB::table('exercises')->where('id',1355)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1357)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The Trans-Atlantic Slave Trade sent Africans to the AMERICAS (North and South America).';
                DB::table('exercises')->where('id',1357)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1358)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'COCOA is the main cash crop of Cameroon. Cameroon is a top cocoa producer.';
                DB::table('exercises')->where('id',1358)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1359)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon\'s oil is produced mainly offshore in the LITTORAL/SOUTH WEST region.';
                DB::table('exercises')->where('id',1359)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1361)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The UN was founded in 1945 after World War II to maintain world peace.';
                DB::table('exercises')->where('id',1361)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1362)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'UNICEF = United Nations Children\'s Fund. Protects children\'s rights globally.';
                DB::table('exercises')->where('id',1362)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1363)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The African Union (AU) headquarters is in ADDIS ABABA, Ethiopia.';
                DB::table('exercises')->where('id',1363)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1853)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Slave trade consequence: Africa lost millions of people, causing economic and social decline.';
                DB::table('exercises')->where('id',1853)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1854)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Germany colonised Cameroon in 1884 after the Berlin Conference.';
                DB::table('exercises')->where('id',1854)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1848)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The Nile River was essential to Ancient Egypt for farming and transport.';
                DB::table('exercises')->where('id',1848)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1855)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Rudolf Manga Bell was hanged by Germans in 1914 for resisting land expropriation.';
                DB::table('exercises')->where('id',1855)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1849)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Ancient Egypt\'s rulers were called PHARAOHS. They were worshipped as gods.';
                DB::table('exercises')->where('id',1849)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1850)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Athens (Greece) is the birthplace of democracy — citizens voted on laws.';
                DB::table('exercises')->where('id',1850)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1851)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The Roman Empire covered parts of Europe, North Africa and Western Asia.';
                DB::table('exercises')->where('id',1851)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1852)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The Atlantic slave trade forcibly transported Africans to work on plantations in the Americas.';
                DB::table('exercises')->where('id',1852)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2006)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In Ancient Athens, only MALE citizens could vote. Women and slaves were excluded.';
                DB::table('exercises')->where('id',2006)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2005)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Germany lost Cameroon after World War I (1914-1918). France and Britain divided it.';
                DB::table('exercises')->where('id',2005)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1863)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon is in Central/West Africa. It borders Nigeria, Chad, CAR, Congo, Gabon, and Equatorial Guinea.';
                DB::table('exercises')->where('id',1863)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1864)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Yaounde is the political capital of Cameroon. Douala is the economic capital.';
                DB::table('exercises')->where('id',1864)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1865)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon has 4 main climate zones: equatorial, tropical, sudanian and sahelian.';
                DB::table('exercises')->where('id',1865)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1866)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Mount Cameroon (4095 m) is the highest peak — an active volcano near Buea.';
                DB::table('exercises')->where('id',1866)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1867)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'COCOA is Cameroon\'s most important export crop. Coffee is second.';
                DB::table('exercises')->where('id',1867)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1868)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The FAR NORTH region has a dry Sahelian climate — very hot and dry.';
                DB::table('exercises')->where('id',1868)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1876)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Nigeria shares the longest border with Cameroon along the western frontier.';
                DB::table('exercises')->where('id',1876)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1877)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon produces OIL (petroleum) offshore in the Gulf of Guinea.';
                DB::table('exercises')->where('id',1877)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2015)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The Adamawa Plateau is in CENTRAL-NORTHERN Cameroon, around Ngaoundere.';
                DB::table('exercises')->where('id',2015)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2016)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'COFFEE is another major export crop of Cameroon alongside cocoa.';
                DB::table('exercises')->where('id',2016)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1883)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Rule of law: EVERYONE including leaders must obey the same laws.';
                DB::table('exercises')->where('id',1883)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1882)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Every child has the right to EDUCATION, health, name, protection and play.';
                DB::table('exercises')->where('id',1882)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1881)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon\'s parliament = NATIONAL ASSEMBLY (lower house) + Senate (upper house).';
                DB::table('exercises')->where('id',1881)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1880)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The President is the HEAD OF STATE and head of government in Cameroon.';
                DB::table('exercises')->where('id',1880)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1879)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon\'s national anthem = \'O Cameroon, Cradle of Our Forefathers\'.';
                DB::table('exercises')->where('id',1879)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2025)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The most important civic duty: VOTING and obeying the law — both are essential.';
                DB::table('exercises')->where('id',2025)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2026)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The gold star on Cameroon\'s flag represents UNITY of the nation.';
                DB::table('exercises')->where('id',2026)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1878)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon\'s flag: GREEN (forests), RED (unity), YELLOW (sun) with gold star.';
                DB::table('exercises')->where('id',1878)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1891)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A civic duty = obeying laws, paying taxes, voting, protecting the environment.';
                DB::table('exercises')->where('id',1891)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1892)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Best peaceful resolution = DIALOGUE — talking and listening to reach agreement.';
                DB::table('exercises')->where('id',1892)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1418)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Pan-Africanism promotes the UNITY and solidarity of African peoples worldwide.';
                DB::table('exercises')->where('id',1418)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1419)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The African Union promotes UNITY, peace and development among African nations.';
                DB::table('exercises')->where('id',1419)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1421)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Kwame Nkrumah was from GHANA. First president of Ghana, key Pan-African leader.';
                DB::table('exercises')->where('id',1421)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1425)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'FRANCE and BRITAIN were the main colonial powers in Cameroon.';
                DB::table('exercises')->where('id',1425)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1426)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The two main religions in Cameroon are Christianity and ISLAM.';
                DB::table('exercises')->where('id',1426)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1422)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Bilingualism = using both FRENCH and ENGLISH in official life in Cameroon.';
                DB::table('exercises')->where('id',1422)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1423)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The two Anglophone regions: NORTH WEST and SOUTH WEST.';
                DB::table('exercises')->where('id',1423)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1517)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Shading creates the illusion of DEPTH and 3D form in a flat drawing.';
                DB::table('exercises')->where('id',1517)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1518)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Texture = the feel or visual quality of a surface (rough, smooth, bumpy).';
                DB::table('exercises')->where('id',1518)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1520)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A portrait is a drawing/painting of a PERSON, especially their face.';
                DB::table('exercises')->where('id',1520)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2082)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The most important element in photography is LIGHT. No light = no photo.';
                DB::table('exercises')->where('id',2082)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2083)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'An architect designs BUILDINGS — homes, schools, bridges, offices.';
                DB::table('exercises')->where('id',2083)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1521)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cardiovascular fitness = health of HEART and lungs. Improved by running/cycling.';
                DB::table('exercises')->where('id',1521)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1522)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'BMI (Body Mass Index) measures body weight relative to height.';
                DB::table('exercises')->where('id',1522)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1524)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Performance-enhancing drugs = ILLEGAL (doping). WADA bans them in sport.';
                DB::table('exercises')->where('id',1524)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2118)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Run at an even steady pace. Starting too fast leads to fatigue later.';
                DB::table('exercises')->where('id',2118)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2119)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Ballet originated in ITALY in the 15th century, then developed in France.';
                DB::table('exercises')->where('id',2119)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1533)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'CARBOHYDRATES give energy. Rice, yam, bread, cassava are carbohydrates.';
                DB::table('exercises')->where('id',1533)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1534)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Always TURN OFF heat/gas when not attending to cooking. Safety first!';
                DB::table('exercises')->where('id',1534)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1535)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Always WASH your hands before cooking to prevent food contamination.';
                DB::table('exercises')->where('id',1535)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1537)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A budget helps you plan how to SPEND and SAVE your money wisely.';
                DB::table('exercises')->where('id',1537)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1678)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Tenacious = never giving up despite difficulties. Context: \'never gave up\'.';
                DB::table('exercises')->where('id',1678)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1680)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Trans = across. Transport = carry across. Translate, transfer, transit.';
                DB::table('exercises')->where('id',1680)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1679)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '\'The sun was a golden coin\' — comparing sun to a coin without \'like/as\' = METAPHOR.';
                DB::table('exercises')->where('id',1679)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1681)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '\'Although it was raining\' cannot stand alone → it is the SUBORDINATE clause.';
                DB::table('exercises')->where('id',1681)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1682)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '\'which I read\' is a RELATIVE clause — it gives extra info about the book.';
                DB::table('exercises')->where('id',1682)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1675)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The passage says climate change is caused mainly by HUMAN ACTIVITIES (burning fuels etc.).';
                DB::table('exercises')->where('id',1675)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1674)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The water cycle = continuous movement of water through the environment.';
                DB::table('exercises')->where('id',1674)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1676)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'How-to passage = INSTRUCTIONAL purpose. It tells you how to do something.';
                DB::table('exercises')->where('id',1676)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1748)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A QUESTION MARK (?) ends a question. Did you know?';
                DB::table('exercises')->where('id',1748)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1717)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A QUESTION MARK (?) ends a question. Did you know?';
                DB::table('exercises')->where('id',1717)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        echo 'CarlaC5Explanations: ' . $updated . ' exercises updated.' . PHP_EOL;
    }
}