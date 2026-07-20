<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');

if (isset($_SESSION["message_identification"]))
{
	$smarty->assign('message_identification',$_SESSION["message_identification"]); 
}
else
{
	$smarty->assign('message_identification',""); 
}

// Destruction de la session si déconnexion par l'utilisateur
$_SESSION = array();
session_destroy();
unset($_SESSION);

$smarty->display('index.tpl');
?>