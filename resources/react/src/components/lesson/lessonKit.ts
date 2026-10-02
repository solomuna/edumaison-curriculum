// Kit commun de la boucle de leçon : textes, poses de Mama Judi et séquence
// de retour (son -> bandeau -> voix) partagés par le QCM et LessonShell.
import { MamaJudi } from '../../services/MamaJudi'
import { SoundService } from '../../services/SoundService'
import '../../styles/lesson.css'

// Clavier virtuel : sur les navigateurs qui le superposent à la page, on
// remonte le pied de leçon de la hauteur du clavier (variable --kb-inset).
if (typeof window !== 'undefined' && window.visualViewport && !(window as any).__lessonKbTracked) {
  (window as any).__lessonKbTracked = true
  const vv = window.visualViewport
  const update = () => {
    const inset = Math.max(0, Math.round(window.innerHeight - vv.height - vv.offsetTop))
    document.documentElement.style.setProperty('--kb-inset', `${inset}px`)
  }
  vv.addEventListener('resize', update)
  vv.addEventListener('scroll', update)
  update()
}

/** Même règle que le serveur (ExerciseController::exactAnswer) : la réponse doit
 *  être exacte — majuscules, accents et ponctuation comptent (« Goodbye! »).
 *  Seuls les espaces superflus et la forme de l'apostrophe sont tolérés. */
export function exactAnswer(value: string): string {
  return String(value ?? '')
    .normalize('NFC')
    .replace(/[‘’ʼ`]/g, "'")
    .replace(/\s+/g, ' ')
    .trim()
}

export interface DiffPart { text: string; ok: boolean }

/**
 * Compare caractère par caractère (plus longue sous-suite commune) :
 * expected = bonne réponse, parties non retrouvées = oubliées ou fausses ;
 * actual = réponse de l'enfant, parties non retrouvées = en trop ou fausses.
 */
export function diffAnswer(expected: string, actual: string): { expected: DiffPart[]; actual: DiffPart[] } {
  const a = [...expected]
  const b = [...actual]
  const lcs = Array.from({ length: a.length + 1 }, () => new Array<number>(b.length + 1).fill(0))
  for (let i = a.length - 1; i >= 0; i--) {
    for (let j = b.length - 1; j >= 0; j--) {
      lcs[i][j] = a[i] === b[j] ? lcs[i + 1][j + 1] + 1 : Math.max(lcs[i + 1][j], lcs[i][j + 1])
    }
  }
  const exp: boolean[] = new Array(a.length).fill(false)
  const act: boolean[] = new Array(b.length).fill(false)
  let i = 0
  let j = 0
  while (i < a.length && j < b.length) {
    if (a[i] === b[j]) { exp[i] = true; act[j] = true; i++; j++ }
    else if (lcs[i + 1][j] >= lcs[i][j + 1]) i++
    else j++
  }
  const group = (chars: string[], flags: boolean[]) => chars.reduce<DiffPart[]>((parts, ch, k) => {
    const last = parts[parts.length - 1]
    if (last && last.ok === flags[k]) last.text += ch
    else parts.push({ text: ch, ok: flags[k] })
    return parts
  }, [])
  return { expected: group(a, exp), actual: group(b, act) }
}

export const JUDI = {
  explain: '/images/adventure/characters/mama-judi/explain-v1.webp',
  encourage: '/images/adventure/characters/mama-judi/encourage-v1.webp',
  celebrate: '/images/adventure/characters/mama-judi/celebrate-v1.webp',
}

export const XP_PER_CORRECT = 10

const EN = {
    check: 'Check', continue: 'Continue', finish: 'Finish', retry: 'Try again', back: 'Back to my missions',
    praise: ['Excellent!', 'Well done!', 'Amazing!', 'Great job!', 'Perfect!'],
    wrong: 'Correct answer:', notQuite: 'Not quite!', seeAbove: 'Look at the correction above.',
    streak: (n: number) => `${n} in a row!`,
    doneTitle: 'Lesson complete!', retryTitle: 'Keep practising!',
    doneMsg: (p: number): string => p === 100 ? 'Perfect score, you are a star!' : p >= 70 ? 'Great work, keep it up!' : 'Every try makes you stronger. Let’s go again!',
    xp: 'Total XP', accuracy: 'Accuracy', best: 'Best streak', review: 'See my answers', ok: 'Mastered', ko: 'To review',
    listen: 'Listen', close: 'Leave the lesson', passage: 'Reading passage',
    again: 'Let’s try this one again', saving: 'Saving…', retrySave: 'Try again', saveError: 'Could not save. Check the connection and try again.',
}

export type LessonLabels = typeof EN

const FR: LessonLabels = {
    check: 'Vérifier', continue: 'Continuer', finish: 'Terminer', retry: 'Recommencer', back: 'Retour à mes missions',
    praise: ['Excellent !', 'Bravo !', 'Super !', 'Très bien !', 'Parfait !'],
    wrong: 'Bonne réponse :', notQuite: 'Pas tout à fait !', seeAbove: 'Regarde la correction au-dessus.',
    streak: (n: number) => `${n} d’affilée !`,
    doneTitle: 'Leçon terminée !', retryTitle: 'Continue à t’entraîner !',
    doneMsg: (p: number) => p === 100 ? 'Sans faute, tu es une étoile !' : p >= 70 ? 'Beau travail, continue comme ça !' : 'Chaque essai te rend plus fort. On recommence ?',
    xp: 'XP gagnés', accuracy: 'Précision', best: 'Meilleure série', review: 'Voir mes réponses', ok: 'Maîtrisé', ko: 'À revoir',
    listen: 'Écouter', close: 'Quitter la leçon', passage: 'Texte à lire',
    again: 'On réessaie celle-ci', saving: 'Enregistrement…', retrySave: 'Réessayer', saveError: 'Enregistrement impossible. Vérifie la connexion et réessaie.',
}

export const LABELS = { en: EN, fr: FR }

export const labelsFor = (isFrench: boolean): LessonLabels => (isFrench ? LABELS.fr : LABELS.en)

export const randomPraise = (L: LessonLabels) => L.praise[Math.floor(Math.random() * L.praise.length)]

export const isStreakMilestone = (n: number) => n === 3 || n === 5 || (n > 5 && n % 5 === 0)

/**
 * Séquence de retour à la vérification, dans un ordre fixe :
 * 1. on coupe la lecture en cours (question), 2. le son part à t=0 avec le
 * bandeau, 3. la voix enregistrée de Mama Judi suit à +250 ms (si prête).
 * Renvoie une fonction qui annule la voix programmée (ex. si l'enfant continue).
 */
export function playVerdict(correct: boolean, streak = 0): () => void {
  MamaJudi.stop()
  let voice: 'correct' | 'wrong' | 'streak3' | 'streak5' = correct ? 'correct' : 'wrong'
  if (correct && isStreakMilestone(streak)) {
    SoundService.streak()
    voice = streak === 3 ? 'streak3' : 'streak5'
  } else if (correct) {
    SoundService.correct()
  } else {
    SoundService.wrong()
    if ('vibrate' in navigator) navigator.vibrate?.(120)
  }
  const timer = window.setTimeout(() => MamaJudi.react(voice), 250)
  return () => window.clearTimeout(timer)
}

/** À appeler au montage d'une leçon : sons et voix décodés à l'avance. */
export function prepareLessonAudio(isFrench = false) {
  MamaJudi.setLanguage(isFrench ? 'fr' : 'en')
  SoundService.init()
  MamaJudi.preloadVoices()
}
