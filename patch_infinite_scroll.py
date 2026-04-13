# patch_infinite_scroll.py
HOME = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildHome.tsx"

with open(HOME, "r", encoding="utf-8") as f:
    c = f.read()

# 1. Ajouter useRef import si pas present
if "useRef" not in c.split("import")[1]:
    c = c.replace(
        "import React, { useState, useEffect, useRef }",
        "import React, { useState, useEffect, useRef }"
    )

# 2. Ajouter ref sentinel apres loadMoreExercises
old_load_more = (
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

new_load_more = (
    "  const loadingMoreRef = useRef(false)\n"
    "  const hasMoreRef = useRef(false)\n"
    "  const exPageRef = useRef(1)\n"
    "  useEffect(() => { hasMoreRef.current = hasMore }, [hasMore])\n"
    "  useEffect(() => { exPageRef.current = exPage }, [exPage])\n"
    "\n"
    "  const loadMoreExercises = async () => {\n"
    "    if (loadingMoreRef.current || !hasMoreRef.current) return\n"
    "    loadingMoreRef.current = true\n"
    "    setLoadingMore(true)\n"
    "    const nextPage = exPageRef.current + 1\n"
    "    const { exercises: more, hasMore: hm } = await getMoreExercisesForChild(child.id, child.level_id!, nextPage)\n"
    "    setExercises(prev => [...prev, ...shuffleArray(more)])\n"
    "    setExPage(nextPage)\n"
    "    setHasMore(hm)\n"
    "    loadingMoreRef.current = false\n"
    "    setLoadingMore(false)\n"
    "  }\n"
    "\n"
    "  // IntersectionObserver -- charge plus quand sentinel visible\n"
    "  const sentinelRef = useRef<HTMLDivElement | null>(null)\n"
    "  useEffect(() => {\n"
    "    const el = sentinelRef.current\n"
    "    if (!el) return\n"
    "    const observer = new IntersectionObserver(\n"
    "      entries => { if (entries[0].isIntersecting) loadMoreExercises() },\n"
    "      { threshold: 0.1 }\n"
    "    )\n"
    "    observer.observe(el)\n"
    "    return () => observer.disconnect()\n"
    "  }, [hasMore])"
)

if old_load_more in c:
    c = c.replace(old_load_more, new_load_more)
    print("OK loadMoreExercises + IntersectionObserver")
else:
    print("ERREUR : ancre loadMoreExercises introuvable")

# 3. Remplacer le bouton par un sentinel div invisible
old_btn = (
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

new_btn = (
    "      {/* Sentinel infinite scroll */}\n"
    "      <div ref={sentinelRef} style={{ height: 20, marginBottom: 80 }}>\n"
    "        {loadingMore && (\n"
    "          <div style={{ textAlign: 'center' as const, padding: '10px 0',\n"
    "            fontSize: 13, color: '#7A6050', fontWeight: 700 }}>\n"
    "            Loading more...\n"
    "          </div>\n"
    "        )}\n"
    "      </div>\n\n"
    "      {/* Bottom nav \u2014 always visible */}"
)

if old_btn in c:
    c = c.replace(old_btn, new_btn)
    print("OK sentinel div")
else:
    print("ERREUR : ancre bouton introuvable")

with open(HOME, "w", encoding="utf-8") as f:
    f.write(c)
print("Lance : npm run build")
