import OralDrill from './exercises/OralDrill'



import MCQ from './exercises/MCQ'



import Handwriting from './exercises/Handwriting'

import Dictation from './exercises/Dictation'

import WrittenResponse from './exercises/WrittenResponse'



import FillIn from './exercises/FillIn'



import MatchPairs from './exercises/MatchPairs'



import SentenceOrder from './exercises/SentenceOrder'



import TrueFalse from './exercises/TrueFalse'



import ClockReading from './exercises/ClockReading'



import type { Exercise, ExerciseCompletionHandler } from '../../types/exercise'



import VennDiagram from './exercises/VennDiagram'



import NumberLine from './exercises/NumberLine'



import Geometry from './exercises/Geometry'



import IctIllustration from '../../components/IctIllustration'



import { MamaJudi } from '../../services/MamaJudi'



import { useEffect, useState } from 'react'



import Ardoise from './exercises/Ardoise'

import MamaJudiPose from '../../components/MamaJudiPose'

import LessonShell, { type ReportResult } from '../../components/lesson/LessonShell'











interface Props {



  exercise: Exercise



  onComplete: ExerciseCompletionHandler



  onBack: () => void



}







function BookHint({ exerciseId }: { exerciseId: number }) {



  const [book, setBook] = useState<{book_name:string;page_from:number|null;page_to:number|null;chapter:string}|null>(null)



  useEffect(() => {



    fetch('/api/books/exercise/' + exerciseId)



      .then(r => r.json()).then(d => { if (d && d.book_name) setBook(d) }).catch(() => {})



  }, [exerciseId])



  if (!book) return null



  return (



    <div style={{ background: '#FEF9C3', borderRadius: 14, padding: '8px 14px', margin: '0 16px 10px', border: '1.5px solid #FCD34D', display: 'flex', alignItems: 'center', gap: 8, fontSize: 13 }}>



      <span style={{ fontSize: 18 }}>&#128214;</span>



      <div>



        <span style={{ fontWeight: 800, color: '#92400E' }}>{book.book_name}</span>



        {book.chapter && <span style={{ color: '#B45309' }}> — {book.chapter}</span>}



        {book.page_from && <span style={{ color: '#B45309' }}> p.{book.page_from}{book.page_to && book.page_to !== book.page_from ? '-'+book.page_to : ''}</span>}



      </div>



    </div>



  )



}







function ExerciseShell({ title, onBack, children, category, keyword, exerciseId, instructions, isFrench }: {



  title: string



  onBack: () => void



  children: React.ReactNode



  category?: string



  keyword?: string



  exerciseId?: number



  instructions?: string



  isFrench?: boolean



}) {



  const [showArdoise, setShowArdoise] = useState(false)



  return (



    <div className="adventure-exercise-shell" style={{



      background: 'var(--bg)', minHeight: '100vh',



      fontFamily: 'Nunito, system-ui, sans-serif'



    }}>



      {showArdoise && <Ardoise onClose={() => setShowArdoise(false)} />}



      <div className="adventure-exercise-header" style={{



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



        <button



          onClick={() => {



            const text = instructions || title



            const lang = isFrench ? 'fr-FR' : 'en-GB'



            if ('speechSynthesis' in window) window.speechSynthesis.cancel()



            MamaJudi.speakLangAfter(text, lang, 100)



          }}



          style={{



            background: 'rgba(255,255,255,0.2)', border: 'none',



            borderRadius: 10, padding: '6px 10px', fontSize: 18,



            cursor: 'pointer', lineHeight: 1



          }}



          title="Listen again"



        >



          &#128266;



        </button>



      </div>



      {exerciseId && <BookHint exerciseId={exerciseId} />}

      {instructions && (
        <MamaJudiPose pose="explain" className="adventure-judi-instruction">
          <span className="adventure-eyebrow">Mama Judi</span>
          <strong>{instructions}</strong>
        </MamaJudiPose>
      )}



      {category === 'ict' && keyword && (



        <div style={{



          background: 'var(--card)', borderRadius: 20, margin: '14px 16px 0',



          padding: '14px', display: 'flex', alignItems: 'center', justifyContent: 'center',



          border: '1.5px solid var(--border)'



        }}>



          <IctIllustration keyword={keyword} />



        </div>



      )}



      <div className="adventure-exercise-content" style={{ padding: '16px' }}>



        {children}



      </div>



      {/* Bouton ardoise flottant */}



      <button



        onClick={() => setShowArdoise(true)}



        style={{



          position: 'fixed', bottom: 110, right: 16, zIndex: 100,



          width: 52, height: 52, borderRadius: '50%',



          background: '#C47A3C', border: 'none',



          boxShadow: '0 4px 12px rgba(0,0,0,0.25)',



          fontSize: 22, cursor: 'pointer',



          display: 'flex', alignItems: 'center', justifyContent: 'center'



        }}



        title="Ardoise brouillon"



      >



        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>



      </button>



    </div>



  )



}







export default function ExercisePlayer({ exercise, onComplete, onBack }: Props) {



  // Guard: content peut arriver comme string JSON depuis FastAPI
  const content = typeof exercise.content === 'string'
    ? (() => { try { return JSON.parse(exercise.content) } catch { return {} } })()
    : exercise.content
  const type = content.type







  const FRENCH_SUBJECTS = ['French', 'Francais', 'NLC', 'National Languages and Cultures']



  const subject = (exercise as any).subject ?? ''



  const isFrench = FRENCH_SUBJECTS.some(s => subject.toLowerCase().includes(s.toLowerCase()))







  useEffect(() => {



    // Ces moteurs lisent eux-mêmes consigne et questions.
    if (['oral_drill', 'mcq', 'multiple_choice', 'fill_in'].includes(type) || (!type && Array.isArray(content.questions))) return



    const text = exercise.instructions || exercise.title



    if (!text) return



    const lang = isFrench ? 'fr-FR' : 'en-GB'



    // Annuler tout TTS en cours avant de lire



    if ('speechSynthesis' in window) window.speechSynthesis.cancel()



    MamaJudi.speakLangAfter(text, lang, 800)

    return () => MamaJudi.stop()



  }, [exercise.id])







  const handleBool = (correct: boolean, answers?: Record<string, unknown>) => onComplete(correct ? 100 : 0, {

    verification_status: 'auto_checked',

    answers,

    evidence: { method: 'server_answer_key' },

  })







  // Cadre « Duolingo » commun aux moteurs à une question : verdict immédiat,
  // son synchronisé, enregistrement sur « Continuer ».
  const shell = (render: (report: ReportResult) => React.ReactNode) => (
    <LessonShell
      title={exercise.title}
      instructions={exercise.instructions}
      isFrench={isFrench}
      onBack={onBack}
      onSubmit={handleBool}
      extras={<>
        {exercise.id && <BookHint exerciseId={exercise.id} />}
        {exercise.category === 'ict' && <div className="lesson-media"><IctIllustration keyword={exercise.title} /></div>}
      </>}
    >
      {render}
    </LessonShell>
  )

  if (type === 'oral_drill') {



    return <OralDrill title={exercise.title} instructions={exercise.instructions} content={content} isFrench={isFrench} onComplete={onComplete} onBack={onBack} />



  }







  // Adaptateur format simplifie MCQ -> format questions[]



  let mcqContent = content



  if ((type === 'multiple_choice' || type === 'mcq') && !content.questions && content.options) {



    const opts = content.options



    const ans = content.answer



    const answerIndex = typeof ans === 'number' ? ans : opts.indexOf(ans)



    mcqContent = {



      ...content,



      questions: [{



        text: content.question || exercise.title,



        question: content.question || exercise.title,



        svg: content.svg || null,



        options: opts,



        answer: answerIndex >= 0 ? answerIndex : 0



      }]



    }



  }



  // Normaliser answer texte->index dans questions[] existants



  if ((type === 'multiple_choice' || type === 'mcq') && mcqContent.questions) {



    mcqContent = {



      ...mcqContent,



      questions: mcqContent.questions.map((q: any) => {



        if (typeof q.answer === 'string') {



          const idx = (q.options || []).indexOf(q.answer)



          return { ...q, answer: idx >= 0 ? idx : 0 }



        }



        return q



      })



    }



  }



  if (type === 'multiple_choice' || type === 'mcq') {



    return <MCQ title={exercise.title} instructions={exercise.instructions} content={mcqContent} subject={(exercise as any).subject} onComplete={onComplete} onBack={onBack} />



  }







  if (type === 'handwriting') {



    return <Handwriting title={exercise.title} instructions={exercise.instructions} content={content} isFrench={isFrench} onComplete={onComplete} onBack={onBack} />



  }

  if (type === 'dictation') {
    return <Dictation title={exercise.title} instructions={exercise.instructions} content={content} onComplete={onComplete} onBack={onBack} />
  }

  if (type === 'written_response') {
    return <WrittenResponse title={exercise.title} instructions={exercise.instructions} content={content} isFrench={isFrench} onComplete={onComplete} onBack={onBack} />
  }







  if (type === 'fill_in') {



    return <FillIn title={exercise.title} instructions={exercise.instructions} content={content} isFrench={isFrench} onComplete={onComplete} onBack={onBack} />



  }







  if (type === 'match_pairs') {



    return (



      shell(report => <MatchPairs content={content} onComplete={report} />)



    )



  }







  if (type === 'sentence_order') {



    return (



      shell(report => <SentenceOrder content={content} onComplete={report} />)



    )



  }







  if (type === 'true_false') {



    return (



      shell(report => <TrueFalse content={content} onComplete={report} />)



    )



  }







  if (type === 'clock_reading') {



    return (



      shell(report => <ClockReading content={content} onComplete={report} />)



    )



  }



  if (type === 'geometry') {



    return (



      shell(report => <Geometry content={content} onComplete={report} />)



    )



  }







  if (type === 'venn_diagram') {



    return (



      shell(report => <VennDiagram content={content} onComplete={report} />)



    )



  }







  if (type === 'number_line') {



    return (



      shell(report => <NumberLine content={content} onComplete={report} />)



    )



  }











  // Fallback intelligent selon contenu



  if (content.pairs) {



    return (



      shell(report => <MatchPairs content={content} onComplete={report} />)



    )



  }



  if (content.words && content.answer) {



    return (



      shell(report => <SentenceOrder content={content} onComplete={report} />)



    )



  }



  if (content.statement !== undefined) {



    return (



      shell(report => <TrueFalse content={content} onComplete={report} />)



    )



  }

  // Fallback MCQ — questions[] sans champ type (seeders NLC/Vocational/Social)
  if (content.questions && Array.isArray(content.questions)) {
    const fallbackMcq = {
      ...content,
      questions: content.questions.map((q: any) => {
        if (typeof q.answer === 'string') {
          const idx = (q.options || []).indexOf(q.answer)
          return { ...q, answer: idx >= 0 ? idx : 0 }
        }
        return q
      })
    }
    return <MCQ title={exercise.title} instructions={exercise.instructions} content={fallbackMcq} subject={(exercise as any).subject} onComplete={onComplete} onBack={onBack} />
  }

  // Fallback FillIn — sentence/answer sans champ type
  if (content.sentence !== undefined && content.answer !== undefined) {
    return <FillIn title={exercise.title} instructions={exercise.instructions} content={content} isFrench={isFrench} onComplete={onComplete} onBack={onBack} />
  }







  return (



    <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title} exerciseId={exercise.id} instructions={exercise.instructions} isFrench={isFrench}>



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
