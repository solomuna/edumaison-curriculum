path = r"C:\laragon\www\edumaison-api\api\routes\books.py"
with open(path, "r", encoding="utf-8") as f:
    lines = f.readlines()

new_lines = []
for line in lines:
    line = line.replace(
        '"SELECT br.id, br.book_name, br.page, br.notes,"',
        '"SELECT br.id, br.book_name, br.page_from, br.page_to, br.chapter, br.notes,"'
    )
    line = line.replace(
        '" s.id as subject_id, s.name as subject_name,"',
        '" s.id as subject_id, s.name as subject_name,"'
    )
    line = line.replace(
        '" l.id as level_id, l.name as level_name"',
        '" s.level_id as level_id, br.level as level_name"'
    )
    line = line.replace(
        "LEFT JOIN levels l ON s.level_id = l.id",
        ""
    )
    line = line.replace(
        "WHERE (:level_id = 0 OR s.level_id = :level_id)",
        "WHERE (:level_id = 0 OR s.level_id = :level_id)"
    )
    line = line.replace(
        '"level_id": r.level_id, "level_name": r.level_name}',
        '"level_id": r.level_id, "level_name": r.level_name, "page_from": r.page_from, "page_to": r.page_to, "chapter": r.chapter}'
    )
    line = line.replace(
        '"page": r.page,',
        '"page_from": r.page_from, "page_to": r.page_to,'
    )
    line = line.replace(
        '"INSERT INTO book_references (book_name, page, subject_id, unit_id, level_id, notes)"',
        '"INSERT INTO book_references (book_name, page_from, page_to, chapter, subject_id, unit_id, level, notes)"'
    )
    line = line.replace(
        '" VALUES (:book_name, :page, :subject_id, :unit_id, :level_id, :notes)"',
        '" VALUES (:book_name, :page_from, :page_to, :chapter, :subject_id, :unit_id, :level, :notes)"'
    )
    line = line.replace(
        '"page": payload.get("page", ""),',
        '"page_from": payload.get("page_from"), "page_to": payload.get("page_to"), "chapter": payload.get("chapter", ""),'
    )
    line = line.replace(
        '"level_id": payload.get("level_id"),',
        '"level": payload.get("level", ""),'
    )
    new_lines.append(line)

with open(path, "w", encoding="utf-8") as f:
    f.writelines(new_lines)
print("OK")
