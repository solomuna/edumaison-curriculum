path = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

content = content.replace(
    "fetch('/api/mama/subjects/0').then(r => r.json()).then(setSubjects).catch(() => {})",
    "fetch('/api/mama/subjects/5').then(r => r.json()).then(setSubjects).catch(() => {})"
)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("OK")
