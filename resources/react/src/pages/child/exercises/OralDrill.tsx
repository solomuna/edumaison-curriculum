import { useState, useEffect } from 'react'
import { MamaJudi } from '../../../services/MamaJudi'
import { SoundService } from '../../../services/SoundService'
import type { OralDrillContent } from '../../../types/exercise'

interface Props {
  title: string
  instructions: string
  content: OralDrillContent
  onComplete: (score: number) => void
  onBack: () => void
}

export default function OralDrill({ title, instructions, content, onComplete, onBack }: Props) {
  const [current, setCurrent] = useState(0)
  const [speaking, setSpeaking] = useState(false)
  const [listened, setListened] = useState<boolean[]>([])
  const [done, setDone] = useState(false)
  const [showEval, setShowEval] = useState(false)
  const [selfEval, setSelfEval] = useState<'good' | 'retry' | null>(null)
  const [repeatedCount, setRepeatedCount] = useState(0)

  const ENCOURAGEMENTS_GOOD = [
    'Super ! Tu prononces tres bien !',
    'Excellent ! Continue comme ca !',
    'Magnifique ! Ta prononciation est belle !',
    'Bravo ! Je suis fiere de toi !',
  ]
  const ENCOURAGEMENTS_RETRY = [
    'Ecoute encore et repete apres moi !',
    'Courage ! Prends ton temps et repete.',
    'Tu peux le faire ! Ecoute bien et repete.',
  ]

  const items = content.items
  const item = items[current]

  useEffect(() => {
        MamaJudi.speak(instructions)
    setListened(new Array(items.length).fill(false))
  }, [])

  const playItem = async () => {
    setSpeaking(true)
    MamaJudi.speak(item.text)
    setTimeout(() => setSpeaking(false), 2000)
    const updated = [...listened]
    updated[current] = true
    setListened(updated)
    setRepeatedCount(prev => prev + 1)
  }

  const handleSelfEval = (val: 'good' | 'retry') => {
    setSelfEval(val)
    setShowEval(false)
    if (val === 'good') {
      SoundService.correct()
      const msg = ENCOURAGEMENTS_GOOD[Math.floor(Math.random() * ENCOURAGEMENTS_GOOD.length)]
      MamaJudi.speak(msg)
      setTimeout(() => {
        setSelfEval(null)
        setRepeatedCount(0)
        if (current < items.length - 1) { setCurrent(current + 1) }
        else { setDone(true); const score = Math.round((listened.filter(Boolean).length / items.length) * 100); onComplete(score) }
      }, 1800)
    } else {
      SoundService.streak()
      const msg = ENCOURAGEMENTS_RETRY[Math.floor(Math.random() * ENCOURAGEMENTS_RETRY.length)]
      MamaJudi.speak(msg)
      setTimeout(() => { setSelfEval(null) }, 1500)
    }
  }

  const next = () => {
    if (listened[current]) {
      setShowEval(true)
    } else {
      // N'a pas encore ecoute — passer directement
      if (current < items.length - 1) { setCurrent(current + 1) }
      else { setDone(true); onComplete(0) }
    }
  }

  const prev = () => { if (current > 0) setCurrent(current - 1) }

  if (done) {
    return (
      <div style={{
        background: '#E8DCC8', minHeight: '100vh',
        fontFamily: "-apple-system, BlinkMacSystemFont, 'Trebuchet MS', sans-serif",
        display: 'flex', flexDirection: 'column', alignItems: 'center',
        justifyContent: 'center', padding: '24px 20px', textAlign: 'center'
      }}>
        <div style={{ fontSize: 56, marginBottom: 12 }}>🌟</div>
        <div style={{ fontSize: 26, fontWeight: 900, color: '#3D2B1F', marginBottom: 6 }}>Bravo !</div>
        <div style={{ fontSize: 14, color: '#7A6050', marginBottom: 24 }}>Tu as terminé : {title}</div>
        <button onClick={onBack} style={{
          padding: '13px 32px', borderRadius: 16, border: 'none',
          background: '#1D6B2A', color: 'white', fontSize: 15, fontWeight: 800, cursor: 'pointer'
        }}>Retour aux activités</button>
      </div>
    )
  }

  const pct = Math.round((current / items.length) * 100)

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
          padding: '6px 12px', fontSize: 13, fontWeight: 700, color: '#7A6050', cursor: 'pointer', flexShrink: 0
        }}>← Retour</button>
        <div style={{ flex: 1 }}>
          <div style={{ fontSize: 13, fontWeight: 800, color: '#3D2B1F', marginBottom: 4 }}>{title}</div>
          <div style={{ height: 5, background: '#D0C8B8', borderRadius: 3 }}>
            <div style={{ height: 5, borderRadius: 3, background: '#1D6B2A', width: `${pct}%`, transition: 'width 0.3s', background: '#1D6B2A' }}/>
          </div>
        </div>
        <div style={{ fontSize: 12, color: '#C8A090', fontWeight: 700, flexShrink: 0 }}>
          {current + 1}/{items.length}
        </div>
      </div>

      <div style={{ padding: 16 }}>
        {/* Instructions */}
        <div style={{
          background: '#F0E8D8', borderRadius: 16, padding: '10px 14px',
          marginBottom: 14, border: '1px solid #D0C8B8',
          display: 'flex', alignItems: 'center', gap: 10
        }}>
          <span style={{ fontSize: 20 }}>👩‍🏫</span>
          <span style={{ fontSize: 13, color: '#7A6050', fontWeight: 600 }}>
            {speaking ? 'Mama Judi parle...' : instructions}
          </span>
        </div>

        {/* Illustration */}
        {content.illustration && (
          <div style={{
            background: '#F0E8D8', borderRadius: 20, padding: 16,
            textAlign: 'center', fontSize: 56, marginBottom: 14,
            border: '1px solid #D0C8B8', lineHeight: 1
          }}>
            {content.illustration}
          </div>
        )}

        {/* Item card */}
        <div style={{
          background: '#F0E8D8', borderRadius: 24, padding: '32px 20px',
          marginBottom: 16, border: `2.5px solid ${item.color || '#FF8FAB'}`,
          textAlign: 'center', minHeight: 160,
          display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center'
        }}>
          {item.color && (
            <div style={{
              width: 60, height: 60, borderRadius: '50%',
              background: item.color, marginBottom: 16
            }}/>
          )}
          <div style={{
            fontSize: 28, fontWeight: 900, color: item.color || '#2D1B0E',
            lineHeight: 1.3, marginBottom: 10
          }}>
            {item.text}
          </div>
          {item.audio_hint && (
            <div style={{ fontSize: 13, color: '#B8A090' }}>{item.audio_hint}</div>
          )}
          {listened[current] && (
            <div style={{
              marginTop: 10, background: '#D1FAE5', color: '#065F46',
              fontSize: 12, fontWeight: 700, padding: '4px 12px', borderRadius: 10
            }}>
              ✅ Écouté !
            </div>
          )}
        </div>

        {/* Listen button */}
        <button onClick={playItem} disabled={speaking} style={{
          width: '100%', padding: '15px 0', borderRadius: 18, border: 'none',
          background: speaking ? '#C8A090' : '#FF8FAB',
          color: 'white', fontSize: 16, fontWeight: 800,
          cursor: speaking ? 'default' : 'pointer', marginBottom: 10,
          display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8
        }}>
          <svg width="20" height="20" viewBox="0 0 24 24">
            <path d="M11 5C11 5 6 8 6 12C6 16 11 19 11 19V5Z" fill="white"/>
            <path d="M14 8.5C15.5 9.5 16 10.7 16 12C16 13.3 15.5 14.5 14 15.5" stroke="white" strokeWidth="2" fill="none" strokeLinecap="round"/>
            <path d="M17 6C19.5 7.5 21 9.6 21 12C21 14.4 19.5 16.5 17 18" stroke="white" strokeWidth="2" fill="none" strokeLinecap="round"/>
          </svg>
          {speaking ? 'Mama Judi parle...' : 'Écouter Mama Judi'}
        </button>

        {/* Auto-eval popup */}
        {showEval && (
          <div style={{ position: 'fixed', inset: 0, zIndex: 999, background: 'rgba(0,0,0,0.5)', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '0 24px' }}>
            <div style={{ background: '#F0E8D8', borderRadius: 24, padding: '28px 24px', width: '100%', maxWidth: 340, textAlign: 'center' }}>
              <div style={{ fontSize: 44, marginBottom: 12 }}>{'🗣️'}</div>
              <div style={{ fontSize: 20, fontWeight: 900, color: '#3D2B1F', marginBottom: 6 }}>Tu as bien prononce ?</div>
              <div style={{ fontSize: 13, color: '#7A6050', marginBottom: 24 }}>Ecoute encore si tu n es pas sur !</div>
              <div style={{ display: 'flex', gap: 12 }}>
                <button onClick={() => handleSelfEval('retry')} style={{ flex: 1, padding: '14px', borderRadius: 14, border: '2px solid #D0C8B8', background: '#E8DCC8', fontSize: 24, cursor: 'pointer' }}>
                  {'😐'}
                  <div style={{ fontSize: 12, fontWeight: 700, color: '#7A6050', marginTop: 4 }}>Pas encore</div>
                </button>
                <button onClick={() => handleSelfEval('good')} style={{ flex: 1, padding: '14px', borderRadius: 14, border: 'none', background: '#1D6B2A', fontSize: 24, cursor: 'pointer' }}>
                  {'😊'}
                  <div style={{ fontSize: 12, fontWeight: 700, color: 'white', marginTop: 4 }}>Oui !</div>
                </button>
              </div>
            </div>
          </div>
        )}

        {/* Overlay encouragement */}
        {selfEval && (
          <div style={{ position: 'fixed', inset: 0, zIndex: 998, background: 'rgba(0,0,0,0.3)', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '0 24px' }}>
            <div style={{ background: '#F0E8D8', borderRadius: 24, padding: '24px', textAlign: 'center', maxWidth: 300 }}>
              <div style={{ fontSize: 48, marginBottom: 8 }}>{selfEval === 'good' ? '🌟' : '💪'}</div>
              <div style={{ fontSize: 16, fontWeight: 800, color: '#3D2B1F' }}>{selfEval === 'good' ? 'Super !' : 'Courage !'}</div>
            </div>
          </div>
        )}

        {/* Auto-eval popup */}
        {showEval && (
          <div style={{ position: 'fixed', inset: 0, zIndex: 999, background: 'rgba(0,0,0,0.5)', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '0 24px' }}>
            <div style={{ background: '#F0E8D8', borderRadius: 24, padding: '28px 24px', width: '100%', maxWidth: 340, textAlign: 'center' }}>
              <div style={{ fontSize: 44, marginBottom: 12 }}>{'🗣️'}</div>
              <div style={{ fontSize: 20, fontWeight: 900, color: '#3D2B1F', marginBottom: 6 }}>Tu as bien prononce ?</div>
              <div style={{ fontSize: 13, color: '#7A6050', marginBottom: 24 }}>Ecoute encore si tu n es pas sur !</div>
              <div style={{ display: 'flex', gap: 12 }}>
                <button onClick={() => handleSelfEval('retry')} style={{ flex: 1, padding: '14px', borderRadius: 14, border: '2px solid #D0C8B8', background: '#E8DCC8', fontSize: 24, cursor: 'pointer' }}>
                  {'😐'}
                  <div style={{ fontSize: 12, fontWeight: 700, color: '#7A6050', marginTop: 4 }}>Pas encore</div>
                </button>
                <button onClick={() => handleSelfEval('good')} style={{ flex: 1, padding: '14px', borderRadius: 14, border: 'none', background: '#1D6B2A', fontSize: 24, cursor: 'pointer' }}>
                  {'😊'}
                  <div style={{ fontSize: 12, fontWeight: 700, color: 'white', marginTop: 4 }}>Oui !</div>
                </button>
              </div>
            </div>
          </div>
        )}

        {/* Overlay encouragement */}
        {selfEval && (
          <div style={{ position: 'fixed', inset: 0, zIndex: 998, background: 'rgba(0,0,0,0.3)', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '0 24px' }}>
            <div style={{ background: '#F0E8D8', borderRadius: 24, padding: '24px', textAlign: 'center', maxWidth: 300 }}>
              <div style={{ fontSize: 48, marginBottom: 8 }}>{selfEval === 'good' ? '🌟' : '💪'}</div>
              <div style={{ fontSize: 16, fontWeight: 800, color: '#3D2B1F' }}>{selfEval === 'good' ? 'Super !' : 'Courage !'}</div>
            </div>
          </div>
        )}

        {/* Prev / Next */}
        <div style={{ display: 'flex', gap: 10 }}>
          <button onClick={prev} disabled={current === 0} style={{
            flex: 1, padding: '13px 0', borderRadius: 16, border: 'none',
            background: current === 0 ? '#F0E4D8' : '#FFF0E8',
            color: current === 0 ? '#C8A090' : '#C8704A',
            fontSize: 14, fontWeight: 800, cursor: current === 0 ? 'default' : 'pointer'
          }}>← Précédent</button>
          <button onClick={next} style={{
            flex: 2, padding: '13px 0', borderRadius: 16, border: 'none',
            background: '#10B981', color: 'white',
            fontSize: 14, fontWeight: 800, cursor: 'pointer'
          }}>
            {current < items.length - 1 ? 'Suivant →' : 'Terminer ✓'}
          </button>
        </div>
      </div>
    </div>
  )
}
