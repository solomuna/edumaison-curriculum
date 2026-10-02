import { useState, useEffect, useRef } from 'react'
import MamaJudiPose, { type MamaJudiPoseName } from '../../components/MamaJudiPose'
import { getChildren, loginChild } from '../../services/api'
import '../../styles/anglofun.css'
import '../../styles/adventure.css'
import type { Child } from '../../types/child'

interface Props { onLogin: (child: Child) => void; onParentMode?: () => void }

const BORDER_COLORS = ['#EC4899', '#3B82F6', '#8B5CF6', '#10B981', '#F59E0B', '#EF4444']
const addChildInputStyle: React.CSSProperties = {
  minHeight: 50, borderRadius: 14, border: '2px solid #C9E7D2',
  padding: '0 14px', fontSize: 15, background: 'white', color: '#20352A',
}

const CLASS_WORDS: Record<string, string> = {
  'Class 1': 'Class One', 'Class 2': 'Class Two', 'Class 3': 'Class Three',
  'Class 4': 'Class Four', 'Class 5': 'Class Five', 'Class 6': 'Class Six',
  'Nursery 1': 'Nursery One', 'Nursery 2': 'Nursery Two', 'Pre-Nursery': 'Pre-Nursery',
}
function formatLevel(level: string): string { return CLASS_WORDS[level] || level }

function calcAge(birthDate: string | null | undefined): string {
  if (!birthDate) return ''
  const age = Math.floor((Date.now() - new Date(birthDate).getTime()) / (1000 * 60 * 60 * 24 * 365.25))
  return ` · ${age} yrs`
}

function MamaJudiHead({ pose = 'explain' }: { size?: number; pose?: MamaJudiPoseName }) {
  return <MamaJudiPose pose={pose} className="adventure-entry-inline-judi" decorative />
}

function ChildAvatar({ color, child }: { color: string; child: any }) {
  const initial = (child.name || '?')[0].toUpperCase()
  if (child.avatar) {
    const url = child.avatar.startsWith('http') ? child.avatar : '/storage/' + child.avatar
    return (
      <div style={{ width: 64, height: 64, borderRadius: '50%', border: `3px solid ${color}`, overflow: 'hidden', flexShrink: 0 }}>
        <img src={url} alt={initial} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
      </div>
    )
  }
  return <img src="/images/default-child.webp" alt={initial} style={{ width: 64, height: 64, borderRadius: '50%', border: `3px solid ${color}`, objectFit: 'cover', flexShrink: 0 }} />
  /* legacy fallback */
  return (
    <div style={{ width: 64, height: 64, borderRadius: '50%', background: color + '22', border: `3px solid ${color}`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 26, fontWeight: 900, color, flexShrink: 0 }}>
      {initial}
    </div>
  )
}

function TreeLine() {
  const trees = Array.from({ length: 14 }, (_, i) => {
    const h = 18 + (i % 3) * 8
    const x = i * 34
    return (
      <g key={i} transform={`translate(${x}, ${40 - h})`}>
        <polygon points={`14,0 28,${h} 0,${h}`} fill="#1D6B2A" opacity={0.8 + (i % 3) * 0.07}/>
      </g>
    )
  })
  return (
    <svg className="adventure-entry-tree-line" viewBox="0 0 480 44" width="100%" height="44" style={{ display: 'block', marginTop: -2 }}>
      {trees}
    </svg>
  )
}


function speakText(text: string) {
  if (!('speechSynthesis' in window)) return
  window.speechSynthesis.cancel()
  const u = new SpeechSynthesisUtterance(text)
  u.lang = 'en-GB'
  u.rate = 0.9
  window.speechSynthesis.speak(u)
}
export default function ChildLogin({ onLogin, onParentMode }: Props) {
  const [children, setChildren] = useState<Child[]>([])
  const [selected, setSelected] = useState<Child | null>(null)
  const [pin, setPin] = useState('')
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(true)
  const [checking, setChecking] = useState(false)
  const [showQuitLogin, setShowQuitLogin] = useState(false)
  const [showParentPin, setShowParentPin] = useState(false)
  const [parentPin, setParentPin] = useState('')
  const [parentPinError, setParentPinError] = useState('')
  const [familyConnected, setFamilyConnected] = useState(false)
  const [familyName, setFamilyName] = useState('')
  const [companionName, setCompanionName] = useState('Accompagnant')
  const [legacyAccess, setLegacyAccess] = useState(false)
  const [legacyFamilyName, setLegacyFamilyName] = useState('')
  const [familyLogoutError, setFamilyLogoutError] = useState('')
  const [showAddChild, setShowAddChild] = useState(false)
  const [addingChild, setAddingChild] = useState(false)
  const [addChildError, setAddChildError] = useState('')
  const [levels, setLevels] = useState<Array<{ id: number, name: string }>>([])
  const [newChild, setNewChild] = useState({ first_name: '', last_name: '', birth_date: '', level_id: '', pin: '' })
  const [newChildPhoto, setNewChildPhoto] = useState<File | null>(null)
  const selectedRef = useRef(selected)
  const showQuitLoginRef = useRef(false)
  const loginPushCount = useRef(0)
  const quittingLogin = useRef(false)
  useEffect(() => { selectedRef.current = selected }, [selected])
  useEffect(() => { showQuitLoginRef.current = showQuitLogin }, [showQuitLogin])

  const loadChildren = () => getChildren().then(setChildren).finally(() => setLoading(false))
  useEffect(() => { loadChildren() }, [])

  useEffect(() => {
    fetch('/api/family-auth/me', { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(async response => {
        setFamilyConnected(response.ok)
        if (response.ok) {
          const data = await response.json()
          setFamilyName(data.family?.name || data.household?.name || '')
        }
      })
      .catch(() => setFamilyConnected(false))
    fetch('/api/access/status', { credentials: 'same-origin' })
      .then(response => response.ok ? response.json() : { unlocked: false })
      .then(data => { setLegacyAccess(Boolean(data.unlocked)); setLegacyFamilyName(data.family?.name || '') })
      .catch(() => setLegacyAccess(false))
    fetch('/api/mama/profile', { credentials: 'same-origin' })
      .then(response => response.ok ? response.json() : {})
      .then((data: { display_name?: string }) => setCompanionName(data.display_name || 'Accompagnant'))
      .catch(() => {})
  }, [])

  const logoutFamily = async () => {
    setFamilyLogoutError('')
    try {
      await fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' })
      const rawToken = document.cookie.split('; ').find(value => value.startsWith('XSRF-TOKEN='))?.split('=').slice(1).join('=') || ''
      const response = await fetch('/api/family-auth/logout', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { Accept: 'application/json', 'X-XSRF-TOKEN': decodeURIComponent(rawToken) },
      })
      if (!response.ok) throw new Error('logout_failed')
      localStorage.removeItem('edumaison_session')
      window.location.href = '/app'
    } catch {
      setFamilyLogoutError('La déconnexion a échoué. Vérifie la connexion puis réessaie.')
    }
  }

  const openParentMode = async (event: React.FormEvent) => {
    event.preventDefault(); setParentPinError('')
    try {
      await fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' })
      const rawToken = document.cookie.split('; ').find(value => value.startsWith('XSRF-TOKEN='))?.split('=').slice(1).join('=') || ''
      const response = await fetch(familyConnected ? '/api/family-auth/unlock-pin' : '/api/access/unlock', {
        method: 'POST', credentials: 'same-origin',
        headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-XSRF-TOKEN': decodeURIComponent(rawToken) },
        body: JSON.stringify({ pin: parentPin }),
      })
      if (!response.ok) { setParentPin(''); setParentPinError('PIN familial incorrect.'); return }
      setShowParentPin(false); setParentPin(''); onParentMode?.()
    } catch { setParentPinError('Connexion impossible. Réessaie.') }
  }

  const lockFamily = async () => {
    setFamilyLogoutError('')
    try {
      await fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' })
      const rawToken = document.cookie.split('; ').find(value => value.startsWith('XSRF-TOKEN='))?.split('=').slice(1).join('=') || ''
      const response = await fetch('/api/family-auth/lock', {
        method: 'POST', credentials: 'same-origin',
        headers: { Accept: 'application/json', 'X-XSRF-TOKEN': decodeURIComponent(rawToken) },
      })
      if (!response.ok) throw new Error('lock_failed')
      localStorage.removeItem('edumaison_session')
      window.location.href = '/app'
    } catch {
      setFamilyLogoutError('Impossible de verrouiller la famille. Réessaie.')
    }
  }

  const lockTablet = async () => {
    setFamilyLogoutError('')
    try {
      await fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' })
      const rawToken = document.cookie.split('; ').find(value => value.startsWith('XSRF-TOKEN='))?.split('=').slice(1).join('=') || ''
      const response = await fetch('/api/access/lock', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { Accept: 'application/json', 'X-XSRF-TOKEN': decodeURIComponent(rawToken) },
      })
      if (!response.ok) throw new Error('lock_failed')
      localStorage.removeItem('edumaison_session')
      window.location.href = '/app'
    } catch {
      setFamilyLogoutError('Impossible de verrouiller la tablette. Réessaie.')
    }
  }

  const openAddChild = async () => {
    setAddChildError('')
    setShowAddChild(true)
    if (levels.length) return
    try {
      const response = await fetch('/api/family-auth/levels', { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      setLevels(response.ok ? await response.json() : [])
    } catch {
      setAddChildError('Impossible de charger les classes.')
    }
  }

  const submitNewChild = async (event: React.FormEvent) => {
    event.preventDefault()
    if (addingChild || newChild.pin.length !== 4) return
    setAddingChild(true); setAddChildError('')
    try {
      await fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' })
      const rawToken = document.cookie.split('; ').find(value => value.startsWith('XSRF-TOKEN='))?.split('=').slice(1).join('=') || ''
      const body = new FormData()
      Object.entries(newChild).forEach(([key, value]) => body.append(key, value))
      if (newChildPhoto) body.append('avatar', newChildPhoto)
      const response = await fetch('/api/family-auth/children', {
        method: 'POST', credentials: 'same-origin',
        headers: { Accept: 'application/json', 'X-XSRF-TOKEN': decodeURIComponent(rawToken) },
        body,
      })
      const data = await response.json().catch(() => ({}))
      if (!response.ok) {
        const firstError = Object.values(data.errors || {}).flat()[0]
        setAddChildError(String(firstError || data.message || 'Impossible d’ajouter cet enfant.'))
        return
      }
      setNewChild({ first_name: '', last_name: '', birth_date: '', level_id: '', pin: '' })
      setNewChildPhoto(null)
      setShowAddChild(false)
      setLoading(true)
      await loadChildren()
    } catch {
      setAddChildError('Connexion impossible. Vérifie le Wi-Fi.')
    } finally {
      setAddingChild(false)
    }
  }

  // Back button -- une seule inscription, refs pour valeurs courantes
  useEffect(() => {
    window.history.pushState({}, '')
    loginPushCount.current = 1
    const handler = () => {
      if (quittingLogin.current) return
      if (selectedRef.current) {
        setSelected(null); setPin(''); setError('')
        window.history.pushState({}, '')
        loginPushCount.current++
      } else if (!showQuitLoginRef.current) {
        setShowQuitLogin(true)
        window.history.pushState({}, '')
        loginPushCount.current++
      } else {
        // Dialog deja ouvert -- bloquer le back
        window.history.pushState({}, '')
        loginPushCount.current++
      }
    }
    window.addEventListener('popstate', handler)
    return () => window.removeEventListener('popstate', handler)
  }, [])

  useEffect(() => {
    if (!selected) return
    const handler = (e: KeyboardEvent) => {
      if (e.key >= '0' && e.key <= '9') handlePin(e.key)
      else if (e.key === 'Backspace') handlePin('DEL')
      else if (e.key === 'Enter') handlePin('OK')
    }
    window.addEventListener('keydown', handler)
    return () => window.removeEventListener('keydown', handler)
  }, [selected, pin, checking])

  const handlePin = async (digit: string) => {
    if (!selected || checking) return
    if (digit === 'DEL') { setPin(p => p.slice(0, -1)); setError(''); return }
    const newPin = pin + digit
    setPin(newPin)
    if (newPin.length === 4) {
      setChecking(true)
      const res = await loginChild(selected.id, newPin)
      setChecking(false)
      if (res.error || res.detail || !res.id) { setError('Wrong PIN — try again'); setPin('') }
      else onLogin(res)
    }
  }

  const keys = ['1','2','3','4','5','6','7','8','9','DEL','0','OK']

  if (selected) {
    const color = BORDER_COLORS[(selected.id - 1) % BORDER_COLORS.length]
    const firstName = selected.name.split(' ')[0]
    return (
      <div className="adventure-entry adventure-child-pin" style={{ background: '#87CEEB', minHeight: '100vh', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', fontFamily: 'Nunito, system-ui, sans-serif', padding: '24px 20px' }}>
        <MamaJudiHead pose="encourage" />
        <div onClick={() => speakText('Enter your secret PIN')}
          className="adventure-entry-speech"
          style={{ background: '#F5EDD8', borderRadius: 16, padding: '12px 24px', margin: '14px 0', textAlign: 'center', border: '2px solid #1D6B2A', maxWidth: 560, cursor: 'pointer' }}>
          <div style={{ fontSize: 16, fontWeight: 800, color: '#3D2B1F' }}>Enter your secret PIN</div>
          <div style={{ fontSize: 12, color: '#7A6050', marginTop: 3 }}>&#128266; tap to hear again</div>
        </div>
        <div style={{ fontSize: 22, fontWeight: 900, color: '#3D2B1F', marginBottom: 2 }}>{firstName}</div>
        <div style={{ fontSize: 13, color: '#7A6050', marginBottom: 18 }}>{formatLevel(selected.level)}</div>
        <div style={{ display: 'flex', gap: 16, marginBottom: 10 }}>
          {[0,1,2,3].map(i => (
            <div key={i} style={{ width: 18, height: 18, borderRadius: '50%', background: i < pin.length ? color : 'transparent', border: `3px solid ${i < pin.length ? color : '#1D6B2A'}`, transition: 'all 0.15s' }}/>
          ))}
        </div>
        {error && <div style={{ color: '#CE1126', fontWeight: 700, fontSize: 13, marginBottom: 8 }}>{error}</div>}
        {checking && <div style={{ color: '#1D6B2A', fontWeight: 700, fontSize: 13, marginBottom: 8 }}>Checking...</div>}
        <div className="adventure-pin-keypad" style={{ display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 12, width: 252, marginBottom: 20 }}>
          {keys.map((k, i) => (
            <button className="adventure-pin-key" key={i} onClick={() => handlePin(k)} style={{ width: 68, height: 68, borderRadius: 34, background: k === 'OK' ? '#1D6B2A' : '#F0E8D0', border: k === 'OK' ? '2px solid #155214' : '1.5px solid #D0C8B8', color: k === 'OK' ? 'white' : '#3D2B1F', fontSize: k === 'DEL' || k === 'OK' ? 14 : 22, fontWeight: 900, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Nunito, system-ui, sans-serif' }}>
              {k === 'DEL' ? '⌫' : k === 'OK' ? 'Go' : k}
            </button>
          ))}
        </div>
        <button onClick={() => { setSelected(null); setPin(''); setError('') }} style={{ background: 'none', border: 'none', color: '#7A6050', fontSize: 14, cursor: 'pointer', fontWeight: 700 }}>
          Back
        </button>
      </div>
    )
  }

  return (
    <div className="adventure-entry adventure-child-picker" style={{ background: '#87CEEB', minHeight: '100vh', display: 'flex', flexDirection: 'column', fontFamily: 'Nunito, system-ui, sans-serif' }}>
      <div className="adventure-entry-brand" style={{ padding: '22px 18px 10px', textAlign: 'center' }}>
        <div style={{ fontSize: 34, fontWeight: 900, letterSpacing: '-1px' }}>
          <span style={{ color: '#1D6B2A' }}>{familyConnected || legacyAccess ? 'EDUMAISON' : 'ANGLO'}</span>{!familyConnected && !legacyAccess && <span style={{ color: '#CE1126' }}>FUN</span>}
        </div>
        <div style={{ fontSize: 12, color: '#2A4A1A', marginTop: 3, fontWeight: 600 }}>
          {familyConnected ? (familyName || 'Espace familial') : legacyAccess ? (legacyFamilyName || 'Famille existante') : 'MARIO Nursery & Primary School · Yaoundé, Centre'}
        </div>
      </div>

      <div className="adventure-picker-guide" style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', marginBottom: 6 }}>
        <MamaJudiHead />
        <div onClick={() => speakText('Welcome! Who is learning today?')}
          className="adventure-entry-speech"
          style={{ background: '#F5EDD8', borderRadius: 18, padding: '12px 20px', margin: '10px 18px', border: '2px solid #1D6B2A', textAlign: 'center', maxWidth: 440, width: '100%', boxSizing: 'border-box', cursor: 'pointer' }}>
          <div style={{ fontSize: 16, fontWeight: 800, color: '#3D2B1F' }}>Welcome! Who is learning today?</div>
          <div style={{ fontSize: 12, color: '#7A6050', marginTop: 3 }}>&#128266; tap to hear again</div>
        </div>
      </div>

      <div className="adventure-picker-list" style={{ padding: '0 18px', flex: 1 }}>
        <div style={{ fontSize: 20, fontWeight: 900, color: '#3D2B1F', marginBottom: 12 }}>Who is here today?</div>
        {loading && [1,2,3].map(i => <div key={i} style={{ borderRadius: 18, height: 88, marginBottom: 10, background: '#F5EDD8', opacity: 0.6 }}/>)}
        {!loading && children.length === 0 && familyConnected && (
          <div style={{ background: '#F5EDD8', borderRadius: 22, padding: '28px 20px', textAlign: 'center', border: '2px solid #E8DCC8' }}>
            <div style={{ fontSize: 42, marginBottom: 8 }}>🌱</div>
            <div style={{ fontSize: 20, fontWeight: 900, color: '#3D2B1F' }}>Ajoutons le premier enfant</div>
            <div style={{ fontSize: 14, color: '#7A6050', margin: '6px 0 18px' }}>Crée son profil pour commencer son parcours d’apprentissage.</div>
            <button onClick={openAddChild} style={{ background: '#1D6B2A', border: 'none', borderRadius: 22, padding: '12px 24px', color: 'white', fontSize: 15, fontWeight: 900, cursor: 'pointer' }}>
              Ajouter un enfant
            </button>
          </div>
        )}
        {children.map((child, idx) => {
          const color = BORDER_COLORS[idx % BORDER_COLORS.length]
          const firstName = child.name.split(' ')[0]
          const pct = (child as any).pct ?? 0
          const badge = pct >= 70
            ? { label: 'Good', bg: '#4CAF50' }
            : pct >= 40
            ? { label: 'Watch', bg: '#F59E0B' }
            : pct === 0
            ? { label: 'Start!', bg: '#3B82F6' }
            : { label: 'Urgent', bg: '#CE1126' }
          return (
            <div className="adventure-child-card" key={child.id} onClick={() => setSelected(child)} style={{ background: '#F5EDD8', borderRadius: 20, padding: '14px 16px', marginBottom: 10, cursor: 'pointer', border: '2px solid #E8DCC8', borderLeft: `5px solid ${color}`, display: 'flex', alignItems: 'center', gap: 14, boxShadow: '0 2px 8px rgba(0,0,0,0.06)' }}>
              <ChildAvatar color={color} child={child} />
              <div style={{ flex: 1 }}>
                <div style={{ fontSize: 20, fontWeight: 900, color: '#3D2B1F' }}>{firstName}</div>
                <div style={{ fontSize: 13, color: '#7A6050', marginTop: 1 }}>{formatLevel(child.level)}{calcAge((child as any).birth_date)}</div>
                <div style={{ marginTop: 6 }}>
                  <span style={{ background: badge.bg, color: 'white', borderRadius: 20, padding: '3px 14px', fontSize: 12, fontWeight: 800 }}>{badge.label}</span>
                </div>
              </div>
              <span style={{ fontSize: 22, color: '#7A6050', fontWeight: 900 }}>›</span>
            </div>
          )
        })}
      </div>

      <div className="adventure-entry-actions" style={{ padding: '12px 18px', textAlign: 'center' }}>
        {familyLogoutError && <div style={{ color: '#CE1126', fontSize: 13, fontWeight: 800, marginBottom: 10 }}>{familyLogoutError}</div>}
        <div style={{ display: 'flex', gap: 10, justifyContent: 'center', flexWrap: 'wrap' }}>
        <button onClick={() => { setParentPin(''); setParentPinError(''); setShowParentPin(true) }} style={{ background: 'rgba(255,255,255,0.3)', border: '1.5px solid rgba(255,255,255,0.5)', borderRadius: 24, padding: '10px 28px', fontSize: 14, fontWeight: 700, color: '#2A4A1A', cursor: 'pointer', fontFamily: 'Nunito, system-ui, sans-serif' }}>
          &#128106; Parent view
        </button>
        <button onClick={() => window.location.href = '/mama'} style={{ background: '#6B4226', border: 'none', borderRadius: 24, padding: '10px 28px', fontSize: 14, fontWeight: 700, color: 'white', cursor: 'pointer', fontFamily: 'Nunito, system-ui, sans-serif' }}>
          {companionName}
        </button>
        {familyConnected && <>
          <button onClick={lockFamily} style={{ background: '#1D6B2A', border: 'none', borderRadius: 24, padding: '10px 22px', fontSize: 14, fontWeight: 800, color: 'white', cursor: 'pointer', fontFamily: 'Nunito, system-ui, sans-serif' }}>
            Verrouiller la famille
          </button>
          <button onClick={logoutFamily} style={{ background: 'transparent', border: '1.5px solid rgba(61,43,31,0.35)', borderRadius: 24, padding: '10px 22px', fontSize: 14, fontWeight: 800, color: '#3D2B1F', cursor: 'pointer', fontFamily: 'Nunito, system-ui, sans-serif' }}>
            Déconnecter ce compte
          </button>
        </>}
        {!familyConnected && legacyAccess && <button onClick={lockTablet} style={{ background: 'transparent', border: '1.5px solid rgba(61,43,31,0.35)', borderRadius: 24, padding: '10px 22px', fontSize: 14, fontWeight: 800, color: '#3D2B1F', cursor: 'pointer', fontFamily: 'Nunito, system-ui, sans-serif' }}>
          Verrouiller la tablette
        </button>}
      </div>
      </div>

      <TreeLine />
      {showAddChild && (
        <div style={{ position: 'fixed', inset: 0, zIndex: 10000, background: 'rgba(0,0,0,0.58)', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 20 }}>
          <form onSubmit={submitNewChild} style={{ background: '#FFFDF7', borderRadius: 24, padding: '24px 22px', width: 'min(100%, 440px)', boxSizing: 'border-box', boxShadow: '0 18px 50px rgba(0,0,0,.28)', display: 'flex', flexDirection: 'column', gap: 10 }}>
            <div style={{ fontSize: 24, fontWeight: 900, color: '#20352A', textAlign: 'center' }}>Nouvel enfant</div>
            <img src={newChildPhoto ? URL.createObjectURL(newChildPhoto) : '/images/default-child.webp'} alt="Aperçu de l’enfant" style={{ width: 88, height: 88, borderRadius: '50%', objectFit: 'cover', margin: '0 auto', border: '4px solid #C9E7D2' }} />
            <label style={{ minHeight: 44, display: 'grid', placeItems: 'center', border: '2px dashed #8BCAA2', borderRadius: 14, color: '#126F41', background: '#F7FFF9', fontWeight: 900, cursor: 'pointer' }}>📷 Ajouter sa photo (facultatif)<input type="file" accept="image/*" capture="user" hidden onChange={e => setNewChildPhoto(e.target.files?.[0] || null)} /></label>
            <input style={addChildInputStyle} placeholder="Prénom" value={newChild.first_name} onChange={e => setNewChild({ ...newChild, first_name: e.target.value })} required />
            <input style={addChildInputStyle} placeholder="Nom (facultatif)" value={newChild.last_name} onChange={e => setNewChild({ ...newChild, last_name: e.target.value })} />
            <input style={addChildInputStyle} type="date" aria-label="Date de naissance" value={newChild.birth_date} onChange={e => setNewChild({ ...newChild, birth_date: e.target.value })} required />
            <select style={addChildInputStyle} value={newChild.level_id} onChange={e => setNewChild({ ...newChild, level_id: e.target.value })} required>
              <option value="">Choisir la classe</option>
              {levels.map(level => <option key={level.id} value={level.id}>{level.name}</option>)}
            </select>
            <input style={addChildInputStyle} inputMode="numeric" pattern="[0-9]{4}" maxLength={4} placeholder="PIN enfant à 4 chiffres" value={newChild.pin} onChange={e => setNewChild({ ...newChild, pin: e.target.value.replace(/\D/g, '').slice(0, 4) })} required />
            {addChildError && <div style={{ color: '#B42318', fontSize: 13, fontWeight: 800, textAlign: 'center' }}>{addChildError}</div>}
            <div style={{ display: 'flex', gap: 10, marginTop: 4 }}>
              <button type="button" onClick={() => setShowAddChild(false)} style={{ flex: 1, minHeight: 50, borderRadius: 15, border: '2px solid #C9E7D2', background: 'white', color: '#31513E', fontWeight: 800, cursor: 'pointer' }}>Annuler</button>
              <button disabled={addingChild || newChild.pin.length !== 4} style={{ flex: 1, minHeight: 50, borderRadius: 15, border: 0, background: '#159957', color: 'white', fontWeight: 900, cursor: 'pointer', opacity: addingChild || newChild.pin.length !== 4 ? .55 : 1 }}>{addingChild ? 'Ajout…' : 'Ajouter'}</button>
            </div>
          </form>
        </div>
      )}
      {showParentPin && (
        <div style={{ position: 'fixed', inset: 0, zIndex: 10001, background: 'rgba(0,0,0,.58)', display: 'grid', placeItems: 'center', padding: 20 }}>
          <form onSubmit={openParentMode} style={{ width: 'min(100%,380px)', boxSizing: 'border-box', background: '#FFFDF7', borderRadius: 24, padding: 24, textAlign: 'center', boxShadow: '0 18px 50px rgba(0,0,0,.28)' }}>
            <div style={{ fontSize: 38 }}>🔐</div><h2 style={{ color: '#20352A', margin: '8px 0' }}>Espace parent</h2><p style={{ color: '#5C695F' }}>Entrez le PIN familial pour continuer.</p>
            <input autoFocus style={{ ...addChildInputStyle, width: '100%', boxSizing: 'border-box', textAlign: 'center', fontSize: 28, letterSpacing: 12 }} inputMode="numeric" pattern="[0-9]{4}" maxLength={4} value={parentPin} onChange={e => setParentPin(e.target.value.replace(/\D/g,'').slice(0,4))} placeholder="••••" />
            {parentPinError && <div style={{ color: '#B42318', fontWeight: 800, fontSize: 13, marginTop: 10 }}>{parentPinError}</div>}
            <div style={{ display: 'flex', gap: 10, marginTop: 16 }}><button type="button" onClick={() => setShowParentPin(false)} style={{ flex: 1, minHeight: 48, borderRadius: 14, border: '2px solid #C9E7D2', background: 'white', fontWeight: 800 }}>Annuler</button><button disabled={parentPin.length !== 4} style={{ flex: 1, minHeight: 48, borderRadius: 14, border: 0, background: '#159957', color: 'white', fontWeight: 900, opacity: parentPin.length === 4 ? 1 : .5 }}>Continuer</button></div>
          </form>
        </div>
      )}
      {showQuitLogin && (
        <div style={{ position: 'fixed', inset: 0, zIndex: 9999,
          background: 'rgba(0,0,0,0.55)',
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          padding: '0 32px' }}>
          <div style={{ background: '#F5EDD8', borderRadius: 24, padding: '28px 24px',
            width: '100%', maxWidth: 640, textAlign: 'center',
            boxShadow: '0 8px 40px rgba(0,0,0,0.25)' }}>
            <div style={{ fontSize: 48, marginBottom: 12 }}>📚</div>
            <div style={{ fontSize: 18, fontWeight: 900, color: '#3D2B1F', marginBottom: 8 }}>Quit EduMaison?</div>
            <div style={{ fontSize: 14, color: '#7A6050', marginBottom: 24 }}>See you soon!</div>
            <div style={{ display: 'flex', gap: 12 }}>
              <button onClick={() => { setShowQuitLogin(false) }}
                style={{ flex: 1, padding: '12px', borderRadius: 14, border: '2px solid #D0C8B8',
                  background: '#fff', fontSize: 15, fontWeight: 800, color: '#3D2B1F', cursor: 'pointer' }}>
                Stay
              </button>
              <button onClick={() => { quittingLogin.current = true; setShowQuitLogin(false); window.history.go(-loginPushCount.current) }}
                style={{ flex: 1, padding: '12px', borderRadius: 14, border: 'none',
                  background: '#1D6B2A', fontSize: 15, fontWeight: 800, color: 'white', cursor: 'pointer' }}>
                Quit
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
