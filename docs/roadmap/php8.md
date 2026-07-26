# Compatibilité PHP 8

## Contexte

L'hébergement OVH est sur le container `stable64` → **plancher PHP 7.4** (voir
[environnements-et-deploiement.md](../environnements-et-deploiement.md#6-ovhconfig-et-les-containers-php)).
L'appli tourne aujourd'hui en **7.4**. Objectif : la rendre sûre en **8.x** pour ne pas
rester bloqué quand OVH retirera 7.4.

## Points de rupture 7.4 → 8.x à vérifier

Code procédural ancien → surtout :

- `each()` **supprimé** en 8.0, `create_function()` supprimé.
- Accès chaîne par accolades `$s{0}` **supprimé** (utiliser `$s[0]`).
- Comparaisons non strictes : `0 == "texte"` vaut désormais **false** (peut changer des `if`).
- Clé de tableau / variable indéfinie → *warning* (8.0) voire plus strict — beaucoup de
  `$_GET['x']` non testés à sécuriser avec `isset()` / `??`.
- Changements sur `implode()` (ordre des arguments), `FILTER_*`, arguments de certaines
  fonctions désormais obligatoires.

## Démarche proposée

1. Ajouter un **linter de compat** (ex. `phpcs` + `PHPCompatibility`) exécuté dans Docker.
2. Bumper l'image Docker locale (actuellement `php:7.4`) vers 8.x pour tester.
3. Basculer **`test`** en 8.x (changer `app.engine.version` dans son `.ovhconfig`, garder
   `container.image=stable64`) et parcourir les écrans, corriger les erreurs/warnings.
4. Une fois vert, propager `test` → `recette` → `main`.
