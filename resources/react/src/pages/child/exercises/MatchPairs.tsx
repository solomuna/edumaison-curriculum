import { useState } from 'react'
import { useLesson, useLessonCheck, tapSound, type ReportResult } from '../../../components/lesson/LessonShell'

interface Pair {
  word?: string
  image?: string
  left?: string
  right?: string
}

interface Props {
  content: any
  onComplete: ReportResult
}


export default function MatchPairs({ content, onComplete }: Props) {
  const { t, checked } = useLesson()
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
  const [result, setResult] = useState<boolean | null>(null)

  const allMatched = Object.keys(matches).length === pairs.length

  const pickLeft = (i: number) => {
    if (checked || matches[i] !== undefined) return
    tapSound()
    setSelLeft(prev => prev === i ? null : i)
  }

  const pickRight = (i: number) => {
    if (checked || selLeft === null) return
    if (Object.values(matches).includes(i)) return
    tapSound()
    setMatches(prev => ({ ...prev, [selLeft]: i }))
    setSelLeft(null)
  }

  useLessonCheck(allMatched, () => {
    let ok = true
    Object.entries(matches).forEach(([li, ri]) => {
      if (pairs[Number(li)].word !== rightOrder[Number(ri)].word) ok = false
    })
    setResult(ok)
    onComplete(ok, {
      pairs: Object.entries(matches).map(([leftIndex, rightIndex]) => ({
        left: pairs[Number(leftIndex)].word,
        right: rightOrder[Number(rightIndex)].image,
      })),
    }, ok ? undefined : t('Here are the correct pairs above.', 'Les bonnes paires sont affichées au-dessus.'))
  })

  // Toucher une paire déjà formée la défait (avant vérification).
  const unmatch = (leftIndex: number) => {
    if (checked) return
    tapSound()
    setMatches(prev => { const next = { ...prev }; delete next[leftIndex]; return next })
  }

  const isEmojiMode = pairs.length > 0 && pairs.every(p => (p.image || '').length <= 4)

  const pairOf = (rightIndex: number) => Object.entries(matches).find(([, ri]) => Number(ri) === rightIndex)
  const tone = (leftIndex: number) => {
    if (!checked || result === null) return ''
    const ri = matches[leftIndex]
    return ri !== undefined && pairs[leftIndex].word === rightOrder[ri].word ? ' is-right' : ' is-wrong'
  }

  return (
    <div>
      {content.question && <p className="lesson-question">{content.question}</p>}
      <div className="lesson-match">
        <div className="lesson-match__col">
          {pairs.map((p, i) => {
            const matched = matches[i] !== undefined
            return (
              <button key={i} className={`lesson-tile${selLeft === i ? ' is-selected' : ''}${matched ? ` is-paired c${i % 4}` : ''}${tone(i)}`}
                onClick={() => (matched ? unmatch(i) : pickLeft(i))} disabled={checked}>
                {p.word}
              </button>
            )
          })}
        </div>
        <div className="lesson-match__col">
          {rightOrder.map((p, i) => {
            const entry = pairOf(i)
            const li = entry ? Number(entry[0]) : -1
            return (
              <button key={i} className={`lesson-tile${isEmojiMode ? ' is-emoji' : ''}${entry ? ` is-paired c${li % 4}` : ''}${entry ? tone(li) : ''}`}
                onClick={() => (entry ? unmatch(li) : pickRight(i))} disabled={checked}>
                {p.image}
              </button>
            )
          })}
        </div>
      </div>
      {checked && result === false && (
        <div className="lesson-correction">
          {pairs.map((p, i) => (
            <div key={i} className="lesson-correction__row">
              <span>{p.word}</span><span aria-hidden="true">→</span><span>{p.image}</span>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
