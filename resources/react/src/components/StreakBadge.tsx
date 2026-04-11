import { useStreak } from '../hooks/useStreak'
import type { Child } from '../types/child'

interface Props {
  child: Child
  compact?: boolean
}

export default function StreakBadge({ child, compact = false }: Props) {
  const data = useStreak(child)
  if (!data) return null

  const flame = data.streak >= 7 ? '🔥' : data.streak >= 3 ? '⚡' : '✨'

  if (compact) {
    return (
      <div style={{
        display: 'flex', alignItems: 'center', gap: 6,
        background: data.streak > 0 ? '#FEF3C7' : '#F3F4F6',
        borderRadius: 20, padding: '4px 12px',
        border: `1.5px solid ${data.streak > 0 ? '#F59E0B' : '#E5E7EB'}`
      }}>
        <span style={{ fontSize: 16 }}>{flame}</span>
        <span style={{ fontSize: 13, fontWeight: 800, color: data.streak > 0 ? '#D97706' : '#9CA3AF' }}>
          {data.streak} jour{data.streak !== 1 ? 's' : ''}
        </span>
      </div>
    )
  }

  return (
    <div style={{
      display: 'grid', gridTemplateColumns: 'repeat(4,1fr)', gap: 10,
      margin: '12px 0'
    }}>
      {/* Streak actuel */}
      <div style={{
        background: data.streak > 0 ? '#FEF3C7' : '#F9F6F3',
        borderRadius: 16, padding: '12px 10px', textAlign: 'center',
        border: `2px solid ${data.streak > 0 ? '#FCD34D' : '#E5E7EB'}`
      }}>
        <div style={{ fontSize: 24 }}>{flame}</div>
        <div style={{ fontSize: 20, fontWeight: 900, color: data.streak > 0 ? '#D97706' : '#9CA3AF' }}>
          {data.streak}
        </div>
        <div style={{ fontSize: 10, color: '#B8A090', fontWeight: 600, marginTop: 2 }}>STREAK</div>
      </div>

      {/* Meilleur streak */}
      <div style={{ background: '#EDE9FE', borderRadius: 16, padding: '12px 10px', textAlign: 'center', border: '2px solid #DDD6FE' }}>
        <div style={{ fontSize: 24 }}>🏆</div>
        <div style={{ fontSize: 20, fontWeight: 900, color: '#7C3AED' }}>{data.best_streak}</div>
        <div style={{ fontSize: 10, color: '#B8A090', fontWeight: 600, marginTop: 2 }}>RECORD</div>
      </div>

      {/* Exercices aujourd'hui */}
      <div style={{ background: '#D1FAE5', borderRadius: 16, padding: '12px 10px', textAlign: 'center', border: '2px solid #A7F3D0' }}>
        <div style={{ fontSize: 24 }}>📝</div>
        <div style={{ fontSize: 20, fontWeight: 900, color: '#059669' }}>{data.today_count}</div>
        <div style={{ fontSize: 10, color: '#B8A090', fontWeight: 600, marginTop: 2 }}>AUJOURD'HUI</div>
      </div>

      {/* XP total */}
      <div style={{ background: '#DBEAFE', borderRadius: 16, padding: '12px 10px', textAlign: 'center', border: '2px solid #BFDBFE' }}>
        <div style={{ fontSize: 24 }}>⭐</div>
        <div style={{ fontSize: 20, fontWeight: 900, color: '#1D4ED8' }}>{data.total_xp}</div>
        <div style={{ fontSize: 10, color: '#B8A090', fontWeight: 600, marginTop: 2 }}>XP TOTAL</div>
      </div>
    </div>
  )
}
