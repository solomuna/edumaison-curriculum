import { useState, useEffect } from 'react'
import { getChildProfile } from '../../services/api'
import type { Child } from '../../types/child'
import BulletinPage from './BulletinPage'
import RemediationPage from './RemediationPage'

interface Props { child: Child; onBack: () => void; isDesktop?: boolean }
interface Profile { name: string; level: string; attempts: number; completed: number }

const STAR_FULL  = '\u2B50'
const STAR_EMPTY = '\u2606'
const CHART      = '\u{1F4CA}'
const CLIPBOARD  = '\u{1F4CB}'
const MUSCLE     = '\u{1F4AA}'

export default function ProgressPage({ child, onBack, isDesktop }: Props) {
  const [profile, setProfile] = useState<Profile | null>(null)
  const [loading, setLoading] = useState(true)
  const [showBulletin, setShowBulletin] = useState(false)
  const [showRemediation, setShowRemediation] = useState(false)

  useEffect(() => {
    getChildProfile(child.id).then(setProfile).finally(() => setLoading(false))
  }, [])

  if (showBulletin) return <BulletinPage child={child} onBack={() => setShowBulletin(false)} />
  if (showRemediation) return <RemediationPage child={child} onBack={() => setShowRemediation(false)} />

  const pct = profile && profile.attempts > 0 ? Math.round((profile.completed / profile.attempts) * 100) : 0
  const stars = pct >= 80 ? 3 : pct >= 50 ? 2 : pct > 0 ? 1 : 0
  const judiMsg = pct === 0 ? 'Start your first activities! I am here to help you.'
    : pct < 50 ? 'Keep going! You are making great progress.'
    : pct < 80 ? 'Very well done! You are really improving!'
    : 'Excellent! You are a true star!'

  return (
    <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', paddingBottom: isDesktop ? 20 : 80 }}>
      {!isDesktop && (
        <div style={{ background: '#1D6B2A', padding: '12px 16px', display: 'flex', alignItems: 'center', gap: 10 }}>
          <button onClick={onBack} style={{ background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: 10, padding: '6px 14px', color: 'white', fontWeight: 800, fontSize: 13, cursor: 'pointer' }}>Back</button>
          <div style={{ fontSize: 16, fontWeight: 900, color: 'white' }}>{CHART} My Progress</div>
        </div>
      )}
      {isDesktop && <div style={{ padding: '20px 18px 4px', fontSize: 20, fontWeight: 900, color: 'var(--text-dark)' }}>{CHART} My Progress</div>}
      <div style={{ padding: '16px 18px' }}>
        {loading && [1,2,3].map(i => <div key={i} style={{ borderRadius: 18, height: 88, background: 'var(--card)', marginBottom: 12, opacity: 0.5 }} />)}
        {!loading && profile && (
          <>
            <div style={{ background: 'var(--card)', borderRadius: 20, padding: '24px 20px', marginBottom: 14, textAlign: 'center', border: '2px solid #1D6B2A' }}>
              <div style={{ fontSize: 32, marginBottom: 8, letterSpacing: 4 }}>{STAR_FULL.repeat(stars)}{STAR_EMPTY.repeat(3 - stars)}</div>
              <div style={{ fontSize: 36, fontWeight: 900, color: '#1D6B2A' }}>{pct}%</div>
              <div style={{ fontSize: 12, color: 'var(--text-soft)', marginTop: 4, fontWeight: 700 }}>Success rate</div>
            </div>
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginBottom: 14 }}>
              <div style={{ background: 'var(--card)', borderRadius: 18, padding: '16px 14px', textAlign: 'center', border: '1.5px solid var(--border)' }}>
                <div style={{ fontSize: 32, fontWeight: 900, color: '#1D6B2A' }}>{profile.completed}</div>
                <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 4, fontWeight: 700 }}>Completed</div>
              </div>
              <div style={{ background: 'var(--card)', borderRadius: 18, padding: '16px 14px', textAlign: 'center', border: '1.5px solid var(--border)' }}>
                <div style={{ fontSize: 32, fontWeight: 900, color: '#C47A3C' }}>{profile.attempts}</div>
                <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 4, fontWeight: 700 }}>Total attempts</div>
              </div>
            </div>
            <div style={{ background: 'var(--card)', borderRadius: 18, padding: '14px 16px', display: 'flex', alignItems: 'center', gap: 12, marginBottom: 16, border: '2px solid #1D6B2A' }}>
              <div style={{ fontSize: 26 }}>{MUSCLE}</div>
              <div style={{ fontSize: 13, color: 'var(--text-dark)', fontWeight: 700, lineHeight: 1.4 }}>{judiMsg}</div>
            </div>
            <button onClick={() => setShowBulletin(true)} style={{ width: '100%', padding: '16px 0', borderRadius: 18, border: 'none', background: '#1D6B2A', color: 'white', fontSize: 15, fontWeight: 900, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 10, marginBottom: 10 }}>
              <span style={{ fontSize: 20 }}>{CLIPBOARD}</span> My Report Card
            </button>
            <button onClick={() => setShowRemediation(true)} style={{ width: '100%', padding: '16px 0', borderRadius: 18, border: '2px solid #C47A3C', background: 'transparent', color: '#C47A3C', fontSize: 15, fontWeight: 900, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 10 }}>
              <span style={{ fontSize: 20 }}>{CLIPBOARD}</span> My Remediation Plan
            </button>
          </>
        )}
      </div>
    </div>
  )
}
