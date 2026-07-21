# Architecture (référence agent)

PHP 7.2 procédural, sans framework/routeur. 1 URL = 1 fichier `www/*.php`.
Doc humaine équivalente : `docs/architecture.md` (ne pas dupliquer, ce fichier
est optimisé pour action rapide).

## Grounding rapide

| Besoin | Fichier |
|--------|---------|
| Connexion PDO (`$connexion`) + init Smarty + `session_start()` | `cgi-bin/config/config_general.php` |
| Fonctions communes `Gestion*` | `cgi-bin/config/fonctions_general.php` |
| Contrôleurs | `www/*.php` |
| Vues | `www/templates/*.tpl` (délimiteurs `<!--{ }-->`) |
| Login / logout | `www/index.php` (détruit la session) |
| Cron | `cgi-bin/reservations_maj_automatique.php` |

## En-tête obligatoire de tout contrôleur

```php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');
if (!gestionIdentification($connexion)) { header("Location:index.php"); exit(); }
```

Puis : `assign` menu → lire `$_GET`/`$_POST` (`action`, `id_*`) → SQL → `display`.

## Fonctions de `fonctions_general.php` (signatures réelles)

| Fonction | Effet / piège |
|----------|---------------|
| `GestionHashage($mdp)` | SHA-256 + sel statique en dur (préfixe+suffixe). **Ne pas changer le sel** sans rehacher toute la table. |
| `GestionIdentification($connexion)` | Lit `$_POST["email"]`/`$_POST["mdp"]`, remplit `$_SESSION`. Renvoie `false` si non identifié. Garde à appeler en tête de chaque écran. |
| `GestionMenu($connexion)` | Items depuis `admin_menu ORDER BY ordre`. |
| `GestionMenusDroits($connexion)` | Droit sur le menu courant → `$_SESSION["droit"]`. |
| `GestionDate($date, $mode)` | `$mode=0` : `Y-m-d`→`d-m-Y` ; `$mode=1` : inverse. Renvoie `false` si date vide. |
| `GestionPagination($connexion,$id_table,$nom_table)` | Écrit `$_SESSION["nombre_de_pages"]`. |
| `GestionSuppression($connexion,$id_table,$nom_table,$id_item,$action,$nom_table_bis='')` | **Soft-delete** : `activer`→`id_etat=1`, `archiver`→2, `supprimer`/`supprimer-envoyer`→3. Jamais de `DELETE`. |
| `GestionTri(...)` | Persiste colonne/sens/`items_par_page` dans `admin_utilisateurs_session` par (`id_utilisateur`,`id_admin_menu`). |
| `GestionUtilisateursEtats($groupe)` | `1`→`99` (tout), `2`→`2` (actif+archivé), `3`→`1` (actif). |

## Casse des appels (piège)

Définition PascalCase (`function GestionMenu`), appels souvent camelCase
(`gestionMenu(...)`). PHP insensible à la casse sur les noms de fonctions →
cohabitation normale. **Conserver la définition en PascalCase.**

## États vs statuts

- `id_etat` (table `etats`) : technique, commun à toutes les tables métier —
  `1`=actif, `2`=archivé, `3`=supprimé.
- `id_statut_*` (`clients_statuts`, `reservations_statuts`, `inventaires_statuts`,
  `inventaires_types`) : métier, propre à chaque entité. **Ne pas confondre.**

## Smarty

Délimiteurs `<!--{ $var }-->`. Fragments : `header.tpl`, `header-cdn.tpl`,
`header_mdp.tpl`, `footer.tpl`. Compilation dans `www/templates_c/`.

## PDF

`www/impressions_*.php` (10 écrans) via TCPDF (`cgi-bin/tcpdf/`).
`impressions_automatique_reservations_jour.php` est appelé par le cron.

## Prod / recette

Code dupliqué : `www/`+`cgi-bin/` (prod) et `recette/www/`+`recette/cgi-bin/`
(recette). **Répercuter toute évolution fonctionnelle dans les deux** ou le
signaler. Divergence connue : le `SELECT` du cron utilise `id_etat=2` en prod et
`id_etat=1` en recette (le prod paraît buggé — cf. `conventions.md`).

## Secrets

`config_general.php` et `reservations_maj_automatique.php` sont **ignorés par
git** (`.gitignore`, noms nus → toute profondeur). Modèles versionnés : `*.dist`.
Ne jamais committer de credentials réels ; ne jamais retirer ces entrées du
`.gitignore`.
