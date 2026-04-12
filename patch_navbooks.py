path = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

content = content.replace(
    "    nav_profile: 'Profil',",
    "    nav_books: 'Livres',\n    nav_profile: 'Profil',"
)
content = content.replace(
    "    nav_profile: 'Profile',",
    "    nav_books: 'Books',\n    nav_profile: 'Profile',"
)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("OK")
