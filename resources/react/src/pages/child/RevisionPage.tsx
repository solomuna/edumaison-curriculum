// RevisionPage.tsx — Espace Révision : Dictionnaire, Grammaire, Conjugaison, Maths
import { useState, useEffect, useRef } from 'react'
import type { Child } from '../../types/child'

interface Props {
  child: Child
  onBack: () => void
}

type Module = 'dictionary' | 'grammar' | 'conjugation' | 'maths'

// ── Palette ───────────────────────────────────────────────────────────────────
const P = {
  bg:       '#E8DCC8',
  card:     '#F0E8D8',
  green:    '#1D6B2A',
  accent:   '#C47A3C',
  dark:     '#3D2B1F',
  soft:     '#7A6050',
  border:   '#D0C8B8',
  red:      '#CE1126',
}

// ── Données statiques — Grammaire MINEDUB ────────────────────────────────────
const GRAMMAR_RULES: Record<string, { title: string; rules: { rule: string; example: string }[] }[]> = {
  'C1': [
    { title: 'Nouns', rules: [
      { rule: 'A noun is a naming word for a person, place, animal or thing.', example: 'cat, school, Mary, Cameroon' },
      { rule: 'Proper nouns name specific people, places or days. They start with a capital letter.', example: 'Yaounde, Monday, Irma, Cameroon' },
      { rule: 'Common nouns name general things.', example: 'book, table, dog, water' },
      { rule: 'Plural nouns name more than one thing. Add -s or -es.', example: 'cat → cats, box → boxes, child → children' },
      { rule: 'Collective nouns name a group.', example: 'a herd of cattle, a flock of birds, a class of pupils' },
    ]},
    { title: 'Verbs', rules: [
      { rule: 'A verb is an action word. It tells us what someone does.', example: 'run, eat, write, play, sleep' },
      { rule: 'Add -s or -es for he / she / it in simple present tense.', example: 'She runs. He eats. It flies.' },
      { rule: 'The verb "to be": I am, You are, He/She/It is, We/They are.', example: 'I am a pupil. She is tall.' },
      { rule: 'The verb "to have": I have, You have, He/She/It has.', example: 'I have a book. She has a pen.' },
    ]},
    { title: 'Adjectives', rules: [
      { rule: 'An adjective describes a noun. It tells us what kind, how many or which.', example: 'big cat, red ball, three books, happy girl' },
      { rule: 'Adjectives can show colour, size, shape, number or feeling.', example: 'small, blue, round, two, sad' },
      { rule: 'Adjectives usually come before the noun.', example: 'a beautiful flower, a tall boy' },
    ]},
    { title: 'Pronouns', rules: [
      { rule: 'A pronoun replaces a noun to avoid repetition.', example: 'Mary went home. She ate dinner. (She = Mary)' },
      { rule: 'Personal pronouns: I, you, he, she, it, we, they.', example: 'They play football every day.' },
      { rule: 'Possessive pronouns show ownership.', example: 'my, your, his, her, its, our, their' },
    ]},
    { title: 'Punctuation & Spelling', rules: [
      { rule: 'A sentence begins with a capital letter.', example: 'The cat is black.' },
      { rule: 'A statement ends with a full stop (.).', example: 'I go to school every day.' },
      { rule: 'A question ends with a question mark (?).', example: 'Where do you live?' },
      { rule: 'An exclamation ends with an exclamation mark (!).', example: 'What a beautiful day!' },
    ]},
    { title: 'Vocabulary — Opposites', rules: [
      { rule: 'Opposites (antonyms) are words with opposite meanings.', example: 'big / small, hot / cold, day / night' },
      { rule: 'Common opposites in C1 curriculum.', example: 'happy / sad, long / short, fast / slow, old / new' },
    ]},
  ],
  'C2': [
    { title: 'Nouns — More Types', rules: [
      { rule: 'Abstract nouns name things you cannot see or touch — feelings and ideas.', example: 'love, happiness, anger, freedom, knowledge' },
      { rule: 'Countable nouns can be counted. Uncountable nouns cannot.', example: 'Countable: books, chairs | Uncountable: water, rice, sand' },
      { rule: 'Compound nouns are made of two words joined together.', example: 'blackboard, football, classroom, sunshine' },
    ]},
    { title: 'Pronouns — Extended', rules: [
      { rule: 'Subject pronouns come before the verb and do the action.', example: 'I, you, he, she, it, we, they — She reads every night.' },
      { rule: 'Object pronouns come after the verb or preposition.', example: 'me, you, him, her, it, us, them — Give it to him.' },
      { rule: 'Demonstrative pronouns point to something.', example: 'this (near), that (far), these (near plural), those (far plural)' },
    ]},
    { title: 'Prepositions', rules: [
      { rule: 'Prepositions of place tell us where something is.', example: 'on, in, under, beside, behind, between, above, below' },
      { rule: 'Prepositions of time tell us when something happens.', example: 'at (at 3pm), in (in January), on (on Monday)' },
      { rule: 'Prepositions of movement tell us the direction of movement.', example: 'to, from, into, out of, across, through, up, down' },
    ]},
    { title: 'Conjunctions', rules: [
      { rule: '"And" joins similar ideas.', example: 'I like mango and orange.' },
      { rule: '"But" shows contrast or difference.', example: 'I am tired but happy.' },
      { rule: '"Or" shows a choice.', example: 'Do you want rice or beans?' },
      { rule: '"Because" gives a reason.', example: 'I stayed home because I was sick.' },
      { rule: '"So" shows a result or consequence.', example: 'It rained heavily, so we stayed indoors.' },
    ]},
    { title: 'Adverbs', rules: [
      { rule: 'An adverb modifies (describes) a verb, adjective or another adverb.', example: 'She runs quickly. He is very tall.' },
      { rule: 'Adverbs of manner tell us how something is done. Often end in -ly.', example: 'carefully, slowly, happily, loudly' },
      { rule: 'Adverbs of time tell us when.', example: 'yesterday, today, tomorrow, soon, now, always' },
      { rule: 'Adverbs of place tell us where.', example: 'here, there, inside, outside, everywhere' },
    ]},
    { title: 'Tenses — Intro', rules: [
      { rule: 'Simple Present: for habits and facts. Add -s/-es for he/she/it.', example: 'I eat rice. She eats rice.' },
      { rule: 'Simple Past: for completed actions. Add -ed for regular verbs.', example: 'He walked to school. They played.' },
      { rule: 'Irregular past verbs do not follow the -ed rule.', example: 'go → went, eat → ate, see → saw, come → came' },
      { rule: 'Simple Future: use will + base verb.', example: 'We will visit grandmother tomorrow.' },
    ]},
  ],
  'C3': [
    { title: 'Tenses — All Three', rules: [
      { rule: 'Simple Present: habits, facts, routines. Add -s/-es for he/she/it.', example: 'The sun rises in the east.' },
      { rule: 'Present Continuous: actions happening right now. am/is/are + -ing.', example: 'I am reading a book. They are playing.' },
      { rule: 'Simple Past: completed actions. Regular verbs add -ed.', example: 'She walked home. He ate his food.' },
      { rule: 'Simple Future: will + base verb. For planned future actions.', example: 'We will go to Douala next week.' },
      { rule: 'Near future: going to + verb. For certain near future.', example: 'I am going to visit my grandmother.' },
    ]},
    { title: 'Adjectives — Degrees of Comparison', rules: [
      { rule: 'Positive: describes without comparing.', example: 'Mark is tall. The bag is heavy.' },
      { rule: 'Comparative: compares two things. Add -er or use "more".', example: 'Mark is taller than Ruth. This bag is more expensive.' },
      { rule: 'Superlative: compares three or more. Add -est or use "most".', example: 'Mark is the tallest in the class. This is the most beautiful dress.' },
      { rule: 'Irregular comparisons.', example: 'good → better → best | bad → worse → worst | many → more → most' },
    ]},
    { title: 'Nouns — Gender & Number', rules: [
      { rule: 'Masculine nouns refer to males; feminine nouns refer to females.', example: 'king / queen, father / mother, bull / cow, cock / hen' },
      { rule: 'Singular: one thing. Plural: more than one.', example: 'child → children, man → men, foot → feet, mouse → mice' },
      { rule: 'For nouns ending in -y, change y to i and add -es.', example: 'baby → babies, city → cities, fly → flies' },
      { rule: 'For nouns ending in -f or -fe, change to -ves.', example: 'leaf → leaves, knife → knives, wife → wives' },
    ]},
    { title: 'Sentence Structure', rules: [
      { rule: 'A simple sentence has one main clause: Subject + Verb + Object.', example: 'The girl (S) reads (V) a book (O).' },
      { rule: 'A compound sentence joins two simple sentences with a conjunction.', example: 'I went to market, but I forgot my money.' },
      { rule: 'Subject-Verb agreement: singular subject = singular verb.', example: 'The dog barks. (not: The dog bark.)' },
    ]},
    { title: 'Vocabulary — Synonyms & Antonyms', rules: [
      { rule: 'Synonyms are words with similar meanings.', example: 'big = large = huge | happy = joyful = glad' },
      { rule: 'Antonyms are words with opposite meanings.', example: 'beautiful / ugly, brave / cowardly, ancient / modern' },
      { rule: 'Context helps us understand the meaning of unfamiliar words.', example: '"The arid land had no water." → arid means dry.' },
    ]},
  ],
  'C4': [
    { title: 'Sentence Types', rules: [
      { rule: 'Declarative (statement): gives information. Ends with a full stop.', example: 'Cameroon has ten regions.' },
      { rule: 'Interrogative (question): asks something. Ends with ?.', example: 'Where do you come from?' },
      { rule: 'Imperative (command): gives an instruction. Subject "you" is understood.', example: 'Open your book. Sit down, please.' },
      { rule: 'Exclamatory: expresses strong feeling. Ends with !', example: 'What a wonderful goal! How amazing!' },
    ]},
    { title: 'Relative Pronouns & Clauses', rules: [
      { rule: '"Who" refers to people.', example: 'The teacher who teaches us English is kind.' },
      { rule: '"Which" refers to animals or things.', example: 'The book which I borrowed is very interesting.' },
      { rule: '"Whose" shows possession.', example: 'The boy whose bag was stolen went to the headmaster.' },
      { rule: '"That" can refer to people or things (restrictive clauses).', example: 'The house that burned down was old.' },
      { rule: '"Where" refers to a place.', example: 'The school where I study is well-equipped.' },
    ]},
    { title: 'Composition — Writing Types', rules: [
      { rule: 'Narrative writing tells a story with characters, setting and plot.', example: 'One day, a young boy found a magic stone in the forest...' },
      { rule: 'Descriptive writing paints a picture using the five senses.', example: 'The market was loud and colourful, filled with the smell of ripe mangoes...' },
      { rule: 'Persuasive writing tries to convince the reader. Give reasons and evidence.', example: 'We should protect our forests because they provide oxygen, shelter and food...' },
      { rule: 'A good paragraph has a topic sentence, supporting sentences and a conclusion.', example: 'Topic: Trees are important. Support: They give us oxygen, food and shelter. Conclusion: We must protect them.' },
    ]},
    { title: 'Conjunctions — Advanced', rules: [
      { rule: 'Subordinating conjunctions introduce dependent clauses.', example: 'because, although, when, if, unless, since, after, before, until' },
      { rule: '"Although/Even though" introduces contrast.', example: 'Although it was raining, we went to school.' },
      { rule: '"Unless" means "if not".', example: 'You will fail unless you study hard.' },
      { rule: '"Since" can mean "because" or "from a time in the past".', example: 'Since you are late, you must apologise. | I have been here since Monday.' },
    ]},
    { title: 'Interjections', rules: [
      { rule: 'An interjection expresses sudden feeling or emotion.', example: 'Oh! Wow! Ouch! Hurray! Alas! Bravo!' },
      { rule: 'Interjections are followed by an exclamation mark or comma.', example: 'Ouch! That hurts. Oh, I forgot my book.' },
    ]},
    { title: 'Vocabulary — Word Formation', rules: [
      { rule: 'Prefixes are added to the beginning of a word to change its meaning.', example: 'un- (unhappy), dis- (disagree), re- (rewrite), mis- (misunderstand)' },
      { rule: 'Suffixes are added to the end of a word to change its form.', example: '-ful (careful), -less (careless), -ness (happiness), -er (teacher)' },
      { rule: 'Synonyms: use a thesaurus to find words with similar meanings.', example: 'said → stated, remarked, declared, announced, whispered' },
    ]},
  ],
  'C5': [
    { title: 'Perfect Tenses', rules: [
      { rule: 'Present Perfect: have/has + past participle. Connects past to present.', example: 'I have finished my homework. She has visited Douala.' },
      { rule: 'Past Perfect: had + past participle. Action that happened before another past action.', example: 'By the time we arrived, the teacher had already left.' },
      { rule: 'Present Perfect Continuous: have/has been + verb-ing. Ongoing action started in past.', example: 'I have been studying for three hours.' },
      { rule: 'Common irregular past participles.', example: 'go → gone, write → written, eat → eaten, see → seen, break → broken' },
    ]},
    { title: 'Direct & Indirect Speech', rules: [
      { rule: 'Direct speech: exact words, inside quotation marks.', example: 'She said, "I am going to the market."' },
      { rule: 'Indirect (Reported) speech: reports what was said without quotation marks.', example: 'She said that she was going to the market.' },
      { rule: 'Tense shift in reported speech: present → past.', example: 'Direct: "I am tired." → Indirect: He said he was tired.' },
      { rule: 'Pronoun shift in reported speech.', example: 'Direct: "I will help you." → Indirect: She said she would help me.' },
      { rule: 'Time expressions shift in reported speech.', example: 'now → then | today → that day | tomorrow → the next day | yesterday → the day before' },
    ]},
    { title: 'Active and Passive Voice', rules: [
      { rule: 'Active voice: the subject does the action.', example: 'The chef (S) cooked (V) a delicious meal (O).' },
      { rule: 'Passive voice: the subject receives the action. Object becomes subject.', example: 'A delicious meal was cooked by the chef.' },
      { rule: 'Form passive: subject + am/is/are/was/were + past participle (+ by + agent).', example: 'The letter was written by the secretary.' },
      { rule: 'Use passive when the doer is unknown or unimportant.', example: 'The school was built in 1980.' },
    ]},
    { title: 'Formal Letters', rules: [
      { rule: 'A formal letter has: sender\'s address, date, recipient\'s address, salutation, body, complimentary close, signature.', example: 'P.O. Box 45, Yaounde | 15th April 2026 | Dear Sir/Madam,' },
      { rule: 'Salutation: Dear Sir/Madam (unknown) or Dear Mr/Mrs + name (known).', example: 'Dear Sir, | Dear Mrs. Ngoe,' },
      { rule: 'Complimentary close: Yours faithfully (unknown) or Yours sincerely (known).', example: 'Yours faithfully, | Yours sincerely,' },
      { rule: 'Language in formal letters is polite, clear and impersonal.', example: 'I am writing to inform you... | I would be grateful if...' },
    ]},
    { title: 'Comprehension Skills', rules: [
      { rule: 'Literal questions: answers are directly stated in the text.', example: '"According to the passage, where does the boy live?"' },
      { rule: 'Inferential questions: answers require reading between the lines.', example: '"What does the author suggest about the character\'s feelings?"' },
      { rule: 'Vocabulary in context: use surrounding words to deduce meaning.', example: '"The famished children devoured the food." → famished = very hungry' },
    ]},
  ],
  'C6': [
    { title: 'Complex Sentences', rules: [
      { rule: 'A complex sentence has one main (independent) clause and at least one subordinate (dependent) clause.', example: 'Although it was raining (sub), we went to school (main).' },
      { rule: 'Subordinating conjunctions: although, because, when, if, since, while, as, until, unless, before, after.', example: 'If you work hard, you will succeed.' },
      { rule: 'A compound-complex sentence has two main clauses and at least one subordinate clause.', example: 'When I was young, I loved football, and I played every day.' },
    ]},
    { title: 'Passive Voice — Advanced', rules: [
      { rule: 'Passive voice in different tenses.', example: 'Present: The work is done. | Past: The work was done. | Future: The work will be done.' },
      { rule: 'Passive with modal verbs.', example: 'The problem can be solved. | The rule must be followed.' },
      { rule: 'By-phrase can be omitted if the agent is obvious or unknown.', example: 'The road was built. (We don\'t say "by the workers" — it is obvious.)' },
    ]},
    { title: 'Argumentative Writing', rules: [
      { rule: 'An argumentative essay presents a position and defends it with evidence.', example: 'Introduction: state your position. Body: 2-3 arguments with evidence. Conclusion: restate your position.' },
      { rule: 'Use discourse markers to organise your argument.', example: 'Firstly, ... Furthermore, ... On the other hand, ... In conclusion,...' },
      { rule: 'Acknowledge the opposing view and refute it.', example: 'Some people argue that... However, this is not the case because...' },
      { rule: 'Avoid using "I think" or "I believe" in formal argumentative writing.', example: 'It is evident that... Evidence suggests that... Studies show that...' },
    ]},
    { title: 'Vocabulary — Advanced Word Formation', rules: [
      { rule: 'Prefixes that change meaning: re- (again), pre- (before), post- (after), anti- (against), inter- (between).', example: 'rewrite, prehistoric, postpone, antibiotic, international' },
      { rule: 'Noun suffixes: -tion/-sion, -ment, -ity, -ness, -ance.', example: 'educate → education | develop → development | active → activity' },
      { rule: 'Adjective suffixes: -ous, -ful, -less, -ive, -ible/-able.', example: 'danger → dangerous | beauty → beautiful | use → useless' },
      { rule: 'Synonyms for common verbs — use for variety in writing.', example: 'say → state, declare, assert, claim, argue | big → vast, enormous, immense' },
    ]},
    { title: 'Comprehension — Advanced', rules: [
      { rule: 'Summarising: identify the main idea and key points of a text.', example: 'Read the passage, identify topic sentences, write in your own words.' },
      { rule: 'Tone and mood: the writer\'s attitude towards the subject.', example: 'Formal tone: academic writing. Informal tone: letters to friends. Humorous tone: comedy.' },
      { rule: 'Figurative language: metaphor, simile, personification, hyperbole.', example: 'Simile: as brave as a lion | Metaphor: life is a journey | Personification: the wind whispered' },
    ]},
  ],
}

// ── Données statiques — Conjugaison ──────────────────────────────────────────
// ── Données conjugaison bilingue EN + FR ─────────────────────────────────────

type Lang = 'en' | 'fr'

const VERBS_EN = [
  { verb: 'to be', short: 'be', meanings: 'être',
    subjects_en: ['I', 'You', 'He/She/It', 'We', 'You (pl)', 'They'],
    conjugations: {
      'Simple Present':    ['am', 'are', 'is', 'are', 'are', 'are'],
      'Simple Past':       ['was', 'were', 'was', 'were', 'were', 'were'],
      'Simple Future':     ['will be', 'will be', 'will be', 'will be', 'will be', 'will be'],
      'Pres. Continuous':  ['am being', 'are being', 'is being', 'are being', 'are being', 'are being'],
      'Pres. Perfect':     ['have been', 'have been', 'has been', 'have been', 'have been', 'have been'],
      'Past Perfect':      ['had been', 'had been', 'had been', 'had been', 'had been', 'had been'],
    }},
  { verb: 'to have', short: 'have', meanings: 'avoir',
    subjects_en: ['I', 'You', 'He/She/It', 'We', 'You (pl)', 'They'],
    conjugations: {
      'Simple Present':    ['have', 'have', 'has', 'have', 'have', 'have'],
      'Simple Past':       ['had', 'had', 'had', 'had', 'had', 'had'],
      'Simple Future':     ['will have', 'will have', 'will have', 'will have', 'will have', 'will have'],
      'Pres. Continuous':  ['am having', 'are having', 'is having', 'are having', 'are having', 'are having'],
      'Pres. Perfect':     ['have had', 'have had', 'has had', 'have had', 'have had', 'have had'],
      'Past Perfect':      ['had had', 'had had', 'had had', 'had had', 'had had', 'had had'],
    }},
  { verb: 'to go', short: 'go', meanings: 'aller',
    subjects_en: ['I', 'You', 'He/She/It', 'We', 'You (pl)', 'They'],
    conjugations: {
      'Simple Present':    ['go', 'go', 'goes', 'go', 'go', 'go'],
      'Simple Past':       ['went', 'went', 'went', 'went', 'went', 'went'],
      'Simple Future':     ['will go', 'will go', 'will go', 'will go', 'will go', 'will go'],
      'Pres. Continuous':  ['am going', 'are going', 'is going', 'are going', 'are going', 'are going'],
      'Pres. Perfect':     ['have gone', 'have gone', 'has gone', 'have gone', 'have gone', 'have gone'],
      'Past Perfect':      ['had gone', 'had gone', 'had gone', 'had gone', 'had gone', 'had gone'],
    }},
  { verb: 'to do', short: 'do', meanings: 'faire',
    subjects_en: ['I', 'You', 'He/She/It', 'We', 'You (pl)', 'They'],
    conjugations: {
      'Simple Present':    ['do', 'do', 'does', 'do', 'do', 'do'],
      'Simple Past':       ['did', 'did', 'did', 'did', 'did', 'did'],
      'Simple Future':     ['will do', 'will do', 'will do', 'will do', 'will do', 'will do'],
      'Pres. Continuous':  ['am doing', 'are doing', 'is doing', 'are doing', 'are doing', 'are doing'],
      'Pres. Perfect':     ['have done', 'have done', 'has done', 'have done', 'have done', 'have done'],
      'Past Perfect':      ['had done', 'had done', 'had done', 'had done', 'had done', 'had done'],
    }},
  { verb: 'to see', short: 'see', meanings: 'voir',
    subjects_en: ['I', 'You', 'He/She/It', 'We', 'You (pl)', 'They'],
    conjugations: {
      'Simple Present':    ['see', 'see', 'sees', 'see', 'see', 'see'],
      'Simple Past':       ['saw', 'saw', 'saw', 'saw', 'saw', 'saw'],
      'Simple Future':     ['will see', 'will see', 'will see', 'will see', 'will see', 'will see'],
      'Pres. Continuous':  ['am seeing', 'are seeing', 'is seeing', 'are seeing', 'are seeing', 'are seeing'],
      'Pres. Perfect':     ['have seen', 'have seen', 'has seen', 'have seen', 'have seen', 'have seen'],
      'Past Perfect':      ['had seen', 'had seen', 'had seen', 'had seen', 'had seen', 'had seen'],
    }},
  { verb: 'to eat', short: 'eat', meanings: 'manger',
    subjects_en: ['I', 'You', 'He/She/It', 'We', 'You (pl)', 'They'],
    conjugations: {
      'Simple Present':    ['eat', 'eat', 'eats', 'eat', 'eat', 'eat'],
      'Simple Past':       ['ate', 'ate', 'ate', 'ate', 'ate', 'ate'],
      'Simple Future':     ['will eat', 'will eat', 'will eat', 'will eat', 'will eat', 'will eat'],
      'Pres. Continuous':  ['am eating', 'are eating', 'is eating', 'are eating', 'are eating', 'are eating'],
      'Pres. Perfect':     ['have eaten', 'have eaten', 'has eaten', 'have eaten', 'have eaten', 'have eaten'],
      'Past Perfect':      ['had eaten', 'had eaten', 'had eaten', 'had eaten', 'had eaten', 'had eaten'],
    }},
  { verb: 'to write', short: 'write', meanings: 'écrire',
    subjects_en: ['I', 'You', 'He/She/It', 'We', 'You (pl)', 'They'],
    conjugations: {
      'Simple Present':    ['write', 'write', 'writes', 'write', 'write', 'write'],
      'Simple Past':       ['wrote', 'wrote', 'wrote', 'wrote', 'wrote', 'wrote'],
      'Simple Future':     ['will write', 'will write', 'will write', 'will write', 'will write', 'will write'],
      'Pres. Continuous':  ['am writing', 'are writing', 'is writing', 'are writing', 'are writing', 'are writing'],
      'Pres. Perfect':     ['have written', 'have written', 'has written', 'have written', 'have written', 'have written'],
      'Past Perfect':      ['had written', 'had written', 'had written', 'had written', 'had written', 'had written'],
    }},
  { verb: 'to play', short: 'play', meanings: 'jouer',
    subjects_en: ['I', 'You', 'He/She/It', 'We', 'You (pl)', 'They'],
    conjugations: {
      'Simple Present':    ['play', 'play', 'plays', 'play', 'play', 'play'],
      'Simple Past':       ['played', 'played', 'played', 'played', 'played', 'played'],
      'Simple Future':     ['will play', 'will play', 'will play', 'will play', 'will play', 'will play'],
      'Pres. Continuous':  ['am playing', 'are playing', 'is playing', 'are playing', 'are playing', 'are playing'],
      'Pres. Perfect':     ['have played', 'have played', 'has played', 'have played', 'have played', 'have played'],
      'Past Perfect':      ['had played', 'had played', 'had played', 'had played', 'had played', 'had played'],
    }},
]

const VERBS_FR = [
  { verb: 'être', short: 'être', meanings: 'to be',
    subjects_fr: ['je/j\'', 'tu', 'il/elle', 'nous', 'vous', 'ils/elles'],
    conjugations: {
      'Présent':           ['suis', 'es', 'est', 'sommes', 'êtes', 'sont'],
      'Imparfait':         ['étais', 'étais', 'était', 'étions', 'étiez', 'étaient'],
      'Passé composé':     ['ai été', 'as été', 'a été', 'avons été', 'avez été', 'ont été'],
      'Plus-que-parfait':  ['avais été', 'avais été', 'avait été', 'avions été', 'aviez été', 'avaient été'],
      'Futur simple':      ['serai', 'seras', 'sera', 'serons', 'serez', 'seront'],
      'Futur proche':      ['vais être', 'vas être', 'va être', 'allons être', 'allez être', 'vont être'],
    }},
  { verb: 'avoir', short: 'avoir', meanings: 'to have',
    subjects_fr: ['j\'', 'tu', 'il/elle', 'nous', 'vous', 'ils/elles'],
    conjugations: {
      'Présent':           ['ai', 'as', 'a', 'avons', 'avez', 'ont'],
      'Imparfait':         ['avais', 'avais', 'avait', 'avions', 'aviez', 'avaient'],
      'Passé composé':     ['ai eu', 'as eu', 'a eu', 'avons eu', 'avez eu', 'ont eu'],
      'Plus-que-parfait':  ['avais eu', 'avais eu', 'avait eu', 'avions eu', 'aviez eu', 'avaient eu'],
      'Futur simple':      ['aurai', 'auras', 'aura', 'aurons', 'aurez', 'auront'],
      'Futur proche':      ['vais avoir', 'vas avoir', 'va avoir', 'allons avoir', 'allez avoir', 'vont avoir'],
    }},
  { verb: 'aller', short: 'aller', meanings: 'to go',
    subjects_fr: ['je', 'tu', 'il/elle', 'nous', 'vous', 'ils/elles'],
    conjugations: {
      'Présent':           ['vais', 'vas', 'va', 'allons', 'allez', 'vont'],
      'Imparfait':         ['allais', 'allais', 'allait', 'allions', 'alliez', 'allaient'],
      'Passé composé':     ['suis allé(e)', 'es allé(e)', 'est allé(e)', 'sommes allés', 'êtes allés', 'sont allés'],
      'Plus-que-parfait':  ['étais allé(e)', 'étais allé(e)', 'était allé(e)', 'étions allés', 'étiez allés', 'étaient allés'],
      'Futur simple':      ['irai', 'iras', 'ira', 'irons', 'irez', 'iront'],
      'Futur proche':      ['vais aller', 'vas aller', 'va aller', 'allons aller', 'allez aller', 'vont aller'],
    }},
  { verb: 'faire', short: 'faire', meanings: 'to do/make',
    subjects_fr: ['je', 'tu', 'il/elle', 'nous', 'vous', 'ils/elles'],
    conjugations: {
      'Présent':           ['fais', 'fais', 'fait', 'faisons', 'faites', 'font'],
      'Imparfait':         ['faisais', 'faisais', 'faisait', 'faisions', 'faisiez', 'faisaient'],
      'Passé composé':     ['ai fait', 'as fait', 'a fait', 'avons fait', 'avez fait', 'ont fait'],
      'Plus-que-parfait':  ['avais fait', 'avais fait', 'avait fait', 'avions fait', 'aviez fait', 'avaient fait'],
      'Futur simple':      ['ferai', 'feras', 'fera', 'ferons', 'ferez', 'feront'],
      'Futur proche':      ['vais faire', 'vas faire', 'va faire', 'allons faire', 'allez faire', 'vont faire'],
    }},
  { verb: 'vouloir', short: 'vouloir', meanings: 'to want',
    subjects_fr: ['je', 'tu', 'il/elle', 'nous', 'vous', 'ils/elles'],
    conjugations: {
      'Présent':           ['veux', 'veux', 'veut', 'voulons', 'voulez', 'veulent'],
      'Imparfait':         ['voulais', 'voulais', 'voulait', 'voulions', 'vouliez', 'voulaient'],
      'Passé composé':     ['ai voulu', 'as voulu', 'a voulu', 'avons voulu', 'avez voulu', 'ont voulu'],
      'Plus-que-parfait':  ['avais voulu', 'avais voulu', 'avait voulu', 'avions voulu', 'aviez voulu', 'avaient voulu'],
      'Futur simple':      ['voudrai', 'voudras', 'voudra', 'voudrons', 'voudrez', 'voudront'],
      'Futur proche':      ['vais vouloir', 'vas vouloir', 'va vouloir', 'allons vouloir', 'allez vouloir', 'vont vouloir'],
    }},
  { verb: 'parler', short: 'parler', meanings: 'to speak (-er model)',
    subjects_fr: ['je', 'tu', 'il/elle', 'nous', 'vous', 'ils/elles'],
    conjugations: {
      'Présent':           ['parle', 'parles', 'parle', 'parlons', 'parlez', 'parlent'],
      'Imparfait':         ['parlais', 'parlais', 'parlait', 'parlions', 'parliez', 'parlaient'],
      'Passé composé':     ['ai parlé', 'as parlé', 'a parlé', 'avons parlé', 'avez parlé', 'ont parlé'],
      'Plus-que-parfait':  ['avais parlé', 'avais parlé', 'avait parlé', 'avions parlé', 'aviez parlé', 'avaient parlé'],
      'Futur simple':      ['parlerai', 'parleras', 'parlera', 'parlerons', 'parlerez', 'parleront'],
      'Futur proche':      ['vais parler', 'vas parler', 'va parler', 'allons parler', 'allez parler', 'vont parler'],
    }},
  { verb: 'finir', short: 'finir', meanings: 'to finish (-ir model)',
    subjects_fr: ['je', 'tu', 'il/elle', 'nous', 'vous', 'ils/elles'],
    conjugations: {
      'Présent':           ['finis', 'finis', 'finit', 'finissons', 'finissez', 'finissent'],
      'Imparfait':         ['finissais', 'finissais', 'finissait', 'finissions', 'finissiez', 'finissaient'],
      'Passé composé':     ['ai fini', 'as fini', 'a fini', 'avons fini', 'avez fini', 'ont fini'],
      'Plus-que-parfait':  ['avais fini', 'avais fini', 'avait fini', 'avions fini', 'aviez fini', 'avaient fini'],
      'Futur simple':      ['finirai', 'finiras', 'finira', 'finirons', 'finirez', 'finiront'],
      'Futur proche':      ['vais finir', 'vas finir', 'va finir', 'allons finir', 'allez finir', 'vont finir'],
    }},
  { verb: 'prendre', short: 'prendre', meanings: 'to take',
    subjects_fr: ['je', 'tu', 'il/elle', 'nous', 'vous', 'ils/elles'],
    conjugations: {
      'Présent':           ['prends', 'prends', 'prend', 'prenons', 'prenez', 'prennent'],
      'Imparfait':         ['prenais', 'prenais', 'prenait', 'prenions', 'preniez', 'prenaient'],
      'Passé composé':     ['ai pris', 'as pris', 'a pris', 'avons pris', 'avez pris', 'ont pris'],
      'Plus-que-parfait':  ['avais pris', 'avais pris', 'avait pris', 'avions pris', 'aviez pris', 'avaient pris'],
      'Futur simple':      ['prendrai', 'prendras', 'prendra', 'prendrons', 'prendrez', 'prendront'],
      'Futur proche':      ['vais prendre', 'vas prendre', 'va prendre', 'allons prendre', 'allez prendre', 'vont prendre'],
    }},
]

// ── Données statiques — Maths ─────────────────────────────────────────────────
// ── Données Maths par niveau avec mini-quiz ───────────────────────────────────
interface MathRule { rule: string; formula?: string; example: string }
interface MathQuiz { question: string; options: string[]; answer: number; explanation: string }
interface MathSection { title: string; rules: MathRule[]; quiz?: MathQuiz }

const MATHS_BY_LEVEL: Record<string, MathSection[]> = {
  'C1': [
    { title: 'Numbers 0–100', rules: [
      { rule: 'Count in ones, twos, fives and tens up to 100.', example: '2, 4, 6, 8, 10... | 5, 10, 15, 20...' },
      { rule: 'Place value: tens and units.', formula: '37 = 3 tens + 7 units', example: '37 = 30 + 7' },
      { rule: 'Ordering numbers: use < (less than) and > (greater than).', example: '45 < 67 | 92 > 88' },
    ], quiz: { question: 'What is 30 + 7?', options: ['37', '73', '27', '307'], answer: 0, explanation: '30 + 7 = 37 (3 tens + 7 units)' }},
    { title: 'Addition & Subtraction', rules: [
      { rule: 'Addition: joining two or more numbers together.', formula: 'a + b = c', example: '24 + 15 = 39' },
      { rule: 'Subtraction: taking one number away from another.', formula: 'a - b = c', example: '48 - 23 = 25' },
      { rule: 'The commutative property: order does not matter in addition.', example: '5 + 3 = 3 + 5 = 8' },
    ], quiz: { question: '56 - 24 = ?', options: ['32', '80', '30', '34'], answer: 0, explanation: '56 - 24: 6-4=2, 50-20=30, so 32' }},
    { title: 'Multiplication & Division (Intro)', rules: [
      { rule: 'Multiplication is repeated addition.', formula: 'a × b', example: '3 × 4 = 4 + 4 + 4 = 12' },
      { rule: 'Division is sharing equally.', formula: 'a ÷ b', example: '12 ÷ 3 = 4 (share 12 into 3 equal groups)' },
      { rule: 'Multiplication tables for 2 and 5 are essential at C1.', example: '2×6=12 | 5×4=20' },
    ], quiz: { question: '5 × 4 = ?', options: ['9', '20', '25', '45'], answer: 1, explanation: '5 × 4 = 5+5+5+5 = 20' }},
    { title: 'Shapes (2D)', rules: [
      { rule: 'A circle has no sides and no corners.', example: 'coin, wheel, clock face' },
      { rule: 'A triangle has 3 sides and 3 corners (angles).', example: 'road sign, roof of a house' },
      { rule: 'A square has 4 equal sides and 4 right angles.', example: 'window pane, tile' },
      { rule: 'A rectangle has 2 long sides, 2 short sides and 4 right angles.', example: 'door, book cover, table top' },
    ], quiz: { question: 'How many sides does a triangle have?', options: ['2', '3', '4', '5'], answer: 1, explanation: 'A triangle always has exactly 3 sides and 3 angles.' }},
    { title: 'Money (FCFA) C1', rules: [
      { rule: 'Coins used in Cameroon: 1, 5, 25, 50, 100 FCFA.', example: '2 coins of 25 FCFA = 50 FCFA' },
      { rule: 'Adding amounts of money.', example: '75 FCFA + 25 FCFA = 100 FCFA' },
    ], quiz: { question: 'You have 50 FCFA and spend 25 FCFA. How much is left?', options: ['75 FCFA', '25 FCFA', '15 FCFA', '35 FCFA'], answer: 1, explanation: '50 - 25 = 25 FCFA remaining.' }},
    { title: 'Time', rules: [
      { rule: '1 minute = 60 seconds | 1 hour = 60 minutes | 1 day = 24 hours.', example: '2 hours = 120 minutes' },
      { rule: 'Reading the clock: the short hand shows hours, the long hand shows minutes.', example: 'Short hand on 3, long hand on 12 → 3 o\'clock' },
      { rule: '1 week = 7 days | 1 year = 12 months = 365 days.', example: 'January has 31 days. February has 28 or 29 days.' },
    ], quiz: { question: 'How many minutes are in 1 hour?', options: ['24', '100', '60', '30'], answer: 2, explanation: '1 hour = 60 minutes. Always.' }},
  ],
  'C2': [
    { title: 'Numbers 0–200', rules: [
      { rule: 'Place value: hundreds, tens and units.', formula: '152 = 1 hundred + 5 tens + 2 units', example: '152 = 100 + 50 + 2' },
      { rule: 'Count in 4s and 5s.', example: '4, 8, 12, 16, 20... | 5, 10, 15, 20, 25...' },
      { rule: 'Odd numbers end in 1, 3, 5, 7, 9. Even numbers end in 0, 2, 4, 6, 8.', example: '137 is odd. 164 is even.' },
    ], quiz: { question: 'What is the value of the digit 5 in 152?', options: ['5', '50', '500', '150'], answer: 1, explanation: 'In 152, the 5 is in the tens position, so its value is 50.' }},
    { title: 'Fractions — Introduction', rules: [
      { rule: 'A fraction is a part of a whole.', formula: 'Numerator / Denominator', example: '1/2 means 1 part out of 2 equal parts.' },
      { rule: 'The denominator shows total equal parts. The numerator shows how many parts you have.', example: 'In 3/4, denominator = 4 (total parts), numerator = 3 (your parts).' },
      { rule: 'Common fractions: half (1/2), quarter (1/4), three-quarters (3/4).', example: '1/2 of 20 = 10 | 1/4 of 20 = 5' },
    ], quiz: { question: '1/4 of 20 = ?', options: ['4', '5', '10', '8'], answer: 1, explanation: '1/4 means divide by 4. 20 ÷ 4 = 5.' }},
    { title: 'Sets & Venn Diagrams', rules: [
      { rule: 'A set is a collection of objects with something in common.', example: '{1, 2, 3, 4, 5} = set of numbers less than 6' },
      { rule: 'The universal set (ξ or U) contains all items being considered.', example: 'U = {all pupils in a class}' },
      { rule: 'Intersection (∩): elements in BOTH sets.', example: '{1,2,3} ∩ {2,3,4} = {2,3}' },
      { rule: 'Union (∪): elements in EITHER or BOTH sets.', example: '{1,2,3} ∪ {2,3,4} = {1,2,3,4}' },
    ], quiz: { question: 'What is {2,4,6} ∩ {4,6,8}?', options: ['{2,4,6,8}', '{4,6}', '{4}', '{2,8}'], answer: 1, explanation: 'Intersection = elements in BOTH sets. 4 and 6 appear in both.' }},
    { title: 'Tallying & Graphs', rules: [
      { rule: 'A tally uses marks in groups of 5: IIII = 5.', example: 'Count 12 pupils: IIII IIII II' },
      { rule: 'A bar graph uses bars to show and compare data.', example: 'Taller bar = more items' },
      { rule: 'A pictograph uses pictures to represent numbers.', example: 'Each picture = 2 pupils' },
    ], quiz: { question: 'In a tally, how many does IIII IIII I represent?', options: ['9', '10', '11', '8'], answer: 2, explanation: 'IIII = 5, IIII = 5, I = 1. Total = 11.' }},
  ],
  'C3': [
    { title: 'BODMAS — Order of Operations', rules: [
      { rule: 'BODMAS: Brackets, Orders, Division, Multiplication, Addition, Subtraction.', formula: 'Always follow this order', example: '(2+3) × 4 = 5 × 4 = 20' },
      { rule: 'Always solve brackets FIRST.', example: '3 × (4 + 2) = 3 × 6 = 18 (not 14)' },
      { rule: 'Division and multiplication before addition and subtraction.', example: '10 + 6 ÷ 2 = 10 + 3 = 13 (not 8)' },
    ], quiz: { question: '(5 + 3) × 2 = ?', options: ['11', '16', '8', '13'], answer: 1, explanation: 'Brackets first: 5+3=8. Then 8×2=16.' }},
    { title: 'Fractions — Operations', rules: [
      { rule: 'Adding fractions with the same denominator: add numerators only.', formula: 'a/c + b/c = (a+b)/c', example: '2/5 + 1/5 = 3/5' },
      { rule: 'Subtracting fractions with the same denominator: subtract numerators only.', formula: 'a/c - b/c = (a-b)/c', example: '4/7 - 1/7 = 3/7' },
      { rule: 'To add fractions with different denominators, find the LCM first.', example: '1/2 + 1/4: LCM=4 → 2/4 + 1/4 = 3/4' },
      { rule: 'An improper fraction has numerator > denominator. Convert to mixed number.', example: '7/4 = 1 and 3/4 (1 whole + 3/4)' },
    ], quiz: { question: '3/8 + 2/8 = ?', options: ['5/16', '5/8', '6/8', '1/8'], answer: 1, explanation: 'Same denominator: add numerators only. 3+2=5. Answer: 5/8.' }},
    { title: 'Metric Measures', rules: [
      { rule: 'Length: 10 mm = 1 cm | 100 cm = 1 m | 1000 m = 1 km.', example: '250 cm = 2 m 50 cm = 2.5 m' },
      { rule: 'Mass/Weight: 1000 g = 1 kg.', example: '500 g + 750 g = 1250 g = 1 kg 250 g' },
      { rule: 'Capacity/Volume: 1000 ml = 1 litre (L).', example: '2.5 litres = 2500 ml' },
    ], quiz: { question: 'How many centimetres are in 3 metres?', options: ['30 cm', '300 cm', '3000 cm', '3 cm'], answer: 1, explanation: '1 metre = 100 cm. So 3 metres = 3 × 100 = 300 cm.' }},
    { title: 'Perimeter', rules: [
      { rule: 'Perimeter = total distance around a shape.', formula: 'P = sum of all sides', example: 'Triangle with sides 3cm, 4cm, 5cm: P = 3+4+5 = 12 cm' },
      { rule: 'Perimeter of rectangle.', formula: 'P = 2 × (length + width)', example: 'P = 2 × (8 + 5) = 2 × 13 = 26 cm' },
      { rule: 'Perimeter of square.', formula: 'P = 4 × side', example: 'P = 4 × 7 = 28 cm' },
    ], quiz: { question: 'Find the perimeter of a rectangle 10 cm long and 4 cm wide.', options: ['14 cm', '40 cm', '28 cm', '20 cm'], answer: 2, explanation: 'P = 2×(10+4) = 2×14 = 28 cm.' }},
    { title: 'Time & Calendar', rules: [
      { rule: '12-hour clock: am (midnight to noon) and pm (noon to midnight).', example: '8:00 am = morning | 3:30 pm = afternoon' },
      { rule: '24-hour clock: add 12 to pm times.', example: '3:30 pm = 15:30 | 8:00 am = 08:00' },
      { rule: 'Duration: subtract start time from end time.', example: 'Start 9:00, End 11:30 → Duration = 2 hours 30 minutes' },
    ], quiz: { question: 'A film starts at 14:00 and lasts 2 hours. What time does it end?', options: ['15:00', '16:00', '12:00', '17:00'], answer: 1, explanation: '14:00 + 2 hours = 16:00.' }},
  ],
  'C4': [
    { title: 'Large Numbers 0–5000', rules: [
      { rule: 'Place value: thousands, hundreds, tens, units.', formula: '3 475 = 3000 + 400 + 70 + 5', example: 'Write in words: three thousand four hundred and seventy-five' },
      { rule: 'Rounding to the nearest 10: look at the units digit.', example: '47 → 50 (units ≥5, round up) | 43 → 40 (units <5, round down)' },
      { rule: 'Rounding to the nearest 100: look at the tens digit.', example: '450 → 500 | 430 → 400' },
    ], quiz: { question: 'Round 2 467 to the nearest hundred.', options: ['2 400', '2 500', '2 000', '2 470'], answer: 1, explanation: 'Look at tens digit: 6 ≥ 5, so round up. 2 467 → 2 500.' }},
    { title: 'HCF & LCM', rules: [
      { rule: 'HCF (Highest Common Factor): the largest number that divides two or more numbers exactly.', formula: 'Find factors of each number, pick the largest common one.', example: 'HCF of 12 and 18: factors of 12={1,2,3,4,6,12}, of 18={1,2,3,6,9,18} → HCF=6' },
      { rule: 'LCM (Lowest Common Multiple): the smallest number that is a multiple of both numbers.', formula: 'List multiples of each, find the smallest common one.', example: 'LCM of 4 and 6: multiples of 4={4,8,12,16...}, of 6={6,12,18...} → LCM=12' },
    ], quiz: { question: 'What is the HCF of 8 and 12?', options: ['2', '4', '6', '24'], answer: 1, explanation: 'Factors of 8: 1,2,4,8. Factors of 12: 1,2,3,4,6,12. Highest common = 4.' }},
    { title: 'Area', rules: [
      { rule: 'Area = space inside a 2D shape. Measured in cm², m², km².', example: 'A field 10m × 5m has area = 50 m²' },
      { rule: 'Area of rectangle.', formula: 'A = length × width', example: 'A = 8 × 5 = 40 cm²' },
      { rule: 'Area of square.', formula: 'A = side × side = side²', example: 'A = 6² = 36 cm²' },
      { rule: 'Area of triangle.', formula: 'A = ½ × base × height', example: 'A = ½ × 10 × 6 = 30 cm²' },
    ], quiz: { question: 'Find the area of a rectangle 12 cm long and 5 cm wide.', options: ['34 cm²', '60 cm²', '17 cm²', '120 cm²'], answer: 1, explanation: 'Area = length × width = 12 × 5 = 60 cm².' }},
    { title: 'Money — FCFA C4', rules: [
      { rule: 'Notes: 500, 1000, 2000, 5000, 10000 FCFA.', example: '3 × 1000 FCFA = 3000 FCFA' },
      { rule: 'Profit = Selling price - Cost price. (When selling price > cost price)', formula: 'Profit = SP - CP', example: 'Bought for 2000 FCFA, sold for 2500 FCFA → Profit = 500 FCFA' },
      { rule: 'Loss = Cost price - Selling price. (When selling price < cost price)', formula: 'Loss = CP - SP', example: 'Bought for 3000 FCFA, sold for 2500 FCFA → Loss = 500 FCFA' },
    ], quiz: { question: 'A trader buys mangoes for 1500 FCFA and sells them for 2000 FCFA. What is the profit?', options: ['3500 FCFA', '500 FCFA', '1000 FCFA', '1500 FCFA'], answer: 1, explanation: 'Profit = SP - CP = 2000 - 1500 = 500 FCFA.' }},
    { title: 'Quadrilaterals & Angles', rules: [
      { rule: 'A quadrilateral is any shape with 4 sides and 4 angles.', example: 'square, rectangle, parallelogram, rhombus, trapezium, kite' },
      { rule: 'Parallelogram: opposite sides are equal and parallel.', example: 'Area = base × height' },
      { rule: 'An angle is measured in degrees (°). Right angle = 90°.', example: 'Acute: <90° | Right: 90° | Obtuse: 90°-180° | Straight: 180°' },
      { rule: 'Angles in a triangle always add up to 180°.', example: 'If two angles are 70° and 60°, the third = 180-70-60 = 50°' },
    ], quiz: { question: 'A triangle has angles of 60° and 80°. What is the third angle?', options: ['60°', '40°', '50°', '70°'], answer: 1, explanation: '60 + 80 = 140. Third angle = 180 - 140 = 40°.' }},
  ],
  'C5': [
    { title: 'Large Numbers 0–100 000', rules: [
      { rule: 'Place value: ten-thousands, thousands, hundreds, tens, units.', formula: '45 678 = 40000 + 5000 + 600 + 70 + 8', example: 'Forty-five thousand six hundred and seventy-eight' },
      { rule: 'Multiplying by 10, 100, 1000: add zeros.', example: '47 × 10 = 470 | 47 × 100 = 4700 | 47 × 1000 = 47000' },
      { rule: 'Dividing by 10, 100, 1000: remove zeros or move decimal.', example: '4700 ÷ 100 = 47 | 350 ÷ 10 = 35' },
    ], quiz: { question: '356 × 100 = ?', options: ['3560', '35600', '356000', '3506'], answer: 1, explanation: 'Multiply by 100: add two zeros. 356 × 100 = 35 600.' }},
    { title: 'Decimals', rules: [
      { rule: 'A decimal point separates whole numbers from fractional parts.', example: '3.75 = 3 + 7/10 + 5/100 = 3 and 75 hundredths' },
      { rule: 'Adding decimals: line up the decimal points.', example: '4.5 + 2.36 = 6.86' },
      { rule: 'Multiplying a decimal by 10: move decimal point one place right.', example: '3.75 × 10 = 37.5' },
      { rule: 'Rounding decimals: look at the next digit.', example: '3.467 rounded to 2 d.p. = 3.47 (third decimal is 7 ≥ 5)' },
    ], quiz: { question: 'What is 4.6 + 3.75?', options: ['7.81', '8.35', '8.31', '7.35'], answer: 1, explanation: '4.60 + 3.75 = 8.35. Line up decimal points!' }},
    { title: 'Ratio & Proportion', rules: [
      { rule: 'A ratio compares two quantities. Written as a:b or a/b.', example: 'If there are 3 girls and 5 boys: ratio girls:boys = 3:5' },
      { rule: 'Direct proportion: as one quantity increases, the other increases at the same rate.', formula: 'a/b = c/d (cross multiply)', example: 'If 2 kg costs 400 FCFA, then 5 kg costs 5 × 400/2 = 1000 FCFA' },
      { rule: 'Simplify ratios by dividing both parts by the HCF.', example: '8:12 = 2:3 (divide by HCF=4)' },
    ], quiz: { question: 'If 3 metres of cloth costs 1500 FCFA, how much do 5 metres cost?', options: ['2000 FCFA', '2500 FCFA', '3000 FCFA', '1800 FCFA'], answer: 1, explanation: '1 metre = 1500÷3 = 500 FCFA. 5 metres = 5 × 500 = 2500 FCFA.' }},
    { title: 'Speed, Distance & Time', rules: [
      { rule: 'The SDT triangle: cover what you want to find.', formula: 'Speed = Distance ÷ Time | Distance = Speed × Time | Time = Distance ÷ Speed', example: 'Speed = 120 km ÷ 2 h = 60 km/h' },
      { rule: 'Units must be consistent: km and hours → km/h.', example: 'Distance = 80 km/h × 3 h = 240 km' },
    ], quiz: { question: 'A car travels 150 km in 3 hours. What is its speed?', options: ['50 km/h', '45 km/h', '60 km/h', '450 km/h'], answer: 0, explanation: 'Speed = Distance ÷ Time = 150 ÷ 3 = 50 km/h.' }},
    { title: 'Angles & Circles', rules: [
      { rule: 'Types of angles: acute (<90°), right (=90°), obtuse (90°-180°), straight (180°), reflex (>180°).', example: '45° acute | 120° obtuse | 200° reflex' },
      { rule: 'Angles on a straight line add up to 180°.', example: 'If one angle is 65°, the other = 180-65 = 115°' },
      { rule: 'Angles around a point add up to 360°.', example: '120° + 90° + x = 360° → x = 150°' },
      { rule: 'Circle: radius = half the diameter. Circumference = 2πr.', formula: 'C = 2 × π × r (π ≈ 3.14)', example: 'r = 7 cm: C = 2 × 3.14 × 7 = 43.96 cm' },
    ], quiz: { question: 'Two angles on a straight line are 70° and x°. Find x.', options: ['70°', '110°', '120°', '180°'], answer: 1, explanation: 'Angles on a straight line = 180°. x = 180 - 70 = 110°.' }},
  ],
  'C6': [
    { title: 'Numbers 0–1 000 000', rules: [
      { rule: 'Place value up to millions.', formula: '1 234 567 = 1 million + 234 thousand + 567', example: 'One million two hundred and thirty-four thousand five hundred and sixty-seven' },
      { rule: 'Standard form (scientific notation): a × 10ⁿ where 1 ≤ a < 10.', example: '45 000 = 4.5 × 10⁴ | 3 200 000 = 3.2 × 10⁶' },
      { rule: 'Prime numbers: divisible only by 1 and themselves.', example: '2, 3, 5, 7, 11, 13, 17, 19, 23, 29...' },
      { rule: 'Prime factorisation: express a number as a product of primes.', example: '60 = 2 × 2 × 3 × 5 = 2² × 3 × 5' },
    ], quiz: { question: 'Which of these is a prime number?', options: ['9', '15', '17', '21'], answer: 2, explanation: '17 is divisible only by 1 and 17. 9=3×3, 15=3×5, 21=3×7.' }},
    { title: 'Percentages', rules: [
      { rule: 'Percent (%) means "out of 100".', formula: 'a% = a/100', example: '25% = 25/100 = 1/4' },
      { rule: 'Finding a percentage of a quantity.', formula: '% of quantity = (% ÷ 100) × quantity', example: '20% of 500 FCFA = (20÷100) × 500 = 100 FCFA' },
      { rule: 'Expressing one number as a percentage of another.', formula: '(part ÷ whole) × 100', example: '15 out of 60 = (15÷60) × 100 = 25%' },
      { rule: 'Percentage increase/decrease.', formula: '(change ÷ original) × 100', example: 'Price rises from 2000 to 2500: increase = (500÷2000) × 100 = 25%' },
    ], quiz: { question: 'What is 15% of 200 FCFA?', options: ['15 FCFA', '30 FCFA', '150 FCFA', '20 FCFA'], answer: 1, explanation: '15% of 200 = (15÷100) × 200 = 0.15 × 200 = 30 FCFA.' }},
    { title: 'Simple Interest', rules: [
      { rule: 'Simple Interest (SI): interest calculated on the original principal only.', formula: 'SI = (P × R × T) / 100', example: 'P=5000, R=10%, T=2 years: SI = (5000×10×2)/100 = 1000 FCFA' },
      { rule: 'P = Principal (money saved/borrowed) | R = Rate (%) | T = Time (years).', example: 'P=10000, R=5%, T=3y: SI=(10000×5×3)/100=1500 FCFA' },
      { rule: 'Amount (A) = Principal + Interest.', formula: 'A = P + SI', example: 'A = 5000 + 1000 = 6000 FCFA' },
    ], quiz: { question: 'Find the simple interest on 8000 FCFA at 5% per year for 3 years.', options: ['400 FCFA', '1200 FCFA', '1500 FCFA', '2400 FCFA'], answer: 1, explanation: 'SI = (8000 × 5 × 3) / 100 = 120000 / 100 = 1200 FCFA.' }},
    { title: '3D Shapes', rules: [
      { rule: 'A cube has 6 square faces, 12 edges, 8 vertices. All sides equal.', formula: 'Volume = side³ | Surface area = 6 × side²', example: 'Side=3cm: V=27cm³, SA=54cm²' },
      { rule: 'A cuboid (rectangular prism) has 6 rectangular faces.', formula: 'V = l × w × h', example: 'V = 5 × 4 × 3 = 60 cm³' },
      { rule: 'A cylinder has 2 circular faces and 1 curved surface.', formula: 'V = π × r² × h', example: 'r=7, h=10: V = 3.14 × 49 × 10 = 1538.6 cm³' },
      { rule: 'A cone has a circular base and a curved surface meeting at a point (apex).', example: 'Examples: ice cream cone, roof of a hut' },
    ], quiz: { question: 'Find the volume of a cuboid 6 cm × 4 cm × 3 cm.', options: ['13 cm³', '72 cm³', '48 cm³', '24 cm³'], answer: 1, explanation: 'V = l × w × h = 6 × 4 × 3 = 72 cm³.' }},
    { title: 'Statistics & Graphs', rules: [
      { rule: 'Mean (average) = sum of values ÷ number of values.', formula: 'Mean = Σx / n', example: '4, 6, 8, 10, 12: Mean = 40 ÷ 5 = 8' },
      { rule: 'Median = middle value when data is arranged in order.', example: '3, 5, 7, 9, 11 → Median = 7 (3rd value)' },
      { rule: 'Mode = the value that appears most often.', example: '2, 3, 3, 5, 7, 3, 9 → Mode = 3' },
      { rule: 'Range = highest value - lowest value.', example: 'Data: 4, 9, 2, 7, 1 → Range = 9 - 1 = 8' },
    ], quiz: { question: 'Find the mean of: 10, 14, 8, 12, 6.', options: ['8', '10', '12', '50'], answer: 1, explanation: 'Sum = 10+14+8+12+6 = 50. Mean = 50 ÷ 5 = 10.' }},
  ],
}

// ── Module Dictionnaire ───────────────────────────────────────────────────────
function DictionaryModule({ child }: { child: Child }) {
  const [query, setQuery] = useState('')
  const [results, setResults] = useState<any[]>([])
  const [loading, setLoading] = useState(false)
  const [searched, setSearched] = useState(false)
  const inputRef = useRef<HTMLInputElement>(null)

  const search = async () => {
    if (!query.trim()) return
    setLoading(true); setSearched(true)
    try {
      const res = await fetch(`/api/revision/dictionary?q=${encodeURIComponent(query)}&level_id=${child.level_id || 0}`)
      const data = await res.json()
      setResults(Array.isArray(data) ? data : [])
    } catch { setResults([]) }
    setLoading(false)
  }

  return (
    <div>
      <div style={{ fontSize: 13, color: P.soft, marginBottom: 14, lineHeight: 1.6 }}>
        Search for any English word from your MINEDUB curriculum.
      </div>

      {/* Search bar */}
      <div style={{ display: 'flex', gap: 8, marginBottom: 16 }}>
        <input
          ref={inputRef}
          value={query}
          onChange={e => setQuery(e.target.value)}
          onKeyDown={e => e.key === 'Enter' && search()}
          placeholder="Search a word..."
          style={{ flex: 1, padding: '10px 14px', borderRadius: 12, border: `1.5px solid ${P.border}`, fontSize: 15, fontFamily: 'Nunito, sans-serif', color: P.dark, background: 'white', outline: 'none' }}
        />
        <button onClick={search} disabled={!query.trim() || loading}
          style={{ padding: '10px 18px', borderRadius: 12, border: 'none', background: P.green, color: 'white', fontWeight: 900, fontSize: 14, cursor: 'pointer', fontFamily: 'Nunito, sans-serif' }}>
          {loading ? '...' : '\uD83D\uDD0D'}
        </button>
      </div>

      {/* Results */}
      {loading && <div style={{ textAlign: 'center', color: P.soft, padding: 20 }}>Searching...</div>}

      {!loading && searched && results.length === 0 && (
        <div style={{ textAlign: 'center', padding: 24, color: P.soft }}>
          <div style={{ fontSize: 32, marginBottom: 8 }}>{'\uD83D\uDD0D'}</div>
          <div style={{ fontWeight: 700 }}>No results for "{query}"</div>
          <div style={{ fontSize: 12, marginTop: 4 }}>Try another word or check the spelling.</div>
        </div>
      )}

      {results.map((r, i) => (
        <div key={i} style={{ background: 'white', borderRadius: 16, padding: '14px 16px', marginBottom: 10, border: `1.5px solid ${P.border}` }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 6 }}>
            <span style={{ fontSize: 22, fontWeight: 900, color: P.green }}>{r.word}</span>
            {r.part_of_speech && (
              <span style={{ fontSize: 11, fontWeight: 700, color: 'white', background: P.accent, borderRadius: 6, padding: '2px 8px' }}>{r.part_of_speech}</span>
            )}
            {r.level_name && (
              <span style={{ fontSize: 11, fontWeight: 700, color: P.soft, background: P.border, borderRadius: 6, padding: '2px 8px' }}>{r.level_name}</span>
            )}
          </div>
          <div style={{ fontSize: 14, color: P.dark, marginBottom: 4, lineHeight: 1.6 }}>{r.definition}</div>
          {r.example && (
            <div style={{ fontSize: 13, color: P.soft, fontStyle: 'italic', borderLeft: `3px solid ${P.green}`, paddingLeft: 10 }}>
              e.g. "{r.example}"
            </div>
          )}
          {r.french && (
            <div style={{ fontSize: 12, color: P.accent, fontWeight: 700, marginTop: 6 }}>
              {'\uD83C\uDDEB\uD83C\uDDF7'} {r.french}
            </div>
          )}
        </div>
      ))}
    </div>
  )
}

// ── Module Grammaire ──────────────────────────────────────────────────────────
function GrammarModule({ child }: { child: Child }) {
  const defaultLvl = (() => {
    const n = child.level_name || 'C1'
    const m = n.match(/C(\d)/)
    return m ? `C${m[1]}` : 'C1'
  })()
  const [selLevel, setSelLevel] = useState(defaultLvl)
  const [openIdx, setOpenIdx] = useState<number>(0)
  const rules = GRAMMAR_RULES[selLevel] || GRAMMAR_RULES['C1']
  const levels = ['C1', 'C2', 'C3', 'C4', 'C5', 'C6']

  return (
    <div>
      {/* Sélecteur de niveau */}
      <div style={{ marginBottom: 14 }}>
        <div style={{ fontSize: 11, fontWeight: 900, color: P.soft, letterSpacing: 1, textTransform: 'uppercase' as const, marginBottom: 8 }}>Level</div>
        <div style={{ display: 'flex', gap: 6 }}>
          {levels.map(l => (
            <button key={l} onClick={() => { setSelLevel(l); setOpenIdx(0) }}
              style={{ flex: 1, padding: '7px 0', borderRadius: 10, border: `1.5px solid ${selLevel === l ? P.green : P.border}`, background: selLevel === l ? P.green : 'white', color: selLevel === l ? 'white' : P.dark, fontWeight: 800, fontSize: 12, cursor: 'pointer', fontFamily: 'Nunito, sans-serif' }}>
              {l}
            </button>
          ))}
        </div>
        {selLevel === defaultLvl && (
          <div style={{ fontSize: 11, color: P.accent, fontWeight: 700, marginTop: 6 }}>Your level</div>
        )}
      </div>

      {/* Règles en accordéon */}
      {rules.map((section, i) => (
        <div key={i} style={{ marginBottom: 10 }}>
          <button
            onClick={() => setOpenIdx(openIdx === i ? -1 : i)}
            style={{ width: '100%', padding: '12px 16px', borderRadius: openIdx === i ? '14px 14px 0 0' : '14px', border: `1.5px solid ${P.border}`, background: openIdx === i ? P.green : 'white', color: openIdx === i ? 'white' : P.dark, fontWeight: 800, fontSize: 14, cursor: 'pointer', display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontFamily: 'Nunito, sans-serif' }}>
            <span>{section.title}</span>
            <span>{openIdx === i ? '\u25B2' : '\u25BC'}</span>
          </button>
          {openIdx === i && (
            <div style={{ background: 'white', borderRadius: '0 0 14px 14px', border: `1.5px solid ${P.border}`, borderTop: 'none', padding: 14 }}>
              {section.rules.map((r, j) => (
                <div key={j} style={{ marginBottom: 12, paddingBottom: 12, borderBottom: j < section.rules.length - 1 ? `1px solid ${P.border}` : 'none' }}>
                  <div style={{ fontSize: 13, fontWeight: 700, color: P.dark, marginBottom: 4, lineHeight: 1.6 }}>{r.rule}</div>
                  <div style={{ fontSize: 12, color: P.accent, fontStyle: 'italic', background: '#FEF3C7', borderRadius: 8, padding: '6px 10px' }}>
                    e.g. {r.example}
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      ))}
    </div>
  )
}

// ── Module Conjugaison ────────────────────────────────────────────────────────
function ConjugationModule() {
  const [lang, setLang] = useState<Lang>('en')
  const [selVerb, setSelVerb] = useState(0)
  const [selTense, setSelTense] = useState('Simple Present')

  const verbs = lang === 'en' ? VERBS_EN : VERBS_FR
  const verb = verbs[Math.min(selVerb, verbs.length - 1)] as any
  const tenses = Object.keys(verb.conjugations)
  const subjects = lang === 'en' ? verb.subjects_en : verb.subjects_fr

  // Reset tense when switching language
  const switchLang = (l: Lang) => {
    setLang(l)
    setSelVerb(0)
    setSelTense(l === 'en' ? 'Simple Present' : 'Présent')
  }

  const forms: string[] = (verb.conjugations as any)[selTense] || []

  return (
    <div>
      {/* Language toggle */}
      <div style={{ display: 'flex', gap: 8, marginBottom: 16 }}>
        <button onClick={() => switchLang('en')}
          style={{ flex: 1, padding: '10px 0', borderRadius: 12, border: `2px solid ${lang === 'en' ? '#3B82F6' : P.border}`, background: lang === 'en' ? '#EFF6FF' : 'white', color: lang === 'en' ? '#1D4ED8' : P.soft, fontWeight: 900, fontSize: 14, cursor: 'pointer', fontFamily: 'Nunito, sans-serif' }}>
          English
        </button>
        <button onClick={() => switchLang('fr')}
          style={{ flex: 1, padding: '10px 0', borderRadius: 12, border: `2px solid ${lang === 'fr' ? '#EC4899' : P.border}`, background: lang === 'fr' ? '#FDF2F8' : 'white', color: lang === 'fr' ? '#BE185D' : P.soft, fontWeight: 900, fontSize: 14, cursor: 'pointer', fontFamily: 'Nunito, sans-serif' }}>
          Francais
        </button>
      </div>

      {/* Verb selector */}
      <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6, marginBottom: 10 }}>
        {verbs.map((v: any, i: number) => (
          <button key={i} onClick={() => setSelVerb(i)}
            style={{ padding: '5px 12px', borderRadius: 20, border: `1.5px solid ${P.border}`, background: selVerb === i ? P.green : 'white', color: selVerb === i ? 'white' : P.dark, fontWeight: 800, fontSize: 12, cursor: 'pointer', fontFamily: 'Nunito, sans-serif' }}>
            {v.short}
          </button>
        ))}
      </div>

      {/* Meaning */}
      <div style={{ fontSize: 12, color: P.accent, fontWeight: 700, marginBottom: 10, padding: '6px 12px', background: '#FEF3C7', borderRadius: 8, display: 'inline-block' }}>
        {verb.short} = {verb.meanings}
      </div>

      {/* Tense selector */}
      <div style={{ display: 'flex', gap: 6, marginBottom: 14, flexWrap: 'wrap' }}>
        {tenses.map((t: string) => (
          <button key={t} onClick={() => setSelTense(t)}
            style={{ padding: '5px 12px', borderRadius: 20, border: `1.5px solid ${selTense === t ? (lang === 'en' ? '#3B82F6' : '#EC4899') : P.border}`, background: selTense === t ? (lang === 'en' ? '#EFF6FF' : '#FDF2F8') : 'white', color: selTense === t ? (lang === 'en' ? '#1D4ED8' : '#BE185D') : P.dark, fontWeight: 700, fontSize: 11, cursor: 'pointer', fontFamily: 'Nunito, sans-serif' }}>
            {t}
          </button>
        ))}
      </div>

      {/* Conjugation table */}
      <div style={{ background: 'white', borderRadius: 16, border: `1.5px solid ${P.border}`, overflow: 'hidden' }}>
        {forms.map((form: string, i: number) => (
          <div key={i} style={{ display: 'flex', alignItems: 'center', padding: '10px 16px', borderBottom: i < 5 ? `1px solid ${P.border}` : 'none', background: i % 2 === 0 ? 'white' : '#FAFAF6' }}>
            <span style={{ width: 120, fontSize: 12, color: P.soft, fontWeight: 700 }}>
              {subjects[i]}
            </span>
            <span style={{ fontSize: 15, fontWeight: 900, color: lang === 'en' ? '#1D4ED8' : P.green }}>
              {lang === 'en' ? subjects[i] + ' ' : ''}{form}
            </span>
          </div>
        ))}
      </div>

      {/* Remarque temps */}
      <div style={{ marginTop: 10, fontSize: 11, color: P.soft, textAlign: 'center' }}>
        {lang === 'en' && selTense === 'Simple Present' && 'Use for habits, facts, routines.'}
        {lang === 'en' && selTense === 'Simple Past' && 'Use for completed actions in the past.'}
        {lang === 'en' && selTense === 'Simple Future' && 'Use will + base verb for future actions.'}
        {lang === 'en' && selTense === 'Pres. Continuous' && 'Use for actions happening right now.'}
        {lang === 'en' && selTense === 'Pres. Perfect' && 'Use have/has + past participle. Past with present relevance.'}
        {lang === 'en' && selTense === 'Past Perfect' && 'Use had + past participle. Action before another past action.'}
        {lang === 'fr' && selTense === 'Présent' && 'Action habituelle ou en cours.'}
        {lang === 'fr' && selTense === 'Imparfait' && 'Action repetee ou description dans le passe.'}
        {lang === 'fr' && selTense === 'Passé composé' && 'Action completee dans le passe.'}
        {lang === 'fr' && selTense === 'Plus-que-parfait' && 'Action anterieure a une autre action passee.'}
        {lang === 'fr' && selTense === 'Futur simple' && 'Action qui aura lieu dans le futur.'}
        {lang === 'fr' && selTense === 'Futur proche' && 'Action qui va se passer tres bientot (aller + infinitif).'}
      </div>
    </div>
  )
}

// ── Module Maths ──────────────────────────────────────────────────────────────
function MathsModule({ child }: { child: Child }) {
  const defaultLvl = (() => {
    const n = child.level_name || 'C1'
    const m = n.match(/C(\d)/)
    return m ? `C${m[1]}` : 'C1'
  })()
  const [selLevel, setSelLevel] = useState(defaultLvl)
  const [openIdx, setOpenIdx] = useState<number>(0)
  const [showTable, setShowTable] = useState(false)
  const [tableN, setTableN] = useState(2)
  // Quiz state per section index
  const [quizState, setQuizState] = useState<Record<number, { chosen: number | null; done: boolean }>>({})

  const sections = MATHS_BY_LEVEL[selLevel] || MATHS_BY_LEVEL['C1']
  const levels = ['C1', 'C2', 'C3', 'C4', 'C5', 'C6']

  const answerQuiz = (sIdx: number, chosen: number) => {
    setQuizState(s => ({ ...s, [sIdx]: { chosen, done: true } }))
  }

  return (
    <div>
      {/* Level selector */}
      <div style={{ marginBottom: 14 }}>
        <div style={{ fontSize: 11, fontWeight: 900, color: P.soft, letterSpacing: 1, textTransform: 'uppercase', marginBottom: 8 }}>Level</div>
        <div style={{ display: 'flex', gap: 6 }}>
          {levels.map(l => (
            <button key={l} onClick={() => { setSelLevel(l); setOpenIdx(0); setQuizState({}) }}
              style={{ flex: 1, padding: '7px 0', borderRadius: 10, border: `1.5px solid ${selLevel === l ? '#10B981' : P.border}`, background: selLevel === l ? '#10B981' : 'white', color: selLevel === l ? 'white' : P.dark, fontWeight: 800, fontSize: 12, cursor: 'pointer', fontFamily: 'Nunito, sans-serif' }}>
              {l}
            </button>
          ))}
        </div>
        {selLevel === defaultLvl && (
          <div style={{ fontSize: 11, color: '#10B981', fontWeight: 700, marginTop: 6 }}>Your level</div>
        )}
      </div>

      {/* Multiplication tables button */}
      <button onClick={() => setShowTable(!showTable)}
        style={{ width: '100%', padding: '10px 16px', borderRadius: 14, border: `1.5px solid ${P.border}`, background: showTable ? '#10B981' : 'white', color: showTable ? 'white' : P.dark, fontWeight: 800, fontSize: 13, cursor: 'pointer', display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontFamily: 'Nunito, sans-serif', marginBottom: 10 }}>
        <span>Times Tables (2–12)</span>
        <span>{showTable ? '\u25B2' : '\u25BC'}</span>
      </button>
      {showTable && (
        <div style={{ background: 'white', borderRadius: '0 0 14px 14px', border: `1.5px solid ${P.border}`, borderTop: 'none', padding: 14, marginBottom: 10 }}>
          <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6, marginBottom: 12 }}>
            {[2,3,4,5,6,7,8,9,10,11,12].map(n => (
              <button key={n} onClick={() => setTableN(n)}
                style={{ width: 38, height: 38, borderRadius: 10, border: `1.5px solid ${P.border}`, background: tableN === n ? '#10B981' : 'white', color: tableN === n ? 'white' : P.dark, fontWeight: 900, fontSize: 14, cursor: 'pointer', fontFamily: 'Nunito, sans-serif' }}>
                {n}
              </button>
            ))}
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 4 }}>
            {Array.from({ length: 12 }, (_, i) => i + 1).map(i => (
              <div key={i} style={{ display: 'flex', alignItems: 'center', padding: '7px 12px', borderRadius: 8, background: i % 2 === 0 ? '#F0F9F4' : 'white', border: '1px solid #E8F5EC' }}>
                <span style={{ flex: 1, fontSize: 13, fontWeight: 700, color: P.soft }}>{tableN} × {i} =</span>
                <span style={{ fontSize: 15, fontWeight: 900, color: '#10B981' }}>{tableN * i}</span>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Sections with rules + quiz */}
      {sections.map((section, sIdx) => (
        <div key={sIdx} style={{ marginBottom: 10 }}>
          <button
            onClick={() => { setOpenIdx(openIdx === sIdx ? -1 : sIdx) }}
            style={{ width: '100%', padding: '12px 16px', borderRadius: openIdx === sIdx ? '14px 14px 0 0' : 14, border: `1.5px solid ${P.border}`, background: openIdx === sIdx ? '#10B981' : 'white', color: openIdx === sIdx ? 'white' : P.dark, fontWeight: 800, fontSize: 13, cursor: 'pointer', display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontFamily: 'Nunito, sans-serif' }}>
            <span>{section.title}</span>
            <span>{openIdx === sIdx ? '\u25B2' : '\u25BC'}</span>
          </button>
          {openIdx === sIdx && (
            <div style={{ background: 'white', borderRadius: '0 0 14px 14px', border: `1.5px solid ${P.border}`, borderTop: 'none', padding: 14 }}>
              {/* Rules */}
              {section.rules.map((r, j) => (
                <div key={j} style={{ marginBottom: 12, paddingBottom: 12, borderBottom: `1px solid ${P.border}` }}>
                  <div style={{ fontSize: 13, fontWeight: 700, color: P.dark, marginBottom: 4, lineHeight: 1.6 }}>{r.rule}</div>
                  {r.formula && (
                    <div style={{ fontSize: 13, fontWeight: 900, color: '#10B981', background: '#F0F9F4', borderRadius: 8, padding: '5px 10px', marginBottom: 4, fontFamily: 'monospace' }}>
                      {r.formula}
                    </div>
                  )}
                  <div style={{ fontSize: 12, color: P.accent, fontStyle: 'italic', background: '#FEF3C7', borderRadius: 8, padding: '5px 10px' }}>
                    e.g. {r.example}
                  </div>
                </div>
              ))}

              {/* Mini Quiz */}
              {section.quiz && (
                <div style={{ background: '#F0F9F4', borderRadius: 12, padding: 14, marginTop: 4, border: '1.5px solid #D1FAE5' }}>
                  <div style={{ fontSize: 12, fontWeight: 900, color: '#10B981', marginBottom: 8, textTransform: 'uppercase', letterSpacing: 1 }}>Quick Quiz</div>
                  <div style={{ fontSize: 14, fontWeight: 800, color: P.dark, marginBottom: 10 }}>{section.quiz.question}</div>
                  <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 6 }}>
                    {section.quiz.options.map((opt, oi) => {
                      const qs = quizState[sIdx]
                      const isChosen = qs?.chosen === oi
                      const isCorrect = oi === section.quiz!.answer
                      let bg = 'white', border = P.border, color = P.dark
                      if (qs?.done) {
                        if (isCorrect) { bg = '#D1FAE5'; border = '#10B981'; color = '#065F46' }
                        else if (isChosen) { bg = '#FEE2E2'; border = '#EF4444'; color = '#991B1B' }
                      } else if (isChosen) { bg = '#EFF6FF'; border = '#3B82F6'; color = '#1D4ED8' }
                      return (
                        <button key={oi} onClick={() => !qs?.done && answerQuiz(sIdx, oi)}
                          style={{ padding: '8px 10px', borderRadius: 10, border: `2px solid ${border}`, background: bg, color, fontWeight: 700, fontSize: 13, cursor: qs?.done ? 'default' : 'pointer', fontFamily: 'Nunito, sans-serif', textAlign: 'left' }}>
                          {opt}
                        </button>
                      )
                    })}
                  </div>
                  {quizState[sIdx]?.done && (
                    <div style={{ marginTop: 10, padding: '8px 12px', borderRadius: 8, background: quizState[sIdx].chosen === section.quiz.answer ? '#D1FAE5' : '#FEF3C7', fontSize: 12, fontWeight: 700, color: quizState[sIdx].chosen === section.quiz.answer ? '#065F46' : '#92400E' }}>
                      {quizState[sIdx].chosen === section.quiz.answer ? 'Correct! ' : 'Not quite. '}
                      {section.quiz.explanation}
                    </div>
                  )}
                  {quizState[sIdx]?.done && (
                    <button onClick={() => setQuizState(s => ({ ...s, [sIdx]: { chosen: null, done: false } }))}
                      style={{ marginTop: 8, padding: '6px 14px', borderRadius: 8, border: '1.5px solid #10B981', background: 'white', color: '#10B981', fontWeight: 700, fontSize: 12, cursor: 'pointer', fontFamily: 'Nunito, sans-serif' }}>
                      Try again
                    </button>
                  )}
                </div>
              )}
            </div>
          )}
        </div>
      ))}
    </div>
  )
}

// ── Page principale ───────────────────────────────────────────────────────────
export default function RevisionPage({ child, onBack }: Props) {
  // Le dictionnaire depend de /api/revision/dictionary, servi uniquement par
  // FastAPI. Sur le web (backend Laravel) cet endpoint renvoie 404 -> on masque
  // l'onglet hors application native (Capacitor). Les autres modules restent.
  const isNativeApp = typeof (window as any).Capacitor !== 'undefined'
    && (window as any).Capacitor.isNativePlatform()

  const MODULES: { id: Module; icon: string; label: string; color: string }[] = [
    ...(isNativeApp ? [{ id: 'dictionary' as Module, icon: '\uD83D\uDCD6', label: 'Dictionary', color: '#3B82F6' }] : []),
    { id: 'grammar',      icon: '\uD83D\uDD24', label: 'Grammar',      color: '#8B5CF6' },
    { id: 'conjugation',  icon: '\uD83C\uDDEB\uD83C\uDDF7', label: 'Conjugation', color: '#EC4899' },
    { id: 'maths',        icon: '\uD83D\uDCCA', label: 'Maths',        color: '#10B981' },
  ]

  const [module, setModule] = useState<Module>(MODULES[0].id)

  const activeModule = MODULES.find(m => m.id === module)!

  return (
    <div style={{ background: P.bg, minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', paddingBottom: 20 }}>

      {/* Header */}
      <div style={{ background: P.green, padding: '12px 16px', display: 'flex', alignItems: 'center', gap: 12 }}>
        <button onClick={onBack}
          style={{ background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: 10, padding: '6px 14px', fontSize: 13, fontWeight: 800, color: 'white', cursor: 'pointer' }}>
          Back
        </button>
        <div style={{ flex: 1 }}>
          <div style={{ fontSize: 16, fontWeight: 900, color: 'white' }}>{'\uD83D\uDCDA'} Revision</div>
          <div style={{ fontSize: 11, color: 'rgba(255,255,255,0.7)' }}>{MODULES.map(m => m.label).join(' · ')}</div>
        </div>
      </div>

      {/* Module tabs */}
      <div style={{ display: 'flex', gap: 6, padding: '12px 14px', background: 'white', borderBottom: `1.5px solid ${P.border}`, overflowX: 'auto' }}>
        {MODULES.map(m => (
          <button key={m.id} onClick={() => setModule(m.id)}
            style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 3, padding: '8px 14px', borderRadius: 14, border: `1.5px solid ${module === m.id ? m.color : P.border}`, background: module === m.id ? m.color + '15' : 'white', cursor: 'pointer', flexShrink: 0, fontFamily: 'Nunito, sans-serif' }}>
            <span style={{ fontSize: 20 }}>{m.icon}</span>
            <span style={{ fontSize: 11, fontWeight: 800, color: module === m.id ? m.color : P.soft }}>{m.label}</span>
          </button>
        ))}
      </div>

      {/* Module content */}
      <div style={{ padding: '16px 14px' }}>
        {module === 'dictionary'  && <DictionaryModule child={child} />}
        {module === 'grammar'     && <GrammarModule child={child} />}
        {module === 'conjugation' && <ConjugationModule />}
        {module === 'maths'       && <MathsModule child={child} />}
      </div>
    </div>
  )
}
