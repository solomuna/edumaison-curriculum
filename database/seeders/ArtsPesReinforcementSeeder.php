<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtsPesReinforcementSeeder extends Seeder
{
    public function run(): void
    {
        $levelIds = [5=>9, 6=>10, 7=>5, 8=>6, 9=>7, 10=>8];
        // level_id in subjects table: 5=C1,6=C2,7=C3,8=C4,9=C5,10=C6
        // But actual DB level_ids: C1=5,C2=6,C3=7,C4=8,C5=9,C6=10
        // We use $subjectLevelId directly

        // Arts and Crafts level_id=5
        $subj = DB::table('subjects')->where('level_id',5)->where('name',"Arts and Crafts")->first();
        if (!$subj) { echo 'Not found: Arts and Crafts level 5' . PHP_EOL; }
        if ($subj) {
            $ilt = DB::table('integrated_themes')->where('subject_id',$subj->id)->first();
            $unit = DB::table('units')->where('integrated_theme_id',$ilt->id)->first();
            if ($unit) {
                $lesson = DB::table('lessons')->where('unit_id',$unit->id)->where('name',"Arts and Crafts Extra C1")->first();
                if (!$lesson) {
                    $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Arts and Crafts Extra C1",'slug'=>"arts-and-crafts-extra-c1",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
                } else { $lesson_id = $lesson->id; }
                $ex_artsandcrafts_5 = [
                    ['title'=>"Colours: Primary",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which are the three primary colours?\", \"options\": \"[\\\"Red, yellow, blue\\\",\\\"Red, green, purple\\\",\\\"Orange, pink, black\\\",\\\"White, grey, brown\\\"]\", \"answer\": 0}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Drawing Tools",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which tool do we use to draw straight lines?\", \"options\": \"[\\\"Brush\\\",\\\"Ruler\\\",\\\"Scissors\\\",\\\"Sponge\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Shapes in Art",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"A circle has no corners.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Painting",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"We use a palette to mix paint colours.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Fill: Art Materials",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"We use ___ and water to make clay soft.\", \"answer\": \"hands\"}, {\"sentence\": \"A ___ is used to cut paper in art class.\", \"answer\": \"scissors\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Match: Art Tools",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Brush\", \"right\": \"Painting\"}, {\"left\": \"Pencil\", \"right\": \"Drawing\"}, {\"left\": \"Scissors\", \"right\": \"Cutting\"}, {\"left\": \"Clay\", \"right\": \"Moulding\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                ];
                foreach ($ex_artsandcrafts_5 as $ex) {
                    if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
                }
            }
        }

        // Arts and Crafts level_id=6
        $subj = DB::table('subjects')->where('level_id',6)->where('name',"Arts and Crafts")->first();
        if (!$subj) { echo 'Not found: Arts and Crafts level 6' . PHP_EOL; }
        if ($subj) {
            $ilt = DB::table('integrated_themes')->where('subject_id',$subj->id)->first();
            $unit = DB::table('units')->where('integrated_theme_id',$ilt->id)->first();
            if ($unit) {
                $lesson = DB::table('lessons')->where('unit_id',$unit->id)->where('name',"Arts and Crafts Extra C2")->first();
                if (!$lesson) {
                    $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Arts and Crafts Extra C2",'slug'=>"arts-and-crafts-extra-c2",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
                } else { $lesson_id = $lesson->id; }
                $ex_artsandcrafts_6 = [
                    ['title'=>"Weaving",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What do we use to weave a basket?\", \"options\": \"[\\\"Paint\\\",\\\"Strips of material\\\",\\\"Clay\\\",\\\"Pencils\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Origami",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What is origami?\", \"options\": \"[\\\"Painting with water\\\",\\\"Drawing with chalk\\\",\\\"The art of paper folding\\\",\\\"Moulding with clay\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Colours: Secondary",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Mixing red and blue makes the secondary colour purple.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Art Appreciation",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Art can only be made using expensive materials.\", \"answer\": false}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Fill: Craft",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"Folding paper to make shapes is called ___.\", \"answer\": \"origami\"}, {\"sentence\": \"A ___ is a woven container made from strips.\", \"answer\": \"basket\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Match: Crafts",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Weaving\", \"right\": \"Making baskets and mats\"}, {\"left\": \"Moulding\", \"right\": \"Shaping clay\"}, {\"left\": \"Painting\", \"right\": \"Applying colours to surfaces\"}, {\"left\": \"Folding\", \"right\": \"Making shapes from paper\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                ];
                foreach ($ex_artsandcrafts_6 as $ex) {
                    if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
                }
            }
        }

        // Arts and Crafts level_id=7
        $subj = DB::table('subjects')->where('level_id',7)->where('name',"Arts and Crafts")->first();
        if (!$subj) { echo 'Not found: Arts and Crafts level 7' . PHP_EOL; }
        if ($subj) {
            $ilt = DB::table('integrated_themes')->where('subject_id',$subj->id)->first();
            $unit = DB::table('units')->where('integrated_theme_id',$ilt->id)->first();
            if ($unit) {
                $lesson = DB::table('lessons')->where('unit_id',$unit->id)->where('name',"Arts and Crafts Extra C3")->first();
                if (!$lesson) {
                    $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Arts and Crafts Extra C3",'slug'=>"arts-and-crafts-extra-c3",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
                } else { $lesson_id = $lesson->id; }
                $ex_artsandcrafts_7 = [
                    ['title'=>"Colour Mixing",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What colour do you get when you mix red and yellow?\", \"options\": \"[\\\"Purple\\\",\\\"Green\\\",\\\"Orange\\\",\\\"Brown\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Sculpture Materials",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which material is best for making a sculpture?\", \"options\": \"[\\\"Paper\\\",\\\"Clay\\\",\\\"Water\\\",\\\"Sand alone\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Perspective",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"In drawing, objects that are further away appear smaller.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Symmetry in Art",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"A butterfly's wings are an example of symmetry in nature.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Fill: Drawing",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"Drawing from memory is called drawing from ___.\", \"answer\": \"imagination\"}, {\"sentence\": \"A ___ is a drawing that shows the plan of a building.\", \"answer\": \"blueprint\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Match: Art Terms",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Portrait\", \"right\": \"Drawing of a person's face\"}, {\"left\": \"Landscape\", \"right\": \"Drawing of outdoor scenery\"}, {\"left\": \"Still life\", \"right\": \"Drawing of objects\"}, {\"left\": \"Abstract\", \"right\": \"Art without realistic images\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                ];
                foreach ($ex_artsandcrafts_7 as $ex) {
                    if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
                }
            }
        }

        // Arts and Crafts level_id=8
        $subj = DB::table('subjects')->where('level_id',8)->where('name',"Arts and Crafts")->first();
        if (!$subj) { echo 'Not found: Arts and Crafts level 8' . PHP_EOL; }
        if ($subj) {
            $ilt = DB::table('integrated_themes')->where('subject_id',$subj->id)->first();
            $unit = DB::table('units')->where('integrated_theme_id',$ilt->id)->first();
            if ($unit) {
                $lesson = DB::table('lessons')->where('unit_id',$unit->id)->where('name',"Arts and Crafts Extra C4")->first();
                if (!$lesson) {
                    $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Arts and Crafts Extra C4",'slug'=>"arts-and-crafts-extra-c4",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
                } else { $lesson_id = $lesson->id; }
                $ex_artsandcrafts_8 = [
                    ['title'=>"Traditional Crafts",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which craft is traditional in the Grassfields of Cameroon?\", \"options\": \"[\\\"Pottery only\\\",\\\"Beadwork and weaving\\\",\\\"Origami\\\",\\\"Watercolour painting\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Colour Wheel",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"On the colour wheel, what is the complementary colour of blue?\", \"options\": \"[\\\"Purple\\\",\\\"Green\\\",\\\"Orange\\\",\\\"Red\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Batik",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Batik is a method of dyeing fabric using wax to create patterns.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Photography",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Photography is considered a form of art.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Fill: Traditional Art",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"Cameroon is known for its beautiful ___ masks used in ceremonies.\", \"answer\": \"carved\"}, {\"sentence\": \"The ___ technique uses wax and dye to create fabric patterns.\", \"answer\": \"batik\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Match: African Crafts",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Kente cloth\", \"right\": \"Woven fabric from West Africa\"}, {\"left\": \"Mudcloth\", \"right\": \"Painted fabric from Mali\"}, {\"left\": \"Beadwork\", \"right\": \"Decorative art using small beads\"}, {\"left\": \"Woodcarving\", \"right\": \"Sculpting designs in wood\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                ];
                foreach ($ex_artsandcrafts_8 as $ex) {
                    if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
                }
            }
        }

        // Arts and Crafts level_id=9
        $subj = DB::table('subjects')->where('level_id',9)->where('name',"Arts and Crafts")->first();
        if (!$subj) { echo 'Not found: Arts and Crafts level 9' . PHP_EOL; }
        if ($subj) {
            $ilt = DB::table('integrated_themes')->where('subject_id',$subj->id)->first();
            $unit = DB::table('units')->where('integrated_theme_id',$ilt->id)->first();
            if ($unit) {
                $lesson = DB::table('lessons')->where('unit_id',$unit->id)->where('name',"Arts and Crafts Extra C5")->first();
                if (!$lesson) {
                    $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Arts and Crafts Extra C5",'slug'=>"arts-and-crafts-extra-c5",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
                } else { $lesson_id = $lesson->id; }
                $ex_artsandcrafts_9 = [
                    ['title'=>"Photography",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What is the most important element in photography?\", \"options\": \"[\\\"The camera brand\\\",\\\"Light\\\",\\\"The photographer\\\"s age\\\",\\\"The size of photo\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Architecture",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What does an architect design?\", \"options\": \"[\\\"Clothes\\\",\\\"Buildings\\\",\\\"Cars\\\",\\\"Paintings\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Ceramics",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Ceramics are objects made from clay and hardened by heat.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Music Notation",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"In music, a note written on a stave tells us the pitch of the sound.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Fill: Art Forms",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"___ is the art of designing and constructing buildings.\", \"answer\": \"Architecture\"}, {\"sentence\": \"A ___ is a three-dimensional work of art made from materials like stone or metal.\", \"answer\": \"sculpture\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Match: Art Forms",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Ceramics\", \"right\": \"Clay objects fired in a kiln\"}, {\"left\": \"Photography\", \"right\": \"Capturing images with a camera\"}, {\"left\": \"Architecture\", \"right\": \"Designing buildings\"}, {\"left\": \"Mosaic\", \"right\": \"Art made from small tiles or pieces\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                ];
                foreach ($ex_artsandcrafts_9 as $ex) {
                    if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
                }
            }
        }

        // Arts and Crafts level_id=10
        $subj = DB::table('subjects')->where('level_id',10)->where('name',"Arts and Crafts")->first();
        if (!$subj) { echo 'Not found: Arts and Crafts level 10' . PHP_EOL; }
        if ($subj) {
            $ilt = DB::table('integrated_themes')->where('subject_id',$subj->id)->first();
            $unit = DB::table('units')->where('integrated_theme_id',$ilt->id)->first();
            if ($unit) {
                $lesson = DB::table('lessons')->where('unit_id',$unit->id)->where('name',"Arts and Crafts Extra C6")->first();
                if (!$lesson) {
                    $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Arts and Crafts Extra C6",'slug'=>"arts-and-crafts-extra-c6",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
                } else { $lesson_id = $lesson->id; }
                $ex_artsandcrafts_10 = [
                    ['title'=>"Art Movements",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which art movement is known for its bold colours and simplified forms?\", \"options\": \"[\\\"Realism\\\",\\\"Impressionism\\\",\\\"Cubism\\\",\\\"Photography\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Theatre",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What is a playwright?\", \"options\": \"[\\\"An actor who plays sport\\\",\\\"A person who writes plays\\\",\\\"A theatre director\\\",\\\"A stage designer\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Music and Culture",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Traditional music plays an important role in preserving Cameroonian culture.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Digital Art",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Computers can be used as tools for creating art.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Fill: Performance Arts",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"A ___ is a story told through singing and acting on stage.\", \"answer\": \"musical\"}, {\"sentence\": \"The ___ is the person who leads an orchestra with a baton.\", \"answer\": \"conductor\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Match: Music Terms",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Melody\", \"right\": \"The main tune of a piece of music\"}, {\"left\": \"Rhythm\", \"right\": \"The beat and pattern of music\"}, {\"left\": \"Harmony\", \"right\": \"Two or more notes played together\"}, {\"left\": \"Tempo\", \"right\": \"The speed of music\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                ];
                foreach ($ex_artsandcrafts_10 as $ex) {
                    if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
                }
            }
        }

        // Physical Education level_id=5
        $subj = DB::table('subjects')->where('level_id',5)->where('name',"Physical Education")->first();
        if (!$subj) { echo 'Not found: Physical Education level 5' . PHP_EOL; }
        if ($subj) {
            $ilt = DB::table('integrated_themes')->where('subject_id',$subj->id)->first();
            $unit = DB::table('units')->where('integrated_theme_id',$ilt->id)->first();
            if ($unit) {
                $lesson = DB::table('lessons')->where('unit_id',$unit->id)->where('name',"Physical Education Extra C1")->first();
                if (!$lesson) {
                    $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Physical Education Extra C1",'slug'=>"physical-education-extra-c1",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
                } else { $lesson_id = $lesson->id; }
                $ex_physicaleducation_5 = [
                    ['title'=>"Basic Movement",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which movement involves jumping from one foot to the other?\", \"options\": \"[\\\"Walking\\\",\\\"Hopping\\\",\\\"Skipping\\\",\\\"Rolling\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Ball Skills",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What is dribbling in football?\", \"options\": \"[\\\"Throwing the ball\\\",\\\"Kicking the ball while running\\\",\\\"Catching the ball\\\",\\\"Heading the ball\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Safety in PE",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"We should always warm up before exercising.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Teamwork",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"In team sports, working together helps the team win.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Fill: Exercise",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"Running fast over a short distance is called a ___.\", \"answer\": \"sprint\"}, {\"sentence\": \"We wear ___ to protect our feet during sports.\", \"answer\": \"shoes\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Match: Sports Equipment",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Football\", \"right\": \"Round ball kicked with feet\"}, {\"left\": \"Racket\", \"right\": \"Used in tennis and badminton\"}, {\"left\": \"Bat\", \"right\": \"Used in cricket and baseball\"}, {\"left\": \"Net\", \"right\": \"Divides the court in volleyball\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                ];
                foreach ($ex_physicaleducation_5 as $ex) {
                    if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
                }
            }
        }

        // Physical Education level_id=6
        $subj = DB::table('subjects')->where('level_id',6)->where('name',"Physical Education")->first();
        if (!$subj) { echo 'Not found: Physical Education level 6' . PHP_EOL; }
        if ($subj) {
            $ilt = DB::table('integrated_themes')->where('subject_id',$subj->id)->first();
            $unit = DB::table('units')->where('integrated_theme_id',$ilt->id)->first();
            if ($unit) {
                $lesson = DB::table('lessons')->where('unit_id',$unit->id)->where('name',"Physical Education Extra C2")->first();
                if (!$lesson) {
                    $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Physical Education Extra C2",'slug'=>"physical-education-extra-c2",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
                } else { $lesson_id = $lesson->id; }
                $ex_physicaleducation_6 = [
                    ['title'=>"Athletics",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"In a relay race, what do runners pass to each other?\", \"options\": \"[\\\"A ball\\\",\\\"A baton\\\",\\\"A flag\\\",\\\"A rope\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Gymnastics",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which of these is a gymnastics movement?\", \"options\": \"[\\\"Swimming\\\",\\\"A forward roll\\\",\\\"A sprint\\\",\\\"Dribbling\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Healthy Lifestyle",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Regular exercise helps keep our bodies healthy and strong.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Fair Play",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Cheating in sport is acceptable if your team is losing.\", \"answer\": false}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Fill: Athletics",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"A ___ race involves jumping over obstacles.\", \"answer\": \"hurdles\"}, {\"sentence\": \"We use a ___ to measure the distance of a long jump.\", \"answer\": \"tape measure\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Match: Athletics Events",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Sprint\", \"right\": \"Short fast race\"}, {\"left\": \"Long jump\", \"right\": \"Jumping for distance from a run-up\"}, {\"left\": \"High jump\", \"right\": \"Jumping over a bar\"}, {\"left\": \"Relay\", \"right\": \"Team race passing a baton\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                ];
                foreach ($ex_physicaleducation_6 as $ex) {
                    if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
                }
            }
        }

        // Physical Education level_id=7
        $subj = DB::table('subjects')->where('level_id',7)->where('name',"Physical Education")->first();
        if (!$subj) { echo 'Not found: Physical Education level 7' . PHP_EOL; }
        if ($subj) {
            $ilt = DB::table('integrated_themes')->where('subject_id',$subj->id)->first();
            $unit = DB::table('units')->where('integrated_theme_id',$ilt->id)->first();
            if ($unit) {
                $lesson = DB::table('lessons')->where('unit_id',$unit->id)->where('name',"Physical Education Extra C3")->first();
                if (!$lesson) {
                    $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Physical Education Extra C3",'slug'=>"physical-education-extra-c3",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
                } else { $lesson_id = $lesson->id; }
                $ex_physicaleducation_7 = [
                    ['title'=>"Team Sports",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"How many players are in a standard football team?\", \"options\": \"[\\\"9\\\",\\\"10\\\",\\\"11\\\",\\\"12\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Swimming Safety",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What should you always do before swimming in a pool?\", \"options\": \"[\\\"Eat a large meal\\\",\\\"Check the depth and obey rules\\\",\\\"Run on the pool edge\\\",\\\"Jump in without checking\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Exercise Benefits",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Exercise helps improve concentration and mental health.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Sports Rules",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"In football, a goal is scored when the ball crosses the goal line.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Fill: Team Sports",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"In basketball, the ball is put into play with a ___ jump.\", \"answer\": \"tip-off\"}, {\"sentence\": \"A player who scores three goals in football is said to have scored a ___.\", \"answer\": \"hat-trick\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Match: Team Sports",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Football\", \"right\": \"11 players per team\"}, {\"left\": \"Basketball\", \"right\": \"5 players per team\"}, {\"left\": \"Volleyball\", \"right\": \"6 players per team\"}, {\"left\": \"Cricket\", \"right\": \"11 players per team\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                ];
                foreach ($ex_physicaleducation_7 as $ex) {
                    if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
                }
            }
        }

        // Physical Education level_id=8
        $subj = DB::table('subjects')->where('level_id',8)->where('name',"Physical Education")->first();
        if (!$subj) { echo 'Not found: Physical Education level 8' . PHP_EOL; }
        if ($subj) {
            $ilt = DB::table('integrated_themes')->where('subject_id',$subj->id)->first();
            $unit = DB::table('units')->where('integrated_theme_id',$ilt->id)->first();
            if ($unit) {
                $lesson = DB::table('lessons')->where('unit_id',$unit->id)->where('name',"Physical Education Extra C4")->first();
                if (!$lesson) {
                    $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Physical Education Extra C4",'slug'=>"physical-education-extra-c4",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
                } else { $lesson_id = $lesson->id; }
                $ex_physicaleducation_8 = [
                    ['title'=>"Field Events",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"In which event do athletes throw a heavy metal ball?\", \"options\": \"[\\\"Discus\\\",\\\"Shot put\\\",\\\"Javelin\\\",\\\"Hammer throw\\\"]\", \"answer\": 1}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Cricket",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"In cricket, how many wickets does each team have?\", \"options\": \"[\\\"10\\\",\\\"11\\\",\\\"9\\\",\\\"12\\\"]\", \"answer\": 0}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Nutrition and Sport",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Athletes need more carbohydrates for energy during training.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Cricket Rules",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"In cricket, a batsman is out when a fielder catches the ball before it bounces.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Fill: Field Events",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The ___ throw involves spinning before releasing a round disc.\", \"answer\": \"discus\"}, {\"sentence\": \"In long jump, the athlete runs and jumps from a ___ board.\", \"answer\": \"take-off\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Match: Athletic Throws",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Shot put\", \"right\": \"Heavy metal ball thrown from shoulder\"}, {\"left\": \"Discus\", \"right\": \"Round disc thrown with spinning motion\"}, {\"left\": \"Javelin\", \"right\": \"Spear-like implement thrown for distance\"}, {\"left\": \"Hammer\", \"right\": \"Heavy ball on a wire, thrown by spinning\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                ];
                foreach ($ex_physicaleducation_8 as $ex) {
                    if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
                }
            }
        }

        // Physical Education level_id=9
        $subj = DB::table('subjects')->where('level_id',9)->where('name',"Physical Education")->first();
        if (!$subj) { echo 'Not found: Physical Education level 9' . PHP_EOL; }
        if ($subj) {
            $ilt = DB::table('integrated_themes')->where('subject_id',$subj->id)->first();
            $unit = DB::table('units')->where('integrated_theme_id',$ilt->id)->first();
            if ($unit) {
                $lesson = DB::table('lessons')->where('unit_id',$unit->id)->where('name',"Physical Education Extra C5")->first();
                if (!$lesson) {
                    $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Physical Education Extra C5",'slug'=>"physical-education-extra-c5",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
                } else { $lesson_id = $lesson->id; }
                $ex_physicaleducation_9 = [
                    ['title'=>"Long Distance Running",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"What is the recommended pacing strategy for a long-distance race?\", \"options\": \"[\\\"Sprint the whole way\\\",\\\"Start fast then slow down\\\",\\\"Run at an even steady pace\\\",\\\"Walk most of the way\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Ballet",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Ballet originated in which country?\", \"options\": \"[\\\"England\\\",\\\"Russia\\\",\\\"France\\\",\\\"Italy\\\"]\", \"answer\": 3}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Training Principles",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Rest and recovery are important parts of any training programme.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Sports Psychology",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Confidence and mental focus can improve athletic performance.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Fill: Distance Running",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"A race of 500 metres requires both speed and ___.\", \"answer\": \"endurance\"}, {\"sentence\": \"Runners breathe rhythmically to avoid getting a ___ in their side.\", \"answer\": \"stitch\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Match: Sports Positions",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Goalkeeper\", \"right\": \"Defends the goal in football\"}, {\"left\": \"Striker\", \"right\": \"Attacks and scores goals\"}, {\"left\": \"Midfielder\", \"right\": \"Links defence and attack\"}, {\"left\": \"Defender\", \"right\": \"Protects the team's goal area\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                ];
                foreach ($ex_physicaleducation_9 as $ex) {
                    if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
                }
            }
        }

        // Physical Education level_id=10
        $subj = DB::table('subjects')->where('level_id',10)->where('name',"Physical Education")->first();
        if (!$subj) { echo 'Not found: Physical Education level 10' . PHP_EOL; }
        if ($subj) {
            $ilt = DB::table('integrated_themes')->where('subject_id',$subj->id)->first();
            $unit = DB::table('units')->where('integrated_theme_id',$ilt->id)->first();
            if ($unit) {
                $lesson = DB::table('lessons')->where('unit_id',$unit->id)->where('name',"Physical Education Extra C6")->first();
                if (!$lesson) {
                    $lesson_id = DB::table('lessons')->insertGetId(['name'=>"Physical Education Extra C6",'slug'=>"physical-education-extra-c6",'unit_id'=>$unit->id,'order'=>2,'created_at'=>now(),'updated_at'=>now()]);
                } else { $lesson_id = $lesson->id; }
                $ex_physicaleducation_10 = [
                    ['title'=>"Olympics",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"How often are the Summer Olympic Games held?\", \"options\": \"[\\\"Every 2 years\\\",\\\"Every 3 years\\\",\\\"Every 4 years\\\",\\\"Every 5 years\\\"]\", \"answer\": 2}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Gymnastics Advanced",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"mcq\", \"questions\": [{\"question\": \"Which of these is an advanced gymnastics skill?\", \"options\": \"[\\\"Forward roll\\\",\\\"Cartwheel\\\",\\\"Headstand\\\",\\\"All of the above\\\"]\", \"answer\": 3}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Sports and Health",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"Competitive sport teaches important values like discipline and perseverance.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Olympic Values",'category'=>'quiz','difficulty'=>'medium','content'=>"{\"type\": \"true_false\", \"statement\": \"The Olympic motto 'Faster, Higher, Stronger' encourages athletes to improve.\", \"answer\": true}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Fill: Olympics",'category'=>'reading','difficulty'=>'medium','content'=>"{\"type\": \"fill_in\", \"items\": [{\"sentence\": \"The Olympic Games were originally held in ancient ___.\", \"answer\": \"Greece\"}, {\"sentence\": \"The Olympic ___ consists of five interlocking rings representing the continents.\", \"answer\": \"symbol\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                    ['title'=>"Match: Olympic Sports",'category'=>'vocabulary','difficulty'=>'medium','content'=>"{\"type\": \"match_pairs\", \"pairs\": [{\"left\": \"Gymnastics\", \"right\": \"Floor, beam, vault, bars\"}, {\"left\": \"Athletics\", \"right\": \"Running, jumping, throwing\"}, {\"left\": \"Swimming\", \"right\": \"Freestyle, backstroke, butterfly\"}, {\"left\": \"Rowing\", \"right\": \"Teams in long narrow boats\"}]}",'is_active'=>true,'lesson_id'=>$lesson_id,'created_at'=>now(),'updated_at'=>now()],
                ];
                foreach ($ex_physicaleducation_10 as $ex) {
                    if (!DB::table('exercises')->where('title',$ex['title'])->where('lesson_id',$lesson_id)->exists()) DB::table('exercises')->insert($ex);
                }
            }
        }

    }
}