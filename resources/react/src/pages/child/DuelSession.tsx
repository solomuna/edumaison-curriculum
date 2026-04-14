// DuelSession.tsx — Ecran de duel live entre 2 enfants
import { useState, useEffect, useRef } from 'react'
import type { Child } from '../../types/child'
import ExercisePlayer from './ExercisePlayer'
import { SoundService } from '../../services/SoundService'

interface DuelData {
  id: number
  child1_name: string
  child2_name: string
  nb_exercises: number
  duration_seconds: number
  exercise_ids: number[]
}

interface Props {
  child: Child
  duel: DuelData
  onComplete: () => void
}

export default function DuelSession({ child, duel, onComplete }: Props) {
  const [exercises, setExercises] = useState<any[]>([])
  const [loading, setLoading] = useState(true)
  const [cur, setCur] = useState(0)
  const [scores, setScores] = useState<boolean[]>([])
  const [done, setDone] = useState(false)
  const [timeLeft, setTimeLeft] = useState(duel.duration_seconds)
  const [submitting, setSubmitting] = useState(false)
  const [countdown, setCountdown] = useState(3)
  const [started, setStarted] = useState(false)
  const startTime = useRef(Date.now())
  const scoresRef = useRef<boolean[]>([])

  useEffect(() => {
    const ids = duel.exercise_ids.join(',')
    fetch(`/api/exercises/duel?ids=${ids}`)
      .then(r => r.json())
      .then(data => { setExercises(Array.isArray(data) ? data : []); setLoading(false) })
      .catch(() => setLoading(false))
  }, [])

  useEffect(() => {
    if (loading) return
    SoundService.streak()
    const t = setInterval(() => {
      setCountdown(prev => {
        if (prev <= 1) { clearInterval(t); SoundService.fanfare(); setStarted(true); return 0 }
        SoundService.click()
        return prev - 1
      })
    }, 1000)
    return () => clearInterval(t)
  }, [loading])

  useEffect(() => {
    if (!started || done) return
    const t = setInterval(() => {
      setTimeLeft(prev => {
        if (prev <= 1) { clearInterval(t); handleFinish(scoresRef.current); return 0 }
        if (prev === 30) SoundService.heartLost()
        return prev - 1
      })
    }, 1000)
    return () => clearInterval(t)
  }, [started, done, scores])

  const handleComplete = (s: number) => {
    const ok = s > 0
    const ns = [...scoresRef.current, ok]
    scoresRef.current = ns
    setScores(ns)
    if (ok) SoundService.correct(); else SoundService.wrong()
    if (cur < exercises.length - 1) { setTimeout(() => setCur(c => c + 1), 300) }
    else { handleFinish(ns) }
  }

  const handleFinish = async (finalScores: boolean[]) => {
    if (done || submitting) return
    setSubmitting(true); setDone(true)
    SoundService.applause()
    const score    = finalScores.filter(Boolean).length
    const duration = Math.round((Date.now() - startTime.current) / 1000)
    await fetch(`/api/duels/${duel.id}/result`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ child_id: child.id, score, total: exercises.length, duration_seconds: duration })
    }).catch(() => {})
    setSubmitting(false)
  }

  const mm = String(Math.floor(timeLeft / 60)).padStart(2, '0')
  const ss = String(timeLeft % 60).padStart(2, '0')
  const timerColor = timeLeft < 30 ? '#DC2626' : timeLeft < 60 ? '#F59E0B' : 'white'
  const finalScore = scores.filter(Boolean).length
  const finalPct   = exercises.length > 0 ? Math.round(finalScore / exercises.length * 100) : 0
  const firstName  = child.name.split(' ')[0]

  if (loading) return (
    <div style={{ background: '#E8DCC8', minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Nunito,system-ui,sans-serif' }}>
      <div style={{ textAlign: 'center' }}>
        <div style={{ fontSize: 52 }}>⚔️</div>
        <div style={{ fontSize: 16, color: '#7A6050', marginTop: 16 }}>Preparation du duel...</div>
      </div>
    </div>
  )

  if (!started && countdown > 0) return (
    <div style={{ background: '#FF8FAB', minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Nunito,system-ui,sans-serif' }}>
      <div style={{ textAlign: 'center', color: 'white' }}>
        <div style={{ fontSize: 18, fontWeight: 800, marginBottom: 24, opacity: 0.9 }}>⚔️ {duel.child1_name} vs {duel.child2_name}</div>
        <div style={{ fontSize: 120, fontWeight: 900, lineHeight: 1 }}>{countdown}</div>
        <div style={{ fontSize: 20, marginTop: 24, opacity: 0.8 }}>Pret !</div>
      </div>
    </div>
  )

  if (done) return (
    <div style={{ background: '#E8DCC8', minHeight: '100vh', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: 24, fontFamily: 'Nunito,system-ui,sans-serif', textAlign: 'center' }}>
      <div style={{ fontSize: 72, marginBottom: 16 }}>🏆</div>
      <div style={{ fontSize: 26, fontWeight: 900, color: '#3D2B1F', marginBottom: 8 }}>
        {finalPct >= 70 ? `Bravo ${firstName} !` : 'Bien joue !'}
      </div>
      <div style={{ fontSize: 15, color: '#7A6050', marginBottom: 32 }}>Resultat en attente...</div>
      <div style={{ display: 'flex', gap: 20, marginBottom: 40 }}>
        <div style={{ background: '#FEF3C7', borderRadius: 20, padding: '20px 32px', textAlign: 'center' }}>
          <div style={{ fontSize: 44, fontWeight: 900, color: '#F59E0B' }}>{finalScore}/{exercises.length}</div>
          <div style={{ fontSize: 13, color: '#D97706', marginTop: 4 }}>Bonnes reponses</div>
        </div>
        <div style={{ background: '#D1FAE5', borderRadius: 20, padding: '20px 32px', textAlign: 'center' }}>
          <div style={{ fontSize: 44, fontWeight: 900, color: '#10B981' }}>{finalPct}%</div>
          <div style={{ fontSize: 13, color: '#059669', marginTop: 4 }}>Score</div>
        </div>
      </div>
      <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap', justifyContent: 'center', marginBottom: 32 }}>
        {scores.map((ok, i) => (<div key={i} style={{ width: 16, height: 16, borderRadius: '50%', background: ok ? '#10B981' : '#EF4444' }}/>))}
      </div>
      <button onClick={onComplete} style={{ padding: '16px 48px', borderRadius: 18, border: 'none', background: '#1D6B2A', color: 'white', fontSize: 17, fontWeight: 900, cursor: 'pointer' }}>
        Retour
      </button>
    </div>
  )

  return (
    <div style={{ background: '#E8DCC8', minHeight: '100vh', fontFamily: 'Nunito,system-ui,sans-serif', display: 'flex', flexDirection: 'column' }}>
      <div style={{ background: '#1D6B2A', padding: '12px 18px', display: 'flex', alignItems: 'center', gap: 12 }}>
        <div style={{ flex: 1 }}>
          <div style={{ fontSize: 15, fontWeight: 900, color: 'white' }}>⚔️ {duel.child1_name} vs {duel.child2_name}</div>
          <div style={{ fontSize: 12, color: 'rgba(255,255,255,0.8)' }}>Q {cur + 1}/{exercises.length}</div>
        </div>
        <div style={{ background: 'rgba(255,255,255,0.2)', borderRadius: 12, padding: '8px 16px' }}>
          <div style={{ fontSize: 22, fontWeight: 900, color: timerColor, fontVariantNumeric: 'tabular-nums' }}>⏱️ {mm}:{ss}</div>
        </div>
      </div>
      <div style={{ height: 6, background: 'rgba(29,107,42,0.2)' }}>
        <div style={{ height: 6, background: '#1D6B2A', width: `${Math.round(cur / exercises.length * 100)}%`, transition: 'width 0.3s' }}/>
      </div>
      <div style={{ padding: '8px 18px 0', display: 'flex', gap: 5, flexWrap: 'wrap' }}>
        {exercises.map((_, i) => (<div key={i} style={{ width: 12, height: 12, borderRadius: '50%', background: i < scores.length ? (scores[i] ? '#10B981' : '#EF4444') : i === cur ? '#1D6B2A' : '#E0D4CA' }}/>))}
      </div>
      <div style={{ flex: 1 }}>
        {exercises[cur] && (
          <ExercisePlayer
            key={exercises[cur].id}
            exercise={{ ...exercises[cur], content: typeof exercises[cur].content === 'string' ? JSON.parse(exercises[cur].content) : exercises[cur].content }}
            onComplete={handleComplete}
            onBack={() => { if (cur < exercises.length - 1) { setCur(c => c + 1) } else { handleFinish(scoresRef.current) } }}
          />
        )}
      </div>
    </div>
  )
}
