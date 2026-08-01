# Contexte de reprise — Curriculum

## Production

- Projet : EduMaison Curriculum
- Dépôt et répertoire : `/opt/edumaison-curriculum`
- Branche : `master`
- URL : `https://edumaison.kamgangdavid.com` (accès protégé par authentification HTTP Basic)
- Docker Compose : application, nginx, queue, cron, PostgreSQL et Redis.
- Laravel : production, debug désactivé, file d'attente et cache en base.

## Exploitation

- Sauvegarde PostgreSQL : `php artisan app:backup-database`, chaque jour à 01:45.
- Contrôle de fraîcheur : `php artisan app:backup-database --check`, chaque jour à 06:15.
- Fichiers : `storage/app/private/backups`, rétention définie par `BACKUP_KEEP_DAYS` (7 jours par défaut).
- Une copie hors serveur reste recommandée : ces sauvegardes locales ne protègent pas contre la perte du VPS.
- CI GitHub : validation Composer, audit des dépendances, tests Laravel SQLite et build frontend.

## État connu

- Services sains lors de l'audit du 1er août 2026.
- Les dernières erreurs Laravel observées étaient historiques (6 juillet 2026), sans erreur récente.
- La réponse HTTP 401 sur la page publique est attendue tant que l'authentification Basic est active.

## Vérifications rapides

```bash
cd /opt/edumaison-curriculum
docker compose ps
docker compose exec cron php artisan schedule:list
docker compose exec cron php artisan app:backup-database --check
curl -I https://edumaison.kamgangdavid.com
git status --short --branch
```
