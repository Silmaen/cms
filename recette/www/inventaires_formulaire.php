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

//print "GET-id_inventaire=".$_GET["id_inventaire"]." POST-id_inventaire=".$_POST["id_inventaire"]." POST-action=".$_POST["action"]." id_inventaire_selectionne=".$id_inventaire_selectionne;


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

//print "AAA action_selectionne=".$action_selectionne."  ";


if( ($action_selectionne!="modifier" AND $action_selectionne=="copier") OR ($action_selectionne=="ajouter")) 
{	
	//print "BBB action_selectionne=".$action_selectionne."  ";
	
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
			//print"id_inventaire_selectionne=".$id_inventaire_selectionne." <br/>";
				
		
			$sql_inventaires=$connexion->prepare("SELECT t1.id_article, t1.quantite_precedent, t1.quantite_totale, t1.commentaire FROM inventaires_articles AS t1 LEFT JOIN inventaires AS t2 ON t2.id_inventaire=t1.id_inventaire WHERE t2.id_inventaire=:id_inventaire AND t1.id_article=:id_article");
			$sql_exec_inventaires=$sql_inventaires->execute([":id_inventaire"=>$id_inventaire_selectionne, ":id_article"=>$row_articles["id_article"]]);
			if(!$sql_exec) echo "LISTE INVENTAIRES: Pb d'accès à la table inventaires";
			else
			{
				$row_articles["commentaire"] = ""; // valeur par defaut
				$row_articles["quantite_precedent"] = 0;
				$row_articles["quantite_totale"] = 0;
				$row_articles["ecart"] = 0; // valeur par defaut
				
				//print"id_article".$row_articles["id_article"]." <br/>";
				
				foreach ($sql_inventaires->fetchAll() as $row_inventaires) 
				{					
					print"bbb <br/>";
				
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
	$sql=$connexion->prepare("SELECT t1.id_inventaire, t1.date_inventaire, t1.commentaire, t1.date_creation, t1.date_modification, t1.id_statut_inventaire, t4.libelle_statut, t1.id_type_inventaire, t5.libelle_type, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat FROM inventaires AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN inventaires_statuts AS t4 ON t4.id_statut_inventaire=t1.id_statut_inventaire LEFT JOIN inventaires_types AS t5 ON t5.id_type_inventaire=t1.id_type_inventaire WHERE (t1.id_inventaire=:id_inventaire)");
	$sql_exec=$sql->execute([":id_inventaire"=>$id_inventaire_selectionne]);	
	if(!$sql_exec) echo "COPIER - DONNEES : Pb d'accès à la table inventaires";
	else
	{
		foreach ($sql->fetchAll() as $row) 
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
		}
		$smarty->assign('liste_articles',$liste_articles);		
	}		
}
// CAS MODIFIER 
elseif (isset($action_selectionne) AND $action_selectionne=="modifier") 
{
	$sql=$connexion->prepare("SELECT t1.id_inventaire, t1.date_inventaire, t1.commentaire, t1.date_creation, t1.date_modification, t1.id_statut_inventaire, t4.libelle_statut, t1.id_type_inventaire, t5.libelle_type, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat FROM inventaires AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN inventaires_statuts AS t4 ON t4.id_statut_inventaire=t1.id_statut_inventaire LEFT JOIN inventaires_types AS t5 ON t5.id_type_inventaire=t1.id_type_inventaire WHERE (t1.id_inventaire=:id_inventaire)");
	$sql_exec=$sql->execute([":id_inventaire"=>$id_inventaire_selectionne]);	
	if(!$sql_exec) echo "MODIFIER - DONNEES : Pb d'accès à la table inventaires";
	else
	{
		foreach ($sql->fetchAll() as $row) 
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
		}
		$smarty->assign('liste_articles',$liste_articles);		
	}			
}
// CAS AJOUTER VALIDER 
elseif(isset($action_selectionne) AND $action_selectionne=="ajouter-valider" AND $_SESSION["droit"]==1) 
{	
	$sql=$connexion->prepare("INSERT INTO inventaires (commentaire, date_inventaire, date_creation, date_modification, id_statut_inventaire, id_type_inventaire, id_etat, id_membre_auteur) VALUES (:commentaire, :date_inventaire, :date_creation, :date_modification, :id_statut_inventaire, :id_type_inventaire, :id_etat, :id_membre_auteur)");
	
	$sql_exec=$sql->execute([":commentaire"=>$_POST["commentaire"], ":date_inventaire"=>GestionDate($_POST["date_inventaire"],'1'), ":date_creation"=>date("Y-m-d"), ":date_modification"=>date("Y-m-d"), ":id_statut_inventaire"=>$_POST["id_statut_inventaire"], ":id_type_inventaire"=>$_POST["id_type_inventaire"], ":id_etat"=>'1', ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
	
	if(!$sql_exec) echo "Ajouter-Valider : Pb d'accès à la table inventaires";
	else
	{
		$id_inventaire_selectionne = $connexion->lastInsertId();
		
		for ($i=0; $i<$nb_articles_actifs; $i++)
		{
			$sql=$connexion->prepare("INSERT INTO inventaires_articles (id_inventaire, id_article, quantite_precedent, quantite_totale, commentaire, id_membre_auteur) VALUES (:id_inventaire, :id_article, :quantite_precedent, :quantite_totale, :commentaire, :id_membre_auteur)");
	
			$sql_exec=$sql->execute([":id_inventaire"=>$id_inventaire_selectionne, ":id_article"=>$_POST["id_article_".$i], ":quantite_precedent"=>$_POST["qteprecedent_".$i], ":quantite_totale"=>$_POST["qtetotale_".$i], ":commentaire"=>$_POST["commentaire_".$i],":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
		}

		// CHANGEMENT D'ETAT DE L'INVENTAIRE
		$sql=$connexion->prepare("UPDATE inventaires SET id_etat='2' WHERE (date_inventaire<:date_inventaire)");
		$sql_exec=$sql->execute([":date_inventaire"=>GestionDate($_POST["date_inventaire"],'1')]);		
		if(!$sql_exec) echo "MAJ-Etat : Pb d'accès à la table INVENTAIRES";
	
		$message_formulaire="Ajout enregistré";
	}
}
// CAS COPIER VALIDER 
elseif(isset($action_selectionne) AND $action_selectionne=="copier-valider" AND $_SESSION["droit"]==1) 
{
	$sql=$connexion->prepare("INSERT INTO inventaires (commentaire, date_inventaire, date_creation, date_modification, id_statut_inventaire, id_type_inventaire, id_membre_auteur) VALUES (:commentaire, :date_inventaire, :date_creation, :date_modification, :id_statut_inventaire, :id_type_inventaire, :id_membre_auteur)");
	
	$sql_exec=$sql->execute([":commentaire"=>$_POST["commentaire"], ":date_inventaire"=>GestionDate($_POST["date_inventaire"],'1'), ":date_creation"=>date("Y-m-d"), ":date_modification"=>date("Y-m-d"), ":id_statut_inventaire"=>$_POST["id_statut_inventaire"], ":id_type_inventaire"=>$_POST["id_type_inventaire"], ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
	
	if(!$sql_exec) echo "Ajouter-Valider : Pb d'accès à la table inventaires";
	else
	{
		$id_inventaire_selectionne = $connexion->lastInsertId();
		
		for ($i=0; $i<$nb_articles_actifs; $i++)
		{
			$sql_ajout=$connexion->prepare("INSERT INTO inventaires_articles (id_inventaire, id_article, quantite_precedent, quantite_totale, commentaire, id_membre_auteur) VALUES (:id_inventaire, :id_article, :quantite_precedent, :quantite_totale, :commentaire, :id_membre_auteur)");
	
			$sql_exec=$sql_ajout->execute([":id_inventaire"=>$id_inventaire_selectionne, ":id_article"=>$_POST["id_article_".$i], ":quantite_precedent"=>$_POST["qteprecedent_".$i], ":quantite_totale"=>$_POST["qtetotale_".$i], ":commentaire"=>$_POST["commentaire_".$i], ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
		}

		// CHANGEMENT D'ETAT DE L'INVENTAIRE
		$sql=$connexion->prepare("UPDATE inventaires SET id_etat='2' WHERE (date_inventaire<:date_inventaire)");
		$sql_exec=$sql->execute([":date_inventaire"=>GestionDate($_POST["date_inventaire"],'1')]);		
		if(!$sql_exec) echo "MAJ-Etat : Pb d'accès à la table INVENTAIRES";
		
		$message_formulaire="Ajout enregistré";
	}}
// CAS MODIFIER VALIDER 
elseif(isset($action_selectionne) AND $action_selectionne=="modifier-valider" AND $_SESSION["droit"]==1) 
{
	$sql=$connexion->prepare("UPDATE inventaires SET commentaire=:commentaire, date_inventaire=:date_inventaire, date_modification=:date_modification, id_statut_inventaire=:id_statut_inventaire, id_type_inventaire=:id_type_inventaire, id_membre_auteur=:id_membre_auteur WHERE (id_inventaire=:id_inventaire)");
	
	$sql_exec=$sql->execute([":commentaire"=>$_POST["commentaire"], ":date_inventaire"=>GestionDate($_POST["date_inventaire"],'1'), ":date_modification"=>date("Y-m-d"), ":id_statut_inventaire"=>$_POST["id_statut_inventaire"], ":id_type_inventaire"=>$_POST["id_type_inventaire"], ":id_membre_auteur"=>$_SESSION["id_membre_auteur"], ":id_inventaire"=>$_POST["id_inventaire"]]);
	
	if(!$sql_exec) echo "Modifier-Valider : Pb d'accès à la table temoignages";
	else
	{
		// SUPPRESSION DES ARTICLES RESERVES AVANT D'ENREGISTRER LES NOUVELLES QUANTITES
		$sql_suppression=$connexion->prepare("DELETE FROM inventaires_articles WHERE id_inventaire=:id_inventaire");
		$sql_exec=$sql_suppression->execute([":id_inventaire"=>$id_inventaire_selectionne]);
		if(!$sql_exec) echo "Supprimer : Pb d'accès à la table ITEMS";

		for ($i=0; $i<$nb_articles_actifs; $i++)
		{
			$sql_ajout=$connexion->prepare("INSERT INTO inventaires_articles (id_inventaire, id_article, quantite_precedent, quantite_totale, commentaire, id_membre_auteur) VALUES (:id_inventaire, :id_article, :quantite_precedent, :quantite_totale, :commentaire, :id_membre_auteur)");
	
			$sql_exec=$sql_ajout->execute([":id_inventaire"=>$id_inventaire_selectionne, ":id_article"=>$_POST["id_article_".$i], ":quantite_precedent"=>$_POST["qteprecedent_".$i], ":quantite_totale"=>$_POST["qtetotale_".$i], ":commentaire"=>$_POST["commentaire_".$i], ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
		}
		
		$message_formulaire="Modification enregistrée";
	}
}


// AFFICHAGE DES DONNEES APRES VALIDATION
if($action_selectionne=="ajouter-valider" OR $action_selectionne=="copier-valider" OR $action_selectionne=="modifier-valider")
{
	header("Location:inventaires_liste.php?id_admin_menu=4");
	exit();

	/*
	$sql=$connexion->prepare("SELECT t1.id_inventaire, t1.commentaire, t1.date_inventaire,t1.date_creation, t1.date_modification, t1.id_statut_inventaire, t4.libelle_statut, t5.id_type_inventaire, t5.libelle_type, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat FROM inventaires AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN inventaires_statuts AS t4 ON t4.id_statut_inventaire=t1.id_statut_inventaire LEFT JOIN inventaires_types AS t5 ON t5.id_type_inventaire=t1.id_type_inventaire WHERE (t1.id_inventaire= :id_inventaire)");
	$sql_exec=$sql->execute([":id_inventaire"=>$id_inventaire_selectionne]);	
	if(!$sql_exec) echo "MODIFIER - DONNEES : Pb d'accès à la table inventaires";
	else
	{
		foreach ($sql->fetchAll() as $row) 
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
		}
		$smarty->assign('liste_articles',$liste_articles);		
	}	
	*/
}


// création de la liste des statuts
$liste_statuts = array();
$sql_statuts="SELECT * FROM inventaires_statuts ORDER BY libelle_statut ASC";
if(!$connexion->query($sql_statuts)) echo "LISTE : Pb d'accès à la table inventaires_statuts";
else
{
	foreach ($connexion->query($sql_statuts) as $row_statuts) 
	{
		array_push($liste_statuts,$row_statuts);		
	}
	$smarty->assign('liste_statuts',$liste_statuts);
}

// création de la liste des types
$liste_types = array();
$sql_types="SELECT * FROM inventaires_types ORDER BY libelle_type ASC";
if(!$connexion->query($sql_types)) echo "LISTE : Pb d'accès à la table inventaires_types";
else
{
	foreach ($connexion->query($sql_types) as $row_types) 
	{
		array_push($liste_types,$row_types);		
	}
	$smarty->assign('liste_types',$liste_types);
}

// création de la liste des clients
$liste_clients = array();
$sql_clients="SELECT * FROM clients ORDER BY nom ASC, prenom ASC";
if(!$connexion->query($sql_clients)) echo "LISTE : Pb d'accès à la table clients";
else
{
	foreach ($connexion->query($sql_clients) as $row_clients) 
	{
		array_push($liste_clients,$row_clients);		
	}
	$smarty->assign('liste_clients',$liste_clients);
}


$smarty->assign('message_formulaire',$message_formulaire);

$smarty->display('inventaires_formulaire.tpl');
?>