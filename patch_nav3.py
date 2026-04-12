path = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Mobile NAV - ligne 438
content = content.replace(
    "    { id: 'profile' as Screen, icon: '\U0001f464', label: t.nav_profile },",
    "    { id: 'books' as Screen, icon: '\U0001f4da', label: t.nav_books },\n    { id: 'profile' as Screen, icon: '\U0001f464', label: t.nav_profile },"
)

# Desktop NAV - ligne 505
content = content.replace(
    "    { id: 'profile' as Screen, icon: '\U0001f464', label: t.profile }",
    "    { id: 'books' as Screen, icon: '\U0001f4da', label: t.nav_books },\n    { id: 'profile' as Screen, icon: '\U0001f464', label: t.profile }"
)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("OK - books count:", content.count("'books' as Screen"))
