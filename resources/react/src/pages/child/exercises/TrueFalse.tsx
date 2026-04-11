import { useState } from "react"

interface Props {
  content: any
  onComplete: (correct: boolean) => void
}

export default function TrueFalse({ content, onComplete }: Props) {
  const [answered, setAnswered] = useState(false)
  const [chosen, setChosen] = useState<boolean | null>(null)

  const answer = (v: boolean) => {
    if (answered) return
    setAnswered(true)
    setChosen(v)
    const ok = v === content.answer
    setTimeout(() => onComplete(ok), 1000)
  }

  const isCorrect = chosen !== null && chosen === content.answer

  const btnStyle = (v: boolean) => {
    const base: React.CSSProperties = {
      padding: 18, borderRadius: 18, fontSize: 18, fontWeight: 900,
      cursor: answered ? 'default' : 'pointer', border: '2.5px solid',
      width: '100%'
    }
    if (!answered) {
      return {
        ...base,
        borderColor: v ? '#10B981' : '#EF4444',
        background: v ? '#ECFDF5' : '#FEF2F2',
        color: v ? '#065F46' : '#991B1B'
      }
    }
    if (v === content.answer) {
      return { ...base, borderColor: '#10B981', background: '#ECFDF5', color: '#065F46' }
    }
    if (v === chosen) {
      return { ...base, borderColor: '#EF4444', background: '#FEF2F2', color: '#991B1B' }
    }
    return { ...base, borderColor: '#E0D4CA', background: '#fff', color: '#9CA3AF' }
  }

  return (
    <div>
      <div style={{
        fontSize: 17, fontWeight: 800, color: '#2D1B0E',
        textAlign: 'center', margin: '0 0 20px', lineHeight: 1.4
      }}>
        "{content.statement}"
      </div>

      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
        <button onClick={() => answer(true)} disabled={answered} style={btnStyle(true)}>
          VRAI ✓
        </button>
        <button onClick={() => answer(false)} disabled={answered} style={btnStyle(false)}>
          FAUX ✗
        </button>
      </div>

      {answered && chosen !== null && (
        <div style={{
          borderRadius: 16, padding: '12px 16px', marginTop: 14,
          background: isCorrect ? '#ECFDF5' : '#FEF2F2',
          border: `1.5px solid ${isCorrect ? '#6EE7B7' : '#FCA5A5'}`,
          fontWeight: 800, fontSize: 14,
          color: isCorrect ? '#065F46' : '#991B1B'
        }}>
          {isCorrect
            ? '🎉 Bravo ! C\'est correct !'
            : `La bonne réponse est : ${content.answer ? 'VRAI' : 'FAUX'}`}
        </div>
      )}
    </div>
  )
}
