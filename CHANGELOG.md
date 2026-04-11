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
- [ ] Seeders manquants : French C2/C3/C6, Science C2/C3/C6, ICT C6, Citizenship C6
- [ ] Bulletins Filament : Irma (15.54 rang27), Mark (7.32 rang34 ⚠️), Ruth (14.91 rang71)
- [ ] Plan remédiation Mark : Maths 27/100, Science 22/100
- [ ] Pagination exercices (actuellement tout chargé d'un coup → lenteur)
- [ ] Landing page publique (Hero + Hall of Fame slider + CTA)

### P2 — Moyen terme
- [ ] **Whisper.js (reconnaissance vocale offline)** — intégrer OpenAI Whisper en WebAssembly pour évaluer la prononciation dans OralDrill ET dans les duels. Tourne entièrement en local (~50MB téléchargé une fois). Évaluation phonème par phonème avec score de similarité. Permet aussi de réécouter sa propre prononciation. C'est le différenciateur majeur vs apps basiques.
- [ ] **TensorFlow.js OCR (reconnaissance écriture offline)** — modèle léger (~5MB) pour évaluer le handwriting sur canvas tactile. Court terme : auto-évaluation enfant (Correct/Pas tout à fait). Long terme : reconnaissance automatique des lettres/mots via TensorFlow.js. Handwriting exclu des duels jusqu'à implémentation.
- [ ] **Espace Mama Judi** (`/mama`) — interface simple S9+, bilingue FR/EN
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
- [ ] **Association books physiques**
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
