#!/usr/bin/env node
// Génère le pack de voix commun de Mama Judi avec ElevenLabs.
//
//   ELEVENLABS_API_KEY=...  ELEVENLABS_VOICE_ID=...  node scripts/generate-mama-voice.mjs [--force]
//
// Entrée  : docs/voice/mama-judi-lines.json (répliques FR/EN, plusieurs variantes)
// Sortie  : public/sounds/mama/v2/{en,fr}/{moment}_{n}.mp3 + public/sounds/mama/v2/manifest.json
// La clé n'est jamais écrite dans un fichier. Les MP3 existants sont conservés
// (pas de nouvelle facturation) sauf avec --force.
import { mkdir, readFile, writeFile, access } from 'node:fs/promises'
import { dirname, join } from 'node:path'
import { fileURLToPath } from 'node:url'

const root = join(dirname(fileURLToPath(import.meta.url)), '..')
// Espaces ou guillemets collés par erreur autour des valeurs : ignorés.
const clean = value => (value ?? '').trim().replace(/^["']|["']$/g, '')
const apiKey = clean(process.env.ELEVENLABS_API_KEY)
const voiceId = clean(process.env.ELEVENLABS_VOICE_ID)
const modelId = process.env.ELEVENLABS_MODEL || 'eleven_multilingual_v2'
const force = process.argv.includes('--force')

if (!apiKey || !voiceId) {
  console.error('ELEVENLABS_API_KEY et ELEVENLABS_VOICE_ID sont requis (variables d\'environnement).')
  process.exit(1)
}

// Vérifie la voix avant toute génération (et toute facturation).
const check = await fetch(`https://api.elevenlabs.io/v1/voices/${encodeURIComponent(voiceId)}`, { headers: { 'xi-api-key': apiKey } })
if (check.status === 404 || check.status === 400) {
  console.error([
    `Voix introuvable (HTTP ${check.status}) pour l'identifiant « ${voiceId} ».`,
    "- Copie l'ID depuis Voix > « … » > Copier l'ID de la voix (environ 20 caractères).",
    "- Une voix de la Voice Library doit d'abord être ajoutée à « Mes voix ».",
  ].join('\n'))
  process.exitCode = 1
} else if (!check.ok) {
  // Clé limitée à Text to Speech : la vérification n'est pas permise, on génère directement.
  console.log(`Vérification de la voix impossible (HTTP ${check.status}), génération quand même.`)
  await generate()
} else {
  console.log(`Voix trouvée : ${(await check.json()).name}`)
  await generate()
}

async function generate() {
const lines = JSON.parse(await readFile(join(root, 'docs/voice/mama-judi-lines.json'), 'utf-8'))
const outDir = join(root, 'public/sounds/mama/v2')
const manifest = { voice_id: voiceId, model_id: modelId, generated_at: new Date().toISOString(), clips: {} }
const exists = path => access(path).then(() => true, () => false)

let created = 0
let kept = 0
for (const [lang, events] of Object.entries(lines)) {
  if (lang.startsWith('_')) continue
  manifest.clips[lang] = {}
  await mkdir(join(outDir, lang), { recursive: true })
  for (const [event, texts] of Object.entries(events)) {
    manifest.clips[lang][event] = []
    for (const [i, text] of texts.entries()) {
      const file = `${event}_${i + 1}.mp3`
      const path = join(outDir, lang, file)
      manifest.clips[lang][event].push({ file: `${lang}/${file}`, text })
      if (!force && await exists(path)) { kept++; continue }
      const res = await fetch(`https://api.elevenlabs.io/v1/text-to-speech/${voiceId}?output_format=mp3_44100_128`, {
        method: 'POST',
        headers: { 'xi-api-key': apiKey, 'Content-Type': 'application/json', Accept: 'audio/mpeg' },
        body: JSON.stringify({
          text,
          model_id: modelId,
          voice_settings: { stability: 0.5, similarity_boost: 0.75, style: 0.3, use_speaker_boost: true },
        }),
      })
      if (!res.ok) {
        console.error(`Échec ${lang}/${file} : HTTP ${res.status} ${await res.text()}`)
        process.exitCode = 1
        return
      }
      await writeFile(path, Buffer.from(await res.arrayBuffer()))
      created++
      console.log(`✓ ${lang}/${file}  « ${text} »`)
      await new Promise(r => setTimeout(r, 400))
    }
  }
}

await writeFile(join(outDir, 'manifest.json'), JSON.stringify(manifest, null, 2) + '\n')
console.log(`\nTerminé : ${created} fichier(s) créé(s), ${kept} conservé(s). Inventaire : public/sounds/mama/v2/manifest.json`)
}
