import { useState, useEffect } from 'react'

interface Subject { id: number; name: string }

interface Props {
  householdId: number
  onCreated?: () => void
}

export default function ExamCreator({ householdId, onCreated }: Props) {
  const [subjects, setSubjects] = useState<Subject[]>([])
  const [form, setForm] = useState({
    title: '',
    subject_id: '',
    question_count: 10,
    duration_minutes: 30,
    scheduled_at: '',
  })
  const [saving, setSaving] = useState(false)
  const [success, setSuccess] = useState(false)

  useEffect(() => {
    fetch('/api/subjects').then(r => r.json()).then(setSubjects).catch(() => {})
  }, [])

  const submit = async () => {
    if (!form.title || !form.subject_id || !form.scheduled_at) return
    setSaving(true)
    await fetch('/api/exams', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ ...form, household_id: householdId }),
    })
    setSaving(false)
    setSuccess(true)
    setForm({ title: '', subject_id: '', question_count: 10, duration_minutes: 30, scheduled_at: '' })
    setTimeout(() => setSuccess(false), 3000)
    onCreated?.()
  }

  return (
    <div style={{ background: 'white', borderRadius: 18, padding: '20px', border: '1.5px solid #F0E4D8', fontFamily: 'system-ui,sans-serif' }}>
      <div style={{ fontSize: 16, fontWeight: 900, color: '#2D1B0E', marginBottom: 16 }}>📝 Schedule an Exam</div>

      {success && (
        <div style={{ background: '#D1FAE5', color: '#059669', padding: '10px 14px', borderRadius: 10, marginBottom: 12, fontWeight: 700, fontSize: 13 }}>
          ✅ Exam scheduled! Your children will see it on their home screen.
        </div>
      )}

      <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
        <input value={form.title} onChange={e => setForm(f => ({ ...f, title: e.target.value }))}
          placeholder="Exam title (e.g. Maths Test Week 5)"
          style={{ padding: '10px 14px', borderRadius: 10, border: '1.5px solid #F0E4D8', fontSize: 14, outline: 'none' }}
        />

        <select value={form.subject_id} onChange={e => setForm(f => ({ ...f, subject_id: e.target.value }))}
          style={{ padding: '10px 14px', borderRadius: 10, border: '1.5px solid #F0E4D8', fontSize: 14, outline: 'none', background: 'white', color: '#2D1B0E' }}>
          <option value="">Select subject...</option>
          {subjects.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
        </select>

        <div style={{ display: 'flex', gap: 10 }}>
          <div style={{ flex: 1 }}>
            <div style={{ fontSize: 11, color: '#C8A090', marginBottom: 4 }}>Questions</div>
            <input type="number" value={form.question_count} min={5} max={30}
              onChange={e => setForm(f => ({ ...f, question_count: +e.target.value }))}
              style={{ width: '100%', padding: '10px 14px', borderRadius: 10, border: '1.5px solid #F0E4D8', fontSize: 14 }}
            />
          </div>
          <div style={{ flex: 1 }}>
            <div style={{ fontSize: 11, color: '#C8A090', marginBottom: 4 }}>Duration (min)</div>
            <input type="number" value={form.duration_minutes} min={5} max={120}
              onChange={e => setForm(f => ({ ...f, duration_minutes: +e.target.value }))}
              style={{ width: '100%', padding: '10px 14px', borderRadius: 10, border: '1.5px solid #F0E4D8', fontSize: 14 }}
            />
          </div>
        </div>

        <div>
          <div style={{ fontSize: 11, color: '#C8A090', marginBottom: 4 }}>Date & Time</div>
          <input type="datetime-local" value={form.scheduled_at}
            onChange={e => setForm(f => ({ ...f, scheduled_at: e.target.value }))}
            style={{ width: '100%', padding: '10px 14px', borderRadius: 10, border: '1.5px solid #F0E4D8', fontSize: 14 }}
          />
        </div>

        <button onClick={submit} disabled={saving}
          style={{ padding: '13px 0', borderRadius: 14, border: 'none', background: saving ? '#E0D4CA' : '#FF8FAB', color: 'white', fontSize: 15, fontWeight: 800, cursor: saving ? 'default' : 'pointer' }}>
          {saving ? 'Scheduling...' : '📅 Schedule Exam'}
        </button>
      </div>
    </div>
  )
}
