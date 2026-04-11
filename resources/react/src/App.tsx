import './styles/anglofun.css'
import { useState } from 'react'
import { SoundService } from './services/SoundService'
import { ThemeProvider } from './context/ThemeContext'
import ChildLogin from './pages/child/ChildLogin'
import ChildHome from './pages/child/ChildHome'
import ParentDashboard from './pages/parent/ParentDashboard'
import TVApp from './TVApp'
import DesktopApp from './DesktopApp'
import type { Child } from './types/child'

const isTV = window.location.pathname.startsWith('/tv') ||
  navigator.userAgent.toLowerCase().includes('webos') ||
  navigator.userAgent.toLowerCase().includes('smart-tv')
const isDesktop = !isTV && window.innerWidth >= 1024
const isMama = window.location.pathname.startsWith('/mama')

export default function App() {
  const [child, setChild] = useState<Child | null>(null)
  const [mode, setMode] = useState<'child' | 'parent'>('child')

  if (isTV) return <ThemeProvider><TVApp /></ThemeProvider>

  if (isMama) return <ThemeProvider><MamaSpace onExit={() => window.location.href = '/'} /></ThemeProvider>

  // Unlock audio on first interaction
  if (typeof window !== 'undefined') {
    document.addEventListener('click', () => SoundService.unlock(), { once: true })
  }

  const shell: Record<string, string | number> = {
    maxWidth: 480, margin: '0 auto', minHeight: '100vh',
    position: 'relative', overflow: 'hidden',
    boxShadow: '0 0 60px rgba(0,0,0,0.15)'
  }

  if (mode === 'parent') {
    return (
      <ThemeProvider>
        <div style={shell}>
          <div style={{ background: '#E8DCC8', minHeight: '100vh', fontFamily: 'Nunito, system-ui, sans-serif' }}>
            <div style={{ padding: '12px 16px', background: '#1D6B2A', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
              <div style={{ fontWeight: 900, color: 'white', fontSize: 16 }}>EDUMAISON</div>
              <button onClick={() => setMode('child')} style={{ fontSize: 12, padding: '6px 14px', borderRadius: 20, border: '1.5px solid rgba(255,255,255,0.4)', background: 'rgba(255,255,255,0.15)', color: 'white', cursor: 'pointer', fontWeight: 700 }}>
                Child mode
              </button>
            </div>
            <ParentDashboard />
          </div>
        </div>
      </ThemeProvider>
    )
  }

  if (!child) {
    if (isDesktop) return <ThemeProvider><ChildLogin onLogin={setChild} onParentMode={() => setMode('parent')} /></ThemeProvider>
    return <ThemeProvider><div style={shell}><ChildLogin onLogin={setChild} onParentMode={() => setMode('parent')} /></div></ThemeProvider>
  }

  if (isDesktop) return <ThemeProvider><DesktopApp child={child} onLogout={() => setChild(null)} /></ThemeProvider>
  return <ThemeProvider><div style={shell}><ChildHome child={child} onLogout={() => setChild(null)} /></div></ThemeProvider>
}
