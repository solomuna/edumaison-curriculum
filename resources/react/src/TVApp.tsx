import { useState, useEffect } from 'react'
import { getChildren, loginChild, getExercisesForChild, saveAttempt } from './services/api'
import type { Child } from './types/child'
import type { Exercise } from './types/exercise'
import ExercisePlayer from './pages/child/ExercisePlayer'

const CHILD_COLORS = [
  { bg: '#FCE7F3', accent: '#EC4899', icon: '🌸' },
  { bg: '#FEF3C7', accent: '#F59E0B', icon: '⚡' },
  { bg: '#EDE9FE', accent: '#8B5CF6', icon: '⭐' },
  { bg: '#D1FAE5', accent: '#10B981', icon: '🌿' },
]

const SUBJECT_COLORS: Record<string, { bg: string; accent: string }> = {
  English:                  { bg: '#DBEAFE', accent: '#3B82F6' },
  Mathematics:              { bg: '#FEF3C7', accent: '#F59E0B' },
  French:                   { bg: '#EDE9FE', accent: '#8B5CF6' },
  'Science and Technology': { bg: '#D1FAE5', accent: '#10B981' },
  ICT:                      { bg: '#CFFAFE', accent: '#0EA5E9' },
  Citizenship:              { bg: '#FEF9C3', accent: '#EAB308' },
  Reading:                  { bg: '#FCE7F3', accent: '#EC4899' },
  Handwriting:              { bg: '#E0F2FE', accent: '#0284C7' },
}
const DEF_COL = { bg: '#F3F4F6', accent: '#6B7280' }
const gc = (s: string) => SUBJECT_COLORS[s] || DEF_COL

function MamaJudiSVG({ size = 100 }: { size?: number }) {
  return (
    <svg viewBox="0 0 84 104" width={size} height={size * 104 / 84} xmlns="http://www.w3.org/2000/svg">
      <circle cx="42" cy="36" r="28" fill="#2A1500"/>
      <circle cx="16" cy="43" r="13" fill="#2A1500"/>
      <circle cx="68" cy="43" r="13" fill="#2A1500"/>
      <circle cx="42" cy="44" r="21" fill="#C8874A"/>
      <ellipse cx="21" cy="44" rx="4" ry="5" fill="#B87A40"/>
      <ellipse cx="63" cy="44" rx="4" ry="5" fill="#B87A40"/>
      <circle cx="34" cy="40" r="3.5" fill="#1A0A00"/>
      <circle cx="50" cy="40" r="3.5" fill="#1A0A00"/>
      <circle cx="35.5" cy="38.8" r="1.3" fill="white"/>
      <circle cx="51.5" cy="38.8" r="1.3" fill="white"/>
      <ellipse cx="42" cy="48" rx="2.5" ry="2" fill="#A86835"/>
      <path d="M30 54 Q42 65 54 54" stroke="#1A0A00" strokeWidth="2.2" fill="none" strokeLinecap="round"/>
      <path d="M30 54 Q42 60 54 54" fill="white" opacity="0.5"/>
      <ellipse cx="28" cy="50" rx="5.5" ry="3.5" fill="#E8956A" opacity="0.35"/>
      <ellipse cx="56" cy="50" rx="5.5" ry="3.5" fill="#E8956A" opacity="0.35"/>
      <rect x="38" y="63" width="8" height="10" rx="4" fill="#C8874A"/>
      <path d="M14 92 Q12 76 42 72 Q72 76 70 92 L68 104 L16 104 Z" fill="#FF8FAB"/>
      <path d="M18 82 Q4 66 10 50" stroke="#C8874A" strokeWidth="7" fill="none" strokeLinecap="round"/>
      <circle cx="9" cy="48" r="7" fill="#C8874A"/>
      <polygon points="75,39 77.5,45.5 84,45.5 79,49.5 81,56 75,52.5 69,56 71,49.5 66,45.5 72.5,45.5" fill="#FFD700"/>
      <path d="M66 82 Q76 66 74 52" stroke="#C8874A" strokeWidth="7" fill="none" strokeLinecap="round"/>
      <circle cx="75" cy="50" r="7" fill="#C8874A"/>
    </svg>
  )
}

// ── 1. Child Select ───────────────────────────────────────────────────────────
function TVChildSelect({ children, onSelect }: { children: Child[]; onSelect: (c: Child) => void }) {
  const [focused, setFocused] = useState(0)
  useEffect(() => {
    const h = (e: KeyboardEvent) => {
      if (e.key === 'ArrowRight') setFocused(f => Math.min(f + 1, children.length - 1))
      if (e.key === 'ArrowLeft') setFocused(f => Math.max(f - 1, 0))
      if (e.key === 'Enter') onSelect(children[focused])
    }
    window.addEventListener('keydown', h)
    return () => window.removeEventListener('keydown', h)
  }, [focused, children])
  return (
    <div style={{ background: '#FFF8F2', minHeight: '100vh', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', fontFamily: "system-ui,sans-serif", padding: '60px 80px' }}>
      <MamaJudiSVG size={130} />
      <div style={{ fontSize: 40, fontWeight: 900, color: '#2D1B0E', margin: '20px 0 10px' }}>EduMaison</div>
      <div style={{ fontSize: 24, color: '#C8A090', marginBottom: 50 }}>Qui apprend aujourd'hui ?</div>
      <div style={{ display: 'flex', gap: 40 }}>
        {children.map((child, idx) => {
          const col = CHILD_COLORS[idx % CHILD_COLORS.length]
          const isFoc = idx === focused
          return (
            <div key={child.id} onClick={() => onSelect(child)} onMouseEnter={() => setFocused(idx)}
              style={{ background: col.bg, borderRadius: 26, padding: '36px 44px', textAlign: 'center', cursor: 'pointer', transition: 'transform 0.15s', transform: isFoc ? 'scale(1.08)' : 'scale(1)', border: `4px solid ${isFoc ? col.accent : col.accent + '44'}`, minWidth: 180 }}>
              <div style={{ fontSize: 64, marginBottom: 14 }}>{col.icon}</div>
              <div style={{ fontSize: 28, fontWeight: 900, color: '#2D1B0E', marginBottom: 6 }}>{child.name}</div>
              <div style={{ fontSize: 16, color: col.accent, fontWeight: 700 }}>{child.level}</div>
            </div>
          )
        })}
      </div>
      <div style={{ marginTop: 44, fontSize: 15, color: '#D4C0B0' }}>← → naviguer · OK sélectionner</div>
    </div>
  )
}

// ── 2. PIN Entry ──────────────────────────────────────────────────────────────
function TVPinEntry({ child, onSuccess, onBack }: { child: Child; onSuccess: (c: Child) => void; onBack: () => void }) {
  const [pin, setPin] = useState('')
  const [error, setError] = useState('')
  const [checking, setChecking] = useState(false)
  const [focused, setFocused] = useState(0)
  const col = CHILD_COLORS[(child.id - 1) % CHILD_COLORS.length]
  const keys = ['1','2','3','4','5','6','7','8','9','←','0','OK']

  const press = async (k: string) => {
    if (checking) return
    if (k === '←') { setPin(p => p.slice(0, -1)); setError(''); return }
    if (k === 'OK') {
      if (pin.length !== 4) return
      setChecking(true)
      const res = await loginChild(child.id, pin)
      setChecking(false)
      if (res.error) { setError('Code incorrect !'); setPin('') }
      else onSuccess(res)
      return
    }
    if (pin.length < 4) {
      const np = pin + k
      setPin(np); setError('')
      if (np.length === 4) {
        setChecking(true)
        const res = await loginChild(child.id, np)
        setChecking(false)
        if (res.error) { setError('Code incorrect !'); setPin('') }
        else onSuccess(res)
      }
    }
  }

  useEffect(() => {
    const h = async (e: KeyboardEvent) => {
      if (checking) return
      if (e.key === 'Escape') { onBack(); return }
      if (e.key === 'ArrowRight') setFocused(f => Math.min(f + 1, 11))
      if (e.key === 'ArrowLeft') setFocused(f => Math.max(f - 1, 0))
      if (e.key === 'ArrowDown') setFocused(f => Math.min(f + 3, 11))
      if (e.key === 'ArrowUp') setFocused(f => Math.max(f - 3, 0))
      if (e.key >= '0' && e.key <= '9') await press(e.key)
      if (e.key === 'Backspace') await press('←')
      if (e.key === 'Enter') await press(keys[focused])
    }
    window.addEventListener('keydown', h)
    return () => window.removeEventListener('keydown', h)
  }, [focused, pin, checking])

  return (
    <div style={{ background: '#FFF8F2', minHeight: '100vh', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', fontFamily: "system-ui,sans-serif" }}>
      <div style={{ width: 90, height: 90, borderRadius: 24, background: col.bg, border: `3px solid ${col.accent}44`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 48, marginBottom: 16 }}>{col.icon}</div>
      <div style={{ fontSize: 32, fontWeight: 900, color: '#2D1B0E', marginBottom: 6 }}>{child.name}</div>
      <div style={{ fontSize: 20, color: '#8A6050', marginBottom: 36 }}>Entre ton code secret</div>
      <div style={{ display: 'flex', gap: 18, marginBottom: 14 }}>
        {[0,1,2,3].map(i => (
          <div key={i} style={{ width: 22, height: 22, borderRadius: '50%', background: i < pin.length ? col.accent : 'transparent', border: `3px solid ${col.accent}`, transition: 'all 0.15s' }}/>
        ))}
      </div>
      {error && <div style={{ color: '#EF4444', fontSize: 18, fontWeight: 700, marginBottom: 14 }}>{error}</div>}
      {checking && <div style={{ color: col.accent, fontSize: 18, marginBottom: 14 }}>Vérification...</div>}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 14, width: 340 }}>
        {keys.map((k, i) => (
          <button key={i} onClick={() => press(k)} onMouseEnter={() => setFocused(i)}
            style={{ padding: '20px 0', borderRadius: 16, border: `3px solid ${i === focused ? col.accent : '#E0D4CA'}`, background: i === focused ? col.bg : 'white', color: k === 'OK' ? col.accent : '#2D1B0E', fontSize: k === 'OK' ? 18 : 26, fontWeight: 900, cursor: 'pointer', transform: i === focused ? 'scale(1.06)' : 'scale(1)', opacity: k === '' ? 0 : 1, transition: 'all 0.1s' }}>
            {k}
          </button>
        ))}
      </div>
      <button onClick={onBack} style={{ marginTop: 30, background: 'none', border: 'none', color: '#C8A090', fontSize: 16, cursor: 'pointer', fontWeight: 600 }}>← Changer d'enfant</button>
    </div>
  )
}

// ── 3. Session player ─────────────────────────────────────────────────────────
function TVSession({ exercises, subject, onComplete, onBack }: {
  exercises: (Exercise & { subject: string })[]
  subject: string
  onComplete: (ids: number[]) => void
  onBack: () => void
}) {
  const [cur, setCur] = useState(0)
  const [scores, setScores] = useState<boolean[]>([])
  const [done, setDone] = useState(false)
  const col = gc(subject)
  const pct = Math.round(cur / exercises.length * 100)

  const handleComplete = (s: number) => {
    const ok = s > 0
    const ns = [...scores, ok]
    setScores(ns)
    if (cur < exercises.length - 1) {
      setTimeout(() => setCur(c => c + 1), 300)
    } else {
      setDone(true)
    }
  }

  if (done) {
    const good = scores.filter(Boolean).length
    const pct2 = Math.round(good / exercises.length * 100)
    return (
      <div style={{ background: '#FFF8F2', minHeight: '100vh', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', fontFamily: "system-ui,sans-serif", padding: '60px', textAlign: 'center' }}>
        <MamaJudiSVG size={110} />
        <div style={{ fontSize: 44, fontWeight: 900, color: '#2D1B0E', margin: '20px 0 10px' }}>
          {pct2 >= 80 ? 'Excellent !' : pct2 >= 60 ? 'Bien joué !' : 'Continue !'}
        </div>
        <div style={{ fontSize: 22, color: '#8A6050', marginBottom: 36 }}>Session {subject} terminée</div>
        <div style={{ display: 'flex', gap: 28, marginBottom: 44 }}>
          <div style={{ background: '#FEF3C7', borderRadius: 18, padding: '22px 36px', textAlign: 'center' }}>
            <div style={{ fontSize: 44, fontWeight: 900, color: '#F59E0B' }}>{good}/{exercises.length}</div>
            <div style={{ fontSize: 16, color: '#D97706', marginTop: 4 }}>Bonnes réponses</div>
          </div>
          <div style={{ background: '#D1FAE5', borderRadius: 18, padding: '22px 36px', textAlign: 'center' }}>
            <div style={{ fontSize: 44, fontWeight: 900, color: '#10B981' }}>{pct2}%</div>
            <div style={{ fontSize: 16, color: '#059669', marginTop: 4 }}>Score</div>
          </div>
          <div style={{ background: '#EDE9FE', borderRadius: 18, padding: '22px 36px', textAlign: 'center' }}>
            <div style={{ fontSize: 44, fontWeight: 900, color: '#8B5CF6' }}>{good * 10}</div>
            <div style={{ fontSize: 16, color: '#7C3AED', marginTop: 4 }}>Étoiles</div>
          </div>
        </div>
        <button onClick={() => onComplete(exercises.map(e => e.id))}
          style={{ padding: '20px 56px', borderRadius: 18, border: 'none', background: '#FF8FAB', color: 'white', fontSize: 22, fontWeight: 900, cursor: 'pointer' }}>
          Retour aux matières
        </button>
      </div>
    )
  }

  return (
    <div style={{ background: '#FFF8F2', minHeight: '100vh', fontFamily: "system-ui,sans-serif", display: 'flex', flexDirection: 'column' }}>
      <div style={{ background: 'white', padding: '16px 60px', borderBottom: `3px solid ${col.accent}44`, display: 'flex', alignItems: 'center', gap: 20 }}>
        <button onClick={onBack} style={{ background: '#FFF0E8', border: '2px solid #FFD4B0', borderRadius: 10, padding: '10px 18px', fontSize: 17, fontWeight: 700, color: '#C8704A', cursor: 'pointer' }}>← Quitter</button>
        <div style={{ flex: 1 }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 5 }}>
            <span style={{ fontSize: 19, fontWeight: 800, color: '#2D1B0E' }}>{subject}</span>
            <span style={{ fontSize: 19, fontWeight: 800, color: col.accent }}>{cur + 1} / {exercises.length}</span>
          </div>
          <div style={{ height: 10, background: '#F0E4D8', borderRadius: 5 }}>
            <div style={{ height: 10, borderRadius: 5, background: col.accent, width: `${pct}%`, transition: 'width 0.4s' }}/>
          </div>
        </div>
        <div style={{ display: 'flex', gap: 6 }}>
          {exercises.map((_, i) => (
            <div key={i} style={{ width: 12, height: 12, borderRadius: '50%', background: i < scores.length ? (scores[i] ? '#10B981' : '#EF4444') : i === cur ? col.accent : '#E0D4CA' }}/>
          ))}
        </div>
      </div>
      <div style={{ flex: 1, padding: '28px 100px', maxWidth: 1100, margin: '0 auto', width: '100%' }}>
        <ExercisePlayer key={exercises[cur]?.id} exercise={exercises[cur]} onComplete={handleComplete} onBack={onBack} />
      </div>
    </div>
  )
}

// ── 4. Dashboard ──────────────────────────────────────────────────────────────
function TVDashboard({ child, onLogout }: { child: Child; onLogout: () => void }) {
  const [exercises, setExercises] = useState<(Exercise & { subject: string })[]>([])
  const [loading, setLoading] = useState(true)
  const [completed, setCompleted] = useState<number[]>([])
  const [activeSubject, setActiveSubject] = useState<string | null>(null)
  const [session, setSession] = useState<(Exercise & { subject: string })[] | null>(null)
  const [focused, setFocused] = useState(0)
  const col = CHILD_COLORS[(child.id - 1) % CHILD_COLORS.length]

  useEffect(() => {
    if (!child.level_id) return
    getExercisesForChild(child.id, child.level_id!).then(setExercises).finally(() => setLoading(false))
  }, [child.id])

  const subjects = Object.entries(
    exercises.reduce((acc, ex) => {
      if (!acc[ex.subject]) acc[ex.subject] = { total: 0, done: 0 }
      acc[ex.subject].total++
      if (completed.includes(ex.id)) acc[ex.subject].done++
      return acc
    }, {} as Record<string, { total: number; done: number }>)
  ).map(([name, s]) => ({ name, ...s }))

  const subExs = activeSubject ? exercises.filter(e => e.subject === activeSubject) : []

  useEffect(() => {
    const list = activeSubject ? subExs : subjects
    const h = (e: KeyboardEvent) => {
      if (e.key === 'ArrowRight' || e.key === 'ArrowDown') setFocused(f => Math.min(f + 1, list.length - 1))
      if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') setFocused(f => Math.max(f - 1, 0))
      if (e.key === 'Enter') {
        if (activeSubject) {
          const todo = subExs.filter(ex => !completed.includes(ex.id))
          if (todo.length > 0) setSession(todo)
        } else {
          const sub = subjects[focused]
          if (sub) { setActiveSubject(sub.name); setFocused(0) }
        }
      }
      if (e.key === 'Escape' || e.key === 'Backspace') {
        if (activeSubject) { setActiveSubject(null); setFocused(0) }
        else onLogout()
      }
    }
    window.addEventListener('keydown', h)
    return () => window.removeEventListener('keydown', h)
  }, [focused, activeSubject, subjects, subExs, completed])

  const totalPct = exercises.length > 0 ? Math.round(completed.length / exercises.length * 100) : 0

  if (session && activeSubject) {
    return (
      <TVSession
        exercises={session}
        subject={activeSubject}
        onBack={() => setSession(null)}
        onComplete={async (ids) => {
          for (const id of ids) {
            await saveAttempt(child.id, id, 100)
          }
          setCompleted(prev => [...prev, ...ids.filter(id => !prev.includes(id))])
          setSession(null)
        }}
      />
    )
  }

  return (
    <div style={{ background: '#FFF8F2', minHeight: '100vh', fontFamily: "system-ui,sans-serif", display: 'flex', flexDirection: 'column' }}>
      <div style={{ background: 'white', padding: '18px 60px', borderBottom: '2px solid #F0E4D8', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 16 }}>
          <MamaJudiSVG size={56} />
          <div>
            <div style={{ fontSize: 26, fontWeight: 900, color: '#2D1B0E' }}>Bonjour, {child.name} !</div>
            <div style={{ fontSize: 15, color: col.accent, fontWeight: 700 }}>{child.level}</div>
          </div>
        </div>
        <div style={{ display: 'flex', gap: 18, alignItems: 'center' }}>
          <div style={{ textAlign: 'right' }}>
            <div style={{ fontSize: 13, color: '#C8A090', marginBottom: 4 }}>Progression globale</div>
            <div style={{ width: 180, height: 10, background: '#F0E4D8', borderRadius: 5 }}>
              <div style={{ height: 10, borderRadius: 5, background: '#FF8FAB', width: `${totalPct}%`, transition: 'width 0.4s' }}/>
            </div>
            <div style={{ fontSize: 13, color: '#FF8FAB', fontWeight: 700, marginTop: 3 }}>{completed.length}/{exercises.length} · {totalPct}%</div>
          </div>
          <div style={{ background: '#FEF3C7', borderRadius: 12, padding: '10px 18px', textAlign: 'center' }}>
            <div style={{ fontSize: 24, fontWeight: 900, color: '#F59E0B' }}>{completed.length * 10}</div>
            <div style={{ fontSize: 12, color: '#D97706' }}>Étoiles</div>
          </div>
        </div>
      </div>

      <div style={{ padding: '14px 60px 0', display: 'flex', alignItems: 'center', gap: 8 }}>
        <span onClick={() => { setActiveSubject(null); setFocused(0) }}
          style={{ fontSize: 17, color: activeSubject ? '#3B82F6' : '#2D1B0E', fontWeight: 700, cursor: activeSubject ? 'pointer' : 'default' }}>
          Mes matières
        </span>
        {activeSubject && (
          <>
            <span style={{ fontSize: 17, color: '#C8A090' }}>›</span>
            <span style={{ fontSize: 17, fontWeight: 700, color: '#2D1B0E' }}>{activeSubject}</span>
          </>
        )}
      </div>

      <div style={{ flex: 1, padding: '18px 60px', overflowY: 'auto' }}>
        {loading ? (
          <div style={{ textAlign: 'center', padding: 80, fontSize: 22, color: '#C8A090' }}>Chargement...</div>
        ) : !activeSubject ? (
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 22 }}>
            {subjects.map((sub, idx) => {
              const c2 = gc(sub.name)
              const p2 = sub.total > 0 ? Math.round(sub.done / sub.total * 100) : 0
              const isFoc = idx === focused
              return (
                <div key={sub.name} onClick={() => { setActiveSubject(sub.name); setFocused(0) }} onMouseEnter={() => setFocused(idx)}
                  style={{ background: c2.bg, borderRadius: 20, padding: '26px 30px', cursor: 'pointer', transition: 'all 0.15s', border: `3px solid ${isFoc ? c2.accent : c2.accent + '33'}`, transform: isFoc ? 'scale(1.05)' : 'scale(1)' }}>
                  <div style={{ fontSize: 15, fontWeight: 700, color: c2.accent, textTransform: 'uppercase', letterSpacing: '0.4px', marginBottom: 6 }}>{sub.name}</div>
                  <div style={{ fontSize: 16, color: '#2D1B0E', fontWeight: 600, marginBottom: 14 }}>{sub.done}/{sub.total} exercices</div>
                  <div style={{ height: 8, background: c2.accent + '22', borderRadius: 4, marginBottom: 6 }}>
                    <div style={{ height: 8, borderRadius: 4, background: c2.accent, width: `${p2}%` }}/>
                  </div>
                  <div style={{ fontSize: 13, color: c2.accent, fontWeight: 700 }}>{p2}%</div>
                  {isFoc && (
                    <div style={{ marginTop: 12, background: c2.accent, color: 'white', borderRadius: 8, padding: '8px 0', textAlign: 'center', fontSize: 14, fontWeight: 800 }}>
                      OK — Lancer session ▶
                    </div>
                  )}
                </div>
              )
            })}
          </div>
        ) : (
          <div>
            <div style={{ marginBottom: 16, display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <div style={{ fontSize: 16, color: '#8A6050' }}>{subExs.filter(e => !completed.includes(e.id)).length} exercices restants</div>
              {subExs.filter(e => !completed.includes(e.id)).length > 0 && (
                <button onClick={() => setSession(subExs.filter(e => !completed.includes(e.id)))}
                  style={{ padding: '12px 28px', borderRadius: 14, border: 'none', background: gc(activeSubject).accent, color: 'white', fontSize: 16, fontWeight: 800, cursor: 'pointer' }}>
                  ▶ Lancer la session ({subExs.filter(e => !completed.includes(e.id)).length})
                </button>
              )}
            </div>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 18 }}>
              {subExs.map((ex, idx) => {
                const isDone = completed.includes(ex.id)
                const c2 = gc(ex.subject)
                const isFoc = idx === focused
                return (
                  <div key={ex.id} onMouseEnter={() => setFocused(idx)}
                    style={{ background: isDone ? '#F9F6F3' : c2.bg, borderRadius: 18, padding: '20px 24px', opacity: isDone ? 0.6 : 1, border: `3px solid ${isFoc && !isDone ? c2.accent : c2.accent + '33'}`, transform: isFoc && !isDone ? 'scale(1.04)' : 'scale(1)', transition: 'all 0.15s' }}>
                    <div style={{ fontSize: 11, fontWeight: 700, color: isDone ? '#B8A090' : c2.accent, textTransform: 'uppercase', marginBottom: 5 }}>{ex.category}</div>
                    <div style={{ fontSize: 16, fontWeight: 800, color: isDone ? '#9A8A80' : '#2D1B0E', lineHeight: 1.3 }}>{ex.title}</div>
                    {isDone && <div style={{ fontSize: 13, color: '#10B981', fontWeight: 700, marginTop: 6 }}>✓ Terminé</div>}
                  </div>
                )
              })}
            </div>
          </div>
        )}
      </div>

      <div style={{ padding: '12px 60px', textAlign: 'center', fontSize: 14, color: '#D4C0B0', borderTop: '1px solid #F0E4D8', background: 'white' }}>
        {activeSubject ? '← → naviguer · OK / bouton lancer session · ESC ← matières' : '← → naviguer · OK ouvrir matière · ESC déconnexion'}
      </div>
    </div>
  )
}

// ── Main TVApp ────────────────────────────────────────────────────────────────
export default function TVApp() {
  const [children, setChildren] = useState<Child[]>([])
  const [loading, setLoading] = useState(true)
  const [selected, setSelected] = useState<Child | null>(null)
  const [logged, setLogged] = useState<Child | null>(null)

  useEffect(() => {
    getChildren().then(data => setChildren(Array.isArray(data) ? data : (data?.data ?? []))).finally(() => setLoading(false))
  }, [])

  if (loading) return (
    <div style={{ background: '#FFF8F2', minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 28, color: '#C8A090', fontFamily: "system-ui,sans-serif" }}>
      EduMaison · Chargement...
    </div>
  )

  if (logged) return <TVDashboard child={logged} onLogout={() => { setLogged(null); setSelected(null) }} />
  if (selected) return <TVPinEntry child={selected} onSuccess={setLogged} onBack={() => setSelected(null)} />
  return <TVChildSelect children={children} onSelect={setSelected} />
}
