# Contribuer

Merci de contribuer à l'application du Comité des Fêtes de Genay ! Ce guide résume
l'essentiel. Toute la communication, le code et la documentation sont **en français**.

## Mettre en place l'environnement local

L'application tourne en Docker (PHP 7.4 + MySQL) :

```bash
cp .env.sample .env    # au premier clone (puis ajuster UID/GID)
./dc.sh up             # construit l'image si besoin, pose l'env, démarre la stack
```

Le site est alors sur http://localhost:8080. Détails : [`docker/README.md`](docker/README.md).

## Flux de branches

On ne pousse **jamais** directement sur les branches déployées. Le flux est :

```
feature/*  ou  bugfix/*   →  test  →  recette  →  main
```

- On part d'une branche **`feature/<sujet>`** ou **`bugfix/<sujet>`**.
- On ouvre une **Pull Request** vers `test`. Une fois fusionnée, la branche est supprimée.
- La remontée se fait ensuite par PR : `test` → `recette` → `main`.
- Les branches `test`, `recette` et `main` sont **protégées** (pas de push direct, pas de
  suppression, pas de *force-push*).

> ⚠️ **Ne jamais faire de `git push --force`** sur `test` / `recette` / `main` : cela
> casse le déploiement automatique OVH. Voir
> [`docs/environnements-et-deploiement.md`](docs/environnements-et-deploiement.md).

## Conventions de code

À lire avant de coder : [`docs/conventions.md`](docs/conventions.md) et
[`docs/architecture.md`](docs/architecture.md). En résumé : PHP procédural, fonctions
`GestionXxx` en PascalCase, variables et SQL en français, requêtes **préparées**,
soft-delete via `id_etat` (jamais de `DELETE`), et **aucun secret dans Git**.

### Mise en forme (php-cs-fixer)

L'indentation est aux **tabulations** (cf. `.editorconfig`). Un formateur normalise
l'indentation et les espaces sans toucher au reste (accolades, guillemets, contenu
des chaînes) :

```bash
./cs.sh              # corrige la mise en forme
./cs.sh --dry-run    # vérifie sans modifier (ce que fait la CI)
```

Règles : [`.php-cs-fixer.dist.php`](.php-cs-fixer.dist.php). La CI **Style** rejette
toute PR dont le code n'est pas au format (lance `./cs.sh` avant de pousser).
> PyCharm ne formate pas le PHP (c'est une fonctionnalité de PhpStorm) : utiliser `./cs.sh`.

## Où en est le projet

Les chantiers à venir (par priorité) sont dans [`docs/roadmap.md`](docs/roadmap.md).
