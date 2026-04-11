import { useState, useRef, useEffect } from 'react'

interface Props { src?: string; volume?: number }

export default function BackgroundMusic({ src = '/sounds/background.mp3', volume = 0.15 }: Props) {
  const [playing, setPlaying] = useState(false)
  const ref = useRef<HTMLAudioElement>(null)

  useEffect(() => {
    if (ref.current) { ref.current.volume = volume; ref.current.loop = true }
  }, [volume])

  const toggle = () => {
    if (!ref.current) return
    if (playing) { ref.current.pause(); setPlaying(false) }
    else { ref.current.play().catch(() => {}); setPlaying(true) }
  }

  return (
    <>
      <audio ref={ref} src={src} preload="none" />
      <button onClick={toggle} title={playing ? 'Mute music' : 'Play music'}
        style={{ position: 'fixed', bottom: 72, right: 14, zIndex: 100, width: 40, height: 40, borderRadius: '50%', border: '1.5px solid var(--border)', background: 'var(--card)', cursor: 'pointer', fontSize: 20, display: 'flex', alignItems: 'center', justifyContent: 'center', boxShadow: '0 2px 8px rgba(0,0,0,0.12)' }}>
        {playing ? '🔊' : '🔇'}
      </button>
    </>
  )
}
