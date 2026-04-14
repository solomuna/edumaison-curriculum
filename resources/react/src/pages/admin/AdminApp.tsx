// AdminApp.tsx — Interface Admin EduMaison (remplace Filament)
import { useState, useEffect } from 'react'

const BASE = 'http://192.168.100.106:8100/api/admin'

const P = {
  bg: '#F7F3EE', card: '#FFFFFF', sidebar: '#1D6B2A',
  accent: '#C47A3C', red: '#CE1126', border: '#E8E0D8',
  dark: '#3D2B1F', soft: '#7A6050', light: '#F0E8D8',
}

type Screen = 'dashboard' | 'children' | 'exercises' | 'subjects' | 'lessons'

// ── API ───────────────────────────────────────────────────────────────────────
async function api(path: string, opts?: RequestInit) {
  const r = await fetch(BASE + path, { headers: { 'Content-Type': 'application/json' }, ...opts })
  return r.json()
}

// ── DASHBOARD ─────────────────────────────────────────────────────────────────
function Dashboard() {
  const [stats, setStats] = useState<any>(null)

  useEffect(() => { api('/stats').then(setStats) }, [])

  const cards = stats ? [
    { label: 'Enfants actifs', value: stats.children, icon: '👨‍👩‍👧‍👦', color: '#1D6B2A' },
    { label: 'Exercices', value: stats.exercises, icon: '📝', color: '#C47A3C' },
    { label: 'Leçons', value: stats.lessons, icon: '📚', color: '#3B82F6' },
    { label: 'Matières', value: stats.subjects, icon: '🎯', color: '#8B5CF6' },
    { label: "Tentatives aujourd'hui", value: stats.attempts_today, icon: '⚡', color: '#F59E0B' },
  ] : []

  return (
    <div>
      <h2 style={{ fontSize: 22, fontWeight: 900, color: P.dark, marginBottom: 24 }}>Tableau de bord</h2>
      {!stats ? (
        <div style={{ color: P.soft }}>Chargement...</div>
      ) : (
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(180px, 1fr))', gap: 16 }}>
          {cards.map((c, i) => (
            <div key={i} style={{ background: P.card, borderRadius: 18, padding: '20px 18px', border: `1.5px solid ${P.border}`, boxShadow: '0 2px 8px rgba(0,0,0,0.05)' }}>
              <div style={{ fontSize: 32, marginBottom: 10 }}>{c.icon}</div>
              <div style={{ fontSize: 32, fontWeight: 900, color: c.color }}>{c.value?.toLocaleString()}</div>
              <div style={{ fontSize: 12, color: P.soft, marginTop: 4, fontWeight: 700 }}>{c.label}</div>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}

// ── CHILDREN ─────────────────────────────────────────────────────────────────
function ChildrenScreen() {
  const [children, setChildren] = useState<any[]>([])
  const [levels, setLevels] = useState<any[]>([])
  const [editing, setEditing] = useState<any>(null)
  const [form, setForm] = useState({ first_name: '', last_name: '', pin: '', level_id: 5, is_active: true })
  const [search, setSearch] = useState('')

  useEffect(() => {
    api('/children').then(setChildren)
    api('/levels').then(setLevels)
  }, [])

  const save = async () => {
    if (editing?.id) {
      await api(`/children/${editing.id}`, { method: 'PUT', body: JSON.stringify(form) })
    } else {
      await api('/children', { method: 'POST', body: JSON.stringify(form) })
    }
    setEditing(null)
    api('/children').then(setChildren)
  }

  const filtered = children.filter(c =>
    `${c.first_name} ${c.last_name}`.toLowerCase().includes(search.toLowerCase())
  )

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 20 }}>
        <h2 style={{ fontSize: 22, fontWeight: 900, color: P.dark }}>Enfants ({children.length})</h2>
        <button onClick={() => { setEditing({}); setForm({ first_name: '', last_name: '', pin: '', level_id: 5, is_active: true }) }}
          style={{ background: P.sidebar, color: 'white', border: 'none', borderRadius: 12, padding: '10px 18px', fontWeight: 800, cursor: 'pointer', fontSize: 14 }}>
          + Ajouter
        </button>
      </div>

      <input value={search} onChange={e => setSearch(e.target.value)}
        placeholder="Rechercher un enfant..."
        style={{ width: '100%', padding: '10px 14px', borderRadius: 12, border: `1.5px solid ${P.border}`, fontSize: 14, marginBottom: 16, boxSizing: 'border-box' as const, fontFamily: 'Nunito, sans-serif' }} />

      {editing !== null && (
        <div style={{ background: P.light, borderRadius: 18, padding: 20, marginBottom: 20, border: `1.5px solid ${P.border}` }}>
          <div style={{ fontSize: 16, fontWeight: 900, color: P.dark, marginBottom: 14 }}>
            {editing.id ? 'Modifier' : 'Nouvel enfant'}
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
            <input placeholder="Prénom *" value={form.first_name} onChange={e => setForm(f => ({ ...f, first_name: e.target.value }))}
              style={inputStyle} />
            <input placeholder="Nom" value={form.last_name} onChange={e => setForm(f => ({ ...f, last_name: e.target.value }))}
              style={inputStyle} />
            <input placeholder="PIN (4 chiffres) *" value={form.pin} onChange={e => setForm(f => ({ ...f, pin: e.target.value }))}
              style={inputStyle} maxLength={4} />
            <select value={form.level_id} onChange={e => setForm(f => ({ ...f, level_id: Number(e.target.value) }))}
              style={inputStyle}>
              {levels.map(l => <option key={l.id} value={l.id}>{l.name}</option>)}
            </select>
          </div>
          <div style={{ display: 'flex', gap: 10 }}>
            <button onClick={save} style={{ background: P.sidebar, color: 'white', border: 'none', borderRadius: 10, padding: '10px 20px', fontWeight: 800, cursor: 'pointer' }}>
              Enregistrer
            </button>
            <button onClick={() => setEditing(null)} style={{ background: P.border, color: P.dark, border: 'none', borderRadius: 10, padding: '10px 20px', fontWeight: 700, cursor: 'pointer' }}>
              Annuler
            </button>
          </div>
        </div>
      )}

      <div style={{ display: 'grid', gap: 10 }}>
        {filtered.map(c => (
          <div key={c.id} style={{ background: P.card, borderRadius: 14, padding: '14px 18px', border: `1.5px solid ${P.border}`, display: 'flex', alignItems: 'center', gap: 14 }}>
            <div style={{ width: 42, height: 42, borderRadius: '50%', background: c.is_active ? P.sidebar + '22' : P.border, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 18, fontWeight: 900, color: P.sidebar }}>
              {c.first_name[0]}
            </div>
            <div style={{ flex: 1 }}>
              <div style={{ fontWeight: 900, color: P.dark, fontSize: 15 }}>{c.first_name} {c.last_name}</div>
              <div style={{ fontSize: 12, color: P.soft, marginTop: 2 }}>{c.level_name} • PIN: {c.pin}</div>
            </div>
            {!c.is_active && <span style={{ background: '#FEE2E2', color: P.red, borderRadius: 8, padding: '2px 8px', fontSize: 11, fontWeight: 800 }}>Inactif</span>}
            <button onClick={() => { setEditing(c); setForm({ first_name: c.first_name, last_name: c.last_name || '', pin: c.pin, level_id: c.level_id, is_active: c.is_active }) }}
              style={{ background: P.accent + '22', color: P.accent, border: 'none', borderRadius: 8, padding: '6px 12px', fontWeight: 800, cursor: 'pointer', fontSize: 12 }}>
              Modifier
            </button>
          </div>
        ))}
      </div>
    </div>
  )
}

// ── EXERCISES ─────────────────────────────────────────────────────────────────
function ExercisesScreen() {
  const [exercises, setExercises] = useState<any[]>([])
  const [total, setTotal] = useState(0)
  const [levels, setLevels] = useState<any[]>([])
  const [levelFilter, setLevelFilter] = useState('')
  const [search, setSearch] = useState('')
  const [page, setPage] = useState(0)
  const limit = 30

  useEffect(() => { api('/levels').then(setLevels) }, [])

  useEffect(() => {
    const params = new URLSearchParams({ limit: String(limit), offset: String(page * limit) })
    if (levelFilter) params.set('level_id', levelFilter)
    api(`/exercises?${params}`).then(d => { setExercises(d.data || []); setTotal(d.total || 0) })
  }, [levelFilter, page])

  const filtered = exercises.filter(e => e.title.toLowerCase().includes(search.toLowerCase()))

  const typeColor = (t: string) => {
    const m: Record<string, string> = { mcq: '#3B82F6', multiple_choice: '#3B82F6', true_false: '#10B981', fill_in: '#F59E0B', match_pairs: '#8B5CF6', oral_drill: '#EC4899', handwriting: '#6B7280' }
    return m[t] || '#9CA3AF'
  }

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 20 }}>
        <h2 style={{ fontSize: 22, fontWeight: 900, color: P.dark }}>Exercices ({total.toLocaleString()})</h2>
      </div>

      <div style={{ display: 'flex', gap: 10, marginBottom: 16 }}>
        <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Rechercher..."
          style={{ ...inputStyle, flex: 1 }} />
        <select value={levelFilter} onChange={e => { setLevelFilter(e.target.value); setPage(0) }} style={inputStyle}>
          <option value="">Tous les niveaux</option>
          {levels.map(l => <option key={l.id} value={l.id}>{l.name}</option>)}
        </select>
      </div>

      <div style={{ background: P.card, borderRadius: 14, border: `1.5px solid ${P.border}`, overflow: 'hidden' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead>
            <tr style={{ background: P.light }}>
              {['ID', 'Titre', 'Type', 'Matière', 'Niveau', 'Statut'].map(h => (
                <th key={h} style={{ padding: '12px 14px', textAlign: 'left', fontSize: 11, fontWeight: 900, color: P.soft, textTransform: 'uppercase', letterSpacing: 1 }}>{h}</th>
              ))}
            </tr>
          </thead>
          <tbody>
            {filtered.map((e, i) => (
              <tr key={e.id} style={{ borderTop: `1px solid ${P.border}`, background: i % 2 === 0 ? P.card : '#FAFAF8' }}>
                <td style={{ padding: '10px 14px', fontSize: 12, color: P.soft }}>{e.id}</td>
                <td style={{ padding: '10px 14px', fontSize: 13, fontWeight: 700, color: P.dark, maxWidth: 200, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{e.title}</td>
                <td style={{ padding: '10px 14px' }}>
                  <span style={{ background: typeColor(e.type) + '22', color: typeColor(e.type), borderRadius: 6, padding: '2px 8px', fontSize: 11, fontWeight: 800 }}>{e.type || '—'}</span>
                </td>
                <td style={{ padding: '10px 14px', fontSize: 12, color: P.soft }}>{e.subject_name}</td>
                <td style={{ padding: '10px 14px', fontSize: 12, color: P.soft }}>{e.level_name}</td>
                <td style={{ padding: '10px 14px' }}>
                  <span style={{ background: e.is_active ? '#D1FAE5' : '#FEE2E2', color: e.is_active ? '#065F46' : P.red, borderRadius: 6, padding: '2px 8px', fontSize: 11, fontWeight: 800 }}>
                    {e.is_active ? 'Actif' : 'Inactif'}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Pagination */}
      <div style={{ display: 'flex', justifyContent: 'center', gap: 8, marginTop: 16 }}>
        <button disabled={page === 0} onClick={() => setPage(p => p - 1)}
          style={{ padding: '8px 16px', borderRadius: 8, border: `1.5px solid ${P.border}`, background: page === 0 ? P.border : P.card, cursor: page === 0 ? 'default' : 'pointer', fontWeight: 700 }}>
          ← Préc.
        </button>
        <span style={{ padding: '8px 16px', fontSize: 13, color: P.soft }}>
          {page + 1} / {Math.ceil(total / limit)}
        </span>
        <button disabled={(page + 1) * limit >= total} onClick={() => setPage(p => p + 1)}
          style={{ padding: '8px 16px', borderRadius: 8, border: `1.5px solid ${P.border}`, background: (page + 1) * limit >= total ? P.border : P.card, cursor: (page + 1) * limit >= total ? 'default' : 'pointer', fontWeight: 700 }}>
          Suiv. →
        </button>
      </div>
    </div>
  )
}

const inputStyle: React.CSSProperties = {
  padding: '10px 14px', borderRadius: 10, border: `1.5px solid #E8E0D8`,
  fontSize: 14, fontFamily: 'Nunito, sans-serif', outline: 'none',
  background: 'white', color: '#3D2B1F'
}

// ── SUBJECTS ─────────────────────────────────────────────────────────────────
function SubjectsScreen() {
  const [subjects, setSubjects] = useState<any[]>([])

  useEffect(() => { api('/subjects').then(setSubjects) }, [])

  return (
    <div>
      <h2 style={{ fontSize: 22, fontWeight: 900, color: P.dark, marginBottom: 20 }}>Matières ({subjects.length})</h2>
      <div style={{ display: 'grid', gap: 10 }}>
        {subjects.map(s => (
          <div key={s.id} style={{ background: P.card, borderRadius: 14, padding: '14px 18px', border: `1.5px solid ${P.border}`, display: 'flex', alignItems: 'center', gap: 14 }}>
            <div style={{ flex: 1 }}>
              <div style={{ fontWeight: 900, color: P.dark }}>{s.name}</div>
              <div style={{ fontSize: 12, color: P.soft, marginTop: 2 }}>{s.level_name}</div>
            </div>
            <span style={{ background: P.sidebar + '22', color: P.sidebar, borderRadius: 8, padding: '4px 10px', fontSize: 12, fontWeight: 800 }}>
              {s.exercise_count} exercices
            </span>
          </div>
        ))}
      </div>
    </div>
  )
}

// ── APP ───────────────────────────────────────────────────────────────────────
export default function AdminApp() {
  const [screen, setScreen] = useState<Screen>('dashboard')

  const NAV: { id: Screen; icon: string; label: string }[] = [
    { id: 'dashboard', icon: '📊', label: 'Dashboard' },
    { id: 'children', icon: '👨‍👩‍👧‍👦', label: 'Enfants' },
    { id: 'exercises', icon: '📝', label: 'Exercices' },
    { id: 'subjects', icon: '🎯', label: 'Matières' },
  ]

  return (
    <div style={{ display: 'flex', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', background: P.bg }}>
      {/* Sidebar */}
      <div style={{ width: 220, background: P.sidebar, display: 'flex', flexDirection: 'column', position: 'fixed', height: '100vh', left: 0, top: 0 }}>
        <div style={{ padding: '24px 20px 20px', borderBottom: '1px solid rgba(255,255,255,.15)' }}>
          <div style={{ fontSize: 11, fontWeight: 900, color: 'rgba(255,255,255,.5)', letterSpacing: 2 }}>EDUMAISON</div>
          <div style={{ fontSize: 18, fontWeight: 900, color: 'white', marginTop: 4 }}>Admin</div>
        </div>
        <nav style={{ padding: '12px 10px', flex: 1 }}>
          {NAV.map(item => (
            <button key={item.id} onClick={() => setScreen(item.id)}
              style={{ width: '100%', display: 'flex', alignItems: 'center', gap: 10, padding: '11px 14px', borderRadius: 12, border: 'none', cursor: 'pointer', marginBottom: 4, background: screen === item.id ? 'rgba(255,255,255,.2)' : 'transparent', color: screen === item.id ? 'white' : 'rgba(255,255,255,.6)', fontWeight: screen === item.id ? 900 : 600, fontSize: 14, fontFamily: 'Nunito, sans-serif', textAlign: 'left' }}>
              <span style={{ fontSize: 18 }}>{item.icon}</span>{item.label}
            </button>
          ))}
        </nav>
        <div style={{ padding: '12px', borderTop: '1px solid rgba(255,255,255,.15)' }}>
          <a href="/app" style={{ display: 'block', textAlign: 'center', color: 'rgba(255,255,255,.5)', fontSize: 12, fontWeight: 700, textDecoration: 'none' }}>
            ← App enfant
          </a>
        </div>
      </div>

      {/* Main */}
      <div style={{ flex: 1, marginLeft: 220, padding: '32px 40px', maxWidth: 'calc(100vw - 220px)' }}>
        {screen === 'dashboard' && <Dashboard />}
        {screen === 'children' && <ChildrenScreen />}
        {screen === 'exercises' && <ExercisesScreen />}
        {screen === 'subjects' && <SubjectsScreen />}
      </div>
    </div>
  )
}
