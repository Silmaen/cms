# Migration vers un backend plus moderne

> **Statut : exploratoire / long terme.** Rien n'est engagé. Ce document consigne
> l'analyse pour pouvoir décider plus tard en connaissance de cause. Prix et offres
> relevés en **juillet 2026** — à revérifier au moment d'agir.

## Principe directeur : découpler les trois décisions

La « modernisation » recouvre **trois chantiers indépendants** qu'il ne faut surtout
**pas mener en même temps** (mélanger les sources de risque rend le débogage impossible) :

1. **Hébergement** — mutualisé OVH → VPS que l'on administre (débloque Docker, root, cron réel).
2. **Moteur web** — PHP procédural → éventuellement Python/Django.
3. **Base de données** — MySQL → PostgreSQL ou SQLite.

On peut faire (1) **sans** (2) ni (3) : c'est même l'ordre recommandé. La zone DNS OVH
est découplée de l'hébergement — migrer = repointer un enregistrement `A`, sans toucher
aux MX ni au reste.

---

## Axe 1 — Hébergement : mutualisé → VPS

Le mutualisé « Perso » actuel ne donnera jamais Docker ni un vrai contrôle. Puisqu'on
sait administrer un serveur, le **VPS OVH** est le point d'équilibre.

### Comparaison des coûts (prix HT/mois, ~+20 % TTC)

| Offre | vCore | RAM | SSD NVMe | Prix HT /mois | Prix HT /an |
|-------|-------|-----|----------|---------------|-------------|
| **Perso** (actuel, tarif de renouvellement) | — | — | 100 Go | 5,99 € | 71,88 € |
| **VPS-1** | 2 | 4 Go | 40 Go | 3,81 € | 45,72 € |
| **VPS-2** | 4 | 8 Go | 75 Go | 7,21 € | 86,52 € |

- **Le VPS-1 est moins cher que le Perso actuel** (~26 € HT/an d'économie) tout en
  apportant Docker + root. La migration n'est donc pas un surcoût.
- Sauvegarde quotidienne automatique et anti-DDoS **inclus** sur les VPS.

### Dimensionnement : le VPS-1 suffit largement

Charge attendue au repos (ordre de grandeur) : MySQL ~300–500 Mo + PHP-FPM ~150 Mo +
reverse-proxy ~50 Mo + OS/Docker ~500 Mo ≈ **1,5 Go** sur les 4 Go du VPS-1. Reste ~2,5 Go
de marge, de quoi ajouter plus tard un conteneur Django sans changer d'offre.

Passer au **VPS-2** seulement si l'on héberge d'autres services sur la machine, ou pendant
une phase de bascule où PHP **et** Django (et deux moteurs de base) tournent en parallèle.
OVH permet l'upgrade à ce moment-là — inutile de surpayer aujourd'hui.

### Coût caché

Le VPS transfère la charge d'**administration système** (MAJ OS, TLS via Let's Encrypt /
Caddy / Traefik, supervision, sauvegardes) que le mutualisé prenait en charge. Acceptable
pour un administrateur, mais ce n'est pas « gratuit ».

### Ce qui se déploie tel quel

Le `docker-compose.yml` et `docker/php/Dockerfile` déjà présents tournent sur le VPS :
**l'appli PHP actuelle fonctionne à l'identique**, déployée par `git push` (dépôt bare +
hook `post-receive`) ou `docker compose up`. Le cron
(`cgi-bin/reservations_maj_automatique.php`) devient un vrai `crontab`.

---

## Axe 2 — Moteur web : PHP → (éventuellement) Python/Django

Passer à Django n'est **pas une migration mais un rewrite complet** :

- réimplémenter tous les contrôleurs `www/*.php` et les vues Smarty `*.tpl` ;
- refaire la génération PDF (aujourd'hui TCPDF, cf. [architecture.md](../architecture.md#pdf)) ;
- migrer/adapter le schéma (cf. axe 3) et retester tout le métier (adhésions,
  réservations, soft-delete, droits…).

Pour une appli maintenue bénévolement, c'est plusieurs semaines et une période où **deux
codebases coexistent**. Le manque d'appétence pour PHP est une raison valable *à terme*,
mais ce chantier **ne doit pas bloquer** la modernisation d'infra (axe 1).

**Si on y va un jour :** après la migration VPS, de façon **incrémentale** — nouvelle appli
Django derrière le même reverse-proxy, bascule écran par écran plutôt qu'un big-bang.

> Note : voir aussi le chantier [PHP 8](php8.md). Rendre l'appli sûre en PHP 8.x est un
> objectif à **court terme** (indépendant et bien moins coûteux) ; le rewrite Django est
> une alternative de **long terme**. Les deux ne se cumulent pas : si Django aboutit, PHP 8
> devient sans objet — mais on ne parie pas là-dessus tant que Django n'est pas engagé.

---

## Axe 3 — Base de données : MySQL → ?

### Candidats réalistes à notre échelle

(quelques utilisateurs, base de quelques milliers de lignes, peu d'écritures concurrentes)

| Critère | MySQL / MariaDB | PostgreSQL | SQLite |
|---------|-----------------|------------|--------|
| Coût de migration depuis l'existant | **Nul** | Moyen | Moyen |
| Serveur à administrer | Oui (conteneur) | Oui (conteneur) | **Non** (un fichier) |
| Sauvegarde | dump SQL | dump SQL | **copie du fichier** |
| Écritures concurrentes | Bonnes | Excellentes | Sérialisées (OK à faible charge) |
| Écosystème Python/Django | Bon | **Référence** | Bon |
| Rigueur / intégrité | Correcte (tolérante) | **Très forte** | Basique |
| Adapté à notre échelle | ✅ | ✅ (un peu surdimensionné) | ✅ (voire idéal) |

- **MariaDB** = fork libre de MySQL, « drop-in », transparent pour le code PDO. C'est ce
  qu'on lance en conteneur plutôt que MySQL Oracle.
- **PostgreSQL** : standard du monde Django. Plus strict (refuse les données incohérentes
  que MySQL tolère), `JSONB` indexable, extensions (PostGIS, plein-texte), transactions
  y compris sur le DDL. Gain réel **seulement** si on réécrit la couche d'accès.
- **SQLite** : le candidat « anti-ops ». Un seul fichier, aucune base à administrer,
  sauvegarde = `cp`. Écritures sérialisées invisibles en dessous de quelques utilisateurs.

### Décision calée sur les phases

- **Tant qu'on est en PHP : rester sur MariaDB.** Aucune raison de bouger ; un `mysqldump`
  → restauration dans un conteneur, et l'appli fonctionne à l'identique. Ne pas changer de
  moteur en même temps que d'hébergement.
- **Si rewrite Django : c'est LE moment de choisir.** On réécrit déjà toutes les requêtes,
  donc le surcoût de changer de moteur est quasi nul.
  - **PostgreSQL** si l'on veut robustesse + marge de croissance + écosystème Django natif.
  - **SQLite** si la charge reste ce qu'elle est et qu'on veut supprimer un service à
    administrer (Django permet de démarrer en SQLite puis basculer sur Postgres plus tard).

### Outils d'administration (équivalents phpMyAdmin)

Aujourd'hui : **phpMyAdmin** (conteneur dans `docker-compose.yml`). Côté Postgres / multi-moteurs :

| Outil | Type | Remarque |
|-------|------|----------|
| **Adminer** ⭐ | **1 fichier PHP**, web | Multi-bases : MySQL/MariaDB **+** PostgreSQL **+** SQLite dans la même interface. Idéal pendant une migration (deux moteurs en parallèle), esprit phpMyAdmin en plus léger. |
| pgAdmin 4 | web, dédié Postgres | L'équivalent direct de phpMyAdmin pour Postgres, très complet. |
| DBeaver | client lourd (bureau) | Gratuit, universel, pour du travail sérieux. |
| Onglet *Database* de PyCharm | client lourd (IDE) | Déjà disponible (moteur DataGrip) : MySQL/Postgres/SQLite, rien à installer. |

**Reco outillage** : pendant la bascule, **Adminer** (un conteneur, gère les deux moteurs
à la fois) + l'onglet *Database* de PyCharm au quotidien. pgAdmin en réserve si l'on veut
une console web 100 % dédiée Postgres.

---

## Séquencement proposé

1. **Court terme, concret** — migrer l'hébergement vers un **VPS-1**, PHP + **MariaDB**
   inchangés, déploiement Git/Docker. Bénéfice immédiat, risque faible, coût égal ou moindre.
2. **Court/moyen terme, indépendant** — sécuriser l'appli en **PHP 8** (cf. [php8.md](php8.md)).
3. **Long terme, hypothétique** — *si* la volonté persiste, **rewrite Django incrémental**,
   et **à ce moment-là seulement** trancher **PostgreSQL vs SQLite**.

## Points de vigilance

- Les prix VPS peuvent aussi avoir un tarif promo 1ʳᵉ année + renouvellement plus élevé —
  vérifier sur le devis.
- Ne jamais migrer hébergement **et** moteur **et** base en une seule étape.
- Conserver la stratégie de secrets hors-git (`CDF_DB_*` / `config_secrets.php`, cf.
  [environnements-et-deploiement.md](../environnements-et-deploiement.md)) quel que soit le moteur.
