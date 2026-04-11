import { useState } from "react"

interface Props {
  content: any
  onComplete: (correct: boolean) => void
}

export default function SentenceOrder({ content, onComplete }: Props) {
  const answer: string[] = content.answer || []
  const [pool, setPool] = useState<string[]>(() =>
    [...(content.words || [])].sort(() => Math.random() - 0.5)
  )
  const [sentence, setSentence] = useState<string[]>([])
  const [checked, setChecked] = useState(false)
  const [result, setResult] = useState<boolean | null>(null)

  const addWord = (word: string, idx: number) => {
    if (checked) return
    setSentence(prev => [...prev, word])
    setPool(prev => {
      const next = [...prev]
      next.splice(idx, 1)
      return next
    })
  }

  const removeWord = (idx: number) => {
    if (checked) return
    const word = sentence[idx]
    setSentence(prev => prev.filter((_, i) => i !== idx))
    setPool(prev => [...prev, word])
  }

  const check = () => {
    const ok = sentence.join(' ') === answer.join(' ')
    setResult(ok)
    setChecked(true)
    setTimeout(() => onComplete(ok), 1400)
  }

  return (
    <div>
      {content.question && (
        <p style={{ fontSize: 13, color: '#8A6050', fontWeight: 700, marginBottom: 10 }}>
          {content.question}
        </p>
      )}

      <div style={{
        minHeight: 58, background: '#FFF0E8', borderRadius: 16,
        padding: '10px 12px', marginBottom: 12,
        display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 4,
        border: checked
          ? `1.5px solid ${result ? '#10B981' : '#EF4444'}`
          : '1.5px dashed #FFBFA0'
      }}>
        {sentence.length === 0 ? (
          <span style={{ fontSize: 13, color: '#C8A090', fontStyle: 'italic' }}>
            Clique les mots ci-dessous pour former la phrase...
          </span>
        ) : (
          sentence.map((w, i) => (
            <span
              key={i}
              onClick={() => removeWord(i)}
              style={{
                display: 'inline-flex', padding: '7px 13px',
                borderRadius: 12, fontSize: 13, fontWeight: 700,
                cursor: checked ? 'default' : 'pointer',
                background: '#FF8FAB22', border: '2px solid #FF8FAB',
                color: '#B84050'
              }}
            >
              {w}
            </span>
          ))
        )}
      </div>

      {checked && result === false && (
        <div style={{
          fontSize: 12, color: '#991B1B', marginBottom: 8,
          background: '#FEF2F2', padding: '8px 12px', borderRadius: 10
        }}>
          Bonne réponse : <strong>{answer.join(' ')}</strong>
        </div>
      )}

      <div style={{ display: 'flex', flexWrap: 'wrap', gap: 4, marginBottom: 14 }}>
        {pool.map((w, i) => (
          <span
            key={i}
            onClick={() => addWord(w, i)}
            style={{
              display: 'inline-flex', padding: '8px 14px',
              borderRadius: 12, fontSize: 13, fontWeight: 700,
              cursor: checked ? 'default' : 'pointer',
              background: '#fff', border: '2px solid #E0D4CA', color: '#2D1B0E',
              opacity: checked ? 0.5 : 1
            }}
          >
            {w}
          </span>
        ))}
      </div>

      {!checked && (
        <button
          onClick={check}
          disabled={sentence.length === 0}
          style={{
            width: '100%', padding: '13px 0',
            borderRadius: 16, border: 'none',
            background: sentence.length > 0 ? '#3B82F6' : '#E0D4CA',
            color: 'white', fontSize: 15, fontWeight: 800,
            cursor: sentence.length > 0 ? 'pointer' : 'default'
          }}
        >
          Vérifier
        </button>
      )}

      {checked && result !== null && (
        <div style={{
          borderRadius: 16, padding: '12px 16px',
          background: result ? '#ECFDF5' : '#FEF2F2',
          border: `1.5px solid ${result ? '#6EE7B7' : '#FCA5A5'}`,
          fontWeight: 800, fontSize: 14,
          color: result ? '#065F46' : '#991B1B'
        }}>
          {result ? '🎉 Bonne phrase ! +15 XP' : 'Pas tout à fait, regarde la correction.'}
        </div>
      )}
    </div>
  )
}
