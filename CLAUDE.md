# Instructions projet — Comité des Fêtes (CMS)

> Toute la communication, le code, les commentaires et la documentation de ce
> dépôt sont **en français**. La base de données et l'interface le sont aussi.

## Ce qu'est ce projet

Application web « maison » de gestion pour le Comité des Fêtes de Genay :
clients/adhérents, adhésions annuelles, articles, inventaire et **réservations
de matériel**, avec édition PDF. PHP 7.2 procédural + Smarty + MySQL (PDO),
hébergée sur mutualisé OVH.

## Repères rapides

| Élément | Emplacement |
|---------|-------------|
| Contrôleurs (une page = un écran) | `www/*.php` |
| Vues (Smarty, délimiteurs `<!--{ }-->`) | `www/templates/*.tpl` |
| Connexion BDD + init Smarty | `cgi-bin/config/config_general.php` |
| Fonctions transverses (auth, menu, tri…) | `cgi-bin/config/fonctions_general.php` |
| Tâche planifiée | `cgi-bin/reservations_maj_automatique.php` |
| Environnement de recette (copie complète) | `recette/` |

## Règles de travail

- **Docker/outillage** : ce projet n'a pas de conteneur ; c'est du PHP interprété.
  Pas de build. Pour exécuter/tester, utiliser un PHP 7.2 + MySQL (voir README).
- **Pattern d'un écran** : `require config_general + fonctions_general` →
  `gestionIdentification($connexion)` (garde, sinon redirige vers `index.php`) →
  lecture `$_GET`/`$_POST` (`action`, `id_*`) → SQL PDO → `$smarty->assign(...)` →
  `$smarty->display('xxx.tpl')`. **Respecter ce squelette** pour tout nouvel écran.
- **Prod ET recette** : le code est dupliqué dans `www/` et `recette/www/`. Une
  correction fonctionnelle doit en principe être portée **dans les deux**
  (le signaler si tu n'en modifies qu'un).
- **Soft-delete** : ne jamais `DELETE` une ligne métier ; utiliser `id_etat`
  (1=actif, 2=archivé, 3=supprimé) via `gestionSuppression()`.
- **Ne pas committer les secrets** : `config_general.php` et le cron contiennent
  des identifiants BDD. Voir la section sécurité du README ; proposer
  l'externalisation plutôt que de les figer.
- **Style** : suivre le code existant (procédural, noms de fonctions
  `GestionXxx` en PascalCase, variables et SQL en français). Ne pas « moderniser »
  spontanément (framework, POO) sans demande explicite.

## Documentation détaillée

Lire au besoin dans `.claude/docs/` :

- [`architecture.md`](.claude/docs/architecture.md) — flux et patterns.
- [`base-de-donnees.md`](.claude/docs/base-de-donnees.md) — schéma des tables.
- [`domaine-metier.md`](.claude/docs/domaine-metier.md) — vocabulaire métier.
- [`conventions.md`](.claude/docs/conventions.md) — conventions de code.
