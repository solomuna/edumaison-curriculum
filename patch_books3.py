path = r"C:\laragon\www\edumaison-api\api\routes\books.py"
with open(path, "r", encoding="utf-8") as f:
    lines = f.readlines()

new_lines = []
for line in lines:
    line = line.replace("LEFT JOIN levels l ON br.level_id = l.id", "LEFT JOIN levels l ON s.level_id = l.id")
    line = line.replace("WHERE (:level_id = 0 OR br.level_id = :level_id)", "WHERE (:level_id = 0 OR s.level_id = :level_id)")
    new_lines.append(line)

with open(path, "w", encoding="utf-8") as f:
    f.writelines(new_lines)
print("OK")
