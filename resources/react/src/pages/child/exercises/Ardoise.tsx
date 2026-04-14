import { useRef, useState, useEffect } from 'react'

interface Props {
  onClose: () => void
}

const COLORS = ['#1D6B2A', '#CE1126', '#1E40AF', '#C47A3C', '#7C3AED', '#000000']
const SIZES = [3, 6, 12]

export default function Ardoise({ onClose }: Props) {
  const canvasRef = useRef<HTMLCanvasElement>(null)
  const [drawing, setDrawing] = useState(false)
  const [color, setColor] = useState('#1D6B2A')
  const [size, setSize] = useState(6)
  const [eraser, setEraser] = useState(false)
  const lastPos = useRef<{x:number,y:number}|null>(null)

  useEffect(() => {
    const canvas = canvasRef.current
    if (!canvas) return
    canvas.width = canvas.offsetWidth
    canvas.height = canvas.offsetHeight
    const ctx = canvas.getContext('2d')
    if (!ctx) return
    ctx.fillStyle = '#FFFDF5'
    ctx.fillRect(0, 0, canvas.width, canvas.height)
  }, [])

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
    const pos = getPos(e)
    lastPos.current = pos
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
    ctx.strokeStyle = eraser ? '#FFFDF5' : color
    ctx.lineWidth = eraser ? size * 4 : size
    ctx.lineCap = 'round'
    ctx.lineJoin = 'round'
    ctx.stroke()
    lastPos.current = pos
  }

  const endDraw = () => {
    setDrawing(false)
    lastPos.current = null
  }

  const clear = () => {
    const canvas = canvasRef.current
    const ctx = canvas?.getContext('2d')
    if (!ctx || !canvas) return
    ctx.fillStyle = '#FFFDF5'
    ctx.fillRect(0, 0, canvas.width, canvas.height)
  }

  return (
    <div style={{
      position: 'fixed', inset: 0, zIndex: 1000,
      background: 'rgba(0,0,0,0.6)',
      display: 'flex', flexDirection: 'column',
      fontFamily: 'Nunito, system-ui, sans-serif'
    }}>
      {/* Header */}
      <div style={{
        display: 'flex', alignItems: 'center', gap: 10,
        padding: '10px 14px', background: '#1D6B2A'
      }}>
        <span style={{ fontSize: 20 }}>&#9998;</span>
        <span style={{ color: 'white', fontWeight: 900, fontSize: 15, flex: 1 }}>Ardoise brouillon</span>
        <button onClick={clear} style={{
          background: 'rgba(255,255,255,0.2)', border: 'none', borderRadius: 8,
          padding: '5px 12px', color: 'white', fontWeight: 800, fontSize: 13, cursor: 'pointer'
        }}>Effacer</button>
        <button onClick={onClose} style={{
          background: '#CE1126', border: 'none', borderRadius: 8,
          padding: '5px 12px', color: 'white', fontWeight: 800, fontSize: 13, cursor: 'pointer'
        }}>Fermer</button>
      </div>

      {/* Canvas */}
      <canvas
        ref={canvasRef}
        style={{ flex: 1, touchAction: 'none', cursor: eraser ? 'cell' : 'crosshair', display: 'block', width: '100%' }}
        onMouseDown={startDraw} onMouseMove={draw} onMouseUp={endDraw} onMouseLeave={endDraw}
        onTouchStart={startDraw} onTouchMove={draw} onTouchEnd={endDraw}
      />

      {/* Toolbar */}
      <div style={{
        display: 'flex', alignItems: 'center', gap: 10, flexWrap: 'wrap',
        padding: '10px 14px', background: '#F0E8D8',
        borderTop: '2px solid #D0C8B8'
      }}>
        {/* Couleurs */}
        <div style={{ display: 'flex', gap: 6 }}>
          {COLORS.map(c => (
            <div key={c} onClick={() => { setColor(c); setEraser(false) }} style={{
              width: 26, height: 26, borderRadius: '50%', background: c,
              border: color === c && !eraser ? '3px solid #3D2B1F' : '2px solid #D0C8B8',
              cursor: 'pointer', boxSizing: 'border-box'
            }} />
          ))}
        </div>
        {/* Tailles */}
        <div style={{ display: 'flex', gap: 6, alignItems: 'center' }}>
          {SIZES.map(s => (
            <div key={s} onClick={() => setSize(s)} style={{
              width: s * 3 + 8, height: s * 3 + 8, borderRadius: '50%',
              background: size === s ? '#1D6B2A' : '#D0C8B8',
              cursor: 'pointer', transition: 'background 0.15s'
            }} />
          ))}
        </div>
        {/* Gomme */}
        <button onClick={() => setEraser(e => !e)} style={{
          padding: '5px 12px', borderRadius: 8, border: 'none',
          background: eraser ? '#CE1126' : '#D0C8B8',
          color: eraser ? 'white' : '#3D2B1F',
          fontWeight: 800, fontSize: 13, cursor: 'pointer'
        }}>&#9003; Gomme</button>
      </div>
    </div>
  )
}
