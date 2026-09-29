// TrueFalse.tsx — Moteur Vrai/Faux : choisir puis VÉRIFIER (bouton du cadre).
import { useState } from 'react'
import { useLesson, useLessonCheck, tapSound, type ReportResult } from '../../../components/lesson/LessonShell'

interface Props {
  content: any
  onComplete: ReportResult
}

export default function TrueFalse({ content, onComplete }: Props) {
  const { t, checked } = useLesson()
  const [chosen, setChosen] = useState<boolean | null>(null)
  const label = (v: boolean) => (v ? t('TRUE', 'VRAI') : t('FALSE', 'FAUX'))

  useLessonCheck(chosen !== null, () => {
    if (chosen === null) return
    const ok = chosen === content.answer
    onComplete(ok, { selected: chosen }, ok ? undefined : `${t('The correct answer is', 'La bonne réponse est')} ${label(!!content.answer)}.`)
  })

  const state = (v: boolean) => {
    if (!checked) return chosen === v ? ' is-selected' : ''
    if (v === content.answer) return ' is-right'
    return v === chosen ? ' is-wrong' : ' is-faded'
  }

  return (
    <div>
      <div className="lesson-statement">{t(`“${content.statement}”`, `« ${content.statement} »`)}</div>
      <div className="lesson-choices is-grid is-grid-2">
        {[true, false].map((v, i) => (
          <button key={String(v)} className={`lesson-choice lesson-choice--center${state(v)}`}
            onClick={() => { if (!checked) { tapSound(); setChosen(v) } }} disabled={checked}
            role="radio" aria-checked={chosen === v}>
            <span className="lesson-choice__key">{i + 1}</span>
            <span>{label(v)} {v ? '✓' : '✗'}</span>
          </button>
        ))}
      </div>
    </div>
  )
}
