export interface OralDrillItem {
  text: string
  audio_hint?: string
  color?: string
}

export interface OralDrillContent {
  type: 'oral_drill'
  items: OralDrillItem[]
  illustration?: string
  language?: 'en-GB' | 'fr-FR'
}

export type AttemptVerificationStatus =
  | 'auto_checked'
  | 'pending_review'
  | 'practice_only'
  | 'client_checked'
  | 'parent_verified'

export interface AttemptDetails {
  verification_status: AttemptVerificationStatus
  answers?: Record<string, unknown>
  evidence?: Record<string, unknown>
  duration_seconds?: number
}

export type ExerciseCompletionHandler = (score: number, details?: AttemptDetails) => void | Promise<void>

export interface DictationItem {
  text: string
  hint?: string
  audio_url?: string
}

export interface DictationContent {
  type: 'dictation'
  items: DictationItem[]
  language?: 'en-GB' | 'fr-FR'
  max_replays?: number
}

export interface WrittenResponseContent {
  type: 'written_response'
  prompt: string
  min_words?: number
  required_sentences?: number
  required_any_terms?: string[]
  accepted_words?: string[]
  min_recognized_ratio?: number
  max_unrecognized_words?: number
  checklist?: string[]
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
  prompts?: string[]
  word?: string
  letter?: string
  guide_style?: 'print' | 'upright_joint_script'
  practice_mode?: 'trace' | 'copy'
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
  | DictationContent
  | WrittenResponseContent

export interface Exercise {
  id: number
  title: string
  instructions: string
  category: string
  difficulty: string
  content: ExerciseContent
}
