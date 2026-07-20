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

/*
// CHANGEMENT DE L'ETAT DES RESERVATIONS DONT LA DATE DE RETOUR > AUJOURD'HUI
$sql=$connexion->prepare("SELECT id_reservation FROM reservations WHERE date_retour<='".date("Y-m-d")."'");
$sql_exec=$sql->execute();	
if(!$sql_exec) echo "SELECT - DONNEES : Pb d'accès à la table reservations";
else
{
	foreach ($sql->fetchAll() as $row) 
	{

		$sql=$connexion->prepare("UPDATE reservations SET id_etat=2 WHERE (id_reservation=:id_reservation)");
		$sql_exec=$sql->execute([":id_reservation"=>$row["id_reservation"]]);
		if(!$sql_exec) echo "Modifier-etat : Pb d'accès à la table reservations";
		
	}
}
*/

$message_formulaire = 'Bonjour '.$_SESSION["prenom_utilisateur"].'. Vous êtes connecté(e) au CMS de votre site Internet.';
$smarty->assign('message_formulaire',$message_formulaire);
$smarty->display('accueil.tpl');

?>