# patch_login_error.py
LOGIN = r"C:\laragon\www\edumaison\resources\react\src\pages\child\ChildLogin.tsx"

with open(LOGIN, "r", encoding="utf-8") as f:
    c = f.read()

# Chercher et afficher la ligne exacte
import re
matches = [(i+1, line) for i, line in enumerate(c.split('\n')) if 'res.error' in line or 'onLogin(res)' in line]
for lineno, line in matches:
    print(f"L{lineno}: {line}")
