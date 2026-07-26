#!/usr/bin/env bash
#
# Lance la suite de tests PHPUnit DANS le conteneur web (PHP 7.4, comme la prod).
#
# PHPUnit est utilisé en PHAR (pas de Composer). Le binaire est téléchargé au
# premier lancement dans tests/.bin/ (ignoré par git). Le conteneur doit tourner
# (docker compose up -d).
#
#   ./tests/run.sh                 # toute la suite
#   ./tests/run.sh --filter Date   # filtrer par nom de test
#
set -euo pipefail
cd "$(dirname "$0")/.."

# On exécute avec l'UID/GID de l'hôte (et non root) pour que les fichiers écrits
# (snapshots, phar, templates compilés) restent la propriété de l'utilisateur.
USER_SPEC="$(id -u):$(id -g)"

mkdir -p tests/.bin
if [ ! -f tests/.bin/phpunit.phar ]; then
    echo "Téléchargement de PHPUnit 9 (phar)..."
    docker compose exec -T -u "$USER_SPEC" web php -r \
        'copy("https://phar.phpunit.de/phpunit-9.phar", "/var/www/html/tests/.bin/phpunit.phar") or exit(1);'
fi

exec docker compose exec -T -u "$USER_SPEC" web \
    php /var/www/html/tests/.bin/phpunit.phar \
    --configuration /var/www/html/phpunit.xml "$@"
