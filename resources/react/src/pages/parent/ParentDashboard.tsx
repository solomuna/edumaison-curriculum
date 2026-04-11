import { useState, useEffect } from 'react'
import { getParentDashboard, getChildDetail } from '../../services/api'
import ExamResultsDashboard from './ExamResultsDashboard'
import ExamCreator from '../../components/ExamCreator'

const LEVEL_COLORS: Record<string, string> = {
  'Class 1': '#4CAF50', 'Class 2': '#2196F3', 'Class 3': '#9C27B0',
  'Class 4': '#CE1126', 'Class 5': '#00BCD4', 'Class 6': '#C47A3C',
  'Nursery 1': '#FF8FAB', 'Nursery 2': '#FF8FAB', 'Pre-Nursery': '#FF8FAB',
}

interface ChildSummary { id: number; name: string; level: string; attempts: number; completed: number; avg_score: number; pct: number }
interface Dashboard { school_year: string; children: ChildSummary[]; total_completed: number; total_attempts: number }
type Tab = 'children' | 'exams' | 'create'

export default function ParentDashboard() {
  const [data, setData] = useState<Dashboard | null>(null)
  const [loading, setLoading] = useState(true)
  const [selected, setSelected] = useState<number | null>(null)
  const [detail, setDetail] = useState<any>(null)
  const [tab, setTab] = useState<Tab>('children')
  const [promoting, setPromoting] = useState<number | null>(null)
  const [promoted, setPromoted] = useState<number[]>([])

  useEffect(() => { getParentDashboard().then(setData).finally(() => setLoading(false)) }, [])

  const openDetail = async (childId: number) => {
    setSelected(childId)
    setDetail(await getChildDetail(childId))
  }

  const promoteChild = async (childId: number) => {
    setPromoting(childId)
    try {
      const r = await fetch(`/api/children/${childId}/promote`, { method: 'POST' })
      if (r.ok) { setPromoted(p => [...p, childId]); setData(await fetch('/api/parent/dashboard').then(x => x.json())) }
    } finally { setPromoting(null) }
  }

  const promoteAll = async () => {
    setPromoting(-1)
    try {
      await fetch('/api/children/promote-all', { method: 'POST' })
      const fresh = await fetch('/api/parent/dashboard').then(x => x.json())
      setData(fresh); setPromoted(data?.children.map(c => c.id) || [])
    } finally { setPromoting(null) }
  }

  const NEXT_LEVEL: Record<string,string> = {
    'Pre-Nursery':'Nursery 1','Nursery 1':'Nursery 2','Nursery 2':'Class 1',
    'Class 1':'Class 2','Class 2':'Class 3','Class 3':'Class 4','Class 4':'Class 5','Class 5':'Class 6'
  }

  if (selected && detail) {
    const color = LEVEL_COLORS[detail.level] || '#1D6B2A'
    return (
      <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', padding: '0 0 40px' }}>
        <div style={{ background: '#1D6B2A', padding: '12px 18px', display: 'flex', alignItems: 'center', gap: 10 }}>
          <button onClick={() => { setSelected(null); setDetail(null) }} style={{ background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: 10, padding: '6px 14px', color: 'white', fontWeight: 800, fontSize: 13, cursor: 'pointer' }}>← Back</button>
          <div style={{ fontSize: 16, fontWeight: 900, color: 'white' }}>{detail.name}</div>
        </div>
        <div style={{ padding: '16px 18px' }}>
          <div style={{ background: 'var(--card)', borderRadius: 18, padding: '20px', marginBottom: 14, textAlign: 'center', border: '2px solid ' + color }}>
            <div style={{ fontSize: 48, marginBottom: 8 }}>👧</div>
            <div style={{ fontSize: 22, fontWeight: 900, color: 'var(--text-dark)' }}>{detail.name}</div>
            <span style={{ background: color, color: 'white', borderRadius: 20, padding: '3px 14px', fontSize: 12, fontWeight: 800, marginTop: 6, display: 'inline-block' }}>{detail.level}</span>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
            <div style={{ background: 'var(--card)', borderRadius: 16, padding: '16px', textAlign: 'center', border: '1.5px solid var(--border)' }}>
              <div style={{ fontSize: 28, fontWeight: 900, color: '#1D6B2A' }}>{detail.completed || 0}</div>
              <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 4, fontWeight: 700 }}>Completed</div>
            </div>
            <div style={{ background: 'var(--card)', borderRadius: 16, padding: '16px', textAlign: 'center', border: '1.5px solid var(--border)' }}>
              <div style={{ fontSize: 28, fontWeight: 900, color: '#C47A3C' }}>{detail.pct || 0}%</div>
              <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 4, fontWeight: 700 }}>Success rate</div>
            </div>
          </div>
        </div>
      </div>
    )
  }

  const TABS: [Tab, string, string][] = [
    ['children', 'Children', '👨‍👧‍👦'],
    ['exams', 'Exam Results', '📝'],
    ['create', 'Schedule Exam', '📅'],
  ]

  return (
    <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', paddingBottom: 40 }}>

      {/* Header */}
      <div style={{ background: '#1D6B2A', padding: '14px 18px 0' }}>
        <div style={{ fontSize: 11, fontWeight: 900, color: 'rgba(255,255,255,0.6)', letterSpacing: '2px' }}>EDUMAISON</div>
        <div style={{ fontSize: 20, fontWeight: 900, color: 'white', marginTop: 2 }}>Parent Dashboard</div>
        {data && <div style={{ fontSize: 12, color: 'rgba(255,255,255,0.65)', marginTop: 2, paddingBottom: 12 }}>{data.school_year}</div>}
        {/* Tabs */}
        <div style={{ display: 'flex', gap: 6, paddingBottom: 0 }}>
          {TABS.map(([id, label, icon]) => (
            <button key={id} onClick={() => setTab(id)} style={{
              padding: '10px 16px', borderRadius: '12px 12px 0 0', border: 'none', fontSize: 12, fontWeight: 800, cursor: 'pointer',
              background: tab === id ? '#E8DCC8' : 'rgba(255,255,255,0.15)',
              color: tab === id ? '#1D6B2A' : 'rgba(255,255,255,0.8)',
              fontFamily: 'Nunito, system-ui, sans-serif'
            }}>
              {icon} {label}
            </button>
          ))}
        </div>
      </div>

      <div style={{ padding: '16px 18px' }}>
        {tab === 'children' && (
          <>
            {data && (
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 20 }}>
                <div style={{ background: 'var(--card)', borderRadius: 16, padding: '14px', textAlign: 'center', border: '1.5px solid var(--border)' }}>
                  <div style={{ fontSize: 26, fontWeight: 900, color: '#1D6B2A' }}>{data.total_completed}</div>
                  <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 4, fontWeight: 700 }}>Activities done</div>
                </div>
                <div style={{ background: 'var(--card)', borderRadius: 16, padding: '14px', textAlign: 'center', border: '1.5px solid var(--border)' }}>
                  <div style={{ fontSize: 26, fontWeight: 900, color: '#C47A3C' }}>{data.children.length}</div>
                  <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 4, fontWeight: 700 }}>Children</div>
                </div>
              </div>
            )}
            {!loading && data && data.children.some(c => NEXT_LEVEL[c.level]) && (
              <div style={{ background: '#DBEAFE', borderRadius: 18, padding: '16px', marginBottom: 20, border: '2px solid #93C5FD' }}>
                <div style={{ fontSize: 14, fontWeight: 900, color: '#1D4ED8', marginBottom: 4 }}>🎓 New School Year</div>
                <div style={{ fontSize: 12, color: '#3B82F6', marginBottom: 14, lineHeight: 1.4 }}>Promote children to the next class.</div>
                {data.children.map(child => {
                  const next = NEXT_LEVEL[child.level]
                  if (!next) return null
                  const isDone = promoted.includes(child.id)
                  return (
                    <div key={child.id} style={{ background: 'var(--white)', borderRadius: 12, padding: '12px 14px', marginBottom: 8, display: 'flex', alignItems: 'center', gap: 10, opacity: isDone ? 0.55 : 1 }}>
                      <div style={{ flex: 1 }}>
                        <div style={{ fontSize: 14, fontWeight: 800, color: '#1E3A5F' }}>{child.name.split(' ')[0]}</div>
                        <div style={{ fontSize: 12, color: '#6B7280', marginTop: 2 }}>{child.level} → {next}</div>
                      </div>
                      <button onClick={() => !isDone && promoteChild(child.id)} disabled={isDone || promoting === child.id}
                        style={{ padding: '7px 14px', borderRadius: 10, border: 'none', background: isDone ? '#D1FAE5' : '#1D4ED8', color: isDone ? '#059669' : 'white', fontWeight: 800, fontSize: 12, cursor: isDone ? 'default' : 'pointer', fontFamily: 'Nunito, system-ui, sans-serif' }}>
                        {isDone ? '✓ Done' : promoting === child.id ? '...' : 'Promote'}
                      </button>
                    </div>
                  )
                })}
                {data.children.filter(c => NEXT_LEVEL[c.level]).length > 1 && (
                  <button onClick={promoteAll} disabled={promoting === -1} style={{ width: '100%', marginTop: 4, padding: '10px 0', borderRadius: 12, border: 'none', background: '#1D4ED8', color: 'white', fontWeight: 900, fontSize: 13, cursor: 'pointer', fontFamily: 'Nunito, system-ui, sans-serif' }}>
                    {promoting === -1 ? 'Promoting...' : 'Promote All →'}
                  </button>
                )}
              </div>
            )}
            {loading && [1,2,3].map(i => <div key={i} style={{ borderRadius: 18, height: 100, marginBottom: 12, background: 'var(--card)', opacity: 0.5 }}/>)}
            {!loading && data?.children.map((child, i) => {
              const color = LEVEL_COLORS[child.level] || '#1D6B2A'
              const avatar = ['👦','👧','🧒'][i % 3]
              return (
                <div key={child.id} onClick={() => openDetail(child.id)}
                  style={{ background: 'var(--card)', borderRadius: 18, padding: '16px', marginBottom: 12, cursor: 'pointer', border: `2px solid ${color}44`, borderLeft: `5px solid ${color}` }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 10 }}>
                    <div style={{ width: 44, height: 44, borderRadius: 14, background: color + '22', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 22 }}>{avatar}</div>
                    <div style={{ flex: 1 }}>
                      <div style={{ fontSize: 15, fontWeight: 800, color: 'var(--text-dark)' }}>{child.name}</div>
                      <div style={{ fontSize: 11, color: color, fontWeight: 700, marginTop: 2 }}>{child.level}</div>
                    </div>
                    <div style={{ textAlign: 'right' }}>
                      <div style={{ fontSize: 22, fontWeight: 900, color }}>{child.pct}%</div>
                      <div style={{ fontSize: 10, color: 'var(--text-soft)' }}>success</div>
                    </div>
                  </div>
                  <div style={{ height: 6, background: 'var(--border)', borderRadius: 3 }}>
                    <div style={{ height: 6, borderRadius: 3, background: color, width: `${child.pct}%`, transition: 'width 0.4s' }}/>
                  </div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: 8, fontSize: 11, color: 'var(--text-soft)' }}>
                    <span>{child.completed} done / {child.attempts} attempts</span>
                    <span>Avg: {child.avg_score}%</span>
                  </div>
                </div>
              )
            })}
          </>
        )}
        {tab === 'exams' && <ExamResultsDashboard />}
        {tab === 'create' && <ExamCreator householdId={1} onCreated={() => setTab('exams')} />}
      </div>
    </div>
  )
}
