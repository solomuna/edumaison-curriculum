// Écran de fin de leçon partagé (QCM, texte à trous…) : Mama Judi animée,
// XP / précision / meilleure série en compteurs, détail repliable.
// La tentative est enregistrée sur « Continuer » (onContinue).
import { useEffect, useState } from 'react'
import Confetti from '../Confetti'
import { MamaJudi } from '../../services/MamaJudi'
import { SoundService } from '../../services/SoundService'
import { JUDI, XP_PER_CORRECT, labelsFor } from './lessonKit'

interface Props {
  isFrench: boolean
  results: { title: string; correct: boolean }[]
  total: number
  bestStreak: number
  /** Précision affichée si le score n'est pas binaire (ex. dictée) ; sinon bonnes réponses / total. */
  accuracy?: number
  /** Enregistre la tentative ; une erreur est affichée et le bouton réactivé. */
  onContinue: () => void | Promise<unknown>
}

function useCountUp(target: number, delay: number, sound = false) {
  const [value, setValue] = useState(0)
  useEffect(() => {
    let frame = 0
    let shown = 0
    const start = performance.now() + delay
    const tick = (now: number) => {
      const t = Math.min(1, Math.max(0, (now - start) / 700))
      const next = Math.round(target * (1 - Math.pow(1 - t, 3)))
      if (next !== shown) {
        shown = next
        setValue(next)
        // Cliquetis pendant que le compteur monte (limité dans SoundService.tick).
        if (sound && next > 0) SoundService.tick()
      }
      if (t < 1) frame = requestAnimationFrame(tick)
    }
    frame = requestAnimationFrame(tick)
    return () => cancelAnimationFrame(frame)
  }, [target, delay])
  return value
}

export default function LessonEnd({ isFrench, results, total, bestStreak, accuracy, onContinue }: Props) {
  const L = labelsFor(isFrench)
  const correct = results.filter(r => r.correct).length
  const pct = accuracy ?? (total ? Math.round(correct / total * 100) : 0)
  const [busy, setBusy] = useState(false)
  const [done, setDone] = useState(false)
  const [error, setError] = useState('')

  const finish = async () => {
    if (busy || done) return
    setBusy(true)
    setError('')
    try {
      await onContinue()
      setDone(true)
    } catch (reason) {
      setError(reason instanceof Error ? reason.message : L.saveError)
    } finally {
      setBusy(false)
    }
  }
  const good = pct >= 70
  const xp = useCountUp(correct * XP_PER_CORRECT, 550, true)
  const acc = useCountUp(pct, 700)
  const best = useCountUp(bestStreak, 850)

  useEffect(() => {
    MamaJudi.stop()
    const t = window.setTimeout(() => {
      if (pct === 100) { SoundService.fanfare(); MamaJudi.sessionPerfect() }
      else if (pct >= 70) { SoundService.applause(); MamaJudi.sessionGood() }
      else if (pct < 50) { SoundService.heartLost(); MamaJudi.sessionRetry() }
    }, 300)
    return () => window.clearTimeout(t)
  }, [])

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
            <div className="lesson-stat__value">⚡ {xp}</div>
          </div>
          <div className="lesson-stat" style={{ '--c': '#58cc02' } as React.CSSProperties}>
            <div className="lesson-stat__label">{L.accuracy}</div>
            <div className="lesson-stat__value">🎯 {acc}%</div>
          </div>
          <div className="lesson-stat" style={{ '--c': '#ff9600' } as React.CSSProperties}>
            <div className="lesson-stat__label">{L.best}</div>
            <div className="lesson-stat__value">🔥 {best}</div>
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
          {error && <div className="lesson-save-error" role="alert">{error}</div>}
          <button className="lesson-btn" onClick={finish} disabled={busy || done} autoFocus>{busy ? L.saving : error ? L.retrySave : L.continue}</button>
        </div>
      </div>
    </div>
  )
}
