# Bugs connus (repérés, non corrigés)

> Bugs repérés lors de l'extraction de la couche métier. **Volontairement non
> corrigés** pour l'instant : le refactoring en cours doit garder un comportement
> **strictement identique**. Chaque correction est un changement de comportement à
> traiter séparément (avec un test de non-régression dédié).
>
> Sévérité : 🔴 casse une fonctionnalité · 🟠 comportement faux/silencieux · 🟡 latent / à confirmer

## 🔴 `copier-valider` d'un utilisateur — INSERT invalide

`www/admin_utilisateurs_formulaire.php`, cas `copier-valider`.

La requête d'insertion contient une **valeur vide** entre deux virgules :

```sql
... VALUES (:nom_utilisateur, ..., :id_utilisateur_groupe, , :date_creation, ...)
```

Le `, ,` rend le SQL invalide : `execute()` échoue toujours, donc **dupliquer un
utilisateur ne crée jamais l'enregistrement** (le message d'erreur « Copier-Valider »
est affiché). Correctif : retirer la virgule vide.

## 🔴 `modifier-rc` d'une réservation — alias SQL sans jointure

`www/reservations_formulaire.php`, cas `modifier-rc` (Réservation Classique).

Le `SELECT` sélectionne `t6.libelle_statut AS libelle_statut_client` mais le `FROM`
**ne joint pas** `clients_statuts AS t6` (contrairement aux cas `copier`/`modifier`).
MySQL renvoie « Unknown column 't6.libelle_statut' » : la requête échoue toujours,
la branche `else` n'est jamais exécutée et **la liste d'articles du mode Réservation
Classique ne s'affiche jamais**. Correctif : ajouter la jointure
`LEFT JOIN clients_statuts AS t6 ON t6.id_statut_client=t4.id_statut_client`.

## 🟠 Test d'erreur sur la mauvaise variable (boucles d'articles)

`www/inventaires_formulaire.php` (cas ajouter/copier/modifier) et boucles
équivalentes de `www/reservations_formulaire.php`.

Dans les boucles de construction de la liste d'articles, le contrôle d'erreur de la
sous-requête teste `if(!$sql_exec)` alors que `$sql_exec` porte le résultat de la
**requête précédente** (déjà réussie) ; le résultat réel de la sous-requête est dans
une autre variable (`$sql_exec_inventaires`, etc.). Conséquence : le message d'erreur
ne se déclenche **jamais** ; une panne de la sous-requête passerait inaperçue. Bug
latent (n'affecte pas le cas nominal). Correctif : tester la bonne variable.

## 🟠 Précédence AND/OR dans le filtre des associations

`www/reservations_formulaire.php`, listes `liste_associations` (deux requêtes).

```sql
WHERE (id_etat='1' AND id_statut_client=3 OR id_statut_client=4 OR id_statut_client=5)
```

`AND` étant prioritaire sur `OR`, la condition équivaut à
`(id_etat=1 AND statut=3) OR statut=4 OR statut=5` : les clients de statut 4 ou 5
**sont inclus quel que soit leur état** (y compris archivés/supprimés). Même schéma
pour les statuts 1/2 dans la seconde requête. Correctif : parenthéser le filtre de
statut, `id_etat='1' AND (id_statut_client IN (...))`.

## 🟡 Auteur des mutations : `$_SESSION["id_membre_auteur"]` — à confirmer

Tous les `*_formulaire.php`.

Les INSERT/UPDATE enregistrent l'auteur via `$_SESSION["id_membre_auteur"]`, alors que
la session semble stocker l'identifiant sous `$_SESSION["id_utilisateur"]` (utilisé
partout ailleurs, y compris les `$smarty->assign` du cas « ajouter »). Si la clé
`id_membre_auteur` n'est jamais renseignée, l'auteur enregistré vaut NULL/0. **À
vérifier** en base réelle avant toute correction.
