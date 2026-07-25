<?php
//
// Détection de l'environnement d'exécution — SANS aucun secret (versionné dans git).
//
// Le même code, déployé sur les trois branches/hébergements, se comporte
// correctement grâce à la détection par nom d'hôte. Résultat : l'une des valeurs
//   'prod' | 'recette' | 'test' | 'local'
// qui pilote le thème (couleur) et le bandeau d'environnement.
//
// Ordre de priorité de la détection :
//   1. Variable d'environnement CDF_ENV (docker-compose en local, ou surcharge
//      ponctuelle pour prévisualiser un thème).
//   2. Nom d'hôte HTTP (déploiements OVH) :
//        www.cdf-genay.com / apex          -> prod
//        recette.cdf-genay.com             -> recette
//        test.cdf-genay.com                -> test
//        localhost / 127.0.0.1 / *.local   -> local
//   3. Défaut (ex. CLI/cron sans nom d'hôte) : 'prod' (cas le plus sûr :
//      aucun bandeau d'alerte n'est affiché).
//
// Les identifiants de base de données sont résolus séparément : voir
// identifiants_bdd.php et config_secrets.php.dist.
//

if (!function_exists('cdfDetecterEnvironnement')) {

	function cdfDetecterEnvironnement()
	{
		// 1. Surcharge explicite par variable d'environnement.
		$force = getenv('CDF_ENV');
		if ($force !== false && $force !== '') {
			$force = strtolower(trim($force));
			if (in_array($force, array('prod', 'recette', 'test', 'local'), true)) {
				return $force;
			}
		}

		// 2. Détection par nom d'hôte (on retire un éventuel port, ex. localhost:8080).
		$hote = isset($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : '';
		$hote = preg_replace('/:\d+$/', '', $hote);

		if ($hote === '') {
			// Contexte CLI (cron) sans nom d'hôte : on retombe sur le défaut sûr.
			return 'prod';
		}
		if ($hote === 'localhost' || $hote === '127.0.0.1' || substr($hote, -6) === '.local') {
			return 'local';
		}
		if (strpos($hote, 'test.') === 0 || strpos($hote, 'test-') === 0) {
			return 'test';
		}
		if (strpos($hote, 'recette.') === 0 || strpos($hote, 'recette-') === 0) {
			return 'recette';
		}
		// www.cdf-genay.com, cdf-genay.com, ...
		return 'prod';
	}
}

// Constantes globales dérivées, réutilisables partout (contrôleurs, cron).
if (!defined('CDF_ENV')) {
	define('CDF_ENV', cdfDetecterEnvironnement());

	// Libellé affiché dans le bandeau (badge).
	$cdf_libelles = array(
		'prod'    => 'PRODUCTION',
		'recette' => 'RECETTE',
		'test'    => 'TEST',
		'local'   => 'LOCAL',
	);
	define('CDF_ENV_LIBELLE', $cdf_libelles[CDF_ENV]);

	// Afficher le bandeau d'environnement ? Non en production, pour ne pas gêner
	// l'utilisateur final (le vert « normal » suffit).
	define('CDF_ENV_BADGE', CDF_ENV !== 'prod');

	unset($cdf_libelles);
}
