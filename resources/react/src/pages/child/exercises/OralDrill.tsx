// OralDrill.tsx — Ecoute + Reconnaissance vocale Web Speech API
import { useState, useEffect, useRef } from 'react'
import { MamaJudi } from '../../../services/MamaJudi'
import { SoundService } from '../../../services/SoundService'
import type { OralDrillContent } from '../../../types/exercise'
import Ardoise from './Ardoise'

interface Props {
  title: string
  instructions: string
  content: OralDrillContent
  onComplete: (score: number) => void
  onBack: () => void
}

// Similarite entre deux chaines (0-100)
function similarity(a: string, b: string): number {
  const normalize = (s: string) => s.toLowerCase().trim().replace(/[^a-z0-9\s]/g, '')
  const na = normalize(a)
  const nb = normalize(b)
  if (na === nb) return 100
  // Verifier si le texte reconnu contient le mot attendu
  if (nb.includes(na) || na.includes(nb)) return 90
  // Compter les mots en commun
  const wa = na.split(' ')
  const wb = nb.split(' ')
  const common = wa.filter(w => wb.includes(w)).length
  if (common === 0) return 0
  return Math.round((common / Math.max(wa.length, wb.length)) * 100)
}

const C = {
  bg: '#E8DCC8', card: '#F0E8D8', green: '#1D6B2A',
  golden: '#C47A3C', dark: '#3D2B1F', soft: '#7A6050',
  border: '#D0C8B8', red: '#CE1126',
}

export default function OralDrill({ title, instructions, content, onComplete, onBack }: Props) {
  const [current, setCurrent] = useState(0)
  const [speaking, setSpeaking] = useState(false)
  const [listened, setListened] = useState<boolean[]>([])
  const [recording, setRecording] = useState(false)
  const [transcript, setTranscript] = useState('')
  const [score, setScore] = useState<number | null>(null)
  const [scores, setScores] = useState<number[]>([])
  const [done, setDone] = useState(false)
  const [speechSupported, setSpeechSupported] = useState(false)
  const [error, setError] = useState('')
  const [showArdoise, setShowArdoise] = useState(false)
  const recognitionRef = useRef<any>(null)

  const items = content.items
  const item = items[current]

  useEffect(() => {
    MamaJudi.speak(instructions)
    setListened(new Array(items.length).fill(false))
    // Verifier support Web Speech API
    const SR = (window as any).SpeechRecognition || (window as any).webkitSpeechRecognition
    setSpeechSupported(!!SR)
    // Demander autorisation micro en avance pour eviter le blocage au premier enregistrement
    if (SR && navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices.getUserMedia({ audio: true })
        .then(stream => {
          // Autorisation obtenue -- on coupe le stream immediatement
          stream.getTracks().forEach(t => t.stop())
        })
        .catch(() => {
          // Autorisation refusee -- le fallback auto-eval sera utilise
          setSpeechSupported(false)
        })
    }
  }, [])

  const playItem = () => {
    setSpeaking(true)
    MamaJudi.speakLang(item.text, 'en-GB')
    setTimeout(() => setSpeaking(false), Math.max(1500, item.text.length * 80))
    const updated = [...listened]
    updated[current] = true
    setListened(updated)
    setTranscript('')
    setScore(null)
    setError('')
  }

  const startRecording = () => {
    const SR = (window as any).SpeechRecognition || (window as any).webkitSpeechRecognition
    if (!SR) return
    setError('')
    setTranscript('')
    setScore(null)
    const recognition = new SR()
    recognitionRef.current = recognition
    recognition.lang = 'en-GB'
    recognition.interimResults = false
    recognition.maxAlternatives = 3

    recognition.onstart = () => setRecording(true)
    recognition.onend = () => setRecording(false)
    recognition.onerror = (e: any) => {
      setRecording(false)
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
        MamaJudi.speak('Excellent pronunciation!')
      } else if (bestScore >= 50) {
        SoundService.streak()
        MamaJudi.speak('Good try! Listen again and repeat.')
      } else {
        MamaJudi.speak('Listen carefully and try again.')
      }
    }
    recognition.start()
  }

  const stopRecording = () => {
    recognitionRef.current?.stop()
    setRecording(false)
  }

  const next = () => {
    const finalScore = score ?? 0
    const newScores = [...scores, finalScore]
    setScores(newScores)
    setTranscript('')
    setScore(null)
    setError('')
    if (current < items.length - 1) {
      setCurrent(current + 1)
    } else {
      const avg = Math.round(newScores.reduce((a, b) => a + b, 0) / newScores.length)
      setDone(true)
      onComplete(avg)
    }
  }

  const skip = () => {
    const newScores = [...scores, 0]
    setScores(newScores)
    setTranscript('')
    setScore(null)
    setError('')
    if (current < items.length - 1) { setCurrent(current + 1) }
    else { setDone(true); onComplete(0) }
  }

  if (done) {
    const avg = scores.length > 0 ? Math.round(scores.reduce((a, b) => a + b, 0) / scores.length) : 0
    return (
      <div style={{ background: C.bg, minHeight: '100vh', fontFamily: 'Nunito, sans-serif', display: 'flex', flexDirection: 'column' as const, alignItems: 'center', justifyContent: 'center', padding: '24px 20px', textAlign: 'center' }}>
        <div style={{ fontSize: 56, marginBottom: 12 }}>{avg >= 80 ? '⭐' : avg >= 50 ? '👍' : '\u{1F4AA}'}</div>
        <div style={{ fontSize: 26, fontWeight: 900, color: C.dark, marginBottom: 6 }}>
          {avg >= 80 ? 'Excellent!' : avg >= 50 ? 'Good effort!' : 'Keep practising!'}
        </div>
        <div style={{ fontSize: 18, fontWeight: 800, color: C.green, marginBottom: 4 }}>{avg}%</div>
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

  return (
    <div style={{ background: C.bg, minHeight: '100vh', fontFamily: 'Nunito, sans-serif' }}>
      {showArdoise && <Ardoise onClose={() => setShowArdoise(false)} />}

      {/* Top bar */}
      <div style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '12px 16px', background: C.card, borderBottom: '1px solid ' + C.border }}>
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

      <div style={{ padding: '18px 18px', maxWidth: 480, margin: '0 auto' }}>

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
        <button onClick={playItem} disabled={speaking}
          style={{ width: '100%', padding: '14px 0', borderRadius: 16, border: 'none', background: speaking ? C.border : C.green, color: 'white', fontSize: 15, fontWeight: 800, cursor: speaking ? 'default' : 'pointer', marginBottom: 18, fontFamily: 'Nunito, sans-serif', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 }}>
          <svg width="18" height="18" viewBox="0 0 24 24"><path d="M11 5C11 5 6 8 6 12C6 16 11 19 11 19V5Z" fill="white"/><path d="M14 8.5C15.5 9.5 16 10.7 16 12C16 13.3 15.5 14.5 14 15.5" stroke="white" strokeWidth="2" fill="none" strokeLinecap="round"/><path d="M17 6C19.5 7.5 21 9.6 21 12C21 14.4 19.5 16.5 17 18" stroke="white" strokeWidth="2" fill="none" strokeLinecap="round"/></svg>
          {speaking ? 'Mama Judi is speaking...' : 'Listen to Mama Judi'}
        </button>

        {/* Step 2 — Speak */}
        {speechSupported ? (
          <>
            <div style={{ fontSize: 12, fontWeight: 900, color: C.soft, textTransform: 'uppercase' as const, letterSpacing: 1, marginBottom: 8 }}>Step 2 — Repeat</div>
            <button
              onClick={recording ? stopRecording : startRecording}
              style={{ width: '100%', padding: '14px 0', borderRadius: 16, border: 'none', background: recording ? C.red : C.golden, color: 'white', fontSize: 15, fontWeight: 800, cursor: 'pointer', marginBottom: 12, fontFamily: 'Nunito, sans-serif', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8, animation: recording ? 'pulse 1s infinite' : 'none' }}>
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
                <div style={{ fontSize: 22, fontWeight: 900, color: scoreColor }}>{score}% — {scoreLabel}</div>
                <div style={{ height: 6, background: C.border, borderRadius: 3, marginTop: 8 }}>
                  <div style={{ height: 6, borderRadius: 3, background: scoreColor, width: (score || 0) + '%', transition: 'width .5s' }}/>
                </div>
              </div>
            )}

            {error && <div style={{ color: C.red, fontSize: 13, fontWeight: 700, textAlign: 'center', marginBottom: 12 }}>{error}</div>}
          </>
        ) : (
          <div style={{ background: C.card, borderRadius: 14, padding: '12px 14px', marginBottom: 12, border: '1.5px solid ' + C.border, fontSize: 13, color: C.soft, textAlign: 'center' }}>
            Speech recognition not available. Evaluate yourself below.
            <div style={{ display: 'flex', gap: 10, marginTop: 12 }}>
              <button onClick={() => { setScore(90); SoundService.correct() }} style={{ flex: 1, padding: '10px', borderRadius: 12, border: 'none', background: C.green, color: 'white', fontWeight: 800, cursor: 'pointer', fontFamily: 'Nunito, sans-serif' }}>
                &#128077; Good
              </button>
              <button onClick={() => { setScore(30); SoundService.streak() }} style={{ flex: 1, padding: '10px', borderRadius: 12, border: 'none', background: C.golden, color: 'white', fontWeight: 800, cursor: 'pointer', fontFamily: 'Nunito, sans-serif' }}>
                &#128260; Retry
              </button>
            </div>
          </div>
        )}

        {/* Navigation */}
        <div style={{ display: 'flex', gap: 10, marginTop: 8 }}>
          <button onClick={skip} style={{ flex: 1, padding: '12px', borderRadius: 14, border: '1.5px solid ' + C.border, background: C.card, color: C.soft, fontWeight: 700, cursor: 'pointer', fontSize: 13, fontFamily: 'Nunito, sans-serif' }}>
            Skip
          </button>
          <button onClick={next} style={{ flex: 2, padding: '12px', borderRadius: 14, border: 'none', background: C.green, color: 'white', fontWeight: 900, cursor: 'pointer', fontSize: 14, fontFamily: 'Nunito, sans-serif' }}>
            {current < items.length - 1 ? 'Next →' : 'Finish ✓'}
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
