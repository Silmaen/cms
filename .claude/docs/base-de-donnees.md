# Base de données (référence agent)

Pas de dump SQL dans le dépôt. Schéma **déduit des requêtes** → colonnes fiables,
types non garantis. Doc humaine : `docs/base-de-donnees.md`.

## Tables (recensées via FROM/JOIN/INTO/UPDATE)

Métier : `clients`, `clients_adhesions`, `clients_statuts`, `clients_import`,
`articles`, `inventaires`, `inventaires_articles`, `inventaires_types`,
`inventaires_statuts`, `reservations`, `reservations_articles`,
`reservations_statuts`, `fichiers`.
Test/bac à sable (ignorer) : `reservations_test`, `reservations_test_avec_id`.
Admin : `etats`, `admin_menu`, `admin_menus_groupes`, `admin_utilisateurs`,
`admin_utilisateurs_groupes`, `admin_utilisateurs_session`, `log_cron`.

## Colonnes (d'après les INSERT réels)

- **clients** : `id_client`(PK), `association`, `nom`, `prenom`, `adresse1..3`,
  `cp`, `ville`, `telephone`, `email`, `commentaire`, `cle_client`,
  `date_creation`, `date_modification`, `id_etat`, `id_statut_client`,
  `id_membre_auteur`.
- **clients_adhesions** : `id_client`, `annee`, `montant`, `date_creation`,
  `date_modification`, `id_membre_auteur`.
- **articles** : `id_article`(PK), `designation`, `commentaire`, `date_creation`,
  `date_modification`, `id_etat`, `ordre_article`, `id_membre_auteur`.
- **reservations** : `id_reservation`(PK), `id_client`, `commentaire`, `don`,
  `date_don`, `date_depart`, `date_retour`, `date_creation`, `date_modification`,
  `heure_modification`, `annee_reservation`, `id_etat`, `id_statut_reservation`,
  `id_membre_auteur`.
- **reservations_articles** : `id_reservation`, `id_article`, `quantite_reservee`,
  `id_membre_auteur`.
- **inventaires** : `id_inventaire`(PK), `commentaire`, `date_inventaire`,
  `date_creation`, `date_modification`, `id_statut_inventaire`,
  `id_type_inventaire`, `id_etat`, `id_membre_auteur`.
- **admin_utilisateurs** : `id_utilisateur`(PK), `id_utilisateur_groupe`,
  `prenom_utilisateur`, `nom_utilisateur`, `email_utilisateur`,
  `mdp_utilisateur` (SHA-256 salé), `items_par_page`.
- **admin_utilisateurs_session** : `id_utilisateur`, `id_admin_menu`, `colonne`,
  `colonne_titre_fr`, `largeur`, `ordre`, `sens_tri`, `tri_utilisateur`,
  `items_par_page`.
- **admin_menu** : `id_admin_menu`(PK), `titre_fr`, `url`, `niveau`, `id_parent`,
  `ordre`.
- **admin_menus_groupes** : `id_utilisateur_groupe`, `id_admin_menu`, `droit`.
- **log_cron** : `date`, `id_reservation`.

## Conventions de schéma (à respecter pour toute nouvelle table/colonne)

- PK : `id_<table_singulier>`. FK : même nom que la PK cible.
- Socle d'audit sur les tables métier : `date_creation`, `date_modification`,
  `id_membre_auteur` (= `$_SESSION["id_membre_auteur"]`).
- Soft-delete via `id_etat` (FK → `etats` ; 1/2/3). Filtrer la visibilité selon
  `GestionUtilisateursEtats($_SESSION["id_utilisateur_groupe"])`.
- Statut métier séparé : `id_statut_*` distinct de `id_etat`.

## Relations clés

`clients 1—N clients_adhesions` · `clients 1—N reservations` ·
`reservations 1—N reservations_articles N—1 articles` ·
`inventaires 1—N inventaires_articles N—1 articles` ·
`admin_utilisateurs_groupes 1—N admin_utilisateurs` ·
`(admin_menu × admin_utilisateurs_groupes) → admin_menus_groupes(droit)`.
