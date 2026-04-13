import { useState, useEffect } from 'react'
import type { Child } from '../types/child'

interface Exam {
  id: number
  title: string
  subject_name: string
  scheduled_at: string
  duration_minutes: number
  question_count: number
  status: string
  already_taken: boolean
  score?: number
  total?: number
}

interface Props {
  child: Child
  onStartExam: (exam: Exam) => void
}

function Countdown({ target }: { target: Date }) {
  const [diff, setDiff] = useState(target.getTime() - Date.now())

  useEffect(() => {
    const t = setInterval(() => setDiff(target.getTime() - Date.now()), 1000)
    return () => clearInterval(t)
  }, [target])

  if (diff <= 0) return <span style={{ color: '#10B981', fontWeight: 800 }}>Now!</span>

  const d = Math.floor(diff / 86400000)
  const h = Math.floor((diff % 86400000) / 3600000)
  const m = Math.floor((diff % 3600000) / 60000)
  const s = Math.floor((diff % 60000) / 1000)

  return (
    <span style={{ fontWeight: 800, color: '#F59E0B', fontVariantNumeric: 'tabular-nums' }}>
      {d > 0 && `${d}d `}{h > 0 && `${String(h).padStart(2,'0')}h `}{String(m).padStart(2,'0')}m {String(s).padStart(2,'0')}s
    </span>
  )
}

export default function ExamBanner({ child, onStartExam }: Props) {
  const [exams, setExams] = useState<Exam[]>([])

  useEffect(() => {
    fetch(`/api/exams/child/${child.id}`)
      .then(r => r.json())
      .then(data => setExams(Array.isArray(data) ? data : []))
      .catch(() => {})

    // Refresh every 30s
    const t = setInterval(() => {
      fetch(`/api/exams/child/${child.id}`)
        .then(r => r.json())
        .then(data => setExams(Array.isArray(data) ? data : []))
        .catch(() => {})
    }, 30000)
    return () => clearInterval(t)
  }, [child.id])

  const visible = exams.filter(e => !e.already_taken)
  if (visible.length === 0) return null

  return (
    <div style={{ padding: '0 16px', marginBottom: 14 }}>
      {visible.map(exam => {
        const scheduledAt = new Date(exam.scheduled_at)
        const isReady = Date.now() >= scheduledAt.getTime()
        const isDone = exam.already_taken

        return (
          <div key={exam.id} style={{
            borderRadius: 18, padding: '14px 16px',
            background: isDone ? '#F0FDF4' : isReady ? '#FEF3C7' : '#EFF6FF',
            border: `2px solid ${isDone ? '#86EFAC' : isReady ? '#FCD34D' : '#93C5FD'}`,
            marginBottom: 8
          }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
              <div style={{ fontSize: 24 }}>{isDone ? '✅' : isReady ? '📝' : '⏰'}</div>
              <div style={{ flex: 1 }}>
                <div style={{ fontSize: 14, fontWeight: 900, color: '#2D1B0E' }}>{exam.title}</div>
                <div style={{ fontSize: 11, color: '#8A6050', marginTop: 2 }}>
                  {exam.subject_name} · {exam.question_count} questions · {exam.duration_minutes} min
                </div>
                {isDone && exam.score !== undefined && (
                  <div style={{ fontSize: 12, fontWeight: 700, color: '#059669', marginTop: 4 }}>
                    Your score: {exam.score}/{exam.total} — {Math.round(exam.score / (exam.total || 1) * 100)}%
                  </div>
                )}
                {!isDone && !isReady && (
                  <div style={{ fontSize: 12, color: '#6B7280', marginTop: 4 }}>
                    Starts in: <Countdown target={scheduledAt} />
                  </div>
                )}
                {!isDone && isReady && (
                  <div style={{ fontSize: 12, color: '#D97706', fontWeight: 700, marginTop: 4 }}>
                    Exam is ready! Tap to start.
                  </div>
                )}
              </div>
              {!isDone && isReady && (
                <button onClick={() => onStartExam(exam)} style={{
                  padding: '10px 18px', borderRadius: 12, border: 'none',
                  background: '#F59E0B', color: 'white', fontSize: 13, fontWeight: 800, cursor: 'pointer'
                }}>
                  Start ▶
                </button>
              )}
            </div>
          </div>
        )
      })}
    </div>
  )
}
