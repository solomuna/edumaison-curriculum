import { useEffect, useRef } from 'react'

interface Props {
  active: boolean
  duration?: number  // ms, default 3000
}

const COLORS = ['#1D6B2A','#C47A3C','#CE1126','#F59E0B','#3B82F6','#EC4899','#10B981','#8B5CF6']

function randomBetween(a: number, b: number) { return a + Math.random() * (b - a) }

export default function Confetti({ active, duration = 3000 }: Props) {
  const canvasRef = useRef<HTMLCanvasElement>(null)
  const rafRef = useRef<number>(0)

  useEffect(() => {
    if (!active) return
    const canvas = canvasRef.current
    if (!canvas) return
    const ctx = canvas.getContext('2d')
    if (!ctx) return

    canvas.width = window.innerWidth
    canvas.height = window.innerHeight

    const pieces = Array.from({ length: 120 }, () => ({
      x: randomBetween(0, canvas.width),
      y: randomBetween(-canvas.height * 0.5, 0),
      w: randomBetween(7, 14),
      h: randomBetween(4, 9),
      color: COLORS[Math.floor(Math.random() * COLORS.length)],
      vx: randomBetween(-2, 2),
      vy: randomBetween(2, 5),
      angle: randomBetween(0, Math.PI * 2),
      spin: randomBetween(-0.15, 0.15),
    }))

    const start = performance.now()

    const draw = (now: number) => {
      const elapsed = now - start
      const alpha = Math.max(0, 1 - (elapsed - duration * 0.6) / (duration * 0.4))
      ctx.clearRect(0, 0, canvas.width, canvas.height)
      ctx.globalAlpha = alpha
      pieces.forEach(p => {
        p.x += p.vx
        p.y += p.vy
        p.angle += p.spin
        if (p.y > canvas.height) { p.y = -20; p.x = randomBetween(0, canvas.width) }
        ctx.save()
        ctx.translate(p.x, p.y)
        ctx.rotate(p.angle)
        ctx.fillStyle = p.color
        ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h)
        ctx.restore()
      })
      if (elapsed < duration) rafRef.current = requestAnimationFrame(draw)
      else ctx.clearRect(0, 0, canvas.width, canvas.height)
    }

    rafRef.current = requestAnimationFrame(draw)
    return () => cancelAnimationFrame(rafRef.current)
  }, [active])

  if (!active) return null

  return (
    <canvas
      ref={canvasRef}
      style={{
        position: 'fixed', top: 0, left: 0, width: '100%', height: '100%',
        pointerEvents: 'none', zIndex: 999
      }}
    />
  )
}
