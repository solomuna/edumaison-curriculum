# Consignes agents — EduMaison Curriculum

**Lire d'abord [MANIFESTE.md](MANIFESTE.md)** : règles non négociables pour quiconque travaille sur la stack. Ce fichier en est le résumé opérationnel.

## Source de vérité : ce dépôt git

- Le seul emplacement de travail est ce dépôt (`C:\laragon\www\edumaison`, remote `solomuna/edumaison-curriculum`).
- Interdit : créer ou utiliser une copie de travail hors dépôt (ex. `ArkiSuite/.codex-work/…`, dossiers `Documents/Codex/…`). Les fichiers temporaires de test vont dans un dossier ignoré par git, jamais comme copie parallèle du code.
- Chaque lot de travail se fait sur une branche (`feat/…`, `fix/…`) et se termine par un commit. Un travail non committé n'existe pas.

## Déploiement production (`/opt/edumaison-curriculum` sur le VPS)

- On ne déploie que du code **committé et poussé**. Noter le SHA du commit déployé dans `CODEX_STATUS.md`.
- Interdit : copier des fichiers isolés vers le serveur (scp/tar) depuis une copie locale non committée.
- Si un fichier de production diffère du dépôt, on le rapatrie d'abord dans git (commit), puis on déploie.
- Un contrôle de déploiement qui échoue (ex. `artisan route:list`) signale un vrai problème : on corrige la cause, on ne retire pas le contrôle.
- Sauvegarde + autorisation explicite de l'utilisateur avant toute mutation de production (règles globales inchangées).

## Continuité

- `CODEX_STATUS.md` à la racine de ce dépôt est le journal de suivi ; il est committé avec le travail qu'il décrit.
