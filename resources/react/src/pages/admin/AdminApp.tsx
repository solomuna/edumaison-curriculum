// AdminApp.tsx — Interface Admin EduMaison Phase 2
// Curriculum screen: pills cascade Niveau > Matiere > Unite > Lecons + search + CRUD
import { useState, useEffect } from 'react'

const BASE = 'http://192.168.100.106:8100/api/admin'

const P = {
  bg: '#F7F3EE', card: '#FFFFFF', sidebar: '#1D6B2A',
  accent: '#C47A3C', red: '#CE1126', border: '#E8E0D8',
  dark: '#3D2B1F', soft: '#7A6050', light: '#F0E8D0',
}

const inputStyle: React.CSSProperties = {
  padding: '10px 14px', borderRadius: 10, border: `1.5px solid #E8E0D8`,
  fontSize: 14, fontFamily: 'Nunito, sans-serif', outline: 'none',
  background: 'white', color: '#3D2B1F',
}
const taStyle: React.CSSProperties = {
  ...inputStyle, width: '100%', minHeight: 160, resize: 'vertical' as const,
  fontFamily: 'monospace', fontSize: 12, boxSizing: 'border-box' as const,
}
const btnStyle = (color: string, text = 'white'): React.CSSProperties => ({
  background: color, color: text, border: 'none', borderRadius: 10,
  padding: '9px 18px', fontWeight: 800, cursor: 'pointer', fontSize: 13,
  fontFamily: 'Nunito, sans-serif',
})

type Screen = 'dashboard' | 'children' | 'subjects' | 'curriculum' | 'exercises' | 'assets' | 'bulletin' | 'health'

async function api(path: string, opts?: RequestInit) {
  const r = await fetch(BASE + path, { headers: { 'Content-Type': 'application/json' }, ...opts })
  return r.json()
}

// ── CONFIRM DELETE ────────────────────────────────────────────────────────────
function ConfirmDelete({ label, onConfirm, onCancel }: { label: string; onConfirm: () => void; onCancel: () => void }) {
  return (
    <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,.4)', display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 1000 }}>
      <div style={{ background: P.card, borderRadius: 18, padding: 28, maxWidth: 360, textAlign: 'center', boxShadow: '0 8px 32px rgba(0,0,0,.15)' }}>
        <div style={{ fontSize: 36, marginBottom: 12 }}>🗑️</div>
        <div style={{ fontWeight: 900, color: P.dark, fontSize: 16, marginBottom: 8 }}>Supprimer ?</div>
        <div style={{ color: P.soft, fontSize: 13, marginBottom: 20 }}>{label}</div>
        <div style={{ display: 'flex', gap: 10, justifyContent: 'center' }}>
          <button onClick={onConfirm} style={btnStyle(P.red)}>Supprimer</button>
          <button onClick={onCancel} style={btnStyle(P.border, P.dark)}>Annuler</button>
        </div>
      </div>
    </div>
  )
}

// ── UNIT MODAL ────────────────────────────────────────────────────────────────
function UnitModal({ unit, themes, onSave, onClose }: { unit: any; themes: any[]; onSave: (d: any) => void; onClose: () => void }) {
  const [form, setForm] = useState({ name: unit.name || '', integrated_theme_id: unit.integrated_theme_id || (themes[0]?.id || 0) })
  const [error, setError] = useState('')
  const save = () => { if (!form.name.trim()) return setError('Nom requis'); onSave(form) }
  return (
    <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,.45)', display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 1000 }}>
      <div style={{ background: P.card, borderRadius: 20, padding: 28, width: 440, boxShadow: '0 12px 48px rgba(0,0,0,.2)' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 20 }}>
          <div style={{ fontWeight: 900, color: P.dark, fontSize: 16 }}>{unit.id ? "Modifier l'unite" : 'Nouvelle unite'}</div>
          <button onClick={onClose} style={{ background: 'none', border: 'none', fontSize: 22, cursor: 'pointer', color: P.soft }}>✕</button>
        </div>
        {error && <div style={{ background: '#FEE2E2', color: P.red, borderRadius: 10, padding: '8px 12px', marginBottom: 12, fontSize: 13, fontWeight: 700 }}>{error}</div>}
        <div style={{ display: 'grid', gap: 12, marginBottom: 16 }}>
          <input placeholder="Nom de l'unite *" value={form.name} onChange={e => setForm(f => ({ ...f, name: e.target.value }))}
            style={{ ...inputStyle, width: '100%', boxSizing: 'border-box' as const }} />
          <select value={form.integrated_theme_id} onChange={e => setForm(f => ({ ...f, integrated_theme_id: Number(e.target.value) }))} style={inputStyle}>
            {themes.map(t => <option key={t.id} value={t.id}>{t.name}</option>)}
          </select>
        </div>
        <div style={{ display: 'flex', gap: 10 }}>
          <button onClick={save} style={btnStyle(P.sidebar)}>Enregistrer</button>
          <button onClick={onClose} style={btnStyle(P.border, P.dark)}>Annuler</button>
        </div>
      </div>
    </div>
  )
}

// ── LESSON MODAL ──────────────────────────────────────────────────────────────
function LessonModal({ lesson, units, onSave, onClose }: { lesson: any; units: any[]; onSave: (d: any) => void; onClose: () => void }) {
  const [form, setForm] = useState({ name: lesson.name || '', unit_id: lesson.unit_id || (units[0]?.id || 0) })
  const [error, setError] = useState('')
  const save = () => { if (!form.name.trim()) return setError('Nom requis'); onSave(form) }
  return (
    <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,.45)', display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 1000 }}>
      <div style={{ background: P.card, borderRadius: 20, padding: 28, width: 440, boxShadow: '0 12px 48px rgba(0,0,0,.2)' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 20 }}>
          <div style={{ fontWeight: 900, color: P.dark, fontSize: 16 }}>{lesson.id ? 'Modifier la lecon' : 'Nouvelle lecon'}</div>
          <button onClick={onClose} style={{ background: 'none', border: 'none', fontSize: 22, cursor: 'pointer', color: P.soft }}>✕</button>
        </div>
        {error && <div style={{ background: '#FEE2E2', color: P.red, borderRadius: 10, padding: '8px 12px', marginBottom: 12, fontSize: 13, fontWeight: 700 }}>{error}</div>}
        <div style={{ display: 'grid', gap: 12, marginBottom: 16 }}>
          <input placeholder="Nom de la lecon *" value={form.name} onChange={e => setForm(f => ({ ...f, name: e.target.value }))}
            style={{ ...inputStyle, width: '100%', boxSizing: 'border-box' as const }} />
          <select value={form.unit_id} onChange={e => setForm(f => ({ ...f, unit_id: Number(e.target.value) }))} style={inputStyle}>
            {units.map(u => <option key={u.id} value={u.id}>{u.name}</option>)}
          </select>
        </div>
        <div style={{ display: 'flex', gap: 10 }}>
          <button onClick={save} style={btnStyle(P.sidebar)}>Enregistrer</button>
          <button onClick={onClose} style={btnStyle(P.border, P.dark)}>Annuler</button>
        </div>
      </div>
    </div>
  )
}

// ── CURRICULUM ────────────────────────────────────────────────────────────────
function CurriculumScreen({ goTo }: { goTo: (s: Screen, p?: any) => void }) {
  const [search, setSearch] = useState('')
  const [resetTarget, setResetTarget] = useState<any>(null)
  const [resetDone, setResetDone] = useState<string | null>(null)
  const [levels, setLevels] = useState<any[]>([])
  const [subjects, setSubjects] = useState<any[]>([])
  const [units, setUnits] = useState<any[]>([])
  const [lessons, setLessons] = useState<any[]>([])
  const [themes, setThemes] = useState<any[]>([])
  const [selLevel, setSelLevel] = useState<number|null>(null)
  const [selSubject, setSelSubject] = useState<number|null>(null)
  const [selUnit, setSelUnit] = useState<number|null>(null)
  const [unitModal, setUnitModal] = useState<any>(null)
  const [lessonModal, setLessonModal] = useState<any>(null)
  const [deleteTarget, setDeleteTarget] = useState<{type:'unit'|'lesson';item:any}|null>(null)
  const [error, setError] = useState('')

  useEffect(() => { api('/levels').then(setLevels) }, [])

  useEffect(() => {
    if (selLevel) {
      api('/subjects').then(data => {
        setSubjects(data.filter((s: any) => s.level_id === selLevel))
        setSelSubject(null); setUnits([]); setSelUnit(null); setLessons([])
      })
    }
  }, [selLevel])

  useEffect(() => {
    if (selSubject) {
      api(`/units?subject_id=${selSubject}`).then(setUnits)
      api(`/integrated-themes?subject_id=${selSubject}`).then(setThemes)
      setSelUnit(null); setLessons([])
    }
  }, [selSubject])

  useEffect(() => {
    if (selUnit) api(`/lessons?unit_id=${selUnit}`).then(setLessons)
  }, [selUnit])

  const q = search.toLowerCase()
  const fUnits = units.filter(u => !q || u.name.toLowerCase().includes(q))
  const fLessons = lessons.filter(l => !q || l.name.toLowerCase().includes(q))

  const saveUnit = async (data: any) => {
    if (unitModal?.id) await api(`/units/${unitModal.id}`, { method: 'PUT', body: JSON.stringify(data) })
    else await api('/units', { method: 'POST', body: JSON.stringify(data) })
    setUnitModal(null)
    if (selSubject) api(`/units?subject_id=${selSubject}`).then(setUnits)
  }

  const saveLesson = async (data: any) => {
    if (lessonModal?.id) await api(`/lessons/${lessonModal.id}`, { method: 'PUT', body: JSON.stringify(data) })
    else await api('/lessons', { method: 'POST', body: JSON.stringify(data) })
    setLessonModal(null)
    if (selUnit) api(`/lessons?unit_id=${selUnit}`).then(setLessons)
  }

  const confirmDelete = async () => {
    if (!deleteTarget) return
    setError('')
    const { type, item } = deleteTarget
    const res = await api(`/${type === 'unit' ? 'units' : 'lessons'}/${item.id}`, { method: 'DELETE' })
    if (res.detail) { setError(res.detail); setDeleteTarget(null); return }
    setDeleteTarget(null)
    if (type === 'unit') { if (selSubject) api(`/units?subject_id=${selSubject}`).then(setUnits); setSelUnit(null); setLessons([]) }
    else { if (selUnit) api(`/lessons?unit_id=${selUnit}`).then(setLessons) }
  }

  const pill = (label: string, active: boolean, onClick: () => void, activeColor = P.sidebar) => (
    <button onClick={onClick} style={{ padding: '7px 16px', borderRadius: 999, border: `1.5px solid ${active ? activeColor : P.border}`, background: active ? activeColor : P.card, color: active ? 'white' : P.dark, fontWeight: active ? 800 : 600, fontSize: 13, cursor: 'pointer', fontFamily: 'Nunito,sans-serif', transition: 'all .15s' }}>
      {label}
    </button>
  )

  const secLabel = (txt: string) => <div style={{ fontSize: 10, fontWeight: 900, color: P.soft, letterSpacing: 1.5, textTransform: 'uppercase' as const, marginBottom: 8 }}>{txt}</div>

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 20 }}>
        <h2 style={{ fontSize: 22, fontWeight: 900, color: P.dark }}>
          Curriculum
          {selLevel && <span style={{ color: P.soft, fontWeight: 600, fontSize: 15 }}> &rsaquo; {levels.find(l => l.id === selLevel)?.name}</span>}
          {selSubject && <span style={{ color: P.soft, fontWeight: 600, fontSize: 15 }}> &rsaquo; {subjects.find(s => s.id === selSubject)?.name}</span>}
          {selUnit && <span style={{ color: P.soft, fontWeight: 600, fontSize: 15 }}> &rsaquo; {units.find(u => u.id === selUnit)?.name}</span>}
        </h2>
        <div style={{ display: 'flex', gap: 8 }}>
          {selSubject && <button onClick={() => setUnitModal({})} style={btnStyle(P.sidebar)}>+ Unite</button>}
          {selUnit && <button onClick={() => setLessonModal({ unit_id: selUnit })} style={btnStyle(P.accent)}>+ Lecon</button>}
        </div>
      </div>

      {/* Search */}
      <div style={{ position: 'relative', marginBottom: 20 }}>
        <span style={{ position: 'absolute', left: 14, top: '50%', transform: 'translateY(-50%)', color: P.soft }}>🔍</span>
        <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Rechercher unites, lecons..."
          style={{ ...inputStyle, width: '100%', boxSizing: 'border-box' as const, paddingLeft: 40 }} />
        {search && <button onClick={() => setSearch('')} style={{ position: 'absolute', right: 12, top: '50%', transform: 'translateY(-50%)', background: 'none', border: 'none', cursor: 'pointer', color: P.soft, fontSize: 18 }}>✕</button>}
      </div>

      {error && <div style={{ background: '#FEE2E2', color: P.red, borderRadius: 10, padding: '10px 14px', marginBottom: 14, fontWeight: 700, fontSize: 13 }}>{error}</div>}

      {/* Niveau */}
      <div style={{ marginBottom: 16 }}>
        {secLabel('Niveau')}
        <div style={{ display: 'flex', flexWrap: 'wrap' as const, gap: 8 }}>
          {levels.map(l => <span key={l.id}>{pill(l.name, selLevel === l.id, () => { setSelLevel(l.id); setSearch('') })}</span>)}
        </div>
      </div>

      {/* Matiere */}
      {selLevel && subjects.length > 0 && (
        <div style={{ marginBottom: 16 }}>
          {secLabel('Matiere')}
          <div style={{ display: 'flex', flexWrap: 'wrap' as const, gap: 8 }}>
            {subjects.map(s => <span key={s.id}>{pill(s.name, selSubject === s.id, () => { setSelSubject(s.id); setSearch('') }, '#8B5CF6')}</span>)}
          </div>
        </div>
      )}

      {/* Unite */}
      {selSubject && fUnits.length > 0 && (
        <div style={{ marginBottom: 16 }}>
          {secLabel('Unite')}
          <div style={{ display: 'flex', flexWrap: 'wrap' as const, gap: 8 }}>
            {fUnits.map(u => (
              <div key={u.id} style={{ display: 'flex', alignItems: 'center', gap: 2 }}>
                <button onClick={() => setSelUnit(u.id)} style={{ padding: '7px 14px', borderRadius: 999, border: `1.5px solid ${selUnit === u.id ? '#3B82F6' : P.border}`, background: selUnit === u.id ? '#3B82F6' : P.card, color: selUnit === u.id ? 'white' : P.dark, fontWeight: selUnit === u.id ? 800 : 600, fontSize: 13, cursor: 'pointer', fontFamily: 'Nunito,sans-serif' }}>
                  {u.name} <span style={{ fontSize: 11, opacity: .65, marginLeft: 4 }}>({u.lesson_count})</span>
                </button>
                <button onClick={() => setUnitModal(u)} title="Modifier" style={{ background: 'none', border: 'none', cursor: 'pointer', fontSize: 14, color: P.accent, padding: '3px 5px' }}>✏️</button>
                <button onClick={() => setDeleteTarget({ type: 'unit', item: u })} title="Supprimer" style={{ background: 'none', border: 'none', cursor: 'pointer', fontSize: 14, color: P.red, padding: '3px 5px' }}>✕</button>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Lecons */}
      {selUnit && (
        <div>
          {secLabel(`Lecons (${fLessons.length})`)}
          {fLessons.length === 0
            ? <div style={{ color: P.soft, fontSize: 13, paddingTop: 12 }}>Aucune lecon.</div>
            : <div style={{ display: 'grid', gap: 8 }}>
              {fLessons.map(l => (
                <div key={l.id} style={{ background: P.card, borderRadius: 14, padding: '13px 18px', border: `1.5px solid ${P.border}`, display: 'flex', alignItems: 'center', gap: 12 }}>
                  <div style={{ width: 32, height: 32, borderRadius: 8, background: '#8B5CF622', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 12, fontWeight: 900, color: '#8B5CF6' }}>{l.id}</div>
                  <div style={{ flex: 1 }}>
                    <div style={{ fontWeight: 800, color: P.dark, fontSize: 14 }}>{l.name}</div>
                    <div style={{ fontSize: 11, color: P.soft, marginTop: 2 }}>{l.exercise_count} exercice(s)</div>
                  </div>
                  <button onClick={() => goTo('exercises', { lesson_id: l.id, lesson_name: l.name })} style={{ background: P.accent+'22', color: P.accent, border: 'none', borderRadius: 8, padding: '5px 10px', fontWeight: 800, cursor: 'pointer', fontSize: 12 }}>Exercices →</button>
                  <button onClick={() => setLessonModal(l)} style={{ background: P.sidebar+'22', color: P.sidebar, border: 'none', borderRadius: 8, padding: '5px 10px', fontWeight: 800, cursor: 'pointer', fontSize: 12 }}>Modifier</button>
                  <button onClick={() => setDeleteTarget({ type: 'lesson', item: l })} style={{ background: '#FEE2E222', color: P.red, border: 'none', borderRadius: 8, padding: '5px 10px', fontWeight: 800, cursor: 'pointer', fontSize: 12 }}>✕</button>
                </div>
              ))}
            </div>
          }
        </div>
      )}

      {!selLevel && <div style={{ textAlign: 'center', padding: '60px 0', color: P.soft, fontSize: 14 }}>Selectionnez un niveau pour commencer</div>}
      {selLevel && subjects.length === 0 && <div style={{ color: P.soft, fontSize: 13 }}>Aucune matiere pour ce niveau.</div>}
      {selSubject && fUnits.length === 0 && !search && <div style={{ color: P.soft, fontSize: 13 }}>Aucune unite pour cette matiere.</div>}

      {unitModal !== null && <UnitModal unit={unitModal} themes={themes} onSave={saveUnit} onClose={() => setUnitModal(null)} />}
      {lessonModal !== null && <LessonModal lesson={lessonModal} units={units} onSave={saveLesson} onClose={() => setLessonModal(null)} />}
      {deleteTarget && <ConfirmDelete label={`"${deleteTarget.item.name}"`} onConfirm={confirmDelete} onCancel={() => setDeleteTarget(null)} />}
    </div>
  )
}

// ── DASHBOARD ─────────────────────────────────────────────────────────────────
function Dashboard({ goTo }: { goTo: (s: Screen) => void }) {
  const [stats, setStats] = useState<any>(null)
  useEffect(() => { api('/stats').then(setStats) }, [])
  const cards = stats ? [
    { label: 'Enfants actifs', value: stats.children, icon: '👨‍👩‍👧‍👦', color: '#1D6B2A', screen: 'children' as Screen },
    { label: 'Exercices', value: stats.exercises, icon: '📝', color: '#C47A3C', screen: 'exercises' as Screen },
    { label: 'Lecons', value: stats.lessons, icon: '📖', color: '#3B82F6', screen: 'curriculum' as Screen },
    { label: 'Matieres', value: stats.subjects, icon: '🎯', color: '#8B5CF6', screen: 'subjects' as Screen },
    { label: "Tentatives aujourd'hui", value: stats.attempts_today, icon: '⚡', color: '#F59E0B', screen: null },
  ] : []
  return (
    <div>
      <h2 style={{ fontSize: 22, fontWeight: 900, color: P.dark, marginBottom: 24 }}>Tableau de bord</h2>
      {!stats ? <div style={{ color: P.soft }}>Chargement...</div> : (
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(180px,1fr))', gap: 16 }}>
          {cards.map((c, i) => (
            <div key={i} onClick={() => c.screen && goTo(c.screen)}
              style={{ background: P.card, borderRadius: 18, padding: '20px 18px', border: `1.5px solid ${P.border}`, boxShadow: '0 2px 8px rgba(0,0,0,.05)', cursor: c.screen ? 'pointer' : 'default' }}>
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
  const [resetTarget, setResetTarget] = useState<any>(null)
  const [resetDone, setResetDone] = useState<string | null>(null)
  useEffect(() => { api('/children').then(setChildren); api('/levels').then(setLevels) }, [])
  const save = async () => {
    if (editing?.id) await api(`/children/${editing.id}`, { method: 'PUT', body: JSON.stringify(form) })
    else await api('/children', { method: 'POST', body: JSON.stringify(form) })
    setEditing(null); api('/children').then(setChildren)
  }

  const resetChild = async () => {
    await api(`/children/${resetTarget.id}/reset`, { method: 'POST' })
    setResetTarget(null)
    setResetDone(resetTarget.first_name)
    setTimeout(() => setResetDone(null), 4000)
  }
  const filtered = children.filter(c => `${c.first_name} ${c.last_name}`.toLowerCase().includes(search.toLowerCase()))
  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 20 }}>
        <h2 style={{ fontSize: 22, fontWeight: 900, color: P.dark }}>Enfants ({children.length})</h2>
        <button onClick={() => { setEditing({}); setForm({ first_name: '', last_name: '', pin: '', level_id: 5, is_active: true }) }} style={btnStyle(P.sidebar)}>+ Ajouter</button>
      </div>
      <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Rechercher..."
        style={{ ...inputStyle, width: '100%', boxSizing: 'border-box' as const, marginBottom: 16 }} />
      {editing !== null && (
        <div style={{ background: P.light, borderRadius: 18, padding: 20, marginBottom: 20, border: `1.5px solid ${P.border}` }}>
          <div style={{ fontSize: 16, fontWeight: 900, color: P.dark, marginBottom: 14 }}>{editing.id ? 'Modifier' : 'Nouvel enfant'}</div>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 12 }}>
            <input placeholder="Prenom *" value={form.first_name} onChange={e => setForm(f => ({ ...f, first_name: e.target.value }))} style={inputStyle} />
            <input placeholder="Nom" value={form.last_name} onChange={e => setForm(f => ({ ...f, last_name: e.target.value }))} style={inputStyle} />
            <input placeholder="PIN (4 chiffres) *" value={form.pin} onChange={e => setForm(f => ({ ...f, pin: e.target.value }))} style={inputStyle} maxLength={4} />
            <select value={form.level_id} onChange={e => setForm(f => ({ ...f, level_id: Number(e.target.value) }))} style={inputStyle}>
              {levels.map(l => <option key={l.id} value={l.id}>{l.name}</option>)}
            </select>
          </div>
          <div style={{ display: 'flex', gap: 10 }}>
            <button onClick={save} style={btnStyle(P.sidebar)}>Enregistrer</button>
            <button onClick={() => setEditing(null)} style={btnStyle(P.border, P.dark)}>Annuler</button>
          </div>
        </div>
      )}
      <div style={{ display: 'grid', gap: 10 }}>
        {filtered.map(c => (
          <div key={c.id} style={{ background: P.card, borderRadius: 14, padding: '14px 18px', border: `1.5px solid ${P.border}`, display: 'flex', alignItems: 'center', gap: 14 }}>
            <div style={{ width: 42, height: 42, borderRadius: '50%', background: P.sidebar+'22', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 18, fontWeight: 900, color: P.sidebar }}>{c.first_name[0]}</div>
            <div style={{ flex: 1 }}>
              <div style={{ fontWeight: 900, color: P.dark, fontSize: 15 }}>{c.first_name} {c.last_name}</div>
              <div style={{ fontSize: 12, color: P.soft, marginTop: 2 }}>{c.level_name} • PIN: {c.pin}</div>
            </div>
            {!c.is_active && <span style={{ background: '#FEE2E2', color: P.red, borderRadius: 8, padding: '2px 8px', fontSize: 11, fontWeight: 800 }}>Inactif</span>}
            <button onClick={() => { setEditing(c); setForm({ first_name: c.first_name, last_name: c.last_name||'', pin: c.pin, level_id: c.level_id, is_active: c.is_active }) }}
              style={{ background: P.accent+'22', color: P.accent, border: 'none', borderRadius: 8, padding: '6px 12px', fontWeight: 800, cursor: 'pointer', fontSize: 12 }}>Modifier</button>
            <button onClick={async () => {
                await api(`/children/${c.id}`, { method: 'PUT', body: JSON.stringify({ first_name: c.first_name, last_name: c.last_name||'', pin: c.pin, level_id: c.level_id, is_active: !c.is_active }) })
                api('/children').then(setChildren)
              }}
              style={{ background: c.is_active ? '#FEE2E222' : '#D1FAE5', color: c.is_active ? P.red : '#065F46', border: 'none', borderRadius: 8, padding: '6px 12px', fontWeight: 800, cursor: 'pointer', fontSize: 12 }}>
              {c.is_active ? 'Masquer' : 'Activer'}
            </button>
            <button onClick={() => setResetTarget(c)}
              style={{ background: '#FEE2E222', color: P.red, border: 'none', borderRadius: 8, padding: '6px 12px', fontWeight: 800, cursor: 'pointer', fontSize: 12 }}>Reset</button>
          </div>
        ))}
      </div>

      {resetDone && (
        <div style={{ position: 'fixed', bottom: 24, right: 24, background: '#D1FAE5', color: '#065F46', borderRadius: 14, padding: '14px 20px', fontWeight: 800, fontSize: 14, boxShadow: '0 4px 16px rgba(0,0,0,.12)', zIndex: 999 }}>
          ✅ {resetDone} remis(e) a zero avec succes
        </div>
      )}

      {resetTarget && (
        <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,.4)', display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 1000 }}>
          <div style={{ background: P.card, borderRadius: 18, padding: 28, maxWidth: 400, textAlign: 'center', boxShadow: '0 8px 32px rgba(0,0,0,.15)' }}>
            <div style={{ fontSize: 40, marginBottom: 12 }}>⚠️</div>
            <div style={{ fontWeight: 900, color: P.dark, fontSize: 17, marginBottom: 8 }}>Reset {resetTarget.first_name} ?</div>
            <div style={{ color: P.soft, fontSize: 13, marginBottom: 6 }}>
              Supprime <strong>toutes les tentatives, examens et duels</strong>.
            </div>
            <div style={{ background: '#D1FAE5', color: '#065F46', borderRadius: 10, padding: '8px 14px', fontSize: 12, fontWeight: 700, marginBottom: 20 }}>
              ✅ Le bulletin (school_results) est conserve
            </div>
            <div style={{ display: 'flex', gap: 10, justifyContent: 'center' }}>
              <button onClick={resetChild} style={{ background: P.red, color: 'white', border: 'none', borderRadius: 10, padding: '10px 20px', fontWeight: 800, cursor: 'pointer', fontSize: 14 }}>
                Confirmer le reset
              </button>
              <button onClick={() => setResetTarget(null)} style={{ background: P.border, color: P.dark, border: 'none', borderRadius: 10, padding: '10px 20px', fontWeight: 700, cursor: 'pointer' }}>
                Annuler
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}

// ── SUBJECTS ──────────────────────────────────────────────────────────────────
function SubjectsScreen() {
  const [subjects, setSubjects] = useState<any[]>([])
  useEffect(() => { api('/subjects').then(setSubjects) }, [])
  return (
    <div>
      <h2 style={{ fontSize: 22, fontWeight: 900, color: P.dark, marginBottom: 20 }}>Matieres ({subjects.length})</h2>
      <div style={{ display: 'grid', gap: 10 }}>
        {subjects.map(s => (
          <div key={s.id} style={{ background: P.card, borderRadius: 14, padding: '14px 18px', border: `1.5px solid ${P.border}`, display: 'flex', alignItems: 'center', gap: 14 }}>
            <div style={{ flex: 1 }}>
              <div style={{ fontWeight: 900, color: P.dark }}>{s.name}</div>
              <div style={{ fontSize: 12, color: P.soft, marginTop: 2 }}>{s.level_name}</div>
            </div>
            <span style={{ background: P.sidebar+'22', color: P.sidebar, borderRadius: 8, padding: '4px 10px', fontSize: 12, fontWeight: 800 }}>{s.exercise_count} ex.</span>
          </div>
        ))}
      </div>
    </div>
  )
}

// ── EXERCISE EDIT MODAL ───────────────────────────────────────────────────────
function ExerciseEditModal({ exerciseId, onClose, onSaved }: { exerciseId: number; onClose: () => void; onSaved: () => void }) {
  const [ex, setEx] = useState<any>(null)
  const [form, setForm] = useState({ title: '', category: 'reading', difficulty: 'easy', is_active: true, lesson_id: 0, content: '' })
  const [error, setError] = useState('')
  const [saving, setSaving] = useState(false)
  useEffect(() => {
    api(`/exercises/${exerciseId}`).then(data => {
      setEx(data)
      setForm({ title: data.title||'', category: data.category||'reading', difficulty: data.difficulty||'easy', is_active: data.is_active??true, lesson_id: data.lesson_id||0, content: typeof data.content==='object' ? JSON.stringify(data.content,null,2) : (data.content||'{}') })
    })
  }, [exerciseId])
  const save = async () => {
    setError(''); let content: any
    try { content = JSON.parse(form.content) } catch { return setError('JSON invalide') }
    setSaving(true)
    await api(`/exercises/${exerciseId}`, { method: 'PUT', body: JSON.stringify({ ...form, content }) })
    setSaving(false); onSaved()
  }
  return (
    <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,.5)', display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 1000, padding: 20 }}>
      <div style={{ background: P.card, borderRadius: 20, width: '100%', maxWidth: 680, maxHeight: '90vh', overflow: 'auto', padding: 28, boxShadow: '0 12px 48px rgba(0,0,0,.2)' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 20 }}>
          <div style={{ fontWeight: 900, color: P.dark, fontSize: 17 }}>Modifier exercice #{exerciseId}</div>
          <button onClick={onClose} style={{ background: 'none', border: 'none', fontSize: 22, cursor: 'pointer', color: P.soft }}>✕</button>
        </div>
        {!ex ? <div style={{ color: P.soft, textAlign: 'center', padding: 40 }}>Chargement...</div> : (
          <>
            <div style={{ marginBottom: 10, fontSize: 12, color: P.soft }}>{ex.level_name} — {ex.subject_name} — {ex.lesson_name}</div>
            {error && <div style={{ background: '#FEE2E2', color: P.red, borderRadius: 10, padding: '10px 14px', marginBottom: 12, fontWeight: 700, fontSize: 13 }}>{error}</div>}
            <div style={{ display: 'grid', gap: 12, marginBottom: 16 }}>
              <input placeholder="Titre *" value={form.title} onChange={e => setForm(f => ({ ...f, title: e.target.value }))} style={{ ...inputStyle, width: '100%', boxSizing: 'border-box' as const }} />
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: 10 }}>
                <select value={form.category} onChange={e => setForm(f => ({ ...f, category: e.target.value }))} style={inputStyle}>
                  {['reading','writing','listening','speaking','maths','science','citizenship','ict','home_economics','arts','pe'].map(c => <option key={c} value={c}>{c}</option>)}
                </select>
                <select value={form.difficulty} onChange={e => setForm(f => ({ ...f, difficulty: e.target.value }))} style={inputStyle}>
                  <option value="easy">Easy</option><option value="medium">Medium</option><option value="hard">Hard</option>
                </select>
                <label style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 14, fontWeight: 700, color: P.dark, cursor: 'pointer' }}>
                  <input type="checkbox" checked={form.is_active} onChange={e => setForm(f => ({ ...f, is_active: e.target.checked }))} /> Actif
                </label>
              </div>
              <div>
                <div style={{ fontSize: 12, fontWeight: 800, color: P.soft, marginBottom: 6, textTransform: 'uppercase' as const, letterSpacing: 1 }}>Contenu JSON</div>
                <textarea value={form.content} onChange={e => setForm(f => ({ ...f, content: e.target.value }))} style={taStyle} />
              </div>
            </div>
            <div style={{ display: 'flex', gap: 10 }}>
              <button onClick={save} disabled={saving} style={btnStyle(P.sidebar)}>{saving ? 'Enregistrement...' : 'Enregistrer'}</button>
              <button onClick={onClose} style={btnStyle(P.border, P.dark)}>Annuler</button>
            </div>
          </>
        )}
      </div>
    </div>
  )
}

// ── EXERCISES ─────────────────────────────────────────────────────────────────
function ExercisesScreen({ initParams }: { initParams?: any }) {
  const [exercises, setExercises] = useState<any[]>([])
  const [total, setTotal] = useState(0)
  const [levels, setLevels] = useState<any[]>([])
  const [levelFilter, setLevelFilter] = useState('')
  const [search, setSearch] = useState('')
  const [resetTarget, setResetTarget] = useState<any>(null)
  const [resetDone, setResetDone] = useState<string | null>(null)
  const [page, setPage] = useState(0)
  const [editingId, setEditingId] = useState<number|null>(null)
  const [deleteTarget, setDeleteTarget] = useState<any>(null)
  const lessonFilter = initParams?.lesson_id?.toString() || ''
  const limit = 30
  useEffect(() => { api('/levels').then(setLevels) }, [])
  const reload = () => {
    const params = new URLSearchParams({ limit: String(limit), offset: String(page * limit) })
    if (levelFilter) params.set('level_id', levelFilter)
    if (lessonFilter) params.set('lesson_id', lessonFilter)
    api(`/exercises?${params}`).then(d => { setExercises(d.data||[]); setTotal(d.total||0) })
  }
  useEffect(() => { reload() }, [levelFilter, page, lessonFilter])
  const confirmDelete = async () => { await api(`/exercises/${deleteTarget.id}`, { method: 'DELETE' }); setDeleteTarget(null); reload() }
  const filtered = exercises.filter(e => e.title.toLowerCase().includes(search.toLowerCase()))
  const tc = (t: string) => ({ mcq:'#3B82F6',multiple_choice:'#3B82F6',true_false:'#10B981',fill_in:'#F59E0B',match_pairs:'#8B5CF6',oral_drill:'#EC4899',handwriting:'#6B7280',sentence_order:'#F97316',clock_reading:'#06B6D4' } as Record<string,string>)[t]||'#9CA3AF'
  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 20 }}>
        <h2 style={{ fontSize: 22, fontWeight: 900, color: P.dark }}>
          Exercices{initParams?.lesson_name && <span style={{ color: P.soft, fontWeight: 700 }}> — {initParams.lesson_name}</span>}
          <span style={{ fontSize: 15, marginLeft: 8 }}>({total.toLocaleString()})</span>
        </h2>
      </div>
      <div style={{ display: 'flex', gap: 10, marginBottom: 16 }}>
        <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Rechercher..." style={{ ...inputStyle, flex: 1 }} />
        {!lessonFilter && <select value={levelFilter} onChange={e => { setLevelFilter(e.target.value); setPage(0) }} style={inputStyle}>
          <option value="">Tous les niveaux</option>
          {levels.map(l => <option key={l.id} value={l.id}>{l.name}</option>)}
        </select>}
      </div>
      <div style={{ background: P.card, borderRadius: 14, border: `1.5px solid ${P.border}`, overflow: 'hidden' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead><tr style={{ background: P.light }}>
            {['ID','Titre','Type','Matiere','Niveau','Statut',''].map(h => <th key={h} style={{ padding: '12px 14px', textAlign: 'left', fontSize: 11, fontWeight: 900, color: P.soft, textTransform: 'uppercase' as const, letterSpacing: 1 }}>{h}</th>)}
          </tr></thead>
          <tbody>
            {filtered.map((e, i) => (
              <tr key={e.id} style={{ borderTop: `1px solid ${P.border}`, background: i%2===0 ? P.card : '#FAFAF8' }}>
                <td style={{ padding: '10px 14px', fontSize: 12, color: P.soft }}>{e.id}</td>
                <td style={{ padding: '10px 14px', fontSize: 13, fontWeight: 700, color: P.dark, maxWidth: 200, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' as const }}>{e.title}</td>
                <td style={{ padding: '10px 14px' }}><span style={{ background: tc(e.type)+'22', color: tc(e.type), borderRadius: 6, padding: '2px 8px', fontSize: 11, fontWeight: 800 }}>{e.type||'—'}</span></td>
                <td style={{ padding: '10px 14px', fontSize: 12, color: P.soft }}>{e.subject_name}</td>
                <td style={{ padding: '10px 14px', fontSize: 12, color: P.soft }}>{e.level_name}</td>
                <td style={{ padding: '10px 14px' }}><span style={{ background: e.is_active ? '#D1FAE5' : '#FEE2E2', color: e.is_active ? '#065F46' : P.red, borderRadius: 6, padding: '2px 8px', fontSize: 11, fontWeight: 800 }}>{e.is_active ? 'Actif' : 'Inactif'}</span></td>
                <td style={{ padding: '10px 14px', whiteSpace: 'nowrap' as const }}>
                  <button onClick={() => setEditingId(e.id)} style={{ background: P.accent+'22', color: P.accent, border: 'none', borderRadius: 7, padding: '5px 10px', fontWeight: 800, cursor: 'pointer', fontSize: 11, marginRight: 6 }}>Modifier</button>
                  <button onClick={() => setDeleteTarget(e)} style={{ background: '#FEE2E222', color: P.red, border: 'none', borderRadius: 7, padding: '5px 10px', fontWeight: 800, cursor: 'pointer', fontSize: 11 }}>✕</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      <div style={{ display: 'flex', justifyContent: 'center', gap: 8, marginTop: 16 }}>
        <button disabled={page===0} onClick={() => setPage(p => p-1)} style={{ padding: '8px 16px', borderRadius: 8, border: `1.5px solid ${P.border}`, background: page===0?P.border:P.card, cursor: page===0?'default':'pointer', fontWeight: 700 }}>← Prec.</button>
        <span style={{ padding: '8px 16px', fontSize: 13, color: P.soft }}>{page+1} / {Math.max(1,Math.ceil(total/limit))}</span>
        <button disabled={(page+1)*limit>=total} onClick={() => setPage(p => p+1)} style={{ padding: '8px 16px', borderRadius: 8, border: `1.5px solid ${P.border}`, background: (page+1)*limit>=total?P.border:P.card, cursor: (page+1)*limit>=total?'default':'pointer', fontWeight: 700 }}>Suiv. →</button>
      </div>
      {editingId !== null && <ExerciseEditModal exerciseId={editingId} onClose={() => setEditingId(null)} onSaved={() => { setEditingId(null); reload() }} />}
      {deleteTarget && <ConfirmDelete label={`"${deleteTarget.title}"`} onConfirm={confirmDelete} onCancel={() => setDeleteTarget(null)} />}
    </div>
  )
}


// ── ASSETS SCREEN ─────────────────────────────────────────────────────────────
function AssetsScreen() {
  const ABASE = 'http://192.168.100.106:8100/assets'
  const [assets, setAssets] = useState<any[]>([])
  const [search, setSearch] = useState('')
  const [resetTarget, setResetTarget] = useState<any>(null)
  const [resetDone, setResetDone] = useState<string | null>(null)
  const [typeFilter, setTypeFilter] = useState('')
  const [editing, setEditing] = useState<any>(null)
  const [form, setForm] = useState({ key:'', type:'emoji', value:'', tags:'', description:'' })
  const [deleteTarget, setDeleteTarget] = useState<any>(null)
  const [error, setError] = useState('')

  const reload = () => fetch(ABASE+'/icons').then(r=>r.json()).then(setAssets)
  useEffect(() => { reload() }, [])

  const openAdd = () => { setError(''); setForm({key:'',type:'emoji',value:'',tags:'',description:''}); setEditing({}) }
  const openEdit = (a: any) => { setError(''); setForm({key:a.key,type:a.type,value:a.value,tags:(a.tags||[]).join(', '),description:a.description||''}); setEditing(a) }

  const save = async () => {
    if (!form.key.trim() || !form.value.trim()) return setError('Cle et valeur requises')
    const payload = { ...form, tags: form.tags.split(',').map((t:string)=>t.trim()).filter(Boolean) }
    if (editing?.key) {
      await fetch(ABASE+'/icons/'+editing.key, { method:'PUT', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload) })
    } else {
      await fetch(ABASE+'/icons', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload) })
    }
    setEditing(null); reload()
  }

  const confirmDelete = async () => {
    await fetch(ABASE+'/icons/'+deleteTarget.key, { method:'DELETE' })
    setDeleteTarget(null); reload()
  }

  const preview = (a: any) => {
    if (a.type === 'emoji') return <span style={{fontSize:28}}>{a.value}</span>
    if (a.type === 'url') return <img src={a.value} style={{width:32,height:32,objectFit:'contain'}} alt={a.key} onError={e=>(e.currentTarget.style.display='none')} />
    return <span style={{fontSize:11,color:'#8B5CF6',fontWeight:800}}>SVG</span>
  }

  const filtered = assets.filter(a => {
    const q = search.toLowerCase()
    const matchQ = !q || a.key.toLowerCase().includes(q) || (a.description||'?').toLowerCase().includes(q) || (a.tags||[]).some((t:string)=>t.toLowerCase().includes(q))
    const matchT = !typeFilter || a.type === typeFilter
    return matchQ && matchT
  })

  return (
    <div>
      <div style={{display:'flex',justifyContent:'space-between',alignItems:'center',marginBottom:20}}>
        <h2 style={{fontSize:22,fontWeight:900,color:P.dark}}>Assets / Icones ({assets.length})</h2>
        <button onClick={openAdd} style={btnStyle(P.sidebar)}>+ Ajouter</button>
      </div>

      <div style={{display:'flex',gap:10,marginBottom:16}}>
        <input value={search} onChange={e=>setSearch(e.target.value)} placeholder="Rechercher cle, tag, description..."
          style={{...inputStyle,flex:1}} />
        <select value={typeFilter} onChange={e=>setTypeFilter(e.target.value)} style={inputStyle}>
          <option value="">Tous les types</option>
          <option value="emoji">Emoji</option>
          <option value="url">URL Image</option>
          <option value="svg">SVG</option>
        </select>
      </div>

      {editing !== null && (
        <div style={{background:P.light,borderRadius:18,padding:20,marginBottom:20,border:`1.5px solid ${P.border}`}}>
          <div style={{fontSize:16,fontWeight:900,color:P.dark,marginBottom:14}}>{editing.key ? 'Modifier' : 'Nouvel asset'}</div>
          {error && <div style={{background:'#FEE2E2',color:P.red,borderRadius:10,padding:'8px 12px',marginBottom:12,fontSize:13,fontWeight:700}}>{error}</div>}
          <div style={{display:'grid',gridTemplateColumns:'1fr 1fr',gap:12,marginBottom:12}}>
            <input placeholder="Cle unique *" value={form.key} onChange={e=>setForm(f=>({...f,key:e.target.value}))}
              style={inputStyle} disabled={!!editing.key} />
            <select value={form.type} onChange={e=>setForm(f=>({...f,type:e.target.value}))} style={inputStyle}>
              <option value="emoji">Emoji</option>
              <option value="url">URL Image</option>
              <option value="svg">SVG inline</option>
            </select>
            <input placeholder="Valeur * (emoji, URL ou SVG)" value={form.value} onChange={e=>setForm(f=>({...f,value:e.target.value}))} style={inputStyle} />
            <input placeholder="Tags (virgule separee)" value={form.tags} onChange={e=>setForm(f=>({...f,tags:e.target.value}))} style={inputStyle} />
            <input placeholder="Description" value={form.description} onChange={e=>setForm(f=>({...f,description:e.target.value}))} style={{...inputStyle,gridColumn:'1/-1'}} />
          </div>
          {form.value && (
            <div style={{marginBottom:12,padding:'10px 14px',background:P.card,borderRadius:10,border:`1.5px solid ${P.border}`,display:'flex',alignItems:'center',gap:10}}>
              <span style={{fontSize:11,fontWeight:800,color:P.soft}}>APERCU :</span>
              {form.type==='emoji' && <span style={{fontSize:32}}>{form.value}</span>}
              {form.type==='url' && <img src={form.value} style={{width:40,height:40,objectFit:'contain'}} alt="preview" />}
              {form.type==='svg' && <span style={{fontSize:11,color:'#8B5CF6'}}>SVG inline</span>}
            </div>
          )}
          <div style={{display:'flex',gap:10}}>
            <button onClick={save} style={btnStyle(P.sidebar)}>Enregistrer</button>
            <button onClick={()=>setEditing(null)} style={btnStyle(P.border,P.dark)}>Annuler</button>
          </div>
        </div>
      )}

      <div style={{display:'grid',gridTemplateColumns:'repeat(auto-fill,minmax(220px,1fr))',gap:12}}>
        {filtered.map(a => (
          <div key={a.key} style={{background:P.card,borderRadius:14,padding:'14px 16px',border:`1.5px solid ${P.border}`,display:'flex',flexDirection:'column',gap:8}}>
            <div style={{display:'flex',alignItems:'center',gap:12}}>
              <div style={{width:48,height:48,borderRadius:12,background:P.light,display:'flex',alignItems:'center',justifyContent:'center'}}>
                {preview(a)}
              </div>
              <div style={{flex:1,minWidth:0}}>
                <div style={{fontWeight:900,color:P.dark,fontSize:13,overflow:'hidden',textOverflow:'ellipsis',whiteSpace:'nowrap'}}>{a.key}</div>
                <div style={{fontSize:11,color:P.soft,marginTop:2}}>{a.description||'—'}</div>
              </div>
            </div>
            <div style={{display:'flex',gap:4,flexWrap:'wrap'}}>
              <span style={{background:'#3B82F622',color:'#3B82F6',borderRadius:6,padding:'2px 7px',fontSize:10,fontWeight:800}}>{a.type}</span>
              {(a.tags||[]).slice(0,3).map((t:string) => (
                <span key={t} style={{background:P.sidebar+'22',color:P.sidebar,borderRadius:6,padding:'2px 7px',fontSize:10,fontWeight:700}}>{t}</span>
              ))}
            </div>
            <div style={{display:'flex',gap:6,marginTop:2}}>
              <button onClick={()=>openEdit(a)} style={{flex:1,background:P.accent+'22',color:P.accent,border:'none',borderRadius:8,padding:'5px',fontWeight:800,cursor:'pointer',fontSize:12}}>Modifier</button>
              <button onClick={()=>setDeleteTarget(a)} style={{background:'#FEE2E222',color:P.red,border:'none',borderRadius:8,padding:'5px 10px',fontWeight:800,cursor:'pointer',fontSize:12}}>✕</button>
            </div>
          </div>
        ))}
      </div>

      {deleteTarget && (
        <ConfirmDelete label={`"${deleteTarget.key}" (${deleteTarget.description||deleteTarget.value})`}
          onConfirm={confirmDelete} onCancel={()=>setDeleteTarget(null)} />
      )}
    </div>
  )
}


// ── HEALTH SCREEN ─────────────────────────────────────────────────────────────
function HealthScreen() {
  const [data, setData] = useState<any>(null)
  const [loading, setLoading] = useState(false)

  const check = async () => {
    setLoading(true)
    const res = await api('/health')
    setData(res)
    setLoading(false)
  }

  useEffect(() => { check() }, [])

  const statusColor = (s: string) => s === 'ok' ? '#10B981' : P.red
  const statusBg = (s: string) => s === 'ok' ? '#D1FAE5' : '#FEE2E2'
  const msColor = (ms: number) => ms < 100 ? '#10B981' : ms < 300 ? '#F59E0B' : P.red

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 24 }}>
        <h2 style={{ fontSize: 22, fontWeight: 900, color: P.dark }}>
          Health Check
          {data && <span style={{ fontSize: 14, fontWeight: 600, color: P.soft, marginLeft: 12 }}>
            {data.ok}/{data.total} modules OK
          </span>}
        </h2>
        <button onClick={check} disabled={loading} style={{ background: P.sidebar, color: 'white', border: 'none', borderRadius: 10, padding: '9px 18px', fontWeight: 800, cursor: loading ? 'wait' : 'pointer', fontSize: 13, fontFamily: 'Nunito,sans-serif' }}>
          {loading ? 'Verification...' : 'Tester tout'}
        </button>
      </div>

      {data && (
        <>
          {/* Summary bar */}
          <div style={{ background: P.card, borderRadius: 14, padding: '14px 20px', border: `1.5px solid ${P.border}`, marginBottom: 20, display: 'flex', gap: 24 }}>
            <div style={{ textAlign: 'center' }}>
              <div style={{ fontSize: 28, fontWeight: 900, color: '#10B981' }}>{data.ok}</div>
              <div style={{ fontSize: 11, color: P.soft, fontWeight: 700 }}>OK</div>
            </div>
            <div style={{ textAlign: 'center' }}>
              <div style={{ fontSize: 28, fontWeight: 900, color: P.red }}>{data.total - data.ok}</div>
              <div style={{ fontSize: 11, color: P.soft, fontWeight: 700 }}>ERREUR</div>
            </div>
            <div style={{ textAlign: 'center' }}>
              <div style={{ fontSize: 28, fontWeight: 900, color: P.dark }}>{data.total}</div>
              <div style={{ fontSize: 11, color: P.soft, fontWeight: 700 }}>TOTAL</div>
            </div>
            <div style={{ flex: 1, display: 'flex', alignItems: 'center' }}>
              <div style={{ flex: 1, height: 10, background: P.border, borderRadius: 999, overflow: 'hidden' }}>
                <div style={{ width: `${(data.ok/data.total)*100}%`, height: '100%', background: '#10B981', borderRadius: 999, transition: 'width .5s' }} />
              </div>
            </div>
          </div>

          {/* Modules grid */}
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill,minmax(240px,1fr))', gap: 10 }}>
            {data.modules.map((m: any) => (
              <div key={m.name} style={{ background: P.card, borderRadius: 14, padding: '14px 18px', border: `1.5px solid ${m.status==='ok' ? '#D1FAE5' : '#FEE2E2'}`, display: 'flex', alignItems: 'center', gap: 12 }}>
                <div style={{ width: 10, height: 10, borderRadius: '50%', background: statusColor(m.status), flexShrink: 0 }} />
                <div style={{ flex: 1 }}>
                  <div style={{ fontWeight: 900, color: P.dark, fontSize: 14 }}>{m.name}</div>
                  {m.error && <div style={{ fontSize: 11, color: P.red, marginTop: 2 }}>{m.error}</div>}
                </div>
                <div style={{ textAlign: 'right' }}>
                  <span style={{ background: statusBg(m.status), color: statusColor(m.status), borderRadius: 6, padding: '2px 8px', fontSize: 11, fontWeight: 800, display: 'block', marginBottom: 4 }}>
                    {m.status === 'ok' ? `${m.code}` : `ERR ${m.code}`}
                  </span>
                  <span style={{ fontSize: 11, fontWeight: 700, color: msColor(m.ms) }}>{m.ms}ms</span>
                </div>
              </div>
            ))}
          </div>

          <div style={{ marginTop: 12, fontSize: 11, color: P.soft, textAlign: 'right' }}>
            Verifie le {new Date(data.checked_at * 1000).toLocaleTimeString('fr-FR')}
          </div>
        </>
      )}

      {!data && !loading && <div style={{ color: P.soft, textAlign: 'center', padding: 40 }}>Cliquez sur "Tester tout"</div>}
    </div>
  )
}


// ── BULLETIN SCREEN ──────────────────────────────────────────────────────────
function BulletinScreen() {
  const BBASE = 'http://192.168.100.106:8100/api'
  const [children, setChildren] = useState<any[]>([])
  const [selChild, setSelChild] = useState<number | null>(null)
  const [bulletin, setBulletin] = useState<any>(null)
  const [loading, setLoading] = useState(false)

  useEffect(() => { api('/children').then(setChildren) }, [])

  useEffect(() => {
    if (!selChild) return
    setLoading(true); setBulletin(null)
    fetch(`${BBASE}/bulletin/child/${selChild}`)
      .then(r => r.json()).then(d => { setBulletin(d); setLoading(false) })
  }, [selChild])

  const appColor = (a: string) => {
    const m: Record<string,string> = { 'Excellent':'#10B981','Very Good':'#3B82F6','Good':'#8B5CF6','Pass':'#F59E0B' }
    return m[a] || P.red
  }

  const compLabel = (subj: string) => {
    const s = subj.toLowerCase()
    if (['english','french','nlc','reading','handwriting'].some(x=>s.includes(x))) return 'C1'
    if (['mathematics','science'].some(x=>s.includes(x))) return 'C2'
    if (['citizenship','social'].some(x=>s.includes(x))) return 'C3'
    if (['home'].some(x=>s.includes(x))) return 'C4'
    if (['ict'].some(x=>s.includes(x))) return 'C5'
    if (['arts','pe','physical'].some(x=>s.includes(x))) return 'C6'
    return '—'
  }

  return (
    <div>
      <h2 style={{fontSize:22,fontWeight:900,color:P.dark,marginBottom:20}}>Bulletins MINEDUB</h2>

      <div style={{display:'flex',gap:10,marginBottom:24,flexWrap:'wrap' as const}}>
        {children.map(c => (
          <button key={c.id} onClick={() => setSelChild(c.id)} style={{
            padding:'8px 18px',borderRadius:999,
            border:`1.5px solid ${selChild===c.id ? P.sidebar : P.border}`,
            background:selChild===c.id ? P.sidebar : P.card,
            color:selChild===c.id ? 'white' : P.dark,
            fontWeight:selChild===c.id ? 900 : 600,fontSize:14,
            cursor:'pointer',fontFamily:'Nunito,sans-serif',
          }}>
            {c.first_name} <span style={{fontSize:11,opacity:.7}}>({c.level_name})</span>
          </button>
        ))}
      </div>

      {!selChild && <div style={{textAlign:'center',padding:'40px 0',color:P.soft}}>Selectionnez un enfant</div>}
      {loading && <div style={{textAlign:'center',padding:'40px 0',color:P.soft}}>Chargement...</div>}

      {bulletin && !loading && (
        <div style={{background:P.card,borderRadius:18,border:`1.5px solid ${P.border}`,overflow:'hidden'}}>
          <div style={{background:P.sidebar,padding:'20px 28px',color:'white'}}>
            <div style={{display:'flex',justifyContent:'space-between',alignItems:'flex-start'}}>
              <div>
                <div style={{fontSize:11,fontWeight:700,opacity:.7,letterSpacing:1.5,textTransform:'uppercase' as const}}>EDUMAISON — MINEDUB</div>
                <div style={{fontSize:22,fontWeight:900,marginTop:4}}>{bulletin.child.name}</div>
                <div style={{fontSize:13,opacity:.8,marginTop:4}}>{bulletin.child.level} • {bulletin.year}</div>
              </div>
              <div style={{textAlign:'right'}}>
                <div style={{fontSize:36,fontWeight:900}}>{bulletin.average?.toFixed(1)}</div>
                <div style={{fontSize:11,opacity:.7}}>Moyenne</div>
                {bulletin.rank && <div style={{fontSize:13,fontWeight:700,marginTop:4}}>Rang {bulletin.rank}/{bulletin.class_size}</div>}
              </div>
            </div>
          </div>

          <div style={{display:'grid',gridTemplateColumns:'repeat(4,1fr)',borderBottom:`1px solid ${P.border}`}}>
            {[
              {label:'XP Total',value:bulletin.total_xp?.toLocaleString(),color:P.accent},
              {label:'Rang',value:bulletin.rank ? `${bulletin.rank}/${bulletin.class_size}` : '—',color:'#3B82F6'},
              {label:'Moy. classe',value:bulletin.class_average?.toFixed(1)||'—',color:'#8B5CF6'},
              {label:'Matieres',value:bulletin.results?.length,color:'#10B981'},
            ].map((s,i) => (
              <div key={i} style={{padding:'16px 20px',borderRight:i<3?`1px solid ${P.border}`:'none',textAlign:'center'}}>
                <div style={{fontSize:24,fontWeight:900,color:s.color}}>{s.value}</div>
                <div style={{fontSize:11,color:P.soft,fontWeight:700,marginTop:2}}>{s.label}</div>
              </div>
            ))}
          </div>

          <table style={{width:'100%',borderCollapse:'collapse'}}>
            <thead><tr style={{background:P.light}}>
              {['Comp','Matiere','Note','/Max','Appreciation','Commentaire'].map(h => (
                <th key={h} style={{padding:'11px 16px',textAlign:'left',fontSize:11,fontWeight:900,color:P.soft,textTransform:'uppercase' as const,letterSpacing:1}}>{h}</th>
              ))}
            </tr></thead>
            <tbody>
              {bulletin.results?.map((r: any, i: number) => (
                <tr key={r.subject_id} style={{borderTop:`1px solid ${P.border}`,background:i%2===0?P.card:'#FAFAF8'}}>
                  <td style={{padding:'10px 16px'}}><span style={{background:P.sidebar+'22',color:P.sidebar,borderRadius:6,padding:'2px 7px',fontSize:11,fontWeight:800}}>{compLabel(r.subject)}</span></td>
                  <td style={{padding:'10px 16px',fontWeight:800,color:P.dark,fontSize:14}}>{r.subject}</td>
                  <td style={{padding:'10px 16px'}}><span style={{fontSize:18,fontWeight:900,color:r.average_score>=10?'#10B981':P.red}}>{r.average_score?.toFixed(1)}</span></td>
                  <td style={{padding:'10px 16px',fontSize:13,color:P.soft}}>{r.max_score}</td>
                  <td style={{padding:'10px 16px'}}><span style={{color:appColor(r.appreciation),fontWeight:800,fontSize:12}}>{r.appreciation||'—'}</span></td>
                  <td style={{padding:'10px 16px',fontSize:12,color:P.soft,maxWidth:200,overflow:'hidden',textOverflow:'ellipsis',whiteSpace:'nowrap' as const}}>{r.teacher_comment||'—'}</td>
                </tr>
              ))}
            </tbody>
          </table>

          {!bulletin.results?.length && (
            <div style={{padding:'32px',textAlign:'center',color:P.soft,fontSize:14}}>Aucun resultat disponible.</div>
          )}
        </div>
      )}

      {bulletin === null && selChild && !loading && (
        <div style={{textAlign:'center',padding:'40px 0',color:P.soft}}>Aucun bulletin disponible.</div>
      )}
    </div>
  )
}

// ── APP ───────────────────────────────────────────────────────────────────────
export default function AdminApp() {
  const [screen, setScreen] = useState<Screen>('dashboard')
  const [screenParams, setScreenParams] = useState<any>(null)
  const goTo = (s: Screen, params?: any) => { setScreenParams(params||null); setScreen(s) }
  const NAV = [
    { id: 'dashboard' as Screen, icon: '📊', label: 'Dashboard' },
    { id: 'children' as Screen, icon: '👨‍👩‍👧‍👦', label: 'Enfants' },
    { id: 'subjects' as Screen, icon: '🎯', label: 'Matieres' },
    { id: 'curriculum' as Screen, icon: '🗂️', label: 'Curriculum' },
    { id: 'assets' as Screen, icon: '🖼️', label: 'Assets' },
    { id: 'bulletin' as Screen, icon: '📋', label: 'Bulletins' },
    { id: 'health' as Screen, icon: '🩺', label: 'Health' },
    { id: 'exercises' as Screen, icon: '📝', label: 'Exercices' },
  ]
  return (
    <div style={{ display: 'flex', minHeight: '100vh', fontFamily: 'Nunito,system-ui,sans-serif', background: P.bg }}>
      <div style={{ width: 220, background: P.sidebar, display: 'flex', flexDirection: 'column', position: 'fixed', height: '100vh', left: 0, top: 0 }}>
        <div style={{ padding: '24px 20px 20px', borderBottom: '1px solid rgba(255,255,255,.15)' }}>
          <div style={{ fontSize: 11, fontWeight: 900, color: 'rgba(255,255,255,.5)', letterSpacing: 2 }}>EDUMAISON</div>
          <div style={{ fontSize: 18, fontWeight: 900, color: 'white', marginTop: 4 }}>Admin</div>
        </div>
        <nav style={{ padding: '12px 10px', flex: 1 }}>
          {NAV.map(item => (
            <button key={item.id} onClick={() => goTo(item.id)}
              style={{ width: '100%', display: 'flex', alignItems: 'center', gap: 10, padding: '11px 14px', borderRadius: 12, border: 'none', cursor: 'pointer', marginBottom: 4, background: screen===item.id ? 'rgba(255,255,255,.2)' : 'transparent', color: screen===item.id ? 'white' : 'rgba(255,255,255,.6)', fontWeight: screen===item.id ? 900 : 600, fontSize: 14, fontFamily: 'Nunito,sans-serif', textAlign: 'left' as const }}>
              <span style={{ fontSize: 18 }}>{item.icon}</span>{item.label}
            </button>
          ))}
        </nav>
        <div style={{ padding: '12px', borderTop: '1px solid rgba(255,255,255,.15)' }}>
          <a href="/app" style={{ display: 'block', textAlign: 'center', color: 'rgba(255,255,255,.5)', fontSize: 12, fontWeight: 700, textDecoration: 'none' }}>← App enfant</a>
        </div>
      </div>
      <div style={{ flex: 1, marginLeft: 220, padding: '32px 40px', maxWidth: 'calc(100vw - 220px)' }}>
        {screen === 'dashboard' && <Dashboard goTo={goTo} />}
        {screen === 'children' && <ChildrenScreen />}
        {screen === 'subjects' && <SubjectsScreen />}
        {screen === 'curriculum' && <CurriculumScreen goTo={goTo} />}
        {screen === 'exercises' && <ExercisesScreen initParams={screenParams} />}
        {screen === 'assets' && <AssetsScreen />}
        {screen === 'bulletin' && <BulletinScreen />}
        {screen === 'health' && <HealthScreen />}
      </div>
    </div>
  )
}
