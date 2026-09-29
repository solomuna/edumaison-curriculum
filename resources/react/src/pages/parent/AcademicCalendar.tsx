import { useEffect, useState } from 'react'

type Year = {
  label: string; is_current: boolean; status: string; start_date: string | null; end_date: string | null
  source: { authority: string | null; url: string | null; reference: string | null; published_at: string | null }
  terms: Array<{ name: string; start_date: string | null; end_date: string | null }>
}

export default function AcademicCalendar() {
  const [years, setYears] = useState<Year[]>([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  useEffect(() => {
    fetch('/api/academic-calendar', { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(async response => { if (!response.ok) throw new Error(); return response.json() })
      .then(data => setYears(data.years || []))
      .catch(() => setError('Impossible de charger le calendrier pour le moment.'))
      .finally(() => setLoading(false))
  }, [])

  if (loading) return <div style={{ padding: 20 }}>Chargement du calendrier…</div>
  if (error) return <div style={{ color: '#B91C1C', padding: 20 }}>{error}</div>

  return <div>
    <h2 style={{ margin: '0 0 6px', fontSize: 20 }}>📅 Année scolaire</h2>
    <p style={{ margin: '0 0 18px', color: 'var(--text-soft)', fontSize: 13 }}>Dates, trimestres et séquences provenant des autorités scolaires.</p>
    {years.map(year => {
      const waiting = year.status === 'awaiting_official'
      return <section key={year.label} style={{ background: 'var(--card)', border: `1.5px solid ${waiting ? '#E5B85C' : 'var(--border)'}`, borderRadius: 18, padding: 18, marginBottom: 14 }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10, flexWrap: 'wrap' }}>
          <strong style={{ fontSize: 18 }}>{year.label}</strong>
          <span style={{ borderRadius: 999, padding: '5px 10px', fontSize: 11, fontWeight: 900, color: waiting ? '#7C4A03' : '#166534', background: waiting ? '#FEF3C7' : '#DCFCE7' }}>
            {waiting ? 'En attente de publication officielle' : year.is_current ? 'Année en cours' : 'Référence historique'}
          </span>
        </div>
        {waiting ? <p style={{ margin: '12px 0', lineHeight: 1.5, color: '#76552A', fontSize: 13 }}>EduMaison est prêt pour cette rentrée. Les dates ne seront affichées qu’après publication de l’arrêté officiel.</p> : <p style={{ margin: '10px 0', fontSize: 13 }}>{year.start_date || '—'} → {year.end_date || '—'}</p>}
        {year.terms.map(term => <div key={term.name} style={{ marginTop: 12, paddingTop: 10, borderTop: '1px solid var(--border)' }}><strong>{term.name}</strong> <span style={{ fontSize: 12, color: 'var(--text-soft)' }}>{term.start_date || 'date à confirmer'} → {term.end_date || 'date à confirmer'}</span></div>)}
        {year.source.authority && <div style={{ marginTop: 14, fontSize: 12, color: 'var(--text-soft)' }}>Source : {year.source.url ? <a href={year.source.url} target="_blank" rel="noreferrer">{year.source.authority}</a> : year.source.authority}{year.source.reference ? ` · ${year.source.reference}` : ''}</div>}
      </section>
    })}
  </div>
}
