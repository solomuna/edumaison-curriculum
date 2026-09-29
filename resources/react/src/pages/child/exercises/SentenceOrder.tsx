// SentenceOrder.tsx — Remise en ordre : toucher les mots pour construire la phrase.
import { useState } from 'react'
import { useLesson, useLessonCheck, tapSound, type ReportResult } from '../../../components/lesson/LessonShell'

interface Props {
  content: any
  onComplete: ReportResult
}

export default function SentenceOrder({ content, onComplete }: Props) {
  const { t, checked } = useLesson()
  const answer: string[] = content.answer || []
  const [pool, setPool] = useState<string[]>(() => [...(content.words || [])].sort(() => Math.random() - 0.5))
  const [sentence, setSentence] = useState<string[]>([])
  const [result, setResult] = useState<boolean | null>(null)

  const addWord = (word: string, idx: number) => {
    if (checked) return
    tapSound()
    setSentence(prev => [...prev, word])
    setPool(prev => prev.filter((_, i) => i !== idx))
  }

  const removeWord = (idx: number) => {
    if (checked) return
    tapSound()
    setPool(prev => [...prev, sentence[idx]])
    setSentence(prev => prev.filter((_, i) => i !== idx))
  }

  useLessonCheck(sentence.length > 0, () => {
    const ok = sentence.join(' ') === answer.join(' ')
    setResult(ok)
    onComplete(ok, { words: sentence }, ok ? undefined : `${t('Correct sentence:', 'Bonne phrase :')} ${answer.join(' ')}`)
  })

  return (
    <div>
      {content.question && <p className="lesson-question">{content.question}</p>}
      <div className={`lesson-answer-line${result === true ? ' is-right' : result === false ? ' is-wrong' : ''}`}>
        {sentence.length === 0 ? (
          <span className="lesson-answer-line__hint">{t('Tap the words below to build the sentence', 'Touche les mots ci-dessous pour former la phrase')}</span>
        ) : sentence.map((w, i) => (
          <button key={`${w}-${i}`} className="lesson-chip is-placed" onClick={() => removeWord(i)} disabled={checked}>{w}</button>
        ))}
      </div>
      <div className="lesson-chip-bank">
        {pool.map((w, i) => (
          <button key={`${w}-${i}`} className="lesson-chip" onClick={() => addWord(w, i)} disabled={checked}>{w}</button>
        ))}
      </div>
    </div>
  )
}
