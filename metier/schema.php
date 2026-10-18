<?php
//
// Mise à jour du schéma de la base « à chaud ».
//
// La base peut être restaurée depuis une sauvegarde d'une version antérieure : le
// code la remet alors à niveau tout seul, à la requête suivante, sans script manuel.
//
//   - la table `admin_schema` mémorise la version du schéma de la base ;
//   - chaque évolution est une migration numérotée (SchemaMigrations()) ;
//   - à chaque requête, les migrations de numéro > version de la base sont appliquées.
//
// Une base sans `admin_schema` (sauvegarde antérieure à ce mécanisme) est en version 0 :
// tout est rejoué. Chaque migration doit donc être REJOUABLE : vérifier l'état réel
// (CREATE TABLE IF NOT EXISTS, information_schema…) avant d'agir.
//
// Pour faire évoluer le schéma : AJOUTER une migration en fin de liste, ne jamais
// modifier une migration existante.
//

/**
 * Liste ordonnée des migrations : numéro → nom de la fonction à appeler.
 *
 * @return array<int,string>
 */
function SchemaMigrations()
{
	return array(
		1 => 'SchemaMigration1PreferencesUtilisateurs',
	);
}

/**
 * Version du schéma attendue par le code (numéro de la dernière migration).
 *
 * @return int
 */
function SchemaVersionCode()
{
	return max(array_keys(SchemaMigrations()));
}

/**
 * Version du schéma de la base (0 si la table `admin_schema` n'existe pas encore).
 *
 * @return int
 */
function SchemaVersionBase($connexion)
{
	// Table absente : false en mode silencieux (production), exception sinon (tests).
	try {
		$resultat = $connexion->query("SELECT version FROM admin_schema WHERE id_schema=1");
	} catch (PDOException $e) {
		$resultat = false;
	}
	if (!$resultat) {
		return 0;
	}
	$version = $resultat->fetchColumn();
	return ($version === false) ? 0 : intval($version);
}

/**
 * Enregistre la version du schéma de la base (crée `admin_schema` au besoin).
 *
 * @return bool
 */
function SchemaVersionEnregistrer($connexion, $version)
{
	$creation = $connexion->exec("CREATE TABLE IF NOT EXISTS admin_schema (
		id_schema mediumint NOT NULL,
		version mediumint NOT NULL,
		date_modification datetime DEFAULT NULL,
		PRIMARY KEY (id_schema)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	if ($creation === false) {
		return false;
	}
	$sql = $connexion->prepare("REPLACE INTO admin_schema (id_schema, version, date_modification) VALUES (1, :version, NOW())");
	return $sql->execute([":version" => $version]);
}

/**
 * Met la base à niveau : applique, dans l'ordre, les migrations qu'elle n'a pas encore.
 *
 * Coût en régime normal : une seule lecture de `admin_schema`. Un verrou MySQL évite
 * que deux requêtes simultanées migrent en même temps.
 *
 * @return bool false si une migration a échoué (la base reste à la dernière version réussie).
 */
function SchemaMettreAJour($connexion)
{
	$versionCode = SchemaVersionCode();
	if (SchemaVersionBase($connexion) >= $versionCode) {
		return true;
	}

	$connexion->query("SELECT GET_LOCK('cdf_schema', 10)");
	// Relue sous verrou : une autre requête a pu migrer entre-temps.
	$versionBase = SchemaVersionBase($connexion);
	$ok = true;
	foreach (SchemaMigrations() as $numero => $migration) {
		if ($numero <= $versionBase) {
			continue;
		}
		if (!$migration($connexion) || !SchemaVersionEnregistrer($connexion, $numero)) {
			echo "Schéma : échec de la migration ".$numero." (".$migration.")";
			$ok = false;
			break;
		}
	}
	$connexion->query("SELECT RELEASE_LOCK('cdf_schema')");
	return $ok;
}

/////////////////
// Migrations //
/////////////////

/**
 * 1 — Préférences utilisateur (clé/valeur), une ligne par utilisateur et par préférence.
 */
function SchemaMigration1PreferencesUtilisateurs($connexion)
{
	return $connexion->exec("CREATE TABLE IF NOT EXISTS admin_utilisateurs_preferences (
		id_utilisateur mediumint NOT NULL,
		cle varchar(50) NOT NULL,
		valeur varchar(200) DEFAULT NULL,
		date_modification datetime DEFAULT NULL,
		PRIMARY KEY (id_utilisateur, cle)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8") !== false;
}
