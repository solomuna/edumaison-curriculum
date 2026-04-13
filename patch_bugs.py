# patch_bugs.py -- Fix global bugs
import os

BASE = r"C:\laragon\www\edumaison\resources\react\src\pages\child\exercises"
PLAYER = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ExercisePlayer.tsx"

# ─────────────────────────────────────────────────────────────────────────────
#  1. OralDrill.tsx -- HTML entities dans JSX -> unicode
# ─────────────────────────────────────────────────────────────────────────────
oral = os.path.join(BASE, "OralDrill.tsx")
with open(oral, "r", encoding="utf-8") as f:
    c = f.read()

# Fix Next &#8594; et Finish &#10003;
c = c.replace(
    "Next &#8594;",
    "Next \u2192"
)
c = c.replace(
    "Finish &#10003;",
    "Finish \u2713"
)
# Fix emojis dans done screen
c = c.replace("'&#11088;'", "'\u2b50'")
c = c.replace("'&#128077;'", "'\U0001f44d'")

with open(oral, "w", encoding="utf-8") as f:
    f.write(c)
print("OK OralDrill.tsx -- HTML entities fixes")

# ─────────────────────────────────────────────────────────────────────────────
#  2. Geometry.tsx -- feedback FR -> EN + fix draw (skip si pas d'options)
# ─────────────────────────────────────────────────────────────────────────────
geo = os.path.join(BASE, "Geometry.tsx")
with open(geo, "r", encoding="utf-8") as f:
    c = f.read()

# Feedback FR -> EN
c = c.replace(
    "sel === ans ? '\U0001f389 Bravo ! Bonne r\u00e9ponse !' : `La bonne r\u00e9ponse est : ${opts[ans]}`",
    "sel === ans ? '\U0001f389 Correct! Well done!' : `The correct answer is: ${opts[ans]}`"
)

# Fix draw_line -- si pas d'options, appeler onComplete directement
old_check = (
    "  const check = () => {\n"
    "    if (sel === null) return\n"
    "    setChecked(true)\n"
    "    setTimeout(() => onComplete(sel === ans), 1200)\n"
    "  }"
)

new_check = (
    "  const check = () => {\n"
    "    if (sel === null) return\n"
    "    setChecked(true)\n"
    "    setTimeout(() => onComplete(sel === ans), 1200)\n"
    "  }\n"
    "\n"
    "  // Si pas d'options (ex: draw exercise) -- skip automatique\n"
    "  if (opts.length === 0) {\n"
    "    return (\n"
    "      <div>\n"
    "        {question && <p style={{ fontSize: 14, fontWeight: 800, color: '#2D1B0E', marginBottom: 14, textAlign: 'center' }}>{question}</p>}\n"
    "        <div style={{ background: illBg, borderRadius: 20, padding: '16px 12px', marginBottom: 14, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>\n"
    "          <GeometrySVG content={content} />\n"
    "        </div>\n"
    "        <button onClick={() => onComplete(true)} style={{ width: '100%', padding: '13px 0', borderRadius: 16, border: 'none', background: '#8B5CF6', color: 'white', fontSize: 15, fontWeight: 800, cursor: 'pointer' }}>\n"
    "          Next \u2192\n"
    "        </button>\n"
    "      </div>\n"
    "    )\n"
    "  }"
)

if old_check in c:
    c = c.replace(old_check, new_check)
    print("OK Geometry.tsx -- draw fix + feedback EN")
else:
    print("ERREUR : ancre check Geometry introuvable")

with open(geo, "w", encoding="utf-8") as f:
    f.write(c)

# ─────────────────────────────────────────────────────────────────────────────
#  3. MCQ.tsx -- feedback FR -> EN
# ─────────────────────────────────────────────────────────────────────────────
mcq = os.path.join(BASE, "MCQ.tsx")
with open(mcq, "r", encoding="utf-8") as f:
    c = f.read()

c = c.replace(
    "'Correct ! Bonne r\u00e9ponse !'",
    "'Correct! Well done!'"
)
c = c.replace(
    "`La bonne r\u00e9ponse est : ${shuffledQ.options[shuffledQ.answerIndex]}`",
    "`The correct answer is: ${shuffledQ.options[shuffledQ.answerIndex]}`"
)
c = c.replace(
    "? 'Correct ! ' : 'Presque ! ') + (q as any).explanation",
    "? 'Correct! ' : 'Almost! ') + (q as any).explanation"
)
# Session complete messages FR -> EN
c = c.replace(
    "'Excellent travail ! Continue comme \u00e7a, ma belle.'",
    "'Excellent work! Keep it up!'"
)
c = c.replace(
    "'Bonne tentative, Belle m\u00e8re. Les corrections reviendront en r\u00e9vision SRS.'",
    "'Good try! Review your mistakes and try again.'"
)
c = c.replace(
    "'Ne te d\u00e9courage pas ! Revois le cours et r\u00e9essaie.'",
    "'Don\\'t give up! Review the lesson and try again.'"
)
# Summary labels
c = c.replace("'Ma\u00eetris\u00e9'", "'Correct'")
c = c.replace("'\u00c0 revoir'", "'Review'")
# Back button
c = c.replace(
    "'\u2190 Back au tableau de bord'",
    "'\u2190 Back to dashboard'"
)

with open(mcq, "w", encoding="utf-8") as f:
    f.write(c)
print("OK MCQ.tsx -- feedback EN")

# ─────────────────────────────────────────────────────────────────────────────
#  4. ExercisePlayer.tsx -- TTS auto au chargement (re-trigger sur exercise.id)
# ─────────────────────────────────────────────────────────────────────────────
with open(PLAYER, "r", encoding="utf-8") as f:
    c = f.read()

# Le useEffect TTS existe deja mais ne se re-declenche peut-etre pas
# On s'assure que le delai est suffisant et que le texte est bien lu
old_tts = (
    "  useEffect(() => {\n"
    "    if (type === 'oral_drill') return\n"
    "    const text = exercise.instructions || exercise.title\n"
    "    if (!text) return\n"
    "    const lang = isFrench ? 'fr-FR' : 'en-GB'\n"
    "    setTimeout(() => MamaJudi.speakLang(text, lang), 500)\n"
    "  }, [exercise.id])"
)

new_tts = (
    "  useEffect(() => {\n"
    "    if (type === 'oral_drill') return\n"
    "    const text = exercise.instructions || exercise.title\n"
    "    if (!text) return\n"
    "    const lang = isFrench ? 'fr-FR' : 'en-GB'\n"
    "    // Annuler tout TTS en cours avant de lire\n"
    "    if ('speechSynthesis' in window) window.speechSynthesis.cancel()\n"
    "    setTimeout(() => MamaJudi.speakLang(text, lang), 800)\n"
    "  }, [exercise.id])"
)

if old_tts in c:
    c = c.replace(old_tts, new_tts)
    print("OK ExercisePlayer.tsx -- TTS fix")
else:
    print("ERREUR : ancre TTS introuvable")

with open(PLAYER, "w", encoding="utf-8") as f:
    f.write(c)

print("\nTous les patches appliques. Lance : npm run build")
