import { useState, useEffect, useRef } from 'react'
import { MamaJudi } from '../../../services/MamaJudi'
import { fireSuccess } from '../../../components/SuccessFx'
import LessonEnd from '../../../components/lesson/LessonEnd'
import { useRetryQueue } from '../../../components/lesson/useRetryQueue'
import { JUDI, XP_PER_CORRECT, labelsFor, randomPraise, isStreakMilestone, playVerdict, prepareLessonAudio, exactAnswer, diffAnswer } from '../../../components/lesson/lessonKit'
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

/** Bonne réponse avec ce qui manque surligné, puis la réponse de l'enfant avec ce qui est en trop barré. */
function AnswerDiff({ expected, actual, youWrote }: { expected: string; actual: string; youWrote: string }) {
  const diff = diffAnswer(expected, actual)
  const show = (text: string) => text.replace(/ /g, ' ')
  return (
    <>
      <div className="lesson-verdict__detail lesson-diff">
        {diff.expected.map((part, k) => part.ok
          ? <span key={k}>{show(part.text)}</span>
          : <mark key={k} className="lesson-diff__miss">{part.text === ' ' ? '␣' : show(part.text)}</mark>)}
      </div>
      {actual && (
        <div className="lesson-verdict__detail lesson-diff">
          {youWrote}{' '}
          {diff.actual.map((part, k) => part.ok
            ? <span key={k}>{show(part.text)}</span>
            : <del key={k} className="lesson-diff__extra">{part.text === ' ' ? '␣' : show(part.text)}</del>)}
        </div>
      )}
    </>
  )
}

const SpeakerIcon = () => (
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
    <path d="M11 5 6 9H3v6h3l5 4V5z" fill="currentColor" /><path d="M15.5 8.5a5 5 0 0 1 0 7" /><path d="M18.5 5.5a9 9 0 0 1 0 13" />
  </svg>
)


export default function FillIn({ title, instructions, content, isFrench = false, onComplete, onBack }: Props) {
  const [lastAnswer, setLastAnswer] = useState('')
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
  // Phrases ratées reprises en fin de leçon ; seule la 1re réponse est notée.
  const rq = useRetryQueue(items.length)
  const current = rq.current
  const item = items[current]
  const text = item?.prompt || item?.text || item?.sentence || ''
  const parts = text.split('___')
  const spoken = text.replace('___', C.blank)
  const isLast = rq.isLastStep
  // Indice : lettres seulement (« Goodbye! » -> G, 7 lettres)
  const answerLetters = (item?.answer || '').match(/[\p{L}\p{N}]/gu) ?? []
  const hintFirst = answerLetters[0] || ''

  useEffect(() => {
    prepareLessonAudio(isFrench)
    return () => { cancelVoice.current(); MamaJudi.stop() }
  }, [])

  useEffect(() => {
    setInput('')
    setFeedback(null)
    setHintUsed(false)
    if (item) MamaJudi.speakLangAfter(rq.step === 0 && instructions ? `${instructions}. ${spoken}` : spoken, lang, 250, 0.85)
  }, [rq.step])

  const check = () => {
    if (feedback || !input.trim() || !item) return
    // Réponse exacte exigée, comme au serveur : majuscules, accents et ponctuation comptent.
    const answer = exactAnswer(input)
    const correct = answer === exactAnswer(item.answer) || (item.alternatives || []).map(exactAnswer).includes(answer)
    setFeedback(correct ? 'correct' : 'wrong')
    setLastAnswer(input.trim())
    if (!rq.isRetry) {
      setScores(s => [...s, correct])
      setResponses(r => [...r, input.trim()])
    }
    rq.record(correct)
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
    if (!isLast) rq.advance()
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
    setInput(answerLetters[0] || '')
  }

  useEffect(() => {
    if (!feedback || done) return
    const onKey = (e: KeyboardEvent) => {
      // Une touche déjà traitée (ex. Entrée qui vient de vérifier) ne doit pas
      // déclencher aussi l'action suivante : l'écouteur est réinstallé pendant l'appui.
      if (e.defaultPrevented) return
      if (e.key === 'Enter') { e.preventDefault(); next() }
    }
    window.addEventListener('keydown', onKey)
    return () => window.removeEventListener('keydown', onKey)
  }, [feedback, done, rq.step])

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

  const answered = rq.resolvedCount
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

        {rq.isRetry && <div className="lesson-retry-tag">🔁 {L.again}</div>}
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
                    enterKeyHint="go"
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
            <div className="lesson-hint is-used">💡 {C.hint} : {C.hintText(hintFirst, answerLetters.length)}</div>
          ) : (
            <button className="lesson-hint" onClick={revealHint}>💡 {C.hint} — {C.hintText(hintFirst, answerLetters.length)}</button>
          )
        )}
      </div>

      <div className={`lesson-footer${feedback ? (isRight ? ' is-right' : ' is-wrong') : ''}`} key={feedback ? `v${rq.step}` : `q${rq.step}`}>
        <div className="lesson-footer__inner">
          {feedback ? (
            <div className="lesson-verdict" role="status">
              <img className="lesson-verdict__judi" src={isRight ? JUDI.celebrate : JUDI.encourage} alt="" />
              <div>
                <div className="lesson-verdict__title">{isRight ? praise : L.wrong}</div>
                {!isRight && <AnswerDiff expected={exactAnswer(item.answer)} actual={exactAnswer(lastAnswer)} youWrote={C.youWrote} />}
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
