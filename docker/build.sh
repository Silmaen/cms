#!/usr/bin/env bash
#
# Construit l'image web (cms-web:local) utilisée par le service web.
#
# Pourquoi ce script plutôt que `docker compose build` ?
# BuildKit (activé par défaut dans compose) échoue à résoudre le manifest de la
# vieille image de base `php:7.4-apache` sur certains réseaux (timeout DNS sur
# registry-1.docker.io), alors que le builder classique — qui passe par le daemon
# Docker — fonctionne. On force donc DOCKER_BUILDKIT=0, et on aligne l'utilisateur
# Apache sur l'utilisateur hôte (droits d'écriture pour Smarty).
#
set -euo pipefail
cd "$(dirname "$0")/.."

# Version de PHP : source de vérité UNIQUE = .ovhconfig (app.engine.version).
PHP_VERSION="$(grep -E '^app.engine.version=' .ovhconfig | head -1 | cut -d= -f2 | tr -d '[:space:]')"
PHP_VERSION="${PHP_VERSION:-7.4}"
echo "PHP $PHP_VERSION (depuis .ovhconfig)"

DOCKER_BUILDKIT=0 docker build \
    -t cms-web:local \
    --build-arg PHP_VERSION="$PHP_VERSION" \
    --build-arg UID="$(id -u)" \
    --build-arg GID="$(id -g)" \
    ./docker/php

echo
echo "Image 'cms-web:local' construite."
echo "Démarrez l'environnement avec :  docker compose up -d"
