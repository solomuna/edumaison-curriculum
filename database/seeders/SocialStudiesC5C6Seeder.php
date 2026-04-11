<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialStudiesC5C6Seeder extends Seeder
{
    public function run(): void
    {
        $levels = [9 => 'Class 5', 10 => 'Class 6'];
        foreach ($levels as $level_id => $level_name) {
            $subject = DB::table('subjects')->where('level_id', $level_id)->where('name', 'Social Studies')->first();
            if (!$subject) {
                $subject_id = DB::table('subjects')->insertGetId(['name'=>'Social Studies','slug'=>'social-studies-'.$level_id,'level_id'=>$level_id,'order'=>0,'is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);
            } else { $subject_id = $subject->id; }
            $ilt = DB::table('integrated_themes')->where('subject_id', $subject_id)->first();
            if (!$ilt) {
                $ilt_id = DB::table('integrated_themes')->insertGetId(['name'=>'Society and the World','slug'=>'society-world-'.$level_id,'subject_id'=>$subject_id,'order'=>0,'created_at'=>now(),'updated_at'=>now()]);
            } else { $ilt_id = $ilt->id; }
            $units_data[$level_id] = ['subject_id'=>$subject_id,'ilt_id'=>$ilt_id];
        }

        // Unit 1 History (level 9)
        $ilt_id = $units_data[9]['ilt_id'];
        $unit = DB::table('units')->where('name',"Unit 1 History")->where('integrated_theme_id',$ilt_id)->first();
        if (!$unit) {
            $unit_id = DB::table('units')->insertGetId(['name'=>"Unit 1 History",'slug'=>"unit-1-history-9",'integrated_theme_id'=>$ilt_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $unit_id = $unit->id; }
        $lesson = DB::table('lessons')->where('name',"History C5")->where('unit_id',$unit_id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"History C5",'slug'=>"history-c5",'unit_id'=>$unit_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_9_1 = [
            ['title'=>"Ancient Egypt: The Nile",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What river was essential to Ancient Egypt?\", \"options\": [\"The Congo\", \"The Nile\", \"The Niger\", \"The Zambezi\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Egyptian Rulers",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What was the title of Ancient Egypt's rulers?\", \"options\": [\"Kings\", \"Emperors\", \"Pharaohs\", \"Sultans\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Athens and Democracy",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which city-state is the birthplace of democracy?\", \"options\": [\"Sparta\", \"Corinth\", \"Troy\", \"Athens\"], \"answer\": 3}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Roman Empire",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The Roman Empire covered parts of which continents?\", \"options\": [\"Africa and Asia only\", \"Europe only\", \"Europe, Africa and Asia\", \"America and Europe\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"The Slave Trade",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What was the Atlantic slave trade?\", \"options\": [\"Trading goods between countries\", \"Buying and selling human beings\", \"Exchanging animals\", \"Selling land\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Slave Trade Consequences",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"A major consequence of the slave trade for Africa was:\", \"options\": [\"Population growth\", \"Economic development\", \"Loss of millions of people\", \"Stronger armies\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Germany in Cameroon",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"When did Germany colonise Cameroon?\", \"options\": [\"1804\", \"1884\", \"1914\", \"1945\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Rudolf Manga Bell",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which leader was hanged by Germans for resisting colonial rule?\", \"options\": [\"Sultan Njoya\", \"Chief Atangana\", \"Rudolf Manga Bell\", \"Chief Galega II\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Sultan Njoya",'content'=>"{\"type\": \"true_false\", \"statement\": \"Sultan Njoya invented his own writing system called Bamoun script.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Chief Atangana",'content'=>"{\"type\": \"true_false\", \"statement\": \"Chief Atangana collaborated with the German colonial administration.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Slave Trade Routes",'content'=>"{\"type\": \"true_false\", \"statement\": \"Most enslaved Africans were taken to Europe to work on plantations.\", \"answer\": false}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Ancient Greece Location",'content'=>"{\"type\": \"true_false\", \"statement\": \"Ancient Greece was located in southern Europe.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Ancient Egypt",'content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The ancient Egyptians built ___ as tombs for their pharaohs.\", \"answer\": \"pyramids\"}, {\"sentence\": \"The Nile flows northward into the ___ Sea.\", \"answer\": \"Mediterranean\"}]}",'category'=>'reading','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: German Colonisation",'content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"Germany colonised Cameroon in ___.\", \"answer\": \"1884\"}, {\"sentence\": \"Rudolf Manga Bell was a resistance leader from the ___ people.\", \"answer\": \"Duala\"}]}",'category'=>'reading','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Civilisations",'content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Athens\", \"right\": \"Birthplace of democracy\"}, {\"left\": \"Rome\", \"right\": \"Built an empire across Europe\"}, {\"left\": \"Egypt\", \"right\": \"Land of pharaohs and pyramids\"}, {\"left\": \"Njoya\", \"right\": \"Invented Bamoun script\"}]}",'category'=>'vocabulary','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_9_1 as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

        // Unit 2 Geography (level 9)
        $ilt_id = $units_data[9]['ilt_id'];
        $unit = DB::table('units')->where('name',"Unit 2 Geography")->where('integrated_theme_id',$ilt_id)->first();
        if (!$unit) {
            $unit_id = DB::table('units')->insertGetId(['name'=>"Unit 2 Geography",'slug'=>"unit-2-geography-9",'integrated_theme_id'=>$ilt_id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
        } else { $unit_id = $unit->id; }
        $lesson = DB::table('lessons')->where('name',"Geography C5")->where('unit_id',$unit_id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Geography C5",'slug'=>"geography-c5",'unit_id'=>$unit_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_9_2 = [
            ['title'=>"Cameroon Location",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Where is Cameroon located in Africa?\", \"options\": [\"East Africa\", \"Central/West Africa\", \"Southern Africa\", \"North Africa\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Capital City",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What is the capital city of Cameroon?\", \"options\": [\"Douala\", \"Bamenda\", \"Garoua\", \"Yaounde\"], \"answer\": 3}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Climate Zones",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"How many main climate zones does Cameroon have?\", \"options\": [\"2\", \"3\", \"4\", \"5\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Highest Peak",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What is the highest mountain in Cameroon?\", \"options\": [\"Mount Oku\", \"Mandara Mountains\", \"Mount Cameroon\", \"Adamawa Plateau\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Export Crop",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which is Cameroon's most important export crop?\", \"options\": [\"Rice\", \"Cocoa\", \"Wheat\", \"Sugarcane\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Sahel Region",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which region has a dry semi-desert climate?\", \"options\": [\"South Region\", \"Littoral Region\", \"Far North Region\", \"West Region\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Southern Climate",'content'=>"{\"type\": \"true_false\", \"statement\": \"The southern part of Cameroon has a tropical rainforest climate.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Africa in Miniature",'content'=>"{\"type\": \"true_false\", \"statement\": \"Cameroon is called 'Africa in miniature' because of its diverse landscapes.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Capital Confusion",'content'=>"{\"type\": \"true_false\", \"statement\": \"Douala is the political capital of Cameroon.\", \"answer\": false}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Lake Chad",'content'=>"{\"type\": \"true_false\", \"statement\": \"Lake Chad is located in the northern part of Cameroon.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Rivers",'content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The ___ River is one of the major rivers in Cameroon.\", \"answer\": \"Sanaga\"}, {\"sentence\": \"Cameroon is bordered by ___ countries.\", \"answer\": \"six\"}]}",'category'=>'reading','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Crops",'content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"Coffee and ___ are Cameroon's main export crops.\", \"answer\": \"cocoa\"}, {\"sentence\": \"The ___ region has the most fertile land for farming.\", \"answer\": \"West\"}]}",'category'=>'reading','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Regions",'content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Far North\", \"right\": \"Sahel climate, Lake Chad\"}, {\"left\": \"South\", \"right\": \"Rainforest, border with Gabon\"}, {\"left\": \"West\", \"right\": \"Fertile highlands, coffee\"}, {\"left\": \"Littoral\", \"right\": \"Douala, Atlantic coast\"}]}",'category'=>'vocabulary','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Longest Border",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which country shares the longest border with Cameroon?\", \"options\": [\"Nigeria\", \"Chad\", \"Central African Republic\", \"Gabon\"], \"answer\": 0}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Natural Resources",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which natural resource is found offshore in Cameroon?\", \"options\": [\"Diamonds\", \"Gold\", \"Petroleum\", \"Copper\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_9_2 as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

        // Unit 3 Citizenship (level 9)
        $ilt_id = $units_data[9]['ilt_id'];
        $unit = DB::table('units')->where('name',"Unit 3 Citizenship")->where('integrated_theme_id',$ilt_id)->first();
        if (!$unit) {
            $unit_id = DB::table('units')->insertGetId(['name'=>"Unit 3 Citizenship",'slug'=>"unit-3-citizenship-9",'integrated_theme_id'=>$ilt_id,'order'=>3,'created_at'=>now(),'updated_at'=>now()]);
        } else { $unit_id = $unit->id; }
        $lesson = DB::table('lessons')->where('name',"Citizenship C5")->where('unit_id',$unit_id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Citizenship C5",'slug'=>"citizenship-c5",'unit_id'=>$unit_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_9_3 = [
            ['title'=>"Flag Colours",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What are the colours of Cameroon's flag?\", \"options\": [\"Blue, white, red\", \"Green, red, yellow\", \"Black, red, green\", \"Yellow, green, blue\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"National Anthem",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What is Cameroon's national anthem title?\", \"options\": [\"O Cameroon Cradle of Our Forefathers\", \"Stand and Sing of Cameroon\", \"Chant de Ralliement\", \"All of the above\"], \"answer\": 3}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Head of State",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What is the head of government in Cameroon?\", \"options\": [\"King\", \"President\", \"Prime Minister\", \"Governor\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Parliament Name",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What is Cameroon's parliament called?\", \"options\": [\"National Congress\", \"National Assembly\", \"Senate only\", \"House of Representatives\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Children Rights",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which is a basic right of every child?\", \"options\": [\"Right to drive\", \"Right to vote\", \"Right to education\", \"Right to own property\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Rule of Law",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What does 'rule of law' mean?\", \"options\": [\"Only rulers obey the law\", \"Everyone including leaders must obey the law\", \"Laws apply to poor people only\", \"Police can do anything\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Flag Star",'content'=>"{\"type\": \"true_false\", \"statement\": \"The star on Cameroon's flag is green.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Child Protection",'content'=>"{\"type\": \"true_false\", \"statement\": \"Children have the right to be protected from violence and abuse.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Presidential Term",'content'=>"{\"type\": \"true_false\", \"statement\": \"The President of Cameroon is elected every 5 years.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Rights for All",'content'=>"{\"type\": \"true_false\", \"statement\": \"Only adults have human rights.\", \"answer\": false}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Symbols",'content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The ___ on Cameroon's flag represents unity.\", \"answer\": \"star\"}, {\"sentence\": \"Cameroon's national day is celebrated on ___ May.\", \"answer\": \"20\"}]}",'category'=>'reading','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: UNICEF",'content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"Every child has the right to ___ and to learn.\", \"answer\": \"education\"}, {\"sentence\": \"The UN organisation that protects children is called ___.\", \"answer\": \"UNICEF\"}]}",'category'=>'reading','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Government",'content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"President\", \"right\": \"Head of State\"}, {\"left\": \"National Assembly\", \"right\": \"Makes laws\"}, {\"left\": \"Courts\", \"right\": \"Apply justice\"}, {\"left\": \"Police\", \"right\": \"Maintain order\"}]}",'category'=>'vocabulary','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Civic Duty",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which is a civic duty of every citizen?\", \"options\": [\"Avoiding taxes\", \"Paying taxes and obeying laws\", \"Ignoring community rules\", \"Only working for yourself\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Conflict Resolution",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The best way to resolve conflicts peacefully is:\", \"options\": [\"Fighting\", \"Dialogue and negotiation\", \"Ignoring the problem\", \"Involving only one side\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_9_3 as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

        // Unit 1 History (level 10)
        $ilt_id = $units_data[10]['ilt_id'];
        $unit = DB::table('units')->where('name',"Unit 1 History")->where('integrated_theme_id',$ilt_id)->first();
        if (!$unit) {
            $unit_id = DB::table('units')->insertGetId(['name'=>"Unit 1 History",'slug'=>"unit-1-history-10",'integrated_theme_id'=>$ilt_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $unit_id = $unit->id; }
        $lesson = DB::table('lessons')->where('name',"History C6")->where('unit_id',$unit_id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"History C6",'slug'=>"history-c6",'unit_id'=>$unit_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_10_1 = [
            ['title'=>"WWII Cause",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"A main cause of World War II was:\", \"options\": [\"A volcanic eruption\", \"Rise of Nazi Germany under Hitler\", \"A trade dispute over tea\", \"An earthquake\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"WWII Years",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"World War II took place from:\", \"options\": [\"1914-1918\", \"1939-1945\", \"1950-1953\", \"1929-1935\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"UN Founded",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"When was the United Nations founded?\", \"options\": [\"1918\", \"1939\", \"1945\", \"1960\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"UN Purpose",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The main purpose of the UN is:\", \"options\": [\"To run a world government\", \"To promote peace and international cooperation\", \"To control world trade\", \"To manage global armies\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Cameroon Independence",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"When did French Cameroon gain independence?\", \"options\": [\"1960\", \"1961\", \"1972\", \"1884\"], \"answer\": 0}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Cameroon Reunification",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"When did Southern Cameroons reunite with French Cameroon?\", \"options\": [\"1960\", \"1961\", \"1965\", \"1972\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Atomic Bombs",'content'=>"{\"type\": \"true_false\", \"statement\": \"World War II ended with atomic bombs dropped on Japan.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"UN Security Council",'content'=>"{\"type\": \"true_false\", \"statement\": \"The UN Security Council has five permanent members.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Dual Colonisation",'content'=>"{\"type\": \"true_false\", \"statement\": \"Cameroon was colonised by France and Britain at the same time.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Northern Cameroons",'content'=>"{\"type\": \"true_false\", \"statement\": \"Northern Cameroons voted to join Nigeria after independence.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: WWII",'content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"World War II ended in ___.\", \"answer\": \"1945\"}, {\"sentence\": \"The United Nations was created to maintain international ___ and security.\", \"answer\": \"peace\"}]}",'category'=>'reading','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Independence",'content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"French Cameroon became independent on 1st January ___.\", \"answer\": \"1960\"}, {\"sentence\": \"The reunification of Cameroon took place in ___.\", \"answer\": \"1961\"}]}",'category'=>'reading','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Dates",'content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"1939\", \"right\": \"Start of World War II\"}, {\"left\": \"1945\", \"right\": \"End of WWII and UN founded\"}, {\"left\": \"1960\", \"right\": \"Cameroon independence\"}, {\"left\": \"1961\", \"right\": \"Cameroon reunification\"}]}",'category'=>'vocabulary','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Year of Africa",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The 1960s is called the 'Year of Africa' because:\", \"options\": [\"Many African countries gained independence\", \"Africa won the World Cup\", \"Africa discovered oil\", \"Many African cities were built\"], \"answer\": 0}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"The Holocaust",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What was the Holocaust?\", \"options\": [\"A natural disaster in Europe\", \"Systematic murder of six million Jews by Nazi Germany\", \"A war between France and Germany\", \"A famine in Eastern Europe\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_10_1 as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

        // Unit 2 Geography (level 10)
        $ilt_id = $units_data[10]['ilt_id'];
        $unit = DB::table('units')->where('name',"Unit 2 Geography")->where('integrated_theme_id',$ilt_id)->first();
        if (!$unit) {
            $unit_id = DB::table('units')->insertGetId(['name'=>"Unit 2 Geography",'slug'=>"unit-2-geography-10",'integrated_theme_id'=>$ilt_id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
        } else { $unit_id = $unit->id; }
        $lesson = DB::table('lessons')->where('name',"Geography C6")->where('unit_id',$unit_id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Geography C6",'slug'=>"geography-c6",'unit_id'=>$unit_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_10_2 = [
            ['title'=>"African Countries",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"How many countries are there in Africa?\", \"options\": [\"44\", \"54\", \"64\", \"74\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Largest in Africa",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The largest country in Africa by area is:\", \"options\": [\"Nigeria\", \"South Africa\", \"Algeria\", \"DR Congo\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"World Continents",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"How many continents are there in the world?\", \"options\": [\"5\", \"6\", \"7\", \"8\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Climate Change Cause",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The main human cause of climate change is:\", \"options\": [\"Farming animals\", \"Burning fossil fuels releasing CO2\", \"Building roads\", \"Planting trees\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Export Definition",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What does 'export' mean?\", \"options\": [\"Buying goods from another country\", \"Selling goods to another country\", \"Making goods locally\", \"Storing goods\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Transport Importance",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Good transport infrastructure is important because:\", \"options\": [\"It creates pollution\", \"It helps move goods and people efficiently\", \"It reduces farming\", \"It increases taxes\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Pacific Ocean",'content'=>"{\"type\": \"true_false\", \"statement\": \"The Pacific Ocean is the largest ocean in the world.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Africa Size",'content'=>"{\"type\": \"true_false\", \"statement\": \"Africa is the smallest continent in the world.\", \"answer\": false}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Deforestation",'content'=>"{\"type\": \"true_false\", \"statement\": \"Cutting down forests contributes to climate change.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Imports vs Exports",'content'=>"{\"type\": \"true_false\", \"statement\": \"Imports are goods a country sells to other countries.\", \"answer\": false}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Geography",'content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The ___ is the longest river in the world.\", \"answer\": \"Nile\"}, {\"sentence\": \"The continent with the most countries is ___.\", \"answer\": \"Africa\"}]}",'category'=>'reading','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Trade",'content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"Cameroon ___ cocoa and coffee to other countries.\", \"answer\": \"exports\"}, {\"sentence\": \"Countries that buy more than they sell have a trade ___.\", \"answer\": \"deficit\"}]}",'category'=>'reading','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Continents",'content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Africa\", \"right\": \"Most countries\"}, {\"left\": \"Asia\", \"right\": \"Largest by area\"}, {\"left\": \"Antarctica\", \"right\": \"Coldest continent\"}, {\"left\": \"South America\", \"right\": \"Home of Amazon rainforest\"}]}",'category'=>'vocabulary','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Migration",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Migration is:\", \"options\": [\"Building new cities\", \"Movement of people from one place to another\", \"Starting a new business\", \"Learning a new language\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Population Growth",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The fastest-growing population is on which continent?\", \"options\": [\"Europe\", \"Asia\", \"Africa\", \"North America\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_10_2 as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

        // Unit 3 Citizenship (level 10)
        $ilt_id = $units_data[10]['ilt_id'];
        $unit = DB::table('units')->where('name',"Unit 3 Citizenship")->where('integrated_theme_id',$ilt_id)->first();
        if (!$unit) {
            $unit_id = DB::table('units')->insertGetId(['name'=>"Unit 3 Citizenship",'slug'=>"unit-3-citizenship-10",'integrated_theme_id'=>$ilt_id,'order'=>3,'created_at'=>now(),'updated_at'=>now()]);
        } else { $unit_id = $unit->id; }
        $lesson = DB::table('lessons')->where('name',"Citizenship C6")->where('unit_id',$unit_id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Citizenship C6",'slug'=>"citizenship-c6",'unit_id'=>$unit_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_10_3 = [
            ['title'=>"UN Members",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"How many member states does the UN have?\", \"options\": [\"54\", \"100\", \"193\", \"250\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"UNESCO Meaning",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"UNESCO stands for:\", \"options\": [\"UN Economic Security Organisation\", \"UN Educational Scientific and Cultural Organization\", \"UN Emergency Support Council\", \"UN Environment and Social Committee\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"African Union",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The African Union (AU) was established in:\", \"options\": [\"1945\", \"1963 as OAU\", \"2002\", \"2010\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Human Rights Declaration",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The Universal Declaration of Human Rights was adopted in:\", \"options\": [\"1945\", \"1948\", \"1960\", \"1975\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Democratic Election",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"A democratic election means:\", \"options\": [\"A leader choosing their successor\", \"Citizens voting freely to choose leaders\", \"The army selecting a president\", \"A decision made by judges\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"ICJ Location",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The International Court of Justice is located in:\", \"options\": [\"New York\", \"Geneva\", \"The Hague\", \"Paris\"], \"answer\": 2}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Universal Rights",'content'=>"{\"type\": \"true_false\", \"statement\": \"The Universal Declaration of Human Rights applies to all people regardless of race or religion.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"AU Headquarters",'content'=>"{\"type\": \"true_false\", \"statement\": \"The African Union headquarters is in Addis Ababa, Ethiopia.\", \"answer\": true}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Voting Rights",'content'=>"{\"type\": \"true_false\", \"statement\": \"In a democracy, only rich people are allowed to vote.\", \"answer\": false}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Rights Permanence",'content'=>"{\"type\": \"true_false\", \"statement\": \"Human rights can be taken away if a government decides to.\", \"answer\": false}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: UN",'content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The United Nations headquarters is in New ___ City.\", \"answer\": \"York\"}, {\"sentence\": \"UNESCO protects world cultural and natural ___.\", \"answer\": \"heritage\"}]}",'category'=>'reading','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Democracy",'content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"In a democracy, power comes from the ___.\", \"answer\": \"people\"}, {\"sentence\": \"The right to vote is called ___ suffrage.\", \"answer\": \"universal\"}]}",'category'=>'reading','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Organisations",'content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"UN\", \"right\": \"Maintains world peace and security\"}, {\"left\": \"UNESCO\", \"right\": \"Promotes education and culture\"}, {\"left\": \"WHO\", \"right\": \"Promotes global health\"}, {\"left\": \"AU\", \"right\": \"Promotes African unity\"}]}",'category'=>'vocabulary','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Mediation",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Mediation in conflict resolution means:\", \"options\": [\"Fighting until one side wins\", \"A neutral third party helps both sides agree\", \"Ignoring the problem\", \"Using military force\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Rights and Responsibilities",'content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"A responsibility that comes with rights is:\", \"options\": [\"Taking more than your share\", \"Respecting the rights of others\", \"Breaking rules when convenient\", \"Demanding special treatment\"], \"answer\": 1}]}",'category'=>'quiz','difficulty'=>'medium','is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_10_3 as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

    }
}