# patch_exercise_adapter.py
# Ajoute un adaptateur dans ExercisePlayer pour supporter le format simplifie MCQ
# {type:"mcq", question:"...", options:[...], answer:"..."} -> {questions:[...]}
# Et ajoute le rendu SVG dans ExerciseShell

PLAYER = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ExercisePlayer.tsx"

with open(PLAYER, "r", encoding="utf-8") as f:
    c = f.read()

# Ajouter l'adaptateur juste avant le routing MCQ
old_mcq_route = (
    "  if (type === 'multiple_choice' || type === 'mcq') {\n"
    "    return <MCQ title={exercise.title} instructions={exercise.instructions} content={content} subject={(exercise as any).subject} onComplete={onComplete} onBack={onBack} />\n"
    "  }"
)

new_mcq_route = (
    "  // Adaptateur format simplifie MCQ -> format questions[]\n"
    "  let mcqContent = content\n"
    "  if ((type === 'multiple_choice' || type === 'mcq') && !content.questions && content.options) {\n"
    "    const opts = content.options\n"
    "    const ans = content.answer\n"
    "    const answerIndex = typeof ans === 'number' ? ans : opts.indexOf(ans)\n"
    "    mcqContent = {\n"
    "      ...content,\n"
    "      questions: [{\n"
    "        text: content.question || exercise.title,\n"
    "        question: content.question || exercise.title,\n"
    "        svg: content.svg || null,\n"
    "        options: opts,\n"
    "        answer: answerIndex >= 0 ? answerIndex : 0\n"
    "      }]\n"
    "    }\n"
    "  }\n"
    "  if (type === 'multiple_choice' || type === 'mcq') {\n"
    "    return <MCQ title={exercise.title} instructions={exercise.instructions} content={mcqContent} subject={(exercise as any).subject} onComplete={onComplete} onBack={onBack} />\n"
    "  }"
)

if old_mcq_route in c:
    c = c.replace(old_mcq_route, new_mcq_route)
    print("OK adaptateur MCQ format simplifie")
else:
    print("ERREUR : ancre MCQ route introuvable")

# Ajouter rendu SVG dans ExerciseShell avant {children}
old_children = "      <div style={{ padding: '16px' }}>\n        {children}\n      </div>"
new_children = (
    "      <div style={{ padding: '16px' }}>\n"
    "        {children}\n"
    "      </div>"
)
# Pas de changement ici -- le SVG sera rendu dans MCQ.tsx directement

with open(PLAYER, "w", encoding="utf-8") as f:
    f.write(c)
print("OK ExercisePlayer.tsx")

# ─────────────────────────────────────────────────────────────────────────────
# Patch MCQ.tsx -- afficher svg si present dans la question
# ─────────────────────────────────────────────────────────────────────────────
MCQ = r"C:\laragon\www\edumaison\resources\react\src\pages\child\exercises\MCQ.tsx"

with open(MCQ, "r", encoding="utf-8") as f:
    mcq = f.read()

# Trouver l'endroit ou la question est affichee et ajouter le SVG
old_q_display = (
    "  useEffect(() => { if (q) MamaJudi.speak(q.text || q.question || '', 0.85) }, [current])"
)

if old_q_display in mcq:
    print("OK ancre question trouvee dans MCQ.tsx")
else:
    print("ancre non trouvee -- cherche manuellement")
    idx = mcq.find("MamaJudi.speak(q.text")
    print(mcq[max(0,idx-50):idx+100])

# Chercher ou la question text est rendue pour ajouter SVG juste apres
# Chercher le pattern d'affichage de la question
import re
# Trouver la div qui affiche q.text ou q.question
pattern = r'(q\.text \|\| q\.question)'
matches = [(m.start(), m.end()) for m in re.finditer(pattern, mcq)]
print(f"Occurrences de q.text || q.question : {len(matches)}")
for start, end in matches:
    print(f"  ...{mcq[max(0,start-80):end+80]}...")

print("Lance : npm run build")
