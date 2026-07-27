<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');
require_once('../metier/inventaires.php');
require_once('../metier/clients.php');

/* TEST CONNEXION IDENTIFIANT */
if (!gestionIdentification($connexion))
{
	header("Location:index.php");
	exit();
}

/* GESTION DU MENU */
$smarty->assign('id_admin_menu_selectionne',$_SESSION["id_admin_menu_selectionne"]);
$smarty->assign('nom_table','inventaires');
$smarty->assign('titre_menu','Gestion d\'un inventaire');
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
	if(isset($_GET["id_inventaire"]))
	{
		$id_inventaire_selectionne=$_GET["id_inventaire"];
	}
	else
	{
		$id_inventaire_selectionne=0;
	}
}
elseif(isset($_POST["action"]))
{;
	$action_selectionne=$_POST["action"];
	$id_inventaire_selectionne=$_POST["id_inventaire"];
}

// message_formulaire par défaut
$message_formulaire="";

// CALCUL DU NOMBRE DE LIGNES D'ARTICLES A ENREGISTRER
$nb_articles_actifs = 0;
foreach( $_POST as $cle=>$value )
{
	if (substr($cle, 0, 10)=='qtetotale_')
	{
		$nb_articles_actifs ++;
	}
}

if($action_selectionne!="modifier" AND $action_selectionne=="copier")
{
	// RECUPERATION DE L'INVENTAIRE LE PLUS RECENT
	$smarty->assign('date_dernier_inventaire','01-01-2019');
	$row_inv = InventaireLePlusRecent($connexion, true);
	if($row_inv!==null)
	{
		$id_inventaire_selectionne = $row_inv["id_inventaire"];
		$smarty->assign('date_dernier_inventaire',GestionDate($row_inv["date_inventaire"],'0'));
	}
}

// CAS AJOUTER
if(isset($action_selectionne) AND $action_selectionne=="ajouter")
{
	$smarty->assign('id_inventaire','0');
	$smarty->assign('date_inventaire',date("d-m-Y"));
	$smarty->assign('commentaire','');
	$smarty->assign('libelle_etat','Actif');
	$smarty->assign('id_statut_inventaire','1');
	$smarty->assign('libelle_statut','En attente');
	$smarty->assign('id_type_inventaire','1');
	$smarty->assign('libelle_type','Roulant');
	$smarty->assign('date_creation',date("d-m-Y"));
	$smarty->assign('date_modification',date("d-m-Y"));
	$smarty->assign('id_membre_auteur',$_SESSION["id_utilisateur"]);
	$smarty->assign('modifie_par',$_SESSION["nom_utilisateur"]." ".$_SESSION["prenom_utilisateur"]);
	$smarty->assign('action','ajouter-valider');

	// création de la liste des articles
	$liste_articles = array();
	$sql_articles=$connexion->prepare("SELECT t1.id_article, t1.designation FROM articles AS t1 WHERE t1.id_etat='1' ORDER BY t1.designation ASC");
	$sql_exec=$sql_articles->execute();
	if(!$sql_exec) echo "LISTE : Pb d'accès à la table articles";
	else
	{
		foreach ($sql_articles->fetchAll() as $row_articles)
		{
			$sql_inventaires=$connexion->prepare("SELECT t1.id_article, t1.quantite_precedent, t1.quantite_totale, t1.commentaire FROM inventaires_articles AS t1 LEFT JOIN inventaires AS t2 ON t2.id_inventaire=t1.id_inventaire WHERE t2.id_inventaire=:id_inventaire AND t1.id_article=:id_article");
			$sql_exec_inventaires=$sql_inventaires->execute([":id_inventaire"=>$id_inventaire_selectionne, ":id_article"=>$row_articles["id_article"]]);
			if(!$sql_exec) echo "LISTE INVENTAIRES: Pb d'accès à la table inventaires";
			else
			{
				$row_articles["commentaire"] = ""; // valeur par defaut
				$row_articles["quantite_precedent"] = 0;
				$row_articles["quantite_totale"] = 0;
				$row_articles["ecart"] = 0; // valeur par defaut

				foreach ($sql_inventaires->fetchAll() as $row_inventaires)
				{
					$row_articles["quantite_precedent"] = $row_inventaires["quantite_totale"];
					$row_articles["quantite_totale"] = $row_inventaires["quantite_totale"];
					$row_articles["commentaire"] = $row_inventaires["commentaire"];
					$row_articles["ecart"] = $row_articles["quantite_totale"] - $row_articles["quantite_precedent"];
				}
				$row_articles["ecart"] = "";
				$row_articles["commentaire"] = "";
				array_push($liste_articles,$row_articles);
			}
		}
	}
	$smarty->assign('liste_articles',$liste_articles);

	$message_formulaire="Formulaire d'ajout";
}
// CAS COPIER
elseif (isset($action_selectionne) AND $action_selectionne=="copier")
{
	$row = InventaireLire($connexion, $id_inventaire_selectionne);
	if($row===false) echo "COPIER - DONNEES : Pb d'accès à la table inventaires";
	elseif($row!==null)
	{
			$smarty->assign('id_inventaire',$row["id_inventaire"]);
			$smarty->assign('date_inventaire','');
			$smarty->assign('commentaire',$row["commentaire"]);
			$smarty->assign('libelle_etat',$row["libelle_etat"]);
			$smarty->assign('id_statut_inventaire',$row["id_statut_inventaire"]);
			$smarty->assign('libelle_statut',$row["libelle_statut"]);
			$smarty->assign('id_type_inventaire',$row["id_type_inventaire"]);
			$smarty->assign('libelle_type',$row["libelle_type"]);
			$smarty->assign('date_creation',GestionDate($row["date_creation"],'0'));
			$smarty->assign('date_modification',GestionDate($row["date_modification"],'0'));
			$smarty->assign('id_membre_auteur',$row["id_membre_auteur"]);
			$smarty->assign('modifie_par',$row["nom_membre_auteur"]." ".$row["prenom_membre_auteur"]);
			$smarty->assign('action','copier-valider');
			$message_formulaire="Formulaire de duplication";

			// création de la liste des articles
			$liste_articles = array();
			$sql_articles=$connexion->prepare("SELECT t1.id_article, t1.designation FROM articles AS t1 WHERE t1.id_etat='1' ORDER BY t1.designation ASC");
			$sql_exec=$sql_articles->execute();
			if(!$sql_exec) echo "LISTE : Pb d'accès à la table articles";
			else
			{
				foreach ($sql_articles->fetchAll() as $row_articles)
				{
					$sql_inventaires=$connexion->prepare("SELECT t1.id_article, t1.quantite_precedent, t1.quantite_totale, t1.commentaire FROM inventaires_articles AS t1 LEFT JOIN inventaires AS t2 ON t2.id_inventaire=t1.id_inventaire WHERE t2.id_inventaire=:id_inventaire AND t1.id_article=:id_article");
					$sql_exec_inventaires=$sql_inventaires->execute([":id_inventaire"=>$id_inventaire_selectionne, ":id_article"=>$row_articles["id_article"]]);
					if(!$sql_exec) echo "LISTE INVENTAIRES: Pb d'accès à la table inventaires";
					else
					{
						$row_articles["commentaire"] = ""; // valeur par defaut
						$row_articles["quantite_precedent"] = 0;
						$row_articles["quantite_totale"] = 0;
						$row_articles["ecart"] = 0; // valeur par defaut

						foreach ($sql_inventaires->fetchAll() as $row_inventaires)
						{
							$row_articles["quantite_precedent"] = $row_inventaires["quantite_precedent"];
							$row_articles["quantite_totale"] = $row_inventaires["quantite_totale"];
							$row_articles["commentaire"] = $row_inventaires["commentaire"];
							$row_articles["ecart"] = $row_articles["quantite_totale"] - $row_articles["quantite_precedent"];
						}

						array_push($liste_articles,$row_articles);
					}
				}
			}
		$smarty->assign('liste_articles',$liste_articles);
	}
}
// CAS MODIFIER
elseif (isset($action_selectionne) AND $action_selectionne=="modifier")
{
	$row = InventaireLire($connexion, $id_inventaire_selectionne);
	if($row===false) echo "MODIFIER - DONNEES : Pb d'accès à la table inventaires";
	elseif($row!==null)
	{
			$smarty->assign('id_inventaire',$row["id_inventaire"]);
			$smarty->assign('date_inventaire',GestionDate($row["date_inventaire"],'0'));
			$smarty->assign('commentaire',$row["commentaire"]);
			$smarty->assign('libelle_etat',$row["libelle_etat"]);
			$smarty->assign('id_statut_inventaire',$row["id_statut_inventaire"]);
			$smarty->assign('libelle_statut',$row["libelle_statut"]);
			$smarty->assign('id_type_inventaire',$row["id_type_inventaire"]);
			$smarty->assign('libelle_type',$row["libelle_type"]);
			$smarty->assign('date_creation',GestionDate($row["date_creation"],'0'));
			$smarty->assign('date_modification',GestionDate($row["date_modification"],'0'));
			$smarty->assign('id_membre_auteur',$row["id_membre_auteur"]);
			$smarty->assign('modifie_par',$row["nom_membre_auteur"]." ".$row["prenom_membre_auteur"]);
			$smarty->assign('action','modifier-valider');
			$message_formulaire="Formulaire de modification";

			// création de la liste des articles
			$liste_articles = array();
			$sql_articles=$connexion->prepare("SELECT t1.id_article, t1.designation FROM articles AS t1 WHERE t1.id_etat='1' ORDER BY t1.designation ASC");
			$sql_exec=$sql_articles->execute();
			if(!$sql_exec) echo "LISTE : Pb d'accès à la table articles";
			else
			{
				foreach ($sql_articles->fetchAll() as $row_articles)
				{
					$sql_inventaires=$connexion->prepare("SELECT t1.id_article, t1.quantite_precedent, t1.quantite_totale, t1.commentaire FROM inventaires_articles AS t1 LEFT JOIN inventaires AS t2 ON t2.id_inventaire=t1.id_inventaire WHERE t2.id_inventaire=:id_inventaire AND t1.id_article=:id_article");
					$sql_exec_inventaires=$sql_inventaires->execute([":id_inventaire"=>$id_inventaire_selectionne, ":id_article"=>$row_articles["id_article"]]);
					if(!$sql_exec) echo "LISTE INVENTAIRES: Pb d'accès à la table inventaires";
					else
					{
						$row_articles["commentaire"] = ""; // valeur par defaut
						$row_articles["quantite_precedent"] = 0;
						$row_articles["quantite_totale"] = 0;
						$row_articles["ecart"] = 0; // valeur par defaut

						foreach ($sql_inventaires->fetchAll() as $row_inventaires)
						{
							$row_articles["quantite_precedent"] = $row_inventaires["quantite_precedent"];
							$row_articles["quantite_totale"] = $row_inventaires["quantite_totale"];
							$row_articles["commentaire"] = $row_inventaires["commentaire"];
							$row_articles["ecart"] = $row_articles["quantite_totale"] - $row_articles["quantite_precedent"];
						}

						array_push($liste_articles,$row_articles);
					}
				}
			}
		$smarty->assign('liste_articles',$liste_articles);
	}
}
// CAS AJOUTER VALIDER
elseif(isset($action_selectionne) AND $action_selectionne=="ajouter-valider" AND $_SESSION["droit"]==1)
{
	$id_inventaire_selectionne = InventaireAjouter($connexion, $_POST["commentaire"], GestionDate($_POST["date_inventaire"],'1'), $_POST["id_statut_inventaire"], $_POST["id_type_inventaire"], $_SESSION["id_membre_auteur"]);
	$sql_exec = ($id_inventaire_selectionne !== false);

	if(!$sql_exec) echo "Ajouter-Valider : Pb d'accès à la table inventaires";
	else
	{
		$id_inventaire_selectionne = $connexion->lastInsertId();

		for ($i=0; $i<$nb_articles_actifs; $i++)
		{
			$sql_exec = InventaireArticleAjouter($connexion, $id_inventaire_selectionne, $_POST["id_article_".$i], $_POST["qteprecedent_".$i], $_POST["qtetotale_".$i], $_POST["commentaire_".$i], $_SESSION["id_membre_auteur"]);
		}

		// CHANGEMENT D'ETAT DE L'INVENTAIRE
		$sql_exec = InventaireMajEtatAnciens($connexion, GestionDate($_POST["date_inventaire"],'1'));
		if(!$sql_exec) echo "MAJ-Etat : Pb d'accès à la table INVENTAIRES";

		$message_formulaire="Ajout enregistré";
	}
}
// CAS COPIER VALIDER
elseif(isset($action_selectionne) AND $action_selectionne=="copier-valider" AND $_SESSION["droit"]==1)
{
	$id_inventaire_selectionne = InventaireCopier($connexion, $_POST["commentaire"], GestionDate($_POST["date_inventaire"],'1'), $_POST["id_statut_inventaire"], $_POST["id_type_inventaire"], $_SESSION["id_membre_auteur"]);
	$sql_exec = ($id_inventaire_selectionne !== false);

	if(!$sql_exec) echo "Ajouter-Valider : Pb d'accès à la table inventaires";
	else
	{
		$id_inventaire_selectionne = $connexion->lastInsertId();

		for ($i=0; $i<$nb_articles_actifs; $i++)
		{
			$sql_exec = InventaireArticleAjouter($connexion, $id_inventaire_selectionne, $_POST["id_article_".$i], $_POST["qteprecedent_".$i], $_POST["qtetotale_".$i], $_POST["commentaire_".$i], $_SESSION["id_membre_auteur"]);
		}

		// CHANGEMENT D'ETAT DE L'INVENTAIRE
		$sql_exec = InventaireMajEtatAnciens($connexion, GestionDate($_POST["date_inventaire"],'1'));
		if(!$sql_exec) echo "MAJ-Etat : Pb d'accès à la table INVENTAIRES";

		$message_formulaire="Ajout enregistré";
	}}
// CAS MODIFIER VALIDER
elseif(isset($action_selectionne) AND $action_selectionne=="modifier-valider" AND $_SESSION["droit"]==1)
{
	$sql_exec = InventaireModifier($connexion, $_POST["id_inventaire"], $_POST["commentaire"], GestionDate($_POST["date_inventaire"],'1'), $_POST["id_statut_inventaire"], $_POST["id_type_inventaire"], $_SESSION["id_membre_auteur"]);

	if(!$sql_exec) echo "Modifier-Valider : Pb d'accès à la table temoignages";
	else
	{
		// SUPPRESSION DES ARTICLES RESERVES AVANT D'ENREGISTRER LES NOUVELLES QUANTITES
		$sql_exec = InventaireArticlesSupprimer($connexion, $id_inventaire_selectionne);
		if(!$sql_exec) echo "Supprimer : Pb d'accès à la table ITEMS";

		for ($i=0; $i<$nb_articles_actifs; $i++)
		{
			$sql_exec = InventaireArticleAjouter($connexion, $id_inventaire_selectionne, $_POST["id_article_".$i], $_POST["qteprecedent_".$i], $_POST["qtetotale_".$i], $_POST["commentaire_".$i], $_SESSION["id_membre_auteur"]);
		}

		$message_formulaire="Modification enregistrée";
	}
}

// AFFICHAGE DES DONNEES APRES VALIDATION
if($action_selectionne=="ajouter-valider" OR $action_selectionne=="copier-valider" OR $action_selectionne=="modifier-valider")
{
	header("Location:inventaires_liste.php?id_admin_menu=4");
	exit();
}

// création de la liste des statuts
$liste_statuts = StatutsInventairesLister($connexion);
if($liste_statuts===false) echo "LISTE : Pb d'accès à la table inventaires_statuts";
else $smarty->assign('liste_statuts',$liste_statuts);

// création de la liste des types
$liste_types = TypesInventairesLister($connexion);
if($liste_types===false) echo "LISTE : Pb d'accès à la table inventaires_types";
else $smarty->assign('liste_types',$liste_types);

// création de la liste des clients
$liste_clients = ClientsTous($connexion);
if($liste_clients===false) echo "LISTE : Pb d'accès à la table clients";
else $smarty->assign('liste_clients',$liste_clients);

$smarty->assign('message_formulaire',$message_formulaire);

$smarty->display('inventaires_formulaire.tpl');
?>
