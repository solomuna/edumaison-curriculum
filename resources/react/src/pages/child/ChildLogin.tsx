import { useState, useEffect, useRef } from 'react'
import { getChildren, loginChild } from '../../services/api'
import '../../styles/anglofun.css'
import type { Child } from '../../types/child'

interface Props { onLogin: (child: Child) => void; onParentMode?: () => void }

const BORDER_COLORS = ['#EC4899', '#3B82F6', '#8B5CF6', '#10B981', '#F59E0B', '#EF4444']

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

function MamaJudiHead({ size = 90 }: { size?: number }) {
  return (
    <svg viewBox="0 0 84 84" width={size} height={size} xmlns="http://www.w3.org/2000/svg">
      <circle cx="42" cy="42" r="42" fill="#C8874A"/>
      <circle cx="42" cy="38" r="22" fill="#A06830"/>
      <circle cx="33" cy="34" r="4" fill="#1A0A00"/>
      <circle cx="51" cy="34" r="4" fill="#1A0A00"/>
      <circle cx="34.5" cy="32.5" r="1.5" fill="white"/>
      <circle cx="52.5" cy="32.5" r="1.5" fill="white"/>
      <ellipse cx="42" cy="42" rx="2.5" ry="2" fill="#8A5520"/>
      <path d="M30 48 Q42 58 54 48" stroke="#1A0A00" strokeWidth="2.2" fill="none" strokeLinecap="round"/>
      <rect x="6" y="0" width="72" height="28" rx="36" fill="#2A1500"/>
      <ellipse cx="42" cy="56" rx="7" ry="8" fill="#C8874A"/>
      <path d="M10 84 Q8 68 42 62 Q76 68 74 84 Z" fill="#FF8FAB"/>
    </svg>
  )
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
    <svg viewBox="0 0 480 44" width="100%" height="44" style={{ display: 'block', marginTop: -2 }}>
      {trees}
    </svg>
  )
}

export default function ChildLogin({ onLogin, onParentMode }: Props) {
  const [children, setChildren] = useState<Child[]>([])
  const [selected, setSelected] = useState<Child | null>(null)
  const [pin, setPin] = useState('')
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(true)
  const [checking, setChecking] = useState(false)
  const [showQuitLogin, setShowQuitLogin] = useState(false)
  const selectedRef = useRef(selected)
  const showQuitLoginRef = useRef(false)
  const loginPushCount = useRef(0)
  const quittingLogin = useRef(false)
  useEffect(() => { selectedRef.current = selected }, [selected])
  useEffect(() => { showQuitLoginRef.current = showQuitLogin }, [showQuitLogin])

  useEffect(() => { getChildren().then(setChildren).finally(() => setLoading(false)) }, [])

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
      if (res.error) { setError('Wrong PIN — try again'); setPin('') }
      else onLogin(res)
    }
  }

  const keys = ['1','2','3','4','5','6','7','8','9','DEL','0','OK']

  if (selected) {
    const color = BORDER_COLORS[(selected.id - 1) % BORDER_COLORS.length]
    const firstName = selected.name.split(' ')[0]
    return (
      <div style={{ background: '#87CEEB', minHeight: '100vh', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', fontFamily: 'Nunito, system-ui, sans-serif', padding: '24px 20px' }}>
        <MamaJudiHead />
        <div style={{ background: '#F5EDD8', borderRadius: 16, padding: '12px 24px', margin: '14px 0', textAlign: 'center', border: '2px solid #1D6B2A', maxWidth: 280 }}>
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
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 12, width: 252, marginBottom: 20 }}>
          {keys.map((k, i) => (
            <button key={i} onClick={() => handlePin(k)} style={{ width: 68, height: 68, borderRadius: 34, background: k === 'OK' ? '#1D6B2A' : '#F0E8D0', border: k === 'OK' ? '2px solid #155214' : '1.5px solid #D0C8B8', color: k === 'OK' ? 'white' : '#3D2B1F', fontSize: k === 'DEL' || k === 'OK' ? 14 : 22, fontWeight: 900, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Nunito, system-ui, sans-serif' }}>
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
    <div style={{ background: '#87CEEB', minHeight: '100vh', display: 'flex', flexDirection: 'column', fontFamily: 'Nunito, system-ui, sans-serif' }}>
      <div style={{ padding: '22px 18px 10px', textAlign: 'center' }}>
        <div style={{ fontSize: 34, fontWeight: 900, letterSpacing: '-1px' }}>
          <span style={{ color: '#1D6B2A' }}>ANGLO</span><span style={{ color: '#CE1126' }}>FUN</span>
        </div>
        <div style={{ fontSize: 12, color: '#2A4A1A', marginTop: 3, fontWeight: 600 }}>
          🇨🇲 MARIO Nursery &amp; Primary School &middot; Yaound&eacute;, Centre
        </div>
      </div>

      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', marginBottom: 6 }}>
        <MamaJudiHead />
        <div style={{ background: '#F5EDD8', borderRadius: 18, padding: '12px 20px', margin: '10px 18px', border: '2px solid #1D6B2A', textAlign: 'center', maxWidth: 320, width: '100%', boxSizing: 'border-box' }}>
          <div style={{ fontSize: 16, fontWeight: 800, color: '#3D2B1F' }}>Welcome! Who is learning today?</div>
          <div style={{ fontSize: 12, color: '#7A6050', marginTop: 3 }}>&#128266; tap to hear again</div>
        </div>
      </div>

      <div style={{ padding: '0 18px', flex: 1 }}>
        <div style={{ fontSize: 20, fontWeight: 900, color: '#3D2B1F', marginBottom: 12 }}>Who is here today?</div>
        {loading && [1,2,3].map(i => <div key={i} style={{ borderRadius: 18, height: 88, marginBottom: 10, background: '#F5EDD8', opacity: 0.6 }}/>)}
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
            <div key={child.id} onClick={() => setSelected(child)} style={{ background: '#F5EDD8', borderRadius: 20, padding: '14px 16px', marginBottom: 10, cursor: 'pointer', border: '2px solid #E8DCC8', borderLeft: `5px solid ${color}`, display: 'flex', alignItems: 'center', gap: 14, boxShadow: '0 2px 8px rgba(0,0,0,0.06)' }}>
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

      <div style={{ padding: '12px 18px', textAlign: 'center' }}>
        <div style={{ display: 'flex', gap: 10, justifyContent: 'center', flexWrap: 'wrap' }}>
        <button onClick={onParentMode} style={{ background: 'rgba(255,255,255,0.3)', border: '1.5px solid rgba(255,255,255,0.5)', borderRadius: 24, padding: '10px 28px', fontSize: 14, fontWeight: 700, color: '#2A4A1A', cursor: 'pointer', fontFamily: 'Nunito, system-ui, sans-serif' }}>
          &#128106; Parent view
        </button>
        <button onClick={() => window.location.href = '/mama'} style={{ background: '#6B4226', border: 'none', borderRadius: 24, padding: '10px 28px', fontSize: 14, fontWeight: 700, color: 'white', cursor: 'pointer', fontFamily: 'Nunito, system-ui, sans-serif' }}>
          Mama Judi
        </button>
      </div>
      </div>

      <TreeLine />
      {showQuitLogin && (
        <div style={{ position: 'fixed', inset: 0, zIndex: 9999,
          background: 'rgba(0,0,0,0.55)',
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          padding: '0 32px' }}>
          <div style={{ background: '#F5EDD8', borderRadius: 24, padding: '28px 24px',
            width: '100%', maxWidth: 360, textAlign: 'center',
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
