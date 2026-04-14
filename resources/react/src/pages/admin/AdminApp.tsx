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

type Screen = 'dashboard' | 'children' | 'subjects' | 'curriculum' | 'exercises'

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
  useEffect(() => { api('/children').then(setChildren); api('/levels').then(setLevels) }, [])
  const save = async () => {
    if (editing?.id) await api(`/children/${editing.id}`, { method: 'PUT', body: JSON.stringify(form) })
    else await api('/children', { method: 'POST', body: JSON.stringify(form) })
    setEditing(null); api('/children').then(setChildren)
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
          </div>
        ))}
      </div>
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
      </div>
    </div>
  )
}
