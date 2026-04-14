import { useState } from "react"
import Confetti from '../../../components/Confetti'

interface Pair {
  word?: string
  image?: string
  left?: string
  right?: string
}

interface Props {
  content: any
  onComplete: (correct: boolean) => void
}

const COLORS = ['#3B82F6', '#10B981', '#F59E0B', '#EC4899']

export default function MatchPairs({ content, onComplete }: Props) {
  // Guard: content peut arriver comme string JSON depuis FastAPI
  const raw: any = typeof content === 'string' ? (() => { try { return JSON.parse(content) } catch { return {} } })() : content
  const pairs: Pair[] = (raw.pairs || []).map((p: any) => {
    // Format tableau: ["word", "definition"]
    if (Array.isArray(p)) return { word: String(p[0] ?? ''), image: String(p[1] ?? '') }
    // Format objet: {word, image} ou {left, right}
    return {
      word:  String(p.word  ?? p.left  ?? ''),
      image: String(p.image ?? p.right ?? p.definition ?? ''),
    }
  })
  const [rightOrder] = useState<Pair[]>(() => [...pairs].sort(() => Math.random() - 0.5))
  const [selLeft, setSelLeft] = useState<number | null>(null)
  const [matches, setMatches] = useState<Record<number, number>>({})
  const [checked, setChecked] = useState(false)
  const [result, setResult] = useState<boolean | null>(null)

  const allMatched = Object.keys(matches).length === pairs.length

  const pickLeft = (i: number) => {
    if (checked || matches[i] !== undefined) return
    setSelLeft(prev => prev === i ? null : i)
  }

  const pickRight = (i: number) => {
    if (checked || selLeft === null) return
    if (Object.values(matches).includes(i)) return
    setMatches(prev => ({ ...prev, [selLeft]: i }))
    setSelLeft(null)
  }

  const check = () => {
    if (!allMatched) return
    let ok = true
    Object.entries(matches).forEach(([li, ri]) => {
      if (pairs[Number(li)].word !== rightOrder[Number(ri)].word) ok = false
    })
    setResult(ok)
    setChecked(true)
  }

  return (
    <div>
      {content.question && (
        <p style={{ fontSize: 13, color: 'var(--text-soft)', fontWeight: 700, marginBottom: 12 }}>
          {content.question}
        </p>
      )}

      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 10 }}>
        <div>
          {pairs.map((p, i) => {
            const mi = matches[i]
            const isMatched = mi !== undefined
            const col = isMatched ? COLORS[i % 4] : undefined
            return (
              <div
                key={i}
                onClick={() => pickLeft(i)}
                style={{
                  borderRadius: 14, padding: '10px 8px', marginBottom: 8,
                  cursor: isMatched ? 'default' : 'pointer',
                  border: `2px solid ${isMatched ? col : selLeft === i ? '#3B82F6' : '#E0D4CA'}`,
                  background: isMatched ? col + '22' : selLeft === i ? '#EFF6FF' : '#fff',
                  textAlign: 'center', fontSize: 14, fontWeight: 800, color: '#3D2B1F',
                  minHeight: 54, display: 'flex', alignItems: 'center', justifyContent: 'center'
                }}
              >
                {p.word}
              </div>
            )
          })}
        </div>

        <div>
          {rightOrder.map((p, i) => {
            const entry = Object.entries(matches).find(([, ri]) => Number(ri) === i)
            const isMatched = entry !== undefined
            const col = isMatched ? COLORS[Number(entry![0]) % 4] : undefined
            return (
              <div
                key={i}
                onClick={() => pickRight(i)}
                style={{
                  borderRadius: 14, padding: 8, marginBottom: 8,
                  cursor: isMatched ? 'default' : 'pointer',
                  border: `2px solid ${isMatched ? col : '#E0D4CA'}`,
                  background: isMatched ? col + '22' : '#fff',
                  textAlign: 'center', fontSize: 28,
                  minHeight: 54, display: 'flex', alignItems: 'center', justifyContent: 'center'
                }}
              >
                {p.image}
              </div>
            )
          })}
        </div>
      </div>

      {!checked && (
        <button
          onClick={check}
          disabled={!allMatched}
          style={{
            width: '100%', padding: '13px 0', marginTop: 14,
            borderRadius: 16, border: 'none',
            background: allMatched ? '#3B82F6' : '#E0D4CA',
            color: 'white', fontSize: 15, fontWeight: 800,
            cursor: allMatched ? 'pointer' : 'default'
          }}
        >
          Check
        </button>
      )}

      {checked && result === true && <Confetti active={true} />}
      {checked && result !== null && (
        <div style={{ marginTop: 14 }}>
          <div style={{
            borderRadius: 16, padding: '12px 16px',
            background: result ? '#ECFDF5' : '#FEF2F2',
            border: `1.5px solid ${result ? '#6EE7B7' : '#FCA5A5'}`,
            fontWeight: 800, fontSize: 14,
            color: result ? '#065F46' : '#991B1B',
            marginBottom: 10
          }}>
            {result ? 'Perfect! All pairs are correct!' : 'Not quite! Here are the correct answers:'}
          </div>
          {!result && (
            <div style={{ marginBottom: 10 }}>
              {pairs.map((p, i) => (
                <div key={i} style={{
                  display: 'flex', alignItems: 'center', gap: 8,
                  background: '#FFF7ED', borderRadius: 12, padding: '8px 12px', marginBottom: 6,
                  border: '1.5px solid #FCD34D'
                }}>
                  <span style={{ fontWeight: 800, color: '#92400E', flex: 1 }}>{p.word}</span>
                  <span style={{ color: '#B45309' }}>&#8594;</span>
                  <span style={{ fontWeight: 700, color: '#1D6B2A', flex: 1, textAlign: 'right' }}>{p.image}</span>
                </div>
              ))}
            </div>
          )}
          <button
            onClick={() => onComplete(result ?? false)}
            style={{
              width: '100%', padding: '13px 0',
              borderRadius: 16, border: 'none',
              background: result ? '#10B981' : '#F59E0B',
              color: 'white', fontSize: 15, fontWeight: 800,
              cursor: 'pointer'
            }}
          >
            Next
          </button>
        </div>
      )}
    </div>
  )
}
