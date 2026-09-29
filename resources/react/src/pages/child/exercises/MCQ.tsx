import { useState, useEffect, useMemo, useRef, useCallback } from 'react'
import Confetti from '../../../components/Confetti'
import { MamaJudi } from '../../../services/MamaJudi'
import { SoundService } from '../../../services/SoundService'
import { fireSuccess } from '../../../components/SuccessFx'
import type { ExerciseCompletionHandler, MCQContent } from '../../../types/exercise'
import { useAssetLibrary } from '../../../hooks/useAssetLibrary'
import Ardoise from './Ardoise'
import '../../../styles/lesson.css'

interface Props {
  title: string
  instructions: string
  content: MCQContent
  subject?: string
  onComplete: ExerciseCompletionHandler
  onBack: () => void
}

interface Result { correct: boolean; title: string; question_index: number; selected_index: number }

const JUDI = {
  explain: '/images/adventure/characters/mama-judi/explain-v1.webp',
  encourage: '/images/adventure/characters/mama-judi/encourage-v1.webp',
  celebrate: '/images/adventure/characters/mama-judi/celebrate-v1.webp',
}

const XP_PER_CORRECT = 10

const LABELS = {
  en: {
    check: 'Check', continue: 'Continue', finish: 'Finish', retry: 'Try again', back: 'Back to my missions',
    praise: ['Excellent!', 'Well done!', 'Amazing!', 'Great job!', 'Perfect!'],
    wrong: 'Correct answer:', streak: (n: number) => `${n} in a row!`,
    doneTitle: 'Lesson complete!', retryTitle: 'Keep practising!',
    doneMsg: (p: number) => p === 100 ? 'Perfect score, you are a star!' : p >= 70 ? 'Great work, keep it up!' : 'Every try makes you stronger. Let’s go again!',
    xp: 'Total XP', accuracy: 'Accuracy', best: 'Best streak', review: 'See my answers', ok: 'Mastered', ko: 'To review',
    listen: 'Listen', close: 'Leave the lesson', passage: 'Reading passage',
  },
  fr: {
    check: 'Vérifier', continue: 'Continuer', finish: 'Terminer', retry: 'Recommencer', back: 'Retour à mes missions',
    praise: ['Excellent !', 'Bravo !', 'Super !', 'Très bien !', 'Parfait !'],
    wrong: 'Bonne réponse :', streak: (n: number) => `${n} d’affilée !`,
    doneTitle: 'Leçon terminée !', retryTitle: 'Continue à t’entraîner !',
    doneMsg: (p: number) => p === 100 ? 'Sans faute, tu es une étoile !' : p >= 70 ? 'Beau travail, continue comme ça !' : 'Chaque essai te rend plus fort. On recommence ?',
    xp: 'XP gagnés', accuracy: 'Précision', best: 'Meilleure série', review: 'Voir mes réponses', ok: 'Maîtrisé', ko: 'À revoir',
    listen: 'Écouter', close: 'Quitter la leçon', passage: 'Texte à lire',
  },
}

function shuffleOptions(options: any, answerIndex: number) {
  const safeOptions = Array.isArray(options) ? options : []
  const indexed = safeOptions.map((opt, i) => ({ opt, originalIndex: i, isCorrect: i === answerIndex }))
  for (let i = indexed.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [indexed[i], indexed[j]] = [indexed[j], indexed[i]]
  }
  return {
    options: indexed.map(x => x.opt),
    originalIndexes: indexed.map(x => x.originalIndex),
    answerIndex: indexed.findIndex(x => x.isCorrect),
  }
}

function useCountUp(target: number, delay: number, run: boolean) {
  const [value, setValue] = useState(0)
  useEffect(() => {
    if (!run) { setValue(0); return }
    let frame = 0
    const start = performance.now() + delay
    const tick = (now: number) => {
      const t = Math.min(1, Math.max(0, (now - start) / 700))
      setValue(Math.round(target * (1 - Math.pow(1 - t, 3))))
      if (t < 1) frame = requestAnimationFrame(tick)
    }
    frame = requestAnimationFrame(tick)
    return () => cancelAnimationFrame(frame)
  }, [target, delay, run])
  return value
}

const SpeakerIcon = () => (
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
    <path d="M11 5 6 9H3v6h3l5 4V5z" fill="currentColor" /><path d="M15.5 8.5a5 5 0 0 1 0 7" /><path d="M18.5 5.5a9 9 0 0 1 0 13" />
  </svg>
)

export default function MCQ({ title, instructions, content, subject, onComplete, onBack }: Props) {
  const [current, setCurrent] = useState(0)
  const [selectedIdx, setSelectedIdx] = useState<number | null>(null)
  const [checked, setChecked] = useState(false)
  const [results, setResults] = useState<Result[]>([])
  const [streak, setStreak] = useState(0)
  const [bestStreak, setBestStreak] = useState(0)
  const [combo, setCombo] = useState<{ id: number; n: number } | null>(null)
  const [praise, setPraise] = useState('')
  const [showResult, setShowResult] = useState(false)
  const [showArdoise, setShowArdoise] = useState(false)
  const checkBtn = useRef<HTMLButtonElement>(null)
  const { getSubjectIcon } = useAssetLibrary()

  // Guard complet: string JSON, undefined, ou format sans questions[]
  const rawContent: any = !content ? {} : typeof content === 'string' ? (() => { try { return JSON.parse(content) } catch { return {} } })() : content
  const FRENCH_SUBJECTS = ['French', 'Francais', 'NLC', 'National Languages']
  const isFrenchSubject = FRENCH_SUBJECTS.some(s => (subject || '').toLowerCase().includes(s.toLowerCase()))
  const ttsLang = isFrenchSubject ? 'fr-FR' : 'en-GB'
  const L = isFrenchSubject ? LABELS.fr : LABELS.en
  const questions: any[] = Array.isArray(rawContent.questions) ? rawContent.questions
    : rawContent.question && rawContent.options ? [{ text: rawContent.question, question: rawContent.question, options: rawContent.options, answer: rawContent.answer ?? 0 }]
    : []
  const shuffled = useMemo(() =>
    questions.map(q => { const idx = typeof q.answer === 'number' ? q.answer : (q.options||[]).indexOf(q.answer); return shuffleOptions(q.options, idx >= 0 ? idx : 0) })
  , [questions.length])
  const q = questions[current]
  const shuffledQ = shuffled[current] || { options: q?.options || [], originalIndexes: (q?.options || []).map((_: unknown, index: number) => index), answerIndex: 0 }
  const questionText: string = q?.text || q?.question || ''
  const isLast = current === questions.length - 1
  const lastResult = results[results.length - 1]
  const isRight = checked && !!lastResult?.correct

  const voiceTimer = useRef<number | null>(null)
  const clearVoice = () => { if (voiceTimer.current !== null) { clearTimeout(voiceTimer.current); voiceTimer.current = null } }

  // Sons et voix décodés à l'avance, pendant que l'enfant lit la première question.
  useEffect(() => {
    SoundService.init()
    MamaJudi.preloadVoices()
    return () => { clearVoice(); MamaJudi.stop() }
  }, [])
  useEffect(() => { if (q) MamaJudi.speakLangAfter(current === 0 ? `${instructions}. ${questionText}` : questionText, ttsLang, 250) }, [current])

  useEffect(() => {
    if (!showResult) return
    const pct = Math.round(results.filter(r => r.correct).length / questions.length * 100)
    if (pct === 100)    { setTimeout(() => SoundService.fanfare(),  300); MamaJudi.sessionPerfect() }
    else if (pct >= 70) { setTimeout(() => SoundService.applause(), 300); MamaJudi.sessionGood() }
    else if (pct < 50)  { setTimeout(() => SoundService.heartLost(),300); MamaJudi.sessionRetry() }
  }, [showResult])

  const select = (idx: number) => {
    if (checked) return
    SoundService.click()
    setSelectedIdx(idx)
  }

  const check = useCallback(() => {
    if (checked || selectedIdx === null || !q) return
    const correct = selectedIdx === shuffledQ.answerIndex
    setChecked(true)
    setResults(r => [...r, {
      correct,
      title: questionText,
      question_index: current,
      selected_index: shuffledQ.originalIndexes[selectedIdx],
    }])
    // Ordre fixe : on coupe la lecture de la question, le son part avec le
    // bandeau (t=0), la voix de Mama Judi suit 250 ms plus tard.
    MamaJudi.stop()
    clearVoice()
    const say = (event: 'correct' | 'wrong' | 'streak3' | 'streak5') => {
      voiceTimer.current = window.setTimeout(() => { voiceTimer.current = null; MamaJudi.react(event) }, 250)
    }
    if (correct) {
      const next = streak + 1
      setStreak(next)
      setBestStreak(b => Math.max(b, next))
      setPraise(L.praise[Math.floor(Math.random() * L.praise.length)])
      if (next === 3 || next === 5 || (next > 5 && next % 5 === 0)) {
        SoundService.streak()
        setCombo({ id: Date.now(), n: next })
        say(next === 3 ? 'streak3' : 'streak5')
      } else {
        SoundService.correct()
        say('correct')
      }
      const rect = checkBtn.current?.getBoundingClientRect()
      fireSuccess({
        xp: XP_PER_CORRECT,
        x: rect ? rect.left + rect.width / 2 : window.innerWidth / 2,
        y: rect ? rect.top : window.innerHeight * 0.8,
      })
    } else {
      setStreak(0)
      SoundService.wrong()
      say('wrong')
      if ('vibrate' in navigator) navigator.vibrate?.(120)
    }
  }, [checked, selectedIdx, q, shuffledQ, questionText, current, streak, L])

  const next = useCallback(() => {
    if (!checked) return
    clearVoice()
    setSelectedIdx(null)
    setChecked(false)
    if (!isLast) {
      setCurrent(c => c + 1)
      return
    }
    setShowResult(true)
    const total = results.filter(r => r.correct).length
    onComplete(Math.round(total / questions.length * 100), {
      verification_status: 'auto_checked',
      answers: { items: results.map(result => ({ question_index: result.question_index, selected_index: result.selected_index })) },
      evidence: { method: 'answer_key' },
    })
  }, [checked, isLast, results, questions.length, onComplete])

  const restart = () => {
    setCurrent(0); setSelectedIdx(null); setChecked(false); setResults([])
    setStreak(0); setBestStreak(0); setShowResult(false)
  }

  // Clavier : 1-9 pour choisir, Entrée pour vérifier puis continuer.
  useEffect(() => {
    if (showResult || showArdoise) return
    const onKey = (e: KeyboardEvent) => {
      if (e.target instanceof HTMLElement && ['INPUT', 'TEXTAREA'].includes(e.target.tagName)) return
      const n = Number(e.key)
      if (n >= 1 && n <= shuffledQ.options.length) { select(n - 1); return }
      if (e.key === 'Enter') { e.preventDefault(); checked ? next() : check() }
    }
    window.addEventListener('keydown', onKey)
    return () => window.removeEventListener('keydown', onKey)
  }, [showResult, showArdoise, shuffledQ, checked, check, next])

  useEffect(() => {
    if (!combo) return
    const t = setTimeout(() => setCombo(null), 1700)
    return () => clearTimeout(t)
  }, [combo])

  // ── Écran de fin ─────────────────────────────────────────────────────────
  const correctCount = results.filter(r => r.correct).length
  const pct = questions.length ? Math.round(correctCount / questions.length * 100) : 0
  const xpShown = useCountUp(correctCount * XP_PER_CORRECT, 550, showResult)
  const pctShown = useCountUp(pct, 700, showResult)
  const bestShown = useCountUp(bestStreak, 850, showResult)

  if (showResult) {
    const good = pct >= 70
    return (
      <div className="lesson">
        <Confetti active={pct >= 80} />
        <div className="lesson-end">
          <img className="lesson-end__judi" src={good ? JUDI.celebrate : JUDI.encourage} alt="" />
          <h1 className={`lesson-end__title${good ? '' : ' is-retry'}`}>{good ? L.doneTitle : L.retryTitle}</h1>
          <p className="lesson-end__msg">{L.doneMsg(pct)}</p>
          <div className="lesson-stats">
            <div className="lesson-stat" style={{ '--c': '#ffc800' } as React.CSSProperties}>
              <div className="lesson-stat__label">{L.xp}</div>
              <div className="lesson-stat__value">⚡ {xpShown}</div>
            </div>
            <div className="lesson-stat" style={{ '--c': '#58cc02' } as React.CSSProperties}>
              <div className="lesson-stat__label">{L.accuracy}</div>
              <div className="lesson-stat__value">🎯 {pctShown}%</div>
            </div>
            <div className="lesson-stat" style={{ '--c': '#ff9600' } as React.CSSProperties}>
              <div className="lesson-stat__label">{L.best}</div>
              <div className="lesson-stat__value">🔥 {bestShown}</div>
            </div>
          </div>
          <details className="lesson-review">
            <summary>{L.review}</summary>
            {results.map((r, i) => (
              <div key={i} className="lesson-review__item">
                <span>{r.title}</span>
                <span className={`lesson-review__tag ${r.correct ? 'is-right' : 'is-wrong'}`}>{r.correct ? L.ok : L.ko}</span>
              </div>
            ))}
          </details>
        </div>
        <div className="lesson-footer lesson-footer--end">
          <div className="lesson-footer__inner">
            <button className="lesson-btn lesson-btn--ghost" onClick={restart}>{L.retry}</button>
            <button className="lesson-btn" onClick={onBack}>{L.back}</button>
          </div>
        </div>
      </div>
    )
  }

  if (!q) return null

  // ── Écran de question ────────────────────────────────────────────────────
  const answered = current + (checked ? 1 : 0)
  const progress = Math.max(4, Math.round(answered / questions.length * 100))
  const shortOptions = shuffledQ.options.length === 4 && shuffledQ.options.every((o: string) => String(o).length <= 18)
  const correctText = shuffledQ.options[shuffledQ.answerIndex]

  return (
    <div className="lesson">
      {showArdoise && <Ardoise onClose={() => setShowArdoise(false)} />}

      <div className="lesson-top">
        <button className="lesson-icon-btn" onClick={onBack} aria-label={L.close} title={L.close}>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" /></svg>
        </button>
        <div className={`lesson-progress${streak >= 3 ? ' is-streak' : ''}`} role="progressbar" aria-valuemin={0} aria-valuemax={questions.length} aria-valuenow={answered}>
          <div className="lesson-progress__fill" style={{ width: `${progress}%` }} />
        </div>
        <div className={`lesson-streak${streak >= 2 ? ' is-on' : ''}`} aria-live="polite">🔥 {streak}</div>
      </div>

      <div className="lesson-body">
        <div className="lesson-kicker">{instructions || title}</div>

        {rawContent.image_url && (
          <div className="lesson-media">
            <img src={rawContent.image_url} alt={title} onError={e => { (e.currentTarget as HTMLImageElement).style.display = 'none' }} />
          </div>
        )}
        {!rawContent.image_url && rawContent.illustration && (
          <div className="lesson-media lesson-media--emoji" aria-hidden="true">{rawContent.illustration}</div>
        )}
        {!rawContent.image_url && !rawContent.illustration && !q.svg && !rawContent.passage && subject && (
          <div className="lesson-media lesson-media--emoji" aria-hidden="true">{getSubjectIcon(subject)}</div>
        )}
        {q.svg && (
          <div className="lesson-media lesson-media--svg" dangerouslySetInnerHTML={{ __html: q.svg }} />
        )}
        {rawContent.passage && (
          <article className="lesson-passage">
            <div className="lesson-passage__head">
              <span>{L.passage}</span>
              <button className="lesson-speak" onClick={() => MamaJudi.speakLang(rawContent.passage, ttsLang, 0.82)} aria-label={L.listen}><SpeakerIcon /></button>
            </div>
            {rawContent.passage}
          </article>
        )}

        <div className="lesson-prompt">
          <img className="lesson-prompt__judi" src={JUDI.explain} alt="" />
          <div className="lesson-bubble">
            {q.french && <div className="lesson-bubble__hint">{q.french}</div>}
            <div className="lesson-bubble__row">
              <button className="lesson-speak" onClick={() => MamaJudi.speakLangAfter(questionText || instructions, ttsLang, 100, 0.85)} aria-label={L.listen} title={L.listen}><SpeakerIcon /></button>
              <span>{questionText}</span>
            </div>
          </div>
        </div>

        <div className={`lesson-choices${shortOptions ? ' is-grid' : ''}`} role="radiogroup">
          {shuffledQ.options.map((opt: string, i: number) => {
            const isAnswer = i === shuffledQ.answerIndex
            const isChosen = selectedIdx === i
            let state = ''
            if (checked) state = isAnswer ? ' is-right' : isChosen ? ' is-wrong' : ' is-faded'
            else if (isChosen) state = ' is-selected'
            return (
              <button key={`${current}-${i}`} className={`lesson-choice${state}`} onClick={() => select(i)} disabled={checked} role="radio" aria-checked={isChosen}>
                <span className="lesson-choice__key">{i + 1}</span>
                <span>{opt}</span>
              </button>
            )
          })}
        </div>
      </div>

      <div className={`lesson-footer${checked ? (isRight ? ' is-right' : ' is-wrong') : ''}`} key={checked ? `v${current}` : `q${current}`}>
        <div className="lesson-footer__inner">
          {checked ? (
            <div className="lesson-verdict" role="status">
              <img className="lesson-verdict__judi" src={isRight ? JUDI.celebrate : JUDI.encourage} alt="" />
              <div>
                <div className="lesson-verdict__title">{isRight ? praise : L.wrong}</div>
                {!isRight && <div className="lesson-verdict__detail">{correctText}</div>}
                {q.explanation && <div className="lesson-verdict__detail">{q.explanation}</div>}
              </div>
            </div>
          ) : <span />}
          {checked ? (
            <button className={`lesson-btn${isRight ? '' : ' lesson-btn--red'}`} onClick={next} autoFocus>
              {isLast ? L.finish : L.continue}
            </button>
          ) : (
            <button ref={checkBtn} className="lesson-btn" onClick={check} disabled={selectedIdx === null}>
              {L.check}
            </button>
          )}
        </div>
      </div>

      {combo && <div key={combo.id} className="lesson-combo">🔥 {L.streak(combo.n)}</div>}

      <button className="lesson-slate" onClick={() => setShowArdoise(true)} title="Ardoise brouillon" aria-label="Ardoise brouillon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      </button>
    </div>
  )
}
