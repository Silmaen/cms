# Environnement de développement Docker

Environnement local reproduisant l'hébergement OVH (PHP 7.4 + MySQL) : **un site
unique** (comme en production), servi par un seul conteneur web sur sa base de
données, plus un **phpMyAdmin** pour la consulter.

L'environnement applicatif rendu (couleur du thème + bandeau) est piloté par la
variable **`CDF_ENV`** (voir `.env`). Par défaut `local` → thème violet + badge
« LOCAL ». On peut la passer à `test` / `recette` / `prod` pour prévisualiser un
thème sans changer de code ni de base.

## Configuration (fichiers d'environnement)

La configuration est répartie en deux fichiers, empilés par Compose :

- **`.env.defaults`** — *committé*, **source de vérité unique** des valeurs partagées :
  `CDF_ENV`, ports, `MYSQL_VERSION`, `PHPUNIT_VERSION`. *(La version de PHP, elle, vit
  dans `.ovhconfig`.)*
- **`.env`** — *gitignoré*, surcharges propres à ta machine (`UID`/`GID`, un port perso…).
  Il **écrase** `.env.defaults`. Au premier clone, pars du modèle committé :
  `cp .env.sample .env` (puis ajuste `UID`/`GID` avec `id -u` / `id -g`).

Compose ne lit que `.env` par défaut. Pour empiler les deux fichiers (le second
l'emportant), on passe par le wrapper **`./dc.sh`** (à la racine, à côté de
`docker-compose.yml`) : il positionne `COMPOSE_ENV_FILES=.env.defaults,.env` avant
chaque commande. `dc.sh` et `tests/run.sh` s'en chargent ; si tu appelles
`docker compose` **directement**, exporte `COMPOSE_ENV_FILES` toi-même (sinon
`MYSQL_VERSION` & co. manqueront).

## Démarrage

Le wrapper **`./dc.sh`** orchestre tout : il construit l'image au besoin (via le
script dédié — voir [Pourquoi un script de build](#pourquoi-un-script-de-build)) et
pose `COMPOSE_ENV_FILES`.

```bash
# depuis la racine du dépôt
cp .env.sample .env    # au premier clone seulement (puis ajuster UID/GID)
./dc.sh up             # construit l'image si besoin, puis démarre la stack
./dc.sh down           # arrête (garde les données)
./dc.sh down --clean   # arrête + nettoie les artefacts régénérables (Smarty, phar)
```

`./dc.sh up` refait automatiquement un `down` propre si la stack tourne déjà. Pour
forcer la reconstruction de l'image après modification du `Dockerfile` :
`./docker/build.sh`.

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
./dc.sh up                   # démarre (down auto si déjà lancée + build si besoin)
./dc.sh down                 # arrête (conserve les données)
./dc.sh down --clean         # arrête + nettoie les artefacts régénérables
./dc.sh ps                   # passe-plat → docker compose ps
./dc.sh logs -f web          # passe-plat → logs Apache/PHP
./docker/build.sh            # reconstruit l'image après modif du Dockerfile
```

> Les commandes `docker compose` directes fonctionnent aussi, mais **seulement** si
> `COMPOSE_ENV_FILES=.env.defaults,.env` est exporté ; le wrapper `./dc.sh` évite d'y penser.

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
