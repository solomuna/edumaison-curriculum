import { useState, useEffect } from 'react'
import type { Child } from '../../types/child'
import ExercisePlayer from './ExercisePlayer'
import { saveAttempt } from '../../services/api'

interface Plan {
  subject_id: number
  subject: string
  average: number
  appreciation: string
  priority: 'critical' | 'high' | 'medium'
  target: number
  exercises: any[]
  done_count: number
  total_count: number
  pct_done: number
  tips: string[]
}

interface RemediationData {
  status: string
  child_name: string
  plans: Plan[]
}

const PRIORITY_STYLE = {
  critical: { bg: '#FEE2E2', accent: '#DC2626', badge: '#FCA5A5', label: '🚨 Critical' },
  high:     { bg: '#FEF3C7', accent: '#D97706', badge: '#FCD34D', label: '⚠️ High' },
  medium:   { bg: '#DBEAFE', accent: '#2563EB', badge: '#93C5FD', label: '📚 Medium' },
}

interface Props {
  child: Child
  onBack: () => void
}

export default function RemediationPage({ child, onBack }: Props) {
  const [data, setData] = useState<RemediationData | null>(null)
  const [loading, setLoading] = useState(true)
  const [activePlan, setActivePlan] = useState<Plan | null>(null)
  const [activeEx, setActiveEx] = useState<any | null>(null)
  const [sessionIdx, setSessionIdx] = useState(0)
  const [sessionScores, setSessionScores] = useState<boolean[]>([])
  const [sessionDone, setSessionDone] = useState(false)

  useEffect(() => {
    fetch(`/api/remediation/child/${child.id}`)
      .then(r => r.json()).then(setData).catch(() => {}).finally(() => setLoading(false))
  }, [child.id])

  const startSession = (plan: Plan) => {
    setActivePlan(plan)
    setSessionIdx(0)
    setSessionScores([])
    setSessionDone(false)
    setActiveEx(plan.exercises[0] || null)
  }

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

  // Exercise player
  if (activeEx && !sessionDone) {
    return (
      <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'system-ui,sans-serif' }}>
        <div style={{ background: 'var(--white)', padding: '14px 18px', borderBottom: '2px solid #F0E4D8', display: 'flex', alignItems: 'center', gap: 12 }}>
          <button onClick={() => { setActiveEx(null); setActivePlan(null) }}
            style={{ background: 'var(--card)', border: '1.5px solid #D0C8B8', borderRadius: 10, padding: '7px 14px', fontSize: 13, fontWeight: 700, color: '#C47A3C', cursor: 'pointer' }}>
            ← Stop
          </button>
          <div style={{ flex: 1 }}>
            <div style={{ fontSize: 14, fontWeight: 800, color: 'var(--text-dark)' }}>Remediation — {activePlan?.subject}</div>
            <div style={{ fontSize: 11, color: 'var(--text-soft)' }}>Q {sessionIdx + 1}/{activePlan?.exercises.length}</div>
          </div>
          <div style={{ display: 'flex', gap: 5 }}>
            {activePlan?.exercises.map((_, i) => (
              <div key={i} style={{ width: 10, height: 10, borderRadius: '50%', background: i < sessionScores.length ? (sessionScores[i] ? '#10B981' : '#EF4444') : i === sessionIdx ? '#F59E0B' : '#E0D4CA' }}/>
            ))}
          </div>
        </div>
        <div style={{ padding: '16px 18px' }}>
          <ExercisePlayer exercise={activeEx} onComplete={handleComplete} onBack={() => { setActiveEx(null); setActivePlan(null) }} />
        </div>
      </div>
    )
  }

  // Session result
  if (sessionDone && activePlan) {
    const good = sessionScores.filter(Boolean).length
    const pct = Math.round(good / sessionScores.length * 100)
    return (
      <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'system-ui,sans-serif', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: 24, textAlign: 'center' }}>
        <div style={{ fontSize: 56, marginBottom: 16 }}>{pct >= 70 ? '🌟' : '💪'}</div>
        <div style={{ fontSize: 22, fontWeight: 900, color: 'var(--text-dark)', marginBottom: 8 }}>
          {pct >= 70 ? 'Great progress!' : 'Keep going!'}
        </div>
        <div style={{ fontSize: 15, color: '#8A6050', marginBottom: 28 }}>{activePlan.subject} remediation session</div>
        <div style={{ display: 'flex', gap: 20, marginBottom: 32 }}>
          <div style={{ background: '#FEF3C7', borderRadius: 16, padding: '18px 28px', textAlign: 'center' }}>
            <div style={{ fontSize: 36, fontWeight: 900, color: '#F59E0B' }}>{good}/{sessionScores.length}</div>
            <div style={{ fontSize: 12, color: '#D97706', marginTop: 4 }}>Correct</div>
          </div>
          <div style={{ background: '#D1FAE5', borderRadius: 16, padding: '18px 28px', textAlign: 'center' }}>
            <div style={{ fontSize: 36, fontWeight: 900, color: '#10B981' }}>{pct}%</div>
            <div style={{ fontSize: 12, color: '#059669', marginTop: 4 }}>Score</div>
          </div>
        </div>
        <div style={{ display: 'flex', gap: 12 }}>
          <button onClick={() => { setActivePlan(null); setSessionDone(false) }}
            style={{ padding: '13px 24px', borderRadius: 14, border: '1.5px solid #F0E4D8', background: 'var(--white)', color: 'var(--text-dark)', fontSize: 14, fontWeight: 700, cursor: 'pointer' }}>
            ← Plans
          </button>
          <button onClick={() => startSession(activePlan)}
            style={{ padding: '13px 24px', borderRadius: 14, border: 'none', background: '#1D6B2A', color: 'white', fontSize: 14, fontWeight: 800, cursor: 'pointer' }}>
            Practice again 🔄
          </button>
        </div>
      </div>
    )
  }

  return (
    <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'system-ui,sans-serif', paddingBottom: 40 }}>
      <div style={{ padding: '14px 18px', display: 'flex', alignItems: 'center', gap: 12, borderBottom: '1px solid #F0E4D8', background: 'var(--white)' }}>
        <button onClick={onBack} style={{ background: 'var(--card)', border: '1.5px solid #D0C8B8', borderRadius: 10, padding: '7px 14px', fontSize: 13, fontWeight: 700, color: '#C47A3C', cursor: 'pointer' }}>← Back</button>
        <div>
          <div style={{ fontSize: 15, fontWeight: 900, color: 'var(--text-dark)' }}>📋 Remediation Plan</div>
          <div style={{ fontSize: 12, color: 'var(--text-soft)' }}>{data?.child_name}</div>
        </div>
      </div>

      {loading && <div style={{ padding: '40px', textAlign: 'center', color: 'var(--text-soft)' }}>Loading...</div>}

      {!loading && data?.status === 'excellent' && (
        <div style={{ padding: '60px 18px', textAlign: 'center' }}>
          <div style={{ fontSize: 56, marginBottom: 16 }}>🏆</div>
          <div style={{ fontSize: 18, fontWeight: 900, color: 'var(--text-dark)', marginBottom: 8 }}>Excellent results!</div>
          <div style={{ fontSize: 13, color: 'var(--text-soft)' }}>No remediation needed. Keep up the great work!</div>
        </div>
      )}

      {!loading && data?.plans && data.plans.length > 0 && (
        <div style={{ padding: '16px 18px' }}>
          <div style={{ fontSize: 13, color: '#8A6050', marginBottom: 16, lineHeight: 1.5 }}>
            Based on {data.child_name}'s school results, here are the subjects that need extra practice:
          </div>

          {data.plans.map(plan => {
            const ps = PRIORITY_STYLE[plan.priority]
            return (
              <div key={plan.subject_id} style={{ background: ps.bg, borderRadius: 20, padding: '18px 16px', marginBottom: 14, border: `2px solid ${ps.badge}` }}>
                {/* Header */}
                <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 12 }}>
                  <div style={{ flex: 1 }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 4 }}>
                      <span style={{ fontSize: 16, fontWeight: 900, color: 'var(--text-dark)' }}>{plan.subject}</span>
                      <span style={{ background: ps.badge, color: ps.accent, fontSize: 10, fontWeight: 800, padding: '2px 8px', borderRadius: 8 }}>{ps.label}</span>
                    </div>
                    <div style={{ fontSize: 12, color: '#6B7280' }}>
                      Current: <strong style={{ color: ps.accent }}>{plan.average.toFixed(2)}/20</strong> → Target: <strong style={{ color: '#059669' }}>{plan.target}/20</strong>
                    </div>
                  </div>
                </div>

                {/* Progress */}
                <div style={{ marginBottom: 12 }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 11, color: '#6B7280', marginBottom: 4 }}>
                    <span>Exercises completed</span>
                    <span>{plan.done_count}/{plan.total_count} · {plan.pct_done}%</span>
                  </div>
                  <div style={{ height: 6, background: 'rgba(255,255,255,0.6)', borderRadius: 3 }}>
                    <div style={{ height: 6, borderRadius: 3, background: ps.accent, width: `${plan.pct_done}%`, transition: 'width 0.4s' }}/>
                  </div>
                </div>

                {/* Tips */}
                <div style={{ marginBottom: 14 }}>
                  {plan.tips.map((tip, i) => (
                    <div key={i} style={{ fontSize: 12, color: '#374151', marginBottom: 4, display: 'flex', gap: 6, alignItems: 'flex-start' }}>
                      <span style={{ flexShrink: 0 }}>💡</span>
                      <span>{tip}</span>
                    </div>
                  ))}
                </div>

                {/* Action button */}
                {plan.exercises.length > 0 ? (
                  <button onClick={() => startSession(plan)} style={{
                    width: '100%', padding: '12px 0', borderRadius: 14, border: 'none',
                    background: ps.accent, color: 'white', fontSize: 14, fontWeight: 800, cursor: 'pointer'
                  }}>
                    ▶ Start {plan.exercises.length} practice exercises
                  </button>
                ) : (
                  <div style={{ textAlign: 'center', fontSize: 12, color: '#6B7280', padding: '8px 0' }}>
                    ✅ All exercises completed for this subject!
                  </div>
                )}
              </div>
            )
          })}
        </div>
      )}
    </div>
  )
}
