# seeder_maths_c2.py -- Mathematics C2 (level_id=6) unites 2-4
# Lessons 44, 38-42

import json, psycopg2
DB = "dbname=edumaison user=postgres host=localhost"

exercises = [
    # ── Unit 2 - Numbers to 100 ─────────────────────────────────────────────
    # Lesson 44 - Numbers to 100
    {
        "lesson_id": 44, "category": "mathematics", "difficulty": "easy",
        "title": "Count to 100",
        "instructions": "Choose the correct number.",
        "content": {"type": "mcq", "question": "What comes after 49?", "options": ["48","50","51","59"], "answer": "50"}
    },
    {
        "lesson_id": 44, "category": "mathematics", "difficulty": "easy",
        "title": "Tens and ones",
        "instructions": "Fill in the missing number.",
        "content": {"type": "fill_in", "items": [{"prompt": "6 tens and 5 ones = ___", "answer": "65"}]}
    },
    {
        "lesson_id": 44, "category": "mathematics", "difficulty": "easy",
        "title": "Which is bigger?",
        "instructions": "Choose the bigger number.",
        "content": {"type": "mcq", "question": "Which is bigger?", "options": ["45","54","44","53"], "answer": "54"}
    },
    {
        "lesson_id": 44, "category": "mathematics", "difficulty": "easy",
        "title": "Order numbers",
        "instructions": "Put these numbers in order from smallest to biggest.",
        "content": {"type": "sentence_order", "words": ["72","35","91","48","60"], "answer": ["35","48","60","72","91"]}
    },
    {
        "lesson_id": 44, "category": "mathematics", "difficulty": "easy",
        "title": "100 is biggest",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "100 is bigger than 99.", "answer": True}
    },
    {
        "lesson_id": 44, "category": "mathematics", "difficulty": "medium",
        "title": "Before and after",
        "instructions": "Fill in the missing numbers.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "___ comes before 80.", "answer": "79"},
            {"prompt": "___ comes after 99.", "answer": "100"}
        ]}
    },
    # ── Unit 3 - Adding Numbers ─────────────────────────────────────────────
    # Lesson 38 - Adding to 10
    {
        "lesson_id": 38, "category": "mathematics", "difficulty": "easy",
        "title": "Add to 10",
        "instructions": "Choose the correct answer.",
        "content": {"type": "mcq", "question": "3 + 4 = ?", "options": ["5","6","7","8"], "answer": "7"}
    },
    {
        "lesson_id": 38, "category": "mathematics", "difficulty": "easy",
        "title": "Fill in the sum",
        "instructions": "Write the missing answer.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "5 + 5 = ___", "answer": "10"},
            {"prompt": "2 + 6 = ___", "answer": "8"}
        ]}
    },
    {
        "lesson_id": 38, "category": "mathematics", "difficulty": "easy",
        "title": "Adding is commutative",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "4 + 3 gives the same answer as 3 + 4.", "answer": True}
    },
    {
        "lesson_id": 38, "category": "mathematics", "difficulty": "easy",
        "title": "Match additions",
        "instructions": "Match each addition to its answer.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "1 + 9", "right": "10"},
            {"left": "4 + 4", "right": "8"},
            {"left": "3 + 5", "right": "8"},
            {"left": "6 + 2", "right": "8"}
        ]}
    },
    # Lesson 39 - Adding to 20
    {
        "lesson_id": 39, "category": "mathematics", "difficulty": "easy",
        "title": "Add to 20",
        "instructions": "Choose the correct answer.",
        "content": {"type": "mcq", "question": "8 + 7 = ?", "options": ["13","14","15","16"], "answer": "15"}
    },
    {
        "lesson_id": 39, "category": "mathematics", "difficulty": "easy",
        "title": "Fill sums to 20",
        "instructions": "Write the missing answers.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "9 + 9 = ___", "answer": "18"},
            {"prompt": "6 + 8 = ___", "answer": "14"}
        ]}
    },
    {
        "lesson_id": 39, "category": "mathematics", "difficulty": "easy",
        "title": "10 + 9 = 19",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "10 + 9 = 19", "answer": True}
    },
    # Lesson 40 - Word Problems
    {
        "lesson_id": 40, "category": "mathematics", "difficulty": "medium",
        "title": "Apples word problem",
        "instructions": "Read and solve.",
        "content": {"type": "mcq", "question": "Mary has 5 apples. She gets 3 more. How many does she have now?", "options": ["6","7","8","9"], "answer": "8"}
    },
    {
        "lesson_id": 40, "category": "mathematics", "difficulty": "medium",
        "title": "Birds word problem",
        "instructions": "Read and fill in.",
        "content": {"type": "fill_in", "items": [{"prompt": "There are 6 birds on a tree. 4 more arrive. Now there are ___ birds.", "answer": "10"}]}
    },
    {
        "lesson_id": 40, "category": "mathematics", "difficulty": "medium",
        "title": "Boys and girls",
        "instructions": "Choose the correct answer.",
        "content": {"type": "mcq", "question": "There are 7 boys and 6 girls in a class. How many children are there?", "options": ["11","12","13","14"], "answer": "13"}
    },
    # ── Unit 4 - Taking Away ────────────────────────────────────────────────
    # Lesson 41 - Subtract from 10
    {
        "lesson_id": 41, "category": "mathematics", "difficulty": "easy",
        "title": "Take away from 10",
        "instructions": "Choose the correct answer.",
        "content": {"type": "mcq", "question": "10 - 3 = ?", "options": ["5","6","7","8"], "answer": "7"}
    },
    {
        "lesson_id": 41, "category": "mathematics", "difficulty": "easy",
        "title": "Fill subtractions",
        "instructions": "Write the missing answers.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "8 - 4 = ___", "answer": "4"},
            {"prompt": "9 - 6 = ___", "answer": "3"}
        ]}
    },
    {
        "lesson_id": 41, "category": "mathematics", "difficulty": "easy",
        "title": "5 - 5 = 0",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "5 - 5 = 0", "answer": True}
    },
    {
        "lesson_id": 41, "category": "mathematics", "difficulty": "easy",
        "title": "Match subtractions",
        "instructions": "Match each subtraction to its answer.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "10 - 2", "right": "8"},
            {"left": "7 - 3", "right": "4"},
            {"left": "6 - 1", "right": "5"},
            {"left": "9 - 4", "right": "5"}
        ]}
    },
    # Lesson 42 - Subtract from 20
    {
        "lesson_id": 42, "category": "mathematics", "difficulty": "easy",
        "title": "Take away from 20",
        "instructions": "Choose the correct answer.",
        "content": {"type": "mcq", "question": "20 - 8 = ?", "options": ["10","11","12","13"], "answer": "12"}
    },
    {
        "lesson_id": 42, "category": "mathematics", "difficulty": "easy",
        "title": "Fill subtractions to 20",
        "instructions": "Write the missing answers.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "15 - 7 = ___", "answer": "8"},
            {"prompt": "18 - 9 = ___", "answer": "9"}
        ]}
    },
    {
        "lesson_id": 42, "category": "mathematics", "difficulty": "medium",
        "title": "Sweets word problem",
        "instructions": "Read and solve.",
        "content": {"type": "mcq", "question": "John has 16 sweets. He gives 5 to his friend. How many does he have left?", "options": ["9","10","11","12"], "answer": "11"}
    },
    {
        "lesson_id": 42, "category": "mathematics", "difficulty": "easy",
        "title": "20 - 20 = 0",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "20 - 20 = 0", "answer": True}
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
print(f"OK {count} exercices Mathematics C2 inseres")
