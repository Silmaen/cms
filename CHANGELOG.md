# Changelog

Ce qui est livré. Ce qui est prévu est dans [`docs/roadmap.md`](docs/roadmap.md).

## En développement

- Formulaire article : l'en-tête n'est plus affiché en double (test de non-régression sur tous les templates).
- Console du navigateur assainie : police Font Awesome 4.7 aux boîtes de glyphes recalculées (avertissement « Glyph bbox was incorrect »), référence à une carte de sources absente retirée de `popper.min.js` (404).
- **Interface « moderne »** au choix dans « Mes préférences » (barre claire, tableaux et formulaires en cartes, pagination en pastilles), compatible thème sombre ; l'interface classique reste celle par défaut.
- Interface moderne : icônes au trait fin (Bootstrap Icons, servies en local) et palette de boutons harmonisée, avec boutons d'action teintés dans les listes.
- Interface moderne : boutons d'action des listes sur une seule ligne, en trois groupes (consultation/édition, état, suppression à part).
- Interface moderne : barre de filtres des listes en barre d'outils (recherche avec loupe, filtres en pastilles entièrement cliquables, bouton « Ajouter » libellé).
- Interface moderne : formulaires avec libellés au-dessus des champs et barre Annuler / Enregistrer collée en bas d'écran ; en-tête de page (fil d'ariane + titre à gauche, message en pastille) ; tri, info-bulles et boîtes de confirmation modernisés.
- **Thème sombre** au choix dans « Mes préférences » (clair, sombre, ou automatique selon l'appareil) ; le thème clair est inchangé.
- Écran **« Mes préférences »** (icône ⚙ de la barre) : thème et nombre de lignes par page, appliqué à toutes les listes.
- Mise à jour du schéma de la base **à chaud** par migrations numérotées : une sauvegarde ancienne restaurée se remet à niveau seule.
- Passage à **PHP 8.5** (`.ovhconfig`, image Docker locale, CI).
- Smarty 3.1.32 → **4.5.8** et TCPDF 6.4.1 → **6.11.4**, compatibles PHP 8.5.
- Accents des PDF restaurés : ils étaient devenus `�` lors de la conversion des sources en UTF-8 ; `utf8_encode()` (déprécié) supprimé.
- `date(m)` corrigé en `date("m")` dans la fiche adhérent (erreur fatale en PHP 8).
- PDO reste en mode d'erreur silencieux, comme en PHP 7, au lieu de lever des exceptions.
- Jeu de caractères de la connexion passé dans le DSN (`PDO::MYSQL_ATTR_INIT_COMMAND` est déprécié en 8.5).
- php-cs-fixer 3.13.0 → **3.95.27** (compatible PHP 8.5).
