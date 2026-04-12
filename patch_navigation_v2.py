# patch_navigation_v2.py
HOME  = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildHome.tsx"
LOGIN = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildLogin.tsx"

# ─────────────────────────────────────────────────────────────────────────────
#  1. ChildHome.tsx
# ─────────────────────────────────────────────────────────────────────────────
with open(HOME, "r", encoding="utf-8") as f:
    home = f.read()

# 1-a. Ajouter refs apres les useRef existants
old_ref = "  const justArrivedAtHome = useRef(false)\n" if "  const justArrivedAtHome = useRef(false)\n" in home else None

# Ajouter refs apres streakData
old_streak = (
    "  const streakData = useStreak(child)\n"
    "  const { isOnline, syncPending } = useOfflineSync(child)\n"
)
new_streak = (
    "  const streakData = useStreak(child)\n"
    "  const { isOnline, syncPending } = useOfflineSync(child)\n"
    "\n"
    "  // Refs pour eviter stale closure dans popstate handler\n"
    "  const activeRef = useRef(active)\n"
    "  const activeExamRef = useRef(activeExam)\n"
    "  const tabRef = useRef(tab)\n"
    "  const activeDuelIdRef = useRef(activeDuelId)\n"
    "  useEffect(() => { activeRef.current = active }, [active])\n"
    "  useEffect(() => { activeExamRef.current = activeExam }, [activeExam])\n"
    "  useEffect(() => { tabRef.current = tab }, [tab])\n"
    "  useEffect(() => { activeDuelIdRef.current = activeDuelId }, [activeDuelId])\n"
)

if old_streak in home:
    home = home.replace(old_streak, new_streak)
    print("OK refs ajoutes")
else:
    print("ERREUR : ancre streakData introuvable")

# 1-b. Remplacer handleBack useEffect par version avec refs (une seule inscription)
old_back_effect = (
    "  useEffect(() => {\n"
    "    const handleBack = (_e: PopStateEvent) => {\n"
    "      if (activeExam) { setActiveExam(null); pushNav(); return }\n"
    "      if (activeDuelId) { pushNav(); return }\n"
    "      if (active) { setActive(null); pushNav(); return }\n"
    "      if (tab !== 'home') {\n"
    "        setTab('home')\n"
    "        setOpenSubjectName(null)\n"
    "        pushNav()\n"
    "        return\n"
    "      }\n"
    "      setShowQuitDialog(true)\n"
    "      pushNav()\n"
    "    }\n"
    "    window.addEventListener('popstate', handleBack)\n"
    "    return () => window.removeEventListener('popstate', handleBack)\n"
    "  }, [activeExam, active, tab])"
)

new_back_effect = (
    "  useEffect(() => {\n"
    "    const handleBack = (_e: PopStateEvent) => {\n"
    "      if (activeExamRef.current) { setActiveExam(null); pushNav(); return }\n"
    "      if (activeDuelIdRef.current) { pushNav(); return }\n"
    "      if (activeRef.current) { setActive(null); pushNav(); return }\n"
    "      if (tabRef.current !== 'home') {\n"
    "        setTab('home')\n"
    "        setOpenSubjectName(null)\n"
    "        pushNav()\n"
    "        return\n"
    "      }\n"
    "      setShowQuitDialog(true)\n"
    "      pushNav()\n"
    "    }\n"
    "    window.addEventListener('popstate', handleBack)\n"
    "    return () => window.removeEventListener('popstate', handleBack)\n"
    "  }, [])  // Une seule inscription -- refs maintiennent les valeurs courantes"
)

if old_back_effect in home:
    home = home.replace(old_back_effect, new_back_effect)
    print("OK handleBack refs")
else:
    print("ERREUR : ancre handleBack introuvable")

# 1-c. Quit button -- onLogout() au lieu de history.back() (evite boucle popstate)
home = home.replace(
    "<button onClick={() => { setShowQuitDialog(false); window.history.back() }}",
    "<button onClick={() => { setShowQuitDialog(false); onLogout() }}"
)
print("OK quit button -> onLogout()")

with open(HOME, "w", encoding="utf-8") as f:
    f.write(home)
print("OK ChildHome.tsx")

# ─────────────────────────────────────────────────────────────────────────────
#  2. ChildLogin.tsx -- fix popstate avec ref
# ─────────────────────────────────────────────────────────────────────────────
with open(LOGIN, "r", encoding="utf-8") as f:
    login = f.read()

# 2-a. Ajouter import useRef si absent
if "useRef" not in login:
    login = login.replace(
        "import { useState, useEffect }",
        "import { useState, useEffect, useRef }"
    )
    print("OK useRef importe")

# 2-b. Ajouter selectedRef apres showQuitLogin state
old_quit_state = "  const [showQuitLogin, setShowQuitLogin] = useState(false)\n"
new_quit_state = (
    "  const [showQuitLogin, setShowQuitLogin] = useState(false)\n"
    "  const selectedRef = useRef(selected)\n"
    "  useEffect(() => { selectedRef.current = selected }, [selected])\n"
)
login = login.replace(old_quit_state, new_quit_state)

# 2-c. Remplacer le useEffect popstate par version avec ref ([] deps)
old_popstate = (
    "  // Back button -- retour ou popup quitter\n"
    "  useEffect(() => {\n"
    "    window.history.pushState({}, '')\n"
    "    const handler = () => {\n"
    "      if (selected) {\n"
    "        setSelected(null); setPin(''); setError('')\n"
    "        window.history.pushState({}, '')\n"
    "      } else {\n"
    "        setShowQuitLogin(true)\n"
    "        window.history.pushState({}, '')\n"
    "      }\n"
    "    }\n"
    "    window.addEventListener('popstate', handler)\n"
    "    return () => window.removeEventListener('popstate', handler)\n"
    "  }, [selected])\n"
)

new_popstate = (
    "  // Back button -- ref evite stale closure, une seule inscription\n"
    "  useEffect(() => {\n"
    "    window.history.pushState({}, '')\n"
    "    const handler = () => {\n"
    "      if (selectedRef.current) {\n"
    "        setSelected(null); setPin(''); setError('')\n"
    "        window.history.pushState({}, '')\n"
    "      } else {\n"
    "        setShowQuitLogin(true)\n"
    "        window.history.pushState({}, '')\n"
    "      }\n"
    "    }\n"
    "    window.addEventListener('popstate', handler)\n"
    "    return () => window.removeEventListener('popstate', handler)\n"
    "  }, [])\n"
)

if old_popstate in login:
    login = login.replace(old_popstate, new_popstate)
    print("OK popstate login ref")
else:
    print("ERREUR : ancre popstate login introuvable")

# 2-d. Quit button login -- window.close() pour fermer le PWA
login = login.replace(
    "<button onClick={() => { setShowQuitLogin(false); window.history.back() }}",
    "<button onClick={() => { setShowQuitLogin(false); window.close() }}"
)
print("OK quit login -> window.close()")

with open(LOGIN, "w", encoding="utf-8") as f:
    f.write(login)
print("OK ChildLogin.tsx")
print("Lance : npm run build")
