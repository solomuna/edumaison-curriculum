const BASE = '/api'


// Shuffle array in place — Fisher-Yates
function shuffleArray<T>(arr: T[]): T[] {
  const a = [...arr]
  for (let i = a.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [a[i], a[j]] = [a[j], a[i]]
  }
  return a
}

export async function getChildren() {
  const res = await fetch(`${BASE}/children`)
  return res.json()
}

export async function loginChild(childId: number, pin: string) {
  const res = await fetch(`${BASE}/auth/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ child_id: childId, pin }),
  })
  return res.json()
}

export async function getExercisesForChild(childId: number, levelId: number) {
  const res = await fetch(`${BASE}/exercises/child/${childId}?level_id=${levelId}`)
  return res.json()
}

export async function saveAttempt(childId: number, exerciseId: number, score: number) {
  const res = await fetch(`${BASE}/exercises/attempt`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ child_id: childId, exercise_id: exerciseId, score }),
  })
  return res.json()
}

export async function getChildProfile(childId: number) {
  const res = await fetch(`${BASE}/child/${childId}/profile`)
  return res.json()
}

export async function getSubjects(levelId: number) {
  const res = await fetch(`${BASE}/subjects?level_id=${levelId}`)
  return res.json()
}

export async function getParentDashboard() {
  const res = await fetch(`${BASE}/parent/dashboard`)
  return res.json()
}

export async function getChildDetail(childId: number) {
  const res = await fetch(`${BASE}/parent/child/${childId}`)
  return res.json()
}
export async function getExercisesForSubject(childId: number, subjectId: number) {
  const res = await fetch(`${BASE}/exercises/child/${childId}/subject/${subjectId}`)
  return res.json()
}
