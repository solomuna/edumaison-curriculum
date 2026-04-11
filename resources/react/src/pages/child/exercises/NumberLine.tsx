import { useState } from "react"

interface Props {
  content: any
  onComplete: (correct: boolean) => void
}

export default function NumberLine({ content, onComplete }: Props) {
  const min: number = content.min ?? 0
  const max: number = content.max ?? 20
  const start: number = content.start ?? 0
  const jumps: number[] = content.jumps || [3]
  const correctAnswer: number = content.answer ?? (start + jumps.reduce((a, b) => a + b, 0))
  const options: number[] = content.options || []

  const [sel, setSel] = useState<number | null>(null)
  const [checked, setChecked] = useState(false)

  // Compute positions
  const range = max - min
  const pct = (n: number) => Math.max(0, Math.min(100, ((n - min) / range) * 100))

  // Animated position after jumps
  const positions: number[] = [start]
  let cur = start
  for (const j of jumps) { cur += j; positions.push(cur) }

  const check = () => {
    if (sel === null) return
    setChecked(true)
    const ok = sel === correctAnswer
    setTimeout(() => onComplete(ok), 1200)
  }

  const tickCount = Math.min(max - min + 1, 21)
  const step = Math.ceil((max - min) / (tickCount - 1))
  const ticks: number[] = []
  for (let i = min; i <= max; i += step) ticks.push(i)
  if (ticks[ticks.length - 1] !== max) ticks.push(max)

  return (
    <div>
      {content.question && (
        <p style={{ fontSize: 14, fontWeight: 800, color: '#2D1B0E', marginBottom: 16, textAlign: 'center' }}>
          {content.question}
        </p>
      )}

      {/* Number line SVG */}
      <div style={{ background: '#FEF3C7', borderRadius: 18, padding: '20px 16px 16px', marginBottom: 16, border: '1.5px solid #FDE68A' }}>
        <div style={{ position: 'relative', height: 80 }}>
          <svg viewBox="0 0 300 80" style={{ width: '100%', height: '100%', overflow: 'visible' }}>
            {/* Main line */}
            <line x1="10" y1="50" x2="290" y2="50" stroke="#D97706" strokeWidth="2.5" strokeLinecap="round"/>
            {/* Arrow */}
            <polygon points="290,46 300,50 290,54" fill="#D97706"/>

            {/* Ticks + labels */}
            {ticks.map(n => {
              const x = 10 + (pct(n) / 100) * 280
              return (
                <g key={n}>
                  <line x1={x} y1="44" x2={x} y2="56" stroke="#D97706" strokeWidth="1.5"/>
                  <text x={x} y="70" textAnchor="middle" fontSize="9" fill="#92400E" fontWeight="bold">
                    {n}
                  </text>
                </g>
              )
            })}

            {/* Jump arcs */}
            {positions.slice(0, -1).map((from, i) => {
              const to = positions[i + 1]
              const x1 = 10 + (pct(from) / 100) * 280
              const x2 = 10 + (pct(to) / 100) * 280
              const mx = (x1 + x2) / 2
              const jump = jumps[i]
              const color = jump > 0 ? '#10B981' : '#EF4444'
              return (
                <g key={i}>
                  <path
                    d={`M ${x1} 50 Q ${mx} ${jump > 0 ? 15 : 80} ${x2} 50`}
                    fill="none" stroke={color} strokeWidth="2" strokeDasharray="4 2"
                  />
                  <text x={mx} y={jump > 0 ? 11 : 76} textAnchor="middle"
                    fontSize="9" fill={color} fontWeight="bold">
                    {jump > 0 ? '+' : ''}{jump}
                  </text>
                  <circle cx={x2} cy="50" r="4" fill={color}/>
                </g>
              )
            })}

            {/* Start dot */}
            <circle cx={10 + (pct(start) / 100) * 280} cy="50" r="5" fill="#3B82F6"/>
            <text
              x={10 + (pct(start) / 100) * 280} y="38"
              textAnchor="middle" fontSize="9" fill="#1D4ED8" fontWeight="bold">
              début
            </text>

            {/* Question mark at end */}
            {!checked && (
              <text
                x={10 + (pct(correctAnswer) / 100) * 280} y="38"
                textAnchor="middle" fontSize="12" fill="#F59E0B" fontWeight="bold">
                ?
              </text>
            )}
          </svg>
        </div>
      </div>

      {/* Options */}
      {options.length > 0 && (
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 8, marginBottom: 14 }}>
          {options.map((opt, i) => {
            let bg = '#fff', border = '#E0D4CA', color = '#2D1B0E'
            if (checked) {
              if (opt === correctAnswer) { bg = '#ECFDF5'; border = '#10B981'; color = '#065F46' }
              else if (opt === sel) { bg = '#FEF2F2'; border = '#EF4444'; color = '#991B1B' }
            } else if (opt === sel) { bg = '#FEF3C7'; border = '#F59E0B'; color = '#92400E' }
            return (
              <div key={i} onClick={() => { if (!checked) setSel(opt) }} style={{
                borderRadius: 14, padding: '12px 10px',
                border: `2px solid ${border}`, background: bg,
                display: 'flex', alignItems: 'center', gap: 10,
                cursor: checked ? 'default' : 'pointer', fontSize: 14, fontWeight: 800, color
              }}>
                <div style={{
                  width: 26, height: 26, borderRadius: 9, flexShrink: 0,
                  background: '#FEF3C7', display: 'flex', alignItems: 'center',
                  justifyContent: 'center', fontWeight: 900, fontSize: 12, color: '#F59E0B'
                }}>{'ABCD'[i]}</div>
                {opt}
              </div>
            )
          })}
        </div>
      )}

      {!checked && (
        <button onClick={check} disabled={sel === null} style={{
          width: '100%', padding: '13px 0', borderRadius: 16, border: 'none',
          background: sel !== null ? '#F59E0B' : '#E0D4CA',
          color: 'white', fontSize: 15, fontWeight: 800,
          cursor: sel !== null ? 'pointer' : 'default'
        }}>
          Vérifier
        </button>
      )}

      {checked && (
        <div style={{
          borderRadius: 16, padding: '12px 16px',
          background: sel === correctAnswer ? '#ECFDF5' : '#FEF2F2',
          border: `1.5px solid ${sel === correctAnswer ? '#6EE7B7' : '#FCA5A5'}`,
          fontWeight: 800, fontSize: 14,
          color: sel === correctAnswer ? '#065F46' : '#991B1B'
        }}>
          {sel === correctAnswer
            ? `🎉 Bravo ! La réponse est bien ${correctAnswer} !`
            : `La bonne réponse est ${correctAnswer}. ${start} ${jumps.map(j => (j > 0 ? '+' : '') + j).join(' ')} = ${correctAnswer}`}
        </div>
      )}
    </div>
  )
}
