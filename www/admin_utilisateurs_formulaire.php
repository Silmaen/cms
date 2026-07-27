<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');
require_once('../metier/commun.php');
require_once('../metier/utilisateurs.php');

/* TEST CONNEXION IDENTIFIANT */
if (!gestionIdentification($connexion))
{
	header("Location:index.php");
	exit();
}

/* GESTION DU MENU */
$smarty->assign('id_admin_menu_selectionne',$_SESSION["id_admin_menu_selectionne"]);
$smarty->assign('nom_table','utilisateurs');
$smarty->assign('titre_menu','Gestion d\'un utilisateur');
$smarty->assign('liste_items_menu',gestionMenu($connexion));

/* GESTION DU FIL D'ARIANNE */
$smarty->assign('breadcrumb',"Retour à la liste");

///////////////////////////
// GESTION DU FORMULAIRE //
///////////////////////////
/* TRAITEMENT DES VARIABLES */
if(isset($_GET["action"]))
{
	$action_selectionne=$_GET["action"];
	if(isset($_GET["id_utilisateur"]))
	{
		$id_utilisateur_selectionne=$_GET["id_utilisateur"];
	}
	else
	{
		$id_utilisateur_selectionne=0;
	}
}
elseif(isset($_POST["action"]))
{;
	$action_selectionne=$_POST["action"];
	$id_utilisateur_selectionne=$_POST["id_utilisateur"];
}
/* message_formulaire par défaut */
$message_formulaire="";

/* CAS AJOUTER */
if(isset($action_selectionne) AND $action_selectionne=="ajouter")
{
	$smarty->assign('id_utilisateur','0');
	$smarty->assign('nom_utilisateur','');
	$smarty->assign('prenom_utilisateur','');
	$smarty->assign('telephone','');
	$smarty->assign('email_utilisateur','');
	$smarty->assign('mdp_utilisateur','');
	$smarty->assign('id_utilisateur_groupe','0');
	$smarty->assign('libelle_utilisateur_groupe','');
	$smarty->assign('libelle_etat','Actif');
	$smarty->assign('date_creation',date("d-m-Y"));
	$smarty->assign('date_modification',date("d-m-Y"));
	$smarty->assign('id_membre_auteur',$_SESSION["id_utilisateur"]);
	$smarty->assign('modifie_par',$_SESSION["nom_utilisateur"]." ".$_SESSION["prenom_utilisateur"]);
	$smarty->assign('action','ajouter-valider');
	$message_formulaire="Formulaire d'ajout";

	// Affichage de la liste des groupes
	$liste_groupes = UtilisateursGroupesTous($connexion);
	if($liste_groupes===false) echo "VALIDER - DONNEES : Pb d'accès à la table admin_utilisateurs_groupes";
	else $smarty->assign('liste_groupes',$liste_groupes);

}
/* CAS COPIER */
elseif (isset($action_selectionne) AND $action_selectionne=="copier")
{
	$row = UtilisateurLire($connexion, $id_utilisateur_selectionne);
	if($row===false) echo "VALIDER - DONNEES : Pb d'accès à la table utilisateurs";
	elseif($row!==null)
	{
		$smarty->assign('id_utilisateur','0');
		$smarty->assign('nom_utilisateur',$row["nom_utilisateur"]);
		$smarty->assign('prenom_utilisateur',$row["prenom_utilisateur"]);
		$smarty->assign('telephone_utilisateur',$row["telephone_utilisateur"]);
		$smarty->assign('email_utilisateur',$row["email_utilisateur"]);
		$smarty->assign('mdp_utilisateur','');
		$smarty->assign('id_utilisateur_groupe',$row["id_utilisateur_groupe"]);
		$smarty->assign('libelle_utilisateur_groupe',$row["libelle_utilisateur_groupe"]);
		$smarty->assign('libelle_etat',$row["libelle_etat"]);
		$smarty->assign('date_creation',GestionDate($row["date_creation"],'0'));
		$smarty->assign('date_modification',GestionDate($row["date_modification"],'0'));
		$smarty->assign('id_membre_auteur',$row["id_membre_auteur"]);
		$smarty->assign('modifie_par',$row["nom_membre_auteur"]." ".$row["prenom_membre_auteur"]);
		$smarty->assign('action','copier-valider');
		$message_formulaire="Formulaire de duplication";

		// Affichage de la liste des groupes
		$liste_groupes = UtilisateursGroupesTous($connexion);
		if($liste_groupes===false) echo "VALIDER - DONNEES : Pb d'accès à la table admin_utilisateurs_groupes";
		else $smarty->assign('liste_groupes',$liste_groupes);
	}
}
/* CAS MODIFIER */
elseif (isset($action_selectionne) AND $action_selectionne=="modifier")
{
	$row = UtilisateurLire($connexion, $id_utilisateur_selectionne);
	if($row===false) echo "VALIDER - DONNEES : Pb d'accès à la table utilisateurs";
	elseif($row!==null)
	{
		$smarty->assign('id_utilisateur',$row["id_utilisateur"]);
		$smarty->assign('nom_utilisateur',$row["nom_utilisateur"]);
		$smarty->assign('prenom_utilisateur',$row["prenom_utilisateur"]);
		$smarty->assign('telephone_utilisateur',$row["telephone_utilisateur"]);
		$smarty->assign('email_utilisateur',$row["email_utilisateur"]);
		$smarty->assign('mdp_utilisateur','');
		$smarty->assign('id_utilisateur_groupe',$row["id_utilisateur_groupe"]);
		$smarty->assign('libelle_utilisateur_groupe',$row["libelle_utilisateur_groupe"]);
		$smarty->assign('libelle_etat',$row["libelle_etat"]);
		$smarty->assign('date_creation',GestionDate($row["date_creation"],'0'));
		$smarty->assign('date_modification',GestionDate($row["date_modification"],'0'));
		$smarty->assign('id_membre_auteur',$row["id_membre_auteur"]);
		$smarty->assign('modifie_par',$row["nom_membre_auteur"]." ".$row["prenom_membre_auteur"]);
		$smarty->assign('action','modifier-valider');
		$message_formulaire="Formulaire de modification";

		// Affichage de la liste des groupes
		$liste_groupes = UtilisateursGroupesTous($connexion);
		if($liste_groupes===false) echo "MODIFIER - DONNEES : Pb d'accès à la table admin_utilisateurs_groupes";
		else $smarty->assign('liste_groupes',$liste_groupes);
	}
}

/* CAS AJOUTER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="ajouter-valider" AND $_SESSION["droit"]==1)
{
	$id_utilisateur_selectionne = UtilisateurAjouter($connexion, $_POST["nom_utilisateur"], $_POST["prenom_utilisateur"], $_POST["telephone_utilisateur"], $_POST["email_utilisateur"], GestionHashage($_POST["mdp_utilisateur"]), $_POST["id_utilisateur_groupe"], uniqid(), $_SESSION["id_membre_auteur"]);
	$sql_exec = ($id_utilisateur_selectionne !== false);

	if(!$sql_exec) echo "Ajouter-Valider : Pb d'accès à la table admin_utilisateurs";
	else
	{
		$id_utilisateur_selectionne = $connexion->lastInsertId();

		if (DupliquerSessionUtilisateur($connexion, '1', $id_utilisateur_selectionne))
		{
			$message_formulaire="Ajout enregistré";
		}
		else
		{
			$message_formulaire="Ajout enregistré avec droits échoués";
		}
	}
}
/* CAS COPIER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="copier-valider" AND $_SESSION["droit"]==1)
{
	$sql=$connexion->prepare("INSERT INTO admin_utilisateurs (nom_utilisateur, prenom_utilisateur, telephone_utilisateur, email_utilisateur, mdp_utilisateur, id_utilisateur_groupe, date_creation, date_modification, id_membre_auteur) VALUES (:nom_utilisateur, :prenom_utilisateur, :telephone_utilisateur, :email_utilisateur, :mdp_utilisateur, :id_utilisateur_groupe, , :date_creation, :date_modification, :id_membre_auteur)");

	$sql_exec=$sql->execute([":nom_utilisateur"=>$_POST["nom_utilisateur"], ":prenom_utilisateur"=>$_POST["prenom_utilisateur"], ":telephone_utilisateur"=>$_POST["telephone_utilisateur"], ":email_utilisateur"=>$_POST["email_utilisateur"], ":mdp_utilisateur"=>GestionHashage($_POST["mdp_utilisateur"]), ":id_utilisateur_groupe"=>$_POST["id_utilisateur_groupe"], ":date_creation"=>date("Y-m-d"), ":date_modification"=>date("Y-m-d"), ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);

	if(!$sql_exec) echo "Copier-Valider : Pb d'accès à la table admin_utilisateurs";
	else
	{
		$id_utilisateur_selectionne = $connexion->lastInsertId();

		if (DupliquerSessionUtilisateur($connexion, '1', $id_utilisateur_selectionne))
		{
			$message_formulaire="Ajout enregistré";
		}
		else
		{
			$message_formulaire="Ajout enregistré avec droits échoués";
		}
	}
}
/* CAS MODIFIER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="modifier-valider" AND $_SESSION["droit"]==1)
{
	if($id_utilisateur_selectionne!=1)
	{
		if($_POST["mdp_utilisateur"]!='')
		{
			$sql_exec = UtilisateurModifier($connexion, $_POST["id_utilisateur"], $_POST["nom_utilisateur"], $_POST["prenom_utilisateur"], $_POST["telephone_utilisateur"], $_POST["email_utilisateur"], GestionHashage($_POST["mdp_utilisateur"]), $_POST["id_utilisateur_groupe"], $_SESSION["id_membre_auteur"]);
		}
		else
		{
			$sql_exec = UtilisateurModifierSansMdp($connexion, $_POST["id_utilisateur"], $_POST["nom_utilisateur"], $_POST["prenom_utilisateur"], $_POST["telephone_utilisateur"], $_POST["email_utilisateur"], $_POST["id_utilisateur_groupe"], $_SESSION["id_membre_auteur"]);
		}

		if(!$sql_exec) echo "Modifier-Valider : Pb d'accès à la table admin_utilisateurs";
		else
		{
			$message_formulaire="Modification enregistrée";
		}
	}
	else
	{
		$message_formulaire="Vous ne pouvez pas modifier ou supprimer cet utilisateur";
	}
}
else
{
	$message_formulaire="Vous n'avez pas les droits nécessaires pour cette action";
}

/* AFFICHAGE DES DONNEES APRES VALIDATION*/
if($action_selectionne=="ajouter-valider" OR $action_selectionne=="copier-valider" OR $action_selectionne=="modifier-valider" )
{
	header("Location:admin_utilisateurs_liste.php?id_admin_menu=90");
	exit();
}

// création de la liste des etats
$liste_etats = EtatsLister($connexion);
if($liste_etats===false) echo "Modifier : Pb d'accès à la table etats";
else $smarty->assign('liste_etats',$liste_etats);

$smarty->assign('message_formulaire',$message_formulaire);

$smarty->display('admin_utilisateurs_formulaire.tpl');
?>
