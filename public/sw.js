const CACHE = 'edumaison-v2'
const API_CACHE = 'edumaison-api-v2'
const QUEUE_KEY = 'offline-attempts'

const STATIC_ASSETS = [
  '/',
  '/react/index.html',
  '/manifest.json',
]

// ── Install ──────────────────────────────────────────────────────────────────
self.addEventListener('install', e => {
  e.waitUntil(
    caches.open(CACHE).then(c =>
      Promise.allSettled(STATIC_ASSETS.map(url => c.add(url).catch(() => {})))
    )
  )
  self.skipWaiting()
})

// ── Activate ─────────────────────────────────────────────────────────────────
self.addEventListener('activate', e => {
  e.waitUntil(
    caches.keys().then(keys =>
      Promise.all(keys.filter(k => k !== CACHE && k !== API_CACHE).map(k => caches.delete(k)))
    )
  )
  self.clients.claim()
})

// ── Fetch ─────────────────────────────────────────────────────────────────────
self.addEventListener('fetch', e => {
  const url = new URL(e.request.url)

  // POST /api/exercises/attempt — queue if offline
  if (url.pathname === '/api/exercises/attempt' && e.request.method === 'POST') {
    e.respondWith(
      fetch(e.request.clone()).catch(async () => {
        const body = await e.request.clone().json()
        await queueAttempt(body)
        return new Response(JSON.stringify({ queued: true }), {
          headers: { 'Content-Type': 'application/json' }
        })
      })
    )
    return
  }

  // GET /api/ — network first, fallback to cache
  if (url.pathname.startsWith('/api/') && e.request.method === 'GET') {
    e.respondWith(
      fetch(e.request).then(response => {
        if (response.ok) {
          const clone = response.clone()
          caches.open(API_CACHE).then(c => c.put(e.request, clone))
        }
        return response
      }).catch(() =>
        caches.match(e.request, { cacheName: API_CACHE }).then(cached =>
          cached || new Response(JSON.stringify({ error: 'offline', cached: false }), {
            headers: { 'Content-Type': 'application/json' }
          })
        )
      )
    )
    return
  }

  // Static assets — cache first
  e.respondWith(
    caches.match(e.request).then(cached => {
      if (cached) return cached
      return fetch(e.request).then(response => {
        if (response.ok) {
          const clone = response.clone()
          caches.open(CACHE).then(c => c.put(e.request, clone))
        }
        return response
      }).catch(() => caches.match('/'))
    })
  )
})

// ── Background Sync ───────────────────────────────────────────────────────────
self.addEventListener('sync', e => {
  if (e.tag === 'sync-attempts') {
    e.waitUntil(syncQueuedAttempts())
  }
})

// ── Message from app ──────────────────────────────────────────────────────────
self.addEventListener('message', e => {
  if (e.data?.type === 'CACHE_EXERCISES') {
    const { childId, levelId } = e.data
    cacheExercisesForChild(childId, levelId)
  }
  if (e.data?.type === 'SYNC_NOW') {
    syncQueuedAttempts()
  }
})

// ── Helpers ───────────────────────────────────────────────────────────────────
async function queueAttempt(body) {
  const db = await openDB()
  const tx = db.transaction('queue', 'readwrite')
  tx.objectStore('queue').add({ body, ts: Date.now() })
}

async function syncQueuedAttempts() {
  const db = await openDB()
  const tx = db.transaction('queue', 'readwrite')
  const store = tx.objectStore('queue')
  const all = await promisify(store.getAll())
  if (!all || all.length === 0) return

  for (const item of all) {
    try {
      const res = await fetch('/api/exercises/attempt', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(item.body),
      })
      if (res.ok) {
        const tx2 = db.transaction('queue', 'readwrite')
        tx2.objectStore('queue').delete(item.id)
      }
    } catch (e) {
      // still offline, try later
    }
  }

  // Notify clients
  const clients = await self.clients.matchAll()
  clients.forEach(c => c.postMessage({ type: 'SYNC_DONE', count: all.length }))
}

async function cacheExercisesForChild(childId, levelId) {
  const urls = [
    `/api/exercises/child/${childId}`,
    `/api/subjects`,
    `/api/streak/child/${childId}`,
    `/api/leaderboard/child/${childId}`,
  ]
  const c = await caches.open(API_CACHE)
  await Promise.allSettled(
    urls.map(url => fetch(url).then(r => r.ok ? c.put(url, r) : null).catch(() => {}))
  )
}

// Simple IndexedDB wrapper
function openDB() {
  return new Promise((resolve, reject) => {
    const req = indexedDB.open('edumaison-offline', 1)
    req.onupgradeneeded = e => {
      const db = e.target.result
      if (!db.objectStoreNames.contains('queue')) {
        db.createObjectStore('queue', { keyPath: 'id', autoIncrement: true })
      }
    }
    req.onsuccess = e => resolve(e.target.result)
    req.onerror = () => reject(req.error)
  })
}

function promisify(req) {
  return new Promise((resolve, reject) => {
    req.onsuccess = () => resolve(req.result)
    req.onerror = () => reject(req.error)
  })
}
