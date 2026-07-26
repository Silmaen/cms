# Hachage des mots de passe

## État actuel

`GestionHashage()` (dans `cgi-bin/config/fonctions_general.php`) fait :

```
empreinte = préfixe_statique + sha256(mot_de_passe) + suffixe_statique
```

Les mots de passe des utilisateurs sont stockés ainsi dans `admin_utilisateurs.mdp_utilisateur`.

**Faiblesses :**

- **Pas de sel par utilisateur** : deux utilisateurs avec le même mot de passe ont la
  même empreinte (le préfixe/suffixe est un *pepper* commun, pas un sel).
- **SHA-256 est un hash rapide** → mal adapté aux mots de passe (attaques par force brute
  massivement parallélisables).
- Le *pepper* est **codé en dur** dans le code versionné.

## Cible

Utiliser les fonctions natives PHP : **`password_hash()`** (bcrypt, ou Argon2 en 8.x) et
**`password_verify()`**. Elles gèrent un **sel aléatoire par mot de passe** et un coût
réglable. La colonne `mdp_utilisateur varchar(200)` est assez large.

## Migration (sans connaître les mots de passe en clair)

On ne peut pas ré-hacher les empreintes existantes directement. On migre **à la volée, à
la prochaine connexion réussie** :

1. À la connexion, si l'empreinte est à l'**ancien** format → vérifier avec `GestionHashage()`.
2. Si OK → recalculer avec `password_hash()` et **remplacer** l'empreinte stockée.
3. Sinon (nouveau format) → `password_verify()`.
4. Détecter le format via `password_get_info()` (ou un préfixe reconnaissable).

Une fois tous les comptes migrés (ou après un délai + reset des inactifs), retirer l'ancien
chemin. À combiner avec un écran « mot de passe oublié » déjà présent pour les cas bloqués.
