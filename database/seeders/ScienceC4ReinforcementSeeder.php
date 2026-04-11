<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScienceC4ReinforcementSeeder extends Seeder
{
    public function run(): void
    {
        $exercises = [

            // ── LESSON 104 — Bones and the Skeleton ─────────────────────────
            [104, 'quiz', 'How many bones does an adult human body have?',
             '{"question":"How many bones does an adult human body have?","options":["206","306","106","406"],"answer":0}'],
            [104, 'quiz', 'What is the main function of the skeleton?',
             '{"question":"What is the main function of the skeleton?","options":["Support and protection","Digestion","Breathing","Pumping blood"],"answer":0}'],
            [104, 'quiz', 'The skull protects the brain.',
             '{"statement":"The skull protects the brain.","answer":true}'],
            [104, 'quiz', 'Bones are made of soft tissue only.',
             '{"statement":"Bones are made of soft tissue only.","answer":false}'],
            [104, 'vocabulary', 'The rib cage protects the ___.',
             '{"sentence":"The rib cage protects the ___.","answer":"heart and lungs","alternatives":["lungs and heart"]}'],
            [104, 'quiz', 'Which part of the skeleton protects the spinal cord?',
             '{"question":"Which part of the skeleton protects the spinal cord?","options":["Vertebral column","Skull","Ribs","Pelvis"],"answer":0}'],
            [104, 'vocabulary', 'The place where two bones meet is called a ___.',
             '{"sentence":"The place where two bones meet is called a ___.","answer":"joint","alternatives":["joints"]}'],

            // ── LESSON 105 — The Senses ──────────────────────────────────────
            [105, 'quiz', 'Which sense organ is used for hearing?',
             '{"question":"Which sense organ is used for hearing?","options":["Ear","Eye","Nose","Skin"],"answer":0}'],
            [105, 'revision', 'Match the sense organ to its function.',
             '{"pairs":[["Eye","Sight"],["Ear","Hearing"],["Nose","Smell"],["Tongue","Taste"],["Skin","Touch"]]}'],
            [105, 'quiz', 'The tongue is used to taste food.',
             '{"statement":"The tongue is used to taste food.","answer":true}'],
            [105, 'vocabulary', 'The sense organ for sight is the ___.',
             '{"sentence":"The sense organ for sight is the ___.","answer":"eye","alternatives":["eyes"]}'],
            [105, 'quiz', 'Which sense organ detects pain and temperature?',
             '{"question":"Which sense organ detects pain and temperature?","options":["Skin","Eye","Ear","Nose"],"answer":0}'],

            // ── LESSON 106 — Water-borne and Insect-borne Diseases ───────────
            [106, 'quiz', 'Which disease is spread by the Anopheles mosquito?',
             '{"question":"Which disease is spread by the Anopheles mosquito?","options":["Malaria","Cholera","Typhoid","Tuberculosis"],"answer":0}'],
            [106, 'quiz', 'Cholera is spread through contaminated ___.',
             '{"question":"Cholera is spread through contaminated ___.","options":["Water and food","Air","Insects","Soil"],"answer":0}'],
            [106, 'quiz', 'Malaria is a water-borne disease.',
             '{"statement":"Malaria is a water-borne disease.","answer":false}'],
            [106, 'quiz', 'Boiling water helps prevent water-borne diseases.',
             '{"statement":"Boiling water helps prevent water-borne diseases.","answer":true}'],
            [106, 'vocabulary', 'Typhoid fever is caused by drinking ___ water.',
             '{"sentence":"Typhoid fever is caused by drinking ___ water.","answer":"contaminated","alternatives":["dirty","polluted"]}'],
            [106, 'quiz', 'Which of these helps prevent malaria?',
             '{"question":"Which of these helps prevent malaria?","options":["Using mosquito nets","Drinking clean water","Washing hands","Eating vegetables"],"answer":0}'],

            // ── LESSON 107 — Hygiene and First Aid ──────────────────────────
            [107, 'quiz', 'What should you do first when someone is bleeding?',
             '{"question":"What should you do first when someone is bleeding?","options":["Apply pressure to the wound","Give them water","Call a friend","Run away"],"answer":0}'],
            [107, 'quiz', 'Washing hands regularly helps prevent the spread of disease.',
             '{"statement":"Washing hands regularly helps prevent the spread of disease.","answer":true}'],
            [107, 'vocabulary', 'First aid is the ___ help given to an injured person.',
             '{"sentence":"First aid is the ___ help given to an injured person.","answer":"immediate","alternatives":["first","emergency"]}'],
            [107, 'quiz', 'Which item is NOT found in a first aid kit?',
             '{"question":"Which item is NOT found in a first aid kit?","options":["Hammer","Bandage","Antiseptic","Plaster"],"answer":0}'],
            [107, 'quiz', 'Personal hygiene includes which of the following?',
             '{"question":"Personal hygiene includes which of the following?","options":["Brushing teeth daily","Eating junk food","Sleeping late","Drinking soda"],"answer":0}'],

            // ── LESSON 108 — Balanced Diet ───────────────────────────────────
            [108, 'quiz', 'Which nutrient gives the body energy?',
             '{"question":"Which nutrient gives the body energy?","options":["Carbohydrates","Vitamins","Minerals","Water"],"answer":0}'],
            [108, 'quiz', 'Which food group helps build and repair body tissues?',
             '{"question":"Which food group helps build and repair body tissues?","options":["Proteins","Fats","Carbohydrates","Vitamins"],"answer":0}'],
            [108, 'quiz', 'A balanced diet contains all the necessary nutrients.',
             '{"statement":"A balanced diet contains all the necessary nutrients.","answer":true}'],
            [108, 'vocabulary', 'Fruits and vegetables are rich in ___ and minerals.',
             '{"sentence":"Fruits and vegetables are rich in ___ and minerals.","answer":"vitamins","alternatives":["vitamin"]}'],
            [108, 'revision', 'Match the food to its nutrient group.',
             '{"pairs":[["Rice","Carbohydrates"],["Meat","Proteins"],["Butter","Fats"],["Orange","Vitamins"],["Milk","Calcium"]]}'],
            [108, 'quiz', 'What is the role of water in the body?',
             '{"question":"What is the role of water in the body?","options":["Regulates body temperature and transports nutrients","Builds muscles","Provides energy","Protects organs"],"answer":0}'],

            // ── LESSON 109 — Animals and their Habitats ──────────────────────
            [109, 'quiz', 'Where does a fish live?',
             '{"question":"Where does a fish live?","options":["Water","Desert","Forest","Underground"],"answer":0}'],
            [109, 'revision', 'Match the animal to its habitat.',
             '{"pairs":[["Fish","Water"],["Eagle","Sky/Trees"],["Mole","Underground"],["Camel","Desert"],["Frog","Water and Land"]]}'],
            [109, 'quiz', 'Animals adapt to their environment to survive.',
             '{"statement":"Animals adapt to their environment to survive.","answer":true}'],
            [109, 'vocabulary', 'Animals that live in water are called ___ animals.',
             '{"sentence":"Animals that live in water are called ___ animals.","answer":"aquatic","alternatives":["water"]}'],
            [109, 'quiz', 'Which animal is adapted to live in the desert?',
             '{"question":"Which animal is adapted to live in the desert?","options":["Camel","Whale","Frog","Penguin"],"answer":0}'],

            // ── LESSON 110 — Plants and Seeds ────────────────────────────────
            [110, 'quiz', 'What do seeds need to germinate?',
             '{"question":"What do seeds need to germinate?","options":["Water, warmth and air","Only sunlight","Only water","Cold temperature"],"answer":0}'],
            [110, 'quiz', 'All plants produce seeds.',
             '{"statement":"All plants produce seeds.","answer":false}'],
            [110, 'vocabulary', 'The process by which a seed develops into a plant is called ___.',
             '{"sentence":"The process by which a seed develops into a plant is called ___.","answer":"germination","alternatives":["sprouting"]}'],
            [110, 'quiz', 'Which part of the plant absorbs water from the soil?',
             '{"question":"Which part of the plant absorbs water from the soil?","options":["Roots","Leaves","Stem","Flower"],"answer":0}'],
            [110, 'quiz', 'What is the function of leaves in a plant?',
             '{"question":"What is the function of leaves in a plant?","options":["Make food through photosynthesis","Absorb water","Support the plant","Attract insects"],"answer":0}'],

            // ── LESSON 111 — Properties of Matter ───────────────────────────
            [111, 'quiz', 'Which of these is a property of matter?',
             '{"question":"Which of these is a property of matter?","options":["Mass and volume","Speed and direction","Colour only","Sound only"],"answer":0}'],
            [111, 'quiz', 'What are the three states of matter?',
             '{"question":"What are the three states of matter?","options":["Solid, liquid, gas","Hot, warm, cold","Wood, water, air","Heavy, light, medium"],"answer":0}'],
            [111, 'quiz', 'A solid has a definite shape.',
             '{"statement":"A solid has a definite shape.","answer":true}'],
            [111, 'quiz', 'A gas has a fixed volume.',
             '{"statement":"A gas has a fixed volume.","answer":false}'],
            [111, 'vocabulary', 'When a solid is heated it may turn into a ___.',
             '{"sentence":"When a solid is heated it may turn into a ___.","answer":"liquid","alternatives":["liquid or gas"]}'],
            [111, 'revision', 'Match the state of matter to its property.',
             '{"pairs":[["Solid","Definite shape and volume"],["Liquid","Definite volume, no fixed shape"],["Gas","No definite shape or volume"]]}'],
            [111, 'quiz', 'Which state of matter can be compressed easily?',
             '{"question":"Which state of matter can be compressed easily?","options":["Gas","Solid","Liquid","None of them"],"answer":0}'],

            // ── LESSON 112 — Water and Pollution ─────────────────────────────
            [112, 'quiz', 'What percentage of the Earth surface is covered by water?',
             '{"question":"What percentage of the Earth surface is covered by water?","options":["About 70%","About 30%","About 50%","About 90%"],"answer":0}'],
            [112, 'quiz', 'Throwing rubbish in rivers causes water pollution.',
             '{"statement":"Throwing rubbish in rivers causes water pollution.","answer":true}'],
            [112, 'vocabulary', 'Water changes from liquid to gas through ___.',
             '{"sentence":"Water changes from liquid to gas through ___.","answer":"evaporation","alternatives":["evaporating"]}'],
            [112, 'quiz', 'Which of these is NOT a source of clean water?',
             '{"question":"Which of these is NOT a source of clean water?","options":["Stagnant pond","Borehole","Tap water","Rain water"],"answer":0}'],
            [112, 'quiz', 'What is the best way to purify water at home?',
             '{"question":"What is the best way to purify water at home?","options":["Boiling","Adding sugar","Freezing","Leaving it in the sun"],"answer":0}'],

            // ── LESSON 113 — Types of Soil ───────────────────────────────────
            [113, 'quiz', 'Which type of soil is best for growing crops?',
             '{"question":"Which type of soil is best for growing crops?","options":["Loamy soil","Sandy soil","Clay soil","Rocky soil"],"answer":0}'],
            [113, 'quiz', 'Which soil retains the most water?',
             '{"question":"Which soil retains the most water?","options":["Clay soil","Sandy soil","Loamy soil","Gravel"],"answer":0}'],
            [113, 'quiz', 'Sandy soil drains water very quickly.',
             '{"statement":"Sandy soil drains water very quickly.","answer":true}'],
            [113, 'vocabulary', 'The layer of dead leaves and animals that enriches soil is called ___.',
             '{"sentence":"The layer of dead leaves and animals that enriches soil is called ___.","answer":"humus","alternatives":["organic matter"]}'],
            [113, 'revision', 'Match the soil type to its property.',
             '{"pairs":[["Sandy soil","Drains quickly, poor in nutrients"],["Clay soil","Holds water, heavy"],["Loamy soil","Best for farming, balanced"]]}'],
            [113, 'quiz', 'What do earthworms do to soil?',
             '{"question":"What do earthworms do to soil?","options":["Loosen and enrich it","Make it hard","Dry it out","Remove minerals"],"answer":0}'],

            // ── LESSON 114 — Simple Machines ─────────────────────────────────
            [114, 'quiz', 'Which of these is a simple machine?',
             '{"question":"Which of these is a simple machine?","options":["Lever","Computer","Television","Radio"],"answer":0}'],
            [114, 'quiz', 'A see-saw is an example of which simple machine?',
             '{"question":"A see-saw is an example of which simple machine?","options":["Lever","Pulley","Wheel and axle","Inclined plane"],"answer":0}'],
            [114, 'quiz', 'Simple machines make work easier.',
             '{"statement":"Simple machines make work easier.","answer":true}'],
            [114, 'vocabulary', 'A ramp is an example of an ___ plane.',
             '{"sentence":"A ramp is an example of an ___ plane.","answer":"inclined","alternatives":["sloped"]}'],
            [114, 'revision', 'Match the simple machine to its example.',
             '{"pairs":[["Lever","See-saw"],["Pulley","Well bucket"],["Inclined plane","Ramp"],["Wheel and axle","Bicycle wheel"],["Screw","Bottle cap"]]}'],
            [114, 'quiz', 'Which simple machine is used to lift heavy loads over a height?',
             '{"question":"Which simple machine is used to lift heavy loads over a height?","options":["Pulley","Lever","Wedge","Screw"],"answer":0}'],

            // ── LESSON 115 — Energy Sources ──────────────────────────────────
            [115, 'quiz', 'Which of these is a renewable energy source?',
             '{"question":"Which of these is a renewable energy source?","options":["Solar energy","Coal","Petroleum","Natural gas"],"answer":0}'],
            [115, 'quiz', 'Where does solar energy come from?',
             '{"question":"Where does solar energy come from?","options":["The Sun","The Moon","The Wind","The Earth"],"answer":0}'],
            [115, 'quiz', 'Coal is a non-renewable energy source.',
             '{"statement":"Coal is a non-renewable energy source.","answer":true}'],
            [115, 'vocabulary', 'Energy from moving water is called ___ energy.',
             '{"sentence":"Energy from moving water is called ___ energy.","answer":"hydroelectric","alternatives":["hydro","water"]}'],
            [115, 'revision', 'Match the energy source to its type.',
             '{"pairs":[["Solar","Renewable"],["Wind","Renewable"],["Coal","Non-renewable"],["Petroleum","Non-renewable"],["Hydroelectric","Renewable"]]}'],
            [115, 'quiz', 'Why should we save energy?',
             '{"question":"Why should we save energy?","options":["To protect the environment and reduce costs","To make machines work faster","To increase pollution","To use more fossil fuels"],"answer":0}'],

            // ── LESSON 116 — Electric Circuits ───────────────────────────────
            [116, 'quiz', 'What does an electric circuit need to work?',
             '{"question":"What does an electric circuit need to work?","options":["A complete path for electricity to flow","Only a battery","Only a bulb","A switch only"],"answer":0}'],
            [116, 'quiz', 'Which material conducts electricity?',
             '{"question":"Which material conducts electricity?","options":["Copper wire","Plastic","Rubber","Wood"],"answer":0}'],
            [116, 'quiz', 'A switch opens and closes an electric circuit.',
             '{"statement":"A switch opens and closes an electric circuit.","answer":true}'],
            [116, 'quiz', 'Rubber is a good conductor of electricity.',
             '{"statement":"Rubber is a good conductor of electricity.","answer":false}'],
            [116, 'vocabulary', 'Materials that do not allow electricity to pass through are called ___.',
             '{"sentence":"Materials that do not allow electricity to pass through are called ___.","answer":"insulators","alternatives":["insulator"]}'],
            [116, 'quiz', 'What safety rule should you follow around electricity?',
             '{"question":"What safety rule should you follow around electricity?","options":["Never touch live wires","Always touch wires with wet hands","Use metal tools near sockets","Leave switches on always"],"answer":0}'],
            [116, 'quiz', 'In a series circuit, if one bulb goes out, what happens?',
             '{"question":"In a series circuit, if one bulb goes out, what happens?","options":["All bulbs go out","Other bulbs stay on","Nothing changes","Bulbs get brighter"],"answer":0}'],
        ];

        $schoolYearId = DB::table('school_years')->where('is_current', true)->value('id') ?? 1;
        $inserted = 0;

        foreach ($exercises as [$lessonId, $category, $title, $content]) {
            $exists = DB::table('exercises')
                ->where('lesson_id', $lessonId)
                ->where('title', $title)
                ->exists();
            if ($exists) continue;

            DB::table('exercises')->insert([
                'lesson_id'    => $lessonId,
                'title'        => $title,
                'category'     => $category,
                'difficulty'   => 'medium',
                'content'      => $content,
                'instructions' => match($category) {
                    'quiz'        => 'Choose the correct answer.',
                    'quiz'  => 'Is this statement true or false?',
                    'vocabulary'     => 'Fill in the missing word.',
                    'revision' => 'Match each item to its correct pair.',
                    default      => 'Complete the exercise.',
                },
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
            $inserted++;
        }

        $this->command->info("   Science C4 : {$inserted} exercices ajoutés");
    }
}
