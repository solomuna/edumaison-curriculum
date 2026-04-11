import { useState, useEffect, useRef } from 'react'
import { useTheme } from '../../context/ThemeContext'
import { getChildProfile } from '../../services/api'
import type { Child } from '../../types/child'
import CertificatePage from '../../components/CertificatePage'

interface Props { child: Child; onLogout: () => void; onBack: () => void; isDesktop?: boolean }

const AVATARS = ['\u{1F466}','\u{1F467}','\u{1F9D2}','\u{1F476}','\u{1F9D1}','\u{1F469}','\u{1F466}\u{1F3FD}','\u{1F467}\u{1F3FD}','\u{1F9D2}\u{1F3FD}','\u{1F9D1}\u{1F3FD}']

const LEVEL_ACCENT: Record<string, string> = {
  'Pre-Nursery': '#FF9800', 'Nursery 1': '#FF9800', 'Nursery 2': '#FF9800',
  'Class 1': '#4CAF50', 'Class 2': '#2196F3', 'Class 3': '#9C27B0',
  'Class 4': '#CE1126', 'Class 5': '#00BCD4', 'Class 6': '#C47A3C',
}

const CERT   = '\u{1F3C5}'
const DOOR   = '\u{1F6AA}'
const MUSCLE = '\u{1F4AA}'
const PENCIL = '\u270F'

export default function ProfilePage({ child, onLogout, onBack, isDesktop }: Props) {
  const { isDark, toggle } = useTheme()
  const [avatarUrl, setAvatarUrl] = useState<string | null>(child.avatar || null)
  const fileRef = useRef<HTMLInputElement>(null)

  const handleAvatarChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0]
    if (!file) return
    const form = new FormData()
    form.append('avatar', file)
    const r = await fetch(`/api/children/${child.id}/avatar`, { method: 'POST', body: form })
    if (r.ok) {
      const data = await r.json()
      setAvatarUrl(data.avatar_url)
    }
  }
  const [profile, setProfile] = useState<any>(null)
  const [showCerts, setShowCerts] = useState(false)
  const [loading, setLoading] = useState(true)
  const [avatarIdx, setAvatarIdx] = useState(child.id % AVATARS.length)
  const [showAvatars, setShowAvatars] = useState(false)

  useEffect(() => {
    getChildProfile(child.id).then(setProfile).finally(() => setLoading(false))
  }, [])

  const accent = LEVEL_ACCENT[child.level] || '#1D6B2A'
  const firstName = child.name.split(' ')[0]

  if (showCerts) return <CertificatePage child={child} onBack={() => setShowCerts(false)} />

  return (
    <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', paddingBottom: isDesktop ? 20 : 80 }}>
      {!isDesktop && (
        <div style={{ background: '#1D6B2A', padding: '12px 16px', display: 'flex', alignItems: 'center', gap: 10 }}>
          <button onClick={onBack} style={{ background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: 10, padding: '6px 14px', color: 'white', fontWeight: 800, fontSize: 13, cursor: 'pointer' }}>Back</button>
          <div style={{ fontSize: 16, fontWeight: 900, color: 'white' }}>{PENCIL} My Profile</div>
        </div>
      )}
      {isDesktop && <div style={{ padding: '20px 18px 4px', fontSize: 20, fontWeight: 900, color: 'var(--text-dark)' }}>{PENCIL} My Profile</div>}
      <div style={{ padding: '16px 18px' }}>
        <div style={{ background: 'var(--card)', borderRadius: 20, padding: '24px 16px', marginBottom: 14, textAlign: 'center', border: '2px solid ' + accent }}>
          <div style={{ position: 'relative', display: 'inline-block', marginBottom: 8 }} onClick={() => fileRef.current?.click()}>
            {avatarUrl
              ? <img src={avatarUrl.startsWith('http') ? avatarUrl : '/storage/' + avatarUrl} alt="avatar"
                  style={{ width: 90, height: 90, borderRadius: '50%', objectFit: 'cover', border: '3px solid ' + accent, cursor: 'pointer' }} />
              : <div style={{ fontSize: 72, cursor: 'pointer', display: 'inline-block' }} onClick={() => setShowAvatars(!showAvatars)}>{AVATARS[avatarIdx]}</div>
            }
            <div style={{ position: 'absolute', bottom: 0, right: 0, background: '#1D6B2A', borderRadius: '50%', width: 26, height: 26, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 13, cursor: 'pointer' }}>
              {PENCIL}
            </div>
          </div>
          <input ref={fileRef} type="file" accept="image/*" style={{ display: 'none' }} onChange={handleAvatarChange} />
          {showAvatars && (
            <div style={{ display: 'flex', flexWrap: 'wrap', justifyContent: 'center', gap: 10, marginBottom: 12 }}>
              {AVATARS.map((av, i) => (
                <button key={i} onClick={() => { setAvatarIdx(i); setShowAvatars(false) }}
                  style={{ fontSize: 28, background: 'none', border: i === avatarIdx ? '2px solid #1D6B2A' : '2px solid transparent', borderRadius: 10, padding: 4, cursor: 'pointer', opacity: i === avatarIdx ? 1 : 0.55 }}>
                  {av}
                </button>
              ))}
            </div>
          )}
          {!showAvatars && <div style={{ fontSize: 11, color: 'var(--text-soft)', marginBottom: 10, fontWeight: 600 }}>Tap avatar to change</div>}
          <div style={{ fontSize: 24, fontWeight: 900, color: 'var(--text-dark)', marginBottom: 6 }}>{child.name}</div>
          <span style={{ background: accent, color: 'white', borderRadius: 20, padding: '4px 16px', fontSize: 12, fontWeight: 800 }}>{child.level}</span>
        </div>
        {!loading && profile && (
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 14 }}>
            <div style={{ background: 'var(--card)', borderRadius: 18, padding: '16px', textAlign: 'center', border: '1.5px solid var(--border)' }}>
              <div style={{ fontSize: 32, fontWeight: 900, color: '#1D6B2A' }}>{profile.completed}</div>
              <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 4, fontWeight: 700 }}>Completed</div>
            </div>
            <div style={{ background: 'var(--card)', borderRadius: 18, padding: '16px', textAlign: 'center', border: '1.5px solid var(--border)' }}>
              <div style={{ fontSize: 32, fontWeight: 900, color: '#C47A3C' }}>{profile.attempts}</div>
              <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 4, fontWeight: 700 }}>Attempts</div>
            </div>
          </div>
        )}
        <div style={{ background: 'var(--card)', borderRadius: 18, padding: '16px', marginBottom: 14, border: '1.5px solid var(--border)' }}>
          <div style={{ fontSize: 11, fontWeight: 900, color: 'var(--text-soft)', textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: 12 }}>Information</div>
          {[{label:'Name',value:child.name},{label:'Level',value:child.level,color:accent},{label:'School',value:'MARIO Nursery & Primary'}].map(({label,value,color}:any) => (
            <div key={label} style={{ display: 'flex', justifyContent: 'space-between', paddingBottom: 10, marginBottom: 10, borderBottom: '1px solid #D0C8B8' }}>
              <span style={{ fontSize: 13, color: 'var(--text-soft)', fontWeight: 700 }}>{label}</span>
              <span style={{ fontSize: 13, fontWeight: 800, color: color || '#3D2B1F' }}>{value}</span>
            </div>
          ))}
        </div>
        <div style={{ background: 'var(--card)', borderRadius: 18, padding: '14px 16px', marginBottom: 14, display: 'flex', alignItems: 'center', gap: 12, border: '2px solid #1D6B2A' }}>
          <div style={{ fontSize: 26 }}>{MUSCLE}</div>
          <div style={{ fontSize: 13, color: 'var(--text-dark)', fontWeight: 700, lineHeight: 1.4 }}>Bravo {firstName} ! Continue comme ça, tu es fantastique !</div>
        </div>
        <button onClick={toggle} style={{ width: '100%', padding: '12px 0', borderRadius: 16, border: '2px solid var(--border)', background: 'var(--card)', color: 'var(--text-dark)', fontSize: 14, fontWeight: 900, cursor: 'pointer', marginBottom: 10, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 }}>
          {isDark ? '☀️' : '🌙'} {isDark ? 'Light mode' : 'Dark mode'}
        </button>
        <button onClick={() => setShowCerts(true)} style={{ width: '100%', padding: '14px 0', borderRadius: 16, border: '2px solid #C47A3C', background: 'transparent', color: '#C47A3C', fontSize: 14, fontWeight: 900, cursor: 'pointer', marginBottom: 10, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 }}>
          {CERT} My Certificates
        </button>
        <button onClick={onLogout} style={{ width: '100%', padding: '14px 0', borderRadius: 16, border: '2px solid #CE1126', background: 'transparent', color: '#CE1126', fontSize: 14, fontWeight: 900, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 }}>
          {DOOR} Change profile
        </button>
      </div>
    </div>
  )
}
