# Tests unitaires et exécution automatique (CI)

## État actuel

**Aucun test automatisé.** Le projet est du PHP procédural sans build ; on vérifie à la
main dans le navigateur.

## Objectif

- Des **tests unitaires** sur la logique réutilisable.
- Une **exécution automatique** à chaque `push` (intégration continue), qui échoue
  visiblement si un test casse.

## Piste technique

- **Outil** : **PHPUnit** (une version compatible PHP 7.4). Exécuté **dans Docker**
  (règle projet : pas d'outillage natif sur l'hôte) — via un service dédié du
  `docker-compose.yml` ou une commande one-shot sur l'image PHP.
- **Par quoi commencer** : les fonctions **pures** de `cgi-bin/config/fonctions_general.php`
  qui ne touchent pas la base — ex. `GestionDate` (formats `Y-m-d` ↔ `d-m-Y`),
  `GestionHashage`, `GestionUtilisateursEtats`. Faciles à couvrir, gros retour sur
  investissement.
- **Tests avec base** : lancer une **base MySQL de test** (le service Docker existe déjà)
  et tester les fonctions qui prennent `$connexion` sur un jeu de données contrôlé.
- **Limite** : les contrôleurs `www/*.php` (procéduraux, couplés à `$_GET`/`$_POST`,
  Smarty, `exit`) sont difficiles à tester unitairement → extraire progressivement la
  logique en fonctions testables (chantier lié à la [modernisation PHP 8](php8.md)).

## CI

Un workflow **GitHub Actions** (`.github/workflows/tests.yml`) qui, sur `push` et sur les
Pull Requests :

1. démarre PHP 7.4 + un service MySQL ;
2. installe PHPUnit ;
3. lance la suite de tests.

À terme, ce job peut devenir un **check requis** sur les PR (une fois les branches
protégées — voir l'historique des discussions de déploiement).

## Étapes

1. Ajouter PHPUnit et une arborescence `tests/` (+ commande d'exécution dans Docker).
2. Couvrir d'abord les fonctions pures, puis celles avec base.
3. Écrire le workflow GitHub Actions et le rendre visible sur les PR.
