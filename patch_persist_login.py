# patch_persist_login.py -- Persiste l'enfant connecte dans localStorage
# Fichier touche : resources/react/src/main.tsx (ou App.tsx selon structure)
# On cherche d'abord quel fichier gere le state child

import os

# Chercher le fichier qui contient onLogin et child state
candidates = [
    r"C:\laragon\www\edumaison\resources\react\src\main.tsx",
    r"C:\laragon\www\edumaison\resources\react\src\App.tsx",
    r"C:\laragon\www\edumaison\resources\react\src\app.tsx",
]

target = None
for path in candidates:
    if os.path.exists(path):
        with open(path, "r", encoding="utf-8") as f:
            content = f.read()
        if "onLogin" in content or "ChildLogin" in content or "ChildHome" in content:
            target = path
            print(f"Fichier trouve : {path}")
            print(content[:3000])
            break

if not target:
    print("Fichier non trouve -- cherche manuellement avec :")
    print("Get-ChildItem C:\\laragon\\www\\edumaison\\resources\\react\\src -Recurse -Filter '*.tsx' | Select-String 'onLogin' | Select-Object Path")
