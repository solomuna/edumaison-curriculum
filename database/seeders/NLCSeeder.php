<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NLCSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([5=>1,6=>2,7=>3,8=>4,9=>5,10=>6] as $levelId=>$class) {
            $sid = $this->mkSubject($levelId, 'National Languages and Cultures', 'nlc-c'.$class);
            $this->{'nlcC'.$class}($sid);
        }
        $this->command->info('✅ NLC C1-C6 seeded');
    }

    private function mkSubject(int $levelId, string $name, string $slug): int
    {
        $existing = DB::table('subjects')->where('level_id',$levelId)->where('name',$name)->value('id');
        if ($existing) return $existing;
        return DB::table('subjects')->insertGetId([
            'level_id'=>$levelId,'name'=>$name,'slug'=>$slug,
            'icon'=>'🎭','is_active'=>true,'created_at'=>now(),'updated_at'=>now(),
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
            'content'=>json_encode(['type'=>'true_false','illustration'=>'🎭','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function nlcC1(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Languages of Cameroon','Our Languages','Cameroon Languages');
        $this->mcq($l1,'Official languages','🗣️','Cameroon has ___ official languages.',['1','2','3','4'],1);
        $this->mcq($l1,'Languages count','🌍','Cameroon has approximately ___ local languages.',['50','100','200','280'],3);
        $this->tf($l1,'Languages fact','French and English are the two official languages of Cameroon.',true);
        $this->mcq($l1,'Pidgin','🗣️','Pidgin English is used in Cameroon as a ___ language.',['official','foreign','lingua franca','school'],2);

        $l2 = $this->mkLesson($sid,'Cultural Practices','Greetings','Greetings in Cameroon');
        $this->mcq($l2,'Greetings','🤝','In most Cameroonian cultures, greeting elders shows ___.',['weakness','disrespect','respect','fear'],2);
        $this->tf($l2,'Greeting fact','Different ethnic groups in Cameroon have different ways of greeting.',true);
        $this->mcq($l2,'Cultural values','💛','Sharing food with visitors is a sign of ___.',['waste','poverty','hospitality','laziness'],2);

        $l3 = $this->mkLesson($sid,'Traditional Life','Food and Clothing','Traditional Foods');
        $this->mcq($l3,'Traditional food','🍲','Ndolé is a traditional dish from which region?',['North','West','Littoral/South','East'],2);
        $this->mcq($l3,'Traditional clothing','👘','Traditional clothing in Cameroon varies by ___.',['age only','ethnic group and region','school','weather only'],1);
        $this->tf($l3,'Food fact','Fufu corn and njama njama is a popular dish in the Western region.',true);

        $this->command->info('   NLC C1: 10 exercises');
    }

    private function nlcC2(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Ethnic Groups','Major Groups','Peoples of Cameroon');
        $this->mcq($l1,'Major groups','👥','Which ethnic group is found mainly in the Western highlands?',['Beti','Bamileke','Fulani','Baka'],1);
        $this->mcq($l1,'Baka people','🌳','The Baka people are mainly known for living in ___.',['cities','mountains','the forest','deserts'],2);
        $this->tf($l1,'Ethnic fact','Cameroon is home to over 200 different ethnic groups.',true);
        $this->mcq($l1,'Fulani','🐄','The Fulani are traditionally known as ___.',['farmers','fishermen','cattle herders','miners'],2);

        $l2 = $this->mkLesson($sid,'Cultural Practices','Ceremonies','Traditional Ceremonies');
        $this->mcq($l2,'Marriage','💍','In many Cameroonian cultures, marriage involves paying ___.',['school fees','bride price','house rent','tax'],1);
        $this->mcq($l2,'Naming ceremony','👶','A naming ceremony is held to ___.',['sell a child','give a child their name','punish parents','celebrate a death'],1);
        $this->tf($l2,'Ceremony fact','Traditional ceremonies help to preserve cultural identity.',true);

        $l3 = $this->mkLesson($sid,'Arts and Crafts','Traditional Arts','Crafts of Cameroon');
        $this->mcq($l3,'Bamoun art','🏺','The Bamoun people are famous for their ___.',['woodwork only','bronze and brass works','weaving only','music only'],1);
        $this->mcq($l3,'Kente cloth','🧵','Woven cloth and raffia items are mainly produced in the ___ region.',['North','East','West','South'],2);
        $this->tf($l3,'Craft fact','Traditional crafts in Cameroon include pottery, weaving and carving.',true);

        $this->command->info('   NLC C2: 10 exercises');
    }

    private function nlcC3(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Traditional Governance','Chiefs and Councils','Traditional Rulers');
        $this->mcq($l1,'Traditional ruler','👑','A traditional ruler in Cameroon is called a ___.',['mayor','governor','chief or fon','president'],2);
        $this->mcq($l1,'Fon','👑','The Fon is a traditional ruler mainly found in the ___ region.',['North','Coastal','Western grassfields','Eastern'],2);
        $this->tf($l1,'Chief fact','Traditional chiefs help maintain peace and cultural values.',true);
        $this->mcq($l1,'Lamido','🕌','A Lamido is a traditional ruler mainly among the ___ people.',['Bamileke','Beti','Fulani/Muslim north','Baka'],2);

        $l2 = $this->mkLesson($sid,'Oral Tradition','Folklore','Proverbs and Stories');
        $this->mcq($l2,'Proverbs','📖','A proverb is a short saying that contains ___.',['a joke','a name','wisdom','a song'],2);
        $this->mcq($l2,'Oral tradition','🗣️','Oral tradition passes knowledge through ___.',['books','internet','spoken word and stories','television'],2);
        $this->tf($l2,'Folklore fact','Folktales often teach moral lessons.',true);
        $this->mcq($l2,'Griots','🎵','In West African tradition, a griot is a ___.',['farmer','warrior','storyteller and musician','chief'],2);

        $l3 = $this->mkLesson($sid,'Music and Dance','Traditional Music','Musical Instruments');
        $this->mcq($l3,'Balafon','🎵','The balafon is a traditional ___ instrument.',['string','wind','percussion/xylophone','brass'],2);
        $this->mcq($l3,'Bikutsi','🎶','Bikutsi is a traditional music style from the ___ people.',['Fulani','Beti/Ewondo','Bamileke','Coastal'],1);
        $this->tf($l3,'Music fact','Traditional music is used in ceremonies, celebrations and storytelling.',true);

        $this->command->info('   NLC C3: 11 exercises');
    }

    private function nlcC4(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Cultural Heritage','UNESCO Heritage','Protecting Our Culture');
        $this->mcq($l1,'Cultural heritage','🏛️','Cultural heritage includes ___.',['only buildings','only music','traditions, arts, language and monuments','only food'],2);
        $this->mcq($l1,'UNESCO','🌍','UNESCO helps to ___.',['build roads','protect cultural and natural heritage','grow crops','make laws'],1);
        $this->tf($l1,'Heritage fact','It is important to preserve our cultural heritage for future generations.',true);

        $l2 = $this->mkLesson($sid,'Languages','Writing Systems','Scripts and Writing');
        $this->mcq($l2,'Bamoun script','✍️','Sultan Njoya of the Bamoun invented a writing system called ___.',['Arabic','Latin','Shumom script','Greek'],2);
        $this->mcq($l2,'Script fact','🏛️','The Shumom script was created in approximately ___.',['1800','1896','1920','1950'],1);
        $this->tf($l2,'Writing fact','The Bamoun script is one of the few African writing systems invented in modern times.',true);
        $this->mcq($l2,'Fulfulde','🗣️','Fulfulde is the language spoken mainly by the ___ people.',['Beti','Bamileke','Fulani','Baka'],2);

        $l3 = $this->mkLesson($sid,'Cultural Festivals','Celebrations','Major Cultural Festivals');
        $this->mcq($l3,'Ngondo festival','🚣','The Ngondo festival is celebrated by the ___ people on the River Wouri.',['Bamileke','Fulani','Sawa/Coastal','Beti'],2);
        $this->mcq($l3,'Lela festival','🎭','The Lela festival is celebrated in the ___ region.',['North','West','Western grassfields (Bali)','East'],2);
        $this->tf($l3,'Festival fact','Cultural festivals strengthen community bonds and preserve traditions.',true);

        $this->command->info('   NLC C4: 10 exercises');
    }

    private function nlcC5(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Pan-Africanism','African Identity','African Unity');
        $this->mcq($l1,'Pan-Africanism','✊','Pan-Africanism promotes the ___ of African peoples.',['division','unity and solidarity','competition','isolation'],1);
        $this->mcq($l1,'African Union','🌍','The African Union was founded to promote ___ among African nations.',['war','trade and unity','colonialism','religion'],1);
        $this->tf($l1,'Africa fact','Pan-Africanism began as a movement against slavery and colonialism.',true);
        $this->mcq($l1,'Kwame Nkrumah','👤','Kwame Nkrumah was a Pan-African leader from ___.',['Nigeria','Kenya','Ghana','Cameroon'],2);

        $l2 = $this->mkLesson($sid,'Bilingualism','Living Together','French and English in Cameroon');
        $this->mcq($l2,'Bilingualism','🗣️','Bilingualism in Cameroon means using both ___ in official life.',['French and Arabic','French and English','English and Fulfulde','French and Ewondo'],1);
        $this->mcq($l2,'Anglophone regions','📍','The two Anglophone regions of Cameroon are ___.',
            ['Centre and South','North West and South West','West and Littoral','Adamawa and East'],1);
        $this->tf($l2,'Bilingualism fact','Bilingualism is an important part of Cameroon\'s national identity.',true);

        $l3 = $this->mkLesson($sid,'Cultural Exchange','Influences','Cultural Influences in Cameroon');
        $this->mcq($l3,'Colonial influence','🏛️','Which colonial powers most influenced Cameroonian culture?',['Spain and Portugal','France and Britain','Germany and Belgium','Italy and Holland'],1);
        $this->mcq($l3,'Religion','⛪','The two main religions in Cameroon are Christianity and ___.',['Hinduism','Buddhism','Islam','Judaism'],2);
        $this->tf($l3,'Culture fact','Cameroon\'s cultural diversity is one of its greatest strengths.',true);

        $this->command->info('   NLC C5: 10 exercises');
    }

    private function nlcC6(int $sid): void
    {
        $l1 = $this->mkLesson($sid,'Cultural Identity','National Identity','Being Cameroonian');
        $this->mcq($l1,'National identity','🇨🇲','National identity is shaped by shared ___.',['wealth','history, culture and language','sports only','food only'],1);
        $this->mcq($l1,'Unity in diversity','🤝','The motto "Peace, Work, Fatherland" promotes ___.',['division','national unity','competition','isolation'],1);
        $this->tf($l1,'Identity fact','Cameroon is often called "Africa in Miniature" because of its diversity.',true);
        $this->mcq($l1,'Cultural tolerance','💛','Cultural tolerance means ___.',['forcing others to change','accepting and respecting other cultures','ignoring differences','fighting over culture'],1);

        $l2 = $this->mkLesson($sid,'Globalisation','Modern Influences','Globalisation and Culture');
        $this->mcq($l2,'Globalisation','🌐','Globalisation refers to the increasing ___ of the world.',['isolation','size','interconnectedness','pollution'],2);
        $this->mcq($l2,'Cultural erosion','⚠️','Cultural erosion means the ___ of traditional cultural values.',['growth','preservation','gradual loss','celebration'],2);
        $this->tf($l2,'Globalisation fact','Globalisation can both enrich and threaten local cultures.',true);
        $this->mcq($l2,'Internet and culture','💻','The internet has spread cultural influences ___.',['slowly','only in cities','globally and rapidly','only in schools'],2);

        $l3 = $this->mkLesson($sid,'Future of Culture','Preservation','Preserving Cameroon\'s Heritage');
        $this->mcq($l3,'Preservation methods','🏛️','Which best helps preserve cultural heritage?',
            ['Banning all foreign culture','Teaching traditions in schools and communities','Ignoring the past','Closing museums'],1);
        $this->mcq($l3,'Museums','🏺','Museums help preserve cultural heritage by ___.',['selling artefacts','destroying old items','storing and displaying artefacts','building new cities'],2);
        $this->tf($l3,'Heritage preservation','Schools play an important role in preserving cultural heritage.',true);

        $this->command->info('   NLC C6: 11 exercises');
    }
}
