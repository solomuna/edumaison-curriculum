import { useState, useEffect } from 'react'
import type { Child } from '../types/child'

interface Entry {
  id: number
  name: string
  level: string
  xp: number
  total_done: number
  today_done: number
  streak: number
  rank: number
  is_current: boolean
}

const RANK_COLORS = ['#F59E0B','#94A3B8','#CD7F32']
const RANK_LABELS = ['ðŸ¥‡','ðŸ¥ˆ','ðŸ¥‰']
const CHILD_COLORS = [
  { bg: '#FCE7F3', accent: '#EC4899' },
  { bg: '#FEF3C7', accent: '#F59E0B' },
  { bg: '#EDE9FE', accent: '#8B5CF6' },
  { bg: '#D1FAE5', accent: '#10B981' },
]

interface Props {
  child: Child
}

export default function FamilyLeaderboard({ child }: Props) {
  const [entries, setEntries] = useState<Entry[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetch(`/api/leaderboard/child/${child.id}`)
      .then(r => r.json())
      .then(setEntries)
      .catch(() => {})
      .finally(() => setLoading(false))
  }, [child.id])

  if (loading) return (
    <div style={{ padding: '12px 16px' }}>
      {[1,2,3].map(i => (
        <div key={i} style={{ height: 70, borderRadius: 18, background: '#F0E4D8', opacity: 0.4, marginBottom: 10 }}/>
      ))}
    </div>
  )

  if (entries.length <= 1) return null

  return (
    <div style={{ padding: '0 16px', marginBottom: 16 }}>
      <div style={{ fontSize: 14, fontWeight: 900, color: '#2D1B0E', marginBottom: 10, display: 'flex', alignItems: 'center', gap: 6 }}>
        ðŸ† Family Leaderboard
      </div>
      {entries.map((e, idx) => {
        const col = CHILD_COLORS[idx % CHILD_COLORS.length]
        const isCurrent = e.is_current
        const flame = e.streak >= 7 ? 'ðŸ”¥' : e.streak >= 3 ? 'âš¡' : e.streak > 0 ? 'âœ¨' : ''
        return (
          <div key={e.id} style={{
            background: isCurrent ? col.bg : 'white',
            borderRadius: 18, padding: '12px 14px', marginBottom: 8,
            border: `2px solid ${isCurrent ? col.accent : '#F0E4D8'}`,
            display: 'flex', alignItems: 'center', gap: 12,
            transition: 'all 0.2s'
          }}>
            {/* Rank */}
            <div style={{
              width: 36, height: 36, borderRadius: 12, flexShrink: 0,
              background: idx < 3 ? RANK_COLORS[idx] + '22' : '#F3F4F6',
              display: 'flex', alignItems: 'center', justifyContent: 'center',
              fontSize: idx < 3 ? 20 : 14, fontWeight: 900,
              color: idx < 3 ? RANK_COLORS[idx] : '#9CA3AF'
            }}>
              {idx < 3 ? RANK_LABELS[idx] : e.rank}
            </div>

            {/* Name & level */}
            <div style={{ flex: 1, minWidth: 0 }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                <span style={{ fontSize: 15, fontWeight: 900, color: isCurrent ? col.accent : '#2D1B0E' }}>
                  {e.name}
                </span>
                {isCurrent && <span style={{ fontSize: 10, background: col.accent, color: 'white', borderRadius: 6, padding: '1px 6px', fontWeight: 700 }}>Toi</span>}
                {flame && <span style={{ fontSize: 14 }}>{flame}</span>}
              </div>
              <div style={{ fontSize: 11, color: '#C8A090', fontWeight: 600, marginTop: 1 }}>{e.level}</div>
            </div>

            {/* Stats */}
            <div style={{ display: 'flex', gap: 10, flexShrink: 0 }}>
              <div style={{ textAlign: 'center' }}>
                <div style={{ fontSize: 16, fontWeight: 900, color: '#F59E0B' }}>{e.xp}</div>
                <div style={{ fontSize: 9, color: '#C8A090', fontWeight: 600 }}>XP</div>
              </div>
              <div style={{ textAlign: 'center' }}>
                <div style={{ fontSize: 16, fontWeight: 900, color: '#10B981' }}>{e.today_done}</div>
                <div style={{ fontSize: 9, color: '#C8A090', fontWeight: 600 }}>Auj.</div>
              </div>
              <div style={{ textAlign: 'center' }}>
                <div style={{ fontSize: 16, fontWeight: 900, color: '#8B5CF6' }}>{e.streak}j</div>
                <div style={{ fontSize: 9, color: '#C8A090', fontWeight: 600 }}>Streak</div>
              </div>
            </div>
          </div>
        )
      })}
    </div>
  )
}

