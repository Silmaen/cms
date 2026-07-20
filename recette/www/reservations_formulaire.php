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
$smarty->assign('nom_table','reservations');
$smarty->assign('titre_menu','Gestion d\'un reservation');
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
	if(isset($_GET["id_reservation"]))
	{
		$id_reservation_selectionne=$_GET["id_reservation"];
	}
	else
	{
		$id_reservation_selectionne=0;
	}
}
elseif(isset($_POST["action"]))
{
	$action_selectionne=$_POST["action"]; 
	$id_reservation_selectionne=$_POST["id_reservation"];
}	


if(isset($_POST["envoyer"]))
{
	$envoyer=$_POST["envoyer"];
}
else
{
	$envoyer="NULL";
}


if(isset($_POST["date_retour"]))
{
 
	$date_explosee = explode("-", $_POST["date_retour"]);
	 
	$jour = $date_explosee[0];
	$mois = $date_explosee[1];
	$annee = $date_explosee[2];
	
	// NOUS TESTONS SI LA RESERVATION EST RENDUE AVANT OU APRES LA BASCULE D'ANNEE FISCALE	
	if($mois>9) 
	{
		//print"AA mois = ".$mois."<br />";
		$_POST["annee_reservation"] = $annee;
	}	
	else				
	{
		//print"BB mois = ".$mois."<br />";
		$_POST["annee_reservation"] = ($annee-1);
	}
	//print "date retour = ".$_POST["date_retour"]." // annee_reservation = ".$_POST["annee_reservation"]."</ br>";

}

// CALCUL DE L'ANNEE COURANTE 

$date_courante = explode("-", date("d-m-Y"));
$jour_temp = $date_courante[0];
$mois_temp = $date_courante[1];
$annee_temp = $date_courante[2];
if($mois_temp>9) 
{
	$annee_courante = $annee_temp;
}	
else				
{
	$annee_courante = ($annee_temp-1);
}
	


// message_formulaire par défaut 
$message_formulaire="";


// CALCUL DU NOMBRE DE LIGNES DE RESERVATIONS A ENREGISTRER
$nb_articles_actifs =0;
foreach( $_POST as $cle=>$value )
{
	if (substr($cle, 0, 12)=='qtereservee_')
	{
		$nb_articles_actifs ++;
	}
}


// RECUPERATION DE L'INVENTAIRE LE PLUS RECENT
$sql=$connexion->prepare("SELECT id_inventaire FROM inventaires WHERE id_statut_inventaire=2 AND id_etat=1 ORDER BY date_inventaire DESC LIMIT 1");
$sql_exec=$sql->execute();	
if(!$sql_exec) echo "INVENTAIRE : Pb d'accès à la table INVENTAIRES";
else
{
	foreach ($sql->fetchAll() as $row) 
	{
		$id_inventaire_selectionne = $row["id_inventaire"];
		
		//print "id_inventaire=".$id_inventaire_selectionne." <br />";
	}
}

// CAS AJOUTER 
if(isset($action_selectionne) AND $action_selectionne=="ajouter")
{	
	$smarty->assign('id_reservation','0');
	$smarty->assign('id_client','0');	
	$smarty->assign('association','');		
	$smarty->assign('nom_client','');		
	$smarty->assign('prenom_client','');			
	$smarty->assign('ville_client','');		
	$smarty->assign('association_client','');		
	$smarty->assign('adresse1_client','');			
	$smarty->assign('adresse2_client','');			
	$smarty->assign('adresse3_client','');			
	$smarty->assign('libelle_statut_client','');			
	$smarty->assign('telephone_client','');			
	$smarty->assign('email_client','');			

	// Calcul de la date de départ par defaut => 0 = dimanche
	$aujourdhui = new DateTime(date("Y-m-d"));
	$date_depart = new DateTime(date("Y-m-d"));
	$date_retour = new DateTime(date("Y-m-d"));

	if ($aujourdhui->format('w')==0){$date_depart->add(new DateInterval('P1D')); $date_retour->add(new DateInterval('P5D'));}
	if ($aujourdhui->format('w')==1){$date_depart->add(new DateInterval('P0D')); $date_retour->add(new DateInterval('P4D'));}
	if ($aujourdhui->format('w')==2){$date_depart->add(new DateInterval('P3D')); $date_retour->add(new DateInterval('P6D'));}
	if ($aujourdhui->format('w')==3){$date_depart->add(new DateInterval('P2D')); $date_retour->add(new DateInterval('P5D'));}
	if ($aujourdhui->format('w')==4){$date_depart->add(new DateInterval('P1D')); $date_retour->add(new DateInterval('P4D'));}
	if ($aujourdhui->format('w')==5){$date_depart->add(new DateInterval('P0D')); $date_retour->add(new DateInterval('P3D'));}
	if ($aujourdhui->format('w')==6){$date_depart->add(new DateInterval('P2D')); $date_retour->add(new DateInterval('P6D'));}
		
	$smarty->assign('date_depart',$date_depart->format('d-m-Y'));	
	$smarty->assign('date_retour',$date_retour->format('d-m-Y'));	
	
	$smarty->assign('commentaire','');	
	$smarty->assign('don','0');	
	$smarty->assign('date_don',date("d-m-Y"));
	$smarty->assign('id_etat','1');		
	$smarty->assign('libelle_etat','Actif');		
	$smarty->assign('id_statut_reservation','2');	
	$smarty->assign('libelle_statut','Validé');		
	$smarty->assign('date_creation',date("d-m-Y"));	
	$smarty->assign('date_modification',date("d-m-Y"));
	$smarty->assign('id_membre_auteur',$_SESSION["id_utilisateur"]);	
	$smarty->assign('modifie_par',$_SESSION["nom_utilisateur"]." ".$_SESSION["prenom_utilisateur"]);	
	$smarty->assign('action','ajouter-valider');
	
	$message_formulaire="Formulaire d'ajout";
}
// CAS COPIER 
elseif (isset($action_selectionne) AND $action_selectionne=="copier") 
{
	$sql=$connexion->prepare("SELECT t1.id_reservation, t1.id_client, t1.date_depart, t1.date_retour, t1.commentaire, t1.don, t1.date_don, t1.date_creation, t1.date_modification, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat, t4.nom as nom_client, t4.prenom as prenom_client, t4.association, t4.ville as ville_client, t4.cp as cp_client, t4.telephone as telephone_client, t4.email as email_client, t4.adresse1 as adresse1_client, t4.adresse2 as adresse2_client, t4.adresse3 as adresse3_client, t4.commentaire as commentaire_client, t5.id_statut_reservation, t5.libelle_statut, t6.libelle_statut AS libelle_statut_client FROM reservations AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN clients AS t4 ON t4.id_client=t1.id_client LEFT JOIN reservations_statuts AS t5 ON t5.id_statut_reservation=t1.id_statut_reservation  LEFT JOIN clients_statuts AS t6 ON t6.id_statut_client=t4.id_statut_client WHERE (t1.id_reservation= :id_reservation)");
	$sql_exec=$sql->execute([":id_reservation"=>$id_reservation_selectionne]);	
	if(!$sql_exec) echo "MODIFIER - DONNEES : Pb d'accès à la table reservations";
	else
	{
		foreach ($sql->fetchAll() as $row) 
		{
			$smarty->assign('id_reservation',$row["id_reservation"]);
			$smarty->assign('id_client',$row["id_client"]);	
			$row["libelle"] = "";
			if($row["nom_client"]!=""){$row["libelle"] .= $row["nom_client"];}
			if($row["prenom_client"]!=""){$row["libelle"] .= " ".$row["prenom_client"];}
			if($row["association"]!=""){$row["libelle"] .= " - ".$row["association"];}
			if($row["ville_client"]!=""){$row["libelle"] .= " - ".$row["ville_client"];}

			$smarty->assign('libelle',$row["libelle"]);	
			$smarty->assign('association',$row["association"]." - ");		
			$smarty->assign('nom_client',$row["nom_client"]);		
			$smarty->assign('prenom_client',$row["prenom_client"]);			
			$smarty->assign('ville_client',$row["ville_client"]);			
			$smarty->assign('association_client',$row["association"]);		
			$smarty->assign('adresse1_client',$row["adresse1_client"]);			
			$smarty->assign('adresse2_client',$row["adresse2_client"]);			
			$smarty->assign('adresse3_client',$row["adresse3_client"]);			
			$smarty->assign('libelle_statut_client',$row["libelle_statut_client"]);			
			$smarty->assign('commentaire_client',$row["commentaire_client"]);	
			$smarty->assign('telephone_client',$row["telephone_client"]);			
			$smarty->assign('email_client',$row["email_client"]);			
			
			// Calcul de la date de départ par defaut => 0 = dimanche
			$aujourdhui = new DateTime(date("Y-m-d"));
			$date_depart = new DateTime(date("Y-m-d"));
			$date_retour = new DateTime(date("Y-m-d"));

			if ($aujourdhui->format('w')==0){$date_depart->add(new DateInterval('P1D')); $date_retour->add(new DateInterval('P5D'));}
			if ($aujourdhui->format('w')==1){$date_depart->add(new DateInterval('P0D')); $date_retour->add(new DateInterval('P4D'));}
			if ($aujourdhui->format('w')==2){$date_depart->add(new DateInterval('P3D')); $date_retour->add(new DateInterval('P6D'));}
			if ($aujourdhui->format('w')==3){$date_depart->add(new DateInterval('P2D')); $date_retour->add(new DateInterval('P5D'));}
			if ($aujourdhui->format('w')==4){$date_depart->add(new DateInterval('P1D')); $date_retour->add(new DateInterval('P4D'));}
			if ($aujourdhui->format('w')==5){$date_depart->add(new DateInterval('P0D')); $date_retour->add(new DateInterval('P3D'));}
			if ($aujourdhui->format('w')==6){$date_depart->add(new DateInterval('P2D')); $date_retour->add(new DateInterval('P6D'));}

			$smarty->assign('date_depart',$date_depart->format('d-m-Y'));	
			$smarty->assign('date_retour',$date_retour->format('d-m-Y'));				
			$smarty->assign('date_retour_temp',$date_retour->format('Y-m-d'));	
			$smarty->assign('aujourdhui_temp',date("Y-m-d"));	
			$smarty->assign('commentaire',$row["commentaire"]);	
			$smarty->assign('don','0');	
			$smarty->assign('date_don',$date_retour->format('d-m-Y'));	
			$smarty->assign('id_etat','1');		
			$smarty->assign('libelle_etat','Actif');		
			$smarty->assign('id_statut_reservation',$row["id_statut_reservation"]);	
			$smarty->assign('libelle_statut',$row["libelle_statut"]);		
			$smarty->assign('date_creation',date("d-m-Y"));	
			$smarty->assign('date_modification',GestionDate($row["date_modification"],'0'));
			$smarty->assign('id_membre_auteur',$_SESSION["id_utilisateur"]);	
			$smarty->assign('modifie_par',$_SESSION["nom_utilisateur"]." ".$_SESSION["prenom_utilisateur"]);	
			$smarty->assign('action','copier-valider');
			$message_formulaire="Formulaire de dupplication";		
		
		
			// RECHERCHE DE L'ADHESION DE CE CLIENT POUR L'ANNEE EN COURS
			$smarty->assign('adhesion_client',0);	// valeur par défaut
			$sql_adhesions=$connexion->prepare("SELECT t1.id_client, t1.id_adhesion, t1.annee, t1.montant FROM clients_adhesions AS t1 WHERE (t1.id_client=:id_client AND t1.annee=:annee) ORDER BY t1.annee DESC");
			$sql_exec=$sql_adhesions->execute([":id_client"=>$row["id_client"], ":annee"=>date("Y")]);	
			if(!$sql_exec) echo "MODIFIER 2 - DONNEES : Pb d'accès à la table clients_adhesions";
			else
			{
				foreach ($sql_adhesions->fetchAll() as $row_adhesions) 
				{
					$smarty->assign('adhesion_client',$row_adhesions["montant"]);	
				}
			}


			// création de la liste des articles
			$liste_articles = array();
			$sql_articles=$connexion->prepare("SELECT t3.id_article, t3.designation, t2.quantite_totale, t3.id_etat as id_etat_article FROM inventaires AS t1 LEFT JOIN inventaires_articles AS t2 ON t2.id_inventaire=t1.id_inventaire LEFT JOIN articles AS t3 ON t3.id_article=t2.id_article WHERE t1.id_inventaire=:id_inventaire ORDER BY t3.designation ASC");
			$sql_exec=$sql_articles->execute([":id_inventaire"=>$id_inventaire_selectionne]);
			if(!$sql_exec) echo "LISTE : Pb d'accès à la table articles";
			else
			{
			
				foreach ($sql_articles->fetchAll() as $row_articles) 
				{			
					// Calcul du nombre d'articles disponibles
					$sql_stock=$connexion->prepare("SELECT SUM(t1.quantite_reservee) as total_reserve FROM reservations_articles AS t1 LEFT JOIN reservations AS t2 ON t2.id_reservation=t1.id_reservation WHERE (t2.id_etat=1 AND t1.id_article=:id_article AND date_depart<'".$date_retour->format('Y-m-d')."' AND t2.date_retour>'".$date_depart->format('Y-m-d')."')");
	
					$sql_exec=$sql_stock->execute([":id_article"=>$row_articles["id_article"]]);
					if(!$sql_exec) echo "STOCK 1: Pb d'accès à la table reservations_articles";
					else
					{
						foreach ($sql_stock->fetchAll() as $row_stock) 
						{
							// Récupération du nombre d'articles réservés dans cette réservation
							$row_articles["quantite_reservee"]=0;
							$sql_reservations=$connexion->prepare("SELECT t1.quantite_reservee FROM reservations_articles AS t1 WHERE (t1.id_reservation=:id_reservation AND t1.id_article=:id_article)");
							$sql_exec=$sql_reservations->execute([":id_reservation"=>$id_reservation_selectionne, ":id_article"=>$row_articles["id_article"]]);
							if(!$sql_exec) echo "STOCK 2: Pb d'accès à la table reservations_articles";
							else
							{
								foreach ($sql_reservations->fetchAll() as $row_reservations) 
								{
									$row_articles["quantite_reservee"]=$row_reservations["quantite_reservee"];
								}
							}	
							
							if($row_articles["quantite_totale"]>0)
							{
								$row_articles["quantite_actuelle"]=($row_articles["quantite_totale"] - $row_stock["total_reserve"] + $row_articles["quantite_reservee"]);	
							}
							else
							{
								$row_articles["quantite_actuelle"]=0;
							}
							
							$row_articles["quantite_apres"]=($row_articles["quantite_actuelle"] - $row_articles["quantite_reservee"]);
						}
					}	

					// Valeur par defaut si un article n'a pas de fichier rattaché
					$row_articles["id_fichier"] = '';
					$row_articles["nom_fichier"] = '';
					$row_articles["extension"] = '';

					// Liste des fichiers joints 
					$sql_fichiers=$connexion->prepare("SELECT id_fichier, nom_fichier, extension, poids, largeur FROM fichiers WHERE (id_menu=3 AND id_parent=:id_parent)");
					$sql_exec=$sql_fichiers->execute([":id_parent"=>$row_articles["id_article"]]);	
					if(!$sql_exec) echo "VALIDER - FICHIERS: Pb d'accès à la table fichiers";

					else
					{
						foreach ($sql_fichiers->fetchAll() as $row_fichiers) 
						{	
							$row_articles["id_fichier"] = $row_fichiers["id_fichier"];
							$row_articles["nom_fichier"] = $row_fichiers["nom_fichier"];
							$row_articles["extension"] = $row_fichiers["extension"];
						}
					}
					array_push($liste_articles,$row_articles);
					
				}
			}	
			$smarty->assign('liste_articles',$liste_articles);
		}
	}	
}
// CAS MODIFIER 

//toute modification faite sur cette partie doit être reportée sur CAS MODIFIER-RC et sur l'affichage final après validation
elseif (isset($action_selectionne) AND $action_selectionne=="modifier") 
{	
	$sql=$connexion->prepare("SELECT t1.id_reservation, t1.id_client, t1.date_depart, t1.date_retour, t1.commentaire, t1.don, t1.date_don, t1.date_creation, t1.date_modification, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat, t4.nom as nom_client, t4.prenom as prenom_client, t4.association, t4.ville as ville_client, t4.cp as cp_client, t4.telephone as telephone_client, t4.email as email_client, t4.adresse1 as adresse1_client, t4.adresse2 as adresse2_client, t4.adresse3 as adresse3_client, t4.commentaire as commentaire_client, t5.id_statut_reservation, t5.libelle_statut, t6.libelle_statut AS libelle_statut_client FROM reservations AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN clients AS t4 ON t4.id_client=t1.id_client LEFT JOIN reservations_statuts AS t5 ON t5.id_statut_reservation=t1.id_statut_reservation LEFT JOIN clients_statuts AS t6 ON t6.id_statut_client=t4.id_statut_client WHERE (t1.id_reservation= :id_reservation)");
	$sql_exec=$sql->execute([":id_reservation"=>$id_reservation_selectionne]);	
	if(!$sql_exec) echo "MODIFIER TT - DONNEES : Pb d'accès à la table reservations";
	else
	{
		foreach ($sql->fetchAll() as $row) 
		{
			$smarty->assign('id_reservation',$row["id_reservation"]);
			$smarty->assign('id_client',$row["id_client"]);	



			$row["libelle"] = "";
			if($row["nom_client"]!=""){$row["libelle"] .= $row["nom_client"];}
			if($row["prenom_client"]!=""){$row["libelle"] .= " ".$row["prenom_client"];}
			if($row["association"]!=""){$row["libelle"] .= " - ".$row["association"];}
			if($row["ville_client"]!=""){$row["libelle"] .= " - ".$row["ville_client"];}
			$smarty->assign('libelle',$row["libelle"]);	

			$row["libelle_association"] = "";
			if($row["association"]!=""){$row["libelle_association"] .= $row["association"]." - ";}
			if($row["nom_client"]!=""){$row["libelle_association"] .= $row["nom_client"];}
			if($row["prenom_client"]!=""){$row["libelle_association"] .= " ".$row["prenom_client"];}
			if($row["ville_client"]!=""){$row["libelle_association"] .= " - ".$row["ville_client"];}
			$smarty->assign('libelle_association',$row["libelle_association"]);	

			$smarty->assign('association',$row["association"]." - ");		
			$smarty->assign('nom_client',$row["nom_client"]);		
			$smarty->assign('prenom_client',$row["prenom_client"]);			
			$smarty->assign('cp_client',$row["cp_client"]);			
			$smarty->assign('ville_client',$row["ville_client"]);		
			$smarty->assign('association_client',$row["association"]);		
			$smarty->assign('adresse1_client',$row["adresse1_client"]);			
			$smarty->assign('adresse2_client',$row["adresse2_client"]);			
			$smarty->assign('adresse3_client',$row["adresse3_client"]);			
			$smarty->assign('libelle_statut_client',$row["libelle_statut_client"]);			
			$smarty->assign('commentaire_client',$row["commentaire_client"]);	
			$smarty->assign('telephone_client',$row["telephone_client"]);			
			$smarty->assign('email_client',$row["email_client"]);			
			$smarty->assign('date_depart',GestionDate($row["date_depart"],'0'));	
			$smarty->assign('date_retour',GestionDate($row["date_retour"],'0'));	
			$smarty->assign('date_retour_temp',$row["date_retour"]);	
			$smarty->assign('aujourdhui_temp',date("Y-m-d"));	
			$smarty->assign('commentaire',$row["commentaire"]);	
			$smarty->assign('don',$row["don"]);	
			$smarty->assign('date_don',GestionDate($row["date_don"],'0'));
			$smarty->assign('id_etat',$row["id_etat"]);		
			$smarty->assign('libelle_etat',$row["libelle_etat"]);		
			$smarty->assign('id_statut_reservation',$row["id_statut_reservation"]);	
			$smarty->assign('libelle_statut',$row["libelle_statut"]);		
			$smarty->assign('date_creation',GestionDate($row["date_creation"],'0'));	
			$smarty->assign('date_modification',GestionDate($row["date_modification"],'0'));
			$smarty->assign('id_membre_auteur',$row["id_membre_auteur"]);	
			$smarty->assign('modifie_par',$row["nom_membre_auteur"]." ".$row["prenom_membre_auteur"]);	
			$smarty->assign('action','modifier-valider');
			$message_formulaire="Formulaire de modification";		



			// RECHERCHE DE L'ADHESION DE CE CLIENT POUR L'ANNEE EN COURS
			$smarty->assign('adhesion_client',0);	// valeur par défaut
			$sql_adhesions=$connexion->prepare("SELECT t1.id_client, t1.id_adhesion, t1.annee, t1.montant FROM clients_adhesions AS t1 WHERE (t1.id_client=:id_client AND t1.annee=:annee) ORDER BY t1.annee DESC");
			$sql_exec=$sql_adhesions->execute([":id_client"=>$row["id_client"], ":annee"=>$annee_courante]);	
			if(!$sql_exec) echo "MODIFIER 2 - DONNEES : Pb d'accès à la table clients_adhesions";
			else
			{
				foreach ($sql_adhesions->fetchAll() as $row_adhesions) 
				{
					$smarty->assign('adhesion_client',$row_adhesions["montant"]);	
				}
			}
	
			// création de la liste des articles
			$liste_articles = array();
			$sql_articles=$connexion->prepare("SELECT t3.id_article, t3.designation, t3.id_etat as id_etat_article, t2.quantite_totale FROM inventaires AS t1 LEFT JOIN inventaires_articles AS t2 ON t2.id_inventaire=t1.id_inventaire LEFT JOIN articles AS t3 ON t3.id_article=t2.id_article WHERE t1.id_inventaire=:id_inventaire ORDER BY t3.designation ASC");
			$sql_exec=$sql_articles->execute([":id_inventaire"=>$id_inventaire_selectionne]);
			if(!$sql_exec) echo "LISTE : Pb d'accès à la table articles";
			else
			{
				foreach ($sql_articles->fetchAll() as $row_articles) 
				{		
					//print "date_depart=".$row["date_depart"]." <br />";
					
					$row_articles["quantite_reservee"]=0;
					$row_articles["quantite_rendue"]=0;
										
					$sql_stock=$connexion->prepare("SELECT SUM(t1.quantite_reservee) as total_reserve FROM reservations_articles AS t1 LEFT JOIN reservations AS t2 ON t2.id_reservation=t1.id_reservation WHERE (t2.id_etat=1 AND t1.id_article=:id_article AND ((date_depart<='".$row["date_depart"]."' AND t2.date_retour>'".$row["date_depart"]."') OR (date_depart>='".$row["date_depart"]."' AND t2.date_retour<='".$row["date_depart"]."')) AND t1.quantite_reservee>0)");									
					$sql_exec=$sql_stock->execute([":id_article"=>$row_articles["id_article"]]);

					if(!$sql_exec) echo "STOCK A1: Pb d'accès à la table reservations_articles";
					
					foreach ($sql_stock->fetchAll() as $row_stock) 
					{
						// Récupération du nombre d'articles réservés dans cette réservation
						
						$sql_reservations=$connexion->prepare("SELECT t1.quantite_reservee FROM reservations_articles AS t1 WHERE (t1.id_reservation=:id_reservation AND t1.id_article=:id_article)");
						$sql_exec=$sql_reservations->execute([":id_reservation"=>$id_reservation_selectionne, ":id_article"=>$row_articles["id_article"]]);
						if(!$sql_exec) echo "STOCK B2: Pb d'accès à la table reservations_articles";
						else
						{
							foreach ($sql_reservations->fetchAll() as $row_reservations) 
							{
								$row_articles["quantite_reservee"]=$row_reservations["quantite_reservee"];
								//print "id_reservation=".$id_reservation_selectionne." quantite_reservee=".$row_articles["quantite_reservee"];
							}
						}	
						
						
						$sql_rendu=$connexion->prepare("SELECT t1.id_reservation, t1.quantite_reservee FROM reservations_articles AS t1 LEFT JOIN reservations AS t2 ON t2.id_reservation=t1.id_reservation WHERE (t2.id_etat=1 AND t1.id_article=:id_article AND date_depart>='".$row["date_depart"]."' AND t2.date_retour<'".$row["date_retour"]."')");
						$sql_exec=$sql_rendu->execute([":id_article"=>$row_articles["id_article"]]);
						if(!$sql_exec) echo "STOCK C3: Pb d'accès à la table reservations_articles";
						else
						{
							foreach ($sql_rendu->fetchAll() as $row_rendu) 
							{
								$row_articles["quantite_rendue"]=$row_rendu["quantite_reservee"];
								//print " id_reservation=".$row_rendu["id_reservation"]." quantite_rendue=".$row_articles["quantite_reservee"]." <br />";

							}
						}	

						$row_articles["quantite_actuelle"]=($row_articles["quantite_totale"] - $row_stock["total_reserve"] + $row_articles["quantite_reservee"] + $row_rendu["quantite_rendue"]);	
						
						//print " quantite_actuelle=".$row_articles["quantite_actuelle"];
						//print "quantite_actuelle = quantite_totale=".$row_articles["quantite_totale"]." - total_reserve=".$row_stock["total_reserve"]." + quantite_reservee=".$row_articles["quantite_reservee"]." + quantite_rendue=".$row_rendu["quantite_rendue"]." <br /><br />";
						
						$row_articles["quantite_apres"]=($row_articles["quantite_actuelle"] - $row_articles["quantite_reservee"]);		
						//print " quantite_apres=".$row_articles["quantite_apres"]." <br />";
					}								
							
	
					// Valeur par defaut si un article n'a pas de fichier rattaché
					$row_articles["id_fichier"] = '';
					$row_articles["nom_fichier"] = '';
					$row_articles["extension"] = '';

					// Liste des fichiers joints 
					$sql_fichiers=$connexion->prepare("SELECT id_fichier, nom_fichier, extension, poids, largeur FROM fichiers WHERE (id_menu=3 AND id_parent=:id_parent)");
					$sql_exec=$sql_fichiers->execute([":id_parent"=>$row_articles["id_article"]]);	
					if(!$sql_exec) echo "VALIDER - FICHIERS: Pb d'accès à la table fichiers";

					else
					{
						foreach ($sql_fichiers->fetchAll() as $row_fichiers) 
						{	
							$row_articles["id_fichier"] = $row_fichiers["id_fichier"];
							$row_articles["nom_fichier"] = $row_fichiers["nom_fichier"];
							$row_articles["extension"] = $row_fichiers["extension"];
						}
					}
							
					array_push($liste_articles,$row_articles);		
				}
			}	
			$smarty->assign('liste_articles',$liste_articles);
		}
	}	
}






// CAS MODIFIER RC - Réservation Classique
elseif (isset($action_selectionne) AND $action_selectionne=="modifier-rc") 
{
	$sql=$connexion->prepare("SELECT t1.id_reservation, t1.id_client, t1.date_depart, t1.date_retour, t1.commentaire, t1.don, t1.date_don, t1.date_creation, t1.date_modification, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat, t4.nom as nom_client, t4.prenom as prenom_client, t4.association, t4.ville as ville_client, t4.cp as cp_client, t4.telephone as telephone_client, t4.email as email_client, t4.adresse1 as adresse1_client, t4.adresse2 as adresse2_client, t4.adresse3 as adresse3_client,  t5.id_statut_reservation, t5.libelle_statut, t6.libelle_statut AS libelle_statut_client FROM reservations AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN clients AS t4 ON t4.id_client=t1.id_client LEFT JOIN reservations_statuts AS t5 ON t5.id_statut_reservation=t1.id_statut_reservation WHERE (t1.id_reservation= :id_reservation)");
	$sql_exec=$sql->execute([":id_reservation"=>$id_reservation_selectionne]);	
	if(!$sql_exec) echo "MODIFIER TT - DONNEES : Pb d'accès à la table reservations";
	else
	{
		foreach ($sql->fetchAll() as $row) 
		{
			$smarty->assign('id_reservation',$row["id_reservation"]);
			$smarty->assign('id_client',$row["id_client"]);	

			$row["libelle"] = "";
			if($row["nom_client"]!=""){$row["libelle"] .= $row["nom_client"];}
			if($row["prenom_client"]!=""){$row["libelle"] .= " ".$row["prenom_client"];}
			if($row["association"]!=""){$row["libelle"] .= " - ".$row["association"];}
			if($row["ville_client"]!=""){$row["libelle"] .= " - ".$row["ville_client"];}
			$smarty->assign('libelle',$row["libelle"]);	

			$row["libelle_association"] = "";
			if($row["association"]!=""){$row["libelle_association"] .= $row["association"]." - ";}
			if($row["nom_client"]!=""){$row["libelle_association"] .= $row["nom_client"];}
			if($row["prenom_client"]!=""){$row["libelle_association"] .= " ".$row["prenom_client"];}
			if($row["ville_client"]!=""){$row["libelle_association"] .= " - ".$row["ville_client"];}
			$smarty->assign('libelle_association',$row["libelle_association"]);	

			$smarty->assign('association',$row["association"]." - ");		
			$smarty->assign('nom_client',$row["nom_client"]);		
			$smarty->assign('prenom_client',$row["prenom_client"]);			
			$smarty->assign('cp_client',$row["cp_client"]);			
			$smarty->assign('ville_client',$row["ville_client"]);		
			$smarty->assign('association_client',$row["association"]);		
			$smarty->assign('adresse1_client',$row["adresse1_client"]);			
			$smarty->assign('adresse2_client',$row["adresse2_client"]);			
			$smarty->assign('adresse3_client',$row["adresse3_client"]);			
			$smarty->assign('libelle_statut_client',$row["libelle_statut_client"]);			
			$smarty->assign('telephone_client',$row["telephone_client"]);			
			$smarty->assign('email_client',$row["email_client"]);			
			$smarty->assign('date_depart',GestionDate($row["date_depart"],'0'));	
			$smarty->assign('date_retour',GestionDate($row["date_retour"],'0'));	
			$smarty->assign('date_retour_temp',$row["date_retour"]);	
			$smarty->assign('aujourdhui_temp',date("Y-m-d"));	
			$smarty->assign('commentaire',$row["commentaire"]);	
			$smarty->assign('don',$row["don"]);	
			$smarty->assign('date_don',GestionDate($row["date_don"],'0'));
			$smarty->assign('id_etat',$row["id_etat"]);		
			$smarty->assign('libelle_etat',$row["libelle_etat"]);		
			$smarty->assign('id_statut_reservation',$row["id_statut_reservation"]);	
			$smarty->assign('libelle_statut',$row["libelle_statut"]);		
			$smarty->assign('date_creation',GestionDate($row["date_creation"],'0'));	
			$smarty->assign('date_modification',GestionDate($row["date_modification"],'0'));
			$smarty->assign('id_membre_auteur',$row["id_membre_auteur"]);	
			$smarty->assign('modifie_par',$row["nom_membre_auteur"]." ".$row["prenom_membre_auteur"]);	
			$smarty->assign('action','modifier-valider-rc');
			$message_formulaire="Formulaire de modification";		
		
			// RECHERCHE DE L'ADHESION DE CE CLIENT POUR L'ANNEE EN COURS
			$smarty->assign('adhesion_client',0);	// valeur par défaut
			$sql_adhesions=$connexion->prepare("SELECT t1.id_client, t1.id_adhesion, t1.annee, t1.montant FROM clients_adhesions AS t1 WHERE (t1.id_client=:id_client AND t1.annee=:annee) ORDER BY t1.annee DESC");
			$sql_exec=$sql_adhesions->execute([":id_client"=>$row["id_client"], ":annee"=>date("Y")]);	
			if(!$sql_exec) echo "MODIFIER 2 - DONNEES : Pb d'accès à la table clients_adhesions";
			else
			{
				foreach ($sql_adhesions->fetchAll() as $row_adhesions) 
				{
					$smarty->assign('adhesion_client',$row_adhesions["montant"]);	
				}
			}

			// création de la liste des articles
			$liste_articles = array();
						
			// Liste des articles Réservation Classique
			$sql_articles=$connexion->prepare("SELECT t3.id_article, t3.designation, t3.id_etat as id_etat_article, t2.quantite_totale FROM inventaires AS t1 LEFT JOIN inventaires_articles AS t2 ON t2.id_inventaire=t1.id_inventaire LEFT JOIN articles AS t3 ON t3.id_article=t2.id_article WHERE (t1.id_inventaire=:id_inventaire AND t3.ordre_article>0) ORDER BY t3.ordre_article ASC");
			$sql_exec=$sql_articles->execute([":id_inventaire"=>$id_inventaire_selectionne]);
			if(!$sql_exec) echo "LISTE : Pb d'accès à la table articles RC 1";
			else
			{
				foreach ($sql_articles->fetchAll() as $row_articles) 
				{		
					// Calcul du nombre d'articles disponibles
					$sql_stock=$connexion->prepare("SELECT SUM(t1.quantite_reservee) as total_reserve FROM reservations_articles AS t1 LEFT JOIN reservations AS t2 ON t2.id_reservation=t1.id_reservation WHERE (t2.id_etat=1 AND t1.id_article=:id_article AND date_depart<'".$row["date_retour"]."' AND t2.date_retour>'".$row["date_depart"]."')");
					$sql_exec=$sql_stock->execute([":id_article"=>$row_articles["id_article"]]);
					if(!$sql_exec) echo "STOCK 1: Pb d'accès à la table reservations_articles";
					else
					{
						foreach ($sql_stock->fetchAll() as $row_stock) 
						{
							// Récupération du nombre d'articles réservés dans cette réservation
							$row_articles["quantite_reservee"]=0;
							$sql_reservations=$connexion->prepare("SELECT t1.quantite_reservee FROM reservations_articles AS t1 WHERE (t1.id_reservation=:id_reservation AND t1.id_article=:id_article)");
							$sql_exec=$sql_reservations->execute([":id_reservation"=>$id_reservation_selectionne, ":id_article"=>$row_articles["id_article"]]);
							if(!$sql_exec) echo "STOCK 2: Pb d'accès à la table reservations_articles";
							else
							{
								foreach ($sql_reservations->fetchAll() as $row_reservations) 
								{
									$row_articles["quantite_reservee"]=$row_reservations["quantite_reservee"];
								}
							}	
							
							$row_articles["quantite_actuelle"]=($row_articles["quantite_totale"] - $row_stock["total_reserve"] + $row_articles["quantite_reservee"]);	
								
							$row_articles["quantite_apres"]=($row_articles["quantite_actuelle"] - $row_articles["quantite_reservee"]);
								
						}
					}	
					
					// Valeur par defaut si un article n'a pas de fichier rattaché
					$row_articles["id_fichier"] = '';
					$row_articles["nom_fichier"] = '';
					$row_articles["extension"] = '';

					// Liste des fichiers joints 
					$sql_fichiers=$connexion->prepare("SELECT id_fichier, nom_fichier, extension, poids, largeur FROM fichiers WHERE (id_menu=3 AND id_parent=:id_parent)");
					$sql_exec=$sql_fichiers->execute([":id_parent"=>$row_articles["id_article"]]);	
					if(!$sql_exec) echo "VALIDER - FICHIERS: Pb d'accès à la table fichiers";

					else
					{
						foreach ($sql_fichiers->fetchAll() as $row_fichiers) 
						{	
							$row_articles["id_fichier"] = $row_fichiers["id_fichier"];
							$row_articles["nom_fichier"] = $row_fichiers["nom_fichier"];
							$row_articles["extension"] = $row_fichiers["extension"];
						}
					}
							
					array_push($liste_articles,$row_articles);		
				}
			}	
			
			
			
			$premier_article_temp = 0;
			// Liste des articles de la liste à l'exclusion de ceux avec un ORDRE>0
			$sql_articles=$connexion->prepare("SELECT t3.id_article, t3.designation, t3.id_etat as id_etat_article, t2.quantite_totale FROM inventaires AS t1 LEFT JOIN inventaires_articles AS t2 ON t2.id_inventaire=t1.id_inventaire LEFT JOIN articles AS t3 ON t3.id_article=t2.id_article WHERE (t1.id_inventaire=:id_inventaire AND t3.ordre_article=0) ORDER BY t3.designation ASC");
			$sql_exec=$sql_articles->execute([":id_inventaire"=>$id_inventaire_selectionne]);
			if(!$sql_exec) echo "LISTE : Pb d'accès à la table articles  RC 2";
			else
			{
				// Cette variable est utilisée pour ajouter un espace à la fin de la liste des articles CLASSIQUES
				foreach ($sql_articles->fetchAll() as $row_articles) 
				{		
					$premier_article_temp ++;					
					$row_articles["premier_article"] = $premier_article_temp;
					
					// Calcul du nombre d'articles disponibles
					$sql_stock=$connexion->prepare("SELECT SUM(t1.quantite_reservee) as total_reserve FROM reservations_articles AS t1 LEFT JOIN reservations AS t2 ON t2.id_reservation=t1.id_reservation WHERE (t2.id_etat=1 AND t1.id_article=:id_article AND date_depart<'".$row["date_retour"]."' AND t2.date_retour>'".$row["date_depart"]."')");
					$sql_exec=$sql_stock->execute([":id_article"=>$row_articles["id_article"]]);
					if(!$sql_exec) echo "STOCK 1: Pb d'accès à la table reservations_articles";
					else
					{
						foreach ($sql_stock->fetchAll() as $row_stock) 
						{
							// Récupération du nombre d'articles réservés dans cette réservation
							$row_articles["quantite_reservee"]=0;
							$sql_reservations=$connexion->prepare("SELECT t1.quantite_reservee FROM reservations_articles AS t1 WHERE (t1.id_reservation=:id_reservation AND t1.id_article=:id_article)");
							$sql_exec=$sql_reservations->execute([":id_reservation"=>$id_reservation_selectionne, ":id_article"=>$row_articles["id_article"]]);
							if(!$sql_exec) echo "STOCK 2: Pb d'accès à la table reservations_articles";
							else
							{
								foreach ($sql_reservations->fetchAll() as $row_reservations) 
								{
									$row_articles["quantite_reservee"]=$row_reservations["quantite_reservee"];
								}
							}	
							
							$row_articles["quantite_actuelle"]=($row_articles["quantite_totale"] - $row_stock["total_reserve"] + $row_articles["quantite_reservee"]);	
								
							$row_articles["quantite_apres"]=($row_articles["quantite_actuelle"] - $row_articles["quantite_reservee"]);
								
						}
					}	
					
					// Valeur par defaut si un article n'a pas de fichier rattaché
					$row_articles["id_fichier"] = '';
					$row_articles["nom_fichier"] = '';
					$row_articles["extension"] = '';

					// Liste des fichiers joints 
					$sql_fichiers=$connexion->prepare("SELECT id_fichier, nom_fichier, extension, poids, largeur FROM fichiers WHERE (id_menu=3 AND id_parent=:id_parent)");
					$sql_exec=$sql_fichiers->execute([":id_parent"=>$row_articles["id_article"]]);	
					if(!$sql_exec) echo "VALIDER - FICHIERS: Pb d'accès à la table fichiers";

					else
					{
						foreach ($sql_fichiers->fetchAll() as $row_fichiers) 
						{	
							$row_articles["id_fichier"] = $row_fichiers["id_fichier"];
							$row_articles["nom_fichier"] = $row_fichiers["nom_fichier"];
							$row_articles["extension"] = $row_fichiers["extension"];
						}
					}
							
					array_push($liste_articles,$row_articles);		
				}
			}			
			
			
			$smarty->assign('liste_articles',$liste_articles);
		}
	}	
}



// CAS AJOUTER VALIDER 
elseif(isset($action_selectionne) AND $action_selectionne=="ajouter-valider" AND $_SESSION["droit"]==1) 
{		
	$sql=$connexion->prepare("INSERT INTO reservations (id_client, commentaire, don, date_don, date_depart, date_retour, date_creation, date_modification, heure_modification, annee_reservation, id_etat, id_statut_reservation, id_membre_auteur) VALUES (:id_client, :commentaire, :don, :date_don, :date_depart, :date_retour, :date_creation, :date_modification, :heure_modification, :annee_reservation, :id_etat, :id_statut_reservation, :id_membre_auteur)");
	
	$sql_exec=$sql->execute([":id_client"=>$_POST["id_client"], ":commentaire"=>$_POST["commentaire"], ":don"=>$_POST["don"], ":date_don"=>GestionDate($_POST["date_retour"],'1'), ":date_depart"=>GestionDate($_POST["date_depart"],'1'), ":date_retour"=>GestionDate($_POST["date_retour"],'1'), ":date_creation"=>date("Y-m-d"), ":date_modification"=>date("Y-m-d"), ":heure_modification"=>date("H:i:s"), ":id_etat"=>$_POST["id_etat"], ":id_statut_reservation"=>$_POST["id_statut_reservation"], ":annee_reservation"=>$_POST["annee_reservation"], ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
	
	if(!$sql_exec) echo "Ajouter-Valider : Pb d'accès à la table reservations";
	else
	{		
		$id_reservation_selectionne = $connexion->lastInsertId();
						
		for ($i=0; $i<$nb_articles_actifs; $i++)
		{			
			$sql=$connexion->prepare("INSERT INTO reservations_articles (id_reservation, id_article, quantite_reservee, id_membre_auteur) VALUES (:id_reservation, :id_article, :quantite_reservee, :id_membre_auteur)");
	
			$sql_exec=$sql->execute([":id_reservation"=>$id_reservation_selectionne, ":id_article"=>$_POST["id_article_".$i], ":quantite_reservee"=>$_POST["qtereservee_".$i],":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
		}
		
		$message_formulaire="Ajout enregistré";
	}
}
// CAS COPIER VALIDER 
elseif(isset($action_selectionne) AND ($action_selectionne=="copier-valider") AND $_SESSION["droit"]==1) 
{
	$sql=$connexion->prepare("INSERT INTO reservations (id_client, commentaire, don, date_don, date_depart, date_retour, date_creation, date_modification, heure_modification, id_etat, id_statut_reservation, annee_reservation, id_membre_auteur) VALUES (:id_client, :commentaire, :don, :date_don, :date_depart, :date_retour, :date_creation, :date_modification, :heure_modification, :id_etat, :id_statut_reservation, :annee_reservation, :id_membre_auteur)");
	
	$sql_exec=$sql->execute([":id_client"=>$_POST["id_client"], ":commentaire"=>$_POST["commentaire"], ":don"=>$_POST["don"], ":date_don"=>GestionDate($_POST["date_don"],'1'), ":date_depart"=>GestionDate($_POST["date_depart"],'1'), ":date_retour"=>GestionDate($_POST["date_retour"],'1'), ":date_creation"=>date("Y-m-d"), ":date_modification"=>date("Y-m-d"), ":heure_modification"=>date("H:i:s"), ":id_etat"=>$_POST["id_etat"], ":id_statut_reservation"=>$_POST["id_statut_reservation"], ":annee_reservation"=>$_POST["annee_reservation"], ":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	

	
	if(!$sql_exec) echo "Ajouter-Valider : Pb d'accès à la table reservations";
	else
	{
		$id_reservation_selectionne = $connexion->lastInsertId();
		
		for ($i=0; $i<$nb_articles_actifs; $i++)
		{
			$sql_ajout=$connexion->prepare("INSERT INTO reservations_articles (id_reservation, id_article, quantite_reservee, id_membre_auteur) VALUES (:id_reservation, :id_article, :quantite_reservee, :id_membre_auteur)");
	
			$sql_exec=$sql_ajout->execute([":id_reservation"=>$id_reservation_selectionne, ":id_article"=>$_POST["id_article_".$i], ":quantite_reservee"=>$_POST["qtereservee_".$i],":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
		}
		
		$message_formulaire="Ajout enregistré";
	}
}
// CAS MODIFIER VALIDER 
elseif(isset($action_selectionne) AND ($action_selectionne=="modifier-valider" OR $action_selectionne=="modifier-valider-rc") AND $_SESSION["droit"]==1) 
{		
	$sql=$connexion->prepare("UPDATE reservations SET id_client=:id_client, commentaire=:commentaire, don=:don, date_don=:date_don, date_depart=:date_depart, date_retour=:date_retour, date_modification=:date_modification, heure_modification=:heure_modification, id_etat=:id_etat, annee_reservation=:annee_reservation, id_statut_reservation=:id_statut_reservation, id_membre_auteur=:id_membre_auteur WHERE (id_reservation=:id_reservation)");
	$sql_exec=$sql->execute([":id_client"=>$_POST["id_client"], ":commentaire"=>$_POST["commentaire"], ":don"=>$_POST["don"], ":date_don"=>GestionDate($_POST["date_don"],'1'), ":date_depart"=>GestionDate($_POST["date_depart"],'1'), ":date_retour"=>GestionDate($_POST["date_retour"],'1'), ":date_modification"=>date("Y-m-d"), ":heure_modification"=>date("H:i:s"), ":id_etat"=>$_POST["id_etat"], ":annee_reservation"=>$_POST["annee_reservation"], ":id_statut_reservation"=>$_POST["id_statut_reservation"], ":id_membre_auteur"=>$_SESSION["id_membre_auteur"], ":id_reservation"=>$_POST["id_reservation"]]);
	
	
	if(!$sql_exec) echo "Modifier-Valider : Pb d'accès à la table reservations";
	else
	{
		// SUPPRESSION DES ARTICLES RESERVES AVANT D'ENREGISTRER LES NOUVELLES QUANTITES
		$sql_suppression=$connexion->prepare("DELETE FROM reservations_articles WHERE id_reservation=:id_reservation");
		$sql_exec=$sql_suppression->execute([":id_reservation"=>$id_reservation_selectionne]);
		if(!$sql_exec) echo "Supprimer : Pb d'accès à la table ITEMS";
	
		// AJOUT DES ARTICLES RESERVES
		for ($i=0; $i<$nb_articles_actifs; $i++)
		{
			$sql_ajout=$connexion->prepare("INSERT INTO reservations_articles (id_reservation, id_article, quantite_reservee, id_membre_auteur) VALUES (:id_reservation, :id_article, :quantite_reservee, :id_membre_auteur)");
	
			$sql_exec=$sql_ajout->execute([":id_reservation"=>$id_reservation_selectionne, ":id_article"=>$_POST["id_article_".$i], ":quantite_reservee"=>$_POST["qtereservee_".$i],":id_membre_auteur"=>$_SESSION["id_membre_auteur"]]);	
		}
		
		$message_formulaire="Modification enregistrée";
	}
}




// AFFICHAGE DES DONNEES APRES VALIDATION
if($action_selectionne=="ajouter-valider" OR $action_selectionne=="copier-valider" OR $action_selectionne=="modifier-valider" OR $action_selectionne=="modifier-valider-rc")
{
	if($envoyer=="oui")
	{
		if($action_selectionne=="copier-valider")
		{
			$sql_derniere_resa=$connexion->prepare("SELECT t1.id_reservation FROM reservations AS t1 WHERE id_etat=1 ORDER BY t1.id_reservation DESC lIMIT 1");
			$sql_exec=$sql_derniere_resa->execute([":id_reservation"=>$_POST["id_reservation"]]);	
			if(!$sql_exec) echo "Consultation: Pb d'accès à la table reservations et clients";
			else
			{
				foreach ($sql_derniere_resa->fetchAll() as $row_derniere_resa) 
				{
					$_POST["id_reservation"]=$row_derniere_resa["id_reservation"];
				}
			}
		}
		
		$nom = "";
		$prenom = "";
		$email_temp = "";
		
		
				
		// Recherche de l'email de l'utilisateur pour validation
		$sql=$connexion->prepare("SELECT t1.id_reservation, t1.id_client, t1.date_depart, t1.date_retour, t2.cle_client, t2.nom, t2.prenom, t2.email FROM reservations AS t1 LEFT JOIN clients AS t2 ON t2.id_client=t1.id_client WHERE (id_reservation= :id_reservation)");
		$sql_exec=$sql->execute([":id_reservation"=>$_POST["id_reservation"]]);	
		if(!$sql_exec) echo "Consultation: Pb d'accès à la table reservations et clients";
		else
		{
			foreach ($sql->fetchAll() as $row) 
			{		
				$lien="http://www.cdf-genay.com/fiche_reservation_client.php?action=imprimer&id_reservation=".$row["id_reservation"]."&cle_client=".$row["cle_client"];
				
				$nom = $row["nom"];
				$prenom = $row["prenom"];
				$email_temp = $row["email"];
				$date_depart_temp = $row["date_depart"];
				$date_retour_temp = $row["date_retour"];
			}
		}		
		
		$encoding = "utf-8";
		$from_name = "Comité des Fêtes de Genay";
		$from_mail = "no-reply@cdf-genay.com";
		$mail_subject = $prenom." ".$nom." : Confirmation de votre réservation auprès du Comité des Fêtes de Genay";
		$mail_to = $email_temp;
		$mail_message =  "Bonjour ".$prenom." ".$nom.",
			<br /><br /> Nous vous confirmons votre réservation réalisée auprès du Comité des Fêtes de Genay.
			<br /><br /> Le matériel est réservé du ".GestionDate($date_depart_temp,'0')." jusqu'au ".GestionDate($date_retour_temp,'0').".
			<br /><br /> Vous pouvez consulter le détail du matériel réservé en téléchargeant votre confirmation sur le lien suivant : ".$lien." .
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
		$header .= "Bcc: no-reply@cdf-genay.com; \r\n";
		$header .= "Date: ".date("r (T)")." \r\n";
		//$header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);

		// Send mail
		mail($mail_to, $mail_subject, $mail_message, $header);	
		
		if($email_temp!="")
		{

			header("Location:reservations_liste.php?id_admin_menu=1&envoi=ok");
			exit();
		}
	}
	else if($action_selectionne!="ajouter-valider")
	{
		header("Location:reservations_liste.php?id_admin_menu=1");
		exit();
	} 
	else
	{	
		$sql=$connexion->prepare("SELECT t1.id_reservation, t1.id_client, t1.date_depart, t1.date_retour, t1.commentaire, t1.don, t1.date_don,  t1.date_creation, t1.date_modification, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat, t4.nom as nom_client, t4.prenom as prenom_client, t4.association, t4.ville as ville_client, t4.cp as cp_client, t4.telephone as telephone_client, t4.email as email_client, t4.adresse1 as adresse1_client, t4.adresse2 as adresse2_client, t4.adresse3 as adresse3_client, t4.commentaire as commentaire_client, t5.id_statut_reservation, t5.libelle_statut, t6.libelle_statut AS libelle_statut_client FROM reservations AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN clients AS t4 ON t4.id_client=t1.id_client LEFT JOIN reservations_statuts AS t5 ON t5.id_statut_reservation=t1.id_statut_reservation LEFT JOIN clients_statuts AS t6 ON t6.id_statut_client=t4.id_statut_client  WHERE (t1.id_reservation= :id_reservation)");
		$sql_exec=$sql->execute([":id_reservation"=>$id_reservation_selectionne]);	
		if(!$sql_exec) echo "MODIFIER TT - DONNEES : Pb d'accès à la table reservations";
		else
		{
			foreach ($sql->fetchAll() as $row) 
			{
				$smarty->assign('id_reservation',$row["id_reservation"]);
				$smarty->assign('id_client',$row["id_client"]);	


				$row["libelle"] = "";
				if($row["nom_client"]!=""){$row["libelle"] .= $row["nom_client"];}
				if($row["prenom_client"]!=""){$row["libelle"] .= " ".$row["prenom_client"];}
				if($row["association"]!=""){$row["libelle"] .= " - ".$row["association"];}
				if($row["ville_client"]!=""){$row["libelle"] .= " - ".$row["ville_client"];}
				$smarty->assign('libelle',$row["libelle"]);	

				$row["libelle_association"] = "";
				if($row["association"]!=""){$row["libelle_association"] .= $row["association"]." - ";}
				if($row["nom_client"]!=""){$row["libelle_association"] .= $row["nom_client"];}
				if($row["prenom_client"]!=""){$row["libelle_association"] .= " ".$row["prenom_client"];}
				if($row["ville_client"]!=""){$row["libelle_association"] .= " - ".$row["ville_client"];}
				$smarty->assign('libelle_association',$row["libelle_association"]);	

				$smarty->assign('association',$row["association"]." - ");		
				$smarty->assign('nom_client',$row["nom_client"]);		
				$smarty->assign('prenom_client',$row["prenom_client"]);			
				$smarty->assign('cp_client',$row["cp_client"]);			
				$smarty->assign('ville_client',$row["ville_client"]);		
				$smarty->assign('association_client',$row["association"]);		
				$smarty->assign('adresse1_client',$row["adresse1_client"]);			
				$smarty->assign('adresse2_client',$row["adresse2_client"]);			
				$smarty->assign('adresse3_client',$row["adresse3_client"]);			
				$smarty->assign('libelle_statut_client',$row["libelle_statut_client"]);			
				$smarty->assign('commentaire_client',$row["commentaire_client"]);	
				$smarty->assign('telephone_client',$row["telephone_client"]);			
				$smarty->assign('email_client',$row["email_client"]);			
				$smarty->assign('date_depart',GestionDate($row["date_depart"],'0'));	
				$smarty->assign('date_retour',GestionDate($row["date_retour"],'0'));	
				$smarty->assign('date_retour_temp',$row["date_retour"]);	
				$smarty->assign('aujourdhui_temp',date("Y-m-d"));	
				$smarty->assign('commentaire',$row["commentaire"]);	
				$smarty->assign('don',$row["don"]);	
				$smarty->assign('date_don',GestionDate($row["date_don"],'0'));
				$smarty->assign('id_etat',$row["id_etat"]);		
				$smarty->assign('libelle_etat',$row["libelle_etat"]);		
				$smarty->assign('id_statut_reservation',$row["id_statut_reservation"]);	
				$smarty->assign('libelle_statut',$row["libelle_statut"]);		
				$smarty->assign('date_creation',GestionDate($row["date_creation"],'0'));	
				$smarty->assign('date_modification',GestionDate($row["date_modification"],'0'));
				$smarty->assign('id_membre_auteur',$row["id_membre_auteur"]);	
				$smarty->assign('modifie_par',$row["nom_membre_auteur"]." ".$row["prenom_membre_auteur"]);	
				$smarty->assign('action','modifier-valider');
				$message_formulaire="Formulaire de modification";		
			
				// création de la liste des articles
				$liste_articles = array();
				$sql_articles=$connexion->prepare("SELECT t3.id_article, t3.designation, t3.id_etat as id_etat_article, t2.quantite_totale FROM inventaires AS t1 LEFT JOIN inventaires_articles AS t2 ON t2.id_inventaire=t1.id_inventaire LEFT JOIN articles AS t3 ON t3.id_article=t2.id_article WHERE t1.id_inventaire=:id_inventaire ORDER BY t3.designation ASC");
				$sql_exec=$sql_articles->execute([":id_inventaire"=>$id_inventaire_selectionne]);
				if(!$sql_exec) echo "LISTE : Pb d'accès à la table articles";
				else
				{
					foreach ($sql_articles->fetchAll() as $row_articles) 
					{		
						// Calcul du nombre d'articles disponibles
						$sql_stock=$connexion->prepare("SELECT SUM(t1.quantite_reservee) as total_reserve FROM reservations_articles AS t1 LEFT JOIN reservations AS t2 ON t2.id_reservation=t1.id_reservation WHERE (t2.id_etat=1 AND t1.id_article=:id_article AND date_depart<'".$row["date_retour"]."' AND t2.date_retour>'".$row["date_depart"]."')");
						$sql_exec=$sql_stock->execute([":id_article"=>$row_articles["id_article"]]);
						if(!$sql_exec) echo "STOCK 1: Pb d'accès à la table reservations_articles";
						else
						{
							foreach ($sql_stock->fetchAll() as $row_stock) 
							{
								// Récupération du nombre d'articles réservés dans cette réservation
								$row_articles["quantite_reservee"]=0;
								$sql_reservations=$connexion->prepare("SELECT t1.quantite_reservee FROM reservations_articles AS t1 WHERE (t1.id_reservation=:id_reservation AND t1.id_article=:id_article)");
								$sql_exec=$sql_reservations->execute([":id_reservation"=>$id_reservation_selectionne, ":id_article"=>$row_articles["id_article"]]);
								if(!$sql_exec) echo "STOCK 2: Pb d'accès à la table reservations_articles";
								else
								{
									foreach ($sql_reservations->fetchAll() as $row_reservations) 
									{
										$row_articles["quantite_reservee"]=$row_reservations["quantite_reservee"];
									}
								}	
								
								$row_articles["quantite_actuelle"]=($row_articles["quantite_totale"] - $row_stock["total_reserve"] + $row_articles["quantite_reservee"]);	
									
								$row_articles["quantite_apres"]=($row_articles["quantite_actuelle"] - $row_articles["quantite_reservee"]);
									
							}
						}	
						
						// Valeur par defaut si un article n'a pas de fichier rattaché
						$row_articles["id_fichier"] = '';
						$row_articles["nom_fichier"] = '';
						$row_articles["extension"] = '';

						// Liste des fichiers joints 
						$sql_fichiers=$connexion->prepare("SELECT id_fichier, nom_fichier, extension, poids, largeur FROM fichiers WHERE (id_menu=3 AND id_parent=:id_parent)");
						$sql_exec=$sql_fichiers->execute([":id_parent"=>$row_articles["id_article"]]);	
						if(!$sql_exec) echo "VALIDER - FICHIERS: Pb d'accès à la table fichiers";

						else
						{
							foreach ($sql_fichiers->fetchAll() as $row_fichiers) 
							{	
								$row_articles["id_fichier"] = $row_fichiers["id_fichier"];
								$row_articles["nom_fichier"] = $row_fichiers["nom_fichier"];
								$row_articles["extension"] = $row_fichiers["extension"];
							}
						}
								
						array_push($liste_articles,$row_articles);		
					}
				}	
				$smarty->assign('liste_articles',$liste_articles);
			}
		}
	}
}


// création de la liste des etats
$liste_etats = array();
$sql_etats="SELECT * FROM etats ORDER BY libelle_etat ASC";
if(!$connexion->query($sql_etats)) echo "LISTE : Pb d'accès à la table etats";
else
{
	foreach ($connexion->query($sql_etats) as $row_etats) 
	{
		array_push($liste_etats,$row_etats);		
	}
	$smarty->assign('liste_etats',$liste_etats);
}

// création de la liste des statuts des reservations
$liste_statuts = array();
$sql_statut="SELECT * FROM reservations_statuts ORDER BY libelle_statut ASC";
if(!$connexion->query($sql_statut)) echo "LISTE : Pb d'accès à la table reservations_statuts";
else
{
	foreach ($connexion->query($sql_statut) as $row_statut) 
	{
		array_push($liste_statuts,$row_statut);		
	}
	$smarty->assign('liste_statuts',$liste_statuts);
}

// création de la liste des clients
$liste_clients = array();
$sql_clients_a="SELECT * FROM clients WHERE (id_etat='1') ORDER BY nom ASC, prenom ASC, association ASC";
if(!$connexion->query($sql_clients_a)) echo "LISTE : Pb d'accès à la table clients 1";
else
{
	foreach ($connexion->query($sql_clients_a) as $row_clients) 
	{
		$row_clients["libelle"] = "";
		if($row_clients["nom"]!=""){$row_clients["libelle"] .= $row_clients["nom"];}
		if($row_clients["prenom"]!=""){$row_clients["libelle"] .= " ".$row_clients["prenom"];}
		if($row_clients["association"]!=""){$row_clients["libelle"] .= " - ".$row_clients["association"];}
		if($row_clients["ville"]!=""){$row_clients["libelle"] .= " - ".$row_clients["ville"];}
		
		array_push($liste_clients,$row_clients);		
	}
}

$smarty->assign('liste_clients',$liste_clients);

// création de la liste des association
$liste_associations = array();
$sql_associations_a="SELECT * FROM clients WHERE (id_etat='1' AND id_statut_client=3 OR id_statut_client=4 OR id_statut_client=5) ORDER BY association ASC, nom ASC, prenom ASC";
if(!$connexion->query($sql_associations_a)) echo "LISTE : Pb d'accès à la table clients 2";
else
{
	foreach ($connexion->query($sql_associations_a) as $row_associations) 
	{
		$row_associations["libelle"] = "";
		if($row_associations["association"]!=""){$row_associations["libelle"] .= $row_associations["association"]." - ";}
		if($row_associations["nom"]!=""){$row_associations["libelle"] .= $row_associations["nom"];}
		if($row_associations["prenom"]!=""){$row_associations["libelle"] .= " ".$row_associations["prenom"];}
		if($row_associations["ville"]!=""){$row_associations["libelle"] .= " - ".$row_associations["ville"];}
		
		array_push($liste_associations,$row_associations);		
	}
}

$sql_associations_b="SELECT * FROM clients WHERE (id_etat='1' AND id_statut_client=1 OR id_statut_client=2) ORDER BY nom ASC, prenom ASC";
if(!$connexion->query($sql_associations_b)) echo "LISTE : Pb d'accès à la table clients 2";
else
{
	foreach ($connexion->query($sql_associations_b) as $row_associations) 
	{
		$row_associations["libelle"] = "";
		if($row_associations["association"]!=""){$row_associations["libelle"] .= $row_associations["association"]." - ";}
		if($row_associations["nom"]!=""){$row_associations["libelle"] .= $row_associations["nom"];}
		if($row_associations["prenom"]!=""){$row_associations["libelle"] .= " ".$row_associations["prenom"];}
		if($row_associations["ville"]!=""){$row_associations["libelle"] .= " - ".$row_associations["ville"];}
		
		array_push($liste_associations,$row_associations);		
	}
}
$smarty->assign('liste_associations',$liste_associations);



$smarty->assign('message_formulaire',$message_formulaire);
$smarty->display('reservations_formulaire.tpl');
?>