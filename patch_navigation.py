# patch_navigation.py -- Back button + quit popup + tab persistence
HOME  = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildHome.tsx"
LOGIN = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildLogin.tsx"

# ─────────────────────────────────────────────────────────────────────────────
#  1. ChildHome.tsx
# ─────────────────────────────────────────────────────────────────────────────
with open(HOME, "r", encoding="utf-8") as f:
    home = f.read()

# 1-a. Supprimer justArrivedAtHome ref (inutile, cause le bug des 3 presses)
home = home.replace(
    "  const justArrivedAtHome = useRef(false)\n",
    ""
)

# 1-b. Simplifier handleBack -- supprimer la logique justArrivedAtHome
old_back = (
    "    const handleBack = (_e: PopStateEvent) => {\n"
    "      if (activeExam) { setActiveExam(null); pushNav(); return }\n"
    "      if (active) { setActive(null); pushNav(); return }\n"
    "      if (tab !== 'home') {\n"
    "        justArrivedAtHome.current = true\n"
    "        setTab('home')\n"
    "        setOpenSubjectName(null)\n"
    "        pushNav()\n"
    "        return\n"
    "      }\n"
    "      // Already on Home\n"
    "      if (justArrivedAtHome.current) {\n"
    "        justArrivedAtHome.current = false\n"
    "        pushNav()\n"
    "        return\n"
    "      }\n"
    "      setShowQuitDialog(true)\n"
    "      pushNav()\n"
    "    }"
)

new_back = (
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
    "    }"
)

if old_back in home:
    home = home.replace(old_back, new_back)
    print("OK handleBack simplifie")
else:
    print("ERREUR : ancre handleBack introuvable")

# 1-c. Ajouter persistence localStorage du tab
# Apres le premier useEffect (pushState sentinel), ajouter sauvegarde/restauration tab
old_sentinel = (
    "  useEffect(() => {\n"
    "    window.history.pushState({ sentinel: true }, '')\n"
    "  }, [])\n"
)

new_sentinel = (
    "  useEffect(() => {\n"
    "    // Restaurer le tab actif apres actualisation\n"
    "    const saved = localStorage.getItem('edumaison_tab_' + child.id)\n"
    "    if (saved && ['home','subjects','progress','profile','bulletin'].includes(saved)) {\n"
    "      setTab(saved as Tab)\n"
    "    }\n"
    "    window.history.pushState({ sentinel: true }, '')\n"
    "  }, [])\n"
    "\n"
    "  // Sauvegarder le tab actif a chaque changement\n"
    "  useEffect(() => {\n"
    "    localStorage.setItem('edumaison_tab_' + child.id, tab)\n"
    "  }, [tab, child.id])\n"
)

if old_sentinel in home:
    home = home.replace(old_sentinel, new_sentinel)
    print("OK tab persistence ajoute")
else:
    print("ERREUR : ancre sentinel introuvable")

with open(HOME, "w", encoding="utf-8") as f:
    f.write(home)
print("OK ChildHome.tsx")

# ─────────────────────────────────────────────────────────────────────────────
#  2. ChildLogin.tsx -- back button + quit dialog
# ─────────────────────────────────────────────────────────────────────────────
with open(LOGIN, "r", encoding="utf-8") as f:
    login = f.read()

# 2-a. Ajouter showQuitLogin state apres les autres states
old_states = (
    "  const [loading, setLoading] = useState(true)\n"
    "  const [checking, setChecking] = useState(false)\n"
)

new_states = (
    "  const [loading, setLoading] = useState(true)\n"
    "  const [checking, setChecking] = useState(false)\n"
    "  const [showQuitLogin, setShowQuitLogin] = useState(false)\n"
)

login = login.replace(old_states, new_states)

# 2-b. Ajouter useEffect popstate apres le useEffect getChildren
old_children_effect = (
    "  useEffect(() => { getChildren().then(setChildren).finally(() => setLoading(false)) }, [])\n"
)

new_children_effect = (
    "  useEffect(() => { getChildren().then(setChildren).finally(() => setLoading(false)) }, [])\n"
    "\n"
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

login = login.replace(old_children_effect, new_children_effect)

# 2-c. Ajouter le dialog quit avant la fermeture du return principal
# Le composant se termine par </div>\n    <TreeLine />\n  </div>\n  )\n}
old_end = (
    "      <TreeLine />\n"
    "    </div>\n"
    "  )\n"
    "}"
)

new_end = (
    "      <TreeLine />\n"
    "      {showQuitLogin && (\n"
    "        <div style={{ position: 'fixed', inset: 0, zIndex: 9999,\n"
    "          background: 'rgba(0,0,0,0.55)',\n"
    "          display: 'flex', alignItems: 'center', justifyContent: 'center',\n"
    "          padding: '0 32px' }}>\n"
    "          <div style={{ background: '#F5EDD8', borderRadius: 24, padding: '28px 24px',\n"
    "            width: '100%', maxWidth: 360, textAlign: 'center',\n"
    "            boxShadow: '0 8px 40px rgba(0,0,0,0.25)' }}>\n"
    "            <div style={{ fontSize: 48, marginBottom: 12 }}>\U0001f4da</div>\n"
    "            <div style={{ fontSize: 18, fontWeight: 900, color: '#3D2B1F', marginBottom: 8 }}>Quit EduMaison?</div>\n"
    "            <div style={{ fontSize: 14, color: '#7A6050', marginBottom: 24 }}>See you soon!</div>\n"
    "            <div style={{ display: 'flex', gap: 12 }}>\n"
    "              <button onClick={() => setShowQuitLogin(false)}\n"
    "                style={{ flex: 1, padding: '12px', borderRadius: 14, border: '2px solid #D0C8B8',\n"
    "                  background: '#fff', fontSize: 15, fontWeight: 800, color: '#3D2B1F', cursor: 'pointer' }}>\n"
    "                Stay\n"
    "              </button>\n"
    "              <button onClick={() => { setShowQuitLogin(false); window.history.back() }}\n"
    "                style={{ flex: 1, padding: '12px', borderRadius: 14, border: 'none',\n"
    "                  background: '#1D6B2A', fontSize: 15, fontWeight: 800, color: 'white', cursor: 'pointer' }}>\n"
    "                Quit\n"
    "              </button>\n"
    "            </div>\n"
    "          </div>\n"
    "        </div>\n"
    "      )}\n"
    "    </div>\n"
    "  )\n"
    "}"
)

if old_end in login:
    login = login.replace(old_end, new_end)
    print("OK quit dialog login ajoute")
else:
    print("ERREUR : ancre fin ChildLogin introuvable")

with open(LOGIN, "w", encoding="utf-8") as f:
    f.write(login)
print("OK ChildLogin.tsx")
print("Lance : npm run build")
