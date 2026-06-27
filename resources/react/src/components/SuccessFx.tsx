// SuccessFx.tsx
// Effet de celebration "exagere" (cf. Duolingo gamification) declenche apres
// une bonne reponse : confetti + "+XP" flottant + scale-pop.
//
// Usage (n'importe ou dans l'arbre) :
//   import { fireSuccess } from '../components/SuccessFx'
//   fireSuccess({ xp: 10, x: ev.clientX, y: ev.clientY })
//
// Le composant <SuccessFx /> doit etre monte UNE FOIS dans App.tsx pour ecouter
// les evenements. Il s'occupe lui-meme de jouer le confetti et d'afficher le +XP.
import { useEffect, useState } from 'react'
import confetti from 'canvas-confetti'

const EVENT = 'edumaison:success' as const

interface SuccessDetail {
  xp?: number   // points a afficher (default 10)
  x?: number    // origine confetti (px, viewport)
  y?: number
}

/** Declenche l'effet succes. Peut etre appele depuis n'importe ou. */
export function fireSuccess(detail: SuccessDetail = {}): void {
  if (typeof window === 'undefined') return
  window.dispatchEvent(new CustomEvent<SuccessDetail>(EVENT, { detail }))
}

interface FloatingXP {
  id: number
  xp: number
  x: number
  y: number
}

let _nextId = 1

export default function SuccessFx() {
  const [bursts, setBursts] = useState<FloatingXP[]>([])

  useEffect(() => {
    const handler = (e: Event) => {
      const d = (e as CustomEvent<SuccessDetail>).detail || {}
      const xp = d.xp ?? 10
      const x  = d.x ?? window.innerWidth / 2
      const y  = d.y ?? window.innerHeight / 2

      // 1. Confetti : 2 bursts pour effet "exagere"
      const origin = {
        x: Math.max(0, Math.min(1, x / window.innerWidth)),
        y: Math.max(0, Math.min(1, y / window.innerHeight)),
      }
      confetti({
        particleCount: 70,
        spread: 75,
        startVelocity: 35,
        origin,
        ticks: 90,
        colors: ['#FFD700', '#FF8FAB', '#1D6B2A', '#FFFFFF', '#F59E0B'],
        scalar: 1.1,
      })
      // Mini deuxieme volee pour effet "boom-pop"
      setTimeout(() => {
        confetti({
          particleCount: 40,
          angle: 60,
          spread: 55,
          origin: { x: Math.max(0, origin.x - 0.1), y: origin.y },
          colors: ['#FFD700', '#FF8FAB'],
        })
        confetti({
          particleCount: 40,
          angle: 120,
          spread: 55,
          origin: { x: Math.min(1, origin.x + 0.1), y: origin.y },
          colors: ['#1D6B2A', '#F59E0B'],
        })
      }, 120)

      // 2. +XP flottant
      const id = _nextId++
      setBursts(prev => [...prev, { id, xp, x, y }])
      // Auto-cleanup apres 1.4s (duree de l'animation)
      setTimeout(() => {
        setBursts(prev => prev.filter(b => b.id !== id))
      }, 1400)
    }
    window.addEventListener(EVENT, handler as EventListener)
    return () => window.removeEventListener(EVENT, handler as EventListener)
  }, [])

  return (
    <>
      {/* CSS de l'animation injectee une fois */}
      <style>{`
        @keyframes edumaison-xp-float {
          0%   { transform: translate(-50%, -50%) scale(0.4); opacity: 0; }
          15%  { transform: translate(-50%, -60%) scale(1.3); opacity: 1; }
          30%  { transform: translate(-50%, -80%) scale(1); opacity: 1; }
          100% { transform: translate(-50%, -180%) scale(0.85); opacity: 0; }
        }
        .edumaison-xp-burst {
          position: fixed;
          z-index: 9999;
          pointer-events: none;
          color: #FFD700;
          font-weight: 900;
          font-size: 36px;
          font-family: Nunito, system-ui, sans-serif;
          text-shadow:
            -2px -2px 0 #1D6B2A,
             2px -2px 0 #1D6B2A,
            -2px  2px 0 #1D6B2A,
             2px  2px 0 #1D6B2A,
             0   0  12px rgba(255,215,0,0.6);
          animation: edumaison-xp-float 1.4s cubic-bezier(.34,1.56,.64,1) forwards;
          will-change: transform, opacity;
        }
      `}</style>
      {bursts.map(b => (
        <div
          key={b.id}
          className="edumaison-xp-burst"
          style={{ left: b.x, top: b.y }}
        >
          +{b.xp} XP
        </div>
      ))}
    </>
  )
}
