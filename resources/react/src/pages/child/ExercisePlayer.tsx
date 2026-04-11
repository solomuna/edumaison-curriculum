import OralDrill from './exercises/OralDrill'
import MCQ from './exercises/MCQ'
import Handwriting from './exercises/Handwriting'
import FillIn from './exercises/FillIn'
import MatchPairs from './exercises/MatchPairs'
import SentenceOrder from './exercises/SentenceOrder'
import TrueFalse from './exercises/TrueFalse'
import ClockReading from './exercises/ClockReading'
import type { Exercise } from '../../types/exercise'
import VennDiagram from './exercises/VennDiagram'
import NumberLine from './exercises/NumberLine'
import Geometry from './exercises/Geometry'
import IctIllustration from '../../components/IctIllustration'
import { MamaJudi } from '../../services/MamaJudi'
import { useEffect } from 'react'


interface Props {
  exercise: Exercise
  onComplete: (score: number) => void
  onBack: () => void
}

function ExerciseShell({ title, onBack, children, category, keyword }: {
  title: string
  onBack: () => void
  children: React.ReactNode
  category?: string
  keyword?: string
}) {
  return (
    <div style={{
      background: 'var(--bg)', minHeight: '100vh',
      fontFamily: 'Nunito, system-ui, sans-serif'
    }}>
      <div style={{
        display: 'flex', alignItems: 'center', gap: 12,
        padding: '12px 16px', background: '#1D6B2A'
      }}>
        <button
          onClick={onBack}
          style={{
            background: 'rgba(255,255,255,0.2)', border: 'none',
            borderRadius: 10, padding: '6px 14px', fontSize: 13,
            fontWeight: 800, color: 'white', cursor: 'pointer'
          }}
        >
          Back
        </button>
        <span style={{ fontSize: 14, fontWeight: 900, color: 'white', flex: 1 }}>
          {title}
        </span>
      </div>
      {category === 'ict' && keyword && (
        <div style={{
          background: 'var(--card)', borderRadius: 20, margin: '14px 16px 0',
          padding: '14px', display: 'flex', alignItems: 'center', justifyContent: 'center',
          border: '1.5px solid var(--border)'
        }}>
          <IctIllustration keyword={keyword} />
        </div>
      )}
      <div style={{ padding: '16px' }}>
        {children}
      </div>
    </div>
  )
}

export default function ExercisePlayer({ exercise, onComplete, onBack }: Props) {
  const { content } = exercise
  const type = content.type

  const FRENCH_SUBJECTS = ['French', 'Francais', 'NLC', 'National Languages and Cultures']
  const subject = (exercise as any).subject ?? ''
  const isFrench = FRENCH_SUBJECTS.some(s => subject.toLowerCase().includes(s.toLowerCase()))

  useEffect(() => {
    if (type === 'oral_drill') return
    const text = exercise.instructions || exercise.title
    if (!text) return
    const lang = isFrench ? 'fr-FR' : 'en-GB'
    setTimeout(() => MamaJudi.speakLang(text, lang), 500)
  }, [exercise.id])

  const handleBool = (correct: boolean) => onComplete(correct ? 1 : 0)

  if (type === 'oral_drill') {
    return <OralDrill title={exercise.title} instructions={exercise.instructions} content={content} onComplete={onComplete} onBack={onBack} />
  }

  if (type === 'multiple_choice' || type === 'mcq') {
    return <MCQ title={exercise.title} instructions={exercise.instructions} content={content} subject={(exercise as any).subject} onComplete={onComplete} onBack={onBack} />
  }

  if (type === 'handwriting') {
    return <Handwriting title={exercise.title} instructions={exercise.instructions} content={content} onComplete={onComplete} onBack={onBack} />
  }

  if (type === 'fill_in') {
    return <FillIn title={exercise.title} instructions={exercise.instructions} content={content} onComplete={onComplete} onBack={onBack} />
  }

  if (type === 'match_pairs') {
    return (
      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title}>
        <MatchPairs content={content} onComplete={handleBool} />
      </ExerciseShell>
    )
  }

  if (type === 'sentence_order') {
    return (
      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title}>
        <SentenceOrder content={content} onComplete={handleBool} />
      </ExerciseShell>
    )
  }

  if (type === 'true_false') {
    return (
      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title}>
        <TrueFalse content={content} onComplete={handleBool} />
      </ExerciseShell>
    )
  }

  if (type === 'clock_reading') {
    return (
      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title}>
        <ClockReading content={content} onComplete={handleBool} />
      </ExerciseShell>
    )
  }
  if (type === 'geometry') {
    return (
      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title}>
        <Geometry content={content} onComplete={handleBool} />
      </ExerciseShell>
    )
  }

  if (type === 'venn_diagram') {
    return (
      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title}>
        <VennDiagram content={content} onComplete={handleBool} />
      </ExerciseShell>
    )
  }

  if (type === 'number_line') {
    return (
      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title}>
        <NumberLine content={content} onComplete={handleBool} />
      </ExerciseShell>
    )
  }


  return (
    <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title}>
      <div style={{ textAlign: 'center', padding: '60px 20px' }}>
        <svg width="64" height="64" viewBox="0 0 64 64" style={{ margin: '0 auto 16px', display: 'block' }}>
          <circle cx="32" cy="32" r="28" fill="#FEF3C7" stroke="#F59E0B" strokeWidth="2"/>
          <line x1="32" y1="20" x2="32" y2="36" stroke="#F59E0B" strokeWidth="3" strokeLinecap="round"/>
          <circle cx="32" cy="44" r="2.5" fill="#F59E0B"/>
        </svg>
        <div style={{ fontSize: 15, fontWeight: 700, color: 'var(--text-dark)', marginBottom: 6 }}>
          Moteur en cours de construction
        </div>
        <div style={{ fontSize: 13, color: '#B8A090' }}>Type : {type}</div>
      </div>
    </ExerciseShell>
  )
}
