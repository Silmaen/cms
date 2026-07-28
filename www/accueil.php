<?php

require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');

// GESTION DE L'IDENTIFICATION
if (!gestionIdentification($connexion))
{
	header("Location:index.php");
	exit();
}

// GESTION DU MENU
$_SESSION["id_admin_menu_selectionne"] = 0;
$smarty->assign('id_admin_menu_selectionne',$_SESSION["id_admin_menu_selectionne"]);
$smarty->assign('liste_items_menu',gestionMenu($connexion));

// GESTION DES DROITS UTILISATEURS SUR CE MENU
GestionMenusDroits($connexion);

$_SESSION['breadcrumb'] = "Accueil";
$_SESSION['titre_page_fr'] = "Accueil";

$message_formulaire = 'Bonjour '.$_SESSION["prenom_utilisateur"].'. Vous êtes connecté(e) au CMS de votre site Internet.';
$smarty->assign('message_formulaire',$message_formulaire);
$smarty->display('accueil.tpl');

?>
