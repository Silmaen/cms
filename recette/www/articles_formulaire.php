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
	$sql=$connexion->prepare("SELECT t1.id_article, t1.designation, t1.commentaire,  t1.date_creation, t1.date_modification, t1.ordre_article, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat FROM articles AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat WHERE (t1.id_article= :id_article)");
	$sql_exec=$sql->execute([":id_article"=>$id_article_selectionne]);	
	if(!$sql_exec) echo "COPIER VALIDER - DONNEES : Pb d'accès à la table articles";
	else
	{
		foreach ($sql->fetchAll() as $row) 
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
}
/* CAS MODIFIER */
elseif (isset($action_selectionne) AND $action_selectionne=="modifier") 
{
	$sql=$connexion->prepare("SELECT t1.id_article, t1.designation, t1.commentaire,  t1.date_creation, t1.date_modification, t1.ordre_article, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat FROM articles AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat WHERE (t1.id_article= :id_article)");
	$sql_exec=$sql->execute([":id_article"=>$id_article_selectionne]);	
	if(!$sql_exec) echo "MODIFIER - DONNEES : Pb d'accès à la table articles";
	else
	{
		foreach ($sql->fetchAll() as $row) 
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
			$sql=$connexion->prepare("SELECT id_fichier, nom_fichier, extension, poids, largeur FROM fichiers WHERE (id_menu=:id_menu AND id_parent=:id_parent)");
			$sql_exec=$sql->execute([":id_menu"=>$_SESSION["id_admin_menu_selectionne"], ":id_parent"=>$id_article_selectionne]);	
			if(!$sql_exec) echo "VALIDER - FICHIERS: Pb d'accès à la table fichiers";

			else
			{$i=1;
			foreach ($sql->fetchAll() as $row) 
				{	
					$row["ordre"]=$i;
					array_push($liste_initialPreviewImages,$row);
					$i++;
				}
			}
			$smarty->assign('compteur_nb_fichiers',$i);	
			$smarty->assign('liste_initialPreviewImages',$liste_initialPreviewImages);	
			$smarty->assign('liste_initialPreviewPdf',$liste_initialPreviewPdf);				
		}
	}	
}
/* CAS AJOUTER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="ajouter-valider" AND $_SESSION["droit"]==1) 
{	
	$sql=$connexion->prepare("INSERT INTO articles (designation,commentaire, date_creation, date_modification, id_etat, ordre_article, id_membre_auteur) VALUES (:designation, :commentaire, :date_creation, :date_modification, :id_etat, :ordre_article, :id_membre_auteur)");
	
	$sql_exec=$sql->execute([":designation"=>$_POST["designation"], ":commentaire"=>$_POST["commentaire"], ":date_creation"=>date("Y-m-d"), ":date_modification"=>date("Y-m-d"), ":id_etat"=>'1', ":ordre_article"=>$_POST["ordre_article"], ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
	
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
	$sql=$connexion->prepare("INSERT INTO articles (designation, commentaire, date_creation, date_modification, ordre_article, id_membre_auteur) VALUES (:designation, :commentaire, :date_creation, :date_modification, :ordre_article, :id_membre_auteur)");
	
	$sql_exec=$sql->execute([":designation"=>$_POST["designation"], ":commentaire"=>$_POST["commentaire"], ":date_creation"=>date("Y-m-d"), ":date_modification"=>date("Y-m-d"), ":ordre_article"=>$_POST["ordre_article"], ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
	
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
	$sql=$connexion->prepare("UPDATE articles SET designation=:designation, commentaire=:commentaire, date_modification=:date_modification, ordre_article=:ordre_article, id_membre_auteur=:id_membre_auteur WHERE (id_article=:id_article)");
	
	$sql_exec=$sql->execute([":id_article"=>$_POST["id_article"], ":designation"=>$_POST["designation"], ":commentaire"=>$_POST["commentaire"], ":date_modification"=>date("Y-m-d"), ":ordre_article"=>$_POST["ordre_article"], ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
	
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

	/*
	if($action_selectionne!="ajouter-valider")
	{
		header("Location:articles_liste.php?id_admin_menu=1");
		exit();
	}
	else
	{	
		$sql=$connexion->prepare("SELECT t1.id_article, t1.designation, t1.commentaire,  t1.date_creation, t1.date_modification, t1.ordre_article, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat FROM articles AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat WHERE (t1.id_article= :id_article)");
		$sql_exec=$sql->execute([":id_article"=>$id_article_selectionne]);	
		if(!$sql_exec) echo "VALIDER - DONNEES : Pb d'accès à la table articles";
		else
		{
			foreach ($sql->fetchAll() as $row) 
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
				
				// Liste des fichiers joints 
				$liste_initialPreviewImages = array();
				$liste_initialPreviewPdf = array();
				$sql=$connexion->prepare("SELECT id_fichier, nom_fichier, extension, poids, largeur FROM fichiers WHERE (id_menu=:id_menu AND id_parent=:id_parent)");
				$sql_exec=$sql->execute([":id_menu"=>$_SESSION["id_admin_menu_selectionne"], ":id_parent"=>$id_article_selectionne]);	
				if(!$sql_exec) echo "VALIDER - FICHIERS: Pb d'accès à la table fichiers";

				else
				{$i=1;
				foreach ($sql->fetchAll() as $row) 
					{	
						$row["ordre"]=$i;
						array_push($liste_initialPreviewImages,$row);
						$i++;
					}
				}
				$smarty->assign('compteur_nb_fichiers',$i);	
				$smarty->assign('liste_initialPreviewImages',$liste_initialPreviewImages);	
				$smarty->assign('liste_initialPreviewPdf',$liste_initialPreviewPdf);				
			}
		}
	}*/
}


$smarty->assign('message_formulaire',$message_formulaire);

$smarty->display('articles_formulaire.tpl');
?>