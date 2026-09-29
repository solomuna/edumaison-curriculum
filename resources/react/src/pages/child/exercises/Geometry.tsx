import { useState } from 'react'
import { useLesson, useLessonCheck, tapSound, type ReportResult } from '../../../components/lesson/LessonShell'

interface Props {
  content: any
  onComplete: ReportResult
}


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

const panelTone: Record<string, string> = {
  identify_shape: 'lesson-panel--violet',
  identify_line: 'lesson-panel--sky',
  identify_angle: 'lesson-panel--sun',
  draw_line: 'lesson-panel--sky',
}

export default function Geometry({ content, onComplete }: Props) {
  const { t, L, checked } = useLesson()
  const [sel, setSel] = useState<number | null>(null)
  const subtype: string = content.subtype || 'identify_shape'
  const question: string = content.question || ''
  const opts: string[] = content.options || []
  const ans: number = content.answer ?? 0
  const tone = panelTone[subtype] || 'lesson-panel--sky'
  const practiceOnly = opts.length === 0

  // Sans choix (activité de tracé) : entraînement, pas de correction automatique.
  useLessonCheck(practiceOnly || sel !== null, () => {
    if (practiceOnly) { onComplete(false, { practice_only: true }); return }
    if (sel === null) return
    const ok = sel === ans
    onComplete(ok, { selected_index: sel }, ok ? undefined : `${t('The correct answer is', 'La bonne réponse est')} : ${opts[ans]}`)
  })

  const state = (i: number) => {
    if (!checked) return sel === i ? ' is-selected' : ''
    if (i === ans) return ' is-right'
    return i === sel ? ' is-wrong' : ' is-faded'
  }

  return (
    <div>
      {question && <p className="lesson-question">{question}</p>}
      <div className={`lesson-panel ${tone}`}>
        <GeometrySVG content={content} />
      </div>
      {practiceOnly ? (
        <p className="lesson-question">{t('Look at the figure, then press', 'Observe la figure, puis appuie sur')} {L.check}.</p>
      ) : (
        <div className="lesson-choices">
          {opts.map((opt, i) => (
            <button key={i} className={`lesson-choice${state(i)}`} onClick={() => { if (!checked) { tapSound(); setSel(i) } }}
              disabled={checked} role="radio" aria-checked={sel === i}>
              <span className="lesson-choice__key">{i + 1}</span>
              <span>{opt}</span>
            </button>
          ))}
        </div>
      )}
    </div>
  )
}
