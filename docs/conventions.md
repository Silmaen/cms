# Conventions de code

Ce document rassemble les règles à suivre pour contribuer au projet et rester
cohérent avec l'existant. L'application est **procédurale, en français, et sans
framework** : mieux vaut suivre le style en place que chercher à le moderniser
sans raison.

## La langue

**Tout est en français** : les noms de variables et de fonctions, les colonnes
de la base, les commentaires, et bien sûr les messages affichés à l'utilisateur.

## Le nommage

| Élément | Règle | Exemple |
|---------|-------|---------|
| Fonctions communes | Préfixe `Gestion`, en un seul mot | `GestionIdentification`, `GestionTri` |
| Variables | Mots séparés par des tirets bas | `$id_client_selectionne` |
| Fichiers d'écran | `<domaine>_<vue>.php` | `clients_formulaire.php` |
| Templates | Même nom que l'écran, en `.tpl` | `clients_formulaire.tpl` |
| Scripts d'essai / ponctuels | Préfixe `_`, ou `test-`/`example_` | `_reservations_import.php` |
| Identifiants en base | `id_<élément>` | `id_client`, `id_reservation` |

> À noter : les fonctions sont **définies** avec une majuscule (`GestionMenu`)
> mais souvent **appelées** avec une minuscule (`gestionMenu`). PHP ne fait pas la
> différence sur le nom des fonctions, d'où cette cohabitation dans le code.

## Le squelette d'une page

Toutes les pages suivent la même structure. Pour créer un nouvel écran, partez de
ce modèle :

```php
<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');

/* 1. On vérifie que l'utilisateur est connecté */
if (!gestionIdentification($connexion)) {
    header("Location:index.php");
    exit();
}

/* 2. On prépare le menu et le fil d'Ariane */
$smarty->assign('nom_table', 'clients');
$smarty->assign('titre_menu', "Gestion d'un client");
$smarty->assign('liste_items_menu', gestionMenu($connexion));

/* 3. On lit l'action demandée et l'identifiant (dans l'URL, puis le formulaire) */
if (isset($_GET["action"]))       { $action_selectionne = $_GET["action"];  }
elseif (isset($_POST["action"]))  { $action_selectionne = $_POST["action"]; }

/* 4. On traite l'action (ajouter, modifier, supprimer…) en base */

/* 5. On affiche la page */
$smarty->display('clients_formulaire.tpl');
```

## L'accès à la base de données

- La connexion est déjà ouverte dans la variable `$connexion` (fournie par
  `config_general.php`). On la réutilise, on n'en ouvre pas d'autre.
- **Privilégier les requêtes préparées** (`prepare` + `execute`) plutôt que de
  coller directement des valeurs dans le SQL. C'est plus sûr, et c'est déjà le
  cas dans les parties récentes du code.
- Les **dates** sont stockées au format `AAAA-MM-JJ` en base, mais affichées en
  `JJ-MM-AAAA`. La fonction de conversion des dates fait le pont entre les deux.

## Ne jamais supprimer réellement

On n'efface pas les données métier avec un `DELETE`. On utilise la fonction de
suppression prévue, qui se contente de changer l'**état** de l'élément :
*actif*, *archivé* ou *supprimé*. Un élément « supprimé » reste ainsi
récupérable. (Voir l'[architecture](architecture.md#rien-nest-jamais-vraiment-supprimé).)

## Les templates

- Les variables dans les templates s'écrivent entre `<!--{` et `}-->` (et non
  entre accolades simples).
- Réutilisez les fragments communs `header.tpl` et `footer.tpl`.
- Les données sont transmises depuis la page PHP vers le template avec
  `$smarty->assign('nom', $valeur)`.

## Production et recette

Le code existe en deux exemplaires : `www/` (production) et `recette/`
(environnement de test). En principe, **une modification fonctionnelle doit être
appliquée des deux côtés**. Seule la configuration de connexion à la base doit
différer entre les deux.

## Sécurité : les points connus à améliorer

Ces limites sont connues et documentées dans le
[README principal](../README.md#sécurité--points-dattention) :

- les identifiants de base de données figuraient en clair dans le code
  (désormais sortis du dépôt via des fichiers `.dist`) ;
- les mots de passe sont hachés avec une méthode ancienne, à moderniser ;
- certaines requêtes construites « à la main » sont à sécuriser.
