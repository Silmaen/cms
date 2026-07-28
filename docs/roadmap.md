# Roadmap

> **Convention** : ce fichier reste **court** — une ligne par chantier, **groupé par
> thème**. Les chantiers qui demandent des explications ont un document dédié dans le
> dossier `roadmap/`, lié depuis la ligne correspondante.
>
> **Priorité** (badge en début de ligne) : 🔴 P1 haute · 🟠 P2 moyenne · 🟡 P3 basse · ⚪ P4 minimale

## Sécurité

- [ ] 🔴 **Hachage des mots de passe** — remplacer le SHA-256 + sel statique par `password_hash()` / `password_verify()`. → [détails](roadmap/hashage-mots-de-passe.md)
- [ ] 🟠 **Requêtes SQL / injection** — auditer et garantir des requêtes 100 % préparées (et sécuriser les identifiants dynamiques). → [détails](roadmap/securite-sql.md)

## Correctifs

- [ ] 🟠 **Bugs connus** — bugs repérés pendant le refactoring, laissés tels quels pour préserver le comportement ; à corriger un par un avec test de non-régression. → [liste](roadmap/bugs-connus.md)

## Technique

- [x] 🔴 **Tests unitaires + exécution automatique (CI)** — socle en place (fonctions pures/BDD + golden master des vues + CI) ; à étendre au fil de l'eau. → [détails](roadmap/tests-et-ci.md)
- [ ] 🟠 **Compatibilité PHP 8** — moderniser le code (le plancher OVH est déjà 7.4 ; l'appli n'est pas encore sûre en 8.x). → [détails](roadmap/php8.md)

## Infrastructure & modernisation

- [ ] ⚪ **Migration vers un backend plus moderne** — chantier long terme, encore exploratoire : hébergement mutualisé → VPS Docker, PHP → éventuellement Python/Django, MySQL → PostgreSQL/SQLite. À mener en trois étapes découplées, jamais en une fois. → [détails](roadmap/migration-backend.md)

## Traçabilité

- [ ] 🟡 **Journal des actions** — historiser qui a modifié quoi, sur les réservations, articles et adhérents. → [détails](roadmap/journal-actions.md)

## Interface

- [ ] ⚪ **Rendu responsive** — adapter l'affichage aux petits écrans et revoir les largeurs de colonnes.
- [ ] ⚪ **Thème sombre** — proposer un mode sombre, mémorisé dans les préférences utilisateur. → [détails](roadmap/preferences-utilisateur.md)
- [ ] ⚪ **Logo du Comité des Fêtes** — remplacer le logo provisoire « cms ».

## Fonctionnalités

- [ ] ⚪ **Préférences utilisateur** — un profil de préférences que l'utilisateur peut consulter et modifier. → [détails](roadmap/preferences-utilisateur.md)
- [ ] ⚪ **Aide en ligne** — une aide accessible directement dans l'application.
