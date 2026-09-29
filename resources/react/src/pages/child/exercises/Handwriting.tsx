import { useCallback, useEffect, useRef, useState } from 'react'
import { MamaJudi } from '../../../services/MamaJudi'
import type { ExerciseCompletionHandler } from '../../../types/exercise'
import {
  assessHandwritingTrace,
  renderHandwritingGuide,
  type HandwritingGuideStyle,
  type HandwritingPracticeMode,
  type HandwritingPoint,
  type HandwritingStroke,
  type HandwritingTraceAssessment,
} from './handwritingTrace'

interface HandwritingContent {
  type: 'handwriting'
  prompts?: string[]
  word?: string
  letter?: string
  guide_style?: HandwritingGuideStyle
  practice_mode?: HandwritingPracticeMode
}

interface HandwritingSample {
  prompt: string
  stroke_count: number
  point_count: number
  drawn_distance: number
  strokes: HandwritingStroke[]
  trace_feedback: HandwritingTraceAssessment & { status: 'good' | 'needs_help' }
  image_data_url: string
}

interface Props {
  title: string
  instructions: string
  content: HandwritingContent
  isFrench?: boolean
  onComplete: ExerciseCompletionHandler
  onBack: () => void
}

const EMPTY_ASSESSMENT: HandwritingTraceAssessment = { score: 0, precision: 0, coverage: 0, passed: false }

function traceDistance(strokes: HandwritingStroke[]) {
  return strokes.reduce((total, stroke) => total + stroke.slice(1).reduce((strokeTotal, point, index) => {
    const previous = stroke[index]
    return strokeTotal + Math.hypot(point.x - previous.x, point.y - previous.y)
  }, 0), 0)
}

export default function Handwriting({ title, instructions, content, isFrench = false, onComplete, onBack }: Props) {
  const suppliedPrompts = content.prompts?.filter(Boolean)
    ?? (content.word ? [content.word] : undefined)
    ?? (content.letter ? [content.letter] : undefined)
  const prompts = suppliedPrompts && suppliedPrompts.length > 0 ? suppliedPrompts : [isFrench ? 'Écris ici' : 'Write here']
  const [current, setCurrent] = useState(0)
  const [done, setDone] = useState(false)
  const [submitting, setSubmitting] = useState(false)
  const [error, setError] = useState('')
  const [samples, setSamples] = useState<HandwritingSample[]>([])
  const [assessment, setAssessment] = useState<HandwritingTraceAssessment>(EMPTY_ASSESSMENT)
  const [hasInk, setHasInk] = useState(false)
  const [attempts, setAttempts] = useState(0)
  const [canAskForHelp, setCanAskForHelp] = useState(false)
  const guideCanvasRef = useRef<HTMLCanvasElement>(null)
  const inkCanvasRef = useRef<HTMLCanvasElement>(null)
  const containerRef = useRef<HTMLDivElement>(null)
  const drawing = useRef(false)
  const pointerId = useRef<number | null>(null)
  const strokes = useRef<HandwritingStroke[]>([])
  const startedAt = useRef(Date.now())
  const prompt = prompts[current]
  // Pas d'écriture cursive (attachée) : modèles toujours en lettres droites,
  // même si un contenu ancien demande 'upright_joint_script'.
  const guideStyle: HandwritingGuideStyle = 'print'
  const practiceMode = content.practice_mode ?? 'trace'
  const usesJointScript = false
  const isCopying = practiceMode === 'copy'
  const progress = Math.round(((current + 1) / prompts.length) * 100)
  const language = isFrench ? 'fr-FR' : 'en-GB'
  const [guideReady, setGuideReady] = useState(!usesJointScript)
  const [guideLoadFailed, setGuideLoadFailed] = useState(false)

  const labels = isFrench ? {
    back: 'Retour', write: 'À écrire', listen: 'Écouter', instruction: 'Suis doucement le modèle avec ton doigt ou ton stylet.',
    onGuide: 'Sur le modèle', covered: 'Tracé terminé', clear: 'Effacer', check: 'Vérifier', next: 'Suivant', finish: 'Terminer',
    empty: 'Commence par tracer le modèle.', closer: 'Reste plus près du modèle et termine toutes les lettres.',
    good: 'Bon tracé. Il sera encore vérifié par un adulte.', help: 'Envoyer à un adulte pour être aidé',
    submitted: 'Écriture envoyée', waiting: 'En attente de vérification', activities: 'Retour aux activités',
    submitError: "L'écriture n'a pas pu être envoyée. Réessaie.",
  } : {
    back: 'Back', write: 'Write', listen: 'Listen', instruction: 'Follow the pale model slowly with your finger or stylus.',
    onGuide: 'On the guide', covered: 'Prompt covered', clear: 'Clear', check: 'Check', next: 'Next', finish: 'Finish',
    empty: 'Start by tracing the model.', closer: 'Stay closer to the model and finish every letter.',
    good: 'Good trace. An adult will still review it.', help: 'Send to an adult for help',
    submitted: 'Writing submitted', waiting: 'Waiting for review', activities: 'Back to activities',
    submitError: 'The writing could not be submitted. Try again.',
  }

  const guidanceInstruction = isCopying
    ? (isFrench ? 'Copie la phrase sur les lignes avec une ecriture soignee.' : 'Copy the sentence onto the lines in your neatest handwriting.')
    : labels.instruction
  const incompleteMessage = isCopying
    ? (isFrench ? "Copie tous les mots jusqu'au repere de fin." : 'Copy every word and reach the end marker.')
    : labels.closer
  const acceptedMessage = isCopying
    ? (isFrench ? 'Assez decriture pour envoyer. Un adulte verifiera la copie.' : 'Enough writing to send. An adult will verify the copy.')
    : labels.good
  const detectedWords = assessment.detectedWords ?? 0
  const expectedWords = assessment.expectedWords ?? prompt.trim().split(/\s+/).filter(Boolean).length
  const feedbackMetrics: Array<[string, number, string]> = isCopying
    ? [
        [isFrench ? 'Mots detectes' : 'Words detected', expectedWords > 0 ? Math.min(100, Math.round(detectedWords / expectedWords * 100)) : 0, `${detectedWords}/${expectedWords}`],
        [isFrench ? 'Ligne couverte' : 'Line coverage', assessment.precision, `${assessment.precision}%`],
      ]
    : [[labels.onGuide, assessment.precision, `${assessment.precision}%`], [labels.covered, assessment.coverage, `${assessment.coverage}%`]]
  const canContinue = guideReady && hasInk && !submitting

  const configureCanvas = useCallback((canvas: HTMLCanvasElement, width: number, height: number) => {
    const ratio = Math.min(2, window.devicePixelRatio || 1)
    canvas.width = Math.max(1, Math.round(width * ratio))
    canvas.height = Math.max(1, Math.round(height * ratio))
    canvas.getContext('2d')?.setTransform(ratio, 0, 0, ratio, 0, 0)
  }, [])

  const paintInk = useCallback(() => {
    const canvas = inkCanvasRef.current
    if (!canvas) return
    const context = canvas.getContext('2d')
    if (!context) return
    const width = canvas.clientWidth
    const height = canvas.clientHeight
    context.clearRect(0, 0, width, height)
    context.strokeStyle = '#176B3A'
    context.fillStyle = '#176B3A'
    context.lineWidth = 5
    context.lineCap = 'round'
    context.lineJoin = 'round'
    for (const stroke of strokes.current) {
      if (stroke.length === 1) {
        context.beginPath()
        context.arc(stroke[0].x * width, stroke[0].y * height, 2.5, 0, Math.PI * 2)
        context.fill()
        continue
      }
      if (stroke.length < 2) continue
      context.beginPath()
      context.moveTo(stroke[0].x * width, stroke[0].y * height)
      for (const point of stroke.slice(1)) context.lineTo(point.x * width, point.y * height)
      context.stroke()
    }
  }, [])

  const resizeCanvases = useCallback(() => {
    const container = containerRef.current
    const guide = guideCanvasRef.current
    const ink = inkCanvasRef.current
    if (!container || !guide || !ink) return
    const rect = container.getBoundingClientRect()
    configureCanvas(guide, rect.width, rect.height)
    configureCanvas(ink, rect.width, rect.height)
    renderHandwritingGuide(guide, prompt, guideStyle, practiceMode)
    paintInk()
  }, [configureCanvas, guideStyle, paintInk, practiceMode, prompt])

  const surfaceAspectRatio = useCallback(() => {
    const canvas = inkCanvasRef.current
    if (!canvas || canvas.clientWidth <= 0) return 220 / 360
    return canvas.clientHeight / canvas.clientWidth
  }, [])

  const clearCanvas = useCallback(() => {
    strokes.current = []
    setAssessment(EMPTY_ASSESSMENT)
    setHasInk(false)
    setAttempts(0)
    setCanAskForHelp(false)
    setError('')
    paintInk()
  }, [paintInk])

  useEffect(() => {
    const timer = window.setTimeout(resizeCanvases, 80)
    window.addEventListener('resize', resizeCanvases)
    return () => {
      window.clearTimeout(timer)
      window.removeEventListener('resize', resizeCanvases)
      MamaJudi.stop()
    }
  }, [])

  useEffect(() => {
    if (!usesJointScript) {
      setGuideReady(true)
      setGuideLoadFailed(false)
      return
    }

    let cancelled = false
    setGuideReady(false)
    setGuideLoadFailed(false)
    void document.fonts.load('400 48px "Schoolbell"').then(loadedFaces => {
      if (cancelled) return
      if (loadedFaces.length === 0) {
        setGuideLoadFailed(true)
        return
      }
      setGuideReady(true)
      window.setTimeout(resizeCanvases, 0)
    }).catch(() => {
      if (!cancelled) setGuideLoadFailed(true)
    })
    return () => { cancelled = true }
  }, [resizeCanvases, usesJointScript])

  useEffect(() => {
    clearCanvas()
    const timer = window.setTimeout(resizeCanvases, 50)
    const announcement = current === 0
      ? `${instructions}. ${isFrench ? 'Écris' : 'Write'}: ${prompt}`
      : `${isFrench ? 'Écris' : 'Write'}: ${prompt}`
    MamaJudi.speakLangAfter(announcement, language, 250, 0.78)
    return () => window.clearTimeout(timer)
  }, [current, prompt])

  const pointFromEvent = (event: React.PointerEvent<HTMLCanvasElement>): HandwritingPoint => {
    const rect = event.currentTarget.getBoundingClientRect()
    return {
      x: Math.max(0, Math.min(1, (event.clientX - rect.left) / rect.width)),
      y: Math.max(0, Math.min(1, (event.clientY - rect.top) / rect.height)),
      t: Math.max(0, Date.now() - startedAt.current),
    }
  }

  const startDraw = (event: React.PointerEvent<HTMLCanvasElement>) => {
    if (!guideReady || submitting || (event.pointerType === 'mouse' && event.button !== 0)) return
    event.currentTarget.setPointerCapture(event.pointerId)
    drawing.current = true
    pointerId.current = event.pointerId
    strokes.current.push([pointFromEvent(event)])
    setHasInk(true)
    setError('')
    paintInk()
  }

  const draw = (event: React.PointerEvent<HTMLCanvasElement>) => {
    if (!drawing.current || pointerId.current !== event.pointerId) return
    event.preventDefault()
    const stroke = strokes.current[strokes.current.length - 1]
    const point = pointFromEvent(event)
    const previous = stroke[stroke.length - 1]
    if (stroke.length >= 800 || (Math.hypot(point.x - previous.x, point.y - previous.y) < 0.002 && point.t - previous.t < 24)) return
    stroke.push(point)
    paintInk()
  }

  const stopDraw = (event: React.PointerEvent<HTMLCanvasElement>) => {
    if (pointerId.current !== event.pointerId) return
    drawing.current = false
    pointerId.current = null
    try { event.currentTarget.releasePointerCapture(event.pointerId) } catch { /* Pointer capture can already be released. */ }
    setAssessment(assessHandwritingTrace(prompt, strokes.current, guideStyle, surfaceAspectRatio(), practiceMode))
  }

  const exportInk = () => {
    const ink = inkCanvasRef.current
    if (!ink) return ''
    const output = document.createElement('canvas')
    output.width = ink.width
    output.height = ink.height
    const context = output.getContext('2d')
    if (!context) return ''
    context.fillStyle = '#FFFDF8'
    context.fillRect(0, 0, output.width, output.height)
    context.drawImage(ink, 0, 0)
    return output.toDataURL('image/webp', 0.72)
  }

  const advance = async (forceParentHelp = false) => {
    const trace = strokes.current
    const pointCount = trace.reduce((total, stroke) => total + stroke.length, 0)
    if (!guideReady) return
    const currentAssessment = assessHandwritingTrace(prompt, trace, guideStyle, surfaceAspectRatio(), practiceMode)
    setAssessment(currentAssessment)
    if (pointCount < 6) {
      setError(labels.empty)
      return
    }
    if (!currentAssessment.passed && !forceParentHelp) {
      const nextAttempts = attempts + 1
      setAttempts(nextAttempts)
      setCanAskForHelp(nextAttempts >= 2)
      setError(incompleteMessage)
      return
    }

    const normalizedStrokes = trace.map(stroke => stroke.map(point => ({
      x: Math.round(point.x * 10000) / 10000,
      y: Math.round(point.y * 10000) / 10000,
      t: Math.round(point.t),
    })))
    const sample: HandwritingSample = {
      prompt,
      stroke_count: normalizedStrokes.length,
      point_count: pointCount,
      drawn_distance: Math.round(traceDistance(normalizedStrokes) * 1000),
      strokes: normalizedStrokes,
      trace_feedback: { ...currentAssessment, status: currentAssessment.passed ? 'good' : 'needs_help' },
      image_data_url: exportInk(),
    }
    const nextSamples = [...samples, sample]
    setSamples(nextSamples)
    if (current < prompts.length - 1) {
      setCurrent(value => value + 1)
      return
    }

    setSubmitting(true)
    setError('')
    try {
      await onComplete(0, {
        verification_status: 'pending_review',
        answers: { samples: nextSamples },
        evidence: { method: isCopying ? 'handwriting_copy_capture' : 'handwriting_trace_capture', trace_version: 2, sample_count: nextSamples.length },
        duration_seconds: Math.max(1, Math.round((Date.now() - startedAt.current) / 1000)),
      })
      setDone(true)
    } catch (reason) {
      setError(reason instanceof Error ? reason.message : labels.submitError)
    } finally {
      setSubmitting(false)
    }
  }

  if (done) {
    return (
      <div className="adventure-result-page" style={{ minHeight: '100vh', background: '#E8DCC8', color: '#2D1B0E', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: 24, textAlign: 'center' }}>
        <div aria-hidden="true" style={{ fontSize: 52, marginBottom: 12 }}>✍️</div>
        <div style={{ fontSize: 26, fontWeight: 900, marginBottom: 6 }}>{labels.submitted}</div>
        <div style={{ fontSize: 15, color: '#176B3A', fontWeight: 800, marginBottom: 6 }}>{labels.waiting}</div>
        <div style={{ fontSize: 14, color: '#7A6050', marginBottom: 24 }}>{title}</div>
        <button onClick={onBack} style={{ minHeight: 48, padding: '0 28px', borderRadius: 8, border: 0, background: '#176B3A', color: 'white', fontSize: 15, fontWeight: 900, cursor: 'pointer' }}>{labels.activities}</button>
      </div>
    )
  }

  return (
    <div className="adventure-exercise-shell adventure-handwriting-page" style={{ background: '#E8DCC8', minHeight: '100vh', color: '#2D1B0E', fontFamily: 'Nunito, system-ui, sans-serif' }}>
      <header className="adventure-exercise-header" style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '12px 14px', background: '#F7F2E8', borderBottom: '2px solid #E2B52C' }}>
        <button onClick={onBack} aria-label={labels.back} style={{ minWidth: 44, minHeight: 44, border: '1px solid #C9C0B1', borderRadius: 8, background: '#FFFDF8', color: '#523927', fontSize: 20, cursor: 'pointer' }}>←</button>
        <div style={{ flex: 1, minWidth: 0 }}>
          <div style={{ overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap', fontSize: 14, fontWeight: 900 }}>{title}</div>
          <div style={{ height: 6, marginTop: 5, overflow: 'hidden', borderRadius: 4, background: '#DED6C8' }}>
            <div style={{ width: `${progress}%`, height: '100%', background: '#176B3A', transition: 'width 0.2s' }} />
          </div>
        </div>
        <strong style={{ fontSize: 13, color: '#6C5142', flexShrink: 0 }}>{current + 1}/{prompts.length}</strong>
      </header>

      <main style={{ width: '100%', maxWidth: 760, boxSizing: 'border-box', margin: '0 auto', padding: 16 }}>
        <section style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 14, padding: '12px 14px', marginBottom: 12, border: '1px solid #C9C0B1', borderRadius: 8, background: '#FFFDF8' }}>
          <div style={{ minWidth: 0 }}>
            <div style={{ marginBottom: 3, color: '#176B3A', fontSize: 11, fontWeight: 900, textTransform: 'uppercase' }}>{labels.write}</div>
            <div style={{ overflowWrap: 'anywhere', fontSize: usesJointScript ? 29 : 24, lineHeight: 1.2, fontWeight: usesJointScript ? 400 : 900, fontFamily: usesJointScript ? '"Schoolbell", sans-serif' : undefined }}>{prompt}</div>
          </div>
          <button onClick={() => void MamaJudi.speakLang(`${isFrench ? 'Écris' : 'Write'}: ${prompt}`, language, 0.78)} aria-label={labels.listen} title={labels.listen} style={{ width: 46, height: 46, flexShrink: 0, border: 0, borderRadius: 8, background: '#176B3A', color: 'white', fontSize: 20, cursor: 'pointer' }}>🔊</button>
        </section>

        <p style={{ margin: '0 0 10px', color: '#6C5142', fontSize: 13, fontWeight: 750 }}>{guidanceInstruction}</p>

        <div ref={containerRef} style={{ position: 'relative', height: 'clamp(230px, 40vh, 340px)', overflow: 'hidden', border: `3px solid ${assessment.passed ? '#2F8F4E' : '#BDB3A4'}`, borderRadius: 8, background: '#FFFDF8', boxShadow: '0 3px 0 rgba(61,43,31,.1)' }}>
          <canvas ref={guideCanvasRef} aria-hidden="true" style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', pointerEvents: 'none' }} />
          <canvas ref={inkCanvasRef} aria-label={guidanceInstruction} style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', touchAction: 'none', cursor: guideReady ? 'crosshair' : 'wait' }} onPointerDown={startDraw} onPointerMove={draw} onPointerUp={stopDraw} onPointerCancel={stopDraw} />
          {!guideReady && <div role={guideLoadFailed ? 'alert' : 'status'} style={{ position: 'absolute', inset: 0, display: 'grid', placeItems: 'center', padding: 20, background: 'rgba(255,253,248,.92)', color: guideLoadFailed ? '#B42318' : '#176B3A', fontSize: 14, fontWeight: 850, textAlign: 'center', pointerEvents: 'none' }}>{guideLoadFailed ? (isFrench ? "Le modele d'ecriture n'a pas pu etre charge." : 'The writing guide could not be loaded.') : (isFrench ? 'Preparation du modele...' : 'Preparing the writing guide...')}</div>}
        </div>

        <div aria-live="polite" style={{ display: 'grid', gridTemplateColumns: 'repeat(2, minmax(0, 1fr))', gap: 10, marginTop: 10 }}>
          {feedbackMetrics.map(([label, value, displayValue]) => (
            <div key={String(label)} style={{ minWidth: 0 }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', gap: 6, marginBottom: 4, fontSize: 11, fontWeight: 850, color: '#6C5142' }}><span>{label}</span><span>{displayValue}</span></div>
              <div style={{ height: 7, borderRadius: 4, background: '#DCD4C7', overflow: 'hidden' }}><div style={{ width: `${value}%`, height: '100%', background: assessment.passed ? '#2F8F4E' : '#E39A2D', transition: 'width .15s' }} /></div>
            </div>
          ))}
        </div>

        {assessment.passed && <div style={{ marginTop: 10, color: '#176B3A', fontSize: 13, fontWeight: 850 }}>{acceptedMessage}</div>}
        {error && <div role="alert" style={{ marginTop: 10, color: '#B42318', fontSize: 13, fontWeight: 850 }}>{error}</div>}

        <div style={{ display: 'grid', gridTemplateColumns: 'minmax(90px, 1fr) minmax(150px, 2fr)', gap: 10, marginTop: 14 }}>
          <button onClick={clearCanvas} disabled={!canContinue} style={{ minHeight: 48, border: '1px solid #BDB3A4', borderRadius: 8, background: '#FFFDF8', color: canContinue ? '#523927' : '#8A8178', fontSize: 14, fontWeight: 900, cursor: canContinue ? 'pointer' : 'default' }}>{labels.clear}</button>
          <button onClick={() => void advance(false)} disabled={!canContinue} style={{ minHeight: 48, border: 0, borderRadius: 8, background: !canContinue ? '#8A8A7E' : assessment.passed ? '#176B3A' : '#C96A16', color: 'white', fontSize: 15, fontWeight: 900, cursor: canContinue ? 'pointer' : 'default' }}>{submitting ? '...' : !assessment.passed ? `${labels.check} ✓` : current === prompts.length - 1 ? `${labels.finish} ✓` : `${labels.next} →`}</button>
        </div>
        {canAskForHelp && !assessment.passed && <button onClick={() => void advance(true)} disabled={submitting} style={{ width: '100%', minHeight: 44, marginTop: 10, border: '1px solid #B56B35', borderRadius: 8, background: '#FFF6EB', color: '#8A451D', fontWeight: 900, cursor: submitting ? 'default' : 'pointer' }}>{labels.help}</button>}
      </main>
    </div>
  )
}
