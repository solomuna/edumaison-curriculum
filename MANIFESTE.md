# Manifeste — Stack EduMaison

Ce texte s'adresse à toute personne ou tout agent (Claude, Codex, autre IA, développeur humain) qui touche au code, aux données ou aux serveurs d'EduMaison. Il est court à dessein : chaque règle vient d'un incident réel.

EduMaison est utilisé chaque jour par des enfants. Leur progression, leurs enregistrements et la confiance de leurs parents passent avant la vitesse de livraison.

---

## 1. Git est la seule source de vérité

- Le code vit dans ce dépôt (`solomuna/edumaison-curriculum`). Nulle part ailleurs.
- Pas de copie de travail parallèle, pas de « miroir local », pas de dossier `.codex-work`. Un fichier qui n'est pas dans git n'existe pas.
- Chaque lot de travail est fait sur une branche (`feat/…`, `fix/…`, `wip/…`), se termine par un commit, et entre dans `master` par une pull request dont la CI est verte.
- `master` ne contient que ce qui est prêt pour la production. Le travail inachevé vit dans une branche `wip/…`.

> *Incident, août–septembre 2026 : un agent a travaillé hors dépôt et déployé fichier par fichier pendant six semaines. Git ne correspondait plus à la production ; un simple redéploiement aurait tout effacé. Il a fallu une reconstitution complète pour rapatrier le travail.*

## 2. On ne déploie que `master`

Le seul chemin vers la production est :

1. PR fusionnée dans `master`, CI verte.
2. Sauvegarde de la base PostgreSQL et du code actif, vérifiée (taille, empreinte, restaurable).
3. Mise à jour de `/opt/edumaison-curriculum` depuis git, au SHA exact de `master`.
4. `composer install --no-dev`, build React, migrations.
5. Rechargement gracieux de PHP-FPM (`opcache.validate_timestamps=Off` : sans rechargement, l'ancien code continue de tourner).
6. Contrôles : `/app` et `/mama` en HTTP 200, routes protégées en 401 sans session, conteneurs `running` sans redémarrage, journaux sans erreur.
7. SHA déployé noté dans `CODEX_STATUS.md`.

Interdits : `scp` ou `tar` de fichiers isolés vers le serveur, modifications directes sur le serveur, déploiement depuis une branche autre que `master`.

## 3. La production n'est pas un bac à sable

- On commence toujours par des diagnostics en lecture seule.
- Toute mutation (fichiers, base, services, secrets) demande l'accord explicite du propriétaire, pour cette opération précise. Un accord passé ne couvre pas la suivante.
- Pas de suppression de sauvegarde, pas de rotation de secrets, pas de redémarrage de service sans accord.
- Les tests de migration et de restauration se font sur une base jetable (SQLite ou PostgreSQL temporaire), jamais sur la base de production.

## 4. Un contrôle qui échoue signale un vrai problème

- Si `artisan route:list`, un test, un audit ou une vérification échoue, on corrige la cause. On ne retire pas le contrôle pour passer.
- La CI (`composer audit`, `php artisan test`, `npm run build`) doit rester verte. Une CI rouge est une urgence, pas un bruit de fond.

> *Incident, 28/09/2026 : `route:list` échouait parce qu'une route pointait vers un contrôleur jamais déployé. Le contrôle a été retiré au lieu de corriger la route ; le bug est resté en production.*

## 5. Les enfants d'abord

- Aucune donnée personnelle d'enfant (nom, date de naissance, enregistrement audio, texte écrit) dans les journaux, rapports, messages de commit ou fichiers de suivi. On compte, on ne nomme pas.
- Aucun mot de passe, PIN, jeton, clé ou valeur de `.env` écrit dans le code, la documentation ou les fichiers de suivi.
- L'audio d'un enfant n'est enregistré et analysé qu'avec le consentement du foyer.
- Les données d'un foyer ne sont jamais visibles par un autre (isolation stricte entre familles).

## 6. Une pédagogie honnête

- La note est calculée par le serveur à partir de la clé de réponse. On ne fait jamais confiance au score envoyé par le téléphone.
- Ce qui n'est pas vérifiable n'est pas présenté comme réussi : `practice_only` et `pending_review` ne sont pas des validations.
- Aucun contenu inventé. Les exercices suivent le programme officiel (MINEDUB). Les contenus en langues nationales (Fe'fe'/Nufi, Ghomala'…) ne sont publiés qu'après revue par un locuteur compétent, puis revue pédagogique.
- Une proposition d'une famille reste privée à ce foyer. Elle ne devient jamais automatiquement un contenu global.

## 7. Traçabilité

- Messages de commit clairs : ce qui change et pourquoi.
- `CODEX_STATUS.md` (racine du dépôt) est le journal de bord : travail fait, vérifications, blocages, prochaine action sûre. Il est committé avec le travail qu'il décrit.
- On dit ce qui a été vérifié et ce qui ne l'a pas été. « Déployé » veut dire contrôlé en production, pas « copié ».

## 8. En cas de doute

On s'arrête et on demande. Une question coûte une minute ; une production cassée coûte la confiance d'une famille.

---

*Rédigé le 2026-09-29. Toute modification de ce manifeste passe, comme le reste, par une pull request.*
