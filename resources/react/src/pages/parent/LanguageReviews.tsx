import { useEffect, useState } from 'react'

interface WritingReview {
  id: number
  child_name: string
  exercise_title: string
  type: 'handwriting' | 'written_response'
  text: string | null
  samples: Array<{
    prompt: string
    media_url: string
    trace_feedback: { score: number; precision: number; coverage: number; status: 'good' | 'needs_help'; client_feedback_only: true } | null
    trace_summary: { stroke_count: number; point_count: number; normalized_distance: number } | null
  }>
  attempted_at: string
}

interface SpeakingReview {
  id: number
  child_name: string
  exercise_title: string
  target_text: string
  transcript: string
  transcript_score: number | null
  pronunciation_score: number | null
  parent_feedback: string | null
  audio_url: string
  attempted_at: string
}

interface ReviewData {
  settings: { speaking_audio_enabled: boolean; retention_days: number }
  pending_writing: WritingReview[]
  speaking: SpeakingReview[]
}

const writingRubricLabels: Record<string, string> = {
  relevance: 'Respect de la consigne',
  sentence_structure: 'Construction des phrases',
  spelling: 'Orthographe',
  punctuation: 'Ponctuation',
}

const handwritingRubricLabels: Record<string, string> = {
  letter_shape: 'Forme des lettres',
  line_control: 'Tenue sur la ligne',
  spacing: 'Espacement',
  legibility: 'Lisibilité',
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
    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-XSRF-TOKEN': token },
    body: body === undefined ? undefined : JSON.stringify(body),
  })
  const payload = await response.json().catch(() => ({}))
  if (!response.ok) throw new Error(payload.message || 'Action impossible.')
  return payload
}

/** Mama Judi dit le prénom des enfants : accord explicite du parent, retrait = effacement. */
function NameVoiceSetting() {
  const [state, setState] = useState<{ enabled: boolean; available: boolean } | null>(null)
  const [busy, setBusy] = useState(false)
  const [error, setError] = useState('')

  useEffect(() => {
    fetch('/api/parent/name-voice/settings', { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(response => (response.ok ? response.json() : null))
      .then(setState)
      .catch(() => setState(null))
  }, [])

  const toggle = async (enabled: boolean) => {
    if (enabled && !window.confirm([
      'Mama Judi dira le prénom de vos enfants (« Bravo Ama ! »).',
      'Pour fabriquer cette voix, le prénom est envoyé au service de synthèse vocale ElevenLabs. '
        + 'Les enregistrements restent privés à votre famille et sont effacés si vous décochez cette case.',
      'Accepter ?',
    ].join('\n\n'))) return
    setBusy(true)
    setError('')
    try {
      setState(await writeJson('/api/parent/name-voice/settings', 'PUT', { enabled }))
    } catch (reason) {
      setError(reason instanceof Error ? reason.message : 'Action impossible.')
    } finally {
      setBusy(false)
    }
  }

  if (!state) return null
  return (
    <div style={{ marginTop: 14 }}>
      <label style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', gap: 12, fontWeight: 800 }}>
        <span>Mama Judi dit le prénom de mes enfants</span>
        <input type="checkbox" checked={state.enabled} disabled={busy || (!state.available && !state.enabled)} onChange={event => toggle(event.target.checked)} style={{ width: 22, height: 22 }} />
      </label>
      <div style={{ marginTop: 4, fontSize: 12, color: 'var(--text-soft)' }}>
        {!state.available && !state.enabled
          ? 'Bientôt disponible.'
          : state.enabled
            ? 'Activé : la voix est préparée en quelques minutes. Décocher efface les enregistrements.'
            : 'Le prénom est envoyé à ElevenLabs pour fabriquer la voix ; les enregistrements restent privés.'}
      </div>
      {error && <div role="alert" style={{ marginTop: 6, fontSize: 13, color: '#B42318' }}>{error}</div>}
    </div>
  )
}

function WritingItem({ item, onReviewed }: { item: WritingReview; onReviewed: () => void }) {
  const rubricLabels = item.type === 'handwriting' ? handwritingRubricLabels : writingRubricLabels
  const [rubric, setRubric] = useState<Record<string, number>>(() => Object.fromEntries(Object.keys(rubricLabels).map(key => [key, 2])))
  const [feedback, setFeedback] = useState('')
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState('')
  const score = Math.round(Object.values(rubric).reduce((sum, value) => sum + value, 0) / (Object.keys(rubric).length * 4) * 100)

  const submit = async () => {
    setSaving(true)
    setError('')
    try {
      await writeJson(`/api/parent/language-reviews/${item.id}`, 'POST', { score, feedback, rubric })
      onReviewed()
    } catch (reason) {
      setError(reason instanceof Error ? reason.message : 'Validation impossible.')
    } finally { setSaving(false) }
  }

  return (
    <article style={{ background: 'var(--card)', border: '1.5px solid var(--border)', borderLeft: '5px solid #1D6B2A', borderRadius: 8, padding: 16, marginBottom: 14 }}>
      <div style={{ display: 'flex', justifyContent: 'space-between', gap: 10, marginBottom: 12 }}>
        <div><strong style={{ color: 'var(--text-dark)' }}>{item.child_name}</strong><div style={{ color: 'var(--text-soft)', fontSize: 12 }}>{item.exercise_title}</div></div>
        <time style={{ color: 'var(--text-soft)', fontSize: 11 }}>{new Date(item.attempted_at).toLocaleDateString()}</time>
      </div>
      {item.text && <div style={{ background: '#FFFDF8', borderLeft: '3px solid #C47A3C', padding: 14, lineHeight: 1.65, whiteSpace: 'pre-wrap', marginBottom: 14 }}>{item.text}</div>}
      {item.samples.map((sample, index) => (
        <figure key={sample.media_url} style={{ margin: '0 0 14px' }}>
          <figcaption style={{ fontWeight: 800, marginBottom: 6 }}>{sample.prompt}</figcaption>
          <img src={sample.media_url} alt={`Production manuscrite ${index + 1}`} style={{ display: 'block', width: '100%', maxHeight: 260, objectFit: 'contain', background: '#FFFDF8', border: '1px solid var(--border)' }} />
          {sample.trace_feedback && (
            <div style={{ marginTop: 6, padding: 9, border: '1px solid #D7CBB8', borderRadius: 6, background: '#FFF9EE', color: '#6C5142', fontSize: 11 }}>
              <strong>Indication du guide, à confirmer visuellement</strong>
              <div style={{ marginTop: 3 }}>Proximité {sample.trace_feedback.precision}% · Modèle couvert {sample.trace_feedback.coverage}% · {sample.trace_feedback.status === 'good' ? 'tracé guidé terminé' : 'aide demandée'}</div>
            </div>
          )}
        </figure>
      ))}
      <div style={{ display: 'grid', gap: 10 }}>
        {Object.entries(rubric).map(([key, value]) => (
          <label key={key} style={{ display: 'grid', gridTemplateColumns: 'minmax(140px,1fr) 2fr 36px', alignItems: 'center', gap: 8, fontSize: 12, fontWeight: 700 }}>
            <span>{rubricLabels[key]}</span>
            <input type="range" min="0" max="4" step="1" value={value} onChange={event => setRubric(current => ({ ...current, [key]: Number(event.target.value) }))} />
            <strong>{value}/4</strong>
          </label>
        ))}
      </div>
      <label style={{ display: 'block', marginTop: 14, fontWeight: 800, fontSize: 12 }}>Commentaire</label>
      <textarea value={feedback} onChange={event => setFeedback(event.target.value)} rows={3} maxLength={1000} style={{ boxSizing: 'border-box', width: '100%', marginTop: 6, padding: 10, border: '1.5px solid var(--border)', borderRadius: 6, background: '#FFFDF8', font: 'inherit' }} />
      <button onClick={submit} disabled={saving} style={{ width: '100%', minHeight: 46, marginTop: 12, border: 0, borderRadius: 8, background: '#1D6B2A', color: 'white', fontWeight: 900, cursor: saving ? 'default' : 'pointer' }}>
        {saving ? 'Enregistrement...' : `Valider la production · ${score}%`}
      </button>
      {error && <div role="alert" style={{ marginTop: 8, color: '#B42318', fontSize: 12, fontWeight: 800 }}>{error}</div>}
    </article>
  )
}

function SpeakingItem({ item, onChanged }: { item: SpeakingReview; onChanged: () => void }) {
  const [score, setScore] = useState(item.pronunciation_score ?? 50)
  const [feedback, setFeedback] = useState(item.parent_feedback ?? '')
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState('')

  const submit = async () => {
    setSaving(true)
    setError('')
    try {
      await writeJson(`/api/parent/pronunciation-reviews/${item.id}`, 'POST', { score, feedback })
      onChanged()
    } catch (reason) {
      setError(reason instanceof Error ? reason.message : 'Validation impossible.')
    } finally { setSaving(false) }
  }

  const remove = async () => {
    if (!window.confirm('Supprimer définitivement cet enregistrement vocal ?')) return
    setSaving(true)
    setError('')
    try {
      await writeJson(`/api/parent/pronunciation-reviews/${item.id}/audio`, 'DELETE')
      onChanged()
    } catch (reason) {
      setError(reason instanceof Error ? reason.message : 'Suppression impossible.')
    } finally { setSaving(false) }
  }

  return (
    <article style={{ background: 'var(--card)', border: '1.5px solid var(--border)', borderLeft: '5px solid #C47A3C', borderRadius: 8, padding: 16, marginBottom: 14 }}>
      <div style={{ display: 'flex', justifyContent: 'space-between', gap: 10 }}><strong>{item.child_name}</strong><time style={{ color: 'var(--text-soft)', fontSize: 11 }}>{new Date(item.attempted_at).toLocaleDateString()}</time></div>
      <div style={{ color: 'var(--text-soft)', fontSize: 12, marginBottom: 10 }}>{item.exercise_title}</div>
      <div style={{ marginBottom: 5 }}><strong>À prononcer :</strong> {item.target_text}</div>
      <div style={{ marginBottom: 10, color: '#6C5142' }}><strong>Reconnu :</strong> {item.transcript || 'Aucun texte'} {item.transcript_score !== null && `(${item.transcript_score}%)`}</div>
      <audio controls preload="none" src={item.audio_url} style={{ width: '100%', height: 42 }} />
      <label style={{ display: 'grid', gridTemplateColumns: '130px 1fr 48px', gap: 8, alignItems: 'center', marginTop: 12, fontSize: 12, fontWeight: 800 }}>
        <span>Prononciation</span><input type="range" min="0" max="100" step="5" value={score} onChange={event => setScore(Number(event.target.value))} /><strong>{score}%</strong>
      </label>
      <textarea value={feedback} onChange={event => setFeedback(event.target.value)} rows={2} maxLength={1000} placeholder="Commentaire facultatif" style={{ boxSizing: 'border-box', width: '100%', marginTop: 10, padding: 10, border: '1.5px solid var(--border)', borderRadius: 6, background: '#FFFDF8', font: 'inherit' }} />
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 44px', gap: 8, marginTop: 10 }}>
        <button onClick={submit} disabled={saving} style={{ minHeight: 44, border: 0, borderRadius: 8, background: '#1D6B2A', color: 'white', fontWeight: 900, cursor: saving ? 'default' : 'pointer' }}>Valider l’écoute</button>
        <button onClick={remove} disabled={saving} title="Supprimer l’enregistrement" aria-label="Supprimer l’enregistrement" style={{ minHeight: 44, border: '1px solid #E3A4A4', borderRadius: 8, background: '#FFF5F5', color: '#B42318', fontSize: 20, cursor: saving ? 'default' : 'pointer' }}>×</button>
      </div>
      {error && <div role="alert" style={{ marginTop: 8, color: '#B42318', fontSize: 12, fontWeight: 800 }}>{error}</div>}
    </article>
  )
}

export default function LanguageReviews() {
  const [data, setData] = useState<ReviewData | null>(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [section, setSection] = useState<'writing' | 'speaking'>('writing')

  const load = async () => {
    setError('')
    const response = await fetch('/api/parent/language-reviews', { credentials: 'same-origin', headers: { Accept: 'application/json' } })
    if (!response.ok) throw new Error('Impossible de charger les productions.')
    setData(await response.json())
  }

  useEffect(() => { load().catch(error => setError(error.message)).finally(() => setLoading(false)) }, [])

  const updateSettings = async (enabled: boolean) => {
    if (!data) return
    const deleteExisting = !enabled && data.speaking.length > 0 && window.confirm('Supprimer aussi tous les enregistrements déjà conservés ?')
    const settings = await writeJson('/api/parent/language-reviews/settings', 'PUT', {
      speaking_audio_enabled: enabled,
      retention_days: data.settings.retention_days,
      delete_existing_audio: deleteExisting,
    })
    setData(current => current ? { ...current, settings, ...(deleteExisting ? { speaking: [] } : {}) } : current)
  }

  const updateRetention = async (days: number) => {
    if (!data) return
    const settings = await writeJson('/api/parent/language-reviews/settings', 'PUT', {
      speaking_audio_enabled: data.settings.speaking_audio_enabled,
      retention_days: days,
    })
    setData(current => current ? { ...current, settings } : current)
  }

  if (loading) return <div style={{ padding: 30, textAlign: 'center' }}>Chargement...</div>
  if (error) return <div role="alert" style={{ padding: 18, color: '#B42318' }}>{error}</div>
  if (!data) return null

  const rows = section === 'writing' ? data.pending_writing : data.speaking
  return (
    <div>
      <section style={{ padding: '2px 0 18px', borderBottom: '1px solid var(--border)', marginBottom: 18 }}>
        <h2 style={{ margin: '0 0 8px', fontSize: 19, color: 'var(--text-dark)' }}>Validation des apprentissages</h2>
        <label style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', gap: 12, fontWeight: 800 }}>
          <span>Conserver l’audio Speaking</span>
          <input type="checkbox" checked={data.settings.speaking_audio_enabled} onChange={event => updateSettings(event.target.checked).catch(error => setError(error.message))} style={{ width: 22, height: 22 }} />
        </label>
        {data.settings.speaking_audio_enabled && (
          <label style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', gap: 12, marginTop: 12, fontSize: 13 }}>
            <span>Suppression automatique</span>
            <select value={data.settings.retention_days} onChange={event => updateRetention(Number(event.target.value)).catch(error => setError(error.message))} style={{ padding: '8px 10px', border: '1px solid var(--border)', borderRadius: 6, background: 'var(--card)' }}>
              {[7, 14, 30, 60, 90].map(days => <option key={days} value={days}>{days} jours</option>)}
            </select>
          </label>
        )}
        <NameVoiceSetting />
      </section>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 8, marginBottom: 18 }}>
        <button onClick={() => setSection('writing')} style={{ minHeight: 44, border: section === 'writing' ? '2px solid #1D6B2A' : '1px solid var(--border)', borderRadius: 8, background: section === 'writing' ? '#E7F3E8' : 'var(--card)', color: '#2D1B0E', fontWeight: 900 }}>Écritures ({data.pending_writing.length})</button>
        <button onClick={() => setSection('speaking')} style={{ minHeight: 44, border: section === 'speaking' ? '2px solid #C47A3C' : '1px solid var(--border)', borderRadius: 8, background: section === 'speaking' ? '#F8EBDD' : 'var(--card)', color: '#2D1B0E', fontWeight: 900 }}>Speaking ({data.speaking.length})</button>
      </div>
      {rows.length === 0 && <div style={{ padding: 30, textAlign: 'center', color: 'var(--text-soft)' }}>Aucune production en attente.</div>}
      {section === 'writing' && data.pending_writing.map(item => <WritingItem key={item.id} item={item} onReviewed={() => load().catch(error => setError(error.message))} />)}
      {section === 'speaking' && data.speaking.map(item => <SpeakingItem key={item.id} item={item} onChanged={() => load().catch(error => setError(error.message))} />)}
    </div>
  )
}
