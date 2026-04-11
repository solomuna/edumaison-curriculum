<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarkC4OtherExplanationsSeeder extends Seeder
{
    public function run(): void
    {
        $updated = 0;

        $ex = DB::table('exercises')->where('id',1466)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Football = FOOT + BALL. Two separate words joined = compound word.';
                DB::table('exercises')->where('id',1466)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1465)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Their, there and they\'re all sound the same = homophones.';
                DB::table('exercises')->where('id',1465)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1467)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '\'Raining cats and dogs\' = idiom meaning it is raining VERY HEAVILY.';
                DB::table('exercises')->where('id',1467)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',395)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Superlative of tall = tallest. Mount Cameroon is the HIGHEST/TALLEST.';
                DB::table('exercises')->where('id',395)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',226)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Present perfect: has/have + past participle. She HAS already eaten.';
                DB::table('exercises')->where('id',226)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',227)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Zero conditional: if + present, will + base verb. We WILL stay inside.';
                DB::table('exercises')->where('id',227)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',309)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Present perfect: She HAS already eaten.';
                DB::table('exercises')->where('id',309)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',310)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'If it rains, we WILL stay inside. (First conditional)';
                DB::table('exercises')->where('id',310)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',392)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Present perfect: She HAS already eaten.';
                DB::table('exercises')->where('id',392)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',393)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'If it rains, we WILL stay inside.';
                DB::table('exercises')->where('id',393)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1456)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'BUT shows contrast/opposition. She was tired BUT she kept working.';
                DB::table('exercises')->where('id',1456)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1457)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Comparative: adjective + -er + than. Mount Cameroon is HIGHER than Mount Oku.';
                DB::table('exercises')->where('id',1457)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1458)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Superlative: the + adjective + -est. He is the BEST/SMARTEST student.';
                DB::table('exercises')->where('id',1458)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1459)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Words inside quotation marks = DIRECT speech. \'I am tired,\' she said.';
                DB::table('exercises')->where('id',1459)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1461)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Adverbs describe verbs. She runs QUICKLY/FAST. Adverbs often end in -ly.';
                DB::table('exercises')->where('id',1461)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1460)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Positive sentence with positive tag: She IS your friend, ISN\'T she?';
                DB::table('exercises')->where('id',1460)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1468)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Market opens 7am, closes 6pm. Mama arrived 8am. She had 10 hours (8am-6pm).';
                DB::table('exercises')->where('id',1468)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',225)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'WHO is used for people as subject. The girl WHO won = subject.';
                DB::table('exercises')->where('id',225)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',229)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Superlative: the HIGHEST/TALLEST mountain.';
                DB::table('exercises')->where('id',229)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',308)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'WHO refers to a person (subject). The girl WHO won.';
                DB::table('exercises')->where('id',308)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',312)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Superlative: the HIGHEST mountain.';
                DB::table('exercises')->where('id',312)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',391)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'WHO refers to people as subject.';
                DB::table('exercises')->where('id',391)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1455)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Present perfect: She has FINISHED/DONE her homework. Has + past participle.';
                DB::table('exercises')->where('id',1455)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1769)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'On met une majuscule au debut d\'une PHRASE et aux noms propres (Cameroun, Pierre).';
                DB::table('exercises')->where('id',1769)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1760)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Futur de finir: je finirai, tu finiras, il finira, nous FINIRONS.';
                DB::table('exercises')->where('id',1760)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1763)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Synonyme de rapide = VITE ou RAPIDE. Aussi: prompt, vif.';
                DB::table('exercises')->where('id',1763)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1764)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Antonyme de fort = FAIBLE. Fort/faible, grand/petit, chaud/froid.';
                DB::table('exercises')->where('id',1764)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1765)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Famille de jardin: jardinage, jardinier, jardiner. Ils partagent la racine JARDIN.';
                DB::table('exercises')->where('id',1765)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1766)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Un texte qui explique comment faire = texte INJONCTIF ou procédural.';
                DB::table('exercises')->where('id',1766)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1768)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Une question se termine par un POINT D\'INTERROGATION (?).';
                DB::table('exercises')->where('id',1768)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',233)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Chat est masculin: LE chat. La chatte (feminin). LE chat est dans la maison.';
                DB::table('exercises')->where('id',233)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',234)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Des chats = PLUSIEURS chats. Le pluriel ajoute -s en general.';
                DB::table('exercises')->where('id',234)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',235)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Nous SOMMES des eleves. Etre: je suis, tu es, il est, nous SOMMES.';
                DB::table('exercises')->where('id',235)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',316)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Chat est masculin: LE chat.';
                DB::table('exercises')->where('id',316)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',317)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Des chats = plusieurs chats.';
                DB::table('exercises')->where('id',317)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',318)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Nous SOMMES des eleves.';
                DB::table('exercises')->where('id',318)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',399)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Chat est masculin: LE chat.';
                DB::table('exercises')->where('id',399)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',400)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Des chats = plusieurs chats.';
                DB::table('exercises')->where('id',400)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',401)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Nous SOMMES des eleves.';
                DB::table('exercises')->where('id',401)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',403)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'La fille est BELLE ou GRANDE ou INTELLIGENTE. Adjectif qualificatif feminin.';
                DB::table('exercises')->where('id',403)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1756)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'COD = Complement d\'Objet Direct. \'Je mange UNE POMME\' — la pomme est mangee directement.';
                DB::table('exercises')->where('id',1756)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1757)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'COI = Complement d\'Objet Indirect. \'Elle parle A SON AMI\' — a = preposition indirecte.';
                DB::table('exercises')->where('id',1757)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1759)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Futur de aller: je irai, tu iras, elle IRA a l\'ecole.';
                DB::table('exercises')->where('id',1759)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',122)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'On va chercher ses medicaments a la PHARMACIE.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = 'On depose son argent a la BANQUE.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = 'On prend le bus a la GARE ROUTIERE.';
                if (isset($c['questions'][3])) $c['questions'][3]['explanation'] = 'On va consulter un medecin au DISPENSAIRE ou a l\'hopital.';
                DB::table('exercises')->where('id',122)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',124)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Synonyme de content = HEUREUX. Mots de meme sens.';
                if (isset($c['questions'][1])) $c['questions'][1]['explanation'] = 'Synonyme de rapide = VITE ou PROMPT.';
                if (isset($c['questions'][2])) $c['questions'][2]['explanation'] = 'Synonyme de commencer = DEBUTER ou ENTAMER.';
                if (isset($c['questions'][3])) $c['questions'][3]['explanation'] = 'Synonyme de difficile = ARDU ou COMPLIQUE.';
                DB::table('exercises')->where('id',124)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',437)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Malaria is spread by the bite of an infected MOSQUITO (female Anopheles).';
                DB::table('exercises')->where('id',437)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',438)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cholera is caused by drinking CONTAMINATED (dirty/polluted) water.';
                DB::table('exercises')->where('id',438)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',441)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Typhoid is an INFECTIOUS disease spread through contaminated food and water.';
                DB::table('exercises')->where('id',441)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',442)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'PROTEINS: fish, meat, eggs, beans, groundnuts. They help the body grow.';
                DB::table('exercises')->where('id',442)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',443)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Vitamin C: oranges, lemons, tomatoes, peppers. Prevents scurvy.';
                DB::table('exercises')->where('id',443)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',448)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A seed needs WATER, warmth and air to germinate. Sunlight helps later.';
                DB::table('exercises')->where('id',448)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',446)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Photosynthesis: sunlight + water + CO2 (CARBON DIOXIDE) = glucose + oxygen.';
                DB::table('exercises')->where('id',446)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',449)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A SEESAW (or crowbar or scissors) is an example of a lever.';
                DB::table('exercises')->where('id',449)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',450)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A RAMP (inclined plane) makes it easier to move objects to higher levels.';
                DB::table('exercises')->where('id',450)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',432)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The skull protects the BRAIN — the most important organ in our body.';
                DB::table('exercises')->where('id',432)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',431)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'An adult human body has 206 BONES. Babies are born with about 270.';
                DB::table('exercises')->where('id',431)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',436)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Taste buds are found on the TONGUE. They detect sweet, sour, salty, bitter.';
                DB::table('exercises')->where('id',436)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',722)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'INPUT devices put data INTO the computer: keyboard, mouse, scanner, microphone.';
                DB::table('exercises')->where('id',722)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',723)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'OUTPUT devices take data OUT of the computer: monitor, printer, speakers.';
                DB::table('exercises')->where('id',723)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',734)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'To protect your eyes: take REGULAR BREAKS (every 20 minutes). Use the 20-20-20 rule.';
                DB::table('exercises')->where('id',734)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',731)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Facebook, WhatsApp, Twitter, Instagram and TikTok are social media platforms.';
                DB::table('exercises')->where('id',731)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',727)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Ctrl+B = BOLD. Ctrl+I = Italic. Ctrl+U = Underline.';
                DB::table('exercises')->where('id',727)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',728)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Ctrl+S = SAVE. Always save your work frequently!';
                DB::table('exercises')->where('id',728)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',729)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Ctrl+V = PASTE. Ctrl+C = Copy. Ctrl+X = Cut.';
                DB::table('exercises')->where('id',729)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',745)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'GERMANY first colonised Cameroon in 1884 (Treaty of Protectorate).';
                DB::table('exercises')->where('id',745)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',746)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'RUDOLF MANGA BELL (Douala chief) and Sultan Njoya resisted German rule.';
                DB::table('exercises')->where('id',746)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',748)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The mayor is the head of the TOWN COUNCIL (local government).';
                DB::table('exercises')->where('id',748)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',749)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Citizens vote to choose their LEADERS (representatives and president).';
                DB::table('exercises')->where('id',749)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',751)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The best way to solve a conflict is through DIALOGUE (talking peacefully).';
                DB::table('exercises')->where('id',751)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',753)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'UNESCO promotes EDUCATION, science and culture worldwide.';
                DB::table('exercises')->where('id',753)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',754)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Tolerance means ACCEPTING people who are different from us.';
                DB::table('exercises')->where('id',754)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1347)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Germany colonised Cameroon in 1884 at the Berlin Conference.';
                DB::table('exercises')->where('id',1347)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1350)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'After WWI (1916), Cameroon was divided between FRANCE and BRITAIN.';
                DB::table('exercises')->where('id',1350)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1348)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'RUDOLF MANGA BELL was hanged by Germans in 1914 for resisting expropriation.';
                DB::table('exercises')->where('id',1348)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1351)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The head of state in Cameroon is the PRESIDENT (currently Paul Biya).';
                DB::table('exercises')->where('id',1351)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1352)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The law-making body = NATIONAL ASSEMBLY (Parliament). Senators + Deputies.';
                DB::table('exercises')->where('id',1352)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1344)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The coastal region borders the ATLANTIC OCEAN (Gulf of Guinea).';
                DB::table('exercises')->where('id',1344)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1343)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cameroon has 10 REGIONS, each with a Governor and regional capital.';
                DB::table('exercises')->where('id',1343)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1345)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The Adamawa plateau is in the ADAMAWA region (centre-north, capital Ngaoundere).';
                DB::table('exercises')->where('id',1345)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1416)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The LELA festival is celebrated in the NORTH WEST region (Bali-Nyonga).';
                DB::table('exercises')->where('id',1416)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1415)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The NGONDO festival is celebrated by the SAWA (Douala) people on the Wouri.';
                DB::table('exercises')->where('id',1415)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1408)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Cultural heritage includes music, dances, ceremonies, buildings and traditions.';
                DB::table('exercises')->where('id',1408)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1409)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'UNESCO helps to PROTECT and PRESERVE world cultural and natural heritage.';
                DB::table('exercises')->where('id',1409)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1414)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'FULFULDE is spoken mainly by the FULANI (Peul) people of northern Cameroon.';
                DB::table('exercises')->where('id',1414)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1412)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The Shumom script was created by Sultan Njoya around 1896-1903.';
                DB::table('exercises')->where('id',1412)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1411)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Sultan Njoya invented the SHUMOM (Bamoun) script — one of few African writing systems.';
                DB::table('exercises')->where('id',1411)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1509)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Drawing objects smaller in the distance creates the illusion of DEPTH/PERSPECTIVE.';
                DB::table('exercises')->where('id',1509)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1510)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Warm colours: RED, ORANGE, YELLOW. They remind us of fire and the sun.';
                DB::table('exercises')->where('id',1510)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1512)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Batik is a method of decorating FABRIC (cloth) using wax-resist dyeing.';
                DB::table('exercises')->where('id',1512)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2076)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'BEADWORK and weaving are traditional crafts of the Grassfields (West/NW).';
                DB::table('exercises')->where('id',2076)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2077)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The complementary colour of blue on the colour wheel is ORANGE.';
                DB::table('exercises')->where('id',2077)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1513)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A 100m sprint is a SHORT distance race requiring maximum speed.';
                DB::table('exercises')->where('id',1513)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1514)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In the high jump, athletes jump over a HORIZONTAL BAR/CROSSBAR.';
                DB::table('exercises')->where('id',1514)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1516)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Athletes need CARBOHYDRATES (rice, pasta, yam) for energy during sport.';
                DB::table('exercises')->where('id',1516)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2113)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'In cricket, each team has 10 WICKETS. A team is all out after 10 wickets fall.';
                DB::table('exercises')->where('id',2113)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',2112)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The SHOT PUT involves throwing a heavy metal ball from the shoulder.';
                DB::table('exercises')->where('id',2112)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1668)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Sunshine = SUN + SHINE. Two words joined together = compound word.';
                DB::table('exercises')->where('id',1668)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1669)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = '\'Break a leg\' = idiom meaning GOOD LUCK. Not literal!';
                DB::table('exercises')->where('id',1669)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1670)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Re- means AGAIN. Rewrite = write again. Redo, replay, return, repeat.';
                DB::table('exercises')->where('id',1670)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1664)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The text says Yaounde is in the CENTRE REGION. Read carefully!';
                DB::table('exercises')->where('id',1664)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1665)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'The farmer woke at 5am, fed animals, then went to his farm = morning ROUTINE.';
                DB::table('exercises')->where('id',1665)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1666)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'A passage about taking care of teeth is mainly about DENTAL HEALTH.';
                DB::table('exercises')->where('id',1666)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1672)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Past tense: She FINISHED/COMPLETED her homework before dinner.';
                DB::table('exercises')->where('id',1672)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1673)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'Future tense: Tomorrow, he WILL VISIT his grandmother.';
                DB::table('exercises')->where('id',1673)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1741)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'When starting a new paragraph, you should INDENT (leave a small space/margin).';
                DB::table('exercises')->where('id',1741)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        $ex = DB::table('exercises')->where('id',1710)->first();
        if ($ex) {
            $c = json_decode($ex->content, true);
            if (isset($c['questions'])) {
                if (isset($c['questions'][0])) $c['questions'][0]['explanation'] = 'When starting a new paragraph, you should INDENT the first line.';
                DB::table('exercises')->where('id',1710)->update(['content'=>json_encode($c,JSON_UNESCAPED_UNICODE),'updated_at'=>now()]);
                $updated++;
            }
        }

        echo 'MarkC4Other: ' . $updated . ' exercises updated.' . PHP_EOL;
    }
}