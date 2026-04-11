import { useState, useEffect } from 'react'

interface ExamResult {
  first_name: string
  score: number
  total: number
  pct: number
  rank: number
  duration_seconds: number
  finished_at: string
}

interface ExamData {
  exam: {
    id: number
    title: string
    subject_name: string
    scheduled_at: string
    question_count: number
    duration_minutes: number
    status: string
  }
  results: ExamResult[]
}

interface Exam {
  id: number
  title: string
  subject_name: string
  scheduled_at: string
  status: string
  household_id: number
}

const RANK_COLORS = ['#F59E0B', '#94A3B8', '#CD7F32']
const RANK_LABELS = ['🥇', '🥈', '🥉']

function formatDuration(seconds: number): string {
  const m = Math.floor(seconds / 60)
  const s = seconds % 60
  return `${m}m ${String(s).padStart(2,'0')}s`
}

export default function ExamResultsDashboard() {
  const [exams, setExams] = useState<Exam[]>([])
  const [selected, setSelected] = useState<ExamData | null>(null)
  const [loading, setLoading] = useState(true)
  const [loadingResults, setLoadingResults] = useState(false)

  useEffect(() => {
    // Get all exams for household 1 (could be dynamic)
    fetch('/api/exams/child/1')
      .then(r => r.json())
      .then(data => setExams(Array.isArray(data) ? data : []))
      .catch(() => {})
      .finally(() => setLoading(false))
  }, [])

  const loadResults = (examId: number) => {
    setLoadingResults(true)
    fetch(`/api/exams/${examId}/results`)
      .then(r => r.json())
      .then(setSelected)
      .catch(() => {})
      .finally(() => setLoadingResults(false))
  }

  if (selected) return (
    <div style={{ fontFamily: 'system-ui,sans-serif', padding: '20px' }}>
      <button onClick={() => setSelected(null)} style={{ background: '#FFF0E8', border: '1.5px solid #FFD4B0', borderRadius: 10, padding: '7px 14px', fontSize: 13, fontWeight: 700, color: '#C8704A', cursor: 'pointer', marginBottom: 20 }}>
        ← Back to exams
      </button>

      <div style={{ marginBottom: 20 }}>
        <div style={{ fontSize: 20, fontWeight: 900, color: '#2D1B0E' }}>{selected.exam.title}</div>
        <div style={{ fontSize: 13, color: '#C8A090', marginTop: 4 }}>
          {selected.exam.subject_name} · {selected.exam.question_count} questions · {selected.exam.duration_minutes} min · {new Date(selected.exam.scheduled_at).toLocaleDateString('en-GB')}
        </div>
      </div>

      {selected.results.length === 0 ? (
        <div style={{ textAlign: 'center', padding: '40px', color: '#C8A090' }}>
          <div style={{ fontSize: 40, marginBottom: 12 }}>⏳</div>
          No results yet — children haven't taken this exam.
        </div>
      ) : (
        <>
          {/* Podium */}
          {selected.results.length >= 2 && (
            <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'flex-end', gap: 12, marginBottom: 24, padding: '20px 0' }}>
              {selected.results.slice(0, 3).map((r, i) => (
                <div key={i} style={{
                  textAlign: 'center',
                  order: i === 0 ? 2 : i === 1 ? 1 : 3,
                }}>
                  <div style={{ fontSize: 28, marginBottom: 4 }}>{RANK_LABELS[i]}</div>
                  <div style={{ fontWeight: 900, fontSize: 15, color: '#2D1B0E', marginBottom: 4 }}>{r.first_name}</div>
                  <div style={{
                    background: RANK_COLORS[i] + '22',
                    border: `2px solid ${RANK_COLORS[i]}`,
                    borderRadius: '12px 12px 0 0',
                    padding: '12px 20px',
                    height: i === 0 ? 90 : i === 1 ? 70 : 50,
                    display: 'flex', alignItems: 'flex-start', justifyContent: 'center', paddingTop: 12
                  }}>
                    <div style={{ fontSize: 20, fontWeight: 900, color: RANK_COLORS[i] }}>{r.pct}%</div>
                  </div>
                </div>
              ))}
            </div>
          )}

          {/* Full results table */}
          <div style={{ background: 'white', borderRadius: 16, overflow: 'hidden', border: '1.5px solid #F0E4D8' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 13 }}>
              <thead>
                <tr style={{ background: '#F9F3EF' }}>
                  <th style={{ padding: '12px 16px', textAlign: 'left', fontWeight: 800, color: '#2D1B0E' }}>Rank</th>
                  <th style={{ padding: '12px 16px', textAlign: 'left', fontWeight: 800, color: '#2D1B0E' }}>Child</th>
                  <th style={{ padding: '12px 16px', textAlign: 'center', fontWeight: 800, color: '#2D1B0E' }}>Score</th>
                  <th style={{ padding: '12px 16px', textAlign: 'center', fontWeight: 800, color: '#2D1B0E' }}>%</th>
                  <th style={{ padding: '12px 16px', textAlign: 'center', fontWeight: 800, color: '#2D1B0E' }}>Time</th>
                  <th style={{ padding: '12px 16px', textAlign: 'center', fontWeight: 800, color: '#2D1B0E' }}>Finished</th>
                </tr>
              </thead>
              <tbody>
                {selected.results.map((r, i) => {
                  const scoreColor = r.pct >= 80 ? '#059669' : r.pct >= 60 ? '#D97706' : '#DC2626'
                  return (
                    <tr key={i} style={{ borderTop: '1px solid #F0E4D8', background: i % 2 === 0 ? 'white' : '#FDFBF9' }}>
                      <td style={{ padding: '12px 16px', fontSize: 18 }}>{i < 3 ? RANK_LABELS[i] : `#${r.rank}`}</td>
                      <td style={{ padding: '12px 16px', fontWeight: 700, color: '#2D1B0E' }}>{r.first_name}</td>
                      <td style={{ padding: '12px 16px', textAlign: 'center', fontWeight: 800, color: scoreColor }}>{r.score}/{r.total}</td>
                      <td style={{ padding: '12px 16px', textAlign: 'center' }}>
                        <span style={{ background: scoreColor + '22', color: scoreColor, padding: '3px 10px', borderRadius: 8, fontWeight: 700, fontSize: 12 }}>{r.pct}%</span>
                      </td>
                      <td style={{ padding: '12px 16px', textAlign: 'center', color: '#6B7280' }}>
                        {r.duration_seconds ? formatDuration(r.duration_seconds) : '—'}
                      </td>
                      <td style={{ padding: '12px 16px', textAlign: 'center', color: '#6B7280', fontSize: 11 }}>
                        {r.finished_at ? new Date(r.finished_at).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }) : '—'}
                      </td>
                    </tr>
                  )
                })}
              </tbody>
            </table>
          </div>
        </>
      )}
    </div>
  )

  return (
    <div style={{ fontFamily: 'system-ui,sans-serif', padding: '20px' }}>
      <div style={{ fontSize: 18, fontWeight: 900, color: '#2D1B0E', marginBottom: 16 }}>📝 Exam Results</div>

      {loading && <div style={{ color: '#C8A090', padding: '20px 0' }}>Loading...</div>}

      {!loading && exams.length === 0 && (
        <div style={{ textAlign: 'center', padding: '40px', color: '#C8A090' }}>
          <div style={{ fontSize: 40, marginBottom: 12 }}>📋</div>
          No exams scheduled yet.
        </div>
      )}

      {exams.map(exam => (
        <div key={exam.id} style={{
          background: 'white', borderRadius: 16, padding: '16px',
          border: '1.5px solid #F0E4D8', marginBottom: 10,
          display: 'flex', alignItems: 'center', gap: 12, cursor: 'pointer'
        }} onClick={() => loadResults(exam.id)}>
          <div style={{ fontSize: 28 }}>{exam.status === 'completed' ? '✅' : exam.status === 'active' ? '🔴' : '⏰'}</div>
          <div style={{ flex: 1 }}>
            <div style={{ fontSize: 15, fontWeight: 800, color: '#2D1B0E' }}>{exam.title}</div>
            <div style={{ fontSize: 12, color: '#C8A090', marginTop: 2 }}>
              {exam.subject_name} · {new Date(exam.scheduled_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}
            </div>
          </div>
          <div style={{ fontSize: 12, fontWeight: 700, padding: '4px 10px', borderRadius: 8,
            background: exam.status === 'completed' ? '#D1FAE5' : exam.status === 'active' ? '#FEE2E2' : '#EFF6FF',
            color: exam.status === 'completed' ? '#059669' : exam.status === 'active' ? '#DC2626' : '#2563EB'
          }}>
            {exam.status}
          </div>
          <div style={{ color: '#C8A090', fontSize: 18 }}>›</div>
        </div>
      ))}
    </div>
  )
}
