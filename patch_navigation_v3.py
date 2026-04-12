# patch_navigation_v3.py
HOME  = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildHome.tsx"
LOGIN = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildLogin.tsx"

# ─────────────────────────────────────────────────────────────────────────────
#  1. ChildHome.tsx -- fix double quit dialog
# ─────────────────────────────────────────────────────────────────────────────
with open(HOME, "r", encoding="utf-8") as f:
    home = f.read()

# Ajouter showQuitRef + quittingRef apres les autres refs
old_refs = (
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

new_refs = (
    "  // Refs pour eviter stale closure dans popstate handler\n"
    "  const activeRef = useRef(active)\n"
    "  const activeExamRef = useRef(activeExam)\n"
    "  const tabRef = useRef(tab)\n"
    "  const activeDuelIdRef = useRef(activeDuelId)\n"
    "  const showQuitRef = useRef(false)\n"
    "  const quitPushCount = useRef(0)\n"
    "  useEffect(() => { activeRef.current = active }, [active])\n"
    "  useEffect(() => { activeExamRef.current = activeExam }, [activeExam])\n"
    "  useEffect(() => { tabRef.current = tab }, [tab])\n"
    "  useEffect(() => { activeDuelIdRef.current = activeDuelId }, [activeDuelId])\n"
    "  useEffect(() => { showQuitRef.current = showQuitDialog }, [showQuitDialog])\n"
)

if old_refs in home:
    home = home.replace(old_refs, new_refs)
    print("OK refs showQuit ajoutes")
else:
    print("ERREUR refs home")

# Mettre a jour handleBack pour bloquer double dialog + compter push
old_back = (
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

new_back = (
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
    "      // Ne pas afficher le dialog deux fois\n"
    "      if (showQuitRef.current) { pushNav(); return }\n"
    "      setShowQuitDialog(true)\n"
    "      pushNav()\n"
    "      quitPushCount.current++\n"
    "    }\n"
    "    window.addEventListener('popstate', handleBack)\n"
    "    return () => window.removeEventListener('popstate', handleBack)\n"
    "  }, [])"
)

if old_back in home:
    home = home.replace(old_back, new_back)
    print("OK handleBack home")
else:
    print("ERREUR handleBack home")

# Quit button : onLogout() deja applique, juste reset quitPushCount
home = home.replace(
    "<button onClick={() => { setShowQuitDialog(false); onLogout() }}",
    "<button onClick={() => { quitPushCount.current = 0; setShowQuitDialog(false); onLogout() }}"
)

with open(HOME, "w", encoding="utf-8") as f:
    f.write(home)
print("OK ChildHome.tsx")

# ─────────────────────────────────────────────────────────────────────────────
#  2. ChildLogin.tsx -- fix double dialog + quit propre
# ─────────────────────────────────────────────────────────────────────────────
with open(LOGIN, "r", encoding="utf-8") as f:
    login = f.read()

# Ajouter showQuitLoginRef + quittingRef + pushCountRef
old_refs2 = (
    "  const selectedRef = useRef(selected)\n"
    "  useEffect(() => { selectedRef.current = selected }, [selected])\n"
)

new_refs2 = (
    "  const selectedRef = useRef(selected)\n"
    "  const showQuitLoginRef = useRef(false)\n"
    "  const loginPushCount = useRef(0)\n"
    "  const quittingLogin = useRef(false)\n"
    "  useEffect(() => { selectedRef.current = selected }, [selected])\n"
    "  useEffect(() => { showQuitLoginRef.current = showQuitLogin }, [showQuitLogin])\n"
)

if old_refs2 in login:
    login = login.replace(old_refs2, new_refs2)
    print("OK refs login")
else:
    print("ERREUR refs login")

# Mettre a jour le handler popstate
old_handler = (
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

new_handler = (
    "  // Back button -- une seule inscription, refs pour valeurs courantes\n"
    "  useEffect(() => {\n"
    "    window.history.pushState({}, '')\n"
    "    loginPushCount.current = 1\n"
    "    const handler = () => {\n"
    "      if (quittingLogin.current) return\n"
    "      if (selectedRef.current) {\n"
    "        setSelected(null); setPin(''); setError('')\n"
    "        window.history.pushState({}, '')\n"
    "        loginPushCount.current++\n"
    "      } else if (!showQuitLoginRef.current) {\n"
    "        setShowQuitLogin(true)\n"
    "        window.history.pushState({}, '')\n"
    "        loginPushCount.current++\n"
    "      } else {\n"
    "        // Dialog deja ouvert -- bloquer le back\n"
    "        window.history.pushState({}, '')\n"
    "        loginPushCount.current++\n"
    "      }\n"
    "    }\n"
    "    window.addEventListener('popstate', handler)\n"
    "    return () => window.removeEventListener('popstate', handler)\n"
    "  }, [])\n"
)

if old_handler in login:
    login = login.replace(old_handler, new_handler)
    print("OK handler login")
else:
    print("ERREUR handler login")

# Quit button : go(-pushCount) pour vraiment quitter
login = login.replace(
    "<button onClick={() => { setShowQuitLogin(false); window.close() }}",
    "<button onClick={() => { quittingLogin.current = true; setShowQuitLogin(false); window.history.go(-loginPushCount.current) }}"
)

# Stay button : reset dialog sans pousser etat
login = login.replace(
    "<button onClick={() => setShowQuitLogin(false)}\n"
    "                style={{ flex: 1, padding: '12px', borderRadius: 14, border: '2px solid #D0C8B8',",
    "<button onClick={() => { setShowQuitLogin(false) }}\n"
    "                style={{ flex: 1, padding: '12px', borderRadius: 14, border: '2px solid #D0C8B8',"
)

with open(LOGIN, "w", encoding="utf-8") as f:
    f.write(login)
print("OK ChildLogin.tsx")
print("Lance : npm run build")
