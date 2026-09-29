import { useEffect, useState } from 'react'

interface Props {
  size?: number
  borderColor?: string
  borderWidth?: number
}

const fallback = '/images/default-companion.webp'

function avatarUrl(value: unknown): string {
  if (typeof value !== 'string' || value === '') return fallback
  if (value.startsWith('http') || value.startsWith('/')) return value
  return `/storage/${value}`
}

export default function CompanionAvatar({ size = 64, borderColor = '#1D6B2A', borderWidth = 3 }: Props) {
  const [src, setSrc] = useState(fallback)
  const [name, setName] = useState('Accompagnateur')

  useEffect(() => {
    fetch('/api/mama/profile', { headers: { Accept: 'application/json' } })
      .then(response => response.ok ? response.json() : null)
      .then(profile => {
        if (!profile) return
        setSrc(avatarUrl(profile.avatar))
        setName(profile.display_name || 'Accompagnateur')
      })
      .catch(() => {})
  }, [])

  return (
    <img
      src={src}
      alt={name}
      onError={event => { event.currentTarget.src = fallback }}
      style={{
        width: size, height: size, borderRadius: '50%', objectFit: 'cover',
        border: `${borderWidth}px solid ${borderColor}`, flexShrink: 0,
      }}
    />
  )
}
