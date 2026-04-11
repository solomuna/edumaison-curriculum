import { useState } from "react"

interface Props {
  content: any
  onComplete: (correct: boolean) => void
}

function ClockSVG({ hours, minutes, accentColor }: { hours: number, minutes: number, accentColor: string }) {
  const cx = 100, cy = 100
  const hAngle = ((hours % 12) * 30 + minutes * 0.5 - 90) * Math.PI / 180
  const mAngle = (minutes * 6 - 90) * Math.PI / 180
  const hx = (cx + 55 * Math.cos(hAngle)).toFixed(1)
  const hy = (cy + 55 * Math.sin(hAngle)).toFixed(1)
  const mx = (cx + 76 * Math.cos(mAngle)).toFixed(1)
  const my = (cy + 76 * Math.sin(mAngle)).toFixed(1)

  const markers = Array.from({ length: 12 }, (_, i) => {
    const a = (i * 30 - 90) * Math.PI / 180
    const isMain = i % 3 === 0
    const r1 = 86, r2 = r1 - (isMain ? 12 : 7)
    return {
      x1: (cx + r1 * Math.cos(a)).toFixed(1),
      y1: (cy + r1 * Math.sin(a)).toFixed(1),
      x2: (cx + r2 * Math.cos(a)).toFixed(1),
      y2: (cy + r2 * Math.sin(a)).toFixed(1),
      w: isMain ? 3 : 1.5
    }
  })

  const nums = [
    { n: '12', x: 100, y: 26 },
    { n: '3', x: 174, y: 105 },
    { n: '6', x: 100, y: 178 },
    { n: '9', x: 26, y: 105 }
  ]

  return (
    <svg viewBox="0 0 200 200" width="160" height="160">
      <circle cx={cx} cy={cy} r="88" fill="white" stroke="#E5E7EB" strokeWidth="3" />
      <circle cx={cx} cy={cy} r="88" fill={accentColor} fillOpacity="0.06" />
      {markers.map((m, i) => (
        <line key={i} x1={m.x1} y1={m.y1} x2={m.x2} y2={m.y2}
          stroke="#D1D5DB" strokeWidth={m.w} strokeLinecap="round" />
      ))}
      {nums.map(({ n, x, y }) => (
        <text key={n} x={x} y={y} textAnchor="middle" dominantBaseline="middle"
          fontSize="14" fontWeight="bold" fontFamily="sans-serif" fill="#374151">
          {n}
        </text>
      ))}
      <line x1={cx} y1={cy} x2={hx} y2={hy} stroke="#1F2937" strokeWidth="6" strokeLinecap="round" />
      <line x1={cx} y1={cy} x2={mx} y2={my} stroke="#3B82F6" strokeWidth="3.5" strokeLinecap="round" />
      <circle cx={cx} cy={cy} r="5" fill="#1F2937" />
    </svg>
  )
}

export default function ClockReading({ content, onComplete }: Props) {
  const [sel, setSel] = useState<number | null>(null)
  const [checked, setChecked] = useState(false)

  const opts: string[] = content.options || []
  const ans: number = content.answer ?? 0
  const hours: number = content.hours || 0
  const minutes: number = content.minutes || 0

  const check = () => {
    if (sel === null) return
    setChecked(true)
    const ok = sel === ans
    setTimeout(() => onComplete(ok), 1200)
  }

  const optStyle = (i: number): React.CSSProperties => {
    const base: React.CSSProperties = {
      borderRadius: 14, padding: '11px 14px', cursor: checked ? 'default' : 'pointer',
      border: '2px solid', background: '#fff',
      display: 'flex', alignItems: 'center', gap: 10,
      fontSize: 14, fontWeight: 700, color: '#2D1B0E'
    }
    if (!checked) {
      return { ...base, borderColor: sel === i ? '#F59E0B' : '#F0E4D8',
        background: sel === i ? '#FFFBEB' : '#fff' }
    }
    if (i === ans) return { ...base, borderColor: '#10B981', background: '#ECFDF5', color: '#065F46' }
    if (i === sel && sel !== ans) return { ...base, borderColor: '#EF4444', background: '#FEF2F2', color: '#991B1B' }
    return { ...base, borderColor: '#F0E4D8' }
  }

  return (
    <div>
      {content.question && (
        <p style={{ fontSize: 13, color: '#8A6050', fontWeight: 700, marginBottom: 8, textAlign: 'center' }}>
          {content.question}
        </p>
      )}

      <div style={{
        textAlign: 'center', background: '#FEF3C7', borderRadius: 18,
        padding: 16, marginBottom: 14
      }}>
        <ClockSVG hours={hours} minutes={minutes} accentColor="#F59E0B" />
      </div>

      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 8, marginBottom: 14 }}>
        {opts.map((o, i) => (
          <div key={i} onClick={() => { if (!checked) setSel(i) }} style={optStyle(i)}>
            <div style={{
              width: 26, height: 26, borderRadius: 9, flexShrink: 0,
              background: '#FEF3C7', display: 'flex', alignItems: 'center',
              justifyContent: 'center', fontWeight: 900, fontSize: 12, color: '#F59E0B'
            }}>
              {'ABCD'[i]}
            </div>
            {o}
          </div>
        ))}
      </div>

      {!checked && (
        <button
          onClick={check}
          disabled={sel === null}
          style={{
            width: '100%', padding: '13px 0', borderRadius: 16, border: 'none',
            background: sel !== null ? '#F59E0B' : '#E0D4CA',
            color: 'white', fontSize: 15, fontWeight: 800,
            cursor: sel !== null ? 'pointer' : 'default'
          }}
        >
          Vérifier
        </button>
      )}

      {checked && (
        <div style={{
          borderRadius: 16, padding: '12px 16px',
          background: sel === ans ? '#ECFDF5' : '#FEF2F2',
          border: `1.5px solid ${sel === ans ? '#6EE7B7' : '#FCA5A5'}`,
          fontWeight: 800, fontSize: 14,
          color: sel === ans ? '#065F46' : '#991B1B'
        }}>
          {sel === ans
            ? `🎉 Bravo ! Il est bien ${opts[ans]} !`
            : `Il est ${opts[ans]}. Regarde bien les aiguilles !`}
        </div>
      )}
    </div>
  )
}
