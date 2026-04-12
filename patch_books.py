path = r"C:\laragon\www\edumaison-api\api\routes\books.py"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

new_endpoints = (
    "\n\n@router.get(\"/books\")\n"
    "def get_books(level_id: int = 0, db: Session = Depends(get_db)):\n"
    "    sql = text(\n"
    "        \"SELECT br.id, br.book_name, br.page, br.notes,\"\n"
    "        \" s.id as subject_id, s.name as subject_name,\"\n"
    "        \" u.id as unit_id, u.name as unit_name,\"\n"
    "        \" l.id as level_id, l.name as level_name\"\n"
    "        \" FROM book_references br\"\n"
    "        \" LEFT JOIN subjects s ON br.subject_id = s.id\"\n"
    "        \" LEFT JOIN units u ON br.unit_id = u.id\"\n"
    "        \" LEFT JOIN levels l ON br.level_id = l.id\"\n"
    "        \" WHERE (:level_id = 0 OR br.level_id = :level_id)\"\n"
    "        \" ORDER BY l.name, s.name, u.name\"\n"
    "    )\n"
    "    rows = db.execute(sql, {\"level_id\": level_id}).fetchall()\n"
    "    return [{\"id\": r.id, \"book_name\": r.book_name, \"page\": r.page, \"notes\": r.notes,\n"
    "             \"subject_id\": r.subject_id, \"subject_name\": r.subject_name,\n"
    "             \"unit_id\": r.unit_id, \"unit_name\": r.unit_name,\n"
    "             \"level_id\": r.level_id, \"level_name\": r.level_name} for r in rows]\n"
    "\n\n@router.post(\"/books\")\n"
    "def add_book(payload: dict, db: Session = Depends(get_db)):\n"
    "    db.execute(text(\n"
    "        \"INSERT INTO book_references (book_name, page, subject_id, unit_id, level_id, notes)\"\n"
    "        \" VALUES (:book_name, :page, :subject_id, :unit_id, :level_id, :notes)\"\n"
    "    ), {\n"
    "        \"book_name\": payload.get(\"book_name\", \"\"),\n"
    "        \"page\": payload.get(\"page\", \"\"),\n"
    "        \"subject_id\": payload.get(\"subject_id\"),\n"
    "        \"unit_id\": payload.get(\"unit_id\"),\n"
    "        \"level_id\": payload.get(\"level_id\"),\n"
    "        \"notes\": payload.get(\"notes\", \"\"),\n"
    "    })\n"
    "    db.commit()\n"
    "    return {\"success\": True}\n"
    "\n\n@router.delete(\"/books/{book_id}\")\n"
    "def delete_book(book_id: int, db: Session = Depends(get_db)):\n"
    "    db.execute(text(\"DELETE FROM book_references WHERE id = :id\"), {\"id\": book_id})\n"
    "    db.commit()\n"
    "    return {\"success\": True}\n"
)

content = content.rstrip() + new_endpoints
with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("OK")
