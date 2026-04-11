import { useState, useEffect } from 'react'
import type { Child } from '../types/child'

interface StreakData {
  streak: number
  best_streak: number
  total_days: number
  today_count: number
  total_xp: number
}

export function useStreak(child: Child | null) {
  const [data, setData] = useState<StreakData | null>(null)

  useEffect(() => {
    if (!child) return
    fetch(`/api/streak/child/${child.id}`)
      .then(r => r.json())
      .then(setData)
      .catch(() => {})
  }, [child?.id])

  return data
}
