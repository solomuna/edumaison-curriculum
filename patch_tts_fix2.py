# patch_tts_fix2.py -- Fix Chrome TTS v2 : voix explicite + onstart keepAlive
TSX = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"

with open(TSX, "r", encoding="utf-8") as f:
    c = f.read()

# Remplacer toute la fonction startVocal
old = (
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

new = (
    "  const startVocal = () => {\n"
    "    if (!('speechSynthesis' in window)) return\n"
    "    window.speechSynthesis.cancel()\n"
    "    const text = generateBriefText(brief)\n"
    "    // Selectionner voix francaise explicitement\n"
    "    const voices = window.speechSynthesis.getVoices()\n"
    "    const frVoice = voices.find(v => v.lang.startsWith('fr')) || null\n"
    "    const utter = new SpeechSynthesisUtterance(text)\n"
    "    if (frVoice) utter.voice = frVoice\n"
    "    utter.lang = 'fr-FR'\n"
    "    utter.rate = 0.85\n"
    "    utter.pitch = 1.0\n"
    "    // keepAlive demarre uniquement quand onstart confirme que ca parle\n"
    "    let ka: any = null\n"
    "    utter.onstart = () => {\n"
    "      ka = setInterval(() => {\n"
    "        if (!window.speechSynthesis.speaking) { clearInterval(ka); setSpeaking(false); return }\n"
    "        window.speechSynthesis.pause()\n"
    "        window.speechSynthesis.resume()\n"
    "      }, 14000)\n"
    "    }\n"
    "    utter.onend = () => { if (ka) clearInterval(ka); setSpeaking(false) }\n"
    "    utter.onerror = () => { if (ka) clearInterval(ka); setSpeaking(false) }\n"
    "    setSpeaking(true)\n"
    "    window.speechSynthesis.speak(utter)\n"
    "  }\n"
    "\n"
    "  const stopVocal = () => {\n"
    "    window.speechSynthesis.cancel()\n"
    "    setSpeaking(false)\n"
    "  }"
)

if old in c:
    c = c.replace(old, new)
    print("OK startVocal v2 applique")
else:
    print("ERREUR : ancre introuvable — cherche manuellement")

with open(TSX, "w", encoding="utf-8") as f:
    f.write(c)
print("Lance : npm run build")
