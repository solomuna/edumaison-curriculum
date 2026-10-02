# Codex Status

## 2026-10-02 - Lot de nuit deploye (contenus, alerte, oral, types) : feaa395

- Deploye via `docker/deploy.sh feaa395` (PR #25, #26, #27, #28, #29, #30). Precedent : `cd1bb63`. Sauvegarde : `/home/david/edumaison-backups/deploy-20261002T075935Z`. Aucune migration.
- #25 : contenus mal lus ou mal notes corriges (QCM aux choix stockes en texte, QCM sans type, reponses numeriques en texte mal notees par le serveur, Venn a intersection separee, associations a doublons a droite).
- #27 : rapport d'audit des 2 794 exercices actifs (`docs/audits/2026-10-02-contenus.md`).
- #28 : `app:attempts-health` planifiee toutes les heures (verifie dans `schedule:list`). Sans `ALERT_WEBHOOK_URL` / `ALERT_EMAIL`, l'alerte ne va que dans les journaux.
- #29 : exercice oral dans la boucle de lecon (seuil 70 % de mots reconnus ; contenu envoye inchange).
- #30 : zero erreur TypeScript, `npm run typecheck` en CI ; bugs corriges : sortie de remediation, prenom vide en remediation, niveau par defaut de la revision.
- #26 fusionne mais seeder NON lance : `php artisan db:seed --class=NurseryLostEmojiRepairSeeder` attend l'accord explicite.
- Controles : `/app`, `/mama` 200 x3, API 401, assets 200, conteneurs up, `composer audit` propre, aucune erreur Laravel.
- En attente : #24 (fichiers audio ElevenLabs), adresse d'alerte sur le serveur, decisions de l'audit (doublons, contenus defectueux), test de l'oral au vrai micro sur tablette.

## 2026-09-30 - Exercices oraux valides par correspondance des mots : cd1bb63

- Deploye via `docker/deploy.sh cd1bb63` (PR #23). Precedent : `7810b4c`. Sauvegarde : `/home/david/edumaison-backups/deploy-20260930T083657Z`. Aucune migration.
- Decision utilisateur (option A, approche Duolingo) : une phrase dite est notee par la correspondance des mots reconnus par le navigateur ; tentative `auto_checked`, `pronunciation_verified=false`, methode `speech_transcript_match`. Phrase sautee = `practice_only`. Azure (si active plus tard) reste prioritaire.
- Limite acceptee : transcription fournie par le navigateur (falsifiable). Options futures : transcription serveur (Whisper) ou Azure.
- Controles : deploiement OK, regle active cote serveur, aucune erreur Laravel.
- Pistes en attente de decision : pack de voix Mama Judi commun + voix enregistrees par les parents (retirer les prenoms codes en dur dans MamaJudi.ts), reconnaissance d'ecriture (ML Kit Digital Ink, app Android), alerte en cas d'echecs d'enregistrement en serie.

## 2026-09-30 - Reponses effacees par la validation corrigees : 7810b4c

- Deploye via `docker/deploy.sh 7810b4c` (PR #21). Precedent : `301d35c`. Sauvegarde : `/home/david/edumaison-backups/deploy-20260930T074801Z`. Aucune migration.
- Cause : les regles `answers.items.*.audio_data_url` / `assessment_token` (ajoutees vers le 20/08 pour l'oral) faisaient que `validated()` vidait `answers` pour le QCM, le texte a trous et la dictee (422 « A response is required… ») et perdait la transcription de l'oral. Aucune tentative de ces types enregistree entre la semaine du 17/08 et le 30/09 : progression perdue, irrecuperable.
- Correction : `withSubmittedAnswers` (regles conservees, notation sur les reponses envoyees) + test de regression.
- Verifie en production : tentatives enregistrees (HTTP 200) pour un oral (practice_only) et « Write the Greeting » (auto_checked, detail par phrase coherent avec l'ecran).
- Piste proposee : alerte serveur si les enregistrements de tentatives echouent en serie.

## 2026-09-30 - Correctifs remontes par le test sur tablette deployes : 301d35c

- Deploye via `docker/deploy.sh 301d35c` (PR #17, #18, #19). Precedent : `0770ce6`. Sauvegarde : `/home/david/edumaison-backups/deploy-20260930T065825Z`. Aucune migration.
- Bug 1 (pas de bouton de validation) : la barre de navigation de l'accueil (fixe, z-index 100) recouvrait le pied de lecon depuis la page des matieres ; masquee pendant une lecon. Clavier virtuel : `interactive-widget=resizes-content` + repli `--kb-inset`.
- Bug 2 (reponses justes comptees fausses) : la touche Entree verifiait ET passait a la suite (bandeau jamais visible) ; un appui deja traite est desormais ignore.
- Decision utilisateur : dans les textes a trous, majuscules, accents et ponctuation comptent (« Goodbye! »). Serveur (`exactAnswer`) et ecran alignes ; bandeau d'erreur avec les caracteres manquants surlignes et en trop barres.
- Serveur, associations : comparaison en multiensemble (mots repetes a gauche).
- Controles : `/app`, `/mama` 200 x3, API 401, assets 200, `interactive-widget` present, CSS masquant la barre, bundle avec l'affichage des differences, `exactAnswer` actif, extension intl presente, aucune erreur Laravel.
- Verification utilisateur restante : rejouer « Write the Greeting » sur tablette.

## 2026-09-29 - Correctifs espace Mama et ardoise deployes : 0770ce6

- Deploye via `docker/deploy.sh 0770ce6` (PR #15). Precedent : `346eeea`. Sauvegarde : `/home/david/edumaison-backups/deploy-20260929T211136Z`. Aucune migration.
- `App.tsx` : le chemin `/mama` de l'app principale utilise le vrai `MamaJudiApp` (charge a la demande) au lieu d'un `MamaSpace` inexistant. Le web sert toujours `mama.html`.
- Telephone : bouton ardoise en haut a droite, ne chevauche plus les reponses.
- Controles : `/app`, `/mama` 200 x3, API 401, assets 200, aucune erreur Laravel.

## 2026-09-29 - Reprise des questions ratees deployee : 346eeea

- Deploye via `docker/deploy.sh 346eeea` (PR #13). Precedent : `c1f3c15`. Sauvegarde : `/home/david/edumaison-backups/deploy-20260929T210026Z`. Aucune migration.
- QCM, texte a trous et dictee : une question ratee revient en fin de lecon (au plus 2 reprises, badge « On reessaie celle-ci »). Seule la premiere reponse est notee et envoyee ; les ecoutes de reprise de la dictee ne sont pas envoyees.
- Controles : `/app`, `/mama` 200 x3, API 401, assets 200, bundle contenant la reprise, aucune erreur Laravel.

## 2026-09-29 - Dictee, production ecrite et ecriture sans cursive deployees : c1f3c15

- Deploye via `docker/deploy.sh c1f3c15` (PR #11). Precedent : `cac9ddf`. Sauvegarde : `/home/david/edumaison-backups/deploy-20260929T204413Z`. Aucune migration.
- Ecriture manuscrite : modeles toujours en lettres droites (`print`), meme pour les contenus Class 3 en base reglés `upright_joint_script` ; seeder aligne (non execute). Decision utilisateur : pas d'ecriture cursive dans les exercices d'ecriture.
- Dictee : boucle interactive (Ecouter, VERIFIER, bandeau vert >= 80 %, detail par critere, ecran de fin). Production ecrite : bouton « Envoyer a mon parent », refus serveur affiche avec « Corriger mon texte ». Contrats serveur inchanges.
- Enregistrement tolerant aux erreurs partout (message + « Reessayer »).
- Controles : `/app`, `/mama` 200 x3, API 401, assets 200, bundle contenant la nouvelle dictee et la production ecrite, aucune erreur Laravel.

## 2026-09-29 - Moteurs d'exercices harmonises deployes : cac9ddf

- Deploye via `docker/deploy.sh cac9ddf` (PR #9). Precedent : `262f781`. Sauvegarde : `/home/david/edumaison-backups/deploy-20260929T200606Z`. Aucune migration.
- Un seul bouton VERIFIER (cadre `LessonShell`, `useLessonCheck`) pour vrai/faux, associations, remise en ordre, horloge, droite numerique, geometrie et Venn ; texte de correction dans le bandeau, en FR ou EN selon la matiere.
- Vrai/faux : choisir puis verifier. Venn : prendre un element puis toucher sa zone. Associations : paires colorees, correction listee.
- Controles : `/app`, `/mama` 200 x3, API 401, assets 200, bundle contenant les nouveaux composants, aucune erreur Laravel. Reponses envoyees au serveur inchangees (verifie sur les 7 moteurs).
- Reste connu : sur telephone, le bouton ardoise chevauche legerement le dernier bouton de zone du Venn.

## 2026-09-29 - Boucle de lecon interactive et sons synchronises deployes : 262f781

- Deploye via `docker/deploy.sh 262f781` (PR #7). Precedent : `a97da07`. Sauvegarde : `/home/david/edumaison-backups/deploy-20260929T193516Z`. Aucune migration.
- QCM et texte a trous : boucle Verifier -> bandeau vert/rouge avec Mama Judi -> Continuer, series, ecran de fin (XP, precision, meilleure serie). Vrai/faux, associations, remise en ordre, horloge, geometrie, droite numerique et Venn passent par `LessonShell` avec verdict immediat.
- Sons : prechargement et decodage Web Audio, MP3 legers dans `public/sounds/fx` (5,8 Mo -> 244 Ko), son a t=0 et voix a +250 ms ; plus de synthese vocale pour les retours immediats.
- La tentative est enregistree sur « Continuer » (une fois) ; « Recommencer » retire de l'ecran de fin.
- Controles : `/app`, `/mama` 200 x3, API 401, assets et sons `fx/*.mp3` 200 (audio/mpeg), bundle `main-ClU0vrMy.js` contenant la nouvelle boucle, aucune erreur Laravel.
- Verification utilisateur restante : sur tablette, actualiser completement l'application et jouer un QCM puis un texte a trous pour confirmer la synchronisation du son.
- Suites possibles : harmoniser le style et la langue des boutons internes des moteurs (horloge, geometrie, Venn, associations), puis la dictee et la production ecrite.

## 2026-09-29 - Premier deploiement depuis git : a97da07

- Production realignee sur git : `/opt/edumaison-curriculum` passe de `8436eb5` + 188 modifications non committees a `a97da07` (master), via `docker/deploy.sh`.
- Contenu : etat production precedent inchange, dependances composer securisees (Laravel 13.33, Symfony 7.4.x ; `composer audit` : 0), route `speaking-assessment` sans controleur retiree (desormais 404 au lieu de 500), manifeste et consignes agents. Aucune migration.
- Sauvegarde avant bascule : `/home/david/edumaison-backups/deploy-20260929T175802Z` (db.dump 57 tables, code.tgz, vendor.tgz, SHA256SUMS, PREVIOUS_HEAD). Un premier essai (`deploy-20260929T175333Z`) s'etait arrete a la sauvegarde, sans bascule.
- Controles : `/app` et `/mama` 200 x3, API protegee 401, assets et fonds d'environnement 200, `curriculum-app` et `curriculum-nginx` sans redemarrage, `curriculum-queue` relance par `queue:restart`, aucune erreur dans les journaux.
- Seules differences suivies restantes sur le serveur : `public/react/index.html` et `mama.html` (sortie du build). Les fichiers non suivis laisses par Codex (scripts d'audit a la racine, anciens dossiers `public/react-before-*`) sont conserves.
- Travail non deploye conserve sur `wip/codex-speaking-contributions` (Speaking/Azure, Atelier langue, pack Fe'fe').
- Prochaine action sure : tout deploiement suivant passe par PR -> master -> `bash docker/deploy.sh <sha>`.

## 2026-09-29 - Rapatriement dans git (Claude Code)

- Le code de production et le travail local Codex ont ete reverses dans git : branche `sync/production-2026-09-28`, commits `3076109` (etat production au 28/09) et `5d1c5de` (travaux non deployes : Speaking/Azure, contributions de langues, pack Fe'fe' Bafang).
- La copie `ArkiSuite/.codex-work/edumaison-family` est abandonnee. Travailler uniquement dans `C:\laragon\www\edumaison` (voir `AGENTS.md`).
- Bug de production connu : `routes/api.php` reference `SpeakingAssessmentController`, absent du serveur (POST speaking-assessment en 500, `artisan route:list`/`route:cache` en echec). A corriger par un deploiement depuis git.

## 2026-09-29 - Pack pilote Fe'fe' / Nufi Bafang Class 1 pret hors production

- Le seeder idempotent `database/seeders/BafangFefeClass1DraftPackSeeder.php` prepare `fefe-fmp-bafang-class-1-foundations` pour `National Languages and Cultures` Class 1. Il utilise le code de catalogue `fmp` et conserve `Bafang` comme variante familiale a confirmer, sans creer un nouveau code de langue.
- Le pack reste strictement `draft`, version `0.1.0-draft`, inactif, non publie, sans date de revue et sans exercice. Il ne peut donc ni apparaitre chez l'enfant, ni etre active, ni etre affecte.
- La fiche `docs/BAFANG_FEFE_CLASS1_REVIEW.md` definit six etapes : proposition familiale privee, revue par un locuteur Bafang, revue orthographique, revue audio, revue pedagogique puis decision de publication. Une proposition familiale ne devient jamais automatiquement un contenu global SaaS.
- Aucun mot, aucune phrase et aucun audio n'ont ete inventes ou importes. Glottolog confirme `fmp` pour Fe'fe' et repertorie notamment Nufi et Bafang comme appellations; l'archive SIL Cameroun 5177 inclut Fe'fe' dans une ressource alphabetique de reference. Ces ressources restent des references non reutilisables sans verification de droits.
- Preflight SQLite jetable reussi apres deux executions du seeder : une langue, un pack, zero exercice, statut enfant `awaiting_pack`, brouillon non compatible avec l'enfant et ecrasement d'un pack verifie/publie bloque. Marqueur `BAFANG_FEFE_CLASS1_DRAFT_PREFLIGHT_OK`; bases temporaires supprimees.
- Les regressions `NATIONAL_LANGUAGE_PROFILES_PREFLIGHT_OK` et `GHOMALA_CLASS3_DRAFT_PREFLIGHT_OK` passent. Le seeder et le preflight passent le lint PHP.
- Empreintes : seeder `d8275c9d2f86bb7cf22cc7d35e61d251fbf8a454b17afb3df98b38fb3d3f1f23`, dossier de revue `282ce855c00bacaf588a2eebc8f7be24ef08617eae5c399f4491547c8e74483f`, preflight `9e00d42f601f1ecbfc6535a731644b884ec38039544d52b5cb8e13c51a2d150b`.
- Rapport `.codex-tmp/bafang-fefe-class1-draft-preflight-20260929.json`, SHA-256 `42a4b6559203d5d3a390c835d60d5e7bfe51693936bf5e2e85f1589fd46fc248`. Production inchangee : aucun fichier, seeder ou contenu distant n'a ete modifie.
- Prochaine action sure : faire remplir la fiche par un ou plusieurs adultes locuteurs Bafang avec un petit premier lot de salutations et de formes familiales, puis effectuer les revues linguistique et pedagogique avant de creer le moindre exercice.

## 2026-09-28 - Langues familiales et affectations confirmees en production

- Le proprietaire a configure `Fe'fe' / Nufi` avec la variante familiale `Bafang` en priorite 0 et `Ghomala'` avec la variante familiale `Bamendjoun` en priorite 1.
- La verification en lecture seule confirme trois enfants actifs. Chacun possede exactement deux langues accessibles et exactement une langue de depart; aucun nom d'enfant n'a ete lu ni consigne dans le rapport.
- `/app` repond HTTP 200. `curriculum-app`, `curriculum-postgres` et `curriculum-nginx` restent `running`, chacun avec `RestartCount=0`.
- Rapport `.codex-tmp/family-languages-verification-20260928.json`, SHA-256 `1cb900aa7ec83ab3886da2c3d8359cc240aacd8603abcf43e377188a3ab3389e`.
- Prochaine action sure : verifier dans `National Languages and Cultures` que chaque enfant voit le selecteur de langue et peut basculer entre Bafang et Bamendjoun. Les activites linguistiques resteront en attente tant qu'un pack n'aura pas ete valide par un locuteur competent.

## 2026-09-28 - Proprietaire du foyer historique confirme en production

- Apres creation du compte par le proprietaire dans l'interface, la verification de production en lecture seule confirme exactement une liaison active avec le role `owner` pour le foyer historique. Aucune adresse e-mail, aucun nom et aucune credentielle n'ont ete lus ou consignes.
- Les trois enfants actifs sont toujours rattaches au meme foyer. `/app` repond HTTP 200 et `curriculum-app`, `curriculum-postgres`, `curriculum-nginx` restent `running`, chacun avec `RestartCount=0`.
- Rapport `.codex-tmp/legacy-family-owner-verification-20260928.json`, SHA-256 `22e9c8327695d19d7845938b5b9c0e19a32961ce5bf3950db0dfba2b03648ac5`.
- Prochaine action sure : dans `Parent view > Parametres`, ajouter les langues familiales, enregistrer, puis ouvrir chaque enfant pour choisir ses langues accessibles et sa langue de depart.

## 2026-09-28 - Rattachement du foyer historique deploye en production

- Apres validation locale et autorisation `feu vert`, le correctif a ete limite a quatre fichiers : `FamilyAuthController.php`, `routes/api.php`, `ParentDashboard.tsx` et le nouveau `LegacyFamilyClaim.tsx`. Aucune migration, aucun seeder et aucun compte parent n'ont ete crees pendant le deploiement.
- Le paquet contient 8 348 octets, SHA-256 `3a7608b94bb26a426a80a43cfa802fe09945956b0d9642e85216a0c7a74f0463`. Les trois sources existantes correspondaient exactement a la version active avant mutation et le nouveau composant etait absent; les quatre empreintes finales correspondent au candidat valide.
- Sauvegarde finale avant mutation : `/home/david/edumaison-backups/legacy-family-claim-20260928T045713Z`. Le dump PostgreSQL contient 518 977 octets et 520 entrees TOC, SHA-256 `4de1f4d10bd88aff1065406f0e0d9c6278d44310c8cad2225c6fdbcbe14ce774`. L'archive sources et ancien `public/react` contient 360 872 octets, SHA-256 `951bc9953e906a700cc333bfc13ba588b883192976ee8d2bef7d6ffdb30a15e9`.
- Le premier passage a construit 84 modules mais `artisan route:list` a echoue sur l'ancien `SpeakingAssessmentController` absent, sans rapport avec ce lot. Le rollback automatique a restaure les trois sources et l'ancien bundle, supprime le nouveau composant et recharge PHP-FPM; l'application est restee HTTP 200 avec trois conteneurs `running`, `RestartCount=0`. La sauvegarde `/home/david/edumaison-backups/legacy-family-claim-20260928T045256Z` a ete conservee.
- La reprise a remplace ce controle global fragile par la verification HTTP directe de la route protegee. Le build final transforme 84 modules et active `main-BZYpPS0Q.js`, 489 176 octets, SHA-256 `02938cd4c04dfb1feb646736612de042e6967d9d37def5d11718b9f10444e3a4`. Le bundle contient `Securiser cette famille` et `Ajouter une langue locale`.
- Verification independante : `/app` et le bundle repondent HTTP 200; `POST /api/family-auth/claim-legacy` sans cookie familial repond HTTP 401, donc la route est active et protegee. Le foyer historique compte toujours zero liaison parent active : aucune credentielle n'a ete inventee ou creee par le deploiement.
- Le navigateur reel charge le bundle actif sans erreur console. `curriculum-app`, `curriculum-postgres` et `curriculum-nginx` sont `running`, chacun avec `RestartCount=0`; aucun journal serveur recent ne contient d'erreur et les fichiers `/tmp` ont ete supprimes.
- Rapport `.codex-tmp/legacy-family-claim-production-20260928.json`, SHA-256 `6275d134357cbaa3b4c2ffb21b0b60b3507f3fdbb218892b68345542ce874994`.
- Prochaine action sure : actualiser completement l'application, ouvrir `Parent view` avec le PIN familial, choisir `Parametres`, puis creer soi-meme le compte proprietaire du foyer existant. Apres cette creation, le meme onglet affichera les langues familiales et les enfants, sans perdre leur progression.

## 2026-09-28 - Rattachement du foyer historique pret hors production

- Apres le deploiement des langues familiales, le proprietaire ne voyait pas le bouton attendu. Le diagnostic de production en lecture seule confirme que le foyer historique existe mais possede zero compte parent actif dans `household_user`. L'acces courant repose sur le cookie PIN historique : il ouvre les enfants, mais ne peut pas autoriser les parametres prives du foyer.
- L'onglet `Parametres` est maintenant toujours visible dans le candidat local. Pour un foyer encore historique, il affiche `Securiser cette famille` au lieu d'appeler une API qui repondrait 401. Le formulaire demande le nom du parent, l'adresse e-mail, un mot de passe et sa confirmation.
- La nouvelle route `POST /api/family-auth/claim-legacy` exige le cookie familial historique valide, applique une limite de debit, verrouille le foyer pendant la transaction et refuse toute revendication si un compte actif lui est deja lie. Elle cree le premier utilisateur proprietaire dans le foyer existant, authentifie la session et ne cree aucun nouveau foyer.
- Preflight SQLite jetable reussi : reponse 201, lien `owner` actif, session rattachee au foyer 1, enfants preserves, seconde revendication bloquee en 409 et requete sans cookie bloquee en 403. Marqueur `LEGACY_FAMILY_CLAIM_OK`; base temporaire supprimee.
- Build Vite reussi avec 84 modules. Bundle `main-BKah4_Fv.js`, 496 227 octets, SHA-256 `898467248e3d912678a182b43f372556e0b9ff00ff487bd8ed5e07bcb920da18`; les controles `Securiser cette famille` et `Ajouter une langue locale` sont tous deux presents.
- Apercu local valide : `http://127.0.0.1:4193/react/legacy-family-claim-preview.html`. L'onglet Parametres et le formulaire de rattachement sont visibles; aucune information reelle n'a ete saisie.
- Rapport `.codex-tmp/legacy-family-claim-preflight-20260928.json`, SHA-256 `846a9973455d86113b01ce0e5a77834961ebd42e254e282aaf3d074b5cffb630`. Production inchangee par ce correctif : aucune route, source, donnee ou session distante n'a ete modifiee.
- Prochaine action sure : valider visuellement l'ecran local, puis obtenir une autorisation de production distincte pour deployer les quatre fichiers correctifs. Le proprietaire creera ensuite lui-meme son adresse et son mot de passe dans l'application; aucune credentielle ne doit etre creee par Codex.

## 2026-09-27 - Langues locales multi-tenant deployees en production

- Apres validation visuelle locale et autorisation `feu vert`, le lot de production a ete limite a `NationalLanguageProfileService.php` et `FamilySettings.tsx`. Aucune migration, aucun seeder et aucune ecriture de donnee applicative n'ont ete executes.
- Le paquet de deux fichiers contient 8 898 octets, SHA-256 `892a745ae6b4ede3a113a3d74cee47c6a1cbc1d88d148c22c1125414dec3b9c5`. Les empreintes actives avant mutation correspondaient a la version precedente; les empreintes finales correspondent exactement au candidat valide : service `e83ffe4c49ceb7bad09d60d0e2020fde22f73160f9e0ec73583af3b0effbc2e4`, ecran parent `8d0d2bb6ee44d5c8795c02aa4362058428962c1f5938492b956f03b839b373a8`.
- Sauvegarde durable avant mutation : `/home/david/edumaison-backups/saas-custom-languages-20260927T220217Z`. Le dump PostgreSQL contient 518 002 octets et 520 entrees TOC, SHA-256 `9d861e74a5b28111ccb4b9c1bc1fbd99169c7a299a971022260a4cd08c3aa53f`. L'archive des deux sources et de l'ancien `public/react` contient 245 426 octets, SHA-256 `ba6c6a124771d82e868a04fe56cb8e7a039f2fb1f37a752a86cc792fcf33fc48`.
- Un premier passage s'est arrete avant toute mutation parce que `pg_restore` n'etait pas installe sur l'hote. La validation du dump a ete deplacee dans le conteneur PostgreSQL, puis le deploiement complet a reussi. Le repertoire de precontrole incomplet `/home/david/edumaison-backups/saas-custom-languages-20260927T215822Z` a ete conserve et non supprime.
- Le build de production transforme 83 modules. Le bundle actif `main-B27VV7b7.js` contient 486 194 octets, SHA-256 `56bf168d7cff91f8dffa70ffbfdd577c587d93de6c7f7b3b1e02bcbfb93205e0`; il contient le controle `Ajouter une langue locale`. Neuf assets sont servis.
- Verification independante : `/app` et le bundle repondent HTTP 200, l'API familiale sans session repond HTTP 401, la migration linguistique reste `Ran` en batch 26 et aucun journal serveur recent ne contient d'erreur. Le navigateur reel charge l'accueil enfant de production sans erreur console.
- `curriculum-app`, `curriculum-postgres` et `curriculum-nginx` sont `running`, chacun avec `RestartCount=0`. Les deux fichiers temporaires `/tmp` ont ete supprimes.
- Rapport `.codex-tmp/saas-custom-languages-production-20260927.json`, SHA-256 `2cc46f537f2d37c6df6a1ee3ad8d7d6576b417134640de02ffbad3ed2a52a351`.
- Prochaine action sure : tester dans l'espace parent l'ajout de plusieurs langues libres sur une famille reelle, puis verifier leur affectation individuelle aux enfants. L'etape SaaS suivante reste un circuit modere de proposition au catalogue global, distinct des libelles prives des foyers.

## 2026-09-27 - Langues locales multi-tenant pretes hors production

- L'audit confirme que Bafang, Bamendjou, Ghomala et Fe'fe ne sont pas codes en dur dans le moteur tenant : ces noms appartiennent aux seeders et sources de contenu. Le moteur applicatif utilise les profils generiques `household_national_language` et `child_national_language`.
- L'espace parent accepte maintenant jusqu'a quatre noms libres de langues ou dialectes locaux par foyer, avec ajout et retrait dynamiques. Les choix du catalogue global restent disponibles en parallele; les noms libres demeurent prives au foyer et ne deviennent jamais automatiquement des langues globales.
- Le service supprime les doublons de noms libres sans tenir compte de la casse. Ainsi `Medumba` et `medumba` produisent une seule langue familiale, sans modifier les libelles saisis par une autre famille.
- Le preflight SQLite jetable met en scene deux tenants : le premier obtient `Medumba` et `Yemba`, le second `Duala` et `Basaa`. L'affectation d'une langue du premier tenant a l'enfant du second est bloquee et la mise a jour du premier ne modifie pas le second. Marqueur `SAAS_CUSTOM_LANGUAGE_ISOLATION_OK`; base temporaire supprimee apres le test.
- Le build Vite reussit avec 83 modules et produit `main-DejCAH5T.js`, 493 245 octets, SHA-256 `e4c062b45fbd5ad3162899b77531cfe11be2bab5b1d9c9f35a98397220aed55c`. Le lint PHP du service reussit.
- Le test visuel de l'espace parent confirme quatre champs maximum, la disparition du bouton d'ajout a la limite, son retour apres suppression et l'absence de debordement horizontal en vue mobile. Apercu local : `http://127.0.0.1:4193/react/multilingual-parent-preview.html`.
- Rapport `.codex-tmp/saas-custom-languages-preflight-20260927.json`, SHA-256 `1621e0021ad94fc67b57e4bb0ee6970a09d72f7a83f427c1148f63c71c4deb2f`. Production inchangee : aucun fichier, aucune migration et aucune donnee n'ont ete modifies sur le serveur.
- Prochaine action sure : faire valider l'apercu local, puis obtenir une autorisation de production distincte pour deployer uniquement le service et l'ecran parent. L'etape SaaS suivante sera un circuit modere de proposition de langues au catalogue global, sans exposer les libelles prives des familles.

## 2026-09-27 - Choix multilingue familial Bafang/Bamendjou deploye en production

- Apres autorisation explicite, le lot a ete limite a 19 fichiers pour les profils linguistiques, les controles serveur, l'espace parent, le choix enfant et le catalogue. La route Speaking et le champ `speaking_analysis_consent_at`, trouves dans la branche locale mais encore hors perimetre, ont ete explicitement exclus.
- Le paquet final pese 38 835 octets et a le SHA-256 `44eba10a173dffca80ea9093917ae8a7148f7bbf92bc45e048f15c2fa81700c1`. Les 14 sources existantes correspondaient exactement a la copie active avant mutation; les cinq nouveaux fichiers etaient absents. Les 19 empreintes finales ont ete revérifiees sur l'application active.
- Le premier passage a rencontre un retour chariot Windows dans le manifeste apres la migration. Le rollback automatique a annule la migration, restaure les sources et laisse les trois services `running`, `RestartCount=0`; les empreintes historiques, l'absence des trois tables et `/app` HTTP 200 ont ete controles avant toute relance. La sauvegarde de ce passage est conservee dans `/home/david/edumaison-backups/multilingual-family-choice-20260926T204047Z`.
- Sauvegarde finale avant mutation : `/home/david/edumaison-backups/multilingual-family-choice-20260926T204615Z`. Le dump PostgreSQL contient 500 881 octets et 480 entrees TOC, SHA-256 `7fab7ae532ca142faff13a1f0d6f24cf5a4457d604c4490aa1bcf976c3b843b2`. L'archive fichiers contient 261 920 octets, SHA-256 `7c2598d169f9ecd9442c38e619c6f86a3f3a93197318a258d2333cf0edfa6011`.
- La migration `2026_09_18_120000_create_national_language_profiles` est executee en batch 26. Les trois tables attendues existent et le catalogue contient exactement `bbj:Ghomala'` et `fmp:Fe'fe' / Nufi`. Aucun profil familial/enfant ni pack linguistique n'a ete cree automatiquement; les compteurs existants de foyers, enfants, exercices, tentatives, packs et affectations sont inchanges.
- Build isole reussi avec 83 modules. Bundles actifs : `assets/main-BNLq7VxB.js` (485 200 octets) et `assets/mama-BA1QY53t.js` (54 203 octets); neuf assets sont servis. Le bundle React precedent reste disponible dans `/opt/edumaison-curriculum/public/react-before-multilingual-family-choice-20260926T204615Z`.
- Verification independante : 19 empreintes correctes, migration `Ran`, deux routes de profil enregistrees, `/app` et le bundle principal HTTP 200. Le premier controle web de la nouvelle route a revele l'ancien bytecode car `opcache.validate_timestamps=Off`; PHP-FPM a ete recharge gracieusement avec `USR2`, sans redemarrer le conteneur. La route repond maintenant HTTP 401 sans session, au lieu de 404, ce qui confirme son activation dans le processus HTTP.
- `curriculum-app`, `curriculum-postgres` et `curriculum-nginx` sont `running`, chacun avec `RestartCount=0`, sans erreur recente. Controle navigateur reel apres rechargement : la matiere `National Languages and Cultures` affiche `Culture · langue à choisir` pour une famille non encore configuree et la console ne contient aucune erreur. Rapport `.codex-tmp/multilingual-family-choice-production-20260927.json`, SHA-256 `b12aad0db05946b707ae2fd9016668367f4a153b516cb2140c939243475c2f5d`.
- Prochaine action sure : dans l'espace parent, activer `Fe'fe' / Nufi` et/ou `Ghomala'`, renseigner le libelle familial souhaite comme `Bamendjou`, puis affecter les langues a chaque enfant. Le selecteur `Aujourd'hui, j'apprends...` apparaitra lorsqu'un enfant disposera de plusieurs langues; aucun contenu linguistique ne sera publie avant validation par un locuteur competent.

## 2026-09-26 - Choix multilingue familial Bafang/Bamendjou pret hors production

- L'architecture locale accepte maintenant plusieurs langues nationales actives par foyer, un sous-ensemble autorise par enfant et une seule langue courante par session. Les anciennes colonnes de langue unique restent un repli compatible pendant la migration.
- Le catalogue candidat ajoute fmp pour Fe'fe' / Nufi dans le contexte familial de Bafang et conserve bbj pour Ghomala'. Le foyer peut definir un nom local ou une variante sans modifier le catalogue global; Bamendjou est valide comme exemple de libelle familial. Aucun contenu n'est automatiquement associe au code nge.
- L'espace parent selectionne plusieurs langues pour le foyer, choisit celles accessibles a chaque enfant et fixe une langue de depart. Dans National Languages and Cultures, l'enfant voit Aujourd'hui, j'apprends..., peut choisir parmi ses langues autorisees, puis ne recoit que les packs et exercices de la langue courante.
- Les affectations parentales acceptent un pack correspondant a n'importe quelle langue autorisee de l'enfant. L'affichage et les soumissions restent filtres sur la langue courante, ce qui garde les activites et progressions separees.
- Preflight SQLite isole reussi : catalogue idempotent bbj/fmp, deux langues pour le foyer, deux pour un enfant, une pour l'autre, exactement un choix courant par enfant, bascule fmp vers bbj, refus d'une langue non autorisee, filtrage inverse des exercices et route PUT enregistree. Marqueur MULTILINGUAL_FAMILY_CHOICE_PREFLIGHT_OK.
- Preflight PostgreSQL 17 local isole reussi avec les memes assertions. Le round-trip de migration down/up reussit avec le marqueur NATIONAL_LANGUAGE_MIGRATION_ROUNDTRIP_OK; la base temporaire edumaison_multilang_preflight_20260926_a1 a ensuite ete supprimee et son absence verifiee.
- Validation visuelle locale reussie sur les vrais composants React : l'enfant voit Fe'fe' / Nufi et Bamendjou dans Aujourd'hui, j'apprends..., la bascule ouvre la matiere, et l'espace parent affiche les deux cases, l'autonyme Ghomala et le libelle familial Bamendjou sans debordement observe.
- Les regressions NATIONAL_LANGUAGE_PROFILES_PREFLIGHT_OK et GHOMALA_CLASS3_DRAFT_PREFLIGHT_OK passent. Les fichiers PHP modifies passent l'analyse syntaxique. Le build Vite transforme 83 modules et produit main-DDc38JC7.js, SHA-256 4c5286e8b463e1d6cefde8f0ec4afed0623f60670dfb90b71c9dc08a643beeb7.
- Rapport .codex-tmp/multilingual-family-choice-preflight-20260926.json. Production inchangee : aucune migration, aucun seeder, aucun fichier applicatif et aucune donnee n'ont ete transferes ou executes sur le serveur.
- Prochaine action sure : valider localement les libelles visibles Fe'fe' / Nufi et Bamendjou, puis preparer un preflight PostgreSQL sur restauration isolee. Un deploiement exigera une autorisation de production distincte.

## 2026-09-23 - Ghomala (bbj) catalogue et pack Class 3 brouillon prets hors production

- Le proprietaire a choisi Ghomala comme premiere langue nationale. Le catalogue local utilise le code ISO 639-3 `bbj`, le nom `Ghomala'` et l'autonyme `Ghɔmáláʼ`.
- Les references de cadrage sont le dictionnaire SIL Cameroun archive 83096, le comite de langue/APROCLAGH sur `ghomalaonline.com`, son repertoire d'alphabetisation, l'etagere Bloom `bbj` et la bibliographie Glottolog du dictionnaire de 2010. Ces ressources restent des references : aucun texte, dessin ou audio externe n'a ete copie dans EduMaison.
- Le seeder idempotent `database/seeders/GhomalaClass3DraftPackSeeder.php`, SHA-256 `aec1f3e70f77502f7d65403dc680a659682564a560bf43f3e6a75161fc4b9232`, cree ou restaure l'entree de catalogue et prepare `ghomala-bbj-class-3-foundations` en version `0.1.0-draft`.
- Le pack est volontairement inactif, non publie, sans date de revue et sans exercice. Ses six blocages explicites sont la variete familiale, l'orthographe, le vocabulaire, l'audio, l'identite du relecteur et l'absence d'activites validees. Le seeder refuse d'ecraser un pack deja revu ou publie.
- La fiche `docs/GHOMALA_CLASS3_REVIEW.md`, SHA-256 `a691ac5075c4c15970800b33a95e23d00225840ae2f1d2119dc1494c66a1d71a`, definit les sources, les modules prevus, les droits, la revue linguistique et les criteres audio. Elle interdit de melanger les varietes locales sans validation du locuteur.
- Preflight SQLite isole reussi : deux executions produisent une langue et un pack uniques; le profil enfant reste `awaiting_pack`, le brouillon ne correspond pas a l'enfant pour une affectation et la protection contre l'ecrasement d'un pack revu fonctionne. Le preflight general des profils linguistiques repasse ensuite integralement. Marqueurs `GHOMALA_CLASS3_DRAFT_PREFLIGHT_OK` et `NATIONAL_LANGUAGE_PROFILES_PREFLIGHT_OK`.
- Rapport `.codex-tmp/ghomala-class3-draft-preflight-20260923.json`, SHA-256 `bc7933983c61e174530e1df92d54ac675ff2c7e628d1e41ed41ad989419f8384`. Production inchangee; aucun seeder, catalogue, pack ou fichier n'a ete transfere au serveur.
- Prochaine action sure : confirmer la variete de Ghɔmáláʼ parlee dans la famille, par exemple Bandjoun/Jo, Baham/Hom, Bahouan, Bayangam, Bafoussam, Baleng ou une autre variete, puis identifier le premier locuteur-relecteur avant de rediger le vocabulaire et les audios.

## 2026-09-23 - Profils de langue nationale et packs verifies prets hors production

- Une architecture multi-famille et multi-enfant est maintenant preparee localement : catalogue `national_languages`, choix par defaut au niveau du foyer, surcharge facultative par enfant et saisie libre lorsqu'une langue n'est pas encore cataloguee. Aucun catalogue arbitraire n'est precharge, afin de ne pas exclure ou mal nommer une langue familiale.
- Les packs `national_language` portent desormais une langue, une version de contenu et un statut de revue linguistique. Un pack n'est eligible que s'il est actif, publie, cible exactement `National Languages and Cultures` et le niveau de l'enfant, possede une version, une date de revue et le statut `verified`.
- `NationalLanguageProfileService` resout l'heritage foyer/enfant et les statuts `not_configured`, `awaiting_catalogue`, `awaiting_pack` et `ready`. Il filtre les exercices de pack par enfant et bloque cote serveur toute tentative pour une langue non affectee, meme si une URL est appelee directement.
- Les controles de `LearningPackController`, `SubjectController` et `ExerciseController` empechent l'activation, l'affectation, l'affichage et la soumission d'un pack linguistique non verifie ou incompatible. Les exercices culturels de base restent independants des futurs packs linguistiques.
- L'espace parent permet de choisir aucune langue, une langue du catalogue ou une langue libre pour le foyer, puis d'heriter ce choix ou de le remplacer pour chaque enfant. L'espace enfant affiche la langue prete ou indique clairement qu'un choix ou un pack est encore attendu. La route `GET /api/children/{childId}/national-language-profile` est enregistree et protegee par l'appartenance familiale.
- Preflight local reussi deux fois dans une application Laravel isolee et une base SQLite jetable : colonnes presentes, heritage du foyer, surcharge enfant, langue libre en attente de catalogue, pack verifie accepte uniquement pour le bon enfant, pack brouillon refuse, exercice masque a l'autre langue et tentative directe bloquee. La transaction laisse zero langue, pack ou affectation temporaire apres rollback. Marqueur `NATIONAL_LANGUAGE_PROFILES_PREFLIGHT_OK`.
- Les 16 fichiers PHP concernes passent l'analyse syntaxique. Le build React Vite reussit avec 83 modules; bundle principal `main-CacmOcMq.js`, 487,85 ko, SHA-256 `6cddb47edd694c8fe0b9259e37d02195b79f20c6edda68b80819251aea5a8b28`. Les avertissements connus concernent seulement les images d'environnement resolues au runtime.
- Rapport `.codex-tmp/national-language-profiles-preflight-20260923.json`, SHA-256 `37d5c9ab17025a1f987d2a4e7dac2b98a9677628b6e2a6123ddcd533ea240b80`. Le transfert distant n'a pas ete execute : l'autorisation courante couvrait la poursuite locale, pas l'export de ce lot vers l'hote de production. Le dossier distant `/tmp/edumaison-national-language-profiles-upload-20260918` peut exister vide apres la tentative interrompue; aucune donnee ni fichier applicatif de production n'a ete modifie.
- Prochaine action sure : constituer avec des locuteurs de confiance un premier catalogue source et un pack versionne/revu pour une langue reelle, puis effectuer un preflight PostgreSQL sur restauration isolee. Le deploiement de l'architecture et de la migration exigera une autorisation de production distincte.

## 2026-09-18 - National Languages Class 3 audite et candidat culturel pret hors production

- Le programme MINEDUB Level II pages 85-88, document verifie SHA-256 `1921b9d044f23849c1532f403021e0a8d23a46123d4b225b8b083b827e3fdd1c`, a ete confronte au catalogue National Languages and Cultures Class 3. Les attendus couvrent la langue nationale de l'enfant, les langues de la communaute, le GACL, le vocabulaire de la famille nucleaire, les echanges polis, les evenements culturels, les recits et chants, puis la lecture, l'ecriture, la grammaire et le dialogue dans la langue nationale choisie.
- L'audit de production en lecture seule confirme 32 exercices actifs, zero inactif, zero tentative, zero competence, zero lien et zero media structure. Les 32 contrats existants sont techniquement valides et le contenu correspond exactement au snapshot local d'aout. Rapport `.codex-tmp/class-3-national-languages-audit-summary-20260918.json`, SHA-256 `346492b8539f4af9a5c4d9fde96dd3faf8a7bb5f28d5ea0a9c31db80f60309cd`.
- Dix fichiers images sont references par 17 exercices, mais toutes les associations sont trompeuses ou sans rapport : paysage pour un fon, icone Wi-Fi pour la tradition orale, bras pour un griot, tete de cheval pour le balafon, poule et poussins pour la famille humaine, pied pour les evenements, glace et fourmi pour le mariage, hibou pour les traditions. Le candidat retire ces 17 references et n'ajoute aucun SVG ni media non verifie.
- Le catalogue actuel teste surtout des generalites culturelles, parfois hors niveau : famille elargie, monogamie/polygamie, dot et funerailles. Il ne peut pas valider la langue nationale de l'enfant, car la famille ne choisit encore aucune langue nationale et aucun pack lexical/audio propre a une langue n'existe. Les competences de parole, lecture, ecriture, sons et vocabulaire doivent donc rester explicitement sans lien plutot que d'etre certifiees artificiellement.
- Le candidat `database/seeders/Class3NationalLanguagesAlignmentPilotSeeder.php`, SHA-256 `c2ccccbe36796bf49c68dd8b124a02777ed00780095d031446166f204c0aa05c`, ne supprime aucun enregistrement. Il conserve 20 activites culturelles/preparatoires, desactive 12 anciens exercices sans historique, reorganise quatre lecons, retire toutes les images et utilise uniquement les categories PostgreSQL autorisees `quiz` et `revision`.
- Quatorze competences officielles sourcees pages 85-88 sont creees, mais seulement sept liens stricts sont poses sur l'identification culturelle et les evenements de vie. Les douze autres axes linguistiques restent visibles avec zero lien tant qu'une langue familiale et un pack valide ne sont pas disponibles.
- Preflight reussi sur une restauration PostgreSQL isolee avec deux executions idempotentes : 20 actifs, 12 inactifs, 20 contrats valides, zero doublon exact, zero image, zero media, zero tentative et zero lien croise. Rapport `.codex-tmp/class-3-national-languages-preflight-20260918.json`, SHA-256 `9a67e971ae9cd7d1484dd75148dec32c94dccbc2b66565fa44b507fc9593bed6`; verificateur SHA-256 `1e0b9a0cf21a701df32863751ae6cc909cb7295b3dc39d0f3f2dc510f707b057`.
- Production inchangee apres le preflight : 32 actifs, zero tentative, zero competence et zero lien. `/app` repond HTTP 200; `curriculum-app`, `curriculum-postgres` et `curriculum-nginx` sont `running`, chacun avec `RestartCount=0`; aucun conteneur ou fichier temporaire de test ne subsiste.
- Prochaine action sure : concevoir localement le profil de langue nationale par famille ou enfant, la selection du pack linguistique et la revue par un locuteur de confiance. Le seeder culturel ne doit pas etre deploye avant une autorisation de production distincte.


## 2026-09-18 - Citizenship Class 3 aligne en production

- Le proprietaire a confirme l'autorisation de sauvegarder la base, deployer le seeder Citizenship Class 3 et l'executer en production. L'etat initial a ete revalide avant mutation : Citizenship comptait 10 exercices actifs, zero tentative et zero competence; Social Studies comptait 27 exercices actifs, trois tentatives et 11 competences.
- Sauvegarde durable avant mutation : `/home/david/edumaison-backups/class3-citizenship-20260917T232822Z`. Le dump PostgreSQL contient 500 011 octets et 480 entrees TOC; SHA-256 `a5203f11a97d17c97bbe95158f253f84e47ab9b2bee2086b29dc334751c9a213`. Le dossier conserve aussi le seeder, le verificateur, leurs empreintes et l'etat initial.
- Seul `database/seeders/Class3CitizenshipAlignmentPilotSeeder.php` a ete installe puis execute une fois avec `--force`. Les empreintes locale, sauvegardee et deployee sont identiques : SHA-256 `e987d086fffa4c78a3cbeae44a91a85afc960f8875a302fb85eed4e91f8f0f3b`.
- Etat final verifie deux fois, dont une verification independante sur l'etat courant : 30 exercices Citizenship actifs, sept exercices Social Studies actifs, 30 contrats de reponse valides, zero doublon exact et zero lien croise. Les quatre themes civiques ont rejoint Citizenship; History et Geography restent dans Social Studies.
- Neuf competences officielles sourcees pages 73-76 portent exactement 29 liens. Les 29 exercices certifies sont relies a leur competence; `Tax` (`1341`) reste volontairement actif et non certifie. Les quatre competences History/Geography de Social Studies sont preservees.
- Les deux tentatives existantes de `Community` (`2836`) sont preservees dans Citizenship et la tentative Social Studies restante est preservee. Aucune suppression d'exercice, tentative, note ou progression enfant n'a ete effectuee.
- Les seules illustrations Citizenship actives sont maintenant pertinentes : drapeau, communaute, election et vote. Les six associations trompeuses ont ete retirees; aucun SVG ni media structure n'a ete ajoute.
- Rapport de production local : `.codex-tmp/class-3-citizenship-production-20260918.json`, SHA-256 `00b4e2783bea46f8cc582245e40ebf9e77af677241ce526c9d0e8cc168022dfb`. `/app` repond trois fois HTTP 200, la tentative anonyme reste HTTP 401 et `curriculum-app`, `curriculum-postgres`, `curriculum-nginx` sont `running`, chacun avec `RestartCount=0`; aucun message recent `fatal`, `panic`, `exception` ou `error`.
- Prochaine action sure : auditer National Languages and Cultures Class 3 en lecture seule, confronter le catalogue au programme officiel et preparer un candidat hors production avant toute nouvelle autorisation.

## 2026-09-17 - Citizenship Class 3 audite et candidat pret hors production

- Le programme MINEDUB Level II pages 73-76 a ete confronte au catalogue actuel. Citizenship Class 3 couvre officiellement les emblemes nationaux, les regles, les autorites, les elections et institutions, les valeurs, le bien commun, les droits/devoirs de l'enfant ainsi que la paix et la securite.
- L'audit de production en lecture seule confirme 10 exercices Citizenship actifs, zero tentative, zero competence et zero media structure. Les 10 contrats de reponse sont valides. Export `.codex-tmp/class-3-citizenship-audit-20260917.json`, SHA-256 `72896c1e1c70d50d8f112cd01d89931c68a8ba217b9a0603391f4a2ef1ba1b9d`.
- Huit exercices referenceaient une image. Seul `flag.png`, utilise par deux exercices, est pertinent. Six associations sont trompeuses : fourmi pour `anthem` et `rules`, puits pour `treating them well`, silhouette generique pour les droits, bras pour `harm` et glace pour `UNICEF`. Les six fichiers ont ete copies puis controles visuellement; leurs empreintes figurent dans l'export.
- L'audit croise montre que quatre vrais themes civiques restent ranges dans Social Studies : `Rights and Duties`, `Moral Education`, `Human Rights and Peace` et `Civics and Governance`, soit 20 exercices. Social Studies compte actuellement 27 actifs, trois tentatives et 11 competences. Deux tentatives appartiennent a `Community` (`2836`); elles doivent etre preservees. Export `.codex-tmp/class-3-citizenship-overlap-audit-20260917.json`, SHA-256 `9514992e93cad7800ce9eaca477a8762ea5d6b67193d636c9e0790b7a5b1a16e`.
- Le candidat `database/seeders/Class3CitizenshipAlignmentPilotSeeder.php`, SHA-256 `e987d086fffa4c78a3cbeae44a91a85afc960f8875a302fb85eed4e91f8f0f3b`, ne supprime aucun enregistrement. Il deplace les quatre themes civiques vers Citizenship, laisse History et Geography dans Social Studies et conserve les IDs ainsi que toutes les tentatives.
- Les 10 exercices Citizenship sans historique sont reecrits sur les emblemes, le respect de l'hymne, le sceau et la devise, les regles, les autorites, les salutations et excuses, les biens publics, les droits, les devoirs, la paix et la securite. Ils conservent leurs IDs `735` a `744`; seule l'image du drapeau reste. Les silhouettes `rights.png` sont aussi retirees de `1342` et `2838`.
- Les illustrations conservees ont ete verifiees visuellement : `community_p.png` represente une communaute et `election.png`/`vote.png` une urne. Les deux derniers fichiers sont identiques, mais leur dessin est pertinent. Aucun SVG ni media structure n'est introduit.
- Le resultat candidat comporte 30 exercices Citizenship et sept exercices Social Studies consacres a l'Histoire/Geographie. Neuf competences officielles sourcees pages 73-76 portent 29 liens stricts; `Tax` (`1341`) reste volontairement actif mais non certifie. Il n'existe aucun lien croise entre les deux matieres.
- Preflight reussi sur restauration PostgreSQL isolee avec deux executions idempotentes : les 30 contrats sont valides, zero doublon exact, zero image trompeuse et zero lien croise. Les deux lignes de tentative de `2836` sont identiques avant/apres. Rapport `.codex-tmp/class-3-citizenship-preflight-20260917.json`, SHA-256 `ab4b450324640170e1cc836c1ab7cef3dff6d24cce8a7ff983d67abf24bc2b73`.
- Le conteneur, le dump et les fichiers distants temporaires ont ete supprimes. Production inchangee : Citizenship `10 actifs / 0 tentative / 0 competence`, Social Studies `27 actifs / 3 tentatives / 11 competences`; `/app` repond HTTP 200 et les trois conteneurs sont `running`, `RestartCount=0`.
- Prochaine action sure : apres autorisation explicite, sauvegarder PostgreSQL, transferer uniquement le seeder Citizenship, l'executer une fois en production, verifier les 30/7 exercices, les 29 liens, les tentatives preservees et le runtime, puis passer a National Languages and Cultures Class 3.

## 2026-09-17 - Handwriting Class 3 aligne en production

- Le proprietaire a confirme l'autorisation distincte de sauvegarder, deployer les fichiers Handwriting Class 3 et executer le seeder en production. L'etat initial a ete revalide juste avant l'action : 15 exercices actifs, zero tentative et zero competence Handwriting Class 3; les trois services etaient `running`, `RestartCount=0`.
- Les 68 sources React de production ont ete comparees au candidat fichier par fichier. Aucune difference inattendue n'a ete trouvee hors des fichiers Handwriting autorises, de `index.css`, des types et des polices locales. La page et l'entree Vite d'apercu ont ete exclues du lot de production. Le build Vite reussi transforme 83 modules et produit `main-De0fSW77.js`, SHA-256 `f1247584bc5b020787f10073aecd62b7c021abdf91297d9f605b10cb38d6d9ae`, avec `Schoolbell-Regular-B0Op7rKS.ttf`, SHA-256 `00ff6655a5eb1eb70d32f2b7351d1bcf3f45f3f9ca40fd5c0d25da79f7f82a50`.
- Sauvegarde durable avant mutation : `/home/david/edumaison-backups/class3-handwriting-20260917T080144Z`. Le dump PostgreSQL contient 502 711 octets et 480 entrees TOC; SHA-256 `b87cfd331d6b9d635387381f6794520025429e6e1ee043c78a260f60a7122090`. Le dossier de 1,5 Mo conserve aussi les sources et le bundle precedents, leurs manifestes SHA-256 et les empreintes deployees.
- Le paquet distant de 22 fichiers a ete verifie integralement avant copie. Empreintes principales deployees : `ExerciseController.php` `8521b4fe85693cd7fac8a71a56f47168599807b70036ca198e57cbaef09524b1`; `Handwriting.tsx` `e729f6e19bda6f010d8d1ab90d87df156701759259348168a69201c3d0f83e26`; `handwritingTrace.ts` `fc84d51006a10d41dab9832ac94240227e33ec5e8b47370dde8176df79e01f8a`; seeder `53a166015bfd7c85472439f66b2c81392982db1a6e326079d144a08e4f17ed0b`.
- Le seeder transactionnel a ete execute une fois avec `--force`. Etat final verifie en lecture seule : cinq activites actives (`1698`, `1699`, `1700`, `1701`, `1729`), dix anciennes activites inactives sans suppression, 25 phrases toutes uniques, lecon `Five-sentence Texts|writing|true`, une competence officielle page 45 et cinq liens; zero doublon exact, image, media ou tentative.
- Le moteur de copie actif recompte les groupes de mots cote serveur, ignore les longs balayages pour ce comptage, refuse les traces courtes, partielles, etroites ou pleine hauteur, et garde la production en revue parentale. Le smoke test du controleur a reussi avant et apres le rechargement gracieux de PHP-FPM.
- Verification runtime : `/app`, le manifeste, `main-De0fSW77.js` et la police repondent HTTP 200; la tentative sans session reste HTTP 401. `curriculum-app`, `curriculum-postgres` et `curriculum-nginx` sont `running`, chacun avec `RestartCount=0`; zero erreur nouvelle dans les journaux. Le paquet `/tmp` et les fichiers de test internes ont ete supprimes.
- Prochaine action sure : actualiser completement l'application sur telephone ou tablette, ouvrir Handwriting Class 3, copier une phrase complete puis une phrase partielle, et confirmer que seule la copie complete atteint `Finish` avant la revue parentale.

## 2026-09-16 - Handwriting Class 3 audite et candidat pret hors production

- L'audit de production en lecture seule confirme 15 exercices Handwriting Class 3 actifs, dont 14 `handwriting` et un QCM, tous techniquement valides mais sans tentative, competence ni media. Six paires sont des doublons semantiques; le contenu porte surtout sur des lettres ou mots isoles alors que le programme MINEDUB Level II page 45 demande l'`upright joint script` et la copie lisible et reguliere de courts textes d'au moins cinq phrases. Export `.codex-tmp/class-3-handwriting-audit-20260902.json`, SHA-256 `1e73325a8ac857c87fc891f63c59f4e1ae12bc3ff88da133178991201cd8edd8`.
- Le candidat `database/seeders/Class3HandwritingAlignmentPilotSeeder.php`, SHA-256 `53a166015bfd7c85472439f66b2c81392982db1a6e326079d144a08e4f17ed0b`, conserve tous les enregistrements : cinq exercices deviennent cinq copies de textes distinctes de cinq phrases et dix anciens exercices sans tentative sont seulement desactives. Il cree la hierarchie `Upright Joint Script > Copying Short Texts > Five-sentence Texts` et une competence officielle sourcee page 45 reliee aux cinq exercices.
- L'ardoise locale prend maintenant en charge `practice_mode=copy` et `guide_style=upright_joint_script`. La phrase modele est visible au-dessus de lignes vierges avec `Schoolbell`, une police manuscrite imprimee aux lettres separees, embarquee localement sous licence Apache; le fichier pese 48 904 octets et a le SHA-256 `00ff6655a5eb1eb70d32f2b7351d1bcf3f45f3f9ca40fd5c0d25da79f7f82a50`. Le bouton de continuation reste desactive tant qu'aucune encre n'est presente, le libelle accessible decrit la copie et la progression affiche l'etape courante.
- Le controle automatique reste volontairement structurel : quantite d'encre, distance et surface utilisee. Il rejette un point, une trace trop courte, une gribouille etroite et un balayage pleine hauteur, mais ne pretend pas reconnaitre les lettres. Le retour enfant dit maintenant qu'il y a assez d'ecriture pour envoyer; la conformite de la copie et la lisibilite restent `pending_review` et sont decidees par le parent.
- Une capture utilisateur a revele qu'une copie partielle `Amina wa...` atteignant seulement 66 % de l'ancien indicateur pouvait encore etre presentee comme prete. Le seuil depend maintenant de la longueur de la phrase et exige davantage de points, de distance et de couverture horizontale. A 61 % le cas reproduit reste `Check` en orange et demande d'atteindre le repere de fin; une trace couvrant toute la ligne passe a `Finish` en vert. Des reperes debut/fin sont dessines sur l'ardoise. Tests client et serveur incluent desormais explicitement le refus d'une phrase partielle.
- Le faux comptage de mots a ensuite ete remplace par une estimation des groupes d'encre separes par des espaces, recalculee aussi cote serveur. L'interface affiche `Words detected 2/4` pour deux groupes et garde `Check`; quatre groupes, la couverture et l'effort requis donnent `4/4` puis `Finish`. Ce signal ne constitue pas une reconnaissance OCR des lettres ou de l'orthographe : le parent doit toujours verifier l'image avant `parent_verified`.
- Une seconde capture a montre que de longs traits horizontaux traversant toute l'ardoise fusionnaient les groupes et produisaient `1/4`. Les traits couvrant plus de 45 % de la largeur sont maintenant exclus du comptage des mots, cote client et serveur, tout en restant visibles dans l'image parentale. Les tests confirment : quatre groupes avec deux balayages donnent `4/4`, deux groupes avec balayages restent `2/4`, et des balayages seuls donnent `0/4`.
- La terminologie et le rendu ont ete verifies apres clarification sur l'ecriture anglophone. Les variantes `Playwrite GB S`, `Playwrite US Modern`, `Briem Hand`, `Briem Print` et `Edu AU VIC WA NT Precursive` ont ete comparees puis ecartees et retirees du candidat, car leur modele restait trop cursif. L'instruction enfant dit maintenant `neatest handwriting`; l'exigence pedagogique officielle `upright joint script` reste dans le seeder et la revue parentale. Les anciens titres `Cursive...` du seeder restent seulement des gardes de reconnaissance de l'etat historique et ne sont pas publies dans le nouveau parcours.
- Verification locale reussie : lint PHP du controleur et du seeder; build Vite de 85 modules avec `Schoolbell` emis a 48,90 ko; tests structurels client reussis. Le test tactile a 580 x 650 progresse de `1/4` a `4/4`, conserve `Check` tant que l'effort reste insuffisant, puis affiche `Finish` avec le retour positif; les copies partielles, les balayages seuls et les traces trop faibles restent refuses. Le rendu mobile est lisible, non cursif et sans debordement. Apercu local : `http://127.0.0.1:4187/react/handwriting-class3-preview.html`.
- Le premier preflight sur dump PostgreSQL reel restaure dans un conteneur isole a detecte avant production que `lessons.type=handwriting` violait `lessons_type_check`. La transaction a ete annulee et tous les artefacts temporaires ont ete supprimes. Le seeder utilise maintenant la valeur autorisee et semantiquement adaptee `writing`; le preflight verifie aussi explicitement ce type et retourne un code d'erreur strict en cas d'exception.
- Le preflight corrige a reussi avec deux executions idempotentes du seeder : cinq exercices actifs (`1698`, `1699`, `1700`, `1701`, `1729`), 25 phrases toutes uniques, cinq liens vers `MINEDUB-L2-HWR-C3-UPRIGHT-JOINT` page 45, zero doublon exact, image, media ou tentative. Les dix autres exercices deviennent inactifs et les quatre anciennes lecons vides sont desactivees dans la base de test. Marqueur final : `CLASS3_HANDWRITING_PREFLIGHT_OK`.
- Le conteneur, le dump et les trois fichiers `/tmp` ont ete supprimes automatiquement. Verification finale de production : 15 exercices Handwriting Class 3 toujours actifs, zero competence, zero tentative, lecon 315 toujours `Simple Sentences|mixed|true`; `/app` repond HTTP 200 et `curriculum-app`/`curriculum-postgres` restent `running`, `RestartCount=0`. Aucune donnee ni fichier de production n'a ete modifie.
- Prochaine action sure : preparer la sauvegarde et le lot minimal, puis obtenir une autorisation distincte avant de deployer les fichiers Handwriting, executer le seeder en production et verifier l'application.

## 2026-09-02 - Reading Class 3 aligne en production

- Le proprietaire a autorise explicitement le deploiement et l'execution du seeder Reading Class 3 en production. L'etat initial a ete revalide juste avant l'action : 39 exercices Reading actifs, aucune tentative, aucun lien de competence et aucune competence Reading Class 3.
- Sauvegarde PostgreSQL verifiee avant mutation : `/home/david/edumaison-backups/reading-class3-20260902T034015Z/curriculum-before-reading-class3.dump`, 501 202 octets, 465 entrees TOC, SHA-256 `6fe16629c2b080f32ed24370362260db46c76796eac862c2ec354bbc668ea672`.
- Seul `database/seeders/Class3ReadingAlignmentPilotSeeder.php` a ete installe puis execute avec `--force`. Empreinte locale et distante identique : SHA-256 `d9c0e46f2e57cde5821e48d06544f89a9798d9e497a9b0b0909d407fceeaa7fc`. Le lint PHP dans `curriculum-app` a reussi.
- Resultat fonctionnel : sept vraies comprehensions restent actives dans Reading (`1655`, `1656`, `2367`, `2368`, `2372`, `3147`, `3148`), la copie `1657` est seulement desactivee, 26 exercices sont reclasses dans English et cinq dans Science. Aucune suppression physique n'a ete faite.
- Quatre competences Reading sourcees page 44 existent maintenant. Les sept comprehensions portent sept liens officiels; les trois lacunes sans exercice, lecture a voix haute, nombres 101-500 et litterature, restent visibles avec zero lien. English recoit deux groupes de 13 liens; quatre des cinq exercices Science rejoignent leur competence officielle et `2369` reste volontairement non certifie.
- Le verificateur independant confirme : contrats de reponse valides, zero doublon exact dans le lot Reading, zero reference d'image, zero media structure, zero tentative et hierarchies vides desactivees. Export `.codex-tmp/verify-class3-reading-production-20260902.json`, SHA-256 `0b3d1dfb9081235520f8dd7c80c517eca5c107eafabfe46ee56b4c3130616ba5`.
- L'audit global passe de 2 805 a 2 804 exercices actifs et de 2 571 a 2 533 exercices sans lien de competence, soit 38 liens manquants resolus. Les 69 contrats invalides et 189 doublons exacts deja connus ailleurs restent inchanges; aucun nouveau probleme n'a ete introduit. Export `.codex-tmp/exercise-contract-audit-after-reading-20260902.json`, SHA-256 `0aaffba27ae3d6bdabdef91ddbef3debdf3b1bdf5802b32411c831f93e0476af`.
- Etat final : `/app` repond HTTP 200; `curriculum-app`, `curriculum-postgres` et `curriculum-nginx` sont `running`, chacun avec `RestartCount=0`. Aucun redemarrage, tentative, note ou progression enfant n'a ete effectue. Les scripts temporaires de controle ont ete retires; la sauvegarde est conservee.
- Prochaine action sure : auditer le contenu pedagogique et les contrats des 15 exercices Handwriting Class 3, puis preparer un candidat hors production avant toute nouvelle autorisation.

## 2026-09-02 - Reading Class 3 audite et candidat d'alignement pret hors production

- L'inventaire de production a ete relance en lecture seule pour les quatre matieres Class 3 restantes : Citizenship 10 exercices actifs, Handwriting 15, National Languages and Cultures 32 et Reading 39. Aucune ne porte de tentative ni de lien vers une competence officielle. Export `.codex-tmp/class-3-remaining-subjects-audit-20260901.json`, SHA-256 `51e624f4c9af4b7e443d1564ddbc719fbd3d8a92ff7238665fc870ecc615ea8f`.
- Le PDF MINEDUB Level II verifie, SHA-256 `1921b9d044f23849c1532f403021e0a8d23a46123d4b225b8b083b827e3fdd1c`, a ete relu a la page 44. Reading Class 3 couvre la lecture a voix haute de mots, images et courts textes, les nombres 101 a 500, la comprehension silencieuse de courts recits/descriptions et la litterature avec point de vue du personnage et lecon morale.
- Les 39 exercices Reading etaient repartis artificiellement en trois groupes de 13. Huit relevaient de la comprehension, 13 des noms/verbes English, 13 du vocabulaire English et cinq des animaux Science. Aucun dessin structure ni `image_url` n'est associe aux vraies comprehensions; aucune illustration trompeuse n'a donc ete conservee.
- Le candidat `database/seeders/Class3ReadingAlignmentPilotSeeder.php`, SHA-256 `d9c0e46f2e57cde5821e48d06544f89a9798d9e497a9b0b0909d407fceeaa7fc`, ne supprime aucun exercice. Il fusionne les deux questions du marche dans `1656`, desactive uniquement la copie `1657`, raccourcit trois titres trop longs, deplace 26 exercices vers les lecons English officielles et cinq vers Science.
- Quatre competences Reading sourcees page 44 sont creees. Sept comprehensions sont reliees a `Read texts silently and answer questions`; lecture a voix haute, nombres 101-500 et litterature restent volontairement a zero lien pour rendre les lacunes visibles. Deux competences English sourcees pages 46 et 48 recoivent 13 liens chacune. Quatre exercices sur nutrition/reproduction animale rejoignent la competence Science page 56; `nocturnal` reste non certifie.
- Preflight sur restauration PostgreSQL temporaire : deux executions successives reussies, sept Reading actifs, 0 tentative, 0 doublon exact, 0 contrat de reponse invalide, 0 reference d'image et 0 media structure. Le premier essai s'etait arrete avant le seeder parce que PostgreSQL terminait son initialisation; l'attente a ete rendue bloquante puis le test a reussi. Le conteneur, le dump et la copie applicative temporaires ont ete supprimes.
- Audit global de reference inchange : 2 805 exercices actifs, dont 2 619 controles serveur, 72 revus par un parent et 114 encore a fiabiliser. Export `.codex-tmp/exercise-contract-audit-summary-20260901.json`, SHA-256 `34484298353b4528b3857c0530374a840e200524fc079950216f3436f6bd4d78`.
- Production inchangee : aucun seeder, fichier applicatif, service, tentative, note ou progression enfant n'a ete modifie. Controle final : `/app` HTTP 200; `curriculum-app` et `curriculum-postgres` sont `running`, `RestartCount=0`. Prochaine action sure : apres autorisation explicite de production, sauvegarder la base, transferer uniquement le seeder Reading, l'executer une fois, verifier les comptes et `/app`, puis passer a Handwriting Class 3.

## 2026-09-01 - Moteur Handwriting deploye en production

- Le deploiement a ete construit depuis les 67 sources React exactes de production, puis limite aux six fichiers Handwriting. Les ajouts Speaking/Azure restes en attente n'ont pas ete inclus; aucun marqueur `assessment_token`, `azure_pronunciation` ou reglage d'analyse vocale n'est present dans le bundle actif.
- L'ardoise enfant separe le modele pale de l'encre, accepte doigt, stylet et souris, calcule une aide de proximite/couverture sans en faire une note, refuse les points et traces trop faibles, et propose une aide adulte apres deux essais insuffisants. Toute production reste `pending_review`.
- Le serveur recalcule le nombre de traits, de points, la distance normalisee et les limites; il refuse les coordonnees hors zone et les charges excessives. Il ne conserve que le resume structurel et l'image d'encre, jamais la trajectoire brute.
- La revue parentale utilise une grille manuscrite distincte : forme des lettres, tenue sur la ligne, espacement et lisibilite. L'indication automatique est affichee comme aide a confirmer visuellement, jamais comme validation.
- Verification avant deploiement : lint PHP des deux controleurs et des tests reussi; test autonome reussi pour un vrai trace, le refus d'un point et le refus de coordonnees falsifiees; TypeScript cible sans erreur; build Vite reussi avec 83 modules depuis les sources actives.
- Bundle actif : `main-PWG1Bddi.js`, 472 805 octets, SHA-256 `97e304984f5d2aa5b0a3e32b7877533b4452fa937cdf2a963f5fa9e37794c630`. Le manifeste PWA requis par l'accueil mobile a ete ajoute a `public/react/manifest.json`, SHA-256 `08e824a4f1720342d376af26b1f998d82f2cd66b00b84fa81653ef6617498133`.
- Empreintes principales deployees : `ExerciseController.php` `e05e7ea04522aefe742b7ef898ca59a0ba17210d18e4737b61f536244c2104f3`; `LanguageReviewController.php` `780c278a6a93e883854a61afdf6644d9691f064fe0da084f2ab749eaf4e4ad34`; `Handwriting.tsx` `acdcc597db19ef31a1869a04ef131327da6c39123fa1ded4c83e179296dcb015`; `handwritingTrace.ts` `64218e8ec55efff2468dece5cebfa3312e52f496f980061108e53ca4dd7c564c`; `LanguageReviews.tsx` `ca37ba1e608a69e99096531c9beaf0485ded651821f7685e164b1d22f72b244e`; `exercise.ts` `962508498c2e599163921c6e72a55505a330af02d62fbde37b6e8392cec725bf`.
- Sauvegarde : `/tmp/edumaison-before-handwriting-20260901T121107Z`, avec inventaire SHA-256 de 15 fichiers et etat d'absence initiale du manifeste React. Aucun schema, tentative, note, progression enfant ou audio n'a ete modifie.
- Verification production : test du calcul actif reussi dans `curriculum-app`; `/app`, `/mama`, le bundle et `/react/manifest.json` repondent HTTP 200; l'API de tentative sans session reste HTTP 401. PHP-FPM a ete recharge gracieusement pour OPcache; `curriculum-app` et `curriculum-nginx` restent `running`, `RestartCount=0`.
- Observation independante : un balayage Internet automatise a ensuite provoque de nombreux 403/404 correctement bloques et un avertissement ponctuel `pm.max_children=5`, sans erreur Handwriting ni arret. Cette capacite PHP devra etre auditee separement si des lenteurs sont observees.
- Prochaine action sure : actualiser completement l'application sur telephone, ouvrir un exercice Handwriting, tester un bon trace puis une gribouille, et verifier que le parent voit l'image d'encre et la grille manuscrite avant de valider.


## 2026-09-01 - Moteur local de guidage Handwriting et preuve de trace

- Speaking reste volontairement en attente de l'abonnement fournisseur. La suite locale porte sur Handwriting, distinct de la production textuelle tapee et de son filtre anti-charabia.
- L'ardoise separe maintenant le modele pale de l'encre de l'enfant. L'image privee envoyee au parent contient uniquement le vrai trace sur fond clair; le modele n'est plus fusionne dans la production et ne peut plus masquer une ardoise vide.
- Les mouvements doigt, stylet ou souris sont captures en trajectoires normalisees. Un retour immediat mesure la proximite du modele et sa couverture; un trace satisfaisant est encourage, tandis qu'un point ou une gribouille est refuse. Apres deux essais insuffisants, l'enfant peut envoyer la production explicitement pour recevoir de l'aide, sans validation automatique.
- Le navigateur ne produit aucune note finale. Toutes les productions restent `pending_review`; les mesures visuelles sont marquees `client_feedback_only` et le parent conserve la decision de lisibilite.
- Le serveur ignore les compteurs declares par le navigateur et recalcule le nombre de traits, le nombre de points, la distance normalisee et les limites du trace. Il refuse les points hors ardoise, les traces minuscules, les charges excessives et ne conserve que le resume structurel, pas les trajectoires brutes.
- La revue parentale utilise maintenant une grille adaptee au manuscrit : forme des lettres, tenue sur la ligne, espacement et lisibilite. Les productions tapees gardent leur grille consigne, phrases, orthographe et ponctuation. L'indication automatique est affichee comme aide a confirmer visuellement, jamais comme note.
- Fichiers principaux : `Handwriting.tsx` SHA-256 `ACDCC597DB19EF31A1869A04EF131327DA6C39123FA1DED4C83E179296DCB015`; `handwritingTrace.ts` SHA-256 `64218E8EC55EFFF2468DECE5CEBFA3312E52F496F980061108E53CA4DD7C564C`; `ExerciseController.php` SHA-256 `330CEDBB94A21FAB047659E0A5EDEAE092E025154FBF8AA4CDEB67562ACE16A8`; `LanguageReviews.tsx` SHA-256 `6CEA31859FE795A1CD646E2A15E0E008B51016760B889F3CDC276A7D0E2574FC`.
- Verification : lint PHP reussi; test autonome reussi pour un vrai trace, le refus d'un point et le refus de coordonnees falsifiees; TypeScript cible sans erreur; build Vite reussi avec 83 modules. Bundle `main-DnaSBqkB.js`, 479 865 octets, SHA-256 `ACFA21C35DB0FB911F41FCCEE9D726AABD9FEED16A311E6FBA4993C785DC3FAD`.
- Verification visuelle et interactive : a 390 x 844, aucun debordement; un `A` simule donne 64 % de proximite et 31 % de couverture avec retour positif; un point est bloque; une gribouille propose l'aide adulte apres deux essais; une phrase longue tient sur trois lignes. A 1280 x 800, aucun debordement n'est present.
- Apercu local : `http://127.0.0.1:4187/react/handwriting-preview.html?prompt=A`. Production inchangee; aucun fichier, schema, tentative ou service distant n'a ete modifie.
- Prochaine action sure : revue utilisateur de l'ardoise sur telephone avec le doigt ou un stylet. Apres validation ergonomique, preparer un deploiement distinct avec sauvegarde et autorisation explicite.

## 2026-09-01 - Activation Azure Speech en attente d'un abonnement

- Clarification d'architecture : l'abonnement et la ressource Speech appartiennent a la plateforme EduMaison, jamais aux familles. Tous les tenants passent par une passerelle serveur unique avec consentement, quotas par famille et secrets invisibles aux navigateurs.
- Le serveur de production actuel possede 4 vCPU, environ 8 Go de RAM et aucun GPU. Il peut supporter un petit moteur de transcription CPU pour du secours, mais il n'est pas dimensionne pour une evaluation phonemique interactive fiable et simultanee pour plusieurs familles. Whisper seul transcrit; il ne constitue pas un controle fiable de la prononciation.
- Architecture retenue pour le pilote : passerelle Speech centrale dans Laravel, fournisseur interchangeable, extraits audio courts traites sans conservation, limitation par tenant et repli navigateur en mode entrainement uniquement. Une variante auto-hebergee pourra etre ajoutee plus tard sur un noeud GPU dedie.
- La connexion au portail Azure a ete verifiee en lecture seule. Le compte connecte ne possede aucun abonnement Azure et aucun autre repertoire accessible; aucune ressource Speech ne peut donc encore etre creee.
- Le niveau Azure Speech Free `F0` convient au pilote Speaking et annonce actuellement 5 heures mensuelles gratuites de transcription en temps reel, avec une seule requete simultanee. Un abonnement Azure actif reste toutefois obligatoire et peut demander une verification de paiement.
- Aucun abonnement, moyen de paiement, ressource, cle, secret ou fichier de production n'a ete cree ou modifie.
- Prochaine action : le proprietaire doit soit activer lui-meme un abonnement Azure sur ce compte, soit se connecter avec un compte possedant deja un abonnement. Apres confirmation, creer une ressource Speech `F0`, generer ses acces avec confirmation au moment de l'action, puis effectuer les tests audio English/French hors production.

## 2026-08-31 - Fondation locale du moteur Speaking verifiable

- Le flux `oral_drill` ne depend plus seulement de la reconnaissance vocale du navigateur : une analyse audio cote serveur est prete localement pour English (`en-GB`) et French (`fr-FR`) avec Azure Speech Pronunciation Assessment. Le serveur mesure prononciation, exactitude, fluidite et completude, puis renvoie un detail borne par mot.
- La validation est maintenant autoritaire cote serveur. Le score envoye par le navigateur est ignore; seul un jeton d'evaluation serveur, lie a l'enfant, l'exercice, l'item et la phrase cible, peut produire `auto_checked`. Une simple transcription navigateur ou un passage volontaire reste `practice_only` et ne peut pas valider l'exercice.
- L'audio WAV 16 kHz mono est produit dans le navigateur puis transmis pour analyse. Le resultat normalise est conserve 15 minutes en cache; l'audio d'analyse n'est pas conserve. La conservation facultative d'un enregistrement pour le parent reste un consentement distinct.
- Un consentement familial separe `speaking_analysis_consent_at` a ete ajoute. L'interface parentale distingue clairement l'analyse automatique de la conservation audio et indique quand le moteur externe n'est pas disponible.
- L'interface enfant affiche les quatre mesures et colore les mots selon leur exactitude. Le repli Web Speech reste disponible pour pratiquer, mais il est explicitement presente comme non verifie.
- Principaux fichiers : `app/Services/Speech/PronunciationAssessmentService.php` SHA-256 `8D6736C514A649FECFE5C4E0E060AD8AE592B238E3E91D589997721DD354E9CD`; `app/Http/Controllers/Api/SpeakingAssessmentController.php` SHA-256 `5F296ACD51E03EE9540BABA834F1626F5F37938CE9BC52E0EF67565204F82FE0`; `resources/react/src/pages/child/exercises/OralDrill.tsx` SHA-256 `8AC5972FF832417AAC0B80F332EC8A529BBC25332571F52D37D241B39F25C8CA`; `resources/react/src/pages/parent/LanguageReviews.tsx` SHA-256 `B648993703FB685308D46E44D34C1A6E11F0F41D14AE4D858CA6D04CD2D2A5D9`.
- Verification locale : syntaxe PHP valide sur tous les fichiers modifies; tests autonomes reussis pour le repli `practice_only`, l'autorite du score serveur et la normalisation du fournisseur; compilation Vite reussie avec 82 modules. Bundle `main-W18G2p7V.js`, 473 182 octets. Aucun probleme TypeScript ne subsiste dans `OralDrill` ou `LanguageReviews`; le controle global conserve des erreurs preexistantes dans d'autres ecrans.
- Limite actuelle : la production ne contient ni `AZURE_SPEECH_KEY` ni `AZURE_SPEECH_REGION`; aucune requete reelle Azure n'a donc ete executee. Aucun fichier, schema, service ou secret de production n'a ete modifie.
- Prochaine action sure : creer ou choisir une ressource Azure Speech, valider le traitement externe et son cout, injecter les deux secrets sans les inscrire dans le depot, puis tester deux courts enregistrements English/French dans un environnement isole. Le deploiement en production exigera une autorisation explicite distincte.

## 2026-08-20 - Filtre anti-charabia et statut honnete des productions ecrites

- Le defaut signale a ete reproduit : `written_response` ne controlait que le nombre de fragments separes par des espaces, enregistrait directement `status=completed` avec `verification_status=pending_review`, puis la liste d'unite affichait une coche verte. Le texte prive de l'enfant n'est pas consigne dans ce statut.
- Le serveur applique maintenant un precontrole configurable avant la revue humaine : nombre minimal de vrais mots, deux phrases terminees par une ponctuation, au moins un terme familial, ratio minimal de mots English reconnus de 70 % et au plus deux mots inconnus pour permettre un nom propre. Ce filtre rejette le charabia manifeste et les textes hors sujet; il ne produit aucune note et une reponse recevable reste `pending_review` jusqu'au parent.
- L'exercice `3157` contient 14 termes familiaux et 67 mots English acceptes. La tentative signalee `2392` a ete conservee mais reclassee `incomplete/practice_only`, sans note; elle n'apparait plus dans la file parentale et l'exercice est rejouable.
- La liste d'unite distingue maintenant `pending_review` des exercices verifies. Une production soumise affiche `Parent review` en ambre au lieu d'une coche verte, ne declenche pas de celebration et retrouve le meme statut apres rechargement. Les exercices inactifs ou supprimes ne sont plus renvoyes par l'API d'unite.
- Le calcul de progression d'une unite compte uniquement les tentatives `completed` dont la verification est `auto_checked`, `client_checked` ou `parent_verified`; `practice_only` et `pending_review` ne sont plus presentes comme validees.
- Seeder transactionnel et idempotent : `database/seeders/Class1WrittenResponseQualityGateSeeder.php`, SHA-256 `96b6f5f9a9692408bd91e3a872404c8f6a1bed9aa514072279a6dd040d7e80b1`. Le controleur de tentative a le SHA-256 `450f4b94619c40bb78a08749ef96f775fa4117ca460b795e29b8888b3d3a60ae`; le controleur d'unite `4084f6302dbba8412d48c59db707c1b74e653f5e1b7f69b0d880a0f5c84c1ba7`.
- Test avant production : restauration PostgreSQL temporaire, deux executions du seeder, rejet du texte signale, d'une phrase unique et d'un texte hors sujet, acceptation sans note d'une reponse familiale claire, controle du payload d'unite et build Vite reussi avec 78 modules. Bundle `main-PGFVtR9h.js`, 460 170 octets environ, SHA-256 `63c38959efa99a9640d381d35cd42e1efb6e70fa9a665ab4df009435e4b7e7b6`.
- Sauvegardes : `/tmp/curriculum-before-writing-quality-gate-20260820.dump`, 492 842 octets, 480 entrees, SHA-256 `88a407a4645f1ff469b22c23b6d69700d263b71854f5603868393438e4c62f53`; `/tmp/edumaison-before-writing-quality-gate-20260820.tgz`, 201 338 octets, SHA-256 `0c727dbe0c8366fc04242707efeead178d96583b30ffcdb146ce8cc5fd9ca748`.
- OPcache ne revalidant pas les fichiers, `curriculum-app` a ete redemarre avec l'autorisation explicite du proprietaire. Un 502 transitoire a ete observe pendant la remise en route, puis trois controles HTTP 200 consecutifs ont confirme la stabilite. Etat final : conteneur `running`, `/app` HTTP 200, bundle HTTP 200, test anti-charabia reussi dans le conteneur recharge.
- Verification utilisateur : actualiser completement la tablette, rouvrir English > My Family Tree et refaire `My Family: Two Sentences`. Une mauvaise reponse doit rester dans l'editeur avec une erreur; une reponse recevable doit afficher `Parent review`, jamais une coche verte avant la notation parentale.

## 2026-08-20 - Premiere production ecrite courte English Class 1

- Une categorie technique `writing` distingue maintenant les productions tapees des exercices `handwriting`. La migration additive `2026_08_20_100000_add_writing_exercise_category.php`, SHA-256 `2d3538553dc88f101e8af36095e3a3a99327f94fea66ced633d161d3ec2ea548`, etend la contrainte PostgreSQL puis reclasse les deux `written_response` Class 3 existants sans modifier leur contenu ni leurs tentatives.
- Le seeder historique `Class3LanguageSkillsFoundationSeeder.php` utilise aussi `writing` pour eviter qu'une reexecution future retablisse le mauvais classement. Apres migration, les 3 productions tapees sont en `writing` et les 69 exercices de trace restent en `handwriting`.
- L'exercice English Class 1 `3157`, `My Family: Two Sentences`, est actif dans la lecon `10` My Family Tree. Il demande deux phrases tapees, au moins 6 mots, un membre de la famille, une majuscule au debut de chaque phrase et un point final. Le parent doit ensuite le corriger dans l'onglet `Productions`.
- L'exercice est relie une seule fois a la competence verifiee `120`, `Writing words and short simple sentences`, page officielle 44. Il n'est pas lie a une competence de lisibilite manuscrite et ne possede encore aucune tentative.
- Seeder transactionnel et idempotent : `database/seeders/Class1WrittenResponsePilotSeeder.php`, SHA-256 `8ce4565e52fba2fa28b9f4773434515a05275a4b85a54da480ee95adaf74e609`. Il verifie l'empreinte du PDF officiel et refuse de reecrire une definition qui aurait deja des tentatives.
- Test avant production : restauration dans un conteneur PostgreSQL temporaire, migration reussie, deux executions du seeder sans duplication, categorie et lien officiel verifies; le conteneur temporaire a ete supprime automatiquement.
- Sauvegarde complete : `/tmp/curriculum-before-class1-written-response-20260820.dump`, 492 342 octets, 480 entrees, SHA-256 `a3cec57b7f18c66afcaa004e04922165ab8dc3fbb0261aabfe95b50f3f4d2a67`.
- Audit final : English Class 1 contient 44 exercices actifs et French 18; les quatre routes de revue parentale Writing sont presentes. `https://edumaison.kamgangdavid.com/app` repond HTTP 200; `curriculum-app` est `running`, `RestartCount=0`; aucun redemarrage n'a ete necessaire.
- Verification manuelle restante : soumettre `3157` avec un enfant authentifie puis confirmer son apparition et sa notation dans l'onglet parent `Productions`. Prochaine extension sure apres ce controle : une production courte French Class 1 localisee, puis Class 2, sans recopier la meme consigne entre niveaux.

## 2026-08-20 - Doublons et illustrations English/French Class 1 nettoyes

- L'inventaire corrige le total annonce precedemment : English et French Class 1 contenaient 77 exercices actifs, soit 59 English et 18 French, et non 73.
- Huit groupes de copies pedagogiques ont ete qualifies. Seize copies English ont ete desactivees sans suppression; les huit versions conservees sont `197`, `198`, `199`, `201`, `202`, `206`, `366` et `369`. English compte maintenant 43 exercices actifs et 16 inactifs; French reste a 18 actifs.
- Les 37 tentatives portees par les copies desactivees sont intactes. Aucune de ces copies n'etait liee a une competence officielle, un parcours familial ou un media structure.
- Les 26 references d'images actives initiales ont ete comparees visuellement a leur question. Neuf fichiers distincts ont ete inspectes. Treize associations trompeuses ont ete retirees : fleur pour ciel/herbe/soleil/sang, famille de poules pour parents humains ou salutations, tomate absente de la liste de fruits, oreille pour la vue, et main pour visage, tete, jambes ou chanson du corps.
- Les images pertinentes restent en place : orange, mangue, palette, enseignante, oreille pour l'audition et main pour les mains. L'audit final trouve 0 reference SVG dans English/French Class 1, 0 groupe de doublons stricts actifs et 0 reference trompeuse parmi les treize cibles.
- Seeder transactionnel et idempotent : `database/seeders/Class1LanguageDuplicateAndImageCleanupSeeder.php`, SHA-256 `439b081b1a6c01a18e79318564aa8170124e6b6f822b24dff7bd8826091cf2d3`.
- Test avant production : restauration dans un conteneur PostgreSQL temporaire, deux executions successives reussies, assertions sur les comptes, les originaux, les tentatives et les images; le conteneur temporaire a ete supprime automatiquement.
- Sauvegarde complete : `/tmp/curriculum-before-class1-language-cleanup-20260820.dump`, 491 985 octets, 480 entrees, SHA-256 `9ec0b187e748c28b5a4ec46dabad78c4b822b2558ab262969e79206f0af200a0`.
- Runtime final : `https://edumaison.kamgangdavid.com/app` repond HTTP 200; `curriculum-app` est `running`, `RestartCount=0`; aucun redemarrage n'a ete necessaire.
- Prochaine action sure : ajouter une premiere production ecrite courte Class 1, avec consigne concrete, minimum de mots adapte a l'age et revue parentale, sans la declarer comme preuve d'ecriture manuscrite.

## 2026-08-20 - Pilote de dictee English et Francais Class 1

- Le programme officiel manquant a ete retrouve et enregistre : `Cameroon Primary School Curriculum - English Subsystem - Level I: Class 1 & Class 2`, MINEDUB, 2018, document `3`, statut `verified_source`. Fichier prive `curricula/cameroon-primary-english-level-1-class-1-2.pdf`, 1 763 995 octets, SHA-256 `38a7c7eddea5ede2bc05bf071716e7067fd9fb987cad2d43fd33a9e294baf957`.
- Les pages English 41-47 demandent notamment la conscience phonemique, la construction et l'orthographe de mots d'une a trois syllabes, puis l'ecriture de mots et de phrases courtes. Les pages Francais 56-62 couvrent mots usuels, graphies des phonemes, mots et phrases simples, majuscule et ponctuation de base.
- Cinq competences Class 1 verifiees ont ete creees : English `119` Sound and word building et `120` Writing words and short simple sentences; French `121` Writing familiar French words and simple sentences, `122` Capital letters and basic punctuation et `123` French phoneme spellings.
- Quatre dictees actives ont ete creees : `3153` Build and Spell Words (5 mots English), `3154` My Home and School (3 phrases English), `3155` Les mots de la maison (4 groupes French) et `3156` Une phrase a l'ecole (3 phrases French). Elles utilisent `en-GB` ou `fr-FR`, trois ecoutes maximum et le moteur de notation verifiable.
- Les liens sont volontairement limites a six preuves de mots, orthographe et phrases simples. Aucune dictee clavier n'est liee a une competence de lisibilite manuscrite.
- Seeder transactionnel et idempotent : `database/seeders/Class1LanguageFoundationPilotSeeder.php`, SHA-256 `af0a3d2ea655342e93f5b87ebd89005e0f7b1147c3d780c25fc2885a93b2cb3d`. Il verifie l'empreinte du PDF et refuse de modifier une definition devenue differente apres des tentatives.
- Test avant production : restauration dans un conteneur PostgreSQL temporaire, deux executions successives reussies, 4 exercices uniques, 5 competences, 6 liens, comptes stables English 59 et French 18. Le conteneur temporaire a ete supprime apres le test.
- Sauvegarde complete : `/tmp/curriculum-before-class1-language-pilot-20260820.dump`, 491 160 octets, 480 entrees, SHA-256 `6f5031e507597bd4d7255b851c3545f41ba8d998ae9a25d59a9a2744873a1ee1`.
- Audit final : exercices `3153-3156`, 0 tentative, 0 contrat de dictee invalide; English Class 1 contient 59 exercices actifs et French 18. `/app` repond HTTP 200; `curriculum-app` est `running`, `RestartCount=0`; aucun redemarrage n'a ete necessaire.
- Prochaine action sure : auditer les 73 exercices English/French Class 1 existants, en particulier les triplicats historiques des lecons synthetiques, puis ajouter une premiere production ecrite courte avec revue parentale sans la confondre avec l'ecriture manuscrite.

## 2026-08-20 - Moteur de dictee verifiable et retour pedagogique

- L'inventaire de production confirme que seules deux dictees existent, toutes deux en English Class 3 (`3149`, `3150`), sans tentative enregistree. Aucun autre niveau ne possede encore de `dictation` ou de `written_response`; les `oral_drill` sont en revanche presents de la Pre-Nursery a la Class 6.
- Le serveur ne note plus la dictee avec la seule distance de mots normalisee. Chaque phrase recoit maintenant quatre mesures recalculees cote serveur : mots 60 %, orthographe exacte et accents 25 %, majuscule initiale 5 %, ponctuation 10 %. La preuve conserve le detail par item sous `dictation_words_spelling_mechanics`.
- Une tentative est rejetee si un item n'a pas ete ecoute ou si le nombre d'ecoutes depasse la limite de l'exercice. Le serveur exige autant de compteurs d'ecoute que de phrases et borne la limite de 1 a 5.
- Le lecteur enfant bloque la saisie avant la premiere ecoute, desactive l'autocorrection, fige la reponse apres validation et affiche ensuite la phrase produite, la phrase attendue et les quatre indicateurs avant de continuer. Les libelles s'adaptent a `en-GB` et `fr-FR`; les apostrophes droites et typographiques sont harmonisees.
- Tests isoles : phrase exacte 100; phrase correcte sans majuscule ni point 85; mot omis 86; deux accents manquants dans une phrase francaise 57; tentative sans ecoute rejetee. Lint PHP reussi et build Vite reussi, 78 modules, bundle `main-Bh3n0ciq.js` (459 134 octets, 108,47 kB gzip).
- Sauvegarde fichiers : `/tmp/edumaison-before-dictation-engine-20260820.tgz`, 193 650 octets, SHA-256 `494b70880e0c2312b5c3536c2e4f056e17187231defd22f532f1ea57e72c2ef4`.
- Runtime final : `/app` et `main-Bh3n0ciq.js` repondent HTTP 200; `curriculum-app` est `running`, `RestartCount=0`. Les empreintes du controleur et du lecteur deployes correspondent aux fichiers testes. Un redemarrage cible a recharge PHP-FPM sans erreur.
- Verification navigateur : la page publique EduMaison s'affiche correctement, puis demande normalement de creer ou d'ouvrir une famille. La session de test n'etant pas authentifiee, l'ecoute TTS et le parcours complet restent a verifier sur la tablette familiale.
- Prochaine action sure : construire un premier lot pilote pour Class 1 ou Class 2 avec dictees de mots, groupes nominaux et phrases courtes alignees au programme, puis ajouter progressivement production ecrite et Speaking revu par le parent. Ne pas recopier les phrases Class 3 dans les autres niveaux.

## 2026-08-20 - Revue parentale Writing et Speaking avec audio prive

- Le tableau parent possede maintenant un onglet `Productions`, reserve aux comptes familiaux authentifies. Il regroupe les ecritures en attente et les enregistrements Speaking du foyer actif; les routes de revue et de medias appliquent `family.access`, `auth:sanctum` et un controle d'appartenance au foyer.
- Writing et Handwriting ne recoivent plus leur note finale sans lecture humaine : le parent voit le texte ou les traces manuscrites privees, utilise une grille sur 4 (consigne, phrases, orthographe, ponctuation), ajoute un commentaire facultatif et produit une note `parent_verified` comprise entre 0 et 100.
- Speaking conserve la transcription comme mesure d'intelligibilite, sans la presenter comme une preuve de prononciation. Le parent peut ecouter chaque prise, attribuer une note de prononciation, commenter et supprimer definitivement le fichier. La tentative d'exercice devient `parent_verified` seulement quand toutes ses prises ont ete evaluees.
- La conservation audio est desactivee par defaut pour les 2 foyers existants. Un parent doit l'activer explicitement; meme apres ce consentement, l'enfant doit cocher `Save my voice for parent review` pour chaque exercice. L'enregistrement prive dure au maximum 12 secondes, utilise un faible debit, n'est jamais place dans `public`, et sa duree de conservation est reglable de 7 a 90 jours (30 par defaut).
- Le serveur refuse tout audio sans consentement, valide le type data URL et limite le binaire a 2,5 Mo. Les chemins ne sont pas stockes dans le JSON de reponse enfant : ils sont relies a `pronunciation_attempts.exercise_attempt_id`; les fichiers incomplets sont supprimes si la transaction echoue.
- Migration additive appliquee : `2026_08_19_090000_add_language_review_and_audio_consent.php`. Elle ajoute le consentement et la retention a `households`, puis une cle etrangere nullable avec suppression en cascade vers `exercise_attempts`.
- Verification : lint PHP reussi sur 6 fichiers; deux builds Vite isoles reussis, 78 modules; bundle `main-hV16q1Le.js` (456 118 octets, 107,39 kB gzip). Les 7 routes parentales affichent les middlewares attendus; leur acces anonyme renvoie HTTP 401 apres rechargement de PHP-FPM.
- Sauvegardes : `/tmp/curriculum-before-language-review-audio-20260820.dump`, 489 659 octets, 479 entrees, SHA-256 `969fcad957e61857d0ac32e1b9d9d55e22ecc85bdd33128843876ff4169b9671`; `/tmp/edumaison-files-before-language-review-audio-20260820.tgz`, 198 233 octets, SHA-256 `0615c0996aaf8219871e88d9c15cdbddac9c33b256da97246c94cd6b669a6626`.
- Runtime final : `/app` et le nouveau bundle repondent HTTP 200; `curriculum-app` est `running`, `RestartCount=0`. Un redemarrage cible a ete necessaire pour recharger la table des routes. Aucun audio n'etait stocke a la fin du deploiement.
- Verification restante : faire un parcours manuel authentifie sur tablette avec un parent et un enfant afin de confirmer l'autorisation micro, l'ecoute audio, l'affichage d'une trace manuscrite et les deux validations. Prochaine evolution fonctionnelle : definir le moteur de dictee et decliner ces controles Writing/Speaking sur les autres niveaux scolaires.

## 2026-08-18 - Fondation verifiable Reading, Speaking, Writing et dictee

- Le circuit des resultats a ete corrige de bout en bout. `exercise_attempts` conserve maintenant `verification_status`, les reponses structurees existantes et une preuve JSON; la migration additive `2026_08_18_120000_add_verification_evidence_to_exercise_attempts.php` a ete appliquee sans redemarrage.
- Le serveur ne fait plus confiance au score annonce par le navigateur pour les QCM/multiple choice, fill-in, vrai/faux, associations, ordre de phrase, dictee et oral drill. Il recalcule les reponses contre le contenu versionne de l'exercice et impose une note comprise entre 0 et 100.
- Le lecteur Fill-in ne compte plus deux fois la derniere reponse correcte; ce defaut pouvait produire un score superieur a 100 %. QCM, Fill-in, MatchPairs, SentenceOrder et TrueFalse transmettent maintenant la reponse choisie au serveur.
- Speaking n'accorde plus 90 % par auto-evaluation lorsque la reconnaissance vocale est indisponible. Un element non reconnu est marque `practice_only`; les transcriptions reconnues sont conservees dans `pronunciation_attempts`. Le statut et la preuve precisent que la correspondance de transcription mesure l'intelligibilite et ne certifie pas encore la prononciation, la prosodie ou le rythme.
- Handwriting refuse une ardoise vide ou un trace insuffisant, capture chaque production en WebP prive et enregistre la tentative avec `pending_review` et une note nulle. Le bouton historique `Looks good` et l'attribution automatique de 100 % ont ete retires.
- Deux lecteurs ont ete ajoutes : `dictation`, corrige cote serveur par distance de mots avec limite d'ecoutes, et `written_response`, qui impose une longueur minimale et conserve la production sans note jusqu'a sa revue.
- Six exercices Class 3 ont ete ajoutes : comprehension multi-question `3147` et `3148`, dictees `3149` et `3150`, productions ecrites `3151` et `3152`. Les productions ecrites sont reliees a la competence verifiee `Legible and coherent writing`; les dictees restent des activites complementaires non declarees comme preuve complete de cette competence.
- L'exercice Reading `1657` contient maintenant son propre passage sur Ambe au marche au lieu de faire reference a un passage absent. Le lecteur QCM affiche les passages dans un bloc distinct avec ecoute facultative.
- Seeder transactionnel et idempotent : `database/seeders/Class3LanguageSkillsFoundationSeeder.php`. Class 3 contient maintenant 354 exercices actifs.
- Tests : lint PHP sans erreur; deux builds Vite reussis, 77 modules; bundle public `main-B2Zvi7dK.js` (444,62 kB, 104,61 kB gzip); test autonome des scores reussi sur le code deploye; audit final de 354 exercices, 0 contrat casse, export `.codex-tmp/class-3-active-exercises-audit-language-20260818.json`, SHA-256 `285e10bbfda3f41e37272134bd5d81f01b4f03307d74556c62f03fc0e222f84d`.
- Sauvegardes : `/tmp/curriculum-before-language-verification-20260818.dump`, 490 314 octets, 479 entrees, SHA-256 `b303f460fbc186a587301c4c55a51c5796a119224b658868a60adf4d3c5c76bf`; `/tmp/edumaison-language-files-before-20260818.tgz`, SHA-256 `d68ac234824740e07f743cb82bbfb6999078361b389dd8bffa925f735f812de7`; `/tmp/edumaison-objective-readers-before-20260818.tgz`, SHA-256 `f99594bd4f487c3bc41ca8573803189a18fd74034f83557ed42c74b4710053ce`.
- Runtime final : `/app` HTTP 200; `curriculum-app` est `running`, `RestartCount=0`. Aucun navigateur controle n'etait disponible pour la verification visuelle authentifiee; une verification manuelle tablette reste requise pour micro, dictee, trace et redaction.
- Prochaine action sure : ajouter la file de revue parentale des ecritures, enregistrer l'audio Speaking avec consentement et evaluation de prononciation adaptee aux accents, puis reclasser les anciens exercices Reading de grammaire/vocabulaire avant de decliner dictee et production ecrite sur les autres niveaux.

## 2026-08-18 - ICT Class 3 aligne et dessins verifies

- Les pages MINEDUB 89-93 ont ete controlees visuellement : composants et ordinateurs integres, appareils necessitant un logiciel, usages du computer, clavier, souris, traitement de texte, sources fiables, navigateurs, reseaux sociaux et sante devant les ecrans.
- Les 11 exercices ICT restent actifs et n'avaient aucune tentative. Dix exercices portent dix liens stricts vers dix competences officielles, sans lien multiple ni lien croise.
- Trois lacunes officielles restent visibles : ordinateurs a composants integres, sources fiables et sante/securite devant les ecrans. `716` Windows OS reste actif mais non certifie, car les systemes d'exploitation sont dans la colonne Class 4.
- Les dessins ont ete verifies visuellement et par HTTP. `computer.png` montre un reparateur avec deux laptops : il est conserve seulement pour l'exercice general `714`. `internet.png`, un symbole Wi-Fi, est conserve pour `720` et `721`. Les deux fichiers repondent HTTP 200.
- Six references visuelles trompeuses ont ete retirees : le dessin generique computer pour monitor, keyboard, mouse, software et browser (`711`, `712`, `715`, `718`, `719`), ainsi que `stem.png`, qui representait des fleurs, sur Windows OS (`716`).
- L'exercice `713` utilise quatre emoji valides et pris en charge par le lecteur MatchPairs : souris, clavier, moniteur et imprimante. Son instruction dit maintenant exactement `Match each computer part to its picture.` Aucun SVG n'est reference par les exercices ICT Class 3.
- La structure distingue maintenant `Word Processor`, `Web Browsers and Information` et `Social Media and Privacy`; les exercices `717` et `721` ont ete replaces sans changer leurs IDs.
- L'audit de contrat reutilisable `.codex-tmp/audit_class4_contracts.mjs` valide desormais explicitement les formats MatchPairs `word/image`, `left/right` et tableau, ainsi que les deux cotes non vides.
- Seeder transactionnel et idempotent : `database/seeders/Class3IctAlignmentPilotSeeder.php`, SHA-256 `f3fc9ef6d5ef5ce96379e0bcf755fd34743a619309529f65a44cd3b12147dc7a`.
- Export final : `.codex-tmp/class-3-active-exercises-audit-ict-20260818.json`, SHA-256 `701a01b1417e0f2825e6f65ead4a94e497c71ec1ebcd8621d35430ff7c244e08`. Audit global : 348 actifs, 0 contrat casse et 0 doublon exact interne a la Class 3.
- Sauvegarde complete : `/tmp/curriculum-before-class3-ict-alignment-20260818.dump`, 489 255 octets, 479 entrees, SHA-256 `87f72c08b22f1e21e73fba89f7660fe05910dea8c390e0b94d5fb33c12c60531`.
- Verification runtime : `/app` HTTP 200; `curriculum-app` est reste `running` avec `RestartCount=0`, sans redemarrage.
- Prochaine action sure : auditer Reading Class 3, puis Handwriting, Citizenship et National Languages and Cultures.

## 2026-08-18 - Physical Education Class 3 nettoye et aligne

- Les pages MINEDUB 83-84 ont ete controlees visuellement. Le programme Class 3 couvre mouvement rythmique, relais, sprint de 20 a 40 metres, sauts, lancers, sports d'equipe et gymnastique.
- Les dix exercices initiaux etaient presque entierement centres sur les regles des sports d'equipe. La copie semantique `2106`, sans tentative, a ete desactivee et jamais supprimee; l'original `1505` reste actif. Physical Education passe de 10 a 9 actifs et la Class 3 globale de 349 a 348.
- L'unique tentative, portee par `1508` Fair play, est preservee. Quatre images de chat hors sujet ont ete retirees, y compris sur la copie inactive afin de conserver l'idempotence.
- Trois regles de football ont ete precisees contre les Lois du jeu IFAB : but direct sur corner contre l'adversaire (`1506`), maniement du ballon par le gardien dans sa propre surface (`1507`) et franchissement complet de la ligne entre les poteaux sous la barre (`2109`). L'amorce basketball de `2110` utilise maintenant `jump ball`.
- La lecon sourcee s'intitule maintenant `Rules and Roles in Team Sports`; les exercices `2109`-`2111` y ont ete replaces sans changer leurs IDs.
- Sept competences officielles Physical Education sont actives. Sept exercices distincts portent sept liens stricts vers les sports d'equipe, sans lien multiple ni lien croise.
- Six competences restent volontairement sans exercice : mouvement rythmique, relais, sprint, sauts, lancers et gymnastique. `2107` Swimming Safety et `2108` Exercise Benefits restent actifs mais non certifies, car ils ne correspondent pas strictement aux contenus des pages 83-84.
- Seeder transactionnel et idempotent : `database/seeders/Class3PhysicalEducationAlignmentPilotSeeder.php`, SHA-256 `ec83cf35e94855ca6331774dff4015d5f0d0934b6ee5ac2c32c14c61d7c86b38`.
- Export final : `.codex-tmp/class-3-active-exercises-audit-physical-education-20260818.json`, SHA-256 `d29a7ad57d2cb500c9a7a860f24da464e2f65e72ca245d6df11bb8120f26d51c`. Audit global : 348 actifs, 0 contrat casse et 0 doublon exact interne a la Class 3.
- Sauvegarde complete : `/tmp/curriculum-before-class3-physical-education-alignment-20260818.dump`, 488 295 octets, 479 entrees, SHA-256 `5da898c5367a4d55e5a577579d1c4909c3628e78151926c165dd4ae67e937d24`.
- Verification runtime : `/app` HTTP 200; `curriculum-app` est reste `running` avec `RestartCount=0`, sans redemarrage.
- Prochaine action sure : auditer ICT Class 3 contre ses pages officielles, puis Reading, Handwriting, Citizenship et National Languages and Cultures.

## 2026-08-18 - Vocational Studies et Arts Class 3 restructures et alignes

- Les pages MINEDUB 77, 79-82 ont ete controlees visuellement pour Home Economics, Vocational Arts and Crafts, Agro-Pastoral Farming, Visual Arts et Performing Arts.
- Huit exercices de musique (`2970`-`2977`) ont ete deplaces de Vocational Studies vers une nouvelle structure officielle `Performing Arts > Music` dans Arts and Crafts. Les exercices de weaving, modelling et decoration `1501`-`1503`, ainsi que les outils de carpentry/weaving, sont maintenant ranges dans le composant officiel Vocational `Arts and Crafts`.
- La structure Arts and Crafts distingue maintenant `Visual Arts` et `Performing Arts`; l'exercice de couleur `2070` est range sous `Painting and Colour`. Vocational distingue l'usage sur des outils et la production artisanale.
- La copie semantiquement identique `2805` a ete desactivee, jamais supprimee; l'original `2958` reste actif. Les deux exercices avaient zero tentative. Le total Class 3 passe de 350 a 349 actifs.
- Dix-huit references d'images manifestement hors sujet ont ete retirees. La question `2970` associe desormais correctement le mvet aux peuples Beti-Fang plutot qu'aux Baka; la correction a ete recoupee avec le Musee national du Cameroun.
- Treize competences officielles Vocational et six competences Arts sont actives. Vingt-et-un exercices distincts produisent 21 liens stricts, sans lien multiple ni lien croise : 16 en Vocational et 5 en Arts.
- Les lacunes officielles restent visibles : needle work, fastenings, preservation des aliments/table setting, folding/cutting, outils agricoles, farming/gardening, germination, livestock, photography, architecture, dance et theatre n'ont aucun exercice strictement correspondant.
- Treize exercices Vocational restent actifs mais non certifies, notamment food groups/balanced diet et les metiers mason/electrician/plumber qui ne figurent pas dans la colonne officielle Class 3. Dix exercices Arts restent non certifies, dont printing, sculpture, perspective et quatre activites de connaissances musicales ne demontrant pas directement la performance attendue.
- Seeder transactionnel et idempotent : `database/seeders/Class3VocationalArtsAlignmentPilotSeeder.php`, SHA-256 `2e7990db4aff2e9a11a92aee17ca5a5f400e8841cb8130bc0532dee12e9f28fa`.
- Export final : `.codex-tmp/class-3-active-exercises-audit-vocational-arts-20260818.json`, SHA-256 `185a2a15b51173031a45c39be4abe7ce48d2b6dda6d182e7332f320606bcfa53`. Audit global : 349 actifs, 0 contrat casse et 0 doublon exact interne a la Class 3.
- Sauvegarde complete : `/tmp/curriculum-before-class3-vocational-arts-alignment-20260818.dump`, 486 158 octets, 479 entrees, SHA-256 `bfcc329895e4ae3c2e1648ed24d1b7c0fd2abbb5ee9e7b14edadc146a1030b52`.
- Verification runtime : `/app` HTTP 200; `curriculum-app` est reste `running` avec `RestartCount=0`, sans redemarrage.
- Prochaine action sure : inventorier les matieres Class 3 restantes non encore auditees, puis poursuivre avec la prochaine matiere contre ses pages officielles.

## 2026-08-18 - Social Studies Class 3 aligne

- Les pages MINEDUB 71-75 ont ete controlees visuellement pour History, Geography, Citizenship/Civics et Moral Education. L'inventaire contient 27 exercices actifs et trois tentatives, toutes preservees.
- Deux exercices sur les Bamileke et les Fulani (`1337`, `1338`) ont ete deplaces de History vers une nouvelle lecon sourcee `Peoples and Ethnic Groups` dans Human Geography.
- Huit references d'images manifestement sans rapport avec les questions ont ete retirees : bras pour Fulani, chat pour vote/UNESCO, glace pour taxes/droits, fourmi pour valeurs, table pour tricherie et puits pour devoirs. Les exercices conservent leur illustration generique et leur contenu.
- Onze competences officielles Social Studies Class 3 sont actives; 14 exercices distincts produisent 14 liens, sans lien croise ni lien multiple.
- Six competences restent volontairement sans exercice : histoire/sources, geographie physique/meteo, geographie economique, emblemes nationaux, regles et institutions de l'Etat. Treize exercices actifs restent non certifies, notamment relief et villes du Cameroun, droits de l'enfant et resolution des conflits.
- Dix-sept contenus Social Studies sont strictement identiques entre Class 3 et Class 4. Les themes valeurs, elections et institutions figurent dans les deux colonnes officielles mais avec des attentes differentes; ils sont conserves pour les deux niveaux et devront etre differencies lors de l'audit Class 4.
- L'exercice Elections conserve `20 years` comme age electoral. Ce point a ete reverifie en aout 2026 contre ELECAM, qui confirme l'age de vote de 20 ans au Cameroun.
- Seeder transactionnel : `database/seeders/Class3SocialStudiesAlignmentPilotSeeder.php`, SHA-256 `02588c68975a70c6285ed1f2f58235e03d878ed5584c41a25d6b6fd91e3ca3ad`.
- Export final : `.codex-tmp/class-3-active-exercises-audit-social-studies-20260818.json`, SHA-256 `e4327f4bd74ff17a06a1bb44dfe163eeabf5ba82e217ae41d87adee410e7d3a4`. Audit global : 350 actifs, 0 contrat casse et 0 doublon exact interne a la Class 3.
- Sauvegarde complete : `/tmp/curriculum-before-class3-social-studies-alignment-20260818.dump`, 484 597 octets, 479 entrees, SHA-256 `d9467e4c7788eec4720d3acec6ca88b6d110c0b9dcbb156ec474ce540ae119dc`.
- Verification runtime : `/app` HTTP 200; `curriculum-app` est reste `running` avec `RestartCount=0`, sans redemarrage.
- Prochaine action sure : auditer Vocational Studies Class 3 contre les pages officielles suivantes, puis poursuivre les matieres restantes de Class 3.

## 2026-08-18 - French Class 3 nettoye et aligne

- Les pages MINEDUB 29-30 et 61-70 ont ete extraites puis controlees visuellement. Les attentes terminales couvrent l'oral, la lecture et la production ecrite; les pages detaillees placent en Class 3 le groupe nominal, le groupe verbal/COD, les adjectifs qualificatifs, leur accord et la description.
- Onze paires de contenus strictement identiques et sans aucune tentative ont ete verifiees. Onze copies ont ete desactivees, jamais supprimees; French Class 3 passe de 22 a 11 exercices actifs et la Class 3 globale de 361 a 350.
- L'exercice `489` proposait correctement `etre` mais pointait sur `aller`; son index de reponse est corrige de `1` a `0`. La copie inactive `2146` a recu la meme correction afin de conserver l'idempotence du seeder.
- L'exercice COD `494`, auparavant range sous la description, se trouve maintenant dans une nouvelle lecon sourcee `Identifier le groupe verbal et le COD` sous le theme Grammaire.
- Six competences officielles French Class 3 sont actives; sept exercices distincts produisent sept liens, sans lien croise ni lien multiple. Les competences oral et lecture restent volontairement sans exercice afin de rendre les lacunes visibles.
- Les quatre exercices de passe compose `488`-`491` restent actifs mais non certifies Class 3. Le referentiel les place explicitement en Class 4; leur transfert attend l'audit complet du French Class 4 pour choisir une lecon thematique sans creer de doublon.
- Le premier lancement a ete refuse par la contrainte `lessons_type_check` sur une valeur de type non permise; toute la transaction a ete annulee. Le type a ete aligne sur les lecons voisines (`mixed`), puis le lancement a reussi sans redemarrage.
- Seeder transactionnel : `database/seeders/Class3FrenchAlignmentPilotSeeder.php`, SHA-256 `042c906a330a56d1dfb746e2dda73472a3c02f172f1a41b14d0a3176f85265ee`.
- Export final : `.codex-tmp/class-3-active-exercises-audit-french-20260818.json`, SHA-256 `feb6fe0b7397c84c9ddcb46c5f4afeae6b429484bd239bb357cb98cce9d596cd`. Audit final : 350 exercices actifs, 0 contrat casse et 0 groupe de doublons exacts sur toute la Class 3.
- Sauvegarde complete : `/tmp/curriculum-before-class3-french-alignment-20260818.dump`, 483 633 octets, 479 entrees, SHA-256 `c5537a60b2b7823bb372d283985f247989e36c12c02b6bbccbccaef182bfc79f`.
- Verification runtime : `/app` HTTP 200; `curriculum-app` est reste `running` avec `RestartCount=0`.
- Prochaine action sure : inventorier Social Studies Class 3 contre les pages officielles 71-74, puis continuer matiere par matiere jusqu'a couvrir les 13 matieres de Class 3 avant les autres niveaux.

## 2026-08-18 - Mathematics Class 3 nettoye et aligne

- Les pages MINEDUB 50-52 ont ete controlees visuellement. La Class 3 couvre ensembles/logique, nombres et operations jusqu'a 500, mesures/temps/monnaie jusqu'a 1 000 F, geometrie et ligne numerique jusqu'a 30, puis statistiques et graphiques.
- Neuf groupes de trois exercices Math identiques ont ete verifies. Dix-huit copies sans tentative ont ete desactivees, jamais supprimees; les originaux `176` et `182` conservent leurs deux tentatives BODMAS. Mathematics Class 3 passe de 110 a 92 exercices actifs et la Class 3 globale de 379 a 361.
- Vingt-cinq exercices manifestement mal ranges ont ete replaces sans changer leurs IDs : ligne numerique et cercle vers geometrie; temps et chaine metrique vers mesure; ensembles, monnaie, pictogrammes et graphiques vers l'unite correspondante.
- Cinq competences officielles Math Class 3 sont actives et sourcees : ensembles/logique, nombres et operations, mesure/temps/monnaie, geometrie/espace, statistiques/graphiques. Cinquante-quatre exercices distincts portent 54 liens; aucun lien ne traverse une autre matiere et aucun exercice n'est relie deux fois.
- Vingt-et-un exercices candidats restent actifs mais non certifies Class 3 : nombres jusqu'a 1 000, BODMAS, HCF, perimetre, union et arrondis. Ils devront etre arbitres comme Class 4 ou parcours extra; aucune tentative n'a ete perdue.
- L'audit final confirme 361 exercices Class 3 actifs, 0 contrat casse, 0 doublon Mathematics actif et 11 groupes de doublons exacts encore presents dans les autres matieres.
- Seeder transactionnel : `database/seeders/Class3MathematicsAlignmentPilotSeeder.php`. Export final : `.codex-tmp/class-3-active-exercises-audit-mathematics-20260818.json`, SHA-256 `7ccc81a3500d71d8632ddb3e53f965ad3ed4948b8af9a46e0547a025ec93d270`.
- Sauvegarde complete : `/tmp/curriculum-before-class3-mathematics-alignment-20260818.dump`, 482 534 octets, 468 entrees, SHA-256 `761009978b4b36488cc45c6b647062b1c279ef81427c44a4a14b55af0c8efd0b`.
- Verification runtime : `/app` HTTP 200; `curriculum-app` est reste `running` avec `RestartCount=0`, sans redemarrage.
- Prochaine action sure : auditer French Class 3, en commencant par les 11 groupes de doublons exacts sans tentative, puis poursuivre les autres matieres de Class 3 avant de passer aux autres niveaux scolaires.

## 2026-08-18 - English Class 3 nettoye et cadrage Mathematics Class 3

- English Class 3 a ete compare aux pages MINEDUB 41-49. Huit groupes de contenus strictement identiques, generes en plusieurs exemplaires et sans aucune tentative, ont ete verifies avant correction.
- Dix-sept copies redondantes ont ete desactivees, jamais supprimees; huit exercices canoniques restent actifs. English Class 3 passe de 47 a 30 exercices actifs.
- Cinq exercices ont ete replaces : quatre activites de prepositions/temps/grammaire vers `Unit 1 - Grammar`, et `Arrange words C3` vers `Unit 4 - Writing Skills`.
- Trois competences English Class 3 sourcees sont actives; cinq liens prudents ont ete ajoutes : oral 3, comprehension 1, ecriture 1.
- Les doublons actifs globaux de Class 3 passent de 28 groupes/74 lignes/46 redondances potentielles a 20 groupes/49 lignes/29 redondances potentielles.
- Le total Class 3 passe de 396 a 379 exercices actifs uniquement par desactivation de copies sans tentative. L'audit confirme 0 contrat casse et `/app` HTTP 200.
- Seeder idempotent : `database/seeders/Class3EnglishAlignmentPilotSeeder.php`.
- Sauvegarde : `/tmp/curriculum-before-class3-english-alignment-20260818.dump`, 481 069 octets, 479 entrees, SHA-256 `807d5b1948ecd63a79595213e87dc40d9e3011342d80a1404c68aa7e544c7acc`.
- Mathematics Class 3 a ete compare visuellement aux pages MINEDUB 50-52. Le programme officiel couvre ensembles/logique, nombres et operations jusqu'a 500, mesure/temps/monnaie jusqu'a 1 000 F, geometrie/nombre line jusqu'a 30 et statistiques/cartes.
- Cadrage Mathematics : 110 exercices avant nettoyage; 9 groupes de doublons exacts; contenus potentiellement hors perimetre identifies sur BODMAS, HCF, perimetre, arrondis et nombres superieurs a 500. Deux exercices BODMAS canoniques portent chacun une tentative; aucune desactivation mathematique n'a encore ete faite.
- Prochaine action sure : qualifier individuellement les 110 exercices Mathematics Class 3, desactiver seulement les copies sans tentative, puis separer officiel, reclassement et extra avant toute correction de structure.

## 2026-08-18 - Debut de l'audit complet Class 3 et pilote Science

- Inventaire Class 3 : 396 exercices actifs sur 13 matieres. Avant correction, 5 exercices avaient un contrat de lecteur casse et aucune competence officielle n'etait reliee.
- Contrats repares sans perte d'historique : `1454` fournit maintenant un tableau `answer`; les options MCQ `2070`, `2071`, `2106` et `2107` sont de vrais tableaux. L'audit post-correction confirme 396 actifs et 0 erreur de contrat.
- Doublons exacts Class 3 : 28 groupes, 74 lignes, 46 redondances potentielles. Vingt-six groupes n'ont aucune tentative; deux groupes Mathematics (`BODMAS` et `BODMAS rule`) portent chacun une tentative. Aucun doublon n'a ete desactive.
- Science Class 3 a ete compare a la colonne officielle Class 3 des pages MINEDUB 53-60.
- Trois lecons sourcees ont ete creees : `Drugs and Health Hazards`, `Matter and Water`, `Machines and Communication`. Huit exercices manifestement mal ranges y ont ete deplaces sans changer leurs IDs.
- Huit competences Science Class 3 verifiees ont ete creees; 23 exercices actifs clairement correspondants produisent 23 liens, sans lien croise vers une autre matiere.
- Une competence reste sans exercice : oiseaux, poissons, insectes, plantes et dispersion des graines (page 57). Les autres lacunes concernent aussi le corps/sens, la sante reproductive, les accidents/alimentation, les animaux, la pollution, les machines et l'electricite.
- Les sept exercices sur chaines alimentaires (`1801`-`1804`), gravite et magnetisme (`1805`-`1807`) restent actifs mais non certifies; ils seront arbitres comme extras ou niveau superieur.
- Les 33 exercices Science Class 4 non relies n'ont pas ete reclasses : l'audit Class 3 confirme des chevauchements sur la matiere et les sols, donc un transfert global creerait des redondances.
- Seeders idempotents : `database/seeders/Class3ExerciseContractRepairSeeder.php` et `database/seeders/Class3ScienceAlignmentPilotSeeder.php`.
- Sauvegardes : `/tmp/curriculum-before-class3-contract-repair-20260818.dump` (480 147 octets, 479 entrees, SHA-256 `80d02466f84aa72cb0e332757892a4eadb783240523f50afe97dfb4fedda25ee`) et `/tmp/curriculum-before-class3-science-alignment-20260818.dump` (480 203 octets, 479 entrees, SHA-256 `49f348f98b8985f09e78529de7642d300428f0d937c550c1d6dad1f3111aca59`).
- Verification finale : export Class 3 SHA-256 `f579c350a1bd2b827b241580be2b3805c5a14d8e632499d083efc9a75dfa800e`, `/app` HTTP 200, aucun redemarrage.
- Prochaine action sure : auditer English Class 3 contre les pages 41-49, puis Mathematics Class 3 contre les pages 50-52, avant les autres matieres.

## 2026-08-18 - Pilote Science and Technology Class 4 et controle inter-niveaux

- Les pages officielles MINEDUB Level II 53-60 ont ete inspectees visuellement pour Health Education, Environmental Science et Technology and Engineering en Class 4.
- Huit competences officielles Science Class 4 ont ete creees et activees avec `verification_status=verified_source`, codes stables et pages sources.
- Soixante-cinq exercices actifs clairement correspondants sont relies une seule fois : corps/sens/hygiene 20, maladies 11, accidents/alimentation 13, environnement/animaux 5, eau/pollution 3, energie/electricite/securite 13.
- Deux competences officielles restent volontairement sans exercice afin de rendre les lacunes visibles : oiseaux/peche/insectes/plantes (page 57) et machines/construction/plomberie/telecommunications (page 59).
- Science Class 4 conserve 98 exercices actifs. Aucun lien ne traverse une autre matiere; 65 exercices uniques produisent 65 liens.
- Trente-trois exercices restent non relies : plantes/graines 9, etats de la matiere 7, deux contenus eau hors correspondance stricte, types de sols 6 et machines simples 9. Ils n'ont ete ni desactives ni deplaces.
- Le controle inter-niveaux a exporte 396 exercices actifs Class 3. Il montre que Class 3 contient deja des exercices sur les sols et les etats de la matiere, avec deux exercices `States of matter` actuellement ranges sous `Bones and Muscles`; aucun reclassement depuis Class 4 ne doit preceder l'audit complet de Class 3.
- Export reutilisable ajoute : `.codex-tmp/export_edumaison_level_exercises.php`. Export Class 3 SHA-256 `5c22b1d4427bdcbf129ca3d2f781ef0341cd3a6d9bfa3a71db9b69fafceada37`.
- Seeder idempotent : `database/seeders/Class4ScienceCompetencyPilotSeeder.php`.
- Verification globale apres livraison : 433 exercices Class 4 actifs, 0 erreur de contrat, `/app` HTTP 200, aucun redemarrage.
- Sauvegarde : `/tmp/curriculum-before-class4-science-competencies-20260818.dump`, 479 163 octets, 479 entrees, SHA-256 `79e7cd101164a59615e260a46e87ad6d6dcfd5c8036916cd3264ab48f128499b`.
- Methode retenue pour tous les niveaux : document officiel, inventaire technique, correspondance contenu/niveau/matiere, controle des doublons et tentatives, sauvegarde, correction limitee, puis verification. Prochaine action sure : auditer la Class 3 completement avant tout reclassement des 33 candidats Science.

## 2026-08-18 - Contrats d'exercices et pilote d'alignement English Class 4

- Inventaire detaille de 433 exercices actifs Class 4 exporte et controle contre les contrats reels des lecteurs React.
- Cinq contrats casses ont ete repares sans changer les IDs ni l'historique : `1469` fournit maintenant un tableau `answer` a `sentence_order`; les options des QCM `2076`, `2077`, `2112` et `2113` sont maintenant de vrais tableaux au lieu de chaines JSON.
- L'audit reproductible `.codex-tmp/audit_class4_contracts.mjs` confirme apres correction : 433 actifs, 0 exercice affecte et 0 erreur de contrat.
- Le PDF MINEDUB verifie `Cameroon Primary School Curriculum - English Subsystem - Level II` (2018), SHA-256 `1921b9d044f23849c1532f403021e0a8d23a46123d4b225b8b083b827e3fdd1c`, a ete compare visuellement pour les pages English 41-49.
- Dix-huit exercices English ont ete rattaches a la bonne lecon sans modifier leur contenu : 15 exercices de grammaire vers la lecon `344`, et punctuation/construction de phrase (`397`, `398`, `1469`) vers la lecon d'ecriture `347`.
- Les 18 tentatives existantes sur ces 18 exercices sont conservees. English Class 4 reste a 28 exercices actifs; le total Class 4 reste 433.
- Les trois competences English deja sourcees sont actives. Sept liens stricts ont ete ajoutes : oral familier (`49`, `51`, `53`), comprehension (`1468`), ecriture (`397`, `398`, `1469`).
- Seeder technique idempotent : `database/seeders/Class4ExerciseContractRepairSeeder.php`. Seeder pedagogique idempotent : `database/seeders/Class4EnglishAlignmentPilotSeeder.php`.
- Les contenus `Direct speech C4`, `Question tags C4`, `Compound words C4` et `Idiom C4` restent actifs mais ne sont pas declares alignes : ils doivent etre arbitres comme programme officiel ou parcours extra avant toute desactivation.
- Audit doublons confirme : 125 groupes exacts globaux; 78 sans tentative, 5 avec tentative sur une seule copie et 42 avec tentatives reparties sur plusieurs copies. Aucun doublon n'a ete desactive automatiquement.
- Sauvegardes : `/tmp/curriculum-before-class4-contract-repair-20260818.dump` (478 865 octets, 479 entrees, SHA-256 `fa6af1bef95f30c7d76765b821caa6129049d45109c6faac3647685e5400671a`) et `/tmp/curriculum-before-class4-english-alignment-20260818.dump` (478 995 octets, 479 entrees, SHA-256 `602d84d7d06f359642ba1b3510ec413fcaebed76fd77687a1edcc5f643cf8d43`).
- Aucun redemarrage n'a ete necessaire; `/app` repond HTTP 200.
- Prochaine action sure : qualifier les quatre contenus English non explicitement couverts comme extras, puis auditer Science and Technology Class 4 contre les pages 53-58 du meme document officiel.

## 2026-08-18 - Remplacement du visage historique de felicitations

- Le dessin SVG historique affiche a la fin des QCM a ete remplace par la photo de l'accompagnateur du foyer, avec `/images/default-companion.webp` comme repli.
- Un composant partage `CompanionAvatar` fournit desormais le meme portrait sur l'ecran de felicitation, l'accueil enfant, la connexion enfant et l'ecran TV.
- Les anciens SVG inaccessibles ont aussi ete retires de la connexion enfant et de l'espace parent. Les SVG pedagogiques des exercices n'ont pas ete modifies.
- Recherche de controle : aucune occurrence de `MamaJudiSVG`, `#2A1500` ou `#C8874A` ne reste dans les sources React deployees.
- Build Vite reussi : 75 modules, bundle enfant `public/react/assets/main-D81Co1_m.js` (437,49 kB; 102,81 kB gzip). `/app` et `/mama` repondent HTTP 200.
- Aucun redemarrage : `curriculum-app` est reste `running` avec `RestartCount=0`.
- Sauvegarde avant livraison : `/tmp/edumaison-companion-congrats-before-20260818.tgz`, SHA-256 `8e6c1033972ea8b37906f653e5568b2d6ae4a1ffe71cc5ab4b478c2db90b9370`.
- Verification utilisateur restante : actualiser completement la tablette, terminer un QCM et confirmer que la photo de l'accompagnateur (ou le nouvel avatar generique) apparait sur l'ecran de felicitation.

Updated: 2026-08-10

## 2026-08-11 — Normalisation des erreurs d'authentification API

- Les routes `auth:sanctum` renvoyaient `500` aux clients omettant `Accept: application/json`, car Laravel tentait de générer une route web `login` inexistante.
- Le bootstrap force désormais les visiteurs anonymes de `/api/*` à lever `AuthenticationException`; le gestionnaire existant produit une réponse JSON `401` stable.
- Syntaxe PHP validée, caches Laravel purgés et seul le conteneur `curriculum-app` a été redémarré avec l'autorisation du propriétaire.
- Vérification publique : santé `200`, `/app` `200`, route Paramètres anonyme `401` avec et sans en-tête JSON; aucun nouvel incident critique dans les journaux.
- Sauvegarde ciblée : `/tmp/bootstrap-app.php.before-api-auth-20260811`.

## Family accounts — phase 1

- Added an additive family identity foundation: many-to-many adult membership (`household_user`) with owner/parent roles, locale/timezone metadata, and hashed child PIN support.
- Added same-origin, session-based family registration, login, logout, and current-session endpoints using Laravel Sanctum's stateful API middleware.
- Added first-child onboarding (name, birth date, level, four-digit hashed PIN) and scoped the child selector/login to the authenticated household. Legacy tablet access remains available during migration.
- Replaced the global-only access modal with a choice between creating a family, signing into an existing family, or using the legacy tablet code.
- Existing plaintext child PINs are migrated to a password hash only after the next successful legacy child login; no progression records were changed.
- PHP lint and an isolated Vite production build passed. Migration `2026_08_10_150000_create_family_memberships` ran successfully as batch 15. R2 has a recent encrypted Curriculum backup dated 2026-08-09.
- Public `/app` serves the new frontend bundle and `/sanctum/csrf-cookie` returns `204`.
- The application container was explicitly restarted to load the new routes. A second authorized reload applied API-aware unauthenticated handling, changing `/api/family-auth/me` from an erroneous `500` to the expected `401`.
- Runtime checks passed: public health `200`, legacy access status `200`, CSRF cookie `204`, empty family login validation `422`, anonymous family session `401`, and the public app references the new onboarding bundle.
- Restored the family access PIN hash to the owner-confirmed value without recording the PIN; configuration cache was cleared and unlock verification returned `200`.
- Corrected persistence across Docker Compose reloads: the bcrypt hash is now stored as a quoted literal in the host environment so its `$` segments are not interpreted or doubled. In-container verification confirms the expected 60-character hash and a successful password check.
- Added an authenticated-only “Déconnecter la famille” action on the child selector. It calls the CSRF-protected logout endpoint, clears the local child session, and returns to onboarding.
- Added legacy-mode “Verrouiller la tablette”: the new `/api/access/lock` endpoint expires the 30-day access cookie, clears the local child session in the UI, and returns to onboarding. Runtime lock verification returned `200` followed by `unlocked:false`.
- Fixed tablet-only re-unlock failures after locking: service worker v3 could cache `/sanctum/csrf-cookie` and authenticated session GETs. Version v4 always sends `/sanctum/*`, `/api/access/*`, `/api/family-auth/*`, child login, and Mama PIN verification to the network and purges prior v3 caches on activation.
- Browser-equivalent server verification passed for unlock/lock/re-unlock (`200/200/200`); the published v4 worker passes `node --check` and is served with `no-cache, no-store`.
- Next safe action: exercise the registration/onboarding journey with the owner in the browser, then add account recovery, email verification, additional-child management, and systematic household authorization across every child-addressed controller before inviting external pilot families.

## Per-family PIN and account locking

- Added nullable hashed `households.access_pin_hash` in migration batch 16; no existing household or child record was rewritten.
- New registrations require a confirmed four-digit family PIN. Existing family accounts without one receive a one-time setup screen that also requires the current parent password.
- “Verrouiller la famille” preserves the authenticated account session but blocks protected family APIs with `423`; `/api/family-auth/unlock-pin` reopens it after a rate-limited hash check.
- “Déconnecter ce compte” remains a full session logout and requires email/password next time. Legacy tablet mode keeps its separate “Verrouiller la tablette” action during migration.
- PHP lint and isolated Vite build passed. Migration ran successfully, all nine family-auth routes are active, public health is `200`, anonymous lock is `401`, invalid registration is `422`, and the new bundle is public.
- Browser-equivalent CSRF verification confirms the transitional legacy family code still unlocks with `200` after the deployment.
- Family accounts with zero children now receive an explicit empty state and can add their first child from the selector (first/last name, birth date, level, and hashed four-digit child PIN). The isolated Vite build passed and the new production bundle returns `200`.

## Admin page audit

- `resources/react/src/pages/admin/AdminApp.tsx` is a large orphaned frontend containing global curriculum CRUD, child management, raw PIN display/editing, progress reset, seeders, logs, health, assets, exams, and reporting screens.
- It is not active in production: `/admin-react`, `/api/admin/stats`, and `/api/admin/children` all return `404`, and Laravel registers no admin routes.
- The current frontend must not be exposed as-is: it has no administrator authentication boundary, no household isolation, no CSRF helper, displays child PIN values, and offers destructive reset/seeder actions.
- Next safe admin action: define separate roles and surfaces before activation — platform admin for global curriculum/publication, family parent for household children and companion profile, and school staff for School-scoped data. Mama personalization belongs to the family parent surface, not the global platform admin.

## Family-specific companion profile

- Migration batch 17 extends `mama_profile` with nullable unique `household_id`, display name, relationship, child-facing address, tone, and language. The existing null-household row remains the legacy Mama Judi profile; authenticated families receive neutral defaults until they save their own profile.
- `MamaProfileController` now resolves profiles from the authenticated session household, isolates avatar filenames, hashes newly changed Mama PINs, and falls back to the family's hashed access PIN for first Mama entry when no dedicated Mama PIN exists.
- Mama access cookies are bound to the current household so an unlocked Mama session cannot be reused after switching families. Legacy null-household cookies remain compatible.
- “Mon Profil” now edits companion identity, role, child-facing name, tone, language, photo, and Mama PIN using CSRF-protected writes; the desktop sidebar uses the configured display name.
- PHP lint, isolated Vite build, migration, route inspection, public Mama bundle, and production health checks passed.
- `MamaBridgeController::brief` now filters upstream FastAPI children to the authenticated `household_id` and recomputes summary counts; legacy mode preserves its original brief. The controller passed lint, was reloaded, and production health remains `200`.
- Remaining isolation work: authorize child IDs and family ownership for duel and evening-session endpoints, then separate family-owned scheduler configuration from global FastAPI state.
- Rollback copies of touched application files and the previous React build are in `/tmp/edumaison-family-backup-20260810`; staged validation files are under `/tmp/edumaison-family-stage` and `/tmp/edumaison-family-build-20260810`.

## Cross-environment API contract audit

- A read-only comparison found 21 Curriculum frontend API call candidates without a corresponding Laravel route on the main domain.
- Representative runtime checks confirmed `404` for Books, pending Duels, pending Evening Sessions, scheduler configuration, Admin seeders, and Revision dictionary after valid family access.
- Most missing implementations exist in the separate FastAPI service; they require explicit, authorization-aware Laravel bridges rather than a generic proxy. The Mama bridge already added is the working reference pattern.
- No additional route was exposed and no production correction was made during this audit.
- Follow-up completed: explicit Laravel bridges now expose Books, Duels, pending/completed Evening Sessions, duel exercises, and Revision dictionary through the main domain.
- Added an 8-hour encrypted HttpOnly Mama session cookie after successful Mama PIN verification, a `mama.access` middleware, and a five-attempts-per-minute PIN limit.
- Mama-only writes (book management, duel creation, evening-session creation/scheduler, avatar and PIN changes) now require both family and Mama access; child reads/game actions require family access.
- Runtime verification passed with family access: Books, pending Duel, pending Evening Session, dictionary, and book reference returned `200`; scheduler configuration returned `403` without Mama access.
- General Admin FastAPI routes and child-progress reset remain intentionally unexposed because no server-side administrator authentication exists yet.

## EduMaison family access

- Replaced browser HTTP Basic Auth on `edumaison.kamgangdavid.com` with a tablet-friendly React PIN modal.
- Added server-side Laravel PIN verification, a secure HttpOnly 30-day access cookie, and rate limiting on PIN attempts.
- Protected all existing Laravel API routes with `RequireFamilyAccess`; access status and unlock routes remain public.
- Removed an existing UTF-8 BOM from `bootstrap/providers.php` that prevented PHP from returning correct HTTP statuses and cookies.
- Removed the Basic Auth directives from Curriculum Nginx after the new access flow passed validation.
- Verification passed: PHP lint, Laravel route middleware inspection, isolated and production Vite builds, `nginx -t`, locked API `401`, PIN unlock `200`, authenticated API `200`.
- Existing local `Dockerfile` changes were preserved. Temporary rollback copies for this intervention are under `/tmp/edumaison-pin-backup` until the next routine cleanup.
- Fixed stale tablet sessions caused by service-worker caching: cache names moved to `v3`, navigations are network-first, and `/api/access/*` is never cached.
- Added automatic return to the family PIN modal when the API emits the dedicated `X-EduMaison-Access: required` response header; child-PIN failures remain distinct.
- Added an exact Nginx rule that serves `/sw.js` with `no-cache, no-store` so service-worker security fixes update promptly.
- Verification passed: service worker syntax, isolated and production Vite builds, PHP lint, `nginx -t`, public `v3` worker headers, protected Mama API `401` before PIN and `200` after PIN.
- Fixed the Mama Judi daily-summary infinite loader: Laravel now bridges the whitelisted FastAPI routes for brief, subjects, blackboard, and evening sessions while keeping `RequireFamilyAccess` in front of them.
- Mama now times out failed brief requests after 15 seconds and presents a friendly retry screen instead of swallowing errors indefinitely.
- Verification passed: controller and route PHP lint, middleware inspection on all bridge routes, isolated and production Vite builds, public Mama bundle check, brief `200` in about 2.5 seconds, and subjects `200`.

## Shared infrastructure state

- The application stack is hosted on `185.202.236.202` with Docker.
- Cloudflare R2 backups are available through the encrypted `r2crypt` remote.
- A restoration audit on 2026-08-03 downloaded and tested the latest Care, Curriculum, School, and POSCAM backups in an isolated PostgreSQL 17 container.
- Archive integrity checks passed and the restored public-table names matched production exactly.
- Care restored 210 tables, Curriculum 44, School 26, and POSCAM 27.
- Care and Curriculum dumps require PostgreSQL 17.
- POSCAM restoration requires creating the PostgreSQL role `poscam` before importing.
- The temporary audit container and downloaded files were removed. No R2 objects or production databases were modified.

## Next safe action

Inspect this stack's current containers, repository state, and latest R2 object timestamp before continuing. Keep all restoration tests isolated from production.
# 2026-08-10 — Fermeture complète de l’isolation familiale

- Le tableau parent et le détail enfant sont désormais filtrés par le `household_id` de l’utilisateur authentifié.
- Ajout du middleware `EnsureChildBelongsToFamily` sur les routes enfant sensibles : profil, exercices, tentatives, progression, classement, certificats, bulletin, remédiation, examens, promotions, avatar et parcours Mama associés à un enfant.
- `promoteAll` ne touche plus que les enfants de la famille connectée.
- Les examens parents n’utilisent plus le foyer `1` codé en dur : création, liste et résultats sont rattachés au foyer authentifié; un examen ne peut pas être passé par l’enfant d’un autre foyer.
- L’accueil d’une famille authentifiée affiche EduMaison, son nom familial et le nom de son accompagnant au lieu du branding historique ANGLOFUN/MARIO/Mama Judi.
- Les requêtes d’écriture du tableau parent et du créateur d’examen envoient désormais la protection CSRF.
- Vérification : lint PHP réussi sur les contrôleurs, middleware, routes et bootstrap; build Vite réussi (70 modules); page `/app` HTTP 200 avec le bundle `main-DXOYiIhs.js`; API parent sans session HTTP 401; middleware `EnsureChildBelongsToFamily` visible sur la route de détail; aucune erreur critique détectée depuis le redémarrage.
- Sauvegarde de retour arrière : `/tmp/edumaison-family-backup-20260810` sur le VPS.

## Écran PIN de l’accompagnateur

- L’écran `/mama` n’affiche plus le titre historique « Espace Mama Judi » pour les nouvelles familles : il compose le titre avec `companion.display_name` et réutilise l’avatar du profil.
- L’en-tête mobile de l’espace accompagnateur affiche également le nom personnalisé; le desktop le faisait déjà.
- Vérification : build Vite réussi (70 modules) et bundle public `mama-D-nXGW76.js` confirmé.
- Sauvegarde ciblée : `/tmp/MamaJudiApp.tsx.before-companion-name-20260810`.

# 2026-08-11 — Photos parent et enfants à la création

- Nouveaux avatars EduMaison par défaut pour l’accompagnateur et l’enfant, générés puis optimisés en WebP 512×512 : `public/images/default-companion.webp` et `public/images/default-child.webp`.
- Le formulaire « Créer ma famille » accepte une photo facultative du parent avec aperçu; elle initialise le profil de l’accompagnateur du foyer.
- Le formulaire du premier enfant et la fenêtre « Nouvel enfant » acceptent une photo facultative avec aperçu.
- Les contrôleurs valident les images (4 Mo maximum), les stockent sur le disque public et rattachent chaque photo au bon foyer ou enfant.
- Le formulaire familial demande désormais explicitement le PIN familial et sa confirmation, déjà obligatoires côté serveur.
- Les anciens SVG par défaut mal proportionnés sont remplacés dans l’accueil enfant et l’espace accompagnateur.
- Vérification : lint PHP réussi; build Vite réussi (70 modules); bundles publics `main-DvGg_zfY.js` et `mama-CcCIVeHL.js`; avatars HTTP 200 (19 016 et 23 910 octets); aucune erreur critique après redémarrage.
- Sauvegarde de retour arrière : `/tmp/edumaison-photos-backup-20260811`.
- Sources des illustrations générées : prompt `illustration-story`, portraits africains chaleureux, cadrage carré prévu pour affichage circulaire, sans texte ni logo; outil intégré imagegen.

## Sorties de l’écran PIN accompagnateur

- Titre simplifié en « Espace accompagnateur »; le nom familial personnalisé apparaît séparément dessous.
- Ajout de « Retour à l’accueil » vers `/app` et d’une aide « PIN oublié ? » rappelant l’usage initial du PIN familial, sans contournement ni réinitialisation non authentifiée.
- Vérification : build Vite réussi et bundle public `mama-Ce64dRk4.js` confirmé avec l’aide PIN.
- Sauvegarde ciblée : `/tmp/MamaJudiApp.tsx.before-pin-help-20260811`.

# 2026-08-11 — Paramètres parent et gestion des enfants

- Nouvel onglet parent « Paramètres » : modification du nom du parent, nom familial, ville, école et photo de l’accompagnateur.
- Changement du PIN familial protégé par le mot de passe courant et limité à cinq tentatives par minute.
- Gestion des profils enfants : prénom, nom, naissance, classe, photo et nouveau PIN facultatif.
- Désactivation d’un enfant protégée par le mot de passe parent; le profil disparaît de l’accueil mais son historique est conservé.
- Toutes les nouvelles routes de réglage exigent `auth:sanctum`; les routes enfant ajoutent aussi `EnsureChildBelongsToFamily`, empêchant le mode tablette historique et les autres foyers de les utiliser.
- « Parent view » exige maintenant le PIN familial avant d’ouvrir le tableau parent.
- Vérification : lint PHP réussi, build Vite réussi (71 modules), bundle public `main-x4-TyWd5.js`, barrière « Espace parent » confirmée et aucune erreur critique détectée après déploiement.
- Limite connue : une requête anonyme sans en-tête `Accept: application/json` obtient encore 500 au lieu de 401; le frontend corrigé envoie systématiquement cet en-tête. Une normalisation globale des erreurs API reste à faire.
- Le navigateur intégré n’était pas disponible pour un essai avec la session Choula; un essai manuel du parcours Paramètres est recommandé.
- Sauvegarde de retour arrière : `/tmp/edumaison-parent-settings-backup-20260811`.
- Récupération par e-mail non exposée : les variables mail existent, mais le transport et la délivrabilité doivent être testés avant activation.

## Réparation du PIN familial historique

- Le foyer historique Famille Kamgang n’avait aucun `access_pin_hash`, ce qui expliquait le refus du seul code familial connu.
- Après autorisation explicite du propriétaire, un PIN familial a été enregistré sous forme hashée uniquement sur ce foyer; la vérification Laravel a réussi.
- Aucun PIN ni hash n’est consigné dans ce fichier. Le profil accompagnateur, sans PIN propre, hérite maintenant correctement du PIN familial.

# 2026-08-11 — Cloisonnement SaaS du mode historique

- Audit confirmé : l’ancien cookie tablette ne contenait aucun `household_id` et certains contrôleurs conservaient une portée globale en l’absence d’utilisateur, créant un risque de croisement entre tenants.
- Le cookie historique contient désormais un tenant chiffré et expirant; les anciens cookies sans tenant sont automatiquement invalidés.
- `ACCESS_LEGACY_HOUSEHOLD_ID` cible exclusivement le foyer historique Famille Kamgang. Choula reste accessible uniquement par son propre compte.
- Nouveau `FamilyContext` : toutes les listes, connexions enfant, détails parent, promotions collectives et vérifications d’appartenance utilisent le tenant effectif de la session ou du cookie historique.
- `EnsureChildBelongsToFamily` refuse désormais toute requête enfant sans tenant au lieu de laisser passer le mode historique global.
- UX simplifiée : « Ouvrir ma famille existante », nom du foyer affiché, PIN historique utilisable pour Parent view, et paramètres de compte masqués tant que le foyer historique n’a pas son propre compte parent.
- Vérification HTTP : déverrouillage réussi, statut limité à `Famille Kamgang`, exactement trois enfants retournés (IDs 1, 2, 3), nouveau bundle public `main-CukrjL5T.js` et libellé UX confirmé.
- Aucun rattachement ni transfert de données n’a été effectué entre Choula et Kamgang.
- Sauvegardes : `/tmp/edumaison-tenant-isolation-backup-20260811` et `/tmp/edumaison-tenant-ux-backup-20260811`.

## Correction du PIN sur l’accueil

- Le serveur acceptait le PIN historique, mais `AccessGate.submitPin` référençait une variable `registering` inexistante après la réponse réussie, empêchant React d’ouvrir l’application.
- Transitions corrigées explicitement : PIN historique → accueil; création de compte → création du premier enfant; création du premier enfant → accueil.
- Vérification : bundle public `main-CWET6ub5.js`; déverrouillage HTTP `200`, `unlocked:true` et tenant Famille Kamgang.
- Sauvegarde ciblée : `/tmp/AccessGate.tsx.before-pin-transition-fix-20260811`.
## 2026-08-12 — Emploi du temps familial par enfant

- Audit : les modèles `school_years`, `terms` et `sequences` existent, mais les périodes et séquences sont vides; l'année encore marquée courante est 2025-2026. Aucune date officielle 2026-2027 n'a été inventée.
- Migration batch 18 : nouvelle table additive `child_timetable_entries`, rattachée au foyer et à l'enfant, avec jour, heures, matière et note.
- Nouveau contrôleur parent `ChildTimetableController` : lecture, ajout et suppression; session Sanctum obligatoire, appartenance foyer/enfant vérifiée et chevauchements refusés.
- Nouvel onglet parent « Emploi du temps » permettant de choisir l'enfant et saisir sa semaine scolaire.
- Lint PHP, migration et build Vite réussis (72 modules); bundle public `main-CdiiXOt_.js`; trois routes actives.
- Une requête anonyme sur l'emploi du temps d'un enfant renvoie 404 afin de ne pas révéler son existence. Aucune entrée familiale n'a été créée pendant le déploiement.
- Le navigateur intégré n'était pas connecté : prochain contrôle manuel recommandé depuis Parent view, puis liaison de l'emploi du temps aux propositions de révision du soir.
- Sauvegarde de retour arrière : `/tmp/edumaison-timetable-backup-20260812`.
- Après le premier essai parent, PHP-FPM servait encore l'ancienne table de routes et renvoyait `404`. Avec autorisation explicite, seul `curriculum-app` a été redémarré. Vérification après reload : `/app` 200, accès 200, route timetable anonyme 401 JSON (donc reconnue et protégée), bundle inchangé.
## 2026-08-12 — Correction ciblée du niveau de Mark

- Après confirmation du propriétaire, Mark Harrys (Famille Kamgang, enfant 2) a été corrigé de `Class 1` (`level_id=5`) vers `Class 4` (`level_id=8`).
- Seul `children.level_id` a changé; exercices, scores, historique et emploi du temps sont intacts.
- Sauvegarde ciblée privée : `/home/david/mark_before_class4_20260812.json`; aucun PIN ni secret consigné.
- Vérification serveur : le profil retourne désormais `Class 4`.
## 2026-08-12 — Suggestions de révision issues de l'emploi du temps

- Nouvelle route familiale `GET /api/children/{childId}/revision-suggestions` : calcule les cours vus aujourd'hui et prévus demain dans le tenant de l'enfant.
- Rapprochement prudent entre le nom libre saisi dans l'emploi du temps et les matières actives du niveau; aucune matière n'est inventée si le rapprochement échoue.
- L'espace accompagnateur affiche des cartes « Suggestions de l'école ». Un clic sélectionne l'enfant et la matière reconnue, mais la création de la session reste une décision humaine.
- Aucune donnée d'emploi du temps n'est envoyée au scheduler FastAPI global; le cloisonnement familial reste dans Laravel.
- Sauvegarde ciblée : `/tmp/edumaison-revision-suggestions-backup-20260812`.
- Lint PHP et build Vite réussis; bundle `mama-Dvv_-pcP.js`. Après redémarrage autorisé du seul conteneur `curriculum-app` : `/app` 200, `/mama` 200 et route anonyme 401 avec les middlewares famille/enfant.
- Fallback ajouté : lorsque l'enfant n'a aucun cours aujourd'hui ni demain, la suggestion cherche le premier jour renseigné dans les sept jours suivants et affiche « Prochain cours · {jour} ». Bundle public `mama-CMjJ3R2n.js`, `/app` et `/mama` 200 après reload ciblé.
## 2026-08-12 — Révision automatique isolée par famille

- Migration batch 19 : `family_revision_settings`, une configuration unique par foyer avec activation, heure, enfants sélectionnés et verrou quotidien.
- Les endpoints historiques `scheduler-config` et `trigger-auto` ne relaient plus la configuration FastAPI globale; ils utilisent `FamilyRevisionController`, `FamilyContext` et `mama.access`.
- `FamilyRevisionService` recoupe systématiquement les IDs demandés avec les enfants actifs du foyer avant de créer une session.
- Commande `family:trigger-due-revisions` planifiée chaque minute par le cron Laravel existant; elle respecte le fuseau du foyer et n'envoie qu'une fois par date locale.
- La nouvelle configuration commence désactivée pour tous les foyers. Aucun réglage ni envoi global n'a été importé ou déclenché pendant le déploiement.
- L'écran permet de choisir l'heure et les enfants; aucun choix signifie tous les enfants actifs du foyer.
- Lint PHP, migration, commande à vide, liste du scheduler et build Vite réussis. Bundle `mama-BN8JEvFY.js`; `/app` et `/mama` 200 après redémarrage ciblé; endpoints anonymes 401 avec `family.access` + `mama.access`.
- Sauvegarde ciblée : `/tmp/edumaison-family-revision-backup-20260812`.

## 2026-08-12 — Préparation vérifiable de l’année scolaire 2026-2027

- La recherche sur les domaines officiels MINEDUB/MINESEC n’a pas permis de retrouver un arrêté de calendrier 2026-2027 publié; aucune date de rentrée, de trimestre ou de séquence n’a donc été inventée.
- Migration batch 20 : les dates d’une année peuvent rester nulles tant que le calendrier officiel n’est pas publié; ajout du statut officiel, de l’autorité source, de l’URL, de la référence de l’arrêté et de sa date de publication.
- L’année 2026-2027 est préparée avec le statut `awaiting_official`, sans dates et sans devenir l’année courante. Une ligne préexistante portant ce libellé n’aurait pas été écrasée.
- Nouvelle API familiale `GET /api/academic-calendar` et nouvel onglet parent « Année scolaire » : l’interface distingue explicitement une année en attente d’une année officielle et affiche la provenance.
- Lint PHP et build Vite réussis (73 modules); bundle parent `main-B2v8kKCJ.js`; migration réussie; route active; `/app` retourne 200 et l’API sans accès familial retourne 401.
- Sauvegarde de retour arrière : `/tmp/edumaison-calendar-backup-20260812`.
- Prochaine action sûre : dès publication de l’arrêté conjoint, vérifier le PDF officiel, enregistrer sa référence et ses dates, créer les trimestres/séquences, puis seulement basculer 2026-2027 comme année courante.

## 2026-08-12 — Audit de couverture pédagogique

- Audit strictement en lecture seule : 9 niveaux, 92 matières, 268 thèmes intégrés, 411 unités, 496 leçons et 2 913 exercices.
- Les neuf niveaux de Pre-Nursery à Class 6 possèdent du contenu, mais les 92 matières totalisent zéro `school_competency`; la base ne permet donc pas encore de démontrer l’alignement aux compétences officielles.
- Les modèles actuels n’enregistrent ni document source, ni version du curriculum, ni page de référence, ni statut de vérification. Le contenu existant doit rester considéré comme « à auditer », et non présenté comme officiel.
- Les recherches sur le domaine MINEDUB confirment l’existence du « Cameroon Primary School Curriculum » et l’usage de nouveaux curricula, sans fournir dans les résultats publics les tomes anglophones complets exploitables.
- Le dépôt GitHub de sauvegarde fourni ne possède actuellement aucun `HEAD`/commit récupérable; aucun PDF n’a pu en être inventorié.
- Lot pilote recommandé : Class 4 (niveau actuel de Mark), puis Class 5 et Class 1. Pour chaque matière : source officielle, compétence, unité, séquence et exercices associés.
- Prochaine action sûre : obtenir le PDF officiel anglophone correspondant, créer le registre de provenance/version, puis mapper Class 4 sans modifier les résultats historiques.

## 2026-08-12 — Socle pédagogique multimédia et lancement du pilote Class 4

- Migration batch 21 additive : `levels.education_subsystem`, registre `curriculum_documents`, provenance et séquence sur `school_competencies`, médias structurés `exercise_media_assets` et clips vocaux privés `family_voice_clips`.
- Architecture documentée dans `docs/architecture-pedagogique.md` : séparation interface/langue d'enseignement/sous-système, chaîne document-compétence-séquence-exercice et isolation des voix familiales.
- Les 9 niveaux existants sont explicitement anglophones. Les 2 913 exercices et tous les résultats historiques sont intacts.
- Aucun exercice, média, clip vocal ou compétence n'a été créé automatiquement. Les nouvelles tables média et voix restent vides.
- Deux PDF locaux ont été copiés dans le stockage privé du projet après contrôle SHA-256, puis catalogués avec le statut `pending_verification` : Level II (Class 3-4) et Level III (Class 5-6).
- Les PDF ne sont pas exposés publiquement et ne sont pas encore marqués vérifiés/officiels dans l'application.
- Vérifications : lint PHP de huit fichiers, migration réussie, 9 niveaux dont 9 anglophones, 2 913 exercices, 2 documents catalogués, application HTTP 200.
- Sauvegarde des modèles remplacés : `/tmp/edumaison-pedagogy-backup-20260812`.
- Prochaine action sûre : extraire le sommaire et les pages Class 4 du Level II, vérifier autorité/version, puis créer les premières compétences en brouillon avec références de pages.

## 2026-08-12 — Première extraction officielle du pilote Class 4

- Le PDF Level II a été inspecté avec `pdfinfo` et `pdftotext` : 99 pages A4, couverture République du Cameroun/MINEDUB, English Subsystem, Level II Class 3 & Class 4, édition 2018.
- Le document indique remplacer le curriculum primaire de 2000 et structure le contenu autour de huit thèmes intégrés mensuels, pas de six séquences nationales fixes. EduMaison ne créera donc pas de dates/séquences fictives.
- Trois compétences terminales d'anglais Class 4 ont été créées et reliées au document et aux pages : oral (25-26, 41-43), lecture (25-26, 44), écriture (25-26, 45-49).
- Le document Level II porte maintenant le statut `verified_source`, version 2018. Les trois compétences restent `is_active=false` et aucun exercice ne leur est rattaché.
- Audit des contenus English Class 4 : 3 thèmes, 7 unités, 7 leçons et 48 exercices. Répartition : 30 reading, 6 revision, 3 vocabulary, 3 oral_drill, 2 quiz, 3 mathematics et 1 science.
- Lacunes constatées : aucun exercice classé handwriting, listening ou speaking; les quatre exercices mathematics/science présents sous English doivent être examinés avant reclassement.
- Le seeder idempotent est conservé dans `database/seeders/Class4EnglishCompetencyPilotSeeder.php`; aucune tentative ni résultat historique n'a changé.
- Prochaine action sûre : auditer le contenu des 48 exercices, proposer leur rattachement aux trois compétences, isoler les quatre classifications suspectes et créer les activités manquantes seulement après validation pédagogique.

## 2026-08-12 — Audit et nettoyage des exercices English Class 4

- Audit complet des 48 exercices : huit contenus avaient été importés trois fois. Les deux anciennes séries (16 lignes) sont désactivées, jamais supprimées; leurs 16 tentatives historiques sont conservées. La série 391-398 reste active.
- L'exercice 52 « Parts of a Plant » a été déplacé d'English vers la leçon Class 4 Science « Plants and Seeds »; sa tentative reste rattachée au même exercice.
- Les exercices 3104-3106 de diagrammes de Venn, sans tentative et rangés sous English, sont désactivés jusqu'à l'audit Mathematics.
- Corrections de contenu : règle positive/négative du question tag (1460), durée 8h-12h = 4h (1468), formulation du superlatif `highest` (395).
- Le total reste 2 913 exercices; English Class 4 possède désormais 28 exercices actifs après retrait des doublons, des trois Venn et de l'exercice Science.
- Sauvegarde avant correction : `/home/david/class4_english_before_audit_fixes_20260812.json`.
- Nouveau visuel pédagogique « plante complète » généré avec ImageGen, redimensionné en JPEG 768×768 (92 977 octets), publié sous `/storage/images/edu/plant-parts-class4-v2.jpg` et enregistré dans `exercise_media_assets` avec texte alternatif.
- Tous les `image_url` et emojis décoratifs des exercices English Class 4 actifs ont été retirés : zéro image générique restante. Les illustrations sont désormais réservées aux cas où elles apportent une information.
- Copie locale finale : `.codex-work/edumaison-family/public/images/edu/plant-parts-class4-v2.jpg`. Style pilote : illustration scientifique primaire, fond chaud propre, plante entière, sans texte, flèche, personnage ni filigrane.
- Vérifications : seeder de correction et seeder visuel réussis, média HTTP 200, application HTTP 200.
- Prochaine action sûre : auditer Mathematics Class 4, créer la compétence Sets and Logic, puis décider si les trois Venn peuvent y être réactivés; concevoir ensuite les premiers vrais exercices d'écriture et d'écoute.

## 2026-08-12 — Pilote Mathematics Class 4 : Sets and Logic

- Le curriculum officiel Level II, page 50, confirme pour Class 4 les ensembles égaux/équivalents, l'intersection, l'union, les symboles et les diagrammes.
- Création d'une structure pédagogique dédiée dans Mathematics Class 4 : thème et unité « Sets and Logic », puis leçon « Intersection and Union of Sets ».
- Création et activation de la compétence sourcée « Describe intersection and union of sets », reliée au document MINEDUB vérifié et à la page 50.
- Les exercices 3104-3106 ont quitté English et sont désormais actifs dans cette leçon Mathematics. Les contextes ambigus sur les aliments et les animaux ont été remplacés par des classements numériques dont les réponses sont objectives.
- Les trois exercices sont reliés à la compétence officielle. Ils n'avaient aucune tentative avant le déplacement; aucun résultat historique n'a donc été touché.
- Sauvegarde ciblée avant intervention : `/tmp/class4_sets_before_20260812.json` dans le conteneur applicatif.
- Seeder idempotent : `database/seeders/Class4MathematicsSetsPilotSeeder.php`.
- Vérifications : syntaxe PHP valide, seeder réussi, 3 exercices actifs sous Mathematics, 3 liens de compétence, source `verified_source`, application publique HTTP 200.
- L'audit général Mathematics Class 4 révèle encore des leçons trop larges ou mal nommées, des doublons et de nombreux emojis génériques. Prochaine action sûre : produire l'inventaire des doublons avec leurs tentatives, puis corriger la structure sans perdre les historiques.

## 2026-08-12 — Nettoyage des doublons et décorations Mathematics Class 4

- Audit de 146 exercices Mathematics Class 4 et 320 tentatives : une série de douze exercices avait été importée trois fois.
- Les 24 anciennes copies (185-196 et 268-279) sont désormais inactives; la série récente 351-362 reste active. Aucune ligne ni tentative n'a été supprimée.
- Le nombre d'exercices actifs passe de 146 à 122, sans changement du total de 320 tentatives historiques.
- Les champs décoratifs `illustration` et `image_url` ont été retirés des exercices actifs lorsqu'ils n'apportaient aucune information. Les rendus structurés (`clock_reading`, `geometry`, `number_line`, `venn_diagram`) restent intacts.
- Résultat : zéro emoji ou image générique sur les exercices Mathematics Class 4 actifs. Les champs encore présents sur les 24 copies archivées n'affectent pas l'enfant.
- Seeder idempotent : `database/seeders/Class4MathematicsDuplicateVisualCleanupSeeder.php`.
- Un instantané JSON courant des 146 exercices et 320 tentatives est disponible dans `/tmp/class4_math_before_duplicate_visual_cleanup_20260812.json`; la réactivation des IDs archivés reste mécaniquement réversible via le seeder et les IDs connus.
- Vérifications : syntaxe PHP valide, seeder réussi, 122 actifs, 320 tentatives conservées et application publique HTTP 200.
- Prochaine action sûre : séparer les unités actuellement regroupées sous le thème générique « Large Numbers » selon les cinq composantes officielles, sans modifier les exercices ni leurs résultats.

## 2026-08-12 — Structure officielle Mathematics Class 4

- Les 122 exercices actifs sont désormais rangés exclusivement sous les cinq composantes du curriculum MINEDUB Level II : Sets and Logic (10), Numbers and Operations (52), Measurement and Size (37), Geometry and Space (16), Statistics and Graphs (7).
- Les thèmes génériques historiques « Large Numbers » et « Fractions » sont inactifs; aucun exercice actif ne demeure en dehors des cinq composantes officielles.
- Les blocs mixtes ont été séparés : Money Calculations sous Numbers and Operations, Perimeter and Area sous Measurement and Size, Statistics and Graphs dans sa propre composante.
- Les exercices sur ensembles, nombres, temps, formes, mesures et statistiques ont été déplacés sans changer leur ID; les tentatives restent donc rattachées aux mêmes exercices.
- Le total historique reste 320 tentatives : 315 appartiennent aux exercices désormais situés dans les cinq composantes et 5 restent sur d'anciennes copies archivées du bloc retiré « Money & Data ».
- Sauvegarde avant restructuration : `/tmp/class4_math_before_official_structure_20260812.json`.
- Seeder idempotent : `database/seeders/Class4MathematicsOfficialStructureSeeder.php`.
- Vérifications : syntaxe PHP valide, 122 actifs, zéro actif hors composantes officielles et application publique HTTP 200.
- Point d'audit suivant : vérifier la difficulté réelle de contenus possiblement au-dessus de Class 4 (million, exposants BODMAS, TVA, pourcentages) avant toute désactivation ou reclassement.

## 2026-08-12 — Audit de difficulté Mathematics Class 4

- Comparaison directe avec les pages 50-52 du curriculum MINEDUB Level II : Class 4 couvre les nombres jusqu'à 5 000, BODMAS sans exigence d'exposants, les achats jusqu'à 5 000 F, les unités/temps, la géométrie et la représentation/interprétation de données.
- 25 exercices clairement hors périmètre ont été désactivés, jamais supprimés : nombres jusqu'au million, opérations décimales, vitesse, bénéfice/perte/remise/TVA/pourcentages, moyenne/médiane/mode/étendue et exposants BODMAS.
- Ces 25 exercices conservent leurs 40 tentatives et pourront être réévalués pour un niveau supérieur lors de l'audit correspondant; aucun reclassement automatique n'a été effectué.
- Mathematics Class 4 possède désormais 97 exercices actifs conformes : Sets and Logic 10, Numbers and Operations 34, Measurement and Size 36, Geometry and Space 16, Statistics and Graphs 1.
- Le total historique reste 320 tentatives sur les 146 exercices conservés en base. Sauvegarde ciblée vérifiée : `/tmp/class4_math_before_scope_cleanup_20260812.json` (34 478 octets).
- Seeder idempotent : `database/seeders/Class4MathematicsScopeCleanupSeeder.php`.
- Vérifications : 25 ciblés inactifs, 97 actifs, zéro actif hors composante officielle, total historique 320 et application publique HTTP 200.
- Prochaine action sûre : combler Statistics and Graphs avec des activités Class 4 sur collecte de données, classement, tallying, coordonnées et interprétation de graphiques, sans introduire moyenne/médiane/mode.

## 2026-08-12 — Pilote Statistics and Graphs Class 4

- Création de six exercices conformes et compatibles avec le moteur actuel : comptage de tallies, représentation d'un total par tallies, classement ascendant, lecture d'un pictogramme, lecture d'une coordonnée et méthode de collecte de données.
- L'exercice existant « Interpret graph » complète le lot, soit 7 exercices actifs dans Statistics and Graphs.
- Création et activation de la compétence « Represent and interpret data on graphs and grids », sourcée sur le curriculum MINEDUB Level II page 52; les 7 exercices y sont reliés.
- Mathematics Class 4 possède désormais 103 exercices actifs : Sets and Logic 10, Numbers and Operations 34, Measurement and Size 36, Geometry and Space 16, Statistics and Graphs 7.
- Aucun exercice actif n'est situé hors des cinq composantes officielles. Les nouvelles activités n'ont encore aucune tentative; le total historique reste 320.
- Sauvegarde ciblée avant création : `/tmp/class4_statistics_before_pilot_20260812.json`.
- Seeder idempotent : `database/seeders/Class4StatisticsGraphsPilotSeeder.php`.
- Vérifications : syntaxe PHP valide, compétence `verified_source`, 7 liens, 103 actifs et application publique HTTP 200.
- Prochaine action sûre : concevoir un rendu SVG/tablette pour tallies, pictogrammes, grilles et graphiques afin de remplacer progressivement les représentations textuelles par des visuels pédagogiques exacts.

## 2026-08-12 — Visuels SVG Statistics and Graphs Class 4

- Le lecteur MCQ existant prend déjà en charge un SVG par question; aucun nouveau type d'exercice ni changement du frontend n'a été nécessaire.
- Cinq exercices possèdent désormais un visuel vectoriel responsive : comptage de 8 tallies, représentation de 12 tallies, pictogramme de livres, grille au point (3,2) et graphique linéaire de 20 °C à 30 °C.
- Les SVG utilisent des formes et valeurs déterministes : ils restent nets sur téléphone/tablette et ne dépendent d'aucun emoji, fichier distant ou texte généré approximativement.
- Seeder idempotent : `database/seeders/Class4StatisticsSvgVisualSeeder.php`.
- Vérifications : syntaxe PHP valide, cinq SVG complets présents sur cinq exercices actifs et application publique HTTP 200.
- Prochaine action sûre : essai visuel dans une session enfant Class 4, puis appliquer le même standard SVG aux formes/angles, mesures et ICT.

## 2026-08-12 — Avatar et identité de l'accompagnateur dans la barre enfant

- Cause corrigée : `MamaProfileController` ne résolvait le foyer que pour les comptes authentifiés; en mode tablette historique il lisait donc le profil global « Mama Judi » et son ancienne image.
- Le contrôleur utilise maintenant `FamilyContext`, y compris pour le tenant chiffré du cookie tablette. La vérification PIN peut également reprendre le PIN familial de ce foyer lorsqu'aucun PIN d'accompagnateur distinct n'existe.
- La carte desktop enfant n'a plus « Mama Judi » codé en dur : elle affiche `display_name`, utilise la photo propre au foyer et retombe sur `/images/default-companion.webp` en absence de photo ou en cas d'erreur de chargement.
- Build Vite réussi (73 modules), bundle `main-CwtrigC3.js`; sauvegarde ciblée `/tmp/edumaison-companion-sidebar-backup-20260812`.
- Après autorisation explicite, seul le service `curriculum-app` a été redémarré. Un 502 transitoire a eu lieu pendant l'initialisation; le service est ensuite `Up`, PHP-FPM est prêt et `/app` retourne HTTP 200.
- Prochaine vérification utilisateur : actualiser complètement la tablette puis confirmer que la carte affiche le nom/l'avatar du foyer, ou le nouvel accompagnateur générique si aucune photo n'est configurée.

## 2026-08-17 — Audit de continuité en lecture seule

- Production confirmée sur `185.202.236.202` : `curriculum-app`, `curriculum-queue`, `curriculum-cron`, `edumaison-api`, Nginx, Redis et PostgreSQL sont actifs; `curriculum-postgres` est sain.
- `curriculum-app` est actif depuis le 12 août, sans redémarrage inattendu (`RestartCount=0`). Les pages publiques `/app` et `/mama` répondent HTTP `200`.
- Le code déployé est monté depuis `/opt/edumaison-curriculum`. Le dépôt de production contient encore les évolutions d'août sous forme de modifications et fichiers non suivis; ne pas nettoyer, réinitialiser ou redéployer depuis le dépôt local avant consolidation.
- Le dépôt Windows `C:\laragon\www\edumaison` est plus ancien que la production et contient de nombreuses suppressions locales non validées; elles ont été préservées.
- La tâche `/etc/cron.d/kamgang-offsite-backup` s'exécute chaque jour à 03:30. La synchronisation R2 du 17 août s'est terminée avec contrôle sans différence; le dernier dump Curriculum visible est `edumaison-20260816-014551.dump`.
- Les premières tentatives R2 retournent régulièrement `501 Not Implemented`, mais les secondes tentatives réussissent et `rclone check` confirme l'intégrité de la copie. Ce comportement reste à surveiller.
- Le VPS dispose de 113 Go libres sur 145 Go.
- Prochaine action sûre : effectuer sur la tablette un rafraîchissement complet et confirmer le nom/avatar de l'accompagnateur. Avant toute nouvelle livraison, consolider et sauvegarder l'important working tree de production dans Git.

## 2026-08-17 — Audit global SVG et première passe exercices

- Audit en lecture seule de 2 919 exercices, y compris les contenus inactifs : 10 fragments SVG stockés en base, tous XML valides, avec racine `svg`, canevas (`viewBox` ou dimensions), éléments visibles et aucune balise dangereuse.
- Les 10 SVG pédagogiques sont actifs et rattachés à Mathematics : graphique historique (1253), formes géométriques (2612-2616) et Statistics and Graphs (3141, 3142, 3144, 3145).
- Les fichiers autonomes `public/favicon.svg` et `public/icons/icon.svg` sont valides avec `viewBox`. Les balises SVG JSX des composants React sont équilibrées; le faux positif de `MCQ.tsx` provenait des chaînes de remplacement de SVG.
- Le navigateur visuel intégré n'était pas disponible; le contrôle couvre donc la structure, la sécurité et le contenu graphique, mais pas une capture réelle sur tablette.
- Audit structurel des 2 854 exercices actifs : aucun orphelin, aucun parent pédagogique inactif, aucun contenu vide, aucune incohérence certaine Math/Science/ICT vers la matière et aucune question avec contrat cassé selon les formats acceptés par le lecteur.
- 125 groupes de contenu strictement dupliqué dans une même leçon regroupent 291 lignes actives, soit 166 lignes potentiellement redondantes. Ne rien désactiver avant comparaison des titres, dates et tentatives afin de préserver l'historique.
- Seulement 10 exercices actifs ont au moins un lien vers une compétence officielle; 2 844 restent sans rattachement. Cela reflète le pilote officiel Class 4 encore limité, pas nécessairement une erreur de contenu.
- Scripts reproductibles locaux : `.codex-tmp/audit_edumaison_svg.php` et `.codex-tmp/audit_edumaison_exercises.php`.
- Prochaine action sûre : qualifier les 125 groupes de doublons avec leurs tentatives, puis poursuivre l'alignement matière/niveau/compétence par pilote documenté, sans correction automatique globale.

## 2026-08-17 — Socle multi-tenant des parcours complémentaires

- Architecture retenue : programme officiel MINEDUB séparé des packs complémentaires globaux (`remediation`, `enrichment`, `life_skill`, `advanced`), activables par foyer et attribuables par enfant.
- Migration additive batch 22 `2026_08_17_160000_create_learning_packs` : tables `learning_packs`, `learning_pack_exercise`, `household_learning_pack` et `child_learning_pack`, avec clés étrangères, unicité et `household_id` explicite sur les attributions.
- Modèles ajoutés : `LearningPack`, `HouseholdLearningPack`, `ChildLearningPack`; relations ajoutées à `Household`, `Child` et `Exercise`.
- API protégée ajoutée : catalogue familial, activation/désactivation par foyer, liste/attribution/mise en pause par enfant. Les six routes exigent `family.access` et `auth:sanctum`; les routes enfant exigent aussi `child.family`.
- Les familles ne peuvent pas créer ni modifier le catalogue global. Elles peuvent seulement activer un pack publié et l'attribuer à leurs propres enfants.
- Dump pré-migration vérifié : `/tmp/curriculum-before-learning-packs-20260817.dump`, 457 427 octets, format PostgreSQL custom, 416 entrées TOC, SHA-256 `376072ef6b43ae1923cae626f029c2045b41261ea00d96e2020c2b15c39ec9b0`.
- Sauvegarde ciblée du code remplacé : `/tmp/edumaison-learning-packs-code-before-20260817.tgz`, SHA-256 `ac7ab3cf8d9b70621f164f838e53ac9e207d0721ca5cb0dc6d0a251c78ef66a0`.
- Validation : lint PHP des neuf fichiers, migration réussie, quatre tables présentes, modèles chargés avec compteurs à zéro, six routes visibles avec les bons middlewares, route anonyme `401` JSON, `/app` et `/mama` HTTP `200`.
- Avec autorisation explicite, seul `curriculum-app` a été redémarré pour recharger la table de routes. PHP-FPM est prêt et les journaux ne montrent aucune erreur critique.
- Le catalogue reste volontairement vide : aucun exercice non aligné n'a été classé automatiquement comme extra. Prochaine action sûre : constituer un premier pack pilote publié à partir d'exercices explicitement qualifiés et sourcés, puis construire l'interface parent.
- Le seeder de dédoublonnage global a seulement été préparé et linté; il n'a pas été installé ni exécuté en production.

## 2026-08-17 — Premier parcours complémentaire et interface parent

- Premier pack global publié : `class-4-statistics-and-graphs-remediation` (`remediation`), ciblé sur Mathematics Class 4 anglophone. Il contient exclusivement les 7 exercices actifs reliés à la compétence vérifiée `Represent and interpret data on graphs and grids` (MINEDUB Level II 2018, page 52).
- Seeder idempotent ajouté et exécuté : `database/seeders/Class4StatisticsRemediationPackSeeder.php`. Le catalogue contient 1 pack et 7 associations; aucune activation de foyer et aucune affectation enfant n'ont été créées automatiquement.
- L'API fonctionne maintenant avec le contexte familial de `/mama`, y compris le foyer tablette historique : le middleware PIN compare le foyer du cookie Mama au `FamilyContext`, et `LearningPackController` accepte un utilisateur familial ou le contexte tablette protégé.
- Règles renforcées : impossible d'affecter un pack à un enfant d'un autre niveau; désactiver un pack pour un foyer met en pause ses affectations `assigned` ou `in_progress`; les IDs enfants actifs sont renvoyés au catalogue pour synchroniser l'interface.
- Nouvel écran bilingue `Parcours` dans `MamaJudiApp.tsx` : activation par foyer, affectation par cases à cocher, désactivation des enfants de niveau incompatible, états chargement/vide/erreur et navigation mobile horizontale lorsque l'espace est insuffisant.
- Nettoyage localisé du même composant : suppression de traductions dupliquées, d'une propriété de style dupliquée et remplacement de `P.cream` inexistant par `P.card`. La vérification TypeScript ciblée termine sans erreur.
- Build Vite réussi : 73 modules, bundle parent `public/react/assets/mama-CaUtyTVS.js` (53,07 kB; 13,06 kB gzip). Les pages `/app` et `/mama` répondent HTTP 200 après redémarrage ciblé du seul conteneur `curriculum-app`.
- Les 6 routes `LearningPackController` sont protégées par `family.access` et `mama.access`; les 3 routes enfant ajoutent `child.family`. Après rechargement PHP-FPM, un appel anonyme au catalogue retourne HTTP 401 `Connexion familiale requise`, confirmant l'activation de la nouvelle chaîne de middlewares.
- Sauvegardes pré-déploiement : `/tmp/curriculum-before-pilot-pack-20260817.dump` (477 742 octets, 479 entrées TOC, SHA-256 `0eadd3cb5b689a2fd28d7e61071731b858248cee00fc5c268f87c2b75871773a`), `/tmp/edumaison-pilot-pack-code-before-20260817.tgz` (SHA-256 `11088e73c0e8b7da3fff2c44f388f9c9e4047d24bc766ce3a07859679a1a3b4e`) et `/tmp/edumaison-react-assets-before-pilot-pack-20260817.tgz` (SHA-256 `7fca98532683f4e4e2e7040d1ac535cb069391a08be492168c110fa6eb3cb546`).
- Vérification restante : tester visuellement l'activation et l'affectation dans une session parent authentifiée réelle. Prochaine action sûre : exposer les parcours affectés dans l'espace enfant et permettre de lancer leurs exercices dans l'ordre du pack, sans dupliquer les tentatives existantes.

## 2026-08-18 — Parcours affectés dans l'espace enfant

- La route `GET /api/children/{childId}/learning-packs` est maintenant accessible dans le contexte enfant avec `family.access` et `child.family`, sans exiger le PIN Mama. Les routes POST/DELETE d'affectation restent protégées par `mama.access`.
- `LearningPackController::forChild` ne retourne que les packs publiés, actifs, activés pour le foyer et affectés à l'enfant avec un statut actif. Chaque pack contient ses exercices actifs dans l'ordre du pivot, le niveau, la matière et la progression de l'enfant.
- La progression compte les exercices tentés depuis la première affectation du pack; elle ne réutilise donc pas les anciennes tentatives antérieures à l'affectation. Aucun résultat ni exercice n'est dupliqué.
- `ExerciseController::attempt` synchronise désormais les affectations concernées : premier exercice terminé → `in_progress`; tous les exercices requis terminés → `completed`, avec `started_at` et `completed_at`.
- Nouveau composant partagé `resources/react/src/pages/child/LearningPacksPage.tsx` : écran `My Learning Paths`, états chargement/vide/erreur, progression, actions `Start path`, `Continue path` et `Practice again`.
- `ChildHome.tsx` et `DesktopApp.tsx` exposent le nouvel onglet `Paths/My Paths`. Le lancement réutilise `ExercisePlayer`, `saveAttempt` et la file ordonnée du pack; quitter le lecteur vide proprement cette file.
- Vérifications locales : lint PHP valide pour les deux contrôleurs et les routes; aucune erreur TypeScript dans `LearningPacksPage.tsx`, `ChildHome.tsx` ou `DesktopApp.tsx`. Le graphe TypeScript complet contient encore des erreurs anciennes dans plusieurs lecteurs d'exercices, non introduites par ce lot.
- Sauvegarde pré-déploiement : `/tmp/edumaison-child-packs-code-before-20260818.tgz`, 196 896 octets, SHA-256 `af5a95e73149a64dbd71e3256f8b1b8ce9f531dadd81a5da8a341e23dd037b93`.
- Build Vite réussi : 74 modules, bundle enfant `public/react/assets/main-BMmMsPFt.js` (441,98 kB; 104,14 kB gzip). La chaîne `My Learning Paths` est présente dans le bundle servi.
- Après autorisation explicite, seul `curriculum-app` a été redémarré. Le conteneur est `running`, PHP-FPM est prêt, `/app` et `/mama` répondent HTTP 200 et la route enfant hors contexte familial répond HTTP 401 `Connexion familiale requise`.
- Aucun foyer ni enfant n'a été activé automatiquement. Vérification restante : depuis `/mama`, activer le pack pilote pour un foyer de test, l'affecter à un enfant Class 4, puis vérifier visuellement l'enchaînement des 7 exercices et le passage `assigned → in_progress → completed`.
- Validation réelle effectuée ensuite par l'utilisateur : pack activé pour un foyer, affecté à un enfant Class 4, statut automatiquement passé à `in_progress` avec 2 exercices distincts tentés sur 7. Il reste à terminer les 5 exercices suivants pour confirmer le passage final à `completed`.
- Validation bout en bout terminée : l'interface enfant affiche `Completed` et `Practice again`; la base confirme `status=completed`, `started_at` et `completed_at` renseignés, avec 7 exercices distincts tentés sur 7. Le pilote parent → affectation → lecture ordonnée → tentatives → complétion est validé.

## 2026-08-20 — Validation parentale des productions écrites

- Cause du bouton parent inopérant corrigée dans `LanguageReviewController::reviewWriting` : le type d'exercice était lu depuis une propriété Eloquent inexistante et provoquait une réponse 404. Il est maintenant lu depuis `exercise.content.type`, comme dans le reste du module.
- L'interface parent affiche désormais une erreur explicite si la validation d'une production écrite, d'un enregistrement oral ou la suppression d'un audio échoue.
- Dans l'espace enfant, l'état non interactif `Parent review` a été renommé `Waiting for parent` afin qu'il ne soit plus interprété comme un bouton.
- Test d'intégration réalisé sur une restauration PostgreSQL isolée : validation réussie pour `handwriting` et `written_response`; contrôle anti-charabia, état de l'unité et build Vite également réussis. Aucun essai n'a modifié une tentative réelle.
- Build déployé : `public/react/assets/main-BkWI9L5W.js`. Sauvegarde avant remplacement : `/home/david/edumaison-backups/review-button-20260820T124713Z`.
- Après autorisation explicite, seul `curriculum-app` a été redémarré. Le conteneur est `running`; le contrôleur passe le lint PHP et `/app` ainsi que le nouveau bundle répondent HTTP 200.
- Point distinct non modifié : le foyer historique qui contient les enfants possède actuellement zéro compte adulte associé. Ses deux anciennes productions `handwriting` en attente ne peuvent donc apparaître dans le compte parent moderne tant que ce foyer n'est pas rattaché à un adulte autorisé.
- Prochaine action sûre : confirmer l'identité du compte adulte propriétaire, sauvegarder la base, puis rattacher explicitement le foyer historique à ce compte avant de tester visuellement le bouton sur une production réelle.

## 2026-08-20 — Prévalidation interactive du Writing

- L'interface `WrittenResponse` applique désormais en direct les mêmes règles que le serveur : minimum de mots, nombre de phrases ponctuées, présence d'un terme familial et seuil de vocabulaire anglais reconnu.
- Tant qu'un critère échoue, un message ciblé est affiché et `Submit for review` est réellement désactivé avec un état visuel gris. Le serveur demeure l'autorité finale lors de l'envoi.
- La saisie de charabia fournie dans la capture utilisateur est bloquée avant envoi; une réponse valide débloque le bouton et reste soumise à la revue parentale sans score automatique.
- Suite isolée réussie : contrôle anti-charabia, état de l'unité, validations parentales `handwriting` et `written_response`, puis build Vite de 78 modules.
- Build déployé sans redémarrage : `public/react/assets/main-CHNndW1M.js`; `/app` et le bundle répondent HTTP 200. Sauvegarde : `/home/david/edumaison-backups/writing-live-validation-20260820T133937Z`.
- Prochaine amélioration UX : généraliser la boucle retour immédiat → action bloquée → explication ciblée → confirmation aux exercices Speaking, Dictation et aux autres lecteurs encore fondés sur une validation tardive.

## 2026-08-20 — Boucle interactive Speaking et Dictation

- Speaking impose désormais l'ordre écoute → répétition → résultat → navigation. Les boutons impossibles sont réellement désactivés et expliqués; écoute, enregistrement et soumission ne peuvent plus se chevaucher.
- La mesure automatique Speaking est présentée honnêtement comme `word match`. L'algorithme client utilise maintenant la même distance d'édition par mots que le serveur; il ne prétend plus vérifier la prononciation. La prononciation réelle reste soumise à l'écoute parentale lorsqu'un audio privé est enregistré.
- La dernière étape Speaking attend la réponse serveur avant d'afficher la réussite. Une erreur conserve la transcription et permet de réessayer; le mode sans reconnaissance vocale reste explicitement `practice_only`.
- Dictation désactive `Check my sentence` avant l'écoute ou avec une réponse vide, montre l'action attendue, bloque les contrôles pendant la sauvegarde et conserve la correction affichée en cas d'erreur serveur.
- Vérifications : build Vite réussi (78 modules), suite isolée anti-charabia/revue parentale réussie et test serveur Dictation réussi (exact 100, mécanique 85 sans majuscule/ponctuation, écoute zéro refusée).
- Build déployé sans redémarrage : `public/react/assets/main-UwPD5pzs.js`; `/app` et le bundle répondent HTTP 200. Sauvegarde : `/home/david/edumaison-backups/speaking-dictation-ux-20260820T155658Z`.
- Prochaine action sûre : essai réel sur tablette avec microphone autorisé, puis audit des autres lecteurs pour repérer les boutons actifs trop tôt et les confirmations déclenchées avant sauvegarde serveur.

## 2026-08-23 — Surligneur dynamique Writing et diagnostic Speaking

- `WrittenResponse` affiche sous la zone de saisie un miroir dynamique du texte : vocabulaire reconnu de la leçon souligné en vert, mots inconnus soulignés en rouge ondulé et ponctuation laissée neutre.
- Le rouge signifie `Check`, pas nécessairement faute : un prénom valide absent de la liste peut être signalé. La validation serveur reste inchangée et demeure l'autorité contre le charabia.
- Build Vite réussi (78 modules) et suite isolée complète réussie : anti-charabia, état de l'unité, revue parentale et conservation des historiques. Build déployé : `public/react/assets/main-DVWUcDjz.js`.
- `/app` et le bundle répondent HTTP 200. Sauvegarde avant déploiement : `/home/david/edumaison-backups/writing-word-highlighter-20260823T190753Z`.
- Diagnostic Speaking : English Class 1 possède 6 `oral_drill` actifs, mais `Family Words - Listen and Repeat` (exercice 10) est classé dans l'ancien bloc `Unit 1 — Phonics & Reading`, donc absent de `Unit 3 - My Family` affiché par l'utilisateur.
- Seeder préparé mais non exécuté en production : `Class1FamilySpeakingPlacementSeeder.php`. Le test isolé le place dans `Lesson 1 - Mother and Father`, conserve l'ID 10 et ses 4 tentatives historiques, et rend 1 Speaking visible parmi 4 exercices actifs de Unit 3.
- Prochaine action sûre : après autorisation explicite de modification des données de production, créer un dump, transférer et exécuter ce seeder idempotent, puis vérifier que Unit 3 retourne 4 activités dont 1 `oral_drill`.

## 2026-08-23 — Speaking visible dans Unit 3 My Family

- Après autorisation explicite, `Class1FamilySpeakingPlacementSeeder` a été installé et exécuté deux fois en production avec succès.
- L'exercice historique 10 `Family Words - Listen and Repeat` a été déplacé de la leçon générique 374 vers `Lesson 1 - Mother and Father` (leçon 8, Unit 3). Son contenu, son ID et ses 4 tentatives de 3 enfants sont conservés.
- Dump complet avant modification : `/home/david/edumaison-backups/curriculum-before-class1-family-speaking-20260823T191350Z.dump`, format PostgreSQL custom, 480 entrées TOC, SHA-256 `82159a63dc8da0347f5e4566864e5e6ba1176b2df98aa565233e6595548078a0`.
- Vérification base : Unit 3 contient 4 exercices actifs dont 1 `oral_drill`; le seeder est idempotent.
- Vérification contrôleur : le payload enfant Unit 3 retourne les IDs 10 Speaking, 3157 Writing, 11 Quiz et 3154 Dictation. `/app` répond HTTP 200; aucun redémarrage n'a été nécessaire.
- Prochaine vérification utilisateur : actualiser complètement l'application, ouvrir English → Unit 3 - My Family et confirmer l'affichage de `Family Words - Listen and Repeat`.

## 2026-08-24 — Audio d'écoute réel pour Speaking

- Cause corrigée : `OralDrill` marquait auparavant l'item comme écouté immédiatement après l'appel à la synthèse vocale, même lorsque le navigateur ou l'appareil ne produisait aucun son.
- Quatre MP3 anglais réels ont été ajoutés pour l'exercice Class 1 `Family Words - Listen and Repeat` : `mother`, `father`, `parents` et `family`. Ils durent chacun entre 1,40 et 1,74 seconde et sont servis avec le type `audio/mpeg`.
- Speaking lit d'abord le MP3 de la leçon, utilise la synthèse vocale en secours, puis affiche `Listened` seulement après une lecture terminée avec succès. Si les deux mécanismes échouent, l'étape reste verrouillée et un message demande de vérifier le volume média.
- `MamaJudi` retourne maintenant explicitement le succès ou l'échec de la synthèse Web/Capacitor. Dictation attend également la fin de la lecture et ne consomme plus une écoute lorsqu'aucun audio n'a pu être lancé.
- Suite isolée réussie : validation des quatre MP3, restauration PostgreSQL temporaire, seeders idempotents, contrôle anti-charabia, état de Unit 3, revue parentale et build Vite de 78 modules.
- Build déployé sans redémarrage : `public/react/assets/main-COTCQit2.js`. `/app`, le bundle et les quatre MP3 répondent HTTP 200; le conteneur `curriculum-app` est resté actif.
- Sauvegarde avant remplacement : `/home/david/edumaison-backups/listening-audio-20260824T051137Z`.
- Vérification restante : actualiser complètement l'application sur la tablette, ouvrir English → Unit 3 → Speaking et confirmer que les quatre mots sont audibles avec le volume média activé.

## 2026-08-24 — Popup de révision et concurrence audio

- Cause du popup récurrent identifiée : le service interne conservait deux anciennes sessions `pending`, pour Irma depuis le 29 juillet et pour Ruth depuis le 12 août. Le client affichait toute session en attente sans limite d'âge.
- Le polling enfant ignore désormais les annonces de révision âgées de plus de 24 heures. Pour une révision récente, `Plus tard` et `Commencer` attendent une réponse HTTP réussie contenant `success=true`; en cas d'échec, le popup reste ouvert avec un message explicite au lieu de disparaître puis revenir silencieusement.
- Les annonces audio retardées sont maintenant centralisées et annulables dans `MamaJudi`. L'accueil programmé et les annonces d'exercice sont annulés dès qu'une autre lecture commence ou lorsque le composant est quitté, ce qui évite leur superposition avec Speaking, MCQ et les autres lecteurs.
- Les quatre MP3 SAPI de `Family Words - Listen and Repeat` ont été remplacés par la voix neuronale anglaise `en-GB-SoniaNeural` à débit ralenti. Les fichiers durent entre 1,13 et 1,78 seconde et restent servis en `audio/mpeg`.
- Suite isolée réussie : quatre audios valides, restauration PostgreSQL temporaire, seeders idempotents, anti-charabia, état de Unit 3, revue parentale et build Vite de 78 modules.
- Build déployé sans redémarrage : `public/react/assets/main-BZ6ZgGnI.js`. `/app`, le bundle et l'audio neuronal répondent HTTP 200; le hash du MP3 servi correspond au fichier déployé.
- Sauvegarde avant remplacement : `/home/david/edumaison-backups/revision-audio-overlap-20260824T060026Z`.
- Vérification restante : actualiser complètement la tablette, confirmer la disparition de l'ancien popup, puis écouter les quatre mots et vérifier qu'aucune annonce d'accueil ne se superpose.

## 2026-08-24 — Zone de saisie Dictation déverrouillée

- Cause corrigée : Dictation désactivait entièrement la zone de saisie jusqu'à la résolution de la promesse vocale. Certains navigateurs de tablette jouent bien la voix mais ne déclenchent pas systématiquement `SpeechSynthesisUtterance.onend`, laissant l'écran bloqué en lecture.
- Le service vocal considère maintenant la lecture disponible dès `onstart`; un garde-fou inspecte également les états `speaking` et `pending`, puis échoue explicitement après cinq secondes au lieu d'attendre indéfiniment.
- La zone de texte Dictation reste toujours saisissable hors revue/sauvegarde. La règle pédagogique demeure : `Check my sentence` reste désactivé tant qu'une écoute n'a pas été reconnue.
- Suite isolée réussie : restauration PostgreSQL temporaire, seeders idempotents, contrôles Writing/Dictation, état de Unit 3, revue parentale et build Vite de 78 modules.
- Build déployé sans redémarrage : `public/react/assets/main-CGnrmmT8.js`. `/app` et le bundle répondent HTTP 200; la source de production contient le déverrouillage et le traitement `onstart`.
- Sauvegarde avant remplacement : `/home/david/edumaison-backups/dictation-input-unlock-20260824T060844Z`.
- Vérification restante : actualiser complètement la tablette, ouvrir Dictation, confirmer que la zone accepte immédiatement la saisie et que la validation ne s'active qu'après écoute.

## 2026-08-24 — Compteur de mots Dictation aligné

- Le compteur affichait auparavant uniquement le nombre de mots saisis, sans référence à la longueur de la phrase réellement lue.
- Il affiche maintenant `mots saisis / mots attendus`. La cible est calculée à partir de `item.text` avec la même normalisation que le correcteur, sans révéler la phrase.
- Pour `Dictation: My Home and School`, chacune des trois phrases contient quatre mots; l'interface affiche donc `0 / 4 words` et passe le compteur au vert exactement à quatre mots, sinon en rouge après le début de la saisie.
- La ligne d'aide peut revenir à la ligne sur petit écran afin que le message et le compteur ne se chevauchent pas.
- Suite isolée réussie : restauration PostgreSQL temporaire, seeders idempotents, contrôles Writing/Dictation, état de Unit 3, revue parentale et build Vite de 78 modules.
- Build déployé sans redémarrage : `public/react/assets/main-aDcrLbWm.js`. `/app` et le bundle répondent HTTP 200; le conteneur est resté actif.
- Sauvegarde avant remplacement : `/home/david/edumaison-backups/dictation-word-target-20260824T063231Z`.

## 2026-08-24 — Activation du bouton Check testée dans le navigateur

- Cause corrigée : `Check my sentence` dépendait d'un compteur d'écoute incrémenté seulement après le retour asynchrone du moteur vocal. Sur tablette, la phrase pouvait être audible sans retour immédiat, laissant le bouton gris après saisie.
- L'écoute est maintenant enregistrée dès le clic `Listen` qui lance la lecture. L'activation est calculée directement par `écoute enregistrée + texte non vide + aucune revue/sauvegarde en cours`, sans dépendre d'une chaîne de message intermédiaire.
- Un harnais Vite temporaire a rendu le vrai composant `Dictation.tsx` dans le navigateur. Parcours vérifié : bouton désactivé au départ, toujours désactivé après texte seul, activé après `Listen`, compteur `4 / 4 words`, puis vue de correction visible après clic sur `Check my sentence`.
- La page de test technique a été supprimée après exécution et son URL retourne HTTP 404. Les sources reproductibles du harnais restent dans `.codex-tmp/dictation-ui-test`.
- Suite isolée complète également réussie : restauration PostgreSQL temporaire, seeders idempotents, contrôles Writing/Dictation, état de Unit 3, revue parentale et build Vite de 78 modules.
- Build déployé sans redémarrage : `public/react/assets/main-BT0-8ERX.js`. `/app` et le bundle répondent HTTP 200; le conteneur est resté actif.
- Sauvegarde avant remplacement : `/home/david/edumaison-backups/dictation-check-enable-20260824T071807Z`.

## 2026-08-24 — Pilote Dictation Class 2 préparé et testé hors production

- Le programme Level I ne présente pas la dictée comme une rubrique autonome, mais les compétences de Class 2 demandent l'écriture de phrases et de courts textes, l'orthographe par construction des mots, ainsi que les majuscules et la ponctuation de base. La progression retenue utilise donc des phrases familières de 4 à 7 mots, plus longues que le pilote Class 1.
- Quatre exercices ont été préparés sans modifier la production : English `Dictation: My Neighbourhood` dans la leçon 20 et `Dictation: Safe on the Road` dans la leçon 74; French `Dictée : Noms et phrases` dans la leçon 132 et `Dictée : Nos actions` dans la leçon 133.
- Chaque exercice contient trois phrases, utilise `en-GB` ou `fr-FR`, autorise trois écoutes et impose implicitement les contrôles du moteur Dictation sur les mots, l'orthographe, la majuscule et la ponctuation. Les difficultés sont réparties entre `easy` et `medium`.
- Douze MP3 naturels ont été générés, un par phrase, avec `en-GB-SoniaNeural` et `fr-FR-DeniseNeural` à débit ralenti. Les fichiers sont des MP3 mono 24 kHz de 12 240 à 16 992 octets, tous distincts. Le lecteur Dictation utilise désormais `audio_url` en priorité, se replie sur la synthèse vocale si nécessaire et interrompt proprement l'audio quand l'écran est quitté.
- Cinq compétences Class 2 vérifiées sont prévues : phrases/courts textes et orthographe en English; phrases/courts textes, ponctuation et graphies/homophones en French. La dictée au clavier n'est pas présentée comme une preuve d'écriture manuscrite.
- Seeder transactionnel et idempotent préparé : `database/seeders/Class2LanguageDictationPilotSeeder.php`. Il vérifie le PDF officiel, l'appartenance de chaque leçon au bon niveau et refuse de réécrire une définition différente dès qu'elle possède une tentative.
- Test d'intégration réussi sur une restauration PostgreSQL isolée : deux exécutions du seeder, quatre exercices uniques, deux dictées English, deux French, longueurs exactes de 4 à 7 mots, douze chemins audio valides et liens limités aux compétences `verified_source`. Le build Vite passe avec 78 modules et produit le bundle de test `main-BAz0hloY.js`; le conteneur temporaire a été supprimé automatiquement.
- La base et les fichiers de production n'ont pas été modifiés. Prochaine action sûre : après autorisation explicite, sauvegarder la base et les fichiers, transférer le lecteur, les types, les douze MP3 et le seeder, construire le frontend, exécuter le seeder deux fois, puis vérifier les quatre activités dans le navigateur avant d'envisager les niveaux suivants.

## 2026-08-24 — Dictation Class 2 déployée et vérifiée en production

- Après autorisation explicite, une sauvegarde complète a été créée dans `/home/david/edumaison-backups/class2-dictation-20260824T093629Z`. Le dump PostgreSQL contient 480 entrées TOC et a le SHA-256 `0176666ff9d7050fbbdfbbf9cb62b9a55c39904bc9fc8e8a60242b1c5fb20e91`; l'archive fichiers a le SHA-256 `f46f7e72a5655b33c95a9c7f366a8c2a3d7f31e7fb9535951234910541433ab6`.
- Le seeder `Class2LanguageDictationPilotSeeder` a été linté puis exécuté deux fois avec succès. Les exercices créés sont `3158` My Neighbourhood, `3159` Safe on the Road, `3160` Noms et phrases et `3161` Nos actions. Ils sont actifs dans les leçons Class 2 attendues, possèdent trois items chacun, totalisent dix liens vers cinq compétences vérifiées et n'ont encore aucune tentative.
- Les douze MP3 neuronaux English/French sont déployés sous `public/sounds/lessons/dictation/class2`. Les douze URL répondent HTTP 200 avec `audio/mpeg`; les tailles servies correspondent aux fichiers testés.
- Le lecteur privilégie maintenant l'`audio_url` enregistré et conserve la synthèse vocale en secours. Son SHA-256 déployé est `a12d44181c6a81f124e64d1bc37483dc64f49e180a70a8afd87e2d641d2bf249`; le seeder est `a7b944a8926d84bc574af6b844537aa1e7a187c28e90afca4f9f4f6513cd33f4` et les types `477733acea3ac18a09b3f3a5ee7cfd6374adc50f55b0351b9100034c7a2c4588`.
- Le test navigateur du vrai composant a confirmé : zone saisissable, bouton Check bloqué avant écoute, audio lancé, compteur `4 / 4 words`, bouton activé après saisie et quatre critères à 100 % pour une phrase exacte. Le test a révélé puis permis de corriger le faux libellé `Listening limit reached` pendant la revue; le bouton d'écoute est maintenant masqué à cette étape.
- Le harnais temporaire a été supprimé et son URL répond 404. Le build final Vite passe avec 78 modules; bundle actif `public/react/assets/main-DEW2duXQ.js`, HTTP 200, 466 998 octets. `/app` répond 200; `curriculum-app` est `running`, `RestartCount=0`; aucun redémarrage n'a été nécessaire.
- Prochaine action sûre : tester les quatre activités avec un enfant réellement inscrit en Class 2, puis étendre la progression aux autres niveaux sans réutiliser les mêmes phrases ni présenter la dictée clavier comme une preuve d'écriture manuscrite.

## 2026-08-24 — Concept visuel gamifié de l'accueil enfant

- Une première maquette haute fidélité de l'accueil enfant a été générée sans modifier l'application. Elle traduit EduMaison en aventure éducative originale : paysage camerounais, école-bibliothèque, enfant explorateur, Mama Judi comme guide, mission du jour, série de sept jours, six matières et navigation enfant.
- La composition reste fonctionnelle : une seule mission principale, accès direct aux matières, progression lisible et vocabulaire français cohérent avec l'application. Elle évite les éléments de la référence externe qui ne correspondent pas au produit, notamment boutique, arène, armes, gemmes, château, mascotte animale et copie de marque.
- Fichier de référence : `docs/design/edumaison-child-home-concept-v1.png`, 2 204 609 octets, SHA-256 `8fe315e3634de808a67293cdbf3187f99e009339242e4dcc75ca1736b6051461`.
- Aucun fichier runtime ni aucune donnée de production n'a été modifié. Prochaine action sûre : faire valider la direction artistique, puis construire un prototype React responsive de l'accueil enfant avec les vraies données et des états fonctionnels avant tout déploiement.

## 2026-08-24 - Prototype React gamifie et responsive

- Le concept valide a ete transforme en interface React fonctionnelle partagee par les accueils enfant mobile et desktop. `AdventureDashboard` utilise les vrais enfants, exercices, completions, series et parcours familiaux; les boutons Mission, Matiere et Parcours declenchent les navigations existantes.
- Un decor original camerounais optimise a ete ajoute sous `public/images/adventure/edumaison-learning-valley-v1.webp` (1672 x 941, 274 928 octets). Le tableau de bord conserve EduMaison, Mama Judi, les missions du jour et la progression scolaire sans reprendre la marque, la boutique, l'arene ou les armes de la reference externe.
- Le systeme visuel est applique aux pages Matiere, Unite, liste d'activites, lecteur d'exercice et resultat via `resources/react/src/styles/adventure.css`. Les rayons sont limites a 8 px, les panneaux restent lisibles sur le decor et les anciennes branches d'accueil sont conservees localement derriere une condition desactivee pour faciliter un retour arriere pendant la validation.
- Build Vite complet reussi dans une copie isolee de la production : 80 modules, JS `main-CJMzl5v1.js` (462,55 kB) et CSS `main-C-Zs-MjE.css` (12,35 kB). Le type `resources/react/src/types/child.ts` a ete rapatrie en lecture seule pour rendre l'overlay local autonome.
- Validation navigateur desktop : decor, mission, accompagnatrice et progression visibles; clic Mission puis clic Matiere confirmes par le harnais. Validation mobile a 390 x 844 : accueil, statistiques, mission et Mama Judi sans chevauchement; largeur utile 376 px, `scrollWidth=376`, donc aucun debordement horizontal.
- Apercu local servi sur `http://127.0.0.1:4176/`; version mobile sur `/adventure-mobile-preview.html`. Aucun fichier, aucune donnee et aucun service de production n'ont ete modifies ou redemarres.
- Prochaine action sure : faire valider visuellement l'accueil local, puis obtenir une autorisation explicite de deploiement avant sauvegarde, transfert, build de production et verification des ecrans enfant reels.

## 2026-08-24 - Lisibilite mobile du tableau de bord

- Le rail des matieres affiche maintenant exactement deux cartes lisibles par ecran mobile au lieu de comprimer cinq cartes dans la largeur. Chaque carte mesure environ 178 px dans le test 390 px, avec un espace stable de 10 px et un defilement horizontal par crans.
- La taille des libelles et compteurs a ete renforcee; la mission et Mama Judi occupent toute la largeur utile avec des dimensions stables. Le `box-sizing` est explicitement fixe sur tout le tableau de bord.
- Build complet isole reussi : bundle applicatif `main-BLr12fiN.js` (462 555 octets) et feuille `main-CAxOuM6q.css` (12 757 octets). Aucun fichier de production n'a ete modifie.
- Validation navigateur a 390 x 844 : `clientWidth=390`, `scrollWidth=390`, mission et guide bornes entre 10 et 380 px, donc aucun debordement de page. Le rail conserve son defilement interne attendu; le test desktop confirme aussi l'absence de debordement et le clic sur `Start mission`.
- Apercu local actualise : `http://127.0.0.1:4176/` et `http://127.0.0.1:4176/adventure-mobile-preview.html`.

## 2026-08-24 - Nettoyage final de l'apercu mobile

- La barre de defilement du rail des matieres est maintenant masquee tout en conservant le geste horizontal et l'alignement par crans.
- Le badge `Interactive preview`, propre au harnais de test et absent de l'application, est visuellement masque tout en restant disponible aux tests automatises.
- Le harnais choisit maintenant correctement le mode mobile sous 901 px; Mama Judi utilise donc la taille mobile reelle au lieu de recevoir le parametre desktop.
- Build complet reussi avec 80 modules : `main-jtEBVNRm.js` et `main-BXwbDhzB.css`. Verification navigateur : scrollbar `none`, statut de test limite a 1 x 1 px, aucun debordement horizontal et capture mobile propre.
- Production toujours inchangee; apercu local actualise sur le port 4176.

## 2026-08-24 - Alignement complet des pages enfant

- L'audit visuel ne s'est pas limite a l'accueil. Un harnais isole avec reponses API fictives parcourt maintenant les vrais composants Matiere, Unite, Activites, QCM et Resultat, sans creer de tentative ni lire de donnee enfant reelle.
- Deux defauts responsive ont ete corriges : les cartes Unite/Activite debordaient de 13 px a cause du modele de boite, et les changements d'ecran conservaient parfois l'ancienne position de defilement. Toutes les pages secondaires utilisent maintenant `border-box` et reviennent en haut a chaque transition.
- Les lecteurs QCM, Dictation, Writing, Handwriting et Speaking utilisent la coque commune : fond vert clair, en-tete vert fonce, bordure jaune, panneaux blancs et rayons limites a 8 px. Le resultat utilise le decor camerounais avec texte contraste.
- `My Learning Paths`, `Progress`, `Profile` et la coque de `Report Card` ont aussi ete harmonises. Le bulletin conserve son format scolaire imprimable; sa synthese mobile passe en deux colonnes pour eviter le rognage du cinquieme indicateur.
- Validation mobile reussie a 390 x 844 pour Accueil, Matieres, Unites, Activites, QCM, Resultat, Dictation, Writing, Speaking, Handwriting, My Paths, Progress, Profile et Report Card. Pour chaque page mesuree, `scrollWidth` egale `clientWidth`; le rail des matieres reste le seul defilement horizontal volontaire.
- Validation desktop reussie pour les pages Matieres/Unites a 1280 px, sans debordement. Le parcours QCM complet enregistre la reponse fictive et affiche le resultat final.
- Build Vite complet final reussi avec 80 modules : `main-uhc4E8J8.js` (464 620 octets) et `main-mZesXmlv.css` (14 550 octets). Aucun fichier, aucune donnee et aucun service de production n'ont ete modifies ou redemarres.
- Apercus locaux : accueil `/adventure-mobile-preview.html`, parcours `/adventure-flow-mobile-preview.html`, et ecrans directs via `?screen=dictation`, `writing`, `speaking`, `handwriting`, `paths`, `progress`, `profile` ou `report` sur `http://127.0.0.1:4176`.
- Prochaine action sure : apres validation utilisateur et autorisation explicite, sauvegarder les fichiers de production, transferer uniquement les sources et l'asset valides, executer le build, verifier le manifest et tester les routes enfant sans modifier les donnees.

## 2026-08-24 - Accueil recentre sur la carte d'aventure

- La reference validee a ete reinterpretee comme une carte d'aventure interactive, et non comme un tableau de bord superpose a une photographie. Un nouveau decor original montre une ecole camerounaise sur la colline, un chemin progressif, une mere-guide et deux enfants; il ne contient aucun texte, logo ni bouton incruste.
- Asset ajoute : `public/images/adventure/edumaison-adventure-campus-v2.png` (1672 x 941, 2 689 570 octets). La source generee reste conservee hors projet dans le repertoire d'images Codex.
- `AdventureDashboard` place les vraies donnees dans la scene : mission du jour, etoiles, serie, niveau, sept jours, quatre arrets de matieres cliquables, acces aux parcours familiaux et rail complet des matieres. Les callbacks existants sont conserves.
- La version desktop utilise la scene en plein ecran avec les panneaux places sur les zones libres. Sous 760 px, l'illustration reste visible en tete, puis la mission et la semaine passent dans une composition verticale; le rail conserve deux matieres par cran.
- Build Vite complet reussi dans une copie isolee : 80 modules, JS `main-CHlppmGZ.js` (465,34 kB) et CSS `main-ey8j4lgB.css` (20,87 kB). L'asset et les pages d'apercu repondent HTTP 200 sur le serveur local.
- Le navigateur integre n'etait pas disponible pour la capture et le test visuel automatises de cette iteration. Une validation manuelle reste donc requise sur `http://127.0.0.1:4176/adventure-preview.html` et `/adventure-mobile-preview.html` avant tout deploiement.
- Production inchangee. Prochaine action sure : valider la nouvelle composition dans l'apercu local, corriger au besoin les positions, puis demander une autorisation explicite avant sauvegarde et deploiement.

## 2026-08-24 - Canon graphique des personnages v1

- Une bible artistique exploitable a ete creee dans `docs/design/EDUMAISON_ART_BIBLE.md`. Elle fixe l'identite des personnages, les familles d'environnements, la separation obligatoire des couches, les formats et budgets runtime, les points de controle responsive, le flux d'approbation et la politique de personnalisation par tenant.
- Trois maitres de conception ont ete produits : `mama-judi-master-v1.png` (1024 x 1536), `child-guide-boy-master-v1.png` (1024 x 1536) et `child-guide-girl-master-v1.png` (1122 x 1402). Ils sont stockes dans `docs/design/characters` et ne sont pas charges par l'application.
- Mama Judi reprend le visage du portrait canonique existant, une robe vert foret a motifs sobres et une posture de guide avec carnet. Le garcon reprend le portrait enfant existant; la fille adopte les deux chignons et la palette corail/violet du concept valide.
- Les deux enfants-guides restent des personnages fictifs. La bible interdit de transformer la photo reelle d'un enfant en personnage genere sans consentement explicite distinct.
- Verification technique : les trois fichiers sont des PNG RGB de reference. `mama-judi-master-v1.png` contient encore un damier simule malgre une tentative de detourage par generation; il est explicitement interdit de l'utiliser comme asset runtime avant obtention et verification d'un vrai canal alpha.
- Aucun fichier runtime, aucune donnee et aucun service de production n'ont ete modifies. Prochaine action sure : faire approuver les trois identites, puis produire les poses prioritaires sur fond reellement transparent, optimiser et enregistrer les assets dans un manifeste avant integration React.

## 2026-08-24 - Premier lot runtime de Mama Judi

- Apres validation du canon, trois poses prioritaires de Mama Judi ont ete produites en conservant son visage, son age, sa coiffure, ses boucles d'oreilles, sa robe vert foret et ses proportions : `explain-v1.webp`, `encourage-v1.webp` et `celebrate-v1.webp`.
- Les generations ont utilise un fond chroma magenta uniforme. Un traitement deterministe a retire le fond et un controle visuel sur fond vert contraste a confirme l'absence du panneau magenta autour du personnage.
- Les trois fichiers runtime sont des WebP 1024 x 1536 en `yuva420p` avec vrai canal alpha. Leurs tailles sont respectivement 45 912, 44 708 et 54 140 octets, largement sous le budget de 180 Ko par pose fixe dans la bible artistique.
- Un manifeste versionne a ete ajoute dans `public/images/adventure/assets-v1.json` avec chemins, dimensions, points focaux et textes alternatifs. La bible artistique reference maintenant ce lot approuve.
- Une page de revue locale affiche les trois poses sur fond contraste : `http://127.0.0.1:4176/character-preview.html`. La page et chaque asset repondent HTTP 200.
- Les assets ne sont pas encore relies aux composants React et la production reste inchangee. Prochaine action sure : apres revue visuelle du lot, connecter `explain` aux ecrans de consigne, `encourage` aux erreurs recuperables et `celebrate` aux resultats reussis, puis verifier desktop/mobile avant de produire les poses des enfants-guides.

## 2026-08-25 - Integration React locale des poses Mama Judi

- Un composant type `MamaJudiPose` centralise les chemins, dimensions, textes alternatifs et poses `explain`, `encourage` et `celebrate`; les ecrans ne dupliquent donc pas la logique des assets.
- La coque commune des exercices affiche `explain` avec la consigne. Le resultat d'unite utilise `celebrate` a partir de 80 % et `encourage` en dessous. Le resultat QCM applique la meme regle dans le message pedagogique de Mama Judi.
- Les dimensions desktop/mobile sont stabilisees dans `adventure.css` pour eviter que le chargement de l'image ne decale le contenu. Les images decoratives ont un texte alternatif vide; la consigne conserve son alternative descriptive.
- Le manifeste JSON est valide, les trois chemins pointent vers des fichiers presents et l'apercu local `http://127.0.0.1:4176/mama-judi-integration-preview.html` repond HTTP 200.
- Apres autorisation utilisateur, seuls les composants et assets concernes ont ete transferes vers `/tmp`. Le premier script, qui aurait relu toute l'application de production pour recreer une copie, a ete refuse; le build a donc reutilise la copie temporaire existante sans lire ni modifier la production.
- Build Vite complet reussi avec 81 modules : `main-hY1Rn7Qr.js` (466,28 kB) et `main-Dtqs1S32.css` (22,31 kB). Le build du harnais reussit aussi et contient explicitement le composant, les trois chemins d'assets et les classes responsive.
- Apercu local actualise dans `adventure-preview-dist-v11`; les pages desktop/mobile, la revue des poses et la revue d'integration repondent HTTP 200, comme les trois WebP.
- Le navigateur integre n'etait pas disponible pour une nouvelle capture automatisee. La structure compilee, les dimensions fixes, le manifeste et les reponses HTTP ont ete verifies; la revue visuelle manuelle reste accessible sur `http://127.0.0.1:4176/mama-judi-integration-preview.html`.
- Production inchangee. Prochaine action sure : valider visuellement l'integration locale, puis produire les poses prioritaires des enfants-guides ou, avec une autorisation de deploiement distincte, sauvegarder et deployer ce lot.

## 2026-08-25 - Premier lot runtime des enfants-guides

- Quatre poses prioritaires des deux personnages fictifs ont ete produites depuis leurs maitres approuves : `child-guide-boy/explore-v1.webp`, `child-guide-boy/think-v1.webp`, `child-guide-girl/read-v1.webp` et `child-guide-girl/discover-v1.webp`.
- Le garcon conserve son polo jaune et vert, son short turquoise et son sac bleu; la fille conserve ses deux chignons, sa tenue corail et bleu marine et son sac violet. Les accessoires correspondent aux actions pedagogiques : carte, carnet, livre et observation de la prochaine destination.
- Les quatre fichiers possedent un vrai canal alpha `yuva420p`. Les poses du garcon mesurent 1024 x 1536 et pesent 66 100 et 59 812 octets; celles de la fille mesurent 1122 x 1402 et pesent 65 612 et 64 312 octets. Elles restent toutes sous le budget runtime de 180 Ko.
- Le manifeste `public/images/adventure/assets-v1.json` contient maintenant leurs chemins, dimensions, points focaux et textes alternatifs. La bible artistique indique ce second lot runtime approuve et rappelle que ces guides ne representent pas un enfant authentifie.
- Le detourage a ete controle sur fond vert contraste. La planche locale `http://127.0.0.1:4176/child-guide-preview.html` et les quatre fichiers d'image repondent HTTP 200.
- Aucun composant React n'a ete modifie dans ce lot et aucun nouveau build n'etait necessaire. La production reste inchangee.
- Prochaine action sure : generer un environnement camerounais sans personnage ni texte, puis composer l'accueil React par couches avec le decor, Mama Judi, les enfants-guides et les vraies zones interactives avant une validation desktop/mobile.

## 2026-08-25 - Accueil React compose par couches

- Deux nouveaux decors sans personnage, texte, logo ni controle integre ont ete generes pour le campus camerounais : `desktop-v1.webp` (1672 x 941, 217 220 octets, SHA-256 `ED4C0D7247C37A5EFB36A17E3B81B981AD2EF1476E9809240F6900A213467AB2`) et `mobile-v1.webp` (853 x 1844, 197 508 octets, SHA-256 `645E02AC9A1A6FF2DFAE60A28C05185DDA330D6F1C72985D430BC455FFAB916B`).
- Les deux images sont enregistrees sous `public/images/adventure/environments/school-campus` et dans `assets-v1.json`, avec leurs dimensions et zones sures. Elles restent sous le budget prefere de 450 Ko.
- `AdventureDashboard` ne reference plus l'ancien concept avec personnages fusionnes. Il assemble maintenant le decor, Mama Judi, les enfants-guides, les arrets de matieres et les panneaux React comme couches independantes.
- Le desktop affiche Mama Judi et la guide fille sans recouvrir la mission; le mobile utilise sa composition portrait dediee et ne conserve que le guide garcon pour limiter l'encombrement. Les personnages sont decoratifs pour les technologies d'assistance; les vrais controles et textes restent accessibles.
- Un composant `ChildGuidePose` centralise les quatre poses runtime et leurs metadonnees. Les positions et dimensions sont stabilisees dans `adventure.css` pour eviter les decalages au chargement.
- Build cible du vrai `AdventureDashboard` reussi avec 19 modules : JS `desktop-CU1zRpyD.js` (200,25 Ko) et CSS `desktop-MXGjco-B.css` (21,57 Ko). Le build complet de la copie locale reste hors verification car son miroir ne contient pas `pages/admin/AdminApp`; ce module non modifie est hors du perimetre de ce lot.
- Captures Chrome headless verifiees en 1280 x 800 et dans un iframe reel de 390 x 844. La mission, les personnages, les marqueurs, la semaine et les actions restent lisibles sans chevauchement incoherent. Les pages et les cinq assets affiches repondent HTTP 200.
- Apercus locaux : `http://127.0.0.1:4176/adventure-preview.html` et `http://127.0.0.1:4176/adventure-mobile-preview.html`. Production inchangee.
- Prochaine action sure : faire la revue visuelle utilisateur de cette composition, puis, apres autorisation explicite de deploiement, effectuer une sauvegarde, transferer uniquement les sources et assets valides, lancer le build complet dans l'environnement isole de production et verifier les routes enfant avant activation.

## 2026-08-25 - Accueil aventure deploye en production

- Apres le feu vert explicite, un audit en lecture seule a confirme que les services etaient sains et que la production ne contenait encore aucun fichier de la refonte aventure. Le lot a donc ete traite comme un ensemble coherent : accueil desktop/mobile, pages enfant alignees, lecteurs habilles, composants Mama Judi/enfants-guides et assets associes.
- Sauvegarde creee avant transfert : `/home/david/edumaison-backups/adventure-ui-20260825T040525Z/before-adventure-ui.tar.gz`. Elle contient 88 entrees pour les sources React et le bundle actif; SHA-256 `3a907fe0e7648db8c919981062b9eef082700ea3d679088f8f1836af0f4bb75a`.
- Le paquet deploye contient exactement 28 fichiers : 17 sources React et 11 images/manifeste. La comparaison SHA-256 apres copie signale `DEPLOY_HASH_MISMATCHES=0`.
- Le build complet prealable a ete execute dans `/tmp/edumaison-adventure-ui-test` avec les sources completes de production : 82 modules, JS `main-DjluX1fM.js` (467 663 octets) et CSS `main-CqvPA8I2.css` (23 250 octets). Les chemins du campus, de Mama Judi et des enfants-guides sont presents dans les bundles.
- Le bundle valide a ete active par permutation de dossier. L'ancien bundle reste disponible pour rollback immediat dans `/opt/edumaison-curriculum/public/react-before-adventure-20260825T0418Z`.
- Verification Nginx interne : `/app`, `index.html`, les deux bundles, les deux decors, les personnages testes et `assets-v1.json` repondent HTTP 200 avec les tailles attendues. Verification HTTPS externe identique sur `https://edumaison.kamgangdavid.com`.
- Le manifeste actif reference `main-DjluX1fM.js` et `main-CqvPA8I2.css`. Les journaux des dix dernieres minutes ne contiennent aucun `fatal`, `panic`, `exception` ou `error`; `curriculum-app` et `curriculum-nginx` restent `running` avec `RestartCount=0`. Aucun redemarrage et aucune modification de base de donnees n'ont ete necessaires.
- Verification restante : ouvrir une session enfant authentifiee sur desktop et mobile pour confirmer visuellement les vraies donnees, la navigation Mission/Matiere/Parcours et les lecteurs. En cas de regression, restaurer le dossier React conserve ou l'archive avant d'appliquer une correction.

## 2026-08-25 - Alignement local du parcours d'entree

- L'ecran public `AccessGate` reprend maintenant le campus camerounais et Mama Judi. L'accueil, la connexion, l'inscription et l'ouverture d'une famille existante utilisent une composition commune a rayons limites, sans changer les appels API ni les regles d'authentification.
- La selection de l'enfant et la saisie du PIN ont ete alignees sur le meme univers. Les cartes enfants, les actions parent/famille et le clavier restent de vrais controles React; Mama Judi est chargee depuis les poses runtime existantes.
- La langue reste volontairement contextuelle : acces familial en francais, parcours enfant en anglais. Aucun contenu pedagogique ni aucune donnee de famille n'a ete modifie.
- Build Vite cible reussi avec 27 modules : JS `entry-CDjo4rH6.js` (224 180 octets) et CSS `entry-BnL0OybB.css` (29 300 octets).
- Captures Chrome headless controlees pour l'accueil, la selection et le PIN en 1280 x 800, puis dans un viewport reel de 390 x 844. Les actions restent visibles, le clavier tient dans la hauteur mobile et aucun chevauchement incoherent n'a ete observe.
- Apercus locaux : `http://127.0.0.1:4176/entry-preview.html?view=welcome`, `?view=picker`, `?view=pin`, ainsi que `entry-mobile-welcome.html`, `entry-mobile-picker.html` et `entry-mobile-pin.html`.
- Production inchangee pour ce lot. Prochaine action sure : revue utilisateur des apercus, puis autorisation explicite distincte avant sauvegarde, build complet et deploiement de ces trois ecrans d'entree.

## 2026-08-25 - Environnement local Jardin scientifique

- Un deuxieme environnement camerounais a ete produit pour `Science and Technology` : village en lisiere de foret, chemin d'exploration, ruisseau, passerelle, jardins vivriers et medicinaux, abri d'observation et instruments meteo. Les images ne contiennent ni personnage, ni texte, ni logo, ni controle integre.
- Les compositions sont independantes : `science-garden/desktop-v1.webp` mesure 1672 x 941, pese 322 378 octets et a le SHA-256 `A90539700477B7C0FF51A874105425018CD23F2B42E3E351CEAF56BD766266DC`; `mobile-v1.webp` mesure 853 x 1844, pese 370 650 octets et a le SHA-256 `F0892B80A2D4A89F4E29FECB2FE1DAD65BE8E6800AE091B1EA0CC73F2EEDF638`. Les deux restent sous le budget prefere de 450 Ko.
- Le manifeste `assets-v1.json` et la bible artistique enregistrent ce lot. Les pages locales d'unites et d'activites Sciences selectionnent ce decor; les cartes, titres, progressions et boutons restent des couches React accessibles.
- Un conflit entre la regle generique de fond des pages d'unites et le decor Sciences a ete detecte par la premiere capture, puis corrige avec un selecteur specifique. Le build cible final reussit avec 60 modules.
- Captures Chrome headless controlees en 1280 x 800 et dans un viewport de 390 x 844. Les quatre cartes d'unites restent lisibles sans debordement et le paysage demeure visible autour et sous le contenu.
- Apercus locaux : `http://127.0.0.1:4176/science-preview.html` et `http://127.0.0.1:4176/science-mobile-preview.html`. Les deux pages et les deux WebP repondent HTTP 200.
- Production inchangee. Prochaine action sure : revue utilisateur du Jardin scientifique; ensuite produire l'environnement Bibliotheque pour Reading, French et English, ou demander une autorisation explicite distincte avant deploiement de ce lot.

## 2026-08-25 - Environnement local Bibliotheque

- Un troisieme environnement camerounais a ete produit pour `English`, `French` et `Reading` : bibliotheque scolaire ouverte sur une cour tropicale, rayonnages colores sans titres lisibles, alcoves de lecture, assises basses et chemin de decouverte. Les images ne contiennent ni personnage, ni texte, ni logo, ni controle integre.
- La premiere proposition mobile etait trop dominee par le beige et le bois; elle a ete ecartee. La version retenue utilise blanc, vert, bleu, turquoise, corail et jaune, avec le bois comme materiau secondaire.
- `library/desktop-v1.webp` mesure 1672 x 941, pese 192 868 octets et a le SHA-256 `A7F06B7CD6ECD68D6F50C2B2E8D64E3717ED69F0C115B15B6CBB34C438FCD5A8`; `mobile-v1.webp` mesure 853 x 1844, pese 190 722 octets et a le SHA-256 `FB0630CDEEE89873FDA9085BAF308F9D70E95EFA8E3BCD8A21F5F7C63782909D`. Les deux restent largement sous le budget prefere de 450 Ko.
- Le manifeste `assets-v1.json` et la bible artistique enregistrent ce lot. Une fonction unique associe localement les trois matieres a `adventure-environment-library`, sans modifier les donnees, exercices, progressions ou regles de langue.
- Build cible reussi avec 64 modules. Les pages, les deux WebP et les apercus repondent HTTP 200.
- Captures Chrome headless controlees en 1280 x 800 et dans un viewport de 390 x 844. Le mur libre accueille les quatre cartes sur bureau; le mobile ne presente ni rognage ni chevauchement et conserve le decor visible sous les cartes.
- Apercus locaux : `http://127.0.0.1:4176/library-preview.html` et `http://127.0.0.1:4176/library-mobile-preview.html`.
- Production inchangee. Prochaine action sure : revue utilisateur de la Bibliotheque; ensuite produire l'environnement Atelier creatif pour Arts, Handwriting et activites manuelles, ou demander une autorisation explicite distincte avant deploiement des environnements valides.

## 2026-08-26 - Environnement local Atelier creatif

- Un quatrieme environnement camerounais a ete produit pour `Arts and Crafts`, `Artistic Activities`, `Handwriting`, `Home Economics and Vocational Skills` et `Vocational Studies` : atelier scolaire ouvert sur une cour tropicale, avec poterie, tissage abstrait, papier vierge, crayons, pinceaux, fibres et materiel creatif adapte aux enfants. Les images ne contiennent ni personnage, ni texte, ni logo, ni controle integre.
- La premiere generation mobile a echoue sur une erreur reseau avant de produire un fichier. La relance integree a reussi; aucun mode CLI ni cle API n'a ete utilise.
- `creative-workshop/desktop-v1.webp` mesure 1672 x 941, pese 236 176 octets et a le SHA-256 `A60F783FF97243C0950E3A1D42C0166F61BA7BC5E8718902071A38ECFEC83BE5`; `mobile-v1.webp` mesure 853 x 1844, pese 235 344 octets et a le SHA-256 `8E24A6E1C4B4E56F154E03534DA199BEA960932544A032DF436F531C5B09767B`. Les deux restent sous le budget prefere de 450 Ko.
- Le manifeste `assets-v1.json` et la bible artistique enregistrent ce lot. Une regle d'association unique couvre les cinq noms de matieres rencontres dans les programmes, sans modifier les exercices, progressions ou donnees.
- Build cible reussi avec 68 modules. Les pages, les deux WebP et les apercus repondent HTTP 200.
- Captures Chrome headless controlees en 1280 x 800 et dans un viewport de 390 x 844. Le bureau utilise le mur calme pour les cartes; le mobile conserve les quatre unites lisibles et le decor visible sous le contenu, sans rognage ni chevauchement.
- Apercus locaux : `http://127.0.0.1:4176/creative-preview.html` et `http://127.0.0.1:4176/creative-mobile-preview.html`.
- Production inchangee. Prochaine action sure : revue utilisateur de l'Atelier creatif; ensuite produire l'environnement Place communautaire pour Citizenship, cultures et Social Studies, ou demander une autorisation explicite distincte avant deploiement des environnements valides.

## 2026-08-26 - Environnement local Place communautaire

- Un cinquieme environnement camerounais a ete produit pour `Citizenship`, `Social Studies` et `National Languages and Cultures` : place civique avec salle communautaire, pavillon de rencontre, panneaux vierges, espaces d'exposition, auvents d'apprentissage, carte abstraite, eclairage solaire, jardins et collines tropicales. Les images ne contiennent ni personnage, ni texte, ni logo, ni controle integre.
- `community-square/desktop-v1.webp` mesure 1672 x 941, pese 212 100 octets et a le SHA-256 `44276414BA90D8A257AA89F6C8812DDD6FCCC926292122C9D2DB17B19E877069`; `mobile-v1.webp` mesure 853 x 1844, pese 252 802 octets et a le SHA-256 `6467377E4B93C02812BD86C3D2BF65ADBF3639716DBBD1F7297F853C66C5F372`. Les deux restent sous le budget prefere de 450 Ko.
- Le manifeste `assets-v1.json` et la bible artistique enregistrent ce lot. Une association unique couvre les trois matieres sans modifier leurs unites, exercices, progressions ou donnees.
- Build cible reussi avec 72 modules. Les pages, les deux WebP et les apercus repondent HTTP 200.
- Captures Chrome headless controlees en 1280 x 800 et dans un viewport de 390 x 844. L'esplanade libre accueille les cartes sur bureau; le mobile conserve les quatre unites lisibles et le paysage visible sous le contenu, sans rognage ni chevauchement.
- Apercus locaux : `http://127.0.0.1:4176/community-preview.html` et `http://127.0.0.1:4176/community-mobile-preview.html`.
- Production inchangee. Prochaine action sure : revue utilisateur de la Place communautaire; ensuite produire l'environnement Terrain de sport pour Physical Education, ou demander une autorisation explicite distincte avant deploiement des environnements valides.

## 2026-08-26 - Environnement local Terrain de sport

- Un sixieme environnement camerounais a ete produit pour `Physical Education` : terrain scolaire polyvalent avec piste compacte, pelouse, stations d'agilite adaptees au primaire, poutres basses, cerceaux, cones, cordes rangees, pavillon d'hydratation, amenagement tropical et collines. Les images ne contiennent ni personnage, ni texte, ni logo, ni controle integre.
- `sports-field/desktop-v1.webp` mesure 1672 x 941, pese 262 310 octets et a le SHA-256 `9D10D3F689B1ECD99A4C2EEB02531DBB8C0780A2CA3A535C5D1E351AEDCD7C0B`; `mobile-v1.webp` mesure 853 x 1844, pese 282 762 octets et a le SHA-256 `244877F8673F2F4232BC7F83D18BACEA3C98A3C6CBEBAA70AF8E27898A3E2858`. Les deux restent sous le budget prefere de 450 Ko.
- Le manifeste `assets-v1.json` et la bible artistique enregistrent ce lot. L'association cible uniquement `Physical Education`, sans modifier ses unites, exercices, progressions ou donnees.
- Build cible reussi avec 76 modules. Les pages, les deux WebP et les apercus repondent HTTP 200.
- Captures Chrome headless controlees en 1280 x 800 et dans un viewport de 390 x 844. Le terrain et le ciel offrent des zones calmes derriere les cartes; les quatre unites restent lisibles et le paysage visible, sans rognage ni chevauchement.
- Apercus locaux : `http://127.0.0.1:4176/sports-preview.html` et `http://127.0.0.1:4176/sports-mobile-preview.html`.
- Production inchangee. Les six familles prevues par la bible artistique disposent maintenant de compositions bureau/mobile. Prochaine action sure : effectuer une revue globale des environnements et de leurs associations, puis demander une autorisation explicite avant deploiement du lot valide.

## 2026-08-26 - Revue globale locale des environnements

- Le manifeste et les fichiers ont ete audites pour les six environnements, soit douze WebP bureau/mobile. Chaque fichier existe, respecte les dimensions declarees (`1672 x 941` ou `853 x 1844`) et reste sous le budget de 450 Ko.
- La revue des associations a detecte trois matieres sans environnement explicite : `Mathematics`, `ICT` et `FSLC Preparation`. Elles utilisent maintenant le campus scolaire comme environnement de repli; les autres associations restent Science, Bibliotheque, Atelier creatif, Place communautaire et Terrain de sport.
- Le build cible final reussit avec 82 modules. Le CSS produit pese 34,91 kB et le harnais JavaScript 139,91 kB.
- Les douze apercus de matiere, bureau et mobile, ainsi que la planche globale repondent HTTP 200 (`failures=0`).
- Les captures Mathematics ont ete controlees en 1280 x 800 et dans une composition mobile de 390 x 844. Les cartes, titres, boutons et progressions restent lisibles sans chevauchement; le campus est correctement recadre sur les deux formats.
- La planche `http://127.0.0.1:4176/environment-review.html` confirme la coherence des six familles et de leurs variantes portrait. Les apercus Mathematics sont disponibles sur `http://127.0.0.1:4176/general-preview.html` et `http://127.0.0.1:4176/general-mobile-preview.html`.
- Production inchangee. Prochaine action sure : demander une autorisation explicite distincte avant sauvegarde, build complet et deploiement du lot d'environnements valide.

## 2026-08-27 - Six environnements deployes en production

- Apres autorisation explicite, le lot a ete limite a 15 fichiers : `SubjectsPage.tsx`, le CSS des environnements, `assets-v1.json` et douze WebP bureau/mobile. La comparaison avec la production a detecte que le CSS local contenait aussi le futur habillage d'`AccessGate`; ces 339 lignes non autorisees ont ete exclues du candidat deploye.
- Le paquet transfere dans `/tmp` pese 2 991 143 octets et a le SHA-256 `80791614861d7b070df78e58e5b3469e02158f97912de3358b649a08bceb7983`. Les quinze fichiers ont ete controles une premiere fois a l'extraction, puis dans la copie de build et enfin dans l'application active.
- Le build complet isole dans `/tmp/edumaison-environments-build` reussit avec 82 modules. Bundle actif : `public/react/assets/main-CxUmNCkU.js` (468 342 octets); CSS actif : `public/react/assets/main-oAQdfc06.css` (30 515 octets).
- Sauvegarde prealable : `/home/david/edumaison-backups/environments-20260826T234422Z/before-environments.tar.gz`, 18 entrees, SHA-256 `6983678933ece4e1f6d8062c9f167416c56a5610ef232f2a0db2868bca19da43`.
- L'ancien bundle reste disponible pour retour arriere immediat dans `/opt/edumaison-curriculum/public/react-before-environments-20260826T234509Z`. Aucun redemarrage de service et aucune modification de base de donnees n'ont ete necessaires.
- Verification externe : trois appels successifs a `/app`, le JS, le CSS, le manifeste et les douze illustrations repondent HTTP 200. `curriculum-app` et `curriculum-nginx` sont `running` avec `RestartCount=0`; leurs journaux des dix dernieres minutes ne contiennent aucune ligne `fatal`, `panic`, `exception` ou `error`.
- Une capture Chrome de la page publique confirme que le parcours d'entree historique reste intact, donc que les styles `AccessGate` exclus ne sont pas entres en production. Les compositions des matieres avaient deja ete controlees localement en bureau et mobile; leur derniere verification authentifiee doit etre faite depuis une session enfant reelle.
- Prochaine action sure : ouvrir une matiere de chaque famille depuis la tablette (Mathematics, Science, English/French/Reading, Arts/Handwriting, Citizenship/Social Studies et Physical Education), puis traiter les eventuels ajustements de lisibilite comme un lot distinct.

## 2026-08-27 - Alignement local des acces enfant et accompagnateur

- Les captures de production ont confirme la rupture visuelle attendue : les matieres utilisent les nouveaux environnements, mais la selection des enfants, le PIN enfant et le PIN accompagnateur conservent l'ancien fond uni. Le lot precedent avait volontairement exclu `AccessGate` faute d'autorisation pour ce perimetre.
- L'habillage local deja prepare pour `AccessGate` et `ChildLogin` a ete repris : campus camerounais bureau/mobile, Mama Judi en couche separee, liste d'enfants et claviers React accessibles, panneaux a rayon de 8 px et zones de contenu stables.
- `MamaJudiApp.tsx` possede maintenant une composition dediee au PIN accompagnateur. Elle conserve l'avatar reel du foyer, affiche le clavier dans un panneau borne et utilise un nouveau fichier scope `styles/mama-adventure.css`, sans modifier la verification du code PIN ni les appels API.
- Build Vite cible reussi avec 89 modules. Les chunks propres au PIN accompagnateur sont `mamaPin-COMVL-TG.js` (10,31 kB) et `mamaPin-CeZ5PpSB.css` (3,70 kB); le CSS partage du parcours enfant pese 34,91 kB.
- Sept apercus repondent HTTP 200 : accueil familial, selection enfant, PIN enfant, leurs variantes mobiles et PIN accompagnateur bureau/mobile.
- Captures Chrome controlees en 1280 x 800 et dans des iframes de 390 x 844. Les listes, quatre indicateurs PIN, claviers, actions de retour et aide restent visibles sans chevauchement; les illustrations demeurent inspectables autour des panneaux.
- Apercus principaux : `http://127.0.0.1:4176/entry-preview.html?view=picker`, `http://127.0.0.1:4176/entry-preview.html?view=pin`, `http://127.0.0.1:4176/mama-pin-preview.html` et `http://127.0.0.1:4176/mama-pin-mobile-preview.html`.
- Production inchangee pour ce nouveau lot. Prochaine action sure : revue utilisateur des apercus, puis autorisation explicite distincte avant sauvegarde, build complet et deploiement des acces alignes.

## 2026-08-27 - Acces enfant et accompagnateur alignes en production

- Apres autorisation explicite, le lot a ete limite a cinq fichiers : `AccessGate.tsx`, `ChildLogin.tsx`, `MamaJudiApp.tsx`, `adventure.css` et le nouveau `mama-adventure.css`. Le delta du CSS partage ajoute exactement les 339 lignes d'acces precedemment exclues; les environnements de matieres deployes restent inchanges.
- Le paquet final pese 35 685 octets et a le SHA-256 `2f6216e9b01043b35b8e9b171fa197061d06e70dbcff8103fe7ae52632bfd912`. Les cinq empreintes ont ete controlees a l'extraction, dans la copie de build et dans l'application active.
- Build complet isole reussi avec 83 modules. Bundles actifs : `public/react/assets/main-Cb7d0-Sc.js` (468 856 octets), `main-D5UF_FMM.css` (36 560 octets), `mama-SD_O_KYW.js` (54 106 octets) et `mama-BEUU7kMa.css` (3 703 octets).
- Sauvegarde avant le premier passage : `/home/david/edumaison-backups/access-alignment-20260827T022704Z/before-access-alignment.tar.gz`, 15 entrees, SHA-256 `ea57447290fa413815721c84cf4c3082bb1f56586761e9f008452f65c2b1e693`.
- La premiere capture de production a revele un titre vide sur `/mama` lorsque l'API de profil repondait sans `display_name`. Le chargement fusionne maintenant la reponse avec le profil de repli au lieu de l'ecraser. Ce correctif a ete recompile, teste avec une reponse `Unauthenticated` simulee et sauvegarde avant activation dans `/home/david/edumaison-backups/access-alignment-20260827T031144Z/before-access-alignment.tar.gz`, 16 entrees, SHA-256 `8a30e8fb63143aae3345cec3552633bd77e075c9c82ca2d90c1f0b082dcd1418`.
- Le bundle immediatement precedent reste disponible dans `/opt/edumaison-curriculum/public/react-before-access-20260827T031334Z`; la premiere sauvegarde permet de revenir integralement a l'etat anterieur au lot.
- Verification externe finale : `/app` et `/mama` repondent chacun trois fois HTTP 200; les sept assets JS/CSS actifs et les deux compositions campus repondent HTTP 200 avec les tailles attendues. `curriculum-app` et `curriculum-nginx` restent `running`, `RestartCount=0`, sans ligne recente `fatal`, `panic`, `exception` ou `error`.
- Captures Chrome de production controlees en desktop : accueil familial avec campus et Mama Judi, puis PIN accompagnateur avec le libelle `Mon accompagnateur`. Les selections et PIN enfant, ainsi que les compositions portrait de 390 x 844, ont ete verifies sur les memes bundles en apercu local. Aucun redemarrage et aucune modification de base de donnees n'ont ete necessaires.
- Verification utilisateur restante : actualiser completement la tablette, confirmer la selection des vrais profils, le PIN enfant et le nom/avatar reel du foyer sur le PIN accompagnateur.

## 2026-08-27 - Rejouer et reinitialiser la progression enfant (local)

- Le comportement signale a ete confirme dans `SubjectsPage` : une tentative verifiee marquait la carte comme terminee et son gestionnaire de clic interdisait ensuite de la rouvrir. Les exercices termines restent maintenant comptabilises mais affichent une action `Refaire` et rouvrent le lecteur; une nouvelle tentative est enregistree sans effacer l'historique.
- Le bouton `Retry unit` remet aussi a zero son etat React interne, y compris la reference utilisee par le mode `Play All`. Les productions en attente de verification parentale restent volontairement bloquees jusqu'a leur revue.
- Le bouton `Reinitialiser` de Mama Judi appelait une route inexistante `/api/admin/children/{id}/reset`. Il cible maintenant `POST /api/children/{childId}/reset-progress`, placee sous les protections `family.access`, `mama.access` et `child.family`.
- La nouvelle operation supprime uniquement les tentatives de l'annee scolaire active pour l'enfant choisi, dans une transaction verrouillee. Les enregistrements audio lies sont supprimes, les parcours complementaires sont remis a l'etat assigne, tandis que les bulletins et les annees precedentes sont conserves.
- Les lectures d'unites, d'exercices et de parcours complementaires filtrent maintenant les tentatives par annee scolaire active afin qu'une ancienne tentative ne reactive pas un etat termine apres la reinitialisation.
- Verification locale : `php -l` reussi pour `ChildController.php`, `SubjectController.php`, `ExerciseController.php`, `LearningPackController.php` et `routes/api.php`; build Vite cible reussi avec 89 modules. Deux captures Chrome en 1280 x 800 confirment la carte terminee avec `Practise again`, puis l'ouverture du lecteur sur cette meme carte.
- Apercu local : `http://127.0.0.1:4177/science-preview.html`; ajouter `?open=replay` pour le scenario d'ouverture automatisee. Aucun fichier, aucune donnee et aucun service de production n'ont ete modifies.
- Prochaine action sure : obtenir une autorisation explicite avant sauvegarde, build complet isole et deploiement des sept fichiers modifies. Apres deploiement, utiliser `Mon accompagnateur` puis `Reinitialiser` sur l'enfant voulu seulement si une remise a zero totale de l'annee est necessaire; pour une simple reprise, utiliser `Refaire`.

## 2026-08-27 - Rejouer et reinitialiser la progression enfant deploye en production

- Apres autorisation explicite, le lot a ete limite aux sept fichiers valides : `SubjectsPage.tsx`, `MamaJudiApp.tsx`, `ChildController.php`, `SubjectController.php`, `ExerciseController.php`, `LearningPackController.php` et `routes/api.php`. Le delta a ete compare ligne par ligne aux sources actives avant transfert.
- Le paquet final pese 35 901 octets et a le SHA-256 `bc4b909e15a74d0cbe3837cba67a03f882eebdb9152ad2882950ac2abc3ff774`. Les sept empreintes ont ete controlees sur la base de production, apres extraction, dans la copie de build et dans l'application active.
- Build complet isole reussi avec 83 modules. Bundles actifs : `public/react/assets/main-D_JfKuPT.js` (469 183 octets), `main-D5UF_FMM.css` (36 560 octets), `mama-CBkZkFu6.js` (54 203 octets) et `mama-BEUU7kMa.css` (3 703 octets).
- La logique destructive a ete executee avant deploiement sur un clone PostgreSQL temporaire de la production. Le test a supprime 1 180 tentatives de l'annee active uniquement dans ce clone, conserve la tentative d'une annee precedente, supprime la ligne et le fichier audio lies, laisse zero tentative courante et conserve le parcours volontairement mis en pause. Le conteneur et le dump temporaires ont ete supprimes automatiquement.
- Sauvegarde prealable : `/home/david/edumaison-backups/progress-reset-20260827T093006Z/before-progress-reset.tar.gz`, 19 entrees, SHA-256 `7e40ff76cea52ee6c251a720bd06e10121e5079d576442af944136f1c4e0bfa6`. Le bundle precedent reste disponible dans `/opt/edumaison-curriculum/public/react-before-progress-reset-20260827T095922Z`.
- Les premieres activations de controle ont declenche leur rollback automatique car PHP-FPM utilisait `opcache.validate_timestamps=Off` et le processus web conservait l'ancienne table des routes. Apres autorisation explicite distincte, l'activation finale a utilise un rechargement gracieux PHP-FPM; le conteneur n'a pas redemarre et `RestartCount` reste a zero.
- La route active `POST /api/children/{childId}/reset-progress` porte `RequireFamilyAccess`, `RequireMamaAccess` et `EnsureChildBelongsToFamily`. Un appel sans acces familial repond HTTP 401; aucune progression enfant reelle n'a ete reinitialisee pendant les tests ou le deploiement.
- Verification externe finale : `/app` et `/mama` repondent chacun trois fois HTTP 200; les sept assets JS/CSS actifs repondent HTTP 200 avec les tailles attendues. `curriculum-app` et `curriculum-nginx` restent `running`, `RestartCount=0`, sans ligne recente `fatal`, `panic`, `exception` ou `error`.
- Verification utilisateur restante : actualiser completement la tablette, ouvrir un exercice deja termine et confirmer l'action `Refaire`. Pour une remise a zero annuelle, ouvrir `Mon accompagnateur`, entrer le PIN puis choisir `Reinitialiser` sur la fiche de l'enfant concerne et confirmer l'avertissement.

## 2026-08-28 - Indicateurs parentaux corriges localement

- La capture du tableau parent a revele un indicateur trompeur : `completed attempts / attempts` etait presente comme un taux de succes. Cela produisait 100 % pour les trois enfants alors que leurs moyennes affichees etaient 49 %, 44 % et 36 %; les reprises pouvaient aussi gonfler `Activities done`.
- `ParentController` calcule maintenant six mesures distinctes pour l'annee active : tentatives, tentatives terminees, exercices actifs uniques termines et verifies, total d'exercices actifs du niveau courant, moyenne de la derniere tentative verifiee de chaque exercice, et pourcentage de progression du programme.
- Les statuts `pending_review` et `practice_only` ne valident pas une progression. Les statuts acquis restent alignes avec le lecteur enfant : `auto_checked`, `client_checked` et `parent_verified`.
- `ParentDashboard` affiche `progression` au lieu de `success`, le ratio exercices uniques termines / exercices disponibles, le nombre de tentatives separe et la moyenne. Le total familial compte maintenant les exercices uniques termines, pas toutes les reprises.
- La fiche detaillee d'un enfant lisait auparavant `detail.name`, `detail.completed` et `detail.pct` alors que l'API renvoyait un objet `child`; elle utilise maintenant correctement `child` et un objet `summary`, avec trois indicateurs : exercices termines, progression et moyenne.
- Verification PHP : syntaxe valide. Un scenario SQLite isole avec deux reprises du meme exercice, une production en attente, un autre niveau et une autre annee confirme `1/2` exercice acquis, `50 %` de progression et `80 %` de moyenne sur la derniere tentative verifiee.
- Verification frontend : build Vite cible reussi avec 23 modules. L'apercu a ete controle dans le navigateur en bureau et en 390 x 844; la liste et la fiche detaillee sont lisibles, et les journaux navigateur ne contiennent aucune erreur ni alerte.
- Apercu local : `http://127.0.0.1:4178/parent-metrics-preview.html`. Les donnees de cet apercu sont simulees uniquement pour verifier l'interface.
- Production inchangee. Prochaine action sure : apres autorisation explicite, comparer les deux sources actives de production, sauvegarder, effectuer un build complet isole, deployer `ParentController.php` et `ParentDashboard.tsx`, recharger PHP-FPM gracieusement si l'opcache l'exige, puis verifier les valeurs reelles sans modifier les tentatives.

## 2026-08-29 - Indicateurs parentaux corriges en production

- Apres autorisation explicite, le lot a ete limite a `app/Http/Controllers/Api/ParentController.php` et `resources/react/src/pages/parent/ParentDashboard.tsx`. Le diff a ete compare aux deux sources actives; les protections `FamilyContext` deja deployees sont conservees et aucune migration, tentative ou donnee enfant n'a ete modifiee.
- Le paquet transfere pese 5 553 octets et a le SHA-256 `cb3f0b48e85e1058ff780b82ae0269f684234b495ce725802d9c85f4c2e71987`. Les empreintes finales des deux sources sont `ffd7b866ffe07bfc434439db1b864c85ded77c86af1b7e8760d8012cc73e2aaf` et `f3415b49d82b34ac57644483f88bf98b005f0a419f210938585fcd8c8c501ad4`.
- Le premier essai du test isole a correctement signale que le processus hote ne resolvait pas le nom Docker PostgreSQL; aucune activation n'a ete tentee. Le test a ete rendu bloquant et relance dans un conteneur temporaire sur le meme reseau que l'application. Il valide en lecture seule les invariants de progression, moyenne et totaux sur les trois enfants actifs de l'annee `2025-2026`.
- Build complet isole reussi avec 83 modules. Bundle principal actif : `public/react/assets/main-B2qn1UTj.js` (469 704 octets); CSS actif inchange : `main-D5UF_FMM.css` (36 560 octets). Les chunks Mama Judi restent `mama-CBkZkFu6.js` et `mama-BEUU7kMa.css`.
- Sauvegarde prealable : `/home/david/edumaison-backups/parent-metrics-20260829T150629Z/before-parent-metrics.tar.gz`, 14 entrees, SHA-256 `6d107005347ff89ffec7530ab465d7304948c5d157a8ec01e34c15ff6f2a6273`.
- Le bundle precedent reste disponible pour retour arriere immediat dans `/opt/edumaison-curriculum/public/react-before-parent-metrics-20260829T150804Z`.
- PHP-FPM a ete recharge gracieusement pour prendre en compte le controleur avec OPcache; le conteneur n'a pas redemarre. Verification finale : `/app` et `/mama` repondent chacun trois fois HTTP 200, les sept assets actifs repondent HTTP 200, l'acces non authentifie au tableau parent reste HTTP 401, `curriculum-app` et `curriculum-nginx` sont `running` avec `RestartCount=0`, et leurs journaux recents ne contiennent aucune ligne `fatal`, `panic`, `exception` ou `error`.
- Prochaine verification utilisateur : actualiser completement le tableau parent et confirmer que chaque fiche affiche desormais la progression du programme, le ratio d'exercices uniques, les tentatives et la moyenne au lieu du faux `100 % success`.

## 2026-08-29 - Fonds tablette sans repetition (local)

- Le defaut signale sur tablette a ete reproduit dans un viewport isole de 820 x 1180 : les pages de matiere chargeaient bien l'image d'environnement, mais une regle generale `background: ... !important` reinitialisait `background-size`, `background-position` et `background-repeat`. Le navigateur calculait donc `auto` et `repeat` malgre les declarations situees plus haut.
- `resources/react/src/styles/adventure.css` protege maintenant explicitement `cover`, le centrage, `no-repeat` et l'attachement local pour les six environnements. Les variantes portrait conservent aussi leur cadrage `center top` avec la priorite requise.
- Les autres grands fonds incomplets ont ete uniformises : accueil enfant, scene de carte et page de resultat declarent tous explicitement `background-repeat: no-repeat`.
- Les deux builds de previsualisation reussissent : 89 modules pour les acces/environnements et 19 modules pour l'accueil aventure.
- Verification navigateur sur les six familles (`campus`, `science`, `library`, `creative`, `community`, `sports`) en 820 x 1180 puis 768 x 1024 : chaque fond calcule `no-repeat, no-repeat`, `cover, cover` et un centrage stable. La scene de l'accueil enfant calcule egalement `no-repeat` et `cover`; les captures ne montrent plus de mosaique du paysage.
- Production inchangee. Prochaine action sure : apres autorisation explicite, sauvegarder le CSS actif, effectuer un build complet isole, deployer uniquement `adventure.css` et les nouveaux bundles, puis verifier `/app` sur une tablette sans modifier les donnees.

## 2026-08-29 - Fonds tablette sans repetition deployes en production

- Apres autorisation explicite, le CSS local a ete compare au fichier actif de production. Le delta est limite aux priorites `cover`, centrage et `no-repeat` des six environnements, plus les declarations `no-repeat` manquantes de l'accueil, de la carte et du resultat.
- Le paquet contient uniquement `resources/react/src/styles/adventure.css`, pese 7 654 octets et a le SHA-256 `70e51707bc0f66ce8845720cd7e4f75d204f6e3ff074c8cf2c0a97951b6a8135`. L'empreinte finale du CSS source est `3173764e707c3b2b3b13d7206d728f6d5a5e64d09ae2bc0b18847217b6ff55a2`.
- Le premier transfert SSH a ete interrompu avant l'arrivee du paquet. Le script s'est arrete sur son controle d'empreinte avant sauvegarde, build ou activation; le transfert a ensuite ete relance en une seule connexion.
- Build complet isole reussi avec 83 modules. Bundle principal actif : `public/react/assets/main-BQ7B3x1s.js` (469 704 octets); CSS actif : `public/react/assets/main-C7JYOz8m.css` (37 014 octets). Les huit ressources du dossier `assets` sont presentes.
- Sauvegarde prealable : `/home/david/edumaison-backups/tablet-bg-20260829T215451Z/before-tablet-bg.tar.gz`, SHA-256 `f0f0f36dc56efa5ca5f28bbda65338081223762c40fe0c49a1901189e506766d`. Le bundle precedent reste disponible dans `/opt/edumaison-curriculum/public/react-before-tablet-bg-20260829T215451Z`.
- Verification externe : `/app` et `/mama` repondent chacun trois fois HTTP 200; les deux pages React, les huit assets actifs et les douze images d'environnement bureau/mobile repondent HTTP 200. Le CSS compile contient les declarations prioritaires `no-repeat` et `cover` attendues.
- `curriculum-app` et `curriculum-nginx` restent `running` avec `RestartCount=0`. Une requete de controle initiale mal formee vers le repertoire vide `/images/adventure/environments//` a produit le refus nginx attendu; aucune nouvelle erreur applicative ou nginx n'apparait ensuite. Aucun service n'a ete redemarre et aucune donnee n'a ete modifiee.
- Verification utilisateur restante : actualiser completement l'application sur la tablette, ouvrir deux ou trois matieres et confirmer que chaque paysage reste unique pendant le defilement et apres rotation de l'ecran.

## 2026-08-30 - Ecrans secondaires enfant alignes (local)

- La verification utilisateur confirme que les fonds deployes sont corrects sur telephone. L'audit suivant a couvert `Progress`, `Profile`, `My Learning Paths` et `Report Card` avec des donnees simulees, sans appel ni modification de production.
- Les trois ecrans de suivi utilisent maintenant le campus camerounais comme environnement partage, avec voile de lisibilite, composition bureau/mobile, `cover` et `no-repeat`. L'entete de `My Learning Paths` devient un repere lisible sur le paysage au lieu de flotter sur un fond uni.
- `Progress` nomme desormais le ratio historique `Completion rate` au lieu de `Success rate`, car l'API renvoie des tentatives terminees sur les tentatives totales et non une note de reussite.
- Le bulletin possede des classes responsive dediees. Sur telephone, la colonne trop etroite des remarques est retiree du tableau et chaque remarque reste visible sous la matiere correspondante; les informations enfant passent sur deux colonnes, l'icone decorative est masquee et la barre d'action reste lisible. Les versions tablette, bureau et impression conservent les cinq colonnes originales.
- Build de previsualisation reussi avec 99 modules. Verification navigateur sur les quatre pages en 390 x 844, 768 x 1024 et 1180 x 800 : aucun debordement horizontal. Les fonds des trois pages calculees utilisent `cover, cover` et `no-repeat, no-repeat`; le bulletin mobile affiche dix remarques integrees, masque seulement leur colonne dupliquee et conserve cette colonne sur tablette/bureau.
- Les captures finales confirment le contraste blanc du titre `Report Card` sur la barre verte, la lisibilite du tableau mobile et l'absence de chevauchement. Production inchangee pour ce nouveau lot.
- Prochaine action sure : apres autorisation explicite, comparer `adventure.css`, `ProgressPage.tsx` et `BulletinPage.tsx` aux sources actives, sauvegarder, effectuer un build complet isole puis deployer uniquement ces trois fichiers et les nouveaux bundles.

## 2026-08-30 - Ecrans secondaires enfant alignes en production

- Apres autorisation explicite, le lot a ete limite a `resources/react/src/styles/adventure.css`, `resources/react/src/pages/child/ProgressPage.tsx` et `resources/react/src/pages/child/BulletinPage.tsx`. Les trois sources actives ont ete rapatriees et comparees ligne par ligne avant transfert; aucun ecart inattendu n'a ete trouve.
- Le paquet final a le SHA-256 `f4e9537d712e228eb4c16569872b27f1ff96fc51266b3e024960f7c90e9aa8d1`. Les empreintes finales des trois sources sont respectivement `3b90ff0802c69315a1049e6b244279cf4a507be8e7ce821416fbc9a8cc16fbed`, `0b0b059bc45ca74ae1eec7a005fe21fd2b1aa56f391ae1417d100f00d86282bb` et `7ca9a13e8e365dba7e1999f22790270b4629bb6ee1c7e9ff363d9d39d235ddfd`.
- Les deux premieres executions se sont arretees avant sauvegarde ou activation : la premiere sur le nom du paquet temporaire, la seconde sur un controle de manifeste non produit par cette configuration Vite. Le controle a ete remplace par la verification bloquante de `index.html` et `mama.html`.
- Build complet isole reussi avec 83 modules. Bundles actifs : `public/react/assets/main-Duo5FYIM.js` (470 080 octets), `main-LlLwei4d.css` (39 081 octets), `mama-CBkZkFu6.js` (54 203 octets) et `mama-BEUU7kMa.css` (3 703 octets). Le CSS compile contient le fond campus responsive et les regles du bulletin mobile; le JavaScript actif contient le libelle `Completion rate`.
- Sauvegarde prealable : `/home/david/edumaison-backups/secondary-pages-20260829T233015Z/before-secondary-pages.tar.gz`, SHA-256 `ea15ab4443a84fb6e0e5b644b2fb08a60b9cecc87a1b0c65f618908183911bca`. Le bundle precedent reste disponible dans `/opt/edumaison-curriculum/public/react-before-secondary-pages-20260829T233015Z`.
- Verification externe finale : `/app` et `/mama` repondent chacun trois fois HTTP 200; les sept assets references par ces pages et les images campus bureau/mobile repondent HTTP 200. `curriculum-app` et `curriculum-nginx` restent `running`, `RestartCount=0`, sans ligne recente `error`, `fatal` ou `exception`. Aucun service, aucune base de donnees et aucune progression enfant n'ont ete modifies.
- Verification utilisateur restante : effectuer une actualisation complete, puis ouvrir `Progress`, `My Learning Paths` et `Report Card` sur telephone. Le bulletin doit afficher chaque remarque sous sa matiere sans debordement horizontal; tablette et bureau doivent conserver la colonne des remarques.

## 2026-08-30 - Base locale consolidee et audit global des exercices

- La copie de travail etait partielle : 21 fichiers React et 146 autres sources applicatives actives manquaient localement. Tous les fichiers absents ont ete recopies depuis la production sans ecraser les sources presentes; les deux caches Laravel generes ont ete volontairement exclus. Les 67 fichiers React locaux correspondent maintenant exactement aux 67 sources actives avant le nouveau correctif.
- La comparaison generale couvre 296 sources actives. Apres consolidation, les seuls ecarts intentionnels sont le correctif d'authentification API deja local dans `bootstrap/app.php`, trois travaux locaux additionnels, et le nouveau lot de verification mathematique. Le build complet de la base consolidee reussit avec 83 modules.
- `audit_exercise_contracts.php` effectue un audit en lecture seule des contrats pedagogiques. Sur 2 805 exercices actifs : 2 619 sont verifies cote serveur, 72 passent par une revue parentale et 114 faisaient encore confiance au resultat calcule par le navigateur.
- Les autres constats classes pour les lots suivants sont : 69 QCM avec contrat invalide, 10 exercices sans type affichable (dont un `oral_response`), 25 sans type explicite, 6 dictees sans fichier audio enregistre, 2 productions ecrites Class 3 sans filtre de qualite, 189 doublons exacts et 2 571 exercices sans competence rattachee. Aucun de ces enregistrements n'a ete modifie pendant l'audit.
- Le premier lot local securise les 88 exercices auto-corrigeables des moteurs `clock_reading` (17), `geometry` (45), `number_line` (14) et `venn_diagram` (12). Les composants transmettent desormais la reponse brute; `ExerciseController` ignore la note proposee par le telephone et recalcule le resultat avec la cle de reponse serveur.
- Les activites de geometrie sans choix ne sont plus validees automatiquement : elles restent en `practice_only`. Les reponses invalides ou incompletes des quatre moteurs sont refusees au lieu d'etre acceptees avec une note client.
- Verification locale : syntaxe PHP valide, build Vite complet reussi avec 83 modules et nouveau bundle candidat `main-DLT5gf-q.js` (470 170 octets). Huit cas isoles passent contre le controleur candidat : pour chacun des quatre moteurs, une mauvaise reponse accompagnee de `100` est ramenee a `0`, et une bonne reponse accompagnee de `0` est remontee a `100`.
- Production inchangee pour ce lot. Prochaine action sure : apres autorisation explicite de deploiement, comparer les cinq sources candidates aux fichiers actifs, sauvegarder, reconstruire en isolation, deployer le controleur et les quatre moteurs, recharger PHP-FPM gracieusement a cause d'OPcache, puis verifier les routes et bundles sans modifier les tentatives existantes.

## 2026-08-30 - Verification serveur des moteurs mathematiques en production

- Apres autorisation explicite, le lot a ete limite a `ExerciseController.php`, `ClockReading.tsx`, `Geometry.tsx`, `NumberLine.tsx` et `VennDiagram.tsx`. Les cinq fichiers actifs ont ete archives, rapatries et compares aux candidats; aucun changement concurrent ni ecart inattendu n'a ete detecte.
- Le paquet final pese 14 487 octets et a le SHA-256 `8ce3db3af75151f8f79ba26426b2db5754d4a4d30d6ee3568c7603a37fb3752f`. Les empreintes finales des cinq sources sont `e1e3933dca2617076d72763759b5513dc6b4ee9367c475a360e75c416d7ad6fa`, `20910153a446b06b581f095d722bf5c384120759faf8505ca01c0ee8a5bac2f1`, `bb5c4fe93091335761874c3569b1a2173a2da82d6791ef8b170720cdfb3ced08`, `26114e21574989e59a0e01cd7c26c93480616487c9b5c3aaa181392b0858dd29` et `5c7abfb3337b19d1341bc2a2d7a232c44303164d5b68f1f6ee6ae8605718d0fa`.
- Avant activation, huit cas isoles ont ete executes contre le controleur candidat : pour l'horloge, la geometrie, la droite numerique et le diagramme de Venn, une mauvaise reponse accompagnee de `100` est ramenee a `0`, tandis qu'une bonne reponse accompagnee de `0` est remontee a `100`.
- Build complet isole reussi avec 83 modules. Bundle principal actif : `public/react/assets/main-DLT5gf-q.js` (470 171 octets); CSS actif inchange : `main-LlLwei4d.css` (39 081 octets). Les chunks Mama Judi restent `mama-CBkZkFu6.js` et `mama-BEUU7kMa.css`.
- Sauvegarde prealable : `/home/david/edumaison-backups/math-verification-20260830T080516Z/before-math-verification.tar.gz`, SHA-256 `21e87fd665fba7783c914419b7b48ce280b8c89614c44714dfde8f0d61b5f54f`. Le bundle precedent reste disponible dans `/opt/edumaison-curriculum/public/react-before-math-verification-20260830T080516Z`.
- PHP-FPM a ete recharge gracieusement pour prendre en compte le controleur avec OPcache; le conteneur n'a pas redemarre. Les huit calculs ont ensuite ete rejoues avec succes sur le controleur actif.
- Verification externe finale : `/app` et `/mama` repondent chacun trois fois HTTP 200; les sept assets references repondent HTTP 200 et contiennent les nouveaux contrats de reponse. `POST /api/exercises/attempt` sans session reste HTTP 401. `curriculum-app` et `curriculum-nginx` sont `running`, `RestartCount=0`, sans erreur recente.
- Aucune tentative, note, progression ou autre donnee existante n'a ete modifiee. Prochaine verification utilisateur : actualiser completement l'application puis essayer une horloge, une geometrie, une droite numerique et un diagramme de Venn; le rendu reste identique mais la note finale est desormais imposee par le serveur.

## 2026-08-31 - Accueil enfant simplifie (local)

- L'accueil enfant est recentre sur une seule decision : la prochaine mission et un grand bouton `Start my mission`. Les compteurs de niveau, la semaine, les etapes flottantes, le raccourci de parcours et le carrousel de matieres ont ete retires de ce premier ecran; la barre du bas passe de six a cinq choix enfant (`Home`, `Learn`, `Practice`, `Paths`, `Me`).
- L'entete conserve uniquement le prenom/avatar et un indicateur discret des activites du jour. Mama Judi et le campus restent visibles comme reperes, sans concurrencer l'action principale.
- Le responsive a ete ajuste pour les petits telephones : le bouton principal reste entierement au-dessus de la barre de navigation sur un viewport de 375 x 667. La tablette utilise maintenant une entete a deux zones au lieu de conserver une colonne de marque vide.
- `Progress` reste accessible depuis un nouveau bouton `My progress` dans `Me`; le retour de la page Progress ramene a `Me`. Le bulletin, les matieres, la revision et les parcours ne perdent aucun point d'acces existant utile.
- La capture utilisateur de 435 x 900 a confirme que la silhouette mobile de Mama Judi restait suspendue et que sa bulle recouvrait sa tete. Sur telephone, la silhouette et la bulle flottantes sont donc retirees; un portrait cadre sur son visage et son buste accompagne maintenant la consigne dans le panneau. Tablette et ordinateur conservent le personnage entier dans le decor.
- Une seconde capture a montre une zone de ciel trop grande. Pour les telephones de plus de 700 px de haut, le fond conserve `cover` mais utilise maintenant un cadrage vertical a 36 %, ce qui rapproche la montagne, l'ecole et le chemin sans couper le paysage ni deplacer le panneau.
- Le medaillon mobile utilisait encore trop de la silhouette en pied. L'image est maintenant agrandie a `2.3` depuis son bord superieur et centree horizontalement; le rond de 48 px montre le visage, la coiffure et les epaules au lieu du corps entier.
- Build complet isole reussi avec 82 modules. Bundles candidats : `main-Ccs3NQ_x.js` (466 122 octets) et `main-Z0ruoFjz.css` (44 157 octets).
- Verification navigateur sur 375 x 667, 390 x 844, 435 x 900, 768 x 1024 et 1180 x 800 : aucun debordement horizontal, aucun chevauchement incoherent ou bouton/navigation, textes et bouton contenus, fond en `cover` et `no-repeat`. Sur 435 x 900, le cadrage final affiche l'ecole et le chemin au-dessus du panneau avec `background-position: 50% 36%`; le portrait mobile est charge et le personnage entier reste visible sans chevauchement sur ordinateur. Aucun avertissement ni erreur navigateur.
- Apercu local : `http://127.0.0.1:4184/adventure-preview.html`. Production inchangee. Prochaine action sure : faire valider visuellement cet accueil local, puis obtenir une autorisation explicite avant sauvegarde, build isole et deploiement des quatre fichiers `AdventureDashboard.tsx`, `ChildHome.tsx`, `ProfilePage.tsx` et `adventure.css`.

## 2026-08-31 - Accueil enfant simplifie deploye en production

- Apres autorisation explicite, le lot a ete limite a `AdventureDashboard.tsx`, `ChildHome.tsx`, `ProfilePage.tsx` et `adventure.css`. Les quatre sources actives ont ete rapatriees et comparees ligne par ligne; leurs empreintes correspondaient au dernier etat connu et aucun changement concurrent n'a ete detecte.
- Le paquet final contient exactement quatre fichiers, pese 21 335 octets et a le SHA-256 `5561831620deb3d01f55523da89a27f29fe6ceba6a1f46a4379fc5b18112b487`. Les empreintes du paquet, des scripts et des quatre sources candidates ont ete controlees avant l'execution.
- Build complet isole reussi avec 82 modules. Bundles actifs : `public/react/assets/main-Ccs3NQ_x.js` (466 122 octets) et `main-Z0ruoFjz.css` (44 157 octets); les chunks Mama Judi restent `mama-CBkZkFu6.js` et `mama-BEUU7kMa.css`. Le dossier actif contient huit ressources.
- Sauvegarde prealable : `/home/david/edumaison-backups/child-home-20260831T100020Z/before-child-home.tar.gz`, SHA-256 `168524ddac24477e358dd6efdf1fcc5ecc0147ec3086a568a2b914e9e2e806d7`. Le bundle precedent reste disponible dans `/opt/edumaison-curriculum/public/react-before-child-home-20260831T100020Z`.
- Verification externe finale : `/app` et `/mama` repondent chacun trois fois HTTP 200; les sept ressources referencees et les images campus bureau/mobile et Mama Judi repondent HTTP 200 avec les tailles attendues. Le JavaScript actif contient `Start my mission` et `My progress`; le CSS actif contient le medaillon mobile et le cadrage `50% 36%`.
- `curriculum-app` et `curriculum-nginx` restent `running`, `RestartCount=0`, sans erreur recente. Aucun service n'a ete redemarre et aucune base, tentative, note ou progression enfant n'a ete modifiee.
- Verification utilisateur restante : effectuer une actualisation complete sur le telephone, ouvrir le profil enfant et confirmer l'accueil simplifie, les cinq choix de navigation et le portrait correctement centre de Mama Judi.
