import { useState, useEffect, useMemo } from 'react'
import Confetti from '../../../components/Confetti'
import { MamaJudi } from '../../../services/MamaJudi'
import { SoundService } from '../../../services/SoundService'
import type { MCQContent } from '../../../types/exercise'

interface Props {
  title: string
  instructions: string
  content: MCQContent
  subject?: string
  onComplete: (score: number) => void
  onBack: () => void
}

const STARS = ['☆', '☆', '☆', '☆', '☆']

function starCount(pct: number) {
  if (pct >= 90) return 5
  if (pct >= 75) return 4
  if (pct >= 55) return 3
  if (pct >= 35) return 2
  return 1
}

function shuffleOptions(options: string[], answerIndex: number) {
  const indexed = options.map((opt, i) => ({ opt, isCorrect: i === answerIndex }))
  for (let i = indexed.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [indexed[i], indexed[j]] = [indexed[j], indexed[i]]
  }
  return { options: indexed.map(x => x.opt), answerIndex: indexed.findIndex(x => x.isCorrect) }
}

function MamaJudiSVG({ size = 44 }: { size?: number }) {
  return (
    <svg viewBox="0 0 56 52" width={size} height={size} xmlns="http://www.w3.org/2000/svg" style={{ flexShrink: 0 }}>
      <circle cx="28" cy="20" r="16" fill="#2A1500"/>
      <circle cx="14" cy="25" r="8" fill="#2A1500"/>
      <circle cx="42" cy="25" r="8" fill="#2A1500"/>
      <circle cx="28" cy="27" r="14" fill="#C8874A"/>
      <ellipse cx="18" cy="27" rx="2.5" ry="3" fill="#B87A40"/>
      <ellipse cx="38" cy="27" rx="2.5" ry="3" fill="#B87A40"/>
      <circle cx="22" cy="22" r="2.8" fill="#1A0A00"/>
      <circle cx="34" cy="22" r="2.8" fill="#1A0A00"/>
      <circle cx="23.2" cy="21" r="1" fill="white"/>
      <circle cx="35.2" cy="21" r="1" fill="white"/>
      <ellipse cx="28" cy="30" rx="2" ry="1.5" fill="#A86835"/>
      <path d="M18 27 Q28 36 38 27" stroke="#1A0A00" strokeWidth="2" fill="none" strokeLinecap="round"/>
    </svg>
  )
}

export default function MCQ({ title, instructions, content, subject, onComplete, onBack }: Props) {
  const [current, setCurrent] = useState(0)
  const [selected, setSelected] = useState<string | null>(null)
  const [results, setResults] = useState<{ correct: boolean; title: string }[]>([])
  const [showResult, setShowResult] = useState(false)

  useEffect(() => {
    if (!showResult) return
    const correct2 = results.filter(r => r.correct).length
    const pct2 = Math.round(correct2 / questions.length * 100)
    if (pct2 === 100)      { setTimeout(() => SoundService.fanfare(),  300); MamaJudi.sessionPerfect() }
    else if (pct2 >= 70)   { setTimeout(() => SoundService.applause(), 300); MamaJudi.sessionGood() }
    else if (pct2 < 50)    { setTimeout(() => SoundService.heartLost(),300); MamaJudi.sessionRetry() }
  }, [showResult])
  const questions = content.questions
  const shuffled = useMemo(() =>
    questions.map(q => shuffleOptions(q.options, typeof q.answer === 'number' ? q.answer : 0))
  , [questions.length])

  const q = questions[current]
  const shuffledQ = shuffled[current] || { options: q?.options || [], answerIndex: 0 }

  useEffect(() => { MamaJudi.speak(instructions) }, [])
  useEffect(() => { if (q) MamaJudi.speak(q.text || q.question || '', 0.85) }, [current])

  const choose = (opt: string, idx: number) => {
    if (selected) return
    setSelected(opt)
    const correct = idx === shuffledQ.answerIndex
    const correctText = shuffledQ.options[shuffledQ.answerIndex]
    if (correct) { MamaJudi.correct(); SoundService.correct() }
    else { MamaJudi.wrong(); SoundService.wrong() }
    setResults(r => [...r, { correct, title: q.text || q.question || '' }])
  }

  const next = () => {
    setSelected(null)
    if (current < questions.length - 1) {
      setCurrent(c => c + 1)
    } else {
      setShowResult(true)
      const total = results.filter(r => r.correct).length
      onComplete(Math.round(total / questions.length * 100))
    }
  }

  // ── Session complete screen ───────────────────────────────────────────────
  if (showResult) {
    const correct = results.filter(r => r.correct).length
    const total = questions.length
    const pct = Math.round(correct / total * 100)
    const stars = starCount(pct)
    const perfect = pct === 100
    const judiMsg = pct >= 80
      ? 'Excellent work! Keep it up!'
      : pct >= 50
        ? 'Bonne tentative, Belle m\u00e8re. Les corrections reviendront en r\u00e9vision SRS.'
        : 'Ne te d\u00e9courage pas ! Revois le cours et r\u00e9essaie.'

    return (
      <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif', paddingBottom: 40 }}>
        <Confetti active={perfect} />

        {/* Header */}
        <div style={{ background: '#E8DCC8', padding: '18px 18px 14px' }}>
          <div style={{ fontSize: 24, fontWeight: 900, color: '#3D2B1F' }}>Session complete!</div>
          <div style={{ fontSize: 14, color: '#7A6050', marginTop: 2 }}>{correct}/{total} correct</div>

          {/* Stars */}
          <div style={{ display: 'flex', gap: 6, marginTop: 12, marginBottom: 4 }}>
            {[1,2,3,4,5].map(i => (
              <span key={i} style={{ fontSize: 28 }}>{i <= stars ? '⭐' : '☆'}</span>
            ))}
          </div>

          {/* Stars earned badge */}
          <div style={{ display: 'inline-block', background: '#FEF3C7', border: '1.5px solid #F59E0B', borderRadius: 20, padding: '4px 14px', fontSize: 13, fontWeight: 700, color: '#B45309', marginTop: 6 }}>
            +{correct * 5} stars earned ⭐
          </div>
        </div>

        {/* Mama Judi message */}
        <div style={{ margin: '14px 16px', background: '#F0E8D8', borderRadius: 18, padding: '14px 16px', border: '1.5px solid #D0C8B8', display: 'flex', gap: 12, alignItems: 'flex-start' }}>
          <MamaJudiSVG size={40} />
          <div style={{ fontSize: 14, color: '#3D2B1F', lineHeight: 1.5, fontWeight: 600, flex: 1 }}>{judiMsg}</div>
        </div>

        {/* Summary */}
        <div style={{ margin: '0 16px' }}>
          <div style={{ fontSize: 11, fontWeight: 900, color: '#7A6050', letterSpacing: '1px', textTransform: 'uppercase', marginBottom: 10 }}>Summary</div>
          {results.map((r, i) => (
            <div key={i} style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '11px 14px', marginBottom: 6, background: '#F0E8D8', borderRadius: 12, border: '1px solid #D0C8B8' }}>
              <span style={{ fontSize: 13, color: '#3D2B1F', flex: 1, marginRight: 10, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                {r.title.length > 38 ? r.title.slice(0, 38) + '...' : r.title}
              </span>
              <span style={{ fontSize: 11, fontWeight: 800, padding: '3px 10px', borderRadius: 20, flexShrink: 0, background: r.correct ? '#D1FAE5' : '#FEE2E2', color: r.correct ? '#065F46' : '#991B1B' }}>
                {r.correct ? 'Ma\u00eetris\u00e9' : '\u00c0 revoir'}
              </span>
            </div>
          ))}
        </div>

        {/* Buttons */}
        <div style={{ padding: '18px 16px 0', display: 'flex', flexDirection: 'column', gap: 10 }}>
          <button onClick={onBack} style={{ width: '100%', padding: '14px 0', borderRadius: 16, border: 'none', background: '#1D6B2A', color: 'white', fontSize: 15, fontWeight: 900, cursor: 'pointer', fontFamily: 'Nunito, system-ui, sans-serif' }}>
            ← Back au tableau de bord
          </button>
          <button onClick={() => { setCurrent(0); setSelected(null); setResults([]); setShowResult(false) }}
            style={{ width: '100%', padding: '13px 0', borderRadius: 16, border: '2px solid #1D6B2A', background: 'transparent', color: '#1D6B2A', fontSize: 14, fontWeight: 800, cursor: 'pointer', fontFamily: 'Nunito, system-ui, sans-serif' }}>
            ↺ Try again
          </button>
        </div>
      </div>
    )
  }

  // ── Exercise screen ───────────────────────────────────────────────────────
  const isCorrectSelected = selected !== null && shuffledQ.options.indexOf(selected) === shuffledQ.answerIndex
  const isLast = current === questions.length - 1

  return (
    <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif' }}>

      {/* Header */}
      <div style={{ background: 'var(--card)', borderBottom: '1px solid var(--border)', padding: '10px 14px 8px' }}>
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 6 }}>
          <button onClick={onBack} style={{ background: 'none', border: 'none', fontSize: 14, fontWeight: 800, color: '#C47A3C', cursor: 'pointer', padding: '4px 0' }}>
            ← Back
          </button>
          <span style={{ fontSize: 13, fontWeight: 800, color: '#1D6B2A' }}>{subject || ''}</span>
        </div>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ fontSize: 13, fontWeight: 700, color: '#7A6050', flexShrink: 0 }}>{current + 1} / {questions.length}</span>
          <div style={{ flex: 1, height: 5, background: 'var(--border)', borderRadius: 3 }}>
            <div style={{ height: 5, borderRadius: 3, background: '#1D6B2A', width: `${Math.round(((current + 1) / questions.length) * 100)}%`, transition: 'width 0.3s' }}/>
          </div>
        </div>
        <div style={{ fontSize: 11, color: '#7A6050', marginTop: 4, fontWeight: 600 }}>
          {subject} — {title}
        </div>
      </div>

      <div style={{ padding: '14px 16px' }}>

        {/* Illustration */}
        {content.illustration && (
          <div style={{ background: '#FFF0E6', borderRadius: 16, padding: '14px', textAlign: 'center', fontSize: 52, marginBottom: 12, border: '1px solid #FFD4B0', lineHeight: 1 }}>
            {content.illustration}
          </div>
        )}

        {/* Question card */}
        <div style={{ background: 'var(--card)', borderRadius: 16, padding: '14px 16px', marginBottom: 14, border: '1.5px solid var(--border)' }}>
          {/* French subtitle */}
          {(q as any).french && (
            <div style={{ fontSize: 12, color: '#C47A3C', fontWeight: 600, marginBottom: 6, lineHeight: 1.4 }}>
              {(q as any).french}
            </div>
          )}
          {/* SVG illustration par question */}
          {(q as any).svg && (
            <div style={{ textAlign: 'center' as const, marginBottom: 10 }}
              dangerouslySetInnerHTML={{ __html: (q as any).svg.replace('<svg', '<svg style="display:block;margin:auto"') }}
            />
          )}
          {/* English question */}
          <div style={{ fontSize: 16, fontWeight: 900, color: 'var(--text-dark)', lineHeight: 1.4 }}>
            {q.text || q.question}
          </div>
        </div>

        {/* Options */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: 9, marginBottom: 14 }}>
          {shuffledQ.options.map((opt: string, i: number) => {
            const isCorrect = i === shuffledQ.answerIndex
            const isChosen = selected === opt
            let bg = 'var(--card)', border = 'var(--border)', color = 'var(--text-dark)'
            if (selected) {
              if (isCorrect) { bg = '#ECFDF5'; border = '#10B981'; color = '#065F46' }
              else if (isChosen) { bg = '#FEF2F2'; border = '#EF4444'; color = '#991B1B' }
            }
            return (
              <div key={i} onClick={() => choose(opt, i)} style={{
                borderRadius: 14, padding: '13px 16px', cursor: selected ? 'default' : 'pointer',
                border: `1.5px solid ${border}`, background: bg, transition: 'all 0.15s'
              }}>
                <span style={{ fontSize: 15, fontWeight: 700, color }}>{opt}</span>
              </div>
            )
          })}
        </div>

        {/* Feedback + explanation */}
        {selected && (
          <div style={{
            borderRadius: 14, padding: '12px 14px', marginBottom: 14,
            background: isCorrectSelected ? '#ECFDF5' : '#FEF2F2',
            border: `1.5px solid ${isCorrectSelected ? '#6EE7B7' : '#FCA5A5'}`,
            display: 'flex', gap: 10, alignItems: 'flex-start'
          }}>
            <span style={{ fontSize: 18, flexShrink: 0 }}>{isCorrectSelected ? '✅' : '\u{1F4A1}'}</span>
            <div style={{ fontSize: 13, fontWeight: 700, color: isCorrectSelected ? '#065F46' : '#991B1B', lineHeight: 1.5 }}>
              {(q as any).explanation
                ? (isCorrectSelected ? 'Correct! ' : 'Almost! ') + (q as any).explanation
                : isCorrectSelected
                  ? 'Correct ! Bonne r\u00e9ponse !'
                  : `La bonne r\u00e9ponse est : ${shuffledQ.options[shuffledQ.answerIndex]}`
              }
            </div>
          </div>
        )}

        {/* Next / See results button */}
        {selected && (
          <button onClick={next} style={{
            width: '100%', padding: '15px 0', borderRadius: 16, border: 'none',
            background: '#1D6B2A', color: 'white', fontSize: 15, fontWeight: 900,
            cursor: 'pointer', fontFamily: 'Nunito, system-ui, sans-serif',
            display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8
          }}>
            {isLast ? 'See results ⭐' : 'Next exercise →'}
          </button>
        )}
      </div>
    </div>
  )
}
