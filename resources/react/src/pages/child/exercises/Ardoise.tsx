import { useRef, useState, useEffect } from 'react'

interface Props {
  onClose: () => void
}

const COLORS = ['#FFFFFF', '#FFEB3B', '#4FC3F7', '#A5D6A7', '#FF8A65', '#F48FB1']
const SIZES = [1, 3, 6, 12]

export default function Ardoise({ onClose }: Props) {
  const canvasRef = useRef<HTMLCanvasElement>(null)
  const [drawing, setDrawing] = useState(false)
  const [color, setColor] = useState('#FFFFFF')
  const [size, setSize] = useState(3)
  const [eraser, setEraser] = useState(false)
  const [expanded, setExpanded] = useState(false)
  const lastPos = useRef<{x:number,y:number}|null>(null)

  useEffect(() => {
    const canvas = canvasRef.current
    if (!canvas) return
    canvas.width = canvas.offsetWidth
    canvas.height = canvas.offsetHeight
    const ctx = canvas.getContext('2d')
    if (!ctx) return
    ctx.fillStyle = '#1A1A2E'
    ctx.fillRect(0, 0, canvas.width, canvas.height)
  }, [expanded])

  const getPos = (e: React.TouchEvent | React.MouseEvent) => {
    const canvas = canvasRef.current
    if (!canvas) return null
    const rect = canvas.getBoundingClientRect()
    const scaleX = canvas.width / rect.width
    const scaleY = canvas.height / rect.height
    if ('touches' in e) {
      const t = e.touches[0]
      return { x: (t.clientX - rect.left) * scaleX, y: (t.clientY - rect.top) * scaleY }
    }
    return { x: ((e as React.MouseEvent).clientX - rect.left) * scaleX, y: ((e as React.MouseEvent).clientY - rect.top) * scaleY }
  }

  const startDraw = (e: React.TouchEvent | React.MouseEvent) => {
    e.preventDefault()
    setDrawing(true)
    lastPos.current = getPos(e)
  }

  const draw = (e: React.TouchEvent | React.MouseEvent) => {
    e.preventDefault()
    if (!drawing) return
    const canvas = canvasRef.current
    const ctx = canvas?.getContext('2d')
    if (!ctx || !canvas) return
    const pos = getPos(e)
    if (!pos || !lastPos.current) return
    ctx.beginPath()
    ctx.moveTo(lastPos.current.x, lastPos.current.y)
    ctx.lineTo(pos.x, pos.y)
    ctx.strokeStyle = eraser ? '#1A1A2E' : color
    ctx.lineWidth = eraser ? size * 4 : size
    ctx.lineCap = 'round'
    ctx.lineJoin = 'round'
    ctx.stroke()
    lastPos.current = pos
  }

  const endDraw = () => { setDrawing(false); lastPos.current = null }

  const clear = () => {
    const canvas = canvasRef.current
    const ctx = canvas?.getContext('2d')
    if (!ctx || !canvas) return
    ctx.fillStyle = '#1A1A2E'
    ctx.fillRect(0, 0, canvas.width, canvas.height)
  }

  return (
    <div style={{
      position: 'fixed', bottom: 0, left: 0, right: 0, zIndex: 200,
      height: expanded ? '92vh' : '55vh',
      background: 'rgba(10,10,20,0.97)',
      borderRadius: '20px 20px 0 0',
      display: 'flex', flexDirection: 'column',
      fontFamily: 'Nunito, system-ui, sans-serif',
      boxShadow: '0 -4px 30px rgba(0,0,0,0.5)',
      transition: 'height 0.3s ease'
    }}>
      {/* Header */}
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '10px 14px 6px' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
          <div style={{ width: 36, height: 4, background: 'rgba(255,255,255,0.3)', borderRadius: 2 }} />
          <span style={{ color: 'rgba(255,255,255,0.6)', fontSize: 12, fontWeight: 700 }}>Ardoise</span>
        </div>
        <div style={{ display: 'flex', gap: 6 }}>
          <button onClick={() => setExpanded(e => !e)} style={{ background: 'rgba(255,255,255,0.1)', border: 'none', borderRadius: 8, padding: '4px 10px', color: 'rgba(255,255,255,0.8)', fontWeight: 800, fontSize: 14, cursor: 'pointer' }}>
            {expanded ? '\u2193' : '\u2191'}
          </button>
          <button onClick={clear} style={{ background: 'rgba(255,255,255,0.1)', border: 'none', borderRadius: 8, padding: '4px 12px', color: 'rgba(255,255,255,0.8)', fontWeight: 800, fontSize: 12, cursor: 'pointer' }}>Effacer</button>
          <button onClick={onClose} style={{ background: '#CE1126', border: 'none', borderRadius: 8, padding: '4px 12px', color: 'white', fontWeight: 800, fontSize: 12, cursor: 'pointer' }}>X</button>
        </div>
      </div>

      {/* Canvas */}
      <canvas
        ref={canvasRef}
        style={{ flex: 1, touchAction: 'none', cursor: eraser ? 'cell' : 'crosshair', display: 'block', width: '100%', borderTop: '1px solid rgba(255,255,255,0.1)', borderBottom: '1px solid rgba(255,255,255,0.1)' }}
        onMouseDown={startDraw} onMouseMove={draw} onMouseUp={endDraw} onMouseLeave={endDraw}
        onTouchStart={startDraw} onTouchMove={draw} onTouchEnd={endDraw}
      />

      {/* Toolbar */}
      <div style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '8px 14px', flexWrap: 'wrap' }}>
        {/* Couleurs */}
        <div style={{ display: 'flex', gap: 5 }}>
          {COLORS.map(c => (
            <div key={c} onClick={() => { setColor(c); setEraser(false) }} style={{ width: 22, height: 22, borderRadius: '50%', background: c, border: color === c && !eraser ? '3px solid #C47A3C' : '2px solid rgba(255,255,255,0.2)', cursor: 'pointer', boxSizing: 'border-box' as const }} />
          ))}
        </div>
        {/* Tailles - 4 options dont 1px fin */}
        <div style={{ display: 'flex', gap: 5, alignItems: 'center' }}>
          {SIZES.map(s => (
            <div key={s} onClick={() => setSize(s)} style={{
              width: Math.max(s * 2 + 4, 10), height: Math.max(s * 2 + 4, 10),
              borderRadius: '50%',
              background: size === s ? '#C47A3C' : 'rgba(255,255,255,0.3)',
              cursor: 'pointer',
              border: size === s ? '2px solid white' : 'none'
            }} />
          ))}
        </div>
        {/* Gomme */}
        <button onClick={() => setEraser(e => !e)} style={{ padding: '3px 10px', borderRadius: 8, border: 'none', background: eraser ? '#CE1126' : 'rgba(255,255,255,0.15)', color: 'white', fontWeight: 800, fontSize: 12, cursor: 'pointer' }}>Gomme</button>
      </div>
    </div>
  )
}
