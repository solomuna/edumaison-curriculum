# seeder_handwriting.py -- Handwriting C1+C2
# Lessons 210-214

import json, psycopg2
DB = "dbname=edumaison user=postgres host=localhost"

exercises = [
    # ── Lesson 210 - Capital Letters A-M ─────────────────────────────────────
    {
        "lesson_id": 210, "category": "handwriting", "difficulty": "easy",
        "title": "Trace capital A",
        "instructions": "Trace the letter and write it on your own.",
        "content": {"type": "handwriting", "letter": "A", "word": "Apple", "hint": "Start at the top, go down left, go down right, cross in the middle."}
    },
    {
        "lesson_id": 210, "category": "handwriting", "difficulty": "easy",
        "title": "Trace capital B",
        "instructions": "Trace the letter and write it on your own.",
        "content": {"type": "handwriting", "letter": "B", "word": "Ball", "hint": "Straight line down, two bumps on the right."}
    },
    {
        "lesson_id": 210, "category": "handwriting", "difficulty": "easy",
        "title": "Trace capital C",
        "instructions": "Trace the letter and write it on your own.",
        "content": {"type": "handwriting", "letter": "C", "word": "Cat", "hint": "Curve from top to bottom, open on the right."}
    },
    {
        "lesson_id": 210, "category": "handwriting", "difficulty": "easy",
        "title": "Trace capital D",
        "instructions": "Trace the letter and write it on your own.",
        "content": {"type": "handwriting", "letter": "D", "word": "Dog", "hint": "Straight line down, one big bump on the right."}
    },
    {
        "lesson_id": 210, "category": "handwriting", "difficulty": "easy",
        "title": "Trace capital E",
        "instructions": "Trace the letter and write it on your own.",
        "content": {"type": "handwriting", "letter": "E", "word": "Egg", "hint": "Straight line down, three lines going right."}
    },
    {
        "lesson_id": 210, "category": "handwriting", "difficulty": "easy",
        "title": "Trace capital F",
        "instructions": "Trace the letter and write it on your own.",
        "content": {"type": "handwriting", "letter": "F", "word": "Fish", "hint": "Straight line down, two lines going right at top and middle."}
    },
    {
        "lesson_id": 210, "category": "handwriting", "difficulty": "easy",
        "title": "Which letter starts Apple?",
        "instructions": "Choose the correct capital letter.",
        "content": {"type": "mcq", "question": "Which capital letter starts the word APPLE?", "options": ["B","A","P","E"], "answer": "A"}
    },
    {
        "lesson_id": 210, "category": "handwriting", "difficulty": "easy",
        "title": "Match letters to words",
        "instructions": "Match each capital letter to its word.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "A", "right": "Apple"},
            {"left": "B", "right": "Ball"},
            {"left": "C", "right": "Cat"},
            {"left": "D", "right": "Dog"}
        ]}
    },
    # ── Lesson 211 - Capital Letters N-Z ─────────────────────────────────────
    {
        "lesson_id": 211, "category": "handwriting", "difficulty": "easy",
        "title": "Trace capital N",
        "instructions": "Trace the letter and write it on your own.",
        "content": {"type": "handwriting", "letter": "N", "word": "Nest", "hint": "Two straight lines down, diagonal line connecting them."}
    },
    {
        "lesson_id": 211, "category": "handwriting", "difficulty": "easy",
        "title": "Trace capital S",
        "instructions": "Trace the letter and write it on your own.",
        "content": {"type": "handwriting", "letter": "S", "word": "Sun", "hint": "Curve right at top, curve left at bottom."}
    },
    {
        "lesson_id": 211, "category": "handwriting", "difficulty": "easy",
        "title": "Trace capital T",
        "instructions": "Trace the letter and write it on your own.",
        "content": {"type": "handwriting", "letter": "T", "word": "Tree", "hint": "Straight line down, cross at the top."}
    },
    {
        "lesson_id": 211, "category": "handwriting", "difficulty": "easy",
        "title": "Match N-Z letters",
        "instructions": "Match each capital letter to its word.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "N", "right": "Nest"},
            {"left": "S", "right": "Sun"},
            {"left": "T", "right": "Tree"},
            {"left": "Z", "right": "Zebra"}
        ]}
    },
    {
        "lesson_id": 211, "category": "handwriting", "difficulty": "easy",
        "title": "Capital letter order N-Z",
        "instructions": "Put these capital letters in alphabetical order.",
        "content": {"type": "sentence_order", "words": ["T","R","N","Z","S"], "answer": ["N","R","S","T","Z"]}
    },
    {
        "lesson_id": 211, "category": "handwriting", "difficulty": "easy",
        "title": "Which letter starts Sun?",
        "instructions": "Choose the correct capital letter.",
        "content": {"type": "mcq", "question": "Which capital letter starts the word SUN?", "options": ["T","N","S","Z"], "answer": "S"}
    },
    # ── Lesson 212 - Simple Words ─────────────────────────────────────────────
    {
        "lesson_id": 212, "category": "handwriting", "difficulty": "easy",
        "title": "Copy the word: cat",
        "instructions": "Trace and copy the word.",
        "content": {"type": "handwriting", "word": "cat", "hint": "Three letters: c - a - t"}
    },
    {
        "lesson_id": 212, "category": "handwriting", "difficulty": "easy",
        "title": "Copy the word: dog",
        "instructions": "Trace and copy the word.",
        "content": {"type": "handwriting", "word": "dog", "hint": "Three letters: d - o - g"}
    },
    {
        "lesson_id": 212, "category": "handwriting", "difficulty": "easy",
        "title": "Copy the word: egg",
        "instructions": "Trace and copy the word.",
        "content": {"type": "handwriting", "word": "egg", "hint": "Three letters: e - g - g"}
    },
    {
        "lesson_id": 212, "category": "handwriting", "difficulty": "easy",
        "title": "Spell the word",
        "instructions": "Put the letters in order to make a word.",
        "content": {"type": "sentence_order", "words": ["t","a","c"], "answer": ["c","a","t"]}
    },
    {
        "lesson_id": 212, "category": "handwriting", "difficulty": "easy",
        "title": "Missing letter",
        "instructions": "Fill in the missing letter.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "d _ g", "answer": "o"},
            {"prompt": "c _ t", "answer": "a"},
            {"prompt": "_ g g", "answer": "e"}
        ]}
    },
    {
        "lesson_id": 212, "category": "handwriting", "difficulty": "easy",
        "title": "Match words to pictures",
        "instructions": "Match each word to its picture.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "cat", "right": "\U0001f431"},
            {"left": "dog", "right": "\U0001f436"},
            {"left": "sun", "right": "\u2600\ufe0f"},
            {"left": "egg", "right": "\U0001f95a"}
        ]}
    },
    # ── Lesson 213 - Writing Sentences ───────────────────────────────────────
    {
        "lesson_id": 213, "category": "handwriting", "difficulty": "easy",
        "title": "Sentence order: I see a cat",
        "instructions": "Put the words in the correct order.",
        "content": {"type": "sentence_order", "words": ["cat","I","a","see"], "answer": ["I","see","a","cat"]}
    },
    {
        "lesson_id": 213, "category": "handwriting", "difficulty": "easy",
        "title": "Sentence order: The dog runs fast",
        "instructions": "Put the words in the correct order.",
        "content": {"type": "sentence_order", "words": ["runs","dog","fast","The"], "answer": ["The","dog","runs","fast"]}
    },
    {
        "lesson_id": 213, "category": "handwriting", "difficulty": "easy",
        "title": "Complete the sentence",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "I ___ to school every day.", "answer": "go"},
            {"prompt": "The sun is very ___.", "answer": "hot"}
        ]}
    },
    {
        "lesson_id": 213, "category": "handwriting", "difficulty": "medium",
        "title": "Capital letter rule",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "Every sentence starts with a capital letter.", "answer": True}
    },
    {
        "lesson_id": 213, "category": "handwriting", "difficulty": "easy",
        "title": "Full stop rule",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "Every sentence ends with a full stop.", "answer": True}
    },
    # ── Lesson 214 - Two-syllable Words ──────────────────────────────────────
    {
        "lesson_id": 214, "category": "handwriting", "difficulty": "medium",
        "title": "Syllables in pencil",
        "instructions": "How many syllables does this word have?",
        "content": {"type": "mcq", "question": "How many syllables in 'PEN-CIL'?", "options": ["1","2","3","4"], "answer": "2"}
    },
    {
        "lesson_id": 214, "category": "handwriting", "difficulty": "medium",
        "title": "Copy two-syllable word",
        "instructions": "Trace and copy the word.",
        "content": {"type": "handwriting", "word": "pencil", "hint": "Two parts: pen - cil"}
    },
    {
        "lesson_id": 214, "category": "handwriting", "difficulty": "medium",
        "title": "Match words to syllables",
        "instructions": "Match each word to its syllable breakdown.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "table", "right": "ta-ble"},
            {"left": "pencil", "right": "pen-cil"},
            {"left": "mango", "right": "man-go"},
            {"left": "water", "right": "wa-ter"}
        ]}
    },
    {
        "lesson_id": 214, "category": "handwriting", "difficulty": "medium",
        "title": "Two-syllable word order",
        "instructions": "Put these syllables together to make a word.",
        "content": {"type": "sentence_order", "words": ["go","man"], "answer": ["man","go"]}
    },
    {
        "lesson_id": 214, "category": "handwriting", "difficulty": "medium",
        "title": "Fill the syllable",
        "instructions": "Fill in the missing syllable.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "ta - ___", "answer": "ble"},
            {"prompt": "wa - ___", "answer": "ter"}
        ]}
    },
]

conn = psycopg2.connect(DB)
cur = conn.cursor()
count = 0
for ex in exercises:
    cur.execute("SELECT id FROM lessons WHERE id = %s", (ex["lesson_id"],))
    if not cur.fetchone():
        print(f"SKIP: lesson_id={ex['lesson_id']} introuvable ({ex['title']})")
        continue
    cur.execute("""
        INSERT INTO exercises (lesson_id, title, instructions, category, difficulty, content, is_active, created_at, updated_at)
        VALUES (%s, %s, %s, %s, %s, %s, true, NOW(), NOW())
    """, (
        ex["lesson_id"], ex["title"], ex["instructions"],
        ex["category"], ex["difficulty"], json.dumps(ex["content"])
    ))
    count += 1
conn.commit()
cur.close()
conn.close()
print(f"OK {count} exercices Handwriting inseres")
