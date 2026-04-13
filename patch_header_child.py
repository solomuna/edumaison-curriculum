# patch_header_child.py
HOME = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildHome.tsx"

with open(HOME, "r", encoding="utf-8") as f:
    c = f.read()

# 1. Remplacer le bouton PERSON par avatar enfant dans le header
old_person_btn = (
    "              <button onClick={() => setTab('profile')} style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 0 }}>\n"
    "                <div style={{ width: 32, height: 32, borderRadius: 9, background: 'rgba(255,255,255,0.2)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 16 }}>\n"
    "                  {PERSON}\n"
    "                </div>\n"
    "              </button>"
)

new_person_btn = (
    "              <button onClick={() => setTab('profile')} style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 0 }}>\n"
    "                {(child as any).avatar\n"
    "                  ? <img src={(child as any).avatar.startsWith('http') ? (child as any).avatar : '/storage/' + (child as any).avatar}\n"
    "                      style={{ width: 36, height: 36, borderRadius: '50%', objectFit: 'cover', border: '2px solid rgba(255,255,255,.5)' }} />\n"
    "                  : <div style={{ width: 36, height: 36, borderRadius: '50%', background: 'rgba(255,255,255,0.25)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 16, fontWeight: 900, color: 'white' }}>\n"
    "                      {firstName[0]}\n"
    "                    </div>\n"
    "                }\n"
    "              </button>"
)

if old_person_btn in c:
    c = c.replace(old_person_btn, new_person_btn)
    print("OK avatar enfant header")
else:
    print("ERREUR : ancre PERSON introuvable")

# 2. Ajouter bouton Mama Judi a cote du logout
old_logout = (
    "                <button onClick={onLogout} style={{ background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: 10, padding: '4px 10px', color: 'white', fontSize: 12, fontWeight: 800, cursor: 'pointer' }}>\n"
    "                  '⇄'\n"
    "                </button>"
)

new_logout = (
    "                <button onClick={() => window.location.href = '/mama'}\n"
    "                  style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 0 }}>\n"
    "                  <MamaJudiSmall />\n"
    "                </button>\n"
    "                <button onClick={onLogout} style={{ background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: 10, padding: '4px 10px', color: 'white', fontSize: 12, fontWeight: 800, cursor: 'pointer' }}>\n"
    "                  '\u21c4'\n"
    "                </button>"
)

if old_logout in c:
    c = c.replace(old_logout, new_logout)
    print("OK bouton Mama Judi header")
else:
    print("ERREUR : ancre logout introuvable")

# 3. Remplacer MamaJudiSmall dans le bloc message par avatar enfant + garder message
old_judi_block = (
    "            {/* Mama Judi */}\n"
    "            <div style={{ display: 'flex', gap: 12, alignItems: 'flex-start', marginBottom: 16 }}>\n"
    "              <MamaJudiSmall />\n"
    "              <div style={{ background: 'var(--card)', borderRadius: 16, borderTopLeftRadius: 4, padding: '12px 14px', flex: 1, border: '2px solid #1D6B2A' }}>\n"
    "                <div style={{ fontSize: 14, fontWeight: 700, color: 'var(--text-dark)', lineHeight: 1.4 }}>{judiMsg}</div>\n"
    "                <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 4 }}>{SPEAKER} tap to hear again</div>\n"
    "              </div>\n"
    "            </div>"
)

new_judi_block = (
    "            {/* Mama Judi message avec avatar enfant */}\n"
    "            <div style={{ display: 'flex', gap: 12, alignItems: 'flex-start', marginBottom: 16 }}>\n"
    "              {(child as any).avatar\n"
    "                ? <img src={(child as any).avatar.startsWith('http') ? (child as any).avatar : '/storage/' + (child as any).avatar}\n"
    "                    style={{ width: 48, height: 48, borderRadius: '50%', objectFit: 'cover', border: '3px solid #1D6B2A', flexShrink: 0 }} />\n"
    "                : <div style={{ width: 48, height: 48, borderRadius: '50%', background: '#1D6B2A', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 22, fontWeight: 900, color: 'white', flexShrink: 0 }}>\n"
    "                    {firstName[0]}\n"
    "                  </div>\n"
    "              }\n"
    "              <div style={{ background: 'var(--card)', borderRadius: 16, borderTopLeftRadius: 4, padding: '12px 14px', flex: 1, border: '2px solid #1D6B2A' }}>\n"
    "                <div style={{ fontSize: 14, fontWeight: 700, color: 'var(--text-dark)', lineHeight: 1.4 }}>{judiMsg}</div>\n"
    "                <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 4 }}>{SPEAKER} tap to hear again</div>\n"
    "              </div>\n"
    "            </div>"
)

if old_judi_block in c:
    c = c.replace(old_judi_block, new_judi_block)
    print("OK bloc message avatar enfant")
else:
    print("ERREUR : ancre bloc Mama Judi introuvable")

# 4. MamaJudiSmall doit etre rendu en petit dans le header -- ajuster la taille
# Le composant MamaJudiSmall affiche 52px -- trop grand pour le header
# On l'enveloppe dans un div 36x36
old_mama_small_ref = (
    "                <button onClick={() => window.location.href = '/mama'}\n"
    "                  style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 0 }}>\n"
    "                  <MamaJudiSmall />\n"
    "                </button>"
)

new_mama_small_ref = (
    "                <button onClick={() => window.location.href = '/mama'}\n"
    "                  style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 0,\n"
    "                    width: 36, height: 36, borderRadius: '50%', overflow: 'hidden',\n"
    "                    border: '2px solid rgba(255,255,255,.4)', flexShrink: 0 }}>\n"
    "                  <MamaJudiSmall />\n"
    "                </button>"
)

if old_mama_small_ref in c:
    c = c.replace(old_mama_small_ref, new_mama_small_ref)
    print("OK taille Mama Judi header")

with open(HOME, "w", encoding="utf-8") as f:
    f.write(c)
print("Lance : npm run build")
