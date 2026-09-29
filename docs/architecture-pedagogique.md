# Architecture pédagogique EduMaison

## Principes

- Séparer la langue de l'interface, la langue d'enseignement et le sous-système scolaire.
- Conserver le curriculum officiel comme référentiel commun et versionné.
- Garder les médias, voix et enregistrements familiaux strictement rattachés au foyer.
- Ne jamais qualifier un contenu d'« officiel » sans document, version et page de référence.
- Préserver les tentatives et résultats historiques lors des révisions de contenu.

## Chaîne pédagogique

`Document officiel -> niveau -> matière -> compétence -> séquence -> unité -> leçon -> exercice -> média`

Chaque exercice peut mobiliser plusieurs compétences et plusieurs médias. Une compétence peut être
en brouillon, auditée ou vérifiée. La publication aux enfants reste distincte de la vérification.

## Langues et sous-systèmes

- `ui_locale` : langue de navigation du parent ou de l'enfant.
- `instruction_language` : langue de la consigne ou de la ressource.
- `education_subsystem` : `anglophone`, `francophone`, `bilingual` ou futur sous-système.
- Le français enseigné à un enfant anglophone reste une matière du sous-système anglophone.

## Médias d'exercice

Les exercices acceptent des images, schémas, graphes, audio et vidéo avec texte alternatif,
transcription, langue et métadonnées. Les fichiers communs appartiennent au curriculum; les
enregistrements de l'enfant restent dans son foyer.

## Voix de l'accompagnateur

Première version : clips enregistrés volontairement par le parent (salutation, encouragement,
consigne, rappel). Chaque clip exige un consentement horodaté, appartient à un seul foyer et peut
être désactivé sans supprimer l'historique scolaire. La synthèse/clonage vocal sera un module
ultérieur avec consentement séparé.

## Pilote Class 4

1. Enregistrer le PDF MINEDUB Level 2 et son empreinte.
2. Extraire uniquement les compétences Class 4 avec les pages sources.
3. Auditer les contenus existants sans les supprimer.
4. Mapper compétences, séquences, unités et exercices.
5. Tester avec Mark, puis répliquer vers Class 3, Class 5 et Class 6.
