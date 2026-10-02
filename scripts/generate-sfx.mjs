#!/usr/bin/env node
// Génère les effets sonores des leçons (style Duolingo : courts, vifs, ludiques)
// avec l'API « Sound Effects » d'ElevenLabs.
//
// 1. Candidats (3 variantes par son, à écouter) :
//      ELEVENLABS_API_KEY=...  node scripts/generate-sfx.mjs [--only tap,pop] [--force]
//    -> storage/app/sfx-candidates/{son}_{n}.mp3   (ignoré par git)
// 2. Choix (copie les variantes retenues dans l'application, sans appel API) :
//      node scripts/generate-sfx.mjs --pick tap=2,pop=1,mic_on=3
//    -> public/sounds/fx/{son}.mp3
//
// La clé n'est jamais écrite dans un fichier. Les candidats existants sont
// conservés (pas de nouvelle facturation) sauf avec --force.
import { mkdir, writeFile, access, copyFile } from 'node:fs/promises'
import { dirname, join } from 'node:path'
import { fileURLToPath } from 'node:url'

const root = join(dirname(fileURLToPath(import.meta.url)), '..')
const candidatesDir = join(root, 'storage/app/sfx-candidates')
const fxDir = join(root, 'public/sounds/fx')
const VARIANTS = 3

// Descriptions en anglais (meilleurs résultats). Durées courtes : un son de
// geste doit finir avant le geste suivant.
const SOUNDS = {
  tap: { seconds: 0.5, text: "A single soft, satisfying button tap for a children's learning app: short rounded wooden tock with a tiny bubbly pop, clean, dry, no reverb." },
  pop: { seconds: 0.7, text: 'Cheerful bubbly pop with a quick rising sparkle, two matching cards snapping together in a playful mobile game, bright and clean.' },
  mic_on: { seconds: 0.7, text: "Friendly two-note rising chime on a soft marimba, signals 'I am listening' in a kids' app, short and clean." },
  mic_off: { seconds: 0.7, text: "Friendly two-note falling chime on a soft marimba, signals that recording stopped in a kids' app, short and clean." },
  tick: { seconds: 0.5, text: 'One tiny crisp high-pitched coin tick for a score counter in a mobile game, very short and clean.' },
  correct: { seconds: 1.0, text: 'Bright happy correct-answer ding for a language learning app, two quick ascending bell notes, sparkly and satisfying, short.' },
  wrong: { seconds: 0.9, text: "Gentle low 'oops' wrong-answer sound for a kids' learning app, soft two-note descending muted tone, friendly and never harsh, short." },
  perfect: { seconds: 2.0, text: "Joyful short lesson-complete fanfare for a kids' learning app, playful brass and bells with a sparkle at the end, triumphant." },
}

const args = process.argv.slice(2)
const argValue = name => { const i = args.indexOf(name); return i >= 0 ? args[i + 1] ?? '' : null }
const exists = path => access(path).then(() => true, () => false)

const pick = argValue('--pick')
if (pick !== null) {
  // --pick tap=2,pop=1 : aucune requête, simple copie des variantes retenues.
  for (const choice of pick.split(',').map(s => s.trim()).filter(Boolean)) {
    const [name, n] = choice.split('=')
    if (!SOUNDS[name] || !/^\d+$/.test(n ?? '')) { console.error(`Choix invalide : « ${choice} » (attendu : son=numéro)`); process.exitCode = 1; continue }
    const from = join(candidatesDir, `${name}_${n}.mp3`)
    if (!(await exists(from))) { console.error(`Candidat absent : storage/app/sfx-candidates/${name}_${n}.mp3`); process.exitCode = 1; continue }
    await copyFile(from, join(fxDir, `${name}.mp3`))
    console.log(`✓ public/sounds/fx/${name}.mp3  <-  ${name}_${n}.mp3`)
  }
} else {
  const clean = value => (value ?? '').trim().replace(/^["']|["']$/g, '')
  const apiKey = clean(process.env.ELEVENLABS_API_KEY)
  const force = args.includes('--force')
  const only = argValue('--only')
  const names = only ? only.split(',').map(s => s.trim()).filter(Boolean) : Object.keys(SOUNDS)
  const unknown = names.filter(name => !SOUNDS[name])
  if (!apiKey) {
    console.error("ELEVENLABS_API_KEY est requis (variable d'environnement).")
    process.exitCode = 1
  } else if (unknown.length) {
    console.error(`Sons inconnus : ${unknown.join(', ')}. Disponibles : ${Object.keys(SOUNDS).join(', ')}`)
    process.exitCode = 1
  } else {
    await mkdir(candidatesDir, { recursive: true })
    let created = 0
    let kept = 0
    generation: for (const name of names) {
      const { seconds, text } = SOUNDS[name]
      for (let n = 1; n <= VARIANTS; n++) {
        const path = join(candidatesDir, `${name}_${n}.mp3`)
        if (!force && await exists(path)) { kept++; continue }
        const res = await fetch('https://api.elevenlabs.io/v1/sound-generation?output_format=mp3_44100_128', {
          method: 'POST',
          headers: { 'xi-api-key': apiKey, 'Content-Type': 'application/json', Accept: 'audio/mpeg' },
          body: JSON.stringify({ text, duration_seconds: seconds, prompt_influence: 0.6 }),
        })
        if (!res.ok) {
          console.error(`Échec ${name}_${n} : HTTP ${res.status} ${await res.text()}`)
          process.exitCode = 1
          break generation
        }
        await writeFile(path, Buffer.from(await res.arrayBuffer()))
        created++
        console.log(`✓ ${name}_${n}.mp3`)
        await new Promise(r => setTimeout(r, 400))
      }
    }
    console.log(`\n${created} candidat(s) créé(s), ${kept} conservé(s) dans storage/app/sfx-candidates/.`)
    if (process.exitCode === 1) {
      console.log('Génération interrompue : corrige le problème ci-dessus puis relance (les fichiers déjà créés sont gardés).')
    } else {
      console.log('Écoute-les, puis : node scripts/generate-sfx.mjs --pick tap=1,pop=2,...')
    }
  }
}
