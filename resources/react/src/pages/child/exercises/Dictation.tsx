import { useEffect, useMemo, useRef, useState } from 'react'
import { MamaJudi } from '../../../services/MamaJudi'
import { fireSuccess } from '../../../components/SuccessFx'
import LessonEnd from '../../../components/lesson/LessonEnd'
import { JUDI, XP_PER_CORRECT, labelsFor, randomPraise, playVerdict, prepareLessonAudio } from '../../../components/lesson/lessonKit'
import type { DictationContent, ExerciseCompletionHandler } from '../../../types/exercise'

interface Props {
  title: string
  instructions: string
  content: DictationContent
  onComplete: ExerciseCompletionHandler
  onBack: () => void
}

interface ScoreBreakdown {
  score: number
  words: number
  spelling: number
  capitalization: number
  punctuation: number
}

const normalizeDictation = (value: string) => value
  .normalize('NFC')
  .replace(/[\u2019\u2018]/g, "'")
  .toLocaleLowerCase()
  .replace(/[^\p{L}\p{N}'\s]/gu, ' ')
  .replace(/\s+/g, ' ')
  .trim()

function sequenceAccuracy(left: string[], right: string[]): number {
  if (left.length === 0) return 0
  const rows = Array.from({ length: left.length + 1 }, () => new Array(right.length + 1).fill(0))
  for (let i = 0; i <= left.length; i++) rows[i][0] = i
  for (let j = 0; j <= right.length; j++) rows[0][j] = j
  for (let i = 1; i <= left.length; i++) {
    for (let j = 1; j <= right.length; j++) {
      rows[i][j] = Math.min(
        rows[i - 1][j] + 1,
        rows[i][j - 1] + 1,
        rows[i - 1][j - 1] + (left[i - 1] === right[j - 1] ? 0 : 1),
      )
    }
  }
  return Math.max(0, Math.round((1 - rows[left.length][right.length] / Math.max(left.length, right.length)) * 100))
}

function initialCapitalizationMatches(expected: string, actual: string): boolean {
  const expectedLetter = expected.match(/^\s*(\p{L})/u)?.[1]
  const actualLetter = actual.match(/^\s*(\p{L})/u)?.[1]
  if (!expectedLetter || !actualLetter) return false
  return (expectedLetter === expectedLetter.toLocaleUpperCase()) === (actualLetter === actualLetter.toLocaleUpperCase())
}

function punctuationSequence(value: string): string[] {
  return value.match(/[.,!?;:]/gu) ?? []
}

function scoreDictation(expected: string, actual: string): ScoreBreakdown {
  const normalizedExpected = normalizeDictation(expected)
  const normalizedActual = normalizeDictation(actual)
  const words = sequenceAccuracy(normalizedExpected.split(' ').filter(Boolean), normalizedActual.split(' ').filter(Boolean))
  const spelling = sequenceAccuracy([...normalizedExpected.replace(/\s/g, '')], [...normalizedActual.replace(/\s/g, '')])
  const capitalization = initialCapitalizationMatches(expected, actual) ? 100 : 0
  const punctuation = JSON.stringify(punctuationSequence(expected)) === JSON.stringify(punctuationSequence(actual)) ? 100 : 0
  return {
    score: Math.round(words * 0.60 + spelling * 0.25 + capitalization * 0.05 + punctuation * 0.10),
    words,
    spelling,
    capitalization,
    punctuation,
  }
}

const labels = {
  en: {
    defaultInstructions: 'Listen carefully, then write exactly what you hear.', listen: 'Listen', left: 'left', limit: 'Listening limit reached',
    prompt: 'What did you hear?', listenFirst: 'Listen before writing.', empty: 'Write what you heard.',
    expected: 'Expected sentence', words: 'Words', spelling: 'Spelling', capitalization: 'Capital letter', punctuation: 'Punctuation',
    word: 'word', wordsCount: 'words', hint: 'Hint', playing: 'Listening…', keepGoing: 'Keep practising!',
    audioError: 'Listening audio is unavailable. Check the media volume and try again.',
  },
  fr: {
    defaultInstructions: 'Écoute attentivement, puis écris exactement ce que tu entends.', listen: 'Écouter', left: 'restantes', limit: "Limite d'écoutes atteinte",
    prompt: "Qu'as-tu entendu ?", listenFirst: "Écoute d'abord la phrase.", empty: 'Écris ce que tu as entendu.',
    expected: 'Phrase attendue', words: 'Mots', spelling: 'Orthographe', capitalization: 'Majuscule', punctuation: 'Ponctuation',
    word: 'mot', wordsCount: 'mots', hint: 'Indice', playing: 'Écoute…', keepGoing: 'Continue à t’entraîner !',
    audioError: "L'audio d'écoute est indisponible. Vérifie le volume et réessaie.",
  },
}

/** Une phrase de dictée est réussie à partir de 80 % (mots, orthographe, majuscule, ponctuation). */
const PASS = 80

const SpeakerIcon = () => (
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
    <path d="M11 5 6 9H3v6h3l5 4V5z" fill="currentColor" /><path d="M15.5 8.5a5 5 0 0 1 0 7" /><path d="M18.5 5.5a9 9 0 0 1 0 13" />
  </svg>
)

export default function Dictation({ title, instructions, content, onComplete, onBack }: Props) {
  const [current, setCurrent] = useState(0)
  const [answer, setAnswer] = useState('')
  const [answers, setAnswers] = useState<string[]>([])
  const [completedScores, setCompletedScores] = useState<ScoreBreakdown[]>([])
  const [replays, setReplays] = useState<number[]>(() => content.items.map(() => 0))
  const [review, setReview] = useState<{ answer: string; breakdown: ScoreBreakdown; praise: string } | null>(null)
  const [error, setError] = useState('')
  const [speaking, setSpeaking] = useState(false)
  const [done, setDone] = useState(false)
  const [streak, setStreak] = useState(0)
  const [bestStreak, setBestStreak] = useState(0)
  const startedAt = useRef(Date.now())
  const recordedAudioRef = useRef<HTMLAudioElement | null>(null)
  const cancelVoice = useRef<() => void>(() => {})
  const checkBtn = useRef<HTMLButtonElement>(null)
  const item = content.items[current]
  const isFrench = content.language === 'fr-FR'
  const t = isFrench ? labels.fr : labels.en
  const L = labelsFor(isFrench)
  const maxReplays = Math.max(1, Math.min(5, content.max_replays ?? 3))
  const canReplay = replays[current] < maxReplays && !review && !speaking
  const hasListened = replays[current] > 0
  const isLast = current === content.items.length - 1
  const wordCount = useMemo(() => answer.trim().split(/\s+/).filter(Boolean).length, [answer])
  const expectedWordCount = useMemo(() => normalizeDictation(item.text).split(' ').filter(Boolean).length, [item.text])
  const guidance = !hasListened ? t.listenFirst : !answer.trim() ? t.empty : ''
  const canCheck = hasListened && answer.trim().length > 0 && !review

  useEffect(() => {
    prepareLessonAudio()
    return () => {
      recordedAudioRef.current?.pause()
      recordedAudioRef.current = null
      cancelVoice.current()
      MamaJudi.stop()
    }
  }, [])

  const playRecordedAudio = (source: string): Promise<boolean> => new Promise(resolve => {
    recordedAudioRef.current?.pause()
    const audio = new Audio(source)
    recordedAudioRef.current = audio
    let settled = false
    const finish = (success: boolean) => {
      if (settled) return
      settled = true
      if (recordedAudioRef.current === audio) recordedAudioRef.current = null
      resolve(success)
    }
    audio.onended = () => finish(true)
    audio.onerror = () => finish(false)
    audio.play().catch(() => finish(false))
  })

  const listen = async () => {
    if (!canReplay) return
    setError('')
    setSpeaking(true)
    setReplays(values => values.map((value, index) => index === current ? value + 1 : value))
    MamaJudi.stop()
    let played = item.audio_url ? await playRecordedAudio(item.audio_url) : false
    if (!played) played = await MamaJudi.speakLang(item.text, content.language ?? 'en-GB', 0.72)
    if (!played) setError(t.audioError)
    setSpeaking(false)
  }

  const check = () => {
    if (review) return
    if (!hasListened) { setError(t.listenFirst); return }
    if (!answer.trim()) { setError(t.empty); return }
    recordedAudioRef.current?.pause()
    const submitted = answer.trim()
    const breakdown = scoreDictation(item.text, submitted)
    const good = breakdown.score >= PASS
    setReview({ answer: submitted, breakdown, praise: randomPraise(L) })
    setError('')
    cancelVoice.current()
    if (good) {
      const next = streak + 1
      setStreak(next)
      setBestStreak(b => Math.max(b, next))
      cancelVoice.current = playVerdict(true, next)
      const rect = checkBtn.current?.getBoundingClientRect()
      fireSuccess({ xp: XP_PER_CORRECT, x: rect ? rect.left + rect.width / 2 : window.innerWidth / 2, y: rect ? rect.top : window.innerHeight * 0.8 })
    } else {
      setStreak(0)
      cancelVoice.current = playVerdict(false)
    }
  }

  const next = () => {
    if (!review) return
    cancelVoice.current()
    setAnswers(values => [...values, review.answer])
    setCompletedScores(values => [...values, review.breakdown])
    if (isLast) { setDone(true); return }
    setCurrent(value => value + 1)
    setAnswer('')
    setReview(null)
    setError('')
  }

  // Enregistrement depuis l'écran de fin : LessonEnd affiche l'erreur éventuelle.
  const submit = () => {
    const score = Math.round(completedScores.reduce((sum, value) => sum + value.score, 0) / completedScores.length)
    return onComplete(score, {
      verification_status: 'auto_checked',
      answers: { items: answers },
      evidence: {
        method: 'dictation_words_spelling_mechanics',
        replays,
        item_scores: completedScores.map(value => value.score),
        item_breakdown: completedScores,
      },
      duration_seconds: Math.max(1, Math.round((Date.now() - startedAt.current) / 1000)),
    })
  }

  // Entrée : vérifier puis continuer (Ctrl+Entrée dans la zone de texte).
  useEffect(() => {
    if (done) return
    const onKey = (e: KeyboardEvent) => {
      if (e.key !== 'Enter') return
      if (e.target instanceof HTMLTextAreaElement && !e.ctrlKey && !review) return
      e.preventDefault()
      if (review) next(); else check()
    }
    window.addEventListener('keydown', onKey)
    return () => window.removeEventListener('keydown', onKey)
  })

  if (done) {
    const accuracy = Math.round(completedScores.reduce((sum, value) => sum + value.score, 0) / Math.max(1, completedScores.length))
    return (
      <LessonEnd
        isFrench={isFrench}
        results={content.items.map((it, i) => ({ title: it.text, correct: (completedScores[i]?.score ?? 0) >= PASS }))}
        total={content.items.length}
        bestStreak={bestStreak}
        accuracy={accuracy}
        onContinue={submit}
      />
    )
  }

  const answered = current + (review ? 1 : 0)
  const progress = Math.max(4, Math.round(answered / content.items.length * 100))
  const good = !!review && review.breakdown.score >= PASS
  const scoreRows = review ? [
    [t.words, review.breakdown.words],
    [t.spelling, review.breakdown.spelling],
    [t.capitalization, review.breakdown.capitalization],
    [t.punctuation, review.breakdown.punctuation],
  ] as Array<[string, number]> : []

  return (
    <div className="lesson">
      <div className="lesson-top">
        <button className="lesson-icon-btn" onClick={onBack} aria-label={L.close} title={L.close}>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" /></svg>
        </button>
        <div className={`lesson-progress${streak >= 3 ? ' is-streak' : ''}`} role="progressbar" aria-valuemin={0} aria-valuemax={content.items.length} aria-valuenow={answered}>
          <div className="lesson-progress__fill" style={{ width: `${progress}%` }} />
        </div>
        <div className={`lesson-streak${streak >= 2 ? ' is-on' : ''}`} aria-live="polite">🔥 {streak}</div>
      </div>

      <div className="lesson-body">
        <div className="lesson-kicker">{title}</div>

        <div className="lesson-prompt">
          <img className="lesson-prompt__judi" src={JUDI.explain} alt="" />
          <div className="lesson-bubble">
            <div className="lesson-bubble__hint">{instructions || t.defaultInstructions}</div>
            <button className="lesson-listen" onClick={listen} disabled={!canReplay}>
              <span className="lesson-listen__icon"><SpeakerIcon /></span>
              <span>{speaking ? t.playing : replays[current] < maxReplays ? `${t.listen} (${maxReplays - replays[current]} ${t.left})` : t.limit}</span>
            </button>
            {item.hint && hasListened && <div className="lesson-bubble__hint">💡 {t.hint} : {item.hint}</div>}
          </div>
        </div>

        <label htmlFor="dictation-answer" className="lesson-field-label">{t.prompt}</label>
        <textarea
          id="dictation-answer"
          className={`lesson-textarea${review ? (good ? ' is-right' : ' is-wrong') : ''}`}
          value={answer}
          onChange={event => { setAnswer(event.target.value); setError('') }}
          disabled={!!review}
          rows={3}
          autoCapitalize="sentences"
          autoComplete="off"
          autoCorrect="off"
          spellCheck={false}
        />
        <div className="lesson-field-meta">
          <span role={error ? 'alert' : undefined} className={error ? 'is-error' : ''}>{error || (!review ? guidance : '')}</span>
          <span className={wordCount === 0 ? '' : wordCount === expectedWordCount ? 'is-ok' : 'is-off'}>{wordCount} / {expectedWordCount} {expectedWordCount === 1 ? t.word : t.wordsCount}</span>
        </div>

        {review && (
          <div className="lesson-review-card">
            <div className="lesson-review-card__row"><strong>{t.expected} :</strong> {item.text}</div>
            <div className="lesson-scores">
              {scoreRows.map(([label, value]) => (
                <div key={label} className={`lesson-score ${value >= 80 ? 'is-ok' : value >= 50 ? 'is-mid' : 'is-low'}`}>
                  <span>{label}</span><strong>{value}%</strong>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>

      <div className={`lesson-footer${review ? (good ? ' is-right' : ' is-wrong') : ''}`} key={review ? `v${current}` : `q${current}`}>
        <div className="lesson-footer__inner">
          {review ? (
            <div className="lesson-verdict" role="status">
              <img className="lesson-verdict__judi" src={good ? JUDI.celebrate : JUDI.encourage} alt="" />
              <div>
                <div className="lesson-verdict__title">{good ? review.praise : t.keepGoing} · {review.breakdown.score}%</div>
                {!good && <div className="lesson-verdict__detail">{item.text}</div>}
              </div>
            </div>
          ) : <span />}
          {review ? (
            <button className={`lesson-btn${good ? '' : ' lesson-btn--red'}`} onClick={next} autoFocus>{isLast ? L.finish : L.continue}</button>
          ) : (
            <button ref={checkBtn} className="lesson-btn" onClick={check} disabled={!canCheck}>{L.check}</button>
          )}
        </div>
      </div>
    </div>
  )
}
