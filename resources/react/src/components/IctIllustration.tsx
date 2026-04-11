
const icons: Record<string, () => JSX.Element> = {
  monitor: () => (
    <svg viewBox="0 0 120 90" width="120" height="90" xmlns="http://www.w3.org/2000/svg">
      <rect x="5" y="4" width="110" height="66" rx="6" fill="#DBEAFE" stroke="#3B82F6" strokeWidth="3"/>
      <rect x="10" y="9" width="100" height="56" rx="3" fill="#EFF6FF"/>
      <line x1="30" y1="25" x2="90" y2="25" stroke="#93C5FD" strokeWidth="2" strokeLinecap="round"/>
      <line x1="30" y1="33" x2="90" y2="33" stroke="#93C5FD" strokeWidth="2" strokeLinecap="round"/>
      <line x1="30" y1="41" x2="70" y2="41" stroke="#93C5FD" strokeWidth="2" strokeLinecap="round"/>
      <rect x="46" y="70" width="28" height="8" rx="2" fill="#93C5FD"/>
      <rect x="32" y="78" width="56" height="5" rx="2" fill="#BFDBFE"/>
    </svg>
  ),
  keyboard: () => (
    <svg viewBox="0 0 120 60" width="120" height="60" xmlns="http://www.w3.org/2000/svg">
      <rect x="2" y="10" width="116" height="44" rx="7" fill="#F1F5F9" stroke="#94A3B8" strokeWidth="2.5"/>
      {[0,1,2,3,4,5,6,7,8,9].map(i => (
        <rect key={i} x={8+i*11} y="17" width="9" height="9" rx="2" fill="white" stroke="#CBD5E1" strokeWidth="1"/>
      ))}
      {[0,1,2,3,4,5,6,7,8].map(i => (
        <rect key={i} x={12+i*11} y="29" width="9" height="9" rx="2" fill="white" stroke="#CBD5E1" strokeWidth="1"/>
      ))}
      <rect x="30" y="41" width="60" height="9" rx="2" fill="white" stroke="#CBD5E1" strokeWidth="1"/>
      <rect x="8" y="41" width="18" height="9" rx="2" fill="#DBEAFE" stroke="#93C5FD" strokeWidth="1"/>
      <rect x="94" y="41" width="18" height="9" rx="2" fill="#DBEAFE" stroke="#93C5FD" strokeWidth="1"/>
    </svg>
  ),
  mouse: () => (
    <svg viewBox="0 0 80 110" width="80" height="110" xmlns="http://www.w3.org/2000/svg">
      <path d="M 40 8 C 12 8 8 30 8 55 C 8 80 20 102 40 102 C 60 102 72 80 72 55 C 72 30 68 8 40 8 Z" fill="#F1F5F9" stroke="#94A3B8" strokeWidth="2.5"/>
      <line x1="40" y1="8" x2="40" y2="55" stroke="#CBD5E1" strokeWidth="1.5"/>
      <rect x="28" y="22" width="10" height="18" rx="5" fill="#DBEAFE" stroke="#93C5FD" strokeWidth="1.5"/>
      <rect x="42" y="22" width="10" height="18" rx="5" fill="#DBEAFE" stroke="#93C5FD" strokeWidth="1.5"/>
      <circle cx="40" cy="62" r="5" fill="#94A3B8"/>
    </svg>
  ),
  printer: () => (
    <svg viewBox="0 0 120 90" width="120" height="90" xmlns="http://www.w3.org/2000/svg">
      <rect x="10" y="5" width="100" height="18" rx="4" fill="#E2E8F0" stroke="#94A3B8" strokeWidth="2"/>
      <rect x="4" y="23" width="112" height="40" rx="6" fill="#F1F5F9" stroke="#94A3B8" strokeWidth="2.5"/>
      <rect x="10" y="63" width="100" height="22" rx="4" fill="white" stroke="#CBD5E1" strokeWidth="1.5"/>
      <line x1="18" y1="70" x2="102" y2="70" stroke="#E2E8F0" strokeWidth="1.5"/>
      <line x1="18" y1="76" x2="80" y2="76" stroke="#E2E8F0" strokeWidth="1.5"/>
      <circle cx="90" cy="43" r="6" fill="#10B981"/>
      <circle cx="75" cy="43" r="6" fill="#F59E0B"/>
    </svg>
  ),
  usb: () => (
    <svg viewBox="0 0 80 100" width="80" height="100" xmlns="http://www.w3.org/2000/svg">
      <rect x="22" y="4" width="36" height="62" rx="6" fill="#DBEAFE" stroke="#3B82F6" strokeWidth="2.5"/>
      <rect x="28" y="10" width="24" height="14" rx="3" fill="white" stroke="#93C5FD" strokeWidth="1.5"/>
      <rect x="30" y="28" width="8" height="6" rx="1" fill="#93C5FD"/>
      <rect x="42" y="28" width="8" height="6" rx="1" fill="#93C5FD"/>
      <rect x="28" y="40" width="24" height="4" rx="2" fill="#BFDBFE"/>
      <rect x="22" y="66" width="36" height="30" rx="3" fill="#1D4ED8" stroke="#1E40AF" strokeWidth="1.5"/>
      <rect x="26" y="70" width="28" height="3" rx="1" fill="#60A5FA"/>
      <circle cx="40" cy="81" r="4" fill="#60A5FA"/>
    </svg>
  ),
  computer: () => (
    <svg viewBox="0 0 130 100" width="130" height="100" xmlns="http://www.w3.org/2000/svg">
      <rect x="4" y="4" width="80" height="58" rx="5" fill="#DBEAFE" stroke="#3B82F6" strokeWidth="2.5"/>
      <rect x="8" y="8" width="72" height="50" rx="3" fill="#EFF6FF"/>
      <text x="44" y="37" textAnchor="middle" fontSize="16" fill="#3B82F6" fontFamily="monospace" fontWeight="bold">&gt;_</text>
      <rect x="38" y="62" width="16" height="8" rx="2" fill="#93C5FD"/>
      <rect x="24" y="70" width="44" height="4" rx="2" fill="#BFDBFE"/>
      <rect x="90" y="20" width="36" height="26" rx="4" fill="#F1F5F9" stroke="#94A3B8" strokeWidth="2"/>
      {[0,1,2].map(i => [0,1,2].map(j => (
        <rect key={`${i}${j}`} x={93+j*11} y={23+i*8} width="8" height="6" rx="1" fill="white" stroke="#CBD5E1" strokeWidth="1"/>
      )))}
    </svg>
  ),
  wifi: () => (
    <svg viewBox="0 0 100 80" width="100" height="80" xmlns="http://www.w3.org/2000/svg">
      <path d="M 10 35 Q 50 5 90 35" fill="none" stroke="#3B82F6" strokeWidth="4" strokeLinecap="round"/>
      <path d="M 22 47 Q 50 27 78 47" fill="none" stroke="#3B82F6" strokeWidth="4" strokeLinecap="round"/>
      <path d="M 34 59 Q 50 49 66 59" fill="none" stroke="#3B82F6" strokeWidth="4" strokeLinecap="round"/>
      <circle cx="50" cy="70" r="5" fill="#3B82F6"/>
    </svg>
  ),
  email: () => (
    <svg viewBox="0 0 120 80" width="120" height="80" xmlns="http://www.w3.org/2000/svg">
      <rect x="4" y="10" width="112" height="62" rx="6" fill="#DBEAFE" stroke="#3B82F6" strokeWidth="2.5"/>
      <path d="M 4 10 L 60 46 L 116 10" fill="none" stroke="#3B82F6" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"/>
      <line x1="4" y1="72" x2="38" y2="44" stroke="#3B82F6" strokeWidth="2" strokeLinecap="round"/>
      <line x1="116" y1="72" x2="82" y2="44" stroke="#3B82F6" strokeWidth="2" strokeLinecap="round"/>
    </svg>
  ),
  scratch: () => (
    <svg viewBox="0 0 110 90" width="110" height="90" xmlns="http://www.w3.org/2000/svg">
      <rect x="4" y="4" width="102" height="82" rx="8" fill="#FEF3C7" stroke="#F59E0B" strokeWidth="2.5"/>
      <rect x="10" y="12" width="40" height="14" rx="5" fill="#F59E0B"/>
      <text x="30" y="23" textAnchor="middle" fontSize="9" fill="white" fontFamily="sans-serif" fontWeight="bold">Motion</text>
      <rect x="10" y="30" width="40" height="14" rx="5" fill="#10B981"/>
      <text x="30" y="41" textAnchor="middle" fontSize="9" fill="white" fontFamily="sans-serif" fontWeight="bold">Looks</text>
      <rect x="10" y="48" width="40" height="14" rx="5" fill="#EC4899"/>
      <text x="30" y="59" textAnchor="middle" fontSize="9" fill="white" fontFamily="sans-serif" fontWeight="bold">Events</text>
      <rect x="58" y="12" width="48" height="62" rx="5" fill="white" stroke="#E5E7EB" strokeWidth="1.5"/>
      <ellipse cx="82" cy="43" rx="16" ry="20" fill="#FDE68A" stroke="#F59E0B" strokeWidth="1.5"/>
      <circle cx="77" cy="38" r="3" fill="#1F2937"/>
      <circle cx="87" cy="38" r="3" fill="#1F2937"/>
      <path d="M 77 50 Q 82 55 87 50" stroke="#1F2937" strokeWidth="1.5" fill="none" strokeLinecap="round"/>
    </svg>
  ),
  cadran: () => (
    <svg viewBox="0 0 100 100" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
      <circle cx="50" cy="50" r="46" fill="#FEF3C7" stroke="#F59E0B" strokeWidth="3"/>
      <circle cx="50" cy="50" r="40" fill="#FFFBEB"/>
      {[0,1,2,3,4,5,6,7,8,9,10,11].map(i => {
        const a = (i*30-90)*Math.PI/180
        return <line key={i} x1={50+34*Math.cos(a)} y1={50+34*Math.sin(a)} x2={50+40*Math.cos(a)} y2={50+40*Math.sin(a)} stroke="#F59E0B" strokeWidth={i%3===0?2.5:1.5} strokeLinecap="round"/>
      })}
      <text x="50" y="28" textAnchor="middle" fontSize="10" fill="#92400E" fontFamily="sans-serif" fontWeight="bold">12</text>
      <text x="72" y="54" textAnchor="middle" fontSize="10" fill="#92400E" fontFamily="sans-serif" fontWeight="bold">3</text>
      <text x="50" y="78" textAnchor="middle" fontSize="10" fill="#92400E" fontFamily="sans-serif" fontWeight="bold">6</text>
      <text x="28" y="54" textAnchor="middle" fontSize="10" fill="#92400E" fontFamily="sans-serif" fontWeight="bold">9</text>
      <line x1="50" y1="50" x2="50" y2="22" stroke="#1F2937" strokeWidth="3" strokeLinecap="round"/>
      <line x1="50" y1="50" x2="68" y2="50" stroke="#3B82F6" strokeWidth="2.5" strokeLinecap="round"/>
      <circle cx="50" cy="50" r="3.5" fill="#1F2937"/>
    </svg>
  ),  security: () => (
    <svg viewBox="0 0 90 100" width="90" height="100" xmlns="http://www.w3.org/2000/svg">
      <path d="M 45 6 L 82 20 L 82 55 C 82 75 65 92 45 98 C 25 92 8 75 8 55 L 8 20 Z" fill="#DBEAFE" stroke="#3B82F6" strokeWidth="2.5"/>
      <path d="M 45 14 L 74 26 L 74 55 C 74 71 61 85 45 90 C 29 85 16 71 16 55 L 16 26 Z" fill="#EFF6FF"/>
      <path d="M 32 52 L 42 62 L 62 42" stroke="#10B981" strokeWidth="4" fill="none" strokeLinecap="round" strokeLinejoin="round"/>
    </svg>
  ),
}

export const ICT_KEYWORDS: Record<string, keyof typeof icons> = {
  monitor: 'monitor', screen: 'monitor', display: 'monitor',
  keyboard: 'keyboard', type: 'keyboard',
  mouse: 'mouse', click: 'mouse',
  printer: 'printer', print: 'printer',
  usb: 'usb', flash: 'usb', storage: 'usb',
  computer: 'computer', laptop: 'computer', pc: 'computer',
  wifi: 'wifi', internet: 'wifi', network: 'wifi', browser: 'wifi',
  email: 'email', mail: 'email', '@': 'email',
  scratch: 'scratch', sprite: 'scratch', programming: 'scratch',
  security: 'security', password: 'security', virus: 'security', antivirus: 'security',
}

interface Props {
  keyword?: string
  fallback?: string
}

export default function IctIllustration({ keyword = '', fallback }: Props) {
  const lower = keyword.toLowerCase()
  let key: keyof typeof icons | undefined

  for (const [k, v] of Object.entries(ICT_KEYWORDS)) {
    if (lower.includes(k)) { key = v; break }
  }

  if (!key) return (
    <div style={{ fontSize: 48, textAlign: 'center' }}>{fallback || 'ðŸ’»'}</div>
  )

  const Icon = icons[key]
  return (
    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
      <Icon />
    </div>
  )
}

