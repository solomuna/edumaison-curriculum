import { useState } from "react"

interface Props {
  content: any
  onComplete: (correct: boolean) => void
}

const OPTION_LABELS = ['A', 'B', 'C', 'D']

function GeometrySVG({ content }: { content: any }) {
  const subtype = content.subtype || 'identify_shape'
  const color = content.color || '#8B5CF6'
  const f = color + '33'

  if (subtype === 'identify_line' || subtype === 'draw_line') {
    const type = content.line_type || 'horizontal'
    const lineMap: Record<string, string> = {
      horizontal: `<line x1="20" y1="70" x2="220" y2="70" stroke="${color}" stroke-width="4" stroke-linecap="round"/><text x="120" y="95" text-anchor="middle" font-size="11" fill="#9CA3AF" font-family="sans-serif">Ligne horizontale</text>`,
      vertical: `<line x1="120" y1="10" x2="120" y2="130" stroke="${color}" stroke-width="4" stroke-linecap="round"/><text x="120" y="150" text-anchor="middle" font-size="11" fill="#9CA3AF" font-family="sans-serif">Ligne verticale</text>`,
      oblique: `<line x1="20" y1="15" x2="220" y2="125" stroke="${color}" stroke-width="4" stroke-linecap="round"/><text x="120" y="150" text-anchor="middle" font-size="11" fill="#9CA3AF" font-family="sans-serif">Ligne oblique</text>`,
      parallel: `<line x1="20" y1="50" x2="220" y2="50" stroke="${color}" stroke-width="3" stroke-linecap="round"/><line x1="20" y1="90" x2="220" y2="90" stroke="${color}" stroke-width="3" stroke-linecap="round"/><text x="120" y="115" text-anchor="middle" font-size="11" fill="#9CA3AF" font-family="sans-serif">Lignes parallèles</text>`,
      curved: `<path d="M 20 70 Q 120 15 220 70" stroke="${color}" stroke-width="4" fill="none" stroke-linecap="round"/><text x="120" y="100" text-anchor="middle" font-size="11" fill="#9CA3AF" font-family="sans-serif">Ligne courbe</text>`,
      zigzag: `<polyline points="10,70 55,25 100,70 145,25 190,70 230,45" stroke="${color}" stroke-width="3.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/><text x="120" y="100" text-anchor="middle" font-size="11" fill="#9CA3AF" font-family="sans-serif">Ligne en zigzag</text>`,
      perpendicular: `<line x1="20" y1="80" x2="220" y2="80" stroke="${color}" stroke-width="3" stroke-linecap="round"/><line x1="120" y1="15" x2="120" y2="140" stroke="${color}" stroke-width="3" stroke-linecap="round"/><rect x="120" y="63" width="17" height="17" fill="none" stroke="${color}" stroke-width="2"/><text x="120" y="160" text-anchor="middle" font-size="11" fill="#9CA3AF" font-family="sans-serif">Lignes perpendiculaires</text>`,
    }
    return (
      <svg viewBox="0 0 240 165" width="240" height="165" xmlns="http://www.w3.org/2000/svg" style={{ display: 'block', margin: '0 auto' }}>
        <g dangerouslySetInnerHTML={{ __html: lineMap[type] || lineMap.horizontal }} />
      </svg>
    )
  }

  if (subtype === 'identify_angle') {
    const deg = content.angle || 90
    const r = Math.PI / 180
    const x2 = (70 * Math.cos(-deg * r) + 120).toFixed(1)
    const y2 = (70 * Math.sin(-deg * r) + 110).toFixed(1)
    const sq = deg === 90 ? `<rect x="123" y="93" width="17" height="17" fill="none" stroke="${color}" stroke-width="2"/>` : ''
    const largeArc = deg > 180 ? 1 : 0
    const arcX = (120 + 28 * Math.cos(-deg * r)).toFixed(1)
    const arcY = (110 + 28 * Math.sin(-deg * r)).toFixed(1)
    return (
      <svg viewBox="0 0 240 170" width="240" height="170" xmlns="http://www.w3.org/2000/svg" style={{ display: 'block', margin: '0 auto' }}>
        <line x1="40" y1="110" x2="200" y2="110" stroke={color} strokeWidth="3" strokeLinecap="round"/>
        <line x1="120" y1="110" x2={x2} y2={y2} stroke={color} strokeWidth="3" strokeLinecap="round"/>
        <g dangerouslySetInnerHTML={{ __html: sq }} />
        <path d={`M 148,110 A 28,28 0 ${largeArc},1 ${arcX},${arcY}`} fill="none" stroke={color} strokeWidth="2" opacity="0.7"/>
        <text x="120" y="155" textAnchor="middle" fontSize="13" fill="#374151" fontFamily="sans-serif" fontWeight="bold">{deg}°</text>
      </svg>
    )
  }

  // Default: identify_shape
  const type = content.shape || 'circle'
  const shapeMap: Record<string, string> = {
    triangle: `<polygon points="120,18 210,142 30,142" fill="${f}" stroke="${color}" stroke-width="3" stroke-linejoin="round"/><text x="120" y="162" text-anchor="middle" font-size="12" fill="#374151" font-family="sans-serif" font-weight="bold">Triangle — 3 côtés</text>`,
    square: `<rect x="50" y="18" width="140" height="120" fill="${f}" stroke="${color}" stroke-width="3"/><text x="120" y="158" text-anchor="middle" font-size="12" fill="#374151" font-family="sans-serif" font-weight="bold">Carré — 4 côtés égaux</text>`,
    rectangle: `<rect x="20" y="40" width="200" height="88" fill="${f}" stroke="${color}" stroke-width="3"/><text x="120" y="150" text-anchor="middle" font-size="12" fill="#374151" font-family="sans-serif" font-weight="bold">Rectangle — 4 côtés</text>`,
    circle: `<circle cx="120" cy="80" r="68" fill="${f}" stroke="${color}" stroke-width="3"/><text x="120" y="165" text-anchor="middle" font-size="12" fill="#374151" font-family="sans-serif" font-weight="bold">Cercle</text>`,
    pentagon: `<polygon points="120,14 210,72 178,158 62,158 30,72" fill="${f}" stroke="${color}" stroke-width="3" stroke-linejoin="round"/><text x="120" y="178" text-anchor="middle" font-size="12" fill="#374151" font-family="sans-serif" font-weight="bold">Pentagone — 5 côtés</text>`,
    rhombus: `<polygon points="120,14 210,80 120,148 30,80" fill="${f}" stroke="${color}" stroke-width="3" stroke-linejoin="round"/><text x="120" y="168" text-anchor="middle" font-size="12" fill="#374151" font-family="sans-serif" font-weight="bold">Losange</text>`,
    trapezium: `<polygon points="50,35 190,35 230,145 10,145" fill="${f}" stroke="${color}" stroke-width="3" stroke-linejoin="round"/><text x="120" y="165" text-anchor="middle" font-size="12" fill="#374151" font-family="sans-serif" font-weight="bold">Trapèze</text>`,
    parallelogram: `<polygon points="50,28 210,28 190,138 30,138" fill="${f}" stroke="${color}" stroke-width="3" stroke-linejoin="round"/><text x="120" y="158" text-anchor="middle" font-size="12" fill="#374151" font-family="sans-serif" font-weight="bold">Parallélogramme</text>`,
  }
  return (
    <svg viewBox="0 0 240 185" width="240" height="185" xmlns="http://www.w3.org/2000/svg" style={{ display: 'block', margin: '0 auto' }}>
      <g dangerouslySetInnerHTML={{ __html: shapeMap[type] || shapeMap.circle }} />
    </svg>
  )
}

const bgColors: Record<string, string> = {
  identify_shape: '#EDE9FE',
  identify_line:  '#DBEAFE',
  identify_angle: '#FEF3C7',
  draw_line:      '#DBEAFE',
}

export default function Geometry({ content, onComplete }: Props) {
  const [sel, setSel] = useState<number | null>(null)
  const [checked, setChecked] = useState(false)

  const subtype: string = content.subtype || 'identify_shape'
  const question: string = content.question || ''
  const opts: string[] = content.options || []
  const ans: number = content.answer ?? 0
  const illBg = bgColors[subtype] || '#F3F4F6'

  const check = () => {
    if (sel === null) return
    setChecked(true)
    setTimeout(() => onComplete(sel === ans), 1200)
  }

  return (
    <div>
      {question && (
        <p style={{ fontSize: 14, fontWeight: 800, color: '#2D1B0E', marginBottom: 14, textAlign: 'center', lineHeight: 1.4 }}>
          {question}
        </p>
      )}

      <div style={{
        background: illBg, borderRadius: 20, padding: '16px 12px',
        marginBottom: 14, display: 'flex', alignItems: 'center', justifyContent: 'center'
      }}>
        <GeometrySVG content={content} />
      </div>

      <div style={{ display: 'flex', flexDirection: 'column', gap: 8, marginBottom: 14 }}>
        {opts.map((opt, i) => {
          let bg = 'white', border = '#F0E4D8', textColor = '#2D1B0E'
          let labelBg = '#EDE9FE', labelColor = '#8B5CF6'
          if (checked) {
            if (i === ans) { bg = '#ECFDF5'; border = '#10B981'; textColor = '#065F46'; labelBg = '#10B981'; labelColor = 'white' }
            else if (i === sel) { bg = '#FEF2F2'; border = '#EF4444'; textColor = '#991B1B'; labelBg = '#EF4444'; labelColor = 'white' }
          } else if (i === sel) {
            bg = '#EDE9FE'; border = '#8B5CF6'; labelBg = '#8B5CF6'; labelColor = 'white'
          }
          return (
            <div key={i} onClick={() => { if (!checked) setSel(i) }} style={{
              borderRadius: 14, padding: '11px 14px', cursor: checked ? 'default' : 'pointer',
              border: `2px solid ${border}`, background: bg,
              display: 'flex', alignItems: 'center', gap: 10
            }}>
              <div style={{
                width: 28, height: 28, borderRadius: 9, flexShrink: 0,
                background: labelBg, display: 'flex', alignItems: 'center',
                justifyContent: 'center', fontWeight: 900, fontSize: 12, color: labelColor
              }}>
                {OPTION_LABELS[i]}
              </div>
              <span style={{ fontSize: 13, fontWeight: 700, color: textColor }}>{opt}</span>
            </div>
          )
        })}
      </div>

      {checked && (
        <div style={{
          borderRadius: 14, padding: '11px 14px', marginBottom: 12,
          background: sel === ans ? '#ECFDF5' : '#FEF2F2',
          border: `1.5px solid ${sel === ans ? '#6EE7B7' : '#FCA5A5'}`,
          fontWeight: 800, fontSize: 13,
          color: sel === ans ? '#065F46' : '#991B1B'
        }}>
          {sel === ans ? '🎉 Bravo ! Bonne réponse !' : `La bonne réponse est : ${opts[ans]}`}
        </div>
      )}

      {!checked && (
        <button onClick={check} disabled={sel === null} style={{
          width: '100%', padding: '13px 0', borderRadius: 16, border: 'none',
          background: sel !== null ? '#8B5CF6' : '#E0D4CA',
          color: 'white', fontSize: 15, fontWeight: 800,
          cursor: sel !== null ? 'pointer' : 'default'
        }}>
          Vérifier
        </button>
      )}
    </div>
  )
}
