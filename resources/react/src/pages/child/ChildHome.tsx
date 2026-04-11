import { MamaJudi } from '../../services/MamaJudi'
import { SoundService } from '../../services/SoundService'
import BackgroundMusic from '../../components/BackgroundMusic'
import React, { useState, useEffect, useRef } from 'react'
import ExercisePlayer from './ExercisePlayer'
import SubjectsPage from './SubjectsPage'
import ProgressPage from './ProgressPage'
import ProfilePage from './ProfilePage'
import ExamSession from './ExamSession'
import DuelSession from './DuelSession'
import BulletinPage from './BulletinPage'
import { getExercisesForChild, getMoreExercisesForChild, saveAttempt } from '../../services/api'
import { useStreak } from '../../hooks/useStreak'
import { useOfflineSync } from '../../hooks/useOfflineSync'
import OfflineBanner from '../../components/OfflineBanner'
import ExamBanner from '../../components/ExamBanner'
import type { Exercise } from '../../types/exercise'
import type { Child } from '../../types/child'

interface Props { child: Child; onLogout: () => void }
type Tab = 'home' | 'subjects' | 'progress' | 'profile' | 'bulletin'

// Subject icons as unicode escapes - no emoji literals
const SUBJECT_ICONS: Record<string, string> = {
  English: '\u{1F4D6}',
  Mathematics: '\u{1F4D0}',
  French: '\u{1F4AC}',
  'Science and Technology': '\u{1F52C}',
  ICT: '\u{1F4BB}',
  Citizenship: '\u{1F3DB}',
  Reading: '\u{1F4DA}',
  Handwriting: '\u{270D}',
  'Social Studies': '\u{1F30D}',
  'National Languages and Cultures': '\u{1F3AD}',
  'Arts and Crafts': '\u{1F3A8}',
  'Physical Education': '\u{26BD}',
  'Home Economics and Vocational Skills': '\u{1F3E0}',
  'Artistic Activities': '\u{1F3A8}',
}

const MEDAL_1 = '\u{1F947}'
const MEDAL_2 = '\u{1F948}'
const MEDAL_3 = '\u{1F949}'
const BOLT = '\u26A1'
const STAR = '\u2B50'
const PERSON = '\u{1F464}'
const SPEAKER = '\u{1F50A}'
const ARROW = '\u2192'
const BOOK = '\u{1F4D8}'

interface LeaderEntry {
  id: number; name: string; xp: number; streak: number; rank: number; is_current: boolean
}

function MiniLeaderboard({ child }: { child: Child }) {
  const [entries, setEntries] = useState<LeaderEntry[]>([])

  useEffect(() => {
    fetch(`/api/leaderboard/child/${child.id}`)
      .then(r => r.json())
      .then(data => setEntries(Array.isArray(data) ? data : []))
      .catch(() => {})
  }, [child.id])

  if (entries.length === 0) return null

  const medals = [MEDAL_1, MEDAL_2, MEDAL_3]

  return (
    <div style={{ display: 'flex', gap: 6 }}>
      {entries.map((e, i) => (
        <div key={e.id} style={{
          flex: 1,
          background: e.is_current ? 'rgba(255,255,255,0.25)' : 'rgba(255,255,255,0.1)',
          borderRadius: 14, padding: '8px 10px',
          border: e.is_current ? '1.5px solid rgba(255,255,255,0.5)' : '1px solid rgba(255,255,255,0.15)',
          textAlign: 'center'
        }}>
          <div style={{ fontSize: 20 }}>{medals[i] || String(i+1)}</div>
          <div style={{ fontSize: 15, fontWeight: 900, color: 'white', marginTop: 2 }}>
            {e.name.split(' ')[0]}
          </div>
          <div style={{ fontSize: 11, color: 'rgba(255,255,255,0.85)' }}>{e.xp}xp</div>
          <div style={{ fontSize: 11, color: 'rgba(255,255,255,0.75)' }}>{BOLT}{e.streak}j</div>
        </div>
      ))}
    </div>
  )
}

function MamaJudiSmall() {
  const [src, setSrc] = React.useState<string | null>(null)
  React.useEffect(() => {
    fetch('/api/mama/profile').then(r => r.json()).then(d => {
      if (d.avatar) setSrc('http://192.168.100.106/storage/' + d.avatar)
    }).catch(() => {})
  }, [])
  if (src) return <img src={src} style={{ width: 52, height: 52, borderRadius: '50%', objectFit: 'cover', border: '2px solid rgba(255,255,255,.3)' }} />
  return (
    <svg viewBox="0 0 60 60" width="52" height="52" xmlns="http://www.w3.org/2000/svg">
      <circle cx="30" cy="30" r="30" fill="#C8874A"/>
      <circle cx="30" cy="26" r="16" fill="#A06830"/>
      <circle cx="24" cy="23" r="3" fill="#1A0A00"/>
      <circle cx="36" cy="23" r="3" fill="#1A0A00"/>
      <circle cx="25" cy="21.5" r="1" fill="white"/>
      <circle cx="37" cy="21.5" r="1" fill="white"/>
      <path d="M22 33 Q30 40 38 33" stroke="#1A0A00" strokeWidth="1.8" fill="none" strokeLinecap="round"/>
      <rect x="4" y="0" width="52" height="18" rx="26" fill="#2A1500"/>
      <ellipse cx="30" cy="42" rx="5" ry="6" fill="#C8874A"/>
      <path d="M8 60 Q6 50 30 46 Q54 50 52 60 Z" fill="#FF8FAB"/>
    </svg>
  )
}

export default function ChildHome({ child, onLogout }: Props) {
  const [tab, setTab] = useState<Tab>('home')
  const [exercises, setExercises] = useState<(Exercise & { subject: string })[]>([])
  const [loading, setLoading] = useState(true)
  const [active, setActive] = useState<(Exercise & { subject: string }) | null>(null)
  const [completed, setCompleted] = useState<number[]>([])
  const [activeExam, setActiveExam] = useState<any>(null)
  const [openSubjectName, setOpenSubjectName] = useState<string | null>(null)
  const [showQuitDialog, setShowQuitDialog] = useState(false)
  const [pendingDuel, setPendingDuel] = useState<any>(null)
  const [pendingEvening, setPendingEvening] = useState<any>(null)
  const [activeDuelId, setActiveDuelId] = useState<number | null>(null)
  const [activeDuelData, setActiveDuelData] = useState<any>(null)
  const justArrivedAtHome = useRef(false)
  const streakData = useStreak(child)
  const { isOnline, syncPending } = useOfflineSync(child)

  // Native back button support
  const pushNav = () => window.history.pushState({}, '')

  useEffect(() => {
    window.history.pushState({ sentinel: true }, '')
  }, [])

  useEffect(() => {
    const handleBack = (_e: PopStateEvent) => {
      if (activeExam) { setActiveExam(null); pushNav(); return }
      if (active) { setActive(null); pushNav(); return }
      if (tab !== 'home') {
        justArrivedAtHome.current = true
        setTab('home')
        setOpenSubjectName(null)
        pushNav()
        return
      }
      // Already on Home
      if (justArrivedAtHome.current) {
        justArrivedAtHome.current = false
        pushNav()
        return
      }
      setShowQuitDialog(true)
      pushNav()
    }
    window.addEventListener('popstate', handleBack)
    return () => window.removeEventListener('popstate', handleBack)
  }, [activeExam, active, tab])

  useEffect(() => {
  }, [])

  // Polling toutes les 5s — duel et revision du soir en attente
  const activeDuelRef = useRef<number | null>(null)
  useEffect(() => {
    const poll = async () => {
      try {
        // Duel en attente
        const duelRes = await fetch(`/api/duels/pending/${child.id}`)
        const duel = await duelRes.json()
        if (duel && duel.id && !activeDuelRef.current) {
          activeDuelRef.current = duel.id
          setPendingDuel(duel)
        }
        // Revision du soir en attente
        const eveningRes = await fetch(`/api/evening-sessions/pending/${child.id}`)
        const evening = await eveningRes.json()
        if (evening && evening.id) {
          setPendingEvening(prev => prev?.id === evening.id ? prev : evening)
        }
      } catch (_) {}
    }
    poll() // immediat
    const interval = setInterval(poll, 5000)
    return () => clearInterval(interval)
  }, [child.id])

  useEffect(() => {
    MamaJudi.setChild(child.name)
    setTimeout(() => MamaJudi.greeting(), 500)
  }, [])

  useEffect(() => {
    if (!child.id || !child.level_id) return
    // Charge la premiere page immediatement
    getExercisesForChild(child.id, child.level_id!)
      .then(first => {
        setExercises(first)
        setLoading(false)
        // Charge les pages suivantes en arriere-plan
        let page = 2
        const loadMore = async () => {
          const { exercises, hasMore } = await getMoreExercisesForChild(child.id, child.level_id!, page)
          if (exercises.length > 0) {
            setExercises(prev => [...prev, ...exercises])
          }
          if (hasMore) {
            page++
            setTimeout(loadMore, 500) // delai pour ne pas surcharger
          }
        }
        setTimeout(loadMore, 1000) // demarre apres 1s
      })
      .catch(() => setLoading(false))
  }, [child.id])

  const handleComplete = async (score: number) => {
    if (!active) return
    await saveAttempt(child.id, active.id, score)
    setCompleted(prev => [...prev, active.id])
    setActive(null)
  }

  if (activeDuelId && activeDuelData) return (
    <DuelSession
      child={child}
      duel={{ ...activeDuelData, id: activeDuelId }}
      onComplete={() => { setActiveDuelId(null); setActiveDuelData(null); activeDuelRef.current = null }}
    />
  )
  if (activeExam) return <ExamSession child={child} exam={activeExam} onBack={() => setActiveExam(null)} onComplete={() => setActiveExam(null)} />
  if (active) return <ExercisePlayer exercise={active} onComplete={handleComplete} onBack={() => setActive(null)} />

  const stars = completed.length * 10
  const streak = streakData?.streak ?? 0
  const remaining = exercises.length - completed.length

  const bySubject: Record<string, (Exercise & { subject: string })[]> = {}
  exercises.forEach(ex => {
    if (!bySubject[ex.subject]) bySubject[ex.subject] = []
    bySubject[ex.subject].push(ex)
  })
  const prioritySubjects = Object.entries(bySubject)
    .filter(([_, exs]) => exs.some(e => !completed.includes(e.id)))
    .slice(0, 4)

  const judiMsg = loading
    ? 'Loading your activities...'
    : remaining > 0
    ? remaining + ' activit' + (remaining > 1 ? 'ies' : 'y') + " to go today. Let's go!"
    : 'Well done! You completed everything!'

  const firstName = child.name.split(' ')[0]

  const NAV_ITEMS = [
    { id: 'home' as Tab, label: 'Home', icon: '\u{1F3E0}' },
    { id: 'subjects' as Tab, label: 'Subjects', icon: '\u{1F4DA}' },
    { id: 'progress' as Tab, label: 'Progress', icon: '\u{1F4CA}' },
    { id: 'profile' as Tab, label: 'Profile', icon: '\u{1F464}' },
  ]

  return (
    <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', paddingBottom: 80 }}>

      {/* Sub-pages — rendered here so bottom nav stays visible */}
      {tab === 'subjects' && <SubjectsPage child={child} onBack={() => { setTab('home'); setOpenSubjectName(null) }} initialSubjectName={openSubjectName} />}
      {tab === 'progress' && <ProgressPage child={child} onBack={() => setTab('home')} />}
      {tab === 'profile'  && <ProfilePage  child={child} onLogout={onLogout} onBack={() => setTab('home')} />}
      {tab === 'bulletin' && <BulletinPage  child={child} onBack={() => setTab('home')} />}

      {/* Home content */}
      {tab === 'home' && (
        <>
          <OfflineBanner isOnline={isOnline} syncPending={syncPending} />

          {/* Header */}
          <div style={{ background: '#1D6B2A', padding: '10px 14px 8px' }}>
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 8 }}>
              <button onClick={() => setTab('profile')} style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 0 }}>
                <div style={{ width: 32, height: 32, borderRadius: 9, background: 'rgba(255,255,255,0.2)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 16 }}>
                  {PERSON}
                </div>
              </button>
              <div style={{ fontSize: 13, fontWeight: 900, color: 'rgba(255,255,255,0.9)', letterSpacing: '1px' }}>EDUMAISON</div>
              <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                <div style={{ background: 'rgba(255,255,255,0.15)', borderRadius: 20, padding: '3px 10px', display: 'flex', alignItems: 'center', gap: 4 }}>
                  <span style={{ fontSize: 13 }}>{BOLT}</span>
                  <span style={{ fontSize: 12, fontWeight: 800, color: 'white' }}>{streak}</span>
                </div>
                <button onClick={onLogout} style={{ background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: 10, padding: '4px 10px', color: 'white', fontSize: 12, fontWeight: 800, cursor: 'pointer' }}>
                  '⇄'
                </button>
              </div>
            </div>
            <MiniLeaderboard child={child} />
          </div>

          <div style={{ padding: '16px 18px 0' }}>
            {/* Mama Judi */}
            <div style={{ display: 'flex', gap: 12, alignItems: 'flex-start', marginBottom: 16 }}>
              <MamaJudiSmall />
              <div style={{ background: 'var(--card)', borderRadius: 16, borderTopLeftRadius: 4, padding: '12px 14px', flex: 1, border: '2px solid #1D6B2A' }}>
                <div style={{ fontSize: 14, fontWeight: 700, color: 'var(--text-dark)', lineHeight: 1.4 }}>{judiMsg}</div>
                <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 4 }}>{SPEAKER} tap to hear again</div>
              </div>
            </div>

            {/* Child name */}
            <div style={{ marginBottom: 16 }}>
              <div style={{ fontSize: 22, fontWeight: 900, color: 'var(--text-dark)' }}>{firstName}</div>
              <div style={{ fontSize: 12, color: 'var(--text-soft)', marginTop: 1 }}>{child.level} · MARIO Nursery & Primary School</div>
            </div>

            {/* Exam banner */}
            <ExamBanner child={child} onStartExam={setActiveExam} />

            {/* Today's activity */}
            {!loading && exercises.find(e => !completed.includes(e.id)) && (() => {
              const next = exercises.find(e => !completed.includes(e.id))!
              return (
                <div onClick={() => setActive(next)} style={{
                  background: '#1D6B2A', borderRadius: 18, padding: '16px 18px',
                  marginBottom: 20, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: 12
                }}>
                  <div style={{ flex: 1 }}>
                    <div style={{ fontSize: 10, fontWeight: 800, color: 'rgba(255,255,255,0.7)', textTransform: 'uppercase', letterSpacing: '1px', marginBottom: 4 }}>
                      {BOOK} TODAY'S ACTIVITY
                    </div>
                    <div style={{ fontSize: 17, fontWeight: 900, color: 'white', lineHeight: 1.2 }}>{next.title}</div>
                    <div style={{ fontSize: 12, color: 'rgba(255,255,255,0.7)', marginTop: 3 }}>{next.subject}</div>
                  </div>
                  <div style={{ width: 36, height: 36, borderRadius: '50%', background: 'rgba(255,255,255,0.2)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                    <span style={{ color: 'white', fontSize: 18 }}>{ARROW}</span>
                  </div>
                </div>
              )
            })()}

            {/* Priority subjects */}
            {prioritySubjects.length > 0 && (
              <>
                <div style={{ fontSize: 13, fontWeight: 900, color: 'var(--text-dark)', textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: 10 }}>
                  PRIORITY SUBJECTS
                </div>
                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 16 }}>
                  {prioritySubjects.map(([subject, exs]) => {
                    const done = exs.filter(e => completed.includes(e.id)).length
                    const pct = Math.round(done / exs.length * 100)
                    const icon = SUBJECT_ICONS[subject] || '\u{1F4CB}'
                    const badgeLabel = pct >= 70 ? 'Good' : pct >= 40 ? 'Watch' : 'Urgent'
                    const badgeBg = pct >= 70 ? '#4CAF50' : pct >= 40 ? '#F59E0B' : '#CE1126'
                    return (
                      <div key={subject} onClick={() => { pushNav(); setOpenSubjectName(subject); setTab('subjects') }} style={{ background: 'var(--card)', borderRadius: 16, padding: '14px 12px', cursor: 'pointer', border: '1.5px solid var(--border)' }}>
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: 6 }}>
                          <span style={{ fontSize: 24 }}>{icon}</span>
                          <span style={{ background: badgeBg, color: 'white', borderRadius: 20, padding: '2px 8px', fontSize: 10, fontWeight: 800 }}>{badgeLabel}</span>
                        </div>
                        <div style={{ fontSize: 13, fontWeight: 800, color: 'var(--text-dark)', marginBottom: 4, lineHeight: 1.2 }}>{subject}</div>
                        <div style={{ fontSize: 11, color: 'var(--text-soft)', marginBottom: 6 }}>{done * 10}pts · {pct}%</div>
                        <div style={{ height: 5, background: 'var(--border)', borderRadius: 3 }}>
                          <div style={{ height: 5, borderRadius: 3, background: '#1D6B2A', width: pct + '%' }}/>
                        </div>
                      </div>
                    )
                  })}
                </div>
              </>
            )}

            {/* Other subjects */}
            {Object.keys(bySubject).length > 4 && (
              <>
                <div style={{ fontSize: 13, fontWeight: 900, color: 'var(--text-dark)', textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: 10 }}>
                  OTHER SUBJECTS - TAP TO PRACTISE
                </div>
                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10, marginBottom: 16 }}>
                  {Object.entries(bySubject).slice(4).map(([subject, exs]) => {
                    const done = exs.filter(e => completed.includes(e.id)).length
                    const pct = Math.round(done / exs.length * 100)
                    const icon = SUBJECT_ICONS[subject] || '\u{1F4CB}'
                    const badgeLabel = pct >= 70 ? 'Good' : 'Watch'
                    const badgeBg = pct >= 70 ? '#4CAF50' : '#F59E0B'
                    return (
                      <div key={subject} onClick={() => { pushNav(); setOpenSubjectName(subject); setTab('subjects') }} style={{ background: 'var(--card)', borderRadius: 16, padding: '14px 12px', cursor: 'pointer', border: '1.5px solid var(--border)' }}>
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: 6 }}>
                          <span style={{ fontSize: 24 }}>{icon}</span>
                          <span style={{ background: badgeBg, color: 'white', borderRadius: 20, padding: '2px 8px', fontSize: 10, fontWeight: 800 }}>{badgeLabel}</span>
                        </div>
                        <div style={{ fontSize: 13, fontWeight: 800, color: 'var(--text-dark)', marginBottom: 4, lineHeight: 1.2 }}>{subject}</div>
                        <div style={{ fontSize: 11, color: 'var(--text-soft)', marginBottom: 6 }}>{done * 10}pts · {pct}%</div>
                        <div style={{ height: 5, background: 'var(--border)', borderRadius: 3 }}>
                          <div style={{ height: 5, borderRadius: 3, background: '#1D6B2A', width: pct + '%' }}/>
                        </div>
                      </div>
                    )
                  })}
                </div>
              </>
            )}
          </div>
        </>
      )}

      {/* Bottom nav — always visible */}
      <div style={{ position: 'fixed', bottom: 0, left: '50%', transform: 'translateX(-50%)', width: '100%', maxWidth: 480, background: 'var(--card)', borderTop: '2px solid var(--border)', padding: '10px 0 14px', display: 'flex', zIndex: 100 }}>
        {NAV_ITEMS.map(item => (
          <button key={item.id} onClick={() => { pushNav(); setTab(item.id as Tab) }} style={{
            flex: 1, background: 'none', border: 'none', cursor: 'pointer',
            display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 2,
            color: tab === item.id ? '#1D6B2A' : '#9A8A7A'
          }}>
            <span style={{ fontSize: 22 }}>{item.icon}</span>
            <span style={{ fontSize: 10, fontWeight: tab === item.id ? 900 : 600 }}>{item.label}</span>
          </button>
        ))}
      </div>
          <BackgroundMusic />

      {/* Popup Duel en attente */}
      {pendingDuel && !activeDuelId && (
        <div style={{
          position: 'fixed', inset: 0, zIndex: 9998,
          background: 'rgba(0,0,0,0.6)',
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          padding: '0 24px'
        }}>
          <div style={{
            background: 'white', borderRadius: 24, padding: '28px 24px',
            width: '100%', maxWidth: 360, textAlign: 'center'
          }}>
            <div style={{ fontSize: 52, marginBottom: 12 }}>⚔️</div>
            <div style={{ fontSize: 28, fontWeight: 900, color: '#3D2B1F', marginBottom: 8 }}>Duel !</div>
            <div style={{ fontSize: 14, color: '#8A6050', marginBottom: 8 }}>
              <span style={{ fontSize: 18, fontWeight: 800, color: '#3D2B1F' }}>{pendingDuel.child1_name} vs {pendingDuel.child2_name}</span>
            </div>
            <div style={{ fontSize: 13, color: '#C8A090', marginBottom: 24 }}>
              <span style={{ fontSize: 16 }}>{pendingDuel.nb_exercises} exercices</span>
            </div>
            <div style={{ display: 'flex', gap: 12 }}>
              <button onClick={() => setPendingDuel(null)}
                style={{ flex: 1, padding: '12px', borderRadius: 14, border: '2px solid #F0E4D8', background: '#FFF8F2', fontSize: 15, fontWeight: 800, color: '#8A6050', cursor: 'pointer' }}>
                Plus tard
              </button>
              <button onClick={() => {
                  SoundService.fanfare()
                  fetch(`/api/duels/${pendingDuel.id}/start`, { method: 'POST' })
                  setActiveDuelData(pendingDuel)
                  setActiveDuelId(pendingDuel.id)
                  setPendingDuel(null)
                }}
                style={{ flex: 1, padding: '12px', borderRadius: 14, border: 'none', background: '#FF8FAB', fontSize: 15, fontWeight: 800, color: 'white', cursor: 'pointer' }}>
                ⚡ Jouer !
              </button>
            </div>
          </div>
        </div>
      )}
      {/* Popup Revision du soir */}
      {pendingEvening && (
        <div style={{
          position: 'fixed', inset: 0, zIndex: 9997,
          background: 'rgba(0,0,0,0.6)',
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          padding: '0 24px'
        }}>
          <div style={{
            background: 'white', borderRadius: 24, padding: '28px 24px',
            width: '100%', maxWidth: 360, textAlign: 'center'
          }}>
            <div style={{ fontSize: 52, marginBottom: 12 }}>📚</div>
            <div style={{ fontSize: 20, fontWeight: 900, color: '#2D1B0E', marginBottom: 8 }}>Revision du soir !</div>
            {pendingEvening.mama_judi_message && (
              <div style={{ fontSize: 14, color: '#8A6050', marginBottom: 12, fontStyle: 'italic' }}>
                "{pendingEvening.mama_judi_message}"
              </div>
            )}
            {pendingEvening.subject_name && (
              <div style={{ fontSize: 13, color: '#C8A090', marginBottom: 16 }}>{pendingEvening.subject_name}</div>
            )}
            <div style={{ display: 'flex', gap: 12 }}>
              <button onClick={async () => {
                  await fetch(`/api/evening-sessions/${pendingEvening.id}/done`, { method: 'POST' })
                  setPendingEvening(null)
                }}
                style={{ flex: 1, padding: '12px', borderRadius: 14, border: '2px solid #F0E4D8', background: '#FFF8F2', fontSize: 15, fontWeight: 800, color: '#8A6050', cursor: 'pointer' }}>
                Plus tard
              </button>
              <button onClick={() => {
                  SoundService.levelup()
                  MamaJudi.speak(pendingEvening.mama_judi_message || 'Bonsoir ! Mama Judi a prepare ta revision.')
                  fetch(`/api/evening-sessions/${pendingEvening.id}/done`, { method: 'POST' })
                  setPendingEvening(null)
                }}
                style={{ flex: 1, padding: '12px', borderRadius: 14, border: 'none', background: '#1D6B2A', fontSize: 15, fontWeight: 800, color: 'white', cursor: 'pointer' }}>
                📚 Commencer !
              </button>
            </div>
          </div>
        </div>
      )}
      {showQuitDialog && (
        <div style={{
          position: 'fixed', inset: 0, zIndex: 9999,
          background: 'rgba(0,0,0,0.55)',
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          padding: '0 32px'
        }}>
          <div style={{
            background: 'var(--card)', borderRadius: 24, padding: '28px 24px',
            width: '100%', maxWidth: 360, textAlign: 'center',
            boxShadow: '0 8px 40px rgba(0,0,0,0.25)'
          }}>
            <div style={{ fontSize: 48, marginBottom: 12 }}>📚</div>
            <div style={{ fontSize: 18, fontWeight: 900, color: 'var(--text-dark)', marginBottom: 8 }}>Quit EduMaison?</div>
            <div style={{ fontSize: 14, color: 'var(--text-soft)', marginBottom: 24 }}>Your progress is saved. See you soon!</div>
            <div style={{ display: 'flex', gap: 12 }}>
              <button onClick={() => setShowQuitDialog(false)} style={{ flex: 1, padding: '12px', borderRadius: 14, border: '2px solid var(--border)', background: 'var(--bg)', fontSize: 15, fontWeight: 800, color: 'var(--text-dark)', cursor: 'pointer' }}>Stay</button>
              <button onClick={() => { setShowQuitDialog(false); window.history.back() }} style={{ flex: 1, padding: '12px', borderRadius: 14, border: 'none', background: '#1D6B2A', fontSize: 15, fontWeight: 800, color: 'white', cursor: 'pointer' }}>Quit</button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
