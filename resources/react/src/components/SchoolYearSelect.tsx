// Choix de l'année scolaire (espace parent, bulletin) : l'année en cours par
// défaut ; les années précédentes restent consultables. Affiché seulement
// quand il y a plus d'une année.
import { useEffect, useState } from 'react'

export interface SchoolYearOption { id: number; label: string; is_current: boolean }

export function useSchoolYears(): SchoolYearOption[] {
  const [years, setYears] = useState<SchoolYearOption[]>([])
  useEffect(() => {
    fetch('/api/school-years', { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(response => (response.ok ? response.json() : []))
      .then(list => setYears(Array.isArray(list) ? list : []))
      .catch(() => setYears([]))
  }, [])
  return years
}

interface Props {
  years: SchoolYearOption[]
  value: number | null
  onChange: (id: number) => void
  /** Texte clair sur fond foncé (en-tête de l'espace parent). */
  light?: boolean
}

export default function SchoolYearSelect({ years, value, onChange, light = false }: Props) {
  if (years.length < 2) return null
  return (
    <select
      aria-label="Année scolaire"
      value={value ?? years.find(y => y.is_current)?.id ?? years[0].id}
      onChange={event => onChange(Number(event.target.value))}
      style={{
        padding: '4px 8px', borderRadius: 8, fontSize: 12, fontWeight: 800, fontFamily: 'inherit',
        border: light ? '1px solid rgba(255,255,255,0.45)' : '1px solid #D0C8B8',
        background: light ? 'rgba(255,255,255,0.12)' : '#FFF', color: light ? '#FFF' : '#333',
      }}
    >
      {years.map(year => (
        <option key={year.id} value={year.id} style={{ color: '#333' }}>
          {year.label}{year.is_current ? ' (en cours)' : ''}
        </option>
      ))}
    </select>
  )
}
