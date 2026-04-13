# fix_mama_desk.py
DESK = r"C:\laragon\www\edumaison\resources\react\src\DesktopApp.tsx"

with open(DESK, "r", encoding="utf-8") as f:
    lines = f.readlines()

# Afficher lignes 40-60 pour voir le probleme exact
for i, line in enumerate(lines[39:62], start=40):
    print(f"{i}: {line}", end="")
