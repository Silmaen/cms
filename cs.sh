#!/usr/bin/env bash
#
# Mise en forme du code PHP (php-cs-fixer), DANS le conteneur web (PHP 7.4).
#
# php-cs-fixer est utilisé en PHAR (pas de Composer). Le binaire est téléchargé au
# premier lancement dans tests/.bin/ (ignoré par git). Le conteneur doit tourner
# (docker compose up -d, ou ./dc.sh up).
#
# Règles : .php-cs-fixer.dist.php (indentation tabs + nettoyage des espaces).
#
#   ./cs.sh              # corrige les fichiers
#   ./cs.sh --dry-run    # vérifie sans modifier (ce que fait la CI)
#   ./cs.sh --dry-run --diff   # + affiche les écarts
#
set -euo pipefail
cd "$(dirname "$0")"

# UID/GID de l'hôte → les fichiers réécrits restent la propriété de l'utilisateur.
USER_SPEC="$(id -u):$(id -g)"

# Compose lit .env.defaults puis .env (surcharges machine).
ENV_FILES=".env.defaults"
[ -f .env ] && ENV_FILES=".env.defaults,.env"
export COMPOSE_ENV_FILES="$ENV_FILES"

# Version de l'outil : source = .env.defaults (surchargeable par .env).
VERSION="$(grep -hE '^PHP_CS_FIXER_VERSION=' .env .env.defaults 2>/dev/null | head -1 | cut -d= -f2 | tr -d '[:space:]')"
VERSION="${VERSION:-3.13.0}"

mkdir -p tests/.bin
if [ ! -f tests/.bin/php-cs-fixer.phar ]; then
    echo "Téléchargement de php-cs-fixer ${VERSION} (phar)..."
    docker compose exec -T -u "$USER_SPEC" web php -r \
        "copy('https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/releases/download/v${VERSION}/php-cs-fixer.phar', '/var/www/html/tests/.bin/php-cs-fixer.phar') or exit(1);"
fi

exec docker compose exec -T -u "$USER_SPEC" web \
    php /var/www/html/tests/.bin/php-cs-fixer.phar fix \
    --config /var/www/html/.php-cs-fixer.dist.php "$@"
