<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSummariesSeeder extends Seeder
{
    public function run(): void
    {
        $updated = 0;

        $s0 = "## Grammar\n**Key concepts:**\n- **Nouns**: names of people, places, things (common & proper)\n- **Pronouns**: I, you, he, she, it, we, they\n- **Verbs**: action words \u2014 present, past, future tenses\n- **Adjectives**: describing words (big, small, red)\n- **Adverbs**: describe verbs (quickly, slowly)\n- **Prepositions**: in, on, at, under, beside\n- **Conjunctions**: and, but, or, because\n\n**Remember:** A sentence must have a subject and a verb.\n> *The boy **runs** fast.* \u2713\n";
        $updated += DB::table('units')->where('name','like',"%Grammar%")->whereNull('summary')->update(['summary'=>$s0,'updated_at'=>now()]);

        $s1 = "## Vocabulary\n**Building your word bank:**\n- Learn **synonyms** (words with similar meaning): happy = joyful\n- Learn **antonyms** (opposite words): hot \u2260 cold\n- Use **context clues** to guess unknown words\n- Learn **word families**: teach \u2192 teacher \u2192 teaching\n\n**Tips:**\n- Read as many books as possible\n- Keep a personal dictionary\n- Use new words in sentences\n";
        $updated += DB::table('units')->where('name','like',"%Vocabulary%")->whereNull('summary')->update(['summary'=>$s1,'updated_at'=>now()]);

        $s2 = "## Reading Comprehension\n**How to understand a text:**\n1. Read the title and predict the content\n2. Read the passage carefully at least twice\n3. Identify the **main idea**\n4. Find **supporting details**\n5. Answer questions using evidence from the text\n\n**Question types:**\n- **Literal**: answer found directly in the text\n- **Inferential**: use clues to find the answer\n- **Vocabulary**: meaning of words in context\n";
        $updated += DB::table('units')->where('name','like',"%Comprehension%")->whereNull('summary')->update(['summary'=>$s2,'updated_at'=>now()]);

        $s3 = "## Writing Skills\n**Types of writing:**\n- **Narrative**: tells a story (beginning, middle, end)\n- **Descriptive**: describes a person, place or thing vividly\n- **Persuasive**: gives reasons to convince the reader\n- **Informative**: explains facts and information\n\n**A good paragraph has:**\n- A topic sentence (main idea)\n- Supporting sentences (details)\n- A concluding sentence\n\n**Remember:** Always plan before you write!\n";
        $updated += DB::table('units')->where('name','like',"%Writing%")->whereNull('summary')->update(['summary'=>$s3,'updated_at'=>now()]);

        $s4 = "## Literature\n**Key literary elements:**\n- **Characters**: the people or animals in a story\n- **Setting**: where and when the story takes place\n- **Plot**: the sequence of events\n- **Theme**: the main message or lesson\n- **Conflict**: the main problem in the story\n\n**Types of literature:**\n- Prose (novels, short stories)\n- Poetry (poems, songs)\n- Drama (plays)\n";
        $updated += DB::table('units')->where('name','like',"%Literature%")->whereNull('summary')->update(['summary'=>$s4,'updated_at'=>now()]);

        $s5 = "## Numbers and Counting\n**Number operations:**\n- **Addition** (+): combining numbers together\n- **Subtraction** (\u2212): taking away\n- **Multiplication** (\u00d7): repeated addition\n- **Division** (\u00f7): sharing equally\n\n**BODMAS rule:** Brackets \u2192 Order \u2192 Division \u2192 Multiplication \u2192 Addition \u2192 Subtraction\n\n**Key facts:**\n- Even numbers end in 0, 2, 4, 6, 8\n- Odd numbers end in 1, 3, 5, 7, 9\n- Prime numbers have exactly 2 factors\n";
        $updated += DB::table('units')->where('name','like',"%Numbers%")->whereNull('summary')->update(['summary'=>$s5,'updated_at'=>now()]);

        $s6 = "## Fractions and Decimals\n**Fractions:**\n- Numerator (top number) \u00f7 Denominator (bottom number)\n- **Equivalent fractions**: 1/2 = 2/4 = 4/8\n- **HCF** (Highest Common Factor): largest factor shared by two numbers\n- **LCM** (Lowest Common Multiple): smallest multiple shared by two numbers\n\n**Decimals:**\n- 0.5 = 1/2, 0.25 = 1/4, 0.75 = 3/4\n- To convert fraction to decimal: divide numerator by denominator\n\n**Operations:**\n- Add/subtract fractions: same denominator needed\n- Multiply fractions: multiply numerators, multiply denominators\n";
        $updated += DB::table('units')->where('name','like',"%Fractions%")->whereNull('summary')->update(['summary'=>$s6,'updated_at'=>now()]);

        $s7 = "## Geometry\n**2D Shapes:**\n| Shape | Sides | Properties |\n|-------|-------|------------|\n| Triangle | 3 | angles add up to 180\u00b0 |\n| Rectangle | 4 | 4 right angles |\n| Square | 4 | equal sides + right angles |\n| Circle | 0 | radius, diameter, circumference |\n\n**3D Shapes:** cube, cuboid, sphere, cylinder, cone\n\n**Angles:**\n- Right angle = 90\u00b0\n- Acute angle < 90\u00b0\n- Obtuse angle > 90\u00b0\n- Straight angle = 180\u00b0\n\n**Area formulas:**\n- Rectangle: length \u00d7 width\n- Triangle: \u00bd \u00d7 base \u00d7 height\n";
        $updated += DB::table('units')->where('name','like',"%Geometry%")->whereNull('summary')->update(['summary'=>$s7,'updated_at'=>now()]);

        $s8 = "## Measurement\n**Length:** mm \u2192 cm \u2192 m \u2192 km (\u00d710, \u00d7100, \u00d71000)\n\n**Mass:** g \u2192 kg (\u00d71000)\n\n**Capacity:** ml \u2192 l (\u00d71000)\n\n**Time:**\n- 60 seconds = 1 minute\n- 60 minutes = 1 hour\n- 24 hours = 1 day\n- 7 days = 1 week\n- 12 months = 1 year\n\n**Temperature:** measured in degrees Celsius (\u00b0C)\n\n**Speed = Distance \u00f7 Time**\n";
        $updated += DB::table('units')->where('name','like',"%Measurement%")->whereNull('summary')->update(['summary'=>$s8,'updated_at'=>now()]);

        $s9 = "## Money and Statistics\n**Money (FCFA):**\n- Count coins and notes carefully\n- Calculate change: Change = Amount paid \u2212 Cost\n- Profit = Selling price \u2212 Cost price\n- Loss = Cost price \u2212 Selling price\n\n**Statistics:**\n- **Tally chart**: count data using marks\n- **Bar graph**: compare quantities using bars\n- **Pictograph**: use pictures to show data\n\n**Reading graphs:**\n1. Read the title\n2. Read the axes labels\n3. Read the scale carefully\n";
        $updated += DB::table('units')->where('name','like',"%Money%")->whereNull('summary')->update(['summary'=>$s9,'updated_at'=>now()]);

        $s10 = "## Fran\u00e7ais\n**La grammaire:**\n- **Le nom**: d\u00e9signe une personne, un animal, une chose\n- **L'article**: le, la, les (d\u00e9finis) / un, une, des (ind\u00e9finis)\n- **L'adjectif**: qualifie le nom (grand, petit, beau)\n- **Le verbe**: exprime une action ou un \u00e9tat\n\n**Les temps:**\n- **Pr\u00e9sent**: je mange, tu manges\n- **Pass\u00e9 compos\u00e9**: j'ai mang\u00e9, tu as mang\u00e9\n- **Futur proche**: je vais manger\n\n**La lecture:**\n- Lire \u00e0 voix haute avec expression\n- Comprendre les textes narratifs et informatifs\n";
        $updated += DB::table('units')->where('name','like',"%French%")->whereNull('summary')->update(['summary'=>$s10,'updated_at'=>now()]);

        $s11 = "## Science and Technology\n**The Human Body:**\n- Skeletal system: 206 bones support and protect the body\n- Digestive system: mouth \u2192 oesophagus \u2192 stomach \u2192 intestines\n- Circulatory system: heart pumps blood through arteries and veins\n- Respiratory system: lungs take in oxygen, release carbon dioxide\n- Nervous system: brain and nerves control the body\n\n**Plants:**\n- Parts: roots, stem, leaves, flowers, fruits, seeds\n- **Photosynthesis**: plants make food using sunlight, water, CO\u2082\n- Seed germination: seed \u2192 seedling \u2192 plant\n\n**Matter:**\n- Three states: solid, liquid, gas\n- Changes of state: melting, freezing, evaporation, condensation\n";
        $updated += DB::table('units')->where('name','like',"%Science%")->whereNull('summary')->update(['summary'=>$s11,'updated_at'=>now()]);

        $s12 = "## Information and Communication Technology\n**Computer components:**\n- **Input devices**: keyboard, mouse, microphone, scanner\n- **Output devices**: monitor, printer, speakers\n- **Storage**: hard drive, USB, CD/DVD\n\n**Operating System:**\n- Windows / Ubuntu manage all computer resources\n- Key shortcuts: Ctrl+C (copy), Ctrl+V (paste), Ctrl+Z (undo)\n\n**Word Processing:**\n- Create, edit, format and save documents\n- Use bold (**B**), italic (*I*), underline (**U**) for formatting\n\n**Internet safety:**\n- Never share personal information online\n- Be kind and respectful \u2014 avoid cyberbullying\n- Ask an adult if unsure about online content\n";
        $updated += DB::table('units')->where('name','like',"%ICT%")->whereNull('summary')->update(['summary'=>$s12,'updated_at'=>now()]);

        $s13 = "## Citizenship\n**National symbols of Cameroon:**\n- Flag: green (forests), red (unity), yellow (sun) + gold star\n- National anthem: *O Cameroon, Cradle of Our Forefathers*\n- National Day: 20th May\n\n**Government:**\n- Executive: President implements laws\n- Legislative: National Assembly makes laws\n- Judiciary: Courts apply justice\n\n**Children's rights (UNICEF):**\n- Right to education\n- Right to health care\n- Right to protection from violence\n- Right to a name and nationality\n\n**Civic duties:**\n- Obey the law\n- Pay taxes\n- Respect others\n";
        $updated += DB::table('units')->where('name','like',"%Citizenship%")->whereNull('summary')->update(['summary'=>$s13,'updated_at'=>now()]);

        $s14 = "## History\n**Sources of history:**\n- Written sources: books, newspapers, documents\n- Oral sources: stories, legends, songs\n- Archaeological sources: tools, buildings, artefacts\n\n**Key periods:**\n- Ancient civilisations: Egypt, Greece, Rome\n- The Slave Trade: 15th\u201319th century\n- Colonisation of Cameroon by Germany (1884)\n- Cameroon independence: 1st January 1960\n- Reunification: 1st October 1961\n\n**Resistance heroes:**\n- Rudolf Manga Bell (executed 1914)\n- Sultan Njoya (Bamoun script inventor)\n- Chief Galega II of Bali\n";
        $updated += DB::table('units')->where('name','like',"%History%")->whereNull('summary')->update(['summary'=>$s14,'updated_at'=>now()]);

        $s15 = "## Geography\n**Physical features of Cameroon:**\n- Highest peak: Mount Cameroon (active volcano, 4095m)\n- Main rivers: Sanaga, Wouri, Benue\n- Climate zones: Equatorial, Tropical, Sudanian, Sahelian\n\n**Economic activities:**\n- Agriculture: cocoa, coffee, bananas, palm oil\n- Fishing, forestry, petroleum extraction\n- Trade and commerce\n\n**The world:**\n- 7 continents: Africa, Asia, Europe, Americas, Oceania, Antarctica\n- 5 oceans: Pacific (largest), Atlantic, Indian, Arctic, Southern\n- Climate change: caused by burning fossil fuels\n";
        $updated += DB::table('units')->where('name','like',"%Geography%")->whereNull('summary')->update(['summary'=>$s15,'updated_at'=>now()]);

        $s16 = "## Home Economics and Vocational Skills\n**Nutrition and food:**\n- Balanced diet: carbohydrates, proteins, fats, vitamins, minerals, water\n- Food hygiene: wash hands, store food properly, avoid expired food\n- Table setting: placement of plates, glasses, cutlery\n\n**Needlework:**\n- Stitches: running stitch, back stitch, hem stitch\n- Care of clothes: wash, iron, store properly\n\n**Housekeeping:**\n- Keep the home clean and tidy\n- Laundry: sort clothes by colour and fabric type\n- Safety at home: avoid sharp objects, electrical hazards\n";
        $updated += DB::table('units')->where('name','like',"%Home Economics%")->whereNull('summary')->update(['summary'=>$s16,'updated_at'=>now()]);

        $s17 = "## Arts and Crafts\n**Visual arts:**\n- **Drawing**: use pencils, sketch basic shapes first\n- **Painting**: primary colours (red, yellow, blue) \u2192 mix to make secondary colours\n- **Sculpture/Moulding**: shape clay or papier-m\u00e2ch\u00e9 into 3D forms\n- **Weaving**: interlace strips to make mats and baskets\n\n**Colour theory:**\n- Primary: red, yellow, blue\n- Secondary: orange (red+yellow), green (yellow+blue), purple (red+blue)\n- Warm colours: red, orange, yellow\n- Cool colours: blue, green, purple\n\n**Traditional Cameroonian crafts:**\n- Beadwork, woodcarving, pottery, kente weaving\n";
        $updated += DB::table('units')->where('name','like',"%Arts%")->whereNull('summary')->update(['summary'=>$s17,'updated_at'=>now()]);

        $s18 = "## Physical Education\n**Benefits of exercise:**\n- Strengthens muscles and bones\n- Improves concentration and mood\n- Prevents disease and maintains healthy weight\n\n**Athletics:**\n- Sprints: 20m, 40m, 60m \u2014 run as fast as possible\n- Long jump: run-up \u2192 take-off \u2192 flight \u2192 landing\n- Throws: shot put, discus, javelin\n\n**Team sports:**\n- Football: 11 players, score by kicking ball into goal\n- Basketball: 5 players, score by shooting ball through hoop\n- Volleyball: 6 players, hit ball over net without letting it touch ground\n- Cricket: 11 players, bat and bowl\n\n**Warm-up always before exercise!**\n";
        $updated += DB::table('units')->where('name','like',"%Physical Education%")->whereNull('summary')->update(['summary'=>$s18,'updated_at'=>now()]);

        $s19 = "## National Languages and Cultures\n**Importance of national languages:**\n- Preserve cultural heritage and identity\n- Used in traditional ceremonies, songs, stories\n- Connect communities across generations\n\n**Oral traditions:**\n- Folktales: teach moral lessons\n- Proverbs: express wisdom (e.g. *\"It takes a village to raise a child\"*)\n- Traditional songs and dances\n\n**Cultural practices:**\n- Birth, marriage, and funeral ceremonies\n- Traditional governance structures\n- Respect for elders and community values\n\n**Key language skills:**\n- Greet in your local language\n- Learn counting and basic conversations\n";
        $updated += DB::table('units')->where('name','like',"%National Languages%")->whereNull('summary')->update(['summary'=>$s19,'updated_at'=>now()]);

        $s20 = "## Artistic Activities\n**We express ourselves through art!**\n\n**Activities:**\n- \ud83c\udfa8 **Drawing** and colouring\n- \u2702\ufe0f **Cutting** and pasting shapes\n- \ud83c\udfb5 **Singing** songs and rhymes\n- \ud83d\udc83 **Dancing** and movement\n- \ud83c\udffa **Moulding** with clay or playdough\n\n**Colours to know:**\nRed, Blue, Yellow, Green, Orange, Purple, Black, White\n\n**Shapes to recognise:**\nCircle \u2b55 | Square \ud83d\udd32 | Triangle \ud83d\udd3a | Rectangle\n";
        $updated += DB::table('units')->where('name','like',"%Artistic%")->whereNull('summary')->update(['summary'=>$s20,'updated_at'=>now()]);

        $s21 = "## FSLC Preparation \u2014 First School Leaving Certificate\n**Exam overview (MINEDUB):**\n- English Language, French Language\n- Mathematics\n- Science and Technology\n- Citizenship and Social Studies\n\n**Exam tips:**\n1. Read each question **twice** before answering\n2. Manage your time \u2014 don't spend too long on one question\n3. Show all working in Mathematics\n4. Write in clear, neat handwriting\n5. Check your answers before submitting\n\n**Key revision areas:**\n- Grammar rules and essay writing\n- Number operations and word problems\n- Human body systems and plants\n- Cameroon geography and history\n";
        $updated += DB::table('units')->where('name','like',"%FSLC%")->whereNull('summary')->update(['summary'=>$s21,'updated_at'=>now()]);

        echo 'UnitSummariesSeeder: ' . $updated . ' units updated.' . PHP_EOL;
    }
}