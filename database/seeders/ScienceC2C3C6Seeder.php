<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScienceC2C3C6Seeder extends Seeder
{
    public function run(): void
    {
        $this->scienceC2();
        $this->scienceC3();
        $this->scienceC6();
        $this->command->info('✅ Science C2, C3, C6 seeded');
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
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'science',
            'content'=>json_encode(['type'=>'mcq','illustration'=>$ill,
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function tf(int $lid, string $title, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'science',
            'content'=>json_encode(['type'=>'true_false','illustration'=>'🔬','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function match(int $lid, string $title, string $ill, string $q, array $pairs): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'science',
            'content'=>json_encode(['type'=>'match_pairs','illustration'=>$ill,'question'=>$q,'pairs'=>$pairs]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    // ── SCIENCE C2 (subject_id = 21) ──────────────────────────────────────
    private function scienceC2(): void
    {
        $sid = 21;

        $l1 = $this->mkLesson($sid,'Health Education','The Human Body','Body Parts and Hygiene');
        $this->mcq($l1,'Body parts','🧍','How many legs does a human have?',['1','2','3','4'],1);
        $this->match($l1,'Match senses','👁️','Match each sense to its organ.',[
            ['word'=>'Sight','image'=>'👁️'],['word'=>'Hearing','image'=>'👂'],
            ['word'=>'Smell','image'=>'👃'],['word'=>'Touch','image'=>'✋'],
        ]);
        $this->mcq($l1,'Hygiene','🧼','We wash our hands to ___.',
            ['make them wet','remove germs','cool them down','make them smell nice'],1);
        $this->tf($l1,'Teeth fact','We should brush our teeth twice a day.',true);
        $this->tf($l1,'Body fact','The heart pumps blood around the body.',true);

        $l2 = $this->mkLesson($sid,'Health Education','Diseases','Common Diseases');
        $this->mcq($l2,'Malaria','🦟','Malaria is spread by ___.',['rats','mosquitoes','flies','dogs'],1);
        $this->mcq($l2,'Cholera','💧','Cholera is caused by drinking ___ water.',['hot','clean','dirty','cold'],2);
        $this->tf($l2,'Prevention','Washing hands prevents many diseases.',true);
        $this->mcq($l2,'First aid','🩹','If you have a small cut, you should ___.',
            ['ignore it','clean and cover it','rub dirt on it','cut it more'],1);

        $l3 = $this->mkLesson($sid,'Environmental Science','Animals','Domestic and Wild Animals');
        $this->mcq($l3,'Domestic animals','🐄','Which animal lives with people at home?',
            ['Lion','Elephant','Cow','Snake'],2);
        $this->mcq($l3,'Wild animals','🦁','Which animal lives in the wild?',
            ['Dog','Cat','Chicken','Lion'],3);
        $this->tf($l3,'Birds fact','Birds have feathers and wings.',true);
        $this->match($l3,'Animal homes','🏠','Match each animal to its home.',[
            ['word'=>'Fish','image'=>'🐟'],['word'=>'Bird','image'=>'🐦'],
            ['word'=>'Dog','image'=>'🐕'],['word'=>'Bee','image'=>'🐝'],
        ]);

        $l4 = $this->mkLesson($sid,'Environmental Science','Plants','Plants and their Uses');
        $this->mcq($l4,'Photosynthesis','🌿','Plants need ___ to make food.',
            ['rain only','sunlight and water','soil only','wind'],1);
        $this->tf($l4,'Plants fact','Plants produce oxygen.',true);
        $this->mcq($l4,'Plant parts','🌱','Which part of a plant absorbs water from soil?',
            ['Leaf','Stem','Root','Flower'],2);

        $l5 = $this->mkLesson($sid,'Technology','Machines','Simple Machines');
        $this->mcq($l5,'Simple machines','⚙️','A wheel is a simple machine that helps us ___.',
            ['fly','move things easily','swim','climb'],1);
        $this->tf($l5,'Electricity danger','Electricity can be dangerous.',true);

        $this->command->info('   Science C2: 17 exercises');
    }

    // ── SCIENCE C3 (subject_id = 27) ──────────────────────────────────────
    private function scienceC3(): void
    {
        $sid = 27;

        $l1 = $this->mkLesson($sid,'Health Education','The Human Body','Bones and Muscles');
        $this->mcq($l1,'Skeleton','🦴','The skeleton gives our body ___.',
            ['colour','support and shape','smell','taste'],1);
        $this->mcq($l1,'Muscles','💪','Muscles help us to ___.',
            ['breathe only','see','move our body','digest food'],2);
        $this->tf($l1,'Bones fact','Babies have more bones than adults.',true);

        $l2 = $this->mkLesson($sid,'Health Education','Diseases','Water and Insect-borne Diseases');
        $this->mcq($l2,'Typhoid','🤒','Typhoid is a ___ disease.',
            ['water-borne','air-borne','touch','insect-borne'],0);
        $this->mcq($l2,'Prevention','🛡️','The best way to prevent malaria is to ___.',
            ['eat more food','use mosquito nets','drink cold water','exercise daily'],1);
        $this->tf($l2,'Drug abuse','Taking too many medicines can be harmful.',true);

        $l3 = $this->mkLesson($sid,'Environmental Science','Living Things','Living and Non-living Things');
        $this->mcq($l3,'Living things','🌱','Which of these is a living thing?',
            ['Stone','Water','Tree','Sand'],2);
        $this->mcq($l3,'Non-living','🪨','Which of these is NOT a living thing?',
            ['Fish','Bird','Rock','Flower'],2);
        $this->tf($l3,'Living things','All living things need food and water.',true);
        $this->match($l3,'Classify things','🔬','Sort these into living and non-living.',[
            ['word'=>'Cat','image'=>'🐱'],['word'=>'Rock','image'=>'🪨'],
            ['word'=>'Tree','image'=>'🌳'],['word'=>'Water','image'=>'💧'],
        ]);

        $l4 = $this->mkLesson($sid,'Environmental Science','Soil','Types of Soil');
        $this->mcq($l4,'Sandy soil','🏜️','Sandy soil is ___ for farming.',
            ['the best','good','poor','excellent'],2);
        $this->mcq($l4,'Loamy soil','🌱','Which type of soil is best for growing crops?',
            ['Sandy','Rocky','Loamy','Clay'],2);
        $this->tf($l4,'Soil fact','Earthworms help to make soil fertile.',true);

        $l5 = $this->mkLesson($sid,'Technology','Energy','Energy Sources');
        $this->mcq($l5,'Solar energy','☀️','Energy from the sun is called ___ energy.',
            ['wind','nuclear','solar','hydro'],2);
        $this->mcq($l5,'Electricity','⚡','Electricity in our homes comes from ___.',
            ['the sun directly','power stations','rain','wind only'],1);
        $this->tf($l5,'Energy fact','We need energy to do work.',true);
        $this->mcq($l5,'Telecommunication','📡','A telephone is used for ___.',
            ['cooking','communication','farming','building'],1);

        $this->command->info('   Science C3: 16 exercises');
    }

    // ── SCIENCE C6 (subject_id = 45) ──────────────────────────────────────
    private function scienceC6(): void
    {
        $sid = 45;

        $l1 = $this->mkLesson($sid,'Health Education','Body Systems','Nervous and Skeletal Systems');
        $this->mcq($l1,'Nervous system','🧠','The brain is part of the ___ system.',
            ['digestive','circulatory','nervous','respiratory'],2);
        $this->mcq($l1,'Skeletal system','🦴','How many bones does an adult human body have?',
            ['106','206','306','406'],1);
        $this->tf($l1,'Spinal cord','The spinal cord connects the brain to the rest of the body.',true);

        $l2 = $this->mkLesson($sid,'Health Education','Reproductive Health','STIs and HIV');
        $this->mcq($l2,'HIV prevention','🛡️','HIV can be prevented by ___.',
            ['sharing needles','blood transfusions without testing','abstinence','eating more food'],2);
        $this->tf($l2,'HIV fact','HIV/AIDS has no cure but can be managed with medication.',true);
        $this->mcq($l2,'Menstrual hygiene','🩺','Good menstrual hygiene helps prevent ___.',
            ['pregnancy','infections','pain only','fatigue'],1);

        $l3 = $this->mkLesson($sid,'Health Education','Public Health','Immunity and Vaccines');
        $this->mcq($l3,'Immunity','💉','Vaccines help the body to ___.',
            ['grow taller','fight diseases','sleep better','run faster'],1);
        $this->tf($l3,'Vaccination','Vaccination is a way of preventing infectious diseases.',true);
        $this->mcq($l3,'First aid burns','🔥','For a minor burn, you should ___.',
            ['put butter on it','cool with running water','cover with cloth immediately','ignore it'],1);

        $l4 = $this->mkLesson($sid,'Environmental Science','Water','Water Purification');
        $this->mcq($l4,'Water purification','💧','Which method purifies water?',
            ['Adding sugar','Boiling','Freezing','Adding salt'],1);
        $this->mcq($l4,'Water cycle','🌧️','Water vapour cools and turns into droplets in a process called ___.',
            ['evaporation','condensation','precipitation','absorption'],1);
        $this->tf($l4,'Waste management','Recycling helps reduce environmental pollution.',true);

        $l5 = $this->mkLesson($sid,'Technology','Electricity','Conductors and Insulators');
        $this->mcq($l5,'Conductor','⚡','Which material conducts electricity best?',
            ['Wood','Plastic','Copper','Rubber'],2);
        $this->mcq($l5,'Insulator','🔌','Which material is an insulator?',
            ['Iron','Copper','Gold','Rubber'],3);
        $this->tf($l5,'Safety','Never touch electrical wires with wet hands.',true);
        $this->mcq($l5,'Forces','🏋️','Which force opposes motion between surfaces?',
            ['Gravity','Tension','Friction','Compression'],2);

        $this->command->info('   Science C6: 15 exercises');
    }
}
