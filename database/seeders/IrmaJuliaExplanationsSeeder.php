<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IrmaJuliaExplanationsSeeder extends Seeder
{
    public function run(): void
    {
        $updated = 0;

        $ex = DB::table('exercises')->where('id',369)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The opposite of big = SMALL or little.';
                DB::table('exercises')->where('id',369)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',198)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In the morning we say: Good morning!';
                DB::table('exercises')->where('id',198)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',199)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The sky is BLUE. The sun is yellow, grass is green.';
                DB::table('exercises')->where('id',199)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',281)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In the morning we say: Good morning!';
                DB::table('exercises')->where('id',281)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',285)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use our EYES to see.';
                DB::table('exercises')->where('id',285)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',286)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The opposite of big = SMALL.';
                DB::table('exercises')->where('id',286)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',366)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '5 in words = FIVE. 1=one, 2=two, 3=three, 4=four, 5=five.';
                DB::table('exercises')->where('id',366)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',368)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use our EYES to see.';
                DB::table('exercises')->where('id',368)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',364)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In the morning we say: Good morning!';
                DB::table('exercises')->where('id',364)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',200)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '5 in words = FIVE.';
                DB::table('exercises')->where('id',200)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',202)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use our EYES to see.';
                DB::table('exercises')->where('id',202)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',203)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The opposite of big = SMALL.';
                DB::table('exercises')->where('id',203)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',282)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The sky is BLUE.';
                DB::table('exercises')->where('id',282)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',283)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '5 in words = FIVE.';
                DB::table('exercises')->where('id',283)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',365)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The sky is BLUE.';
                DB::table('exercises')->where('id',365)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1237)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3 apples + 2 oranges = 5 fruits total. Addition: 3 + 2 = 5.';
                DB::table('exercises')->where('id',1237)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',320)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Count the apples one by one: 1, 2, 3. There are 3 apples.';
                DB::table('exercises')->where('id',320)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',246)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Count: 1,2,3,4,5,6,7. The next number is 8.';
                DB::table('exercises')->where('id',246)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',163)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Count: 1,2,3,4,5,6,7. The next number is 8.';
                DB::table('exercises')->where('id',163)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',978)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 litre is much more than 1 millilitre. 1 litre = 1000 ml!';
                DB::table('exercises')->where('id',978)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',157)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Look at both numbers carefully. The bigger one has more value.';
                DB::table('exercises')->where('id',157)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',154)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Count the apples: 1, 2, 3. There are 3 apples.';
                DB::table('exercises')->where('id',154)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',329)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'After 7 comes 8. Count: 6, 7, 8.';
                DB::table('exercises')->where('id',329)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',237)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Count the apples: 1, 2, 3. There are 3 apples.';
                DB::table('exercises')->where('id',237)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',240)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Compare the two numbers. The one with more value is bigger.';
                DB::table('exercises')->where('id',240)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',323)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Compare the two numbers. The one with more value is bigger.';
                DB::table('exercises')->where('id',323)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',239)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '8 - 3 = 5. Count back from 8: 7, 6, 5. Or use fingers!';
                DB::table('exercises')->where('id',239)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',156)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '8 - 3 = 5.';
                DB::table('exercises')->where('id',156)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',321)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3 + 4 = 7. Count on from 3: 4, 5, 6, 7.';
                DB::table('exercises')->where('id',321)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',322)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '8 - 3 = 5.';
                DB::table('exercises')->where('id',322)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',155)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3 + 4 = 7.';
                DB::table('exercises')->where('id',155)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',238)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3 + 4 = 7.';
                DB::table('exercises')->where('id',238)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',158)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A TRIANGLE has 3 sides and 3 corners. Tri = 3.';
                DB::table('exercises')->where('id',158)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',241)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A TRIANGLE has 3 sides. A square has 4. A circle has 0.';
                DB::table('exercises')->where('id',241)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',324)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A TRIANGLE has 3 sides.';
                DB::table('exercises')->where('id',324)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',977)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 kg = 1000 g. A kilogram is MUCH heavier than a gram.';
                DB::table('exercises')->where('id',977)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',976)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 metre = 100 cm. A metre is MUCH longer than a centimetre.';
                DB::table('exercises')->where('id',976)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',980)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use a SCALE (balance) to measure mass/weight.';
                DB::table('exercises')->where('id',980)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',979)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use a RULER to measure length. It has cm and mm markings.';
                DB::table('exercises')->where('id',979)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',930)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 hour = 60 minutes. A clock has 60 minute marks.';
                DB::table('exercises')->where('id',930)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',242)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '50F - 20F = 30F. Subtract what was spent from what you had.';
                DB::table('exercises')->where('id',242)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',159)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '50F - 20F = 30F.';
                DB::table('exercises')->where('id',159)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',932)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 week = 7 days: Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday.';
                DB::table('exercises')->where('id',932)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',931)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 day = 24 hours. A full day from midnight to midnight.';
                DB::table('exercises')->where('id',931)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',325)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '50F - 20F = 30F.';
                DB::table('exercises')->where('id',325)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1070)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '25F + 50F = 75F. Add the prices together.';
                DB::table('exercises')->where('id',1070)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1069)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '50F - 20F = 30F change.';
                DB::table('exercises')->where('id',1069)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1042)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A set is a GROUP or COLLECTION of objects with something in common.';
                DB::table('exercises')->where('id',1042)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1043)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '{cat, dog, bird} has 3 members. Count the elements inside {}.';
                DB::table('exercises')->where('id',1043)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1236)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '4 tally marks (||||) = 4. Count each mark: 1, 2, 3, 4.';
                DB::table('exercises')->where('id',1236)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',104)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'On dort dans la CHAMBRE. C\'est la piece pour dormir.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = 'On prepare les repas dans la CUISINE.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = 'Dans le salon, on trouve le canape ou des chaises.';
                if (isset($c['questions'][3])) $c['questions'][3]['explanation'] = 'Une fenetre est une ouverture dans le mur pour laisser passer la lumiere.';
                DB::table('exercises')->where('id',104)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',108)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'On achete des legumes au MARCHE.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = 'Le CHEF ou le roi dirige un village traditionnel.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = 'Les gens d\'un village s\'appellent les VILLAGEOIS.';
                DB::table('exercises')->where('id',108)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',112)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Le MEDECIN soigne les malades.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = 'L\'ENSEIGNANT enseigne a l\'ecole.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = 'L\'AGRICULTEUR cultive la terre.';
                if (isset($c['questions'][3])) $c['questions'][3]['explanation'] = 'Le CHAUFFEUR conduit un taxi.';
                DB::table('exercises')->where('id',112)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',118)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'On ecoute la musique sur la RADIO.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = 'On envoie des messages avec le TELEPHONE.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = 'Le TAMBOUR communique par sons dans les traditions africaines.';
                if (isset($c['questions'][3])) $c['questions'][3]['explanation'] = 'On lit les nouvelles dans le JOURNAL.';
                DB::table('exercises')->where('id',118)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1143)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A dog lives in a KENNEL. A bird lives in a nest. A fish lives in water.';
                DB::table('exercises')->where('id',1143)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1142)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The COW gives us milk. It is a domestic animal.';
                DB::table('exercises')->where('id',1142)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1165)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A dog lives in a KENNEL.';
                DB::table('exercises')->where('id',1165)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1164)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The COW gives us milk.';
                DB::table('exercises')->where('id',1164)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1124)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The COW gives us milk.';
                DB::table('exercises')->where('id',1124)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1125)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A dog lives in a KENNEL.';
                DB::table('exercises')->where('id',1125)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1171)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A BICYCLE is a machine. It uses wheels and pedals to move.';
                DB::table('exercises')->where('id',1171)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1149)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A machine is a device that makes work easier. A bicycle, car, scissor are machines.';
                DB::table('exercises')->where('id',1149)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1131)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A machine makes work easier. Examples: bicycle, scissors, hammer.';
                DB::table('exercises')->where('id',1131)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1128)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The ROOTS are underground. They absorb water and anchor the plant.';
                DB::table('exercises')->where('id',1128)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1129)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Plants need SUNLIGHT, water and air (CO2) to grow.';
                DB::table('exercises')->where('id',1129)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1146)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The ROOTS are underground.';
                DB::table('exercises')->where('id',1146)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1147)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Plants need SUNLIGHT and water to grow.';
                DB::table('exercises')->where('id',1147)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1168)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The ROOTS are underground.';
                DB::table('exercises')->where('id',1168)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1169)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Plants need SUNLIGHT and water.';
                DB::table('exercises')->where('id',1169)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1163)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use our EARS for hearing. Eyes=see, Nose=smell, Tongue=taste, Skin=touch.';
                DB::table('exercises')->where('id',1163)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1141)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use our EARS for hearing.';
                DB::table('exercises')->where('id',1141)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1123)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use our EARS for hearing.';
                DB::table('exercises')->where('id',1123)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1158)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use our eyes to SEE. Eyes are the organs of sight.';
                DB::table('exercises')->where('id',1158)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1159)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We wash our hands with SOAP and water to remove germs.';
                DB::table('exercises')->where('id',1159)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1119)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We wash our hands with SOAP and water.';
                DB::table('exercises')->where('id',1119)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1136)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use our eyes to SEE.';
                DB::table('exercises')->where('id',1136)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1137)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We wash our hands with SOAP and water.';
                DB::table('exercises')->where('id',1137)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1117)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Humans have 2 eyes. Two eyes give us depth perception.';
                DB::table('exercises')->where('id',1117)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1118)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use our eyes to SEE.';
                DB::table('exercises')->where('id',1118)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1135)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Humans have 2 eyes.';
                DB::table('exercises')->where('id',1135)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1157)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Humans have 2 eyes.';
                DB::table('exercises')->where('id',1157)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1180)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Binta has 3 goats + 2 cows = 5 animals total. 3 + 2 = 5.';
                DB::table('exercises')->where('id',1180)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1179)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The text says Tom has a RED ball. Always go back to the text!';
                DB::table('exercises')->where('id',1179)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1153)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'B is for Ball, Banana, Bird, Book. Look for the word starting with B.';
                DB::table('exercises')->where('id',1153)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1154)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Look for the word ending in the letter T. Examples: cat, bat, sit, hot.';
                DB::table('exercises')->where('id',1154)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1175)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'B is for Ball, Banana, Bird, Book.';
                DB::table('exercises')->where('id',1175)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1176)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Look for the word ending in T. Examples: cat, bat, rat, hat.';
                DB::table('exercises')->where('id',1176)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',791)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A TV REMOTE controls the television — changes channels and volume.';
                DB::table('exercises')->where('id',791)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',790)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use a mobile phone to CALL and send messages.';
                DB::table('exercises')->where('id',790)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',780)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The part we look at is the SCREEN (monitor).';
                DB::table('exercises')->where('id',780)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',781)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use the KEYBOARD to type letters and numbers.';
                DB::table('exercises')->where('id',781)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',782)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use the mouse to CLICK and navigate on the computer.';
                DB::table('exercises')->where('id',782)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',787)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Books, computers and radios give us INFORMATION.';
                DB::table('exercises')->where('id',787)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',786)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'To turn on a computer, you press the POWER button.';
                DB::table('exercises')->where('id',786)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',814)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Every child has the right to EDUCATION. Go to school!';
                DB::table('exercises')->where('id',814)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',816)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'UNICEF (United Nations Children\'s Fund) protects children\'s rights.';
                DB::table('exercises')->where('id',816)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',810)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We should RESPECT our teachers and parents. Respect = treating others with kindness.';
                DB::table('exercises')->where('id',810)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',812)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Being honest means TELLING THE TRUTH always.';
                DB::table('exercises')->where('id',812)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',819)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'When we have a problem, we should TALK and solve it peacefully.';
                DB::table('exercises')->where('id',819)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',817)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Peace means living without FIGHTING or conflict.';
                DB::table('exercises')->where('id',817)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',807)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The star on the Cameroon flag is GOLD/YELLOW. It represents unity.';
                DB::table('exercises')->where('id',807)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',806)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The Cameroon flag has 3 colours: green, red and yellow.';
                DB::table('exercises')->where('id',806)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',809)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We sing the national anthem to show love for OUR COUNTRY (Cameroon).';
                DB::table('exercises')->where('id',809)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1313)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The father of your father is your GRANDFATHER.';
                DB::table('exercises')->where('id',1313)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1314)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A nuclear family = father, mother and CHILDREN.';
                DB::table('exercises')->where('id',1314)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1316)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Usually the MOTHER cooks food at home. But anyone can cook!';
                DB::table('exercises')->where('id',1316)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1320)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Sick people go to the HOSPITAL or health centre for treatment.';
                DB::table('exercises')->where('id',1320)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1321)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We go to the market to BUY and SELL goods and food.';
                DB::table('exercises')->where('id',1321)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1317)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The TEACHER teaches us at school.';
                DB::table('exercises')->where('id',1317)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1318)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We must OBEY RULES and RESPECT others at school.';
                DB::table('exercises')->where('id',1318)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1384)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Ndole is a traditional dish from the LITTORAL (Coastal) region of Cameroon.';
                DB::table('exercises')->where('id',1384)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1385)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Traditional clothing in Cameroon varies by REGION and ethnic group.';
                DB::table('exercises')->where('id',1385)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1381)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Greeting elders shows RESPECT — it is very important in Cameroonian cultures.';
                DB::table('exercises')->where('id',1381)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1383)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Sharing food with visitors is a sign of HOSPITALITY — welcoming others warmly.';
                DB::table('exercises')->where('id',1383)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1378)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon has approximately 275 local languages. It is very diverse!';
                DB::table('exercises')->where('id',1378)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1380)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Pidgin English is used as a COMMON or trade language between different groups.';
                DB::table('exercises')->where('id',1380)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1377)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon has 2 official languages: FRENCH and ENGLISH.';
                DB::table('exercises')->where('id',1377)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1485)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Primary colours: RED, YELLOW, BLUE. They cannot be made by mixing.';
                DB::table('exercises')->where('id',1485)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1486)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Red + Yellow = ORANGE. Mix primary colours to make secondary colours.';
                DB::table('exercises')->where('id',1486)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1488)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use a BRUSH to paint. A pencil is for drawing.';
                DB::table('exercises')->where('id',1488)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2058)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Primary colours: RED, YELLOW, BLUE. Cannot be mixed from others.';
                DB::table('exercises')->where('id',2058)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2059)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use a RULER to draw straight lines.';
                DB::table('exercises')->where('id',2059)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1489)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Exercise keeps our bodies HEALTHY and strong.';
                DB::table('exercises')->where('id',1489)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1490)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Running is a form of CARDIOVASCULAR (aerobic) exercise.';
                DB::table('exercises')->where('id',1490)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1492)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Football is played by 2 teams — 11 players each.';
                DB::table('exercises')->where('id',1492)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2094)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'SKIPPING involves jumping from one foot to the other alternately.';
                DB::table('exercises')->where('id',2094)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2095)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Dribbling in football = kicking the ball while RUNNING with it.';
                DB::table('exercises')->where('id',2095)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',290)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Use AN before vowel sounds. An ELEPHANT — E is a vowel. An apple, an orange.';
                DB::table('exercises')->where('id',290)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',292)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'She IS a teacher. Verb to be: I am, you are, he/she/it IS.';
                DB::table('exercises')->where('id',292)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',294)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Instead of John (a male name) we use HE.';
                DB::table('exercises')->where('id',294)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',373)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Use AN before vowels: an elephant, an orange, an apple.';
                DB::table('exercises')->where('id',373)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',375)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'She IS a teacher.';
                DB::table('exercises')->where('id',375)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',377)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'John = male = HE.';
                DB::table('exercises')->where('id',377)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',207)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Use AN before vowels: an elephant, an apple.';
                DB::table('exercises')->where('id',207)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',209)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'She IS a teacher.';
                DB::table('exercises')->where('id',209)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',211)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'John = HE. Mary = she. They = plural.';
                DB::table('exercises')->where('id',211)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',374)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'More than one dog = DOGS. Add -s to make most nouns plural.';
                DB::table('exercises')->where('id',374)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',213)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'An adjective describes a noun. \'The BIG cat\' — big describes cat.';
                DB::table('exercises')->where('id',213)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',208)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'More than one dog = DOGS.';
                DB::table('exercises')->where('id',208)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',291)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'More than one dog = DOGS.';
                DB::table('exercises')->where('id',291)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',296)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Adjectives describe nouns. Example: the SMALL cat, the RED ball.';
                DB::table('exercises')->where('id',296)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',379)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Adjectives describe nouns.';
                DB::table('exercises')->where('id',379)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',86)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Fish live in WATER — rivers, lakes and oceans.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = 'Trees give us OXYGEN (clean air), wood, fruit and shade.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = 'A plastic bag is NOT part of nature. It is man-made.';
                if (isset($c['questions'][3])) $c['questions'][3]['explanation'] = 'Farmers grow food on a FARM (in fields or gardens).';
                if (isset($c['questions'][4])) $c['questions'][4]['explanation'] = 'We should NOT dump rubbish in rivers — it pollutes the water.';
                DB::table('exercises')->where('id',86)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',89)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'An AEROPLANE flies in the sky.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = 'A BOAT moves on water.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = 'A BICYCLE has two wheels.';
                if (isset($c['questions'][3])) $c['questions'][3]['explanation'] = 'The person who drives a bus is called a DRIVER or bus driver.';
                if (isset($c['questions'][4])) $c['questions'][4]['explanation'] = 'The safest way to cross = use a PEDESTRIAN CROSSING (zebra crossing).';
                DB::table('exercises')->where('id',89)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',253)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In 35: 3 is in the TENS place = value of 30. The 5 is in the units.';
                DB::table('exercises')->where('id',253)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',247)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '12 + 7 = 19. Count on from 12: 13,14,15,16,17,18,19.';
                DB::table('exercises')->where('id',247)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1240)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Green=7 is the most popular (highest number).';
                DB::table('exercises')->where('id',1240)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1241)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Blue=3 is the least popular (lowest number).';
                DB::table('exercises')->where('id',1241)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',336)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In 35: 3 is in the tens place = value of 30.';
                DB::table('exercises')->where('id',336)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',330)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '12 + 7 = 19.';
                DB::table('exercises')->where('id',330)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',170)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In 35: 3 is in the tens place = 30.';
                DB::table('exercises')->where('id',170)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',164)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '12 + 7 = 19.';
                DB::table('exercises')->where('id',164)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',249)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '4 × 2 = 8. Multiplication is repeated addition: 4+4=8.';
                DB::table('exercises')->where('id',249)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',248)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '18 - 6 = 12. Count back from 18: 17,16,15,14,13,12.';
                DB::table('exercises')->where('id',248)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',165)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '18 - 6 = 12.';
                DB::table('exercises')->where('id',165)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',331)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '18 - 6 = 12.';
                DB::table('exercises')->where('id',331)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',332)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '4 × 2 = 8.';
                DB::table('exercises')->where('id',332)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',166)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '4 × 2 = 8.';
                DB::table('exercises')->where('id',166)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',250)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Half of 10 = 10 ÷ 2 = 5.';
                DB::table('exercises')->where('id',250)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',168)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Compare the fractions. The bigger denominator means smaller pieces.';
                DB::table('exercises')->where('id',168)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',333)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Half of 10 = 5.';
                DB::table('exercises')->where('id',333)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',251)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Compare fractions: same numerator, smaller denominator = bigger fraction.';
                DB::table('exercises')->where('id',251)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',334)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Compare fractions carefully.';
                DB::table('exercises')->where('id',334)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',167)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Half of 10 = 5.';
                DB::table('exercises')->where('id',167)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1086)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Half of 8 = 8 ÷ 2 = 4.';
                DB::table('exercises')->where('id',1086)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1087)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Quarter of 12 = 12 ÷ 4 = 3.';
                DB::table('exercises')->where('id',1087)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1088)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In 3/4: the top number (3) is the NUMERATOR. Bottom (4) = denominator.';
                DB::table('exercises')->where('id',1088)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',174)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A rectangle has 4 sides: 2 long sides and 2 short sides.';
                DB::table('exercises')->where('id',174)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',257)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A rectangle has 4 sides.';
                DB::table('exercises')->where('id',257)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',340)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A rectangle has 4 sides. Opposite sides are equal.';
                DB::table('exercises')->where('id',340)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',984)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 m = 100 cm. So 2 m = 2 × 100 = 200 cm.';
                DB::table('exercises')->where('id',984)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',985)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '300 cm ÷ 100 = 3 metres.';
                DB::table('exercises')->where('id',985)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',986)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 kg = 1000 g. Always!';
                DB::table('exercises')->where('id',986)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',983)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 metre = 100 centimetres.';
                DB::table('exercises')->where('id',983)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',987)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '2 kg × 1000 = 2000 grams.';
                DB::table('exercises')->where('id',987)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',988)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '5000 g ÷ 1000 = 5 kg.';
                DB::table('exercises')->where('id',988)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',990)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3 litres × 1000 = 3000 ml.';
                DB::table('exercises')->where('id',990)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',989)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 litre = 1000 millilitres.';
                DB::table('exercises')->where('id',989)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',937)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '2 weeks × 7 days = 14 days.';
                DB::table('exercises')->where('id',937)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',936)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '2 days × 24 hours = 48 hours.';
                DB::table('exercises')->where('id',936)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',935)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '2 hours × 60 minutes = 120 minutes.';
                DB::table('exercises')->where('id',935)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1072)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '200F - 130F = 70F change.';
                DB::table('exercises')->where('id',1072)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1073)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3 × 25F = 75F total.';
                DB::table('exercises')->where('id',1073)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1074)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '200F > 150F. 200 is more than 150.';
                DB::table('exercises')->where('id',1074)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',341)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 hour = 60 minutes.';
                DB::table('exercises')->where('id',341)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',335)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '200F - 150F = 50F change.';
                DB::table('exercises')->where('id',335)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',258)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 hour = 60 minutes.';
                DB::table('exercises')->where('id',258)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',252)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '200F - 150F = 50F change.';
                DB::table('exercises')->where('id',252)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',175)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 hour = 60 minutes.';
                DB::table('exercises')->where('id',175)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',169)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '200F - 150F = 50F change.';
                DB::table('exercises')->where('id',169)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',939)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '2 minutes × 60 seconds = 120 seconds.';
                DB::table('exercises')->where('id',939)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',938)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 year = 12 months: Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec.';
                DB::table('exercises')->where('id',938)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1239)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '|||| | = 5 tally marks. Every 5th mark crosses the previous four.';
                DB::table('exercises')->where('id',1239)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1047)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The symbol ∈ means \'IS A MEMBER OF\'. Example: 3 ∈ {1,2,3,4}.';
                DB::table('exercises')->where('id',1047)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1046)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A Venn diagram uses CIRCLES (overlapping) to show sets and their relationships.';
                DB::table('exercises')->where('id',1046)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1045)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A set with no members = EMPTY set. Symbol: {} or ∅.';
                DB::table('exercises')->where('id',1045)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',473)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Chat est masculin: LE chat. La chatte (feminin). Le chat est dans la maison.';
                DB::table('exercises')->where('id',473)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',474)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Des chats = PLUSIEURS (more than one). Le pluriel s\'indique par \'des\'.';
                DB::table('exercises')->where('id',474)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',477)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Nous SOMMES des eleves. Etre: je suis, tu es, il est, nous SOMMES.';
                DB::table('exercises')->where('id',477)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',478)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Elle A un livre. Avoir: j\'ai, tu as, il/elle A, nous avons.';
                DB::table('exercises')->where('id',478)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',481)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Le contraire de grand = PETIT. Antonymes: grand/petit, vieux/jeune.';
                DB::table('exercises')->where('id',481)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',482)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Un synonyme de content = HEUREUX. Mots avec le meme sens.';
                DB::table('exercises')->where('id',482)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',843)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'DOMESTIC animals live with people: dog, cat, cow, chicken.';
                DB::table('exercises')->where('id',843)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',844)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'WILD animals live in nature: lion, elephant, giraffe, gorilla.';
                DB::table('exercises')->where('id',844)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',839)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Malaria is spread by the bite of an infected MOSQUITO.';
                DB::table('exercises')->where('id',839)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',840)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cholera is caused by drinking CONTAMINATED (dirty) water.';
                DB::table('exercises')->where('id',840)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',842)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'For a small cut: CLEAN it with water, apply antiseptic, then bandage.';
                DB::table('exercises')->where('id',842)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',850)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A wheel helps us MOVE objects more easily by reducing friction.';
                DB::table('exercises')->where('id',850)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',847)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Plants need SUNLIGHT (and CO2 + water) to make food by photosynthesis.';
                DB::table('exercises')->where('id',847)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',849)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The ROOTS absorb water and minerals from the soil.';
                DB::table('exercises')->where('id',849)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1792)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Dark clouds = it may soon RAIN. Dark clouds carry water droplets.';
                DB::table('exercises')->where('id',1792)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1793)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon has 2 main seasons: RAINY season and DRY season.';
                DB::table('exercises')->where('id',1793)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1795)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A seed needs WATER and warmth to germinate. Also air and light help.';
                DB::table('exercises')->where('id',1795)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',834)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A human has 2 legs. We are BIPEDS (two-legged).';
                DB::table('exercises')->where('id',834)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1796)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Wood, fruit, vegetables and cotton all come from PLANTS.';
                DB::table('exercises')->where('id',1796)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',836)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We wash our hands to REMOVE GERMS and prevent diseases.';
                DB::table('exercises')->where('id',836)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1784)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'CARBOHYDRATES (rice, yam, bread) give us energy.';
                DB::table('exercises')->where('id',1784)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1785)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'PROTEINS (fish, eggs, beans) help our body grow and repair.';
                DB::table('exercises')->where('id',1785)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1788)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Water is NOT used to generate electricity directly in homes (generators use fuel).';
                DB::table('exercises')->where('id',1788)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1789)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We should drink CLEAN (safe, purified or boiled) water.';
                DB::table('exercises')->where('id',1789)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1791)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'SMOKE from factories, cars and burning waste makes the air dirty (pollution).';
                DB::table('exercises')->where('id',1791)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1188)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'CAT rhymes with: bat, hat, mat, rat, sat, fat. They all end in -at.';
                DB::table('exercises')->where('id',1188)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1185)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Read the passage: Ambe grows tomatoes, onions and carrots. He SELLS them.';
                DB::table('exercises')->where('id',1185)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1187)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The opposite of hot = COLD. Antonyms: hot/cold, big/small, old/new.';
                DB::table('exercises')->where('id',1187)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1184)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Read: Mary carries a RED bag. Always find your answer in the text.';
                DB::table('exercises')->where('id',1184)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1182)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The -at family: cat, bat, hat, rat, mat, sat. They all end in -at.';
                DB::table('exercises')->where('id',1182)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1181)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Elephant: el-e-phant = 3 syllables. Clap each syllable to count.';
                DB::table('exercises')->where('id',1181)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',793)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The keyboard is an INPUT device — we put information INTO the computer.';
                DB::table('exercises')->where('id',793)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',794)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The printer is an OUTPUT device — it produces something FROM the computer.';
                DB::table('exercises')->where('id',794)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',805)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Computers help us LEARN, search information and do schoolwork.';
                DB::table('exercises')->where('id',805)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',802)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The internet helps us FIND and SEARCH information.';
                DB::table('exercises')->where('id',802)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',803)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Online, never share your PERSONAL INFORMATION (name, address, password).';
                DB::table('exercises')->where('id',803)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',798)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'To open a program, you DOUBLE CLICK on its icon.';
                DB::table('exercises')->where('id',798)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',799)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The spacebar creates a SPACE between words when typing.';
                DB::table('exercises')->where('id',799)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',800)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The Enter key confirms an action or goes to a new line.';
                DB::table('exercises')->where('id',800)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',833)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Tolerance = accepting people who are DIFFERENT from us.';
                DB::table('exercises')->where('id',833)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',831)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A community = a group of people who LIVE TOGETHER in the same area.';
                DB::table('exercises')->where('id',831)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',823)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon\'s National Day is on 20th MAY.';
                DB::table('exercises')->where('id',823)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',820)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The capital of Cameroon is YAOUNDE (political capital).';
                DB::table('exercises')->where('id',820)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',821)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon has 2 official languages: FRENCH and ENGLISH.';
                DB::table('exercises')->where('id',821)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',830)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Keeping the classroom clean is our DUTY (responsibility).';
                DB::table('exercises')->where('id',830)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',828)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Children have the right to EDUCATION, health, protection and play.';
                DB::table('exercises')->where('id',828)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',826)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'At school, we obey the TEACHERS and HEAD TEACHER.';
                DB::table('exercises')->where('id',826)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',824)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'School rules help us to BE SAFE and LEARN better.';
                DB::table('exercises')->where('id',824)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1323)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Extended family includes grandparents, aunts, uncles and COUSINS.';
                DB::table('exercises')->where('id',1323)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1324)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Respect for elders means LISTENING to them and treating them with honour.';
                DB::table('exercises')->where('id',1324)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1326)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Water, trees, soil and air are NATURAL resources. Plastic is man-made.';
                DB::table('exercises')->where('id',1326)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1327)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We protect the environment by NOT LITTERING and planting trees.';
                DB::table('exercises')->where('id',1327)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1329)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Rivers, lakes and rain are natural sources of water.';
                DB::table('exercises')->where('id',1329)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1330)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The leader of a town council is called a MAYOR.';
                DB::table('exercises')->where('id',1330)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1331)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In a traditional village, the CHIEF or FON settles disputes.';
                DB::table('exercises')->where('id',1331)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1391)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In Cameroonian cultures, marriage involves paying the BRIDE PRICE (dot).';
                DB::table('exercises')->where('id',1391)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1392)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A naming ceremony is held to GIVE A NAME to a newborn baby.';
                DB::table('exercises')->where('id',1392)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1390)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The Fulani are traditionally known as CATTLE HERDERS (pastoralists).';
                DB::table('exercises')->where('id',1390)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1387)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The BAMILEKE people are mainly found in the Western highlands.';
                DB::table('exercises')->where('id',1387)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1388)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The Baka people live in the FORESTS of southern Cameroon.';
                DB::table('exercises')->where('id',1388)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1394)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The Bamoun are famous for their SHUMOM SCRIPT and royal palace arts.';
                DB::table('exercises')->where('id',1394)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1395)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Woven cloth and raffia items come mainly from the WEST region (Grassfields).';
                DB::table('exercises')->where('id',1395)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1493)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Secondary colours: ORANGE (red+yellow), GREEN (blue+yellow), PURPLE (red+blue).';
                DB::table('exercises')->where('id',1493)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1494)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A LINE separates areas of colour in art and design.';
                DB::table('exercises')->where('id',1494)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1496)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A collage is made by GLUING or STICKING materials onto paper.';
                DB::table('exercises')->where('id',1496)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2064)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'We use STRIPS OF MATERIAL (raffia, bamboo, cane) to weave a basket.';
                DB::table('exercises')->where('id',2064)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2065)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Origami is the art of PAPER FOLDING to create shapes and figures.';
                DB::table('exercises')->where('id',2065)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1497)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In the long jump, athletes jump for DISTANCE (as far as possible).';
                DB::table('exercises')->where('id',1497)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1498)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In a relay race, athletes pass a BATON to the next runner.';
                DB::table('exercises')->where('id',1498)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1500)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Before exercise, we should WARM UP to prepare muscles and avoid injury.';
                DB::table('exercises')->where('id',1500)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2100)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In a relay race, runners pass a BATON to the next runner.';
                DB::table('exercises')->where('id',2100)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2101)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A FORWARD ROLL is a gymnastics movement. Running is athletics, not gymnastics.';
                DB::table('exercises')->where('id',2101)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        echo 'IrmaJuliaExplanations: ' . $updated . ' exercises updated.' . PHP_EOL;
    }
}