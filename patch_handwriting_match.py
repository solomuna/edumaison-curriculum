# patch_handwriting_match.py
import os

BASE   = r"C:\laragon\www\edumaison\resources\react\src\pages\child\exercises"
PLAYER = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ExercisePlayer.tsx"

# ─────────────────────────────────────────────────────────────────────────────
#  1. Handwriting.tsx -- adaptateur word/letter -> prompts[] + FR->EN
# ─────────────────────────────────────────────────────────────────────────────
hw = os.path.join(BASE, "Handwriting.tsx")
with open(hw, "r", encoding="utf-8") as f:
    c = f.read()

# FR -> EN labels
c = c.replace("'\u2190 Retour'", "'Back'")
c = c.replace("'\u00c0 \u00e9crire'", "'TO WRITE'")
c = c.replace("'\u00c9couter'", "'Listen'")
c = c.replace("'Effacer'", "'Clear'")
c = c.replace("'Suivant \u2192'", "'Next \u2192'")
c = c.replace("'Terminer \u2713'", "'Finish \u2713'")
c = c.replace("'\u2705 Trace les lettres avec ton doigt ou ta souris !'",
              "'\u2705 Trace the letters with your finger or mouse!'")
c = c.replace("'Tr\u00e8s bien \u00e9crit !'", "'Well written!'")
c = c.replace("'Retour aux activit\u00e9s'", "'Back to activities'")

# Encouragements FR -> EN
c = c.replace(
    "'Excellent ! Tes lettres sont tres belles !'",
    "'Excellent! Your letters look great!'"
)
c = c.replace(
    "'Bravo ! Tu as bien travaille ton ecriture !'",
    "'Well done! Great handwriting practice!'"
)
c = c.replace(
    "'Magnifique ! Continue comme ca !'",
    "'Wonderful! Keep it up!'"
)
c = c.replace(
    "'Tres bien ! Je suis fiere de toi !'",
    "'Very good! I am proud of you!'"
)
c = c.replace(
    "'Pas grave ! Efface et recommence, tu peux le faire !'",
    "'No worries! Erase and try again, you can do it!'"
)
c = c.replace(
    "'Courage ! Prends ton temps et trace bien les lettres.'",
    "'Take your time and trace the letters carefully.'"
)
c = c.replace(
    "'Essaie encore ! Je crois en toi !'",
    "'Try again! I believe in you!'"
)

# Adaptateur word/letter -> prompts[]
old_prompts = "  const prompts = content.prompts"
new_prompts = (
    "  // Adaptateur : word/letter -> prompts[]\n"
    "  const rawPrompts = content.prompts\n"
    "    || (content.word ? [content.word] : null)\n"
    "    || (content.letter ? [content.letter + ' - ' + (content.word || '')] : null)\n"
    "    || ['Write here']\n"
    "  const prompts: string[] = rawPrompts"
)

if old_prompts in c:
    c = c.replace(old_prompts, new_prompts)
    print("OK Handwriting adaptateur word/letter")
else:
    print("ERREUR : ancre prompts introuvable")

with open(hw, "w", encoding="utf-8") as f:
    f.write(c)
print("OK Handwriting.tsx")

# ─────────────────────────────────────────────────────────────────────────────
#  2. ExercisePlayer.tsx -- ajouter type 'match_pairs' depuis content (pas juste type)
#     Le probleme : certains exercices ont type='match_pairs' dans content
#     mais ExercisePlayer route sur content.type
#     Verifier aussi que match_pairs est bien route
# ─────────────────────────────────────────────────────────────────────────────
with open(PLAYER, "r", encoding="utf-8") as f:
    c = f.read()

# Le routing match_pairs existe deja -- le probleme est que certains exercices
# ont des types non reconnus. Ajouter un log pour debug + fallback match_pairs
# si le content a des pairs

old_fallback = (
    "  return (\n"
    "    <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title}>\n"
    "      <div style={{ textAlign: 'center', padding: '60px 20px' }}>"
)

new_fallback = (
    "  // Fallback intelligent selon contenu\n"
    "  if (content.pairs) {\n"
    "    return (\n"
    "      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title}>\n"
    "        <MatchPairs content={content} onComplete={handleBool} />\n"
    "      </ExerciseShell>\n"
    "    )\n"
    "  }\n"
    "  if (content.words && content.answer) {\n"
    "    return (\n"
    "      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title}>\n"
    "        <SentenceOrder content={content} onComplete={handleBool} />\n"
    "      </ExerciseShell>\n"
    "    )\n"
    "  }\n"
    "  if (content.statement !== undefined) {\n"
    "    return (\n"
    "      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title}>\n"
    "        <TrueFalse content={content} onComplete={handleBool} />\n"
    "      </ExerciseShell>\n"
    "    )\n"
    "  }\n"
    "\n"
    "  return (\n"
    "    <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title}>\n"
    "      <div style={{ textAlign: 'center', padding: '60px 20px' }}>"
)

if old_fallback in c:
    c = c.replace(old_fallback, new_fallback)
    print("OK ExercisePlayer fallback intelligent")
else:
    print("ERREUR : ancre fallback introuvable")

with open(PLAYER, "w", encoding="utf-8") as f:
    f.write(c)
print("OK ExercisePlayer.tsx")
print("Lance : npm run build")
