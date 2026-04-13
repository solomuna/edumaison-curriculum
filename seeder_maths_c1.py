# seeder_maths_c1.py -- Mathematics C1 (level_id=5) unites 1-4
# Lessons 28-37 -- exercices manquants

import json, datetime
DB = "dbname=edumaison user=postgres host=localhost"

exercises = [
    # ── Unit 1 - Counting 0-10 ──────────────────────────────────────────────
    # Lesson 28 - Numbers 0 to 5
    {
        "lesson_id": 28,
        "title": "Count the objects",
        "instructions": "How many objects do you see?",
        "category": "MCQ",
        "difficulty": 1,
        "content": {
            "question": "How many stars are there? \u2605\u2605\u2605",
            "options": ["1", "2", "3", "4"],
            "answer": "3"
        }
    },
    {
        "lesson_id": 28,
        "title": "Choose the right number",
        "instructions": "Look at the picture and choose the correct number.",
        "category": "MCQ",
        "difficulty": 1,
        "content": {
            "question": "How many circles? \u25cf\u25cf",
            "options": ["1", "2", "3", "5"],
            "answer": "2"
        }
    },
    {
        "lesson_id": 28,
        "title": "Write the number",
        "instructions": "Fill in the missing number.",
        "category": "FillIn",
        "difficulty": 1,
        "content": {
            "sentence": "There are ___ apples. \ud83c\udf4e\ud83c\udf4e\ud83c\udf4e\ud83c\udf4e",
            "answer": "4",
            "hint": "Count the apples"
        }
    },
    {
        "lesson_id": 28,
        "title": "Number order 0-5",
        "instructions": "Put the numbers in the correct order.",
        "category": "SentenceOrder",
        "difficulty": 1,
        "content": {
            "words": ["3", "0", "5", "1", "4", "2"],
            "answer": ["0", "1", "2", "3", "4", "5"]
        }
    },
    # Lesson 29 - Numbers 6 to 10
    {
        "lesson_id": 29,
        "title": "Count to 10",
        "instructions": "How many objects do you see?",
        "category": "MCQ",
        "difficulty": 1,
        "content": {
            "question": "How many balls? \u26bd\u26bd\u26bd\u26bd\u26bd\u26bd\u26bd",
            "options": ["5", "6", "7", "8"],
            "answer": "7"
        }
    },
    {
        "lesson_id": 29,
        "title": "Number 8",
        "instructions": "Choose the correct number.",
        "category": "MCQ",
        "difficulty": 1,
        "content": {
            "question": "How many dots? \u2022\u2022\u2022\u2022\u2022\u2022\u2022\u2022",
            "options": ["6", "7", "8", "9"],
            "answer": "8"
        }
    },
    {
        "lesson_id": 29,
        "title": "Fill in the number",
        "instructions": "Write the missing number.",
        "category": "FillIn",
        "difficulty": 1,
        "content": {
            "sentence": "I have ___ fingers on both hands.",
            "answer": "10",
            "hint": "Count all your fingers"
        }
    },
    {
        "lesson_id": 29,
        "title": "True or False",
        "instructions": "Is this correct?",
        "category": "TrueFalse",
        "difficulty": 1,
        "content": {
            "statement": "9 is more than 6.",
            "answer": True
        }
    },
    # Lesson 30 - Count and Write
    {
        "lesson_id": 30,
        "title": "Count and match",
        "instructions": "Match the number to the group of objects.",
        "category": "MatchPairs",
        "difficulty": 1,
        "content": {
            "pairs": [
                {"left": "3", "right": "\u2605\u2605\u2605"},
                {"left": "5", "right": "\u25cf\u25cf\u25cf\u25cf\u25cf"},
                {"left": "2", "right": "\u26bd\u26bd"},
                {"left": "4", "right": "\ud83c\udf4e\ud83c\udf4e\ud83c\udf4e\ud83c\udf4e"}
            ]
        }
    },
    {
        "lesson_id": 30,
        "title": "Which is more?",
        "instructions": "Choose the bigger number.",
        "category": "MCQ",
        "difficulty": 1,
        "content": {
            "question": "Which number is bigger?",
            "options": ["4", "7", "2", "5"],
            "answer": "7"
        }
    },
    # ── Unit 2 - Counting 11-20 ─────────────────────────────────────────────
    # Lesson 31 - Numbers 11 to 15
    {
        "lesson_id": 31,
        "title": "Count to 15",
        "instructions": "How many objects are there?",
        "category": "MCQ",
        "difficulty": 1,
        "content": {
            "question": "How many? \u2605\u2605\u2605\u2605\u2605\u2605\u2605\u2605\u2605\u2605\u2605\u2605\u2605",
            "options": ["11", "12", "13", "14"],
            "answer": "13"
        }
    },
    {
        "lesson_id": 31,
        "title": "Fill in 11-15",
        "instructions": "Write the missing number.",
        "category": "FillIn",
        "difficulty": 1,
        "content": {
            "sentence": "10 + 2 = ___",
            "answer": "12",
            "hint": "Add 10 and 2"
        }
    },
    {
        "lesson_id": 31,
        "title": "Order 11-15",
        "instructions": "Put these numbers in order.",
        "category": "SentenceOrder",
        "difficulty": 1,
        "content": {
            "words": ["14", "11", "13", "15", "12"],
            "answer": ["11", "12", "13", "14", "15"]
        }
    },
    # Lesson 32 - Numbers 16 to 20
    {
        "lesson_id": 32,
        "title": "Count to 20",
        "instructions": "Choose the correct number.",
        "category": "MCQ",
        "difficulty": 1,
        "content": {
            "question": "10 + 8 = ?",
            "options": ["16", "17", "18", "19"],
            "answer": "18"
        }
    },
    {
        "lesson_id": 32,
        "title": "Fill in 16-20",
        "instructions": "Write the missing number.",
        "category": "FillIn",
        "difficulty": 1,
        "content": {
            "sentence": "10 + 10 = ___",
            "answer": "20",
            "hint": "Ten plus ten"
        }
    },
    {
        "lesson_id": 32,
        "title": "True or False",
        "instructions": "Is this correct?",
        "category": "TrueFalse",
        "difficulty": 1,
        "content": {
            "statement": "20 is bigger than 16.",
            "answer": True
        }
    },
    # Lesson 33 - Before and After
    {
        "lesson_id": 33,
        "title": "What comes after?",
        "instructions": "Choose the number that comes after.",
        "category": "MCQ",
        "difficulty": 1,
        "content": {
            "question": "What comes after 14?",
            "options": ["13", "14", "15", "16"],
            "answer": "15"
        }
    },
    {
        "lesson_id": 33,
        "title": "What comes before?",
        "instructions": "Fill in the missing number.",
        "category": "FillIn",
        "difficulty": 1,
        "content": {
            "sentence": "___ comes before 19.",
            "answer": "18",
            "hint": "One less than 19"
        }
    },
    {
        "lesson_id": 33,
        "title": "Between numbers",
        "instructions": "What number is between?",
        "category": "FillIn",
        "difficulty": 1,
        "content": {
            "sentence": "16, ___, 18",
            "answer": "17",
            "hint": "Between 16 and 18"
        }
    },
    # ── Unit 3 - Basic Shapes ───────────────────────────────────────────────
    # Lesson 34 - Circle and Square
    {
        "lesson_id": 34,
        "title": "Name the shape",
        "instructions": "What shape is this?",
        "category": "MCQ",
        "difficulty": 1,
        "content": {
            "question": "A ball is shaped like a ___.",
            "options": ["square", "circle", "triangle", "rectangle"],
            "answer": "circle"
        }
    },
    {
        "lesson_id": 34,
        "title": "Squares have 4 sides",
        "instructions": "Is this true or false?",
        "category": "TrueFalse",
        "difficulty": 1,
        "content": {
            "statement": "A square has 4 equal sides.",
            "answer": True
        }
    },
    {
        "lesson_id": 34,
        "title": "Match shapes",
        "instructions": "Match the shape to its name.",
        "category": "MatchPairs",
        "difficulty": 1,
        "content": {
            "pairs": [
                {"left": "Circle", "right": "Round shape, no corners"},
                {"left": "Square", "right": "4 equal sides and 4 corners"}
            ]
        }
    },
    # Lesson 35 - Triangle and Rectangle
    {
        "lesson_id": 35,
        "title": "Triangle sides",
        "instructions": "How many sides does a triangle have?",
        "category": "MCQ",
        "difficulty": 1,
        "content": {
            "question": "How many sides does a triangle have?",
            "options": ["2", "3", "4", "5"],
            "answer": "3"
        }
    },
    {
        "lesson_id": 35,
        "title": "Rectangle",
        "instructions": "Is this correct?",
        "category": "TrueFalse",
        "difficulty": 1,
        "content": {
            "statement": "A rectangle has 4 sides but they are not all equal.",
            "answer": True
        }
    },
    {
        "lesson_id": 35,
        "title": "Shape names",
        "instructions": "Fill in the missing word.",
        "category": "FillIn",
        "difficulty": 1,
        "content": {
            "sentence": "A door is shaped like a ___.",
            "answer": "rectangle",
            "hint": "4 sides, 2 long and 2 short"
        }
    },
    # ── Unit 4 - Long and Short ─────────────────────────────────────────────
    # Lesson 36 - Long and Short
    {
        "lesson_id": 36,
        "title": "Long or Short?",
        "instructions": "Choose the correct word.",
        "category": "MCQ",
        "difficulty": 1,
        "content": {
            "question": "A pencil is ___ than a ruler.",
            "options": ["longer", "shorter", "heavier", "lighter"],
            "answer": "shorter"
        }
    },
    {
        "lesson_id": 36,
        "title": "Compare lengths",
        "instructions": "Is this true or false?",
        "category": "TrueFalse",
        "difficulty": 1,
        "content": {
            "statement": "A bus is longer than a bicycle.",
            "answer": True
        }
    },
    {
        "lesson_id": 36,
        "title": "Fill in long or short",
        "instructions": "Complete the sentence.",
        "category": "FillIn",
        "difficulty": 1,
        "content": {
            "sentence": "A snake is very ___.",
            "answer": "long",
            "hint": "Think about how a snake looks"
        }
    },
    # Lesson 37 - Heavy and Light
    {
        "lesson_id": 37,
        "title": "Heavy or Light?",
        "instructions": "Which is heavier?",
        "category": "MCQ",
        "difficulty": 1,
        "content": {
            "question": "Which is heavier?",
            "options": ["a feather", "a stone", "a leaf", "a paper"],
            "answer": "a stone"
        }
    },
    {
        "lesson_id": 37,
        "title": "Light objects",
        "instructions": "Is this true or false?",
        "category": "TrueFalse",
        "difficulty": 1,
        "content": {
            "statement": "A cotton ball is lighter than a book.",
            "answer": True
        }
    },
    {
        "lesson_id": 37,
        "title": "Fill in heavy or light",
        "instructions": "Complete the sentence.",
        "category": "FillIn",
        "difficulty": 1,
        "content": {
            "sentence": "An elephant is very ___.",
            "answer": "heavy",
            "hint": "Think about how much an elephant weighs"
        }
    },
]

# Insert into DB
import psycopg2
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
