# Changelog

Ce qui est livré. Ce qui est prévu est dans [`docs/roadmap.md`](docs/roadmap.md).

## En développement

- Passage à **PHP 8.5** (`.ovhconfig`, image Docker locale, CI).
- Smarty 3.1.32 → **4.5.8** et TCPDF 6.4.1 → **6.11.4**, compatibles PHP 8.5.
- Accents des PDF restaurés : ils étaient devenus `�` lors de la conversion des sources en UTF-8 ; `utf8_encode()` (déprécié) supprimé.
- `date(m)` corrigé en `date("m")` dans la fiche adhérent (erreur fatale en PHP 8).
- PDO reste en mode d'erreur silencieux, comme en PHP 7, au lieu de lever des exceptions.
- Jeu de caractères de la connexion passé dans le DSN (`PDO::MYSQL_ATTR_INIT_COMMAND` est déprécié en 8.5).
- php-cs-fixer 3.13.0 → **3.95.27** (compatible PHP 8.5).
