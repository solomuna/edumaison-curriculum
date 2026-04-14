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



import { useEffect, useState } from 'react'



import Ardoise from './exercises/Ardoise'











interface Props {



  exercise: Exercise



  onComplete: (score: number) => void



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



    <div style={{



      background: 'var(--bg)', minHeight: '100vh',



      fontFamily: 'Nunito, system-ui, sans-serif'



    }}>



      {showArdoise && <Ardoise onClose={() => setShowArdoise(false)} />}



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



        <button



          onClick={() => {



            const text = instructions || title



            const lang = isFrench ? 'fr-FR' : 'en-GB'



            if ('speechSynthesis' in window) window.speechSynthesis.cancel()



            setTimeout(() => MamaJudi.speakLang(text, lang), 100)



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



    if (type === 'oral_drill') return



    const text = exercise.instructions || exercise.title



    if (!text) return



    const lang = isFrench ? 'fr-FR' : 'en-GB'



    // Annuler tout TTS en cours avant de lire



    if ('speechSynthesis' in window) window.speechSynthesis.cancel()



    setTimeout(() => MamaJudi.speakLang(text, lang), 800)



  }, [exercise.id])







  const handleBool = (correct: boolean) => onComplete(correct ? 1 : 0)







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







  if (type === 'fill_in') {



    return <FillIn title={exercise.title} instructions={exercise.instructions} content={content} isFrench={isFrench} onComplete={onComplete} onBack={onBack} />



  }







  if (type === 'match_pairs') {



    return (



      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title} instructions={exercise.instructions} isFrench={isFrench}>



        <MatchPairs content={content} onComplete={handleBool} />



      </ExerciseShell>



    )



  }







  if (type === 'sentence_order') {



    return (



      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title} instructions={exercise.instructions} isFrench={isFrench}>



        <SentenceOrder content={content} onComplete={handleBool} />



      </ExerciseShell>



    )



  }







  if (type === 'true_false') {



    return (



      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title} instructions={exercise.instructions} isFrench={isFrench}>



        <TrueFalse content={content} onComplete={handleBool} />



      </ExerciseShell>



    )



  }







  if (type === 'clock_reading') {



    return (



      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title} instructions={exercise.instructions} isFrench={isFrench}>



        <ClockReading content={content} onComplete={handleBool} />



      </ExerciseShell>



    )



  }



  if (type === 'geometry') {



    return (



      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title} instructions={exercise.instructions} isFrench={isFrench}>



        <Geometry content={content} onComplete={handleBool} />



      </ExerciseShell>



    )



  }







  if (type === 'venn_diagram') {



    return (



      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title} instructions={exercise.instructions} isFrench={isFrench}>



        <VennDiagram content={content} onComplete={handleBool} />



      </ExerciseShell>



    )



  }







  if (type === 'number_line') {



    return (



      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title} instructions={exercise.instructions} isFrench={isFrench}>



        <NumberLine content={content} onComplete={handleBool} />



      </ExerciseShell>



    )



  }











  // Fallback intelligent selon contenu



  if (content.pairs) {



    return (



      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title} instructions={exercise.instructions} isFrench={isFrench}>



        <MatchPairs content={content} onComplete={handleBool} />



      </ExerciseShell>



    )



  }



  if (content.words && content.answer) {



    return (



      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title} instructions={exercise.instructions} isFrench={isFrench}>



        <SentenceOrder content={content} onComplete={handleBool} />



      </ExerciseShell>



    )



  }



  if (content.statement !== undefined) {



    return (



      <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title} instructions={exercise.instructions} isFrench={isFrench}>



        <TrueFalse content={content} onComplete={handleBool} />



      </ExerciseShell>



    )



  }







  return (



    <ExerciseShell title={exercise.title} onBack={onBack} category={exercise.category} keyword={exercise.title} instructions={exercise.instructions} isFrench={isFrench}>



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




