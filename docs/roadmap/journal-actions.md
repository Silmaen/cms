# Journal des actions (traçabilité)

## État actuel

Les tables métier portent seulement `date_creation`, `date_modification` et
`id_membre_auteur` → on connaît **la dernière** personne ayant modifié une ligne, mais
**pas l'historique** ni le détail de ce qui a changé. La table `log_cron` ne trace que la
tâche planifiée.

## Objectif

Savoir **qui a fait quoi et quand** sur les entités sensibles : **réservations**,
**articles**, **adhérents/clients** (et leurs adhésions). Permettre à un administrateur de
consulter cet historique.

## Piste technique

Une table dédiée, alimentée par le **code** (MyISAM → pas de *triggers* fiables, on
centralise en PHP) :

```
journal_actions(
  id_action        PK,
  date_heure       datetime,
  id_utilisateur   -> admin_utilisateurs,
  action           enum/texte : creation | modification | archivage | suppression
  entite           texte : reservation | article | client | adhesion | ...
  id_entite        l'identifiant concerné,
  details          texte/JSON : champs modifiés (avant → après)
)
```

Alimentation : un helper `JournaliserAction(...)` appelé aux points d'écriture
(insertions, mises à jour, et dans `GestionSuppression()` pour archivage/suppression).

## Étapes

1. Créer la table `journal_actions`.
2. Écrire le helper et l'appeler depuis les contrôleurs d'écriture des 3 entités.
3. Ajouter un écran d'administration de consultation (filtres par entité / utilisateur / date).
4. Optionnel : capturer le *diff* avant/après pour les modifications.
