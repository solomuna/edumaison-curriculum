# fix_rank.py
HOME = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildHome.tsx"

with open(HOME, "r", encoding="utf-8") as f:
    lines = f.readlines()

for i, line in enumerate(lines):
    if "medals[i] ||" in line:
        print(f"L{i+1}: {line.rstrip()}")
        lines[i] = line.replace(
            "medals[i] || String(i+1)",
            "medals[i] || ''"
        )
        print(f"-> {lines[i].rstrip()}")
        break

with open(HOME, "w", encoding="utf-8") as f:
    f.writelines(lines)
print("OK")
