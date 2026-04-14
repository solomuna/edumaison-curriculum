import { useState, useEffect } from 'react'

const BASE = 'http://192.168.100.106:8100'

// Cache en mémoire
let _cache: Record<string, string> | null = null
let _loading = false
let _callbacks: Array<() => void> = []

async function loadLibrary(): Promise<Record<string, string>> {
  if (_cache) return _cache
  if (_loading) {
    return new Promise(resolve => {
      _callbacks.push(() => resolve(_cache!))
    })
  }
  _loading = true
  try {
    const r = await fetch(`${BASE}/assets/icons`)
    const data = await r.json()
    _cache = {}
    for (const item of data) {
      _cache[item.key] = item.value
    }
  } catch {
    _cache = {}
  }
  _loading = false
  _callbacks.forEach(cb => cb())
  _callbacks = []
  return _cache!
}

// Mapping nom de matière → clé bibliothèque
const SUBJECT_MAP: Record<string, string> = {
  'mathematics': 'mathematics',
  'maths': 'mathematics',
  'english': 'english',
  'science and technology': 'science',
  'science': 'science',
  'french': 'french',
  'social studies': 'social_studies',
  'ict': 'ict',
  'reading': 'reading',
  'citizenship': 'citizenship',
  'national languages and cultures': 'nlc',
  'nlc': 'nlc',
  'fslc preparation': 'fslc',
  'handwriting': 'handwriting',
  'arts and crafts': 'arts_crafts',
  'physical education': 'physical_ed',
  'home economics': 'home_economics',
}

export function subjectKey(name: string): string {
  return SUBJECT_MAP[name.toLowerCase()] || 'reading'
}

// Hook principal
export function useAssetLibrary() {
  const [library, setLibrary] = useState<Record<string, string>>(_cache || {})

  useEffect(() => {
    if (_cache) { setLibrary(_cache); return }
    loadLibrary().then(lib => setLibrary({ ...lib }))
  }, [])

  const getIcon = (key: string, fallback = '📚'): string => {
    return library[key] || fallback
  }

  const getSubjectIcon = (subjectName: string): string => {
    const key = subjectKey(subjectName)
    return getIcon(key, '📚')
  }

  return { library, getIcon, getSubjectIcon }
}

// Composant simple pour afficher une icône
export function AssetIcon({ iconKey, subjectName, size = 24, fallback = '📚' }: {
  iconKey?: string
  subjectName?: string
  size?: number
  fallback?: string
}) {
  const { getIcon, getSubjectIcon } = useAssetLibrary()
  const icon = subjectName
    ? getSubjectIcon(subjectName)
    : getIcon(iconKey || '', fallback)

  return (
    <span style={{ fontSize: size, lineHeight: 1 }}>{icon}</span>
  )
}
