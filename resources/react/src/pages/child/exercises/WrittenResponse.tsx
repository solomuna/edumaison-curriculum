import { useEffect, useMemo, useRef, useState } from 'react'
import { MamaJudi } from '../../../services/MamaJudi'
import { SoundService } from '../../../services/SoundService'
import { fireSuccess } from '../../../components/SuccessFx'
import { JUDI, labelsFor, prepareLessonAudio } from '../../../components/lesson/lessonKit'
import type { ExerciseCompletionHandler, WrittenResponseContent } from '../../../types/exercise'

interface Props {
  title: string
  instructions: string
  content: WrittenResponseContent
  isFrench?: boolean
  onComplete: ExerciseCompletionHandler
  onBack: () => void
}

const COPY = {
  en: {
    minWords: (n: number) => `Write at least ${n} words.`,
    sentences: (n: number) => `Write ${n} complete sentences and finish each one with punctuation.`,
    family: 'Use at least one word from the lesson.',
    clear: 'Use clear words from the lesson. Check invented or mixed-language words.',
    counter: (w: number, n: number) => `${w} / ${n} words`,
    wordCheck: 'Word check', recognized: 'Recognised', check: 'Check',
    send: 'Send to my parent', sending: 'Sending…', note: 'Your parent will read your text and give you feedback.',
    refused: 'Not sent yet', fix: 'Fix my text', saveError: 'Could not send your text. Check it and try again.',
  },
  fr: {
    minWords: (n: number) => `Écris au moins ${n} mots.`,
    sentences: (n: number) => `Écris ${n} phrases complètes, chacune terminée par un point.`,
    family: 'Utilise au moins un mot de la leçon.',
    clear: 'Utilise des mots clairs de la leçon. Vérifie les mots inventés ou mélangés.',
    counter: (w: number, n: number) => `${w} / ${n} mots`,
    wordCheck: 'Vérification des mots', recognized: 'Reconnu', check: 'À vérifier',
    send: 'Envoyer à mon parent', sending: 'Envoi…', note: 'Ton parent va lire ton texte et te donner son avis.',
    refused: 'Pas encore envoyé', fix: 'Corriger mon texte', saveError: "Impossible d'envoyer ton texte. Vérifie-le et réessaie.",
  },
}

const SpeakerIcon = () => (
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
    <path d="M11 5 6 9H3v6h3l5 4V5z" fill="currentColor" /><path d="M15.5 8.5a5 5 0 0 1 0 7" /><path d="M18.5 5.5a9 9 0 0 1 0 13" />
  </svg>
)

export default function WrittenResponse({ title, instructions, content, isFrench = false, onComplete, onBack }: Props) {
  const [text, setText] = useState('')
  const [submissionError, setSubmissionError] = useState('')
  const [submitting, setSubmitting] = useState(false)
  const startedAt = useRef(Date.now())
  const textarea = useRef<HTMLTextAreaElement>(null)
  const sendBtn = useRef<HTMLButtonElement>(null)
  const L = labelsFor(isFrench)
  const C = isFrench ? COPY.fr : COPY.en
  const lang = isFrench ? 'fr-FR' : 'en-GB'
  const minWords = Math.max(1, content.min_words ?? 12)
  const normalizedWords = useMemo(() => normalizeWords(text), [text])
  const acceptedWords = useMemo(() => new Set((content.accepted_words ?? []).flatMap(normalizeWords)), [content.accepted_words])
  const highlightedTokens = useMemo(() => highlightWords(text, acceptedWords), [acceptedWords, text])
  const words = normalizedWords.length

  const validationError = useMemo(() => {
    if (words < minWords) return C.minWords(minWords)
    const requiredSentences = Math.max(0, content.required_sentences ?? 0)
    const sentenceCount = text.match(/[^.!?]+[.!?]+/g)?.length ?? 0
    if (requiredSentences > 0 && sentenceCount < requiredSentences) return C.sentences(requiredSentences)
    const requiredTerms = new Set((content.required_any_terms ?? []).flatMap(normalizeWords))
    if (requiredTerms.size > 0 && !normalizedWords.some(word => requiredTerms.has(word))) return C.family
    if (acceptedWords.size > 0) {
      const unrecognizedCount = normalizedWords.filter(word => !acceptedWords.has(word)).length
      const recognizedRatio = words > 0 ? (words - unrecognizedCount) / words : 0
      const minimumRatio = Math.max(0, Math.min(1, content.min_recognized_ratio ?? 0))
      const maximumUnrecognized = Math.max(0, content.max_unrecognized_words ?? Number.MAX_SAFE_INTEGER)
      if (recognizedRatio < minimumRatio || unrecognizedCount > maximumUnrecognized) return C.clear
    }
    return ''
  }, [acceptedWords, content, minWords, normalizedWords, text, words, C])

  const cannotSubmit = submitting || validationError !== ''

  useEffect(() => {
    prepareLessonAudio(isFrench)
    return () => MamaJudi.stop()
  }, [])

  const submit = async () => {
    if (cannotSubmit) return
    setSubmitting(true)
    setSubmissionError('')
    MamaJudi.stop()
    try {
      await onComplete(0, {
        verification_status: 'pending_review',
        answers: { text: text.trim() },
        evidence: { word_count: words, review_rubric: ['relevance', 'sentence_structure', 'spelling', 'punctuation'] },
        duration_seconds: Math.max(1, Math.round((Date.now() - startedAt.current) / 1000)),
      })
      // Envoyé : l'écran se ferme aussitôt (page parente), on salue l'effort.
      SoundService.levelup()
      const rect = sendBtn.current?.getBoundingClientRect()
      fireSuccess({ xp: 10, x: rect ? rect.left + rect.width / 2 : window.innerWidth / 2, y: rect ? rect.top : window.innerHeight * 0.8 })
    } catch (reason) {
      // Refus du serveur (filtre anti-charabia) ou réseau : on laisse corriger.
      SoundService.wrong()
      setSubmissionError(reason instanceof Error ? reason.message : C.saveError)
    } finally {
      setSubmitting(false)
    }
  }

  const fixText = () => {
    setSubmissionError('')
    textarea.current?.focus()
  }

  const progress = Math.max(4, Math.min(100, Math.round(words / minWords * 100)))
  const hint = text.trim() ? validationError : ''

  return (
    <div className="lesson">
      <div className="lesson-top">
        <button className="lesson-icon-btn" onClick={onBack} aria-label={L.close} title={L.close}>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" /></svg>
        </button>
        <div className="lesson-progress" role="progressbar" aria-valuemin={0} aria-valuemax={minWords} aria-valuenow={Math.min(words, minWords)}>
          <div className="lesson-progress__fill" style={{ width: `${progress}%` }} />
        </div>
      </div>

      <div className="lesson-body lesson-body--wide">
        <div className="lesson-kicker">{title}</div>

        <div className="lesson-prompt">
          <img className="lesson-prompt__judi" src={JUDI.explain} alt="" />
          <div className="lesson-bubble">
            {instructions && <div className="lesson-bubble__hint">{instructions}</div>}
            <div className="lesson-bubble__row">
              <button className="lesson-speak" onClick={() => MamaJudi.speakLangAfter(content.prompt, lang, 100, 0.9)} aria-label={L.listen} title={L.listen}><SpeakerIcon /></button>
              <span>{content.prompt}</span>
            </div>
          </div>
        </div>

        {content.checklist && content.checklist.length > 0 && (
          <ul className="lesson-checklist">{content.checklist.map(item => <li key={item}>{item}</li>)}</ul>
        )}

        <textarea
          ref={textarea}
          className="lesson-textarea"
          value={text}
          onChange={event => { setText(event.target.value); setSubmissionError('') }}
          disabled={submitting}
          rows={7}
          autoCapitalize="sentences"
          aria-label={content.prompt}
        />
        <div className="lesson-field-meta">
          <span role={hint ? 'alert' : undefined} className={hint ? 'is-error' : ''}>{hint}</span>
          <span className={words >= minWords ? 'is-ok' : ''}>{C.counter(words, minWords)}</span>
        </div>

        {acceptedWords.size > 0 && text.trim() && (
          <div className="lesson-word-check" aria-label={C.wordCheck}>
            <div className="lesson-word-check__legend">
              <span>{C.wordCheck}</span>
              <span><span className="is-ok">{C.recognized}</span> · <span className="is-check">{C.check}</span></span>
            </div>
            {highlightedTokens.map((token, index) => token.status === 'plain' ? token.text : (
              <span key={`${index}-${token.text}`} className={token.status === 'recognized' ? 'is-ok' : 'is-check'}>{token.text}</span>
            ))}
          </div>
        )}
      </div>

      <div className={`lesson-footer${submissionError ? ' is-wrong' : ''}`} key={submissionError ? 'refused' : 'write'}>
        <div className="lesson-footer__inner">
          {submissionError ? (
            <div className="lesson-verdict" role="alert">
              <img className="lesson-verdict__judi" src={JUDI.encourage} alt="" />
              <div>
                <div className="lesson-verdict__title">{C.refused}</div>
                <div className="lesson-verdict__detail">{submissionError}</div>
              </div>
            </div>
          ) : (
            <div className="lesson-verdict lesson-verdict--note"><span className="lesson-verdict__detail">{C.note}</span></div>
          )}
          {submissionError ? (
            <button className="lesson-btn lesson-btn--red" onClick={fixText}>{C.fix}</button>
          ) : (
            <button ref={sendBtn} className="lesson-btn" onClick={submit} disabled={cannotSubmit}>{submitting ? C.sending : C.send}</button>
          )}
        </div>
      </div>
    </div>
  )
}

function normalizeWords(value: string): string[] {
  return value
    .toLowerCase()
    .normalize('NFD')
    .replace(/[̀-ͯ]/g, '')
    .replace(/[^a-z0-9'\s]/g, ' ')
    .trim()
    .split(/\s+/)
    .filter(Boolean)
}

function highlightWords(value: string, acceptedWords: Set<string>): Array<{ text: string; status: 'recognized' | 'check' | 'plain' }> {
  const tokens = value.match(/[A-Za-z0-9'‘’]+|\s+|[^A-Za-z0-9'‘’\s]+/g) ?? []
  return tokens.map(token => {
    const normalized = normalizeWords(token.replace(/[‘’]/g, "'"))[0]
    if (!normalized) return { text: token, status: 'plain' }
    return { text: token, status: acceptedWords.has(normalized) ? 'recognized' : 'check' }
  })
}
