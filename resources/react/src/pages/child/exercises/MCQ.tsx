import { useState, useEffect, useMemo, useRef, useCallback } from 'react'
import LessonEnd from '../../../components/lesson/LessonEnd'
import { MamaJudi } from '../../../services/MamaJudi'
import { SoundService } from '../../../services/SoundService'
import { JUDI, XP_PER_CORRECT, labelsFor, randomPraise, isStreakMilestone, playVerdict, prepareLessonAudio } from '../../../components/lesson/lessonKit'
import { fireSuccess } from '../../../components/SuccessFx'
import type { ExerciseCompletionHandler, MCQContent } from '../../../types/exercise'
import { useAssetLibrary } from '../../../hooks/useAssetLibrary'
import Ardoise from './Ardoise'

interface Props {
  title: string
  instructions: string
  content: MCQContent
  subject?: string
  onComplete: ExerciseCompletionHandler
  onBack: () => void
}

interface Result { correct: boolean; title: string; question_index: number; selected_index: number }

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
  const L = labelsFor(isFrenchSubject)
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

  const cancelVoice = useRef<() => void>(() => {})
  const clearVoice = () => { cancelVoice.current(); cancelVoice.current = () => {} }

  // Sons et voix décodés à l'avance, pendant que l'enfant lit la première question.
  useEffect(() => {
    prepareLessonAudio()
    return () => { clearVoice(); MamaJudi.stop() }
  }, [])
  useEffect(() => { if (q) MamaJudi.speakLangAfter(current === 0 ? `${instructions}. ${questionText}` : questionText, ttsLang, 250) }, [current])


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
    clearVoice()
    if (correct) {
      const next = streak + 1
      setStreak(next)
      setBestStreak(b => Math.max(b, next))
      setPraise(randomPraise(L))
      if (isStreakMilestone(next)) setCombo({ id: Date.now(), n: next })
      cancelVoice.current = playVerdict(true, next)
      const rect = checkBtn.current?.getBoundingClientRect()
      fireSuccess({
        xp: XP_PER_CORRECT,
        x: rect ? rect.left + rect.width / 2 : window.innerWidth / 2,
        y: rect ? rect.top : window.innerHeight * 0.8,
      })
    } else {
      setStreak(0)
      cancelVoice.current = playVerdict(false)
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
  }, [checked, isLast])

  // La tentative est enregistrée quand l'enfant quitte l'écran de fin :
  // le parent change d'écran dès l'enregistrement, l'enfant doit d'abord voir son bilan.
  const submit = () => {
    const total = results.filter(r => r.correct).length
    return onComplete(Math.round(total / questions.length * 100), {
      verification_status: 'auto_checked',
      answers: { items: results.map(result => ({ question_index: result.question_index, selected_index: result.selected_index })) },
      evidence: { method: 'answer_key' },
    })
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

  if (showResult) {
    return <LessonEnd isFrench={isFrenchSubject} results={results} total={questions.length} bestStreak={bestStreak} onContinue={submit} />
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
