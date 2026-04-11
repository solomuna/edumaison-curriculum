<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReadingC2C3Seeder extends Seeder
{
    public function run(): void
    {
        $exercises = [

            // ── LESSON 208 — Syllables and Words (C2) ────────────────────────
            [208,'vocabulary','How many syllables does the word "elephant" have?',
             '{"sentence":"How many syllables does the word \\"elephant\\" have?","answer":"3","alternatives":["three"]}'],
            [208,'quiz','Which word has TWO syllables?',
             '{"question":"Which word has TWO syllables?","options":["happy","cat","sun","big"],"answer":0}'],
            [208,'quiz','Which word has ONE syllable?',
             '{"question":"Which word has ONE syllable?","options":["book","table","garden","window"],"answer":0}'],
            [208,'vocabulary','The word "butter" has ___ syllables.',
             '{"sentence":"The word \\"butter\\" has ___ syllables.","answer":"2","alternatives":["two"]}'],
            [208,'quiz','How do you split the word "rain-bow"?',
             '{"question":"How do you split the word \\"rain-bow\\"?","options":["rain-bow","ra-inbow","rainb-ow","r-ainbow"],"answer":0}'],
            [208,'revision','Match each word to its number of syllables.',
             '{"pairs":[["dog","1"],["apple","2"],["banana","3"],["sun","1"],["butterfly","3"]]}'],
            [208,'quiz','Which word has THREE syllables?',
             '{"question":"Which word has THREE syllables?","options":["calendar","cat","play","rain"],"answer":0}'],
            [208,'vocabulary','Clapping while saying a word helps you count its ___.',
             '{"sentence":"Clapping while saying a word helps you count its ___.","answer":"syllables","alternatives":["syllable"]}'],
            [208,'quiz','How many syllables does "computer" have?',
             '{"question":"How many syllables does \\"computer\\" have?","options":["3","2","4","1"],"answer":0}'],
            [208,'quiz','Which word has the SAME number of syllables as "happy"?',
             '{"question":"Which word has the SAME number of syllables as \\"happy\\"?","options":["funny","cat","school","book"],"answer":0}'],

            // ── LESSON 209 — Short Stories (C2) ──────────────────────────────
            [209,'reading','Read and answer: "Ama has a cat. The cat is black and white. It drinks milk every morning." What colour is Ama\'s cat?',
             '{"passage":"Ama has a cat. The cat is black and white. It drinks milk every morning.","question":"What colour is Ama\'s cat?","options":["Black and white","Brown","Orange","Grey"],"answer":0}'],
            [209,'reading','Read and answer: "Tom went to the market. He bought mangoes, bananas and groundnuts. He paid 500 francs." What did Tom buy at the market?',
             '{"passage":"Tom went to the market. He bought mangoes, bananas and groundnuts. He paid 500 francs.","question":"What did Tom buy at the market?","options":["Mangoes, bananas and groundnuts","Rice and beans","Fish and bread","Apples and oranges"],"answer":0}'],
            [209,'reading','Read and answer: "Binta wakes up at six o\'clock. She brushes her teeth and has breakfast. Then she walks to school." What time does Binta wake up?',
             '{"passage":"Binta wakes up at six o\'clock. She brushes her teeth and has breakfast. Then she walks to school.","question":"What time does Binta wake up?","options":["Six o\'clock","Seven o\'clock","Five o\'clock","Eight o\'clock"],"answer":0}'],
            [209,'quiz','What is the main idea of a story?',
             '{"question":"What is the main idea of a story?","options":["What the story is mostly about","The longest sentence","The first word","The last paragraph"],"answer":0}'],
            [209,'vocabulary','A ___ is a group of sentences that tells about one main idea.',
             '{"sentence":"A ___ is a group of sentences that tells about one main idea.","answer":"paragraph","alternatives":["paragraphs"]}'],
            [209,'reading','Read: "The sun rose over the hills. Birds began to sing. Children ran out to play in the yard." What time of day is this story set?',
             '{"passage":"The sun rose over the hills. Birds began to sing. Children ran out to play in the yard.","question":"What time of day is this story set?","options":["Morning","Night","Afternoon","Evening"],"answer":0}'],
            [209,'quiz','What does a title tell us about a story?',
             '{"question":"What does a title tell us about a story?","options":["The topic or main idea","The number of pages","Who wrote it","When it was written"],"answer":0}'],
            [209,'reading','Read: "Papa planted maize in the field. He watered it every day. After two months, the maize was ready to harvest." How long did the maize take to grow?',
             '{"passage":"Papa planted maize in the field. He watered it every day. After two months, the maize was ready to harvest.","question":"How long did the maize take to grow?","options":["Two months","One week","One year","Three days"],"answer":0}'],

            // ── LESSON 301 — Animals (C3) ─────────────────────────────────────
            [301,'reading','Read: "The elephant is the largest land animal. It uses its trunk to pick up food and water. Elephants live in herds and protect each other." What does the elephant use its trunk for?',
             '{"passage":"The elephant is the largest land animal. It uses its trunk to pick up food and water. Elephants live in herds and protect each other.","question":"What does the elephant use its trunk for?","options":["To pick up food and water","To run fast","To see clearly","To make sounds only"],"answer":0}'],
            [301,'reading','Read: "Frogs are amphibians. They can live both on land and in water. Frogs lay their eggs in water. Tadpoles hatch from the eggs and slowly grow into frogs." Where do frogs lay their eggs?',
             '{"passage":"Frogs are amphibians. They can live both on land and in water. Frogs lay their eggs in water. Tadpoles hatch from the eggs and slowly grow into frogs.","question":"Where do frogs lay their eggs?","options":["In water","On trees","Under rocks","In soil"],"answer":0}'],
            [301,'quiz','What does "nocturnal" mean when describing an animal?',
             '{"question":"What does \\"nocturnal\\" mean when describing an animal?","options":["Active at night","Very fast","Lives in water","Eats only plants"],"answer":0}'],
            [301,'vocabulary','Animals that eat only plants are called ___.',
             '{"sentence":"Animals that eat only plants are called ___.","answer":"herbivores","alternatives":["herbivore"]}'],
            [301,'revision','Match each animal to its diet type.',
             '{"pairs":[["Cow","Herbivore"],["Lion","Carnivore"],["Human","Omnivore"],["Rabbit","Herbivore"],["Eagle","Carnivore"]]}'],
            [301,'reading','Read: "The honeybee collects nectar from flowers to make honey. As it moves from flower to flower, it carries pollen and helps plants reproduce. Without bees, many plants could not survive." Why are bees important for plants?',
             '{"passage":"The honeybee collects nectar from flowers to make honey. As it moves from flower to flower, it carries pollen and helps plants reproduce. Without bees, many plants could not survive.","question":"Why are bees important for plants?","options":["They carry pollen and help plants reproduce","They eat harmful insects","They water the plants","They provide shade"],"answer":0}'],
            [301,'quiz','What is the correct order of a frog\'s life cycle?',
             '{"question":"What is the correct order of a frog\'s life cycle?","options":["Egg, tadpole, froglet, adult frog","Adult frog, egg, tadpole","Tadpole, egg, frog","Frog, froglet, egg"],"answer":0}'],
            [301,'vocabulary','An animal that feeds on both plants and animals is called an ___.',
             '{"sentence":"An animal that feeds on both plants and animals is called an ___.","answer":"omnivore","alternatives":["omnivores"]}'],

            // ── LESSON 302 — Synonyms and Antonyms (C3) ──────────────────────
            [302,'quiz','What is a synonym?',
             '{"question":"What is a synonym?","options":["A word with the same or similar meaning","A word with the opposite meaning","A describing word","A doing word"],"answer":0}'],
            [302,'quiz','Which word is a synonym of "happy"?',
             '{"question":"Which word is a synonym of \\"happy\\"?","options":["joyful","sad","angry","tired"],"answer":0}'],
            [302,'quiz','What is an antonym?',
             '{"question":"What is an antonym?","options":["A word with the opposite meaning","A word with the same meaning","A type of noun","A connecting word"],"answer":0}'],
            [302,'quiz','What is the antonym of "hot"?',
             '{"question":"What is the antonym of \\"hot\\"?","options":["cold","warm","cool","freezing"],"answer":0}'],
            [302,'revision','Match each word to its synonym.',
             '{"pairs":[["big","large"],["fast","quick"],["sad","unhappy"],["smart","intelligent"],["begin","start"]]}'],
            [302,'revision','Match each word to its antonym.',
             '{"pairs":[["day","night"],["tall","short"],["old","young"],["full","empty"],["strong","weak"]]}'],
            [302,'vocabulary','A word that means the opposite of another word is called an ___.',
             '{"sentence":"A word that means the opposite of another word is called an ___.","answer":"antonym","alternatives":["opposite"]}'],
            [302,'quiz','Which word is a synonym of "large"?',
             '{"question":"Which word is a synonym of \\"large\\"?","options":["big","tiny","small","little"],"answer":0}'],
            [302,'quiz','What is the antonym of "brave"?',
             '{"question":"What is the antonym of \\"brave\\"?","options":["cowardly","strong","bold","daring"],"answer":0}'],
            [302,'quiz','Which pair of words are synonyms?',
             '{"question":"Which pair of words are synonyms?","options":["angry and furious","hot and cold","up and down","big and small"],"answer":0}'],

            // ── LESSON 303 — Nouns and Verbs (C3) ────────────────────────────
            [303,'quiz','What is a noun?',
             '{"question":"What is a noun?","options":["A word that names a person, place, animal or thing","A doing word","A describing word","A connecting word"],"answer":0}'],
            [303,'quiz','Which word is a NOUN in this sentence: "The dog runs fast."?',
             '{"question":"Which word is a NOUN in this sentence: \\"The dog runs fast.\\"?","options":["dog","runs","fast","The"],"answer":0}'],
            [303,'quiz','What is a verb?',
             '{"question":"What is a verb?","options":["A word that shows an action or state of being","A word that names a thing","A describing word","A connecting word"],"answer":0}'],
            [303,'quiz','Which word is a VERB in this sentence: "Mary sings beautifully."?',
             '{"question":"Which word is a VERB in this sentence: \\"Mary sings beautifully.\\"?","options":["sings","Mary","beautifully","sentence"],"answer":0}'],
            [303,'revision','Sort these words into nouns and verbs.',
             '{"pairs":[["school","Noun"],["run","Verb"],["teacher","Noun"],["jump","Verb"],["book","Noun"]]}'],
            [303,'vocabulary','A ___ is a word that names a person, place or thing.',
             '{"sentence":"A ___ is a word that names a person, place or thing.","answer":"noun","alternatives":["nouns"]}'],
            [303,'quiz','Which sentence contains a verb?',
             '{"question":"Which sentence contains a verb?","options":["The children played in the rain.","Big red house.","Happy girl.","Fast car."],"answer":0}'],
            [303,'quiz','Which of these is a PROPER noun?',
             '{"question":"Which of these is a PROPER noun?","options":["Cameroon","city","river","mountain"],"answer":0}'],
            [303,'vocabulary','Words like run, jump, eat and sleep are called ___.',
             '{"sentence":"Words like run, jump, eat and sleep are called ___.","answer":"verbs","alternatives":["verb","action words"]}'],
            [303,'revision','Match each noun to its category.',
             '{"pairs":[["Yaounde","Place"],["David","Person"],["Cat","Animal"],["Book","Thing"],["Cameroon","Country"]]}'],
        ];

        $inserted = 0;
        foreach ($exercises as [$lessonId, $category, $title, $content]) {
            $exists = DB::table('exercises')->where('lesson_id', $lessonId)->where('title', $title)->exists();
            if ($exists) continue;
            $cat = in_array($category, ['reading']) ? 'reading' : $category;
            DB::table('exercises')->insert([
                'lesson_id'    => $lessonId,
                'title'        => $title,
                'category'     => $cat,
                'difficulty'   => 'medium',
                'content'      => $content,
                'instructions' => match($cat) {
                    'reading'    => 'Read the passage and answer the question.',
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
        $this->command->info("   Reading C2+C3 : {$inserted} exercices ajoutés");
    }
}
