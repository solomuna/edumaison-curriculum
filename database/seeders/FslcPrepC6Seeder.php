<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FslcPrepC6Seeder extends Seeder
{
    public function run(): void
    {
        // Find or create FSLC Preparation subject for C6 (level_id=10)
        $subject = DB::table('subjects')->where('level_id',10)->where('name','FSLC Preparation')->first();
        if (!$subject) {
            $subject_id = DB::table('subjects')->insertGetId(['name'=>'FSLC Preparation','slug'=>'fslc-preparation-10','level_id'=>10,'order'=>99,'is_active'=>true,'created_at'=>now(),'updated_at'=>now()]);
        } else { $subject_id = $subject->id; }
        $ilt = DB::table('integrated_themes')->where('subject_id',$subject_id)->first();
        if (!$ilt) {
            $ilt_id = DB::table('integrated_themes')->insertGetId(['name'=>'FSLC Exam Preparation','slug'=>'fslc-exam-prep','subject_id'=>$subject_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $ilt_id = $ilt->id; }

        // Unit 1 English Language
        $unit = DB::table('units')->where('name',"Unit 1 English Language")->where('integrated_theme_id',$ilt_id)->first();
        if (!$unit) {
            $unit_id = DB::table('units')->insertGetId(['name'=>"Unit 1 English Language",'slug'=>"unit-1-english-language-10",'integrated_theme_id'=>$ilt_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $unit_id = $unit->id; }
        $lesson = DB::table('lessons')->where('name',"English Language Revision")->where('unit_id',$unit_id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"English Language Revision",'slug'=>"english-language-revision",'unit_id'=>$unit_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_u1 = [
            ['title'=>"Nouns and Pronouns",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which word is a proper noun?\", \"options\": \"[\\\"cameroon\\\",\\\"city\\\",\\\"teacher\\\",\\\"book\\\"]\", \"answer\": 0}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Subject-Verb Agreement",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Choose the correct verb: The children ___ playing.\", \"options\": \"[\\\"is\\\",\\\"was\\\",\\\"are\\\",\\\"am\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Punctuation",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which sentence uses punctuation correctly?\", \"options\": [\"\\\"Where are you going?\\\"\", \"\\\"where are you going\\\"\", \"\\\"Where are you going\\\"\", \"\\\"where are you going?\\\"\"], \"answer\": 0}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Adjectives",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which word is an adjective in: The tall boy ran fast.\", \"options\": \"[\\\"boy\\\",\\\"ran\\\",\\\"tall\\\",\\\"fast\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Past Tense",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The past tense of 'write' is:\", \"options\": \"[\\\"writed\\\",\\\"wrote\\\",\\\"written\\\",\\\"writ\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Prepositions",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The book is ___ the table.\", \"options\": \"[\\\"on\\\",\\\"in\\\",\\\"of\\\",\\\"at\\\"]\", \"answer\": 0}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Conjunctions",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"I was tired ___ I kept working.\", \"options\": \"[\\\"so\\\",\\\"but\\\",\\\"or\\\",\\\"and\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Plural Forms",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The plural of 'child' is:\", \"options\": \"[\\\"childs\\\",\\\"childrens\\\",\\\"children\\\",\\\"child\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Comprehension",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"'The sun rises in the east.' This sentence tells us about:\", \"options\": \"[\\\"weather\\\",\\\"direction\\\",\\\"time\\\",\\\"seasons\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Synonyms",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"A synonym for 'happy' is:\", \"options\": \"[\\\"sad\\\",\\\"angry\\\",\\\"joyful\\\",\\\"tired\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Antonyms",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The opposite of 'ancient' is:\", \"options\": \"[\\\"old\\\",\\\"modern\\\",\\\"small\\\",\\\"dark\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Articles",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"We use 'an' before words that begin with a vowel sound.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Statements",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"A question always ends with a full stop.\", \"answer\": false}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Grammar",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The ___ of a sentence must agree with the verb.\", \"answer\": \"subject\"}, {\"sentence\": \"An ___ describes a noun.\", \"answer\": \"adjective\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Parts of Speech",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"quickly\", \"right\": \"Adverb\"}, {\"left\": \"beautiful\", \"right\": \"Adjective\"}, {\"left\": \"Cameroon\", \"right\": \"Proper noun\"}, {\"left\": \"and\", \"right\": \"Conjunction\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_u1 as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

        // Unit 2 Mathematics
        $unit = DB::table('units')->where('name',"Unit 2 Mathematics")->where('integrated_theme_id',$ilt_id)->first();
        if (!$unit) {
            $unit_id = DB::table('units')->insertGetId(['name'=>"Unit 2 Mathematics",'slug'=>"unit-2-mathematics-10",'integrated_theme_id'=>$ilt_id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
        } else { $unit_id = $unit->id; }
        $lesson = DB::table('lessons')->where('name',"Mathematics Revision")->where('unit_id',$unit_id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Mathematics Revision",'slug'=>"mathematics-revision",'unit_id'=>$unit_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_u2 = [
            ['title'=>"Place Value",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What is the value of 7 in 4,735?\", \"options\": \"[\\\"7\\\",\\\"70\\\",\\\"700\\\",\\\"7000\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fractions",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which fraction is equivalent to 1/2?\", \"options\": \"[\\\"2/3\\\",\\\"3/4\\\",\\\"4/8\\\",\\\"3/5\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"BODMAS",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Solve: 3 + 4 \\u00d7 2 = ?\", \"options\": \"[\\\"14\\\",\\\"11\\\",\\\"10\\\",\\\"13\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Percentages",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What is 25% of 80?\", \"options\": \"[\\\"15\\\",\\\"20\\\",\\\"25\\\",\\\"30\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"HCF",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The HCF of 12 and 18 is:\", \"options\": \"[\\\"3\\\",\\\"4\\\",\\\"6\\\",\\\"9\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"LCM",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The LCM of 4 and 6 is:\", \"options\": \"[\\\"8\\\",\\\"12\\\",\\\"16\\\",\\\"24\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Geometry: Angles",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"How many degrees are in a right angle?\", \"options\": \"[\\\"45\\\",\\\"60\\\",\\\"90\\\",\\\"180\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Area",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The area of a rectangle 5cm by 3cm is:\", \"options\": \"[\\\"8 cm\\\",\\\"15 cm2\\\",\\\"15 cm\\\",\\\"16 cm2\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Profit and Loss",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"An item bought for 500F sold for 650F. The profit is:\", \"options\": \"[\\\"100F\\\",\\\"150F\\\",\\\"200F\\\",\\\"50F\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Speed",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"A car travels 120km in 2 hours. Its speed is:\", \"options\": \"[\\\"60 km/h\\\",\\\"80 km/h\\\",\\\"50 km/h\\\",\\\"100 km/h\\\"]\", \"answer\": 0}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Decimals",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"0.5 + 0.25 = ?\", \"options\": \"[\\\"0.70\\\",\\\"0.75\\\",\\\"0.80\\\",\\\"1.00\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Triangles",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"The sum of angles in a triangle is 180 degrees.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Multiplication",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"12 \\u00d7 12 = 144\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Maths",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"A number divisible by both 2 and 3 is divisible by ___.\", \"answer\": \"6\"}, {\"sentence\": \"The ___ of a circle is the distance across its widest point.\", \"answer\": \"diameter\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Shapes",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Triangle\", \"right\": \"3 sides\"}, {\"left\": \"Rectangle\", \"right\": \"4 right angles\"}, {\"left\": \"Circle\", \"right\": \"No sides\"}, {\"left\": \"Hexagon\", \"right\": \"6 sides\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_u2 as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

        // Unit 3 General Knowledge
        $unit = DB::table('units')->where('name',"Unit 3 General Knowledge")->where('integrated_theme_id',$ilt_id)->first();
        if (!$unit) {
            $unit_id = DB::table('units')->insertGetId(['name'=>"Unit 3 General Knowledge",'slug'=>"unit-3-general-knowledge-10",'integrated_theme_id'=>$ilt_id,'order'=>3,'created_at'=>now(),'updated_at'=>now()]);
        } else { $unit_id = $unit->id; }
        $lesson = DB::table('lessons')->where('name',"General Knowledge Revision")->where('unit_id',$unit_id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"General Knowledge Revision",'slug'=>"general-knowledge-revision",'unit_id'=>$unit_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_u3 = [
            ['title'=>"Body Systems",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which system pumps blood around the body?\", \"options\": \"[\\\"Digestive\\\",\\\"Nervous\\\",\\\"Circulatory\\\",\\\"Respiratory\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Photosynthesis",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Plants make their food using sunlight, water and:\", \"options\": \"[\\\"oxygen\\\",\\\"carbon dioxide\\\",\\\"nitrogen\\\",\\\"hydrogen\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"States of Matter",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Water turns into steam when:\", \"options\": \"[\\\"cooled\\\",\\\"frozen\\\",\\\"heated\\\",\\\"mixed\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Food Chains",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"In a food chain, the organism that makes its own food is called a:\", \"options\": \"[\\\"consumer\\\",\\\"predator\\\",\\\"producer\\\",\\\"decomposer\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Electricity",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which material is a good conductor of electricity?\", \"options\": \"[\\\"wood\\\",\\\"plastic\\\",\\\"rubber\\\",\\\"copper\\\"]\", \"answer\": 3}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Forces",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Gravity is a force that:\", \"options\": \"[\\\"pushes objects up\\\",\\\"pulls objects down\\\",\\\"has no effect on objects\\\",\\\"only affects water\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Disease Prevention",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The best way to prevent the spread of malaria is:\", \"options\": \"[\\\"eating more vegetables\\\",\\\"sleeping under mosquito nets\\\",\\\"drinking more water\\\",\\\"exercising daily\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Cameroon Government",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The legislative body that makes laws in Cameroon is the:\", \"options\": \"[\\\"Supreme Court\\\",\\\"National Assembly\\\",\\\"Cabinet\\\",\\\"Police Force\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"African Union",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The headquarters of the African Union is in:\", \"options\": \"[\\\"Nairobi\\\",\\\"Cairo\\\",\\\"Addis Ababa\\\",\\\"Dakar\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Human Rights",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"The right to education is guaranteed to every child by:\", \"options\": \"[\\\"the police\\\",\\\"the FSLC exam\\\",\\\"the UN Convention on the Rights of the Child\\\",\\\"the school only\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Skeleton",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"The human skeleton has 206 bones.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Lungs",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"We breathe in oxygen and breathe out carbon dioxide.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Science",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The process by which plants make food using sunlight is called ___.\", \"answer\": \"photosynthesis\"}, {\"sentence\": \"A ___ is a push or pull that changes the motion of an object.\", \"answer\": \"force\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Citizenship",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The three arms of government are Executive, Judiciary and ___.\", \"answer\": \"Legislature\"}, {\"sentence\": \"Citizens have both ___ and responsibilities.\", \"answer\": \"rights\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Body Systems",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Heart\", \"right\": \"Circulatory system\"}, {\"left\": \"Brain\", \"right\": \"Nervous system\"}, {\"left\": \"Lungs\", \"right\": \"Respiratory system\"}, {\"left\": \"Stomach\", \"right\": \"Digestive system\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_u3 as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

        // Unit 4 French Language
        $unit = DB::table('units')->where('name',"Unit 4 French Language")->where('integrated_theme_id',$ilt_id)->first();
        if (!$unit) {
            $unit_id = DB::table('units')->insertGetId(['name'=>"Unit 4 French Language",'slug'=>"unit-4-french-language-10",'integrated_theme_id'=>$ilt_id,'order'=>4,'created_at'=>now(),'updated_at'=>now()]);
        } else { $unit_id = $unit->id; }
        $lesson = DB::table('lessons')->where('name',"French Language Revision")->where('unit_id',$unit_id)->first();
        if (!$lesson) {
            $lesson_id = DB::table('lessons')->insertGetId(['name'=>"French Language Revision",'slug'=>"french-language-revision",'unit_id'=>$unit_id,'order'=>1,'created_at'=>now(),'updated_at'=>now()]);
        } else { $lesson_id = $lesson->id; }
        $ex_u4 = [
            ['title'=>"Les Articles",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Quel est l'article d\\u00e9fini masculin singulier?\", \"options\": \"[\\\"la\\\",\\\"les\\\",\\\"le\\\",\\\"un\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Les Verbes",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Conjuguez 'manger' au pr\\u00e9sent avec 'nous':\", \"options\": \"[\\\"mange\\\",\\\"manges\\\",\\\"mangeons\\\",\\\"mangez\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Les Adjectifs",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Quel adjectif compl\\u00e8te: Une robe ___?\", \"options\": \"[\\\"vert\\\",\\\"verts\\\",\\\"verte\\\",\\\"vertes\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Les Pronoms",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Remplacez 'Paul et Marie' par le pronom:\", \"options\": \"[\\\"il\\\",\\\"elle\\\",\\\"ils\\\",\\\"elles\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Le Pass\u00e9 Compos\u00e9",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Le pass\\u00e9 compos\\u00e9 de 'aller' avec 'je' est:\", \"options\": \"[\\\"j\\\\\\\"ai all\\u00e9\\\",\\\"je suis all\\u00e9\\\",\\\"j\\\\\\\"allais\\\",\\\"j\\\\\\\"irai\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Les Pr\u00e9positions",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Le livre est ___ la table.\", \"options\": \"[\\\"dans\\\",\\\"sur\\\",\\\"avec\\\",\\\"pour\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Vocabulaire",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Le contraire de 'grand' est:\", \"options\": \"[\\\"gros\\\",\\\"petit\\\",\\\"fort\\\",\\\"vieux\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"La N\u00e9gation",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"La forme n\\u00e9gative de 'Je mange' est:\", \"options\": \"[\\\"Je pas mange\\\",\\\"Je ne mange pas\\\",\\\"Je mange ne pas\\\",\\\"Ne je mange pas\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Genre des Noms",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Le mot 'maison' est masculin en fran\\u00e7ais.\", \"answer\": false}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Pluriel",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Le pluriel de 'cheval' est 'chevals'.\", \"answer\": false}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Fran\u00e7ais",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"On utilise ___ devant les mots masculins commen\\u00e7ant par une voyelle.\", \"answer\": \"l'\"}, {\"sentence\": \"Le f\\u00e9minin de 'beau' est ___.\", \"answer\": \"belle\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Fill: Conjugaison",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"Je ___ (\\u00eatre) content aujourd'hui.\", \"answer\": \"suis\"}, {\"sentence\": \"Nous ___ (avoir) un bon professeur.\", \"answer\": \"avons\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Match: Vocabulaire",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"rapide\", \"right\": \"vite\"}, {\"left\": \"heureux\", \"right\": \"content\"}, {\"left\": \"commencer\", \"right\": \"d\\u00e9buter\"}, {\"left\": \"terminer\", \"right\": \"finir\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"Les Questions",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Quelle question demande une information sur le lieu?\", \"options\": \"[\\\"Quand?\\\",\\\"Pourquoi?\\\",\\\"O\\u00f9?\\\",\\\"Comment?\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>"La Ponctuation",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"On met ___ \\u00e0 la fin d'une question.\", \"options\": \"[\\\"un point\\\",\\\"une virgule\\\",\\\"un point d\\\\\\\"exclamation\\\",\\\"un point d\\\\\\\"interrogation\\\"]\", \"answer\": 3}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach ($ex_u4 as $ex) {
            if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
        }

    }
}