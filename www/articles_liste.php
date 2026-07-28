<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');
require_once('../metier/commun.php');
require_once('../metier/articles.php');
require_once('../metier/inventaires.php');

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

// CALCULS POUR L'AFFICHAGE (pagination) — extrait dans PaginationOffset()
$pagination = PaginationOffset(isset($_GET['page']) ? $_GET['page'] : null, $_SESSION['nombre_de_pages'], $_SESSION['items_par_page']);
$page_actuelle = $pagination['page'];
$premiereEntree = $pagination['offset'];
$smarty->assign('page_actuelle',$page_actuelle);

// GESTION DE LA SUPPRESSION
if(isset($_GET["action"]) AND ($_GET["action"]=="supprimer" OR $_GET["action"]=="archiver" OR $_GET["action"]=="activer" ) AND $_SESSION["droit"]==1)
{
	GestionSuppression($connexion, $_SESSION['id_table'], $_SESSION['nom_table'], $_GET["id_article"], '',$_GET["action"]);
}

// Gestion des Droits Utilisateur pour l'affichage des items par Etat
$etat_utilisateur = GestionUtilisateursEtats($_SESSION["id_utilisateur_groupe"]);

// RÉCUPÉRATION DE L'INVENTAIRE LE PLUS RÉCENT (extrait dans InventaireLePlusRecent)
$row_inv = InventaireLePlusRecent($connexion, false);
if ($row_inv !== null) { $id_inventaire_selectionne = $row_inv["id_inventaire"]; }

$smarty->assign('date_dernier_inventaire','01-01-2019');
$row_inv = InventaireLePlusRecent($connexion, true);
if ($row_inv !== null) {
	$id_inventaire_selectionne = $row_inv["id_inventaire"];
	$smarty->assign('date_dernier_inventaire',GestionDate($row_inv["date_inventaire"],'0'));
}

// Gestion des Droits Utilisateur pour l'affichage des items par Etat
$etat_utilisateur = GestionUtilisateursEtats($_SESSION["id_utilisateur_groupe"]);

// création de la liste des articles
$liste_items = array();
foreach (ArticlesLister($connexion, $etat_utilisateur, $_SESSION["colonne"], $_SESSION["sens_tri"], $premiereEntree, $_SESSION["items_par_page"]) as $row_items)
{
	$qteTotale = ArticleQuantiteTotale($connexion, $id_inventaire_selectionne, $row_items["id_article"]);
	if ($qteTotale !== false) { // requête inventaire OK → on conserve la ligne
		$row_items["quantite_totale"] = 0;
		$row_items["quantite_actuelle"] = 0;
		if ($qteTotale !== null) { // l'article figure dans l'inventaire
			$row_items["quantite_totale"] = $qteTotale;
			$reservee = ArticleQuantiteReservee($connexion, $row_items["id_article"], date('Y-m-d'));
			if ($reservee !== false) {
				$row_items["quantite_actuelle"] = $row_items["quantite_totale"] - $reservee;
			}
		}
		array_push($liste_items, $row_items);
	}
}
$smarty->assign('liste_items',$liste_items);
$smarty->assign('message_formulaire',$message_formulaire);
$smarty->display("articles_liste.tpl");
?>
