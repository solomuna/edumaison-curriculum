# patch_tap_to_hear.py -- Active le TTS sur "tap to hear again"
LOGIN = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildLogin.tsx"
HOME  = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildHome.tsx"

# ─── ChildLogin ──────────────────────────────────────────────────────────────
with open(LOGIN, "r", encoding="utf-8") as f:
    login = f.read()

# Ajouter fonction speak apres les imports
speak_fn = (
    "\nfunction speakText(text: string) {\n"
    "  if (!('speechSynthesis' in window)) return\n"
    "  window.speechSynthesis.cancel()\n"
    "  const u = new SpeechSynthesisUtterance(text)\n"
    "  u.lang = 'en-GB'\n"
    "  u.rate = 0.9\n"
    "  window.speechSynthesis.speak(u)\n"
    "}\n"
)

# Inserer apres les imports
import_end = "export default function ChildLogin"
if speak_fn not in login:
    login = login.replace(import_end, speak_fn + import_end)
    print("OK speakText ajoute dans ChildLogin")

# Rendre le bloc "tap to hear" cliquable sur la page principale
old_tap_main = (
    "        <div style={{ background: '#F5EDD8', borderRadius: 18, padding: '12px 20px', margin: '10px 18px', border: '2px solid #1D6B2A', textAlign: 'center', maxWidth: 320, width: '100%', boxSizing: 'border-box' }}>\n"
    "          <div style={{ fontSize: 16, fontWeight: 800, color: '#3D2B1F' }}>Welcome! Who is learning today?</div>\n"
    "          <div style={{ fontSize: 12, color: '#7A6050', marginTop: 3 }}>&#128266; tap to hear again</div>\n"
    "        </div>"
)

new_tap_main = (
    "        <div onClick={() => speakText('Welcome! Who is learning today?')}\n"
    "          style={{ background: '#F5EDD8', borderRadius: 18, padding: '12px 20px', margin: '10px 18px', border: '2px solid #1D6B2A', textAlign: 'center', maxWidth: 320, width: '100%', boxSizing: 'border-box', cursor: 'pointer' }}>\n"
    "          <div style={{ fontSize: 16, fontWeight: 800, color: '#3D2B1F' }}>Welcome! Who is learning today?</div>\n"
    "          <div style={{ fontSize: 12, color: '#7A6050', marginTop: 3 }}>&#128266; tap to hear again</div>\n"
    "        </div>"
)

if old_tap_main in login:
    login = login.replace(old_tap_main, new_tap_main)
    print("OK tap to hear login page principale")
else:
    print("ERREUR : ancre tap main introuvable")

# Rendre le bloc "tap to hear" cliquable sur la page PIN
old_tap_pin = (
    "        <div style={{ background: '#F5EDD8', borderRadius: 16, padding: '12px 24px', margin: '14px 0', textAlign: 'center', border: '2px solid #1D6B2A', maxWidth: 280 }}>\n"
    "          <div style={{ fontSize: 16, fontWeight: 800, color: '#3D2B1F' }}>Enter your secret PIN</div>\n"
    "          <div style={{ fontSize: 12, color: '#7A6050', marginTop: 3 }}>&#128266; tap to hear again</div>\n"
    "        </div>"
)

new_tap_pin = (
    "        <div onClick={() => speakText('Enter your secret PIN')}\n"
    "          style={{ background: '#F5EDD8', borderRadius: 16, padding: '12px 24px', margin: '14px 0', textAlign: 'center', border: '2px solid #1D6B2A', maxWidth: 280, cursor: 'pointer' }}>\n"
    "          <div style={{ fontSize: 16, fontWeight: 800, color: '#3D2B1F' }}>Enter your secret PIN</div>\n"
    "          <div style={{ fontSize: 12, color: '#7A6050', marginTop: 3 }}>&#128266; tap to hear again</div>\n"
    "        </div>"
)

if old_tap_pin in login:
    login = login.replace(old_tap_pin, new_tap_pin)
    print("OK tap to hear login PIN")
else:
    print("ERREUR : ancre tap PIN introuvable")

with open(LOGIN, "w", encoding="utf-8") as f:
    f.write(login)
print("OK ChildLogin.tsx")

# ─── ChildHome ───────────────────────────────────────────────────────────────
with open(HOME, "r", encoding="utf-8") as f:
    home = f.read()

# Rendre le "tap to hear again" cliquable dans le bloc message
old_tap_home = (
    "                <div style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 4 }}>{SPEAKER} tap to hear again</div>"
)

new_tap_home = (
    "                <div onClick={() => {\n"
    "                  if ('speechSynthesis' in window) {\n"
    "                    window.speechSynthesis.cancel()\n"
    "                    const u = new SpeechSynthesisUtterance(judiMsg)\n"
    "                    u.lang = 'en-GB'; u.rate = 0.9\n"
    "                    window.speechSynthesis.speak(u)\n"
    "                  }\n"
    "                }} style={{ fontSize: 11, color: 'var(--text-soft)', marginTop: 4, cursor: 'pointer' }}>\n"
    "                  {SPEAKER} tap to hear again\n"
    "                </div>"
)

if old_tap_home in home:
    home = home.replace(old_tap_home, new_tap_home)
    print("OK tap to hear ChildHome")
else:
    print("ERREUR : ancre tap home introuvable")

with open(HOME, "w", encoding="utf-8") as f:
    f.write(home)
print("OK ChildHome.tsx")
print("Lance : npm run build")
