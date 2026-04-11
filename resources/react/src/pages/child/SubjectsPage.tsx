import { useState, useEffect, useRef } from 'react'
import Confetti from '../../components/Confetti'
import { SoundService } from '../../services/SoundService'
import { getSubjects } from '../../services/api'
import ExercisePlayer from './ExercisePlayer'
import { saveAttempt } from '../../services/api'
import type { Exercise } from '../../types/exercise'
import type { Child } from '../../types/child'

interface Subject { id: number; name: string; icon: string | null }
interface Unit { id: number; name: string; total: number; done: number; pct: number; summary?: string | null }
interface Props { child: Child; onBack: () => void; initialSubjectName?: string | null; isDesktop?: boolean }


function renderMarkdown(text: string): string {
  // Decode unicode escape sequences (e.g. \u2260 -> actual char)
  const decoded = text.replace(/\\u([0-9A-Fa-f]{4})/g, (_, hex) =>
    String.fromCharCode(parseInt(hex, 16))
  )
  return decoded
    .replace(/^### (.+)$/gm, '<h4 style="margin:10px 0 4px;color:var(--text-dark);font-size:14px;font-weight:800">$1</h4>')
    .replace(/^## (.+)$/gm, '<h3 style="margin:12px 0 4px;color:#1D6B2A;font-size:15px;font-weight:900">$1</h3>')
    .replace(/^# (.+)$/gm, '<h2 style="margin:14px 0 6px;color:#1D6B2A;font-size:16px;font-weight:900">$1</h2>')
    .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
    .replace(/\*(.+?)\*/g, '<em>$1</em>')
    .replace(/`(.+?)`/g, '<code style="background:var(--border);padding:1px 5px;border-radius:4px;font-size:12px">$1</code>')
    .replace(/^- (.+)$/gm, '<li style="margin:3px 0;padding-left:4px">$1</li>')
    .replace(/(<li.*?<\/li>\n?)+/g, (m) => `<ul style="padding-left:16px;margin:6px 0;list-style:disc">${m}</ul>`)
    .replace(/\|(.+)\|/g, (row) => {
      const cells = row.split('|').filter(c => c.trim())
      return '<tr>' + cells.map(c => `<td style="padding:3px 8px;border:1px solid var(--border)">${c.trim()}</td>`).join('') + '</tr>'
    })
    .replace(/(<tr>.*<\/tr>\n?)+/g, (m) => `<table style="border-collapse:collapse;width:100%;margin:8px 0;font-size:12px">${m}</table>`)
    .replace(/\n/g, '<br/>')
}

// i18n — adapts labels based on active subject
const isFrench = (name: string | undefined) => name === 'French'

function t(key: string, subjectName?: string): string {
  const fr = isFrench(subjectName)
  const L: Record<string, [string, string]> = {
    back:        ['Back',                    'Retour'],
    playAll:     ['▶ Play All',              '▶ Tout jouer'],
    units:       ['units',                   'unités'],
    exercises:   ['exercises',               'exercices'],
    viewSummary: ['📖 View lesson summary', '📖 Voir le cours'],
    hideSummary: ['📖 Hide lesson summary', '📖 Masquer le cours'],
    practise:    ['Practise',                'Pratiquer'],
    start:       ['Start',                   'Démarrer'],
    watch:       ['Watch',                   'À revoir'],
    good:        ['Good',                    'Bien'],
  }
  const e = L[key]
  return e ? (fr ? e[1] : e[0]) : key
}

const SUBJECT_DATA: Record<string, { bg: string; accent: string; icon: string }> = {
  English:                        { bg: '#DBEAFE', accent: '#3B82F6', icon: '\u{1F4D6}' },
  Mathematics:                    { bg: '#FEF3C7', accent: '#F59E0B', icon: '\u{1F4D0}' },
  French:                         { bg: '#EDE9FE', accent: '#8B5CF6', icon: '\u{1F4AC}' },
  'Science and Technology':       { bg: '#D1FAE5', accent: '#10B981', icon: '\u{1F52C}' },
  Reading:                        { bg: '#FCE7F3', accent: '#EC4899', icon: '\u{1F4DA}' },
  Handwriting:                    { bg: '#CFFAFE', accent: '#0EA5E9', icon: '\u{270D}' },
  ICT:                            { bg: '#ECFDF5', accent: '#22C55E', icon: '\u{1F4BB}' },
  Citizenship:                    { bg: '#FEF9C3', accent: '#EAB308', icon: '\u{1F3DB}' },
  'Social Studies':               { bg: '#FEE2E2', accent: '#EF4444', icon: '\u{1F30D}' },
  'National Languages and Cultures': { bg: '#FCE7F3', accent: '#EC4899', icon: '\u{1F3AD}' },
  'Arts and Crafts':              { bg: '#FFF1F2', accent: '#F43F5E', icon: '\u{1F3A8}' },
  'Physical Education':           { bg: '#ECFDF5', accent: '#22C55E', icon: '\u{26BD}' },
  'Artistic Activities':          { bg: '#FFF1F2', accent: '#F43F5E', icon: '\u{1F3A8}' },
  'Home Economics and Vocational Skills': { bg: '#FEF3C7', accent: '#F59E0B', icon: '\u{1F3E0}' },
}
const DEF = { bg: '#F3F4F6', accent: '#6B7280', icon: '\u{1F4CB}' }
const gc = (name: string) => SUBJECT_DATA[name] || DEF

export default function SubjectsPage({ child, onBack, initialSubjectName, isDesktop }: Props) {
  const [subjects, setSubjects] = useState<Subject[]>([])
  const [loading, setLoading] = useState(true)
  const [selSubject, setSelSubject] = useState<Subject | null>(null)
  const [units, setUnits] = useState<Unit[]>([])
  const [loadingUnits, setLoadingUnits] = useState(false)
  const [selUnit, setSelUnit] = useState<Unit | null>(null)
  const [exercises, setExercises] = useState<any[]>([])
  const [loadingEx, setLoadingEx] = useState(false)
  const [active, setActive] = useState<any | null>(null)
  const [completed, setCompleted] = useState<number[]>([])
  const [showResult, setShowResult] = useState(false)
  const [showSummary, setShowSummary] = useState(false)
  const playAllRef    = useRef(false)
  const exercisesRef  = useRef<any[]>([])
  const completedRef  = useRef<number[]>([])

  useEffect(() => {
    if (!child.level_id) return
    getSubjects(child.level_id!).then(data => {
      setSubjects(data)
      if (initialSubjectName) {
        const match = data.find((s: Subject) => s.name === initialSubjectName)
        if (match) openSubject(match)
      }
    }).finally(() => setLoading(false))
  }, [])

  const openSubject = (s: Subject) => {
    setSelSubject(s)
    setSelUnit(null)
    setUnits([])
    setLoadingUnits(true)
    fetch('/api/subjects/' + s.id + '/units/' + child.id)
      .then(r => r.json()).then(setUnits).finally(() => setLoadingUnits(false))
  }

  const openUnit = (u: Unit) => {
    setShowSummary(false)
    setSelUnit(u)
    setExercises([])
    setCompleted([])
    exercisesRef.current = []
    completedRef.current = []
    playAllRef.current = false
    setLoadingEx(true)
    fetch('/api/units/' + u.id + '/exercises/' + child.id)
      .then(r => r.json()).then(data => {
      const parsed = data.map((e: any) => ({
        ...e,
        content: typeof e.content === 'string' ? JSON.parse(e.content) : e.content
      }))
      setExercises(parsed)
      exercisesRef.current = parsed
    }).finally(() => setLoadingEx(false))
  }

  const handleComplete = async (score: number) => {
    if (!active) return
    await saveAttempt(child.id, active.id, score)
    const newCompleted = [...completedRef.current, active.id]
    completedRef.current = newCompleted
    setCompleted(newCompleted)
    if (newCompleted.length === exercisesRef.current.length) {
      playAllRef.current = false
      setActive(null)
      setShowResult(true)
      SoundService.levelup()
    } else if (playAllRef.current) {
      const next = exercisesRef.current.find(e => !newCompleted.includes(e.id))
      if (next) setActive(next)
      else { playAllRef.current = false; setActive(null); setShowResult(true) }
    } else {
      setActive(null)
    }
  }

  if (active) return <ExercisePlayer key={active.id} exercise={{ ...active, subject: selSubject?.name || '' }} onComplete={handleComplete} onBack={() => setActive(null)} />

  // -- Result screen --
  if (showResult && selUnit && selSubject) {
    const col = gc(selSubject.name)
    const total = exercises.length
    const score = completed.length
    const pct = Math.round(score / total * 100)
    const perfect = score === total
    const stars = score * 10
    return (
      <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: '32px 24px', textAlign: 'center' }}>
        <Confetti active={perfect} />
        <div style={{ fontSize: 64, marginBottom: 8 }}>{perfect ? '\u{1F3C6}' : '\u{1F4AA}'}</div>
        <div style={{ fontSize: 28, fontWeight: 900, color: 'var(--text-dark)', marginBottom: 6 }}>
          {perfect ? 'Perfect score!' : 'Well done!'}
        </div>
        <div style={{ fontSize: 15, color: 'var(--text-soft)', marginBottom: 4 }}>{selUnit.name}</div>
        <div style={{ fontSize: 13, color: 'var(--text-soft)', marginBottom: 24 }}>{selSubject.name}</div>
        <div style={{ display: 'flex', gap: 12, marginBottom: 32 }}>
          <div style={{ background: '#FEF3C7', borderRadius: 18, padding: '16px 22px', textAlign: 'center', minWidth: 80 }}>
            <div style={{ fontSize: 26, fontWeight: 900, color: '#F59E0B' }}>{stars}</div>
            <div style={{ fontSize: 11, color: '#D97706', fontWeight: 700 }}>Stars</div>
          </div>
          <div style={{ background: '#D1FAE5', borderRadius: 18, padding: '16px 22px', textAlign: 'center', minWidth: 80 }}>
            <div style={{ fontSize: 26, fontWeight: 900, color: '#10B981' }}>{pct}%</div>
            <div style={{ fontSize: 11, color: '#059669', fontWeight: 700 }}>Score</div>
          </div>
          <div style={{ background: '#DBEAFE', borderRadius: 18, padding: '16px 22px', textAlign: 'center', minWidth: 80 }}>
            <div style={{ fontSize: 26, fontWeight: 900, color: '#3B82F6' }}>{score}/{total}</div>
            <div style={{ fontSize: 11, color: '#2563EB', fontWeight: 700 }}>Correct</div>
          </div>
        </div>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 10, width: '100%', maxWidth: 300 }}>
          <button onClick={() => { setShowResult(false); setSelUnit(null) }} style={{ padding: '14px 0', borderRadius: 16, border: 'none', background: '#1D6B2A', color: 'white', fontSize: 15, fontWeight: 800, cursor: 'pointer' }}>
            Back to units
          </button>
          <button onClick={() => { setShowResult(false); setCompleted([]); setActive(null) }} style={{ padding: '14px 0', borderRadius: 16, border: '2px solid #1D6B2A', background: 'transparent', color: '#1D6B2A', fontSize: 15, fontWeight: 800, cursor: 'pointer' }}>
            Retry unit
          </button>
        </div>
      </div>
    )
  }

  // ── Exercise list ─────────────────────────────────────────────────────────
  if (selUnit && selSubject) {
    const col = gc(selSubject.name)
    const done = exercises.filter(e => completed.includes(e.id)).length
    return (
      <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', paddingBottom: isDesktop ? 16 : 40 }}>
        <div style={{ background: '#1D6B2A', padding: '12px 16px', display: 'flex', alignItems: 'center', gap: 10 }}>
          <button onClick={() => setSelUnit(null)} style={{ background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: 10, padding: '6px 12px', color: 'white', fontWeight: 800, cursor: 'pointer', fontSize: 13 }}>
            {t('back', selSubject?.name)}
          </button>
          <div style={{ flex: 1 }}>
            <div style={{ fontSize: 15, fontWeight: 900, color: 'white' }}>{selUnit.name}</div>
            <div style={{ fontSize: 11, color: 'rgba(255,255,255,0.7)' }}>{selSubject.name} · {done}/{exercises.length}</div>
          </div>
          {!loadingEx && exercises.some(e => !completed.includes(e.id)) && (
            <button onClick={() => {
              const first = exercisesRef.current.find(e => !completedRef.current.includes(e.id))
              if (first) { playAllRef.current = true; setActive(first) }
            }} style={{ background: '#F59E0B', border: 'none', borderRadius: 10, padding: '7px 14px', color: 'white', fontWeight: 900, cursor: 'pointer', fontSize: 13, display: 'flex', alignItems: 'center', gap: 6, flexShrink: 0 }}>
              {t('playAll', selSubject?.name)}
            </button>
          )}
        </div>
        <div style={{ padding: '14px 16px' }}>
          {selUnit.summary && (
            <div style={{ marginBottom: 14 }}>
              <button onClick={() => setShowSummary(s => !s)} style={{ width: '100%', padding: '12px 16px', borderRadius: 14, border: '1.5px solid #1D6B2A', background: showSummary ? '#1D6B2A' : 'var(--card)', color: showSummary ? 'white' : '#1D6B2A', fontWeight: 800, fontSize: 14, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: 8, fontFamily: 'Nunito, system-ui, sans-serif' }}>
                <span style={{ fontSize: 18 }}>📖</span>
                {showSummary ? t('hideSummary', selSubject?.name) : t('viewSummary', selSubject?.name)}
                <span style={{ marginLeft: 'auto' }}>{showSummary ? '▲' : '▼'}</span>
              </button>
              {showSummary && (
                <div style={{ background: 'var(--card)', borderRadius: '0 0 14px 14px', padding: '16px', border: '1.5px solid #1D6B2A', borderTop: 'none', fontSize: 13, lineHeight: 1.7, color: 'var(--text-dark)' }}
                  dangerouslySetInnerHTML={{ __html: renderMarkdown(selUnit.summary) }}
                />
              )}
            </div>
          )}
          {loadingEx && [1,2,3].map(i => <div key={i} style={{ height: 70, borderRadius: 14, background: 'var(--card)', marginBottom: 10, opacity: 0.5 }}/>)}
          {!loadingEx && exercises.map(ex => {
            const isDone = completed.includes(ex.id)
            return (
              <div key={ex.id} onClick={() => !isDone && setActive(ex)} style={{
                background: isDone ? '#F5F0E8' : '#F0E8D8', borderRadius: 16,
                padding: '14px 16px', marginBottom: 10, cursor: isDone ? 'default' : 'pointer',
                border: isDone ? '1.5px solid #D0C8B8' : '1.5px solid ' + col.accent + '44',
                display: 'flex', alignItems: 'center', gap: 12, opacity: isDone ? 0.7 : 1
              }}>
                <div style={{ width: 42, height: 42, borderRadius: 12, background: isDone ? '#D0C8B8' : col.bg, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 20, flexShrink: 0 }}>
                  {col.icon}
                </div>
                <div style={{ flex: 1 }}>
                  <div style={{ fontSize: 14, fontWeight: 800, color: 'var(--text-dark)' }}>{ex.title}</div>
                  <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 2 }}>{ex.category}</div>
                </div>
                {isDone
                  ? <div style={{ width: 32, height: 32, borderRadius: '50%', background: '#4CAF50', display: 'flex', alignItems: 'center', justifyContent: 'center', color: 'white', fontSize: 16 }}>✓</div>
                  : <div style={{ width: 32, height: 32, borderRadius: '50%', background: col.accent, display: 'flex', alignItems: 'center', justifyContent: 'center', color: 'white', fontSize: 14 }}>▶</div>
                }
              </div>
            )
          })}
        </div>
      </div>
    )
  }

  // ── Unit list ─────────────────────────────────────────────────────────────
  if (selSubject) {
    const col = gc(selSubject.name)
    return (
      <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', paddingBottom: isDesktop ? 16 : 40 }}>
        <div style={{ background: '#1D6B2A', padding: '12px 16px', display: 'flex', alignItems: 'center', gap: 10 }}>
          <button onClick={() => setSelSubject(null)} style={{ background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: 10, padding: '6px 12px', color: 'white', fontWeight: 800, cursor: 'pointer', fontSize: 13 }}>
            {t('back', selSubject.name)}
          </button>
          <div style={{ flex: 1 }}>
            <div style={{ fontSize: 16, fontWeight: 900, color: 'white' }}>{col.icon} {selSubject.name}</div>
            <div style={{ fontSize: 11, color: 'rgba(255,255,255,0.7)' }}>{units.length} {t('units', selSubject.name)}</div>
          </div>
        </div>
        <div style={{ padding: '14px 16px' }}>
          {loadingUnits && [1,2,3].map(i => <div key={i} style={{ height: 80, borderRadius: 16, background: 'var(--card)', marginBottom: 10, opacity: 0.5 }}/>)}
          {!loadingUnits && units.map((u, i) => (
            <div key={u.id} onClick={() => openUnit(u)} style={{
              background: 'var(--card)', borderRadius: 18, padding: '16px',
              marginBottom: 10, cursor: 'pointer', border: '1.5px solid var(--border)'
            }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 8 }}>
                <div style={{ width: 40, height: 40, borderRadius: 12, background: col.accent + '22', border: '2px solid ' + col.accent, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 15, fontWeight: 900, color: col.accent, flexShrink: 0 }}>
                  {i + 1}
                </div>
                <div style={{ flex: 1 }}>
                  <div style={{ fontSize: 15, fontWeight: 900, color: 'var(--text-dark)' }}>{u.name}</div>
                  <div style={{ fontSize: 12, color: 'var(--text-soft)', marginTop: 1 }}>{u.done}/{u.total} {t('exercises', selSubject.name)} · {u.pct}%</div>
                </div>
                <span style={{
                  background: u.pct >= 80 ? '#4CAF50' : u.pct >= 40 ? '#F59E0B' : '#CE1126',
                  color: 'white', borderRadius: 20, padding: '3px 10px', fontSize: 11, fontWeight: 800
                }}>
                  {u.pct >= 80 ? t('good', selSubject.name) : u.pct >= 40 ? t('watch', selSubject.name) : t('start', selSubject.name)}
                </span>
              </div>
              <div style={{ height: 6, background: 'var(--border)', borderRadius: 3 }}>
                <div style={{ height: 6, borderRadius: 3, background: col.accent, width: u.pct + '%', transition: 'width 0.4s' }}/>
              </div>
            </div>
          ))}
        </div>
      </div>
    )
  }

  // ── Subject grid ──────────────────────────────────────────────────────────
  return (
    <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', paddingBottom: isDesktop ? 16 : 40 }}>
      {!isDesktop && (
        <div style={{ background: '#1D6B2A', padding: '12px 16px', display: 'flex', alignItems: 'center', gap: 10 }}>
          <button onClick={onBack} style={{ background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: 10, padding: '6px 12px', color: 'white', fontWeight: 800, cursor: 'pointer', fontSize: 13 }}>Back</button>
          <div style={{ fontSize: 16, fontWeight: 900, color: 'white' }}>My Subjects</div>
        </div>
      )}
      {isDesktop && <div style={{ padding: '20px 16px 4px', fontSize: 20, fontWeight: 900, color: 'var(--text-dark)' }}>My Subjects</div>}
      <div style={{ padding: '14px 16px', display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
        {loading && [1,2,3,4,5,6].map(i => <div key={i} style={{ height: 110, borderRadius: 18, background: 'var(--card)', opacity: 0.5 }}/>)}
        {!loading && subjects.map(s => {
          const col = gc(s.name)
          return (
            <div key={s.id} onClick={() => openSubject(s)} style={{
              background: 'var(--card)', borderRadius: 18, padding: '18px 14px',
              cursor: 'pointer', border: '1.5px solid var(--border)'
            }}>
              <div style={{ fontSize: 30, marginBottom: 8 }}>{col.icon}</div>
              <div style={{ fontSize: 13, fontWeight: 900, color: 'var(--text-dark)', lineHeight: 1.25, marginBottom: 6 }}>{s.name}</div>
              <div style={{ background: col.accent + '22', color: col.accent, fontSize: 10, fontWeight: 700, padding: '3px 10px', borderRadius: 20, display: 'inline-block' }}>
                {t('practise', s.name)}
              </div>
            </div>
          )
        })}
      </div>
    </div>
  )
}
