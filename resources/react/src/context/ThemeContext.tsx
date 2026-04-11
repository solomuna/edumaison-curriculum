import { createContext, useContext, useState, useEffect } from 'react'

interface ThemeCtx { isDark: boolean; toggle: () => void }
const Ctx = createContext<ThemeCtx>({ isDark: false, toggle: () => {} })

export function ThemeProvider({ children }: { children: React.ReactNode }) {
  const [isDark, setIsDark] = useState(() => localStorage.getItem('theme') === 'dark')

  useEffect(() => {
    document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light')
    localStorage.setItem('theme', isDark ? 'dark' : 'light')
  }, [isDark])

  return <Ctx.Provider value={{ isDark, toggle: () => setIsDark(d => !d) }}>{children}</Ctx.Provider>
}

export const useTheme = () => useContext(Ctx)
