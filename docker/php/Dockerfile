# Image PHP 7.4 + Apache pour l'application Comité des Fêtes.
# Reproduit l'environnement OVH (PHP 7.4 sur container « stable64 », MySQL) en local.
FROM php:7.4-apache

# --- Extensions PHP ---
# Installées via docker-php-extension-installer (mlocati) : il gère les dépendances
# système et les dépôts Debian (y compris distributions en fin de vie), ce qui évite
# les bricolages apt/gd manuels — notamment la syntaxe de gd qui a changé en 7.4.
#   pdo_mysql : connexion à la base (PDO).
#   gd + mbstring : requis par TCPDF pour la génération des PDF.
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions pdo_mysql gd mbstring

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
