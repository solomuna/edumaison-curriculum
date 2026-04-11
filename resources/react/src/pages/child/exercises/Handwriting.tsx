import { useState, useRef, useEffect, useCallback } from 'react'
import { MamaJudi } from '../../../services/MamaJudi'
import { SoundService } from '../../../services/SoundService'

interface HandwritingContent {
  type: 'handwriting'
  prompts: string[]
}

interface Props {
  title: string
  instructions: string
  content: HandwritingContent
  onComplete: (score: number) => void
  onBack: () => void
}

export default function Handwriting({ title, instructions, content, onComplete, onBack }: Props) {
  const [current, setCurrent] = useState(0)
  const [done, setDone] = useState(false)
  const [selfEval, setSelfEval] = useState<'good' | 'retry' | null>(null)
  const [showEval, setShowEval] = useState(false)
  const canvasRef = useRef<HTMLCanvasElement>(null)
  const containerRef = useRef<HTMLDivElement>(null)
  const drawing = useRef(false)
  const lastPos = useRef({ x: 0, y: 0 })

  const prompts = content.prompts
  const prompt = prompts[current]
  const pct = Math.round((current / prompts.length) * 100)

  const resizeCanvas = useCallback(() => {
    const canvas = canvasRef.current
    const container = containerRef.current
    if (!canvas || !container) return
    const rect = container.getBoundingClientRect()
    canvas.width = rect.width
    canvas.height = rect.height
  }, [])

  useEffect(() => {
        MamaJudi.speak(instructions)
    setTimeout(resizeCanvas, 100)
    window.addEventListener('resize', resizeCanvas)
    return () => window.removeEventListener('resize', resizeCanvas)
  }, [])

  useEffect(() => {
    setTimeout(() => { resizeCanvas(); clearCanvas() }, 50)
    MamaJudi.speak('Write: ' + prompt, 0.8)
  }, [current])

  const getPos = (e: React.MouseEvent | React.TouchEvent) => {
    const canvas = canvasRef.current
    if (!canvas) return { x: 0, y: 0 }
    const rect = canvas.getBoundingClientRect()
    if ('touches' in e) {
      return { x: e.touches[0].clientX - rect.left, y: e.touches[0].clientY - rect.top }
    }
    return { x: (e as React.MouseEvent).clientX - rect.left, y: (e as React.MouseEvent).clientY - rect.top }
  }

  const startDraw = (e: React.MouseEvent | React.TouchEvent) => {
    drawing.current = true
    lastPos.current = getPos(e)
  }

  const draw = (e: React.MouseEvent | React.TouchEvent) => {
    if (!drawing.current) return
    const canvas = canvasRef.current
    if (!canvas) return
    const ctx = canvas.getContext('2d')
    if (!ctx) return
    e.preventDefault()
    const pos = getPos(e)
    ctx.beginPath()
    ctx.moveTo(lastPos.current.x, lastPos.current.y)
    ctx.lineTo(pos.x, pos.y)
    ctx.strokeStyle = '#1D6B2A'
    ctx.lineWidth = 5
    ctx.lineCap = 'round'
    ctx.lineJoin = 'round'
    ctx.stroke()
    lastPos.current = pos
  }

  const stopDraw = () => { drawing.current = false }

  const clearCanvas = () => {
    const canvas = canvasRef.current
    if (!canvas) return
    const ctx = canvas.getContext('2d')
    if (!ctx) return
    ctx.clearRect(0, 0, canvas.width, canvas.height)
    // Guide letter in background
    ctx.font = `bold ${canvas.height * 0.7}px Georgia, serif`
    ctx.fillStyle = '#F0E4D8'
    ctx.textAlign = 'center'
    ctx.textBaseline = 'middle'
    ctx.fillText(prompt, canvas.width / 2, canvas.height / 2)
    // Baseline
    ctx.strokeStyle = '#FFD4B0'
    ctx.lineWidth = 1.5
    ctx.setLineDash([6, 6])
    const lineY = canvas.height * 0.72
    ctx.beginPath()
    ctx.moveTo(20, lineY)
    ctx.lineTo(canvas.width - 20, lineY)
    ctx.stroke()
    ctx.setLineDash([])
  }

  // Messages encouragement Mama Judi par enfant
  const ENCOURAGEMENTS_GOOD = [
    'Excellent ! Tes lettres sont tres belles !',
    'Bravo ! Tu as bien travaille ton ecriture !',
    'Magnifique ! Continue comme ca !',
    'Tres bien ! Je suis fiere de toi !',
  ]
  const ENCOURAGEMENTS_RETRY = [
    'Pas grave ! Efface et recommence, tu peux le faire !',
    'Courage ! Prends ton temps et trace bien les lettres.',
    'Essaie encore ! Je crois en toi !',
  ]

  const handleSelfEval = (val: 'good' | 'retry') => {
    setSelfEval(val)
    setShowEval(false)
    if (val === 'good') {
      SoundService.correct()
      const msg = ENCOURAGEMENTS_GOOD[Math.floor(Math.random() * ENCOURAGEMENTS_GOOD.length)]
      MamaJudi.speak(msg)
      setTimeout(() => {
        setSelfEval(null)
        if (current < prompts.length - 1) { setCurrent(current + 1) }
        else { setDone(true); onComplete(100) }
      }, 1800)
    } else {
      SoundService.streak()
      const msg = ENCOURAGEMENTS_RETRY[Math.floor(Math.random() * ENCOURAGEMENTS_RETRY.length)]
      MamaJudi.speak(msg)
      setTimeout(() => { setSelfEval(null); clearCanvas() }, 1500)
    }
  }

  const next = () => {
    setShowEval(true)
  }

  if (done) {
    return (
      <div style={{
        background: '#E8DCC8', minHeight: '100vh',
        fontFamily: "-apple-system, BlinkMacSystemFont, 'Trebuchet MS', sans-serif",
        display: 'flex', flexDirection: 'column', alignItems: 'center',
        justifyContent: 'center', padding: '24px 20px', textAlign: 'center'
      }}>
        <div style={{ fontSize: 56, marginBottom: 12 }}>✍️</div>
        <div style={{ fontSize: 26, fontWeight: 900, color: '#2D1B0E', marginBottom: 6 }}>Très bien écrit !</div>
        <div style={{ fontSize: 14, color: '#8A6050', marginBottom: 24 }}>{title}</div>
        <button onClick={onBack} style={{
          padding: '13px 32px', borderRadius: 16, border: 'none',
          background: '#FF8FAB', color: 'white', fontSize: 15, fontWeight: 800, cursor: 'pointer'
        }}>Retour aux activités</button>
      </div>
    )
  }

  return (
    <div style={{
      background: '#E8DCC8', minHeight: '100vh',
      fontFamily: "-apple-system, BlinkMacSystemFont, 'Trebuchet MS', sans-serif"
    }}>
      {/* Top bar */}
      <div style={{
        display: 'flex', alignItems: 'center', gap: 12,
        padding: '14px 16px', background: '#F0E8D8', borderBottom: '1px solid #F0E4D8'
      }}>
        <button onClick={onBack} style={{
          background: '#F0E8D8', border: '1.5px solid #D0C8B8', borderRadius: 10,
          padding: '6px 12px', fontSize: 13, fontWeight: 700, color: '#7A6050',
          cursor: 'pointer', flexShrink: 0
        }}>← Retour</button>
        <div style={{ flex: 1 }}>
          <div style={{ fontSize: 13, fontWeight: 800, color: '#2D1B0E', marginBottom: 4 }}>{title}</div>
          <div style={{ height: 5, background: '#F0E4D8', borderRadius: 3 }}>
            <div style={{ height: 5, borderRadius: 3, background: '#1D6B2A', width: `${pct}%`, transition: 'width 0.3s' }}/>
          </div>
        </div>
        <div style={{ fontSize: 12, color: '#C8A090', fontWeight: 700, flexShrink: 0 }}>
          {current + 1}/{prompts.length}
        </div>
      </div>

      <div style={{ padding: 16 }}>
        {/* Prompt card */}
        <div style={{
          background: '#F0E8D8', borderRadius: 20, padding: '16px 18px',
          marginBottom: 14, border: '1.5px solid #D0C8B8',
          display: 'flex', alignItems: 'center', justifyContent: 'space-between'
        }}>
          <div>
            <div style={{ fontSize: 11, color: '#1D6B2A', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: 4 }}>
              À écrire
            </div>
            <div style={{ fontSize: 28, fontWeight: 900, color: '#3D2B1F', fontFamily: 'Georgia, serif' }}>
              {prompt}
            </div>
          </div>
          <button
            onClick={() => MamaJudi.speak('Write: ' + prompt, 0.75)}
            style={{
              background: '#1D6B2A', border: 'none', borderRadius: 12,
              padding: '8px 14px', cursor: 'pointer',
              display: 'flex', alignItems: 'center', gap: 6
            }}
          >
            <svg width="16" height="16" viewBox="0 0 24 24">
              <path d="M11 5C11 5 6 8 6 12C6 16 11 19 11 19V5Z" fill="white"/>
              <path d="M14 8.5C15.5 9.5 16 10.7 16 12C16 13.3 15.5 14.5 14 15.5" stroke="white" strokeWidth="2" fill="none" strokeLinecap="round"/>
            </svg>
            <span style={{ fontSize: 12, fontWeight: 700, color: 'white' }}>Écouter</span>
          </button>
        </div>

        {/* Instructions */}
        <div style={{
          background: '#FFF0E6', borderRadius: 14, padding: '8px 14px',
          marginBottom: 14, border: '1px solid #FFD4B0',
          fontSize: 12, color: '#7A6050', fontWeight: 600
        }}>
          ✏️ Trace les lettres avec ton doigt ou ta souris !
        </div>

        {/* Canvas */}
        <div
          ref={containerRef}
          style={{
            background: '#F0E8D8', borderRadius: 20, marginBottom: 14,
            border: '2px solid #D0C8B8', height: 220, position: 'relative',
            overflow: 'hidden'
          }}
        >
          <canvas
            ref={canvasRef}
            style={{
              position: 'absolute', top: 0, left: 0,
              width: '100%', height: '100%',
              touchAction: 'none', cursor: 'crosshair'
            }}
            onMouseDown={startDraw}
            onMouseMove={draw}
            onMouseUp={stopDraw}
            onMouseLeave={stopDraw}
            onTouchStart={startDraw}
            onTouchMove={draw}
            onTouchEnd={stopDraw}
          />
        </div>

        {/* Buttons */}
        <div style={{ display: 'flex', gap: 10 }}>
          <button onClick={clearCanvas} style={{
            flex: 1, padding: '13px 0', borderRadius: 16, border: '1.5px solid #D0C8B8',
            background: '#F0E8D8', color: '#7A6050', fontSize: 14, fontWeight: 800, cursor: 'pointer'
          }}>
            Effacer
          </button>
          <button onClick={next} style={{
            flex: 2, padding: '13px 0', borderRadius: 16, border: 'none',
            background: '#1D6B2A', color: 'white', fontSize: 14, fontWeight: 800, cursor: 'pointer'
          }}>
            {current === prompts.length - 1 ? 'Terminer ✓' : 'Suivant →'}
          </button>
        </div>
      </div>
    </div>
  )
}
