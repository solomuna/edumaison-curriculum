path = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

content = content.replace(
    "{ id: 'books' as Screen, icon: '\U0001f4da', label: t.nav_books }",
    "{ id: 'books' as Screen, icon: '\U0001f4d6', label: t.nav_books }"
)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("OK")
