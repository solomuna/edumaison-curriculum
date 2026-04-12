// MamaJudiApp.tsx — Interface Mama Judi v3 (Mobile S9+ + Desktop sidebar)
import { useState, useEffect, useRef, useCallback } from 'react'

// ── BREAKPOINT HOOK ───────────────────────────────────────────────────────────
function useIsDesktop() {
  const [desk, setDesk] = useState(() => window.innerWidth >= 1024)
  useEffect(() => {
    const h = () => setDesk(window.innerWidth >= 1024)
    window.addEventListener('resize', h)
    return () => window.removeEventListener('resize', h)
  }, [])
  return desk
}

// ── TYPES ─────────────────────────────────────────────────────────────────────
interface ChildBrief {
  id: number; name: string; level: string; level_id: number
  today_count: number; today_avg: number; subjects_today: string[]
  weak_subjects: { name: string; avg: number }[]; needs_attention: boolean
}
interface Brief {
  date: string; children: ChildBrief[]
  summary: { total_exercises: number; active_today: number; needs_attention: string[] }
}
interface Subject { id: number; name: string; units: { id: number; name: string }[] }
type Screen = 'home' | 'revision' | 'duel' | 'profile' | 'books'

// ── PALETTE ───────────────────────────────────────────────────────────────────
const P = {
  bg: '#E8DCC8', card: '#F0E8D8', white: '#FFFDF8',
  green: '#1D6B2A', brown: '#6B4226',
  amber: '#F59E0B', red: '#CE1126', soft: '#7A6050', dark: '#3D2B1F', border: '#D0C8B8',
}

// ── TRADUCTIONS ───────────────────────────────────────────────────────────────
const T = {
  fr: {
    title: 'Espace Mama Judi',
    greeting: (h: number) => h < 12 ? 'Bonjour !' : h < 18 ? 'Bon après-midi !' : 'Bonsoir !',
    brief: 'Résumé du jour', revision: 'Révision du soir', duel: 'Lancer un duel',
    exercises_today: "exercices aujourd'hui", avg: 'moy.', weak: 'À renforcer',
    no_activity: "Pas d'activité aujourd'hui", send: 'Envoyer la révision',
    choose_subject: 'Choisir les matières', choose_unit: 'Choisir une unité',
    choose_children: 'Pour qui ?', message: 'Message vocal (optionnel)',
    message_ph: 'Ex: Ce soir on révise les fractions !', auto: 'Laisser EduMaison choisir',
    duel_child1: 'Premier enfant', duel_child2: 'Deuxième enfant',
    duel_exercises: "Nombre d'exercices", duel_duration: 'Durée',
    start_duel: 'Démarrer le duel !', sent: 'Envoyé ! Les enfants vont recevoir la révision.',
    duel_sent: 'Duel créé ! Les enfants vont être notifiés.', back: '← Retour',
    minutes: 'min', pin_title: 'Espace Mama Judi', pin_sub: 'Entrez votre code PIN',
    pin_error: 'Code incorrect', profile: 'Mon Profil',
    change_photo: 'Changer ma photo', change_pin: 'Changer mon PIN',
    current_pin: 'PIN actuel', new_pin: 'Nouveau PIN (4 chiffres)',
    confirm_pin: 'Confirmer le nouveau PIN', save: 'Enregistrer',
    pin_mismatch: 'Les PINs ne correspondent pas',
    pin_wrong: 'PIN actuel incorrect', pin_success: 'PIN modifié !',
    photo_success: 'Photo mise à jour !', logout: 'Quitter',
    nav_brief: 'Résumé', nav_revision: 'Révision', nav_duel: 'Duel', nav_books: 'Livres', nav_profile: 'Profil',
    today: "Aujourd'hui", active: 'actifs', attention: 'attention',
  },
  en: {
    title: 'Mama Judi Space',
    greeting: (h: number) => h < 12 ? 'Good morning!' : h < 18 ? 'Good afternoon!' : 'Good evening!',
    brief: "Today's Summary", revision: 'Evening Revision', duel: 'Start a Duel',
    exercises_today: 'exercises today', avg: 'avg', weak: 'Needs work',
    no_activity: 'No activity today', send: 'Send Revision',
    choose_subject: 'Choose subjects', choose_unit: 'Choose a unit',
    choose_children: 'For who?', message: 'Voice message (optional)',
    message_ph: 'e.g. Tonight we revise fractions!', auto: 'Let EduMaison choose',
    duel_child1: 'First child', duel_child2: 'Second child',
    duel_exercises: 'Number of exercises', duel_duration: 'Duration',
    start_duel: 'Start the Duel!', sent: 'Sent! Children will receive the revision.',
    duel_sent: 'Duel created! Children will be notified.', back: '← Back',
    minutes: 'min', pin_title: 'Mama Judi Space', pin_sub: 'Enter your PIN code',
    pin_error: 'Incorrect PIN', profile: 'My Profile',
    change_photo: 'Change my photo', change_pin: 'Change my PIN',
    current_pin: 'Current PIN', new_pin: 'New PIN (4 digits)',
    confirm_pin: 'Confirm new PIN', save: 'Save',
    pin_mismatch: 'PINs do not match',
    pin_wrong: 'Current PIN incorrect', pin_success: 'PIN updated!',
    photo_success: 'Photo updated!', logout: 'Logout',
    nav_brief: 'Summary', nav_revision: 'Revision', nav_duel: 'Duel', nav_books: 'Books', nav_profile: 'Profile',
    today: 'Today', active: 'active', attention: 'attention',
  }
}

// ── AVATAR ────────────────────────────────────────────────────────────────────
function MamaAvatar({ size = 80, src }: { size?: number; src?: string | null }) {
  if (src) return <img src={src} style={{ width: size, height: size, borderRadius: '50%', objectFit: 'cover', border: '3px solid rgba(255,255,255,.35)', flexShrink: 0 }} />
  return (
    <svg viewBox="0 0 84 104" width={size} height={size * 104 / 84} xmlns="http://www.w3.org/2000/svg" style={{ flexShrink: 0 }}>
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
      <path d="M14 92 Q12 76 42 72 Q72 76 70 92 L68 104 L16 104 Z" fill="#6B4226"/>
    </svg>
  )
}

// ── PIN SCREEN ────────────────────────────────────────────────────────────────
function PinScreen({ onUnlock, t, avatarSrc }: { onUnlock: () => void; t: typeof T['fr']; avatarSrc?: string | null }) {
  const [pin, setPin] = useState('')
  const [error, setError] = useState(false)

  const press = useCallback((d: string) => {
    setPin(prev => {
      if (prev.length >= 4) return prev
      const next = prev + d
      if (next.length === 4) {
        fetch('/api/mama/profile/verify-pin', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ pin: next }) })
          .then(r => r.json())
          .then(res => {
            if (res.valid) setTimeout(() => onUnlock(), 200)
            else { setError(true); setTimeout(() => { setPin(''); setError(false) }, 800) }
          })
          .catch(() => {
            if (next === '0505') setTimeout(() => onUnlock(), 200)
            else { setError(true); setTimeout(() => { setPin(''); setError(false) }, 800) }
          })
      }
      return next
    })
  }, [onUnlock])

  const keys = ['1','2','3','4','5','6','7','8','9','','0','\u232b']

  useEffect(() => {
    const h = (e: KeyboardEvent) => {
      if (e.key >= '0' && e.key <= '9') press(e.key)
      else if (e.key === 'Backspace') setPin(p => p.slice(0, -1))
    }
    window.addEventListener('keydown', h)
    return () => window.removeEventListener('keydown', h)
  }, [press])

  return (
    <div style={{ minHeight: '100vh', background: P.bg, display: 'flex', flexDirection: 'column' as const, alignItems: 'center', justifyContent: 'center', padding: 24, fontFamily: 'Nunito, system-ui, sans-serif' }}>
      <MamaAvatar size={80} src={avatarSrc} />
      <div style={{ fontSize: 22, fontWeight: 900, color: P.dark, marginTop: 12, marginBottom: 4 }}>{t.pin_title}</div>
      <div style={{ fontSize: 14, color: P.soft, marginBottom: 36 }}>{t.pin_sub}</div>
      <div style={{ display: 'flex', gap: 16, marginBottom: 40 }}>
        {[0,1,2,3].map(i => (
          <div key={i} style={{ width: 16, height: 16, borderRadius: '50%', background: pin.length > i ? (error ? P.red : P.green) : 'transparent', border: '2px solid ' + (error ? P.red : P.green), transition: 'background .2s' }}/>
        ))}
      </div>
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 72px)', gap: 12 }}>
        {keys.map((k, i) => (
          <button key={i} onClick={() => k === '\u232b' ? setPin(p => p.slice(0,-1)) : k ? press(k) : undefined}
            style={{ height: 72, borderRadius: 16, border: '1.5px solid ' + P.border, background: k ? P.white : 'transparent', fontSize: k === '\u232b' ? 20 : 24, fontWeight: 900, color: P.dark, cursor: k ? 'pointer' : 'default', fontFamily: 'Nunito, sans-serif' }}>
            {k}
          </button>
        ))}
      </div>
      {error && <div style={{ marginTop: 20, color: P.red, fontWeight: 800, fontSize: 13 }}>{t.pin_error}</div>}
    </div>
  )
}

// ── BRIEF ─────────────────────────────────────────────────────────────────────
function BriefScreen({ brief, t }: { brief: Brief; t: typeof T['fr'] }) {
  return (
    <div>
      <div style={{ fontSize: 13, fontWeight: 900, color: P.soft, textTransform: 'uppercase' as const, letterSpacing: 1, marginBottom: 14 }}>{t.brief}</div>
      {brief.children.map(c => (
        <div key={c.id} style={{ background: P.white, borderRadius: 18, padding: 16, marginBottom: 12, border: `1.5px solid ${c.needs_attention ? '#FECACA' : P.border}`, background: c.needs_attention ? '#FFF5F5' : P.white }}>
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 8 }}>
            <div style={{ fontSize: 16, fontWeight: 900, color: P.dark }}>{c.name}</div>
            <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
              <div style={{ fontSize: 11, color: P.soft }}>{c.level}</div>
              {c.needs_attention && <span style={{ background: '#FEE2E2', color: P.red, borderRadius: 20, padding: '2px 8px', fontSize: 11, fontWeight: 800 }}>⚠️</span>}
            </div>
          </div>
          {c.today_count > 0 ? (
            <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' as const }}>
              <span style={{ background: P.green, color: 'white', borderRadius: 20, padding: '3px 10px', fontSize: 12, fontWeight: 800 }}>{c.today_count} {t.exercises_today}</span>
              <span style={{ background: P.amber + '22', color: P.amber, borderRadius: 20, padding: '3px 10px', fontSize: 12, fontWeight: 800 }}>{t.avg} {c.today_avg}%</span>
              {c.subjects_today.map(s => <span key={s} style={{ background: P.border, color: P.soft, borderRadius: 20, padding: '3px 10px', fontSize: 11 }}>{s}</span>)}
            </div>
          ) : (
            <div style={{ fontSize: 13, color: P.red, fontWeight: 700 }}>⚠️ {t.no_activity}</div>
          )}
          {c.weak_subjects.length > 0 && (
            <div style={{ marginTop: 8 }}>
              <div style={{ fontSize: 11, color: P.soft, marginBottom: 4 }}>{t.weak} :</div>
              <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap' as const }}>
                {c.weak_subjects.map(w => (
                  <span key={w.name} style={{ background: w.avg < 7 ? P.red + '15' : P.amber + '15', color: w.avg < 7 ? P.red : P.amber, borderRadius: 20, padding: '2px 8px', fontSize: 11, fontWeight: 700 }}>{w.name} {w.avg}/20</span>
                ))}
              </div>
            </div>
          )}
        </div>
      ))}
    </div>
  )
}

// ── REVISION ──────────────────────────────────────────────────────────────────
function RevisionScreen({ brief, t, lang }: { brief: Brief; t: typeof T['fr']; lang: 'fr' | 'en' }) {
  const [selectedChildren, setSelectedChildren] = useState<number[]>(brief.children.map(c => c.id))
  const [subjectIds, setSubjectIds] = useState<number[]>([])
  const [unitId, setUnitId] = useState<number | null>(null)
  const [message, setMessage] = useState('')
  const [subjects, setSubjects] = useState<Subject[]>([])
  const [sent, setSent] = useState(false)
  const [loading, setLoading] = useState(false)

  useEffect(() => {
    const child = brief.children.find(c => selectedChildren.includes(c.id))
    if (child) fetch(`/api/mama/subjects/${child.level_id}`).then(r => r.json()).then(setSubjects).catch(() => {})
  }, [selectedChildren])

  const toggleChild = (id: number) => setSelectedChildren(prev => prev.includes(id) ? prev.filter(x => x !== id) : [...prev, id])
  const toggleSubject = (id: number) => { setSubjectIds(prev => prev.includes(id) ? prev.filter(x => x !== id) : [...prev, id]); setUnitId(null) }

  const send = async () => {
    if (!selectedChildren.length) return
    setLoading(true)
    try {
      await fetch('/api/evening-sessions', {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ child_ids: selectedChildren, subject_ids: subjectIds.length > 0 ? subjectIds : null, subject_id: subjectIds.length === 1 ? subjectIds[0] : null, unit_id: unitId, theme_source: subjectIds.length > 0 ? 'manual' : 'auto', mama_judi_message: message || (lang === 'fr' ? 'Bonsoir ! Mama Judi a préparé ta révision.' : 'Good evening! Mama Judi prepared your revision.') })
      })
      setSent(true)
    } finally { setLoading(false) }
  }

  if (sent) return (
    <div style={{ textAlign: 'center', padding: '60px 20px' }}>
      <div style={{ fontSize: 64, marginBottom: 16 }}>✅</div>
      <div style={{ fontSize: 20, fontWeight: 900, color: P.green, marginBottom: 8 }}>{t.sent}</div>
      <button onClick={() => setSent(false)} style={{ marginTop: 16, padding: '10px 24px', borderRadius: 20, border: 'none', background: P.green, color: 'white', fontFamily: 'Nunito, sans-serif', fontSize: 14, fontWeight: 800, cursor: 'pointer' }}>{t.back}</button>
    </div>
  )

  const singleSubject = subjectIds.length === 1 ? subjects.find(s => s.id === subjectIds[0]) : null
  const btnStyle = (active: boolean) => ({ padding: '8px 14px', borderRadius: 20, border: 'none', cursor: 'pointer', background: active ? P.green : P.border, color: active ? 'white' : P.soft, fontWeight: 800, fontSize: 13, fontFamily: 'Nunito, sans-serif' })

  return (
    <div>
      <div style={{ marginBottom: 20 }}>
        <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{t.choose_children}</div>
        <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' as const }}>
          {brief.children.map(c => <button key={c.id} onClick={() => toggleChild(c.id)} style={btnStyle(selectedChildren.includes(c.id))}>{c.name}</button>)}
        </div>
      </div>
      <div style={{ marginBottom: 16 }}>
        <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>
          {t.choose_subject}
          {subjectIds.length > 0 && <span style={{ marginLeft: 8, background: P.green, color: 'white', borderRadius: 20, padding: '2px 10px', fontSize: 11 }}>{subjectIds.length}</span>}
        </div>
        <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' as const }}>
          {subjects.map(s => <button key={s.id} onClick={() => toggleSubject(s.id)} style={btnStyle(subjectIds.includes(s.id))}>{s.name}</button>)}
        </div>
      </div>
      {singleSubject && singleSubject.units.length > 0 && (
        <div style={{ marginBottom: 16 }}>
          <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{t.choose_unit}</div>
          <select value={unitId ?? ''} onChange={e => setUnitId(e.target.value ? Number(e.target.value) : null)}
            style={{ width: '100%', padding: 12, borderRadius: 12, border: `1.5px solid ${P.border}`, fontSize: 14, background: P.card, color: P.dark, fontFamily: 'Nunito, sans-serif' }}>
            <option value="">{t.auto}</option>
            {singleSubject.units.map(u => <option key={u.id} value={u.id}>{u.name}</option>)}
          </select>
        </div>
      )}
      <div style={{ marginBottom: 20 }}>
        <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{t.message}</div>
        <textarea value={message} onChange={e => setMessage(e.target.value)} placeholder={t.message_ph} rows={3}
          style={{ width: '100%', padding: 12, borderRadius: 12, border: `1.5px solid ${P.border}`, fontSize: 14, background: P.card, color: P.dark, resize: 'none' as const, fontFamily: 'Nunito, sans-serif' }}/>
      </div>
      <button onClick={send} disabled={loading || !selectedChildren.length}
        style={{ width: '100%', padding: 15, borderRadius: 16, border: 'none', background: !selectedChildren.length ? P.border : P.green, color: !selectedChildren.length ? P.soft : 'white', fontSize: 16, fontWeight: 900, cursor: !selectedChildren.length ? 'not-allowed' : 'pointer', fontFamily: 'Nunito, sans-serif' }}>
        {loading ? '...' : t.send}
      </button>
    </div>
  )
}

// ── DUEL ──────────────────────────────────────────────────────────────────────
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
      await fetch('/api/duels', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ child1_id: child1, child2_id: child2, nb_exercises: nbEx, duration_seconds: duration }) })
      setSent(true)
    } finally { setLoading(false) }
  }

  if (sent) return (
    <div style={{ textAlign: 'center', padding: '60px 20px' }}>
      <div style={{ fontSize: 64, marginBottom: 16 }}>⚔️</div>
      <div style={{ fontSize: 20, fontWeight: 900, color: P.green }}>{t.duel_sent}</div>
      <button onClick={() => setSent(false)} style={{ marginTop: 20, padding: '10px 24px', borderRadius: 20, border: 'none', background: P.green, color: 'white', fontFamily: 'Nunito, sans-serif', fontSize: 14, fontWeight: 800, cursor: 'pointer' }}>{t.back}</button>
    </div>
  )

  const sel = (label: string, val: number | null, set: (v: number) => void, exclude?: number | null) => (
    <div style={{ marginBottom: 18 }}>
      <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{label}</div>
      <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' as const }}>
        {brief.children.filter(c => c.id !== exclude).map(c => (
          <button key={c.id} onClick={() => set(c.id)} style={{ padding: '10px 18px', borderRadius: 20, border: 'none', cursor: 'pointer', background: val === c.id ? P.brown : P.border, color: val === c.id ? 'white' : P.soft, fontWeight: 800, fontSize: 14, fontFamily: 'Nunito, sans-serif' }}>{c.name}</button>
        ))}
      </div>
    </div>
  )

  return (
    <div>
      {sel(t.duel_child1, child1, setChild1)}
      {sel(t.duel_child2, child2, setChild2, child1)}
      <div style={{ marginBottom: 18 }}>
        <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{t.duel_exercises}</div>
        <div style={{ display: 'flex', gap: 8 }}>
          {[5, 10, 15, 20].map(n => <button key={n} onClick={() => setNbEx(n)} style={{ flex: 1, padding: 10, borderRadius: 12, border: 'none', cursor: 'pointer', background: nbEx === n ? P.amber : P.border, color: nbEx === n ? 'white' : P.soft, fontWeight: 800, fontSize: 15, fontFamily: 'Nunito, sans-serif' }}>{n}</button>)}
        </div>
      </div>
      <div style={{ marginBottom: 24 }}>
        <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{t.duel_duration}</div>
        <div style={{ display: 'flex', gap: 8 }}>
          {[{ v: 180, l: `3 ${t.minutes}` }, { v: 300, l: `5 ${t.minutes}` }, { v: 600, l: `10 ${t.minutes}` }, { v: 900, l: `15 ${t.minutes}` }].map(d => (
            <button key={d.v} onClick={() => setDuration(d.v)} style={{ flex: 1, padding: 10, borderRadius: 12, border: 'none', cursor: 'pointer', background: duration === d.v ? P.amber : P.border, color: duration === d.v ? 'white' : P.soft, fontWeight: 800, fontSize: 13, fontFamily: 'Nunito, sans-serif' }}>{d.l}</button>
          ))}
        </div>
      </div>
      <button onClick={start} disabled={!child1 || !child2 || child1 === child2 || loading}
        style={{ width: '100%', padding: 15, borderRadius: 16, border: 'none', background: !child1 || !child2 || child1 === child2 ? P.border : P.brown, color: !child1 || !child2 || child1 === child2 ? P.soft : 'white', fontSize: 16, fontWeight: 900, cursor: !child1 || !child2 || child1 === child2 ? 'not-allowed' : 'pointer', fontFamily: 'Nunito, sans-serif' }}>
        {loading ? '...' : t.start_duel}
      </button>
    </div>
  )
}

// ── PROFIL ────────────────────────────────────────────────────────────────────
function ProfileScreen({ t, avatarSrc, onAvatarChange }: { t: typeof T['fr']; avatarSrc: string | null; onAvatarChange: (url: string) => void }) {
  const fileRef = useRef<HTMLInputElement>(null)
  const [currentPin, setCurrentPin] = useState('')
  const [newPin, setNewPin] = useState('')
  const [confirmPin, setConfirmPin] = useState('')
  const [pinMsg, setPinMsg] = useState('')
  const [pinOk, setPinOk] = useState(false)
  const [photoMsg, setPhotoMsg] = useState('')
  const [uploading, setUploading] = useState(false)
  const [preview, setPreview] = useState<string | null>(avatarSrc)

  const handlePhoto = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0]
    if (!file) return
    setPreview(URL.createObjectURL(file))
    setUploading(true)
    const form = new FormData()
    form.append('avatar', file)
    try {
      const r = await fetch('/api/mama/profile/avatar', { method: 'POST', body: form })
      const d = await r.json()
      if (d.success) { onAvatarChange('http://192.168.100.106/storage/' + d.avatar); setPhotoMsg(t.photo_success) }
    } catch (_) {} finally { setUploading(false) }
  }

  const handlePin = async () => {
    if (newPin !== confirmPin) { setPinMsg(t.pin_mismatch); setPinOk(false); return }
    if (newPin.length !== 4 || !/^\d+$/.test(newPin)) { setPinMsg(t.new_pin); setPinOk(false); return }
    const vr = await fetch('/api/mama/profile/verify-pin', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ pin: currentPin }) })
    const vd = await vr.json()
    if (!vd.valid) { setPinMsg(t.pin_wrong); setPinOk(false); return }
    const r = await fetch('/api/mama/profile/pin', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ pin: newPin }) })
    const d = await r.json()
    if (d.success) { setPinMsg(t.pin_success); setPinOk(true); setCurrentPin(''); setNewPin(''); setConfirmPin('') }
  }

  const inputStyle = { width: '100%', padding: '11px 14px', borderRadius: 12, border: '1.5px solid ' + P.border, fontFamily: 'Nunito, sans-serif', fontSize: 14, background: P.card, color: P.dark, marginBottom: 10 }

  return (
    <div>
      <div style={{ background: P.white, borderRadius: 20, padding: '20px 18px', marginBottom: 16, border: '1.5px solid ' + P.border }}>
        <div style={{ fontSize: 14, fontWeight: 900, color: P.dark, marginBottom: 14 }}>{t.change_photo}</div>
        <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
          <div style={{ position: 'relative' as const, flexShrink: 0 }}>
            <MamaAvatar size={64} src={preview} />
            {uploading && <div style={{ position: 'absolute' as const, inset: 0, borderRadius: '50%', background: 'rgba(0,0,0,.4)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: 'white' }}>⏳</div>}
          </div>
          <button onClick={() => fileRef.current?.click()}
            style={{ flex: 1, padding: '11px', borderRadius: 14, border: '2px dashed ' + P.border, background: P.bg, cursor: 'pointer', fontSize: 13, fontWeight: 800, color: P.soft, fontFamily: 'Nunito, sans-serif' }}>
            📷 {t.change_photo}
          </button>
          <input ref={fileRef} type="file" accept="image/*" style={{ display: 'none' }} onChange={handlePhoto} />
        </div>
        {photoMsg && <div style={{ marginTop: 10, fontSize: 13, fontWeight: 800, color: P.green }}>{photoMsg}</div>}
      </div>
      <div style={{ background: P.white, borderRadius: 20, padding: '20px 18px', border: '1.5px solid ' + P.border }}>
        <div style={{ fontSize: 14, fontWeight: 900, color: P.dark, marginBottom: 14 }}>{t.change_pin}</div>
        <input type="password" inputMode="numeric" maxLength={4} placeholder={t.current_pin} value={currentPin} onChange={e => setCurrentPin(e.target.value)} style={inputStyle} />
        <input type="password" inputMode="numeric" maxLength={4} placeholder={t.new_pin} value={newPin} onChange={e => setNewPin(e.target.value)} style={inputStyle} />
        <input type="password" inputMode="numeric" maxLength={4} placeholder={t.confirm_pin} value={confirmPin} onChange={e => setConfirmPin(e.target.value)} style={inputStyle} />
        {pinMsg && <div style={{ fontSize: 13, fontWeight: 800, color: pinOk ? P.green : P.red, marginBottom: 10 }}>{pinMsg}</div>}
        <button onClick={handlePin} style={{ width: '100%', padding: 12, borderRadius: 14, border: 'none', background: P.green, color: 'white', fontFamily: 'Nunito, sans-serif', fontSize: 15, fontWeight: 900, cursor: 'pointer' }}>{t.save}</button>
      </div>
    </div>
  )
}

// ── CONTENU PARTAGÉ (mobile + desktop) ───────────────────────────────────────

interface BookRef {
  id: number; book_name: string; page_from: number | null; page_to: number | null
  chapter: string; notes: string; level: string
  subject_id: number; subject_name: string; unit_id: number; unit_name: string
  level_id: number
}

function BooksScreen({ t }: { t: typeof T['fr'] }) {
  const [books, setBooks] = useState<BookRef[]>([])
  const [subjects, setSubjects] = useState<Subject[]>([])
  const [showForm, setShowForm] = useState(false)
  const [loading, setLoading] = useState(true)
  const [form, setForm] = useState({ book_name: '', chapter: '', page_from: '', page_to: '', subject_id: '', unit_id: '', notes: '' })
  const [saving, setSaving] = useState(false)
  const [msg, setMsg] = useState('')

  useEffect(() => {
    fetch('/api/books').then(r => r.json()).then(setBooks).finally(() => setLoading(false))
    fetch('/api/mama/subjects/5').then(r => r.json()).then(setSubjects).catch(() => {})
  }, [])

  const selectedSubject = subjects.find(s => s.id === parseInt(form.subject_id))

  const save = async () => {
    if (!form.book_name || !form.subject_id) return
    setSaving(true)
    try {
      await fetch('/api/books', {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          book_name: form.book_name, chapter: form.chapter,
          page_from: form.page_from ? parseInt(form.page_from) : null,
          page_to: form.page_to ? parseInt(form.page_to) : null,
          subject_id: parseInt(form.subject_id),
          unit_id: form.unit_id ? parseInt(form.unit_id) : null,
          notes: form.notes
        })
      })
      const updated = await fetch('/api/books').then(r => r.json())
      setBooks(updated)
      setForm({ book_name: '', chapter: '', page_from: '', page_to: '', subject_id: '', unit_id: '', notes: '' })
      setShowForm(false)
      setMsg('Livre associe !')
      setTimeout(() => setMsg(''), 2000)
    } finally { setSaving(false) }
  }

  const del = async (id: number) => {
    await fetch('/api/books/' + id, { method: 'DELETE' })
    setBooks(books.filter(b => b.id !== id))
  }

  const inp = { width: '100%', padding: '10px 12px', borderRadius: 12, border: '1.5px solid ' + P.border, fontFamily: 'Nunito, sans-serif', fontSize: 13, background: P.card, color: P.dark, marginBottom: 10 } as React.CSSProperties

  return (
    <div>
      {msg && <div style={{ background: '#D1FAE5', color: P.green, borderRadius: 12, padding: '10px 14px', marginBottom: 14, fontWeight: 800 }}>{msg}</div>}
      <button onClick={() => setShowForm(!showForm)} style={{ width: '100%', padding: 13, borderRadius: 16, border: 'none', background: showForm ? P.border : P.green, color: showForm ? P.soft : 'white', fontFamily: 'Nunito, sans-serif', fontSize: 15, fontWeight: 900, cursor: 'pointer', marginBottom: 16 }}>
        {showForm ? 'Annuler' : '+ Associer un livre'}
      </button>
      {showForm && (
        <div style={{ background: P.white, borderRadius: 20, padding: '18px 16px', marginBottom: 16, border: '1.5px solid ' + P.border }}>
          <input placeholder="Nom du livre" value={form.book_name} onChange={e => setForm({...form, book_name: e.target.value})} style={inp} />
          <input placeholder="Chapitre" value={form.chapter} onChange={e => setForm({...form, chapter: e.target.value})} style={inp} />
          <div style={{ display: 'flex', gap: 8, marginBottom: 10 }}>
            <input type="number" placeholder="Page debut" value={form.page_from} onChange={e => setForm({...form, page_from: e.target.value})} style={{ ...inp, marginBottom: 0, width: '50%' }} />
            <input type="number" placeholder="Page fin" value={form.page_to} onChange={e => setForm({...form, page_to: e.target.value})} style={{ ...inp, marginBottom: 0, width: '50%' }} />
          </div>
          <select value={form.subject_id} onChange={e => setForm({...form, subject_id: e.target.value, unit_id: ''})} style={inp}>
            <option value="">Choisir une matiere...</option>
            {subjects.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
          </select>
          {selectedSubject && selectedSubject.units.length > 0 && (
            <select value={form.unit_id} onChange={e => setForm({...form, unit_id: e.target.value})} style={inp}>
              <option value="">Toutes les unites</option>
              {selectedSubject.units.map(u => <option key={u.id} value={u.id}>{u.name}</option>)}
            </select>
          )}
          <input placeholder="Notes (optionnel)" value={form.notes} onChange={e => setForm({...form, notes: e.target.value})} style={inp} />
          <button onClick={save} disabled={saving || !form.book_name || !form.subject_id} style={{ width: '100%', padding: 12, borderRadius: 14, border: 'none', background: !form.book_name || !form.subject_id ? P.border : P.green, color: 'white', fontFamily: 'Nunito, sans-serif', fontSize: 14, fontWeight: 900, cursor: 'pointer' }}>
            {saving ? '...' : 'Enregistrer'}
          </button>
        </div>
      )}
      {loading && <div style={{ textAlign: 'center', padding: 20, color: P.soft }}>Chargement...</div>}
      {!loading && books.length === 0 && !showForm && (
        <div style={{ textAlign: 'center', padding: '40px 0' }}>
          <div style={{ fontSize: 48, marginBottom: 12 }}>&#128218;</div>
          <div style={{ fontSize: 15, fontWeight: 800, color: P.dark, marginBottom: 6 }}>Aucun livre associe</div>
          <div style={{ fontSize: 13, color: P.soft }}>Associez les livres physiques aux unites EduMaison.</div>
        </div>
      )}
      {books.map(b => (
        <div key={b.id} style={{ background: P.white, borderRadius: 18, padding: '14px 16px', marginBottom: 10, border: '1.5px solid ' + P.border }}>
          <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', marginBottom: 6 }}>
            <div style={{ flex: 1 }}>
              <div style={{ fontSize: 15, fontWeight: 900, color: P.dark }}>{b.book_name}</div>
              {b.chapter && <div style={{ fontSize: 12, color: P.soft }}>{b.chapter}</div>}
            </div>
            <button onClick={() => del(b.id)} style={{ background: '#FEE2E2', color: P.red, border: 'none', borderRadius: 10, padding: '5px 10px', fontSize: 12, fontWeight: 800, cursor: 'pointer', fontFamily: 'Nunito, sans-serif', flexShrink: 0 }}>x</button>
          </div>
          <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap' as const }}>
            <span style={{ background: 'rgba(29,107,42,.1)', color: P.green, borderRadius: 20, padding: '3px 10px', fontSize: 11, fontWeight: 800 }}>{b.subject_name}</span>
            {b.unit_name && <span style={{ background: P.card, color: P.soft, borderRadius: 20, padding: '3px 10px', fontSize: 11 }}>{b.unit_name}</span>}
            {b.page_from && <span style={{ background: P.card, color: P.soft, borderRadius: 20, padding: '3px 10px', fontSize: 11 }}>p.{b.page_from}{b.page_to ? '-'+b.page_to : ''}</span>}
          </div>
          {b.notes && <div style={{ fontSize: 12, color: P.soft, marginTop: 6 }}>{b.notes}</div>}
        </div>
      ))}
    </div>
  )
}

function ScreenContent({ screen, brief, t, lang, avatarSrc, onAvatarChange }: { screen: Screen; brief: Brief | null; t: typeof T['fr']; lang: 'fr'|'en'; avatarSrc: string | null; onAvatarChange: (url: string) => void }) {
  if (!brief && screen !== 'profile') return <div style={{ padding: 40, textAlign: 'center', color: P.soft }}>Chargement...</div>
  if (screen === 'home' && brief) return <BriefScreen brief={brief} t={t} />
  if (screen === 'revision' && brief) return <RevisionScreen brief={brief} t={t} lang={lang} />
  if (screen === 'duel' && brief) return <DuelScreen brief={brief} t={t} />
  if (screen === 'books') return <BooksScreen t={t} />
  if (screen === 'profile') return <ProfileScreen t={t} avatarSrc={avatarSrc} onAvatarChange={onAvatarChange} />
  return null
}

// ── MOBILE LAYOUT ─────────────────────────────────────────────────────────────
function MobileMama({ t, lang, setLang, brief, screen, setScreen, avatarSrc, onAvatarChange, onLogout }: any) {
  const hour = new Date().getHours()

  const NAV = [
    { id: 'home' as Screen, icon: '📋', label: t.nav_brief },
    { id: 'revision' as Screen, icon: '📚', label: t.nav_revision },
    { id: 'duel' as Screen, icon: '⚔️', label: t.nav_duel },
    { id: 'books' as Screen, icon: '📖', label: t.nav_books },
    { id: 'profile' as Screen, icon: '👤', label: t.nav_profile },
  ]

  return (
    <div style={{ minHeight: '100vh', background: P.bg, fontFamily: 'Nunito, system-ui, sans-serif', maxWidth: 480, margin: '0 auto', display: 'flex', flexDirection: 'column' as const }}>
      {/* Header */}
      <div style={{ background: P.brown, padding: '16px 20px', flexShrink: 0 }}>
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
            <button onClick={() => setScreen('profile')} style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 0 }}>
              <MamaAvatar size={42} src={avatarSrc} />
            </button>
            <div>
              <div style={{ fontSize: 12, color: 'rgba(255,255,255,.7)', fontWeight: 700 }}>{t.title}</div>
              <div style={{ fontSize: 16, fontWeight: 900, color: 'white' }}>{t.greeting(hour)}</div>
            </div>
          </div>
          <div style={{ display: 'flex', gap: 8 }}>
            <button onClick={() => setLang(lang === 'fr' ? 'en' : 'fr')} style={{ background: 'rgba(255,255,255,.15)', border: 'none', borderRadius: 20, padding: '5px 12px', color: 'white', fontWeight: 900, cursor: 'pointer', fontSize: 12, fontFamily: 'Nunito, sans-serif' }}>
              {lang === 'fr' ? 'EN' : 'FR'}
            </button>
            <button onClick={onLogout} style={{ background: 'rgba(255,255,255,.1)', border: '1px solid rgba(255,255,255,.25)', borderRadius: 20, padding: '5px 12px', color: 'rgba(255,255,255,.8)', fontWeight: 700, cursor: 'pointer', fontSize: 12, fontFamily: 'Nunito, sans-serif' }}>{t.logout}</button>
          </div>
        </div>
        {brief && screen === 'home' && (
          <div style={{ display: 'flex', gap: 8, marginTop: 12 }}>
            {[
              { val: brief.summary.total_exercises, label: 'ex.' },
              { val: `${brief.summary.active_today}/${brief.children.length}`, label: t.active },
              { val: brief.summary.needs_attention.length, label: t.attention },
            ].map((s, i) => (
              <div key={i} style={{ background: 'rgba(255,255,255,.15)', borderRadius: 10, padding: '7px 10px', flex: 1, textAlign: 'center' as const }}>
                <div style={{ fontSize: 18, fontWeight: 900, color: 'white' }}>{s.val}</div>
                <div style={{ fontSize: 10, color: 'rgba(255,255,255,.7)' }}>{s.label}</div>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Content */}
      <div style={{ flex: 1, padding: '18px 16px', overflowY: 'auto', paddingBottom: 80 }}>
        <ScreenContent screen={screen} brief={brief} t={t} lang={lang} avatarSrc={avatarSrc} onAvatarChange={onAvatarChange} />
      </div>

      {/* Bottom nav */}
      <div style={{ position: 'fixed', bottom: 0, left: '50%', transform: 'translateX(-50%)', width: '100%', maxWidth: 480, background: P.white, borderTop: '1.5px solid ' + P.border, display: 'flex', zIndex: 100 }}>
        {NAV.map(item => (
          <button key={item.id} onClick={() => setScreen(item.id)}
            style={{ flex: 1, background: 'none', border: 'none', cursor: 'pointer', padding: '10px 0 14px', display: 'flex', flexDirection: 'column' as const, alignItems: 'center', gap: 3, color: screen === item.id ? P.brown : P.soft, fontFamily: 'Nunito, sans-serif' }}>
            <span style={{ fontSize: 20 }}>{item.icon}</span>
            <span style={{ fontSize: 10, fontWeight: screen === item.id ? 900 : 600 }}>{item.label}</span>
          </button>
        ))}
      </div>
    </div>
  )
}

// ── DESKTOP LAYOUT ────────────────────────────────────────────────────────────
function DesktopMama({ t, lang, setLang, brief, screen, setScreen, avatarSrc, onAvatarChange, onLogout }: any) {
  const hour = new Date().getHours()

  const NAV = [
    { id: 'home' as Screen, icon: '📋', label: t.brief },
    { id: 'revision' as Screen, icon: '📚', label: t.revision },
    { id: 'duel' as Screen, icon: '⚔️', label: t.duel },
    { id: 'books' as Screen, icon: '📖', label: t.nav_books },
    { id: 'profile' as Screen, icon: '👤', label: t.profile },
  ]

  const screenTitle = screen === 'books' ? t.nav_books : NAV.find(n => n.id === screen)?.label || t.title

  return (
    <div style={{ display: 'flex', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', background: P.bg }}>
      {/* Sidebar */}
      <div style={{ width: 260, background: P.brown, display: 'flex', flexDirection: 'column' as const, flexShrink: 0, position: 'sticky', top: 0, height: '100vh' }}>
        {/* Avatar + greeting */}
        <div style={{ textAlign: 'center', padding: '28px 20px 20px', borderBottom: '1px solid rgba(255,255,255,.15)' }}>
          <div style={{ fontSize: 11, fontWeight: 900, color: 'rgba(255,255,255,.5)', letterSpacing: 2, marginBottom: 14 }}>EDUMAISON</div>
          <button onClick={() => setScreen('profile')} style={{ background: 'none', border: 'none', cursor: 'pointer', display: 'inline-block' }}>
            <MamaAvatar size={72} src={avatarSrc} />
          </button>
          <div style={{ fontSize: 18, fontWeight: 900, color: 'white', marginTop: 10 }}>Mama Judi</div>
          <div style={{ fontSize: 13, color: 'rgba(255,255,255,.65)', marginTop: 3 }}>{t.greeting(hour)}</div>
        </div>

        {/* Stats */}
        {brief && (
          <div style={{ display: 'flex', gap: 6, padding: '14px 12px', borderBottom: '1px solid rgba(255,255,255,.15)' }}>
            {[
              { label: 'Ex.', val: brief.summary.total_exercises },
              { label: t.active, val: `${brief.summary.active_today}/${brief.children.length}` },
              { label: '⚠️', val: brief.summary.needs_attention.length },
            ].map((s, i) => (
              <div key={i} style={{ flex: 1, background: 'rgba(255,255,255,.1)', borderRadius: 10, padding: '8px 4px', textAlign: 'center' as const }}>
                <div style={{ fontSize: 18, fontWeight: 900, color: 'white' }}>{s.val}</div>
                <div style={{ fontSize: 9, color: 'rgba(255,255,255,.6)', marginTop: 2 }}>{s.label}</div>
              </div>
            ))}
          </div>
        )}

        {/* Nav */}
        <nav style={{ padding: '10px 10px', flex: 1 }}>
          {NAV.map(item => (
            <button key={item.id} onClick={() => setScreen(item.id)}
              style={{ width: '100%', display: 'flex', alignItems: 'center', gap: 12, padding: '12px 14px', borderRadius: 14, border: 'none', cursor: 'pointer', marginBottom: 4, textAlign: 'left' as const, background: screen === item.id ? 'rgba(255,255,255,.2)' : 'transparent', color: screen === item.id ? 'white' : 'rgba(255,255,255,.6)', fontWeight: screen === item.id ? 900 : 600, fontSize: 14, fontFamily: 'Nunito, system-ui, sans-serif' }}>
              <span style={{ fontSize: 18 }}>{item.icon}</span>{item.label}
            </button>
          ))}
        </nav>

        {/* Footer sidebar */}
        <div style={{ padding: '12px', borderTop: '1px solid rgba(255,255,255,.15)', display: 'flex', gap: 8 }}>
          <button onClick={() => setLang(lang === 'fr' ? 'en' : 'fr')}
            style={{ flex: 1, padding: '8px 0', borderRadius: 10, border: '1px solid rgba(255,255,255,.25)', background: 'transparent', color: 'rgba(255,255,255,.75)', fontWeight: 800, cursor: 'pointer', fontSize: 12, fontFamily: 'Nunito, sans-serif' }}>
            {lang === 'fr' ? '🇬🇧 EN' : '🇫🇷 FR'}
          </button>
          <button onClick={onLogout}
            style={{ flex: 1, padding: '8px 0', borderRadius: 10, border: '1px solid rgba(255,255,255,.25)', background: 'transparent', color: 'rgba(255,255,255,.65)', fontWeight: 700, cursor: 'pointer', fontSize: 12, fontFamily: 'Nunito, sans-serif' }}>
            {t.logout}
          </button>
        </div>
      </div>

      {/* Main */}
      <div style={{ flex: 1, overflowY: 'auto', minHeight: '100vh' }}>
        <div style={{ padding: '32px 40px', maxWidth: 800 }}>
          <div style={{ fontSize: 24, fontWeight: 900, color: P.dark, marginBottom: 24 }}>{screenTitle}</div>
          <ScreenContent screen={screen} brief={brief} t={t} lang={lang} avatarSrc={avatarSrc} onAvatarChange={onAvatarChange} />
        </div>
      </div>
    </div>
  )
}

// ── APP PRINCIPALE ────────────────────────────────────────────────────────────
export default function MamaJudiApp() {
  const [lang, setLang] = useState<'fr' | 'en'>('fr')
  const [unlocked, setUnlocked] = useState(false)
  const [avatarSrc, setAvatarSrc] = useState<string | null>(null)
  const [brief, setBrief] = useState<Brief | null>(null)
  const [screen, setScreen] = useState<Screen>('home')
  const isDesktop = useIsDesktop()
  const t = T[lang]

  useEffect(() => {
    fetch('/api/mama/profile').then(r => r.json()).then(d => {
      if (d.avatar) setAvatarSrc('http://192.168.100.106/storage/' + d.avatar)
    }).catch(() => {})
  }, [])

  useEffect(() => {
    if (unlocked) {
      fetch('/api/mama/brief').then(r => r.json()).then(setBrief).catch(() => {})
    }
  }, [unlocked])

  if (!unlocked) return <PinScreen onUnlock={() => setUnlocked(true)} t={t} avatarSrc={avatarSrc} />

  const props = { t, lang, setLang, brief, screen, setScreen, avatarSrc, onAvatarChange: setAvatarSrc, onLogout: () => setUnlocked(false) }

  return isDesktop ? <DesktopMama {...props} /> : <MobileMama {...props} />
}
