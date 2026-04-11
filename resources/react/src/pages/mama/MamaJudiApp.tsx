// MamaJudiApp.tsx — Interface Mama Judi (S9+, bilingue FR/EN, tactile)
import { useState, useEffect } from 'react'

// ── Types ─────────────────────────────────────────────────────────────────────
interface ChildBrief {
  id: number
  name: string
  level: string
  level_id: number
  today_count: number
  today_avg: number
  subjects_today: string[]
  weak_subjects: { name: string; avg: number }[]
  needs_attention: boolean
}

interface Brief {
  date: string
  children: ChildBrief[]
  summary: { total_exercises: number; active_today: number; needs_attention: string[] }
}

interface Subject { id: number; name: string; units: { id: number; name: string }[] }

// ── Palette Mama Judi ─────────────────────────────────────────────────────────
const P = {
  bg:      '#FFF8F2',
  card:    '#FFFFFF',
  green:   '#1D6B2A',
  rose:    '#FF8FAB',
  amber:   '#F59E0B',
  red:     '#CE1126',
  soft:    '#C8A090',
  dark:    '#2D1B0E',
  border:  '#F0E4D8',
}

// ── Traductions ───────────────────────────────────────────────────────────────
const T = {
  fr: {
    title: 'Espace Mama Judi',
    greeting: (h: number) => h < 12 ? 'Bonjour !' : h < 18 ? 'Bon après-midi !' : 'Bonsoir !',
    brief: 'Résumé du jour',
    revision: 'Révision du soir',
    duel: 'Lancer un duel',
    exercises_today: 'exercices aujourd\'hui',
    avg: 'moy.',
    weak: 'À renforcer',
    no_activity: 'Pas d\'activité aujourd\'hui',
    send: 'Envoyer la révision ✨',
    choose_subject: 'Choisir une matière',
    choose_unit: 'Choisir une unité',
    choose_children: 'Pour qui ?',
    message: 'Message vocal (optionnel)',
    message_ph: 'Ex: Ce soir on révise les fractions !',
    auto: 'Laisser EduMaison choisir',
    duel_child1: 'Premier enfant',
    duel_child2: 'Deuxième enfant',
    duel_exercises: 'Nombre d\'exercices',
    duel_duration: 'Durée',
    start_duel: '⚔️ Démarrer le duel !',
    sent: 'Envoyé ! Les enfants vont recevoir la révision.',
    duel_sent: 'Duel créé ! Les enfants vont être notifiés.',
    back: '← Retour',
    select_all: 'Tous',
    minutes: 'min',
    attention: 'Attention requise',
    well_done: 'Bravo !',
  },
  en: {
    title: 'Mama Judi Space',
    greeting: (h: number) => h < 12 ? 'Good morning!' : h < 18 ? 'Good afternoon!' : 'Good evening!',
    brief: 'Today\'s Summary',
    revision: 'Evening Revision',
    duel: 'Start a Duel',
    exercises_today: 'exercises today',
    avg: 'avg',
    weak: 'Needs work',
    no_activity: 'No activity today',
    send: 'Send Revision ✨',
    choose_subject: 'Choose a subject',
    choose_unit: 'Choose a unit',
    choose_children: 'For who?',
    message: 'Voice message (optional)',
    message_ph: 'e.g. Tonight we revise fractions!',
    auto: 'Let EduMaison choose',
    duel_child1: 'First child',
    duel_child2: 'Second child',
    duel_exercises: 'Number of exercises',
    duel_duration: 'Duration',
    start_duel: '⚔️ Start the Duel!',
    sent: 'Sent! Children will receive the revision.',
    duel_sent: 'Duel created! Children will be notified.',
    back: '← Back',
    select_all: 'All',
    minutes: 'min',
    attention: 'Needs attention',
    well_done: 'Well done!',
  }
}

// ── MamaJudi SVG ──────────────────────────────────────────────────────────────
function MamaJudiAvatar({ size = 80 }: { size?: number }) {
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
      <path d="M30 54 Q42 65 54 54" stroke="#1A0A00" strokeWidth="2.2" fill="none" strokeLinecap="round"/>
      <rect x="38" y="63" width="8" height="10" rx="4" fill="#C8874A"/>
      <path d="M14 92 Q12 76 42 72 Q72 76 70 92 L68 104 L16 104 Z" fill="#FF8FAB"/>
    </svg>
  )
}

// ── Ecran Brief ───────────────────────────────────────────────────────────────
function BriefScreen({ brief, t }: { brief: Brief; t: typeof T['fr'] }) {
  return (
    <div>
      <div style={{ fontSize: 14, fontWeight: 800, color: P.soft, marginBottom: 12, textTransform: 'uppercase', letterSpacing: 1 }}>
        {t.brief}
      </div>
      {brief.children.map(c => (
        <div key={c.id} style={{
          background: P.card, borderRadius: 18, padding: '16px',
          marginBottom: 12, border: `2px solid ${c.needs_attention ? P.rose + '66' : P.border}`,
          boxShadow: '0 2px 8px rgba(0,0,0,0.05)'
        }}>
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 8 }}>
            <div style={{ fontSize: 17, fontWeight: 900, color: P.dark }}>{c.name}</div>
            <div style={{ fontSize: 11, color: P.soft, fontWeight: 600 }}>{c.level}</div>
          </div>
          {c.today_count > 0 ? (
            <div style={{ display: 'flex', gap: 8, alignItems: 'center', flexWrap: 'wrap' }}>
              <span style={{ background: P.green, color: 'white', borderRadius: 20, padding: '3px 10px', fontSize: 12, fontWeight: 800 }}>
                {c.today_count} {t.exercises_today}
              </span>
              <span style={{ background: P.amber + '22', color: P.amber, borderRadius: 20, padding: '3px 10px', fontSize: 12, fontWeight: 800 }}>
                {t.avg} {c.today_avg}%
              </span>
              {c.subjects_today.map(s => (
                <span key={s} style={{ background: P.border, color: P.soft, borderRadius: 20, padding: '3px 10px', fontSize: 11 }}>{s}</span>
              ))}
            </div>
          ) : (
            <div style={{ fontSize: 13, color: P.rose, fontWeight: 700 }}>⚠️ {t.no_activity}</div>
          )}
          {c.weak_subjects.length > 0 && (
            <div style={{ marginTop: 8 }}>
              <div style={{ fontSize: 11, color: P.soft, marginBottom: 4 }}>{t.weak} :</div>
              <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap' }}>
                {c.weak_subjects.map(w => (
                  <span key={w.name} style={{
                    background: w.avg < 7 ? P.red + '15' : P.amber + '15',
                    color: w.avg < 7 ? P.red : P.amber,
                    borderRadius: 20, padding: '2px 8px', fontSize: 11, fontWeight: 700
                  }}>{w.name} {w.avg}/20</span>
                ))}
              </div>
            </div>
          )}
        </div>
      ))}
    </div>
  )
}

// ── Ecran Revision ────────────────────────────────────────────────────────────
function RevisionScreen({ brief, t, lang }: { brief: Brief; t: typeof T['fr']; lang: 'fr' | 'en' }) {
  const [selectedChildren, setSelectedChildren] = useState<number[]>(brief.children.map(c => c.id))
  const [subjectId, setSubjectId] = useState<number | null>(null)
  const [unitId, setUnitId] = useState<number | null>(null)
  const [message, setMessage] = useState('')
  const [subjects, setSubjects] = useState<Subject[]>([])
  const [sent, setSent] = useState(false)
  const [loading, setLoading] = useState(false)

  // Charger les matieres du premier enfant selectionne
  useEffect(() => {
    const child = brief.children.find(c => selectedChildren.includes(c.id))
    if (child) {
      fetch(`/api/mama/subjects/${child.level_id}`)
        .then(r => r.json())
        .then(setSubjects)
        .catch(() => {})
    }
  }, [selectedChildren])

  const toggleChild = (id: number) => {
    setSelectedChildren(prev =>
      prev.includes(id) ? prev.filter(x => x !== id) : [...prev, id]
    )
  }

  const send = async () => {
    if (selectedChildren.length === 0) return
    setLoading(true)
    try {
      await fetch('/api/evening-sessions', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          child_ids: selectedChildren,
          subject_id: subjectId,
          unit_id: unitId,
          theme_source: subjectId ? 'manual' : 'auto',
          mama_judi_message: message || (lang === 'fr' ? 'Bonsoir ! Mama Judi a préparé ta révision.' : 'Good evening! Mama Judi prepared your revision.')
        })
      })
      setSent(true)
    } finally {
      setLoading(false)
    }
  }

  if (sent) {
    return (
      <div style={{ textAlign: 'center', padding: '40px 20px' }}>
        <div style={{ fontSize: 64, marginBottom: 16 }}>✅</div>
        <div style={{ fontSize: 18, fontWeight: 900, color: P.green }}>{t.sent}</div>
      </div>
    )
  }

  const selectedSubject = subjects.find(s => s.id === subjectId)

  return (
    <div>
      {/* Choix enfants */}
      <div style={{ marginBottom: 20 }}>
        <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{t.choose_children}</div>
        <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
          {brief.children.map(c => (
            <button key={c.id} onClick={() => toggleChild(c.id)} style={{
              padding: '8px 16px', borderRadius: 20, border: 'none', cursor: 'pointer',
              background: selectedChildren.includes(c.id) ? P.green : P.border,
              color: selectedChildren.includes(c.id) ? 'white' : P.soft,
              fontWeight: 800, fontSize: 14
            }}>{c.name}</button>
          ))}
        </div>
      </div>

      {/* Choix matiere */}
      <div style={{ marginBottom: 16 }}>
        <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{t.choose_subject}</div>
        <select
          value={subjectId ?? ''}
          onChange={e => { setSubjectId(e.target.value ? Number(e.target.value) : null); setUnitId(null) }}
          style={{ width: '100%', padding: '12px', borderRadius: 12, border: `2px solid ${P.border}`, fontSize: 15, background: P.card, color: P.dark }}
        >
          <option value="">{t.auto}</option>
          {subjects.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
        </select>
      </div>

      {/* Choix unite */}
      {selectedSubject && selectedSubject.units.length > 0 && (
        <div style={{ marginBottom: 16 }}>
          <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{t.choose_unit}</div>
          <select
            value={unitId ?? ''}
            onChange={e => setUnitId(e.target.value ? Number(e.target.value) : null)}
            style={{ width: '100%', padding: '12px', borderRadius: 12, border: `2px solid ${P.border}`, fontSize: 15, background: P.card, color: P.dark }}
          >
            <option value="">{t.auto}</option>
            {selectedSubject.units.map(u => <option key={u.id} value={u.id}>{u.name}</option>)}
          </select>
        </div>
      )}

      {/* Message vocal */}
      <div style={{ marginBottom: 24 }}>
        <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{t.message}</div>
        <textarea
          value={message}
          onChange={e => setMessage(e.target.value)}
          placeholder={t.message_ph}
          rows={3}
          style={{ width: '100%', padding: '12px', borderRadius: 12, border: `2px solid ${P.border}`, fontSize: 14, background: P.card, color: P.dark, resize: 'none', boxSizing: 'border-box' }}
        />
      </div>

      {/* Bouton envoyer */}
      <button onClick={send} disabled={loading || selectedChildren.length === 0} style={{
        width: '100%', padding: '16px', borderRadius: 16, border: 'none',
        background: selectedChildren.length === 0 ? P.border : P.green,
        color: selectedChildren.length === 0 ? P.soft : 'white',
        fontSize: 17, fontWeight: 900, cursor: selectedChildren.length === 0 ? 'not-allowed' : 'pointer'
      }}>
        {loading ? '...' : t.send}
      </button>
    </div>
  )
}

// ── Ecran Duel ────────────────────────────────────────────────────────────────
function DuelScreen({ brief, t }: { brief: Brief; t: typeof T['fr'] }) {
  const [child1, setChild1] = useState<number | null>(null)
  const [child2, setChild2] = useState<number | null>(null)
  const [nbEx, setNbEx] = useState(10)
  const [duration, setDuration] = useState(300)
  const [sent, setSent] = useState(false)
  const [loading, setLoading] = useState(false)

  const start = async () => {
    if (!child1 || !child2 || child1 === child2) return
    setLoading(true)
    try {
      await fetch('/api/duels', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ child1_id: child1, child2_id: child2, nb_exercises: nbEx, duration_seconds: duration })
      })
      setSent(true)
    } finally {
      setLoading(false)
    }
  }

  if (sent) {
    return (
      <div style={{ textAlign: 'center', padding: '40px 20px' }}>
        <div style={{ fontSize: 64, marginBottom: 16 }}>⚔️</div>
        <div style={{ fontSize: 18, fontWeight: 900, color: P.green }}>{t.duel_sent}</div>
      </div>
    )
  }

  const sel = (label: string, val: number | null, set: (v: number) => void, exclude?: number | null) => (
    <div style={{ marginBottom: 16 }}>
      <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{label}</div>
      <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
        {brief.children.filter(c => c.id !== exclude).map(c => (
          <button key={c.id} onClick={() => set(c.id)} style={{
            padding: '10px 18px', borderRadius: 20, border: 'none', cursor: 'pointer',
            background: val === c.id ? P.rose : P.border,
            color: val === c.id ? 'white' : P.soft,
            fontWeight: 800, fontSize: 14
          }}>{c.name}</button>
        ))}
      </div>
    </div>
  )

  return (
    <div>
      {sel(t.duel_child1, child1, setChild1)}
      {sel(t.duel_child2, child2, setChild2, child1)}

      <div style={{ marginBottom: 16 }}>
        <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{t.duel_exercises}</div>
        <div style={{ display: 'flex', gap: 8 }}>
          {[5, 10, 15, 20].map(n => (
            <button key={n} onClick={() => setNbEx(n)} style={{
              flex: 1, padding: '10px', borderRadius: 12, border: 'none', cursor: 'pointer',
              background: nbEx === n ? P.amber : P.border,
              color: nbEx === n ? 'white' : P.soft,
              fontWeight: 800, fontSize: 15
            }}>{n}</button>
          ))}
        </div>
      </div>

      <div style={{ marginBottom: 24 }}>
        <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{t.duel_duration}</div>
        <div style={{ display: 'flex', gap: 8 }}>
          {[{ v: 180, l: `3 ${t.minutes}` }, { v: 300, l: `5 ${t.minutes}` }, { v: 600, l: `10 ${t.minutes}` }, { v: 900, l: `15 ${t.minutes}` }].map(d => (
            <button key={d.v} onClick={() => setDuration(d.v)} style={{
              flex: 1, padding: '10px', borderRadius: 12, border: 'none', cursor: 'pointer',
              background: duration === d.v ? P.amber : P.border,
              color: duration === d.v ? 'white' : P.soft,
              fontWeight: 800, fontSize: 13
            }}>{d.l}</button>
          ))}
        </div>
      </div>

      <button onClick={start} disabled={!child1 || !child2 || child1 === child2 || loading} style={{
        width: '100%', padding: '16px', borderRadius: 16, border: 'none',
        background: !child1 || !child2 || child1 === child2 ? P.border : P.rose,
        color: !child1 || !child2 || child1 === child2 ? P.soft : 'white',
        fontSize: 17, fontWeight: 900, cursor: !child1 || !child2 || child1 === child2 ? 'not-allowed' : 'pointer'
      }}>
        {loading ? '...' : t.start_duel}
      </button>
    </div>
  )
}

// ── App principale ────────────────────────────────────────────────────────────
export default function MamaJudiApp() {
  const [lang, setLang] = useState<'fr' | 'en'>('fr')
  const [screen, setScreen] = useState<'home' | 'revision' | 'duel'>('home')
  const [brief, setBrief] = useState<Brief | null>(null)
  const [loading, setLoading] = useState(true)
  const t = T[lang]
  const hour = new Date().getHours()

  useEffect(() => {
    fetch('/api/mama/brief')
      .then(r => r.json())
      .then(setBrief)
      .finally(() => setLoading(false))
  }, [])

  const shell = {
    minHeight: '100vh',
    background: P.bg,
    fontFamily: 'Nunito, system-ui, sans-serif',
    maxWidth: 480,
    margin: '0 auto',
  }

  if (loading) return (
    <div style={{ ...shell, display: 'flex', alignItems: 'center', justifyContent: 'center', flexDirection: 'column' as const, gap: 16 }}>
      <MamaJudiAvatar size={80} />
      <div style={{ color: P.soft, fontSize: 16 }}>Chargement...</div>
    </div>
  )

  return (
    <div style={shell}>
      {/* Header */}
      <div style={{ background: P.rose, padding: '16px 20px' }}>
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
            {screen !== 'home' && (
              <button onClick={() => setScreen('home')} style={{ background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: 10, padding: '6px 12px', color: 'white', fontWeight: 800, cursor: 'pointer', fontSize: 13 }}>
                {t.back}
              </button>
            )}
            {screen === 'home' && <MamaJudiAvatar size={44} />}
            <div>
              <div style={{ fontSize: 13, fontWeight: 900, color: 'white', opacity: 0.85 }}>{t.title}</div>
              {screen === 'home' && <div style={{ fontSize: 18, fontWeight: 900, color: 'white' }}>{t.greeting(hour)}</div>}
            </div>
          </div>
          {/* Toggle langue */}
          <button onClick={() => setLang(l => l === 'fr' ? 'en' : 'fr')} style={{
            background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: 20,
            padding: '6px 14px', color: 'white', fontWeight: 900, cursor: 'pointer', fontSize: 13
          }}>
            {lang === 'fr' ? '🇬🇧 EN' : '🇫🇷 FR'}
          </button>
        </div>
        {/* Summary badges */}
        {screen === 'home' && brief && (
          <div style={{ display: 'flex', gap: 8, marginTop: 12 }}>
            <div style={{ background: 'rgba(255,255,255,0.2)', borderRadius: 12, padding: '8px 12px', flex: 1, textAlign: 'center' }}>
              <div style={{ fontSize: 20, fontWeight: 900, color: 'white' }}>{brief.summary.total_exercises}</div>
              <div style={{ fontSize: 10, color: 'rgba(255,255,255,0.8)' }}>exercices</div>
            </div>
            <div style={{ background: 'rgba(255,255,255,0.2)', borderRadius: 12, padding: '8px 12px', flex: 1, textAlign: 'center' }}>
              <div style={{ fontSize: 20, fontWeight: 900, color: 'white' }}>{brief.summary.active_today}</div>
              <div style={{ fontSize: 10, color: 'rgba(255,255,255,0.8)' }}>actifs</div>
            </div>
            <div style={{ background: 'rgba(255,255,255,0.2)', borderRadius: 12, padding: '8px 12px', flex: 1, textAlign: 'center' }}>
              <div style={{ fontSize: 20, fontWeight: 900, color: 'white' }}>{brief.summary.needs_attention.length}</div>
              <div style={{ fontSize: 10, color: 'rgba(255,255,255,0.8)' }}>⚠️ attention</div>
            </div>
          </div>
        )}
      </div>

      {/* Contenu */}
      <div style={{ padding: '20px 16px', paddingBottom: 80 }}>
        {screen === 'home' && brief && (
          <>
            <BriefScreen brief={brief} t={t} />
            {/* Actions */}
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginTop: 8 }}>
              <button onClick={() => setScreen('revision')} style={{
                padding: '20px 12px', borderRadius: 20, border: 'none', cursor: 'pointer',
                background: P.green, color: 'white', fontWeight: 900, fontSize: 15,
                display: 'flex', flexDirection: 'column' as const, alignItems: 'center', gap: 8
              }}>
                <span style={{ fontSize: 32 }}>📚</span>
                {t.revision}
              </button>
              <button onClick={() => setScreen('duel')} style={{
                padding: '20px 12px', borderRadius: 20, border: 'none', cursor: 'pointer',
                background: P.rose, color: 'white', fontWeight: 900, fontSize: 15,
                display: 'flex', flexDirection: 'column' as const, alignItems: 'center', gap: 8
              }}>
                <span style={{ fontSize: 32 }}>⚔️</span>
                {t.duel}
              </button>
            </div>
          </>
        )}
        {screen === 'revision' && brief && <RevisionScreen brief={brief} t={t} lang={lang} />}
        {screen === 'duel' && brief && <DuelScreen brief={brief} t={t} />}
      </div>
    </div>
  )
}
