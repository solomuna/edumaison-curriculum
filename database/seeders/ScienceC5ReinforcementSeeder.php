<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScienceC5ReinforcementSeeder extends Seeder
{
    public function run(): void
    {
        $exercises = [

            // ── LESSON 117 — Digestive and Circulatory Systems ───────────────
            [117,'quiz','What is the function of the digestive system?',
             '{"question":"What is the function of the digestive system?","options":["Break down food into nutrients","Pump blood around the body","Help us breathe","Control body movements"],"answer":0}'],
            [117,'quiz','Which organ pumps blood around the body?',
             '{"question":"Which organ pumps blood around the body?","options":["Heart","Liver","Lungs","Kidney"],"answer":0}'],
            [117,'quiz','Where does digestion begin?',
             '{"question":"Where does digestion begin?","options":["Mouth","Stomach","Small intestine","Large intestine"],"answer":0}'],
            [117,'quiz','What do red blood cells carry?',
             '{"question":"What do red blood cells carry?","options":["Oxygen","Food","Water","Waste"],"answer":0}'],
            [117,'vocabulary','The ___ is the longest part of the digestive system.',
             '{"sentence":"The ___ is the longest part of the digestive system.","answer":"small intestine","alternatives":["intestine"]}'],
            [117,'revision','Match the organ to its function.',
             '{"pairs":[["Heart","Pumps blood"],["Stomach","Digests food"],["Small intestine","Absorbs nutrients"],["Large intestine","Absorbs water"],["Liver","Produces bile"]]}'],

            // ── LESSON 118 — Respiratory and Excretory Systems ───────────────
            [118,'quiz','What is the main organ of the respiratory system?',
             '{"question":"What is the main organ of the respiratory system?","options":["Lungs","Heart","Kidney","Liver"],"answer":0}'],
            [118,'quiz','What gas do we breathe in?',
             '{"question":"What gas do we breathe in?","options":["Oxygen","Carbon dioxide","Nitrogen","Hydrogen"],"answer":0}'],
            [118,'quiz','Which organ filters waste from the blood?',
             '{"question":"Which organ filters waste from the blood?","options":["Kidney","Lung","Heart","Stomach"],"answer":0}'],
            [118,'vocabulary','The ___ system removes waste products from the body.',
             '{"sentence":"The ___ system removes waste products from the body.","answer":"excretory","alternatives":["excretion"]}'],
            [118,'quiz','What is the waste gas produced by the body?',
             '{"question":"What is the waste gas produced by the body?","options":["Carbon dioxide","Oxygen","Nitrogen","Steam"],"answer":0}'],

            // ── LESSON 119 — Puberty and Adolescence ─────────────────────────
            [119,'quiz','Puberty is the process by which a child becomes an ___.',
             '{"question":"Puberty is the process by which a child becomes an ___.","options":["Adult","Infant","Teenager only","Senior"],"answer":0}'],
            [119,'quiz','Which hormone controls puberty in boys?',
             '{"question":"Which hormone controls puberty in boys?","options":["Testosterone","Oestrogen","Insulin","Adrenaline"],"answer":0}'],
            [119,'quiz','Which of these is a change during puberty in girls?',
             '{"question":"Which of these is a change during puberty in girls?","options":["Menstruation begins","Voice deepens","Facial hair grows","Muscles enlarge rapidly"],"answer":0}'],
            [119,'vocabulary','Changes during puberty are controlled by ___.',
             '{"sentence":"Changes during puberty are controlled by ___.","answer":"hormones","alternatives":["hormone"]}'],
            [119,'quiz','What is adolescence?',
             '{"question":"What is adolescence?","options":["The period of transition from childhood to adulthood","A type of disease","A food group","A body organ"],"answer":0}'],

            // ── LESSON 120 — STIs and HIV Prevention ─────────────────────────
            [120,'quiz','What does HIV stand for?',
             '{"question":"What does HIV stand for?","options":["Human Immunodeficiency Virus","Human Infection Virus","High Immunity Virus","Human Intestinal Virus"],"answer":0}'],
            [120,'quiz','How can HIV be prevented?',
             '{"question":"How can HIV be prevented?","options":["Abstinence and safe behaviour","Eating more fruits","Drinking clean water","Taking vitamins"],"answer":0}'],
            [120,'quiz','STI stands for ___.',
             '{"question":"STI stands for ___.","options":["Sexually Transmitted Infection","Skin Tissue Infection","Standard Treatment Index","Social Training Institute"],"answer":0}'],
            [120,'vocabulary','HIV weakens the body\'s ___ system.',
             '{"sentence":"HIV weakens the body\'s ___ system.","answer":"immune","alternatives":["immunity","immunological"]}'],
            [120,'quiz','Which of these helps prevent STIs?',
             '{"question":"Which of these helps prevent STIs?","options":["Abstinence","Sharing needles","Multiple partners","Not visiting the doctor"],"answer":0}'],

            // ── LESSON 121 — Non-communicable Diseases ───────────────────────
            [121,'quiz','Which of these is a non-communicable disease?',
             '{"question":"Which of these is a non-communicable disease?","options":["Diabetes","Malaria","Cholera","Tuberculosis"],"answer":0}'],
            [121,'quiz','What is a major cause of heart disease?',
             '{"question":"What is a major cause of heart disease?","options":["Poor diet and lack of exercise","Mosquito bites","Contaminated water","Bacteria"],"answer":0}'],
            [121,'vocabulary','A disease that cannot be passed from person to person is called a ___ disease.',
             '{"sentence":"A disease that cannot be passed from person to person is called a ___ disease.","answer":"non-communicable","alternatives":["non communicable"]}'],
            [121,'quiz','Which lifestyle habit helps prevent non-communicable diseases?',
             '{"question":"Which lifestyle habit helps prevent non-communicable diseases?","options":["Regular exercise","Smoking","Eating junk food","Staying indoors"],"answer":0}'],
            [121,'quiz','Cancer is an example of a ___.',
             '{"question":"Cancer is an example of a ___.","options":["Non-communicable disease","Communicable disease","Water-borne disease","Insect-borne disease"],"answer":0}'],

            // ── LESSON 122 — Vaccines and Immunity ───────────────────────────
            [122,'quiz','What is a vaccine?',
             '{"question":"What is a vaccine?","options":["A substance that helps the body fight disease","A type of food","A pain killer","A type of vitamin"],"answer":0}'],
            [122,'quiz','What does immunity mean?',
             '{"question":"What does immunity mean?","options":["The ability to resist disease","A type of medicine","A disease","A nutrient"],"answer":0}'],
            [122,'vocabulary','___ is when the body becomes resistant to a disease after a vaccine.',
             '{"sentence":"___ is when the body becomes resistant to a disease after a vaccine.","answer":"Immunity","alternatives":["immune response"]}'],
            [122,'quiz','Which disease has been almost eliminated by vaccination?',
             '{"question":"Which disease has been almost eliminated by vaccination?","options":["Polio","Malaria","Cholera","Typhoid"],"answer":0}'],
            [122,'quiz','Who provides immunisation programmes in Cameroon?',
             '{"question":"Who provides immunisation programmes in Cameroon?","options":["Ministry of Public Health","Ministry of Finance","Ministry of Education","Ministry of Agriculture"],"answer":0}'],

            // ── LESSON 123 — Animal Habitats and Reproduction ────────────────
            [123,'quiz','What is a habitat?',
             '{"question":"What is a habitat?","options":["The natural home of an animal","A type of food","A body part","A weather condition"],"answer":0}'],
            [123,'revision','Match the animal to its habitat.',
             '{"pairs":[["Fish","River/Sea"],["Camel","Desert"],["Polar bear","Arctic"],["Monkey","Forest"],["Earthworm","Soil"]]}'],
            [123,'quiz','How do mammals reproduce?',
             '{"question":"How do mammals reproduce?","options":["By giving birth to live young","By laying eggs","By budding","By spores"],"answer":0}'],
            [123,'vocabulary','Animals that lay eggs are called ___ animals.',
             '{"sentence":"Animals that lay eggs are called ___ animals.","answer":"oviparous","alternatives":["egg-laying"]}'],
            [123,'quiz','Which animal uses camouflage to survive?',
             '{"question":"Which animal uses camouflage to survive?","options":["Chameleon","Lion","Elephant","Dog"],"answer":0}'],
            [123,'quiz','What is migration in animals?',
             '{"question":"What is migration in animals?","options":["Seasonal movement to find food or better conditions","Building a nest","Laying eggs","Hunting prey"],"answer":0}'],

            // ── LESSON 124 — Plant Life Cycles ───────────────────────────────
            [124,'quiz','What are the stages of a plant life cycle?',
             '{"question":"What are the stages of a plant life cycle?","options":["Seed, germination, growth, flowering, seed production","Birth, growth, death","Root, stem, leaf","Soil, water, sun"],"answer":0}'],
            [124,'quiz','What is pollination?',
             '{"question":"What is pollination?","options":["Transfer of pollen from anther to stigma","Growth of roots","Falling of leaves","Absorption of water"],"answer":0}'],
            [124,'vocabulary','The ___ of a plant produces seeds after fertilisation.',
             '{"sentence":"The ___ of a plant produces seeds after fertilisation.","answer":"flower","alternatives":["ovary","fruit"]}'],
            [124,'quiz','Which agent helps in pollination?',
             '{"question":"Which agent helps in pollination?","options":["Bees and wind","Rain only","Soil","Darkness"],"answer":0}'],
            [124,'quiz','What is photosynthesis?',
             '{"question":"What is photosynthesis?","options":["Process by which plants make food using sunlight","Process of seed germination","Process of water absorption","Process of leaf falling"],"answer":0}'],
            [124,'revision','Match the plant part to its function.',
             '{"pairs":[["Roots","Absorb water and minerals"],["Leaves","Make food by photosynthesis"],["Stem","Transport water and food"],["Flower","Reproduction"],["Fruit","Protect seeds"]]}'],

            // ── LESSON 125 — The Water Cycle ─────────────────────────────────
            [125,'quiz','What is the first stage of the water cycle?',
             '{"question":"What is the first stage of the water cycle?","options":["Evaporation","Precipitation","Condensation","Collection"],"answer":0}'],
            [125,'quiz','What is condensation in the water cycle?',
             '{"question":"What is condensation in the water cycle?","options":["Water vapour cools and forms clouds","Water falls as rain","Water evaporates from lakes","Water flows in rivers"],"answer":0}'],
            [125,'vocabulary','Water falling from clouds as rain or snow is called ___.',
             '{"sentence":"Water falling from clouds as rain or snow is called ___.","answer":"precipitation","alternatives":["rainfall"]}'],
            [125,'revision','Order the water cycle stages.',
             '{"pairs":[["1st","Evaporation"],["2nd","Condensation"],["3rd","Precipitation"],["4th","Collection/Run-off"]]}'],
            [125,'quiz','What energy source drives the water cycle?',
             '{"question":"What energy source drives the water cycle?","options":["The Sun","The Moon","Wind only","Gravity only"],"answer":0}'],

            // ── LESSON 126 — Waste Management ────────────────────────────────
            [126,'quiz','What does the 3Rs stand for in waste management?',
             '{"question":"What does the 3Rs stand for in waste management?","options":["Reduce, Reuse, Recycle","Read, Run, Rest","Rain, River, Run-off","Rotate, Renew, Replace"],"answer":0}'],
            [126,'quiz','Which waste management practice helps save energy?',
             '{"question":"Which waste management practice helps save energy?","options":["Recycling","Dumping waste","Burning waste","Burying plastic"],"answer":0}'],
            [126,'vocabulary','The breaking down of organic waste by microorganisms is called ___.',
             '{"sentence":"The breaking down of organic waste by microorganisms is called ___.","answer":"decomposition","alternatives":["decay","biodegradation"]}'],
            [126,'quiz','What is the danger of dumping waste in rivers?',
             '{"question":"What is the danger of dumping waste in rivers?","options":["Water pollution and disease","Better water quality","More fish","Cleaner environment"],"answer":0}'],
            [126,'quiz','Which type of waste takes the longest to decompose?',
             '{"question":"Which type of waste takes the longest to decompose?","options":["Plastic","Food scraps","Paper","Leaves"],"answer":0}'],

            // ── LESSON 127 — Soil Types and Enrichment ───────────────────────
            [127,'quiz','What makes loamy soil best for farming?',
             '{"question":"What makes loamy soil best for farming?","options":["It holds water and drains well","It is very hard","It contains only sand","It has no nutrients"],"answer":0}'],
            [127,'quiz','What is compost?',
             '{"question":"What is compost?","options":["Decayed organic matter used to enrich soil","A type of chemical fertiliser","A pesticide","A type of rock"],"answer":0}'],
            [127,'vocabulary','Adding ___ to soil improves its fertility.',
             '{"sentence":"Adding ___ to soil improves its fertility.","answer":"compost","alternatives":["manure","organic matter","fertiliser"]}'],
            [127,'quiz','What is soil erosion?',
             '{"question":"What is soil erosion?","options":["Removal of topsoil by wind or water","Addition of nutrients to soil","Mixing of different soils","Watering of soil"],"answer":0}'],
            [127,'quiz','Which practice helps prevent soil erosion?',
             '{"question":"Which practice helps prevent soil erosion?","options":["Planting trees and cover crops","Cutting down all trees","Leaving soil bare","Over-irrigating"],"answer":0}'],
            [127,'revision','Match the soil type to its characteristic.',
             '{"pairs":[["Sandy","Drains quickly"],["Clay","Heavy and sticky"],["Loamy","Best for farming"],["Silt","Fine particles, good drainage"]]}'],

            // ── LESSON 128 — Push, Pull, Friction and Tension ────────────────
            [128,'quiz','What is friction?',
             '{"question":"What is friction?","options":["A force that opposes motion","A type of energy","A pulling force","The weight of an object"],"answer":0}'],
            [128,'quiz','Which surfaces produce more friction?',
             '{"question":"Which surfaces produce more friction?","options":["Rough surfaces","Smooth surfaces","Wet surfaces","Polished surfaces"],"answer":0}'],
            [128,'vocabulary','A ___ force moves an object away from you.',
             '{"sentence":"A ___ force moves an object away from you.","answer":"push","alternatives":["pushing"]}'],
            [128,'quiz','Tension is a force found in ___.',
             '{"question":"Tension is a force found in ___.","options":["Stretched ropes or strings","Water","Air","Soil"],"answer":0}'],
            [128,'quiz','What does gravity do to objects?',
             '{"question":"What does gravity do to objects?","options":["Pulls them towards the Earth","Pushes them upwards","Makes them float","Has no effect"],"answer":0}'],

            // ── LESSON 129 — Civil Engineering Basics ────────────────────────
            [129,'quiz','What do civil engineers build?',
             '{"question":"What do civil engineers build?","options":["Roads, bridges and buildings","Cars and aeroplanes","Computers","Medicines"],"answer":0}'],
            [129,'quiz','Which material is most commonly used in construction?',
             '{"question":"Which material is most commonly used in construction?","options":["Concrete and steel","Plastic","Wood only","Glass only"],"answer":0}'],
            [129,'vocabulary','A structure built to carry traffic over water is called a ___.',
             '{"sentence":"A structure built to carry traffic over water is called a ___.","answer":"bridge","alternatives":["bridges"]}'],
            [129,'quiz','Why are foundations important in buildings?',
             '{"question":"Why are foundations important in buildings?","options":["They support the weight of the building","They make buildings look beautiful","They provide electricity","They supply water"],"answer":0}'],
            [129,'quiz','What is the purpose of a dam?',
             '{"question":"What is the purpose of a dam?","options":["Store water and generate electricity","Carry traffic","Provide shade","Connect roads"],"answer":0}'],

            // ── LESSON 130 — Energy Forms and Sources ────────────────────────
            [130,'quiz','Which of these is a form of energy?',
             '{"question":"Which of these is a form of energy?","options":["Light energy","Plastic","Metal","Stone"],"answer":0}'],
            [130,'revision','Match the energy form to its source.',
             '{"pairs":[["Solar energy","Sun"],["Kinetic energy","Moving objects"],["Chemical energy","Food and fuel"],["Electrical energy","Power station"],["Thermal energy","Heat"]]}'],
            [130,'vocabulary','Energy that is stored in food is called ___ energy.',
             '{"sentence":"Energy that is stored in food is called ___ energy.","answer":"chemical","alternatives":["stored"]}'],
            [130,'quiz','What is the law of conservation of energy?',
             '{"question":"What is the law of conservation of energy?","options":["Energy cannot be created or destroyed, only transformed","Energy can be created from nothing","Energy disappears when used","Energy only exists as heat"],"answer":0}'],
            [130,'quiz','Which energy transformation happens in a light bulb?',
             '{"question":"Which energy transformation happens in a light bulb?","options":["Electrical to light and heat","Chemical to electrical","Sound to heat","Mechanical to sound"],"answer":0}'],

            // ── LESSON 131 — Conductors and Insulators ───────────────────────
            [131,'quiz','What is an electrical conductor?',
             '{"question":"What is an electrical conductor?","options":["A material that allows electricity to flow","A material that blocks electricity","A type of battery","A power source"],"answer":0}'],
            [131,'revision','Sort these materials into conductors and insulators.',
             '{"pairs":[["Copper","Conductor"],["Rubber","Insulator"],["Iron","Conductor"],["Plastic","Insulator"],["Silver","Conductor"]]}'],
            [131,'vocabulary','Materials that do not conduct electricity are called ___.',
             '{"sentence":"Materials that do not conduct electricity are called ___.","answer":"insulators","alternatives":["insulator"]}'],
            [131,'quiz','Why are electric wires covered in plastic?',
             '{"question":"Why are electric wires covered in plastic?","options":["To insulate and prevent electric shock","To make them look nice","To make them stronger","To conduct more electricity"],"answer":0}'],
            [131,'quiz','Which of these is the best conductor of electricity?',
             '{"question":"Which of these is the best conductor of electricity?","options":["Copper","Wood","Rubber","Glass"],"answer":0}'],
        ];

        $inserted = 0;
        foreach ($exercises as [$lessonId, $category, $title, $content]) {
            $exists = DB::table('exercises')->where('lesson_id', $lessonId)->where('title', $title)->exists();
            if ($exists) continue;
            DB::table('exercises')->insert([
                'lesson_id'    => $lessonId,
                'title'        => $title,
                'category'     => $category,
                'difficulty'   => 'medium',
                'content'      => $content,
                'instructions' => match($category) {
                    'quiz'       => 'Choose the correct answer.',
                    'vocabulary' => 'Fill in the missing word.',
                    'revision'   => 'Match each item to its correct pair.',
                    default      => 'Complete the exercise.',
                },
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $inserted++;
        }
        $this->command->info("   Science C5 : {$inserted} exercices ajoutés");
    }
}
