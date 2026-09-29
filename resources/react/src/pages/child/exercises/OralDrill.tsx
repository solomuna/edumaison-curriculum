// OralDrill.tsx — Ecoute + Reconnaissance vocale Web Speech API

import { useState, useEffect, useRef } from 'react'

import { MamaJudi } from '../../../services/MamaJudi'

import { SoundService } from '../../../services/SoundService'
import { fireSuccess } from '../../../components/SuccessFx'

import type { ExerciseCompletionHandler, OralDrillContent } from '../../../types/exercise'

import Ardoise from './Ardoise'



interface Props {

  title: string

  instructions: string

  content: OralDrillContent
  isFrench?: boolean

  onComplete: ExerciseCompletionHandler

  onBack: () => void

}



// Similarite entre deux chaines (0-100)

function similarity(a: string, b: string): number {

  const normalize = (s: string) => s.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim().replace(/[^a-z0-9\s]/g, '')

  const na = normalize(a)

  const nb = normalize(b)

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



const C = {

  bg: '#E8DCC8', card: '#F0E8D8', green: '#1D6B2A',

  golden: '#C47A3C', dark: '#3D2B1F', soft: '#7A6050',

  border: '#D0C8B8', red: '#CE1126',

}



export default function OralDrill({ title, instructions, content, isFrench, onComplete, onBack }: Props) {

  const [current, setCurrent] = useState(0)

  const [speaking, setSpeaking] = useState(false)

  const [listened, setListened] = useState<boolean[]>([])

  const [recording, setRecording] = useState(false)

  const [transcript, setTranscript] = useState('')

  const [score, setScore] = useState<number | null>(null)

  const [scores, setScores] = useState<number[]>([])

  const [responses, setResponses] = useState<Array<{ target: string; transcript: string; score: number; method: 'speech_transcript' | 'practice_only'; audio_data_url?: string }>>([])

  const [done, setDone] = useState(false)

  const [speechSupported, setSpeechSupported] = useState(false)

  const [error, setError] = useState('')

  const [submitting, setSubmitting] = useState(false)

  const [showArdoise, setShowArdoise] = useState(false)

  const [audioConsent, setAudioConsent] = useState(false)

  const [saveAudio, setSaveAudio] = useState(false)

  const [audioDataUrl, setAudioDataUrl] = useState('')

  const recognitionRef = useRef<any>(null)

  const mediaRecorderRef = useRef<MediaRecorder | null>(null)

  const mediaStreamRef = useRef<MediaStream | null>(null)

  const stopTimerRef = useRef<number | null>(null)

  const lessonAudioRef = useRef<HTMLAudioElement | null>(null)



  const items = content.items

  const item = items[current]



  useEffect(() => {

    MamaJudi.speak(instructions)

    setListened(new Array(items.length).fill(false))

    // Verifier support Web Speech API

    const SR = (window as any).SpeechRecognition || (window as any).webkitSpeechRecognition

    setSpeechSupported(!!SR)

    fetch('/api/language-practice/settings', { headers: { Accept: 'application/json' } })
      .then(response => response.ok ? response.json() : null)
      .then(settings => setAudioConsent(Boolean(settings?.speaking_audio_enabled)))
      .catch(() => setAudioConsent(false))

    return () => {
      lessonAudioRef.current?.pause()
      lessonAudioRef.current = null
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

  const playItem = async () => {

    if (recording || submitting) return

    setSpeaking(true)
    setError('')
    setTranscript('')
    setScore(null)
    setAudioDataUrl('')

    MamaJudi.stop()
    const audioKey = !isFrench && item.audio_hint?.trim().toLowerCase().replace(/[^a-z0-9-]/g, '')
    let played = audioKey ? await playLessonAudio(`/sounds/lessons/en/${encodeURIComponent(audioKey)}.mp3`) : false
    if (!played) played = await MamaJudi.speakLang(item.text, isFrench ? 'fr-FR' : 'en-GB')

    if (played) {
      setListened(values => values.map((value, index) => index === current ? true : value))
    } else {
      setError('Listening audio is unavailable. Check the media volume and try again.')
    }
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

  const startRecording = async () => {

    if (!listened[current] || speaking || submitting) {

      setError('Listen first, then repeat the words you heard.')

      return

    }

    const SR = (window as any).SpeechRecognition || (window as any).webkitSpeechRecognition

    if (!SR) return

    setError('')

    setTranscript('')

    setScore(null)

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

        setError('Audio could not be saved, but your spoken answer can still be checked.')

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

      if (e.error === 'no-speech') setError('No speech detected. Try again!')

      else if (e.error === 'not-allowed') { setSpeechSupported(false); setError('') }

      else setError('Could not hear you. Try again!')

    }

    recognition.onresult = (e: any) => {

      // Prendre la meilleure alternative

      let best = ''

      let bestScore = 0

      for (let i = 0; i < e.results[0].length; i++) {

        const alt = e.results[0][i].transcript

        const s = similarity(item.text, alt)

        if (s > bestScore) { bestScore = s; best = alt }

      }

      setTranscript(best)

      setScore(bestScore)

      // Feedback sonore et vocal

      if (bestScore >= 80) {

        SoundService.correct()

        fireSuccess({ xp: 10 })

        MamaJudi.speak('The words matched very well!')

      } else if (bestScore >= 50) {

        SoundService.streak()

        MamaJudi.speak('Good try! Listen again and repeat.')

      } else {

        MamaJudi.speak('Listen carefully and try again.')

      }

    }

    recognition.start()

    stopTimerRef.current = window.setTimeout(() => recognition.stop(), 12000)

  }



  const stopRecording = () => {

    recognitionRef.current?.stop()

    stopAudioCapture()

  }



  const next = async () => {

    if (score === null) {

      setError('Speak first so your answer can be checked.')

      return

    }

    if (recording) {

      setError('Wait a moment while the recording is prepared.')

      return

    }

    const finalScore = score

    const newScores = [...scores, finalScore]

    const newResponses = [...responses, {

      target: item.text,

      transcript,

      score: finalScore,

      method: 'speech_transcript' as const,

      ...(audioDataUrl ? { audio_data_url: audioDataUrl } : {}),

    }]

    if (current < items.length - 1) {

      setScores(newScores)

      setResponses(newResponses)

      setTranscript('')

      setScore(null)

      setAudioDataUrl('')

      setError('')

      setCurrent(current + 1)

    } else {

      const avg = Math.round(newScores.reduce((a, b) => a + b, 0) / newScores.length)

      const verificationStatus = newResponses.some(response => response.method === 'practice_only') ? 'practice_only' : 'auto_checked'

      setSubmitting(true)

      setError('')

      try {

        await onComplete(avg, {

          verification_status: verificationStatus,

          answers: { items: newResponses },

          evidence: { method: 'speech_transcript_match', pronunciation_verified: false },

        })

        setScores(newScores)

        setResponses(newResponses)

        setDone(true)

      } catch (reason) {

        setError(reason instanceof Error ? reason.message : 'Could not save this speaking activity. Try again.')

      } finally {

        setSubmitting(false)

      }

    }

  }



  const skip = async () => {

    if (recording || submitting) return

    const newScores = [...scores, 0]

    const newResponses = [...responses, { target: item.text, transcript: '', score: 0, method: 'practice_only' as const }]

    if (current < items.length - 1) {

      setScores(newScores)

      setResponses(newResponses)

      setTranscript('')

      setScore(null)

      setAudioDataUrl('')

      setError('')

      setCurrent(current + 1)

    }

    else {

      const avg = Math.round(newScores.reduce((a, b) => a + b, 0) / newScores.length)

      setSubmitting(true)

      setError('')

      try {

        await onComplete(avg, {

          verification_status: 'practice_only',

          answers: { items: newResponses },

          evidence: { method: 'speech_transcript_match', pronunciation_verified: false },

        })

        setScores(newScores)

        setResponses(newResponses)

        setDone(true)

      } catch (reason) {

        setError(reason instanceof Error ? reason.message : 'Could not save this speaking activity. Try again.')

      } finally {

        setSubmitting(false)

      }

    }

  }



  if (done) {

    const avg = scores.length > 0 ? Math.round(scores.reduce((a, b) => a + b, 0) / scores.length) : 0

    return (

      <div className="adventure-result-page" style={{ background: C.bg, minHeight: '100vh', fontFamily: 'Nunito, sans-serif', display: 'flex', flexDirection: 'column' as const, alignItems: 'center', justifyContent: 'center', padding: '24px 20px', textAlign: 'center' }}>

        <div style={{ fontSize: 56, marginBottom: 12 }}>{avg >= 80 ? '⭐' : avg >= 50 ? '👍' : '\u{1F4AA}'}</div>

        <div style={{ fontSize: 26, fontWeight: 900, color: C.dark, marginBottom: 6 }}>

          {avg >= 80 ? 'Excellent!' : avg >= 50 ? 'Good effort!' : 'Keep practising!'}

        </div>

        <div style={{ fontSize: 18, fontWeight: 800, color: C.green, marginBottom: 4 }}>{avg}% word match</div>

        <div style={{ fontSize: 14, color: C.soft, marginBottom: 28 }}>{title}</div>

        <button onClick={onBack} style={{ padding: '13px 32px', borderRadius: 16, border: 'none', background: C.green, color: 'white', fontSize: 15, fontWeight: 800, cursor: 'pointer', fontFamily: 'Nunito, sans-serif' }}>

          Back to activities

        </button>

      </div>

    )

  }



  const pct = Math.round((current / items.length) * 100)

  const scoreColor = score === null ? C.soft : score >= 80 ? C.green : score >= 50 ? C.golden : C.red

  const scoreLabel = score === null ? '' : score >= 80 ? 'Excellent!' : score >= 50 ? 'Good try!' : 'Try again!'

  const canStartRecording = listened[current] && !speaking && !submitting

  const canContinue = score !== null && !recording && !submitting



  return (

    <div className="adventure-exercise-shell adventure-speaking-page" style={{ background: C.bg, minHeight: '100vh', fontFamily: 'Nunito, sans-serif' }}>

      {showArdoise && <Ardoise onClose={() => setShowArdoise(false)} />}



      {/* Top bar */}

      <div className="adventure-exercise-header" style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '12px 16px', background: C.card, borderBottom: '1px solid ' + C.border }}>

        <button onClick={onBack} style={{ background: C.card, border: '1.5px solid ' + C.border, borderRadius: 10, padding: '6px 12px', fontSize: 13, fontWeight: 700, color: C.soft, cursor: 'pointer', flexShrink: 0 }}>

          &#8592;

        </button>

        <div style={{ flex: 1 }}>

          <div style={{ fontSize: 13, fontWeight: 800, color: C.dark, marginBottom: 3 }}>{title}</div>

          <div style={{ height: 4, background: C.border, borderRadius: 2 }}>

            <div style={{ height: 4, borderRadius: 2, background: C.green, width: pct + '%', transition: 'width .3s' }}/>

          </div>

        </div>

        <div style={{ fontSize: 12, color: C.soft, fontWeight: 700, flexShrink: 0 }}>{current + 1}/{items.length}</div>

      </div>



      <div style={{ padding: '18px 18px', maxWidth: 640, margin: '0 auto' }}>



        {/* Instructions */}

        <div style={{ fontSize: 13, color: C.soft, marginBottom: 14, textAlign: 'center' }}>{instructions}</div>



        {/* Illustration */}

        {content.illustration && (

          <div style={{ background: C.card, borderRadius: 20, padding: 16, textAlign: 'center', fontSize: 56, marginBottom: 14, border: '1px solid ' + C.border }}>

            {content.illustration}

          </div>

        )}



        {/* Item card */}

        <div style={{ background: C.card, borderRadius: 24, padding: '28px 20px', marginBottom: 16, border: '2.5px solid ' + (item.color || C.golden), textAlign: 'center', minHeight: 120, display: 'flex', flexDirection: 'column' as const, alignItems: 'center', justifyContent: 'center' }}>

          {item.color && <div style={{ width: 48, height: 48, borderRadius: '50%', background: item.color, marginBottom: 12 }}/>}

          <div style={{ fontSize: 28, fontWeight: 900, color: item.color || C.dark, lineHeight: 1.3, marginBottom: 6 }}>{item.text}</div>

          {item.audio_hint && <div style={{ fontSize: 13, color: C.soft }}>{item.audio_hint}</div>}

          {listened[current] && <div style={{ marginTop: 8, background: '#D1FAE5', color: '#065F46', fontSize: 12, fontWeight: 700, padding: '3px 10px', borderRadius: 10 }}>&#10003; Listened!</div>}

        </div>



        {/* Step 1 — Listen */}

        <div style={{ fontSize: 12, fontWeight: 900, color: C.soft, textTransform: 'uppercase' as const, letterSpacing: 1, marginBottom: 8 }}>Step 1 — Listen</div>

        <button onClick={playItem} disabled={speaking || recording || submitting}

          style={{ width: '100%', padding: '14px 0', borderRadius: 16, border: 'none', background: speaking || recording || submitting ? C.border : C.green, color: 'white', fontSize: 15, fontWeight: 800, cursor: speaking || recording || submitting ? 'default' : 'pointer', marginBottom: 18, fontFamily: 'Nunito, sans-serif', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 }}>

          <svg width="18" height="18" viewBox="0 0 24 24"><path d="M11 5C11 5 6 8 6 12C6 16 11 19 11 19V5Z" fill="white"/><path d="M14 8.5C15.5 9.5 16 10.7 16 12C16 13.3 15.5 14.5 14 15.5" stroke="white" strokeWidth="2" fill="none" strokeLinecap="round"/><path d="M17 6C19.5 7.5 21 9.6 21 12C21 14.4 19.5 16.5 17 18" stroke="white" strokeWidth="2" fill="none" strokeLinecap="round"/></svg>

          {speaking ? 'Mama Judi is speaking...' : 'Listen to Mama Judi'}

        </button>

        {audioConsent && speechSupported && (

          <label style={{ display: 'flex', alignItems: 'flex-start', gap: 10, background: '#FFFDF8', border: '1px solid ' + C.border, borderRadius: 8, padding: '11px 12px', marginBottom: 16, cursor: recording ? 'default' : 'pointer' }}>

            <input type="checkbox" checked={saveAudio} disabled={recording || submitting} onChange={event => setSaveAudio(event.target.checked)} style={{ width: 20, height: 20, marginTop: 1 }} />

            <span style={{ fontSize: 13, color: C.dark, lineHeight: 1.4 }}>

              <strong>Save my voice for parent review</strong><br />

              <span style={{ color: C.soft }}>The recording is private and can be deleted by your parent.</span>

            </span>

          </label>

        )}



        {/* Step 2 — Speak */}

        {speechSupported ? (

          <>

            <div style={{ fontSize: 12, fontWeight: 900, color: C.soft, textTransform: 'uppercase' as const, letterSpacing: 1, marginBottom: 8 }}>Step 2 — Repeat</div>

            <button

              onClick={recording ? stopRecording : startRecording}

              disabled={!recording && !canStartRecording}

              style={{ width: '100%', padding: '14px 0', borderRadius: 16, border: 'none', background: recording ? C.red : canStartRecording ? C.golden : '#8A8A7E', color: 'white', fontSize: 15, fontWeight: 800, cursor: recording || canStartRecording ? 'pointer' : 'not-allowed', marginBottom: 12, fontFamily: 'Nunito, sans-serif', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8, animation: recording ? 'pulse 1s infinite' : 'none' }}>

              {recording ? (

                <><svg width="18" height="18" viewBox="0 0 24 24"><rect x="6" y="6" width="12" height="12" rx="2" fill="white"/></svg> Stop recording</>

              ) : (

                <><svg width="18" height="18" viewBox="0 0 24 24"><ellipse cx="12" cy="10" rx="4" ry="6" fill="white"/><path d="M6 12a6 6 0 0 0 12 0" stroke="white" strokeWidth="2" fill="none"/><line x1="12" y1="18" x2="12" y2="22" stroke="white" strokeWidth="2"/></svg> Speak now</>

              )}

            </button>



            {/* Transcript + score */}

            {transcript && (

              <div style={{ background: C.card, borderRadius: 16, padding: '14px 16px', marginBottom: 12, border: '1.5px solid ' + C.border, textAlign: 'center' }}>

                <div style={{ fontSize: 12, color: C.soft, marginBottom: 6 }}>You said:</div>

                <div style={{ fontSize: 18, fontWeight: 800, color: C.dark, marginBottom: 8 }}>"{transcript}"</div>

                <div style={{ fontSize: 22, fontWeight: 900, color: scoreColor }}>{score}% word match — {scoreLabel}</div>

                <div style={{ height: 6, background: C.border, borderRadius: 3, marginTop: 8 }}>

                  <div style={{ height: 6, borderRadius: 3, background: scoreColor, width: (score || 0) + '%', transition: 'width .5s' }}/>

                </div>

              </div>

            )}



            {audioDataUrl && <div style={{ color: C.green, fontSize: 12, fontWeight: 800, textAlign: 'center', marginBottom: 12 }}>Voice recording ready for parent review.</div>}

          </>

        ) : (

          <div style={{ background: C.card, borderRadius: 14, padding: '12px 14px', marginBottom: 12, border: '1.5px solid ' + C.border, fontSize: 13, color: C.soft, textAlign: 'center' }}>

            Speech recognition is not available on this device. You can still practise, but this item will not receive a verified score.

          </div>

        )}

        {!listened[current] && speechSupported && <div style={{ color: C.soft, fontSize: 13, fontWeight: 700, textAlign: 'center', marginBottom: 12 }}>Listen first, then repeat the words.</div>}

        {error && <div role="alert" style={{ color: C.red, fontSize: 13, fontWeight: 700, textAlign: 'center', marginBottom: 12 }}>{error}</div>}



        {/* Navigation */}

        <div style={{ display: 'flex', gap: 10, marginTop: 8 }}>

          <button onClick={skip} disabled={recording || submitting} style={{ flex: 1, padding: '12px', borderRadius: 14, border: '1.5px solid ' + C.border, background: C.card, color: C.soft, fontWeight: 700, cursor: recording || submitting ? 'default' : 'pointer', opacity: recording || submitting ? 0.6 : 1, fontSize: 13, fontFamily: 'Nunito, sans-serif' }}>

            Skip

          </button>

          <button onClick={next} disabled={!canContinue} style={{ flex: 2, padding: '12px', borderRadius: 14, border: 'none', background: canContinue ? C.green : '#8A8A7E', color: 'white', fontWeight: 900, cursor: canContinue ? 'pointer' : submitting ? 'wait' : 'not-allowed', fontSize: 14, fontFamily: 'Nunito, sans-serif' }}>

            {submitting ? 'Saving...' : current < items.length - 1 ? 'Next →' : 'Finish ✓'}

          </button>

        </div>

      </div>



      <style>{`

        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.6} }

      `}</style>

      <button onClick={() => setShowArdoise(true)} style={{ position: 'fixed', bottom: 160, right: 16, zIndex: 999, width: 52, height: 52, borderRadius: '50%', background: '#C47A3C', border: 'none', boxShadow: '0 4px 12px rgba(0,0,0,0.25)', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center' }} title="Ardoise brouillon">

        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>

      </button>

    </div>

  )

}
