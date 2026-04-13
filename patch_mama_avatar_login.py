# patch_mama_avatar_login.py
LOGIN = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildLogin.tsx"

with open(LOGIN, "r", encoding="utf-8") as f:
    c = f.read()

# 1. Ajouter import useEffect si pas deja present (useState deja la)
if "useEffect" not in c:
    c = c.replace(
        "import { useState",
        "import { useState, useEffect"
    )

# 2. Remplacer la fonction MamaJudiHead par MamaJudiHead avec fetch photo
old_head = (
    "function MamaJudiHead({ size = 90 }: { size?: number }) {\n"
    "  return (\n"
    "    <svg viewBox=\"0 0 84 84\" width={size} height={size} xmlns=\"http://www.w3.org/2000/svg\">\n"
    "      <circle cx=\"42\" cy=\"42\" r=\"42\" fill=\"#C8874A\"/>\n"
    "      <circle cx=\"42\" cy=\"38\" r=\"22\" fill=\"#A06830\"/>\n"
    "      <circle cx=\"33\" cy=\"34\" r=\"4\" fill=\"#1A0A00\"/>\n"
    "      <circle cx=\"51\" cy=\"34\" r=\"4\" fill=\"#1A0A00\"/>\n"
    "      <circle cx=\"34.5\" cy=\"32.5\" r=\"1.5\" fill=\"white\"/>\n"
    "      <circle cx=\"52.5\" cy=\"32.5\" r=\"1.5\" fill=\"white\"/>\n"
    "      <ellipse cx=\"42\" cy=\"42\" rx=\"2.5\" ry=\"2\" fill=\"#8A5520\"/>\n"
    "      <path d=\"M30 48 Q42 58 54 48\" stroke=\"#1A0A00\" strokeWidth=\"2.2\" fill=\"none\" strokeLinecap=\"round\"/>\n"
    "      <rect x=\"6\" y=\"0\" width=\"72\" height=\"28\" rx=\"36\" fill=\"#2A1500\"/>\n"
    "      <ellipse cx=\"42\" cy=\"56\" rx=\"7\" ry=\"8\" fill=\"#C8874A\"/>\n"
    "      <path d=\"M10 84 Q8 68 42 62 Q76 68 74 84 Z\" fill=\"#FF8FAB\"/>\n"
    "    </svg>\n"
    "  )\n"
    "}"
)

new_head = (
    "function MamaJudiHead({ size = 90 }: { size?: number }) {\n"
    "  const [src, setSrc] = useState<string | null>(null)\n"
    "  useEffect(() => {\n"
    "    fetch('/api/mama/profile').then(r => r.json()).then(d => {\n"
    "      if (d.avatar) setSrc('http://192.168.100.106/storage/' + d.avatar)\n"
    "    }).catch(() => {})\n"
    "  }, [])\n"
    "  if (src) return (\n"
    "    <div style={{ width: size, height: size, borderRadius: '50%', overflow: 'hidden',\n"
    "      border: '3px solid #1D6B2A', flexShrink: 0 }}>\n"
    "      <img src={src} alt=\"Mama Judi\" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />\n"
    "    </div>\n"
    "  )\n"
    "  return (\n"
    "    <svg viewBox=\"0 0 84 84\" width={size} height={size} xmlns=\"http://www.w3.org/2000/svg\">\n"
    "      <circle cx=\"42\" cy=\"42\" r=\"42\" fill=\"#C8874A\"/>\n"
    "      <circle cx=\"42\" cy=\"38\" r=\"22\" fill=\"#A06830\"/>\n"
    "      <circle cx=\"33\" cy=\"34\" r=\"4\" fill=\"#1A0A00\"/>\n"
    "      <circle cx=\"51\" cy=\"34\" r=\"4\" fill=\"#1A0A00\"/>\n"
    "      <circle cx=\"34.5\" cy=\"32.5\" r=\"1.5\" fill=\"white\"/>\n"
    "      <circle cx=\"52.5\" cy=\"32.5\" r=\"1.5\" fill=\"white\"/>\n"
    "      <ellipse cx=\"42\" cy=\"42\" rx=\"2.5\" ry=\"2\" fill=\"#8A5520\"/>\n"
    "      <path d=\"M30 48 Q42 58 54 48\" stroke=\"#1A0A00\" strokeWidth=\"2.2\" fill=\"none\" strokeLinecap=\"round\"/>\n"
    "      <rect x=\"6\" y=\"0\" width=\"72\" height=\"28\" rx=\"36\" fill=\"#2A1500\"/>\n"
    "      <ellipse cx=\"42\" cy=\"56\" rx=\"7\" ry=\"8\" fill=\"#C8874A\"/>\n"
    "      <path d=\"M10 84 Q8 68 42 62 Q76 68 74 84 Z\" fill=\"#FF8FAB\"/>\n"
    "    </svg>\n"
    "  )\n"
    "}"
)

if old_head in c:
    c = c.replace(old_head, new_head)
    print("OK MamaJudiHead avec photo")
else:
    print("ERREUR : ancre MamaJudiHead introuvable")

with open(LOGIN, "w", encoding="utf-8") as f:
    f.write(c)
print("Lance : npm run build")
