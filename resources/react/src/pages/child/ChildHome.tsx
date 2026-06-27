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
import RevisionPage from './RevisionPage'
import RemediationPage from './RemediationPage'
import { getExercisesForChild, getMoreExercisesForChild, saveAttempt } from '../../services/api'
import { useStreak } from '../../hooks/useStreak'
import { useOfflineSync } from '../../hooks/useOfflineSync'
import OfflineBanner from '../../components/OfflineBanner'
import ExamBanner from '../../components/ExamBanner'
import type { Exercise } from '../../types/exercise'
import type { Child } from '../../types/child'

function shuffleArray<T>(arr: T[]): T[] {
  const a = [...arr]
  for (let i = a.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [a[i], a[j]] = [a[j], a[i]]
  }
  return a
}

interface Props { child: Child; onLogout: () => void }
type Tab = 'home' | 'subjects' | 'progress' | 'profile' | 'bulletin' | 'review' | 'remediation'

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
  // Map id -> avatar (charge en parallele depuis /api/children)
  const [avatars, setAvatars] = useState<Record<number, string>>({})

  useEffect(() => {
    fetch(`/api/leaderboard/child/${child.id}`)
      .then(r => r.json())
      .then(data => setEntries(Array.isArray(data) ? data : []))
      .catch(() => {})
    fetch('/api/children')
      .then(r => r.json())
      .then((data: Array<{ id: number; avatar?: string }>) => {
        if (!Array.isArray(data)) return
        const map: Record<number, string> = {}
        for (const c of data) if (c.avatar) map[c.id] = '/storage/' + c.avatar
        setAvatars(map)
      })
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
          {avatars[e.id] ? (
            <img src={avatars[e.id]} alt={e.name} style={{
              width: 42, height: 42, borderRadius: '50%', objectFit: 'cover',
              border: e.is_current ? '2px solid #FFE45D' : '2px solid rgba(255,255,255,0.35)',
              display: 'block', margin: '0 auto'
            }}/>
          ) : (
            <div style={{ fontSize: 28, lineHeight: 1 }}>{medals[i] || ''}</div>
          )}
          <div style={{ fontSize: 14, fontWeight: 900, color: 'white', marginTop: 4 }}>
            {e.name.split(' ')[0]}
          </div>
          <div style={{ fontSize: 11, color: 'rgba(255,255,255,0.85)' }}>{e.xp}xp</div>
          {e.streak > 0 && <div style={{ fontSize: 11, color: '#FFE45D' }}>{BOLT}{e.streak}j</div>}
        </div>
      ))}
    </div>
  )
}

function MamaJudiSmall({ size = 76 }: { size?: number } = {}) {
  const [src, setSrc] = React.useState<string | null>(null)
  React.useEffect(() => {
    fetch('/api/mama/profile').then(r => r.json()).then(d => {
      if (d.avatar) setSrc('/storage/' + d.avatar)
    }).catch(() => {})
  }, [])
  if (src) return <img src={src} style={{ width: size, height: size, borderRadius: '50%', objectFit: 'cover', border: '3px solid #1D6B2A', boxShadow: '0 2px 8px rgba(0,0,0,0.15)' }} />
  return (
    <svg viewBox="0 0 60 60" width={size} height={size} xmlns="http://www.w3.org/2000/svg">
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
  const [tab, setTab] = useState<Tab>(() => {
    const saved = localStorage.getItem('edumaison_tab_' + child.id)
    const valid = ['home', 'subjects', 'progress', 'profile', 'bulletin', 'review', 'remediation']
    return (saved && valid.includes(saved) ? saved : 'home') as Tab
  })
  const [exercises, setExercises] = useState<(Exercise & { subject: string })[]>([])
  const [exPage, setExPage] = useState(1)
  const [hasMore, setHasMore] = useState(false)
  const [loadingMore, setLoadingMore] = useState(false)
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
  const streakData = useStreak(child)
  const { isOnline, syncPending } = useOfflineSync(child)
  // Plan de remediation (calcule depuis les bulletins reels : school_results < 12/20)
  const [remediation, setRemediation] = useState<{ status: string; plans: Array<{ subject: string; average: number; priority: string }> } | null>(null)
  useEffect(() => {
    fetch(`/api/remediation/child/${child.id}`)
      .then(r => r.ok ? r.json() : null)
      .then(d => { if (d) setRemediation(d) })
      .catch(() => {})
  }, [child.id])

  // Refs pour eviter stale closure dans popstate handler
  const activeRef = useRef(active)
  const activeExamRef = useRef(activeExam)
  const tabRef = useRef(tab)
  const activeDuelIdRef = useRef(activeDuelId)
  const showQuitRef = useRef(false)
  const quitPushCount = useRef(0)
  useEffect(() => { activeRef.current = active }, [active])
  useEffect(() => { activeExamRef.current = activeExam }, [activeExam])
  useEffect(() => { tabRef.current = tab }, [tab])
  useEffect(() => { activeDuelIdRef.current = activeDuelId }, [activeDuelId])
  useEffect(() => { showQuitRef.current = showQuitDialog }, [showQuitDialog])

  // Native back button support
  const pushNav = () => window.history.pushState({}, '')

  useEffect(() => {
    // Restaurer le tab actif apres actualisation
    const saved = localStorage.getItem('edumaison_tab_' + child.id)
    if (saved && ['home','subjects','progress','profile','bulletin','review','remediation'].includes(saved)) {
      setTab(saved as Tab)
    }
    window.history.pushState({ sentinel: true }, '')
  }, [])

  // Sauvegarder le tab actif a chaque changement
  useEffect(() => {
    localStorage.setItem('edumaison_tab_' + child.id, tab)
  }, [tab, child.id])

  useEffect(() => {
    const handleBack = (_e: PopStateEvent) => {
      if (activeExamRef.current) { setActiveExam(null); pushNav(); return }
      if (activeDuelIdRef.current) { pushNav(); return }
      if (activeRef.current) { setActive(null); pushNav(); return }
      if (tabRef.current !== 'home') {
        setTab('home')
        setOpenSubjectName(null)
        pushNav()
        return
      }
      // Ne pas afficher le dialog deux fois
      if (showQuitRef.current) { pushNav(); return }
      setShowQuitDialog(true)
      pushNav()
      quitPushCount.current++
    }
    window.addEventListener('popstate', handleBack)
    return () => window.removeEventListener('popstate', handleBack)
  }, [])

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
    // Charge la premiere page uniquement -- lazy loading pour les suivantes
    getExercisesForChild(child.id, child.level_id!)
      .then(first => {
        setExercises(shuffleArray(first))
        setLoading(false)
      })
      .catch(() => setLoading(false))
    // Verifier s'il y a plus de pages
    getMoreExercisesForChild(child.id, child.level_id!, 2)
      .then(({ exercises: more, hasMore: hm }) => {
        setHasMore(hm || more.length > 0)
      })
      .catch(() => {})
  }, [child.id])

  const loadingMoreRef = useRef(false)
  const hasMoreRef = useRef(false)
  const exPageRef = useRef(1)
  useEffect(() => { hasMoreRef.current = hasMore }, [hasMore])
  useEffect(() => { exPageRef.current = exPage }, [exPage])

  const loadMoreExercises = async () => {
    if (loadingMoreRef.current || !hasMoreRef.current) return
    loadingMoreRef.current = true
    setLoadingMore(true)
    const nextPage = exPageRef.current + 1
    const { exercises: more, hasMore: hm } = await getMoreExercisesForChild(child.id, child.level_id!, nextPage)
    setExercises(prev => [...prev, ...shuffleArray(more)])
    setExPage(nextPage)
    setHasMore(hm)
    loadingMoreRef.current = false
    setLoadingMore(false)
    // Cascade : si la sentinelle est toujours dans le viewport ET qu'il reste des pages,
    // on enchaine. Sinon l'utilisateur restait coince a la page 2 quand le contenu charge
    // ne suffisait pas a faire scroller la page (cas tablette / page courte).
    setTimeout(() => {
      if (!hm) return
      const el = sentinelRef.current
      if (!el) return
      const rect = el.getBoundingClientRect()
      if (rect.top < window.innerHeight + 200) loadMoreExercises()
    }, 250)
  }

  // IntersectionObserver -- charge plus quand sentinel visible
  const sentinelRef = useRef<HTMLDivElement | null>(null)
  useEffect(() => {
    const el = sentinelRef.current
    if (!el) return
    const observer = new IntersectionObserver(
      entries => { if (entries[0].isIntersecting) loadMoreExercises() },
      { threshold: 0.1 }
    )
    observer.observe(el)
    return () => observer.disconnect()
  }, [hasMore])

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
    ? `${remaining} activit${remaining > 1 ? 'ies' : 'y'} to go today. Let's go!`
    : 'You completed everything! Well done!'

  const firstName = child.name.split(' ')[0]

  const NAV_ITEMS = [
    { id: 'home' as Tab, label: 'Home', icon: '\u{1F3E0}' },
    { id: 'subjects' as Tab, label: 'Subjects', icon: '\u{1F4DA}' },
    { id: 'review' as Tab, label: 'Revision', icon: '\u{1F4D6}' },
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
      {tab === 'review'   && <RevisionPage  child={child} onBack={() => setTab('home')} />}
      {tab === 'remediation' && <RemediationPage child={child} onBack={() => setTab('home')} />}

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
            {/* Mama Judi -- presente, bulle de dialogue avec pointe */}
            <div style={{ display: 'flex', gap: 12, alignItems: 'center', marginBottom: 16 }}>
              <MamaJudiSmall size={76} />
              <div style={{
                position: 'relative', background: 'var(--card)', borderRadius: 18, padding: '14px 16px',
                flex: 1, border: '2px solid #1D6B2A', boxShadow: '0 2px 10px rgba(29,107,42,0.08)'
              }}>
                {/* pointe de la bulle (triangle) vers Mama Judi a gauche */}
                <span style={{
                  position: 'absolute', left: -10, top: '50%', transform: 'translateY(-50%)',
                  width: 0, height: 0,
                  borderTop: '8px solid transparent', borderBottom: '8px solid transparent',
                  borderRight: '10px solid #1D6B2A'
                }} />
                <span style={{
                  position: 'absolute', left: -7, top: '50%', transform: 'translateY(-50%)',
                  width: 0, height: 0,
                  borderTop: '6px solid transparent', borderBottom: '6px solid transparent',
                  borderRight: '8px solid var(--card)'
                }} />
                {/* Prenom en GROS, accroche claire */}
                <div style={{ fontSize: 22, fontWeight: 900, color: '#1D6B2A', lineHeight: 1.1, marginBottom: 4 }}>
                  Hey {firstName}!
                </div>
                {/* Message Mama Judi en taille normale */}
                <div style={{ fontSize: 14, fontWeight: 700, color: 'var(--text-dark)', lineHeight: 1.35 }}>{judiMsg}</div>
                <div onClick={() => {
                  if ('speechSynthesis' in window) {
                    window.speechSynthesis.cancel()
                    const u = new SpeechSynthesisUtterance(judiMsg)
                    u.lang = 'en-GB'; u.rate = 0.9
                    window.speechSynthesis.speak(u)
                  }
                }} style={{ fontSize: 12, color: '#1D6B2A', marginTop: 6, cursor: 'pointer', fontWeight: 700 }}>
                  {SPEAKER} tap to hear again
                </div>
              </div>
            </div>

            {/* Niveau seul (le prenom est dans la bulle Mama Judi maintenant) */}
            <div style={{ fontSize: 12, color: 'var(--text-soft)', marginTop: -6, marginBottom: 16, marginLeft: 4 }}>
              {child.level}
            </div>

            {/* Exam banner */}
            <ExamBanner child={child} onStartExam={setActiveExam} />

            {/* Plan de remediation -- visible UNIQUEMENT si l'enfant a des lacunes (bulletin < 12/20) */}
            {remediation && remediation.status === 'needs_work' && remediation.plans.length > 0 && (() => {
              const critical = remediation.plans.filter(p => p.priority === 'critical').length
              const tone = critical > 0
                ? { bg: '#FEE2E2', border: '#DC2626', accent: '#991B1B', emoji: '🚨' }
                : { bg: '#FEF3C7', border: '#D97706', accent: '#92400E', emoji: '⚠️' }
              const topSubjects = remediation.plans.slice(0, 3).map(p => p.subject).join(' · ')
              return (
                <div onClick={() => { pushNav(); setTab('remediation') }} style={{
                  background: tone.bg, borderRadius: 18, padding: '14px 16px',
                  marginBottom: 20, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: 12,
                  border: `2px solid ${tone.border}`,
                  boxShadow: `0 2px 12px ${tone.border}22`
                }}>
                  <div style={{ fontSize: 30, lineHeight: 1, flexShrink: 0 }}>{tone.emoji}</div>
                  <div style={{ flex: 1 }}>
                    <div style={{ fontSize: 11, fontWeight: 900, color: tone.accent, textTransform: 'uppercase', letterSpacing: '1px', marginBottom: 2 }}>
                      Plan de remédiation
                    </div>
                    <div style={{ fontSize: 15, fontWeight: 900, color: '#1F1A14', lineHeight: 1.25 }}>
                      {remediation.plans.length} matière{remediation.plans.length > 1 ? 's' : ''} à rattraper
                    </div>
                    <div style={{ fontSize: 11, color: tone.accent, marginTop: 2, fontWeight: 700 }}>{topSubjects}</div>
                  </div>
                  <div style={{ width: 32, height: 32, borderRadius: '50%', background: tone.border, color: 'white', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, fontSize: 16, fontWeight: 900 }}>{ARROW}</div>
                </div>
              )
            })()}

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

            {/* Toutes les matieres -- grille responsive, prioritaires (moins avancees) en premier */}
            {Object.keys(bySubject).length > 0 && (() => {
              const allSubjects = Object.entries(bySubject).map(([subject, exs]) => {
                const done = exs.filter(e => completed.includes(e.id)).length
                const pct = exs.length > 0 ? Math.round(done / exs.length * 100) : 0
                return { subject, exs, done, pct }
              })
              // tri : moins avance d'abord (plus prioritaire pour reprendre)
              allSubjects.sort((a, b) => a.pct - b.pct)
              return (
                <>
                  <div style={{ fontSize: 13, fontWeight: 900, color: 'var(--text-dark)', textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: 10 }}>
                    TES MATIÈRES
                  </div>
                  <div style={{
                    display: 'grid',
                    gridTemplateColumns: 'repeat(auto-fill, minmax(120px, 1fr))',
                    gap: 10, marginBottom: 16
                  }}>
                    {allSubjects.map(({ subject, pct }) => {
                      const icon = SUBJECT_ICONS[subject] || '\u{1F4CB}'
                      const barColor = pct >= 70 ? '#4CAF50' : pct >= 40 ? '#F59E0B' : '#1D6B2A'
                      return (
                        <div key={subject} onClick={() => { pushNav(); setOpenSubjectName(subject); setTab('subjects') }} style={{
                          background: 'var(--card)', borderRadius: 16, padding: '14px 10px',
                          cursor: 'pointer', border: '1.5px solid var(--border)',
                          display: 'flex', flexDirection: 'column', alignItems: 'center', textAlign: 'center', gap: 6
                        }}>
                          <span style={{ fontSize: 36, lineHeight: 1 }}>{icon}</span>
                          <div style={{ fontSize: 12, fontWeight: 800, color: 'var(--text-dark)', lineHeight: 1.2, minHeight: 28 }}>{subject}</div>
                          <div style={{ width: '100%', height: 5, background: 'var(--border)', borderRadius: 3 }}>
                            <div style={{ height: 5, borderRadius: 3, background: barColor, width: pct + '%' }}/>
                          </div>
                          <div style={{ fontSize: 10, color: 'var(--text-soft)', fontWeight: 700 }}>{pct}%</div>
                        </div>
                      )
                    })}
                  </div>
                </>
              )
            })()}
          </div>
        </>
      )}

      {/* Sentinel infinite scroll */}
      <div ref={sentinelRef} style={{ height: 20, marginBottom: 80 }}>
        {loadingMore && (
          <div style={{ textAlign: 'center' as const, padding: '10px 0',
            fontSize: 13, color: '#7A6050', fontWeight: 700 }}>
            Loading more...
          </div>
        )}
      </div>

      {/* Bottom nav — always visible. maxWidth aligne sur le shell App.tsx (720 ou ecran). */}
      <div style={{ position: 'fixed', bottom: 0, left: '50%', transform: 'translateX(-50%)', width: '100%', maxWidth: Math.min(720, window.innerWidth), background: 'var(--card)', borderTop: '2px solid var(--border)', padding: '10px 0 14px', display: 'flex', zIndex: 100 }}>
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
            width: '100%', maxWidth: 480, textAlign: 'center'
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
            width: '100%', maxWidth: 480, textAlign: 'center'
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
            width: '100%', maxWidth: 480, textAlign: 'center',
            boxShadow: '0 8px 40px rgba(0,0,0,0.25)'
          }}>
            <div style={{ fontSize: 48, marginBottom: 12 }}>📚</div>
            <div style={{ fontSize: 18, fontWeight: 900, color: 'var(--text-dark)', marginBottom: 8 }}>Quit EduMaison?</div>
            <div style={{ fontSize: 14, color: 'var(--text-soft)', marginBottom: 24 }}>Your progress is saved. See you soon!</div>
            <div style={{ display: 'flex', gap: 12 }}>
              <button onClick={() => setShowQuitDialog(false)} style={{ flex: 1, padding: '12px', borderRadius: 14, border: '2px solid var(--border)', background: 'var(--bg)', fontSize: 15, fontWeight: 800, color: 'var(--text-dark)', cursor: 'pointer' }}>Stay</button>
              <button onClick={() => { quitPushCount.current = 0; setShowQuitDialog(false); onLogout() }} style={{ flex: 1, padding: '12px', borderRadius: 14, border: 'none', background: '#1D6B2A', fontSize: 15, fontWeight: 800, color: 'white', cursor: 'pointer' }}>Quit</button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
