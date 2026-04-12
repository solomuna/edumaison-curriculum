# patch_tts_fix.py -- Fix Chrome TTS bug dans BriefScreen
TSX = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"

with open(TSX, "r", encoding="utf-8") as f:
    c = f.read()

old = (
    "  const startVocal = () => {\n"
    "    if (!('speechSynthesis' in window)) return\n"
    "    window.speechSynthesis.cancel()\n"
    "    const sentences = generateBriefText(brief).split('.').map(s => s.trim()).filter(s => s.length > 0)\n"
    "    let idx = 0\n"
    "    setSpeaking(true)\n"
    "    const speakNext = () => {\n"
    "      if (idx >= sentences.length) { setSpeaking(false); return }\n"
    "      const utter = new SpeechSynthesisUtterance(sentences[idx] + '.')\n"
    "      utter.lang = 'fr-FR'\n"
    "      utter.rate = 0.88\n"
    "      utter.pitch = 1.05\n"
    "      utter.onend = () => { idx++; speakNext() }\n"
    "      utter.onerror = () => { idx++; speakNext() }\n"
    "      window.speechSynthesis.speak(utter)\n"
    "      idx++\n"
    "    }\n"
    "    setTimeout(speakNext, 150)\n"
    "  }"
)

new = (
    "  const startVocal = () => {\n"
    "    if (!('speechSynthesis' in window)) return\n"
    "    window.speechSynthesis.cancel()\n"
    "    const sentences = generateBriefText(brief).split('.').map(s => s.trim()).filter(s => s.length > 1)\n"
    "    let idx = 0\n"
    "    setSpeaking(true)\n"
    "    // Fix Chrome TTS -- keep synthesis alive toutes les 10s\n"
    "    const keepAlive = setInterval(() => {\n"
    "      if (window.speechSynthesis.speaking) {\n"
    "        window.speechSynthesis.pause()\n"
    "        window.speechSynthesis.resume()\n"
    "      }\n"
    "    }, 10000)\n"
    "    const speakNext = () => {\n"
    "      if (idx >= sentences.length) {\n"
    "        setSpeaking(false)\n"
    "        clearInterval(keepAlive)\n"
    "        return\n"
    "      }\n"
    "      const utter = new SpeechSynthesisUtterance(sentences[idx] + '.')\n"
    "      utter.lang = 'fr-FR'\n"
    "      utter.rate = 0.88\n"
    "      utter.pitch = 1.05\n"
    "      utter.onend = () => { idx++; speakNext() }\n"
    "      utter.onerror = () => { idx++; speakNext() }\n"
    "      window.speechSynthesis.speak(utter)\n"
    "    }\n"
    "    setTimeout(speakNext, 150)\n"
    "  }\n"
    "\n"
    "  const stopVocal = () => {\n"
    "    window.speechSynthesis.cancel()\n"
    "    setSpeaking(false)\n"
    "  }"
)

# Supprimer l'ancienne stopVocal qui sera en doublon
old_stop = "\n  const stopVocal = () => { window.speechSynthesis.cancel(); setSpeaking(false) }"

if old in c:
    c = c.replace(old, new)
    print("OK startVocal corrige")
else:
    print("ERREUR : ancre startVocal introuvable")

if old_stop in c:
    c = c.replace(old_stop, "")
    print("OK doublon stopVocal supprime")

with open(TSX, "w", encoding="utf-8") as f:
    f.write(c)
print("Termine. Lance npm run build")
