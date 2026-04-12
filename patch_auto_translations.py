# patch_auto_translations.py
TSX = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"

with open(TSX, "r", encoding="utf-8") as f:
    c = f.read()

fr_anchor = "nav_tableau: 'Tableau', tableau_search: 'Chercher', tableau_no_results: 'Aucun exercice trouv\u00e9', tableau_exercises_found: 'exercices trouv\u00e9s',"
fr_add    = "\n    auto_section: 'R\u00e9vision automatique', auto_on: 'Activ\u00e9', auto_off: 'D\u00e9sactiv\u00e9', auto_time: 'Heure', auto_trigger_now: 'D\u00e9clencher maintenant', auto_triggered: 'Envoy\u00e9 !',"

en_anchor = "nav_tableau: 'Blackboard', tableau_search: 'Search', tableau_no_results: 'No exercises found', tableau_exercises_found: 'exercises found',"
en_add    = "\n    auto_section: 'Auto Revision', auto_on: 'On', auto_off: 'Off', auto_time: 'Time', auto_trigger_now: 'Trigger now', auto_triggered: 'Sent!',"

if fr_anchor in c:
    c = c.replace(fr_anchor, fr_anchor + fr_add)
    print("OK FR")
else:
    print("ERREUR: ancre FR introuvable")

if en_anchor in c:
    c = c.replace(en_anchor, en_anchor + en_add)
    print("OK EN")
else:
    print("ERREUR: ancre EN introuvable")

with open(TSX, "w", encoding="utf-8") as f:
    f.write(c)
