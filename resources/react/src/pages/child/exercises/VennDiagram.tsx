// VennDiagram.tsx — Diagramme de Venn : prendre un élément, puis toucher sa zone.
import { useState } from 'react'
import { useLesson, useLessonCheck, tapSound, type ReportResult } from '../../../components/lesson/LessonShell'

interface Props {
  content: any
  onComplete: ReportResult
}

type Zone = 'A' | 'B' | 'AB'

export default function VennDiagram({ content, onComplete }: Props) {
  const { t, checked } = useLesson()
  const setA: string[] = content.setA || []
  const setB: string[] = content.setB || []
  const labelA: string = content.labelA || 'A'
  const labelB: string = content.labelB || 'B'
  const correctInter: string[] = content.intersection || []
  const allItems: string[] = content.items || [...setA, ...setB]
  const [placed, setPlaced] = useState<Record<string, Zone | null>>(() => Object.fromEntries(allItems.map(i => [i, null])))
  const unplaced = allItems.filter(i => placed[i] === null)
  const [picked, setPicked] = useState<string | null>(null)
  const active = picked && placed[picked] === null ? picked : unplaced[0] ?? null

  const expected = (item: string): Zone => (setA.includes(item) && setB.includes(item) ? 'AB' : setA.includes(item) ? 'A' : 'B')

  const placeIn = (zone: Zone) => {
    if (checked || !active) return
    tapSound()
    setPlaced(prev => ({ ...prev, [active]: zone }))
    setPicked(null)
  }

  const takeBack = (item: string) => {
    if (checked) return
    tapSound()
    setPlaced(prev => ({ ...prev, [item]: null }))
    setPicked(item)
  }

  useLessonCheck(unplaced.length === 0, () => {
    const ok = allItems.every(item => placed[item] === expected(item))
    onComplete(ok, { placements: placed },
      ok ? undefined : `${t('In both', 'Dans les deux')} (${labelA} ∩ ${labelB}) : ${correctInter.join(', ') || '∅'}`)
  })

  const chipTone = (item: string) => (!checked ? '' : placed[item] === expected(item) ? ' is-right' : ' is-wrong')
  const zoneItems = (zone: Zone) => allItems.filter(i => placed[i] === zone)

  const zones: { zone: Zone; title: string; tone: string }[] = [
    { zone: 'A', title: t(`${labelA} only`, `${labelA} seulement`), tone: 'is-a' },
    { zone: 'AB', title: t('Both', 'Les deux'), tone: 'is-ab' },
    { zone: 'B', title: t(`${labelB} only`, `${labelB} seulement`), tone: 'is-b' },
  ]

  return (
    <div>
      {content.question && <p className="lesson-question">{content.question}</p>}

      {unplaced.length > 0 && !checked && (
        <div className="lesson-chip-bank lesson-chip-bank--pool">
          {unplaced.map(i => (
            <button key={i} className={`lesson-chip${i === active ? ' is-active' : ''}`} onClick={() => { tapSound(); setPicked(i) }}>{i}</button>
          ))}
        </div>
      )}

      <div className="lesson-venn">
        <svg viewBox="0 0 320 170" className="lesson-venn__art" aria-hidden="true">
          <ellipse cx="120" cy="85" rx="105" ry="70" fill="#DBEAFE" fillOpacity="0.75" stroke="#3B82F6" strokeWidth="2.5" />
          <ellipse cx="200" cy="85" rx="105" ry="70" fill="#FEF3C7" fillOpacity="0.75" stroke="#F59E0B" strokeWidth="2.5" />
          <text x="62" y="20" textAnchor="middle" fontSize="14" fontWeight="bold" fill="#1D4ED8">{labelA}</text>
          <text x="258" y="20" textAnchor="middle" fontSize="14" fontWeight="bold" fill="#B45309">{labelB}</text>
        </svg>
        <div className="lesson-venn__zones">
          {zones.map(({ zone, title, tone }) => (
            <div key={zone} className={`lesson-venn__zone ${tone}`}>
              {zoneItems(zone).map(i => (
                <button key={i} className={`lesson-chip is-placed${chipTone(i)}`} onClick={() => takeBack(i)} disabled={checked}>{i}</button>
              ))}
            </div>
          ))}
        </div>
      </div>

      {!checked && unplaced.length > 0 && (
        <div className="lesson-choices is-grid is-grid-3">
          {zones.map(({ zone, title, tone }) => (
            <button key={zone} className={`lesson-choice lesson-choice--center lesson-choice--zone ${tone}`} onClick={() => placeIn(zone)} disabled={!active}>
              <span>{title}</span>
            </button>
          ))}
        </div>
      )}
    </div>
  )
}
