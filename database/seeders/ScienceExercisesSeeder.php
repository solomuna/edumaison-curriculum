<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScienceExercisesSeeder extends Seeder
{
    public function run(): void
    {
        $this->scienceC4();
        $this->scienceC5();
        $this->command->info('✅ Exercices Science C4 et C5 seedés');
    }

    private function lid(int $sid): ?int
    {
        return DB::table('lessons')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)
            ->value('lessons.id');
    }

    private function lidByName(int $sid, string $lessonName): ?int
    {
        return DB::table('lessons')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)
            ->where('lessons.name', $lessonName)
            ->value('lessons.id');
    }

    private function ins(int $lid, string $title, string $cat, array $data): void
    {
        DB::table('exercises')->insert([
            'lesson_id'  => $lid,
            'title'      => $title,
            'category'   => $cat,
            'content'    => json_encode($data),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function mcq(int $lid, string $title, string $ill, string $q, array $opts, int $ans): void
    {
        $this->ins($lid, $title, 'science', [
            'type' => 'mcq', 'illustration' => $ill,
            'questions' => [['text' => $q, 'options' => $opts, 'answer' => $ans]],
        ]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        $this->ins($lid, $title, 'science', [
            'type' => 'true_false', 'illustration' => '🔬',
            'statement' => $stmt, 'answer' => $ans,
        ]);
    }

    private function match(int $lid, string $title, string $ill, string $q, array $pairs): void
    {
        $this->ins($lid, $title, 'science', [
            'type' => 'match_pairs', 'illustration' => $ill,
            'question' => $q, 'pairs' => $pairs,
        ]);
    }

    // ── SCIENCE C4 (subject_id = 33) ─────────────────────────────────────
    private function scienceC4(): void
    {
        $id = $this->lid(33);
        if (!$id) { $this->command->warn('Science C4 lesson introuvable'); return; }

        // Skeleton
        $lid1 = $this->lidByName(33, 'Bones and the Skeleton') ?? $id;
        $this->mcq($lid1,'Bones count','🦴','How many bones does an adult human body have?',['106','206','306','406'],1);
        $this->mcq($lid1,'Skull function','💀','What does the skull protect?',['Heart','Lungs','Brain','Stomach'],2);
        $this->tf($lid1,'Bones fact','Bones are living tissues.',true);
        $this->tf($lid1,'Joints fact','Joints connect bones together.',true);

        // Senses
        $lid2 = $this->lidByName(33, 'The Senses') ?? $id;
        $this->match($lid2,'Match senses','👁️','Match each sense to its organ.',[
            ['word'=>'Sight','image'=>'👁️'],
            ['word'=>'Hearing','image'=>'👂'],
            ['word'=>'Smell','image'=>'👃'],
            ['word'=>'Touch','image'=>'✋'],
        ]);
        $this->mcq($lid2,'Taste buds','👅','Where are taste buds found?',['Nose','Eyes','Tongue','Ears'],2);

        // Diseases
        $lid3 = $this->lidByName(33, 'Water-borne and Insect-borne Diseases') ?? $id;
        $this->mcq($lid3,'Malaria carrier','🦟','Malaria is spread by ___.',['Housefly','Mosquito','Rat','Cockroach'],1);
        $this->mcq($lid3,'Cholera source','💧','Cholera is caused by drinking ___ water.',['hot','clean','dirty','cold'],2);
        $this->tf($lid3,'Disease prevention','Washing hands helps prevent diseases.',true);
        $this->tf($lid3,'Malaria fact','Malaria affects the liver and blood.',true);
        $this->mcq($lid3,'Typhoid','🤒','Typhoid is a ___ disease.',['water-borne','air-borne','insect-borne','touch'],0);

        // Balanced diet
        $lid4 = $this->lidByName(33, 'Balanced Diet') ?? $id;
        $this->mcq($lid4,'Protein food','🥩','Which food gives us protein?',['Rice','Fish','Sugar','Oil'],1);
        $this->mcq($lid4,'Vitamin C','🍊','Which food is rich in Vitamin C?',['Bread','Rice','Orange','Meat'],2);
        $this->tf($lid4,'Balanced diet','A balanced diet contains all nutrients.',true);
        $this->match($lid4,'Food groups','🥦','Match each food to its nutrient group.',[
            ['word'=>'Rice','image'=>'🌾'],
            ['word'=>'Fish','image'=>'🐟'],
            ['word'=>'Carrot','image'=>'🥕'],
            ['word'=>'Milk','image'=>'🥛'],
        ]);

        // Plants
        $lid5 = $this->lidByName(33, 'Plants and Seeds') ?? $id;
        $this->mcq($lid5,'Photosynthesis','🌿','Plants make food using sunlight, water and ___.',['Salt','Carbon dioxide','Oxygen','Nitrogen'],1);
        $this->tf($lid5,'Plants fact','Plants release oxygen during photosynthesis.',true);
        $this->mcq($lid5,'Seed germination','🌱','What does a seed need to germinate?',
            ['Water, warmth and air','Only water','Only sunlight','Only soil'],0);

        // Simple machines
        $lid6 = $this->lidByName(33, 'Simple Machines') ?? $id;
        $this->mcq($lid6,'Lever example','⚖️','Which is an example of a lever?',['Wheel','Scissors','Screw','Pulley'],1);
        $this->mcq($lid6,'Inclined plane','📐','A ramp is an example of ___.',['Pulley','Lever','Inclined plane','Wedge'],2);
        $this->tf($lid6,'Machines fact','Simple machines make work easier.',true);

        $this->command->info('   Science C4 : '.DB::table('exercises')
            ->join('lessons','exercises.lesson_id','=','lessons.id')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',33)->count().' exercices');
    }

    // ── SCIENCE C5 (subject_id = 39) ─────────────────────────────────────
    private function scienceC5(): void
    {
        $id = $this->lid(39);
        if (!$id) { $this->command->warn('Science C5 lesson introuvable'); return; }

        // Body systems
        $lid1 = $this->lidByName(39, 'Digestive and Circulatory Systems') ?? $id;
        $this->mcq($lid1,'Digestive system','🫀','Where does digestion begin?',['Stomach','Intestine','Mouth','Liver'],2);
        $this->mcq($lid1,'Heart function','❤️','The heart pumps ___ around the body.',['air','water','blood','food'],2);
        $this->tf($lid1,'Circulatory system','The heart is part of the circulatory system.',true);
        $this->mcq($lid1,'Lungs','🫁','Which organ is used for breathing?',['Heart','Kidneys','Lungs','Liver'],2);

        // Reproductive health
        $lid2 = $this->lidByName(39, 'Puberty and Adolescence') ?? $id;
        $this->mcq($lid2,'Puberty','🧒','Puberty is the stage when a child becomes ___.',
            ['old','sick','an adult','a baby'],2);
        $this->tf($lid2,'Puberty fact','Both boys and girls go through puberty.',true);

        // Diseases C5
        $lid3 = $this->lidByName(39, 'Non-communicable Diseases') ?? $id;
        $this->mcq($lid3,'Non-communicable','💊','Which disease is NOT contagious?',
            ['Malaria','Cholera','Diabetes','Typhoid'],2);
        $this->tf($lid3,'Cancer fact','Cancer is a non-communicable disease.',true);
        $this->mcq($lid3,'Vaccines','💉','Vaccines help the body to ___.',
            ['get sick','fight diseases','grow taller','sleep better'],1);

        // Water cycle
        $lid4 = $this->lidByName(39, 'The Water Cycle') ?? $id;
        $this->mcq($lid4,'Evaporation','💧','When water turns into vapour it is called ___.',
            ['condensation','precipitation','evaporation','absorption'],2);
        $this->mcq($lid4,'Water cycle','🌧️','What falls from clouds as part of the water cycle?',
            ['Snow only','Rain only','Rain and snow','Steam'],2);
        $this->tf($lid4,'Water cycle','The water cycle has no beginning or end.',true);

        // Forces
        $lid5 = $this->lidByName(39, 'Push, Pull, Friction and Tension') ?? $id;
        $this->mcq($lid5,'Friction','🛝','Friction is a force that ___.',
            ['speeds things up','slows things down','makes things float','makes things hot'],1);
        $this->mcq($lid5,'Force types','💪','Pushing a door open is an example of a ___ force.',
            ['pull','push','friction','gravity'],1);
        $this->tf($lid5,'Forces fact','Gravity pulls objects towards the Earth.',true);

        // Energy
        $lid6 = $this->lidByName(39, 'Energy Forms and Sources') ?? $id;
        $this->mcq($lid6,'Solar energy','☀️','Energy from the sun is called ___ energy.',
            ['wind','nuclear','solar','hydro'],2);
        $this->mcq($lid6,'Renewable','♻️','Which is a renewable energy source?',
            ['Coal','Oil','Natural gas','Solar'],3);
        $this->tf($lid6,'Energy fact','Sound is a form of energy.',true);

        // Conductors
        $lid7 = $this->lidByName(39, 'Conductors and Insulators') ?? $id;
        $this->mcq($lid7,'Conductor','⚡','Which material conducts electricity?',
            ['Wood','Plastic','Copper','Rubber'],2);
        $this->mcq($lid7,'Insulator','🔌','Which material is an insulator?',
            ['Iron','Copper','Gold','Rubber'],3);
        $this->tf($lid7,'Conductors fact','Metals are generally good conductors of electricity.',true);

        $this->command->info('   Science C5 : '.DB::table('exercises')
            ->join('lessons','exercises.lesson_id','=','lessons.id')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',39)->count().' exercices');
    }
}
