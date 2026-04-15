# EduMaison — CHANGELOG & ÉTAT DU PROJET
> ⚠️ CE FICHIER EST LA PREMIÈRE CHOSE À LIRE À CHAQUE SESSION
> Mis à jour le : 2026-04-11

---

## 📦 STACK TECHNIQUE
- **Backend** : Laravel 13.3 + PHP 8.3.30 + PostgreSQL 17
- **Frontend** : React 19 + Vite 8 + TypeScript + Tailwind
- **Admin** : Filament v4.9.4 (⚠️ décision: à supprimer — voir Décisions Architecturales)
- **Local** : `C:\laragon\www\edumaison` — URL : `https://edumaison.test`
- **WiFi local** : `http://192.168.100.106/app` (Nginx écoute sur 0.0.0.0:80)
- **GitHub** : `solomuna/edumaison-curriculum` (branche `master`)
- **PHP** actif : `php-8.3.30-Win32-vs16-x64` (Laragon)

---

## 👨‍👩‍👧‍👦 ENFANTS
| Nom | Niveau | ID | PIN | Notes |
|-----|--------|----|-----|-------|
| Irma | C1 | 1 | 2407 | Motivée, veut enseigner |
| Mark | C4 | 2 | 1309 | ⚠️ Désinvolture, Maths+Science faibles |
| Ruth | C5 | 3 | 0101 | Très intelligente, discrète |
| Julia | C2 | 4 | 2110 | Cousine, très avancée pour son âge |

**Mama Judi** = épouse de David, vacataire dans établissement francophone, Samsung S9+, bilingue FR/EN

---

## 🗄️ BASE DE DONNÉES
```
subjects → integrated_themes → units → lessons → exercises
levels: 2=Pre-Nursery, 3=N1, 4=N2, 5=C1, 6=C2, 7=C3, 8=C4, 9=C5, 10=C6
```
- 1993 exercices encodés (MINEDUB 2018, C1→C6)
- Tables actives : children, subjects, exercises, attempts, exams, exam_results, school_results, streaks

---

## 🎮 MOTEURS D'EXERCICES (11 types)
MCQ, OralDrill, FillIn, Handwriting, MatchPairs, SentenceOrder, TrueFalse, ClockReading, VennDiagram, NumberLine, Geometry

---

## ✅ FONCTIONNALITÉS ACTIVES
- [x] Auth PIN par enfant
- [x] Streak + Leaderboard familial
- [x] Certificats SVG (≥80%)
- [x] Bulletin MINEDUB (Comp1-6, print/PDF)
- [x] Examens simultanés (ExamBanner + ExamSession)
- [x] Remédiation Mark (avg<12)
- [x] PWA (manifest + sw.js + IndexedDB + BackgroundSync)
- [x] OPcache activé (gain ~50% vitesse)
- [x] Mode Desktop (sidebar) + TV (LG webOS)
- [x] TTS multilingue (en-GB / fr-FR selon matière)
- [x] Mama Judi greeting sur Desktop

---

## 🔧 SESSION DU 2026-04-12 — CE QUI A ÉTÉ FAIT
- [x] Migration FastAPI complète — 24 endpoints migrés de Laravel vers FastAPI
- [x] Structure `C:\laragon\www\edumaison-api\` créée (FastAPI + SQLAlchemy + PostgreSQL)
- [x] Modules : auth, children, exercises, subjects, exams, leaderboard, streak, parent, remediation, bulletin, certificates
- [x] Port 8100 — Laravel reste sur 80 pendant la transition
- [x] `school_years` table peuplée (label=2025-2026, is_current=true)
- [x] Dashboard parent fonctionnel — 171 exercices, 4 enfants
- [x] Performance confirmée — auth Mark ~2ms vs 200-400ms Laravel
- [ ] **À FAIRE** : Basculer React vers port 8100 (changer BASE dans api.ts)
- [ ] **À FAIRE** : Ouvrir port 8100 dans le pare-feu Windows
- [ ] **À FAIRE** : Commit edumaison-api sur GitHub

---

## 🔧 SESSION DU 2026-04-12 ? CE QUI A ETE FAIT
- [x] Sons Chrome regles (TTS Mama Judi + effets sonores)
- [x] Julia C2 (id=4, pin=2110) ajoutee
- [x] Migration FastAPI complete -- 24 endpoints, auth ~2ms, port 8100
- [x] React bascule vers FastAPI port 8100
- [x] Port 8100 ouvert dans le pare-feu Windows
- [x] edumaison-api commite sur GitHub
- [x] Seeders French/Science/ICT/Citizenship C2/C3/C6 completes
- [x] Bulletins dynamiques depuis exercise_attempts (fallback school_results)
- [x] Appreciations traduites EN + gamme motivante (Let's Work Harder! etc.)
- [x] Mama Judi's Remarks -- commentaires traduits EN
- [x] Moyenne par groupe Comp dans le bulletin
- [x] Comp_1 adaptee par niveau (110 C1-C3, 60 C4-C6)

---

## 🔧 SESSION DU 2026-04-11 — CE QUI A ÉTÉ FAIT
- [x] Laragon accessible WiFi local (192.168.100.106)
- [x] Pare-feu Windows ouvert port 80
- [x] Bug namespace `SubjectResource` corrigé (Subjects\SubjectResource)
- [x] Config/route/view cache activés
- [x] OPcache activé dans php.ini PHP 8.3.30
- [x] Bouton retour natif Android/iOS (ChildHome)
- [x] Dialog "Quit EduMaison?" sur mobile
- [x] Fix Nginx pour actualisation pages profondes (/app, /tv)
- [x] TTS multilingue patch appliqué (ExercisePlayer + MamaJudi.speakLang)
- [x] Mama Judi greeting sur DesktopApp
- [x] Fix getChildren() TVApp (Array.isArray guard)
- [x] Tailscale désactivé (libère bande passante)
- [x] Git initialisé + premier commit pushé sur GitHub
- [x] SOLVILO, AJUMY, EduMaison tous sur GitHub

---

## 🔴 BUGS CONNUS NON RÉGLÉS
- [ ] Sons Chrome (TTS bloqué sans interaction utilisateur sur certains navigateurs)
- [ ] Actualisation page TV/Desktop — Nginx ne couvre pas tous les cas
- [ ] Patch quit/back non appliqué sur TV et Desktop
- [ ] `e.map is not a function` sur TVApp — partiellement corrigé, à vérifier

---

## 🟡 BACKLOG PRIORISÉ

### P1 — Court terme
- [x] Seeders French/Science/ICT/Citizenship C2/C3/C6 complet�s (9 combinaisons)
- [ ] Bulletins Filament : Irma (15.54 rang27), Mark (7.32 rang34 ⚠️), Ruth (14.91 rang71)
- [ ] Plan remédiation Mark : Maths 27/100, Science 22/100
- [ ] Pagination exercices (actuellement tout chargé d'un coup → lenteur)
- [x] Landing page publique -- bilingue FR/EN, avatars dynamiques, stats API, ordre par age

### P2 — Moyen terme
- [ ] **Whisper.js (reconnaissance vocale offline)** — intégrer OpenAI Whisper en WebAssembly pour évaluer la prononciation dans OralDrill ET dans les duels. Tourne entièrement en local (~50MB téléchargé une fois). Évaluation phonème par phonème avec score de similarité. Permet aussi de réécouter sa propre prononciation. C'est le différenciateur majeur vs apps basiques.
- [ ] **TensorFlow.js OCR (reconnaissance écriture offline)** — modèle léger (~5MB) pour évaluer le handwriting sur canvas tactile. Court terme : auto-évaluation enfant (Correct/Pas tout à fait). Long terme : reconnaissance automatique des lettres/mots via TensorFlow.js. Handwriting exclu des duels jusqu'à implémentation.
- [x] **Espace Mama Judi** -- Brief, Revision multi-matieres, Duel, Profil photo+PIN, Books physiques (`/mama`) — interface simple S9+, bilingue FR/EN
  - Brief du soir (résumé progrès enfants)
  - Programmation révision du soir
  - Lancer un duel
  - Association books physiques ↔ unités EduMaison
  - Tableau noir (saisie leçon → match exercices)
- [ ] **Duel live** (2 enfants, mêmes exercices aléatoires, chrono, son début/fin, vainqueur)
  - Réutiliser infra `exams` existante
  - Polling 3s côté tablettes
  - Son annonce début + fin
- [ ] **Révision du soir Mama Judi**
  - Laravel Scheduler (heure fixe configurable) + déclenchement manuel Filament
  - Analyse `attempts` du jour → unité avec plus d'erreurs
  - Son + popup qui s'impose sur tablettes
  - Message vocal Mama Judi personnalisé
- [x] Books physiques Mama Judi -- association livres/unites, CRUD, icone, titre**
  - Table `book_references` (subject_id, book_name, page, unit_id)
  - Mama Judi associe une fois → automatique ensuite

### P3 — Long terme
- [ ] Multi-familles (inscription famille, isolation données, `family_id` sur children)
- [ ] Page inscription famille (voisine de pallier = premier test)
- [ ] **Portabilité/Multi-nœuds** : VPS + sync local↔VPS (PostgreSQL replication, APP_URL dynamique)
- [ ] APK Capacitor (Julia → papa de Julia = premier utilisateur externe)
- [ ] Assistant IA intégré (plus tard — bien plus tard 😄)
- [ ] Structure Books : ajouter couche Books → Résumés manquante (vision originale non encore implémentée)

---

## 🎯 VISION ORIGINALE (non encore implémentée)
La structure prévue dès le départ était :
```
Niveau → Book physique → Chapitre/Leçon → Résumé pédagogique → Exercices
```
On a construit dans le sens inverse (exercices d'abord). Il faudra ajouter :
- Table `books` (nom, niveau, éditeur, couverture)
- Table `book_chapters` (book_id, page, unit_id)
- Champ `summary` sur `lessons` (contenu pédagogique)

---

## 🚀 ROADMAP COMMERCIALE
```
Phase 1 (maintenant)  : Famille Kamgang — beta vivant
Phase 2 (court terme) : Voisine de pallier — premier test externe
Phase 3 (moyen terme) : Papa de Julia (APK) — premier utilisateur distant
Phase 4 (moyen terme) : 5-10 familles pilotes — validation pédagogique
Phase 5 (long terme)  : Déploiement public — abonnement famille
```
**Modèle économique envisagé** : gratuit limité + abonnement famille (fonctionnalités avancées)
**Différenciateur** : seule plateforme camerounaise anglophone alignée MINEDUB + Espace Mama Judi

### 1. Supprimer Filament
**Problème** : incompatibilités PHP 8.3/8.4, lourd, mal adapté à Mama Judi
**Solution** : Laravel API pure + React admin (même stack que app enfant)
**Impact** : migrer les pages Filament en React
**Priorité** : après stabilisation app enfant

### 2. Migrer vers FastAPI + React
**Problème** : Laravel trop lent pour réseau local (200-400ms vs 10-50ms FastAPI)
**Solution** : FastAPI (Python) + même PostgreSQL + même React (rien à migrer côté front)
**Avantage** : même stack que SOLVILO V2, David maîtrise déjà
**Impact** : réécrire les endpoints Laravel en FastAPI
**Priorité** : décision majeure — à prendre après Espace Mama Judi

---

## 📐 RÈGLES DE DÉVELOPPEMENT
1. **Lire ce fichier en premier** à chaque session
2. **Lire le fichier cible** avant tout patch (jamais de patch à l'aveugle)
3. **Backup avant patch** : `git stash` ou copie du fichier
4. **Commenter le code** : chaque fonction, chaque bloc important
5. **Vérifier les conflits** : imports dupliqués, namespaces, variables existantes
6. **Patches chirurgicaux** : modifier uniquement ce qui est nécessaire
7. **Commit après chaque feature stable** avec message clair
8. **Mettre à jour ce fichier** en fin de session

### Convention commits
```
feat: nouvelle fonctionnalité
fix: correction de bug
patch: modification ciblée
refactor: restructuration sans changement de comportement
docs: documentation
style: CSS/UI uniquement
```

---

## 🚨 FICHIERS CRITIQUES — NE PAS TOUCHER SANS LIRE
| Fichier | Risque |
|---------|--------|
| `resources/react/src/pages/child/ExercisePlayer.tsx` | Moteur central — 11 types |
| `resources/react/src/pages/child/ChildHome.tsx` | Navigation + back button logic |
| `resources/react/src/services/MamaJudi.ts` | Audio — ne pas casser le TTS |
| `app/Filament/Admin/Resources/Subjects/SubjectResource.php` | Namespace corrigé — ne pas toucher |
| `C:\laragon\etc\nginx\sites-enabled\edumaison.test.conf` | Config réseau WiFi |
| `.env` | APP_URL=http://192.168.100.106, SESSION_DOMAIN=192.168.100.106 |

---

## 🎨 UI / DESIGN
- **Palette** : bg=#E8DCC8, card=#F0E8D8, darkGreen=#1D6B2A, textDark=#3D2B1F
- **Font** : Nunito 900/600
- **Style** : Light, colorful, playful — PAS dark navy
- **Règle emoji** : toujours unicode escapes JS (`\u{XXXXX}`) dans les patches Python

---

## 📱 ACCÈS
| Interface | URL | Appareil |
|-----------|-----|---------|
| App enfant mobile | http://192.168.100.106/app | Tablettes WiFi |
| App enfant desktop | http://192.168.100.106/app | PC ≥1024px auto |
| App TV | http://192.168.100.106/tv | LG webOS |
| Admin Filament | http://192.168.100.106/admin | PC David |
| Espace Mama Judi | http://192.168.100.106/mama | Samsung S9+ (à créer) |

## 🔧 SESSION DU 2026-04-12 — CE QUI A ÉTÉ FAIT (suite)
- [x] Tableau noir Mama Judi — onglet 📝, GET /api/mama/blackboard?q=...
      Recherche dans lessons/units/subjects, résultats groupés par matière→unité
- [x] Révision automatique APScheduler — lun-ven 19h00 (Africa/Douala)
      scheduler.py — BackgroundScheduler, sujet le plus faible du jour
      Endpoints: GET/POST /api/evening-sessions/scheduler-config
                 POST /api/evening-sessions/trigger-auto
      Carte Auto dans RevisionScreen — toggle On/Off, heure configurable, déclencher maintenant
- [ ] Brief vocal — À FAIRE

## 🟡 BACKLOG RESTANT
- [ ] Brief vocal
- [ ] Seeders C1/C2/Nursery (Reading/Handwriting faibles)
- [ ] Pagination exercices
- [ ] Portabilité VPS
- [ ] APK Capacitor

## 🔧 SESSION DU 2026-04-12 — CE QUI A ÉTÉ FAIT (suite 2)
- [x] Lien Mama Judi sur page login (/mama)
- [x] Back button natif mobile — ChildHome (exercise→home→quit) + ChildLogin
- [x] Popup Quit EduMaison sur ChildHome et ChildLogin
- [x] Tab actif persisté après refresh (localStorage edumaison_tab_[id])
- [x] Session enfant persistée après refresh (localStorage edumaison_session)
      Plus besoin de reentrer le PIN après actualisation

## 🟡 BACKLOG RESTANT
- [ ] Seeders C1/C2/Nursery (Reading/Handwriting faibles)
- [ ] Pagination exercices
- [ ] Portabilité VPS
- [ ] APK Capacitor
## 🔧 SESSION DU 2026-04-13 — CE QUI A ÉTÉ FAIT
- [x] Tableau noir Mama Judi — onglet 📝, recherche lecons/units/subjects
- [x] Révision automatique APScheduler lun-ven 19h (scheduler.py)
      Carte Auto dans RevisionScreen — toggle, heure configurable, déclencher maintenant
- [x] Brief vocal TTS fr-FR mobile — phrases courtes Android fix
- [x] Lien /mama sur ChildLogin
- [x] Back button natif mobile ChildHome + ChildLogin
- [x] Popup Quit EduMaison sur ChildHome + ChildLogin
- [x] Tab actif persisté après refresh (localStorage)
- [x] Session enfant persistée après refresh (localStorage)
- [x] Avatar Mama Judi depuis profil sur login + desktop
- [x] Examens complétés masqués (already_taken filtrés)
- [x] Pagination infinite scroll (IntersectionObserver, 20 ex/page)
- [x] URLs /storage/ relatives (fix mobile/TV/desktop)

## 🟡 BACKLOG RESTANT
- [ ] Seeders C1/C2/Nursery (Reading/Handwriting faibles)
- [ ] Portabilité VPS
- [ ] APK Capacitor
## 🔧 SESSION DU 2026-04-13 — CE QUI A ÉTÉ FAIT (suite 3)
- [x] Seeders Mathematics C1 -- unites 1-4 (counting, shapes, long/short) +30 ex
- [x] Seeders Mathematics C2 -- unites 2-4 (numbers to 100, add, subtract) +23 ex
- [x] Seeders English C1+C2 -- unites a 0 exercices (classroom, body, animals, environment, transport) +57 ex
- [x] Seeders Handwriting C1+C2 -- lessons 210-214 +30 ex
- [x] Seeders enrichis VennDiagram + NumberLine + Geometry SVG +15 ex
- [x] Adaptateur MCQ format simplifie (question/options/answer -> questions[])
- [x] Rendu SVG inline dans MCQ (dangerouslySetInnerHTML)
- [x] ExamBanner -- masquer examens already_taken
- [x] Pagination infinite scroll (IntersectionObserver)

## 🟡 BACKLOG RESTANT
- [ ] Fix SVG centré dans MCQ (reporté)
- [ ] Seeders Nursery1/Nursery2 complets
- [ ] Portabilité VPS
- [ ] APK Capacitor


## 🔧 SESSION DU 2026-04-13 — CE QUI A ÉTÉ FAIT (suite 4)
- [x] OralDrill -- Next → Finish ✓ (HTML entities fixes)
- [x] Feedback EN partout -- MCQ, Geometry, Handwriting, MatchPairs, OralDrill
- [x] Geometry draw -- bouton Next si pas d'options
- [x] TTS énoncés -- cancel() + délai avant lecture
- [x] Micro permission -- demande au chargement OralDrill, fallback Good/Retry
- [x] MatchPairs -- normalisation left/right -> word/image
- [x] Handwriting -- adaptateur word/letter -> prompts[]
- [x] ExercisePlayer -- fallback intelligent (pairs/words/statement)
- [x] Avatar enfant desktop sidebar + bouton Mama Judi footer
- [x] Tap to hear -- ChildLogin + ChildHome cliquable TTS
- [x] Rank leaderboard -- suppression chiffre après top 3

## 🟡 BACKLOG RESTANT
- [ ] MatchPairs texte toujours vide (en cours)
- [ ] MCQ answer index vs texte DB
- [ ] TTS tap to hear ne parle pas
- [ ] Ardoise brouillon
- [ ] Seeders Nursery1/Nursery2
- [ ] Portabilité VPS
- [ ] APK Capacitor



## 🔧 SESSION DU 2026-04-14 — CE QUI A ÉTÉ FAIT
- [x] MatchPairs — texte vide corrigé (JSON parsing + formats array/objet)
- [x] MatchPairs — corrections affichées après mauvaise réponse
- [x] MatchPairs — font adaptative emoji vs texte
- [x] MCQ — shuffle robuste par texte (fix réponse correcte)
- [x] MCQ — guard questions array (plus de crash)
- [x] MCQ — content JSON guard (ExercisePlayer)
- [x] MCQ — TTS détection FR/EN selon matière
- [x] FillIn — champ `sentence` + `alternatives` supportés
- [x] OralDrill — TTS langue FR/EN selon matière
- [x] MamaJudi.ts — sélection voix native selon langue
- [x] Ardoise brouillon — fond noir flottant, 4 tailles, expand/collapse (55vh↔92vh)
- [x] TTS replay 🔊 dans ExerciseShell + MCQ
- [x] DuelSession — closure stale scores + onBack bloquant corrigés
- [x] 236 exercices sans type corrigés (MCQ/TF/FillIn/MatchPairs)
- [x] Illustrations — 2220 exercices réassignés par matière (psycopg2)
- [x] asset_library — table + 45 icônes + API FastAPI + hook useAssetLibrary
- [x] Seeders Nursery 1+2 — +126 exercices (Maths/English/French)
- [x] Brief vocal Mama Judi — TTS fr-FR, phrases courtes Android

## 🟡 BACKLOG RESTANT
- [ ] Tour de test 360° (bugs à reporter)
- [ ] Duel live — polling résultats adversaire
- [ ] Portabilité VPS
- [ ] APK Capacitor


## 🔧 SESSION DU 2026-04-14 (suite) — CE QUI A ÉTÉ FAIT
- [x] React Admin — dashboard stats, liste enfants CRUD, liste exercices paginée, liste matières
- [x] FastAPI admin routes — /api/admin/stats, children, levels, subjects, units, lessons, exercises
- [x] Route Nginx /admin-react → http://192.168.100.106/admin-react
- [x] Duel live — polling résultats adversaire toutes les 3s, podium 🥇🥈 final
- [x] Brief vocal Mama Judi — TTS fr-FR phrases courtes, fix encodage texte

## 🟡 BACKLOG RESTANT
- [ ] React Admin Phase 2 — édition exercices, gestion leçons/unités
- [ ] Supprimer Filament (après stabilisation React Admin)
- [ ] Tour de test 360° (bugs à reporter)
- [ ] Portabilité VPS
- [ ] APK Capacitor

## 🔧 SESSION DU 2026-04-14 (suite 2) — CE QUI A ÉTÉ FAIT
- [x] React Admin Phase 2 — UnitsScreen CRUD (filtre matière, add/edit/delete)
- [x] React Admin Phase 2 — LessonsScreen CRUD (filtre unité, add/edit/delete)
- [x] React Admin Phase 2 — Exercise edit modal (titre/catégorie/difficulté/actif + JSON content)
- [x] React Admin Phase 2 — Delete confirmé avec modal (unités/leçons/exercices)
- [x] React Admin Phase 2 — Navigation drill-down Matières→Unités→Leçons→Exercices
- [x] React Admin Phase 2 — Dashboard cards cliquables
- [x] FastAPI — GET /integrated-themes, POST/PUT/DELETE /units, POST/PUT/DELETE /lessons
- [x] FastAPI — PUT /exercises inclut lesson_id (déplacement d'exercice)

## 🟡 BACKLOG RESTANT
- [ ] Supprimer Filament (après stabilisation React Admin)
- [ ] Tour de test 360°
- [ ] Portabilité VPS
- [ ] APK Capacitor

## SESSION DU 2026-04-15 - CE QUI A ETE FAIT

### React Admin Phase 2 - P1
- [x] Curriculum screen - pills cascade Niveau > Matiere > Unite > Lecons + search + CRUD
- [x] Assets CRUD - voir/ajouter/modifier/supprimer icones asset_library + preview
- [x] Reset enfant partiel - supprime attempts/exams/duels, conserve school_results
- [x] Toggle actif/masque - bouton direct sur carte enfant
- [x] Health check - ping 19 modules FastAPI avec latence + barre progression
- [x] Bulletins MINEDUB - voir bulletin par enfant (pills + tableau competences)
- [x] Logs live SSE - stream uvicorn temps reel, filtre INFO/WARNING/ERROR + recherche

### React Admin Phase 2 - P2
- [x] Progression enfant - barres par matiere + evolution 7j + exercices faibles (<60%)
- [x] Rapport journalier - resume activite du jour par enfant avec selecteur de date
- [x] Gestion school_years - CRUD annees scolaires, activation, suppression
- [x] Brief vocal config - apercu brief du soir + declenchement manuel + toggle scheduler
- [x] Sidebar groupee - sections repliables Contenu / Suivi / Systeme

### React Admin Phase 2 - P3
- [x] Apercu exercice inline - panneau droit temps reel (MCQ, TrueFalse, FillIn, MatchPairs, OralDrill)
- [x] Fix bug MCQ answer texte vs index - shuffleOptions normalisation dans MCQ.tsx

### FastAPI
- [x] Fix doublons main.py (routers enregistres deux fois)
- [x] Fix GROUP BY subjects (l.id manquant)
- [x] Fix list_children (colonnes inexistantes supprimees)
- [x] log_handler.py - intercepte logs Python pour SSE
- [x] Endpoints: reset enfant, progression, rapport daily, school-years CRUD
- [x] Endpoints: health check, logs SSE, assets CRUD

## BACKLOG RESTANT
- [ ] Bulk actions exercices
- [ ] Remediation Mark
- [ ] Import/Export CSV
- [ ] Seeders depuis Admin
- [ ] Backup DB / vider cache
- [ ] Portabilite VPS
- [ ] APK Capacitor
- [ ] SOLVILO i18n bug dashboard
