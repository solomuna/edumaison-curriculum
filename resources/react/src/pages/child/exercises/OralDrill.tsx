// Exercice oral dans la boucle de leçon : écouter Mama Judi, répéter au micro,
// verdict immédiat par correspondance des mots reconnus (comme Duolingo).
// La prononciation elle-même n'est pas jugée ici (pronunciation_verified=false).
import { useEffect, useRef, useState } from 'react'
import { MamaJudi } from '../../../services/MamaJudi'
import { fireSuccess } from '../../../components/SuccessFx'
import LessonEnd from '../../../components/lesson/LessonEnd'
import { useRetryQueue } from '../../../components/lesson/useRetryQueue'
import { JUDI, XP_PER_CORRECT, labelsFor, randomPraise, playVerdict, prepareLessonAudio } from '../../../components/lesson/lessonKit'
import type { ExerciseCompletionHandler, OralDrillContent } from '../../../types/exercise'

interface Props {
  title: string
  instructions: string
  content: OralDrillContent
  isFrench?: boolean
  onComplete: ExerciseCompletionHandler
  onBack: () => void
}

interface OralResponse {
  target: string
  transcript: string
  score: number
  method: 'speech_transcript' | 'practice_only'
  audio_data_url?: string
}

const normalizeSpeech = (s: string) => s.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim().replace(/[^a-z0-9\s]/g, '')

// Similarite entre deux chaines (0-100)
function similarity(a: string, b: string): number {
  const na = normalizeSpeech(a)
  const nb = normalizeSpeech(b)
  if (na === nb) return 100
  // Verifier si le texte reconnu contient le mot attendu
  if (nb.includes(na) || na.includes(nb)) return 90
  const wa = na.split(/\s+/).filter(Boolean)
  const wb = nb.split(/\s+/).filter(Boolean)
  if (wa.length === 0) return 0
  const rows = Array.from({ length: wa.length + 1 }, () => new Array(wb.length + 1).fill(0))
  for (let i = 0; i <= wa.length; i++) rows[i][0] = i
  for (let j = 0; j <= wb.length; j++) rows[0][j] = j
  for (let i = 1; i <= wa.length; i++) {
    for (let j = 1; j <= wb.length; j++) {
      rows[i][j] = Math.min(
        rows[i - 1][j] + 1,
        rows[i][j - 1] + 1,
        rows[i - 1][j - 1] + (wa[i - 1] === wb[j - 1] ? 0 : 1),
      )
    }
  }
  return Math.max(0, Math.round((1 - rows[wa.length][wb.length] / Math.max(wa.length, wb.length)) * 100))
}

/** Mots de la phrase attendue retrouvés (ou non) dans ce que le navigateur a entendu. */
function wordMatches(target: string, transcript: string): Array<{ text: string; ok: boolean }> {
  const pool = normalizeSpeech(transcript).split(/\s+/).filter(Boolean)
  return target.split(/(\s+)/).map(part => {
    const key = normalizeSpeech(part)
    if (!key) return { text: part, ok: true }
    const at = pool.indexOf(key)
    if (at >= 0) pool.splice(at, 1)
    return { text: part, ok: at >= 0 }
  })
}

/** Une phrase dite est réussie à partir de 70 % de mots reconnus. */
const PASS = 70

const labels = {
  en: {
    defaultInstructions: 'Listen to Mama Judi, then say the sentence.', listen: 'Listen', playing: 'Mama Judi is speaking…',
    listenFirst: 'Listen first, then tap the microphone.', tapToSpeak: 'Tap to speak', listening: 'Listening… tap to stop',
    youSaid: 'You said', matched: 'Words heard', missing: 'Words to work on',
    cantSpeak: 'Can’t speak now', keepGoing: 'Keep practising!', wordMatch: 'word match', preparing: 'Preparing your recording…',
    noSpeech: 'No speech detected. Try again!', notHeard: 'Could not hear you. Try again!',
    audioError: 'Listening audio is unavailable. Check the media volume and try again.',
    audioSaveError: 'Audio could not be saved, but your spoken answer can still be checked.',
    unsupported: 'Speech recognition is not available on this device. You can still practise aloud, but this activity will not receive a verified score.',
    practise: 'Say it aloud, then continue', saveVoice: 'Save my voice for parent review',
    saveVoiceNote: 'The recording is private and can be deleted by your parent.', skipped: 'Practised without the microphone',
  },
  fr: {
    defaultInstructions: 'Écoute Mama Judi, puis dis la phrase.', listen: 'Écouter', playing: 'Mama Judi parle…',
    listenFirst: 'Écoute d’abord, puis touche le micro.', tapToSpeak: 'Touche pour parler', listening: 'Je t’écoute… touche pour arrêter',
    youSaid: 'Tu as dit', matched: 'Mots entendus', missing: 'Mots à retravailler',
    cantSpeak: 'Je ne peux pas parler', keepGoing: 'Continue à t’entraîner !', wordMatch: 'des mots reconnus', preparing: 'Préparation de l’enregistrement…',
    noSpeech: 'Je n’ai rien entendu. Réessaie !', notHeard: 'Je ne t’ai pas bien entendu. Réessaie !',
    audioError: "L'audio d'écoute est indisponible. Vérifie le volume et réessaie.",
    audioSaveError: 'Ta voix n’a pas pu être gardée, mais ta réponse peut quand même être vérifiée.',
    unsupported: 'La reconnaissance vocale n’est pas disponible sur cet appareil. Tu peux t’entraîner à voix haute, mais l’activité ne sera pas notée.',
    practise: 'Dis-la à voix haute, puis continue', saveVoice: 'Garder ma voix pour mes parents',
    saveVoiceNote: 'L’enregistrement reste privé et tes parents peuvent l’effacer.', skipped: 'Entraînement sans micro',
  },
}

const SpeakerIcon = () => (
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
    <path d="M11 5 6 9H3v6h3l5 4V5z" fill="currentColor" /><path d="M15.5 8.5a5 5 0 0 1 0 7" /><path d="M18.5 5.5a9 9 0 0 1 0 13" />
  </svg>
)

const MicIcon = ({ stop }: { stop: boolean }) => stop ? (
  <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="6" y="6" width="12" height="12" rx="2.5" fill="currentColor" /></svg>
) : (
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" aria-hidden="true">
    <rect x="9" y="3" width="6" height="11" rx="3" fill="currentColor" /><path d="M5.5 11a6.5 6.5 0 0 0 13 0" /><path d="M12 17.5V21" />
  </svg>
)

export default function OralDrill({ title, instructions, content, isFrench: isFrenchProp, onComplete, onBack }: Props) {
  const isFrench = isFrenchProp ?? content.language === 'fr-FR'
  const t = isFrench ? labels.fr : labels.en
  const L = labelsFor(isFrench)
  const items = content.items
  // Phrases ratées reprises en fin de leçon ; seule la 1re réponse est envoyée et notée.
  const rq = useRetryQueue(items.length)
  const current = rq.current
  const item = items[current]
  const isLast = rq.isLastStep

  const [heard, setHeard] = useState(false)
  const [speaking, setSpeaking] = useState(false)
  const [recording, setRecording] = useState(false)
  const [speechSupported, setSpeechSupported] = useState(false)
  const [audioConsent, setAudioConsent] = useState(false)
  const [saveAudio, setSaveAudio] = useState(false)
  const [audioDataUrl, setAudioDataUrl] = useState('')
  const [error, setError] = useState('')
  const [review, setReview] = useState<{ transcript: string; score: number; praise: string; retry: boolean; skipped: boolean } | null>(null)
  const [responses, setResponses] = useState<OralResponse[]>([])
  const [done, setDone] = useState(false)
  const [streak, setStreak] = useState(0)
  const [bestStreak, setBestStreak] = useState(0)
  const startedAt = useRef(Date.now())
  const recognitionRef = useRef<any>(null)
  const mediaRecorderRef = useRef<MediaRecorder | null>(null)
  const mediaStreamRef = useRef<MediaStream | null>(null)
  const stopTimerRef = useRef<number | null>(null)
  const lessonAudioRef = useRef<HTMLAudioElement | null>(null)
  const cancelVoice = useRef<() => void>(() => {})
  const micBtn = useRef<HTMLButtonElement>(null)

  useEffect(() => {
    prepareLessonAudio()
    const SR = (window as any).SpeechRecognition || (window as any).webkitSpeechRecognition
    setSpeechSupported(!!SR)
    fetch('/api/language-practice/settings', { headers: { Accept: 'application/json' } })
      .then(response => response.ok ? response.json() : null)
      .then(settings => setAudioConsent(Boolean(settings?.speaking_audio_enabled)))
      .catch(() => setAudioConsent(false))
    return () => {
      lessonAudioRef.current?.pause()
      lessonAudioRef.current = null
      cancelVoice.current()
      MamaJudi.stop()
      recognitionRef.current?.abort?.()
      if (stopTimerRef.current) window.clearTimeout(stopTimerRef.current)
      mediaStreamRef.current?.getTracks().forEach(track => track.stop())
    }
  }, [])

  const playLessonAudio = (source: string): Promise<boolean> => new Promise(resolve => {
    lessonAudioRef.current?.pause()
    const audio = new Audio(source)
    lessonAudioRef.current = audio
    let settled = false
    const finish = (success: boolean) => {
      if (settled) return
      settled = true
      if (lessonAudioRef.current === audio) lessonAudioRef.current = null
      resolve(success)
    }
    audio.onended = () => finish(true)
    audio.onerror = () => finish(false)
    audio.play().catch(() => finish(false))
  })

  const listen = async () => {
    if (recording || speaking || review) return
    setSpeaking(true)
    setError('')
    MamaJudi.stop()
    const audioKey = !isFrench && item.audio_hint?.trim().toLowerCase().replace(/[^a-z0-9-]/g, '')
    let played = audioKey ? await playLessonAudio(`/sounds/lessons/en/${encodeURIComponent(audioKey)}.mp3`) : false
    if (!played) played = await MamaJudi.speakLang(item.text, isFrench ? 'fr-FR' : 'en-GB')
    if (played) setHeard(true)
    else setError(t.audioError)
    setSpeaking(false)
  }

  const stopAudioCapture = () => {
    if (stopTimerRef.current) window.clearTimeout(stopTimerRef.current)
    stopTimerRef.current = null
    const recorder = mediaRecorderRef.current
    if (recorder && recorder.state !== 'inactive') recorder.stop()
    else {
      mediaStreamRef.current?.getTracks().forEach(track => track.stop())
      mediaStreamRef.current = null
      setRecording(false)
    }
  }

  const verdict = (transcript: string, score: number) => {
    const good = score >= PASS
    setReview({ transcript, score, praise: randomPraise(L), retry: rq.isRetry, skipped: false })
    rq.record(good)
    cancelVoice.current()
    if (good) {
      const next = streak + 1
      setStreak(next)
      setBestStreak(b => Math.max(b, next))
      cancelVoice.current = playVerdict(true, next)
      const rect = micBtn.current?.getBoundingClientRect()
      fireSuccess({ xp: XP_PER_CORRECT, x: rect ? rect.left + rect.width / 2 : window.innerWidth / 2, y: rect ? rect.top : window.innerHeight * 0.6 })
    } else {
      setStreak(0)
      cancelVoice.current = playVerdict(false)
    }
  }

  const startRecording = async () => {
    if (review || speaking) return
    if (!heard) { setError(t.listenFirst); return }
    const SR = (window as any).SpeechRecognition || (window as any).webkitSpeechRecognition
    if (!SR) return
    lessonAudioRef.current?.pause()
    MamaJudi.stop()
    setError('')
    setAudioDataUrl('')
    if (saveAudio && audioConsent && navigator.mediaDevices?.getUserMedia && 'MediaRecorder' in window) {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true })
        mediaStreamRef.current = stream
        const chunks: BlobPart[] = []
        const preferredType = MediaRecorder.isTypeSupported('audio/webm;codecs=opus') ? 'audio/webm;codecs=opus' : ''
        const recorder = new MediaRecorder(stream, { ...(preferredType ? { mimeType: preferredType } : {}), audioBitsPerSecond: 24000 })
        mediaRecorderRef.current = recorder
        recorder.ondataavailable = event => { if (event.data.size > 0) chunks.push(event.data) }
        recorder.onstop = () => {
          const blob = new Blob(chunks, { type: recorder.mimeType || 'audio/webm' })
          const reader = new FileReader()
          reader.onloadend = () => {
            setAudioDataUrl(typeof reader.result === 'string' ? reader.result : '')
            setRecording(false)
          }
          reader.readAsDataURL(blob)
          stream.getTracks().forEach(track => track.stop())
          mediaStreamRef.current = null
        }
        recorder.start(250)
      } catch {
        setSaveAudio(false)
        setError(t.audioSaveError)
      }
    }
    const recognition = new SR()
    recognitionRef.current = recognition
    recognition.lang = isFrench ? 'fr-FR' : 'en-GB'
    recognition.interimResults = false
    recognition.maxAlternatives = 3
    recognition.onstart = () => setRecording(true)
    recognition.onend = () => stopAudioCapture()
    recognition.onerror = (e: any) => {
      stopAudioCapture()
      if (e.error === 'no-speech') setError(t.noSpeech)
      else if (e.error === 'not-allowed') { setSpeechSupported(false); setError('') }
      else setError(t.notHeard)
    }
    recognition.onresult = (e: any) => {
      // Prendre la meilleure alternative
      let best = ''
      let bestScore = 0
      for (let i = 0; i < e.results[0].length; i++) {
        const alt = e.results[0][i].transcript
        const s = similarity(item.text, alt)
        if (s > bestScore || !best) { bestScore = s; best = alt }
      }
      verdict(best, bestScore)
    }
    recognition.start()
    stopTimerRef.current = window.setTimeout(() => recognition.stop(), 12000)
  }

  const stopRecording = () => {
    recognitionRef.current?.stop()
    stopAudioCapture()
  }

  // « Je ne peux pas parler » : la phrase est notée entraînement (practice_only),
  // elle n'est pas reprise en fin de leçon.
  const skip = () => {
    if (recording || review) return
    lessonAudioRef.current?.pause()
    MamaJudi.stop()
    setError('')
    setStreak(0)
    setReview({ transcript: '', score: 0, praise: '', retry: rq.isRetry, skipped: true })
    rq.record(true)
  }

  const next = () => {
    if (!review || recording) return
    cancelVoice.current()
    MamaJudi.stop()
    let all = responses
    if (!review.retry) {
      all = [...responses, review.skipped
        ? { target: item.text, transcript: '', score: 0, method: 'practice_only' as const }
        : { target: item.text, transcript: review.transcript, score: review.score, method: 'speech_transcript' as const, ...(audioDataUrl ? { audio_data_url: audioDataUrl } : {}) }]
      setResponses(all)
    }
    if (isLast) { setDone(true); return }
    rq.advance()
    setHeard(false)
    setAudioDataUrl('')
    setReview(null)
    setError('')
  }

  // Enregistrement depuis l'écran de fin : LessonEnd affiche l'erreur éventuelle.
  const submit = () => {
    const score = Math.round(responses.reduce((sum, r) => sum + r.score, 0) / Math.max(1, responses.length))
    const verificationStatus = responses.some(r => r.method === 'practice_only') ? 'practice_only' : 'auto_checked'
    return onComplete(score, {
      verification_status: verificationStatus,
      answers: { items: responses },
      evidence: { method: 'speech_transcript_match', pronunciation_verified: false },
      duration_seconds: Math.max(1, Math.round((Date.now() - startedAt.current) / 1000)),
    })
  }

  // Entrée : continuer après le verdict.
  useEffect(() => {
    if (done) return
    const onKey = (e: KeyboardEvent) => {
      if (e.defaultPrevented || e.key !== 'Enter' || !review) return
      e.preventDefault()
      next()
    }
    window.addEventListener('keydown', onKey)
    return () => window.removeEventListener('keydown', onKey)
  })

  if (done) {
    const accuracy = Math.round(responses.reduce((sum, r) => sum + r.score, 0) / Math.max(1, responses.length))
    return (
      <LessonEnd
        isFrench={isFrench}
        results={items.map((it, i) => ({ title: it.text, correct: responses[i]?.method === 'speech_transcript' && responses[i].score >= PASS }))}
        total={items.length}
        bestStreak={bestStreak}
        accuracy={accuracy}
        onContinue={submit}
      />
    )
  }

  const answered = rq.resolvedCount
  const progress = Math.max(4, Math.round(answered / items.length * 100))
  const good = !!review && !review.skipped && review.score >= PASS
  const words = review && !review.skipped ? wordMatches(item.text, review.transcript) : []
  const footerTone = !review ? '' : review.skipped ? ' is-info' : good ? ' is-right' : ' is-wrong'

  return (
    <div className="lesson">
      <div className="lesson-top">
        <button className="lesson-icon-btn" onClick={onBack} aria-label={L.close} title={L.close}>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" /></svg>
        </button>
        <div className={`lesson-progress${streak >= 3 ? ' is-streak' : ''}`} role="progressbar" aria-valuemin={0} aria-valuemax={items.length} aria-valuenow={answered}>
          <div className="lesson-progress__fill" style={{ width: `${progress}%` }} />
        </div>
        <div className={`lesson-streak${streak >= 2 ? ' is-on' : ''}`} aria-live="polite">🔥 {streak}</div>
      </div>

      <div className="lesson-body">
        <div className="lesson-kicker">{title}</div>

        {rq.isRetry && <div className="lesson-retry-tag">🔁 {L.again}</div>}
        <div className="lesson-prompt">
          <img className="lesson-prompt__judi" src={JUDI.explain} alt="" />
          <div className="lesson-bubble">
            <div className="lesson-bubble__hint">{instructions || t.defaultInstructions}</div>
            <button className="lesson-listen" onClick={listen} disabled={speaking || recording || !!review}>
              <span className="lesson-listen__icon"><SpeakerIcon /></span>
              <span>{speaking ? t.playing : t.listen}</span>
            </button>
          </div>
        </div>

        {content.illustration && <div className="lesson-media lesson-media--emoji">{content.illustration}</div>}

        <div className="lesson-oral-target" style={item.color ? { borderColor: item.color } : undefined}>
          {item.color && <span className="lesson-oral-target__swatch" style={{ background: item.color }} />}
          {review && words.length > 0 ? (
            <span aria-label={`${t.matched} / ${t.missing}`}>
              {words.map((w, i) => w.text.trim() ? <span key={i} className={w.ok ? 'is-ok' : 'is-check'}>{w.text}</span> : w.text)}
            </span>
          ) : <span>{item.text}</span>}
        </div>

        {speechSupported ? (
          <>
            {audioConsent && (
              <label className="lesson-consent">
                <input type="checkbox" checked={saveAudio} disabled={recording || !!review} onChange={event => setSaveAudio(event.target.checked)} />
                <span><strong>{t.saveVoice}</strong><br /><small>{t.saveVoiceNote}</small></span>
              </label>
            )}
            <button
              ref={micBtn}
              className={`lesson-mic${recording ? ' is-recording' : ''}`}
              onClick={recording ? stopRecording : startRecording}
              disabled={!!review || speaking || (!recording && !heard)}
              aria-label={recording ? t.listening : t.tapToSpeak}
            >
              <span className="lesson-mic__icon"><MicIcon stop={recording} /></span>
              <span>{recording ? t.listening : heard ? t.tapToSpeak : t.listenFirst}</span>
            </button>
          </>
        ) : (
          <div className="lesson-review-card">{t.unsupported}</div>
        )}

        {review && !review.skipped && (
          <div className="lesson-review-card">
            <div className="lesson-review-card__row"><strong>{t.youSaid} :</strong> « {review.transcript} »</div>
          </div>
        )}
        {error && <div role="alert" className="lesson-field-meta"><span className="is-error">{error}</span></div>}
      </div>

      <div className={`lesson-footer${footerTone}`} key={review ? `v${rq.step}` : `q${rq.step}`}>
        <div className="lesson-footer__inner">
          {review ? (
            <div className="lesson-verdict" role="status">
              <img className="lesson-verdict__judi" src={good ? JUDI.celebrate : JUDI.encourage} alt="" />
              <div>
                <div className="lesson-verdict__title">{review.skipped ? t.skipped : `${good ? review.praise : t.keepGoing} · ${review.score}% ${t.wordMatch}`}</div>
                {recording ? <div className="lesson-verdict__detail">{t.preparing}</div> : !good && !review.skipped && <div className="lesson-verdict__detail">{item.text}</div>}
              </div>
            </div>
          ) : (
            <button className="lesson-btn lesson-btn--ghost" onClick={skip} disabled={recording}>{speechSupported ? t.cantSpeak : t.practise}</button>
          )}
          {review ? (
            <button className={`lesson-btn${good || review.skipped ? '' : ' lesson-btn--red'}`} onClick={next} disabled={recording} autoFocus>{isLast ? L.finish : L.continue}</button>
          ) : <span />}
        </div>
      </div>
    </div>
  )
}
