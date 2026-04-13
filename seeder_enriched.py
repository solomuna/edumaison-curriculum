# seeder_enriched.py -- VennDiagram + NumberLine + Geometry avec SVG
# Cible : Maths C1 (level_id=5) et C2 (level_id=6)

import json, psycopg2
DB = "dbname=edumaison user=postgres host=localhost"

# Verifier les lesson_ids disponibles pour Sets et Geometry
# On utilisera les unites existantes

exercises = [

    # ════════════════════════════════════════════════════════════════════════
    #  VENN DIAGRAM -- Unit 6 Sets & Patterns C1 (lesson_id a verifier)
    #  On cible lesson_id autour de 40-50 -- a ajuster selon DB
    # ════════════════════════════════════════════════════════════════════════

    # Exercice Venn simple -- fruits et couleurs
    {
        "lesson_id": 52,  # Unit 6 Sets & Patterns C1
        "category": "mathematics", "difficulty": "easy",
        "title": "Sets of fruits and vegetables",
        "instructions": "Place each item in the correct part of the Venn diagram.",
        "content": {
            "type": "venn_diagram",
            "question": "Set A = things that are sweet. Set B = things that are red. Place each item correctly.",
            "labelA": "Sweet",
            "labelB": "Red",
            "setA": ["mango", "banana", "pineapple"],
            "setB": ["tomato", "pepper"],
            "intersection": ["strawberry", "watermelon"],
            "items": ["mango", "banana", "pineapple", "tomato", "pepper", "strawberry", "watermelon"]
        }
    },
    {
        "lesson_id": 52,
        "category": "mathematics", "difficulty": "easy",
        "title": "Sets of numbers",
        "instructions": "Sort the numbers into the correct sets.",
        "content": {
            "type": "venn_diagram",
            "question": "Set A = numbers less than 5. Set B = even numbers. Place each number.",
            "labelA": "Less than 5",
            "labelB": "Even",
            "setA": ["1", "3"],
            "setB": ["6", "8", "10"],
            "intersection": ["2", "4"],
            "items": ["1", "2", "3", "4", "6", "8", "10"]
        }
    },
    {
        "lesson_id": 52,
        "category": "mathematics", "difficulty": "medium",
        "title": "Animals Venn diagram",
        "instructions": "Place each animal in the correct part.",
        "content": {
            "type": "venn_diagram",
            "question": "Set A = animals with 4 legs. Set B = animals that can swim. Place each animal.",
            "labelA": "4 legs",
            "labelB": "Can swim",
            "setA": ["dog", "cat", "goat"],
            "setB": ["fish", "shark"],
            "intersection": ["hippopotamus", "crocodile"],
            "items": ["dog", "cat", "goat", "fish", "shark", "hippopotamus", "crocodile"]
        }
    },

    # ════════════════════════════════════════════════════════════════════════
    #  NUMBER LINE -- Subtraction C2 (lesson_id 41-42)
    # ════════════════════════════════════════════════════════════════════════

    {
        "lesson_id": 41,
        "category": "mathematics", "difficulty": "easy",
        "title": "Subtract on a number line: 5 - 2",
        "instructions": "Use the number line to find the answer.",
        "content": {
            "type": "number_line",
            "question": "Start at 5. Jump back 2. Where do you land?",
            "min": 0, "max": 10,
            "start": 5, "jumps": [-2],
            "answer": 3,
            "options": [2, 3, 4, 7]
        }
    },
    {
        "lesson_id": 41,
        "category": "mathematics", "difficulty": "easy",
        "title": "Subtract on a number line: 10 - 4",
        "instructions": "Use the number line to find the answer.",
        "content": {
            "type": "number_line",
            "question": "Start at 10. Jump back 4. Where do you land?",
            "min": 0, "max": 10,
            "start": 10, "jumps": [-4],
            "answer": 6,
            "options": [4, 5, 6, 8]
        }
    },
    {
        "lesson_id": 42,
        "category": "mathematics", "difficulty": "easy",
        "title": "Subtract on a number line: 15 - 7",
        "instructions": "Use the number line to find the answer.",
        "content": {
            "type": "number_line",
            "question": "Start at 15. Jump back 7. Where do you land?",
            "min": 0, "max": 20,
            "start": 15, "jumps": [-7],
            "answer": 8,
            "options": [7, 8, 9, 22]
        }
    },
    {
        "lesson_id": 42,
        "category": "mathematics", "difficulty": "medium",
        "title": "Subtract on a number line: 13 - 5",
        "instructions": "Use the number line to subtract.",
        "content": {
            "type": "number_line",
            "question": "Start at 13. Hop back 5. Where do you land?",
            "min": 0, "max": 20,
            "start": 13, "jumps": [-5],
            "answer": 8,
            "options": [6, 7, 8, 18]
        }
    },

    # ════════════════════════════════════════════════════════════════════════
    #  GEOMETRY -- Shapes C1 (lesson 34-35) avec SVG inline
    # ════════════════════════════════════════════════════════════════════════

    {
        "lesson_id": 34,
        "category": "mathematics", "difficulty": "easy",
        "title": "Identify the circle",
        "instructions": "Look at the shape and choose its name.",
        "content": {
            "type": "mcq",
            "question": "What shape is this?",
            "svg": "<svg viewBox='0 0 100 100' width='120' height='120'><circle cx='50' cy='50' r='40' fill='#DBEAFE' stroke='#3B82F6' stroke-width='3'/></svg>",
            "options": ["square", "circle", "triangle", "rectangle"],
            "answer": "circle"
        }
    },
    {
        "lesson_id": 34,
        "category": "mathematics", "difficulty": "easy",
        "title": "Identify the square",
        "instructions": "Look at the shape and choose its name.",
        "content": {
            "type": "mcq",
            "question": "What shape is this?",
            "svg": "<svg viewBox='0 0 100 100' width='120' height='120'><rect x='15' y='15' width='70' height='70' fill='#FEF9C3' stroke='#EAB308' stroke-width='3'/></svg>",
            "options": ["circle", "rectangle", "square", "triangle"],
            "answer": "square"
        }
    },
    {
        "lesson_id": 35,
        "category": "mathematics", "difficulty": "easy",
        "title": "Identify the triangle",
        "instructions": "Look at the shape and choose its name.",
        "content": {
            "type": "mcq",
            "question": "What shape is this?",
            "svg": "<svg viewBox='0 0 100 100' width='120' height='120'><polygon points='50,10 90,90 10,90' fill='#DCFCE7' stroke='#22C55E' stroke-width='3'/></svg>",
            "options": ["circle", "square", "triangle", "rectangle"],
            "answer": "triangle"
        }
    },
    {
        "lesson_id": 35,
        "category": "mathematics", "difficulty": "easy",
        "title": "Identify the rectangle",
        "instructions": "Look at the shape and choose its name.",
        "content": {
            "type": "mcq",
            "question": "What shape is this?",
            "svg": "<svg viewBox='0 0 140 80' width='140' height='80'><rect x='10' y='10' width='120' height='60' fill='#FEE2E2' stroke='#EF4444' stroke-width='3'/></svg>",
            "options": ["circle", "square", "triangle", "rectangle"],
            "answer": "rectangle"
        }
    },
    {
        "lesson_id": 35,
        "category": "mathematics", "difficulty": "medium",
        "title": "Count the sides",
        "instructions": "Look at the shape. How many sides does it have?",
        "content": {
            "type": "mcq",
            "question": "How many sides does this shape have?",
            "svg": "<svg viewBox='0 0 100 100' width='120' height='120'><polygon points='50,10 90,90 10,90' fill='#DCFCE7' stroke='#22C55E' stroke-width='3'/></svg>",
            "options": ["2", "3", "4", "5"],
            "answer": "3"
        }
    },
    {
        "lesson_id": 34,
        "category": "mathematics", "difficulty": "medium",
        "title": "Match shapes to names",
        "instructions": "Match each shape description to its name.",
        "content": {
            "type": "match_pairs",
            "pairs": [
                {"left": "Round, no corners", "right": "circle"},
                {"left": "4 equal sides", "right": "square"},
                {"left": "3 sides, 3 corners", "right": "triangle"},
                {"left": "4 sides, 2 long 2 short", "right": "rectangle"}
            ]
        }
    },

    # ════════════════════════════════════════════════════════════════════════
    #  NUMBER LINE -- Addition C2 (lesson 38-39)
    # ════════════════════════════════════════════════════════════════════════

    {
        "lesson_id": 38,
        "category": "mathematics", "difficulty": "easy",
        "title": "Add on a number line: 3 + 4",
        "instructions": "Use the number line to find the answer.",
        "content": {
            "type": "number_line",
            "question": "Start at 3. Jump forward 4. Where do you land?",
            "min": 0, "max": 10,
            "start": 3, "jumps": [4],
            "answer": 7,
            "options": [6, 7, 8, 9]
        }
    },
    {
        "lesson_id": 39,
        "category": "mathematics", "difficulty": "easy",
        "title": "Add on a number line: 8 + 6",
        "instructions": "Use the number line to find the answer.",
        "content": {
            "type": "number_line",
            "question": "Start at 8. Jump forward 6. Where do you land?",
            "min": 0, "max": 20,
            "start": 8, "jumps": [6],
            "answer": 14,
            "options": [12, 13, 14, 16]
        }
    },
]

conn = psycopg2.connect(DB)
cur = conn.cursor()

# Verifier d'abord que lesson_id=52 existe
cur.execute("SELECT id, name FROM lessons WHERE id = 52")
row = cur.fetchone()
if row:
    print(f"Lesson 52 trouvee: {row}")
else:
    print("Lesson 52 introuvable -- cherche la bonne lesson pour Sets & Patterns")
    cur.execute("""
        SELECT l.id, l.name FROM lessons l
        JOIN units u ON l.unit_id = u.id
        JOIN integrated_themes it ON u.integrated_theme_id = it.id
        JOIN subjects s ON it.subject_id = s.id
        WHERE s.level_id = 5 AND u.name ILIKE '%Sets%'
    """)
    rows = cur.fetchall()
    print("Lessons Sets C1:", rows)

count = 0
skipped = 0
for ex in exercises:
    # Verifier que la lesson existe avant d'inserer
    cur.execute("SELECT id FROM lessons WHERE id = %s", (ex["lesson_id"],))
    if not cur.fetchone():
        print(f"SKIP: lesson_id={ex['lesson_id']} introuvable ({ex['title']})")
        skipped += 1
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
print(f"OK {count} exercices enrichis inseres, {skipped} skipped")
