<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');
require_once('../metier/commun.php');
require_once('../metier/articles.php');

/* TEST CONNEXION IDENTIFIANT */
if (!gestionIdentification($connexion))
{
	header("Location:index.php");
	exit();
}

/* GESTION DU MENU */
$smarty->assign('id_admin_menu_selectionne',$_SESSION["id_admin_menu_selectionne"]);
$smarty->assign('nom_table','articles');
$smarty->assign('titre_menu','Gestion d\'un article');
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
	if(isset($_GET["id_article"]))
	{
		$id_article_selectionne=$_GET["id_article"];
	}
	else
	{
		$id_article_selectionne=0;
	}
}
elseif(isset($_POST["action"]))
{;
	$action_selectionne=$_POST["action"];
	$id_article_selectionne=$_POST["id_article"];
}
/* message_formulaire par défaut */
$message_formulaire="";

/* CAS AJOUTER */
if(isset($action_selectionne) AND $action_selectionne=="ajouter")
{
	$smarty->assign('id_article','0');
	$smarty->assign('designation','');
	$smarty->assign('commentaire','');
	$smarty->assign('libelle_etat','Actif');
	$smarty->assign('date_creation',date("d-m-Y"));
	$smarty->assign('date_modification',date("d-m-Y"));
	$smarty->assign('ordre_article','0');
	$smarty->assign('id_membre_auteur',$_SESSION["id_utilisateur"]);
	$smarty->assign('modifie_par',$_SESSION["nom_utilisateur"]." ".$_SESSION["prenom_utilisateur"]);
	$smarty->assign('action','ajouter-valider');

	$message_formulaire="Formulaire d'ajout";
}
/* CAS COPIER */
elseif (isset($action_selectionne) AND $action_selectionne=="copier")
{
	$row = ArticleLire($connexion, $id_article_selectionne);
	if($row===false) echo "COPIER VALIDER - DONNEES : Pb d'accès à la table articles";
	elseif($row!==null)
	{
		$smarty->assign('id_article',$row["id_article"]);
		$smarty->assign('designation','');
		$smarty->assign('commentaire',$row["commentaire"]);
		$smarty->assign('libelle_etat',$row["libelle_etat"]);
		$smarty->assign('date_creation',GestionDate($row["date_creation"],'0'));
		$smarty->assign('date_modification',GestionDate($row["date_modification"],'0'));
		$smarty->assign('ordre_article',$row["ordre_article"]);
		$smarty->assign('id_membre_auteur',$row["id_membre_auteur"]);
		$smarty->assign('modifie_par',$row["nom_membre_auteur"]." ".$row["prenom_membre_auteur"]);
		$smarty->assign('action','copier-valider');
		$message_formulaire="Formulaire de duplication";
	}
}
/* CAS MODIFIER */
elseif (isset($action_selectionne) AND $action_selectionne=="modifier")
{
	$row = ArticleLire($connexion, $id_article_selectionne);
	if($row===false) echo "MODIFIER - DONNEES : Pb d'accès à la table articles";
	elseif($row!==null)
	{
		$smarty->assign('id_article',$row["id_article"]);
		$smarty->assign('designation',$row["designation"]);
		$smarty->assign('commentaire',$row["commentaire"]);
		$smarty->assign('libelle_etat',$row["libelle_etat"]);
		$smarty->assign('date_creation',GestionDate($row["date_creation"],'0'));
		$smarty->assign('date_modification',GestionDate($row["date_modification"],'0'));
		$smarty->assign('ordre_article',$row["ordre_article"]);
		$smarty->assign('id_membre_auteur',$row["id_membre_auteur"]);
		$smarty->assign('modifie_par',$row["nom_membre_auteur"]." ".$row["prenom_membre_auteur"]);
		$smarty->assign('action','modifier-valider');
		$message_formulaire="Formulaire de modification";

		/* Liste des fichiers joints */
		$liste_initialPreviewImages = array();
		$liste_initialPreviewPdf = array();
		$fichiers = FichiersJointsLire($connexion, $_SESSION["id_admin_menu_selectionne"], $id_article_selectionne);
		if($fichiers===false) echo "VALIDER - FICHIERS: Pb d'accès à la table fichiers";
		else
		{
			$i=1;
			foreach ($fichiers as $row_fichier)
			{
				$row_fichier["ordre"]=$i;
				array_push($liste_initialPreviewImages,$row_fichier);
				$i++;
			}
		}
		$smarty->assign('compteur_nb_fichiers',$i);
		$smarty->assign('liste_initialPreviewImages',$liste_initialPreviewImages);
		$smarty->assign('liste_initialPreviewPdf',$liste_initialPreviewPdf);
	}
}
/* CAS AJOUTER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="ajouter-valider" AND $_SESSION["droit"]==1)
{
	$id_article_selectionne = ArticleAjouter($connexion, $_POST["designation"], $_POST["commentaire"], $_POST["ordre_article"], $_SESSION["id_membre_auteur"]);
	$sql_exec = ($id_article_selectionne !== false);
	if(!$sql_exec) echo "Ajouter-Valider : Pb d'accès à la table articles";
	else
	{
		$id_article_selectionne = $connexion->lastInsertId();
		$message_formulaire="Ajout enregistré";
	}
}
/* CAS COPIER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="copier-valider" AND $_SESSION["droit"]==1)
{
	$id_article_selectionne = ArticleCopier($connexion, $_POST["designation"], $_POST["commentaire"], $_POST["ordre_article"], $_SESSION["id_membre_auteur"]);
	$sql_exec = ($id_article_selectionne !== false);
	if(!$sql_exec) echo "Copier-Valider : Pb d'accès à la table articles";
	else
	{
		$id_article_selectionne = $connexion->lastInsertId();
		$message_formulaire="Ajout enregistré";
	}
}
/* CAS MODIFIER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="modifier-valider" AND $_SESSION["droit"]==1)
{
	$sql_exec = ArticleModifier($connexion, $_POST["id_article"], $_POST["designation"], $_POST["commentaire"], $_POST["ordre_article"], $_SESSION["id_membre_auteur"]);
	if(!$sql_exec) echo "Modifier-Valider : Pb d'accès à la table articles";
	else
	{
		$message_formulaire="Modification enregistrée";
	}
}

/* AFFICHAGE DES DONNEES APRES VALIDATION*/
if($action_selectionne=="ajouter-valider" OR $action_selectionne=="copier-valider" OR $action_selectionne=="modifier-valider" )
{
	header("Location:articles_liste.php?id_admin_menu=3");
	exit();
}

$smarty->assign('message_formulaire',$message_formulaire);

$smarty->display('articles_formulaire.tpl');
?>
