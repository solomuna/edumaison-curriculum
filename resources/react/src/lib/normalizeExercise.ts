// Normalisation des contenus d'exercices avant le choix du moteur.
// Même logique que le serveur (ExerciseController::normalizeContent) :
// - type absent : déduit de la forme du contenu ;
// - choix de QCM enregistrés comme texte JSON (« "[\"A\",\"B\"]" ») : relus en liste ;
// - QCM « à plat » ({question, options, answer}) : ramené à questions[] ;
// - réponse de QCM : un entier est une position ; un texte présent dans les choix
//   désigne ce choix (« 3 » parmi 1, 2, 3, 4 = le choix « 3 ») ; sinon un texte
//   numérique est une position (ExerciseController::optionIndex).

type Content = Record<string, any>

export function parseOptions(options: unknown): string[] {
  if (Array.isArray(options)) return options.map(o => String(o))
  if (typeof options === 'string') {
    try {
      const parsed = JSON.parse(options)
      if (Array.isArray(parsed)) return parsed.map(o => String(o))
    } catch { /* pas du JSON : une seule option */ }
    return options.trim() ? [options] : []
  }
  return []
}

export function inferExerciseType(content: Content): string {
  if (content.type) return String(content.type)
  if (Array.isArray(content.questions) || content.options !== undefined) return 'mcq'
  if (content.pairs) return 'match_pairs'
  if (content.words && content.answer !== undefined) return 'sentence_order'
  if (content.statement !== undefined) return 'true_false'
  if (content.sentence !== undefined && content.answer !== undefined) return 'fill_in'
  return ''
}

function answerIndex(answer: unknown, options: string[]): number {
  if (typeof answer === 'number') return answer
  if (typeof answer === 'string') {
    const exact = options.indexOf(answer)
    if (exact >= 0) return exact
    // Ancien format : un texte numérique absent des choix est une position (comme au serveur).
    const numeric = Number(answer)
    if (answer.trim() !== '' && Number.isInteger(numeric)) return numeric
  }
  return 0
}

export function normalizeExerciseContent(raw: unknown, fallbackQuestion = ''): Content {
  const content: Content = typeof raw === 'string'
    ? (() => { try { return JSON.parse(raw) } catch { return {} } })()
    : { ...(raw as Content ?? {}) }
  const type = inferExerciseType(content)
  if (type !== 'mcq' && type !== 'multiple_choice') return { ...content, type }

  const flat = !Array.isArray(content.questions)
  const questions = (flat
    ? [{ text: content.question || fallbackQuestion, question: content.question || fallbackQuestion, svg: content.svg || null, options: content.options, answer: content.answer }]
    : content.questions
  ).map((q: Content) => {
    const options = parseOptions(q.options)
    return { ...q, options, answer: answerIndex(q.answer, options) }
  })
  return { ...content, type, questions }
}
