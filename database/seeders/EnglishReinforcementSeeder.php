<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnglishReinforcementSeeder extends Seeder
{
    public function run(): void
    {
        $this->englishC3();
        $this->englishC4();
        $this->englishC5extra();
        $this->englishC6extra();
        $this->command->info('✅ English reinforcement C3-C6 seeded');
    }

    private function lid(int $sid): ?int
    {
        return DB::table('lessons')
            ->join('units','lessons.unit_id','=','units.id')
            ->join('integrated_themes','units.integrated_theme_id','=','integrated_themes.id')
            ->where('integrated_themes.subject_id',$sid)->value('lessons.id');
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
            'content'=>json_encode(['type'=>'true_false','illustration'=>'📚','statement'=>$stmt,'answer'=>$ans]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function fi(int $lid, string $title, string $cat, array $sentences): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>$cat,
            'content'=>json_encode(['type'=>'fill_in','illustration'=>'✏️','sentences'=>$sentences]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    private function so(int $lid, string $title, array $words, string $correct): void
    {
        DB::table('exercises')->insert(['lesson_id'=>$lid,'title'=>$title,'category'=>'reading',
            'content'=>json_encode(['type'=>'sentence_order','illustration'=>'📝',
                'words'=>$words,'correct'=>$correct]),
            'created_at'=>now(),'updated_at'=>now()]);
    }

    // ── ENGLISH C3 (subject 24) ───────────────────────────────────────────
    private function englishC3(): void
    {
        $id = $this->lid(24); if (!$id) return;

        // Grammar
        $this->mcq($id,'Nouns C3','reading','📝','Which word is a noun?',['Run','Happy','Dog','Quickly'],2);
        $this->mcq($id,'Verbs C3','reading','📝','Which word is a verb?',['Beautiful','Jump','Fast','Blue'],1);
        $this->mcq($id,'Adjectives C3','reading','📝','Which word is an adjective?',['Eat','Tall','Run','Slowly'],1);
        $this->mcq($id,'Plural nouns','reading','📝','The plural of "child" is ___.',['childs','childrens','children','child'],2);
        $this->mcq($id,'Pronouns C3','reading','📝','Replace "Mary and I" with a pronoun.',['They','We','He','She'],1);
        $this->mcq($id,'Simple past','reading','⏰','Yesterday, she ___ to school.',['go','goes','went','gone'],2);
        $this->mcq($id,'Articles C3','reading','📝','___ elephant is a large animal.',['A','An','The','No article'],1);
        $this->mcq($id,'Prepositions C3','reading','📝','The book is ___ the table.',['in','on','at','by'],1);
        $this->fi($id,'Fill in C3 grammar','reading',[
            ['text'=>'She ___ (to be) a good student.','answer'=>'is'],
            ['text'=>'They ___ (to play) football every day.','answer'=>'play'],
            ['text'=>'He ___ (to go) to school yesterday.','answer'=>'went'],
        ]);
        $this->tf($id,'Noun fact C3','reading','A noun is the name of a person, place or thing.',true);
        $this->tf($id,'Verb fact C3','reading','A verb is a doing or being word.',true);

        // Vocabulary
        $this->mcq($id,'Synonyms C3','vocabulary','📚','Another word for "happy" is ___.',['sad','angry','glad','tired'],2);
        $this->mcq($id,'Antonyms C3','vocabulary','↔️','The opposite of "big" is ___.',['large','huge','small','tall'],2);
        $this->mcq($id,'Word meaning C3','vocabulary','📚','"Ancient" means ___.',['new','modern','very old','small'],2);

        // Comprehension
        $this->mcq($id,'Comprehension C3','reading','📖',
            'John woke up early. He washed his face and ate breakfast. Then he walked to school with his bag. What did John do first after waking up?',
            ['Ate breakfast','Walked to school','Washed his face','Put on his bag'],2);

        // Sentence order
        $this->so($id,'Arrange words C3',['school','to','She','goes','every','day'],'She goes to school every day');

        $this->command->info('   English C3: 16 exercises');
    }

    // ── ENGLISH C4 (subject 30) ───────────────────────────────────────────
    private function englishC4(): void
    {
        $id = $this->lid(30); if (!$id) return;

        // Grammar
        $this->mcq($id,'Tenses C4','reading','⏰','She has ___ her homework.',['finish','finishes','finished','finishing'],2);
        $this->mcq($id,'Conjunctions C4','reading','📝','She was tired ___ she kept working.',['but','so','or','nor'],0);
        $this->mcq($id,'Comparative C4','reading','📝','Mount Cameroon is ___ than Mount Oku.',['high','higher','highest','most high'],1);
        $this->mcq($id,'Superlative C4','reading','📝','He is the ___ student in the class.',['smart','smarter','smartest','most smart'],2);
        $this->mcq($id,'Direct speech C4','reading','💬','"I am tired," she said. This is an example of ___ speech.',['indirect','reported','direct','passive'],2);
        $this->mcq($id,'Question tags C4','reading','📝','She is your friend, ___?',['is she','isn\'t she','was she','wasn\'t she'],1);
        $this->mcq($id,'Adverbs C4','reading','📝','She runs ___.',['quick','quickly','quicker','quickest'],1);
        $this->fi($id,'Fill in C4 grammar','reading',[
            ['text'=>'He is taller ___ his brother.','answer'=>'than'],
            ['text'=>'She sings ___ (beautiful).','answer'=>'beautifully'],
            ['text'=>'___ you finished your work?','answer'=>'Have'],
        ]);
        $this->tf($id,'Comparative C4','reading','We use "-er" to compare two things.',true);
        $this->tf($id,'Superlative C4','reading','We use "-est" to compare more than two things.',true);

        // Vocabulary
        $this->mcq($id,'Homophones C4','vocabulary','📚','Which word sounds like "their"?',['there','here','where','fare'],0);
        $this->mcq($id,'Compound words C4','vocabulary','📚','"Football" is made from ___ words.',['3','1','2','4'],2);
        $this->mcq($id,'Idiom C4','vocabulary','📚','"It is raining cats and dogs" means ___.',['animals are falling','it is raining heavily','it is raining lightly','cats and dogs are outside'],1);

        // Comprehension
        $this->mcq($id,'Comprehension C4','reading','📖',
            'The market opens at 7am and closes at 6pm. Mama Bih arrived at 8am and left at 12pm. How many hours did she spend at the market?',
            ['3 hours','4 hours','5 hours','6 hours'],1);

        $this->so($id,'Arrange C4',['quickly','finished','homework','She','her'],'She finished her homework quickly');

        $this->command->info('   English C4: 15 exercises');
    }

    // ── ENGLISH C5 EXTRA (subject 36) ────────────────────────────────────
    private function englishC5extra(): void
    {
        $id = $this->lid(36); if (!$id) return;

        $this->mcq($id,'Modal verbs C5','reading','📝','You ___ wear a seatbelt. It is the law.',['could','might','must','would'],2);
        $this->mcq($id,'Passive voice C5','reading','📝','The cake ___ baked by Mum.',['is','was','were','has'],1);
        $this->mcq($id,'Reported speech C5','reading','💬','He said, "I am hungry." → He said that he ___ hungry.',['is','was','were','has been'],1);
        $this->mcq($id,'Clause types C5','reading','📝','In "Although it rained, we played", "Although it rained" is a ___ clause.',['main','relative','adverbial','noun'],2);
        $this->fi($id,'Fill in C5 extra','reading',[
            ['text'=>'The letter ___ (write) by the teacher.','answer'=>'was written'],
            ['text'=>'If I ___ (have) money, I would buy a bicycle.','answer'=>'had'],
        ]);
        $this->mcq($id,'Vocabulary C5 extra','vocabulary','📚','The word "benevolent" means ___.',['cruel','kind and generous','angry','lazy'],1);
        $this->tf($id,'Passive C5','reading','In passive voice, the subject receives the action.',true);

        $this->command->info('   English C5 extra: 7 exercises');
    }

    // ── ENGLISH C6 EXTRA (subject 42) ────────────────────────────────────
    private function englishC6extra(): void
    {
        $id = $this->lid(42); if (!$id) return;

        $this->mcq($id,'Figures of speech C6','reading','📝','"The wind whispered through the trees" is an example of ___.',['simile','metaphor','personification','alliteration'],2);
        $this->mcq($id,'Simile C6','reading','📝','"She is as brave as a lion" is a ___.',['metaphor','simile','personification','hyperbole'],1);
        $this->mcq($id,'Metaphor C6','reading','📝','"Life is a journey" is a ___.',['simile','metaphor','alliteration','onomatopoeia'],1);
        $this->mcq($id,'Essay types C6','reading','📝','A narrative essay tells ___.',['facts only','a story','an argument','instructions'],1);
        $this->mcq($id,'Letter writing C6','reading','✉️','A formal letter ends with ___.',['Yours truly only','Love','Yours faithfully or Yours sincerely','Best wishes'],2);
        $this->fi($id,'Fill in C6 extra','reading',[
            ['text'=>'___ he studied hard, he failed. (concession)','answer'=>'Although'],
            ['text'=>'She not only sings ___ also dances.','answer'=>'but'],
            ['text'=>'___ sooner had he left than it rained.','answer'=>'No'],
        ]);
        $this->mcq($id,'Onomatopoeia C6','vocabulary','📚','"The bees buzzed" — "buzzed" is an example of ___.',['simile','metaphor','onomatopoeia','alliteration'],2);
        $this->tf($id,'Figures of speech C6','reading','Personification gives human qualities to non-human things.',true);

        $this->command->info('   English C6 extra: 8 exercises');
    }
}
