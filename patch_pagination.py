# patch_pagination.py -- Lazy loading exercices -- charge a la demande
HOME = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildHome.tsx"

with open(HOME, "r", encoding="utf-8") as f:
    c = f.read()

# 1. Ajouter state page + hasMore apres exercises state
old_state = "  const [exercises, setExercises] = useState<(Exercise & { subject: string })[]>([])"
new_state = (
    "  const [exercises, setExercises] = useState<(Exercise & { subject: string })[]>([])\n"
    "  const [exPage, setExPage] = useState(1)\n"
    "  const [hasMore, setHasMore] = useState(false)\n"
    "  const [loadingMore, setLoadingMore] = useState(false)"
)
c = c.replace(old_state, new_state)

# 2. Remplacer le useEffect de chargement -- supprimer la boucle loadMore
old_load = (
    "  useEffect(() => {\n"
    "    if (!child.id || !child.level_id) return\n"
    "    // Charge la premiere page immediatement\n"
    "    getExercisesForChild(child.id, child.level_id!)\n"
    "      .then(first => {\n"
    "        setExercises(first)\n"
    "        setLoading(false)\n"
    "        // Charge les pages suivantes en arriere-plan\n"
    "        let page = 2\n"
    "        const loadMore = async () => {\n"
    "          const { exercises, hasMore } = await getMoreExercisesForChild(child.id, child.level_id!, page)\n"
    "          if (exercises.length > 0) {\n"
    "            setExercises(prev => [...prev, ...exercises])\n"
    "          }\n"
    "          if (hasMore) {\n"
    "            page++\n"
    "            setTimeout(loadMore, 500) // delai pour ne pas surcharger\n"
    "          }\n"
    "        }\n"
    "        setTimeout(loadMore, 1000) // demarre apres 1s\n"
    "      })\n"
    "      .catch(() => setLoading(false))\n"
    "  }, [child.id])"
)

new_load = (
    "  useEffect(() => {\n"
    "    if (!child.id || !child.level_id) return\n"
    "    // Charge la premiere page uniquement -- lazy loading pour les suivantes\n"
    "    getExercisesForChild(child.id, child.level_id!)\n"
    "      .then(first => {\n"
    "        setExercises(shuffleArray(first))\n"
    "        setLoading(false)\n"
    "      })\n"
    "      .catch(() => setLoading(false))\n"
    "    // Verifier s'il y a plus de pages\n"
    "    getMoreExercisesForChild(child.id, child.level_id!, 2)\n"
    "      .then(({ exercises: more, hasMore: hm }) => {\n"
    "        setHasMore(hm || more.length > 0)\n"
    "      })\n"
    "      .catch(() => {})\n"
    "  }, [child.id])\n"
    "\n"
    "  const loadMoreExercises = async () => {\n"
    "    if (loadingMore || !hasMore) return\n"
    "    setLoadingMore(true)\n"
    "    const nextPage = exPage + 1\n"
    "    const { exercises: more, hasMore: hm } = await getMoreExercisesForChild(child.id, child.level_id!, nextPage)\n"
    "    setExercises(prev => [...prev, ...shuffleArray(more)])\n"
    "    setExPage(nextPage)\n"
    "    setHasMore(hm)\n"
    "    setLoadingMore(false)\n"
    "  }"
)

if old_load in c:
    c = c.replace(old_load, new_load)
    print("OK useEffect pagination")
else:
    print("ERREUR : ancre useEffect introuvable")

# 3. Ajouter import shuffleArray depuis api.ts
# shuffleArray est deja defini dans api.ts mais pas exporte -- on l'ajoute localement
# Verifier si deja present
if "function shuffleArray" not in c:
    c = c.replace(
        "interface Props { child: Child; onLogout: () => void }",
        "function shuffleArray<T>(arr: T[]): T[] {\n"
        "  const a = [...arr]\n"
        "  for (let i = a.length - 1; i > 0; i--) {\n"
        "    const j = Math.floor(Math.random() * (i + 1));\n"
        "    [a[i], a[j]] = [a[j], a[i]]\n"
        "  }\n"
        "  return a\n"
        "}\n\n"
        "interface Props { child: Child; onLogout: () => void }"
    )
    print("OK shuffleArray ajoute")

# 4. Ajouter bouton "Load more" avant le bottom nav
old_nav = "      {/* Bottom nav — always visible */}"
new_nav = (
    "      {/* Load more button */}\n"
    "      {hasMore && !loading && (\n"
    "        <div style={{ padding: '0 16px 80px', textAlign: 'center' as const }}>\n"
    "          <button onClick={loadMoreExercises} disabled={loadingMore}\n"
    "            style={{ padding: '12px 28px', borderRadius: 20, border: 'none',\n"
    "              background: loadingMore ? '#D0C8B8' : '#1D6B2A',\n"
    "              color: 'white', fontSize: 14, fontWeight: 800,\n"
    "              cursor: loadingMore ? 'not-allowed' : 'pointer',\n"
    "              fontFamily: 'Nunito, sans-serif' }}>\n"
    "            {loadingMore ? 'Loading...' : '\u271A Load more exercises'}\n"
    "          </button>\n"
    "        </div>\n"
    "      )}\n\n"
    "      {/* Bottom nav \u2014 always visible */}"
)
c = c.replace(old_nav, new_nav)
print("OK bouton load more")

with open(HOME, "w", encoding="utf-8") as f:
    f.write(c)
print("Lance : npm run build")
