import { useState } from "react"

interface Props {
  content: any
  onComplete: (correct: boolean) => void
}

export default function VennDiagram({ content, onComplete }: Props) {
  const setA: string[] = content.setA || []
  const setB: string[] = content.setB || []
  const labelA: string = content.labelA || 'A'
  const labelB: string = content.labelB || 'B'
  const correctInter: string[] = content.intersection || []
  const allItems: string[] = content.items || [...setA, ...setB]

  const [placed, setPlaced] = useState<Record<string, 'A' | 'B' | 'AB' | null>>(
    () => Object.fromEntries(allItems.map(i => [i, null]))
  )
  const [checked, setChecked] = useState(false)
  const [result, setResult] = useState<boolean | null>(null)

  const place = (item: string, zone: 'A' | 'B' | 'AB') => {
    if (checked) return
    setPlaced(prev => ({ ...prev, [item]: prev[item] === zone ? null : zone }))
  }

  const unplaced = allItems.filter(i => placed[i] === null)
  const allPlaced = unplaced.length === 0

  const check = () => {
    let ok = true
    allItems.forEach(item => {
      const inA = setA.includes(item)
      const inB = setB.includes(item)
      const isInter = inA && inB
      const p = placed[item]
      if (isInter && p !== 'AB') ok = false
      else if (inA && !inB && p !== 'A') ok = false
      else if (inB && !inA && p !== 'B') ok = false
    })
    setResult(ok)
    setChecked(true)
    setTimeout(() => onComplete(ok), 1200)
  }

  const zoneItems = (zone: 'A' | 'B' | 'AB') =>
    allItems.filter(i => placed[i] === zone)

  const itemColor = (item: string) => {
    if (!checked) return { bg: '#FEF3C7', border: '#F59E0B', color: '#92400E' }
    const inA = setA.includes(item), inB = setB.includes(item)
    const isInter = inA && inB
    const correct = isInter ? placed[item] === 'AB' : inA ? placed[item] === 'A' : placed[item] === 'B'
    return correct
      ? { bg: '#D1FAE5', border: '#10B981', color: '#065F46' }
      : { bg: '#FEE2E2', border: '#EF4444', color: '#991B1B' }
  }

  const Chip = ({ item, onClick }: { item: string; onClick?: () => void }) => {
    const c = itemColor(item)
    return (
      <span onClick={onClick} style={{
        display: 'inline-block', padding: '5px 10px', borderRadius: 10,
        fontSize: 13, fontWeight: 700, margin: 3,
        background: c.bg, border: `2px solid ${c.border}`, color: c.color,
        cursor: checked ? 'default' : 'pointer'
      }}>
        {item}
      </span>
    )
  }

  return (
    <div>
      {content.question && (
        <p style={{ fontSize: 13, fontWeight: 700, color: '#8A6050', marginBottom: 12 }}>
          {content.question}
        </p>
      )}

      {/* Unplaced items pool */}
      {unplaced.length > 0 && (
        <div style={{ background: '#FFF8F2', borderRadius: 14, padding: 10, marginBottom: 14, border: '1.5px dashed #FFB7CB' }}>
          <div style={{ fontSize: 11, color: '#C8A090', fontWeight: 700, marginBottom: 6 }}>
            Clique une zone pour placer l'élément
          </div>
          <div>{unplaced.map(i => <Chip key={i} item={i} />)}</div>
        </div>
      )}

      {/* Venn diagram */}
      <div style={{ position: 'relative', marginBottom: 14, maxWidth: 420, margin: '0 auto 14px' }}>
        <svg viewBox="0 0 320 180" style={{ width: '100%', height: 'auto' }}>
          <ellipse cx="120" cy="80" rx="105" ry="65" fill="#DBEAFE" fillOpacity="0.7" stroke="#3B82F6" strokeWidth="2.5"/>
          <ellipse cx="200" cy="80" rx="105" ry="65" fill="#FEF3C7" fillOpacity="0.7" stroke="#F59E0B" strokeWidth="2.5"/>
          <text x="68" y="22" textAnchor="middle" fontSize="14" fontWeight="bold" fill="#1D4ED8">{labelA}</text>
          <text x="252" y="22" textAnchor="middle" fontSize="14" fontWeight="bold" fill="#B45309">{labelB}</text>
          <text x="160" y="175" textAnchor="middle" fontSize="11" fill="#9CA3AF">{labelA} ∩ {labelB}</text>
        </svg>

        {/* Zone A only */}
        <div
          onClick={() => { /* select current item logic handled via pool */ }}
          style={{
            position: 'absolute', left: '4%', top: '20%',
            width: '28%', height: '60%', zIndex: 2, zIndex: 2,
            display: 'flex', flexWrap: 'wrap', alignContent: 'center',
            justifyContent: 'center', cursor: 'pointer'
          }}
        >
          {zoneItems('A').map(i => <Chip key={i} item={i} onClick={() => place(i, 'A')} />)}
          {zoneItems('A').length === 0 && !checked && (
            <div style={{ fontSize: 10, color: '#93C5FD', textAlign: 'center' }}>
              {labelA} seulement
            </div>
          )}
        </div>

        {/* Zone AB (intersection) */}
        <div style={{
          position: 'absolute', left: '35%', top: '15%',
          width: '30%', height: '70%', zIndex: 3, zIndex: 3,
          display: 'flex', flexWrap: 'wrap', alignContent: 'center',
          justifyContent: 'center', cursor: 'pointer'
        }}>
          {zoneItems('AB').map(i => <Chip key={i} item={i} onClick={() => place(i, 'AB')} />)}
          {zoneItems('AB').length === 0 && !checked && (
            <div style={{ fontSize: 10, color: '#9CA3AF', textAlign: 'center' }}>∩</div>
          )}
        </div>

        {/* Zone B only */}
        <div style={{
          position: 'absolute', right: '4%', top: '20%',
          width: '28%', height: '60%', zIndex: 2, zIndex: 2,
          display: 'flex', flexWrap: 'wrap', alignContent: 'center',
          justifyContent: 'center', cursor: 'pointer'
        }}>
          {zoneItems('B').map(i => <Chip key={i} item={i} onClick={() => place(i, 'B')} />)}
          {zoneItems('B').length === 0 && !checked && (
            <div style={{ fontSize: 10, color: '#FCD34D', textAlign: 'center' }}>
              {labelB} seulement
            </div>
          )}
        </div>
      </div>

      {/* Selection buttons for unplaced items */}
      {!checked && unplaced.length > 0 && (
        <div style={{ display: 'flex', gap: 8, marginBottom: 14 }}>
          {(['A', 'AB', 'B'] as const).map(zone => (
            <button key={zone} onClick={() => {
              const next = unplaced[0]
              if (next) place(next, zone)
            }} style={{
              flex: 1, padding: '10px 4px', borderRadius: 12, border: '2px solid',
              borderColor: zone === 'A' ? '#3B82F6' : zone === 'B' ? '#F59E0B' : '#9CA3AF',
              background: zone === 'A' ? '#EFF6FF' : zone === 'B' ? '#FFFBEB' : '#F9FAFB',
              color: zone === 'A' ? '#1D4ED8' : zone === 'B' ? '#B45309' : '#374151',
              fontSize: 12, fontWeight: 800, cursor: 'pointer'
            }}>
              {zone === 'A' ? `${labelA} seul` : zone === 'B' ? `${labelB} seul` : 'Les deux'}
            </button>
          ))}
        </div>
      )}

      {!checked && (
        <button onClick={check} disabled={!allPlaced} style={{
          width: '100%', padding: '13px 0', borderRadius: 16, border: 'none',
          background: allPlaced ? '#3B82F6' : '#E0D4CA',
          color: 'white', fontSize: 15, fontWeight: 800,
          cursor: allPlaced ? 'pointer' : 'default'
        }}>
          Vérifier
        </button>
      )}

      {checked && result !== null && (
        <div style={{
          borderRadius: 16, padding: '12px 16px', marginTop: 12,
          background: result ? '#ECFDF5' : '#FEF2F2',
          border: `1.5px solid ${result ? '#6EE7B7' : '#FCA5A5'}`,
          fontWeight: 800, fontSize: 14,
          color: result ? '#065F46' : '#991B1B'
        }}>
          {result
            ? '🎉 Parfait ! Tu as bien rempli le diagramme !'
            : `L'intersection de ${labelA} et ${labelB} = {${correctInter.join(', ')}}`}
        </div>
      )}
    </div>
  )
}
