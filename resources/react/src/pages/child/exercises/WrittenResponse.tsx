import { useMemo, useRef, useState } from 'react'
import type { ExerciseCompletionHandler, WrittenResponseContent } from '../../../types/exercise'

interface Props {
  title: string
  instructions: string
  content: WrittenResponseContent
  onComplete: ExerciseCompletionHandler
  onBack: () => void
}

export default function WrittenResponse({ title, instructions, content, onComplete, onBack }: Props) {
  const [text, setText] = useState('')
  const [submissionError, setSubmissionError] = useState('')
  const [submitting, setSubmitting] = useState(false)
  const startedAt = useRef(Date.now())
  const minWords = Math.max(1, content.min_words ?? 12)
  const normalizedWords = useMemo(() => normalizeWords(text), [text])
  const acceptedWords = useMemo(() => new Set((content.accepted_words ?? []).flatMap(normalizeWords)), [content.accepted_words])
  const highlightedTokens = useMemo(() => highlightWords(text, acceptedWords), [acceptedWords, text])
  const words = normalizedWords.length
  const validationError = useMemo(() => {
    if (words < minWords) return `Write at least ${minWords} words before submitting.`

    const requiredSentences = Math.max(0, content.required_sentences ?? 0)
    const sentenceCount = text.match(/[^.!?]+[.!?]+/g)?.length ?? 0
    if (requiredSentences > 0 && sentenceCount < requiredSentences) {
      return `Write ${requiredSentences} complete sentences and finish each one with punctuation.`
    }

    const requiredTerms = new Set((content.required_any_terms ?? []).flatMap(normalizeWords))
    if (requiredTerms.size > 0 && !normalizedWords.some(word => requiredTerms.has(word))) {
      return 'Use at least one family word from the lesson.'
    }

    if (acceptedWords.size > 0) {
      const unrecognizedCount = normalizedWords.filter(word => !acceptedWords.has(word)).length
      const recognizedRatio = words > 0 ? (words - unrecognizedCount) / words : 0
      const minimumRatio = Math.max(0, Math.min(1, content.min_recognized_ratio ?? 0))
      const maximumUnrecognized = Math.max(0, content.max_unrecognized_words ?? Number.MAX_SAFE_INTEGER)
      if (recognizedRatio < minimumRatio || unrecognizedCount > maximumUnrecognized) {
        return 'Use clear English words from the lesson. Check invented or mixed-language words.'
      }
    }

    return ''
  }, [acceptedWords, content, minWords, normalizedWords, text, words])
  const displayedError = submissionError || (text.trim() ? validationError : '')
  const cannotSubmit = submitting || validationError !== ''

  const submit = async () => {
    if (validationError) return
    setSubmitting(true)
    setSubmissionError('')
    try {
      await onComplete(0, {
        verification_status: 'pending_review',
        answers: { text: text.trim() },
        evidence: { word_count: words, review_rubric: ['relevance', 'sentence_structure', 'spelling', 'punctuation'] },
        duration_seconds: Math.max(1, Math.round((Date.now() - startedAt.current) / 1000)),
      })
    } catch (reason) {
      setSubmissionError(reason instanceof Error ? reason.message : 'Could not submit this writing. Check it and try again.')
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div className="adventure-exercise-shell adventure-writing-page" style={{ minHeight: '100vh', background: '#E8DCC8', color: '#3D2B1F', fontFamily: 'Nunito, system-ui, sans-serif' }}>
      <header className="adventure-exercise-header" style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '12px 16px', background: '#F0E8D8', borderBottom: '1px solid #D0C8B8' }}>
        <button onClick={onBack} aria-label="Back" style={{ border: '1px solid #D0C8B8', background: '#F0E8D8', borderRadius: 8, padding: '7px 12px', cursor: 'pointer' }}>←</button>
        <strong>{title}</strong>
      </header>
      <main className="adventure-exercise-content" style={{ maxWidth: 760, margin: '0 auto', padding: 20 }}>
        <p style={{ color: '#7A6050' }}>{instructions}</p>
        <section className="adventure-exercise-panel" style={{ background: '#F0E8D8', border: '1px solid #D0C8B8', borderRadius: 8, padding: 20 }}>
          <h2 style={{ margin: '0 0 14px', fontSize: 21 }}>{content.prompt}</h2>
          {content.checklist && <ul style={{ paddingLeft: 22, color: '#6C5142' }}>{content.checklist.map(item => <li key={item}>{item}</li>)}</ul>}
          <textarea value={text} onChange={event => { setText(event.target.value); setSubmissionError('') }} rows={10} autoCapitalize="sentences" style={{ boxSizing: 'border-box', width: '100%', resize: 'vertical', border: '2px solid #B9AF9F', borderRadius: 8, padding: 14, background: '#FFFDF8', color: '#3D2B1F', font: '600 18px/1.6 Nunito, system-ui, sans-serif' }} />
          {acceptedWords.size > 0 && text.trim() && (
            <div aria-label="Live word check" style={{ marginTop: 10, padding: '12px 14px', background: '#FFFDF8', border: '1px solid #D0C8B8', borderRadius: 8 }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', gap: 12, marginBottom: 8, color: '#7A6050', fontSize: 12, fontWeight: 800 }}>
                <span>Word check</span>
                <span><span style={{ color: '#1D6B2A' }}>Recognized</span> · <span style={{ color: '#B42318' }}>Check</span></span>
              </div>
              <div style={{ whiteSpace: 'pre-wrap', overflowWrap: 'anywhere', color: '#3D2B1F', font: '700 18px/1.8 Nunito, system-ui, sans-serif' }}>
                {highlightedTokens.map((token, index) => token.status === 'plain' ? token.text : (
                  <span key={`${index}-${token.text}`} style={{ color: token.status === 'recognized' ? '#1D6B2A' : '#B42318', textDecorationLine: 'underline', textDecorationThickness: 2, textDecorationStyle: token.status === 'recognized' ? 'solid' : 'wavy', textUnderlineOffset: 4 }}>
                    {token.text}
                  </span>
                ))}
              </div>
            </div>
          )}
          <div style={{ display: 'flex', justifyContent: 'space-between', minHeight: 24, marginTop: 8, fontSize: 13 }}>
            <span role={displayedError ? 'alert' : undefined} style={{ color: '#B42318' }}>{displayedError}</span>
            <span style={{ color: words >= minWords ? '#1D6B2A' : '#7A6050', fontWeight: 800 }}>{words}/{minWords} words minimum</span>
          </div>
          <button disabled={cannotSubmit} onClick={submit} style={{ width: '100%', minHeight: 48, marginTop: 12, border: 0, borderRadius: 8, background: cannotSubmit ? '#8A8A7E' : '#1D6B2A', color: 'white', fontWeight: 900, cursor: submitting ? 'wait' : cannotSubmit ? 'not-allowed' : 'pointer', opacity: submitting ? 0.7 : 1 }}>{submitting ? 'Checking...' : 'Submit for review'}</button>
          <p style={{ margin: '10px 0 0', color: '#7A6050', fontSize: 12, textAlign: 'center' }}>This production will be scored after review.</p>
        </section>
      </main>
    </div>
  )
}

function normalizeWords(value: string): string[] {
  return value
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9'\s]/g, ' ')
    .trim()
    .split(/\s+/)
    .filter(Boolean)
}

function highlightWords(value: string, acceptedWords: Set<string>): Array<{ text: string; status: 'recognized' | 'check' | 'plain' }> {
  const tokens = value.match(/[A-Za-z0-9'\u2018\u2019]+|\s+|[^A-Za-z0-9'\u2018\u2019\s]+/g) ?? []
  return tokens.map(token => {
    const normalized = normalizeWords(token.replace(/[\u2018\u2019]/g, "'"))[0]
    if (!normalized) return { text: token, status: 'plain' }
    return { text: token, status: acceptedWords.has(normalized) ? 'recognized' : 'check' }
  })
}
