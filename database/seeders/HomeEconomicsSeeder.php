<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomeEconomicsSeeder extends Seeder
{
    public function run(): void
    {
        $exercises = [

            // ── LESSON 267 — Cooking and Kitchen Safety (C5) ─────────────────
            [267,'quiz','What should you do before handling food?',
             '{"question":"What should you do before handling food?","options":["Wash your hands thoroughly","Put on an apron only","Start cooking immediately","Check the recipe"],"answer":0}'],
            [267,'quiz','Which knife technique is safest when cutting vegetables?',
             '{"question":"Which knife technique is safest when cutting vegetables?","options":["Curl fingers under and cut away from body","Hold food loosely","Cut towards your hand","Use a blunt knife"],"answer":0}'],
            [267,'quiz','What does a balanced meal include?',
             '{"question":"What does a balanced meal include?","options":["Carbohydrates, proteins, vegetables and fruits","Only meat and rice","Only bread and butter","Sweets and fizzy drinks"],"answer":0}'],
            [267,'quiz','What should you do if a pan catches fire in the kitchen?',
             '{"question":"What should you do if a pan catches fire in the kitchen?","options":["Cover it with a lid and turn off the heat","Pour water on it","Blow on it","Run away immediately"],"answer":0}'],
            [267,'vocabulary','Keeping the kitchen clean to avoid contamination is called food ___.',
             '{"sentence":"Keeping the kitchen clean to avoid contamination is called food ___.","answer":"hygiene","alternatives":["safety","sanitation"]}'],
            [267,'quiz','Which temperature kills most food bacteria?',
             '{"question":"Which temperature kills most food bacteria?","options":["Above 70°C","Below 0°C","Room temperature","Exactly 37°C"],"answer":0}'],
            [267,'quiz','What is the purpose of a food label?',
             '{"question":"What is the purpose of a food label?","options":["Show ingredients, nutritional value and expiry date","Make the product look nice","Increase the price","Show the manufacturer\'s logo only"],"answer":0}'],
            [267,'revision','Match the kitchen tool to its use.',
             '{"pairs":[["Colander","Draining pasta or vegetables"],["Whisk","Beating eggs or cream"],["Peeler","Removing vegetable skin"],["Grater","Shredding cheese or vegetables"],["Rolling pin","Flattening dough"]]}'],
            [267,'quiz','Why should you keep raw meat separate from other foods?',
             '{"question":"Why should you keep raw meat separate from other foods?","options":["To prevent cross-contamination","To save space","To keep it warm","To make cooking faster"],"answer":0}'],
            [267,'vocabulary','The ___ date on food packaging tells you when the food is no longer safe to eat.',
             '{"sentence":"The ___ date on food packaging tells you when the food is no longer safe to eat.","answer":"expiry","alternatives":["expiration","use by","best before"]}'],
            [267,'quiz','Which cooking method preserves the most nutrients in vegetables?',
             '{"question":"Which cooking method preserves the most nutrients in vegetables?","options":["Steaming","Deep frying","Boiling for a long time","Microwaving with lots of water"],"answer":0}'],
            [267,'quiz','What is the correct way to store leftovers?',
             '{"question":"What is the correct way to store leftovers?","options":["Cover and refrigerate within 2 hours","Leave on the counter overnight","Freeze immediately without cooling","Throw away all leftovers"],"answer":0}'],
            [267,'revision','Sort these foods into their nutrient groups.',
             '{"pairs":[["Rice","Carbohydrates"],["Chicken","Proteins"],["Spinach","Vitamins/Minerals"],["Groundnut oil","Fats"],["Milk","Calcium/Proteins"]]}'],
            [267,'quiz','What safety equipment should you wear when cooking?',
             '{"question":"What safety equipment should you wear when cooking?","options":["Apron and oven gloves","Sunglasses and hat","Gloves and boots","No equipment needed"],"answer":0}'],
            [267,'vocabulary','___ is the contamination of food by harmful bacteria during preparation.',
             '{"sentence":"___ is the contamination of food by harmful bacteria during preparation.","answer":"Cross-contamination","alternatives":["cross contamination","food contamination"]}'],

            // ── LESSON 268 — Entrepreneurship and Life Skills (C6) ────────────
            [268,'quiz','What is an entrepreneur?',
             '{"question":"What is an entrepreneur?","options":["Someone who starts and runs a business","A government worker","A teacher","A student"],"answer":0}'],
            [268,'quiz','Which of these is a life skill?',
             '{"question":"Which of these is a life skill?","options":["Communication and problem solving","Playing video games","Sleeping late","Avoiding responsibilities"],"answer":0}'],
            [268,'vocabulary','The money used to start a business is called ___.',
             '{"sentence":"The money used to start a business is called ___.","answer":"capital","alternatives":["startup capital","investment"]}'],
            [268,'quiz','What is a budget?',
             '{"question":"What is a budget?","options":["A plan for how to spend and save money","A type of food","A business name","A bank account"],"answer":0}'],
            [268,'quiz','Which quality is most important for an entrepreneur?',
             '{"question":"Which quality is most important for an entrepreneur?","options":["Creativity and determination","Shyness","Dependence on others","Fear of failure"],"answer":0}'],
            [268,'quiz','What does profit mean in business?',
             '{"question":"What does profit mean in business?","options":["Money earned after paying all costs","Money spent on goods","The total sales only","Money borrowed from a bank"],"answer":0}'],
            [268,'vocabulary','Selling goods or services to earn money is called ___.',
             '{"sentence":"Selling goods or services to earn money is called ___.","answer":"trade","alternatives":["trading","commerce","business"]}'],
            [268,'quiz','What is the first step in starting a small business?',
             '{"question":"What is the first step in starting a small business?","options":["Identify a need or problem to solve","Buy expensive equipment","Hire many workers","Open a bank account"],"answer":0}'],
            [268,'quiz','What is the difference between a need and a want?',
             '{"question":"What is the difference between a need and a want?","options":["A need is essential for survival; a want is desirable but not essential","They are the same thing","A want is more important","Needs cost more money"],"answer":0}'],
            [268,'revision','Match the business term to its definition.',
             '{"pairs":[["Revenue","Total money earned from sales"],["Expenses","Money spent running the business"],["Profit","Revenue minus expenses"],["Capital","Money used to start the business"],["Loss","When expenses exceed revenue"]]}'],
            [268,'quiz','Why is saving money important?',
             '{"question":"Why is saving money important?","options":["For emergencies and future investments","To show off to friends","Because banks force you to","It is not important"],"answer":0}'],
            [268,'quiz','What is the purpose of advertising a product?',
             '{"question":"What is the purpose of advertising a product?","options":["To attract customers and increase sales","To make the product more expensive","To confuse competitors","To reduce quality"],"answer":0}'],
            [268,'vocabulary','A ___ is a written plan describing a business and how it will operate.',
             '{"sentence":"A ___ is a written plan describing a business and how it will operate.","answer":"business plan","alternatives":["business proposal"]}'],
            [268,'quiz','Which of these is an example of a local Cameroonian enterprise?',
             '{"question":"Which of these is an example of a local Cameroonian enterprise?","options":["Selling plantains at the market","Working for a foreign company only","Importing all goods","Spending all savings"],"answer":0}'],
            [268,'quiz','What does cooperative mean in business?',
             '{"question":"What does cooperative mean in business?","options":["A group of people working together for shared benefit","A single person business","A government department","A foreign company"],"answer":0}'],
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
        $this->command->info("   Home Economics C5+C6 : {$inserted} exercices ajoutés");
    }
}
