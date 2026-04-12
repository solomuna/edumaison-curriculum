content = """# api/routes/books.py -- Livres physiques associes aux unites
from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session
from sqlalchemy import text
from database import get_db

router = APIRouter(prefix="/api", tags=["books"])


@router.get("/books")
def get_books(level_id: int = 0, db: Session = Depends(get_db)):
    sql = text(
        "SELECT br.id, br.book_name, br.page_from, br.page_to, br.chapter, br.notes, br.level,"
        " s.id as subject_id, s.name as subject_name, s.level_id,"
        " u.id as unit_id, u.name as unit_name"
        " FROM book_references br"
        " LEFT JOIN subjects s ON br.subject_id = s.id"
        " LEFT JOIN units u ON br.unit_id = u.id"
        " WHERE (:lvl = 0 OR s.level_id = :lvl)"
        " ORDER BY s.name, u.name"
    )
    rows = db.execute(sql, {"lvl": level_id}).fetchall()
    return [{"id": r.id, "book_name": r.book_name,
             "page_from": r.page_from, "page_to": r.page_to,
             "chapter": r.chapter, "notes": r.notes, "level": r.level,
             "subject_id": r.subject_id, "subject_name": r.subject_name,
             "unit_id": r.unit_id, "unit_name": r.unit_name,
             "level_id": r.level_id} for r in rows]


@router.post("/books")
def add_book(payload: dict, db: Session = Depends(get_db)):
    db.execute(text(
        "INSERT INTO book_references (book_name, page_from, page_to, chapter, subject_id, unit_id, level, notes)"
        " VALUES (:book_name, :page_from, :page_to, :chapter, :subject_id, :unit_id, :level, :notes)"
    ), {
        "book_name": payload.get("book_name", ""),
        "page_from": payload.get("page_from"),
        "page_to": payload.get("page_to"),
        "chapter": payload.get("chapter", ""),
        "subject_id": payload.get("subject_id"),
        "unit_id": payload.get("unit_id"),
        "level": payload.get("level", ""),
        "notes": payload.get("notes", ""),
    })
    db.commit()
    return {"success": True}


@router.delete("/books/{book_id}")
def delete_book(book_id: int, db: Session = Depends(get_db)):
    db.execute(text("DELETE FROM book_references WHERE id = :id"), {"id": book_id})
    db.commit()
    return {"success": True}
"""

with open(r"C:\\laragon\\www\\edumaison-api\\api\\routes\\books.py", "w", encoding="utf-8") as f:
    f.write(content)
print("OK")
