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
$smarty->assign('id_admin_menu_selectionne',$_SESSION["id_admin_menu_selectionne"]);
$smarty->assign('nom_table','textes');
$smarty->assign('titre_menu','Gestion d\'un texte');
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
	if(isset($_GET["id_utilisateur_groupe"]))
	{
		$id_utilisateur_groupe_selectionne=$_GET["id_utilisateur_groupe"];
	}
	else
	{
		$id_utilisateur_groupe_selectionne=0;
	}
}
elseif(isset($_POST["action"]))
{;
	$action_selectionne=$_POST["action"]; 
	$id_utilisateur_groupe_selectionne=$_POST["id_utilisateur_groupe"];
}	
/* message_formulaire par défaut */
$message_formulaire="";


/* Calcul du nombre de menus existants */
$sql="SELECT COUNT(id_admin_menu) AS nb_menu FROM admin_menu";
if(!$connexion->query($sql)) echo "Count : Pb d'accès à la table admin_menu";
else
{
	foreach ($connexion->query($sql) as $row) 
	{
		$nb_menu = $row["nb_menu"];
	}
}


/* CAS AJOUTER */
if(isset($action_selectionne) AND $action_selectionne=="ajouter")
{	
	$smarty->assign('id_utilisateur_groupe','0');
	$smarty->assign('libelle_utilisateur_groupe','');
	$smarty->assign('action','ajouter-valider');
	$message_formulaire="Formulaire d'ajout";
	
	// Création du tableau de menus - droits
	$liste_menus = array();
	$sql_menus="SELECT t2.droit, t2.id_utilisateur_groupe, t1.id_admin_menu, t1.titre_fr AS titre_menu FROM admin_menu AS t1 LEFT JOIN admin_menus_groupes AS t2 ON t2.id_admin_menu=t1.id_admin_menu WHERE t2.id_utilisateur_groupe='1' ORDER BY t1.titre_fr ASC";	
	if(!$connexion->query($sql_menus)) echo "Ajouter : Pb d'accès à la table menus";
	else
	{
		foreach ($connexion->query($sql_menus) as $row_menus) 
		{
			array_push($liste_menus,$row_menus);		
		}
		$smarty->assign('liste_menus',$liste_menus);
	}	
}
/* CAS COPIER */
elseif (isset($action_selectionne) AND $action_selectionne=="copier") 
{
	$sql=$connexion->prepare("SELECT t1.* FROM admin_utilisateurs_groupes AS t1 WHERE (t1.id_utilisateur_groupe=:id_utilisateur_groupe)");
	$sql_exec=$sql->execute([":id_utilisateur_groupe"=>$id_utilisateur_groupe_selectionne]);	
	if(!$sql_exec) echo "VALIDER - DONNEES : Pb d'accès à la table admin_utilisateurs_groupes";
	else
	{
		foreach ($sql->fetchAll() as $row) 
		{
			$smarty->assign('id_utilisateur_groupe','0');
			$smarty->assign('libelle_utilisateur_groupe',$row["libelle_utilisateur_groupe"]);
			$smarty->assign('action','copier-valider');
			$message_formulaire="Formulaire de duplication";
			
			// Création du tableau de menus - droits
			$liste_menus = array();		
			$sql_menus=$connexion->prepare("SELECT t2.droit, t2.id_utilisateur_groupe, t1.id_admin_menu, t1.titre_fr AS titre_menu FROM admin_menu AS t1 LEFT JOIN admin_menus_groupes AS t2 ON t2.id_admin_menu=t1.id_admin_menu WHERE t2.id_utilisateur_groupe=:id_utilisateur_groupe");
			$sql_exec=$sql_menus->execute([":id_utilisateur_groupe"=>$id_utilisateur_groupe_selectionne]);	
			if(!$sql_exec) echo "Modifier2 : Pb d'accès à la table menus";
			else
			{
				foreach ($sql_menus->fetchAll() as $row_menus) 
				{
					array_push($liste_menus,$row_menus);		
				}
				$smarty->assign('liste_menus',$liste_menus);
			}					
		}
	}	
}
/* CAS MODIFIER */
elseif (isset($action_selectionne) AND $action_selectionne=="modifier") 
{
	$sql=$connexion->prepare("SELECT t1.* FROM admin_utilisateurs_groupes AS t1 WHERE (t1.id_utilisateur_groupe= :id_utilisateur_groupe)");
	$sql_exec=$sql->execute([":id_utilisateur_groupe"=>$id_utilisateur_groupe_selectionne]);	
	if(!$sql_exec) echo "VALIDER - DONNEES : Pb d'accès à la table admin_utilisateurs_groupes";
	else
	{
		foreach ($sql->fetchAll() as $row) 
		{
			$smarty->assign('id_utilisateur_groupe',$row["id_utilisateur_groupe"]);
			$smarty->assign('libelle_utilisateur_groupe',$row["libelle_utilisateur_groupe"]);
			$smarty->assign('action','modifier-valider');
			$message_formulaire="Formulaire de modification";
			
			// Création du tableau de menus - droits
			$liste_menus = array();
				$connexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			$sql_menus=$connexion->prepare("SELECT t2.droit, t2.id_utilisateur_groupe, t1.id_admin_menu, t1.titre_fr AS titre_menu FROM admin_menu AS t1 LEFT JOIN admin_menus_groupes AS t2 ON t2.id_admin_menu=t1.id_admin_menu WHERE t2.id_utilisateur_groupe=:id_utilisateur_groupe");
			$sql_exec=$sql_menus->execute([":id_utilisateur_groupe"=>$id_utilisateur_groupe_selectionne]);	
			if(!$sql_exec) echo "Modifier2 : Pb d'accès à la table menus";
			else
			{
				foreach ($sql_menus->fetchAll() as $row_menus) 
				{
					array_push($liste_menus,$row_menus);		
				}
				$smarty->assign('liste_menus',$liste_menus);
			}					
		}
	}	
}
/* CAS AJOUTER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="ajouter-valider" AND $_SESSION["droit"]==1) 
{	
	$sql=$connexion->prepare("INSERT INTO admin_utilisateurs_groupes (libelle_utilisateur_groupe, id_membre_auteur) VALUES (:libelle_utilisateur_groupe, :id_membre_auteur)");
	
	$sql_exec=$sql->execute([":libelle_utilisateur_groupe"=>$_POST["libelle_utilisateur_groupe"],":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
	if(!$sql_exec) echo "Ajouter-Valider : Pb d'accès à la table admin_utilisateurs_groupes";
	else
	{
		$id_utilisateur_groupe_selectionne = $connexion->lastInsertId();
		
		// Ajout en boucle des droits pour chaque menu
		foreach ($_POST as $key => $value) 
		{
			$tab_info = $value;
			$info1 = $tab_info[0];
			if(stristr($key, 'id_amin_menu_'))
			{
				$id_admin_menu_temp = substr($key, 13);
				$sql=$connexion->prepare("INSERT INTO admin_menus_groupes (id_admin_menu, id_utilisateur_groupe, droit, id_membre_auteur) VALUES (:id_admin_menu, :id_utilisateur_groupe, :droit, :id_membre_auteur)");

				$sql_exec=$sql->execute([":id_admin_menu"=>$id_admin_menu_temp, ":droit"=>$_POST["id_amin_menu_".$id_admin_menu_temp], ":id_utilisateur_groupe"=>$id_utilisateur_groupe_selectionne, ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
				
				if(!$sql_exec) echo "Modifier-Valider : Pb d'accès à la table id_admin_menu";
				else
				{
					$message_formulaire="Ajout enregistré";
				}					
			}
		}			}
}
/* CAS COPIER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="copier-valider" AND $_SESSION["droit"]==1) 
{
	$sql=$connexion->prepare("INSERT INTO admin_utilisateurs_groupes (libelle_utilisateur_groupe, id_membre_auteur) VALUES (:libelle_utilisateur_groupe,  :id_membre_auteur)");
	$sql_exec=$sql->execute([":libelle_utilisateur_groupe"=>$_POST["libelle_utilisateur_groupe"], ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);		
	if(!$sql_exec) echo "Copier-Valider : Pb d'accès à la table admin_utilisateurs_groupes";
	else
	{
		$id_utilisateur_groupe_selectionne = $connexion->lastInsertId();
		
		// Ajout en boucle des droits pour chaque menu
		foreach ($_POST as $key => $value) 
		{
			$tab_info = $value;
			$info1 = $tab_info[0];
			if(stristr($key, 'id_amin_menu_'))
			{
				$id_admin_menu_temp = substr($key, 13);
				
				$sql=$connexion->prepare("INSERT INTO admin_menus_groupes (id_admin_menu, id_utilisateur_groupe, droit, id_membre_auteur) VALUES (:id_admin_menu, :id_utilisateur_groupe, :droit, :id_membre_auteur)");

				$sql_exec=$sql->execute([":id_admin_menu"=>$id_admin_menu_temp, ":droit"=>$_POST["id_amin_menu_".$id_admin_menu_temp], ":id_utilisateur_groupe"=>$id_utilisateur_groupe_selectionne, ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
				
				if(!$sql_exec) echo "Modifier-Valider : Pb d'accès à la table id_admin_menu";
				else
				{
					$message_formulaire="Ajout enregistré";
				}					
			}
		}		
	}
}
/* CAS MODIFIER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="modifier-valider" AND $_SESSION["droit"]==1) 
{
	$sql=$connexion->prepare("UPDATE admin_utilisateurs_groupes SET libelle_utilisateur_groupe=:libelle_utilisateur_groupe,  id_membre_auteur=:id_membre_auteur WHERE (id_utilisateur_groupe=:id_utilisateur_groupe)");
	
	$sql_exec=$sql->execute([":id_utilisateur_groupe"=>$_POST["id_utilisateur_groupe"], ":libelle_utilisateur_groupe"=>$_POST["libelle_utilisateur_groupe"], ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);
	if(!$sql_exec) echo "Modifier-Valider : Pb d'accès à la table admin_utilisateurs_groupes";
	else
	{
		// Modification en boucle des droits pour chaque menu
		foreach ($_POST as $key => $value) 
		{
			$tab_info = $value;
			$info1 = $tab_info[0];
			if(stristr($key, 'id_amin_menu_'))
			{
				$id_admin_menu_temp = substr($key, 13);
				
				$sql=$connexion->prepare("UPDATE admin_menus_groupes SET droit=:droit, id_membre_auteur=:id_membre_auteur WHERE (id_admin_menu=:id_admin_menu AND id_utilisateur_groupe=:id_utilisateur_groupe)");
				
				$sql_exec=$sql->execute([":id_admin_menu"=>$id_admin_menu_temp, ":droit"=>$_POST["id_amin_menu_".$id_admin_menu_temp], ":id_utilisateur_groupe"=>$id_utilisateur_groupe_selectionne, ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);
				if(!$sql_exec) echo "Modifier-Valider : Pb d'accès à la table id_admin_menu";
				else
				{
					$message_formulaire="Modification enregistrée";
				}					
			}
		}
	}
}



// AFFICHAGE DES DONNEES APRES VALIDATION
if($action_selectionne=="ajouter-valider" OR $action_selectionne=="copier-valider" OR $action_selectionne=="modifier-valider" )
{
	header("Location:admin_utilisateurs_groupes_liste.php?id_admin_menu=91");
	exit();

	/*
	$sql=$connexion->prepare("SELECT t1.* FROM admin_utilisateurs_groupes AS t1 WHERE (t1.id_utilisateur_groupe= :id_utilisateur_groupe)");
	$sql_exec=$sql->execute([":id_utilisateur_groupe"=>$id_utilisateur_groupe_selectionne]);	
	if(!$sql_exec) echo "VALIDER - DONNEES : Pb d'accès à la table admin_utilisateurs_groupes";
	else
	{
		foreach ($sql->fetchAll() as $row) 
		{
			$smarty->assign('id_utilisateur_groupe',$row["id_utilisateur_groupe"]);
			$smarty->assign('libelle_utilisateur_groupe',$row["libelle_utilisateur_groupe"]);
			$smarty->assign('action','modifier-valider');
			$message_formulaire="Formulaire de modification";
			
			// Création du tableau de menus - droits
			$liste_menus = array();
			$sql_menus=$connexion->prepare("SELECT t2.droit, t2.id_utilisateur_groupe, t1.id_admin_menu, t1.titre_fr AS titre_menu FROM admin_menu AS t1 LEFT JOIN admin_menus_groupes AS t2 ON t2.id_admin_menu=t1.id_admin_menu WHERE t2.id_utilisateur_groupe=:id_utilisateur_groupe");
			$sql_exec=$sql_menus->execute([":id_utilisateur_groupe"=>$id_utilisateur_groupe_selectionne]);	
			if(!$sql_exec) echo "Modifier2 : Pb d'accès à la table menus";
			else
			{
				foreach ($sql_menus->fetchAll() as $row_menus) 
				{
					array_push($liste_menus,$row_menus);		
				}
				$smarty->assign('liste_menus',$liste_menus);
			}					
		}
	}	
	*/
}


$smarty->assign('message_formulaire',$message_formulaire);

$smarty->display('admin_utilisateurs_groupes_formulaire.tpl');
?>