// NumberLine.tsx — Droite numérique : suivre les sauts puis choisir le nombre d'arrivée.
import { useState } from 'react'
import { useLesson, useLessonCheck, tapSound, type ReportResult } from '../../../components/lesson/LessonShell'

interface Props {
  content: any
  onComplete: ReportResult
}

export default function NumberLine({ content, onComplete }: Props) {
  const { t, checked } = useLesson()
  const min: number = content.min ?? 0
  const max: number = content.max ?? 20
  const start: number = content.start ?? 0
  const jumps: number[] = content.jumps || [3]
  const correctAnswer: number = content.answer ?? (start + jumps.reduce((a, b) => a + b, 0))
  const options: number[] = content.options || []
  const [sel, setSel] = useState<number | null>(null)

  const range = max - min
  const pct = (n: number) => Math.max(0, Math.min(100, ((n - min) / range) * 100))
  const positions: number[] = [start]
  let cur = start
  for (const j of jumps) { cur += j; positions.push(cur) }

  const tickCount = Math.min(max - min + 1, 21)
  const step = Math.ceil((max - min) / (tickCount - 1))
  const ticks: number[] = []
  for (let i = min; i <= max; i += step) ticks.push(i)
  if (ticks[ticks.length - 1] !== max) ticks.push(max)

  const equation = `${start} ${jumps.map(j => (j > 0 ? '+ ' : '− ') + Math.abs(j)).join(' ')} = ${correctAnswer}`

  useLessonCheck(sel !== null, () => {
    if (sel === null) return
    const ok = sel === correctAnswer
    onComplete(ok, { selected_value: sel }, ok ? equation : `${t('The answer is', 'La bonne réponse est')} ${correctAnswer} : ${equation}`)
  })

  const state = (opt: number) => {
    if (!checked) return sel === opt ? ' is-selected' : ''
    if (opt === correctAnswer) return ' is-right'
    return opt === sel ? ' is-wrong' : ' is-faded'
  }

  return (
    <div>
      {content.question && <p className="lesson-question">{content.question}</p>}
      <div className="lesson-panel lesson-panel--sun">
        <div style={{ position: 'relative', height: 80, width: '100%' }}>
          <svg viewBox="0 0 300 80" style={{ width: '100%', height: '100%', overflow: 'visible' }}>
            <line x1="10" y1="50" x2="290" y2="50" stroke="#D97706" strokeWidth="2.5" strokeLinecap="round"/>
            <polygon points="290,46 300,50 290,54" fill="#D97706"/>
            {ticks.map(n => {
              const x = 10 + (pct(n) / 100) * 280
              return (
                <g key={n}>
                  <line x1={x} y1="44" x2={x} y2="56" stroke="#D97706" strokeWidth="1.5"/>
                  <text x={x} y="70" textAnchor="middle" fontSize="9" fill="#92400E" fontWeight="bold">{n}</text>
                </g>
              )
            })}
            {positions.slice(0, -1).map((from, i) => {
              const to = positions[i + 1]
              const x1 = 10 + (pct(from) / 100) * 280
              const x2 = 10 + (pct(to) / 100) * 280
              const mx = (x1 + x2) / 2
              const jump = jumps[i]
              const color = jump > 0 ? '#10B981' : '#EF4444'
              return (
                <g key={i}>
                  <path d={`M ${x1} 50 Q ${mx} ${jump > 0 ? 15 : 80} ${x2} 50`} fill="none" stroke={color} strokeWidth="2" strokeDasharray="4 2"/>
                  <text x={mx} y={jump > 0 ? 11 : 76} textAnchor="middle" fontSize="9" fill={color} fontWeight="bold">{jump > 0 ? '+' : ''}{jump}</text>
                  <circle cx={x2} cy="50" r="4" fill={color}/>
                </g>
              )
            })}
            <circle cx={10 + (pct(start) / 100) * 280} cy="50" r="5" fill="#3B82F6"/>
            <text x={10 + (pct(start) / 100) * 280} y="38" textAnchor="middle" fontSize="9" fill="#1D4ED8" fontWeight="bold">{t('start', 'début')}</text>
            {!checked && (
              <text x={10 + (pct(correctAnswer) / 100) * 280} y="38" textAnchor="middle" fontSize="12" fill="#F59E0B" fontWeight="bold">?</text>
            )}
          </svg>
        </div>
      </div>
      {options.length > 0 && (
        <div className="lesson-choices is-grid">
          {options.map((opt, i) => (
            <button key={i} className={`lesson-choice lesson-choice--center${state(opt)}`}
              onClick={() => { if (!checked) { tapSound(); setSel(opt) } }} disabled={checked}
              role="radio" aria-checked={sel === opt}>
              <span className="lesson-choice__key">{i + 1}</span>
              <span>{opt}</span>
            </button>
          ))}
        </div>
      )}
    </div>
  )
}
