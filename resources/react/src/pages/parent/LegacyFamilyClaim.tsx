import { FormEvent, useState } from 'react'

type Props = { onClaimed: () => void }

function token() {
  return decodeURIComponent(document.cookie.split('; ').find(value => value.startsWith('XSRF-TOKEN='))?.split('=').slice(1).join('=') || '')
}

export default function LegacyFamilyClaim({ onClaimed }: Props) {
  const [form, setForm] = useState({ name: '', email: '', password: '', password_confirmation: '' })
  const [message, setMessage] = useState('')
  const [submitting, setSubmitting] = useState(false)

  const submit = async (event: FormEvent) => {
    event.preventDefault()
    if (submitting) return
    setSubmitting(true)
    setMessage('')
    try {
      await fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' })
      const response = await fetch('/api/family-auth/claim-legacy', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-XSRF-TOKEN': token() },
        body: JSON.stringify(form),
      })
      const data = await response.json().catch(() => ({}))
      if (!response.ok) {
        const firstError = Object.values(data.errors || {}).flat()[0]
        setMessage(String(firstError || data.message || 'Impossible de sécuriser cette famille.'))
        return
      }
      onClaimed()
    } catch {
      setMessage('Connexion impossible. Vérifie le réseau puis réessaie.')
    } finally {
      setSubmitting(false)
    }
  }

  const input: React.CSSProperties = { width: '100%', boxSizing: 'border-box', minHeight: 50, borderRadius: 12, border: '2px solid #D7E8DA', padding: '0 14px', fontSize: 15, background: '#FFF' }

  return <section style={{ maxWidth: 560, margin: '0 auto', background: 'var(--card)', border: '1.5px solid var(--border)', borderTop: '5px solid #1D6B2A', borderRadius: 8, padding: 20 }}>
    <div style={{ width: 46, height: 46, borderRadius: '50%', display: 'grid', placeItems: 'center', background: '#DDEFE1', color: '#1D6B2A', fontSize: 22, fontWeight: 900 }} aria-hidden="true">✓</div>
    <h2 style={{ margin: '14px 0 6px', fontSize: 22, color: 'var(--text-dark)' }}>Sécuriser cette famille</h2>
    <p style={{ margin: '0 0 16px', color: 'var(--text-soft)', lineHeight: 1.5, fontSize: 14 }}>
      Crée le compte parent de cette famille existante. Les enfants, leurs exercices et leurs progressions seront conservés.
    </p>
    <form onSubmit={submit} style={{ display: 'grid', gap: 10 }}>
      <input style={input} placeholder="Nom du parent" value={form.name} onChange={event => setForm({ ...form, name: event.target.value })} required />
      <input style={input} type="email" placeholder="Adresse e-mail" autoComplete="email" value={form.email} onChange={event => setForm({ ...form, email: event.target.value })} required />
      <input style={input} type="password" placeholder="Mot de passe (8 caractères minimum)" autoComplete="new-password" minLength={8} value={form.password} onChange={event => setForm({ ...form, password: event.target.value })} required />
      <input style={input} type="password" placeholder="Confirmer le mot de passe" autoComplete="new-password" minLength={8} value={form.password_confirmation} onChange={event => setForm({ ...form, password_confirmation: event.target.value })} required />
      <div aria-live="polite" style={{ minHeight: 20, color: '#B42318', fontSize: 13, fontWeight: 800 }}>{message}</div>
      <button disabled={submitting} style={{ minHeight: 50, border: 0, borderRadius: 12, background: '#1D6B2A', color: '#FFF', fontWeight: 900, fontSize: 15, cursor: submitting ? 'wait' : 'pointer', opacity: submitting ? .65 : 1 }}>
        {submitting ? 'Sécurisation…' : 'Créer le compte parent'}
      </button>
    </form>
  </section>
}
