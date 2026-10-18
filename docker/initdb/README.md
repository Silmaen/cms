# Import initial de la base de données

Les fichiers déposés ici sont **exécutés automatiquement à la première création**
de la base (volume vide), par ordre alphabétique. Formats acceptés : `.sql`,
`.sql.gz`, `.sh`. Ils s'exécutent dans la base `comitefetes` (définie par
`MYSQL_DATABASE` dans `docker-compose.yml`).

> Les `.sql` déposés ici sont **ignorés par git** (données personnelles / RGPD).

## Comment procéder

1. Placer l'export de la base dans ce dossier (ex. `01-structure.sql`,
   `02-donnees.sql`).
   - Pas besoin d'ajouter `CREATE DATABASE` / `USE` : le script s'exécute déjà
     dans la bonne base.
2. Démarrer (ou recréer) l'environnement :
   ```bash
   docker compose up -d --build
   ```

## Réimporter après avoir ajouté/modifié un fichier

Les scripts ne se rejouent **que sur un dossier de données vide** (`docker/data/`, monté
en dossier et non en volume : `docker compose down -v` ne l'efface donc pas). Le plus
simple est de réimporter à chaud, stack démarrée :

```bash
# ⚠️ remplace les données de la base locale (pas la base OVH ; la base de tests est conservée)
./dc.sh exec -T db mysql -uroot -proot -e "DROP DATABASE comitefetes; CREATE DATABASE comitefetes; GRANT ALL ON comitefetes.* TO 'cdf'@'%';"
./dc.sh exec -T db mysql -uroot -proot comitefetes < docker/initdb/cdfgenaytbbdd_mysql_db.sql
```

Un dump plus ancien que le code est remis à niveau tout seul à la première page ouverte
(migrations à chaud, voir `docs/base-de-donnees.md`).

> Astuce : on peut aussi importer un dump à chaud via phpMyAdmin
> (http://localhost:8082) sans passer par ce dossier.
