import { FormEvent, ReactNode, useEffect, useRef, useState } from 'react'
import MamaJudiPose from './MamaJudiPose'
import '../styles/adventure.css'

type AccessGateProps = { children: ReactNode }
type Screen = 'checking' | 'welcome' | 'legacy' | 'login' | 'register' | 'child' | 'open'

function cookie(name: string) {
  return decodeURIComponent(document.cookie.split('; ').find(value => value.startsWith(`${name}=`))?.split('=').slice(1).join('=') || '')
}

async function csrfFetch(url: string, options: RequestInit = {}) {
  await fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' })
  return fetch(url, {
    ...options,
    credentials: 'same-origin',
    headers: {
      Accept: 'application/json',
      ...(options.body instanceof FormData ? {} : { 'Content-Type': 'application/json' }),
      'X-XSRF-TOKEN': cookie('XSRF-TOKEN'),
      ...(options.headers || {}),
    },
  })
}

export default function AccessGate({ children }: AccessGateProps) {
  const [screen, setScreen] = useState<Screen>('checking')
  const [pin, setPin] = useState('')
  const [message, setMessage] = useState('')
  const [submitting, setSubmitting] = useState(false)
  const [form, setForm] = useState({ name: '', family_name: '', email: '', password: '', password_confirmation: '', family_pin: '', family_pin_confirmation: '' })
  const [childForm, setChildForm] = useState({ first_name: '', last_name: '', birth_date: '', level_id: '', pin: '' })
  const [parentPhoto, setParentPhoto] = useState<File | null>(null)
  const [childPhoto, setChildPhoto] = useState<File | null>(null)
  const [levels, setLevels] = useState<Array<{ id: number, name: string }>>([])
  const inputRef = useRef<HTMLInputElement>(null)

  useEffect(() => {
    Promise.all([
      fetch('/api/family-auth/me', { credentials: 'same-origin' }),
      fetch('/api/access/status', { credentials: 'same-origin' }),
    ]).then(async ([account, legacy]) => {
      if (account.ok) return setScreen('open')
      const access = legacy.ok ? await legacy.json() : { unlocked: false }
      setScreen(access.unlocked ? 'open' : 'welcome')
    }).catch(() => {
      setMessage('Connexion impossible. Vérifie le Wi-Fi puis réessaie.')
      setScreen('welcome')
    })
  }, [])

  useEffect(() => {
    if (!('serviceWorker' in navigator)) return
    const handler = (event: MessageEvent) => {
      if (event.data?.type === 'ACCESS_REQUIRED') {
        setMessage('La session a expiré. Reconnecte ta famille.')
        setScreen('welcome')
      }
    }
    navigator.serviceWorker.addEventListener('message', handler)
    return () => navigator.serviceWorker.removeEventListener('message', handler)
  }, [])

  useEffect(() => { if (screen === 'legacy') inputRef.current?.focus() }, [screen])

  useEffect(() => {
    if (screen !== 'child') return
    fetch('/api/family-auth/levels', { credentials: 'same-origin' })
      .then(r => r.ok ? r.json() : [])
      .then(setLevels)
      .catch(() => setMessage('Impossible de charger les classes.'))
  }, [screen])

  async function submitPin(event: FormEvent) {
    event.preventDefault()
    if (pin.length !== 4 || submitting) return
    setSubmitting(true); setMessage('')
    try {
      const response = await fetch('/api/access/unlock', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify({ pin }),
      })
      const data = await response.json().catch(() => ({}))
      if (!response.ok) {
        setPin('')
        setMessage(response.status === 429 ? 'Trop d’essais. Attends une minute.' : (data.message || 'Code incorrect.'))
        return
      }
      setScreen('open')
    } catch { setMessage('Connexion impossible. Vérifie le Wi-Fi.') }
    finally { setSubmitting(false) }
  }

  async function submitChild(event: FormEvent) {
    event.preventDefault()
    if (submitting) return
    setSubmitting(true); setMessage('')
    try {
      const body = new FormData()
      Object.entries(childForm).forEach(([key, value]) => body.append(key, value))
      if (childPhoto) body.append('avatar', childPhoto)
      const response = await csrfFetch('/api/family-auth/children', { method: 'POST', body })
      const data = await response.json().catch(() => ({}))
      if (!response.ok) {
        const firstError = Object.values(data.errors || {}).flat()[0]
        setMessage(String(firstError || data.message || 'Impossible d’ajouter cet enfant.'))
        return
      }
      setScreen('open')
    } catch { setMessage('Connexion impossible. Vérifie le Wi-Fi.') }
    finally { setSubmitting(false) }
  }

  async function submitAccount(event: FormEvent) {
    event.preventDefault()
    if (submitting) return
    setSubmitting(true); setMessage('')
    const registering = screen === 'register'
    try {
      let body: BodyInit
      if (registering) {
        const multipart = new FormData()
        Object.entries(form).forEach(([key, value]) => multipart.append(key, value))
        if (parentPhoto) multipart.append('parent_photo', parentPhoto)
        body = multipart
      } else body = JSON.stringify({ email: form.email, password: form.password })
      const response = await csrfFetch(`/api/family-auth/${registering ? 'register' : 'login'}`, { method: 'POST', body })
      const data = await response.json().catch(() => ({}))
      if (!response.ok) {
        const firstError = Object.values(data.errors || {}).flat()[0]
        setMessage(String(firstError || data.message || 'Impossible de continuer.'))
        return
      }
      setScreen(registering ? 'child' : 'open')
    } catch { setMessage('Connexion impossible. Vérifie le Wi-Fi.') }
    finally { setSubmitting(false) }
  }

  if (screen === 'open') return <>{children}</>

  const accountScreen = screen === 'login' || screen === 'register'
  return (
    <div className={`adventure-entry adventure-entry--${screen}`} style={styles.page}>
      <div className="adventure-entry__backdrop" style={styles.backdrop} aria-hidden="true" />
      <section className="adventure-entry__panel" role="dialog" aria-modal="true" aria-labelledby="access-title" style={styles.modal}>
        <MamaJudiPose pose={screen === 'checking' ? 'encourage' : 'explain'} className="adventure-entry__judi" decorative />
        <p className="adventure-entry__brand" style={styles.brand}>EDUMAISON</p>
        <h1 className="adventure-entry__title" id="access-title" style={styles.title}>
          {screen === 'checking' ? 'Un petit instant…' : screen === 'register' ? 'Créer ma famille' : screen === 'login' ? 'Connexion parent' : screen === 'child' ? 'Ajouter un enfant' : screen === 'legacy' ? 'Code de la tablette' : 'Bienvenue chez vous'}
        </h1>

        {screen === 'checking' && <div className="adventure-entry__loader" style={styles.loader} aria-label="Vérification en cours" />}

        {screen === 'welcome' && <div style={styles.form}>
          <p style={styles.prompt}>Chaque famille possède désormais son espace privé.</p>
          <button style={styles.button} onClick={() => { setMessage(''); setScreen('register') }}>Créer ma famille</button>
          <button style={styles.secondary} onClick={() => { setMessage(''); setScreen('login') }}>J’ai déjà un compte</button>
          <button style={styles.link} onClick={() => { setMessage(''); setScreen('legacy') }}>Ouvrir ma famille existante</button>
        </div>}

        {screen === 'legacy' && <form onSubmit={submitPin} style={styles.form}>
          <p style={styles.prompt}>Entrez le code de votre famille pour retrouver les profils déjà présents sur cette tablette.</p>
          <input ref={inputRef} value={pin} onChange={e => setPin(e.target.value.replace(/\D/g, '').slice(0, 4))} inputMode="numeric" pattern="[0-9]*" autoComplete="one-time-code" maxLength={4} aria-label="Code familial à 4 chiffres" style={styles.pin} placeholder="••••" />
          <div aria-live="polite" style={styles.message}>{message || ' '}</div>
          <button disabled={pin.length !== 4 || submitting} style={{ ...styles.button, opacity: pin.length === 4 && !submitting ? 1 : .5 }}>{submitting ? 'Je vérifie…' : 'Ouvrir EduMaison'}</button>
          <button type="button" style={styles.link} onClick={() => setScreen('welcome')}>Retour</button>
        </form>}

        {accountScreen && <form onSubmit={submitAccount} style={styles.form}>
          {screen === 'register' && <>
            <img src={parentPhoto ? URL.createObjectURL(parentPhoto) : '/images/default-companion.webp'} alt="Aperçu du parent" style={styles.photoPreview} />
            <label style={styles.photoButton}>📷 Ajouter ma photo (facultatif)<input type="file" accept="image/*" capture="user" hidden onChange={e => setParentPhoto(e.target.files?.[0] || null)} /></label>
            <input style={styles.input} placeholder="Votre nom" value={form.name} onChange={e => setForm({ ...form, name: e.target.value })} required />
            <input style={styles.input} placeholder="Nom de la famille" value={form.family_name} onChange={e => setForm({ ...form, family_name: e.target.value })} required />
          </>}
          <input style={styles.input} type="email" placeholder="Adresse e-mail" value={form.email} onChange={e => setForm({ ...form, email: e.target.value })} autoComplete="email" required />
          <input style={styles.input} type="password" placeholder="Mot de passe" value={form.password} onChange={e => setForm({ ...form, password: e.target.value })} autoComplete={screen === 'login' ? 'current-password' : 'new-password'} minLength={8} required />
          {screen === 'register' && <input style={styles.input} type="password" placeholder="Confirmer le mot de passe" value={form.password_confirmation} onChange={e => setForm({ ...form, password_confirmation: e.target.value })} autoComplete="new-password" minLength={8} required />}
          {screen === 'register' && <><input style={styles.input} inputMode="numeric" pattern="[0-9]{4}" maxLength={4} placeholder="PIN familial à 4 chiffres" value={form.family_pin} onChange={e => setForm({ ...form, family_pin: e.target.value.replace(/\D/g, '').slice(0, 4) })} required /><input style={styles.input} inputMode="numeric" pattern="[0-9]{4}" maxLength={4} placeholder="Confirmer le PIN familial" value={form.family_pin_confirmation} onChange={e => setForm({ ...form, family_pin_confirmation: e.target.value.replace(/\D/g, '').slice(0, 4) })} required /></>}
          <div aria-live="polite" style={styles.message}>{message || ' '}</div>
          <button disabled={submitting} style={styles.button}>{submitting ? 'Un instant…' : screen === 'register' ? 'Créer notre espace' : 'Se connecter'}</button>
          <button type="button" style={styles.link} onClick={() => setScreen('welcome')}>Retour</button>
        </form>}

        {screen === 'child' && <form onSubmit={submitChild} style={styles.form}>
          <p style={styles.prompt}>Créons le premier profil d’apprentissage de votre famille.</p>
          <img src={childPhoto ? URL.createObjectURL(childPhoto) : '/images/default-child.webp'} alt="Aperçu de l’enfant" style={styles.photoPreview} />
          <label style={styles.photoButton}>📷 Ajouter sa photo (facultatif)<input type="file" accept="image/*" capture="user" hidden onChange={e => setChildPhoto(e.target.files?.[0] || null)} /></label>
          <input style={styles.input} placeholder="Prénom de l’enfant" value={childForm.first_name} onChange={e => setChildForm({ ...childForm, first_name: e.target.value })} required />
          <input style={styles.input} placeholder="Nom (facultatif)" value={childForm.last_name} onChange={e => setChildForm({ ...childForm, last_name: e.target.value })} />
          <input style={styles.input} type="date" aria-label="Date de naissance" value={childForm.birth_date} onChange={e => setChildForm({ ...childForm, birth_date: e.target.value })} required />
          <select style={styles.input} value={childForm.level_id} onChange={e => setChildForm({ ...childForm, level_id: e.target.value })} required>
            <option value="">Choisir la classe</option>
            {levels.map(level => <option key={level.id} value={level.id}>{level.name}</option>)}
          </select>
          <input style={styles.input} inputMode="numeric" pattern="[0-9]{4}" maxLength={4} placeholder="PIN enfant à 4 chiffres" value={childForm.pin} onChange={e => setChildForm({ ...childForm, pin: e.target.value.replace(/\D/g, '').slice(0, 4) })} required />
          <div aria-live="polite" style={styles.message}>{message || ' '}</div>
          <button disabled={submitting || childForm.pin.length !== 4} style={styles.button}>{submitting ? 'Un instant…' : 'Créer son profil'}</button>
        </form>}
        <p className="adventure-entry__note" style={styles.note}>Les données de chaque famille sont séparées et privées.</p>
      </section>
    </div>
  )
}

const styles: Record<string, React.CSSProperties> = {
  page: { minHeight: '100vh', display: 'grid', placeItems: 'center', padding: 20, boxSizing: 'border-box', position: 'relative', overflow: 'hidden', background: '#0b4828' },
  backdrop: { position: 'absolute', inset: 0, background: 'rgba(4,45,31,.2)' },
  modal: { position: 'relative', width: 'min(100%, 460px)', boxSizing: 'border-box', padding: '30px 24px 24px', borderRadius: 8, background: '#fffdf7', boxShadow: '0 24px 70px rgba(0,0,0,.32)', textAlign: 'center', fontFamily: 'Nunito, system-ui, sans-serif' },
  brand: { margin: '8px 0 4px', color: '#16733a', fontSize: 14, letterSpacing: 2, fontWeight: 900 },
  title: { margin: '4px 0 16px', color: '#20352a', fontSize: 'clamp(26px, 7vw, 36px)', lineHeight: 1.1 },
  prompt: { margin: '0 0 16px', color: '#5c695f', fontSize: 16, fontWeight: 700 },
  form: { display: 'flex', flexDirection: 'column', gap: 10 },
  input: { boxSizing: 'border-box', width: '100%', minHeight: 52, border: '2px solid #c9e7d2', borderRadius: 14, padding: '0 14px', background: '#f7fff9', color: '#183b27', fontSize: 16, fontWeight: 700 },
  pin: { boxSizing: 'border-box', width: '100%', height: 70, border: '3px solid #c9e7d2', borderRadius: 18, background: '#f7fff9', color: '#183b27', textAlign: 'center', fontSize: 36, fontWeight: 900, letterSpacing: 16, paddingLeft: 16 },
  message: { minHeight: 28, color: '#b42318', fontSize: 13, fontWeight: 800 },
  button: { minHeight: 54, border: 0, borderRadius: 16, background: '#159957', color: 'white', fontSize: 17, fontWeight: 900, cursor: 'pointer' },
  secondary: { minHeight: 52, border: '2px solid #159957', borderRadius: 16, background: 'white', color: '#126f41', fontSize: 16, fontWeight: 900, cursor: 'pointer' },
  link: { border: 0, background: 'transparent', color: '#537161', fontSize: 13, fontWeight: 800, cursor: 'pointer', padding: 8 },
  note: { margin: '14px 0 0', color: '#78827b', fontSize: 12, fontWeight: 700 },
  loader: { width: 42, height: 42, margin: '28px auto', border: '5px solid #dcefe2', borderTopColor: '#159957', borderRadius: '50%' },
  photoPreview: { width: 92, height: 92, borderRadius: '50%', objectFit: 'cover', margin: '0 auto', border: '4px solid #c9e7d2' },
  photoButton: { minHeight: 42, display: 'grid', placeItems: 'center', border: '2px dashed #8bcaa2', borderRadius: 14, color: '#126f41', background: '#f7fff9', fontSize: 14, fontWeight: 900, cursor: 'pointer' },
}
