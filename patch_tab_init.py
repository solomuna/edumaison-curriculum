# patch_tab_init.py
HOME = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildHome.tsx"

with open(HOME, "r", encoding="utf-8") as f:
    c = f.read()

old = "  const [tab, setTab] = useState<Tab>('home')"

new = (
    "  const [tab, setTab] = useState<Tab>(() => {\n"
    "    const saved = localStorage.getItem('edumaison_tab_' + child.id)\n"
    "    const valid = ['home', 'subjects', 'progress', 'profile', 'bulletin']\n"
    "    return (saved && valid.includes(saved) ? saved : 'home') as Tab\n"
    "  })"
)

if old in c:
    c = c.replace(old, new)
    print("OK tab init localStorage")
else:
    print("ERREUR : ancre introuvable")

with open(HOME, "w", encoding="utf-8") as f:
    f.write(c)
