<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtsPesVocationalSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([5=>1,6=>2,7=>3,8=>4,9=>5,10=>6] as $levelId=>$class) {
            $artSid = $this->mkSubject($levelId,'Arts and Crafts','arts-crafts-c'.$class,'🎨');
            $pesSid = $this->mkSubject($levelId,'Physical Education','pes-c'.$class,'⚽');
            $this->{'arts'.$class}($artSid);
            $this->{'pes'.$class}($pesSid);
        }
        // Vocational subjects for C5 and C6 only
        foreach ([9=>5,10=>6] as $levelId=>$class) {
            $vocSid = $this->mkSubject($levelId,'Home Economics and Vocational Skills','hevs-c'.$class,'🏠');
            $this->{'vocational'.$class}($vocSid);
        }
        $this->command->info('✅ Arts, PES and Vocational seeded C1-C6');
    }

    private function mkSubject(int $levelId, string $name, string $slug, string $icon): int
    {
        $existing = DB::table('subjects')->where('level_id',$levelId)->where('name',$name)->value('id');
        if ($existing) return $existing;
        return DB::table('subjects')->insertGetId([
            'level_id'=>$levelId,'name'=>$name,'slug'=>$slug,
            'icon'=>$icon,'is_active'=>true,'created_at'=>now(),'updated_at'=>now(),
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

    private function mcq(int $lid, string $title, string $cat, string $ill, string $q, array $opts, int $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>$cat,
            'content'=>json_encode(['type'=>'mcq','illustration'=>$ill,
                'questions'=>[['text'=>$q,'options'=>$opts,'answer'=>$ans]]]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function tf(int $lid, string $title, string $cat, string $stmt, bool $ans): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>$cat,
            'content'=>json_encode(['type'=>'true_false','illustration'=>'🎨','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    // ═══ ARTS ═════════════════════════════════════════════════════════════
    private function arts1(int $sid): void
    {
        $l = $this->mkLesson($sid,'Drawing','Basic Drawing','Colours and Shapes');
        $this->mcq($l,'Primary colours','quiz','🎨','Which are the three primary colours?',['Red, green, blue','Red, yellow, blue','Orange, green, purple','Pink, white, black'],1);
        $this->mcq($l,'Mixing colours','quiz','🎨','Red + Yellow = ___.',['Green','Orange','Purple','Brown'],1);
        $this->tf($l,'Colour fact','quiz','Blue and yellow make green.',true);
        $this->mcq($l,'Art tools','quiz','🖌️','Which tool do we use to paint?',['Pencil','Ruler','Brush','Scissors'],2);
        $this->command->info('   Arts C1: 4');
    }

    private function arts2(int $sid): void
    {
        $l = $this->mkLesson($sid,'Drawing','Shapes and Patterns','2D Shapes in Art');
        $this->mcq($l,'Secondary colours','quiz','🎨','Which is a secondary colour?',['Red','Blue','Green','Yellow'],2);
        $this->mcq($l,'Art elements','quiz','🎨','A ___ is used to separate areas of colour.',['line','eraser','glue','staple'],0);
        $this->tf($l,'Art fact','quiz','We can create patterns by repeating shapes.',true);
        $this->mcq($l,'Collage','quiz','✂️','A collage is made by ___ materials onto paper.',['drawing','painting','gluing cut','writing'],2);
        $this->command->info('   Arts C2: 4');
    }

    private function arts3(int $sid): void
    {
        $l = $this->mkLesson($sid,'Craft','Craft Work','Weaving and Modelling');
        $this->mcq($l,'Weaving','quiz','🧵','Weaving involves interlacing ___ threads.',['vertical and horizontal','only vertical','only horizontal','diagonal only'],0);
        $this->mcq($l,'Modelling material','quiz','🏺','Clay is used for ___.',['painting','weaving','modelling/sculpting','printing'],2);
        $this->tf($l,'Craft fact','quiz','Tie and dye is a method of decorating fabric.',true);
        $this->mcq($l,'Printing','quiz','🖨️','A simple printing block can be made from ___.',['glass','potato or sponge','metal only','plastic only'],1);
        $this->command->info('   Arts C3: 4');
    }

    private function arts4(int $sid): void
    {
        $l = $this->mkLesson($sid,'Design','Design and Technology','Technical Drawing');
        $this->mcq($l,'Perspective','quiz','🎨','Drawing objects smaller when far away creates ___.',['distortion','perspective','reflection','shadow'],1);
        $this->mcq($l,'Warm colours','quiz','🎨','Which are warm colours?',['Blue, green, purple','Red, orange, yellow','Black, grey, white','Green, teal, cyan'],1);
        $this->tf($l,'Design fact','quiz','A sketch is a quick, rough drawing.',true);
        $this->mcq($l,'Batik','quiz','🎨','Batik is a method of decorating ___ using wax.',['paper','wood','fabric','clay'],2);
        $this->command->info('   Arts C4: 4');
    }

    private function arts5(int $sid): void
    {
        $l = $this->mkLesson($sid,'Fine Arts','Painting Techniques','Shading and Texture');
        $this->mcq($l,'Shading','quiz','🎨','Shading in drawing creates the illusion of ___.',['colour only','depth and form','movement','sound'],1);
        $this->mcq($l,'Texture in art','quiz','🎨','Texture in art refers to the ___ surface quality.',['colour','size','visual or tactile','weight'],2);
        $this->tf($l,'Art fact','quiz','Complementary colours are opposite each other on the colour wheel.',true);
        $this->mcq($l,'Portrait','quiz','🖼️','A portrait is a drawing or painting of a ___.',['landscape','building','person\'s face','animal only'],2);
        $this->command->info('   Arts C5: 4');
    }

    private function arts6(int $sid): void
    {
        $l = $this->mkLesson($sid,'Art Appreciation','Famous Art','Art History');
        $this->mcq($l,'Art movements','quiz','🎨','Impressionism is an art style that focuses on ___.',['exact detail','light and colour effects','dark colours only','geometric shapes'],1);
        $this->mcq($l,'Famous artists','quiz','🖼️','Leonardo da Vinci painted the ___.',['Starry Night','Mona Lisa','Sunflowers','Water Lilies'],1);
        $this->tf($l,'Art history','quiz','Abstract art does not represent real objects directly.',true);
        $this->mcq($l,'African art','quiz','🏺','Traditional African art often uses ___ to tell stories.',['photographs','symbols and patterns','computers','writing'],1);
        $this->command->info('   Arts C6: 4');
    }

    // ═══ PES ══════════════════════════════════════════════════════════════
    private function pes1(int $sid): void
    {
        $l = $this->mkLesson($sid,'Physical Education','Games','Basic Games and Movement');
        $this->mcq($l,'Exercise benefits','quiz','⚽','Exercise keeps our bodies ___.',['weak','healthy and strong','tired all day','sick'],1);
        $this->mcq($l,'Running','quiz','🏃','Running is a form of ___ exercise.',['mental','physical','artistic','musical'],1);
        $this->tf($l,'PE fact','quiz','Playing games helps us stay fit and healthy.',true);
        $this->mcq($l,'Team sport','quiz','⚽','Football is played by ___ team(s).',['1','2','3','4'],1);
        $this->command->info('   PES C1: 4');
    }

    private function pes2(int $sid): void
    {
        $l = $this->mkLesson($sid,'Physical Education','Athletics','Running and Jumping');
        $this->mcq($l,'Long jump','quiz','🏅','In the long jump, athletes jump for ___.',['height','distance','style','speed only'],1);
        $this->mcq($l,'Relay race','quiz','🏃','In a relay race, athletes pass a ___.',['ball','baton','flag','rope'],1);
        $this->tf($l,'Athletics fact','quiz','Stretching before exercise helps prevent injuries.',true);
        $this->mcq($l,'Safety in PE','quiz','⚕️','Before exercise, we should ___.',['eat a large meal','warm up','sleep','drink cold water'],1);
        $this->command->info('   PES C2: 4');
    }

    private function pes3(int $sid): void
    {
        $l = $this->mkLesson($sid,'Physical Education','Team Sports','Rules of Football');
        $this->mcq($l,'Football players','quiz','⚽','How many players are in a football team?',['9','10','11','12'],2);
        $this->mcq($l,'Offside rule','quiz','⚽','A goal scored directly from a corner kick is ___.',['always valid','always offside','valid if conditions are met','never valid'],2);
        $this->tf($l,'Football fact','quiz','The goalkeeper is the only player allowed to use hands.',true);
        $this->mcq($l,'Fair play','quiz','🤝','Fair play in sport means ___.',['winning at all costs','playing by the rules and respecting opponents','cheating to win','ignoring the referee'],1);
        $this->command->info('   PES C3: 4');
    }

    private function pes4(int $sid): void
    {
        $l = $this->mkLesson($sid,'Physical Education','Athletics','Track and Field');
        $this->mcq($l,'Sprint distance','quiz','🏃','A 100m sprint is a ___ race.',['long distance','middle distance','short sprint','obstacle'],2);
        $this->mcq($l,'High jump technique','quiz','🏅','In the high jump, athletes jump over a ___.',['rope','net','bar','wall'],2);
        $this->tf($l,'Athletics fact','quiz','Athletics includes both track and field events.',true);
        $this->mcq($l,'Nutrition and sport','quiz','🥗','Athletes need ___ for energy.',['only water','carbohydrates and protein','only sugar','only fat'],1);
        $this->command->info('   PES C4: 4');
    }

    private function pes5(int $sid): void
    {
        $l = $this->mkLesson($sid,'Physical Education','Health and Fitness','Fitness and Lifestyle');
        $this->mcq($l,'Cardiovascular fitness','quiz','❤️','Cardiovascular fitness refers to the health of your ___ and lungs.',['muscles','heart','bones','skin'],1);
        $this->mcq($l,'BMI','quiz','⚖️','BMI is used to measure ___.',['height only','fitness level','body weight relative to height','muscle strength'],2);
        $this->tf($l,'Health fact','quiz','Regular exercise reduces the risk of heart disease.',true);
        $this->mcq($l,'Drug abuse in sport','quiz','⚠️','Using performance-enhancing drugs in sport is ___.',['encouraged','legal everywhere','banned and unfair','required'],2);
        $this->command->info('   PES C5: 4');
    }

    private function pes6(int $sid): void
    {
        $l = $this->mkLesson($sid,'Physical Education','Sport and Society','Sport in Cameroon');
        $this->mcq($l,'Cameroon football','quiz','🏆','The Cameroon national football team is called ___.',['The Lions','The Eagles','The Indomitable Lions','The Black Stars'],2);
        $this->mcq($l,'Olympic Games','quiz','🏅','The Olympic Games are held every ___ years.',['2','4','6','8'],1);
        $this->tf($l,'Sport fact','quiz','Sport promotes national unity and healthy living.',true);
        $this->mcq($l,'Sportsmanship','quiz','🤝','Good sportsmanship means ___.',['cheating to win','being gracious in both victory and defeat','ignoring teammates','arguing with referees'],1);
        $this->command->info('   PES C6: 4');
    }

    // ═══ VOCATIONAL C5/C6 ════════════════════════════════════════════════
    private function vocational5(int $sid): void
    {
        $l = $this->mkLesson($sid,'Home Economics','Food and Nutrition','Cooking and Kitchen Safety');
        $this->mcq($l,'Food groups','quiz','🥗','Which food group gives us energy?',['Proteins','Vitamins','Carbohydrates','Minerals'],2);
        $this->mcq($l,'Kitchen safety','quiz','🔥','When cooking, you should always ___.',['wear loose clothing','keep flammable items near the stove','use oven gloves','leave the stove unattended'],2);
        $this->mcq($l,'Hygiene in cooking','quiz','🧼','Before cooking, you must always ___ your hands.',['dry','wash','colour','paint'],1);
        $this->tf($l,'Nutrition fact','quiz','A balanced diet contains all six classes of food.',true);
        $this->mcq($l,'Budgeting','quiz','💰','A budget helps you plan how to ___ your money.',['waste','hide','spend wisely','borrow'],2);
        $this->command->info('   Vocational C5: 5');
    }

    private function vocational6(int $sid): void
    {
        $l = $this->mkLesson($sid,'Home Economics','Life Skills','Entrepreneurship and Life Skills');
        $this->mcq($l,'Entrepreneurship','quiz','💼','An entrepreneur is someone who ___.',['only works for others','starts and runs a business','avoids work','only studies'],1);
        $this->mcq($l,'Business plan','quiz','📋','A business plan helps you to ___.',['waste money','plan your business activities','avoid customers','ignore competition'],1);
        $this->mcq($l,'Consumer rights','quiz','🛒','As a consumer, you have the right to ___.',['buy faulty goods','be cheated','receive quality goods and information','pay any price demanded'],2);
        $this->tf($l,'Entrepreneur fact','quiz','Entrepreneurs create jobs and contribute to the economy.',true);
        $this->mcq($l,'Savings','quiz','🏦','Saving money means ___.',['spending all your money','keeping some money for future use','borrowing from friends','wasting resources'],1);
        $this->command->info('   Vocational C6: 5');
    }
}
