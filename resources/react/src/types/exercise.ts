export interface OralDrillItem {
  text: string
  audio_hint?: string
  color?: string
}

export interface OralDrillContent {
  type: 'oral_drill'
  items: OralDrillItem[]
}

export interface MCQQuestion {
  question: string
  options: string[]
  answer: string
}

export interface MCQContent {
  type: 'multiple_choice'
  questions: MCQQuestion[]
}

export interface HandwritingContent {
  type: 'handwriting'
  prompts: string[]
}

export interface FillInContent {
  type: 'fill_in'
  items: { prompt: string; answer: string }[]
}

export interface OralResponseContent {
  type: 'oral_response'
  prompt: string
  items: string[]
}

export type ExerciseContent =
  | OralDrillContent
  | MCQContent
  | HandwritingContent
  | FillInContent
  | OralResponseContent

export interface Exercise {
  id: number
  title: string
  instructions: string
  category: string
  difficulty: string
  content: ExerciseContent
}