# fix_svg_center.py
MCQ = r"C:\laragon\www\edumaison\resources\react\src\pages\child\exercises\MCQ.tsx"

with open(MCQ, "r", encoding="utf-8") as f:
    c = f.read()

old = (
    "          {/* SVG illustration par question */}\n"
    "          {(q as any).svg && (\n"
    "            <div style={{ textAlign: 'center' as const, marginBottom: 10 }}\n"
    "              dangerouslySetInnerHTML={{ __html: (q as any).svg }}\n"
    "            />\n"
    "          )}"
)

new = (
    "          {/* SVG illustration par question */}\n"
    "          {(q as any).svg && (\n"
    "            <div style={{ textAlign: 'center' as const, marginBottom: 10 }}\n"
    "              dangerouslySetInnerHTML={{ __html: (q as any).svg.replace('<svg', '<svg style=\"display:block;margin:auto\"') }}\n"
    "            />\n"
    "          )}"
)

if old in c:
    c = c.replace(old, new)
    print("OK SVG centre")
else:
    print("ERREUR ancre introuvable")

with open(MCQ, "w", encoding="utf-8") as f:
    f.write(c)
print("Lance : npm run build")
