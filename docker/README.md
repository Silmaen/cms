# Environnement de développement Docker

Environnement local reproduisant l'hébergement OVH (PHP 7.2 + MySQL), avec les
**deux environnements** (production et recette) servis en parallèle, chacun sur
sa propre base de données, plus un **phpMyAdmin** unique pour les consulter.

## Démarrage

L'image web se construit avec un **script dédié** (et non `docker compose build`) —
voir [Pourquoi un script de build](#pourquoi-un-script-de-build) plus bas.

```bash
# depuis la racine du dépôt
./docker/build.sh          # construit l'image cms-web:local
docker compose up -d       # démarre les 5 services
```

Aux démarrages suivants, tant que le `Dockerfile` n'a pas changé, `docker compose
up -d` suffit (pas besoin de rebuild).

| Service | URL / accès | Détails |
|---------|-------------|---------|
| Application **prod** | http://localhost:8080 | docroot `www/`, base `comitefetes` |
| Application **recette** | http://localhost:8081 | docroot `recette/www/`, base `comitefetesrecette` |
| **phpMyAdmin** | http://localhost:8082 | menu déroulant : *Production* / *Recette* |
| MySQL prod (accès direct) | `localhost:3307` | |
| MySQL recette (accès direct) | `localhost:3308` | |

### Identifiants base de données

| Rôle | Utilisateur | Mot de passe |
|------|-------------|--------------|
| Application | `cdf` | `cdf` |
| Administration (phpMyAdmin) | `root` | `root` |

> Ces identifiants ne servent qu'en local (conteneurs). Ils sont sans rapport
> avec les identifiants OVH.

## Fonctionnement

- Le **dossier courant est monté** dans les conteneurs web (`.:/var/www/html`) :
  toute modification de code est prise en compte immédiatement, sans rebuild.
- La même image PHP sert les deux environnements ; seule la racine web change
  (`APACHE_DOCUMENT_ROOT`).
- L'utilisateur Apache est aligné sur l'UID/GID de l'hôte (fichier `.env`) pour
  que **Smarty** puisse écrire dans `templates_c/` et `cache/`.
- Les fichiers `cgi-bin/config/config_general.php` (prod et recette) — ignorés
  par git — pointent en local vers `db-prod` / `db-recette`. Le bloc OVH réel y
  est conservé en commentaire.

## Bases de données

Voir [`initdb/README.md`](initdb/README.md) pour importer la structure et les
données (dépôt d'un export SQL dans `docker/initdb/prod` et `docker/initdb/recette`).

Tant que les bases sont vides, l'application se connecte mais les pages affichent
des erreurs d'accès aux tables : c'est attendu jusqu'à l'import du schéma.

## Commandes utiles

```bash
docker compose ps                 # état des services
docker compose logs -f web-prod   # logs Apache/PHP (prod)
docker compose down               # arrêt (conserve les données)
docker compose down -v            # arrêt + suppression des bases locales
./docker/build.sh                 # reconstruit l'image après modif du Dockerfile
```

## Pourquoi un script de build

Trois particularités ont dû être contournées ; elles expliquent le workflow.

1. **BuildKit ne résout pas l'image de base.** `php:7.2-apache` est une vieille
   image ; sur certains réseaux, BuildKit (activé par défaut dans `docker compose
   build`) échoue avec un *timeout DNS* sur `registry-1.docker.io`, alors que le
   daemon Docker, lui, sait la télécharger. `docker/build.sh` force donc le
   **builder classique** (`DOCKER_BUILDKIT=0`). C'est pourquoi on **n'utilise pas
   `docker compose up --build`**. Les deux services web partagent la même image
   `cms-web:local` (ils ne diffèrent que par la variable `APACHE_DOCUMENT_ROOT`).

2. **Dépôts Debian Buster en fin de vie.** La base est Debian 10 « buster »,
   dont les miroirs renvoient 404. Le `Dockerfile` bascule apt sur
   `archive.debian.org` (voir commentaires dans le `Dockerfile`).

3. **Redirection HTTPS de la recette.** `recette/www/.htaccess` force le HTTPS
   (utile en prod, gênant en local HTTP). L'image configure Apache pour
   **ignorer les `.htaccess`** (`AllowOverride None`) sur les deux docroots.

## Dépannage

- **`docker compose build` échoue (timeout DNS sur registry-1.docker.io)** :
  c'est le point 1 ci-dessus. Utiliser `./docker/build.sh`.
- **Ports déjà utilisés** : ajuster `WEB_PROD_PORT`, `WEB_RECETTE_PORT`,
  `PMA_PORT`, `DB_*_PORT` dans `.env`.
- **Changement de machine** (droits sur les fichiers) : relancer
  `./docker/build.sh` (il lit `id -u`/`id -g` automatiquement).
- **Erreur d'écriture Smarty** : l'utilisateur Apache de l'image doit correspondre
  au propriétaire des fichiers → reconstruire avec `./docker/build.sh`.
- **La recette redirige encore vers `https://`** : l'image n'est pas à jour,
  reconstruire avec `./docker/build.sh`.
