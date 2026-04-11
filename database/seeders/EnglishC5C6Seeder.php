<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnglishC5C6Seeder extends Seeder
{
    public function run(): void
    {
        $exercises = [

            // ── LESSON 54 — Africa Overview (C5) ─────────────────────────────
            [54,'reading','Africa overview - comprehension',
             '{"passage":"Africa is the second largest continent in the world. It has 54 countries and over 1.4 billion people. The Sahara Desert in the north is the largest hot desert on Earth.","question":"How many countries are in Africa?","options":["54","44","64","74"],"answer":0}'],
            [54,'quiz','What is the largest country in Africa by area?',
             '{"question":"What is the largest country in Africa by area?","options":["Algeria","Nigeria","South Africa","Ethiopia"],"answer":0}'],
            [54,'vocabulary','The ___ is the longest river in Africa.',
             '{"sentence":"The ___ is the longest river in Africa.","answer":"Nile","alternatives":["River Nile","Nile River"]}'],
            [54,'quiz','Which ocean borders Africa to the west?',
             '{"question":"Which ocean borders Africa to the west?","options":["Atlantic Ocean","Pacific Ocean","Indian Ocean","Arctic Ocean"],"answer":0}'],
            [54,'quiz','What is the capital of Cameroon?',
             '{"question":"What is the capital of Cameroon?","options":["Yaoundé","Douala","Bamenda","Bafoussam"],"answer":0}'],

            // ── LESSON 55 — Modern Technology (C5) ───────────────────────────
            [55,'quiz','What is the internet?',
             '{"question":"What is the internet?","options":["A global network connecting computers worldwide","A type of television","A mobile phone brand","A computer game"],"answer":0}'],
            [55,'vocabulary','A ___ is a portable computer that you can carry anywhere.',
             '{"sentence":"A ___ is a portable computer that you can carry anywhere.","answer":"laptop","alternatives":["laptop computer","notebook"]}'],
            [55,'reading','Technology and communication - comprehension',
             '{"passage":"Technology has changed how we communicate. We can now send messages, make video calls and share information instantly. Social media connects people across the world.","question":"What is one way technology has changed communication?","options":["We can make video calls","We write more letters","We talk less","We travel more"],"answer":0}'],
            [55,'quiz','What does GPS stand for?',
             '{"question":"What does GPS stand for?","options":["Global Positioning System","General Phone Service","Guided Planet Signal","Global Phone System"],"answer":0}'],
            [55,'quiz','Which of these is a danger of using the internet?',
             '{"question":"Which of these is a danger of using the internet?","options":["Cyberbullying and online fraud","Learning new things","Connecting with family","Finding information"],"answer":0}'],

            // ── LESSON 56 — Our Culture (C5) ──────────────────────────────────
            [56,'quiz','What is culture?',
             '{"question":"What is culture?","options":["The beliefs, customs and way of life of a group of people","A type of food","A sport","A school subject"],"answer":0}'],
            [56,'vocabulary','Traditional dances and songs are part of a community\'s cultural ___.',
             '{"sentence":"Traditional dances and songs are part of a community\'s cultural ___.","answer":"heritage","alternatives":["tradition","traditions"]}'],
            [56,'reading','Cameroon - Africa in miniature',
             '{"passage":"Cameroon is known as Africa in miniature because it has many cultures, languages and landscapes. There are over 250 ethnic groups in Cameroon, each with its own traditions.","question":"Why is Cameroon called Africa in miniature?","options":["It has many cultures, languages and landscapes","It is the smallest country","It has many animals","It has the best food"],"answer":0}'],
            [56,'quiz','Which of these is an example of a cultural practice?',
             '{"question":"Which of these is an example of a cultural practice?","options":["Traditional marriage ceremonies","Playing football","Using a computer","Going to school"],"answer":0}'],
            [56,'quiz','What language is used in formal education in the Anglophone regions of Cameroon?',
             '{"question":"What language is used in formal education in the Anglophone regions of Cameroon?","options":["English","French","Pidgin","Fulfude"],"answer":0}'],

            // ── LESSON 348 — Grammar (C5) ─────────────────────────────────────
            [348,'quiz','What is a conjunction?',
             '{"question":"What is a conjunction?","options":["A word that joins two clauses or sentences","A describing word","A naming word","A doing word"],"answer":0}'],
            [348,'quiz','Which word is a conjunction in: "I like mangoes and oranges."?',
             '{"question":"Which word is a conjunction in: \\"I like mangoes and oranges.\\"?","options":["and","like","mangoes","oranges"],"answer":0}'],
            [348,'quiz','What tense is used in: "She was reading a book."?',
             '{"question":"What tense is used in: \\"She was reading a book.\\"?","options":["Past continuous","Present simple","Future simple","Past simple"],"answer":0}'],
            [348,'vocabulary','A word that describes a noun is called an ___.',
             '{"sentence":"A word that describes a noun is called an ___.","answer":"adjective","alternatives":["adjectives"]}'],
            [348,'revision','Match the grammatical term to its example.',
             '{"pairs":[["Noun","Cameroon"],["Verb","runs"],["Adjective","beautiful"],["Adverb","quickly"],["Conjunction","because"]]}'],
            [348,'quiz','Which sentence is in the FUTURE tense?',
             '{"question":"Which sentence is in the FUTURE tense?","options":["She will travel tomorrow.","He went to school.","They are playing.","I eat rice."],"answer":0}'],
            [348,'quiz','What is the plural of "child"?',
             '{"question":"What is the plural of \\"child\\"?","options":["children","childs","childes","child"],"answer":0}'],

            // ── LESSON 349 — Vocabulary (C5) ─────────────────────────────────
            [349,'quiz','What does "enormous" mean?',
             '{"question":"What does \\"enormous\\" mean?","options":["Very large","Very small","Very fast","Very slow"],"answer":0}'],
            [349,'revision','Match each word to its definition.',
             '{"pairs":[["transparent","See-through"],["ancient","Very old"],["courageous","Brave"],["exhausted","Very tired"],["fortunate","Lucky"]]}'],
            [349,'vocabulary','A word formed by combining two words, like "sunshine", is called a ___ word.',
             '{"sentence":"A word formed by combining two words, like \\"sunshine\\", is called a ___ word.","answer":"compound","alternatives":["compound word"]}'],
            [349,'quiz','What is the meaning of the prefix "un-" in the word "unhappy"?',
             '{"question":"What is the meaning of the prefix \\"un-\\" in the word \\"unhappy\\"?","options":["Not","Very","Again","Before"],"answer":0}'],
            [349,'quiz','Which word means the same as "angry"?',
             '{"question":"Which word means the same as \\"angry\\"?","options":["furious","happy","calm","excited"],"answer":0}'],

            // ── LESSON 350 — Comprehension (C5) ──────────────────────────────
            [350,'reading','Read: "The rainforest is home to more than half of the world\'s plant and animal species. Trees in the rainforest produce oxygen and absorb carbon dioxide. Deforestation threatens this precious ecosystem." What is one benefit of rainforests?',
             '{"passage":"The rainforest is home to more than half of the world\'s plant and animal species. Trees in the rainforest produce oxygen and absorb carbon dioxide. Deforestation threatens this precious ecosystem.","question":"What is one benefit of rainforests?","options":["They produce oxygen","They cause floods","They make deserts","They reduce rainfall"],"answer":0}'],
            [350,'reading','Nelson Mandela - comprehension',
             '{"passage":"Nelson Mandela was born in South Africa in 1918. He fought against apartheid, a system of racial discrimination. He was imprisoned for 27 years but became president in 1994.","question":"How long was Mandela imprisoned?","options":["27 years","17 years","37 years","7 years"],"answer":0}'],
            [350,'quiz','What is the purpose of a comprehension passage?',
             '{"question":"What is the purpose of a comprehension passage?","options":["To test understanding of a text","To practise handwriting","To learn vocabulary only","To count words"],"answer":0}'],
            [350,'vocabulary','The ___ of a passage tells you what the whole text is about.',
             '{"sentence":"The ___ of a passage tells you what the whole text is about.","answer":"main idea","alternatives":["theme","topic"]}'],
            [350,'reading','Read: "Water covers about 71% of the Earth\'s surface. However, only 3% of this water is fresh water, and most of it is frozen in ice caps. This means very little fresh water is available for humans to use." What percentage of Earth\'s water is fresh?',
             '{"passage":"Water covers about 71% of the Earth\'s surface. However, only 3% of this water is fresh water, and most of it is frozen in ice caps. This means very little fresh water is available for humans to use.","question":"What percentage of Earth\'s water is fresh?","options":["3%","71%","50%","25%"],"answer":0}'],

            // ── LESSON 351 — Writing Skills (C5) ─────────────────────────────
            [351,'quiz','What is a topic sentence?',
             '{"question":"What is a topic sentence?","options":["The main sentence that introduces a paragraph","The last sentence of an essay","A question sentence","A sentence with no verb"],"answer":0}'],
            [351,'vocabulary','A ___ essay presents arguments for and against a topic.',
             '{"sentence":"A ___ essay presents arguments for and against a topic.","answer":"discursive","alternatives":["argumentative","discussion"]}'],
            [351,'quiz','Which type of writing describes events in the order they happened?',
             '{"question":"Which type of writing describes events in the order they happened?","options":["Narrative writing","Descriptive writing","Persuasive writing","Argumentative writing"],"answer":0}'],
            [351,'quiz','What should a good paragraph contain?',
             '{"question":"What should a good paragraph contain?","options":["A topic sentence, supporting details and a conclusion","Only long sentences","Questions only","No punctuation"],"answer":0}'],
            [351,'quiz','Which punctuation mark ends a question?',
             '{"question":"Which punctuation mark ends a question?","options":["Question mark ?","Full stop .","Comma ,","Exclamation mark !"],"answer":0}'],

            // ── LESSON 60 — Continents and Oceans (C6) ───────────────────────
            [60,'quiz','How many continents are there in the world?',
             '{"question":"How many continents are there in the world?","options":["7","5","6","8"],"answer":0}'],
            [60,'quiz','Which is the largest ocean?',
             '{"question":"Which is the largest ocean?","options":["Pacific Ocean","Atlantic Ocean","Indian Ocean","Arctic Ocean"],"answer":0}'],
            [60,'revision','Match each continent to a country within it.',
             '{"pairs":[["Africa","Cameroon"],["Europe","France"],["Asia","China"],["Americas","Brazil"],["Oceania","Australia"]]}'],
            [60,'vocabulary','The imaginary line that divides the Earth into north and south is called the ___.',
             '{"sentence":"The imaginary line that divides the Earth into north and south is called the ___.","answer":"equator","alternatives":["Equator"]}'],
            [60,'quiz','Which continent is the smallest?',
             '{"question":"Which continent is the smallest?","options":["Australia/Oceania","Europe","South America","Antarctica"],"answer":0}'],

            // ── LESSON 61 — Countries and Capitals (C6) ──────────────────────
            [61,'quiz','What is the capital of France?',
             '{"question":"What is the capital of France?","options":["Paris","London","Berlin","Madrid"],"answer":0}'],
            [61,'revision','Match each country to its capital city.',
             '{"pairs":[["Nigeria","Abuja"],["Ghana","Accra"],["Kenya","Nairobi"],["South Africa","Pretoria"],["Egypt","Cairo"]]}'],
            [61,'vocabulary','The ___ city is the seat of government of a country.',
             '{"sentence":"The ___ city is the seat of government of a country.","answer":"capital","alternatives":["capital city"]}'],
            [61,'quiz','What is the largest city in Cameroon?',
             '{"question":"What is the largest city in Cameroon?","options":["Douala","Yaoundé","Bamenda","Garoua"],"answer":0}'],
            [61,'quiz','Which country has the largest population in Africa?',
             '{"question":"Which country has the largest population in Africa?","options":["Nigeria","Ethiopia","Egypt","South Africa"],"answer":0}'],

            // ── LESSON 62 — Rights and Responsibilities (C6) ─────────────────
            [62,'quiz','What is a human right?',
             '{"question":"What is a human right?","options":["A basic right every person deserves regardless of race or gender","A privilege given to some people","A school rule","A government law only"],"answer":0}'],
            [62,'quiz','What is a responsibility?',
             '{"question":"What is a responsibility?","options":["A duty we have towards others and our community","A special reward","A type of right","A school subject"],"answer":0}'],
            [62,'reading','Rights of the Child - comprehension',
             '{"passage":"The United Nations Convention on the Rights of the Child states that every child has the right to education, healthcare and protection from harm. Children also have responsibilities, such as respecting others and obeying rules.","question":"Name one right children have according to the text.","options":["Right to education","Right to drive","Right to vote","Right to own a business"],"answer":0}'],
            [62,'vocabulary','The ___ is an international organisation that protects children\'s rights.',
             '{"sentence":"The ___ is an international organisation that protects children\'s rights.","answer":"United Nations","alternatives":["UN","UNICEF"]}'],
            [62,'quiz','What is the responsibility of a student at school?',
             '{"question":"What is the responsibility of a student at school?","options":["To study hard and respect teachers","To do whatever they want","To come late every day","To ignore school rules"],"answer":0}'],

            // ── LESSON 63 — Community Leaders (C6) ───────────────────────────
            [63,'quiz','What is the role of a mayor?',
             '{"question":"What is the role of a mayor?","options":["To manage a city or town","To lead a country","To manage a hospital","To teach in a school"],"answer":0}'],
            [63,'vocabulary','A person elected to represent people in parliament is called a ___.',
             '{"sentence":"A person elected to represent people in parliament is called a ___.","answer":"Member of Parliament","alternatives":["MP","representative","politician"]}'],
            [63,'quiz','What quality is most important in a community leader?',
             '{"question":"What quality is most important in a community leader?","options":["Honesty and integrity","Wealth","Physical strength","Being famous"],"answer":0}'],
            [63,'reading','Traditional rulers in Cameroon',
             '{"passage":"Traditional rulers in Cameroon such as the Fon and the Lamido play an important role in maintaining peace and preserving culture. They settle disputes and guide their communities.","question":"What role do traditional rulers play?","options":["Maintain peace and preserve culture","Collect taxes only","Run schools","Build roads"],"answer":0}'],
            [63,'quiz','Who is the head of government in Cameroon?',
             '{"question":"Who is the head of government in Cameroon?","options":["The Prime Minister","The Mayor","The Senator","The Fon"],"answer":0}'],

            // ── LESSON 64 — Environment Vocabulary (C6) ──────────────────────
            [64,'quiz','What does "biodiversity" mean?',
             '{"question":"What does \\"biodiversity\\" mean?","options":["The variety of living organisms in an area","A type of pollution","A weather pattern","A farming method"],"answer":0}'],
            [64,'revision','Match each environmental term to its definition.',
             '{"pairs":[["Deforestation","Cutting down forests"],["Erosion","Wearing away of soil"],["Pollution","Contamination of environment"],["Conservation","Protection of nature"],["Extinction","Complete disappearance of a species"]]}'],
            [64,'vocabulary','The gradual warming of the Earth due to greenhouse gases is called ___ warming.',
             '{"sentence":"The gradual warming of the Earth due to greenhouse gases is called ___ warming.","answer":"global","alternatives":["climate"]}'],
            [64,'quiz','What is the greenhouse effect?',
             '{"question":"What is the greenhouse effect?","options":["Trapping of heat in the atmosphere by gases","Growing plants in a greenhouse","A type of pollution","Recycling waste"],"answer":0}'],
            [64,'quiz','Which human activity contributes most to climate change?',
             '{"question":"Which human activity contributes most to climate change?","options":["Burning fossil fuels","Planting trees","Recycling","Saving water"],"answer":0}'],

            // ── LESSON 65 — Actions for the Planet (C6) ──────────────────────
            [65,'quiz','What is one way to reduce carbon emissions?',
             '{"question":"What is one way to reduce carbon emissions?","options":["Use public transport instead of private cars","Buy more plastic products","Cut down more trees","Burn more rubbish"],"answer":0}'],
            [65,'reading','Climate change - comprehension',
             '{"passage":"Climate change is one of the biggest challenges facing our planet. Rising temperatures are causing glaciers to melt and sea levels to rise. Governments and individuals must work together to reduce carbon emissions.","question":"What is causing sea levels to rise?","options":["Rising temperatures melting glaciers","Too much rain","Earthquakes","Ocean pollution"],"answer":0}'],
            [65,'vocabulary','Planting trees to replace those that have been cut down is called ___.',
             '{"sentence":"Planting trees to replace those that have been cut down is called ___.","answer":"reforestation","alternatives":["afforestation","tree planting"]}'],
            [65,'quiz','What is a sustainable development goal?',
             '{"question":"What is a sustainable development goal?","options":["A target to improve life while protecting the environment","A way to cut down forests faster","A plan to increase pollution","A method to waste resources"],"answer":0}'],
            [65,'quiz','Which of these is an individual action to help the environment?',
             '{"question":"Which of these is an individual action to help the environment?","options":["Saving water and electricity at home","Buying more plastic bags","Leaving lights on all night","Wasting food"],"answer":0}'],

            // ── LESSON 352 — Grammar (C6) ─────────────────────────────────────
            [352,'quiz','What is a relative clause?',
             '{"question":"What is a relative clause?","options":["A clause that gives more information about a noun","A type of noun","A main sentence","A punctuation mark"],"answer":0}'],
            [352,'quiz','Which word introduces a relative clause?',
             '{"question":"Which word introduces a relative clause?","options":["who, which, that","and, but, or","because, although","if, unless"],"answer":0}'],
            [352,'quiz','What is the passive voice?',
             '{"question":"What is the passive voice?","options":["When the subject receives the action","When the subject does the action","A type of adjective","A question form"],"answer":0}'],
            [352,'revision','Match each grammar term to an example.',
             '{"pairs":[["Conditional","If it rains, I will stay home."],["Passive voice","The book was written by him."],["Relative clause","The boy who won the race."],["Reported speech","She said she was tired."],["Question tag","You like football, don\'t you?"]]}'],
            [352,'vocabulary','A sentence that expresses a condition and its result is called a ___ sentence.',
             '{"sentence":"A sentence that expresses a condition and its result is called a ___ sentence.","answer":"conditional","alternatives":["conditional sentence"]}'],
            [352,'quiz','Convert to reported speech: He said, "I am tired."',
             '{"question":"Convert to reported speech: He said, \\"I am tired.\\"","options":["He said he was tired.","He said I am tired.","He said he is tired.","He said am tired."],"answer":0}'],

            // ── LESSON 353 — Vocabulary (C6) ─────────────────────────────────
            [353,'quiz','What does the suffix "-tion" do to a word?',
             '{"question":"What does the suffix \\"-tion\\" do to a word?","options":["Turns a verb into a noun","Makes it an adjective","Makes it negative","Turns it into a verb"],"answer":0}'],
            [353,'revision','Match each word to its definition.',
             '{"pairs":[["benevolent","Kind and generous"],["perseverance","Continuing despite difficulty"],["eloquent","Expressing well in speech"],["pragmatic","Practical and realistic"],["ambiguous","Having more than one meaning"]]}'],
            [353,'vocabulary','The prefix \\"mis-\\" means ___.',
             '{"sentence":"The prefix \\"mis-\\" means ___.","answer":"wrongly","alternatives":["wrong","badly","incorrectly"]}'],
            [353,'quiz','Which word contains a silent letter?',
             '{"question":"Which word contains a silent letter?","options":["knife","table","happy","big"],"answer":0}'],
            [353,'quiz','What is a homophone?',
             '{"question":"What is a homophone?","options":["Words that sound the same but have different meanings","Words with opposite meanings","Words with the same meaning","Words that rhyme"],"answer":0}'],

            // ── LESSON 354 — Comprehension (C6) ──────────────────────────────
            [354,'reading','Read: "The Amazon rainforest produces 20% of the world\'s oxygen. It is often called the lungs of the Earth. However, deforestation is destroying this vital resource at an alarming rate." Why is the Amazon called the lungs of the Earth?',
             '{"passage":"The Amazon rainforest produces 20% of the world\'s oxygen. It is often called the lungs of the Earth. However, deforestation is destroying this vital resource at an alarming rate.","question":"Why is the Amazon called the lungs of the Earth?","options":["It produces 20% of the world\'s oxygen","It is very large","It has many animals","It has many rivers"],"answer":0}'],
            [354,'reading','Marie Curie - comprehension',
             '{"passage":"Marie Curie was the first woman to win a Nobel Prize and the only person to win Nobel Prizes in two different sciences. She discovered polonium and radium. Her research on radioactivity changed science forever.","question":"What did Marie Curie discover?","options":["Polonium and radium","Gravity and electricity","Oxygen and nitrogen","DNA and cells"],"answer":0}'],
            [354,'quiz','What is an inference?',
             '{"question":"What is an inference?","options":["A conclusion drawn from evidence in a text","A direct quote from a text","The title of a passage","A type of punctuation"],"answer":0}'],
            [354,'vocabulary','When you read between the lines to understand what is implied, you are making an ___.',
             '{"sentence":"When you read between the lines to understand what is implied, you are making an ___.","answer":"inference","alternatives":["inferences"]}'],
            [354,'reading','Globalisation - comprehension',
             '{"passage":"Globalisation has connected the world like never before. Goods, ideas and people move across borders freely. However, it has also led to the loss of local cultures and increased inequality.","question":"What is one negative effect of globalisation?","options":["Loss of local cultures","Better communication","More travel","New technologies"],"answer":0}'],

            // ── LESSON 355 — Literature (C6) ──────────────────────────────────
            [355,'quiz','What is a metaphor?',
             '{"question":"What is a metaphor?","options":["A comparison that says one thing IS another","A comparison using like or as","A type of poem","A story structure"],"answer":0}'],
            [355,'quiz','What is a simile?',
             '{"question":"What is a simile?","options":["A comparison using like or as","A comparison without like or as","A type of noun","A story ending"],"answer":0}'],
            [355,'vocabulary','The repetition of the same consonant sound at the beginning of words is called ___.',
             '{"sentence":"The repetition of the same consonant sound at the beginning of words is called ___.","answer":"alliteration","alternatives":["Alliteration"]}'],
            [355,'revision','Match each literary device to its example.',
             '{"pairs":[["Simile","She runs like the wind."],["Metaphor","Life is a journey."],["Personification","The trees danced in the breeze."],["Alliteration","Peter Piper picked peppers."],["Onomatopoeia","The bees buzzed loudly."]]}'],
            [355,'quiz','What is the theme of a story?',
             '{"question":"What is the theme of a story?","options":["The central message or lesson","The main character","The setting","The plot summary"],"answer":0}'],
            [355,'quiz','What is the difference between a fable and a novel?',
             '{"question":"What is the difference between a fable and a novel?","options":["A fable is short with a moral lesson; a novel is long","A novel has animals; a fable does not","A fable is always true","A novel has no characters"],"answer":0}'],

            // ── LESSON 356 — Writing Skills (C6) ─────────────────────────────
            [356,'quiz','What is an argumentative essay?',
             '{"question":"What is an argumentative essay?","options":["An essay that takes a position and supports it with evidence","An essay that only describes things","A story about real events","A poem with rhyme"],"answer":0}'],
            [356,'quiz','What is the purpose of a conclusion in an essay?',
             '{"question":"What is the purpose of a conclusion in an essay?","options":["To summarise the main points and restate the position","To introduce new ideas","To list facts","To ask questions"],"answer":0}'],
            [356,'vocabulary','Words like "furthermore", "moreover" and "in addition" are called ___ words.',
             '{"sentence":"Words like \\"furthermore\\", \\"moreover\\" and \\"in addition\\" are called ___ words.","answer":"linking","alternatives":["connective","discourse marker","transition"]}'],
            [356,'quiz','In a formal letter, how do you end if you start with "Dear Sir/Madam"?',
             '{"question":"In a formal letter, how do you end if you start with \\"Dear Sir/Madam\\"?","options":["Yours faithfully","Yours sincerely","Best wishes","Kind regards"],"answer":0}'],
            [356,'quiz','What makes a persuasive text effective?',
             '{"question":"What makes a persuasive text effective?","options":["Strong evidence, rhetorical questions and emotional appeal","Only long sentences","Lots of adjectives","No punctuation"],"answer":0}'],
            [356,'revision','Match each essay type to its purpose.',
             '{"pairs":[["Narrative","To tell a story"],["Descriptive","To describe a scene or object"],["Argumentative","To persuade with evidence"],["Expository","To explain or inform"],["Reflective","To reflect on personal experience"]]}'],
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
        $this->command->info("   English C5+C6 : {$inserted} exercices ajoutés");
    }
}
