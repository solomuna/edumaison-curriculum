import { MamaJudi } from '../../services/MamaJudi'
import { SoundService } from '../../services/SoundService'
import BackgroundMusic from '../../components/BackgroundMusic'
import React, { useState, useEffect, useRef } from 'react'
import ExercisePlayer from './ExercisePlayer'
import LearningPacksPage, { type PackExercise } from './LearningPacksPage'
import SubjectsPage from './SubjectsPage'
import ProgressPage from './ProgressPage'
import ProfilePage from './ProfilePage'
import ExamSession from './ExamSession'
import DuelSession from './DuelSession'
import BulletinPage from './BulletinPage'
import RevisionPage from './RevisionPage'
import RemediationPage from './RemediationPage'
import { getExercisesForChild, getMoreExercisesForChild, saveAttempt } from '../../services/api'
import type { AttemptDetails } from '../../types/exercise'
import { useStreak } from '../../hooks/useStreak'
import { useOfflineSync } from '../../hooks/useOfflineSync'
import OfflineBanner from '../../components/OfflineBanner'
import ExamBanner from '../../components/ExamBanner'
import CompanionAvatar from '../../components/CompanionAvatar'
import AdventureDashboard from '../../components/AdventureDashboard'
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
type Tab = 'home' | 'subjects' | 'progress' | 'profile' | 'bulletin' | 'review' | 'remediation' | 'packs'

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
  return <CompanionAvatar size={size} />
}

export default function ChildHome({ child, onLogout }: Props) {
  const [tab, setTab] = useState<Tab>(() => {
    const saved = localStorage.getItem('edumaison_tab_' + child.id)
    const valid = ['home', 'subjects', 'progress', 'profile', 'bulletin', 'review', 'remediation', 'packs']
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
  const [eveningClosing, setEveningClosing] = useState(false)
  const [eveningError, setEveningError] = useState('')
  const [activeDuelId, setActiveDuelId] = useState<number | null>(null)
  const [activeDuelData, setActiveDuelData] = useState<any>(null)
  const streakData = useStreak(child)
  const packQueueRef = useRef<PackExercise[]>([])
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
    if (saved && ['home','subjects','progress','profile','bulletin','review','remediation','packs'].includes(saved)) {
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
      if (activeRef.current) { packQueueRef.current = []; setActive(null); pushNav(); return }
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
        if (!eveningRes.ok) return
        const evening = await eveningRes.json()
        const triggeredAt = evening?.triggered_at ? Date.parse(evening.triggered_at) : Number.NaN
        const isRecent = Number.isFinite(triggeredAt) && Date.now() - triggeredAt <= 24 * 60 * 60 * 1000
        if (evening?.id && isRecent) {
          setPendingEvening((prev: any) => prev?.id === evening.id ? prev : evening)
        } else {
          setPendingEvening(null)
          setEveningError('')
        }
      } catch (_) {}
    }
    poll() // immediat
    const interval = setInterval(poll, 5000)
    return () => clearInterval(interval)
  }, [child.id])

  useEffect(() => {
    MamaJudi.setChild(child.name, child.id)
    MamaJudi.scheduleGreeting(500)
    return () => MamaJudi.stop()
  }, [])

  const finishEveningSession = async (start: boolean) => {
    if (!pendingEvening || eveningClosing) return
    const session = pendingEvening
    setEveningClosing(true)
    setEveningError('')
    try {
      const response = await fetch(`/api/evening-sessions/${session.id}/done`, {
        method: 'POST',
        headers: { Accept: 'application/json' },
        keepalive: true,
      })
      const result = await response.json().catch(() => null)
      if (!response.ok || result?.success !== true) throw new Error('REVISION_NOT_CLOSED')
      setPendingEvening(null)
      if (start) {
        SoundService.levelup()
        void MamaJudi.speak(session.mama_judi_message || 'Bonsoir ! Mama Judi a prepare ta revision.')
      }
    } catch {
      setEveningError("La revision n'a pas pu etre fermee. Verifie la connexion et reessaie.")
    } finally {
      setEveningClosing(false)
    }
  }

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
    setExercises(prev => [...prev, ...shuffleArray(more as (Exercise & { subject: string })[])])
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

  const handleComplete = async (score: number, details?: AttemptDetails) => {
    if (!active) return
    await saveAttempt(child.id, active.id, score, details)
    setCompleted(prev => [...prev, active.id])
    const queue = packQueueRef.current
    if (queue.length > 0) {
      const currentIndex = queue.findIndex(exercise => exercise.id === active.id)
      const next = currentIndex >= 0 ? queue[currentIndex + 1] : null
      if (next) {
        setActive(next)
        return
      }
      packQueueRef.current = []
    }
    setActive(null)
  }

  const startLearningPack = (packExercises: PackExercise[]) => {
    if (!packExercises.length) return
    packQueueRef.current = packExercises
    setActive(packExercises[0])
  }

  if (activeDuelId && activeDuelData) return (
    <DuelSession
      child={child}
      duel={{ ...activeDuelData, id: activeDuelId }}
      onComplete={() => { setActiveDuelId(null); setActiveDuelData(null); activeDuelRef.current = null }}
    />
  )
  if (activeExam) return <ExamSession child={child} exam={activeExam} onBack={() => setActiveExam(null)} onComplete={() => setActiveExam(null)} />
  if (active) return <ExercisePlayer exercise={active} onComplete={handleComplete} onBack={() => { packQueueRef.current = []; setActive(null) }} />

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
    { id: 'subjects' as Tab, label: 'Learn', icon: '\u{1F4DA}' },
    { id: 'review' as Tab, label: 'Practice', icon: '\u{1F4D6}' },
    { id: 'packs' as Tab, label: 'Paths', icon: '\u{1F3AF}' },
    { id: 'profile' as Tab, label: 'Me', icon: '\u{1F464}' },
  ]

  return (
    <div className="adventure-child-shell" style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', paddingBottom: 80 }}>

      {/* Sub-pages — rendered here so bottom nav stays visible */}
      {tab === 'subjects' && <SubjectsPage child={child} onBack={() => { setTab('home'); setOpenSubjectName(null) }} initialSubjectName={openSubjectName} />}
      {tab === 'progress' && <ProgressPage child={child} onBack={() => setTab('profile')} />}
      {tab === 'profile'  && <ProfilePage child={child} onLogout={onLogout} onBack={() => setTab('home')} onOpenProgress={() => { pushNav(); setTab('progress') }} />}
      {tab === 'bulletin' && <BulletinPage  child={child} onBack={() => setTab('home')} />}
      {tab === 'review'   && <RevisionPage  child={child} onBack={() => setTab('home')} />}
      {tab === 'remediation' && <RemediationPage child={child} onBack={() => setTab('home')} />}
      {tab === 'packs' && <LearningPacksPage child={child} onBack={() => setTab('home')} onStart={startLearningPack} />}

      {tab === 'home' && (
        <>
          <OfflineBanner isOnline={isOnline} syncPending={syncPending} />
          <AdventureDashboard
            child={child}
            exercises={exercises}
            completed={completed}
            loading={loading}
            notice={(
              <>
                <ExamBanner child={child} onStartExam={setActiveExam} />
                {remediation?.status === 'needs_work' && remediation.plans.length > 0 && (
                  <button className="adventure-support-path" onClick={() => { pushNav(); setTab('remediation') }}>
                    <span><strong>Support path ready</strong><small>{remediation.plans.length} subject{remediation.plans.length > 1 ? 's' : ''} selected from the report card</small></span>
                    <b aria-hidden="true">→</b>
                  </button>
                )}
              </>
            )}
            onStartExercise={setActive}
            onOpenPaths={() => { pushNav(); setTab('packs') }}
          />
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
      <div className="adventure-bottom-nav" style={{ position: 'fixed', bottom: 0, left: '50%', transform: 'translateX(-50%)', width: '100%', maxWidth: Math.min(720, window.innerWidth), background: 'var(--card)', borderTop: '2px solid var(--border)', padding: '10px 0 14px', display: 'flex', zIndex: 100 }}>
        {NAV_ITEMS.map(item => {
          const isActive = tab === item.id || (tab === 'progress' && item.id === 'profile')
          return (
            <button className={isActive ? 'is-active' : ''} key={item.id} onClick={() => { pushNav(); setTab(item.id as Tab) }} style={{
              flex: 1, background: 'none', border: 'none', cursor: 'pointer',
              display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 2,
              color: isActive ? '#1D6B2A' : '#9A8A7A'
            }}>
              <span style={{ fontSize: 22 }}>{item.icon}</span>
              <span style={{ fontSize: 10, fontWeight: isActive ? 900 : 600 }}>{item.label}</span>
            </button>
          )
        })}
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
            {eveningError && <div role="alert" style={{ marginBottom: 12, color: '#B42318', fontSize: 13, fontWeight: 800 }}>{eveningError}</div>}
            <div style={{ display: 'flex', gap: 12 }}>
              <button onClick={() => void finishEveningSession(false)} disabled={eveningClosing}
                style={{ flex: 1, padding: '12px', borderRadius: 14, border: '2px solid #F0E4D8', background: '#FFF8F2', fontSize: 15, fontWeight: 800, color: '#8A6050', cursor: eveningClosing ? 'wait' : 'pointer', opacity: eveningClosing ? 0.65 : 1 }}>
                {eveningClosing ? 'Fermeture...' : 'Plus tard'}
              </button>
              <button onClick={() => void finishEveningSession(true)} disabled={eveningClosing}
                style={{ flex: 1, padding: '12px', borderRadius: 14, border: 'none', background: '#1D6B2A', fontSize: 15, fontWeight: 800, color: 'white', cursor: eveningClosing ? 'wait' : 'pointer', opacity: eveningClosing ? 0.65 : 1 }}>
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
