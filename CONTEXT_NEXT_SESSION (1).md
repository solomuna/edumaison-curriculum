# CONTEXT — EduMaison — Prochaine session

## État du projet au 08/04/2026

### Stack technique
- **Backend** : Laravel 13.3 + PHP 8.3 + PostgreSQL 17
- **Frontend** : React 19 + Vite 8 + TypeScript + Tailwind CSS
- **Admin** : Filament v4.9.4
- **Serveur local** : Laragon (Windows) — `https://edumaison.test`
- **IP réseau local** : `192.168.100.106`
- **Build production** : `https://edumaison.test/app` et `http://192.168.100.106/app`
- **Dev** : `http://localhost:5173` (npm run dev:react)

### Chemins importants
- Projet : `C:\laragon\www\edumaison`
- React src : `resources/react/src/`
- Build prod : `public/react/`
- Seeders : `database/seeders/`
- Data JSON : `database/data/`
- API routes : `routes/api.php`
- Moteurs exercices : `resources/react/src/pages/child/exercises/`

### Enfants (vrais noms)
| ID | Prénom | Nom | Niveau | Level ID | PIN | Date naissance |
|----|--------|-----|--------|----------|-----|----------------|
| 1  | Irma   | Motchougam | Class 1 | 5 | 2407 | 24/07/2020 |
| 2  | Mark   | Harrys     | Class 4 | 8 | 1309 | 13/09/2017 |
| 3  | Ruth   | Carla      | Class 5 | 9 | 0101 | 01/01/2016 |

École : MARIO Nursery & Primary School, Yaoundé
Household : Famille Kamgang (id=1)
Année scolaire active : 2025-2026 (id=2)

---

## Constraints CHECK importantes (PostgreSQL)

### lessons.type (valeurs valides)
`reading`, `listening`, `speaking`, `writing`, `mathematics`, `science`, `ict`, `mixed`

### exercises.category (valeurs valides)
`reading`, `handwriting`, `listening`, `speaking`, `vocabulary`, `mathematics`, `science`, `ict`, `revision`, `quiz`, `oral_drill`

---

## Moteurs React (chemins et format)

### ExercisePlayer.tsx
Route `oral_drill`, `mcq` ET `multiple_choice`, `handwriting`, `fill_in`

### Formats de contenu JSON
**oral_drill:**
```json
{"type":"oral_drill","illustration":"🏠","items":[{"text":"...","audio_hint":"..."}]}
```
**mcq:**
```json
{"type":"mcq","illustration":"🎯","questions":[{"text":"...","options":["a","b","c","d"],"answer":1}]}
```
**fill_in:**
```json
{"type":"fill_in","illustration":"✏️","sentences":[{"text":"... ___ ...","answer":"mot"}]}
```
**handwriting:**
```json
{"type":"handwriting","illustration":"✍️","items":[{"text":"A","hint":"description du tracé"}]}
```

### Patch illustration appliqué ✅
Tous les moteurs (OralDrill, MCQ, FillIn) affichent `content.illustration` (emoji) si présent.
MCQ supporte maintenant `q.text` ET `q.question`, réponse par index ET par texte.
FillIn supporte `sentences` ET `items`.

---

## Structure DB (chaîne complète)
```
subjects (level_id)
  → integrated_themes (subject_id)
    → units (integrated_theme_id)
      → lessons (unit_id)
        → exercises (lesson_id)
```

## Subjects par classe
| Level | ID | Matière |
|-------|----|---------|
| Class 1 (5) | 12 | English |
| Class 1 (5) | 13 | Mathematics |
| Class 1 (5) | 14 | French |
| Class 1 (5) | 15 | Science and Technology |
| Class 1 (5) | 16 | Reading |
| Class 1 (5) | 17 | Handwriting |
| Class 2 (6) | 18 | English |
| Class 2 (6) | 19 | Mathematics |
| Class 2 (6) | 20 | French |
| Class 2 (6) | 21 | Science and Technology |
| Class 2 (6) | 22 | Reading |
| Class 2 (6) | 23 | Handwriting |
| Class 3 (7) | 24 | English |
| Class 3 (7) | 25 | Mathematics |
| Class 3 (7) | 26 | French |
| Class 3 (7) | 27 | Science and Technology |
| Class 3 (7) | 28 | ICT |
| Class 3 (7) | 29 | Citizenship |
| Class 4 (8) | 30 | English |
| Class 4 (8) | 31 | Mathematics |
| Class 4 (8) | 32 | French |
| Class 4 (8) | 33 | Science and Technology |
| Class 4 (8) | 34 | ICT |
| Class 4 (8) | 35 | Citizenship |
| Class 5 (9) | 36 | English |
| Class 5 (9) | 37 | Mathematics |
| Class 5 (9) | 38 | French |
| Class 5 (9) | 39 | Science and Technology |
| Class 5 (9) | 40 | ICT |
| Class 5 (9) | 41 | Citizenship |
| Class 6 (10) | 42 | English |
| Class 6 (10) | 43 | Mathematics |
| Class 6 (10) | 44 | French |
| Class 6 (10) | 45 | Science and Technology |
| Class 6 (10) | 46 | ICT |
| Class 6 (10) | 47 | Citizenship |

---

## Livres officiels MINEDUB 2024-2025 (anglophones)

| Classe | Matière | Titre | Éditeur |
|--------|---------|-------|---------|
| C1 | Handwriting | Handwriting Workbook, Class 1 | ATEMEC |
| C1 | Sound/Reading | Sound and Word Building C1&2 | ATEMEC |
| C1 | English | Winners in English, Class 1 | NMI Education |
| C1 | French | Parlons Français C1 | COSMOS ✅ |
| C1 | Mathematics | Innovative Mathematics C1 | DESTINY Prints ✅ |
| C1 | Science | Standard Science and Technology | BECHACAM |
| C1 | Social Studies | The Good Citizen C1&2 | COSMOS |
| C1 | ICT | Winners in ICT C1&2 | NMI |
| C2 | Handwriting | Emergence in Handwriting | MONDOUX |
| C2 | English | Winners in English, Class 2 | NMI Education |
| C2 | French | J'apprends le Français 2 | ANUCAM |
| C2 | Science | Standard Science and Technology | BECHACAM |
| C2 | Social Studies | The Good Citizen C1&2 | COSMOS |
| C3 | English | Winners in English Class 3 | NMI Education |
| C3 | French | French Class 3 | AFRICA Education |
| C3 | Science | Science and Technology Class 3 | ATEMEC |
| C3 | Social Studies | Social Studies Class 3 | GLOBAL Industries |
| C3 | ICT | ICT Classes 3 and 4 | ATEMEC ✅ (photo dispo) |
| C4 | English | Winner in English Class 4 | NMI Education |
| C4 | French | French Class 4 | AFRICA Education ✅ |
| C4 | Mathematics | Innovative Mathematics Class 4 | DESTINY Print ✅ |
| C4 | Science | Science and Technology Class 4 | METROPOLITAIN |
| C4 | Social Studies | Winner in Social Studies C4 | NMI Education |
| C4 | ICT | ICT Classes 3 and 4 | ATEMEC ✅ |
| C5 | English | English Class 5 | LONGHORN ✅ |
| C5 | French | French Class 5 | AFRICA Education ✅ |
| C5 | Mathematics | Mathematics Class 5 | BECHACAM ✅ |
| C5 | Science | Winners in S&T Class 5 | NMI Education |
| C5 | ICT | Information and Communication Technology | BECHACAM |
| C5 | Social Studies | Winner in Social Studies C5&6 | NMI Education |
| C6 | English | English Class 6 | BECHACAM ✅ |
| C6 | Mathematics | Mathematics Class 6 | BECHACAM ✅ |
| C6 | French | French Class 6 | AFRICA Education |
| C6 | Science | Science and Technology Class 6 | LEGEND Publishers |
| C6 | ICT | ICT Classes 5 and 6 | BECHACAM |
| C6 | Social Studies | Winner in Social Studies C5&6 | NMI Education |

---

## État du curriculum (exercices seedés)

| Classe | English | Maths | French | Science | ICT | Citizenship | Reading | Handwriting |
|--------|---------|-------|--------|---------|-----|-------------|---------|-------------|
| C1 | ✅ | ✅ | ✅ | ✅* | — | — | ✅* | ✅* |
| C2 | ✅ | ✅ | ❌ | ❌ | — | — | ❌ | ❌ |
| C3 | ✅ | ✅ | ❌ | ❌ | ✅* | ✅* | — | — |
| C4 | ✅ | ✅ | ✅ | ✅* | ✅* | ✅* | — | — |
| C5 | ✅ | ✅ | ✅ | ✅* | ✅* | ✅* | — | — |
| C6 | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | — | — |

*= seedé dans cette session, à valider après `php artisan db:seed --class=ScienceIctCitizenshipSeeder`

---

## Seeder en attente d'exécution
```powershell
python 'C:\Users\Kamgang David\Downloads\patch_science_ict_citizenship_seeder.py'
php artisan db:seed --class=ScienceIctCitizenshipSeeder
```

---

## PDFs officiels MINEDUB disponibles (téléchargement libre)
- C1&2 : https://s3.eu-west-2.amazonaws.com/ebase-bucket/branding/Cameroon-Primary-School-_Level_One.pdf
- C3&4 : https://s3.eu-west-2.amazonaws.com/ebase-bucket/branding/pages/Cameroon-Primary-School_English-Subsystem_Level2_class3-4.pdf
- C5&6 : https://s3.eu-west-2.amazonaws.com/ebase-bucket/branding/Cameroon-Primary-School-_Level_Three_class5-6.pdf
- Index : https://ebaselearning.org/resources/curriculum-policy-documents

---

## À faire en prochaine session

### Priorité HAUTE
1. **Exécuter ScienceIctCitizenshipSeeder** (patch prêt)
2. **Bulletins de notes** dans Filament — saisir les vraies notes :
   - Irma (Class 1) : moyenne 15.54, rang 27th
   - Mark (Class 4) : moyenne 7.32, rang 34th ⚠️ Weak performance
   - Ruth (Class 5) : moyenne 14.91, rang 71st
3. **Plans de remédiation Mark** :
   - Mathématiques (27/100 en PM4)
   - Science (22/100 en PM4)
4. **Seeders manquants** : French C2, C3, C6 + Science C2, C3, C6 + ICT C6 + Citizenship C6

### Priorité MOYENNE
5. **Mode TV LG webOS** — interface `/tv`
6. **Notifications quotidiennes**
7. **Audio MP3** — remplacer Web Speech API

### Priorité BASSE
8. **Illustrations** — MAJ emojis → vraies images quand budget dispo
9. **Animations** entre exercices
10. **Récompenses** — badges, étoiles, confetti

---

## Workflow de développement
- Patches Python téléchargés depuis Downloads : `python 'C:\Users\Kamgang David\Downloads\patch_xxx.py'`
- Rebuild prod : `npm run build:react`
- Dev : `npm run dev:react` (port 5173)
- PHP 8.3 actif (pas 8.4)
- Filament v4 : `form()` prend `Schema $form`

## Commandes utiles
```bash
# Dev
npm run dev:react

# Build prod
npm run build:react

# Seed
php artisan db:seed --class=NomDuSeeder

# Vérifier les units sans lessons
php artisan tinker --execute="dd(DB::table('units')->whereNotIn('id', DB::table('lessons')->pluck('unit_id'))->count());"

# Cache
php artisan cache:clear && php artisan config:clear
```
