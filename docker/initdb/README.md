# Import initial des bases de données

Les fichiers déposés ici sont **exécutés automatiquement à la première création**
de la base (volume vide), par ordre alphabétique. Formats acceptés : `.sql`,
`.sql.gz`, `.sh`.

| Dossier | Base cible | Service |
|---------|------------|---------|
| `prod/` | `comitefetes` | `db-prod` |
| `recette/` | `comitefetesrecette` | `db-recette` |

## Comment procéder

1. Placer l'export de la base de production dans `prod/` (ex. `prod/01-structure.sql`,
   `prod/02-donnees.sql`) et celui de la recette dans `recette/`.
   - Pas besoin d'ajouter `CREATE DATABASE` / `USE` : le script s'exécute déjà
     dans la bonne base (celle définie par `MYSQL_DATABASE`).
2. Démarrer (ou recréer) l'environnement :
   ```bash
   docker compose up -d --build
   ```

## Réimporter après avoir ajouté/modifié un fichier

Les scripts ne se rejouent **que sur un volume vide**. Pour repartir de zéro et
forcer un nouvel import :

```bash
# ⚠️ supprime les données des DEUX bases locales (pas les bases OVH)
docker compose down -v
docker compose up -d --build
```

Pour ne réinitialiser qu'une seule base :

```bash
docker compose rm -sfv db-prod
docker volume rm cms_db-prod-data
docker compose up -d db-prod
```

> Astuce : on peut aussi importer un dump à chaud via phpMyAdmin
> (http://localhost:8082) sans passer par ces dossiers.
