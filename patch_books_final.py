import sys

path = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

books_screen = """
interface BookRef {
  id: number; book_name: string; page_from: number | null; page_to: number | null
  chapter: string; notes: string; level: string
  subject_id: number; subject_name: string; unit_id: number; unit_name: string
  level_id: number
}

function BooksScreen({ t }: { t: typeof T['fr'] }) {
  const [books, setBooks] = useState<BookRef[]>([])
  const [subjects, setSubjects] = useState<Subject[]>([])
  const [showForm, setShowForm] = useState(false)
  const [loading, setLoading] = useState(true)
  const [form, setForm] = useState({ book_name: '', chapter: '', page_from: '', page_to: '', subject_id: '', unit_id: '', notes: '' })
  const [saving, setSaving] = useState(false)
  const [msg, setMsg] = useState('')

  useEffect(() => {
    fetch('/api/books').then(r => r.json()).then(setBooks).finally(() => setLoading(false))
    fetch('/api/mama/subjects/0').then(r => r.json()).then(setSubjects).catch(() => {})
  }, [])

  const selectedSubject = subjects.find(s => s.id === parseInt(form.subject_id))

  const save = async () => {
    if (!form.book_name || !form.subject_id) return
    setSaving(true)
    try {
      await fetch('/api/books', {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          book_name: form.book_name, chapter: form.chapter,
          page_from: form.page_from ? parseInt(form.page_from) : null,
          page_to: form.page_to ? parseInt(form.page_to) : null,
          subject_id: parseInt(form.subject_id),
          unit_id: form.unit_id ? parseInt(form.unit_id) : null,
          notes: form.notes
        })
      })
      const updated = await fetch('/api/books').then(r => r.json())
      setBooks(updated)
      setForm({ book_name: '', chapter: '', page_from: '', page_to: '', subject_id: '', unit_id: '', notes: '' })
      setShowForm(false)
      setMsg('Livre associe !')
      setTimeout(() => setMsg(''), 2000)
    } finally { setSaving(false) }
  }

  const del = async (id: number) => {
    await fetch('/api/books/' + id, { method: 'DELETE' })
    setBooks(books.filter(b => b.id !== id))
  }

  const inp = { width: '100%', padding: '10px 12px', borderRadius: 12, border: '1.5px solid ' + P.border, fontFamily: 'Nunito, sans-serif', fontSize: 13, background: P.card, color: P.dark, marginBottom: 10 } as React.CSSProperties

  return (
    <div>
      {msg && <div style={{ background: '#D1FAE5', color: P.green, borderRadius: 12, padding: '10px 14px', marginBottom: 14, fontWeight: 800 }}>{msg}</div>}
      <button onClick={() => setShowForm(!showForm)} style={{ width: '100%', padding: 13, borderRadius: 16, border: 'none', background: showForm ? P.border : P.green, color: showForm ? P.soft : 'white', fontFamily: 'Nunito, sans-serif', fontSize: 15, fontWeight: 900, cursor: 'pointer', marginBottom: 16 }}>
        {showForm ? 'Annuler' : '+ Associer un livre'}
      </button>
      {showForm && (
        <div style={{ background: P.white, borderRadius: 20, padding: '18px 16px', marginBottom: 16, border: '1.5px solid ' + P.border }}>
          <input placeholder="Nom du livre" value={form.book_name} onChange={e => setForm({...form, book_name: e.target.value})} style={inp} />
          <input placeholder="Chapitre" value={form.chapter} onChange={e => setForm({...form, chapter: e.target.value})} style={inp} />
          <div style={{ display: 'flex', gap: 8, marginBottom: 10 }}>
            <input type="number" placeholder="Page debut" value={form.page_from} onChange={e => setForm({...form, page_from: e.target.value})} style={{ ...inp, marginBottom: 0, width: '50%' }} />
            <input type="number" placeholder="Page fin" value={form.page_to} onChange={e => setForm({...form, page_to: e.target.value})} style={{ ...inp, marginBottom: 0, width: '50%' }} />
          </div>
          <select value={form.subject_id} onChange={e => setForm({...form, subject_id: e.target.value, unit_id: ''})} style={inp}>
            <option value="">Choisir une matiere...</option>
            {subjects.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
          </select>
          {selectedSubject && selectedSubject.units.length > 0 && (
            <select value={form.unit_id} onChange={e => setForm({...form, unit_id: e.target.value})} style={inp}>
              <option value="">Toutes les unites</option>
              {selectedSubject.units.map(u => <option key={u.id} value={u.id}>{u.name}</option>)}
            </select>
          )}
          <input placeholder="Notes (optionnel)" value={form.notes} onChange={e => setForm({...form, notes: e.target.value})} style={inp} />
          <button onClick={save} disabled={saving || !form.book_name || !form.subject_id} style={{ width: '100%', padding: 12, borderRadius: 14, border: 'none', background: !form.book_name || !form.subject_id ? P.border : P.green, color: 'white', fontFamily: 'Nunito, sans-serif', fontSize: 14, fontWeight: 900, cursor: 'pointer' }}>
            {saving ? '...' : 'Enregistrer'}
          </button>
        </div>
      )}
      {loading && <div style={{ textAlign: 'center', padding: 20, color: P.soft }}>Chargement...</div>}
      {!loading && books.length === 0 && !showForm && (
        <div style={{ textAlign: 'center', padding: '40px 0' }}>
          <div style={{ fontSize: 48, marginBottom: 12 }}>&#128218;</div>
          <div style={{ fontSize: 15, fontWeight: 800, color: P.dark, marginBottom: 6 }}>Aucun livre associe</div>
          <div style={{ fontSize: 13, color: P.soft }}>Associez les livres physiques aux unites EduMaison.</div>
        </div>
      )}
      {books.map(b => (
        <div key={b.id} style={{ background: P.white, borderRadius: 18, padding: '14px 16px', marginBottom: 10, border: '1.5px solid ' + P.border }}>
          <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', marginBottom: 6 }}>
            <div style={{ flex: 1 }}>
              <div style={{ fontSize: 15, fontWeight: 900, color: P.dark }}>{b.book_name}</div>
              {b.chapter && <div style={{ fontSize: 12, color: P.soft }}>{b.chapter}</div>}
            </div>
            <button onClick={() => del(b.id)} style={{ background: '#FEE2E2', color: P.red, border: 'none', borderRadius: 10, padding: '5px 10px', fontSize: 12, fontWeight: 800, cursor: 'pointer', fontFamily: 'Nunito, sans-serif', flexShrink: 0 }}>x</button>
          </div>
          <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap' as const }}>
            <span style={{ background: 'rgba(29,107,42,.1)', color: P.green, borderRadius: 20, padding: '3px 10px', fontSize: 11, fontWeight: 800 }}>{b.subject_name}</span>
            {b.unit_name && <span style={{ background: P.card, color: P.soft, borderRadius: 20, padding: '3px 10px', fontSize: 11 }}>{b.unit_name}</span>}
            {b.page_from && <span style={{ background: P.card, color: P.soft, borderRadius: 20, padding: '3px 10px', fontSize: 11 }}>p.{b.page_from}{b.page_to ? '-'+b.page_to : ''}</span>}
          </div>
          {b.notes && <div style={{ fontSize: 12, color: P.soft, marginTop: 6 }}>{b.notes}</div>}
        </div>
      ))}
    </div>
  )
}
"""

# Inserer avant ScreenContent
content = content.replace(
    "function ScreenContent(",
    books_screen + "\nfunction ScreenContent("
)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("OK - BooksScreen count:", content.count("function BooksScreen"))
