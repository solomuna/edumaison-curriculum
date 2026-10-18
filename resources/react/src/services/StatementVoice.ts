// Énoncés lus par Mama Judi : fichiers préparés par le serveur (ElevenLabs),
// partagés par toutes les familles. Si un morceau manque, ou ne correspond pas
// exactement au texte affiché, l'énoncé est lu par la voix du téléphone.
import { MamaJudi } from './MamaJudi'
import { SoundService } from './SoundService'

export interface StatementPart { key: string; text: string }
interface Manifest { language: string; segments: Record<string, { text: string; url: string | null }> }

const manifests = new Map<number, Promise<Manifest | null>>()
const wait = <T,>(ms: number, value: T) => new Promise<T>(resolve => setTimeout(() => resolve(value), ms))

/** Comparaison tolérante : sans ponctuation, casse ni forme du trou (« ___ » / « … »). */
const simplify = (text: string) => text
  .normalize('NFC')
  .replace(/_{2,}|…/g, ' ')
  .replace(/[^\p{L}\p{N}]+/gu, ' ')
  .trim()
  .toLowerCase()

/** Charge (une fois) les morceaux d'un exercice et précharge ceux qui sont prêts. */
export function loadStatements(exerciseId?: number): Promise<Manifest | null> {
  if (!exerciseId) return Promise.resolve(null)
  let manifest = manifests.get(exerciseId)
  if (!manifest) {
    manifest = fetch(`/api/exercises/${exerciseId}/speech`, { headers: { Accept: 'application/json' }, credentials: 'same-origin' })
      .then(r => (r.ok ? r.json() : null))
      .catch(() => null)
    manifests.set(exerciseId, manifest)
    void manifest.then(m => Object.values(m?.segments ?? {})
      .map(s => s.url)
      .filter((url): url is string => !!url)
      .reduce<Promise<void>>((p, url) => p.then(() => SoundService.load(url)), Promise.resolve()))
  }
  return manifest
}

/**
 * Lit les morceaux `parts` (consigne puis question…) avec la voix de Mama Judi,
 * à la suite. Sinon appelle `fallback` (voix du téléphone, texte complet).
 * Renvoie une fonction qui arrête la lecture.
 */
export function speakStatement(exerciseId: number | undefined, parts: StatementPart[], fallback: () => void): () => void {
  let cancelled = false
  let token = -1
  void (async () => {
    const manifest = await Promise.race([loadStatements(exerciseId), wait(1500, null)])
    if (cancelled) return
    const urls = parts.map(part => {
      const segment = manifest?.segments[part.key]
      return segment?.url && simplify(segment.text) === simplify(part.text) ? segment.url : null
    })
    if (!parts.length || urls.some(url => !url)) { fallback(); return }
    for (const url of urls as string[]) {
      await Promise.race([SoundService.load(url), wait(2500, undefined)])
      if (cancelled || (token !== -1 && SoundService.clipToken !== token)) return
      // Coupe la voix du téléphone qui lirait encore autre chose.
      if (token === -1) MamaJudi.stop()
      const ms = SoundService.durationMs(url)
      if (!ms || !SoundService.play(url, { exclusive: true })) {
        // Fichier illisible : le téléphone reprend tout l'énoncé.
        if (token === -1) fallback()
        return
      }
      token = SoundService.clipToken
      await wait(ms + 200, undefined)
      // Coupé entre-temps (verdict, autre lecture, sortie) : on n'enchaîne pas.
      if (cancelled || SoundService.clipToken !== token) return
    }
  })()
  return () => {
    cancelled = true
    if (token !== -1 && SoundService.clipToken === token) SoundService.stopClip()
  }
}
