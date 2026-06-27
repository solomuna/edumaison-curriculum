// RemediationPage.tsx — Plan de remédiation pour les matières faibles
// Déclenché quand avg < 12/20 sur school_results
import { useState, useEffect } from 'react'
import type { Child } from '../../types/child'
import ExercisePlayer from './ExercisePlayer'
import { saveAttempt } from '../../services/api'

// ── Types ──────────────────────────────────────────────────────────────────────
interface Plan {
  subject_id:  number
  subject:     string
  average:     number
  appreciation:string
  priority:    'critical' | 'high' | 'medium'
  target:      number
  exercises:   any[]
  done_count:  number
  total_count: number
  pct_done:    number
  tips:        string[]
}

interface RemediationData {
  status:     string
  child_name: string
  plans:      Plan[]
}

interface Props {
  child:  Child
  onBack: () => void
}

// ── Couleurs par priorité ──────────────────────────────────────────────────────
const PRIORITY_STYLE = {
  critical: { bg: '#FEE2E2', accent: '#DC2626', badge: '#FCA5A5', label: '\uD83D\uDEA8 Critical' },
  high:     { bg: '#FEF3C7', accent: '#D97706', badge: '#FCD34D', label: '\u26A0\uFE0F High'     },
  medium:   { bg: '#DBEAFE', accent: '#2563EB', badge: '#93C5FD', label: '\uD83D\uDCDA Medium'   },
}

// ── Composant ──────────────────────────────────────────────────────────────────
export default function RemediationPage({ child, onBack }: Props) {
  const [data,         setData        ] = useState<RemediationData | null>(null)
  const [loading,      setLoading     ] = useState(true)
  const [activePlan,   setActivePlan  ] = useState<Plan | null>(null)
  const [activeEx,     setActiveEx    ] = useState<any | null>(null)
  const [sessionIdx,   setSessionIdx  ] = useState(0)
  const [sessionScores,setSessionScores] = useState<boolean[]>([])
  const [sessionDone,  setSessionDone ] = useState(false)

  // Charger le plan de remédiation
  useEffect(() => {
    fetch(`/api/remediation/child/${child.id}`)
      .then(r => r.json())
      .then(setData)
      .catch(() => {})
      .finally(() => setLoading(false))
  }, [child.id])

  // Démarrer une session d'exercices pour une matière
  const startSession = (plan: Plan) => {
    setActivePlan(plan)
    setSessionIdx(0)
    setSessionScores([])
    setSessionDone(false)
    setActiveEx(plan.exercises[0] || null)
  }

  // Fin d'un exercice — enregistre le score, passe au suivant
  const handleComplete = async (score: number) => {
    if (!activePlan || !activeEx) return
    await saveAttempt(child.id, activeEx.id, score)
    const ok = score > 0
    const ns = [...sessionScores, ok]
    setSessionScores(ns)
    if (sessionIdx < activePlan.exercises.length - 1) {
      const next = sessionIdx + 1
      setSessionIdx(next)
      setActiveEx(activePlan.exercises[next])
    } else {
      setSessionDone(true)
      setActiveEx(null)
    }
  }

  // ── Vue : exercice en cours ──────────────────────────────────────────────────
  if (activeEx && !sessionDone) {
    return (
      <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito,system-ui,sans-serif' }}>
        {/* Header exercice */}
        <div style={{ background: 'var(--white)', padding: '14px 18px', borderBottom: '2px solid #F0E4D8', display: 'flex', alignItems: 'center', gap: 12 }}>
          <button
            onClick={() => { setActiveEx(null); setActivePlan(null) }}
            style={{ background: 'var(--card)', border: '1.5px solid #D0C8B8', borderRadius: 10, padding: '7px 14px', fontSize: 13, fontWeight: 700, color: '#C47A3C', cursor: 'pointer' }}>
            {'\u2190'} Back
          </button>
          <div style={{ flex: 1 }}>
            <div style={{ fontSize: 13, fontWeight: 900, color: 'var(--text-dark)' }}>
              {activePlan?.subject} {'\u2014'} Remediation
            </div>
            <div style={{ fontSize: 11, color: 'var(--text-soft)' }}>
              Exercise {sessionIdx + 1} / {activePlan?.exercises.length}
            </div>
          </div>
        </div>

        {/* Barre de progression session */}
        <div style={{ height: 4, background: '#E8DCC8' }}>
          <div style={{
            height: 4, background: '#1D6B2A',
            width: `${Math.round(((sessionIdx) / (activePlan?.exercises.length || 1)) * 100)}%`,
            transition: 'width .3s'
          }} />
        </div>

        {/* Lecteur d'exercice */}
        <ExercisePlayer
          exercise={activeEx}
          child={child}
          onComplete={handleComplete}
        />
      </div>
    )
  }

  // ── Vue : résultats de session ───────────────────────────────────────────────
  if (sessionDone && activePlan) {
    const correct = sessionScores.filter(Boolean).length
    const total   = sessionScores.length
    const pct     = Math.round((correct / total) * 100)
    const great   = pct >= 70

    return (
      <div style={{ background: 'var(--bg)', minHeight: '100vh', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: 24, fontFamily: 'Nunito,system-ui,sans-serif' }}>
        <div style={{ background: 'white', borderRadius: 24, padding: 32, maxWidth: 500, width: '100%', textAlign: 'center', boxShadow: '0 4px 24px rgba(0,0,0,.08)' }}>
          <div style={{ fontSize: 52, marginBottom: 12 }}>
            {great ? '\uD83C\uDF1F' : '\uD83D\uDCAA'}
          </div>
          <div style={{ fontSize: 22, fontWeight: 900, color: '#3D2B1F', marginBottom: 6 }}>
            {great ? 'Great work!' : 'Keep it up!'}
          </div>
          <div style={{ fontSize: 15, color: '#7A6050', marginBottom: 20 }}>
            {correct} / {total} correct &mdash; {pct}%
          </div>

          {/* Mini résultats */}
          <div style={{ display: 'flex', gap: 6, justifyContent: 'center', marginBottom: 24, flexWrap: 'wrap' }}>
            {sessionScores.map((ok, i) => (
              <span key={i} style={{
                width: 28, height: 28, borderRadius: 8, display: 'flex', alignItems: 'center', justifyContent: 'center',
                background: ok ? '#D1FAE5' : '#FEE2E2', fontSize: 14, fontWeight: 900
              }}>
                {ok ? '\u2713' : '\u2715'}
              </span>
            ))}
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
            <button
              onClick={() => startSession(activePlan)}
              style={{ padding: '12px 0', borderRadius: 14, border: 'none', background: '#1D6B2A', color: 'white', fontSize: 14, fontWeight: 800, cursor: 'pointer' }}>
              Practice again
            </button>
            <button
              onClick={() => { setSessionDone(false); setActivePlan(null) }}
              style={{ padding: '12px 0', borderRadius: 14, border: '1.5px solid #D0C8B8', background: 'transparent', color: '#7A6050', fontSize: 14, fontWeight: 700, cursor: 'pointer' }}>
              Back to plan
            </button>
          </div>
        </div>
      </div>
    )
  }

  // ── Vue principale : liste des plans ─────────────────────────────────────────
  return (
    <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito,system-ui,sans-serif' }}>

      {/* Header */}
      <div style={{ background: 'white', padding: '14px 18px', borderBottom: '2px solid #F0E4D8', display: 'flex', alignItems: 'center', gap: 12 }}>
        <button
          onClick={onBack}
          style={{ background: 'var(--card)', border: '1.5px solid #D0C8B8', borderRadius: 10, padding: '7px 14px', fontSize: 13, fontWeight: 700, color: '#C47A3C', cursor: 'pointer' }}>
          {'\u2190'}
        </button>
        <div>
          <div style={{ fontSize: 16, fontWeight: 900, color: '#3D2B1F' }}>Remediation Plan</div>
          <div style={{ fontSize: 12, color: '#7A6050' }}>{child.first_name} {'\u2014'} Subjects needing extra practice</div>
        </div>
      </div>

      {/* Chargement */}
      {loading && (
        <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: 200 }}>
          <div style={{ fontSize: 14, color: '#7A6050', fontWeight: 700 }}>Loading plan...</div>
        </div>
      )}

      {/* Aucune matière faible */}
      {!loading && data?.status === 'excellent' && (
        <div style={{ padding: 32, textAlign: 'center' }}>
          <div style={{ fontSize: 48, marginBottom: 12 }}>{'\uD83C\uDF1F'}</div>
          <div style={{ fontSize: 18, fontWeight: 900, color: '#3D2B1F', marginBottom: 8 }}>Excellent results!</div>
          <div style={{ fontSize: 13, color: '#7A6050' }}>No remediation needed. Keep up the great work!</div>
        </div>
      )}

      {/* Plans de remédiation */}
      {!loading && data?.plans && data.plans.length > 0 && (
        <div style={{ padding: '16px 18px' }}>
          <div style={{ fontSize: 13, color: '#8A6050', marginBottom: 16, lineHeight: 1.6 }}>
            Based on <strong>{data.child_name}</strong>&apos;s school results, these subjects need extra practice:
          </div>

          {data.plans.map(plan => {
            const ps = PRIORITY_STYLE[plan.priority]
            return (
              <div key={plan.subject_id} style={{ background: ps.bg, borderRadius: 20, padding: '18px 16px', marginBottom: 14, border: `2px solid ${ps.badge}` }}>

                {/* En-tête matière */}
                <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 12 }}>
                  <div style={{ flex: 1 }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 4 }}>
                      <span style={{ fontSize: 16, fontWeight: 900, color: '#3D2B1F' }}>{plan.subject}</span>
                      <span style={{ background: ps.badge, color: ps.accent, fontSize: 10, fontWeight: 800, padding: '2px 8px', borderRadius: 8 }}>
                        {ps.label}
                      </span>
                    </div>
                    <div style={{ fontSize: 12, color: '#6B7280' }}>
                      Current: <strong style={{ color: ps.accent }}>{plan.average.toFixed(1)}/20</strong>
                      {' '}&rarr; Target: <strong style={{ color: '#059669' }}>{plan.target}/20</strong>
                    </div>
                  </div>
                </div>

                {/* Barre de progression exercices */}
                <div style={{ marginBottom: 12 }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 11, color: '#6B7280', marginBottom: 4 }}>
                    <span>Exercises completed</span>
                    <span>{plan.done_count} / {plan.total_count} &middot; {plan.pct_done}%</span>
                  </div>
                  <div style={{ height: 6, background: 'rgba(255,255,255,0.6)', borderRadius: 3 }}>
                    <div style={{ height: 6, borderRadius: 3, background: ps.accent, width: `${plan.pct_done}%`, transition: 'width 0.4s' }} />
                  </div>
                </div>

                {/* Conseils */}
                <div style={{ marginBottom: 14 }}>
                  {plan.tips.map((tip, i) => (
                    <div key={i} style={{ fontSize: 12, color: '#374151', marginBottom: 5, display: 'flex', gap: 6, alignItems: 'flex-start' }}>
                      <span style={{ flexShrink: 0 }}>{'\uD83D\uDCA1'}</span>
                      <span>{tip}</span>
                    </div>
                  ))}
                </div>

                {/* Bouton démarrer */}
                {plan.exercises.length > 0 ? (
                  <button
                    onClick={() => startSession(plan)}
                    style={{ width: '100%', padding: '12px 0', borderRadius: 14, border: 'none', background: ps.accent, color: 'white', fontSize: 14, fontWeight: 800, cursor: 'pointer' }}>
                    {'\u25B6'} Start {plan.exercises.length} practice exercise{plan.exercises.length > 1 ? 's' : ''}
                  </button>
                ) : (
                  <div style={{ textAlign: 'center', fontSize: 12, color: '#6B7280', padding: '8px 0' }}>
                    {'\u2705'} All exercises completed for this subject!
                  </div>
                )}
              </div>
            )
          })}
        </div>
      )}

      {/* Erreur ou pas de données */}
      {!loading && !data && (
        <div style={{ padding: 32, textAlign: 'center', color: '#7A6050', fontSize: 13 }}>
          Unable to load remediation plan. Check your connection.
        </div>
      )}
    </div>
  )
}
