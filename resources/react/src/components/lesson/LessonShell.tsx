// LessonShell — cadre « Duolingo » des exercices à une question (vrai/faux,
// associations, remise en ordre, horloge, géométrie, droite, Venn…).
// Le moteur signale son résultat dès sa vérification ; le cadre joue le son,
// monte le bandeau avec Mama Judi, puis enregistre sur « Continuer ».
import { useEffect, useRef, useState, type ReactNode } from 'react'
import { MamaJudi } from '../../services/MamaJudi'
import { fireSuccess } from '../SuccessFx'
import Ardoise from '../../pages/child/exercises/Ardoise'
import { JUDI, XP_PER_CORRECT, labelsFor, randomPraise, playVerdict, prepareLessonAudio } from './lessonKit'

export type ReportResult = (correct: boolean, answers?: Record<string, unknown>) => void

interface Props {
  title: string
  instructions?: string
  isFrench: boolean
  onBack: () => void
  /** Enregistre la tentative (appelé sur « Continuer », une seule fois). */
  onSubmit: ReportResult
  /** Encarts optionnels au-dessus du moteur (manuel de référence, illustration). */
  extras?: ReactNode
  children: (report: ReportResult) => ReactNode
}

const SpeakerIcon = () => (
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
    <path d="M11 5 6 9H3v6h3l5 4V5z" fill="currentColor" /><path d="M15.5 8.5a5 5 0 0 1 0 7" /><path d="M18.5 5.5a9 9 0 0 1 0 13" />
  </svg>
)

export default function LessonShell({ title, instructions, isFrench, onBack, onSubmit, extras, children }: Props) {
  const L = labelsFor(isFrench)
  const lang = isFrench ? 'fr-FR' : 'en-GB'
  const [verdict, setVerdict] = useState<{ correct: boolean; answers?: Record<string, unknown>; praise: string } | null>(null)
  const [submitted, setSubmitted] = useState(false)
  const [showArdoise, setShowArdoise] = useState(false)
  const cancelVoice = useRef<() => void>(() => {})
  const footer = useRef<HTMLDivElement>(null)

  useEffect(() => {
    prepareLessonAudio()
    return () => { cancelVoice.current(); MamaJudi.stop() }
  }, [])

  const report: ReportResult = (correct, answers) => {
    if (verdict || submitted) return
    // Activité d'entraînement sans correction automatique : pas de verdict.
    if (answers?.practice_only) { setSubmitted(true); onSubmit(correct, answers); return }
    setVerdict({ correct, answers, praise: randomPraise(L) })
    cancelVoice.current = playVerdict(correct, correct ? 1 : 0)
    if (correct) {
      const rect = footer.current?.getBoundingClientRect()
      fireSuccess({ xp: XP_PER_CORRECT, x: window.innerWidth / 2, y: rect ? rect.top - 40 : window.innerHeight * 0.75 })
    }
  }

  const submit = () => {
    if (!verdict || submitted) return
    cancelVoice.current()
    setSubmitted(true)
    onSubmit(verdict.correct, verdict.answers)
  }

  useEffect(() => {
    if (!verdict) return
    const onKey = (e: KeyboardEvent) => { if (e.key === 'Enter') { e.preventDefault(); submit() } }
    window.addEventListener('keydown', onKey)
    return () => window.removeEventListener('keydown', onKey)
  }, [verdict, submitted])

  const tone = verdict ? (verdict.correct ? ' is-right' : ' is-wrong') : ''

  return (
    <div className="lesson lesson--shell">
      {showArdoise && <Ardoise onClose={() => setShowArdoise(false)} />}

      <div className="lesson-top">
        <button className="lesson-icon-btn" onClick={onBack} aria-label={L.close} title={L.close}>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" /></svg>
        </button>
        <div className="lesson-progress" role="progressbar" aria-valuemin={0} aria-valuemax={1} aria-valuenow={verdict ? 1 : 0}>
          <div className="lesson-progress__fill" style={{ width: verdict ? '100%' : '4%' }} />
        </div>
      </div>

      <div className="lesson-body lesson-body--wide">
        <div className="lesson-kicker">{title}</div>
        {extras}
        {instructions && (
          <div className="lesson-prompt">
            <img className="lesson-prompt__judi" src={JUDI.explain} alt="" />
            <div className="lesson-bubble">
              <div className="lesson-bubble__row">
                <button className="lesson-speak" onClick={() => MamaJudi.speakLangAfter(instructions, lang, 100, 0.9)} aria-label={L.listen} title={L.listen}><SpeakerIcon /></button>
                <span>{instructions}</span>
              </div>
            </div>
          </div>
        )}
        <div className={`lesson-engine${verdict ? ' is-locked' : ''}`}>{children(report)}</div>
      </div>

      {verdict && (
        <div ref={footer} className={`lesson-footer${tone}`}>
          <div className="lesson-footer__inner">
            <div className="lesson-verdict" role="status">
              <img className="lesson-verdict__judi" src={verdict.correct ? JUDI.celebrate : JUDI.encourage} alt="" />
              <div>
                <div className="lesson-verdict__title">{verdict.correct ? verdict.praise : L.notQuite}</div>
                {!verdict.correct && <div className="lesson-verdict__detail">{L.seeAbove}</div>}
              </div>
            </div>
            <button className={`lesson-btn${verdict.correct ? '' : ' lesson-btn--red'}`} onClick={submit} disabled={submitted} autoFocus>
              {L.continue}
            </button>
          </div>
        </div>
      )}

      <button className="lesson-slate" onClick={() => setShowArdoise(true)} title="Ardoise brouillon" aria-label="Ardoise brouillon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      </button>
    </div>
  )
}
