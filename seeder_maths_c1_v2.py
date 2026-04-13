# seeder_maths_c1_v2.py -- Mathematics C1 (level_id=5) unites 1-4
# category=mathematics, type dans content JSON

import json, psycopg2

DB = "dbname=edumaison user=postgres host=localhost"

exercises = [
    # ── Unit 1 - Counting 0-10 ──────────────────────────────────────────────
    # Lesson 28 - Numbers 0 to 5
    {
        "lesson_id": 28, "category": "mathematics", "difficulty": "easy",
        "title": "Count the stars",
        "instructions": "How many objects do you see?",
        "content": {"type": "mcq", "question": "How many stars are there? \u2605\u2605\u2605", "options": ["1","2","3","4"], "answer": "3"}
    },
    {
        "lesson_id": 28, "category": "mathematics", "difficulty": "easy",
        "title": "Count the circles",
        "instructions": "Look and choose the correct number.",
        "content": {"type": "mcq", "question": "How many circles? \u25cf\u25cf", "options": ["1","2","3","5"], "answer": "2"}
    },
    {
        "lesson_id": 28, "category": "mathematics", "difficulty": "easy",
        "title": "Write the number",
        "instructions": "Fill in the missing number.",
        "content": {"type": "fill_in", "items": [{"prompt": "There are ___ apples. \ud83c\udf4e\ud83c\udf4e\ud83c\udf4e\ud83c\udf4e", "answer": "4"}]}
    },
    {
        "lesson_id": 28, "category": "mathematics", "difficulty": "easy",
        "title": "Order 0 to 5",
        "instructions": "Put the numbers in order from smallest to biggest.",
        "content": {"type": "sentence_order", "words": ["3","0","5","1","4","2"], "answer": ["0","1","2","3","4","5"]}
    },
    # Lesson 29 - Numbers 6 to 10
    {
        "lesson_id": 29, "category": "mathematics", "difficulty": "easy",
        "title": "Count the balls",
        "instructions": "How many objects do you see?",
        "content": {"type": "mcq", "question": "How many balls? \u26bd\u26bd\u26bd\u26bd\u26bd\u26bd\u26bd", "options": ["5","6","7","8"], "answer": "7"}
    },
    {
        "lesson_id": 29, "category": "mathematics", "difficulty": "easy",
        "title": "Count the dots",
        "instructions": "Choose the correct number.",
        "content": {"type": "mcq", "question": "How many dots? \u2022\u2022\u2022\u2022\u2022\u2022\u2022\u2022", "options": ["6","7","8","9"], "answer": "8"}
    },
    {
        "lesson_id": 29, "category": "mathematics", "difficulty": "easy",
        "title": "Fingers on both hands",
        "instructions": "Write the missing number.",
        "content": {"type": "fill_in", "items": [{"prompt": "I have ___ fingers on both hands.", "answer": "10"}]}
    },
    {
        "lesson_id": 29, "category": "mathematics", "difficulty": "easy",
        "title": "9 is more than 6",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "9 is more than 6.", "answer": True}
    },
    # Lesson 30 - Count and Write
    {
        "lesson_id": 30, "category": "mathematics", "difficulty": "easy",
        "title": "Count and match",
        "instructions": "Match the number to the group of objects.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "3", "right": "\u2605\u2605\u2605"},
            {"left": "5", "right": "\u25cf\u25cf\u25cf\u25cf\u25cf"},
            {"left": "2", "right": "\u26bd\u26bd"},
            {"left": "4", "right": "\ud83c\udf4e\ud83c\udf4e\ud83c\udf4e\ud83c\udf4e"}
        ]}
    },
    {
        "lesson_id": 30, "category": "mathematics", "difficulty": "easy",
        "title": "Which is bigger?",
        "instructions": "Choose the bigger number.",
        "content": {"type": "mcq", "question": "Which number is bigger?", "options": ["4","7","2","5"], "answer": "7"}
    },
    # ── Unit 2 - Counting 11-20 ─────────────────────────────────────────────
    # Lesson 31 - Numbers 11 to 15
    {
        "lesson_id": 31, "category": "mathematics", "difficulty": "easy",
        "title": "Count to 15",
        "instructions": "How many objects are there?",
        "content": {"type": "mcq", "question": "How many stars? \u2605\u2605\u2605\u2605\u2605\u2605\u2605\u2605\u2605\u2605\u2605\u2605\u2605", "options": ["11","12","13","14"], "answer": "13"}
    },
    {
        "lesson_id": 31, "category": "mathematics", "difficulty": "easy",
        "title": "10 + 2",
        "instructions": "Fill in the answer.",
        "content": {"type": "fill_in", "items": [{"prompt": "10 + 2 = ___", "answer": "12"}]}
    },
    {
        "lesson_id": 31, "category": "mathematics", "difficulty": "easy",
        "title": "Order 11 to 15",
        "instructions": "Put these numbers in order.",
        "content": {"type": "sentence_order", "words": ["14","11","13","15","12"], "answer": ["11","12","13","14","15"]}
    },
    # Lesson 32 - Numbers 16 to 20
    {
        "lesson_id": 32, "category": "mathematics", "difficulty": "easy",
        "title": "10 + 8",
        "instructions": "Choose the correct answer.",
        "content": {"type": "mcq", "question": "10 + 8 = ?", "options": ["16","17","18","19"], "answer": "18"}
    },
    {
        "lesson_id": 32, "category": "mathematics", "difficulty": "easy",
        "title": "10 + 10",
        "instructions": "Fill in the answer.",
        "content": {"type": "fill_in", "items": [{"prompt": "10 + 10 = ___", "answer": "20"}]}
    },
    {
        "lesson_id": 32, "category": "mathematics", "difficulty": "easy",
        "title": "20 is bigger than 16",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "20 is bigger than 16.", "answer": True}
    },
    # Lesson 33 - Before and After
    {
        "lesson_id": 33, "category": "mathematics", "difficulty": "easy",
        "title": "What comes after 14?",
        "instructions": "Choose the number that comes after.",
        "content": {"type": "mcq", "question": "What comes after 14?", "options": ["13","14","15","16"], "answer": "15"}
    },
    {
        "lesson_id": 33, "category": "mathematics", "difficulty": "easy",
        "title": "Before 19",
        "instructions": "Fill in the missing number.",
        "content": {"type": "fill_in", "items": [{"prompt": "___ comes before 19.", "answer": "18"}]}
    },
    {
        "lesson_id": 33, "category": "mathematics", "difficulty": "easy",
        "title": "Between 16 and 18",
        "instructions": "What number is between?",
        "content": {"type": "fill_in", "items": [{"prompt": "16, ___, 18", "answer": "17"}]}
    },
    # ── Unit 3 - Basic Shapes ───────────────────────────────────────────────
    # Lesson 34 - Circle and Square
    {
        "lesson_id": 34, "category": "mathematics", "difficulty": "easy",
        "title": "Ball shape",
        "instructions": "What shape is this?",
        "content": {"type": "mcq", "question": "A ball is shaped like a ___.", "options": ["square","circle","triangle","rectangle"], "answer": "circle"}
    },
    {
        "lesson_id": 34, "category": "mathematics", "difficulty": "easy",
        "title": "Square has 4 sides",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "A square has 4 equal sides.", "answer": True}
    },
    {
        "lesson_id": 34, "category": "mathematics", "difficulty": "easy",
        "title": "Match shapes",
        "instructions": "Match the shape to its description.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "Circle", "right": "Round, no corners"},
            {"left": "Square", "right": "4 equal sides, 4 corners"}
        ]}
    },
    # Lesson 35 - Triangle and Rectangle
    {
        "lesson_id": 35, "category": "mathematics", "difficulty": "easy",
        "title": "Triangle sides",
        "instructions": "How many sides does a triangle have?",
        "content": {"type": "mcq", "question": "How many sides does a triangle have?", "options": ["2","3","4","5"], "answer": "3"}
    },
    {
        "lesson_id": 35, "category": "mathematics", "difficulty": "easy",
        "title": "Rectangle sides",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "A rectangle has 4 sides but they are not all equal.", "answer": True}
    },
    {
        "lesson_id": 35, "category": "mathematics", "difficulty": "easy",
        "title": "Door shape",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [{"prompt": "A door is shaped like a ___.", "answer": "rectangle"}]}
    },
    # ── Unit 4 - Long and Short ─────────────────────────────────────────────
    # Lesson 36 - Long and Short
    {
        "lesson_id": 36, "category": "mathematics", "difficulty": "easy",
        "title": "Pencil and ruler",
        "instructions": "Choose the correct word.",
        "content": {"type": "mcq", "question": "A pencil is ___ than a ruler.", "options": ["longer","shorter","heavier","lighter"], "answer": "shorter"}
    },
    {
        "lesson_id": 36, "category": "mathematics", "difficulty": "easy",
        "title": "Bus and bicycle",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "A bus is longer than a bicycle.", "answer": True}
    },
    {
        "lesson_id": 36, "category": "mathematics", "difficulty": "easy",
        "title": "Snake is long",
        "instructions": "Complete the sentence.",
        "content": {"type": "fill_in", "items": [{"prompt": "A snake is very ___.", "answer": "long"}]}
    },
    # Lesson 37 - Heavy and Light
    {
        "lesson_id": 37, "category": "mathematics", "difficulty": "easy",
        "title": "Heavier object",
        "instructions": "Which is heavier?",
        "content": {"type": "mcq", "question": "Which is heavier?", "options": ["a feather","a stone","a leaf","a paper"], "answer": "a stone"}
    },
    {
        "lesson_id": 37, "category": "mathematics", "difficulty": "easy",
        "title": "Cotton ball vs book",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "A cotton ball is lighter than a book.", "answer": True}
    },
    {
        "lesson_id": 37, "category": "mathematics", "difficulty": "easy",
        "title": "Elephant is heavy",
        "instructions": "Complete the sentence.",
        "content": {"type": "fill_in", "items": [{"prompt": "An elephant is very ___.", "answer": "heavy"}]}
    },
]

conn = psycopg2.connect(DB)
cur = conn.cursor()
count = 0
for ex in exercises:
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
print(f"OK {count} exercices Mathematics C1 inseres")
