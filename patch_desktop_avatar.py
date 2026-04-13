# patch_desktop_avatar.py
DESK = r"C:\laragon\www\edumaison\resources\react\src\DesktopApp.tsx"

with open(DESK, "r", encoding="utf-8") as f:
    c = f.read()

# 1. Remplacer MamaJudiDesk dans la sidebar par avatar enfant
old_sidebar_top = (
    "          <div style={{ fontSize: 13, fontWeight: 900, color: 'rgba(255,255,255,0.6)', letterSpacing: '2px', marginBottom: 12 }}>EDUMAISON</div>\n"
    "          <MamaJudiDesk />\n"
    "          <div style={{ fontSize: 20, fontWeight: 900, color: 'white', marginTop: 10 }}>{firstName}</div>"
)

new_sidebar_top = (
    "          <div style={{ fontSize: 13, fontWeight: 900, color: 'rgba(255,255,255,0.6)', letterSpacing: '2px', marginBottom: 12 }}>EDUMAISON</div>\n"
    "          {(child as any).avatar\n"
    "            ? <img src={(child as any).avatar.startsWith('http') ? (child as any).avatar : '/storage/' + (child as any).avatar}\n"
    "                style={{ width: 72, height: 72, borderRadius: '50%', objectFit: 'cover',\n"
    "                  border: '3px solid rgba(255,255,255,.35)', flexShrink: 0 }} />\n"
    "            : <div style={{ width: 72, height: 72, borderRadius: '50%', background: 'rgba(255,255,255,0.2)',\n"
    "                display: 'flex', alignItems: 'center', justifyContent: 'center',\n"
    "                fontSize: 32, fontWeight: 900, color: 'white', flexShrink: 0 }}>\n"
    "                {firstName[0]}\n"
    "              </div>\n"
    "          }\n"
    "          <div style={{ fontSize: 20, fontWeight: 900, color: 'white', marginTop: 10 }}>{firstName}</div>"
)

if old_sidebar_top in c:
    c = c.replace(old_sidebar_top, new_sidebar_top)
    print("OK avatar enfant sidebar")
else:
    print("ERREUR : ancre sidebar top introuvable")

# 2. Ajouter bouton Mama Judi discret dans le footer de la sidebar, avant Switch child
old_footer = (
    "          <button onClick={onLogout} style={{\n"
    "            width: '100%', padding: '10px 0', borderRadius: 12,\n"
    "            border: '1.5px solid rgba(255,255,255,0.3)',\n"
    "            background: 'transparent', color: 'rgba(255,255,255,0.8)',\n"
    "            fontSize: 13, fontWeight: 800, cursor: 'pointer',\n"
    "            fontFamily: 'Nunito, system-ui, sans-serif'\n"
    "          }}>\n"
    "            \u21c4 Switch child\n"
    "          </button>"
)

new_footer = (
    "          <button onClick={() => window.location.href = '/mama'}\n"
    "            style={{ width: '100%', padding: '10px 0', borderRadius: 12, border: 'none',\n"
    "              background: 'rgba(255,255,255,0.1)', color: 'rgba(255,255,255,0.75)',\n"
    "              fontSize: 13, fontWeight: 800, cursor: 'pointer', marginBottom: 8,\n"
    "              fontFamily: 'Nunito, system-ui, sans-serif',\n"
    "              display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 }}>\n"
    "            <MamaJudiDesk />\n"
    "            Mama Judi\n"
    "          </button>\n"
    "          <button onClick={onLogout} style={{\n"
    "            width: '100%', padding: '10px 0', borderRadius: 12,\n"
    "            border: '1.5px solid rgba(255,255,255,0.3)',\n"
    "            background: 'transparent', color: 'rgba(255,255,255,0.8)',\n"
    "            fontSize: 13, fontWeight: 800, cursor: 'pointer',\n"
    "            fontFamily: 'Nunito, system-ui, sans-serif'\n"
    "          }}>\n"
    "            \u21c4 Switch child\n"
    "          </button>"
)

if old_footer in c:
    c = c.replace(old_footer, new_footer)
    print("OK bouton Mama Judi footer sidebar")
else:
    print("ERREUR : ancre footer introuvable")

# 3. Reduire MamaJudiDesk a 32px dans le bouton footer
c = c.replace(
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
    "  )",
    "function MamaJudiDesk() {\n"
    "  const [src, setSrc] = useState<string | null>(null)\n"
    "  useEffect(() => {\n"
    "    fetch('/api/mama/profile').then(r => r.json()).then(d => {\n"
    "      if (d.avatar) setSrc('/storage/' + d.avatar)\n"
    "    }).catch(() => {})\n"
    "  }, [])\n"
    "  if (src) return (\n"
    "    <div style={{ width: 32, height: 32, borderRadius: '50%', overflow: 'hidden',\n"
    "      border: '2px solid rgba(255,255,255,.35)', flexShrink: 0 }}>\n"
    "      <img src={src} alt='Mama Judi' style={{ width: '100%', height: '100%', objectFit: 'cover' }} />\n"
    "    </div>\n"
    "  )"
)
print("OK MamaJudiDesk reduite a 32px")

with open(DESK, "w", encoding="utf-8") as f:
    f.write(c)
print("Lance : npm run build")
