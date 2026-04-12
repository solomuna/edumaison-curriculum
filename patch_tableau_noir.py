# patch_tableau_noir.py
# Tableau noir Mama Judi -- saisit une lecon -> EduMaison trouve les exercices
# Fichiers touches :
#   C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx
#   C:\laragon\www\edumaison-api\api\routes\mama.py

TSX  = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"
MAMA = r"C:\laragon\www\edumaison-api\api\routes\mama.py"

# ─────────────────────────────────────────────────────────────────────────────
#  1. MamaJudiApp.tsx
# ─────────────────────────────────────────────────────────────────────────────
with open(TSX, "r", encoding="utf-8") as f:
    tsx = f.read()

# 1-a. Screen type : ajouter 'tableau'
tsx = tsx.replace(
    "type Screen = 'home' | 'revision' | 'duel' | 'profile' | 'books'",
    "type Screen = 'home' | 'revision' | 'duel' | 'profile' | 'books' | 'tableau'"
)

# 1-b. Traductions FR
tsx = tsx.replace(
    "    today: \"Aujourd'hui\", active: 'actifs', attention: 'attention',",
    "    today: \"Aujourd'hui\", active: 'actifs', attention: 'attention',"
    "\n    nav_tableau: 'Tableau', tableau_search: 'Chercher',"
    " tableau_no_results: 'Aucun exercice trouv\u00e9',"
    " tableau_exercises_found: 'exercices trouv\u00e9s',"
)

# 1-c. Traductions EN
tsx = tsx.replace(
    "    today: 'Today', active: 'active', attention: 'attention',",
    "    today: 'Today', active: 'active', attention: 'attention',"
    "\n    nav_tableau: 'Blackboard', tableau_search: 'Search',"
    " tableau_no_results: 'No exercises found',"
    " tableau_exercises_found: 'exercises found',"
)

# 1-d. Composant TableauScreen
tableau_component = """
// \u2500\u2500 TABLEAU NOIR \u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500
function TableauScreen({ t, brief }: { t: typeof T['fr']; brief: Brief | null }) {
  const [query, setQuery] = useState('')
  const [results, setResults] = useState(null as any)
  const [loading, setLoading] = useState(false)
  const [total, setTotal] = useState(0)
  const levelId = brief && brief.children.length > 0 ? brief.children[0].level_id : null

  const search = async () => {
    const q = query.trim()
    if (q.length < 2) return
    setLoading(true)
    try {
      const base = '/api/mama/blackboard?q=' + encodeURIComponent(q)
      const url = levelId ? base + '&level_id=' + levelId : base
      const data = await fetch(url).then(r => r.json())
      setResults(data.results)
      setTotal(data.total)
    } finally {
      setLoading(false)
    }
  }

  const btnDisabled = loading || query.trim().length < 2
  const btnBg = btnDisabled ? P.border : P.brown
  const btnColor = btnDisabled ? P.soft : 'white'
  const showEmpty = results !== null && total === 0
  const showResults = results !== null && total > 0

  return (
    <div>
      <textarea
        value={query}
        onChange={e => setQuery(e.target.value)}
        placeholder="Ex: fractions, adjectives, human body, hygiene..."
        rows={3}
        style={{ width: '100%', padding: 12, borderRadius: 12, border: '1.5px solid ' + P.border,
          fontFamily: 'Nunito, sans-serif', fontSize: 14, background: P.card, color: P.dark,
          resize: 'none' as const, marginBottom: 12 }}
      />
      <button
        onClick={search}
        disabled={btnDisabled}
        style={{ width: '100%', padding: 13, borderRadius: 16, border: 'none', background: btnBg,
          color: btnColor, fontSize: 15, fontWeight: 900,
          cursor: btnDisabled ? 'not-allowed' : 'pointer',
          fontFamily: 'Nunito, sans-serif', marginBottom: 20 }}
      >
        {loading ? '...' : t.tableau_search}
      </button>
      {showEmpty && (
        <div style={{ textAlign: 'center' as const, padding: '30px 0', color: P.soft, fontSize: 14 }}>
          {t.tableau_no_results}
        </div>
      )}
      {showResults && (
        <div>
          <div style={{ fontSize: 12, fontWeight: 900, color: P.soft,
            textTransform: 'uppercase' as const, letterSpacing: 1, marginBottom: 12 }}>
            {total} {t.tableau_exercises_found}
          </div>
          {results.map((s: any) => (
            <div key={s.subject_id} style={{ marginBottom: 16 }}>
              <div style={{ fontSize: 13, fontWeight: 900, color: P.green, marginBottom: 8 }}>
                {s.subject_name}
              </div>
              {s.units.map((u: any) => (
                <div key={u.unit_id} style={{ background: P.white, borderRadius: 16,
                  padding: '12px 14px', marginBottom: 8, border: '1.5px solid ' + P.border }}>
                  <div style={{ fontSize: 14, fontWeight: 800, color: P.dark, marginBottom: 6 }}>
                    {u.unit_name}
                  </div>
                  {u.lessons.map((l: any) => (
                    <div key={l.lesson_id} style={{ display: 'flex', justifyContent: 'space-between',
                      alignItems: 'center', paddingTop: 6, borderTop: '1px solid ' + P.border }}>
                      <div style={{ fontSize: 12, color: P.soft }}>{l.lesson_name}</div>
                      <span style={{ background: P.green, color: 'white', borderRadius: 20,
                        padding: '2px 10px', fontSize: 11, fontWeight: 800, flexShrink: 0 }}>
                        {l.exercise_count} ex.
                      </span>
                    </div>
                  ))}
                </div>
              ))}
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
"""

tsx = tsx.replace("function ScreenContent(", tableau_component + "\nfunction ScreenContent(", 1)

# 1-e. ScreenContent — autoriser brief null pour tableau
tsx = tsx.replace(
    "  if (!brief && screen !== 'profile') return",
    "  if (!brief && screen !== 'profile' && screen !== 'tableau') return"
)

# 1-f. ScreenContent — ajouter cas tableau
tsx = tsx.replace(
    "  if (screen === 'books') return <BooksScreen t={t} />",
    "  if (screen === 'books') return <BooksScreen t={t} />\n"
    "  if (screen === 'tableau') return <TableauScreen t={t} brief={brief} />"
)

# 1-g. Nav mobile (label=t.nav_profile est unique au mobile)
tsx = tsx.replace(
    "    { id: 'profile' as Screen, icon: '\U0001f464', label: t.nav_profile },",
    "    { id: 'profile' as Screen, icon: '\U0001f464', label: t.nav_profile },\n"
    "    { id: 'tableau' as Screen, icon: '\U0001f4dd', label: t.nav_tableau },"
)

# 1-h. Nav desktop (label=t.profile est unique au desktop)
tsx = tsx.replace(
    "    { id: 'profile' as Screen, icon: '\U0001f464', label: t.profile },",
    "    { id: 'profile' as Screen, icon: '\U0001f464', label: t.profile },\n"
    "    { id: 'tableau' as Screen, icon: '\U0001f4dd', label: t.nav_tableau },"
)

with open(TSX, "w", encoding="utf-8") as f:
    f.write(tsx)
print("OK  MamaJudiApp.tsx")

# ─────────────────────────────────────────────────────────────────────────────
#  2. mama.py — endpoint /api/mama/blackboard
# ─────────────────────────────────────────────────────────────────────────────
with open(MAMA, "r", encoding="utf-8") as f:
    mama = f.read()

blackboard = '''

@router.get("/mama/blackboard")
def mama_blackboard(q: str, level_id: int = None, db: Session = Depends(get_db)):
    """
    Tableau noir -- Mama Judi saisit un sujet de lecon,
    EduMaison retourne les exercices correspondants groupes par matiere/unite.
    """
    if not q or len(q.strip()) < 2:
        return {"results": [], "total": 0, "query": q}

    like = "%" + q.strip().lower() + "%"
    params = {"q": like}

    # Construction SQL avec filtre niveau optionnel
    level_clause = ""
    if level_id:
        level_clause = " AND s.level_id = :level_id"
        params["level_id"] = level_id

    sql = (
        "SELECT s.id AS subject_id, s.name AS subject_name,"
        " u.id AS unit_id, u.name AS unit_name,"
        " l.id AS lesson_id, l.name AS lesson_name,"
        " COUNT(e.id) AS exercise_count"
        " FROM lessons l"
        " JOIN units u ON l.unit_id = u.id"
        " JOIN integrated_themes it ON u.integrated_theme_id = it.id"
        " JOIN subjects s ON it.subject_id = s.id"
        " LEFT JOIN exercises e ON e.lesson_id = l.id"
        " WHERE (LOWER(l.name) LIKE :q OR LOWER(u.name) LIKE :q OR LOWER(s.name) LIKE :q)"
        + level_clause +
        " GROUP BY s.id, s.name, u.id, u.name, l.id, l.name"
        " ORDER BY s.name, u.name, l.name LIMIT 30"
    )

    rows = db.execute(text(sql), params).fetchall()

    # Grouper : matiere -> unite -> lecons
    grouped = {}
    total = 0
    for row in rows:
        sid, uid = row.subject_id, row.unit_id
        if sid not in grouped:
            grouped[sid] = {"subject_id": sid, "subject_name": row.subject_name, "units": {}}
        if uid not in grouped[sid]["units"]:
            grouped[sid]["units"][uid] = {"unit_id": uid, "unit_name": row.unit_name, "lessons": []}
        grouped[sid]["units"][uid]["lessons"].append({
            "lesson_id": row.lesson_id,
            "lesson_name": row.lesson_name,
            "exercise_count": int(row.exercise_count),
        })
        total += int(row.exercise_count)

    results = [
        {
            "subject_id": s["subject_id"],
            "subject_name": s["subject_name"],
            "units": list(s["units"].values()),
        }
        for s in grouped.values()
    ]
    return {"results": results, "total": total, "query": q}
'''

mama += blackboard
with open(MAMA, "w", encoding="utf-8") as f:
    f.write(mama)
print("OK  mama.py")
print("Termine. Relancer uvicorn + vite pour tester.")
