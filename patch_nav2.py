path = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Mobile NAV
content = content.replace(
    "  { id: 'profile' as Screen, icon: '&#128100;', label: t.nav_profile },\n  ]",
    "  { id: 'books' as Screen, icon: '&#128218;', label: t.nav_books },\n  { id: 'profile' as Screen, icon: '&#128100;', label: t.nav_profile },\n  ]"
)

# Desktop NAV
content = content.replace(
    "  { id: 'profile' as Screen, icon: '&#128100;', label: t.profile },\n  ]",
    "  { id: 'books' as Screen, icon: '&#128218;', label: t.nav_books },\n  { id: 'profile' as Screen, icon: '&#128100;', label: t.profile },\n  ]"
)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("OK - books in nav:", content.count("'books' as Screen"))
