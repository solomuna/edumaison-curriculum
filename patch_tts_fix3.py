# patch_tts_fix3.py -- TTS mobile : phrases courtes, pas de keepAlive
TSX = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"

with open(TSX, "r", encoding="utf-8") as f:
    c = f.read()

old = (
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
    "  }"
)

new = (
    "  const startVocal = () => {\n"
    "    if (!('speechSynthesis' in window)) return\n"
    "    window.speechSynthesis.cancel()\n"
    "    const voices = window.speechSynthesis.getVoices()\n"
    "    const frVoice = voices.find(v => v.lang.startsWith('fr')) || null\n"
    "    // Decouper en phrases courtes -- Android ne supporte pas les utterances longues\n"
    "    const sentences = generateBriefText(brief)\n"
    "      .split('.')\n"
    "      .map(s => s.trim())\n"
    "      .filter(s => s.length > 1)\n"
    "    let idx = 0\n"
    "    setSpeaking(true)\n"
    "    const speakNext = () => {\n"
    "      if (idx >= sentences.length) { setSpeaking(false); return }\n"
    "      const utter = new SpeechSynthesisUtterance(sentences[idx] + '.')\n"
    "      if (frVoice) utter.voice = frVoice\n"
    "      utter.lang = 'fr-FR'\n"
    "      utter.rate = 0.85\n"
    "      utter.pitch = 1.0\n"
    "      utter.onend = () => { idx++; speakNext() }\n"
    "      utter.onerror = () => { idx++; speakNext() }\n"
    "      window.speechSynthesis.speak(utter)\n"
    "    }\n"
    "    speakNext()\n"
    "  }"
)

if old in c:
    c = c.replace(old, new)
    print("OK startVocal v3")
else:
    print("ERREUR : ancre introuvable")

with open(TSX, "w", encoding="utf-8") as f:
    f.write(c)
print("Lance : npm run build")
