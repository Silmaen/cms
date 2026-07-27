<?php
session_start();
///////////////////////////////////////////
// Configuration et initilisation Smarty //
///////////////////////////////////////////
require_once('../vendor/smarty/Smarty.class.php');
$smarty = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir = 'templates_c';
$smarty->config_dir = 'configs';
$smarty->cache_dir = 'cache';
$smarty->left_delimiter = '<!--{';
$smarty->right_delimiter = '}-->';

///////////////////////////////////////////////
// Détection de l'environnement (thème + BDD) //
///////////////////////////////////////////////
// Détermine 'prod' / 'recette' / 'test' / 'local' — sans aucun secret.
require_once(__DIR__ . '/environnement.php');

////////////////////////////////////////
// Configuration et initilisation BDD //
////////////////////////////////////////
// Résout et définit les constantes serveur/bdd/utilisateur/mdp :
//   - en local  : variables d'environnement CDF_DB_* (docker-compose) ;
//   - sur OVH    : fichier hors-git config_secrets.php (voir .dist).
require_once(__DIR__ . '/identifiants_bdd.php');

$connexion="mysql:dbname=".bdd.";host=".serveur;
try{
	$connexion=new PDO($connexion,utilisateur,mdp, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
}
catch(PDOException $e){
	printf("Échec de la connexion : %s\n", $e->getMessage());
	exit();
}

/////////////////////////////////////////////////////
// Exposition de l'environnement aux vues (templates) //
/////////////////////////////////////////////////////
// Pilote la couleur du thème (attribut data-env sur <body>) et le bandeau.
$smarty->assign('cdf_env', CDF_ENV);
$smarty->assign('cdf_env_libelle', CDF_ENV_LIBELLE);
$smarty->assign('cdf_env_badge', CDF_ENV_BADGE);

?>
