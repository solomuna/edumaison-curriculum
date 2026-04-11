import { useState, useEffect, useRef } from 'react'
import type { Child } from '../../types/child'
import ExercisePlayer from './ExercisePlayer'

interface Exam {
  id: number
  title: string
  subject_name: string
  duration_minutes: number
  question_count: number
}

interface Exercise {
  id: number
  title: string
  category: string
  content: string
}

interface Props {
  child: Child
  exam: Exam
  onBack: () => void
  onComplete: () => void
}

export default function ExamSession({ child, exam, onBack, onComplete }: Props) {
  const [exercises, setExercises] = useState<Exercise[]>([])
  const [loading, setLoading] = useState(true)
  const [cur, setCur] = useState(0)
  const [scores, setScores] = useState<boolean[]>([])
  const [done, setDone] = useState(false)
  const [timeLeft, setTimeLeft] = useState(exam.duration_minutes * 60)
  const [submitting, setSubmitting] = useState(false)
  const startedAt = useRef(new Date().toISOString())
  const startTime = useRef(Date.now())

  useEffect(() => {
    fetch(`/api/exams/${exam.id}/questions/${child.id}`)
      .then(r => r.json())
      .then(data => {
        setExercises(data.exercises || [])
        setLoading(false)
      })
      .catch(() => setLoading(false))
  }, [])

  // Countdown timer
  useEffect(() => {
    if (loading || done) return
    const t = setInterval(() => {
      setTimeLeft(prev => {
        if (prev <= 1) {
          clearInterval(t)
          handleFinish(scores)
          return 0
        }
        return prev - 1
      })
    }, 1000)
    return () => clearInterval(t)
  }, [loading, done, scores])

  const handleComplete = (s: number) => {
    const ok = s > 0
    const ns = [...scores, ok]
    setScores(ns)
    if (cur < exercises.length - 1) {
      setTimeout(() => setCur(c => c + 1), 300)
    } else {
      handleFinish(ns)
    }
  }

  const handleFinish = async (finalScores: boolean[]) => {
    if (done || submitting) return
    setSubmitting(true)
    setDone(true)
    const score = finalScores.filter(Boolean).length
    const duration = Math.round((Date.now() - startTime.current) / 1000)

    await fetch(`/api/exams/${exam.id}/submit`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        child_id: child.id,
        score,
        total: exercises.length,
        duration_seconds: duration,
        started_at: startedAt.current,
      }),
    }).catch(() => {})
    setSubmitting(false)
  }

  const mm = String(Math.floor(timeLeft / 60)).padStart(2, '0')
  const ss = String(timeLeft % 60).padStart(2, '0')
  const timerColor = timeLeft < 60 ? '#DC2626' : timeLeft < 300 ? '#F59E0B' : '#2D1B0E'
  const finalScore = scores.filter(Boolean).length
  const finalPct = exercises.length > 0 ? Math.round(finalScore / exercises.length * 100) : 0

  if (loading) return (
    <div style={{ background: '#FFF8F2', minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'system-ui,sans-serif' }}>
      <div style={{ textAlign: 'center' }}>
        <div style={{ fontSize: 36, marginBottom: 16 }}>📝</div>
        <div style={{ fontSize: 16, color: '#C8A090' }}>Loading exam questions...</div>
      </div>
    </div>
  )

  if (done) {
    return (
      <div style={{ background: '#FFF8F2', minHeight: '100vh', fontFamily: 'system-ui,sans-serif', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: '24px' }}>
        <div style={{ fontSize: 64, marginBottom: 16 }}>{finalPct >= 80 ? '🏆' : finalPct >= 60 ? '🌟' : '📚'}</div>
        <div style={{ fontSize: 24, fontWeight: 900, color: '#2D1B0E', marginBottom: 8 }}>
          {finalPct >= 80 ? 'Excellent!' : finalPct >= 60 ? 'Well done!' : 'Keep practising!'}
        </div>
        <div style={{ fontSize: 16, color: '#8A6050', marginBottom: 32 }}>
          {exam.title} — {exam.subject_name}
        </div>

        <div style={{ display: 'flex', gap: 20, marginBottom: 40 }}>
          <div style={{ background: '#FEF3C7', borderRadius: 18, padding: '20px 32px', textAlign: 'center' }}>
            <div style={{ fontSize: 40, fontWeight: 900, color: '#F59E0B' }}>{finalScore}/{exercises.length}</div>
            <div style={{ fontSize: 13, color: '#D97706', marginTop: 4 }}>Correct answers</div>
          </div>
          <div style={{ background: '#D1FAE5', borderRadius: 18, padding: '20px 32px', textAlign: 'center' }}>
            <div style={{ fontSize: 40, fontWeight: 900, color: '#10B981' }}>{finalPct}%</div>
            <div style={{ fontSize: 13, color: '#059669', marginTop: 4 }}>Score</div>
          </div>
        </div>

        {/* Indicator dots */}
        <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap', justifyContent: 'center', marginBottom: 32, maxWidth: 360 }}>
          {scores.map((ok, i) => (
            <div key={i} style={{
              width: 14, height: 14, borderRadius: '50%',
              background: ok ? '#10B981' : '#EF4444'
            }}/>
          ))}
        </div>

        <button onClick={onComplete} style={{
          padding: '16px 48px', borderRadius: 18, border: 'none',
          background: '#FF8FAB', color: 'white', fontSize: 16, fontWeight: 900, cursor: 'pointer'
        }}>
          Back to Home
        </button>
      </div>
    )
  }

  return (
    <div style={{ background: '#FFF8F2', minHeight: '100vh', fontFamily: 'system-ui,sans-serif', display: 'flex', flexDirection: 'column' }}>
      {/* Header */}
      <div style={{ background: 'white', padding: '14px 18px', borderBottom: '2px solid #F0E4D8', display: 'flex', alignItems: 'center', gap: 12 }}>
        <div style={{ flex: 1 }}>
          <div style={{ fontSize: 14, fontWeight: 800, color: '#2D1B0E' }}>{exam.title}</div>
          <div style={{ fontSize: 11, color: '#C8A090' }}>{exam.subject_name} · Q {cur + 1}/{exercises.length}</div>
        </div>
        {/* Timer */}
        <div style={{
          padding: '8px 16px', borderRadius: 12,
          background: timeLeft < 60 ? '#FEE2E2' : timeLeft < 300 ? '#FEF3C7' : '#F0F9FF',
          border: `1.5px solid ${timeLeft < 60 ? '#FCA5A5' : timeLeft < 300 ? '#FCD34D' : '#BAE6FD'}`
        }}>
          <div style={{ fontSize: 18, fontWeight: 900, color: timerColor, fontVariantNumeric: 'tabular-nums' }}>
            ⏱ {mm}:{ss}
          </div>
        </div>
      </div>

      {/* Progress bar */}
      <div style={{ height: 6, background: '#F0E4D8' }}>
        <div style={{
          height: 6, background: '#F59E0B',
          width: `${Math.round(cur / exercises.length * 100)}%`,
          transition: 'width 0.3s'
        }}/>
      </div>

      {/* Question indicators */}
      <div style={{ padding: '10px 18px 0', display: 'flex', gap: 5, flexWrap: 'wrap' }}>
        {exercises.map((_, i) => (
          <div key={i} style={{
            width: 12, height: 12, borderRadius: '50%',
            background: i < scores.length
              ? (scores[i] ? '#10B981' : '#EF4444')
              : i === cur ? '#F59E0B' : '#E0D4CA'
          }}/>
        ))}
      </div>

      {/* Exercise */}
      <div style={{ flex: 1, padding: '16px 18px' }}>
        {exercises[cur] && (
          <ExercisePlayer
            key={exercises[cur].id}
            exercise={{ ...exercises[cur], content: typeof exercises[cur].content === 'string' ? JSON.parse(exercises[cur].content) : exercises[cur].content, subject: exam.subject_name }}
            onComplete={handleComplete}
            onBack={() => {}}
          />
        )}
      </div>
    </div>
  )
}
