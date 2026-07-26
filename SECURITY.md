# Politique de sécurité

## Signaler une vulnérabilité

Merci de **ne pas ouvrir d'issue publique** pour une faille de sécurité.

Utilisez la fonction **« Report a vulnerability »** de l'onglet *Security* du dépôt
GitHub (avis de sécurité privés), qui permet un échange confidentiel avec le mainteneur.

Nous nous efforçons d'accuser réception sous quelques jours.

## Périmètre

L'environnement de référence est la **production** (branche `main`). Les branches
`recette` et `test` sont des environnements de pré-production et de test.

## Bon à savoir

Plusieurs renforcements sont déjà planifiés (hachage des mots de passe, audit SQL) —
voir la [roadmap](docs/roadmap.md). Aucun identifiant réel n'est versionné : les secrets
vivent hors dépôt (voir [`docs/environnements-et-deploiement.md`](docs/environnements-et-deploiement.md#3-les-identifiants-de-base-de-données)).
