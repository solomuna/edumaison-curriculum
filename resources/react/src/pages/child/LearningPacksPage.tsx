import { useEffect, useState } from 'react'
import type { Child } from '../../types/child'
import type { Exercise } from '../../types/exercise'

export type PackExercise = Exercise & { subject: string; level_id?: number | null }

interface LearningPackView {
  id: number
  name: string
  description: string | null
  type: string
  status: 'assigned' | 'in_progress' | 'completed'
  target_level: { id: number; name: string } | null
  target_subject: { id: number; name: string } | null
  exercise_count: number
  completed_count: number
  completed_exercise_ids: number[]
  exercises: PackExercise[]
}

interface Props {
  child: Child
  onBack: () => void
  onStart: (exercises: PackExercise[]) => void
  isDesktop?: boolean
}

export default function LearningPacksPage({ child, onBack, onStart, isDesktop = false }: Props) {
  const [packs, setPacks] = useState<LearningPackView[]>([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  useEffect(() => {
    const controller = new AbortController()
    fetch(`/api/children/${child.id}/learning-packs`, {
      headers: { Accept: 'application/json' },
      signal: controller.signal,
    })
      .then(async response => {
        if (!response.ok) throw new Error((await response.json().catch(() => null))?.message || 'Unable to load your paths.')
        return response.json()
      })
      .then(data => setPacks(Array.isArray(data.packs) ? data.packs : []))
      .catch(reason => {
        if (reason?.name !== 'AbortError') setError(reason instanceof Error ? reason.message : 'Unable to load your paths.')
      })
      .finally(() => setLoading(false))

    return () => controller.abort()
  }, [child.id])

  const start = (pack: LearningPackView) => {
    const completed = new Set(pack.completed_exercise_ids)
    const remaining = pack.exercises.filter(exercise => !completed.has(exercise.id))
    onStart(remaining.length ? remaining : pack.exercises)
  }

  return (
    <div className="adventure-secondary-page adventure-paths-page" style={{ padding: isDesktop ? '32px 40px' : '18px', maxWidth: 900 }}>
      <div className="adventure-paths-header" style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 22 }}>
        <button onClick={onBack} aria-label="Back to home" title="Back to home" style={{
          width: 38, height: 38, borderRadius: 8, border: '1.5px solid var(--border)',
          background: 'var(--card)', color: 'var(--text-dark)', cursor: 'pointer',
          fontSize: 20, fontWeight: 900, flexShrink: 0,
        }}>←</button>
        <div>
          <div style={{ fontSize: isDesktop ? 26 : 22, fontWeight: 900, color: 'var(--text-dark)' }}>My Learning Paths</div>
          <div style={{ fontSize: 13, color: 'var(--text-soft)', marginTop: 3 }}>Extra practice chosen for you by your family.</div>
        </div>
      </div>

      {loading && <div style={{ padding: '28px 0', color: 'var(--text-soft)' }}>Loading your paths...</div>}
      {error && <div role="alert" style={{ padding: 14, borderRadius: 8, background: '#FDECEC', border: '1px solid #E8A5A5', color: '#8A1C1C' }}>{error}</div>}
      {!loading && !error && packs.length === 0 && (
        <div style={{ padding: 22, borderRadius: 8, background: 'var(--card)', border: '1.5px solid var(--border)', color: 'var(--text-soft)' }}>
          No learning path has been assigned yet.
        </div>
      )}

      <div style={{ display: 'grid', gap: 14 }}>
        {packs.map(pack => {
          const percent = pack.exercise_count > 0 ? Math.round(pack.completed_count / pack.exercise_count * 100) : 0
          const isComplete = pack.completed_count >= pack.exercise_count && pack.exercise_count > 0
          return (
            <section className="adventure-secondary-card" key={pack.id} style={{
              padding: 18, borderRadius: 8, background: 'var(--card)',
              border: '1.5px solid var(--border)', borderLeft: '5px solid #1D6B2A',
            }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', gap: 14, alignItems: 'flex-start', flexWrap: 'wrap' }}>
                <div style={{ flex: '1 1 260px', minWidth: 0 }}>
                  <div style={{ fontSize: 18, fontWeight: 900, color: 'var(--text-dark)', overflowWrap: 'anywhere' }}>{pack.name}</div>
                  <div style={{ display: 'flex', gap: 7, flexWrap: 'wrap', color: 'var(--text-soft)', fontSize: 12, marginTop: 5 }}>
                    {pack.target_subject && <span>{pack.target_subject.name}</span>}
                    {pack.target_level && <span>· {pack.target_level.name}</span>}
                    <span>· {pack.exercise_count} activities</span>
                  </div>
                  {pack.description && <div style={{ fontSize: 13, lineHeight: 1.5, color: 'var(--text-soft)', marginTop: 9 }}>{pack.description}</div>}
                </div>
                <div style={{ color: isComplete ? '#1D6B2A' : '#6B4226', fontSize: 12, fontWeight: 900 }}>
                  {isComplete ? 'Completed' : `${pack.completed_count}/${pack.exercise_count} done`}
                </div>
              </div>

              <div style={{ height: 8, borderRadius: 4, background: 'var(--border)', overflow: 'hidden', marginTop: 16 }}>
                <div style={{ width: `${percent}%`, height: '100%', background: '#1D6B2A', transition: 'width 180ms ease' }} />
              </div>

              <button onClick={() => start(pack)} disabled={pack.exercises.length === 0} style={{
                marginTop: 14, minHeight: 40, padding: '8px 16px', borderRadius: 8, border: 'none',
                background: pack.exercises.length ? '#1D6B2A' : 'var(--border)', color: 'white',
                fontSize: 14, fontWeight: 900, cursor: pack.exercises.length ? 'pointer' : 'not-allowed',
                fontFamily: 'Nunito, system-ui, sans-serif',
              }}>
                {isComplete ? 'Practice again' : pack.completed_count > 0 ? 'Continue path' : 'Start path'}
              </button>
            </section>
          )
        })}
      </div>
    </div>
  )
}
