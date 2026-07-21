# Domaine métier (référence agent)

Comité des fêtes : adhésions annuelles + prêt/location de matériel. Doc humaine :
`docs/domaine-metier.md`.

## Entités → tables → écrans

| Entité | Table(s) | Écrans `www/` |
|--------|----------|---------------|
| Client/adhérent | `clients` (+`clients_statuts`) | `clients_liste.php`, `clients_formulaire.php`, `fiche_client.php` |
| Adhésion | `clients_adhesions` (`annee`,`montant`) | saisie via fiche client ; `impressions_adhesions_exercice.php` |
| Article (matériel) | `articles` | `articles_liste.php`, `articles_formulaire.php` |
| Inventaire | `inventaires`,`inventaires_articles` (+types/statuts) | `inventaires_liste.php`, `inventaires_formulaire.php`, `fiche_inventaire.php` |
| Réservation | `reservations`,`reservations_articles` | `reservations_liste.php`, `reservations_formulaire.php`, `fiche_reservation*.php`, `*confirmation*.php` |
| Don | colonnes `don`,`date_don` de `reservations` | — |
| Règlement (reçu) | dérivé d'adhésions/réservations (pas de table dédiée) | `impressions_reglements_detail.php`, `impressions_reglements_recus.php` |
| Pièce jointe | `fichiers` | `fichier_ajouter.php`, `fichier_supprimer.php` |
| Utilisateur/groupe | `admin_utilisateurs*` | `admin_utilisateurs_*`, `admin_utilisateurs_groupes_*` |

Scripts hors production (ignorer sauf demande) : préfixe `_`, `test-*`,
`example_*`, `index-test.php`.

## Réservation — cycle & règle temporelle

- Dimensions : `id_etat` (technique 1/2/3) + `id_statut_reservation` (métier).
- Champs clés : `date_depart`, `date_retour`, `annee_reservation`, `don`.
- **Cron** (`reservations_maj_automatique.php`) : quand `date_retour <= aujourd'hui`,
  bascule l'état, insère dans `log_cron`, envoie un e-mail avec liens PDF.
  Attention : requête `SELECT` divergente prod (`id_etat=2`) / recette (`id_etat=1`).

## Points d'attention métier

- « Supprimer » = passer `id_etat=3` (jamais d'effacement réel).
- Un client a **plusieurs** adhésions (1 par `annee`).
- Une réservation référence des articles avec `quantite_reservee`.
- E-mails/URL publics du cron : domaine `cdf-genay.com` (pas des secrets).
