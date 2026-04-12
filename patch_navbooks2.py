path = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"
with open(path, "r", encoding="utf-8", errors="replace") as f:
    content = f.read()

content = content.replace(
    "nav_brief: 'R\u00e9sum\u00e9', nav_revision: 'R\u00e9vision', nav_duel: 'Duel', nav_profile: 'Profil',",
    "nav_brief: 'R\u00e9sum\u00e9', nav_revision: 'R\u00e9vision', nav_duel: 'Duel', nav_books: 'Livres', nav_profile: 'Profil',"
)
content = content.replace(
    "nav_brief: 'Summary', nav_revision: 'Revision', nav_duel: 'Duel', nav_profile: 'Profile',",
    "nav_brief: 'Summary', nav_revision: 'Revision', nav_duel: 'Duel', nav_books: 'Books', nav_profile: 'Profile',"
)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("OK")
