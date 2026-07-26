# Tests unitaires et exécution automatique (CI)

## État actuel — en place

- **PHPUnit** en PHAR (sans Composer), lancé **dans Docker** via `tests/run.sh`, sur la
  **version de PHP de la prod** (lue dans `.ovhconfig`).
- **Tests de caractérisation** (golden master) : on fige le comportement actuel, tout
  changement fait « claquer » un test.
- **Couverture** : toutes les fonctions de `fonctions_general.php` (pures **et** BDD, sur
  une base de test dédiée `comitefetes_test` + fixtures) ; **golden master** des vues
  Smarty (login + accueil).
- **CI** : `.github/workflows/tests.yml`, déclenché **sur chaque Pull Request** (pas sur
  push). MySQL est démarré via `docker run` (un seul job → un seul check). Les versions
  proviennent des sources uniques (`.ovhconfig` pour PHP, `.env.defaults` pour MySQL/PHPUnit).
- **Couverture de code** : pilote **PCOV** (dans l'image), périmètre `cgi-bin/config` ;
  résumé texte + rapport HTML (artefact téléchargeable en CI).

Détails d'utilisation : [`../../tests/README.md`](../../tests/README.md).

## Reste à faire

- Étendre le golden master à d'autres écrans (listes, formulaires) et aux **PDF**
  (`impressions_*.php`).
- Rendre le check **`PHPUnit`** *required* dans le ruleset « Branches longues » (après un
  premier passage en PR).

## Limite connue

Les contrôleurs `www/*.php` (procéduraux, couplés à `$_GET`/`$_POST`, Smarty, `exit`) ne
sont pas testables unitairement → on les couvre par golden master (rendu figé), et on
extraira progressivement la logique en fonctions testables (lié à la
[modernisation PHP 8](php8.md)).

## Étapes

1. [x] Socle PHPUnit + arborescence `tests/` (exécution dans Docker).
2. [x] Fonctions couvertes : pures **et** BDD (base de test + fixtures).
3. [x] Golden master des vues (Smarty) — login + accueil ; extensible.
4. [x] Workflow GitHub Actions (MySQL via `docker run`, sur PR).
5. [ ] Check `PHPUnit` rendu *required* dans le ruleset.
