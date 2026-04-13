# patch_mama_desk.py
import re

DESK = r"C:\laragon\www\edumaison\resources\react\src\DesktopApp.tsx"

with open(DESK, "r", encoding="utf-8") as f:
    c = f.read()

# Ajouter useState useEffect si absents
if "useState" not in c:
    c = c.replace(
        "import { MamaJudi }",
        "import { useState, useEffect } from 'react'\nimport { MamaJudi }"
    )
    print("OK imports react ajoutes")

# Trouver et remplacer la fonction MamaJudiDesk
# Elle commence a "function MamaJudiDesk()" et se termine au prochain "}"
pattern = r'function MamaJudiDesk\(\) \{[^}]*\}'
match = re.search(pattern, c, re.DOTALL)
if match:
    print("Trouvee :", match.group()[:100])
    new_fn = (
        "function MamaJudiDesk() {\n"
        "  const [src, setSrc] = useState<string | null>(null)\n"
        "  useEffect(() => {\n"
        "    fetch('/api/mama/profile').then(r => r.json()).then(d => {\n"
        "      if (d.avatar) setSrc('/storage/' + d.avatar)\n"
        "    }).catch(() => {})\n"
        "  }, [])\n"
        "  if (src) return (\n"
        "    <div style={{ width: 72, height: 72, borderRadius: '50%', overflow: 'hidden',\n"
        "      border: '3px solid rgba(255,255,255,.35)', flexShrink: 0 }}>\n"
        "      <img src={src} alt='Mama Judi' style={{ width: '100%', height: '100%', objectFit: 'cover' }} />\n"
        "    </div>\n"
        "  )\n"
        "  return (\n"
        + match.group().split("return (")[1].rstrip("}").rstrip() + "\n"
        "  )\n"
        "}"
    )
    c = c[:match.start()] + new_fn + c[match.end():]
    print("OK MamaJudiDesk patche")
else:
    print("ERREUR : fonction introuvable -- affichage brut :")
    idx = c.find("function MamaJudiDesk")
    print(c[idx:idx+300])

with open(DESK, "w", encoding="utf-8") as f:
    f.write(c)
print("Lance : npm run build")
