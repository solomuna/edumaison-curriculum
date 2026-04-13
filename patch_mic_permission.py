# patch_mic_permission.py -- Demande autorisation micro au chargement OralDrill
ORAL = r"C:\laragon\www\edumaison\resources\react\src\pages\child\exercises\OralDrill.tsx"

with open(ORAL, "r", encoding="utf-8") as f:
    c = f.read()

# Ajouter demande permission micro dans le useEffect initial
old_init = (
    "  useEffect(() => {\n"
    "    MamaJudi.speak(instructions)\n"
    "    setListened(new Array(items.length).fill(false))\n"
    "    // Verifier support Web Speech API\n"
    "    const SR = (window as any).SpeechRecognition || (window as any).webkitSpeechRecognition\n"
    "    setSpeechSupported(!!SR)\n"
    "  }, [])"
)

new_init = (
    "  useEffect(() => {\n"
    "    MamaJudi.speak(instructions)\n"
    "    setListened(new Array(items.length).fill(false))\n"
    "    // Verifier support Web Speech API\n"
    "    const SR = (window as any).SpeechRecognition || (window as any).webkitSpeechRecognition\n"
    "    setSpeechSupported(!!SR)\n"
    "    // Demander autorisation micro en avance pour eviter le blocage au premier enregistrement\n"
    "    if (SR && navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {\n"
    "      navigator.mediaDevices.getUserMedia({ audio: true })\n"
    "        .then(stream => {\n"
    "          // Autorisation obtenue -- on coupe le stream immediatement\n"
    "          stream.getTracks().forEach(t => t.stop())\n"
    "        })\n"
    "        .catch(() => {\n"
    "          // Autorisation refusee -- le fallback auto-eval sera utilise\n"
    "          setSpeechSupported(false)\n"
    "        })\n"
    "    }\n"
    "  }, [])"
)

if old_init in c:
    c = c.replace(old_init, new_init)
    print("OK permission micro ajoutee")
else:
    print("ERREUR : ancre useEffect introuvable")

with open(ORAL, "w", encoding="utf-8") as f:
    f.write(c)
print("Lance : npm run build")
