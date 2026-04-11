import { useState, useEffect } from 'react'
import type { Child } from '../../types/child'

interface Result {
  subject: string
  subject_id: number
  average_score: string
  total_score: string
  max_score: string
  appreciation: string
  teacher_comment: string
}

interface BulletinData {
  child: { name: string; first_name: string; level: string; birth_date: string; student_number: string }
  year: string
  results: Result[]
  average: number
  total_xp: number
  rank: number | null
  class_size: number
  class_average: number | null
}

function getCompetencies(levelId: number) {
  // C1/C2/C3 (level_id 5-7): Reading + Handwriting separees = 110
  // C4/C5/C6 (level_id 8-10): fusionnes dans English = 60
  const comp1 = levelId <= 7
    ? { label: 'Comp_1 — Communication', subjects: ['English','French','National Languages and Cultures','Reading','Handwriting'], sur: 110 }
    : { label: 'Comp_1 — Communication', subjects: ['English','French','National Languages and Cultures'], sur: 60 }
  return [
    comp1,
    { label: 'Comp_2 — Sciences & Mathematics', subjects: ['Mathematics','Science and Technology'], sur: 100 },
    { label: 'Comp_3 — Citizenship & Social Studies', subjects: ['Citizenship','Social Studies'], sur: 40 },
    { label: 'Comp_4 — Entrepreneurship & Vocational', subjects: ['Home Economics and Vocational Skills'], sur: 30 },
    { label: 'Comp_5 — Information Technology', subjects: ['ICT'], sur: 20 },
    { label: 'Comp_6 — Arts & Physical Education', subjects: ['Arts and Crafts','Physical Education'], sur: 20 },
  ]
}

// Translate French appreciation to English
function translateApp(a: string): string {
  const clean = a.trim()
  const map: Record<string, string> = {
    'Excellent': 'Excellent',
    'Très bien': 'Very Good',
    'Tres bien': 'Very Good',
    'TrÃ¨s bien': 'Very Good',
    'Bien': 'Good',
    'Assez bien': 'Keep It Up!',
    'Passable': 'More Effort Needed',
    'Insuffisant': "Let's Work Harder!",
  }
  return map[clean] || clean
}

const APP_STYLES: Record<string, { color: string; bg: string }> = {
  'Excellent':          { color: '#059669', bg: '#D1FAE5' },
  'Very Good':          { color: '#059669', bg: '#D1FAE5' },
  'Good':               { color: '#2563EB', bg: '#DBEAFE' },
  'Keep It Up!':        { color: '#D97706', bg: '#FEF3C7' },
  'More Effort Needed': { color: '#EA580C', bg: '#FFEDD5' },
  "Let's Work Harder!": { color: '#DC2626', bg: '#FEE2E2' },
}
const getApp = (a: string) => APP_STYLES[translateApp(a)] || { color: '#6B7280', bg: '#F3F4F6' }

interface Props { child: Child; onBack: () => void }

export default function BulletinPage({ child, onBack }: Props) {
  const [data, setData] = useState<BulletinData | null>(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetch(`/api/bulletin/child/${child.id}`)
      .then(r => r.json()).then(setData).catch(() => {}).finally(() => setLoading(false))
  }, [child.id])

  if (loading) return (
    <div style={{ background: 'var(--bg)', minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'system-ui,sans-serif' }}>
      <div style={{ fontSize: 16, color: 'var(--text-soft)' }}>Loading report card...</div>
    </div>
  )

  if (!data) return (
    <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'system-ui,sans-serif', padding: '20px 18px' }}>
      <button onClick={onBack} style={{ background: '#FFF0E8', border: '1.5px solid #FFD4B0', borderRadius: 10, padding: '7px 14px', fontSize: 13, fontWeight: 700, color: '#C8704A', cursor: 'pointer', marginBottom: 20 }}>← Back</button>
      <div style={{ textAlign: 'center', padding: '60px 0' }}>
        <div style={{ fontSize: 48, marginBottom: 16 }}>📋</div>
        <div style={{ fontSize: 16, fontWeight: 800, color: 'var(--text-dark)', marginBottom: 8 }}>No report card available</div>
        <div style={{ fontSize: 13, color: 'var(--text-soft)' }}>Scores will appear once entered by your parents.</div>
      </div>
    </div>
  )

  const avg = data.average
  const avgColor = avg >= 14 ? '#059669' : avg >= 10 ? '#D97706' : '#DC2626'
  const COMPETENCIES = getCompetencies(child.level_id)
  const resultMap: Record<string, Result> = {}
  data.results.forEach(r => { resultMap[r.subject] = r })

  return (
    <div style={{ background: 'var(--bg)', minHeight: '100vh', fontFamily: 'system-ui,sans-serif', paddingBottom: 40 }}>

      <div className="no-print" style={{ padding: '14px 18px', display: 'flex', alignItems: 'center', gap: 12, borderBottom: '1px solid var(--border)', background: 'var(--white)' }}>
        <button onClick={onBack} style={{ background: '#FFF0E8', border: '1.5px solid #FFD4B0', borderRadius: 10, padding: '7px 14px', fontSize: 13, fontWeight: 700, color: '#C8704A', cursor: 'pointer' }}>← Back</button>
        <div style={{ flex: 1 }}>
          <div style={{ fontSize: 15, fontWeight: 900, color: 'var(--text-dark)' }}>Report Card</div>
          <div style={{ fontSize: 12, color: 'var(--text-soft)' }}>{data.child.name}</div>
        </div>
        <button onClick={() => window.print()} style={{ padding: '9px 20px', borderRadius: 12, border: 'none', background: '#1D6B2A', color: 'white', fontSize: 13, fontWeight: 800, cursor: 'pointer' }}>
          🖨️ Print / PDF
        </button>
      </div>

      <div id="bulletin" style={{ maxWidth: 720, margin: '20px auto', background: 'var(--white)', boxShadow: '0 2px 20px rgba(0,0,0,0.08)' }}>

        {/* Header */}
        <div style={{ borderBottom: '3px solid #333', padding: '16px 24px', display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
          <div>
            <div style={{ fontSize: 11, fontWeight: 700, color: '#333', textTransform: 'uppercase' }}>Ministry of Basic Education</div>
            <div style={{ fontSize: 16, fontWeight: 900, color: '#1D6B2A', marginTop: 2 }}>EduMaison</div>
            <div style={{ fontSize: 10, color: '#666', marginTop: 1 }}>Digital Educational Platform — Yaoundé</div>
          </div>
          <div style={{ textAlign: 'center' }}>
            <div style={{ width: 60, height: 60, borderRadius: '50%', background: 'linear-gradient(135deg,#1D6B2A,#4A9B5F)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 24 }}>📚</div>
          </div>
          <div style={{ textAlign: 'right' }}>
            <div style={{ fontSize: 10, color: '#666' }}>School Year</div>
            <div style={{ fontSize: 14, fontWeight: 900, color: '#333' }}>{data.year}</div>
            <div style={{ fontSize: 10, color: '#666', marginTop: 4 }}>
              {new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' })}
            </div>
          </div>
        </div>

        {/* Title */}
        <div style={{ textAlign: 'center', padding: '12px 0', borderBottom: '2px solid var(--border)' }}>
          <div style={{ fontSize: 22, fontWeight: 900, letterSpacing: 2, color: '#333' }}>SCHOOL REPORT CARD</div>
        </div>

        {/* Child info */}
        <div style={{ padding: '12px 24px', borderBottom: '2px solid var(--border)', display: 'grid', gridTemplateColumns: '1fr 1fr 1fr 1fr', gap: 8, fontSize: 12 }}>
          <div><span style={{ color: '#888' }}>Name: </span><strong>{data.child.name.toUpperCase()}</strong></div>
          <div><span style={{ color: '#888' }}>Class: </span><strong>{data.child.level}</strong></div>
          <div><span style={{ color: '#888' }}>Date of birth: </span><strong>{data.child.birth_date ? new Date(data.child.birth_date).toLocaleDateString('en-GB') : '—'}</strong></div>
          <div><span style={{ color: '#888' }}>Ref: </span><strong>{data.child.student_number}</strong></div>
        </div>

        {/* Table */}
        <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 11 }}>
          <thead>
            <tr style={{ background: '#F0F0F0' }}>
              <th style={{ padding: '8px 10px', textAlign: 'left', border: '1px solid #ddd', width: '32%' }}>Subjects</th>
              <th style={{ padding: '8px 10px', textAlign: 'center', border: '1px solid #ddd', width: '8%' }}>Out of</th>
              <th style={{ padding: '8px 10px', textAlign: 'center', border: '1px solid #ddd', width: '12%' }}>Score</th>
              <th style={{ padding: '8px 10px', textAlign: 'center', border: '1px solid #ddd', width: '14%' }}>Appreciation</th>
              <th style={{ padding: '8px 10px', textAlign: 'left', border: '1px solid #ddd' }}>Mama Judi's Remarks</th>
            </tr>
          </thead>
          <tbody>
            {COMPETENCIES.map((comp, ci) => {
              const compResults = comp.subjects.map(s => resultMap[s]).filter(Boolean)
              if (compResults.length === 0) return null
              const compTotal = compResults.reduce((sum, r) => sum + parseFloat(r.total_score || r.average_score), 0)
              return (
                <>
                  <tr style={{ background: '#1D6B2A' }}>
                    <td style={{ padding: '6px 10px', border: '1px solid #ddd', fontSize: 10, color: 'white', fontWeight: 800 }}>{comp.label}</td>
                    <td style={{ padding: '6px 10px', border: '1px solid #ddd', textAlign: 'center', fontSize: 10, color: 'white' }}>{comp.sur}</td>
                    <td colSpan={3} style={{ padding: '6px 10px', border: '1px solid #ddd' }}></td>
                  </tr>
                  {compResults.map((r, ri) => {
                    const rawApp = (r.appreciation || '').trim()
                    const appEn = rawApp === 'Insuffisant' || rawApp === "Let's Work Harder!" ? "Let's Work Harder!"
                      : rawApp === 'Passable' || rawApp === 'More Effort Needed' ? 'More Effort Needed'
                      : rawApp === 'Assez bien' || rawApp === 'Keep It Up!' ? 'Keep It Up!'
                      : rawApp === 'Bien' || rawApp === 'Good' ? 'Good'
                      : rawApp === 'Tres bien' || rawApp === 'Very Good' || rawApp.includes('s bien') ? 'Very Good'
                      : rawApp === 'Excellent' ? 'Excellent'
                      : translateApp(rawApp)
                    const app = getApp(r.appreciation)
                    const score = parseFloat(r.average_score)
                    const scoreColor = score >= 14 ? '#059669' : score >= 10 ? '#D97706' : '#DC2626'
                    return (
                      <tr key={`${ci}-${ri}`} style={{ background: ri % 2 === 0 ? 'white' : '#FAFAFA' }}>
                        <td style={{ padding: '7px 10px', border: '1px solid #ddd', paddingLeft: 20 }}>{r.subject}</td>
                        <td style={{ padding: '7px 10px', border: '1px solid #ddd', textAlign: 'center', color: '#666' }}>20</td>
                        <td style={{ padding: '7px 10px', border: '1px solid #ddd', textAlign: 'center' }}>
                          <strong style={{ color: scoreColor, fontSize: 13 }}>{score.toFixed(2)}</strong>
                        </td>
                        <td style={{ padding: '7px 10px', border: '1px solid #ddd', textAlign: 'center' }}>
                          <span style={{ background: app.bg, color: app.color, padding: '2px 8px', borderRadius: 6, fontSize: 10, fontWeight: 700 }}>{appEn}</span>
                        </td>
                        <td style={{ padding: '7px 10px', border: '1px solid #ddd', fontSize: 10, color: '#555', lineHeight: 1.4 }}>{r.teacher_comment || '—'}</td>
                      </tr>
                    )
                  })}
                  <tr style={{ background: '#E8F4FD' }}>
                    <td style={{ padding: '6px 10px', border: '1px solid #ddd', fontSize: 10, color: '#555', fontStyle: 'italic' }}>Subtotal</td>
                    <td style={{ padding: '6px 10px', border: '1px solid #ddd', textAlign: 'center', fontSize: 10, color: '#555' }}>{comp.sur}</td>
                    <td style={{ padding: '6px 10px', border: '1px solid #ddd', textAlign: 'center', fontSize: 12, color: '#1D4ED8', fontWeight: 900 }}>{compTotal.toFixed(2)}</td>
                    <td style={{ padding: '6px 10px', border: '1px solid #ddd', textAlign: 'center', fontSize: 11, color: '#059669', fontWeight: 700 }}>
                      Avg: {(compTotal / compResults.length).toFixed(2)}/20
                    </td>
                    <td style={{ padding: '6px 10px', border: '1px solid #ddd' }}></td>
                  </tr>
                </>
              )
            })}
          </tbody>
        </table>

        {/* Summary */}
        <div style={{ padding: '14px 24px', borderTop: '3px solid #333', display: 'grid', gridTemplateColumns: '1fr 1fr 1fr 1fr 1fr', gap: 10 }}>
          <div style={{ textAlign: 'center', background: 'var(--card2)', borderRadius: 10, padding: '10px 6px' }}>
            <div style={{ fontSize: 9, color: '#888', textTransform: 'uppercase', letterSpacing: '0.5px' }}>General Average</div>
            <div style={{ fontSize: 24, fontWeight: 900, color: avgColor, marginTop: 2 }}>{avg.toFixed(2)}<span style={{ fontSize: 11, color: '#aaa' }}>/20</span></div>
          </div>
          <div style={{ textAlign: 'center', background: 'var(--card2)', borderRadius: 10, padding: '10px 6px' }}>
            <div style={{ fontSize: 9, color: '#888', textTransform: 'uppercase', letterSpacing: '0.5px' }}>Class Rank</div>
            <div style={{ fontSize: 22, fontWeight: 900, color: '#1D4ED8', marginTop: 4 }}>
              {data.rank ? <>{data.rank}<span style={{ fontSize: 11, color: '#aaa' }}>/{data.class_size}</span></> : '—'}
            </div>
          </div>
          <div style={{ textAlign: 'center', background: 'var(--card2)', borderRadius: 10, padding: '10px 6px' }}>
            <div style={{ fontSize: 9, color: '#888', textTransform: 'uppercase', letterSpacing: '0.5px' }}>Class Average</div>
            <div style={{ fontSize: 20, fontWeight: 900, color: '#6B7280', marginTop: 4 }}>
              {data.class_average ? data.class_average.toFixed(2) : '—'}<span style={{ fontSize: 10, color: '#aaa' }}>/20</span>
            </div>
          </div>
          <div style={{ textAlign: 'center', background: 'var(--card2)', borderRadius: 10, padding: '10px 6px' }}>
            <div style={{ fontSize: 9, color: '#888', textTransform: 'uppercase', letterSpacing: '0.5px' }}>EduMaison XP</div>
            <div style={{ fontSize: 20, fontWeight: 900, color: '#F59E0B', marginTop: 4 }}>{data.total_xp}</div>
          </div>
          <div style={{ textAlign: 'center', background: 'var(--card2)', borderRadius: 10, padding: '10px 6px' }}>
            <div style={{ fontSize: 9, color: '#888', textTransform: 'uppercase', letterSpacing: '0.5px' }}>Overall Grade</div>
            <div style={{ fontSize: 12, fontWeight: 800, color: avgColor, marginTop: 6 }}>
              {avg >= 16 ? 'Excellent' : avg >= 14 ? 'Very Good' : avg >= 12 ? 'Good' : avg >= 10 ? 'Pass' : 'Insufficient'}
            </div>
          </div>
        </div>

        {/* Signatures */}
        <div style={{ padding: '14px 24px', borderTop: '1px solid var(--border)', display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: 12, fontSize: 11 }}>
          {['Class Teacher', 'Headteacher', 'Parent/Guardian'].map((label, i) => (
            <div key={i} style={{ textAlign: 'center' }}>
              <div style={{ color: '#888', marginBottom: 24 }}>{label}</div>
              <div style={{ borderTop: '1px solid #ccc', paddingTop: 4, color: '#aaa' }}>{i === 1 ? 'Signature & Stamp' : 'Signature'}</div>
            </div>
          ))}
        </div>

        {/* Footer */}
        <div style={{ padding: '8px 24px', background: 'var(--card2)', textAlign: 'center', borderTop: '1px solid var(--border)' }}>
          <div style={{ fontSize: 9, color: 'var(--text-soft)' }}>
            Generated by EduMaison — Digital Educational Platform · MINEDUB Cameroon
          </div>
        </div>
      </div>

      <style>{`
        @media print {
          .no-print { display: none !important; }
          body { background: white !important; }
          #bulletin { box-shadow: none !important; max-width: 100% !important; margin: 0 !important; }
          @page { margin: 1cm; }
        }
      `}</style>
    </div>
  )
}
