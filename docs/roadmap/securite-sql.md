# Sécurité SQL / injection

## État actuel

Le code utilise **majoritairement des requêtes préparées PDO**
(`$sql = $connexion->prepare(...)` puis `$sql->execute([":param" => $valeur])`). Un
premier passage n'a **pas** trouvé de concaténation directe de `$_GET` / `$_POST` dans une
chaîne SQL — c'est plutôt bon signe. Ce chantier est donc un **audit de confirmation** et
une **standardisation**, pas la correction d'une faille connue.

## Points de vigilance

- **Valeurs** → toujours passées en **paramètres liés** (`:param`), jamais concaténées.
  Vérifier les endroits qui concatènent encore des variables de **session** ou calculées.
- **Identifiants dynamiques** (nom de colonne, `ORDER BY`, sens de tri, nom de table) →
  **ne peuvent pas** être liés en paramètre. Ils apparaissent notamment dans le tri
  (`GestionTri`) : les valider contre une **liste blanche** (jamais injecter la valeur brute).
- `query()` / `exec()` avec chaîne construite → à passer en `prepare()` dès qu'une variable
  intervient.

## Démarche proposée

1. Recenser toutes les requêtes (`grep` de `query(`, `exec(`, `prepare(`) et repérer les
   concaténations restantes.
2. Convertir toute valeur variable en paramètre lié.
3. Encadrer les identifiants dynamiques par des listes blanches (colonnes triables connues).
4. Idéalement, ajouter un contrôle automatique (revue ou lint) pour éviter les régressions.
