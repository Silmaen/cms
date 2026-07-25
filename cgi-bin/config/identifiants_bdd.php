<?php
//
// Résolution des identifiants de la base de données — partagée par l'application
// (config_general.php) et la tâche planifiée (reservations_maj_automatique.php).
//
// Définit les quatre constantes attendues par le code :
//   serveur, bdd, utilisateur, mdp
//
// Deux sources possibles, dans cet ordre :
//   1. Variables d'environnement CDF_DB_* — utilisées en LOCAL (Docker, injectées
//      par docker-compose.yml) et par tout hébergeur capable d'en définir.
//   2. Fichier cgi-bin/config/config_secrets.php — utilisé sur OVH mutualisé.
//      Ce fichier est HORS git (voir .gitignore) : déposé une fois par serveur,
//      il ne contient que les identifiants de CE serveur et n'est jamais écrasé
//      par le déploiement automatique (checkout git). Modèle : config_secrets.php.dist.
//
// Ce fichier ne contient donc AUCUN identifiant.
//

if (!defined('serveur')) {

	$cdf_db_hote = getenv('CDF_DB_HOST');

	if ($cdf_db_hote !== false && $cdf_db_hote !== '') {
		// 1. Identifiants fournis par l'environnement (Docker local).
		define('serveur',     $cdf_db_hote);
		define('bdd',         getenv('CDF_DB_NAME'));
		define('utilisateur', getenv('CDF_DB_USER'));
		define('mdp',         getenv('CDF_DB_PASS'));
	}
	elseif (file_exists(__DIR__ . '/config_secrets.php')) {
		// 2. Identifiants déposés sur le serveur (OVH). Définit les 4 constantes.
		require_once(__DIR__ . '/config_secrets.php');
	}
	else {
		// Ni variables d'environnement, ni fichier de secrets : on échoue clairement.
		if (PHP_SAPI === 'cli') {
			fwrite(STDERR, "Configuration BDD manquante : définir les variables CDF_DB_* "
				. "ou déposer cgi-bin/config/config_secrets.php (voir config_secrets.php.dist).\n");
		} else {
			http_response_code(500);
			echo "Configuration de la base de données manquante. "
				. "Voir cgi-bin/config/config_secrets.php.dist.";
		}
		exit();
	}
}
