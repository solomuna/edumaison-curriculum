import { useState, useEffect } from 'react'
import Confetti from '../../../components/Confetti'
import { MamaJudi } from '../../../services/MamaJudi'

interface FillInItem {
  prompt?: string
  text?: string
  answer: string
}

interface FillInContent {
  type: 'fill_in'
  illustration?: string
  items?: FillInItem[]
  sentences?: FillInItem[]
}

interface Props {
  title: string
  instructions: string
  content: FillInContent
  onComplete: (score: number) => void
  onBack: () => void
}

export default function FillIn({ title, instructions, content, onComplete, onBack }: Props) {
  const [current, setCurrent] = useState(0)
  const [input, setInput] = useState('')
  const [feedback, setFeedback] = useState<'correct' | 'wrong' | null>(null)
  const [scores, setScores] = useState<boolean[]>([])
  const [done, setDone] = useState(false)

  const items = content.items || content.sentences || []
  const item = items[current]
  const text = item?.prompt || item?.text || ''
  const parts = text.split('___')

  useEffect(() => {
        MamaJudi.speak(instructions)
  }, [])

  useEffect(() => {
    setInput('')
    setFeedback(null)
    if (item) MamaJudi.speak(text.replace('___', 'blank'), 0.85)
  }, [current])

  const check = () => {
    const correct = input.trim().toLowerCase() === item.answer.toLowerCase()
    setFeedback(correct ? 'correct' : 'wrong')
    setScores([...scores, correct])
    if (correct) MamaJudi.speak('Correct! Well done!')
    else MamaJudi.speak('The answer is ' + item.answer)
  }

  const next = () => {
    if (current < items.length - 1) {
      setCurrent(current + 1)
    } else {
      setDone(true)
      const total = scores.filter(Boolean).length + (feedback === 'correct' ? 1 : 0)
      onComplete(Math.round((total / items.length) * 100))
    }
  }

  if (done) {
    const total = scores.filter(Boolean).length
    const perfect = total === items.length
    return (
      <div style={{
        background: 'var(--bg)', minHeight: '100vh',
        fontFamily: "-apple-system, BlinkMacSystemFont, 'Trebuchet MS', sans-serif",
        display: 'flex', flexDirection: 'column', alignItems: 'center',
        justifyContent: 'center', padding: '24px 20px', textAlign: 'center'
      }}>
        <Confetti active={perfect} />
        <div style={{ fontSize: 56, marginBottom: 12 }}>{total === items.length ? '🌟' : '👍'}</div>
        <div style={{ fontSize: 26, fontWeight: 900, color: 'var(--text-dark)', marginBottom: 6 }}>
          {total === items.length ? 'Parfait !' : 'Bien joué !'}
        </div>
        <div style={{ fontSize: 15, color: 'var(--text-soft)', marginBottom: 24 }}>
          {total}/{items.length} bonnes réponses
        </div>
        <button onClick={onBack} style={{
          padding: '13px 32px', borderRadius: 16, border: 'none',
          background: '#FF8FAB', color: 'white', fontSize: 15, fontWeight: 800, cursor: 'pointer'
        }}>Retour aux activités</button>
      </div>
    )
  }

  const pct = Math.round((current / items.length) * 100)

  return (
    <div style={{
      background: 'var(--bg)', minHeight: '100vh',
      fontFamily: "-apple-system, BlinkMacSystemFont, 'Trebuchet MS', sans-serif"
    }}>
      {/* Top bar */}
      <div style={{
        display: 'flex', alignItems: 'center', gap: 12,
        padding: '14px 16px', background: 'var(--white)', borderBottom: '1px solid var(--border)'
      }}>
        <button onClick={onBack} style={{
          background: '#FFF0E8', border: '1.5px solid #FFD4B0', borderRadius: 10,
          padding: '6px 12px', fontSize: 13, fontWeight: 700, color: '#C8704A', cursor: 'pointer', flexShrink: 0
        }}>← Retour</button>
        <div style={{ flex: 1 }}>
          <div style={{ fontSize: 13, fontWeight: 800, color: 'var(--text-dark)', marginBottom: 4 }}>{title}</div>
          <div style={{ height: 5, background: 'var(--border)', borderRadius: 3 }}>
            <div style={{ height: 5, borderRadius: 3, background: '#8B5CF6', width: `${pct}%`, transition: 'width 0.3s' }}/>
          </div>
        </div>
        <div style={{ fontSize: 12, color: 'var(--text-soft)', fontWeight: 700, flexShrink: 0 }}>
          {current + 1}/{items.length}
        </div>
      </div>

      <div style={{ padding: 16 }}>
        {/* Illustration */}
        {content.illustration && (
          <div style={{
            background: '#EDE9FE', borderRadius: 20, padding: 16,
            textAlign: 'center', fontSize: 56, marginBottom: 14,
            border: '1px solid #DDD6FE', lineHeight: 1
          }}>
            {content.illustration}
          </div>
        )}

        {/* Sentence card */}
        <div style={{
          background: 'var(--white)', borderRadius: 20, padding: '20px 18px',
          marginBottom: 16, border: '1.5px solid #EDE9FE'
        }}>
          <div style={{ fontSize: 11, color: '#8B5CF6', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: 10 }}>
            Complète la phrase
          </div>
          <div style={{ fontSize: 18, fontWeight: 800, color: 'var(--text-dark)', lineHeight: 1.6, display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 6 }}>
            <span>{parts[0]}</span>
            {feedback ? (
              <span style={{
                padding: '2px 12px', borderRadius: 8, fontWeight: 900,
                background: feedback === 'correct' ? '#D1FAE5' : '#FEE2E2',
                color: feedback === 'correct' ? '#065F46' : '#991B1B',
                border: `2px solid ${feedback === 'correct' ? '#10B981' : '#EF4444'}`
              }}>
                {feedback === 'correct' ? input : item.answer}
              </span>
            ) : (
              <input
                value={input}
                onChange={e => setInput(e.target.value)}
                onKeyDown={e => e.key === 'Enter' && input.trim() && check()}
                placeholder="___"
                autoFocus
                style={{
                  width: Math.max(80, input.length * 12 + 40),
                  padding: '4px 12px', borderRadius: 10, border: '2px solid #8B5CF6',
                  fontSize: 16, fontWeight: 800, color: 'var(--text-dark)',
                  textAlign: 'center', outline: 'none', background: 'var(--input-bg)'
                }}
              />
            )}
            {parts[1] && <span>{parts[1]}</span>}
          </div>
        </div>

        {/* Feedback */}
        {feedback && (
          <div style={{
            borderRadius: 16, padding: '12px 16px', marginBottom: 14,
            background: feedback === 'correct' ? '#ECFDF5' : '#FEF2F2',
            border: `1.5px solid ${feedback === 'correct' ? '#6EE7B7' : '#FCA5A5'}`,
            fontSize: 14, fontWeight: 800,
            color: feedback === 'correct' ? '#065F46' : '#991B1B'
          }}>
            {feedback === 'correct'
              ? '🎉 Bravo ! Bonne réponse !'
              : `La bonne réponse est : "${item.answer}"`}
          </div>
        )}

        {/* Buttons */}
        {!feedback ? (
          <button onClick={check} disabled={!input.trim()} style={{
            width: '100%', padding: '14px 0', borderRadius: 16, border: 'none',
            background: input.trim() ? '#8B5CF6' : '#E0D4CA',
            color: 'white', fontSize: 15, fontWeight: 800,
            cursor: input.trim() ? 'pointer' : 'default'
          }}>Vérifier</button>
        ) : (
          <button onClick={next} style={{
            width: '100%', padding: '14px 0', borderRadius: 16, border: 'none',
            background: current < items.length - 1 ? '#8B5CF6' : '#FF8FAB',
            color: 'white', fontSize: 15, fontWeight: 800, cursor: 'pointer'
          }}>
            {current < items.length - 1 ? 'Phrase suivante →' : 'Voir mes résultats'}
          </button>
        )}
      </div>
    </div>
  )
}
