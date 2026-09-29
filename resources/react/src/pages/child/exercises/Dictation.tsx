import { useEffect, useMemo, useRef, useState } from 'react'
import { MamaJudi } from '../../../services/MamaJudi'
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
    prompt: 'What did you hear?', listenFirst: 'Listen before writing.', empty: 'Write what you heard before continuing.', check: 'Check my sentence',
    yourSentence: 'Your sentence', expected: 'Expected sentence', words: 'Words', spelling: 'Spelling', capitalization: 'Capital letter', punctuation: 'Punctuation',
    continue: 'Continue', finish: 'Finish dictation', word: 'word', wordsCount: 'words', hint: 'Hint',
    playing: 'Playing...', audioError: 'Listening audio is unavailable. Check the media volume and try again.',
  },
  fr: {
    defaultInstructions: 'Ecoute attentivement, puis ecris exactement ce que tu entends.', listen: 'Ecouter', left: 'restantes', limit: "Limite d'ecoutes atteinte",
    prompt: 'Qu\'as-tu entendu ?', listenFirst: "Ecoute d'abord la phrase.", empty: 'Ecris ce que tu as entendu avant de continuer.', check: 'Verifier ma phrase',
    yourSentence: 'Ta phrase', expected: 'Phrase attendue', words: 'Mots', spelling: 'Orthographe', capitalization: 'Majuscule', punctuation: 'Ponctuation',
    continue: 'Continuer', finish: 'Terminer la dictee', word: 'mot', wordsCount: 'mots', hint: 'Indice',
    playing: 'Lecture...', audioError: "L'audio d'ecoute est indisponible. Verifie le volume et reessaie.",
  },
}

export default function Dictation({ title, instructions, content, onComplete, onBack }: Props) {
  const [current, setCurrent] = useState(0)
  const [answer, setAnswer] = useState('')
  const [answers, setAnswers] = useState<string[]>([])
  const [completedScores, setCompletedScores] = useState<ScoreBreakdown[]>([])
  const [replays, setReplays] = useState<number[]>(() => content.items.map(() => 0))
  const [review, setReview] = useState<{ answer: string; breakdown: ScoreBreakdown } | null>(null)
  const [error, setError] = useState('')
  const [submitting, setSubmitting] = useState(false)
  const [speaking, setSpeaking] = useState(false)
  const startedAt = useRef(Date.now())
  const recordedAudioRef = useRef<HTMLAudioElement | null>(null)
  const item = content.items[current]
  const maxReplays = Math.max(1, Math.min(5, content.max_replays ?? 3))
  const canReplay = replays[current] < maxReplays && !review && !speaking
  const hasListened = replays[current] > 0
  const progress = Math.round((current / content.items.length) * 100)
  const wordCount = useMemo(() => answer.trim().split(/\s+/).filter(Boolean).length, [answer])
  const expectedWordCount = useMemo(() => normalizeDictation(item.text).split(' ').filter(Boolean).length, [item.text])
  const wordCountColor = wordCount === 0 ? '#7A6050' : wordCount === expectedWordCount ? '#1D6B2A' : '#B42318'
  const t = content.language === 'fr-FR' ? labels.fr : labels.en
  const guidance = !hasListened ? t.listenFirst : !answer.trim() ? t.empty : ''
  const canCheck = hasListened && answer.trim().length > 0 && !review && !submitting

  useEffect(() => () => {
    recordedAudioRef.current?.pause()
    recordedAudioRef.current = null
    MamaJudi.stop()
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

  const submit = () => {
    if (!hasListened) {
      setError(t.listenFirst)
      return
    }
    if (!answer.trim()) {
      setError(t.empty)
      return
    }
    const submitted = answer.trim()
    setReview({ answer: submitted, breakdown: scoreDictation(item.text, submitted) })
    setError('')
  }

  const continueAfterReview = async () => {
    if (!review) return
    const nextAnswers = [...answers, review.answer]
    const nextScores = [...completedScores, review.breakdown]
    if (current < content.items.length - 1) {
      setAnswers(nextAnswers)
      setCompletedScores(nextScores)
      setCurrent(value => value + 1)
      setAnswer('')
      setReview(null)
      setError('')
      return
    }

    const score = Math.round(nextScores.reduce((sum, value) => sum + value.score, 0) / nextScores.length)
    setSubmitting(true)
    setError('')
    try {
      await onComplete(score, {
        verification_status: 'auto_checked',
        answers: { items: nextAnswers },
        evidence: {
          method: 'dictation_words_spelling_mechanics',
          replays,
          item_scores: nextScores.map(value => value.score),
          item_breakdown: nextScores,
        },
        duration_seconds: Math.max(1, Math.round((Date.now() - startedAt.current) / 1000)),
      })
    } catch (reason) {
      setError(reason instanceof Error ? reason.message : 'Could not save this dictation. Try again.')
    } finally {
      setSubmitting(false)
    }
  }

  const scoreRows = review ? [
    [t.words, review.breakdown.words],
    [t.spelling, review.breakdown.spelling],
    [t.capitalization, review.breakdown.capitalization],
    [t.punctuation, review.breakdown.punctuation],
  ] as Array<[string, number]> : []

  return (
    <div className="adventure-exercise-shell adventure-dictation-page" style={{ minHeight: '100vh', background: '#E8DCC8', color: '#3D2B1F', fontFamily: 'Nunito, system-ui, sans-serif' }}>
      <header className="adventure-exercise-header" style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '12px 16px', background: '#F0E8D8', borderBottom: '1px solid #D0C8B8' }}>
        <button onClick={onBack} aria-label="Back" style={{ border: '1px solid #D0C8B8', background: '#F0E8D8', borderRadius: 8, padding: '7px 12px', cursor: 'pointer' }}>←</button>
        <div style={{ flex: 1 }}>
          <strong>{title}</strong>
          <div style={{ height: 5, marginTop: 6, background: '#D0C8B8' }}><div style={{ width: `${progress}%`, height: '100%', background: '#1D6B2A' }} /></div>
        </div>
        <span style={{ fontSize: 13, fontWeight: 800 }}>{current + 1}/{content.items.length}</span>
      </header>

      <main className="adventure-exercise-content" style={{ maxWidth: 680, margin: '0 auto', padding: 20 }}>
        <p style={{ textAlign: 'center', color: '#7A6050' }}>{instructions || t.defaultInstructions}</p>
        <section className="adventure-exercise-panel" style={{ background: '#F0E8D8', border: '1px solid #D0C8B8', borderRadius: 8, padding: 22 }}>
          {!review && (
            <button onClick={listen} disabled={!canReplay || submitting} style={{ width: '100%', minHeight: 52, border: 0, borderRadius: 8, background: canReplay && !submitting ? '#1D6B2A' : '#9E9A90', color: 'white', fontWeight: 900, cursor: canReplay && !submitting ? 'pointer' : 'default' }}>
              {speaking ? t.playing : canReplay ? `${t.listen} (${maxReplays - replays[current]} ${t.left})` : t.limit}
            </button>
          )}
          {item.hint && hasListened && <p style={{ color: '#7A6050', fontSize: 13 }}><strong>{t.hint}:</strong> {item.hint}</p>}
          <label htmlFor="dictation-answer" style={{ display: 'block', margin: '20px 0 8px', fontWeight: 900 }}>{t.prompt}</label>
          <textarea
            id="dictation-answer"
            value={answer}
            onChange={event => { setAnswer(event.target.value); setError('') }}
            disabled={!!review || submitting}
            rows={4}
            autoCapitalize="sentences"
            autoComplete="off"
            autoCorrect="off"
            spellCheck={false}
            style={{ boxSizing: 'border-box', width: '100%', resize: 'vertical', border: '2px solid #B9AF9F', borderRadius: 8, padding: 14, background: review || submitting ? '#E2DDD3' : '#FFFDF8', color: '#3D2B1F', font: '700 18px Nunito, system-ui, sans-serif' }}
          />
          <div style={{ display: 'flex', justifyContent: 'space-between', flexWrap: 'wrap', gap: 8, minHeight: 22, marginTop: 8, color: error ? '#B42318' : '#7A6050', fontSize: 13 }}>
            <span role={error ? 'alert' : undefined}>{error || (!review ? guidance : '')}</span>
            <span style={{ marginLeft: 'auto', color: wordCountColor, fontWeight: 900 }}>{wordCount} / {expectedWordCount} {expectedWordCount === 1 ? t.word : t.wordsCount}</span>
          </div>

          {!review && (
            <button onClick={submit} disabled={!canCheck} style={{ width: '100%', minHeight: 48, marginTop: 12, border: 0, borderRadius: 8, background: canCheck ? '#C47A3C' : '#8A8A7E', color: 'white', fontWeight: 900, cursor: canCheck ? 'pointer' : 'not-allowed' }}>
              {t.check}
            </button>
          )}

          {review && (
            <div style={{ marginTop: 16 }}>
              <div style={{ padding: 12, background: '#FFFDF8', borderLeft: '4px solid #C47A3C', marginBottom: 8 }}><strong>{t.yourSentence}:</strong> {review.answer}</div>
              <div style={{ padding: 12, background: '#E7F3E8', borderLeft: '4px solid #1D6B2A', marginBottom: 12 }}><strong>{t.expected}:</strong> {item.text}</div>
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(2, minmax(0, 1fr))', gap: 8 }}>
                {scoreRows.map(([label, value]) => (
                  <div key={label} style={{ padding: 10, background: '#FFFDF8', border: '1px solid #D0C8B8', textAlign: 'center' }}>
                    <div style={{ fontSize: 11, color: '#7A6050', fontWeight: 800 }}>{label}</div>
                    <strong style={{ color: value >= 80 ? '#1D6B2A' : value >= 50 ? '#9A5A22' : '#B42318' }}>{value}%</strong>
                  </div>
                ))}
              </div>
              <button onClick={continueAfterReview} disabled={submitting} style={{ width: '100%', minHeight: 48, marginTop: 12, border: 0, borderRadius: 8, background: submitting ? '#8A8A7E' : '#1D6B2A', color: 'white', fontWeight: 900, cursor: submitting ? 'wait' : 'pointer' }}>
                {submitting ? 'Saving...' : current < content.items.length - 1 ? t.continue : t.finish}
              </button>
            </div>
          )}
        </section>
      </main>
    </div>
  )
}
