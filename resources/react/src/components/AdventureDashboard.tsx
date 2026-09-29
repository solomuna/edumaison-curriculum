import type { ReactNode } from 'react'
import type { Child } from '../types/child'
import type { Exercise } from '../types/exercise'
import MamaJudiPose from './MamaJudiPose'
import '../styles/adventure.css'

type AdventureExercise = Exercise & { subject: string }

interface Props {
  child: Child
  exercises: AdventureExercise[]
  completed: number[]
  loading: boolean
  desktop?: boolean
  notice?: ReactNode
  onStartExercise: (exercise: AdventureExercise) => void
  onOpenPaths: () => void
}

function childAvatar(child: Child): string | null {
  const avatar = (child as Child & { avatar?: string }).avatar
  if (!avatar) return null
  return avatar.startsWith('http') || avatar.startsWith('/') ? avatar : `/storage/${avatar}`
}

export default function AdventureDashboard({
  child,
  exercises,
  completed,
  loading,
  desktop = false,
  notice,
  onStartExercise,
  onOpenPaths,
}: Props) {
  const firstName = child.name.split(' ')[0]
  const nextExercise = exercises.find(exercise => !completed.includes(exercise.id))
  const completedToday = exercises.filter(exercise => completed.includes(exercise.id)).length
  const totalToday = exercises.length
  const avatar = childAvatar(child)

  return (
    <main className={`adventure-home adventure-map-home adventure-focus-home${desktop ? ' adventure-home--desktop' : ''}`}>
      <header className="adventure-focus-header">
        <div className="adventure-brand" aria-label="EduMaison">
          <span className="adventure-brand__mark" aria-hidden="true">E</span>
          <span>EDUMAISON</span>
        </div>

        <div className="adventure-greeting">
          <span className="adventure-greeting__avatar">
            {avatar ? <img src={avatar} alt="" /> : firstName.slice(0, 1).toUpperCase()}
          </span>
          <span>
            <strong>Hello {firstName}!</strong>
            <small>Let&apos;s do one mission together.</small>
          </span>
        </div>

        {!loading && totalToday > 0 && (
          <div className="adventure-today" aria-label={`${completedToday} of ${totalToday} activities completed today`}>
            <span aria-hidden="true">✓</span>
            <strong>{completedToday}/{totalToday}</strong>
            <small>Today</small>
          </div>
        )}
      </header>

      {notice && <div className="adventure-notice">{notice}</div>}

      <section className="adventure-map adventure-focus-map" aria-label="Today's EduMaison mission">
        <div className="adventure-map__scene" aria-hidden="true" />

        <div className="adventure-map__characters" aria-hidden="true">
          <MamaJudiPose pose="encourage" className="adventure-map-character adventure-map-character--judi" decorative />
        </div>

        <section className="adventure-mission adventure-focus-mission" aria-labelledby="mission-title">
          <div className="adventure-eyebrow">My next mission</div>
          {loading ? (
            <div className="adventure-loading" role="status">Preparing your mission...</div>
          ) : nextExercise ? (
            <>
              <div className="adventure-mission__subject">{nextExercise.subject}</div>
              <h1 id="mission-title">{nextExercise.title}</h1>
              <div className="adventure-mission__helper">
                <span className="adventure-mission__helper-avatar" aria-hidden="true">
                  <img src="/images/adventure/characters/mama-judi/encourage-v1.webp" alt="" />
                </span>
                <p>Mama Judi is here if you need help.</p>
              </div>
              <button className="adventure-primary" onClick={() => onStartExercise(nextExercise)}>
                Start my mission <span aria-hidden="true">→</span>
              </button>
            </>
          ) : (
            <>
              <div className="adventure-mission__subject">Today</div>
              <h1 id="mission-title">Mission complete!</h1>
              <p>Well done, {firstName}. You can now explore a family path.</p>
              <button className="adventure-primary" onClick={onOpenPaths}>
                Explore my paths <span aria-hidden="true">→</span>
              </button>
            </>
          )}
        </section>

        <div className="adventure-focus-guide" aria-hidden="true">
          <strong>One step at a time.</strong>
        </div>
      </section>
    </main>
  )
}
