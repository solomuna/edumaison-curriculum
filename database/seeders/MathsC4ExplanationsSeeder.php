<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MathsC4ExplanationsSeeder extends Seeder
{
    public function run(): void
    {
        $updated = 0;

        // ID 916
        $ex = DB::table('exercises')->where('id',916)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'AM = matin (avant midi). L\'ecole commence a 7h30, donc c\'est AM.';
                DB::table('exercises')->where('id',916)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 917
        $ex = DB::table('exercises')->where('id',917)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 heure = 60 minutes. Regle fondamentale du temps!';
                DB::table('exercises')->where('id',917)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 950
        $ex = DB::table('exercises')->where('id',950)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '4 heures x 60 minutes = 240 minutes. Multiplie les heures par 60.';
                DB::table('exercises')->where('id',950)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 951
        $ex = DB::table('exercises')->where('id',951)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '4 jours x 24 heures = 96 heures. 1 jour = 24 heures.';
                DB::table('exercises')->where('id',951)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 956
        $ex = DB::table('exercises')->where('id',956)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Avril a 30 jours. Retiens: 30 jours = Avril, Juin, Septembre, Novembre.';
                DB::table('exercises')->where('id',956)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1826
        $ex = DB::table('exercises')->where('id',1826)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '673: chiffre des unites=3 < 5, arrondi a 670. Dizaines=7 >= 5, arrondi a 700.';
                DB::table('exercises')->where('id',1826)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1825
        $ex = DB::table('exercises')->where('id',1825)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '438: chiffre des dizaines=3 < 5, arrondi a 400.';
                DB::table('exercises')->where('id',1825)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1823
        $ex = DB::table('exercises')->where('id',1823)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '450: chiffre des dizaines=5, arrondi vers le haut = 500.';
                DB::table('exercises')->where('id',1823)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1822
        $ex = DB::table('exercises')->where('id',1822)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '782: chiffre des dizaines=8 >= 5, arrondi a 800.';
                DB::table('exercises')->where('id',1822)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1821
        $ex = DB::table('exercises')->where('id',1821)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '650: chiffre des dizaines=5, arrondi vers le haut = 700.';
                DB::table('exercises')->where('id',1821)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1820
        $ex = DB::table('exercises')->where('id',1820)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '347: chiffre des dizaines=4 < 5, arrondi a 300.';
                DB::table('exercises')->where('id',1820)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1819
        $ex = DB::table('exercises')->where('id',1819)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '285: chiffre des unites=5, arrondi vers le haut = 290.';
                DB::table('exercises')->where('id',1819)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1818
        $ex = DB::table('exercises')->where('id',1818)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '136: chiffre des unites=6 >= 5, arrondi a 140.';
                DB::table('exercises')->where('id',1818)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1252
        $ex = DB::table('exercises')->where('id',1252)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Range = max - min = 12 - 3 = 9.';
                DB::table('exercises')->where('id',1252)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1084
        $ex = DB::table('exercises')->where('id',1084)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '2000 x 19.25% = 385F. Total = 2000 + 385 = 2385F.';
                DB::table('exercises')->where('id',1084)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 279
        $ex = DB::table('exercises')->where('id',279)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Annee bissextile = 366 jours. Arrive tous les 4 ans (ex: 2024).';
                DB::table('exercises')->where('id',279)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 362
        $ex = DB::table('exercises')->where('id',362)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Annee bissextile = 366 jours. Arrive tous les 4 ans (ex: 2024).';
                DB::table('exercises')->where('id',362)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 196
        $ex = DB::table('exercises')->where('id',196)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Annee bissextile = 366 jours. Arrive tous les 4 ans (ex: 2024).';
                DB::table('exercises')->where('id',196)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1055
        $ex = DB::table('exercises')->where('id',1055)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cardinal d\'un ensemble = nombre d\'elements. {a,b,c} = cardinal 3.';
                DB::table('exercises')->where('id',1055)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1083
        $ex = DB::table('exercises')->where('id',1083)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '10% de 5000F = 500F. Prix solde = 5000 - 500 = 4500F.';
                DB::table('exercises')->where('id',1083)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1097
        $ex = DB::table('exercises')->where('id',1097)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '7 / 4 = 1 reste 3. Donc 7/4 = 1 et 3/4.';
                DB::table('exercises')->where('id',1097)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1272
        $ex = DB::table('exercises')->where('id',1272)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'BODMAS: division et multiplication d\'abord. 20/4=5 et 3x2=6. Puis 5+6=11.';
                DB::table('exercises')->where('id',1272)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1273
        $ex = DB::table('exercises')->where('id',1273)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Parentheses d\'abord: (10-4)=6 et (2+3)=5. Puis 6x5=30.';
                DB::table('exercises')->where('id',1273)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1274
        $ex = DB::table('exercises')->where('id',1274)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '2 cube=8 d\'abord. Puis 4x3=12. Ensuite 8+12=20.';
                DB::table('exercises')->where('id',1274)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1279
        $ex = DB::table('exercises')->where('id',1279)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Un nombre premier a exactement 2 facteurs: 1 et lui-meme. Ex: 7.';
                DB::table('exercises')->where('id',1279)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1283
        $ex = DB::table('exercises')->where('id',1283)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '2 est le plus petit nombre premier. 1 n\'est pas premier (1 seul facteur).';
                DB::table('exercises')->where('id',1283)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1282
        $ex = DB::table('exercises')->where('id',1282)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '2, 3, 5, 7 sont premiers. 4=2x2, 6=2x3, 9=3x3 ne sont pas premiers.';
                DB::table('exercises')->where('id',1282)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1281
        $ex = DB::table('exercises')->where('id',1281)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '9 = 3 x 3. Il a 3 facteurs (1, 3, 9). Ce n\'est PAS un nombre premier.';
                DB::table('exercises')->where('id',1281)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1280
        $ex = DB::table('exercises')->where('id',1280)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '7 a exactement 2 facteurs: 1 et 7. C\'est bien un nombre premier!';
                DB::table('exercises')->where('id',1280)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 98
        $ex = DB::table('exercises')->where('id',98)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '35742: le 3 est a la position des dizaines de milliers = 30000.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = '47206: quarante-sept mille deux cent six.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = '64380: chiffre des milliers=4 < 5, arrondi a 64000.';
                if (isset($c['questions'][3])) $c['questions'][3]['explanation'] = 'Comparer les chiffres de gauche a droite pour trouver le plus grand.';
                if (isset($c['questions'][4])) $c['questions'][4]['explanation'] = '50000 + 6000 + 400 + 20 + 3 = 56423.';
                DB::table('exercises')->where('id',98)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1099
        $ex = DB::table('exercises')->where('id',1099)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3/4 - 1/4 = 2/4 = 1/2. Meme denominateur: soustraire les numerateurs.';
                DB::table('exercises')->where('id',1099)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1096
        $ex = DB::table('exercises')->where('id',1096)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Fraction impropre: numerateur > denominateur. Ex: 7/4 (7 > 4).';
                DB::table('exercises')->where('id',1096)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1098
        $ex = DB::table('exercises')->where('id',1098)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1/2 + 1/4: meme denominateur: 2/4 + 1/4 = 3/4.';
                DB::table('exercises')->where('id',1098)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1100
        $ex = DB::table('exercises')->where('id',1100)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1/2 x 3/4 = (1x3)/(2x4) = 3/8.';
                DB::table('exercises')->where('id',1100)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1101
        $ex = DB::table('exercises')->where('id',1101)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Facteurs de 12: 1,2,3,4,6,12. Facteurs de 18: 1,2,3,6,9,18. PGCD = 6.';
                DB::table('exercises')->where('id',1101)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1292
        $ex = DB::table('exercises')->where('id',1292)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '5.8 - 2.3: aligner les virgules. 5.8 - 2.3 = 3.5.';
                DB::table('exercises')->where('id',1292)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1293
        $ex = DB::table('exercises')->where('id',1293)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '0.7 = 0.70. Compare 0.70 > 0.65. 0.7 est plus grand.';
                DB::table('exercises')->where('id',1293)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1291
        $ex = DB::table('exercises')->where('id',1291)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1.5 + 2.3: aligner les virgules. 1.5 + 2.3 = 3.8.';
                DB::table('exercises')->where('id',1291)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 186
        $ex = DB::table('exercises')->where('id',186)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3/4 - 1/4 = 2/4 = 1/2. Meme denominateur: soustraire les numerateurs.';
                DB::table('exercises')->where('id',186)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1290
        $ex = DB::table('exercises')->where('id',1290)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3.47: 3=unites, 4=dixiemes, 7=centiemes. Le 4 est aux dixiemes.';
                DB::table('exercises')->where('id',1290)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 952
        $ex = DB::table('exercises')->where('id',952)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '8h30 a 10h00: de 8h30 a 9h30=1h, de 9h30 a 10h00=30min. Total=1h30.';
                DB::table('exercises')->where('id',952)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 954
        $ex = DB::table('exercises')->where('id',954)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3h00 + 90min = 3h00 + 1h30 = 4h30 PM.';
                DB::table('exercises')->where('id',954)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 353
        $ex = DB::table('exercises')->where('id',353)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '0.5 = 5/10 = 1/2. Un demi = 0.5.';
                DB::table('exercises')->where('id',353)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 352
        $ex = DB::table('exercises')->where('id',352)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3/4 - 1/4 = 2/4 = 1/2.';
                DB::table('exercises')->where('id',352)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 270
        $ex = DB::table('exercises')->where('id',270)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '0.5 = 5/10 = 1/2.';
                DB::table('exercises')->where('id',270)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 269
        $ex = DB::table('exercises')->where('id',269)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '3/4 - 1/4 = 2/4 = 1/2.';
                DB::table('exercises')->where('id',269)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 187
        $ex = DB::table('exercises')->where('id',187)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '0.5 = 5/10 = 1/2.';
                DB::table('exercises')->where('id',187)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 95
        $ex = DB::table('exercises')->where('id',95)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Hexagone: hexa = 6. Pentagon=5, Hexagon=6, Heptagon=7, Octagon=8.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = 'Perimetre carre = 4 x cote = 4 x 5 = 20 cm.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = 'Angle droit = exactement 90 degres.';
                if (isset($c['questions'][3])) $c['questions'][3]['explanation'] = 'Aire = longueur x largeur = 6 x 4 = 24 cm2.';
                if (isset($c['questions'][4])) $c['questions'][4]['explanation'] = 'Le cercle n\'a aucun coin ni angle.';
                DB::table('exercises')->where('id',95)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 188
        $ex = DB::table('exercises')->where('id',188)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Aire = longueur x largeur = 6 x 4 = 24 cm2.';
                DB::table('exercises')->where('id',188)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 189
        $ex = DB::table('exercises')->where('id',189)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Angle droit = exactement 90 degres.';
                DB::table('exercises')->where('id',189)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 271
        $ex = DB::table('exercises')->where('id',271)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Aire = longueur x largeur = 6 x 4 = 24 cm2.';
                DB::table('exercises')->where('id',271)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 272
        $ex = DB::table('exercises')->where('id',272)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Angle droit = exactement 90 degres.';
                DB::table('exercises')->where('id',272)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 354
        $ex = DB::table('exercises')->where('id',354)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Aire = longueur x largeur = 6 x 4 = 24 cm2.';
                DB::table('exercises')->where('id',354)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 355
        $ex = DB::table('exercises')->where('id',355)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Angle droit = exactement 90 degres.';
                DB::table('exercises')->where('id',355)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1011
        $ex = DB::table('exercises')->where('id',1011)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Perimetre carre = 4 x cote = 4 x 6 = 24 cm.';
                DB::table('exercises')->where('id',1011)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1012
        $ex = DB::table('exercises')->where('id',1012)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Perimetre rectangle = 2 x (l + L) = 2 x (8+5) = 26 cm.';
                DB::table('exercises')->where('id',1012)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1013
        $ex = DB::table('exercises')->where('id',1013)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Perimetre triangle = 3 + 4 + 5 = 12 cm.';
                DB::table('exercises')->where('id',1013)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1014
        $ex = DB::table('exercises')->where('id',1014)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Aire carre = cote x cote = 5 x 5 = 25 cm2.';
                DB::table('exercises')->where('id',1014)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1015
        $ex = DB::table('exercises')->where('id',1015)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Aire rectangle = 7 x 4 = 28 cm2.';
                DB::table('exercises')->where('id',1015)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1018
        $ex = DB::table('exercises')->where('id',1018)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'L\'aire se mesure en unites carrees: cm2, m2, km2.';
                DB::table('exercises')->where('id',1018)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1007
        $ex = DB::table('exercises')->where('id',1007)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 km = 1000 m. 3500 m / 1000 = 3.5 km.';
                DB::table('exercises')->where('id',1007)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 268
        $ex = DB::table('exercises')->where('id',268)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Multiples de 4: 4,8,12... Multiples de 6: 6,12,18... PPCM = 12.';
                DB::table('exercises')->where('id',268)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1010
        $ex = DB::table('exercises')->where('id',1010)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 litre = 1000 ml. 0.75 l x 1000 = 750 ml.';
                DB::table('exercises')->where('id',1010)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1009
        $ex = DB::table('exercises')->where('id',1009)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 litre = 100 cl. 250 cl / 100 = 2.5 litres.';
                DB::table('exercises')->where('id',1009)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1008
        $ex = DB::table('exercises')->where('id',1008)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 kg = 1000 g. 1.5 kg x 1000 = 1500 g.';
                DB::table('exercises')->where('id',1008)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 274
        $ex = DB::table('exercises')->where('id',274)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Vitesse = Distance / Temps = 60 / 2 = 30 km/h.';
                DB::table('exercises')->where('id',274)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1006
        $ex = DB::table('exercises')->where('id',1006)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '1 km = 1000 m. 2.5 km x 1000 = 2500 m.';
                DB::table('exercises')->where('id',1006)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 185
        $ex = DB::table('exercises')->where('id',185)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Multiples de 4: 4,8,12... Multiples de 6: 6,12,18... PPCM = 12.';
                DB::table('exercises')->where('id',185)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 955
        $ex = DB::table('exercises')->where('id',955)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '13h30 + 2h = 15h30 = 3h30 PM.';
                DB::table('exercises')->where('id',955)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 953
        $ex = DB::table('exercises')->where('id',953)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '7h15 + 45min = 8h00.';
                DB::table('exercises')->where('id',953)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 357
        $ex = DB::table('exercises')->where('id',357)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Vitesse = Distance / Temps = 60 / 2 = 30 km/h.';
                DB::table('exercises')->where('id',357)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 351
        $ex = DB::table('exercises')->where('id',351)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'PPCM de 4 et 6 = 12.';
                DB::table('exercises')->where('id',351)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 191
        $ex = DB::table('exercises')->where('id',191)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Vitesse = Distance / Temps = 60 / 2 = 30 km/h.';
                DB::table('exercises')->where('id',191)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 273
        $ex = DB::table('exercises')->where('id',273)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Intersection = elements dans les DEUX ensembles. {2,4,6,8} et {4,8,12} = {4,8}.';
                DB::table('exercises')->where('id',273)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 192
        $ex = DB::table('exercises')->where('id',192)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Profit = Prix de vente - Prix d\'achat = 1000 - 800 = 200 FCFA.';
                DB::table('exercises')->where('id',192)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 190
        $ex = DB::table('exercises')->where('id',190)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Intersection = elements dans les DEUX ensembles. {2,4,6,8} et {4,8,12} = {4,8}.';
                DB::table('exercises')->where('id',190)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 275
        $ex = DB::table('exercises')->where('id',275)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Profit = Prix de vente - Prix d\'achat = 1000 - 800 = 200 FCFA.';
                DB::table('exercises')->where('id',275)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1058
        $ex = DB::table('exercises')->where('id',1058)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Multiples communs de 4 et 6: 12, 24... Le plus petit = 12 = PPCM.';
                DB::table('exercises')->where('id',1058)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1249
        $ex = DB::table('exercises')->where('id',1249)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Moyenne = somme / nombre = (4+6+8) / 3 = 18 / 3 = 6.';
                DB::table('exercises')->where('id',1249)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1250
        $ex = DB::table('exercises')->where('id',1250)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Mode = valeur la plus frequente. 3 apparait 3 fois = mode.';
                DB::table('exercises')->where('id',1250)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1251
        $ex = DB::table('exercises')->where('id',1251)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Mediane = valeur du milieu. {2,4,6,8,10} = 6 au milieu.';
                DB::table('exercises')->where('id',1251)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1253
        $ex = DB::table('exercises')->where('id',1253)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Temperature: 30 - 20 = augmentation de 10 degres C.';
                DB::table('exercises')->where('id',1253)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1082
        $ex = DB::table('exercises')->where('id',1082)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Profit% = (200 / 1000) x 100 = 20%.';
                DB::table('exercises')->where('id',1082)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1081
        $ex = DB::table('exercises')->where('id',1081)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Perte = Prix d\'achat - Prix de vente = 3000 - 2600 = 400 FCFA.';
                DB::table('exercises')->where('id',1081)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1080
        $ex = DB::table('exercises')->where('id',1080)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Profit = Prix de vente - Prix d\'achat = 2500 - 2000 = 500 FCFA.';
                DB::table('exercises')->where('id',1080)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1057
        $ex = DB::table('exercises')->where('id',1057)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Facteurs communs de 12 et 8: 1, 2, 4. PGCD = 4.';
                DB::table('exercises')->where('id',1057)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 1056
        $ex = DB::table('exercises')->where('id',1056)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A={1,2,3} et B={3,1,2} ont les memes elements = ensembles egaux.';
                DB::table('exercises')->where('id',1056)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 358
        $ex = DB::table('exercises')->where('id',358)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Profit = Prix de vente - Prix d\'achat = 1000 - 800 = 200 FCFA.';
                DB::table('exercises')->where('id',358)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }
        // ID 356
        $ex = DB::table('exercises')->where('id',356)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Intersection = {4,8}.';
                DB::table('exercises')->where('id',356)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        echo 'MathsC4Explanations: ' . $updated . ' exercises updated.' . PHP_EOL;
    }
}