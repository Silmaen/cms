#!/usr/bin/env bash
#
# Wrapper « docker compose » du projet (à la racine, à côté de docker-compose.yml).
#
# Il positionne COMPOSE_ENV_FILES (empilement .env.defaults committé + .env machine)
# — sans lui, `docker compose` ne lit que .env et échoue sur les valeurs partagées
# (MYSQL_VERSION…). Puis il orchestre le cycle de vie de la stack locale.
#
#   ./dc.sh up             démarre la stack (construit l'image si absente ; si la
#                          stack tourne déjà, fait un `down` propre au préalable)
#   ./dc.sh down           arrête + supprime conteneurs et réseau (garde les données)
#   ./dc.sh down --clean   idem + nettoie les artefacts régénérables
#                          (templates compilés Smarty, cache, phar PHPUnit)
#   ./dc.sh <autre>        passe-plat vers `docker compose <autre>` (ps, logs…)
#
# Astuce : alias pratique →  alias dc='./dc.sh'
#
set -euo pipefail
cd "$(dirname "$0")"

# --- Source de vérité des variables : .env.defaults (committé) puis .env (machine) ---
ENV_FILES=".env.defaults"
[ -f .env ] && ENV_FILES=".env.defaults,.env"
export COMPOSE_ENV_FILES="$ENV_FILES"

nettoyer() {
    echo "Nettoyage des artefacts régénérables..."
    rm -rf www/templates_c/* www/cache/* 2>/dev/null || true
    rm -f tests/.bin/phpunit.phar 2>/dev/null || true
}

action="${1:-}"

case "$action" in
    up)
        # Image web absente → on la construit (build.sh contourne la limite BuildKit,
        # et lit la version de PHP dans .ovhconfig).
        if ! docker image inspect cms-web:local >/dev/null 2>&1; then
            echo "Image cms-web:local absente → construction..."
            ./docker/build.sh
        fi
        # Stack déjà active → on redescend d'abord pour repartir propre.
        if [ -n "$(docker compose ps -q 2>/dev/null)" ]; then
            echo "Stack déjà active → down préalable..."
            docker compose down
        fi
        docker compose up -d
        echo "Stack démarrée. Site : http://localhost:${WEB_PORT:-8080}"
        ;;
    down)
        docker compose down
        case "${2:-}" in
            --clean|clean) nettoyer ;;
        esac
        ;;
    "")
        echo "Usage : $0 {up | down [--clean] | <commande docker compose>}" >&2
        exit 2
        ;;
    *)
        # Passe-plat : toute autre commande est transmise à docker compose,
        # avec COMPOSE_ENV_FILES déjà positionné.
        exec docker compose "$@"
        ;;
esac
