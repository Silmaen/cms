# Conventions de code

À respecter pour rester cohérent avec l'existant. Ce projet est **procédural,
en français, sans framework** — ne pas le « moderniser » sans demande explicite.

## Langue

- **Français partout** : noms de variables, fonctions, colonnes, commentaires,
  messages utilisateur, libellés de templates.

## Nommage

| Élément | Convention | Exemple |
|---------|-----------|---------|
| Fonctions transverses | PascalCase, préfixe `Gestion` | `GestionIdentification`, `GestionTri` |
| Variables | snake_case français | `$id_client_selectionne`, `$action_selectionne` |
| Fichiers écrans | `<domaine>_<vue>.php` | `clients_formulaire.php`, `reservations_liste.php` |
| Templates | même nom que l'écran, extension `.tpl` | `clients_formulaire.tpl` |
| Scripts ponctuels/test | préfixe `_` ou `test-`/`example_` | `_reservations_import.php` |
| PK / FK | `id_<entité>` | `id_client`, `id_reservation` |

> ⚠️ Les fonctions sont **définies** en PascalCase (`function GestionMenu`) mais
> souvent **appelées** en camelCase (`gestionMenu(...)`). PHP est insensible à la
> casse sur les noms de fonctions, d'où la cohabitation. Conserver la définition
> PascalCase.

## Squelette d'un écran (contrôleur)

```php
<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');

/* 1. Garde d'authentification */
if (!gestionIdentification($connexion)) {
    header("Location:index.php");
    exit();
}

/* 2. Menu + fil d'Ariane */
$smarty->assign('id_admin_menu_selectionne', $_SESSION["id_admin_menu_selectionne"]);
$smarty->assign('nom_table', 'clients');
$smarty->assign('titre_menu', "Gestion d'un client");
$smarty->assign('liste_items_menu', gestionMenu($connexion));

/* 3. Lecture de l'action et de l'identifiant (GET puis POST) */
if (isset($_GET["action"]))  { $action_selectionne = $_GET["action"];  /* + id via $_GET  */ }
elseif (isset($_POST["action"])) { $action_selectionne = $_POST["action"]; /* + id via $_POST */ }

/* 4. Traitement métier selon $action_selectionne (ajouter / modifier / supprimer …) via PDO */

/* 5. Affichage */
$smarty->assign('message_formulaire', $message_formulaire);
$smarty->display('clients_formulaire.tpl');
```

## Accès base de données

- Connexion unique : variable `$connexion` (PDO) fournie par `config_general.php`.
- **Privilégier les requêtes préparées** (`$connexion->prepare(...)` +
  `execute([...])`) — pattern déjà présent (auth, insertions). Éviter la
  concaténation d'entrées ; c'est une dette de sécurité connue du projet.
- Dates : stockage en base au format `Y-m-d` ; affichage en `d-m-Y` via
  `GestionDate($date, $mode)` (`0` = BDD→affichage, `1` = affichage→BDD).

## Soft-delete (états)

- Ne jamais faire de `DELETE` sur une table métier.
- Utiliser `GestionSuppression($connexion, $id_table, $nom_table, $id_item, $action)`
  avec `$action` ∈ { `activer` (1), `archiver` (2), `supprimer` (3) }.
- La colonne `id_etat` porte l'état ; la visibilité dépend du groupe utilisateur
  (`GestionUtilisateursEtats`).

## Templates Smarty

- **Délimiteurs personnalisés** : `<!--{ $variable }-->` (pas `{ }`).
- Réutiliser les fragments `header.tpl` / `footer.tpl`.
- Passer les données depuis le contrôleur via `$smarty->assign('nom', $valeur)`.

## Duplication prod / recette

- Deux copies du code : `www/` + `cgi-bin/` (prod) et `recette/www/` +
  `recette/cgi-bin/` (recette).
- Répercuter les évolutions fonctionnelles **dans les deux**, ou signaler
  explicitement quand une seule est modifiée.
- Seule la config de connexion (`config_general.php`) doit différer entre les deux.

## Sécurité (dette connue — voir README)

- Identifiants BDD en clair dans `config_general.php` et le cron → à externaliser.
- Mots de passe hachés en SHA-256 avec sel statique → migrer vers
  `password_hash()`/`password_verify()` si un chantier sécurité est ouvert.
- Auditer les concaténations SQL restantes.
