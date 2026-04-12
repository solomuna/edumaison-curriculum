path = r"C:\laragon\www\edumaison-api\api\routes\books.py"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Remplacer tout le endpoint get_books
import re
old = re.search(r"@router\.get\(\"/books\"\).*?return \[.*?\] for r in rows\]", content, re.DOTALL)
if old:
    content = content[:old.start()] + content[old.end():]

new_endpoint = """
@router.get("/books")
def get_books(level_id: int = 0, db: Session = Depends(get_db)):
    sql = text(
        "SELECT br.id, br.book_name, br.page_from, br.page_to, br.chapter, br.notes,"
        " br.level, s.id as subject_id, s.name as subject_name,"
        " u.id as unit_id, u.name as unit_name, s.level_id"
        " FROM book_references br"
        " LEFT JOIN subjects s ON br.subject_id = s.id"
        " LEFT JOIN units u ON br.unit_id = u.id"
        " WHERE (:level_id = 0 OR s.level_id = :level_id)"
        " ORDER BY s.name, u.name"
    )
    rows = db.execute(sql, {"level_id": level_id}).fetchall()
    return [{"id": r.id, "book_name": r.book_name,
             "page_from": r.page_from, "page_to": r.page_to,
             "chapter": r.chapter, "notes": r.notes, "level": r.level,
             "subject_id": r.subject_id, "subject_name": r.subject_name,
             "unit_id": r.unit_id, "unit_name": r.unit_name,
             "level_id": r.level_id} for r in rows]

"""

# Inserer avant @router.post
content = content.replace("@router.post(\"/books\")", new_endpoint + "@router.post(\"/books\")", 1)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("OK")
