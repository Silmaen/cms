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

// Affichage du nom de l'article si celui-ci est utilisé pour la préselection.
if(isset($_GET["id_article"]) && $_GET["id_article"]!='')
{
	$sql_article_selectionne=$connexion->prepare("SELECT t1.designation, t2.quantite_totale FROM articles AS t1 LEFT JOIN inventaires_articles as t2 ON t2.id_article=t1.id_article LEFT JOIN inventaires as t3 ON t3.id_inventaire=t2.id_inventaire WHERE (t1.id_article=:id_article AND t3.id_etat=1)");
	$sql_exec=$sql_article_selectionne->execute([":id_article"=>$_GET["id_article"]]);
	if(!$sql_exec) echo "Session - Pb d'accès aux tables articles 1";
	else
	{
		foreach ($sql_article_selectionne->fetchAll() as $row_article_selectionne)
		{
			$smarty->assign('designation_article_selectionne',$row_article_selectionne["designation"]." - quantité inventaire : ".$row_article_selectionne["quantite_totale"]);
		}
	}
}
else
{
	$smarty->assign('designation_article_selectionne','');
}

// Gestion des Droits Utilisateur pour l'affichage des items par Etat
$etat_utilisateur = GestionUtilisateursEtats($_SESSION["id_utilisateur_groupe"]);

if(!isset($_GET["filtre_reservations"]) AND !isset($_SESSION["filtre_statut"]))
{
	$_GET["filtre_reservations"]=1;
}

else if(!isset($_GET["filtre_reservations"]) AND isset($_SESSION["filtre_statut"]))
{
	$_GET["filtre_reservations"]=$_SESSION["filtre_statut"];
}

// GESTION DES OPTIONS DE FILTRAGE
if($_GET["filtre_reservations"]==1)
{
	$filtre = "t1.id_etat='1' ";
	$_SESSION["filtre_statut"]=1;
}
elseif ($_GET["filtre_reservations"]==4)
{
	if($etat_utilisateur==1)
	{
		$filtre = "(t1.id_statut_reservation='1' AND t1.id_etat='1')";
	}
	else if($etat_utilisateur==2)
	{
		$filtre = "( t1.id_statut_reservation='1' AND (t1.id_etat=1 OR t1.id_etat=2) ) ";
	}
	else
	{
		$filtre = "( t1.id_statut_reservation='1' AND (t1.id_etat=1 OR t1.id_etat=2 OR t1.id_etat=3) ) ";
	}
	$_SESSION["filtre_statut"]=4;
}
elseif ($_GET["filtre_reservations"]==5)
{
	if($etat_utilisateur==1)
	{
		$filtre = "(t1.id_etat='1' AND t1.date_depart>=".date('d-m-Y', strtotime('-6 month'))." AND date_depart<=".date("Y-m-d").") ";
	}
	else if($etat_utilisateur==2)
	{
		$filtre = "(t1.id_etat=1 OR t1.id_etat=2) ";
	}
	else
	{
		$filtre = "( (t1.id_etat=1 OR t1.id_etat=2 OR t1.id_etat=3) AND (t1.date_depart>='".date('Y-m-d', strtotime('-6 month'))."' AND date_depart<='".date("Y-m-d")."')) ";
	}
	$_SESSION["filtre_statut"]=5;
}
else
{
	if($etat_utilisateur==1)
	{
		$filtre = "t1.id_etat='1' ";
	}
	else if($etat_utilisateur==2)
	{
		$filtre = "(t1.id_etat=1 OR t1.id_etat=2) ";
	}
	else
	{
		$filtre = "(t1.id_etat=1 OR t1.id_etat=2 OR t1.id_etat=3 ) ";
	}
	$_SESSION["filtre_statut"]=3;
}

// GESTION DU TRI DU TABLEAU
if((!isset($_GET['colonne']) || $_GET['colonne']=='') && (!isset($_GET['sens_tri']) || $_GET['sens_tri']=='') && (!isset($_POST['items_par_page']) || $_POST['items_par_page']==''))
{
	// Ce cas recherche les infos directement dans la BDD
	GestionTri($connexion, '', '', 'nom', '');
}
else
{
	// Ce met à jour la colonne et le sens de tri
	if((isset($_GET['colonne']) && $_GET['colonne']!='' && isset($_GET['sens_tri']) && $_GET['sens_tri']!='') && (!isset($_POST['items_par_page']) || $_POST['items_par_page']==''))
	{
		GestionTri($connexion, $_GET['colonne'], $_GET['sens_tri'], 'nom', '');
	}
	else
	{
		// Ce met à jour les items par page
		GestionTri($connexion, '', '', 'nom', $_POST['items_par_page']);
	}
}

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
if(isset($_GET["action"]) AND ($_GET["action"]=="supprimer" OR $_GET["action"]=="supprimer-envoyer" OR $_GET["action"]=="archiver" OR $_GET["action"]=="activer" ) AND $_SESSION["droit"]==1)
{
	GestionSuppression($connexion, $_SESSION['id_table'], $_SESSION['nom_table'], $_GET["id_reservation"], '',$_GET["action"]);

	if($_GET["action"]=="supprimer-envoyer")
	{
		$nom = "";
		$prenom = "";
		$email_temp = "";

		// Recherche de l'email de l'utilisateur pour validation
		$sql=$connexion->prepare("SELECT t1.id_reservation, t1.id_client, t1.date_depart, t1.date_retour, t2.cle_client, t2.nom, t2.prenom, t2.email FROM reservations AS t1 LEFT JOIN clients AS t2 ON t2.id_client=t1.id_client WHERE (id_reservation= :id_reservation)");
		$sql_exec=$sql->execute([":id_reservation"=>$_GET["id_reservation"]]);
		if(!$sql_exec) echo "Consultation: Pb d'accès à la table reservations et clients";
		else
		{
			foreach ($sql->fetchAll() as $row)
			{
				$nom = $row["nom"];
				$prenom = $row["prenom"];
				$email_temp = $row["email"];
			}
		}

		if($email_temp!="")
		{
			$encoding = "utf-8";
			$from_name = "Comité des Fêtes de Genay";
			$from_mail = "no-reply@cdf-genay.com";
			$mail_subject = $prenom." ".$nom." : Annulation de votre réservation auprès du Comité des Fêtes de Genay";
			$mail_to = $email_temp;
			$mail_to = $email_temp;
			$mail_message =  "Bonjour ".$prenom." ".$nom.",
				<br /><br /> Nous vous confirmons l'annulation de votre réservation du ".GestionDate($row["date_depart"],'0')." au ".GestionDate($row["date_retour"],'0')." réalisée auprès du Comité des Fêtes de Genay.
				<br /><br /> Cordialement,
				<br /><br /> Comité des Fêtes de Genay
				<br /><br /> http://www.cdf-genay.com";

			// Preferences for Subject field
			$subject_preferences = array(
				"input-charset" => $encoding,
				"output-charset" => $encoding,
				"line-length" => 76,
				"line-break-chars" => "\r\n"
			);

			// Mail header
			$header = "Content-type: text/html; charset=".$encoding." \r\n";
			$header .= "From: ".$from_name." <".$from_mail."> \r\n";
			$header .= "MIME-Version: 1.0 \r\n";
			$header .= "Content-Transfer-Encoding: 8bit \r\n";
			$header .= "Bcc: no-reply@cdf-genay.com \r\n";
			$header .= "Date: ".date("r (T)")." \r\n";
			$header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);

			// Send mail
			mail($mail_to, $mail_subject, $mail_message, $header);
		}
	}
}

// GESTION DU TABLEAU LISTE
$liste_items = array();
if(isset($_GET["id_client"]) && $_GET["id_client"]!='')
{
	$sql_items="SELECT DISTINCT (t1.id_reservation), t1.date_creation, t1.date_depart, t1.date_retour, t1.date_modification, t1.heure_modification, t1.id_etat, t2.association, t2.nom, t2.prenom, t2.ville, t2.email FROM reservations AS t1 LEFT JOIN clients AS t2 ON t2.id_client=t1.id_client WHERE ".$filtre." AND t1.id_client=".$_GET["id_client"]." ORDER BY ".$_SESSION["colonne"]." ".$_SESSION["sens_tri"]." , t1.date_depart DESC LIMIT ".$premiereEntree.", ".$_SESSION["items_par_page"];
	$sql_pagination="SELECT COUNT(t1.id_reservation) AS nombre_resultats FROM reservations AS t1 LEFT JOIN clients AS t2 ON t2.id_client=t1.id_client WHERE ".$filtre." AND t1.id_client=".$_GET["id_client"]." ORDER BY ".$_SESSION["colonne"]." ".$_SESSION["sens_tri"]." , t1.date_depart DESC LIMIT ".$premiereEntree.", ".$_SESSION["items_par_page"];

}
else if(isset($_GET["id_article"]) && $_GET["id_article"]!='')
{
	$sql_items="SELECT DISTINCT (t1.id_reservation), t1.date_creation, t1.date_depart, t1.date_retour, t1.heure_modification, t1.date_modification, t2.quantite_reservee, t1.id_etat, t3.designation, t4.association, t4.nom, t4.prenom, t4.ville, t4.email FROM reservations AS t1 LEFT JOIN reservations_articles AS t2 ON t2.id_reservation=t1.id_reservation LEFT JOIN articles AS t3 ON t3.id_article=t2.id_article LEFT JOIN clients AS t4 ON t4.id_client=t1.id_client WHERE ".$filtre." AND t2.id_article=".$_GET["id_article"]." AND t2.quantite_reservee>0 ORDER BY ".$_SESSION["colonne"]." ".$_SESSION["sens_tri"].", t1.date_depart DESC LIMIT ".$premiereEntree.", ".$_SESSION["items_par_page"];
	$sql_pagination="SELECT COUNT(t1.id_reservation) AS nombre_resultats FROM reservations AS t1 LEFT JOIN reservations_articles AS t2 ON t2.id_reservation=t1.id_reservation LEFT JOIN articles AS t3 ON t3.id_article=t2.id_article LEFT JOIN clients AS t4 ON t4.id_client=t1.id_client WHERE ".$filtre." AND t2.id_article=".$_GET["id_article"]." ORDER BY ".$_SESSION["colonne"]." ".$_SESSION["sens_tri"].", t1.date_depart DESC LIMIT ".$premiereEntree.", ".$_SESSION["items_par_page"];

}
else
{
	$sql_items="SELECT DISTINCT (t1.id_reservation), t1.date_creation, t1.date_depart, t1.date_retour, t1.date_modification, t1.heure_modification, t1.id_etat, t2.association, t2.nom, t2.prenom, t2.ville, t2.email FROM reservations AS t1 LEFT JOIN clients AS t2 ON t2.id_client=t1.id_client WHERE ".$filtre." ORDER BY ".$_SESSION["colonne"]." ".$_SESSION["sens_tri"].", t1.date_depart DESC LIMIT ".$premiereEntree.", ".$_SESSION["items_par_page"];
	$sql_pagination="SELECT COUNT(t1.id_reservation) AS nombre_resultats FROM reservations AS t1 LEFT JOIN clients AS t2 ON t2.id_client=t1.id_client WHERE ".$filtre." ORDER BY ".$_SESSION["colonne"]." ".$_SESSION["sens_tri"].", t1.date_depart DESC LIMIT ".$premiereEntree.", ".$_SESSION["items_par_page"];
}

if(!$connexion->query($sql_items)) echo "LISTE : Pb d'accès à la table ITEMS";
else
{
	foreach ($connexion->query($sql_items) as $row_items)
	{
		$row_items["libelle"] = "";
		if($row_items["association"]!=""){$row_items["libelle"] .= $row_items["association"]." - ";}
		if($row_items["nom"]!=""){$row_items["libelle"] .= $row_items["nom"];}
		if($row_items["prenom"]!=""){$row_items["libelle"] .= " ".$row_items["prenom"];}
		if($row_items["ville"]!=""){$row_items["libelle"] .= " - ".$row_items["ville"];}

		array_push($liste_items,$row_items);
	}
}

// GESTION DE LA PAGINATION
GestionPagination($connexion, $_SESSION['id_table'], $_SESSION['nom_table']);

$smarty->assign('liste_items',$liste_items);
$smarty->assign('message_formulaire',$message_formulaire);
$smarty->display("reservations_liste.tpl");
?>
