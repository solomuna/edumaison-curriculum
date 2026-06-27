# Intégration EduMaison ↔ EduMaison-Campus

Ce document décrit comment **EduMaison** (l'app enfant à la maison, alias *Learning* côté Campus) se branche sur **EduMaison-Campus** (la plateforme SaaS de gestion d'école, alias *School*, Laravel multi-tenant).

> ⚠️ **État au 2026-05-29** : Côté EduMaison, la plomberie est en place dans les deux sens (Campus → nous : endpoints Bearer-auth opérationnels ; nous → Campus : client HTTP prêt mais en attente de l'API Campus). Côté Campus, le service `LearningClient` existe déjà et consomme un contrat précis (cf. `Modules/People/Services/LearningClient.php` + tests).
>
> **Sens 1 (Campus → nous) déjà actif** : voir *Endpoints exposés par FastAPI* ci-dessous.
> **Sens 2 (nous → Campus)** en attente que Campus expose son API tenant (sprint à venir).

## Vocabulaire

| Côté Campus | = | Côté nous |
|---|---|---|
| EduMaison-School (le SaaS d'école) | | EduMaison-Campus |
| **EduMaison Learning** | | EduMaison (FastAPI + Laravel) |
| `students.learning_child_id` | | `children.id` |
| `schools.id` | | `children.campus_school_id` |
| `students.matricule` ou id | | `children.campus_student_id` |

---

## Vue d'ensemble

```
                       ┌────────────────────────┐
                       │  EduMaison-Campus      │
   ┌─────────────┐     │  (Laravel 13, PG)      │
   │   Tablette  │     │  • Multi-tenant        │
   │   enfant    │     │  • Notation, Bulletins │
   └──────┬──────┘     │  • Devoirs, Présences  │
          │            │  • RGPD, Backups       │
          │            └──────────┬─────────────┘
          ▼                       │
   ┌────────────────┐             │ HTTP (Bearer Sanctum
   │  EduMaison     │             │       + X-Tenant)
   │  (FastAPI +    │◀────────────┘
   │   Laravel)     │
   │                │   ▲
   │  • Exercices   │   │ HTTP (lecture)
   │  • Progression │   │
   │  • Remédiation │───┘
   └────────────────┘
```

**Sens du flux actuel** : EduMaison **consomme** Campus (lecture des bulletins, devoirs). Le sens inverse (Campus consomme la progression maison) viendra plus tard.

---

## Mapping élève

Un enfant EduMaison peut être lié à un élève d'une école Campus via :

| Champ                 | Type        | Sens                                                                  |
| --------------------- | ----------- | --------------------------------------------------------------------- |
| `campus_school_id`    | bigint      | `schools.id` côté Campus                                              |
| `campus_student_id`   | varchar(64) | matricule ou id élève dans le schema tenant Campus                    |
| `campus_last_sync_at` | timestamp   | dernière synchronisation réussie (NULL si jamais)                     |

Couple unique (un seul enfant EduMaison par élève Campus). Quand les deux sont `NULL`, l'enfant fonctionne en autonomie.

Migrations : `2026_05_29_175643_align_children_with_campus_contract.php` (corrige le faux départ `tenant_slug/external_student_id` du même jour).

Pour lier un enfant manuellement (en attendant l'UI Mama Judi) :

```sql
UPDATE children
SET campus_school_id = 1,
    campus_student_id = 'STJ-0042'
WHERE id = 1;
```

## Auth Bearer (table `campus_tokens`)

Chaque école Campus reçoit son propre Personal Access Token pour appeler notre API. Stocké en SHA-256, jamais en clair.

```php
// Côté EduMaison (Laravel artisan tinker), pour émettre un token :
use App\Models\CampusToken;
[$token, $clear] = CampusToken::issue(1, 'Saint-Joseph - prod');
echo $clear; // <-- à copier dans Campus (CommunicationSetting `learning.api_token`)
```

Le cleartext est affiché une seule fois. Pour révoquer : `$token->revoke();`.

Modèle : `app/Models/CampusToken.php`. Migration : `2026_05_29_175649_create_campus_tokens_table.php`.

---

## Configuration

Dans `edumaison-api/.env` :

```ini
CAMPUS_BASE_URL=http://edumaison-campus.test   # URL centrale Campus, sans slash final
CAMPUS_API_TOKEN=                              # Personal Access Token Sanctum (à générer côté Campus)
CAMPUS_TIMEOUT_S=10                            # timeout HTTP (par défaut)
```

Tant que `CAMPUS_BASE_URL` **ou** `CAMPUS_API_TOKEN` sont vides, la liaison est désactivée : tous les endpoints `/api/campus/...` renvoient `503` avec un message clair.

Après modif du `.env`, redémarrer FastAPI :

```powershell
nssm restart EduMaisonAPI
```

---

## Endpoints exposés par FastAPI

### Sens 1 — Campus → nous (ACTIF, contrat verrouillé)

Préfixe `/api/learning/*`, **Bearer token requis** (cf. table `campus_tokens` ci-dessus).

| Méthode | Chemin                                | Effet                                                                                           |
| ------- | ------------------------------------- | ----------------------------------------------------------------------------------------------- |
| GET     | `/children/{id}`                      | `verifyChild` — info enfant (id, nom, niveau, avatar). 200/404                                  |
| GET     | `/children/{id}/bulletin?period=2026-T2` | `fetchSummary` — XP, streak, exercices, moyennes par matière. Pour section « Progrès Learning » du bulletin Campus |
| POST    | `/parents/link` (body `{child_id, pin}`) | `verifyParentPin` — vérifie PIN Mama Judi. Retourne `{linked: true}` ou 404 `pin_invalid`     |

**⚠️ À faire côté Campus** : dans `Modules/People/Services/LearningClient.php`, préfixer les routes par `/api/learning/` au lieu de `/api/` (1 ligne par méthode). Sinon les appels tombent sur les routes internes EduMaison sans Bearer auth.

Codes de retour :

- `200` succès
- `401` token Bearer manquant, invalide ou révoqué (mappé `auth_failed` côté Campus)
- `404` enfant inexistant ou PIN invalide (mappés `child_not_found` / `pin_invalid` côté Campus)

Test de contrat : `c:/laragon/www/edumaison-api/tests/test_learning_contract.py`. À relancer après chaque modif :

```powershell
cd C:\laragon\www\edumaison-api
python -X utf8 tests\test_learning_contract.py
```

### Sens 2 — nous → Campus (V2, en attente)

Préfixe `/api/campus/*`. Renvoient `503` tant que `CAMPUS_BASE_URL` + `CAMPUS_API_TOKEN` ne sont pas configurés dans `.env`.

| Méthode | Chemin                                     | Effet                                                                              |
| ------- | ------------------------------------------ | ---------------------------------------------------------------------------------- |
| GET     | `/status`                                  | État de la liaison (pas d'appel réseau)                                            |
| GET     | `/ping?tenant_slug=demo`                   | Smoke test : appelle Campus pour vérifier qu'il répond                             |
| GET     | `/children/{id}/profile`                   | Profil Campus de l'enfant lié (lecture seule)                                       |
| POST    | `/children/{id}/sync-marks?period=2026-T2` | Récupère les notes Campus → écrit dans `school_results` (STUB persistance)         |
| GET     | `/children/{id}/homework?status=pending`   | Liste des devoirs Campus                                                           |

---

## Contrat attendu côté Campus

Les endpoints à exposer côté Campus (sprint à venir, pas encore livré) :

### `GET /api/health`

```json
{ "status": "ok", "version": "V1.20" }
```

### `GET /api/students/{external_id}/profile`

```json
{
  "id": "42",
  "first_name": "Ada",
  "last_name": "Tchounkeu",
  "class_name": "CM1 A",
  "tenant_slug": "demo",
  "birth_date": "2014-03-12"
}
```

### `GET /api/students/{external_id}/marks?period=2026-T2`

```json
[
  {
    "subject_id": 7,
    "subject_name": "Mathematics",
    "average_score": 14.5,
    "max_score": 20,
    "appreciation": "Good",
    "teacher_comment": "Bon trimestre. Continuer les efforts en géométrie.",
    "period": "2026-T2"
  }
]
```

### `GET /api/students/{external_id}/homework?status=pending`

```json
[
  {
    "id": "h-9981",
    "title": "Exercices p.42 #1-5",
    "subject_name": "French",
    "due_at": "2026-06-02T17:00:00Z",
    "instructions": "Faire les 5 exercices en écriture liée.",
    "status": "pending"
  }
]
```

### En-têtes attendus côté Campus pour toutes les requêtes

| En-tête         | Valeur                                |
| --------------- | ------------------------------------- |
| `Authorization` | `Bearer <PAT_Sanctum>`                |
| `Accept`        | `application/json`                    |
| `X-Tenant`      | slug du tenant école (ex: `demo`)     |

C'est `X-Tenant` qui permet à Campus de résoudre l'école à interroger sans dépendre du host name (utile pour le dev local). À discuter avec Campus : peut-être préférable de basculer sur un domaine tenant explicite (`demo.edumaison-campus.test`) plus tard.

---

## Architecture côté EduMaison

```
edumaison-api/
├── core/
│   ├── config.py            ← CAMPUS_BASE_URL, CAMPUS_API_TOKEN, campus_enabled()
│   └── campus_client.py     ← CampusClient(tenant_slug) + CampusUnavailable/CampusError
├── api/routes/
│   └── campus.py            ← endpoints /api/campus/*
└── main.py                  ← include_router(campus.router)

edumaison/
├── app/Models/Child.php     ← +tenant_slug, +external_student_id, +isLinkedToCampus()
└── database/migrations/
    └── 2026_05_29_140851_add_campus_link_to_children_table.php
```

---

## Roadmap

### Phase 1 — Plomberie (✅ fait côté EduMaison)
- [x] Migration `children.tenant_slug` + `external_student_id` + `campus_last_sync_at`
- [x] Modèle Eloquent mis à jour
- [x] Client HTTP `CampusClient` (httpx)
- [x] Endpoints FastAPI `/api/campus/*` (renvoient 503 propre tant que Campus muet)
- [x] Doc d'intégration

### Phase 2 — Campus expose son API (à faire côté Campus, hors de ce repo)
- [ ] Route file `routes/api-external.php` ou extension de `routes/tenant.php` avec middleware `auth:sanctum`
- [ ] Endpoints `health`, `students/{id}/profile`, `marks`, `homework`
- [ ] UI super-admin pour générer un Personal Access Token par école
- [ ] Documentation OpenAPI / Scribe

### Phase 3 — Brancher
- [ ] Renseigner `CAMPUS_BASE_URL` + `CAMPUS_API_TOKEN` dans `edumaison-api/.env`
- [ ] Tester `/api/campus/status` puis `/api/campus/ping`
- [ ] Lier un enfant pilote (SQL ou UI Mama Judi)
- [ ] Tester `/api/campus/children/{id}/profile`
- [ ] Compléter `sync-marks` : mapping marks Campus → `school_results` (UPSERT)

### Phase 4 — UX
- [ ] Onboarding parent : choisir l'école dans Mama Judi
- [ ] Indicateur dans la home page enfant ("📡 Synchro école : il y a 2 h")
- [ ] Affichage des devoirs Campus dans la grille des matières
- [ ] Tâche planifiée nuit : sync auto des marks pour tous les enfants liés
- [ ] Sens inverse : Campus consomme la progression maison

---

## Tester (smoke) la plomberie aujourd'hui

```powershell
# 1. Liaison désactivée (default)
curl http://msi-laptop.local/api/campus/status
# → { "enabled": false, ... }

# 2. Lier un enfant pilote (en SQL)
psql -U postgres -d edumaison -c "UPDATE children SET tenant_slug='demo', external_student_id='42' WHERE id=1;"

# 3. Vérifier qu'un appel renvoie 503 propre (Campus n'expose rien encore)
curl http://msi-laptop.local/api/campus/children/1/profile
# → { "detail": "Liaison Campus non configuree..." }
```

Une fois `CAMPUS_BASE_URL` + token configurés et Campus expose son API, les mêmes appels retourneront du JSON utile.
