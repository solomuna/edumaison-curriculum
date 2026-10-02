// LessonShell — cadre « Duolingo » des exercices à une question (vrai/faux,
// associations, remise en ordre, horloge, géométrie, droite, Venn…).
// Le moteur déclare quand sa réponse est prête (useLessonCheck) : le cadre
// affiche l'unique bouton VÉRIFIER en bas, puis le moteur signale son
// résultat (report) ; le cadre joue le son, monte le bandeau avec Mama Judi
// et le texte de correction, puis enregistre sur « Continuer ».
import { createContext, useCallback, useContext, useEffect, useRef, useState, type ReactNode } from 'react'
import { MamaJudi } from '../../services/MamaJudi'
import { SoundService } from '../../services/SoundService'
import { fireSuccess } from '../SuccessFx'
import Ardoise from '../../pages/child/exercises/Ardoise'
import { JUDI, XP_PER_CORRECT, labelsFor, randomPraise, playVerdict, prepareLessonAudio, type LessonLabels } from './lessonKit'

/** detail : texte de correction affiché dans le bandeau (jamais envoyé au serveur). */
export type ReportResult = (correct: boolean, answers?: Record<string, unknown>, detail?: string) => void

interface LessonContextValue {
  isFrench: boolean
  L: LessonLabels
  /** Traduction courte selon la langue de la matière. */
  t: (en: string, fr: string) => string
  registerCheck: (ready: boolean, onCheck: (() => void) | null) => void
  checked: boolean
}

const LessonContext = createContext<LessonContextValue | null>(null)

export function useLesson(): LessonContextValue {
  const ctx = useContext(LessonContext)
  if (ctx) return ctx
  const L = labelsFor(false)
  return { isFrench: false, L, t: en => en, registerCheck: () => {}, checked: false }
}

/** Le moteur confie sa vérification au bouton VÉRIFIER du cadre. */
export function useLessonCheck(ready: boolean, onCheck: () => void) {
  const { registerCheck } = useLesson()
  const fn = useRef(onCheck)
  fn.current = onCheck
  useEffect(() => { registerCheck(ready, () => fn.current()) }, [ready, registerCheck])
  useEffect(() => () => registerCheck(false, null), [registerCheck])
}

/** Petit son de sélection, commun aux moteurs. */
export const tapSound = () => SoundService.click()

interface Props {
  title: string
  instructions?: string
  isFrench: boolean
  onBack: () => void
  /** Enregistre la tentative (appelé sur « Continuer », une seule fois). */
  onSubmit: (correct: boolean, answers?: Record<string, unknown>) => void | Promise<unknown>
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
  const [verdict, setVerdict] = useState<{ correct: boolean; answers?: Record<string, unknown>; detail?: string; praise: string } | null>(null)
  const [submitted, setSubmitted] = useState(false)
  const [saving, setSaving] = useState(false)
  const [saveError, setSaveError] = useState('')
  const [showArdoise, setShowArdoise] = useState(false)
  const [check, setCheck] = useState<{ ready: boolean; fn: (() => void) | null }>({ ready: false, fn: null })
  const cancelVoice = useRef<() => void>(() => {})
  const checkBtn = useRef<HTMLButtonElement>(null)

  useEffect(() => {
    prepareLessonAudio(isFrench)
    return () => { cancelVoice.current(); MamaJudi.stop() }
  }, [])

  const registerCheck = useCallback((ready: boolean, fn: (() => void) | null) => {
    setCheck(prev => (prev.ready === ready && prev.fn === fn ? prev : { ready, fn }))
  }, [])

  const report: ReportResult = (correct, answers, detail) => {
    if (verdict || submitted) return
    // Activité d'entraînement sans correction automatique : pas de verdict.
    if (answers?.practice_only) { setSubmitted(true); onSubmit(correct, answers); return }
    setVerdict({ correct, answers, detail, praise: randomPraise(L) })
    cancelVoice.current = playVerdict(correct, correct ? 1 : 0)
    if (correct) {
      const rect = checkBtn.current?.getBoundingClientRect()
      fireSuccess({ xp: XP_PER_CORRECT, x: rect ? rect.left + rect.width / 2 : window.innerWidth / 2, y: rect ? rect.top : window.innerHeight * 0.8 })
    }
  }

  const runCheck = () => { if (!verdict && check.ready) check.fn?.() }

  const submit = async () => {
    if (!verdict || submitted || saving) return
    cancelVoice.current()
    setSaving(true)
    setSaveError('')
    try {
      await onSubmit(verdict.correct, verdict.answers)
      setSubmitted(true)
    } catch (reason) {
      setSaveError(reason instanceof Error ? reason.message : L.saveError)
    } finally {
      setSaving(false)
    }
  }

  useEffect(() => {
    const onKey = (e: KeyboardEvent) => {
      // Une touche déjà traitée (ex. Entrée qui vient de vérifier) ne doit pas
      // déclencher aussi l'action suivante : l'écouteur est réinstallé pendant l'appui.
      if (e.defaultPrevented) return
      if (e.key !== 'Enter' || showArdoise) return
      if (e.target instanceof HTMLElement && ['INPUT', 'TEXTAREA'].includes(e.target.tagName)) return
      e.preventDefault()
      verdict ? submit() : runCheck()
    }
    window.addEventListener('keydown', onKey)
    return () => window.removeEventListener('keydown', onKey)
  })

  const t = useCallback((en: string, fr: string) => (isFrench ? fr : en), [isFrench])
  const ctx: LessonContextValue = { isFrench, L, t, registerCheck, checked: !!verdict }
  const tone = verdict ? (verdict.correct ? ' is-right' : ' is-wrong') : ''

  return (
    <LessonContext.Provider value={ctx}>
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

        {(verdict || check.fn) && (
          <div className={`lesson-footer${tone}`} key={verdict ? 'verdict' : 'check'}>
            <div className="lesson-footer__inner">
              {verdict ? (
                <div className="lesson-verdict" role="status">
                  <img className="lesson-verdict__judi" src={verdict.correct ? JUDI.celebrate : JUDI.encourage} alt="" />
                  <div>
                    <div className="lesson-verdict__title">{verdict.correct ? verdict.praise : L.notQuite}</div>
                    {(verdict.detail || !verdict.correct) && (
                      <div className="lesson-verdict__detail">{verdict.detail || L.seeAbove}</div>
                    )}
                    {saveError && <div className="lesson-save-error" role="alert">{saveError}</div>}
                  </div>
                </div>
              ) : <span />}
              {verdict ? (
                <button className={`lesson-btn${verdict.correct ? '' : ' lesson-btn--red'}`} onClick={submit} disabled={submitted || saving} autoFocus>
                  {saving ? L.saving : saveError ? L.retrySave : L.continue}
                </button>
              ) : (
                <button ref={checkBtn} className="lesson-btn" onClick={runCheck} disabled={!check.ready}>{L.check}</button>
              )}
            </div>
          </div>
        )}

        <button className="lesson-slate" onClick={() => setShowArdoise(true)} title="Ardoise brouillon" aria-label="Ardoise brouillon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </button>
      </div>
    </LessonContext.Provider>
  )
}
