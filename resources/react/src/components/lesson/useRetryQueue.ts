// File de rattrapage « à la Duolingo » : une question ratée revient en fin de
// leçon (au plus maxRetries fois). Seule la PREMIÈRE réponse compte pour la
// note : les reprises servent à apprendre, pas à gonfler le score.
import { useCallback, useState } from 'react'

interface State {
  queue: number[]
  pos: number
  attempts: Record<number, number>
  resolved: number[]
}

export function useRetryQueue(total: number, maxRetries = 2) {
  const [state, setState] = useState<State>(() => ({
    queue: Array.from({ length: total }, (_, i) => i),
    pos: 0,
    attempts: {},
    resolved: [],
  }))

  const current = state.queue[state.pos] ?? 0

  /** Enregistre la réponse à l'étape courante ; une erreur replanifie la question. */
  const record = useCallback((correct: boolean) => {
    setState(s => {
      const idx = s.queue[s.pos]
      const tries = (s.attempts[idx] ?? 0) + 1
      const retry = !correct && tries <= maxRetries
      return {
        ...s,
        attempts: { ...s.attempts, [idx]: tries },
        queue: retry ? [...s.queue, idx] : s.queue,
        resolved: !retry && !s.resolved.includes(idx) ? [...s.resolved, idx] : s.resolved,
      }
    })
  }, [maxRetries])

  const advance = useCallback(() => setState(s => ({ ...s, pos: Math.min(s.pos + 1, s.queue.length - 1) })), [])

  const reset = useCallback(() => setState({ queue: Array.from({ length: total }, (_, i) => i), pos: 0, attempts: {}, resolved: [] }), [total])

  return {
    /** Index de la question affichée (dans l'ordre d'origine). */
    current,
    /** Rang de l'étape (sert de clé pour relancer les animations). */
    step: state.pos,
    /** Vrai si la question a déjà été tentée : c'est une reprise. */
    isRetry: state.queue.indexOf(current) < state.pos,
    /** Vrai si c'est la dernière étape prévue (après enregistrement de la réponse). */
    isLastStep: state.pos >= state.queue.length - 1,
    /** Questions réglées (réussies ou reprises épuisées) : la barre ne recule jamais. */
    resolvedCount: state.resolved.length,
    record,
    advance,
    reset,
  }
}
