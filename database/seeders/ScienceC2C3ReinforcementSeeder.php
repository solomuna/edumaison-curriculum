<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScienceC2C3ReinforcementSeeder extends Seeder
{
    public function run(): void
    {
        $this->scienceC2();
        $this->scienceC3();
        $this->command->info('✅ Science C2 and C3 reinforced');
    }

    private function lid(int $sid): ?int
    {
        return DB::table('lessons')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)->value('lessons.id');
    }

    private function mcq(int $lid, string $title, string $ill, string $q, array $opts, int $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'science',
            'content'=>json_encode(['type'=>'mcq','illustration'=>$ill,
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'science',
            'content'=>json_encode(['type'=>'true_false','illustration'=>'🔬',
                'statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function match(int $lid, string $title, string $ill, string $q, array $pairs): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'science',
            'content'=>json_encode(['type'=>'match_pairs','illustration'=>$ill,
                'question'=>$q,'pairs'=>$pairs]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    // ── SCIENCE C2 (subject 21) ───────────────────────────────────────────
    private function scienceC2(): void
    {
        $id = $this->lid(21); if (!$id) return;

        // Food and nutrition
        $this->mcq($id,'Food for energy','🍚','Which food gives us energy?',
            ['Meat','Rice and bread','Vegetables','Water'],1);
        $this->mcq($id,'Protein food','🥩','Which food helps our body grow?',
            ['Sugar','Oil','Fish and beans','Salt'],2);
        $this->match($id,'Match food to nutrient','🥗','Match each food to its nutrient.',[
            ['word'=>'Rice','image'=>'🌾'],
            ['word'=>'Fish','image'=>'🐟'],
            ['word'=>'Carrot','image'=>'🥕'],
            ['word'=>'Milk','image'=>'🥛'],
        ]);
        $this->tf($id,'Nutrition C2','We should eat different types of food every day.',true);

        // Air and water
        $this->mcq($id,'Uses of water','💧','Which is NOT a use of water?',
            ['Drinking','Cooking','Breathing','Washing'],2);
        $this->mcq($id,'Clean water','💧','We should drink ___ water.',
            ['dirty','clean and safe','salty','hot'],1);
        $this->tf($id,'Air fact C2','Air is all around us even if we cannot see it.',true);
        $this->mcq($id,'Pollution','🏭','What makes the air dirty?',
            ['Trees','Flowers','Smoke from cars and factories','Rain'],2);

        // Weather
        $this->mcq($id,'Weather C2','🌤️','When we see dark clouds, it may soon ___.',
            ['get sunny','rain','get cold','get hot'],1);
        $this->mcq($id,'Seasons C2','🌧️','Cameroon has ___ main seasons.',
            ['1','2','3','4'],1);
        $this->tf($id,'Weather fact','The rainy season brings more water to rivers and farms.',true);

        // Plants
        $this->mcq($id,'Germination C2','🌱','What does a seed need to germinate?',
            ['Only sunlight','Water, warmth and air','Only soil','Only water'],1);
        $this->mcq($id,'Plant products C2','🌿','Which product comes from plants?',
            ['Milk','Wool','Wood','Eggs'],2);

        $this->command->info('   Science C2: 13 exercises added');
    }

    // ── SCIENCE C3 (subject 27) ───────────────────────────────────────────
    private function scienceC3(): void
    {
        $id = $this->lid(27); if (!$id) return;

        // States of matter
        $this->mcq($id,'States of matter','💧','Water can exist as solid, liquid and ___.',
            ['metal','gas/vapour','powder','stone'],1);
        $this->mcq($id,'Melting','🧊','When ice is heated it ___.',
            ['freezes','melts into water','becomes harder','disappears'],1);
        $this->mcq($id,'Evaporation C3','☀️','When water is heated strongly it ___.',
            ['freezes','becomes ice','evaporates into steam','becomes heavier'],2);
        $this->tf($id,'States of matter fact','Water is the only substance that exists naturally as solid, liquid and gas.',true);

        // Food chains
        $this->mcq($id,'Food chain C3','🌿','In a food chain: grass → rabbit → fox. The grass is a ___.',
            ['consumer','predator','producer','decomposer'],2);
        $this->mcq($id,'Predator C3','🦁','In the food chain: grass → rabbit → fox, the fox is a ___.',
            ['producer','prey','predator','decomposer'],2);
        $this->tf($id,'Food chain fact','All food chains begin with a plant (producer).',true);
        $this->match($id,'Match food chain roles','🌿','Match each organism to its role.',[
            ['word'=>'Grass','image'=>'🌿'],
            ['word'=>'Rabbit','image'=>'🐰'],
            ['word'=>'Fox','image'=>'🦊'],
            ['word'=>'Bacteria','image'=>'🦠'],
        ]);

        // Forces
        $this->mcq($id,'Gravity C3','🌍','Gravity pulls objects ___.',
            ['upward','sideways','downward','in circles'],2);
        $this->mcq($id,'Magnetism C3','🧲','A magnet attracts objects made of ___.',
            ['wood','plastic','iron and steel','rubber'],2);
        $this->tf($id,'Magnet fact','A magnet has two poles — north and south.',true);

        // Health
        $this->mcq($id,'Immunisation C3','💉','We get immunised to ___.',
            ['get sick faster','prevent diseases','grow taller','sleep better'],1);
        $this->mcq($id,'Drug abuse C3','⚠️','Taking medicines without a doctor\'s prescription is ___.',
            ['safe','helpful','dangerous','encouraged'],2);

        $this->command->info('   Science C3: 13 exercises added');
    }
}
