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

Les scripts ne se rejouent **que sur un volume vide**. Pour repartir de zéro et
forcer un nouvel import :

```bash
# ⚠️ supprime les données de la base locale (pas la base OVH)
docker compose down -v
docker compose up -d --build
```

> Astuce : on peut aussi importer un dump à chaud via phpMyAdmin
> (http://localhost:8082) sans passer par ce dossier.
