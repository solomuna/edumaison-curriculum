import { useState, useEffect } from 'react'
import type { Child } from '../types/child'

export function useOfflineSync(child: Child | null) {
  const [isOnline, setIsOnline] = useState(navigator.onLine)
  const [syncPending, setSyncPending] = useState(false)

  useEffect(() => {
    const goOnline = () => {
      setIsOnline(true)
      // Trigger sync when back online
      if ('serviceWorker' in navigator && 'SyncManager' in window) {
        navigator.serviceWorker.ready.then(sw => {
          (sw as any).sync?.register('sync-attempts').catch(() => {
            // Fallback: send message to SW
            sw.active?.postMessage({ type: 'SYNC_NOW' })
          })
        })
      }
    }
    const goOffline = () => setIsOnline(false)

    window.addEventListener('online', goOnline)
    window.addEventListener('offline', goOffline)

    // Listen for sync done
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.addEventListener('message', e => {
        if (e.data?.type === 'SYNC_DONE') {
          setSyncPending(false)
        }
      })
    }

    return () => {
      window.removeEventListener('online', goOnline)
      window.removeEventListener('offline', goOffline)
    }
  }, [])

  // Pre-cache exercises when child logs in
  useEffect(() => {
    if (!child) return
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.ready.then(sw => {
        sw.active?.postMessage({
          type: 'CACHE_EXERCISES',
          childId: child.id,
          levelId: child.level_id,
        })
      }).catch(() => {})
    }
  }, [child?.id])

  return { isOnline, syncPending }
}
