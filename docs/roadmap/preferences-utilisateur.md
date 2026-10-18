# Préférences utilisateur (profil + thème sombre)

Deux chantiers liés, car le thème sombre se **mémorise dans les préférences**.

## État actuel

Des préférences existent déjà, mais **implicites** (pas d'écran dédié) :

- `admin_utilisateurs.items_par_page` — pagination par défaut ;
- `admin_utilisateurs_session` — par utilisateur **et par écran** : colonnes affichées,
  **largeurs**, ordre, sens du tri, pagination.

Le thème, lui, est piloté par la variable CSS `--cdf-primaire` et l'attribut `data-env`
sur `<body>` (voir [environnements-et-deploiement.md](../environnements-et-deploiement.md#2-comment-lenvironnement-est-détecté)).

## Objectif 1 — Profil de préférences

Un écran où l'utilisateur **consulte et modifie** ses préférences (pagination, tri par
défaut, thème…), au lieu qu'elles soient seulement déduites de son usage.

## Objectif 2 — Thème sombre

Proposer un **mode sombre**, mémorisé par utilisateur.

**Piste technique :** ajouter un attribut `data-theme="dark"` sur `<body>` (à côté de
`data-env`) et des règles CSS sombres dans `www/css/styles.css` (idéalement via des
variables CSS pour les fonds/textes, comme `--cdf-primaire` pour la couleur d'accent).
Stocker le choix dans une préférence utilisateur — une nouvelle colonne
`admin_utilisateurs.theme_prefere` (`clair` / `sombre` / `auto`), ou une table de
préférences générique si on en prévoit d'autres.

## Étapes

1. ~~Décider du stockage~~ → table clé/valeur `admin_utilisateurs_preferences`, créée par migration à chaud (`metier/schema.php`).
2. ~~Écran « Mes préférences »~~ → `www/preferences.php` : thème (mémorisé, porté par `data-theme` sur `<body>`) et lignes par page (appliquées à toutes les listes).
3. ~~Règles sombres~~ → `www/assets/css/styles.css`, toutes sous `body[data-theme="sombre"]` (le thème clair n'est pas touché).
4. ~~Option `auto`~~ → petit script en tête de `header.tpl` qui bascule `data-theme` en `sombre` selon `prefers-color-scheme`, en direct.

Reste ouvert : la page de connexion (sans session) est toujours en clair ; d'autres préférences pourront s'ajouter dans `PreferencesChoix()` sans migration.
