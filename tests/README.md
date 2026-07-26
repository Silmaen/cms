# Tests

Tests **de caractérisation** (*golden master*) : on fige le comportement **actuel**
du code pour détecter les régressions. Un test qui « claque » = un comportement a
changé → soit c'est **voulu** (on met à jour ou on ajoute le test), soit c'est une
**régression** (on corrige). Ces tests décrivent ce que le code **fait**, pas ce
qu'il devrait idéalement faire (les bizarreries sont figées telles quelles).

## Lancer les tests

La stack doit tourner (`./dc.sh up`). Puis :

```bash
./tests/run.sh                 # toute la suite
./tests/run.sh --filter Date   # filtrer par nom de test
```

PHPUnit (version `PHPUNIT_VERSION` de `.env.defaults`, compatible **PHP 7.4**) est utilisé
en **PHAR**, téléchargé au premier lancement dans `tests/.bin/` (ignoré par git). **Pas de
Composer.** Les tests s'exécutent **dans le conteneur** (même PHP que la prod).

## Couverture de code

Le pilote **PCOV** est inclus dans l'image. Le périmètre mesuré (`cgi-bin/config`) est
défini par `<coverage>` dans `phpunit.xml`.

```bash
./tests/run.sh --coverage-text                 # résumé dans le terminal
./tests/run.sh --coverage-html tests/coverage  # rapport navigable → tests/coverage/index.html
```

En CI, le résumé s'affiche dans le log et le rapport **HTML** est publié en **artefact
téléchargeable** de chaque exécution.

> Le pourcentage reflète surtout `fonctions_general.php` (bien couvert). Les fichiers
> d'amorçage (`config_general.php`, `environnement.php`, `identifiants_bdd.php`) ne sont
> pas exécutés par PHPUnit → ils tirent le total vers le bas ; c'est attendu.

## Structure

```
tests/
├── bootstrap.php    # charge les fonctions + les classes de base
├── unit/            # tests des fonctions PURES (sans BDD)
├── integration/     # tests des fonctions BDD (contre une base de test)
├── golden/          # golden master des vues (rendu Smarty)
├── support/         # DatabaseTestCase + SmartyRenderTestCase
├── fixtures/        # schema.sql de la base de test
├── snapshots/       # rendus HTML figés (versionnés) — le « golden »
└── .bin/            # binaire phpunit.phar (gitignoré)
```

Les tests BDD utilisent une base **`comitefetes_test`** (créée à la volée), un **schéma
rechargé avant chaque test** (isolation), et des données **synthétiques** insérées par
chaque test. Connexion paramétrable par `CDF_TEST_DB_*` (défauts = Docker local).

Le **golden master** rend un template Smarty avec des données fixes et compare le HTML
au fichier figé dans `snapshots/`. Un rendu qui change → test rouge. Pour **régénérer**
un snapshot après un changement voulu : supprimer le fichier concerné et relancer.

## Couverture actuelle

- ✅ **Fonctions pures** : `GestionHashage`, `GestionDate`, `GestionUtilisateursEtats`.
- ✅ **Fonctions BDD** : `GestionMenu`, `GestionSuppression`, `GestionIdentification`,
  `GestionPagination`, `GestionMenusDroits`, `DupliquerSessionUtilisateur`, `GestionTri`.
- ✅ **Vues (golden master)** : page de connexion (`index.tpl`) et accueil connecté
  (`accueil.tpl` → navbar du header).
- ✅ **CI** : `.github/workflows/tests.yml` — sur chaque **Pull Request** (MySQL démarré
  via `docker run`, versions lues dans `.ovhconfig` / `.env.defaults`).

## À venir

Voir [`../docs/roadmap/tests-et-ci.md`](../docs/roadmap/tests-et-ci.md) :

- Étendre le golden master à d'autres écrans (listes, formulaires) et aux **PDF**
  (`impressions_*.php`).
