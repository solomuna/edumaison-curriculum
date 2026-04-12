path = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Fix titre desktop
content = content.replace(
    "const screenTitle = NAV.find(n => n.id === screen)?.label || t.title",
    "const screenTitle = screen === 'books' ? t.nav_books : NAV.find(n => n.id === screen)?.label || t.title"
)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("OK")
