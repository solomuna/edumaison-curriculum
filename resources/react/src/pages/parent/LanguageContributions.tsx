import { useEffect, useState } from 'react'

type Language = {
  id: number
  code: string | null
  catalogue_name: string | null
  variant_name: string | null
  display_name: string
}

type Contribution = {
  id: number
  language_profile_id: number
  variant_name: string | null
  kind: 'word' | 'expression' | 'short_sentence'
  source_text: string
  french_translation: string
  english_translation: string
  usage_context: string
  source_origin: 'adult_speaker' | 'family_creation' | 'authorized_reference'
  source_reference: string | null
  rights_confirmed: boolean
  status: 'draft' | 'submitted' | 'in_review' | 'approved' | 'rejected'
  submitted_at: string | null
  created_at: string
}

type FormState = {
  language_profile_id: string
  kind: Contribution['kind']
  source_text: string
  french_translation: string
  english_translation: string
  usage_context: string
  source_origin: Contribution['source_origin']
  source_reference: string
  rights_confirmed: boolean
}

const emptyForm = (languageId = ''): FormState => ({
  language_profile_id: languageId,
  kind: 'word',
  source_text: '',
  french_translation: '',
  english_translation: '',
  usage_context: '',
  source_origin: 'adult_speaker',
  source_reference: '',
  rights_confirmed: false,
})

const input: React.CSSProperties = {
  width: '100%', boxSizing: 'border-box', minHeight: 46, borderRadius: 10,
  border: '2px solid #D7E8DA', padding: '10px 12px', fontSize: 14, background: '#FFF',
  color: '#20352A', fontFamily: 'Nunito, system-ui, sans-serif',
}
const label: React.CSSProperties = { display: 'grid', gap: 6, fontSize: 12, fontWeight: 900, color: '#3D2B1F' }
const primary: React.CSSProperties = {
  minHeight: 44, border: 0, borderRadius: 10, padding: '0 16px', background: '#1D6B2A',
  color: '#FFF', fontWeight: 900, cursor: 'pointer', fontFamily: 'Nunito, system-ui, sans-serif',
}

async function csrfToken() {
  await fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' })
  return decodeURIComponent(document.cookie.split('; ').find(value => value.startsWith('XSRF-TOKEN='))?.split('=').slice(1).join('=') || '')
}

async function writeJson(url: string, method: string, body?: unknown) {
  const token = await csrfToken()
  const response = await fetch(url, {
    method,
    credentials: 'same-origin',
    headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-XSRF-TOKEN': token },
    body: body === undefined ? undefined : JSON.stringify(body),
  })
  const payload = await response.json().catch(() => ({}))
  if (!response.ok) {
    const firstError = Object.values(payload.errors || {}).flat()[0]
    throw new Error(String(firstError || payload.message || 'Action impossible.'))
  }
  return payload
}

const statusLabel: Record<Contribution['status'], string> = {
  draft: 'Brouillon familial',
  submitted: 'Envoyé pour revue',
  in_review: 'En cours de revue',
  approved: 'Approuvé',
  rejected: 'À corriger',
}

export default function LanguageContributions() {
  const [languages, setLanguages] = useState<Language[]>([])
  const [items, setItems] = useState<Contribution[]>([])
  const [form, setForm] = useState<FormState>(emptyForm())
  const [editingId, setEditingId] = useState<number | null>(null)
  const [busy, setBusy] = useState(false)
  const [message, setMessage] = useState('')
  const [error, setError] = useState('')

  const load = async () => {
    const response = await fetch('/api/parent/language-contributions', {
      credentials: 'same-origin', headers: { Accept: 'application/json' },
    })
    if (!response.ok) throw new Error('Chargement impossible.')
    const data = await response.json()
    setLanguages(data.languages)
    setItems(data.contributions)
    setForm(current => current.language_profile_id || data.languages.length === 0
      ? current
      : { ...current, language_profile_id: String(data.languages[0].id) })
  }

  useEffect(() => { void load().catch(e => setError(e.message)) }, [])

  const reset = () => {
    setEditingId(null)
    setForm(emptyForm(languages[0] ? String(languages[0].id) : ''))
  }

  const save = async (action: 'draft' | 'submit') => {
    setBusy(true); setMessage(''); setError('')
    try {
      const body = { ...form, language_profile_id: Number(form.language_profile_id), action }
      if (editingId) {
        await writeJson(`/api/parent/language-contributions/${editingId}`, 'PUT', body)
        if (action === 'submit') await writeJson(`/api/parent/language-contributions/${editingId}/submit`, 'POST')
      } else {
        await writeJson('/api/parent/language-contributions', 'POST', body)
      }
      setMessage(action === 'submit' ? 'Proposition envoyée pour revue.' : 'Brouillon enregistré.')
      reset()
      await load()
    } catch (e) {
      setError(e instanceof Error ? e.message : 'Action impossible.')
    } finally {
      setBusy(false)
    }
  }

  const edit = (item: Contribution) => {
    setEditingId(item.id)
    setForm({
      language_profile_id: String(item.language_profile_id),
      kind: item.kind,
      source_text: item.source_text,
      french_translation: item.french_translation,
      english_translation: item.english_translation,
      usage_context: item.usage_context,
      source_origin: item.source_origin,
      source_reference: item.source_reference || '',
      rights_confirmed: item.rights_confirmed,
    })
    setMessage(''); setError('')
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }

  const remove = async (id: number) => {
    setBusy(true); setError('')
    try {
      await writeJson(`/api/parent/language-contributions/${id}`, 'DELETE')
      if (editingId === id) reset()
      await load()
    } catch (e) {
      setError(e instanceof Error ? e.message : 'Suppression impossible.')
    } finally {
      setBusy(false)
    }
  }

  const submitDraft = async (id: number) => {
    setBusy(true); setError('')
    try {
      await writeJson(`/api/parent/language-contributions/${id}/submit`, 'POST')
      setMessage('Proposition envoyée pour revue.')
      await load()
    } catch (e) {
      setError(e instanceof Error ? e.message : 'Soumission impossible.')
    } finally {
      setBusy(false)
    }
  }

  return <div style={{ maxWidth: 760, margin: '0 auto' }}>
    <div style={{ marginBottom: 18 }}>
      <h2 style={{ margin: 0, color: '#20352A', fontSize: 22 }}>Atelier langue familiale</h2>
      <p style={{ margin: '6px 0 0', color: '#6A5848', fontSize: 13, lineHeight: 1.5 }}>
        Proposez une forme réellement utilisée dans votre famille. Elle restera privée jusqu’à sa revue et ne sera jamais publiée automatiquement.
      </p>
    </div>

    {languages.length === 0 ? <div style={{ padding: 16, background: '#FFF7E6', border: '1px solid #E8B04A', borderRadius: 10 }}>
      Ajoutez d’abord une langue familiale dans Paramètres.
    </div> : <div style={{ background: 'var(--card)', border: '1.5px solid var(--border)', borderRadius: 12, padding: 18, marginBottom: 18 }}>
      <div style={{ display: 'grid', gap: 13 }}>
        <label style={label}>Langue familiale
          <select style={input} value={form.language_profile_id} onChange={e => setForm({ ...form, language_profile_id: e.target.value })}>
            {languages.map(language => <option key={language.id} value={language.id}>
              {language.display_name}{language.catalogue_name && language.display_name !== language.catalogue_name ? ` · ${language.catalogue_name}` : ''}
            </option>)}
          </select>
        </label>
        <label style={label}>Type
          <select style={input} value={form.kind} onChange={e => setForm({ ...form, kind: e.target.value as FormState['kind'] })}>
            <option value="word">Mot</option>
            <option value="expression">Expression</option>
            <option value="short_sentence">Phrase courte</option>
          </select>
        </label>
        <label style={label}>Forme dans la langue
          <input style={input} maxLength={160} value={form.source_text} onChange={e => setForm({ ...form, source_text: e.target.value })} placeholder="Écriture exacte, tons compris" />
        </label>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit,minmax(220px,1fr))', gap: 12 }}>
          <label style={label}>Traduction française
            <input style={input} maxLength={200} value={form.french_translation} onChange={e => setForm({ ...form, french_translation: e.target.value })} />
          </label>
          <label style={label}>Traduction anglaise
            <input style={input} maxLength={200} value={form.english_translation} onChange={e => setForm({ ...form, english_translation: e.target.value })} />
          </label>
        </div>
        <label style={label}>Contexte d’usage
          <textarea style={{ ...input, minHeight: 88, resize: 'vertical' }} maxLength={1000} value={form.usage_context} onChange={e => setForm({ ...form, usage_context: e.target.value })} placeholder="Qui l’emploie, dans quelle situation et avec quel sens ?" />
        </label>
        <label style={label}>Origine de la proposition
          <select style={input} value={form.source_origin} onChange={e => setForm({ ...form, source_origin: e.target.value as FormState['source_origin'] })}>
            <option value="adult_speaker">Locuteur adulte de la famille</option>
            <option value="family_creation">Création originale de la famille</option>
            <option value="authorized_reference">Référence autorisée</option>
          </select>
        </label>
        {form.source_origin === 'authorized_reference' && <label style={label}>Référence et autorisation
          <input style={input} maxLength={500} value={form.source_reference} onChange={e => setForm({ ...form, source_reference: e.target.value })} placeholder="Titre, lien, licence ou autorisation" />
        </label>}
        <label style={{ display: 'flex', alignItems: 'flex-start', gap: 9, fontSize: 12, lineHeight: 1.45, color: '#3D2B1F', cursor: 'pointer' }}>
          <input type="checkbox" checked={form.rights_confirmed} onChange={e => setForm({ ...form, rights_confirmed: e.target.checked })} style={{ marginTop: 3 }} />
          Je confirme que cette proposition peut être examinée par EduMaison et qu’elle ne contient aucune donnée personnelle d’enfant.
        </label>
        {error && <div role="alert" style={{ color: '#B42318', fontSize: 12, fontWeight: 800 }}>{error}</div>}
        {message && <div role="status" style={{ color: '#1D6B2A', fontSize: 12, fontWeight: 800 }}>{message}</div>}
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 9 }}>
          <button type="button" disabled={busy} onClick={() => void save('draft')} style={{ ...primary, background: '#FFF', color: '#1D6B2A', border: '2px solid #1D6B2A' }}>Enregistrer le brouillon</button>
          <button type="button" disabled={busy} onClick={() => void save('submit')} style={primary}>Envoyer pour revue</button>
          {editingId && <button type="button" disabled={busy} onClick={reset} style={{ ...primary, background: '#E8DCC8', color: '#3D2B1F' }}>Annuler</button>}
        </div>
      </div>
    </div>}

    <h3 style={{ color: '#20352A', margin: '0 0 10px' }}>Mes propositions</h3>
    {items.length === 0 && <div style={{ color: '#6A5848', fontSize: 13 }}>Aucune proposition enregistrée.</div>}
    {items.map(item => <div key={item.id} style={{ background: 'var(--card)', border: '1.5px solid var(--border)', borderLeft: `5px solid ${item.status === 'draft' ? '#C47A3C' : '#1D6B2A'}`, borderRadius: 10, padding: 15, marginBottom: 10 }}>
      <div style={{ display: 'flex', gap: 10, alignItems: 'flex-start' }}>
        <div style={{ flex: 1, minWidth: 0 }}>
          <div style={{ fontSize: 16, fontWeight: 900, color: '#20352A', overflowWrap: 'anywhere' }}>{item.source_text}</div>
          <div style={{ fontSize: 12, color: '#6A5848', marginTop: 4 }}>{item.variant_name || 'Langue familiale'} · {item.french_translation} · {item.english_translation}</div>
        </div>
        <span style={{ fontSize: 10, fontWeight: 900, color: item.status === 'draft' ? '#8A5A12' : '#1D6B2A', background: item.status === 'draft' ? '#FFF2CC' : '#DDF3E1', padding: '5px 8px', borderRadius: 8, whiteSpace: 'nowrap' }}>{statusLabel[item.status]}</span>
      </div>
      {item.status === 'draft' && <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8, marginTop: 12 }}>
        <button type="button" disabled={busy} onClick={() => edit(item)} style={{ ...primary, minHeight: 38, background: '#FFF', color: '#1D6B2A', border: '1.5px solid #1D6B2A' }}>Modifier</button>
        <button type="button" disabled={busy || !item.rights_confirmed} onClick={() => void submitDraft(item.id)} style={{ ...primary, minHeight: 38 }}>Envoyer</button>
        <button type="button" disabled={busy} onClick={() => void remove(item.id)} style={{ ...primary, minHeight: 38, background: '#FFF', color: '#B42318', border: '1.5px solid #B42318' }}>Supprimer</button>
      </div>}
    </div>)}
  </div>
}
