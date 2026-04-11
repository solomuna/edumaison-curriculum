import { useState, useEffect, useRef } from 'react'
import type { Child } from '../types/child'

interface Certificate {
  subject_id: number
  subject_name: string
  child_name: string
  level: string
  pct: number
  done: number
  total: number
  earned: boolean
  earned_at: string
}

const SUBJECT_COLORS: Record<string, { bg: string; accent: string; dark: string }> = {
  English:                  { bg: '#DBEAFE', accent: '#3B82F6', dark: '#1D4ED8' },
  Mathematics:              { bg: '#FEF3C7', accent: '#F59E0B', dark: '#D97706' },
  French:                   { bg: '#EDE9FE', accent: '#8B5CF6', dark: '#7C3AED' },
  'Science and Technology': { bg: '#D1FAE5', accent: '#10B981', dark: '#059669' },
  ICT:                      { bg: '#CFFAFE', accent: '#0EA5E9', dark: '#0284C7' },
  Citizenship:              { bg: '#FEF9C3', accent: '#EAB308', dark: '#CA8A04' },
  'Social Studies':         { bg: '#FEE2E2', accent: '#EF4444', dark: '#DC2626' },
  'National Languages and Cultures': { bg: '#FCE7F3', accent: '#EC4899', dark: '#DB2777' },
}
const DEF = { bg: '#F3F4F6', accent: '#6B7280', dark: '#4B5563' }
const gc = (s: string) => SUBJECT_COLORS[s] || DEF

function CertificateSVG({ cert }: { cert: Certificate }) {
  const col = gc(cert.subject_name)
  const date = cert.earned_at
    ? new Date(cert.earned_at).toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' })
    : ''

  return (
    <svg viewBox="0 0 440 280" width="100%" xmlns="http://www.w3.org/2000/svg"
      style={{ borderRadius: 20, boxShadow: '0 4px 24px rgba(0,0,0,0.10)' }}>
      {/* Background */}
      <rect x="0" y="0" width="440" height="280" rx="20" fill={col.bg}/>
      {/* Top accent bar */}
      <rect x="0" y="0" width="440" height="8" rx="4" fill={col.accent}/>
      {/* Border decoration */}
      <rect x="10" y="12" width="420" height="258" rx="14" fill="none" stroke={col.accent} strokeWidth="1.5" opacity="0.4"/>
      <rect x="16" y="18" width="408" height="246" rx="10" fill="none" stroke={col.accent} strokeWidth="0.8" opacity="0.25"/>

      {/* Stars decoration */}
      <polygon points="30,50 32.5,57 40,57 34,61 36.5,68 30,64 23.5,68 26,61 20,57 27.5,57" fill={col.accent} opacity="0.6"/>
      <polygon points="410,50 412.5,57 420,57 414,61 416.5,68 410,64 403.5,68 406,61 400,57 407.5,57" fill={col.accent} opacity="0.6"/>

      {/* Title */}
      <text x="220" y="55" textAnchor="middle" fontSize="11" fontWeight="700" fill={col.dark} fontFamily="system-ui,sans-serif" letterSpacing="3" opacity="0.7">
        CERTIFICAT D'EXCELLENCE
      </text>

      {/* Big subject icon area */}
      <circle cx="220" cy="115" r="40" fill={col.accent} opacity="0.15"/>
      <circle cx="220" cy="115" r="30" fill={col.accent} opacity="0.2"/>
      <text x="220" y="124" textAnchor="middle" fontSize="30" fontFamily="system-ui,sans-serif">🏆</text>

      {/* Child name */}
      <text x="220" y="172" textAnchor="middle" fontSize="22" fontWeight="900" fill={col.dark} fontFamily="system-ui,sans-serif">
        {cert.child_name}
      </text>

      {/* Subject */}
      <text x="220" y="196" textAnchor="middle" fontSize="13" fill={col.dark} fontFamily="system-ui,sans-serif" opacity="0.8">
        a complété avec succès
      </text>
      <text x="220" y="216" textAnchor="middle" fontSize="16" fontWeight="800" fill={col.accent} fontFamily="system-ui,sans-serif">
        {cert.subject_name} — {cert.level}
      </text>

      {/* Score */}
      <text x="220" y="236" textAnchor="middle" fontSize="12" fill={col.dark} fontFamily="system-ui,sans-serif" opacity="0.6">
        Score : {cert.pct}% · {cert.done}/{cert.total} exercices
      </text>

      {/* Date */}
      <text x="220" y="256" textAnchor="middle" fontSize="10" fill={col.dark} fontFamily="system-ui,sans-serif" opacity="0.5">
        {date}
      </text>

      {/* EduMaison branding */}
      <text x="24" y="270" fontSize="9" fill={col.dark} fontFamily="system-ui,sans-serif" opacity="0.4">EduMaison</text>
      <text x="416" y="270" textAnchor="end" fontSize="9" fill={col.dark} fontFamily="system-ui,sans-serif" opacity="0.4">MINEDUB</text>
    </svg>
  )
}

interface Props {
  child: Child
  onBack: () => void
}

export default function CertificatePage({ child, onBack }: Props) {
  const [certs, setCerts] = useState<Certificate[]>([])
  const [loading, setLoading] = useState(true)
  const [selected, setSelected] = useState<Certificate | null>(null)

  useEffect(() => {
    fetch(`/api/certificates/child/${child.id}`)
      .then(r => r.json())
      .then(setCerts)
      .catch(() => {})
      .finally(() => setLoading(false))
  }, [child.id])

  return (
    <div style={{ background: '#FFF8F2', minHeight: '100vh', fontFamily: "system-ui,sans-serif", paddingBottom: 40 }}>
      {/* Header */}
      <div style={{ padding: '16px 18px', display: 'flex', alignItems: 'center', gap: 12, borderBottom: '1px solid #F0E4D8', background: 'white' }}>
        <button onClick={onBack} style={{ background: '#FFF0E8', border: '1.5px solid #FFD4B0', borderRadius: 10, padding: '7px 14px', fontSize: 13, fontWeight: 700, color: '#C8704A', cursor: 'pointer' }}>
          ← Retour
        </button>
        <div>
          <div style={{ fontSize: 16, fontWeight: 900, color: '#2D1B0E' }}>Mes certificats 🏆</div>
          <div style={{ fontSize: 12, color: '#C8A090' }}>{child.name} · {certs.length} obtenu{certs.length !== 1 ? 's' : ''}</div>
        </div>
      </div>

      {loading && (
        <div style={{ padding: '40px 18px', textAlign: 'center', color: '#C8A090', fontSize: 16 }}>Chargement...</div>
      )}

      {!loading && certs.length === 0 && (
        <div style={{ padding: '60px 18px', textAlign: 'center' }}>
          <div style={{ fontSize: 48, marginBottom: 16 }}>📚</div>
          <div style={{ fontSize: 16, fontWeight: 800, color: '#2D1B0E', marginBottom: 8 }}>Pas encore de certificat</div>
          <div style={{ fontSize: 13, color: '#C8A090' }}>Complète 80% d'une matière pour obtenir ton premier certificat !</div>
        </div>
      )}

      {!loading && certs.length > 0 && !selected && (
        <div style={{ padding: '16px 18px' }}>
          <div style={{ fontSize: 13, color: '#8A6050', marginBottom: 16 }}>
            Appuie sur un certificat pour le voir en grand.
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(280px,1fr))', gap: 16 }}>
            {certs.map(cert => (
              <div key={cert.subject_id} onClick={() => setSelected(cert)} style={{ cursor: 'pointer', transition: 'transform 0.15s' }}
                onMouseEnter={e => (e.currentTarget.style.transform = 'scale(1.02)')}
                onMouseLeave={e => (e.currentTarget.style.transform = 'scale(1)')}>
                <CertificateSVG cert={cert} />
              </div>
            ))}
          </div>
        </div>
      )}

      {selected && (
        <div style={{ padding: '24px 18px' }}>
          <div style={{ maxWidth: 480, margin: '0 auto' }}>
            <CertificateSVG cert={selected} />
            <div style={{ display: 'flex', gap: 10, marginTop: 16 }}>
              <button onClick={() => setSelected(null)} style={{
                flex: 1, padding: '13px 0', borderRadius: 14, border: '1.5px solid #F0E4D8',
                background: 'white', color: '#2D1B0E', fontSize: 14, fontWeight: 700, cursor: 'pointer'
              }}>
                ← Retour
              </button>
              <button onClick={() => {
                const svg = document.querySelector('svg[viewBox="0 0 440 280"]')
                if (!svg) return
                const blob = new Blob([svg.outerHTML], { type: 'image/svg+xml' })
                const url = URL.createObjectURL(blob)
                const a = document.createElement('a')
                a.href = url
                a.download = `certificat-${selected.child_name}-${selected.subject_name}.svg`
                a.click()
                URL.revokeObjectURL(url)
              }} style={{
                flex: 1, padding: '13px 0', borderRadius: 14, border: 'none',
                background: '#FF8FAB', color: 'white', fontSize: 14, fontWeight: 800, cursor: 'pointer'
              }}>
                ⬇ Télécharger
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
