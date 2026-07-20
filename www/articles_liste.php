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
if(isset($_GET["id_admin_menu"]))
{
	$_SESSION["id_admin_menu_selectionne"] = $_GET["id_admin_menu"];
}

$smarty->assign('id_admin_menu_selectionne',$_SESSION["id_admin_menu_selectionne"]);
$smarty->assign('liste_items_menu',gestionMenu($connexion));

// GESTION DES DROITS UTILISATEURS SUR CE MENU
GestionMenusDroits($connexion);

// CREATION DE LA PAGE
$liste_page = array();
$sql_page=$connexion->prepare("SELECT t1.titre_fr, t1.titre_page_fr, t1.nom_table, t1.id_table, t2.colonne, t2.colonne_titre_fr, t2.largeur, t2.ordre FROM admin_menu AS t1 LEFT JOIN admin_utilisateurs_session AS t2 ON t1.id_admin_menu = t2.id_admin_menu WHERE (t1.id_admin_menu=:id_admin_menu AND t2.id_utilisateur=:id_utilisateur) ORDER BY t2.ordre");
$sql_exec=$sql_page->execute([":id_admin_menu"=>$_SESSION["id_admin_menu_selectionne"], ":id_utilisateur"=>$_SESSION["id_utilisateur"]]);
if(!$sql_exec) echo "Session - Pb d'accès aux tables admin_menu et admin_utilisateurs_session";
else
{
	foreach ($sql_page->fetchAll() as $row) 
	{
		if($row["ordre"]==1){$_SESSION['colonne_1'] = $row["colonne"];}
		if($row["ordre"]==2){$_SESSION['colonne_2'] = $row["colonne"];}
		if($row["ordre"]==3){$_SESSION['colonne_3'] = $row["colonne"];}
		if($row["ordre"]==4){$_SESSION['colonne_4'] = $row["colonne"];}
		if($row["ordre"]==5){$_SESSION['colonne_5'] = $row["colonne"];}
		if($row["ordre"]==6){$_SESSION['colonne_6'] = $row["colonne"];}
		$_SESSION['breadcrumb'] = $row["titre_fr"];
		$_SESSION['nom_table'] = $row["nom_table"];
		$_SESSION['id_table'] = $row["id_table"];
		$_SESSION['titre_page_fr'] = $row["titre_page_fr"];
		array_push($liste_page,$row);
	}
}
$smarty->assign('liste_page',$liste_page);


// GESTION DU TRI DU TABLEAU
if((!isset($_GET['colonne']) || $_GET['colonne']=='') && (!isset($_GET['sens_tri']) || $_GET['sens_tri']=='') && (!isset($_POST['items_par_page']) || $_POST['items_par_page']==''))
{
	// Ce cas recherche les infos directement dans la BDD
	GestionTri($connexion, '', '', 'designation', '');
}
else
{
	// Ce met à jour la colonne et le sens de tri
	if((isset($_GET['colonne']) && $_GET['colonne']!='' && isset($_GET['sens_tri']) && $_GET['sens_tri']!='') && (!isset($_POST['items_par_page']) || $_POST['items_par_page']==''))
	{
		GestionTri($connexion, $_GET['colonne'], $_GET['sens_tri'], 'designation', '');
	}
	else
	{
		// Ce met à jour les items par page
		GestionTri($connexion, '', '', 'designation', $_POST['items_par_page']);
	}
}


// GESTION DE LA PAGINATION
GestionPagination($connexion, $_SESSION['id_table'], $_SESSION['nom_table']);


// Message par défaut 
$message_formulaire ="";


// CALCULS POUR L'AFFICHAGE DES BONNES DONNEES DANS LE TABLEAU 
if(isset($_GET['page'])) // Si la variable $_GET['page'] existe...
{
     $page_actuelle=intval($_GET['page']);
     if($page_actuelle>$_SESSION['nombre_de_pages']) // Si la valeur de $page_actuelle (le numéro de la page) est plus grande que $nombre_de_pages...
     {
          $page_actuelle=$_SESSION['nombre_de_pages'];
     }
}
else
{
     $page_actuelle=1; // La page actuelle est la n°1    
}
$premiereEntree=($page_actuelle-1)*$_SESSION['items_par_page'];/* GESTION DE LA PAGINATION */
if(isset($_GET['page'])) // Si la variable $_GET['page'] existe...
{
     
     if($page_actuelle>$_SESSION['nombre_de_pages']) // Si la valeur de $page_actuelle (le numéro de la page) est plus grande que $nombre_de_pages...
     {
          $page_actuelle=$_SESSION['nombre_de_pages'];
     }
}
else
{
     $page_actuelle=1; // La page actuelle est la n°1    
}
$premiereEntree=($page_actuelle-1)*$_SESSION['items_par_page'];
$smarty->assign('page_actuelle',$page_actuelle);


// GESTION DE LA SUPPRESSION
if(isset($_GET["action"]) AND ($_GET["action"]=="supprimer" OR $_GET["action"]=="archiver" OR $_GET["action"]=="activer" ) AND $_SESSION["droit"]==1)
{
	GestionSuppression($connexion, $_SESSION['id_table'], $_SESSION['nom_table'], $_GET["id_article"], '',$_GET["action"]);
}

// Gestion des Droits Utilisateur pour l'affichage des items par Etat
$etat_utilisateur = GestionUtilisateursEtats($_SESSION["id_utilisateur_groupe"]);

// RECUPERATION DE L'INVENTAIRE LE PLUS RECENT
$sql=$connexion->prepare("SELECT id_inventaire FROM inventaires ORDER BY date_inventaire DESC LIMIT 1");
$sql_exec=$sql->execute();	
if(!$sql_exec) echo "INVENTAIRE : Pb d'accès à la table INVENTAIRES";
else
{
	foreach ($sql->fetchAll() as $row) 
	{
		$id_inventaire_selectionne = $row["id_inventaire"];
	}
}


// RECUPERATION DE L'INVENTAIRE LE PLUS RECENT
$smarty->assign('date_dernier_inventaire','01-01-2019');
$sql=$connexion->prepare("SELECT id_inventaire, date_inventaire FROM inventaires WHERE id_etat='1' ORDER BY date_inventaire DESC LIMIT 1");
$sql_exec=$sql->execute();	
if(!$sql_exec) echo "INVENTAIRE : Pb d'accès à la table INVENTAIRES";
else
{
	foreach ($sql->fetchAll() as $row) 
	{
		$id_inventaire_selectionne = $row["id_inventaire"];
		$smarty->assign('date_dernier_inventaire',GestionDate($row["date_inventaire"],'0'));
	}
}

// Gestion des Droits Utilisateur pour l'affichage des items par Etat
$etat_utilisateur = GestionUtilisateursEtats($_SESSION["id_utilisateur_groupe"]);


// création de la liste des articles
$liste_items = array();
$sql_items="SELECT * FROM articles WHERE id_etat<=".$etat_utilisateur." ORDER BY ".$_SESSION["colonne"]." ".$_SESSION["sens_tri"]." LIMIT ".$premiereEntree.", ".$_SESSION["items_par_page"];
if(!$connexion->query($sql_items)) echo "LISTE : Pb d'accès à la table ITEMS";
else
{
	foreach ($connexion->query($sql_items) as $row_items) 
	{	
		$sql_inventaires="SELECT t1.quantite_totale FROM inventaires_articles AS t1 LEFT JOIN inventaires AS t2 ON t2.id_inventaire=t1.id_inventaire WHERE t2.id_inventaire=".$id_inventaire_selectionne." AND t1.id_article=".$row_items["id_article"];
		if(!$connexion->query($sql_inventaires)) echo "LISTE 2: Pb d'accès à la table ITEMS";
		else
		{
			$row_items["quantite_totale"] = 0;
			$row_items["quantite_actuelle"] = 0;
			
			foreach ($connexion->query($sql_inventaires) as $row_inventaires) 
			{
				$row_items["quantite_totale"] = $row_inventaires["quantite_totale"];
				
				$sql_stock=$connexion->prepare("SELECT SUM(t1.quantite_reservee) as total_reserve FROM reservations_articles AS t1 LEFT JOIN reservations AS t2 ON t2.id_reservation=t1.id_reservation WHERE (t1.id_article=:id_article AND date_depart<'".date('Y-m-d')."' AND t2.date_retour>'".date('Y-m-d')."')");
				$sql_exec=$sql_stock->execute([":id_article"=>$row_items["id_article"]]);
				if(!$sql_exec) echo "STOCK : Pb d'accès à la table articles";
				else
				{
					foreach ($sql_stock->fetchAll() as $row_stock) 
					{
						$row_items["quantite_actuelle"]=($row_items["quantite_totale"] - $row_stock["total_reserve"]);
					}
				}
				
				
				
				
				
			}
			array_push($liste_items,$row_items);		
		}
	}
}
$smarty->assign('liste_items',$liste_items);


$smarty->assign('message_formulaire',$message_formulaire);
$smarty->display("articles_liste.tpl");
?>