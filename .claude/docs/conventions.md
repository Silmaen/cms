# Conventions (référence agent)

Règles impératives. Projet procédural, français, sans framework — **ne pas
moderniser sans demande explicite**. Doc humaine : `docs/conventions.md`.

## À FAIRE

- Tout nommer/écrire **en français** (variables, fonctions, colonnes, messages).
- Commencer chaque contrôleur par le bootstrap + garde `gestionIdentification`
  (voir `architecture.md`).
- Utiliser `$connexion` (PDO) déjà fourni ; **requêtes préparées** (`prepare`+
  `execute([...])`).
- Dates : stocker en `Y-m-d`, afficher en `d-m-Y` via `GestionDate`.
- Suppression via `GestionSuppression` (soft-delete `id_etat`), jamais `DELETE`.
- Renseigner `date_creation`/`date_modification`/`id_membre_auteur` sur écritures.
- Répercuter les changements fonctionnels dans **`www/` ET `recette/www/`**.
- Templates : variables entre `<!--{ }-->`, réutiliser `header.tpl`/`footer.tpl`.

## À NE PAS FAIRE

- Introduire un framework, de la POO, Composer, un build : hors périmètre.
- Committer `config_general.php` / `reservations_maj_automatique.php` ou tout
  credential réel (ignorés par `.gitignore` ; modèles `*.dist`).
- Renommer les fonctions `GestionXxx` (définition PascalCase, appels camelCase
  tolérés par PHP — ne pas « corriger »).
- `DELETE` sur une table métier.

## Nommage

`GestionXxx` (fonctions) · `$snake_case_fr` (variables) ·
`domaine_vue.php` (écrans) + `domaine_vue.tpl` (templates) · `id_<entité>` (BDD) ·
préfixe `_`/`test-`/`example_` = scripts hors prod.

## Dette de sécurité connue (ne pas régresser)

- Credentials désormais hors dépôt via `.dist` — garder ainsi.
- Mots de passe : SHA-256 + sel statique (`GestionHashage`). Migration
  `password_hash()`/`password_verify()` seulement si chantier sécurité demandé
  (implique rehash + colonne).
- Concaténations SQL restantes (variables de session dans certaines requêtes) :
  préférer le préparé sur tout code touché.
