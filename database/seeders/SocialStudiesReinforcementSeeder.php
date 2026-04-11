<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialStudiesReinforcementSeeder extends Seeder
{
    public function run(): void
    {
        // History C5 Reinforcement
        $subject = DB::table('subjects')->where('level_id',9)->where('name','Social Studies')->first();
        if (!$subject) { echo 'Social Studies not found for level 9' . PHP_EOL; return; }
        $ilt = DB::table('integrated_themes')->where('subject_id',$subject->id)->first();
        $unit = DB::table('units')->where('name',"Unit 1 History")->where('integrated_theme_id',$ilt->id)->first();
        if (!$unit) { echo 'Unit not found: Unit 1 History' . PHP_EOL; return; }
        $lesson = DB::table('lessons')->where('name',"History C5 Reinforcement")->where('unit_id',$unit->id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"History C5 Reinforcement",'slug'=>"history-c5-reinforcement",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_9_unit_1_history_r = [
            ['title'=>"Match: Ancient Leaders",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Julius Caesar\", \"right\": \"Roman general and statesman\"}, {\"left\": \"Cleopatra\", \"right\": \"Last pharaoh of Egypt\"}, {\"left\": \"Njoya\", \"right\": \"Invented Bamoun script\"}, {\"left\": \"Manga Bell\", \"right\": \"Resistance leader hanged by Germans\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Slavery",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"Enslaved Africans were transported to the Americas during the ___ trade.\", \"answer\": \"slave\"}, {\"sentence\": \"The transatlantic slave trade lasted approximately ___ years.\", \"answer\": \"400\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Cameroon Resistance",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"Rudolf Manga Bell was executed by the Germans in ___.\", \"answer\": \"1914\"}, {\"sentence\": \"The Douala chiefs resisted German plans to ___ their land.\", \"answer\": \"expropriate\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Roman Empire",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"The Roman Empire was the largest empire in world history at its peak.\", \"answer\": false}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Egyptian Pyramids",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"The Great Pyramid of Giza was built as a tomb for Pharaoh Khufu.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Slave Trade Duration",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"The transatlantic slave trade lasted for about 400 years.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Sultan Njoya",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Sultan Njoya of Bamoun was from the Far North region of Cameroon.\", \"answer\": false}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"MCQ: End of German Rule",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Germany lost its colonies in Cameroon after which event?\", \"options\": \"[\\\"World War I\\\",\\\"World War II\\\",\\\"The Berlin Conference\\\",\\\"The French Revolution\\\"]\", \"answer\": 0}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"MCQ: Greek Democracy",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"In Ancient Athens, who was allowed to vote?\", \"options\": \"[\\\"All citizens including women\\\",\\\"Male citizens only\\\",\\\"Everyone including slaves\\\",\\\"Only the wealthy\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Historical Dates",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"1884\", \"right\": \"Germany colonises Cameroon\"}, {\"left\": \"1914\", \"right\": \"Rudolf Manga Bell executed\"}, {\"left\": \"44 BC\", \"right\": \"Julius Caesar assassinated\"}, {\"left\": \"30 BC\", \"right\": \"End of Egyptian pharaohs\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_9_unit_1_history_r as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

        // Geography C5 Reinforcement
        $subject = DB::table('subjects')->where('level_id',9)->where('name','Social Studies')->first();
        if (!$subject) { echo 'Social Studies not found for level 9' . PHP_EOL; return; }
        $ilt = DB::table('integrated_themes')->where('subject_id',$subject->id)->first();
        $unit = DB::table('units')->where('name',"Unit 2 Geography")->where('integrated_theme_id',$ilt->id)->first();
        if (!$unit) { echo 'Unit not found: Unit 2 Geography' . PHP_EOL; return; }
        $lesson = DB::table('lessons')->where('name',"Geography C5 Reinforcement")->where('unit_id',$unit->id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Geography C5 Reinforcement",'slug'=>"geography-c5-reinforcement",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_9_unit_2_geography_r = [
            ['title'=>"Match: Cameroon Cities",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Yaounde\", \"right\": \"Political capital\"}, {\"left\": \"Douala\", \"right\": \"Economic capital\"}, {\"left\": \"Bafoussam\", \"right\": \"Capital of West Region\"}, {\"left\": \"Garoua\", \"right\": \"Capital of North Region\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Physical Geography",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The ___ Plateau covers much of central Cameroon.\", \"answer\": \"Adamawa\"}, {\"sentence\": \"Cameroon's coastline borders the ___ Ocean.\", \"answer\": \"Atlantic\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Climate",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"Cameroon has ___ main climate zones.\", \"answer\": \"four\"}, {\"sentence\": \"The wettest location near Mount Cameroon is ___.\", \"answer\": \"Debundscha\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Borders",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Cameroon shares a border with six countries.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Congo Basin",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"The Congo Basin rainforest extends into southern Cameroon.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Largest in Central Africa",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Cameroon is the largest country in Central Africa.\", \"answer\": false}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Volcano",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Mount Cameroon is an active volcano.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"MCQ: Adamawa Location",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The Adamawa Plateau is located in which part of Cameroon?\", \"options\": \"[\\\"Southern\\\",\\\"Western\\\",\\\"Central-Northern\\\",\\\"Eastern\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"MCQ: Second Export",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Apart from cocoa, which is another major export crop of Cameroon?\", \"options\": \"[\\\"Diamonds\\\",\\\"Timber\\\",\\\"Coffee\\\",\\\"Gold\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Climate Zones",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Equatorial\", \"right\": \"Hot and wet all year, south\"}, {\"left\": \"Tropical\", \"right\": \"Wet and dry seasons, centre\"}, {\"left\": \"Sudanian\", \"right\": \"Short rainy season, north\"}, {\"left\": \"Sahelian\", \"right\": \"Very dry, Far North\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_9_unit_2_geography_r as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

        // Citizenship C5 Reinforcement
        $subject = DB::table('subjects')->where('level_id',9)->where('name','Social Studies')->first();
        if (!$subject) { echo 'Social Studies not found for level 9' . PHP_EOL; return; }
        $ilt = DB::table('integrated_themes')->where('subject_id',$subject->id)->first();
        $unit = DB::table('units')->where('name',"Unit 3 Citizenship")->where('integrated_theme_id',$ilt->id)->first();
        if (!$unit) { echo 'Unit not found: Unit 3 Citizenship' . PHP_EOL; return; }
        $lesson = DB::table('lessons')->where('name',"Citizenship C5 Reinforcement")->where('unit_id',$unit->id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Citizenship C5 Reinforcement",'slug'=>"citizenship-c5-reinforcement",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_9_unit_3_citizenship_r = [
            ['title'=>"Match: Flag Colors",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Green\", \"right\": \"Forests and hope\"}, {\"left\": \"Red\", \"right\": \"Unity\"}, {\"left\": \"Yellow\", \"right\": \"Sun and savannah\"}, {\"left\": \"Star\", \"right\": \"Unity of the nation\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Government",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The President of Cameroon is elected for a term of ___ years.\", \"answer\": \"seven\"}, {\"sentence\": \"The ___ is responsible for making laws in Cameroon.\", \"answer\": \"National Assembly\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Children Rights",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"Every child has the right to a ___ to know their identity.\", \"answer\": \"name\"}, {\"sentence\": \"The UN body that protects children internationally is ___.\", \"answer\": \"UNICEF\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Democracy",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Cameroon is a democratic republic with a multi-party system.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Child Labour",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Children have the right to work full-time from age 12.\", \"answer\": false}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: National Day",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Cameroon's National Day is celebrated on 20th May.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: National Assembly",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"The National Assembly of Cameroon meets in Yaounde.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"MCQ: Civic Duty",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which is the most important civic responsibility?\", \"options\": \"[\\\"Paying taxes only\\\",\\\"Voting and obeying the law\\\",\\\"Criticising the government\\\",\\\"Avoiding community service\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"MCQ: Flag Star",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The star on Cameroon's flag represents:\", \"options\": \"[\\\"The sun\\\",\\\"Unity of the country\\\",\\\"The mountains\\\",\\\"Independence\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Government Branches",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Executive\", \"right\": \"Implements laws, led by President\"}, {\"left\": \"Legislative\", \"right\": \"Makes laws, National Assembly\"}, {\"left\": \"Judiciary\", \"right\": \"Interprets laws, Supreme Court\"}, {\"left\": \"Local councils\", \"right\": \"Manages regions\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_9_unit_3_citizenship_r as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

        // History C6 Reinforcement
        $subject = DB::table('subjects')->where('level_id',10)->where('name','Social Studies')->first();
        if (!$subject) { echo 'Social Studies not found for level 10' . PHP_EOL; return; }
        $ilt = DB::table('integrated_themes')->where('subject_id',$subject->id)->first();
        $unit = DB::table('units')->where('name',"Unit 1 History")->where('integrated_theme_id',$ilt->id)->first();
        if (!$unit) { echo 'Unit not found: Unit 1 History' . PHP_EOL; return; }
        $lesson = DB::table('lessons')->where('name',"History C6 Reinforcement")->where('unit_id',$unit->id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"History C6 Reinforcement",'slug'=>"history-c6-reinforcement",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_10_unit_1_history_r = [
            ['title'=>"Match: WWII Leaders",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Churchill\", \"right\": \"British Prime Minister during WWII\"}, {\"left\": \"Hitler\", \"right\": \"Leader of Nazi Germany\"}, {\"left\": \"Roosevelt\", \"right\": \"US President during WWII\"}, {\"left\": \"Stalin\", \"right\": \"Soviet leader during WWII\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: United Nations",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The UN was founded in ___ Francisco in 1945.\", \"answer\": \"San\"}, {\"sentence\": \"The UN Security Council has ___ permanent members.\", \"answer\": \"five\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Cameroon Independence",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The first President of independent Cameroon was ___.\", \"answer\": \"Ahmadou Ahidjo\"}, {\"sentence\": \"Cameroon became a unitary state after the referendum of ___.\", \"answer\": \"1972\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Reunification Vote",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Both Northern and Southern Cameroons voted to join French Cameroon in 1961.\", \"answer\": false}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: UN Charter",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"The UN Charter was signed by 51 founding member states.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Holocaust",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"The Holocaust caused the death of approximately six million Jewish people.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Federal Republic",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Cameroon was a Federal Republic from 1961 to 1972.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"MCQ: WWII End Europe",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"When did World War II end in Europe?\", \"options\": \"[\\\"May 1944\\\",\\\"May 1945\\\",\\\"August 1945\\\",\\\"September 1945\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"MCQ: Year of Africa",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which year is known as the Year of Africa?\", \"options\": \"[\\\"1955\\\",\\\"1960\\\",\\\"1963\\\",\\\"1970\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: WWII Timeline",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"1939\", \"right\": \"Germany invades Poland\"}, {\"left\": \"1941\", \"right\": \"Japan attacks Pearl Harbor\"}, {\"left\": \"1944\", \"right\": \"D-Day Normandy landings\"}, {\"left\": \"1945\", \"right\": \"Atomic bombs dropped on Japan\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_10_unit_1_history_r as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

        // Geography C6 Reinforcement
        $subject = DB::table('subjects')->where('level_id',10)->where('name','Social Studies')->first();
        if (!$subject) { echo 'Social Studies not found for level 10' . PHP_EOL; return; }
        $ilt = DB::table('integrated_themes')->where('subject_id',$subject->id)->first();
        $unit = DB::table('units')->where('name',"Unit 2 Geography")->where('integrated_theme_id',$ilt->id)->first();
        if (!$unit) { echo 'Unit not found: Unit 2 Geography' . PHP_EOL; return; }
        $lesson = DB::table('lessons')->where('name',"Geography C6 Reinforcement")->where('unit_id',$unit->id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Geography C6 Reinforcement",'slug'=>"geography-c6-reinforcement",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_10_unit_2_geography_r = [
            ['title'=>"Match: African Countries",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Nigeria\", \"right\": \"Most populous country in Africa\"}, {\"left\": \"Algeria\", \"right\": \"Largest country by area in Africa\"}, {\"left\": \"South Africa\", \"right\": \"Most industrialised in Africa\"}, {\"left\": \"Ethiopia\", \"right\": \"Second most populous in Africa\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: World Geography",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The Amazon River flows through ___.\", \"answer\": \"Brazil\"}, {\"sentence\": \"The ___ Desert is the largest hot desert in the world.\", \"answer\": \"Sahara\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Climate Change",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The main greenhouse gas causing climate change is ___ dioxide.\", \"answer\": \"carbon\"}, {\"sentence\": \"Cutting down forests is called ___.\", \"answer\": \"deforestation\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Nile River",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"The Nile River flows through both Sudan and Egypt.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Most Populated Continent",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Asia is the most populated continent in the world.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Renewable Energy",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Renewable energy sources like solar and wind help reduce climate change.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Trade Surplus",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"A country has a trade surplus when its exports exceed its imports.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"MCQ: Longest River Africa",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What is the longest river in Africa?\", \"options\": \"[\\\"Congo\\\",\\\"Niger\\\",\\\"Zambezi\\\",\\\"Nile\\\"]\", \"answer\": 3}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"MCQ: Population Growth",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The main reason for rapid population growth in Africa is:\", \"options\": \"[\\\"High birth rates\\\",\\\"Immigration from Europe\\\",\\\"Low death rates\\\",\\\"Economic growth\\\"]\", \"answer\": 0}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: World Records",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"China\", \"right\": \"Most populated country\"}, {\"left\": \"Russia\", \"right\": \"Largest country by area\"}, {\"left\": \"Vatican City\", \"right\": \"Smallest country in the world\"}, {\"left\": \"Tokyo\", \"right\": \"Largest city by population\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_10_unit_2_geography_r as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

        // Citizenship C6 Reinforcement
        $subject = DB::table('subjects')->where('level_id',10)->where('name','Social Studies')->first();
        if (!$subject) { echo 'Social Studies not found for level 10' . PHP_EOL; return; }
        $ilt = DB::table('integrated_themes')->where('subject_id',$subject->id)->first();
        $unit = DB::table('units')->where('name',"Unit 3 Citizenship")->where('integrated_theme_id',$ilt->id)->first();
        if (!$unit) { echo 'Unit not found: Unit 3 Citizenship' . PHP_EOL; return; }
        $lesson = DB::table('lessons')->where('name',"Citizenship C6 Reinforcement")->where('unit_id',$unit->id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Citizenship C6 Reinforcement",'slug'=>"citizenship-c6-reinforcement",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_10_unit_3_citizenship_r = [
            ['title'=>"Match: UN Agencies",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"UNICEF\", \"right\": \"Children's rights and welfare\"}, {\"left\": \"UNESCO\", \"right\": \"Education, Science and Culture\"}, {\"left\": \"WHO\", \"right\": \"World health promotion\"}, {\"left\": \"FAO\", \"right\": \"Food and Agriculture\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Human Rights",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The Universal Declaration of Human Rights has ___ articles.\", \"answer\": \"30\"}, {\"sentence\": \"Human rights are ___, meaning everyone is born with them.\", \"answer\": \"universal\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Democracy",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"In a democracy, citizens choose their leaders through ___.\", \"answer\": \"elections\"}, {\"sentence\": \"Freedom of ___ allows people to express their opinions.\", \"answer\": \"speech\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: African Union",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"The African Union replaced the Organisation of African Unity in 2002.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Economic Rights",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Economic rights such as the right to work are NOT human rights.\", \"answer\": false}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: Secret Ballot",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"A secret ballot protects voters from pressure when voting.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"TF: ICJ",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"The International Court of Justice settles disputes between countries.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"MCQ: Rule of Law",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which best describes the rule of law?\", \"options\": \"[\\\"Leaders are above the law\\\",\\\"Everyone must follow the same laws\\\",\\\"Only citizens follow laws\\\",\\\"Laws only apply to criminals\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"MCQ: AU Headquarters",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Where is the African Union headquarters?\", \"options\": \"[\\\"Nairobi, Kenya\\\",\\\"Lagos, Nigeria\\\",\\\"Addis Ababa, Ethiopia\\\",\\\"Cairo, Egypt\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Rights Categories",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Civil rights\", \"right\": \"Freedom of speech and religion\"}, {\"left\": \"Political rights\", \"right\": \"Right to vote and stand for election\"}, {\"left\": \"Economic rights\", \"right\": \"Right to work and fair wages\"}, {\"left\": \"Social rights\", \"right\": \"Right to education and healthcare\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_10_unit_3_citizenship_r as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

    }
}