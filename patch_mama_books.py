path = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Ajouter books au type Screen
content = content.replace(
    "type Screen = 'home' | 'revision' | 'duel' | 'profile'",
    "type Screen = 'home' | 'revision' | 'duel' | 'profile' | 'books'"
)

# Ajouter interface BookRef + BooksScreen avant ScreenContent
books_code = open(r"C:\Users\Kamgang David\Desktop\BooksScreen_fragment.tsx", "r", encoding="utf-8").read()
content = content.replace(
    "// -- CONTENU PARTAGE",
    books_code + "\n// -- CONTENU PARTAGE"
)

# Ajouter books dans ScreenContent
content = content.replace(
    "  if (screen === 'profile') return <ProfileScreen",
    "  if (screen === 'books') return <BooksScreen t={t} />\n  if (screen === 'profile') return <ProfileScreen"
)

# Ajouter nav books dans mobile et desktop
content = content.replace(
    "  { id: 'profile' as Screen, icon: '&#128100;', label: t.nav_profile },",
    "  { id: 'books' as Screen, icon: '&#128218;', label: t.nav_books },\n  { id: 'profile' as Screen, icon: '&#128100;', label: t.nav_profile },"
)

# Ajouter traductions
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
