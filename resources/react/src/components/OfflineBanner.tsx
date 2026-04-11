interface Props {
  isOnline: boolean
  syncPending: boolean
}

export default function OfflineBanner({ isOnline, syncPending }: Props) {
  if (isOnline && !syncPending) return null

  return (
    <div style={{
      position: 'fixed', top: 0, left: 0, right: 0, zIndex: 999,
      padding: '8px 16px', textAlign: 'center',
      background: isOnline ? '#D1FAE5' : '#FEE2E2',
      borderBottom: `2px solid ${isOnline ? '#86EFAC' : '#FCA5A5'}`,
      fontSize: 13, fontWeight: 700,
      color: isOnline ? '#065F46' : '#991B1B',
      fontFamily: 'system-ui,sans-serif'
    }}>
      {!isOnline && '📵 You are offline — exercises are still available'}
      {isOnline && syncPending && '🔄 Syncing your progress...'}
    </div>
  )
}
