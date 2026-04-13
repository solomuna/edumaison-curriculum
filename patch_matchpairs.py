# patch_matchpairs.py -- Fix format left/right + feedback EN
MATCH = r"C:\laragon\www\edumaison\resources\react\src\pages\child\exercises\MatchPairs.tsx"

with open(MATCH, "r", encoding="utf-8") as f:
    c = f.read()

# 1. Fix interface Pair -- supporter left/right ET word/image
old_interface = (
    "interface Pair {\n"
    "  word: string\n"
    "  image: string\n"
    "}"
)

new_interface = (
    "interface Pair {\n"
    "  word?: string\n"
    "  image?: string\n"
    "  left?: string\n"
    "  right?: string\n"
    "}"
)

c = c.replace(old_interface, new_interface)
print("OK interface Pair")

# 2. Normaliser les paires -- convertir left/right en word/image
old_pairs = (
    "  const pairs: Pair[] = content.pairs || []"
)

new_pairs = (
    "  // Normaliser left/right -> word/image pour compatibilite\n"
    "  const pairs: Pair[] = (content.pairs || []).map((p: any) => ({\n"
    "    word:  p.word  ?? p.left  ?? '',\n"
    "    image: p.image ?? p.right ?? '',\n"
    "  }))"
)

c = c.replace(old_pairs, new_pairs)
print("OK normalisation left/right")

# 3. Feedback FR -> EN + bouton Verifier
c = c.replace("V\u00e9rifier", "Check")
c = c.replace(
    "result ? '\U0001f389 Parfait ! Toutes les paires sont correctes !' : 'Presque ! R\u00e9essaie.'",
    "result ? '\U0001f389 Perfect! All pairs are correct!' : 'Not quite! Try again.'"
)

print("OK feedback EN")

with open(MATCH, "w", encoding="utf-8") as f:
    f.write(c)
print("Lance : npm run build")
