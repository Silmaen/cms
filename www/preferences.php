<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');

/* TEST CONNEXION IDENTIFIANT */
if (!gestionIdentification($connexion))
{
	header("Location:index.php");
	exit();
}

/* GESTION DU MENU */
$_SESSION["id_admin_menu_selectionne"] = 0;
$smarty->assign('id_admin_menu_selectionne',$_SESSION["id_admin_menu_selectionne"]);
$smarty->assign('liste_items_menu',gestionMenu($connexion));

/* GESTION DU FIL D'ARIANNE */
$_SESSION['breadcrumb'] = "Accueil";
$_SESSION['titre_page_fr'] = "Mes préférences";

/* message_formulaire par défaut */
$message_formulaire="";

/* CAS VALIDER : chaque utilisateur ne modifie que ses propres préférences */
if(isset($_POST["action"]) AND $_POST["action"]=="valider")
{
	$enregistre = true;

	// Chaque préférence est aussi mise en session ($_SESSION['theme'], $_SESSION['interface']…).
	foreach(array_keys(PreferencesChoix()) as $cle)
	{
		if(PreferenceEnregistrer($connexion, $_SESSION["id_utilisateur"], $cle, $_POST[$cle] ?? ''))
		{
			$_SESSION[$cle] = $_POST[$cle];
		}
		else
		{
			$enregistre = false;
		}
	}

	// Seulement si la valeur change : l'enregistrer écrase le réglage propre à chaque liste.
	$items_par_page = intval($_POST["items_par_page"] ?? 0);
	if($items_par_page != PreferencesItemsParPageLire($connexion, $_SESSION["id_utilisateur"]))
	{
		if(PreferencesItemsParPageEnregistrer($connexion, $_SESSION["id_utilisateur"], $items_par_page))
		{
			$_SESSION['items_par_page'] = $items_par_page;
		}
		else
		{
			$enregistre = false;
		}
	}

	header("Location:preferences.php?enregistre=".($enregistre ? "1" : "0"));
	exit();
}

if(isset($_GET["enregistre"]))
{
	$message_formulaire = ($_GET["enregistre"]=="1") ? "Préférences enregistrées" : "Pb d'enregistrement des préférences";
}

/* AFFICHAGE DES PREFERENCES */
$preferences = PreferencesLire($connexion, $_SESSION["id_utilisateur"]);
$smarty->assign('theme',$preferences['theme']);
$smarty->assign('liste_themes',PreferencesChoix()['theme']);
$smarty->assign('interface',$preferences['interface']);
$smarty->assign('liste_interfaces',PreferencesChoix()['interface']);

$items_par_page = PreferencesItemsParPageLire($connexion, $_SESSION["id_utilisateur"]);
if($items_par_page===false) echo "Préférences : Pb d'accès à la table admin_utilisateurs";
$liste_items_par_page = PreferencesItemsParPageChoix();
// Valeur héritée hors des choix proposés (ex. 50) : on la garde sélectionnable.
if($items_par_page AND !isset($liste_items_par_page[$items_par_page]))
{
	$liste_items_par_page[$items_par_page] = $items_par_page." par page";
	ksort($liste_items_par_page);
}
$smarty->assign('items_par_page',$items_par_page);
$smarty->assign('liste_items_par_page',$liste_items_par_page);

$smarty->assign('message_formulaire',$message_formulaire);

$smarty->display('preferences.tpl');
?>
