# patch_brief_vocal.py -- Brief vocal Mama Judi
# Fichier touche : MamaJudiApp.tsx uniquement (pur frontend, Web Speech API)

TSX = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"

with open(TSX, "r", encoding="utf-8") as f:
    c = f.read()

# ── 1. Traductions FR ─────────────────────────────────────────────────────────
c = c.replace(
    "    auto_section: 'R\u00e9vision automatique', auto_on: 'Activ\u00e9', auto_off: 'D\u00e9sactiv\u00e9', auto_time: 'Heure', auto_trigger_now: 'D\u00e9clencher maintenant', auto_triggered: 'Envoy\u00e9 !',",
    "    auto_section: 'R\u00e9vision automatique', auto_on: 'Activ\u00e9', auto_off: 'D\u00e9sactiv\u00e9', auto_time: 'Heure', auto_trigger_now: 'D\u00e9clencher maintenant', auto_triggered: 'Envoy\u00e9 !',"
    "\n    brief_vocal: 'Brief vocal', brief_stop: 'Arr\u00eater', brief_speaking: 'En cours...',"
)

# ── 2. Traductions EN ─────────────────────────────────────────────────────────
c = c.replace(
    "    auto_section: 'Auto Revision', auto_on: 'On', auto_off: 'Off', auto_time: 'Time', auto_trigger_now: 'Trigger now', auto_triggered: 'Sent!',",
    "    auto_section: 'Auto Revision', auto_on: 'On', auto_off: 'Off', auto_time: 'Time', auto_trigger_now: 'Trigger now', auto_triggered: 'Sent!',"
    "\n    brief_vocal: 'Voice Brief', brief_stop: 'Stop', brief_speaking: 'Speaking...',"
)

# ── 3. Fonction generateBriefText + mise a jour BriefScreen ──────────────────
brief_vocal_addition = (
    "\n// \u2500\u2500 BRIEF VOCAL \u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\n"
    "function generateBriefText(brief: Brief): string {\n"
    "  const hour = new Date().getHours()\n"
    "  const greet = hour < 12 ? 'Bonjour' : hour < 18 ? 'Bon apr\u00e8s-midi' : 'Bonsoir'\n"
    "  let text = greet + ' ! Voici le r\u00e9sum\u00e9 EduMaison du ' + brief.date + '. '\n"
    "  for (const ch of brief.children) {\n"
    "    if (ch.today_count === 0) {\n"
    "      text += ch.name + ' n\u2019a pas encore fait d\u2019exercices aujourd\u2019hui. '\n"
    "    } else {\n"
    "      const s = ch.today_count > 1 ? 's' : ''\n"
    "      text += ch.name + ' a fait ' + ch.today_count + ' exercice' + s + ' avec une moyenne de ' + ch.today_avg + ' pour cent. '\n"
    "    }\n"
    "    if (ch.weak_subjects.length > 0) {\n"
    "      const names = ch.weak_subjects.map(w => w.name).join(', ')\n"
    "      text += '\u00c0 renforcer pour ' + ch.name + ' : ' + names + '. '\n"
    "    }\n"
    "  }\n"
    "  const allActive = brief.children.every(ch => ch.today_count > 0)\n"
    "  if (allActive) text += 'Tous les enfants ont travaill\u00e9 aujourd\u2019hui, bravo !'\n"
    "  return text\n"
    "}\n"
)

c = c.replace(
    "// \u2500\u2500 BRIEF \u2500",
    brief_vocal_addition + "// \u2500\u2500 BRIEF \u2500"
)

# ── 4. BriefScreen : ajouter hooks + bouton vocal ────────────────────────────
old_brief_open = (
    "function BriefScreen({ brief, t }: { brief: Brief; t: typeof T['fr'] }) {\n"
    "  return (\n"
    "    <div>\n"
    "      <div style={{ fontSize: 13, fontWeight: 900, color: P.soft, textTransform: 'uppercase' as const, letterSpacing: 1, marginBottom: 14 }}>{t.brief}</div>"
)

new_brief_open = (
    "function BriefScreen({ brief, t }: { brief: Brief; t: typeof T['fr'] }) {\n"
    "  const [speaking, setSpeaking] = useState(false)\n"
    "\n"
    "  const startVocal = () => {\n"
    "    if (!('speechSynthesis' in window)) return\n"
    "    window.speechSynthesis.cancel()\n"
    "    const utter = new SpeechSynthesisUtterance(generateBriefText(brief))\n"
    "    utter.lang = 'fr-FR'\n"
    "    utter.rate = 0.88\n"
    "    utter.pitch = 1.05\n"
    "    utter.onend = () => setSpeaking(false)\n"
    "    utter.onerror = () => setSpeaking(false)\n"
    "    setSpeaking(true)\n"
    "    window.speechSynthesis.speak(utter)\n"
    "  }\n"
    "\n"
    "  const stopVocal = () => { window.speechSynthesis.cancel(); setSpeaking(false) }\n"
    "\n"
    "  return (\n"
    "    <div>\n"
    "      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 14 }}>\n"
    "        <div style={{ fontSize: 13, fontWeight: 900, color: P.soft, textTransform: 'uppercase' as const, letterSpacing: 1 }}>{t.brief}</div>\n"
    "        <button onClick={speaking ? stopVocal : startVocal}\n"
    "          style={{ padding: '6px 16px', borderRadius: 20, border: 'none', cursor: 'pointer',\n"
    "            background: speaking ? P.red : P.brown, color: 'white',\n"
    "            fontSize: 12, fontWeight: 800, fontFamily: 'Nunito, sans-serif',\n"
    "            display: 'flex', alignItems: 'center', gap: 6 }}>\n"
    "          <span>{speaking ? '\u23f9\ufe0f' : '\U0001f50a'}</span>\n"
    "          <span>{speaking ? t.brief_stop : t.brief_vocal}</span>\n"
    "        </button>\n"
    "      </div>"
)

if old_brief_open in c:
    c = c.replace(old_brief_open, new_brief_open)
    print("OK BriefScreen patche")
else:
    print("ERREUR : ancre BriefScreen introuvable")

with open(TSX, "w", encoding="utf-8") as f:
    f.write(c)
print("OK MamaJudiApp.tsx")
print("Lance : npm run build")
