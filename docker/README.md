# Environnement de développement Docker

Environnement local reproduisant l'hébergement OVH (PHP 7.4 + MySQL) : **un site
unique** (comme en production), servi par un seul conteneur web sur sa base de
données, plus un **phpMyAdmin** pour la consulter.

L'environnement applicatif rendu (couleur du thème + bandeau) est piloté par la
variable **`CDF_ENV`** (voir `.env`). Par défaut `local` → thème violet + badge
« LOCAL ». On peut la passer à `test` / `recette` / `prod` pour prévisualiser un
thème sans changer de code ni de base.

## Démarrage

L'image web se construit avec un **script dédié** (et non `docker compose build`) —
voir [Pourquoi un script de build](#pourquoi-un-script-de-build) plus bas.

```bash
# depuis la racine du dépôt
./docker/build.sh          # construit l'image cms-web:local
docker compose up -d       # démarre les services
```

Aux démarrages suivants, tant que le `Dockerfile` n'a pas changé, `docker compose
up -d` suffit (pas besoin de rebuild).

| Service              | URL / accès           | Détails                            |
|----------------------|-----------------------|------------------------------------|
| Application          | http://localhost:8080 | docroot `www/`, base `comitefetes` |
| **phpMyAdmin**       | http://localhost:8082 | serveur `db`                       |
| MySQL (accès direct) | `localhost:3307`      |                                    |

### Identifiants base de données

| Rôle                        | Utilisateur  | Mot de passe  |
|-----------------------------|--------------|---------------|
| Application                 | `cdf`        | `cdf`         |
| Administration (phpMyAdmin) | `root`       | `root`        |

> Ces identifiants ne servent qu'en local (conteneurs). Ils sont sans rapport
> avec les identifiants OVH.

## Fonctionnement

- Le **dossier courant est monté** dans le conteneur web (`.:/var/www/html`) :
  toute modification de code est prise en compte immédiatement, sans rebuild.
- L'utilisateur Apache est aligné sur l'UID/GID de l'hôte (fichier `.env`) pour
  que **Smarty** puisse écrire dans `templates_c/` et `cache/`.
- **Aucun `config_secrets.php` n'est nécessaire en local** : les identifiants BDD
  sont injectés par `docker-compose.yml` via les variables `CDF_DB_*`, lues par
  `cgi-bin/config/identifiants_bdd.php`. Sur OVH, c'est le fichier hors-git
  `config_secrets.php` qui prend le relais (voir `config_secrets.php.dist`).

## Base de données

Voir [`initdb/README.md`](initdb/README.md) pour importer la structure et les
données (dépôt d'un export SQL dans `docker/initdb/`).

Tant que la base est vide, l'application se connecte, mais les pages affichent
des erreurs d'accès aux tables : c'est attendu jusqu'à l'import du schéma.

## Commandes utiles

```bash
docker compose ps            # état des services
docker compose logs -f web   # logs Apache/PHP
docker compose down          # arrêt (conserve les données)
docker compose down -v       # arrêt + suppression de la base locale
./docker/build.sh            # reconstruit l'image après modif du Dockerfile
```

## Pourquoi un script de build

Deux particularités ont dû être contournées ; elles expliquent le workflow.

1. **BuildKit ne résout pas l'image de base.** `php:7.4-apache` est une vieille
   image ; sur certains réseaux, BuildKit (activé par défaut dans `docker compose
   build`) échoue avec un *timeout DNS* sur `registry-1.docker.io`, alors que le
   daemon Docker, lui, sait la télécharger. `docker/build.sh` force donc le
   **builder classique** (`DOCKER_BUILDKIT=0`). C'est pourquoi on **n'utilise pas
   `docker compose up --build`**.

2. **Dépôts Debian Buster en fin de vie.** La base est Debian 10 « buster »,
   dont les miroirs renvoient 404. Le `Dockerfile` bascule apt sur
   `archive.debian.org` (voir commentaires dans le `Dockerfile`).

## Dépannage

- **`docker compose build` échoue (timeout DNS sur registry-1.docker.io)** :
  c'est le point 1 ci-dessus. Utiliser `./docker/build.sh`.
- **Ports déjà utilisés** : ajuster `WEB_PORT`, `PMA_PORT`, `DB_PORT` dans `.env`.
- **Changement de machine** (droits sur les fichiers) : relancer
  `./docker/build.sh` (il lit `id -u`/`id -g` automatiquement).
- **Erreur d'écriture Smarty** : l'utilisateur Apache de l'image doit correspondre
  au propriétaire des fichiers → reconstruire avec `./docker/build.sh`.
