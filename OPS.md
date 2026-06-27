# OPS / How-To EduMaison

Mémo opérationnel pour les tâches courantes : redémarrages, dépannage, nouveau projet, accès tablette, sauvegardes, ajout d'exercices.

> Toutes les commandes `nssm` et `Restart-Service` doivent être lancées depuis un **PowerShell admin** (clic droit Démarrer → *Terminal (admin)*).

---

## 🔧 1. Redémarrer un service

nginx, PostgreSQL et FastAPI sont des **services Windows** indépendants de Laragon. Quand tu touches à leur config, redémarre seulement le service concerné — pas besoin de toucher à Laragon.

```powershell
nssm restart nginx            # apres modif d'un .conf nginx
nssm restart postgresql       # rare (apres tuning postgresql.conf)
nssm restart EduMaisonAPI     # apres modif du code FastAPI
```

Si `nssm` n'est pas dans le PATH :
```powershell
& "C:\nssm\nssm.exe" restart nginx
```

Vérifier l'état :
```powershell
Get-Service nginx, postgresql, EduMaisonAPI | Format-Table Name, Status, StartType
```

**À retenir : redémarrer Laragon ne sert plus à rien** — il ne contrôle plus nginx/postgres/FastAPI depuis qu'on les a installés en services (cf. `install-services.ps1`).

---

## 🔍 2. « L'app ne marche plus » — diagnostic en 30 sec

Lance ce one-liner pour voir d'un coup d'œil ce qui répond et ce qui ne répond pas :

```powershell
@(
  @{Url='http://msi-laptop.local/app';        Label='React enfant'},
  @{Url='http://msi-laptop.local/api/children';Label='FastAPI (BD)'},
  @{Url='http://msi-laptop.local/admin-react';Label='Admin'}
) | ForEach-Object {
  try { $c=(Invoke-WebRequest $_.Url -UseBasicParsing -TimeoutSec 3).StatusCode }
  catch { $c='X' }
  "{0,-15} {1}" -f $c, $_.Label
}
```

| Code | Coupable probable |
|---|---|
| `200` partout | tout va bien |
| React = 200, API = 500 ou X | **PostgreSQL down** → `nssm restart postgresql` |
| React = 200, API = 502 | **FastAPI down** → `nssm restart EduMaisonAPI` |
| Tout = X / timeout | **nginx down** → `nssm restart nginx` |
| 200 mais pas de données | **cache navigateur** sur la tablette → forcer recharge / vider cache |

---

## 🆕 3. Ajouter un nouveau projet Laragon

```powershell
# 1. Cree le dossier dans C:\laragon\www\
mkdir C:\laragon\www\mon-projet

# 2. Genere le vhost
C:\laragon\new-vhost.ps1 mon-projet

# 3. Redemarre nginx (admin)
nssm restart nginx
```

→ `http://mon-projet.test` répond.

Le script détecte tout seul si c'est du Laravel/Symfony (avec `public/`) ou pas. Options : `-Force` (écraser), `-Reload` (redémarrer nginx en même temps si tu es déjà en admin).

---

## 🌐 4. Activer / désactiver la capture des IPs LAN par EduMaison

Quand activée, taper l'IP du PC dans un navigateur sur le LAN ouvre EduMaison (utile pour les tablettes sur un Wi-Fi où `msi-laptop.local` ne se résout pas).

**Fichier** : `C:\laragon\etc\nginx\sites-enabled\edumaison.test.conf`

```nginx
server_name edumaison.test *.edumaison.test msi-laptop msi-laptop.local;
#            ~^192\.168\.\d+\.\d+$
#            ~^10\.\d+\.\d+\.\d+$
#            ~^172\.(1[6-9]|2\d|3[01])\.\d+\.\d+$;
```

- **Pour activer** : enlève le `;` en fin de `msi-laptop.local`, enlève les `#` des 3 regex, mets le `;` final après la dernière regex.
- **Pour désactiver** : commente les 3 regex, mets le `;` à la fin de `msi-laptop.local`.

Puis `nssm restart nginx`.

---

## 📱 5. Tablette n'arrive pas à joindre EduMaison

Dans l'ordre :

1. **Tablette et PC sur le même WiFi ?** Vérifier en bas à droite du PC le nom du réseau. Vérifier sur la tablette aussi.
2. **PC connecté au bon WiFi ?** Souvent le PC repart sur le partage iPhone tout seul → décocher « Se connecter automatiquement » sur ces réseaux indésirables.
3. Essayer `http://msi-laptop.local/app` → si erreur `ERR_NAME_NOT_RESOLVED`, le routeur bloque mDNS → utiliser l'IP directe (`ipconfig` côté PC, prendre l'IP Wi-Fi, puis activer la capture LAN ci-dessus).
4. Pour figer l'IP du PC à long terme : faire une **réservation DHCP** dans l'admin du routeur.

---

## 💾 6. Sauvegardes

- **Auto** : tâche Windows `EduMaison Backup` tourne tous les jours à 02:00, garde 7 jours dans `C:\laragon\www\edumaison\backups\`.
- **Manuel** : `schtasks /run /tn "EduMaison Backup"` (ne demande pas admin).
- **Restauration** d'un dump :

```powershell
& "C:\laragon\bin\postgresql\postgresql\bin\psql.exe" -h 127.0.0.1 -U postgres -d edumaison -f "C:\laragon\www\edumaison\backups\edumaison_YYYYMMDD_HHMMSS.sql"
```

---

## 📝 7. Ajouter des exercices

**Méthode UI** : `/admin-react → Seeders → ▶ Lancer` un seeder existant.

**Méthode fichier** (édition + relance) :

1. Édite ou crée un seeder dans `C:\laragon\www\edumaison-api\seeders\` (copie `seeder_TEMPLATE.py`).
2. Ajoute tes exercices dans la liste `exercises`. ⚠️ `category` en minuscules (`mathematics`, `reading`…), `difficulty` aussi (`easy`/`medium`/`hard`).
3. Lance via l'admin OU `python -X utf8 seeders/seeder_mon_sujet.py` depuis `edumaison-api\`.

Détails complets : `c:\laragon\www\edumaison-api\seeders\README.md`.

---

## 🔥 8. Réinitialiser un enfant (effacer ses tentatives)

- **UI** : Espace Mama Judi (`/mama`) → bouton **↻ Réinitialiser** sous la carte de l'enfant.
- **API direct** : `POST http://msi-laptop.local/api/admin/children/{id}/reset`
- Garde les bulletins / résultats scolaires (donc le plan de remédiation reste correct).

---

## 🗂 9. URLs à connaître

| URL | À quoi ça sert |
|---|---|
| `http://msi-laptop.local/app` | App enfant (PWA) |
| `http://msi-laptop.local/mama` | Espace parent (Mama Judi), PIN demandé |
| `http://msi-laptop.local/admin-react` | Admin React (dashboard, enfants, exos, bulletins, seeders, logs…) |
| `http://msi-laptop.local/tv` | Mode TV |

---

## 📜 10. Logs en cas de plantage

| Service | Fichier |
|---|---|
| nginx accès | `C:\laragon\bin\nginx\nginx-1.27.3\logs\access.log` |
| nginx erreurs | `C:\laragon\bin\nginx\nginx-1.27.3\logs\error.log` |
| nginx (via NSSM) | `C:\laragon\bin\nginx\nginx-1.27.3\logs\nssm-stderr.log` |
| FastAPI | dispo dans `/admin-react → Logs` (live SSE) |
| PostgreSQL | `C:\laragon\data\postgresql\server.log` |

```powershell
Get-Content "C:\laragon\bin\nginx\nginx-1.27.3\logs\error.log" -Tail 30
```

---

## 🛟 11. Rollback rapide d'une conf nginx

À chaque fois que je touche `edumaison.test.conf`, je crée un `.bak`. Pour revenir en arrière :

```powershell
ls "C:\laragon\etc\nginx\sites-enabled\*.bak*"
Copy-Item "C:\laragon\etc\nginx\sites-enabled\edumaison.test.conf.bak" `
          "C:\laragon\etc\nginx\sites-enabled\edumaison.test.conf" -Force
& "C:\laragon\bin\nginx\nginx-1.27.3\nginx.exe" -t -p "C:\laragon\bin\nginx\nginx-1.27.3"
nssm restart nginx
```
