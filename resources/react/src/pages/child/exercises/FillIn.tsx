import { useState, useEffect, useRef } from 'react'
import { MamaJudi } from '../../../services/MamaJudi'
import { fireSuccess } from '../../../components/SuccessFx'
import LessonEnd from '../../../components/lesson/LessonEnd'
import { JUDI, XP_PER_CORRECT, labelsFor, randomPraise, isStreakMilestone, playVerdict, prepareLessonAudio } from '../../../components/lesson/lessonKit'
import type { ExerciseCompletionHandler } from '../../../types/exercise'

interface FillInItem {
  prompt?: string
  text?: string
  sentence?: string
  answer: string
  alternatives?: string[]
}

interface FillInContent {
  type: 'fill_in'
  illustration?: string
  items?: FillInItem[]
  sentences?: FillInItem[]
}

interface Props {
  title: string
  instructions: string
  content: FillInContent
  isFrench?: boolean
  onComplete: ExerciseCompletionHandler
  onBack: () => void
}

const COPY = {
  en: { complete: 'Complete the sentence', hint: 'Hint', hintText: (l: string, n: number) => `starts with ${l} (${n} letters)`, youWrote: 'You wrote:', blank: 'blank' },
  fr: { complete: 'Complète la phrase', hint: 'Indice', hintText: (l: string, n: number) => `commence par ${l} (${n} lettres)`, youWrote: 'Tu as écrit :', blank: 'blanc' },
}

const SpeakerIcon = () => (
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
    <path d="M11 5 6 9H3v6h3l5 4V5z" fill="currentColor" /><path d="M15.5 8.5a5 5 0 0 1 0 7" /><path d="M18.5 5.5a9 9 0 0 1 0 13" />
  </svg>
)

const normalized = (s: string) => s.trim().toLowerCase().replace(/[‘’]/g, "'")

export default function FillIn({ title, instructions, content, isFrench = false, onComplete, onBack }: Props) {
  const [current, setCurrent] = useState(0)
  const [input, setInput] = useState('')
  const [feedback, setFeedback] = useState<'correct' | 'wrong' | null>(null)
  const [scores, setScores] = useState<boolean[]>([])
  const [responses, setResponses] = useState<string[]>([])
  const [done, setDone] = useState(false)
  const [hintUsed, setHintUsed] = useState(false)
  const [streak, setStreak] = useState(0)
  const [bestStreak, setBestStreak] = useState(0)
  const [combo, setCombo] = useState<{ id: number; n: number } | null>(null)
  const [praise, setPraise] = useState('')
  const cancelVoice = useRef<() => void>(() => {})
  const checkBtn = useRef<HTMLButtonElement>(null)
  const L = labelsFor(isFrench)
  const C = isFrench ? COPY.fr : COPY.en
  const lang = isFrench ? 'fr-FR' : 'en-GB'

  // Format plat {sentence, answer} sans tableau items[]
  const rawContent = content as any
  const flatItem: FillInItem[] = rawContent.sentence ? [{ sentence: rawContent.sentence, answer: rawContent.answer, alternatives: rawContent.alternatives }] : []
  const items: FillInItem[] = content.items || content.sentences || flatItem
  const item = items[current]
  const text = item?.prompt || item?.text || item?.sentence || ''
  const parts = text.split('___')
  const spoken = text.replace('___', C.blank)
  const isLast = current === items.length - 1

  useEffect(() => {
    prepareLessonAudio()
    return () => { cancelVoice.current(); MamaJudi.stop() }
  }, [])

  useEffect(() => {
    setInput('')
    setFeedback(null)
    setHintUsed(false)
    if (item) MamaJudi.speakLangAfter(current === 0 && instructions ? `${instructions}. ${spoken}` : spoken, lang, 250, 0.85)
  }, [current])

  const check = () => {
    if (feedback || !input.trim() || !item) return
    const answer = normalized(input)
    const correct = answer === normalized(item.answer) || (item.alternatives || []).map(normalized).includes(answer)
    setFeedback(correct ? 'correct' : 'wrong')
    setScores(s => [...s, correct])
    setResponses(r => [...r, input.trim()])
    cancelVoice.current()
    if (correct) {
      const next = streak + 1
      setStreak(next)
      setBestStreak(b => Math.max(b, next))
      setPraise(randomPraise(L))
      if (isStreakMilestone(next)) setCombo({ id: Date.now(), n: next })
      cancelVoice.current = playVerdict(true, next)
      const rect = checkBtn.current?.getBoundingClientRect()
      fireSuccess({ xp: XP_PER_CORRECT, x: rect ? rect.left + rect.width / 2 : window.innerWidth / 2, y: rect ? rect.top : window.innerHeight * 0.8 })
    } else {
      setStreak(0)
      cancelVoice.current = playVerdict(false)
    }
  }

  const next = () => {
    if (!feedback) return
    cancelVoice.current()
    if (!isLast) setCurrent(c => c + 1)
    else setDone(true)
  }

  // Enregistrement sur « Continuer » depuis l'écran de fin (voir LessonEnd).
  const submit = () => {
    const total = scores.filter(Boolean).length
    return onComplete(Math.round((total / items.length) * 100), {
      verification_status: 'auto_checked',
      answers: { items: responses },
      evidence: { method: 'server_answer_key' },
    })
  }

  const revealHint = () => {
    if (!item?.answer) return
    setHintUsed(true)
    setInput(item.answer[0])
  }

  useEffect(() => {
    if (!feedback || done) return
    const onKey = (e: KeyboardEvent) => { if (e.key === 'Enter') { e.preventDefault(); next() } }
    window.addEventListener('keydown', onKey)
    return () => window.removeEventListener('keydown', onKey)
  }, [feedback, done, current])

  useEffect(() => {
    if (!combo) return
    const t = setTimeout(() => setCombo(null), 1700)
    return () => clearTimeout(t)
  }, [combo])

  if (done) {
    return (
      <LessonEnd
        isFrench={isFrench}
        results={items.map((it, i) => ({ title: (it.prompt || it.text || it.sentence || '').replace('___', '…'), correct: !!scores[i] }))}
        total={items.length}
        bestStreak={bestStreak}
       
        onContinue={submit}
      />
    )
  }

  if (!item) return null

  const answered = current + (feedback ? 1 : 0)
  const progress = Math.max(4, Math.round(answered / items.length * 100))
  const isRight = feedback === 'correct'

  return (
    <div className="lesson">
      <div className="lesson-top">
        <button className="lesson-icon-btn" onClick={onBack} aria-label={L.close} title={L.close}>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" /></svg>
        </button>
        <div className={`lesson-progress${streak >= 3 ? ' is-streak' : ''}`} role="progressbar" aria-valuemin={0} aria-valuemax={items.length} aria-valuenow={answered}>
          <div className="lesson-progress__fill" style={{ width: `${progress}%` }} />
        </div>
        <div className={`lesson-streak${streak >= 2 ? ' is-on' : ''}`} aria-live="polite">🔥 {streak}</div>
      </div>

      <div className="lesson-body">
        <div className="lesson-kicker">{C.complete}</div>
        {content.illustration && <div className="lesson-media lesson-media--emoji" aria-hidden="true">{content.illustration}</div>}

        <div className="lesson-prompt">
          <img className="lesson-prompt__judi" src={JUDI.explain} alt="" />
          <div className="lesson-bubble">
            {instructions && <div className="lesson-bubble__hint">{instructions}</div>}
            <div className="lesson-bubble__row">
              <button className="lesson-speak" onClick={() => MamaJudi.speakLangAfter(spoken, lang, 100, 0.85)} aria-label={L.listen} title={L.listen}><SpeakerIcon /></button>
              <span className="lesson-fill__sentence">
                <span>{parts[0]}</span>
                {feedback ? (
                  <span className={`lesson-fill__slot ${isRight ? 'is-right' : 'is-wrong'}`}>{isRight ? input.trim() : item.answer}</span>
                ) : (
                  <input
                    className="lesson-fill__input"
                    value={input}
                    onChange={e => setInput(e.target.value)}
                    onKeyDown={e => { if (e.key === 'Enter') { e.preventDefault(); check() } }}
                    placeholder="…"
                    autoFocus
                    autoCapitalize="off"
                    autoCorrect="off"
                    spellCheck={false}
                    aria-label={C.complete}
                    style={{ width: `${Math.max(4, input.length + 2)}ch` }}
                  />
                )}
                {parts[1] && <span>{parts[1]}</span>}
              </span>
            </div>
          </div>
        </div>

        {!feedback && (
          hintUsed ? (
            <div className="lesson-hint is-used">💡 {C.hint} : {C.hintText((item.answer || '')[0]?.toUpperCase() || '', item.answer?.length || 0)}</div>
          ) : (
            <button className="lesson-hint" onClick={revealHint}>💡 {C.hint} — {C.hintText((item.answer || '')[0]?.toUpperCase() || '', item.answer?.length || 0)}</button>
          )
        )}
      </div>

      <div className={`lesson-footer${feedback ? (isRight ? ' is-right' : ' is-wrong') : ''}`} key={feedback ? `v${current}` : `q${current}`}>
        <div className="lesson-footer__inner">
          {feedback ? (
            <div className="lesson-verdict" role="status">
              <img className="lesson-verdict__judi" src={isRight ? JUDI.celebrate : JUDI.encourage} alt="" />
              <div>
                <div className="lesson-verdict__title">{isRight ? praise : L.wrong}</div>
                {!isRight && <div className="lesson-verdict__detail">{item.answer}</div>}
                {!isRight && responses[current] && <div className="lesson-verdict__detail">{C.youWrote} <s>{responses[current]}</s></div>}
              </div>
            </div>
          ) : <span />}
          {feedback ? (
            <button className={`lesson-btn${isRight ? '' : ' lesson-btn--red'}`} onClick={next} autoFocus>
              {isLast ? L.finish : L.continue}
            </button>
          ) : (
            <button ref={checkBtn} className="lesson-btn" onClick={check} disabled={!input.trim()}>{L.check}</button>
          )}
        </div>
      </div>

      {combo && <div key={combo.id} className="lesson-combo">🔥 {L.streak(combo.n)}</div>}
    </div>
  )
}
