import { FormEvent, useEffect, useState } from 'react'

const input: React.CSSProperties = { width: '100%', boxSizing: 'border-box', minHeight: 48, borderRadius: 13, border: '2px solid #D7E8DA', padding: '0 13px', fontSize: 14, background: '#FFF' }
const button: React.CSSProperties = { minHeight: 46, border: 0, borderRadius: 13, padding: '0 18px', background: '#1D6B2A', color: 'white', fontWeight: 900, cursor: 'pointer' }
const card: React.CSSProperties = { background: 'var(--card)', borderRadius: 18, padding: 18, marginBottom: 16, border: '1.5px solid var(--border)' }

const languageStatus: Record<string, string> = {
  not_configured: 'Langue non configurée',
  awaiting_catalogue: 'Catalogue en attente',
  awaiting_pack: 'Contenu en préparation',
  ready: 'Activités vérifiées et prêtes',
}
const maxCustomLanguages = 4

function token() {
  return decodeURIComponent(document.cookie.split('; ').find(v => v.startsWith('XSRF-TOKEN='))?.split('=').slice(1).join('=') || '')
}
async function csrf() {
  await fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' })
}
const toggle = (values: string[], value: string) =>
  values.includes(value) ? values.filter(item => item !== value) : [...values, value]

export default function FamilySettings() {
  const [data, setData] = useState<any>(null)
  const [levels, setLevels] = useState<any[]>([])
  const [message, setMessage] = useState('')
  const [photo, setPhoto] = useState<File | null>(null)
  const [family, setFamily] = useState({
    parent_name: '', family_name: '', city: '', school: '',
    national_language_ids: [] as string[], national_language_other_names: [] as string[],
    national_language_variants: {} as Record<string, string>,
  })
  const [pin, setPin] = useState({ password: '', pin: '', pin_confirmation: '' })
  const [editing, setEditing] = useState<any>(null)
  const [childPhoto, setChildPhoto] = useState<File | null>(null)

  const load = () => fetch('/api/family/settings', {
    credentials: 'same-origin',
    headers: { Accept: 'application/json' },
  }).then(r => r.json()).then(d => {
    setData(d)
    setFamily({
      parent_name: d.parent.name || '',
      family_name: d.family.name || '',
      city: d.family.city || '',
      school: d.family.school || '',
      national_language_ids: d.family_languages
        .map((language: any) => language.language_id ? String(language.language_id) : null)
        .filter(Boolean),
      national_language_other_names: d.family_languages
        .filter((language: any) => !language.language_id)
        .map((language: any) => language.custom_name || '')
        .filter(Boolean),
      national_language_variants: Object.fromEntries(
        d.family_languages
          .filter((language: any) => language.language_id)
          .map((language: any) => [String(language.language_id), language.variant_name || ''])
      ),
    })
  })

  useEffect(() => {
    void load()
    fetch('/api/family-auth/levels', { credentials: 'same-origin' }).then(r => r.json()).then(setLevels)
  }, [])

  const saveFamily = async (e: FormEvent) => {
    e.preventDefault()
    setMessage('')
    await csrf()
    const body = new FormData()
    ;(['parent_name', 'family_name', 'city', 'school'] as const).forEach(key => body.append(key, family[key]))
    body.append('national_language_ids_present', '1')
    family.national_language_ids.forEach(id => body.append('national_language_ids[]', id))
    family.national_language_ids.forEach(id => {
      const variant = family.national_language_variants[id]?.trim()
      if (variant) body.append(`national_language_variants[${id}]`, variant)
    })
    family.national_language_other_names
      .map(name => name.trim())
      .filter(Boolean)
      .forEach(name => body.append('national_language_other_names[]', name))
    if (photo) body.append('parent_photo', photo)
    const response = await fetch('/api/family/settings', {
      method: 'POST', credentials: 'same-origin',
      headers: { Accept: 'application/json', 'X-XSRF-TOKEN': token() },
      body,
    })
    setMessage(response.ok ? 'Famille mise à jour.' : 'La mise à jour a échoué.')
    if (response.ok) {
      setPhoto(null)
      await load()
    }
  }

  const savePin = async (e: FormEvent) => {
    e.preventDefault()
    setMessage('')
    await csrf()
    const response = await fetch('/api/family/settings/pin', {
      method: 'POST', credentials: 'same-origin',
      headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-XSRF-TOKEN': token() },
      body: JSON.stringify(pin),
    })
    setMessage(response.ok ? 'PIN familial modifié.' : 'Mot de passe ou PIN incorrect.')
    if (response.ok) setPin({ password: '', pin: '', pin_confirmation: '' })
  }

  const saveChild = async (e: FormEvent) => {
    e.preventDefault()
    await csrf()
    const body = new FormData()
    ;['first_name', 'last_name', 'birth_date', 'level_id', 'pin'].forEach(key => body.append(key, editing[key] || ''))
    editing.national_language_profile_ids.forEach((id: string) => body.append('national_language_profile_ids[]', id))
    if (editing.current_national_language_profile_id) {
      body.append('current_national_language_profile_id', editing.current_national_language_profile_id)
    }
    if (childPhoto) body.append('avatar', childPhoto)
    const response = await fetch(`/api/children/${editing.id}/settings`, {
      method: 'POST', credentials: 'same-origin',
      headers: { Accept: 'application/json', 'X-XSRF-TOKEN': token() },
      body,
    })
    setMessage(response.ok ? 'Profil enfant mis à jour.' : 'Choisis au moins une langue et vérifie les informations.')
    if (response.ok) {
      setEditing(null)
      setChildPhoto(null)
      await load()
    }
  }

  const deactivate = async () => {
    const password = window.prompt('Confirme avec le mot de passe parent :')
    if (!password) return
    await csrf()
    const response = await fetch(`/api/children/${editing.id}`, {
      method: 'DELETE', credentials: 'same-origin',
      headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-XSRF-TOKEN': token() },
      body: JSON.stringify({ password }),
    })
    setMessage(response.ok ? 'Profil enfant désactivé; son historique est conservé.' : 'Mot de passe incorrect.')
    if (response.ok) {
      setEditing(null)
      await load()
    }
  }

  if (!data) return <div>Chargement…</div>
  const avatar = data.companion.avatar ? '/storage/' + data.companion.avatar : '/images/default-companion.webp'

  return <div>
    {message && <div style={{ ...card, color: '#1D6B2A', fontWeight: 900 }}>{message}</div>}
    <form onSubmit={saveFamily} style={card}>
      <h3 style={{ marginTop: 0 }}>Parent et famille</h3>
      <img src={photo ? URL.createObjectURL(photo) : avatar} alt="Parent" style={{ width: 82, height: 82, borderRadius: '50%', objectFit: 'cover' }} />
      <label style={{ display: 'block', margin: '8px 0 12px', color: '#1D6B2A', fontWeight: 800, cursor: 'pointer' }}>
        Changer la photo
        <input hidden type="file" accept="image/*" onChange={e => setPhoto(e.target.files?.[0] || null)} />
      </label>
      <div style={{ display: 'grid', gap: 10 }}>
        <input style={input} placeholder="Nom du parent" value={family.parent_name} onChange={e => setFamily({ ...family, parent_name: e.target.value })} required />
        <input style={input} placeholder="Nom de la famille" value={family.family_name} onChange={e => setFamily({ ...family, family_name: e.target.value })} required />
        <input style={input} placeholder="Ville (facultatif)" value={family.city} onChange={e => setFamily({ ...family, city: e.target.value })} />
        <input style={input} placeholder="École (facultatif)" value={family.school} onChange={e => setFamily({ ...family, school: e.target.value })} />
        <fieldset style={{ border: '1.5px solid #D7E8DA', borderRadius: 14, padding: 12 }}>
          <legend style={{ fontSize: 13, fontWeight: 900, color: '#20352A' }}>Langues de la famille</legend>
          <div style={{ fontSize: 12, color: '#6A5848', marginBottom: 10 }}>Sélectionne toutes les langues que les enfants pourront apprendre.</div>
          <div style={{ display: 'grid', gap: 8 }}>
            {data.national_languages.map((language: any) => {
              const id = String(language.id)
              const checked = family.national_language_ids.includes(id)
              return <div key={language.id}>
                <label style={{ display: 'flex', alignItems: 'center', gap: 10, minHeight: 42, cursor: 'pointer' }}>
                  <input type="checkbox" checked={checked} onChange={() => setFamily({ ...family, national_language_ids: toggle(family.national_language_ids, id) })} />
                  <span><strong>{language.name}</strong>{language.autonym ? ` · ${language.autonym}` : ''}</span>
                </label>
                {checked && <input style={{ ...input, minHeight: 40, marginBottom: 6 }} placeholder="Nom local ou variante (facultatif)" value={family.national_language_variants[id] || ''} onChange={e => setFamily({
                  ...family,
                  national_language_variants: { ...family.national_language_variants, [id]: e.target.value },
                })} />}
              </div>
            })}
          </div>
          <div style={{ display: 'grid', gap: 8, marginTop: 10 }}>
            {family.national_language_other_names.map((name, index) => <div key={index} style={{ display: 'grid', gridTemplateColumns: 'minmax(0,1fr) 42px', gap: 8 }}>
              <input
                style={input}
                aria-label={`Langue locale ${index + 1}`}
                placeholder="Nom de la langue ou du dialecte"
                value={name}
                onChange={e => {
                  const next = [...family.national_language_other_names]
                  next[index] = e.target.value
                  setFamily({ ...family, national_language_other_names: next })
                }}
              />
              <button
                type="button"
                title="Retirer cette langue"
                aria-label={`Retirer la langue locale ${index + 1}`}
                onClick={() => setFamily({
                  ...family,
                  national_language_other_names: family.national_language_other_names.filter((_, itemIndex) => itemIndex !== index),
                })}
                style={{ width: 42, height: 42, alignSelf: 'center', border: '1.5px solid #D7E8DA', borderRadius: 10, background: '#FFF', color: '#8A2D24', fontSize: 22, fontWeight: 900, cursor: 'pointer' }}
              >×</button>
            </div>)}
            {family.national_language_other_names.length < maxCustomLanguages && <button
              type="button"
              onClick={() => setFamily({
                ...family,
                national_language_other_names: [...family.national_language_other_names, ''],
              })}
              style={{ ...button, minHeight: 42, background: '#FFF', color: '#1D6B2A', border: '1.5px solid #1D6B2A' }}
            >+ Ajouter une langue locale</button>}
          </div>
        </fieldset>
        <div style={{ fontSize: 12, color: '#6A5848', lineHeight: 1.45 }}>Un pack n’est proposé qu’après validation de son contenu par un locuteur compétent.</div>
        <button style={button}>Enregistrer</button>
      </div>
    </form>

    <form onSubmit={savePin} style={card}>
      <h3 style={{ marginTop: 0 }}>PIN familial</h3>
      <div style={{ display: 'grid', gap: 10 }}>
        <input style={input} type="password" placeholder="Mot de passe parent" value={pin.password} onChange={e => setPin({ ...pin, password: e.target.value })} required />
        <input style={input} inputMode="numeric" maxLength={4} placeholder="Nouveau PIN" value={pin.pin} onChange={e => setPin({ ...pin, pin: e.target.value.replace(/\D/g, '').slice(0, 4) })} required />
        <input style={input} inputMode="numeric" maxLength={4} placeholder="Confirmer le PIN" value={pin.pin_confirmation} onChange={e => setPin({ ...pin, pin_confirmation: e.target.value.replace(/\D/g, '').slice(0, 4) })} required />
        <button style={button}>Changer le PIN</button>
      </div>
    </form>

    <div style={card}>
      <h3 style={{ marginTop: 0 }}>Enfants</h3>
      {data.children.map((child: any) => <button key={child.id} onClick={() => setEditing({
        ...child,
        pin: '',
        national_language_profile_ids: child.enabled_national_language_profile_ids.map(String),
        current_national_language_profile_id: child.national_language_profile.language_profile_id
          ? String(child.national_language_profile.language_profile_id)
          : '',
      })} style={{ ...button, width: '100%', marginBottom: 8, background: '#FFF', color: '#20352A', border: '2px solid #D7E8DA', textAlign: 'left' }}>
        <span style={{ display: 'block' }}>{child.first_name} {child.last_name} — Modifier</span>
        <span style={{ display: 'block', fontSize: 11, fontWeight: 700, color: '#6A5848', marginTop: 3 }}>
          {child.national_language_profile.languages.map((language: any) => language.display_name).join(' + ') || 'Langue non configurée'}
          {' · '}{languageStatus[child.national_language_profile.status]}
        </span>
      </button>)}
    </div>

    {editing && <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,.55)', display: 'grid', placeItems: 'center', zIndex: 1000, padding: 18 }}>
      <form onSubmit={saveChild} style={{ ...card, width: 'min(100%,440px)', maxHeight: '90vh', overflowY: 'auto', boxSizing: 'border-box' }}>
        <h3>Modifier {editing.first_name}</h3>
        <img src={childPhoto ? URL.createObjectURL(childPhoto) : (editing.avatar ? '/storage/' + editing.avatar : '/images/default-child.webp')} alt="" style={{ width: 82, height: 82, borderRadius: '50%', objectFit: 'cover' }} />
        <label style={{ display: 'block', margin: '8px 0', fontWeight: 800, cursor: 'pointer' }}>
          Changer sa photo
          <input hidden type="file" accept="image/*" onChange={e => setChildPhoto(e.target.files?.[0] || null)} />
        </label>
        <div style={{ display: 'grid', gap: 10 }}>
          <input style={input} value={editing.first_name} onChange={e => setEditing({ ...editing, first_name: e.target.value })} required />
          <input style={input} value={editing.last_name || ''} onChange={e => setEditing({ ...editing, last_name: e.target.value })} />
          <input style={input} type="date" value={String(editing.birth_date).slice(0, 10)} onChange={e => setEditing({ ...editing, birth_date: e.target.value })} required />
          <select style={input} value={editing.level_id} onChange={e => setEditing({ ...editing, level_id: e.target.value })}>
            {levels.map(level => <option key={level.id} value={level.id}>{level.name}</option>)}
          </select>
          <fieldset style={{ border: '1.5px solid #D7E8DA', borderRadius: 14, padding: 12 }}>
            <legend style={{ fontSize: 13, fontWeight: 900 }}>Langues accessibles</legend>
            {data.family_languages.length === 0 && <div style={{ color: '#8A5A12', fontSize: 12 }}>Enregistre d’abord les langues de la famille.</div>}
            {data.family_languages.map((language: any) => {
              const id = String(language.language_profile_id)
              const checked = editing.national_language_profile_ids.includes(id)
              return <div key={id} style={{ display: 'grid', gridTemplateColumns: '1fr auto', alignItems: 'center', gap: 8, minHeight: 44 }}>
                <label style={{ display: 'flex', alignItems: 'center', gap: 9, cursor: 'pointer' }}>
                  <input type="checkbox" checked={checked} onChange={() => {
                    const next = toggle(editing.national_language_profile_ids, id)
                    const current = next.includes(editing.current_national_language_profile_id)
                      ? editing.current_national_language_profile_id
                      : (next[0] || '')
                    setEditing({ ...editing, national_language_profile_ids: next, current_national_language_profile_id: current })
                  }} />
                  <strong>{language.display_name}</strong>
                </label>
                {checked && <label style={{ fontSize: 11, fontWeight: 800 }}>
                  <input type="radio" name="current_language" checked={editing.current_national_language_profile_id === id} onChange={() => setEditing({ ...editing, current_national_language_profile_id: id })} /> Départ
                </label>}
              </div>
            })}
          </fieldset>
          <input style={input} inputMode="numeric" maxLength={4} placeholder="Nouveau PIN (facultatif)" value={editing.pin} onChange={e => setEditing({ ...editing, pin: e.target.value.replace(/\D/g, '').slice(0, 4) })} />
          <button style={button} disabled={editing.national_language_profile_ids.length === 0}>Enregistrer</button>
          <button type="button" onClick={() => setEditing(null)} style={{ ...button, background: '#E8DCC8', color: '#3D2B1F' }}>Annuler</button>
          <button type="button" onClick={deactivate} style={{ ...button, background: '#B42318' }}>Désactiver ce profil</button>
        </div>
      </form>
    </div>}
  </div>
}
