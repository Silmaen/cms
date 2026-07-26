# Image PHP 7.4 + Apache pour l'application Comité des Fêtes.
# Reproduit l'environnement OVH (PHP 7.4, MySQL) en local.
FROM php:7.4-apache

# --- Dépôts Debian Buster (EOL) → archive.debian.org ---
# Debian 10 "buster" (base de php:7.4) est en fin de vie : les miroirs standard
# renvoient 404. On bascule apt sur les archives et on ignore la date d'expiration.
RUN sed -i \
        -e 's|deb.debian.org/debian|archive.debian.org/debian|g' \
        -e 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' \
        -e '/buster-updates/d' \
        /etc/apt/sources.list

# --- Extensions PHP ---
# pdo_mysql : connexion à la base (l'appli utilise PDO).
# gd + mbstring : requis par TCPDF pour la génération des PDF.
RUN apt-get -o Acquire::Check-Valid-Until=false update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql gd mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# --- Apache ---
# Racine web paramétrable par variable d'environnement (APACHE_DOCUMENT_ROOT).
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/www
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# ServerName (supprime l'avertissement FQDN) + on ignore les .htaccess en local
# (certains forcent le HTTPS, inutile et gênant en développement local sur HTTP).
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf \
    && printf '<Directory /var/www/html/www>\n    AllowOverride None\n</Directory>\n' \
        > /etc/apache2/conf-available/zzz-local-dev.conf \
    && a2enconf zzz-local-dev

# --- Droits sur les volumes montés ---
# On aligne l'utilisateur Apache (www-data) sur l'utilisateur hôte pour que
# Smarty puisse écrire dans templates_c/ et cache/ (dossiers montés depuis l'hôte).
ARG UID=1000
ARG GID=1000
RUN groupmod -o -g "${GID}" www-data \
    && usermod  -o -u "${UID}" www-data
