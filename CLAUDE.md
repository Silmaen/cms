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
- **Un seul code, plusieurs environnements** : il n'y a plus de dossier `recette/`
  dupliqué. Le même code (`www/` + `cgi-bin/`) est déployé sur trois branches git
  (`main`→prod, `recette`→recette, `test`→test) plus le Docker local.
  L'environnement est **détecté automatiquement** (`cgi-bin/config/environnement.php`
  via `CDF_ENV` ou le nom d'hôte) et pilote la couleur du thème (vert/bleu/rouge/violet)
  et le bandeau. Ne pas ré-introduire de duplication de dossier.
- **Soft-delete** : ne jamais `DELETE` une ligne métier ; utiliser `id_etat`
  (1=actif, 2=archivé, 3=supprimé) via `gestionSuppression()`.
- **Secrets BDD hors git** : `config_general.php` est désormais versionné et **sans
  secret** ; les identifiants viennent des variables d'environnement `CDF_DB_*`
  (local/Docker) ou du fichier hors-git `config_secrets.php` par serveur (OVH),
  résolus dans `cgi-bin/config/identifiants_bdd.php`. Ne jamais figer d'identifiant
  dans un fichier versionné.
- **Style** : suivre le code existant (procédural, noms de fonctions
  `GestionXxx` en PascalCase, variables et SQL en français). Ne pas « moderniser »
  spontanément (framework, POO) sans demande explicite.
- **Roadmap** : les chantiers à venir sont listés dans `docs/roadmap.md`, qui doit
  rester **concis** (une ligne par chantier), **groupé par thème**, chaque chantier
  portant un **badge de priorité** (🔴 P1 → ⚪ P4). Un chantier qui demande des détails a
  un document dédié dans `docs/roadmap/`, lié depuis sa ligne. Respecter cette convention
  pour tout ajout à la roadmap.

## Documentation détaillée

Lire au besoin dans `.claude/docs/` :

- [`architecture.md`](.claude/docs/architecture.md) — flux et patterns.
- [`base-de-donnees.md`](.claude/docs/base-de-donnees.md) — schéma des tables.
- [`domaine-metier.md`](.claude/docs/domaine-metier.md) — vocabulaire métier.
- [`conventions.md`](.claude/docs/conventions.md) — conventions de code.
