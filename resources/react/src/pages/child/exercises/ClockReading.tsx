import { useState } from 'react'
import { useLesson, useLessonCheck, tapSound, type ReportResult } from '../../../components/lesson/LessonShell'

interface Props {
  content: any
  onComplete: ReportResult
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
  const { t, checked } = useLesson()
  const [sel, setSel] = useState<number | null>(null)
  const opts: string[] = content.options || []
  const ans: number = content.answer ?? 0
  const hours: number = content.hours || 0
  const minutes: number = content.minutes || 0

  useLessonCheck(sel !== null, () => {
    if (sel === null) return
    const ok = sel === ans
    onComplete(ok, { selected_index: sel },
      ok ? t(`Yes, it is ${opts[ans]}!`, `Oui, il est ${opts[ans]} !`)
         : t(`It is ${opts[ans]}. Look carefully at the hands!`, `Il est ${opts[ans]}. Regarde bien les aiguilles !`))
  })

  const state = (i: number) => {
    if (!checked) return sel === i ? ' is-selected' : ''
    if (i === ans) return ' is-right'
    return i === sel ? ' is-wrong' : ' is-faded'
  }

  return (
    <div>
      {content.question && <p className="lesson-question">{content.question}</p>}
      <div className="lesson-panel lesson-panel--sun">
        <ClockSVG hours={hours} minutes={minutes} accentColor="#F59E0B" />
      </div>
      <div className="lesson-choices is-grid">
        {opts.map((o, i) => (
          <button key={i} className={`lesson-choice${state(i)}`} onClick={() => { if (!checked) { tapSound(); setSel(i) } }}
            disabled={checked} role="radio" aria-checked={sel === i}>
            <span className="lesson-choice__key">{i + 1}</span>
            <span>{o}</span>
          </button>
        ))}
      </div>
    </div>
  )
}
