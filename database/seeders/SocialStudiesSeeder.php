<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialStudiesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([5=>1,6=>2,7=>3,8=>4,9=>5,10=>6] as $levelId=>$class) {
            $sid = $this->mkSubject($levelId, 'Social Studies', 'social-studies-c'.$class);
            $this->{'socialC'.$class}($sid);
        }
        $this->command->info('✅ Social Studies C1-C6 seeded');
    }

    private function mkSubject(int $levelId, string $name, string $slug): int
    {
        $existing = DB::table('subjects')->where('level_id',$levelId)->where('name',$name)->value('id');
        if ($existing) return $existing;
        return DB::table('subjects')->insertGetId([
            'level_id'=>$levelId,'name'=>$name,'slug'=>$slug,
            'icon'=>'🌍','is_active'=>true,'created_at'=>now(),'updated_at'=>now(),
        ]);
    }

    private function mkLesson(int $sid, string $theme, string $unit, string $lesson): int
    {
        $ts = strtolower(preg_replace('/[^a-z0-9]+/i','-',$theme)).'-'.$sid;
        $tid = DB::table('integrated_themes')->where('slug',$ts)->value('id');
        if (!$tid) $tid = DB::table('integrated_themes')->insertGetId([
            'subject_id'=>$sid,'name'=>$theme,'slug'=>$ts,'created_at'=>now(),'updated_at'=>now()]);
        $us = strtolower(preg_replace('/[^a-z0-9]+/i','-',$unit)).'-'.$tid;
        $uid = DB::table('units')->where('slug',$us)->value('id');
        if (!$uid) $uid = DB::table('units')->insertGetId([
            'integrated_theme_id'=>$tid,'name'=>$unit,'slug'=>$us,'created_at'=>now(),'updated_at'=>now()]);
        $ls = strtolower(preg_replace('/[^a-z0-9]+/i','-',$lesson)).'-'.$uid;
        $lid = DB::table('lessons')->where('slug',$ls)->value('id');
        if (!$lid) $lid = DB::table('lessons')->insertGetId([
            'unit_id'=>$uid,'name'=>$lesson,'slug'=>$ls,'type'=>'mixed','created_at'=>now(),'updated_at'=>now()]);
        return $lid;
    }

    private function mcq(int $lid, string $title, string $ill, string $q, array $opts, int $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'revision',
            'content'=>json_encode(['type'=>'mcq','illustration'=>$ill,
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'revision',
            'content'=>json_encode(['type'=>'true_false','illustration'=>'🌍','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function socialC1(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'My Family','Family Members','Members of the Family');
        $this->mcq($l1,'Family members','👨‍👩‍👧','Who is the father of your father?',['Uncle','Brother','Grandfather','Cousin'],2);
        $this->mcq($l1,'Nuclear family','👨‍👩‍👧','A nuclear family consists of father, mother and ___.',['cousins','children','uncles','grandparents'],1);
        $this->tf($l1,'Family fact','A family is a group of people related to each other.',true);
        $this->mcq($l1,'Family roles','👩','Who usually cooks food at home?',['Father only','Mother only','Any family member','Children only'],2);

        $l2 = $this->mkLesson($sid,'My School','School Life','People at School');
        $this->mcq($l2,'School workers','🏫','Who teaches us at school?',['Doctor','Teacher','Driver','Nurse'],1);
        $this->mcq($l2,'School rules','📋','We must ___ at school.',['fight','be quiet and listen','eat in class','sleep'],1);
        $this->tf($l2,'School fact','The headmaster leads the school.',true);

        $l3 = $this->mkLesson($sid,'My Community','Our Neighbourhood','Places in the Community');
        $this->mcq($l3,'Community places','🏥','Where do sick people go to get treatment?',['School','Market','Hospital','Church'],2);
        $this->mcq($l3,'Market','🛒','We go to the market to ___.',['learn','pray','buy and sell','sleep'],2);
        $this->tf($l3,'Community fact','A community is a group of people living in the same area.',true);

        $this->command->info('   Social Studies C1: 10 exercises');
    }

    private function socialC2(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'My Family','Extended Family','Extended Family Members');
        $this->mcq($l1,'Extended family','👨‍👩‍👧','An extended family includes grandparents, aunts, uncles and ___.',['teachers','friends','cousins','neighbours'],2);
        $this->mcq($l1,'Family values','💛','Respect for elders means ___.',['ignoring them','greeting and obeying them','shouting at them','fighting them'],1);
        $this->tf($l1,'Extended family','An extended family is larger than a nuclear family.',true);

        $l2 = $this->mkLesson($sid,'My Environment','Natural Environment','Our Natural Surroundings');
        $this->mcq($l2,'Natural resources','🌳','Which is a natural resource?',['Car','Television','Forest','House'],2);
        $this->mcq($l2,'Environment care','♻️','We protect the environment by ___.',['burning rubbish everywhere','cutting all trees','planting trees and recycling','pouring oil in rivers'],2);
        $this->tf($l2,'Environment fact','Pollution harms our environment.',true);
        $this->mcq($l2,'Water sources','💧','Which is a natural source of water?',['Tap','Pipe','River','Bottle'],2);

        $l3 = $this->mkLesson($sid,'Local Government','Our Town','Local Leaders');
        $this->mcq($l3,'Mayor','🏛️','The leader of a town council is called a ___.',['President','Governor','Mayor','Minister'],2);
        $this->mcq($l3,'Community leaders','👤','Who settles disputes in a traditional village?',['The teacher','The chief','The doctor','The market seller'],1);
        $this->tf($l3,'Leadership fact','Community leaders help solve problems in the community.',true);

        $this->command->info('   Social Studies C2: 10 exercises');
    }

    private function socialC3(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Cameroon Geography','Physical Features','Rivers and Mountains');
        $this->mcq($l1,'Longest river','🌊','The longest river in Cameroon is ___.',['Wouri','Sanaga','Mungo','Benue'],1);
        $this->mcq($l1,'Highest mountain','⛰️','The highest mountain in Cameroon is ___.',['Mount Oku','Mount Bamboutos','Mount Cameroon','Mount Mandara'],2);
        $this->tf($l1,'Geography fact','Mount Cameroon is an active volcano.',true);
        $this->mcq($l1,'Major cities','🏙️','Which city is the economic capital of Cameroon?',['Yaounde','Douala','Buea','Garoua'],1);

        $l2 = $this->mkLesson($sid,'Cameroon History','Pre-colonial Cameroon','African Kingdoms');
        $this->mcq($l2,'Bamileke','👑','The Bamileke people are mainly found in the ___ region.',['Coastal','Western','Northern','Eastern'],1);
        $this->mcq($l2,'Fulani','🐄','The Fulani people are mainly known for ___.',['fishing','farming rice','cattle rearing','mining'],2);
        $this->tf($l2,'History fact','Cameroon had many kingdoms before colonisation.',true);

        $l3 = $this->mkLesson($sid,'Rights and Duties','Civic Education','Rights in the Community');
        $this->mcq($l3,'Civic duty','🗳️','Voting in elections is a ___ of citizens.',['right only','duty only','right and duty','neither'],2);
        $this->mcq($l3,'Tax','💰','Citizens pay taxes to ___.',['buy food','fund government services','pay teachers directly','buy land'],1);
        $this->tf($l3,'Civic fact','Every citizen has both rights and responsibilities.',true);

        $this->command->info('   Social Studies C3: 11 exercises');
    }

    private function socialC4(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Cameroon Geography','Regions','The 10 Regions of Cameroon');
        $this->mcq($l1,'Number of regions','🗺️','How many regions does Cameroon have?',['8','10','12','14'],1);
        $this->mcq($l1,'Coastal region','🌊','The coastal region of Cameroon borders the ___.',['Mediterranean Sea','Atlantic Ocean','Indian Ocean','Red Sea'],1);
        $this->mcq($l1,'Adamawa','🏔️','The Adamawa plateau is in the ___ region.',['South','West','Adamawa','East'],2);
        $this->tf($l1,'Regions fact','Each region of Cameroon has a governor.',true);

        $l2 = $this->mkLesson($sid,'Colonial History','German Colonisation','Cameroon Under Germany');
        $this->mcq($l2,'German colony','🏴','Germany colonised Cameroon in ___.',['1880','1884','1900','1914'],1);
        $this->mcq($l2,'Resistance','⚔️','Which Cameroonian leader resisted German rule?',['Ahmadou','Manga Bell','Njoya','Bello'],1);
        $this->tf($l2,'Colonial fact','Cameroon was called "Kamerun" under German rule.',true);
        $this->mcq($l2,'WWI result','📅','After WWI, Cameroon was divided between ___ and ___.',['Germany and Spain','France and Britain','Belgium and France','Britain and Germany'],1);

        $l3 = $this->mkLesson($sid,'Government','National Government','Structure of Government');
        $this->mcq($l3,'Head of state','👤','The head of state in Cameroon is ___.',['Prime Minister','President','Governor','Mayor'],1);
        $this->mcq($l3,'Parliament','🏛️','The law-making body in Cameroon is called ___.',['The Cabinet','The Senate only','The National Assembly and Senate','The Supreme Court'],2);
        $this->tf($l3,'Government fact','Cameroon has a multi-party democratic system.',true);

        $this->command->info('   Social Studies C4: 12 exercises');
    }

    private function socialC5(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'African History','Africa Before Colonisation','Great African Kingdoms');
        $this->mcq($l1,'Mali Empire','👑','The Mali Empire was famous for its wealth in ___.',['oil','gold and salt','diamonds','iron'],1);
        $this->mcq($l1,'Great Zimbabwe','🏛️','Great Zimbabwe is an ancient monument in ___.',['Nigeria','Egypt','Zimbabwe','Ghana'],2);
        $this->tf($l1,'African kingdoms','Africa had great kingdoms long before European colonisation.',true);
        $this->mcq($l1,'Slave trade','⛓️','The Trans-Atlantic Slave Trade involved taking Africans to ___.',['Asia','Europe','the Americas','Australia'],2);

        $l2 = $this->mkLesson($sid,'Cameroon Economy','Economic Activities','Agriculture and Trade');
        $this->mcq($l2,'Main crop','🌱','The main cash crop in Cameroon is ___.',['rice','wheat','cocoa','beans'],2);
        $this->mcq($l2,'Oil production','⛽','Cameroon produces oil mainly in the ___ region.',['Western','Coastal','Northern','Adamawa'],1);
        $this->tf($l2,'Economy fact','Agriculture is the main economic activity in Cameroon.',true);

        $l3 = $this->mkLesson($sid,'Human Rights','International Organisations','The United Nations');
        $this->mcq($l3,'UN founding','🌍','The United Nations was founded in ___.',['1939','1945','1948','1960'],1);
        $this->mcq($l3,'UNICEF','👶','UNICEF mainly works to protect ___.',['animals','adults','children','soldiers'],2);
        $this->mcq($l3,'AU','🌍','The African Union (AU) is headquartered in ___.',['Cairo','Lagos','Addis Ababa','Nairobi'],2);
        $this->tf($l3,'UN fact','The UN has 5 permanent members on the Security Council.',true);

        $this->command->info('   Social Studies C5: 11 exercises');
    }

    private function socialC6(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'World Geography','Continents and Oceans','The World Map');
        $this->mcq($l1,'Continents count','🌍','How many continents are there in the world?',['5','6','7','8'],2);
        $this->mcq($l1,'Largest continent','🌍','The largest continent in the world is ___.',['Africa','America','Asia','Europe'],2);
        $this->mcq($l1,'Largest ocean','🌊','The largest ocean in the world is ___.',['Atlantic','Indian','Arctic','Pacific'],3);
        $this->tf($l1,'Geography fact','Africa is the second largest continent.',true);

        $l2 = $this->mkLesson($sid,'Independence','Road to Independence','Cameroon\'s Independence');
        $this->mcq($l2,'Independence date','📅','Cameroon gained independence from France on ___.',['January 1, 1958','January 1, 1960','May 20, 1960','October 1, 1961'],1);
        $this->mcq($l2,'Reunification','🤝','Southern Cameroons joined French Cameroon in ___.',['1960','1961','1962','1963'],1);
        $this->mcq($l2,'National day','🎉','Cameroon\'s National Day is ___.',['January 1','May 20','October 1','December 25'],1);
        $this->tf($l2,'Independence fact','Ahmadou Ahidjo was Cameroon\'s first president.',true);

        $l3 = $this->mkLesson($sid,'Global Issues','Environmental Issues','Climate Change');
        $this->mcq($l3,'Climate change','🌡️','Global warming is mainly caused by ___.',['rain','greenhouse gases','wind','sunlight'],1);
        $this->mcq($l3,'Deforestation','🌳','Cutting down too many trees causes ___.',['more rain','soil erosion and drought','cooler weather','more oxygen'],1);
        $this->tf($l3,'Climate fact','Climate change affects food production and weather patterns.',true);
        $this->mcq($l3,'Renewable energy','♻️','Which is a renewable energy source?',['Coal','Petrol','Solar','Gas'],2);

        $this->command->info('   Social Studies C6: 12 exercises');
    }
}
