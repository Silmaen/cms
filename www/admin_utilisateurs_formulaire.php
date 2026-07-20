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
	$liste_groupes = array();
	$sql_groupes="SELECT id_utilisateur_groupe, libelle_utilisateur_groupe FROM admin_utilisateurs_groupes ORDER BY libelle_utilisateur_groupe ASC";
	if(!$connexion->query($sql_groupes)) echo "VALIDER - DONNEES : Pb d'accès à la table admin_utilisateurs_groupes";
	else
	{
		foreach ($connexion->query($sql_groupes) as $row_groupe) 
		{
			array_push($liste_groupes,$row_groupe);
		}
		$smarty->assign('liste_groupes',$liste_groupes);
	}					
	
}
/* CAS COPIER */
elseif (isset($action_selectionne) AND $action_selectionne=="copier") 
{
	$sql=$connexion->prepare("
	SELECT t1.id_utilisateur, t1.nom_utilisateur, t1.prenom_utilisateur, t1.telephone_utilisateur,  t1.email_utilisateur, t1.mdp_utilisateur, t1.id_utilisateur_groupe, t1.date_creation, t1.date_modification, t1.id_membre_auteur,  
	t2.id_utilisateur_groupe, t2.libelle_utilisateur_groupe, 
	t3.id_etat, t3.libelle_etat,
	t4.nom_utilisateur AS nom_membre_auteur, t4.prenom_utilisateur AS prenom_membre_auteur	
	FROM admin_utilisateurs AS t1 
	LEFT JOIN admin_utilisateurs_groupes AS t2 ON t1.id_utilisateur_groupe=t2.id_utilisateur_groupe 
	LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat
	LEFT JOIN admin_utilisateurs AS t4 ON t4.id_utilisateur=t1.id_membre_auteur
	WHERE (t1.id_utilisateur = :id_utilisateur)");
	$sql_exec=$sql->execute([":id_utilisateur"=>$id_utilisateur_selectionne]);	
	if(!$sql_exec) echo "VALIDER - DONNEES : Pb d'accès à la table utilisateurs";
	else
	{
		foreach ($sql->fetchAll() as $row) 
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
			$liste_groupes = array();
			$sql_groupes="SELECT id_utilisateur_groupe, libelle_utilisateur_groupe FROM admin_utilisateurs_groupes ORDER BY libelle_utilisateur_groupe ASC";
			if(!$connexion->query($sql_groupes)) echo "VALIDER - DONNEES : Pb d'accès à la table admin_utilisateurs_groupes";
			else
			{
				foreach ($connexion->query($sql_groupes) as $row_groupe) 
				{
					array_push($liste_groupes,$row_groupe);
				}
				$smarty->assign('liste_groupes',$liste_groupes);
			}					
		}
	}	
}
/* CAS MODIFIER */
elseif (isset($action_selectionne) AND $action_selectionne=="modifier") 
{
	$sql=$connexion->prepare("
	SELECT t1.id_utilisateur, t1.nom_utilisateur, t1.prenom_utilisateur, t1.telephone_utilisateur, t1.email_utilisateur, t1.mdp_utilisateur, t1.id_utilisateur_groupe, t1.date_creation, t1.date_modification, t1.id_membre_auteur,  
	t2.id_utilisateur_groupe, t2.libelle_utilisateur_groupe, 
	t3.id_etat, t3.libelle_etat,
	t4.nom_utilisateur AS nom_membre_auteur, t4.prenom_utilisateur AS prenom_membre_auteur	
	FROM admin_utilisateurs AS t1 
	LEFT JOIN admin_utilisateurs_groupes AS t2 ON t1.id_utilisateur_groupe=t2.id_utilisateur_groupe 
	LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat
	LEFT JOIN admin_utilisateurs AS t4 ON t4.id_utilisateur=t1.id_membre_auteur
	WHERE (t1.id_utilisateur = :id_utilisateur)");
	$sql_exec=$sql->execute([":id_utilisateur"=>$id_utilisateur_selectionne]);	
	if(!$sql_exec) echo "VALIDER - DONNEES : Pb d'accès à la table utilisateurs";
	else
	{
		foreach ($sql->fetchAll() as $row) 
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
			$liste_groupes = array();
			$sql_groupes="SELECT id_utilisateur_groupe, libelle_utilisateur_groupe FROM admin_utilisateurs_groupes ORDER BY libelle_utilisateur_groupe ASC";
			if(!$connexion->query($sql_groupes)) echo "MODIFIER - DONNEES : Pb d'accès à la table admin_utilisateurs_groupes";
			else
			{
				foreach ($connexion->query($sql_groupes) as $row_groupe) 
				{
					array_push($liste_groupes,$row_groupe);
				}
				$smarty->assign('liste_groupes',$liste_groupes);
			}	
		}
	}	
}			

/* CAS AJOUTER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="ajouter-valider" AND $_SESSION["droit"]==1) 
{	
	$sql=$connexion->prepare("INSERT INTO admin_utilisateurs (nom_utilisateur, prenom_utilisateur, telephone_utilisateur, email_utilisateur, mdp_utilisateur, id_utilisateur_groupe, id_etat, date_creation, date_modification, cle_utilisateur, id_membre_auteur) VALUES (:nom_utilisateur, :prenom_utilisateur, :telephone_utilisateur, :email_utilisateur, :mdp_utilisateur, :id_utilisateur_groupe, :id_etat, :date_creation, :date_modification, :cle_utilisateur, :id_membre_auteur)");

	$sql_exec=$sql->execute([":nom_utilisateur"=>$_POST["nom_utilisateur"], ":prenom_utilisateur"=>$_POST["prenom_utilisateur"], ":telephone_utilisateur"=>$_POST["telephone_utilisateur"], ":email_utilisateur"=>$_POST["email_utilisateur"], ":mdp_utilisateur"=>GestionHashage($_POST["mdp_utilisateur"]), ":id_utilisateur_groupe"=>$_POST["id_utilisateur_groupe"], ":id_etat"=>'1', ":date_creation"=>date("Y-m-d"), ":date_modification"=>date("Y-m-d"), ":cle_utilisateur"=>uniqid(), ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);
	
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
			$sql=$connexion->prepare("UPDATE admin_utilisateurs SET nom_utilisateur=:nom_utilisateur, prenom_utilisateur=:prenom_utilisateur, telephone_utilisateur=:telephone_utilisateur, email_utilisateur=:email_utilisateur, mdp_utilisateur=:mdp_utilisateur, id_utilisateur_groupe=:id_utilisateur_groupe, date_modification=:date_modification, id_membre_auteur=:id_membre_auteur WHERE (id_utilisateur=:id_utilisateur)");
				
			$sql_exec=$sql->execute([":id_utilisateur"=>$_POST["id_utilisateur"], ":nom_utilisateur"=>$_POST["nom_utilisateur"], ":prenom_utilisateur"=>$_POST["prenom_utilisateur"], ":telephone_utilisateur"=>$_POST["telephone_utilisateur"], ":email_utilisateur"=>$_POST["email_utilisateur"], ":mdp_utilisateur"=>GestionHashage($_POST["mdp_utilisateur"]), ":id_utilisateur_groupe"=>$_POST["id_utilisateur_groupe"], ":date_modification"=>date("Y-m-d"), ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);
		}
		else
		{
			$sql=$connexion->prepare("UPDATE admin_utilisateurs SET nom_utilisateur=:nom_utilisateur, prenom_utilisateur=:prenom_utilisateur, telephone_utilisateur=:telephone_utilisateur, email_utilisateur=:email_utilisateur, id_utilisateur_groupe=:id_utilisateur_groupe, date_modification=:date_modification, id_membre_auteur=:id_membre_auteur WHERE (id_utilisateur=:id_utilisateur)");

			$sql_exec=$sql->execute([":id_utilisateur"=>$_POST["id_utilisateur"], ":nom_utilisateur"=>$_POST["nom_utilisateur"], ":prenom_utilisateur"=>$_POST["prenom_utilisateur"], ":telephone_utilisateur"=>$_POST["telephone_utilisateur"], ":email_utilisateur"=>$_POST["email_utilisateur"], ":id_utilisateur_groupe"=>$_POST["id_utilisateur_groupe"], ":date_modification"=>date("Y-m-d"), ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);
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

	/*
	$liste_items = array();
	$sql=$connexion->prepare("
	SELECT t1.id_utilisateur, t1.nom_utilisateur, t1.prenom_utilisateur, t1.telephone_utilisateur, t1.email_utilisateur, t1.mdp_utilisateur, t1.id_utilisateur_groupe, t1.date_creation, t1.date_modification, t1.id_membre_auteur,  
	t2.id_utilisateur_groupe, t2.libelle_utilisateur_groupe, 
	t3.id_etat, t3.libelle_etat,
	t4.nom_utilisateur AS nom_membre_auteur, t4.prenom_utilisateur AS prenom_membre_auteur	
	FROM admin_utilisateurs AS t1 
	LEFT JOIN admin_utilisateurs_groupes AS t2 ON t1.id_utilisateur_groupe=t2.id_utilisateur_groupe 
	LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat
	LEFT JOIN admin_utilisateurs AS t4 ON t4.id_utilisateur=t1.id_membre_auteur
	WHERE (t1.id_utilisateur = :id_utilisateur)");
	$sql_exec=$sql->execute([":id_utilisateur"=>$id_utilisateur_selectionne]);	
	if(!$sql_exec) echo "VALIDER - DONNEES : Pb d'accès à la table utilisateurs";
	else
	{
		foreach ($sql->fetchAll() as $row) 
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
			
			
			// Affichage de la liste des groupes
			$liste_groupes = array();
			$sql_groupes="SELECT id_utilisateur_groupe, libelle_utilisateur_groupe FROM admin_utilisateurs_groupes ORDER BY libelle_utilisateur_groupe ASC";
			if(!$connexion->query($sql_groupes)) echo "VALIDER - DONNEES : Pb d'accès à la table admin_utilisateurs_groupes";
			else
			{
				foreach ($connexion->query($sql_groupes) as $row_groupe) 
				{
					array_push($liste_groupes,$row_groupe);
				}
				$smarty->assign('liste_groupes',$liste_groupes);
			}
		}
	}
	*/
}

// création de la liste des etats
$liste_etats = array();
$sql_etats="SELECT * FROM etats ORDER BY libelle_etat ASC";
if(!$connexion->query($sql_etats)) echo "Modifier : Pb d'accès à la table etats";
else
{
	foreach ($connexion->query($sql_etats) as $row_etats) 
	{
		array_push($liste_etats,$row_etats);		
	}
	$smarty->assign('liste_etats',$liste_etats);
}

$smarty->assign('message_formulaire',$message_formulaire);

$smarty->display('admin_utilisateurs_formulaire.tpl');
?>