import { useState, useEffect, useRef } from 'react'
import { getExercisesForChild, getMoreExercisesForChild, saveAttempt } from './services/api'
import { useStreak } from './hooks/useStreak'
import ExercisePlayer from './pages/child/ExercisePlayer'
import SubjectsPage from './pages/child/SubjectsPage'
import ProgressPage from './pages/child/ProgressPage'
import ProfilePage from './pages/child/ProfilePage'
import BulletinPage from './pages/child/BulletinPage'
import ExamSession from './pages/child/ExamSession'
import ExamBanner from './components/ExamBanner'
import Confetti from './components/Confetti'
import type { Child } from './types/child'
import type { Exercise } from './types/exercise'
import { MamaJudi } from './services/MamaJudi'

type Tab = 'home' | 'subjects' | 'progress' | 'profile'

const SUBJECT_ICONS: Record<string, string> = {
  English: '\u{1F4D6}', Mathematics: '\u{1F4D0}', French: '\u{1F4AC}',
  'Science and Technology': '\u{1F52C}', ICT: '\u{1F4BB}', Citizenship: '\u{1F3DB}',
  Reading: '\u{1F4DA}', Handwriting: '\u270D', 'Social Studies': '\u{1F30D}',
  'National Languages and Cultures': '\u{1F3AD}', 'Arts and Crafts': '\u{1F3A8}',
  'Physical Education': '\u26BD', 'Home Economics and Vocational Skills': '\u{1F3E0}',
  'Artistic Activities': '\u{1F3A8}', 'FSLC Preparation': '\u{1F393}',
}

function MamaJudiDesk() {
  const [src, setSrc] = useState<string | null>(null)
  useEffect(() => {
    fetch('/api/mama/profile').then(r => r.json()).then(d => {
      if (d.avatar) setSrc('/storage/' + d.avatar)
    }).catch(() => {})
  }, [])
  if (src) return (
    <div style={{ width: 32, height: 32, borderRadius: '50%', overflow: 'hidden',
      border: '2px solid rgba(255,255,255,.35)', flexShrink: 0 }}>
      <img src={src} alt='Mama Judi' style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
    </div>
  )
  return (

    <svg viewBox="0 0 84 84" width="72" height="72" xmlns="http://www.w3.org/2000/svg">
      <circle cx="42" cy="42" r="42" fill="#C8874A"/>
      <circle cx="42" cy="38" r="22" fill="#A06830"/>
      <circle cx="33" cy="34" r="4" fill="#1A0A00"/>
      <circle cx="51" cy="34" r="4" fill="#1A0A00"/>
      <circle cx="34.5" cy="32.5" r="1.5" fill="white"/>
      <circle cx="52.5" cy="32.5" r="1.5" fill="white"/>
      <path d="M30 48 Q42 58 54 48" stroke="#1A0A00" strokeWidth="2.2" fill="none" strokeLinecap="round"/>
      <rect x="6" y="0" width="72" height="28" rx="36" fill="#2A1500"/>
    </svg>
  )
}

interface Props { child: Child; onLogout: () => void }

export default function DesktopApp({ child, onLogout }: Props) {
  const [tab, setTab] = useState<Tab>('home')
  const [exercises, setExercises] = useState<(Exercise & { subject: string })[]>([])
  const [loading, setLoading] = useState(true)
  const [active, setActive] = useState<(Exercise & { subject: string }) | null>(null)
  const [completed, setCompleted] = useState<number[]>([])
  const [activeExam, setActiveExam] = useState<any>(null)
  const [openSubjectName, setOpenSubjectName] = useState<string | null>(null)
  const [showBulletin, setShowBulletin] = useState(false)
  const [confetti, setConfetti] = useState(false)
  const streakData = useStreak(child)
  const playAllRef = useRef(false)
  const exercisesRef = useRef<any[]>([])
  const completedRef = useRef<number[]>([])

  useEffect(() => {
    if (!child.id || !child.level_id) return
    getExercisesForChild(child.id, child.level_id!)
      .then(first => {
        setExercises(first)
        exercisesRef.current = first
        setLoading(false)
        // Charge les pages suivantes en arriere-plan
        let page = 2
        const loadMore = async () => {
          const { exercises, hasMore } = await getMoreExercisesForChild(child.id, child.level_id!, page)
          if (exercises.length > 0) {
            setExercises(prev => { const u = [...prev, ...exercises]; exercisesRef.current = u; return u })
          }
          if (hasMore) { page++; setTimeout(loadMore, 500) }
        }
        setTimeout(loadMore, 1000)
      })
      .catch(() => setLoading(false))
  }, [child.id])

  useEffect(() => {
    MamaJudi.setChild(child.name)
    setTimeout(() => MamaJudi.greeting(), 600)
  }, [])

  const handleComplete = async (score: number) => {
    if (!active) return
    await saveAttempt(child.id, active.id, score)
    const nc = [...completedRef.current, active.id]
    completedRef.current = nc
    setCompleted(nc)
    if (nc.length === exercisesRef.current.length) {
      playAllRef.current = false; setActive(null); setConfetti(true)
      setTimeout(() => setConfetti(false), 3500)
    } else if (playAllRef.current) {
      const next = exercisesRef.current.find(e => !nc.includes(e.id))
      if (next) setActive(next); else { playAllRef.current = false; setActive(null) }
    } else { setActive(null) }
  }

  const streak = streakData?.streak ?? 0
  const xp = completed.length * 10
  const remaining = exercises.length - completed.length
  const firstName = child.name.split(' ')[0]

  const NAV = [
    { id: 'home' as Tab,     label: 'Home',     icon: '🏠' },
    { id: 'subjects' as Tab, label: 'Subjects', icon: '📚' },
    { id: 'progress' as Tab, label: 'Progress', icon: '📊' },
    { id: 'profile' as Tab,  label: 'Profile',  icon: '👤' },
  ]

  if (activeExam) return <ExamSession child={child} exam={activeExam} onBack={() => setActiveExam(null)} onComplete={() => setActiveExam(null)} />
  if (active) return <ExercisePlayer key={active.id} exercise={active} onComplete={handleComplete} onBack={() => { playAllRef.current = false; setActive(null) }} />

  const bySubject: Record<string, (Exercise & { subject: string })[]> = {}
  exercises.forEach(ex => { if (!bySubject[ex.subject]) bySubject[ex.subject] = []; bySubject[ex.subject].push(ex) })

  return (
    <div style={{ display: 'flex', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', background: 'var(--bg)' }}>
      <Confetti active={confetti} />

      {/* Sidebar */}
      <div style={{ width: 260, background: '#1D6B2A', display: 'flex', flexDirection: 'column', flexShrink: 0, position: 'sticky', top: 0, height: '100vh', overflowY: 'auto' }}>
        <div style={{ textAlign: 'center', padding: '24px 20px 20px', borderBottom: '1px solid rgba(255,255,255,0.15)' }}>
          <div style={{ fontSize: 13, fontWeight: 900, color: 'rgba(255,255,255,0.6)', letterSpacing: '2px', marginBottom: 12 }}>EDUMAISON</div>
          {(child as any).avatar
            ? <img src={(child as any).avatar.startsWith('http') ? (child as any).avatar : '/storage/' + (child as any).avatar}
                style={{ width: 72, height: 72, borderRadius: '50%', objectFit: 'cover',
                  border: '3px solid rgba(255,255,255,.35)', flexShrink: 0 }} />
            : <div style={{ width: 72, height: 72, borderRadius: '50%', background: 'rgba(255,255,255,0.2)',
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                fontSize: 32, fontWeight: 900, color: 'white', flexShrink: 0 }}>
                {firstName[0]}
              </div>
          }
          <div style={{ fontSize: 20, fontWeight: 900, color: 'white', marginTop: 10 }}>{firstName}</div>
          <div style={{ fontSize: 12, color: 'rgba(255,255,255,0.65)', marginTop: 3 }}>{child.level}</div>
          <div style={{ fontSize: 11, color: 'rgba(255,255,255,0.5)', marginTop: 2 }}>MARIO Nursery &amp; Primary</div>
        </div>
        <div style={{ display: 'flex', gap: 8, padding: '14px 12px', borderBottom: '1px solid rgba(255,255,255,0.15)' }}>
          {[{ label: 'Streak', val: streak, color: '#FFD700' }, { label: 'XP', val: xp, color: '#F59E0B' }, { label: 'Left', val: remaining, color: 'white' }].map(s => (
            <div key={s.label} style={{ flex: 1, background: 'rgba(255,255,255,0.1)', borderRadius: 12, padding: '10px 6px', textAlign: 'center' }}>
              <div style={{ fontSize: 20, fontWeight: 900, color: s.color }}>{s.val}</div>
              <div style={{ fontSize: 10, color: 'rgba(255,255,255,0.65)', marginTop: 2 }}>{s.label}</div>
            </div>
          ))}
        </div>
        <nav style={{ padding: '12px', flex: 1 }}>
          {NAV.map(item => (
            <button key={item.id} onClick={() => { setTab(item.id); setShowBulletin(false) }} style={{
              width: '100%', display: 'flex', alignItems: 'center', gap: 12,
              padding: '12px 16px', borderRadius: 14, border: 'none', cursor: 'pointer',
              marginBottom: 4, textAlign: 'left' as const,
              background: tab === item.id && !showBulletin ? 'rgba(255,255,255,0.2)' : 'transparent',
              color: tab === item.id && !showBulletin ? 'white' : 'rgba(255,255,255,0.65)',
              fontWeight: tab === item.id && !showBulletin ? 900 : 600, fontSize: 14,
              fontFamily: 'Nunito, system-ui, sans-serif', transition: 'all 0.15s'
            }}>
              <span style={{ fontSize: 20 }}>{item.icon}</span>
              {item.label}
            </button>
          ))}
          <button onClick={() => setShowBulletin(true)} style={{
            width: '100%', display: 'flex', alignItems: 'center', gap: 12,
            padding: '12px 16px', borderRadius: 14, border: 'none', cursor: 'pointer',
            background: showBulletin ? 'rgba(255,255,255,0.2)' : 'transparent',
            color: showBulletin ? 'white' : 'rgba(255,255,255,0.65)',
            fontWeight: showBulletin ? 900 : 600, fontSize: 14,
            fontFamily: 'Nunito, system-ui, sans-serif', textAlign: 'left' as const, marginBottom: 4
          }}>
            <span style={{ fontSize: 20 }}>📋</span>
            Report Card
          </button>
        </nav>
        <div style={{ padding: '14px 16px', borderTop: '1px solid rgba(255,255,255,0.15)' }}>
          <button onClick={() => window.location.href = '/mama'}
            style={{ width: '100%', padding: '10px 0', borderRadius: 12, border: 'none',
              background: 'rgba(255,255,255,0.1)', color: 'rgba(255,255,255,0.75)',
              fontSize: 13, fontWeight: 800, cursor: 'pointer', marginBottom: 8,
              fontFamily: 'Nunito, system-ui, sans-serif',
              display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 }}>
            <MamaJudiDesk />
            Mama Judi
          </button>
          <button onClick={() => window.location.href = '/mama'}
            style={{ width: '100%', padding: '10px 0', borderRadius: 12, border: 'none',
              background: 'rgba(255,255,255,0.1)', color: 'rgba(255,255,255,0.75)',
              fontSize: 13, fontWeight: 800, cursor: 'pointer', marginBottom: 8,
              fontFamily: 'Nunito, system-ui, sans-serif',
              display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 }}>
            <MamaJudiDesk />
            Mama Judi
          </button>
          <button onClick={onLogout} style={{
            width: '100%', padding: '10px 0', borderRadius: 12,
            border: '1.5px solid rgba(255,255,255,0.3)',
            background: 'transparent', color: 'rgba(255,255,255,0.8)',
            fontSize: 13, fontWeight: 800, cursor: 'pointer',
            fontFamily: 'Nunito, system-ui, sans-serif'
          }}>
            ⇄ Switch child
          </button>
        </div>
      </div>

      {/* Main content */}
      <div style={{ flex: 1, overflowY: 'auto', minHeight: '100vh' }}>
        {showBulletin && <BulletinPage child={child} onBack={() => setShowBulletin(false)} />}
        {!showBulletin && tab === 'subjects' && <SubjectsPage child={child} onBack={() => setTab('home')} initialSubjectName={openSubjectName} isDesktop />}
        {!showBulletin && tab === 'progress' && <ProgressPage child={child} onBack={() => setTab('home')} isDesktop />}
        {!showBulletin && tab === 'profile'  && <ProfilePage  child={child} onLogout={onLogout} onBack={() => setTab('home')} isDesktop />}
        {!showBulletin && tab === 'home' && (
          <div style={{ padding: '32px 40px', maxWidth: 900 }}>
            <div style={{ marginBottom: 28 }}>
              <div style={{ fontSize: 28, fontWeight: 900, color: 'var(--text-dark)' }}>Good day, {firstName}! 👋</div>
              <div style={{ fontSize: 14, color: 'var(--text-soft)', marginTop: 4 }}>
                {remaining > 0 ? `${remaining} activit${remaining > 1 ? 'ies' : 'y'} waiting for you today.` : 'All done today! Well done!'}
              </div>
            </div>
            <ExamBanner child={child} onStartExam={setActiveExam} />
            {!loading && exercises.find(e => !completed.includes(e.id)) && (() => {
              const next = exercises.find(e => !completed.includes(e.id))!
              return (
                <div onClick={() => setActive(next)} style={{ background: '#1D6B2A', borderRadius: 20, padding: '20px 28px', marginBottom: 28, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: 16 }}>
                  <div style={{ flex: 1 }}>
                    <div style={{ fontSize: 11, fontWeight: 800, color: 'rgba(255,255,255,0.65)', textTransform: 'uppercase', letterSpacing: '1px', marginBottom: 6 }}>📘 TODAY'S ACTIVITY</div>
                    <div style={{ fontSize: 20, fontWeight: 900, color: 'white' }}>{next.title}</div>
                    <div style={{ fontSize: 13, color: 'rgba(255,255,255,0.65)', marginTop: 4 }}>{next.subject}</div>
                  </div>
                  <div style={{ background: 'rgba(255,255,255,0.2)', borderRadius: 14, padding: '12px 20px', color: 'white', fontWeight: 900, fontSize: 14, flexShrink: 0 }}>Start →</div>
                </div>
              )
            })()}
            {!loading && (
              <>
                <div style={{ fontSize: 13, fontWeight: 900, color: 'var(--text-dark)', textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: 14 }}>ALL SUBJECTS</div>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 14, paddingBottom: 40 }}>
                  {Object.entries(bySubject).map(([subject, exs]) => {
                    const done = exs.filter(e => completed.includes(e.id)).length
                    const pct = Math.round(done / exs.length * 100)
                    const icon = SUBJECT_ICONS[subject] || '\u{1F4CB}'
                    const badgeColor = pct >= 70 ? '#4CAF50' : pct >= 40 ? '#F59E0B' : '#CE1126'
                    return (
                      <div key={subject} onClick={() => { setOpenSubjectName(subject); setTab('subjects') }}
                        style={{ background: 'var(--card)', borderRadius: 16, padding: '18px 16px', cursor: 'pointer', border: '1.5px solid var(--border)' }}>
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: 8 }}>
                          <span style={{ fontSize: 28 }}>{icon}</span>
                          <span style={{ background: badgeColor, color: 'white', borderRadius: 20, padding: '2px 10px', fontSize: 11, fontWeight: 800 }}>{pct}%</span>
                        </div>
                        <div style={{ fontSize: 14, fontWeight: 800, color: 'var(--text-dark)', marginBottom: 8, lineHeight: 1.2 }}>{subject}</div>
                        <div style={{ height: 5, background: 'var(--border)', borderRadius: 3 }}>
                          <div style={{ height: 5, borderRadius: 3, background: '#1D6B2A', width: pct + '%' }}/>
                        </div>
                        <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 6 }}>{done}/{exs.length} done</div>
                      </div>
                    )
                  })}
                </div>
              </>
            )}
            {loading && <div style={{ color: 'var(--text-soft)', padding: '40px 0', textAlign: 'center', fontSize: 15 }}>Loading subjects...</div>}
          </div>
        )}
      </div>
    </div>
  )
}
