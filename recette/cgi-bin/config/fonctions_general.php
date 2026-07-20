<?php

function GestionHashage($mdp)
{
	$prefixe = 'azd12s5qsd558qs41qs4qsd62s8';
	$suffixe = '2s4hx5d8z4123cbdr31sdfqsd54';
	$mdp_hash = $prefixe.hash('sha256',$mdp).$suffixe;
	return $mdp_hash;
}

function GestionMenu($connexion)
{
	$liste_items_menu = array();
	$sql_menu="SELECT * FROM ".$prefixe_bdd."admin_menu ORDER BY ordre ASC";
	if(!$connexion->query($sql_menu)) echo "Pb d'accès à la table admin_menu";
	else
	{
		foreach ($connexion->query($sql_menu) as $row) 
		{
			$row["id_admin_menu"]=$row["id_admin_menu"];
			$row["titre_fr"]=$row["titre_fr"];
			$row["url"]=$row["url"];
			$row["niveau"]=$row["niveau"];
			$row["id_parent"]=$row["id_parent"];
			$row["ordre"]=$row["ordre"];

			array_push($liste_items_menu,$row);	
		}
	}
	return $liste_items_menu;
}



function GestionMenusDroits($connexion)
{
	$sql="SELECT id_utilisateur_groupe, id_admin_menu, droit FROM ".$prefixe_bdd."admin_menus_groupes WHERE (id_utilisateur_groupe='".$_SESSION["id_utilisateur_groupe"]."' AND id_admin_menu='".$_SESSION["id_admin_menu_selectionne"]."')";
	if(!$connexion->query($sql)) echo "Droits menus : Pb d'accès à la table admin_menus_groupes";
	else
	{
		foreach ($connexion->query($sql) as $row) 
		{
			$_SESSION["droit"] = $row["droit"];
		}
	}
}



function GestionIdentification($connexion)
{
    if (!isset($_SESSION["id_utilisateur"]))
	{			
		$sql=$connexion->prepare('SELECT t1.id_utilisateur_groupe, t1.id_utilisateur, t1.prenom_utilisateur, t1.nom_utilisateur, t1.items_par_page, t2.id_admin_menu, t2.droit FROM '.$prefixe_bdd.'admin_utilisateurs AS t1 LEFT JOIN '.$prefixe_bdd.'admin_menus_groupes AS t2 ON t2.id_utilisateur_groupe=t1.id_utilisateur_groupe WHERE (t1.email_utilisateur=:email_utilisateur AND t1.mdp_utilisateur=:mdp_utilisateur)');

		$sql_exec=$sql->execute([":email_utilisateur"=>$_POST["email"], ":mdp_utilisateur"=>GestionHashage($_POST["mdp"])]);
		if(!$sql_exec) echo "Pb d'accès à la table utilisateurs";
		$nombre_resultats = $sql->rowCount();
		
		if($nombre_resultats==0)
		{
			$_SESSION["id_utilisateur_groupe"] = '';
			$_SESSION["message_identification"]="Nous n'avons pas trouvé d'identifiant correspondant aux données saisies. Veuillez recommencer.";
			return false;
		}
		elseif($nombre_resultats >0) 
		{
			while ($data=$sql->fetch()) 
			{
				$_SESSION["id_utilisateur_groupe"] = $data["id_utilisateur_groupe"];
				$_SESSION["id_utilisateur"] = $data["id_utilisateur"];
				$_SESSION["id_utilisateur_groupe"] = $data["id_utilisateur_groupe"];
				$_SESSION["id_membre_auteur"] = $data["id_utilisateur"];
				$_SESSION["nom_utilisateur"] = $data["nom_utilisateur"];
				$_SESSION["prenom_utilisateur"] = $data["prenom_utilisateur"];
				$_SESSION['items_par_page'] = $data["items_par_page"];				
				return true;
			}
		}
	}	
	else
	{
		return true;
	}
}

		
function GestionDate($date, $mode){ 

	if($date !='' AND $mode=='0'){
		$date = DateTime::createFromFormat('Y-m-d', $date);
		$resultat = $date->format('d-m-Y');
		return $resultat;
	}
	elseif($date !='' AND $mode=='1')
	{
		$date = DateTime::createFromFormat('d-m-Y', $date);
		$resultat = $date->format('Y-m-d');
		return $resultat;
	}
	else 
	{
		return false;
	}
}


function GestionPagination($connexion, $id_table, $nom_table){ 

	$nombre_de_pages = 0;
	$nombre_resultats = 0;
	$sql_pagination="SELECT COUNT(".$id_table.") AS nombre_resultats FROM ".$prefixe_bdd.$nom_table;
	if(!$connexion->query($sql_pagination)) echo "Pagination - Pb d'accès à la table PAGINATION";
	else
	{
		$nombre_resultats = $connexion->query($sql_pagination)->fetchColumn();
		$_SESSION["nombre_de_pages"] = ceil($nombre_resultats / $_SESSION['items_par_page']);
	}
}	

function GestionPaginationReservations($connexion, $id_table, $nom_table, $sql){ 

	$nombre_de_pages = 0;
	$nombre_resultats = 0;
	$sql_pagination=$sql;
	if(!$connexion->query($sql_pagination)) echo "Pagination - Pb d'accès à la table PAGINATION 2";
	else
	{
		$nombre_resultats = $connexion->query($sql_pagination)->fetchColumn();
		$_SESSION["nombre_de_pages"] = ceil($nombre_resultats / $_SESSION['items_par_page']);
	}
}	


function GestionSuppression($connexion, $id_table, $nom_table, $id_item, $action, $nom_table_bis='')
{
	if(isset($id_item))
	{	
		if($action=="activer"){$id_etat="1";}
		if($action=="archiver"){$id_etat="2";}
		if($action=="supprimer" OR $action=="supprimer-envoyer"){$id_etat="3";}
 
		$connexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

		$sql=$connexion->prepare("UPDATE ".$nom_table." SET id_etat=:id_etat, date_modification=:date_modification, id_membre_auteur=:id_membre_auteur WHERE (".$id_table."=:id_item)");
		$sql_exec=$sql->execute([":id_item"=>$id_item, ":id_etat"=>$id_etat, ":date_modification"=>date("Y-m-d"), ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
		if(!$sql_exec) echo "Supprimer-Archiver-Valider : Pb d'accès à la table item";
		else
		{
			if($action=="activer"){$_SESSION["message_formulaire"]="Activation enregistrée";	}
			if($action=="archiver"){$_SESSION["message_formulaire"]="Archivage enregistré";	}
			if($action=="supprimer" OR $action=="supprimer-envoyer"){$_SESSION["message_formulaire"]="Suppression enregistrée";	}
		}
	}
	else
	{
		$_SESSION["message_formulaire"]="Suppression annulée";		
	}
}


function DupliquerSessionUtilisateur($connexion, $id_utilisateur_source, $id_utilisateur_nouveau)
{
	$sql_duplication = "INSERT INTO admin_utilisateurs_session (id_utilisateur, id_admin_menu, colonne, colonne_titre_fr, largeur, ordre, sens_tri, tri_utilisateur) SELECT ".$id_utilisateur_nouveau.", id_admin_menu, colonne, colonne_titre_fr, largeur, ordre, sens_tri, tri_utilisateur FROM ".$prefixe_bdd."admin_utilisateurs_session WHERE id_utilisateur = '".$id_utilisateur_source."'";

	if(!$connexion->query($sql_duplication)) 
	{
		echo "Duplication : Pb d'accès à la table admin_utilisateurs_session"; 
		return false;
	}
	else
	{
		return true;
	}	
}



function GestionTri($connexion, $colonne, $sens_tri, $colonne_default, $items_par_page)
{
	if( ($colonne!='' AND $sens_tri!='') AND $items_par_page=='')
	{
		//print"TEST 1 colonne=".$colonne." sens_tri=".$sens_tri." items_par_page".$items_par_page." id_utilisateur=".$_SESSION["id_utilisateur"]." id_admin_menu=".$_SESSION["id_admin_menu_selectionne"]."<br/>";

		//ON REPASSE TOUS LES TRI_UTILISATEURS DE CE MENU A 0
		$sql_deselection=$connexion->prepare("UPDATE admin_utilisateurs_session SET tri_utilisateur=0 WHERE (id_utilisateur=:id_utilisateur AND id_admin_menu=:id_admin_menu)");

		$sql_exec=$sql_deselection->execute([":id_utilisateur"=>$_SESSION["id_utilisateur"], ":id_admin_menu"=>$_SESSION["id_admin_menu_selectionne"]]);
		if(!$sql_exec) {echo "Problème Gestion Tri Colonne désélection"; return false;} 

			
		// ON MET A JOUR LA COLONNE ET LE SENS DE TRI
		$sql_selection=$connexion->prepare("UPDATE admin_utilisateurs_session SET sens_tri=:sens_tri, tri_utilisateur=1 WHERE (id_utilisateur=:id_utilisateur AND id_admin_menu=:id_admin_menu AND colonne=:colonne)");

		$sql_exec=$sql_selection->execute([":colonne"=>$colonne, ":sens_tri"=>$sens_tri, ":id_utilisateur"=>$_SESSION["id_utilisateur"], ":id_admin_menu"=>$_SESSION["id_admin_menu_selectionne"]]);
		if(!$sql_exec) {echo "Problème Gestion Tri Colonne sélection"; return false;} 
	}
	else if ($colonne=='' AND $sens_tri=='' AND $items_par_page!='')
	{
		//print"TEST 2 colonne=".$colonne." sens_tri=".$sens_tri." colonne_default=".$colonne_default." items_par_page".$items_par_page."<br/>";
		
		// ON MET A JOUR LES ITEMS PAR PAGE
		$sql=$connexion->prepare("UPDATE admin_utilisateurs_session SET items_par_page=:items_par_page WHERE (id_utilisateur=:id_utilisateur AND id_admin_menu=:id_admin_menu)");

		$sql_exec=$sql->execute([":items_par_page"=>$items_par_page, ":id_utilisateur"=>$_SESSION["id_utilisateur"], ":id_admin_menu"=>$_SESSION["id_admin_menu_selectionne"]]);

		if(!$sql_exec) {echo "Problème Gestion Tri Message"; return false;} 			
	}



	//print"TEST 3 colonne=".$colonne." sens_tri=".$sens_tri." items_par_page".$items_par_page."<br/>";
	// ON CHERCHE LA COLONNE DE TRI CHOISIE PAR L'UTILISATEUR
	$sql_session="SELECT id_utilisateur, id_admin_menu, colonne, sens_tri, tri_utilisateur FROM ".$prefixe_bdd."admin_utilisateurs_session WHERE (id_admin_menu='".$_SESSION["id_admin_menu_selectionne"]."' AND id_utilisateur='".$_SESSION["id_utilisateur"]."' AND tri_utilisateur='1') LIMIT 1";

	if(!$connexion->query($sql_session)) {echo "Problème Gestion Tri Utilisateur"; return false;}
	else
	{
		foreach ($connexion->query($sql_session) as $row) 
		{
			$colonne = $row["colonne"];
		}
	} 	

	
	
	// ON RENVOIE LES DONNEES POUR L'AFFICHAGE
	$sql_session="SELECT id_utilisateur, id_admin_menu, colonne, sens_tri, items_par_page, tri_utilisateur FROM ".$prefixe_bdd."admin_utilisateurs_session WHERE (id_admin_menu='".$_SESSION["id_admin_menu_selectionne"]."' AND id_utilisateur='".$_SESSION["id_utilisateur"]."' AND colonne='".$colonne."'  AND tri_utilisateur='1') ORDER BY ordre ASC LIMIT 1";

	if(!$connexion->query($sql_session)) {echo "Problème Gestion Tri 2"; return false;}
	else
	{
		foreach ($connexion->query($sql_session) as $row) 
		{
			$_SESSION["colonne"] = $row["colonne"];
			$_SESSION["sens_tri"] = $row["sens_tri"];
			$_SESSION["tri_utilisateur"] = $row["tri_utilisateur"];			
			$_SESSION["items_par_page"] = $row["items_par_page"];
		}
	} 
	
	
	//print "<br/> S_colonne=".$_SESSION["colonne"]." S_sens_tri=".$_SESSION["sens_tri"]." S_tri_utilisateur=".$_SESSION["tri_utilisateur"]." S_items_par_page=".$_SESSION["items_par_page"];
	
}




function GestionUtilisateursEtats($id_utilisateur_groupe)
{
	if($id_utilisateur_groupe=='1'){$etat_utilisateur="99";} // Cet utilisateur a le droit de tout voir
	if($id_utilisateur_groupe=='2'){$etat_utilisateur="2";} // Cet utilisateur a le droit de voir les items ETAT=1 => Actif + Etat=2 => Archivé
	if($id_utilisateur_groupe=='3'){$etat_utilisateur="1";} // Cet utilisateur a le droit de voir les items ETAT=1 => Actif
	return $etat_utilisateur;
}
?>