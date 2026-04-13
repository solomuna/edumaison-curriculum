# seeder_english_c1_c2.py -- English C1+C2 unites a 0 exercices
import json, psycopg2
DB = "dbname=edumaison user=postgres host=localhost"

exercises = [
    # ── C1 Unit 4 - My Classroom ─────────────────────────────────────────────
    # Lesson 11 - Classroom Objects
    {
        "lesson_id": 11, "category": "reading", "difficulty": "easy",
        "title": "Classroom objects",
        "instructions": "What do you use in class?",
        "content": {"type": "mcq", "question": "Which one do you use to write?", "options": ["ruler","pencil","bag","chair"], "answer": "pencil"}
    },
    {
        "lesson_id": 11, "category": "reading", "difficulty": "easy",
        "title": "Match classroom objects",
        "instructions": "Match each object to its use.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "pencil", "right": "to write"},
            {"left": "ruler", "right": "to measure"},
            {"left": "book", "right": "to read"},
            {"left": "eraser", "right": "to rub out"}
        ]}
    },
    {
        "lesson_id": 11, "category": "reading", "difficulty": "easy",
        "title": "Name the object",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "We sit on a ___.", "answer": "chair"},
            {"prompt": "The teacher writes on the ___.", "answer": "board"}
        ]}
    },
    {
        "lesson_id": 11, "category": "vocabulary", "difficulty": "easy",
        "title": "Classroom word order",
        "instructions": "Put the words in the correct order.",
        "content": {"type": "sentence_order", "words": ["is","This","my","pencil"], "answer": ["This","is","my","pencil"]}
    },
    # Lesson 12 - My Teacher
    {
        "lesson_id": 12, "category": "reading", "difficulty": "easy",
        "title": "My teacher",
        "instructions": "Choose the correct answer.",
        "content": {"type": "mcq", "question": "What does a teacher do?", "options": ["cooks food","teaches children","drives a bus","sells goods"], "answer": "teaches children"}
    },
    {
        "lesson_id": 12, "category": "vocabulary", "difficulty": "easy",
        "title": "Greet your teacher",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "Good morning, ___!", "answer": "teacher"},
            {"prompt": "Thank you, ___.", "answer": "teacher"}
        ]}
    },
    {
        "lesson_id": 12, "category": "reading", "difficulty": "easy",
        "title": "Teacher is kind",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "A teacher helps children learn.", "answer": True}
    },
    # Lesson 13 - School Rules
    {
        "lesson_id": 13, "category": "reading", "difficulty": "easy",
        "title": "School rules",
        "instructions": "Choose the correct school rule.",
        "content": {"type": "mcq", "question": "What should you do in class?", "options": ["shout loudly","listen to the teacher","sleep","eat food"], "answer": "listen to the teacher"}
    },
    {
        "lesson_id": 13, "category": "reading", "difficulty": "easy",
        "title": "Good behaviour",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "We must be quiet when the teacher is talking.", "answer": True}
    },
    {
        "lesson_id": 13, "category": "vocabulary", "difficulty": "easy",
        "title": "Complete the rule",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "We must not ___ in class.", "answer": "run"},
            {"prompt": "We must ___ our hands before eating.", "answer": "wash"}
        ]}
    },
    # ── C1 Unit 5 - Parts of the Body ────────────────────────────────────────
    # Lesson 14 - Head and Face
    {
        "lesson_id": 14, "category": "vocabulary", "difficulty": "easy",
        "title": "Parts of the face",
        "instructions": "Choose the correct answer.",
        "content": {"type": "mcq", "question": "Which part of the face do we use to see?", "options": ["ears","nose","eyes","mouth"], "answer": "eyes"}
    },
    {
        "lesson_id": 14, "category": "vocabulary", "difficulty": "easy",
        "title": "Match face parts",
        "instructions": "Match each part to its function.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "eyes", "right": "to see"},
            {"left": "ears", "right": "to hear"},
            {"left": "nose", "right": "to smell"},
            {"left": "mouth", "right": "to eat and speak"}
        ]}
    },
    {
        "lesson_id": 14, "category": "vocabulary", "difficulty": "easy",
        "title": "Head parts",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "We have two ___.", "answer": "eyes"},
            {"prompt": "Hair grows on our ___.", "answer": "head"}
        ]}
    },
    {
        "lesson_id": 14, "category": "vocabulary", "difficulty": "easy",
        "title": "Ears to hear",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "We use our ears to hear.", "answer": True}
    },
    # Lesson 15 - Arms and Legs
    {
        "lesson_id": 15, "category": "vocabulary", "difficulty": "easy",
        "title": "Arms and hands",
        "instructions": "Choose the correct answer.",
        "content": {"type": "mcq", "question": "How many hands do we have?", "options": ["1","2","3","4"], "answer": "2"}
    },
    {
        "lesson_id": 15, "category": "vocabulary", "difficulty": "easy",
        "title": "Legs and feet",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "We use our legs to walk and run.", "answer": True}
    },
    {
        "lesson_id": 15, "category": "vocabulary", "difficulty": "easy",
        "title": "Body parts fill in",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "We have two ___ to walk.", "answer": "legs"},
            {"prompt": "Fingers are on our ___.", "answer": "hands"}
        ]}
    },
    # Lesson 16 - Head Shoulders Knees
    {
        "lesson_id": 16, "category": "vocabulary", "difficulty": "easy",
        "title": "Body song order",
        "instructions": "Put the body parts in order as in the song.",
        "content": {"type": "sentence_order", "words": ["knees","shoulders","head","toes"], "answer": ["head","shoulders","knees","toes"]}
    },
    {
        "lesson_id": 16, "category": "vocabulary", "difficulty": "easy",
        "title": "Knees and toes",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "Knees are part of our legs.", "answer": True}
    },
    {
        "lesson_id": 16, "category": "vocabulary", "difficulty": "easy",
        "title": "Complete the song",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "Head, ___, knees and toes.", "answer": "shoulders"}
        ]}
    },
    # ── C2 Unit 1 - My Neighbourhood ─────────────────────────────────────────
    # Lesson 20 - My Street
    {
        "lesson_id": 20, "category": "reading", "difficulty": "easy",
        "title": "My street",
        "instructions": "Choose the correct answer.",
        "content": {"type": "mcq", "question": "What can you find on a street?", "options": ["fish","houses and shops","mountains","rivers"], "answer": "houses and shops"}
    },
    {
        "lesson_id": 20, "category": "vocabulary", "difficulty": "easy",
        "title": "Street vocabulary",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "Cars drive on the ___.", "answer": "road"},
            {"prompt": "People walk on the ___.", "answer": "pavement"}
        ]}
    },
    {
        "lesson_id": 20, "category": "reading", "difficulty": "easy",
        "title": "Streets have houses",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "A street has houses and roads.", "answer": True}
    },
    # Lesson 21 - Buildings Around Us
    {
        "lesson_id": 21, "category": "vocabulary", "difficulty": "easy",
        "title": "Buildings",
        "instructions": "Choose the correct answer.",
        "content": {"type": "mcq", "question": "Where do sick people go?", "options": ["school","market","hospital","church"], "answer": "hospital"}
    },
    {
        "lesson_id": 21, "category": "vocabulary", "difficulty": "easy",
        "title": "Match buildings",
        "instructions": "Match each building to its purpose.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "school", "right": "children learn here"},
            {"left": "hospital", "right": "sick people come here"},
            {"left": "market", "right": "people buy and sell"},
            {"left": "church", "right": "people pray here"}
        ]}
    },
    {
        "lesson_id": 21, "category": "reading", "difficulty": "easy",
        "title": "School building",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "Children go to school to learn.", "answer": True}
    },
    # Lesson 22 - Where do you live?
    {
        "lesson_id": 22, "category": "reading", "difficulty": "easy",
        "title": "Where I live",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "I live in a ___.", "answer": "house"},
            {"prompt": "My ___ is in Yaounde.", "answer": "home"}
        ]}
    },
    {
        "lesson_id": 22, "category": "vocabulary", "difficulty": "easy",
        "title": "Address sentence",
        "instructions": "Put the words in the correct order.",
        "content": {"type": "sentence_order", "words": ["in","live","Yaounde","I"], "answer": ["I","live","in","Yaounde"]}
    },
    # ── C2 Unit 2 - Farm Animals ──────────────────────────────────────────────
    # Lesson 23 - Animals on the Farm
    {
        "lesson_id": 23, "category": "vocabulary", "difficulty": "easy",
        "title": "Farm animals",
        "instructions": "Which animal lives on a farm?",
        "content": {"type": "mcq", "question": "Which animal lives on a farm?", "options": ["lion","cow","elephant","tiger"], "answer": "cow"}
    },
    {
        "lesson_id": 23, "category": "vocabulary", "difficulty": "easy",
        "title": "Match farm animals",
        "instructions": "Match each animal to what it gives us.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "cow", "right": "milk"},
            {"left": "hen", "right": "eggs"},
            {"left": "sheep", "right": "wool"},
            {"left": "pig", "right": "meat"}
        ]}
    },
    {
        "lesson_id": 23, "category": "reading", "difficulty": "easy",
        "title": "Cows give milk",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "Cows give us milk.", "answer": True}
    },
    # Lesson 24 - Animal Sounds
    {
        "lesson_id": 24, "category": "vocabulary", "difficulty": "easy",
        "title": "Animal sounds",
        "instructions": "What sound does this animal make?",
        "content": {"type": "mcq", "question": "What sound does a cow make?", "options": ["moo","baa","oink","cluck"], "answer": "moo"}
    },
    {
        "lesson_id": 24, "category": "vocabulary", "difficulty": "easy",
        "title": "Match sounds",
        "instructions": "Match each animal to its sound.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "dog", "right": "woof"},
            {"left": "cat", "right": "meow"},
            {"left": "sheep", "right": "baa"},
            {"left": "pig", "right": "oink"}
        ]}
    },
    {
        "lesson_id": 24, "category": "vocabulary", "difficulty": "easy",
        "title": "Fill the sound",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "A hen says ___.", "answer": "cluck"},
            {"prompt": "A duck says ___.", "answer": "quack"}
        ]}
    },
    # Lesson 25 - Baby Animals
    {
        "lesson_id": 25, "category": "vocabulary", "difficulty": "easy",
        "title": "Baby animal names",
        "instructions": "What is a baby cow called?",
        "content": {"type": "mcq", "question": "What is a baby cow called?", "options": ["kitten","calf","lamb","chick"], "answer": "calf"}
    },
    {
        "lesson_id": 25, "category": "vocabulary", "difficulty": "easy",
        "title": "Match baby animals",
        "instructions": "Match each animal to its baby.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "hen", "right": "chick"},
            {"left": "dog", "right": "puppy"},
            {"left": "cat", "right": "kitten"},
            {"left": "sheep", "right": "lamb"}
        ]}
    },
    {
        "lesson_id": 25, "category": "reading", "difficulty": "easy",
        "title": "Kitten is baby cat",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "A kitten is a baby cat.", "answer": True}
    },
    # ── C2 Unit 3 - Wild Animals ──────────────────────────────────────────────
    # Lesson 26 - Animals in the Forest
    {
        "lesson_id": 26, "category": "vocabulary", "difficulty": "easy",
        "title": "Forest animals",
        "instructions": "Which animal lives in the forest?",
        "content": {"type": "mcq", "question": "Which animal lives in the forest?", "options": ["cow","monkey","hen","goat"], "answer": "monkey"}
    },
    {
        "lesson_id": 26, "category": "vocabulary", "difficulty": "easy",
        "title": "Match forest animals",
        "instructions": "Match each animal to where it lives.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "monkey", "right": "forest"},
            {"left": "fish", "right": "water"},
            {"left": "eagle", "right": "sky"},
            {"left": "lion", "right": "savanna"}
        ]}
    },
    {
        "lesson_id": 26, "category": "reading", "difficulty": "easy",
        "title": "Lions are wild",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "Lions are wild animals.", "answer": True}
    },
    # Lesson 27 - Animals in Africa
    {
        "lesson_id": 27, "category": "vocabulary", "difficulty": "easy",
        "title": "African animals",
        "instructions": "Which animal is found in Africa?",
        "content": {"type": "mcq", "question": "Which is the biggest land animal in Africa?", "options": ["rabbit","elephant","cat","dog"], "answer": "elephant"}
    },
    {
        "lesson_id": 27, "category": "vocabulary", "difficulty": "easy",
        "title": "African animal facts",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "Giraffes have very long necks.", "answer": True}
    },
    {
        "lesson_id": 27, "category": "vocabulary", "difficulty": "easy",
        "title": "Fill in African animal",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "The ___ is the fastest land animal.", "answer": "cheetah"},
            {"prompt": "Zebras have black and white ___.", "answer": "stripes"}
        ]}
    },
    # ── C2 Unit 4 - Our Environment ──────────────────────────────────────────
    # Lesson 70 - The World Around Us
    {
        "lesson_id": 70, "category": "reading", "difficulty": "easy",
        "title": "Our environment",
        "instructions": "Choose the correct answer.",
        "content": {"type": "mcq", "question": "What is part of our natural environment?", "options": ["cars","trees","buildings","roads"], "answer": "trees"}
    },
    {
        "lesson_id": 70, "category": "reading", "difficulty": "easy",
        "title": "Natural things",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "Rivers, trees and animals are part of nature.", "answer": True}
    },
    {
        "lesson_id": 70, "category": "vocabulary", "difficulty": "easy",
        "title": "Environment fill in",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "We must protect our ___.", "answer": "environment"}
        ]}
    },
    # Lesson 71 - Caring for Our Environment
    {
        "lesson_id": 71, "category": "reading", "difficulty": "easy",
        "title": "Protect nature",
        "instructions": "How can we protect the environment?",
        "content": {"type": "mcq", "question": "How can we protect the environment?", "options": ["burn rubbish","plant trees","cut all trees","throw rubbish in rivers"], "answer": "plant trees"}
    },
    {
        "lesson_id": 71, "category": "reading", "difficulty": "easy",
        "title": "Don't pollute",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "We should not throw rubbish in rivers.", "answer": True}
    },
    {
        "lesson_id": 71, "category": "vocabulary", "difficulty": "easy",
        "title": "Environment sentence",
        "instructions": "Put the words in order.",
        "content": {"type": "sentence_order", "words": ["our","protect","must","We","environment"], "answer": ["We","must","protect","our","environment"]}
    },
    # ── C2 Unit 5 - How We Travel ─────────────────────────────────────────────
    # Lesson 73 - Transport Vocabulary
    {
        "lesson_id": 73, "category": "vocabulary", "difficulty": "easy",
        "title": "Transport types",
        "instructions": "Choose the correct answer.",
        "content": {"type": "mcq", "question": "Which transport travels on water?", "options": ["bus","car","boat","bicycle"], "answer": "boat"}
    },
    {
        "lesson_id": 73, "category": "vocabulary", "difficulty": "easy",
        "title": "Match transport",
        "instructions": "Match each transport to where it travels.",
        "content": {"type": "match_pairs", "pairs": [
            {"left": "aeroplane", "right": "in the sky"},
            {"left": "boat", "right": "on water"},
            {"left": "train", "right": "on rails"},
            {"left": "car", "right": "on roads"}
        ]}
    },
    {
        "lesson_id": 73, "category": "vocabulary", "difficulty": "easy",
        "title": "Transport fill in",
        "instructions": "Fill in the missing word.",
        "content": {"type": "fill_in", "items": [
            {"prompt": "We travel by ___ to cross the sea.", "answer": "boat"},
            {"prompt": "An ___ flies in the sky.", "answer": "aeroplane"}
        ]}
    },
    # Lesson 74 - Road Safety
    {
        "lesson_id": 74, "category": "reading", "difficulty": "easy",
        "title": "Road safety rules",
        "instructions": "Choose the correct road safety rule.",
        "content": {"type": "mcq", "question": "What should you do before crossing the road?", "options": ["run fast","look left and right","close your eyes","play on the road"], "answer": "look left and right"}
    },
    {
        "lesson_id": 74, "category": "reading", "difficulty": "easy",
        "title": "Traffic lights",
        "instructions": "True or false?",
        "content": {"type": "true_false", "statement": "Red means stop at a traffic light.", "answer": True}
    },
    {
        "lesson_id": 74, "category": "vocabulary", "difficulty": "easy",
        "title": "Safety sentence",
        "instructions": "Put the words in order.",
        "content": {"type": "sentence_order", "words": ["crossing","before","Look","the","road"], "answer": ["Look","before","crossing","the","road"]}
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
print(f"OK {count} exercices English C1+C2 inseres")
