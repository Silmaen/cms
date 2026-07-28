<?php
require_once('../cgi-bin/config/config_general.php');
require_once('../cgi-bin/config/fonctions_general.php');
require_once('../metier/commun.php');
require_once('../metier/clients.php');

/* TEST CONNEXION IDENTIFIANT */
if (!gestionIdentification($connexion))
{
	header("Location:index.php");
	exit();
}

/* GESTION DU MENU */
$smarty->assign('id_admin_menu_selectionne',$_SESSION["id_admin_menu_selectionne"]);
$smarty->assign('nom_table','clients');
$smarty->assign('titre_menu','Gestion d\'un client');
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
	if(isset($_GET["id_client"]))
	{
		$id_client_selectionne=$_GET["id_client"];
	}
	else
	{
		$id_client_selectionne=0;
	}
}
elseif(isset($_POST["action"]))
{;
	$action_selectionne=$_POST["action"];
	$id_client_selectionne=$_POST["id_client"];
}
/* message_formulaire par défaut */
$message_formulaire="";

/* CAS AJOUTER */
if(isset($action_selectionne) AND $action_selectionne=="ajouter")
{
	$smarty->assign('id_client','0');
	$smarty->assign('association','');
	$smarty->assign('nom','');
	$smarty->assign('prenom','');
	$smarty->assign('adresse1','');
	$smarty->assign('adresse2','');
	$smarty->assign('adresse3','');
	$smarty->assign('cp','69730');
	$smarty->assign('ville','Genay');
	$smarty->assign('telephone','');
	$smarty->assign('email','');
	$smarty->assign('id_statut_client','1');
	$smarty->assign('libelle_statut','Particulier');
	$smarty->assign('commentaire','');
	$smarty->assign('libelle_etat','Actif');
	$smarty->assign('date_creation',date("d-m-Y"));
	$smarty->assign('date_modification',date("d-m-Y"));
	$smarty->assign('id_membre_auteur',$_SESSION["id_utilisateur"]);
	$smarty->assign('modifie_par',$_SESSION["nom_utilisateur"]." ".$_SESSION["prenom_utilisateur"]);
	$smarty->assign('action','ajouter-valider');

	// LISTE DES ADHESIONS
	$liste_adhesions = array();
	$row_adhesions["id_adhesion"] = '0';
	// NOUS TESTONS SI NOUS SOMMES ENCORE SUR L'ANCIEN EXERCICE OU SUR LE NOUVEAU
	if(date(m)>9)
	{
		$row_adhesions["annee"] = date("Y").' - '.(date("Y")+1);
		$debut_exercice_fiscal = date("Y").'-10-01 <br />';
		$fin_exercice_fiscal = (date("Y")+1).'-09-30  <br />';
	}
	else
	{
		$row_adhesions["annee"] = (date("Y")-1).' - '.date("Y");
		$debut_exercice_fiscal = (date("Y")-1).'-10-01';
		$fin_exercice_fiscal = date("Y").'-09-30';
	}
	$row_adhesions["montant"] = '0';
	array_push($liste_adhesions,$row_adhesions);
	$smarty->assign('liste_adhesions',$liste_adhesions);

	$message_formulaire="Formulaire d'ajout";
}
/* CAS COPIER */
elseif (isset($action_selectionne) AND $action_selectionne=="copier")
{
	$row = ClientLirePourCopie($connexion, $id_client_selectionne, $_SESSION["id_membre_auteur"]);
	if($row===false) echo "COPIER VALIDER - DONNEES : Pb d'accès à la table clients";
	elseif($row!==null)
	{
			$smarty->assign('id_client',$row["id_client"]);
			$smarty->assign('association','');
			$smarty->assign('nom','');
			$smarty->assign('prenom','');
			$smarty->assign('adresse1',$row["adresse1"]);
			$smarty->assign('adresse2',$row["adresse2"]);
			$smarty->assign('adresse3',$row["adresse3"]);
			$smarty->assign('cp',$row["cp"]);
			$smarty->assign('ville',$row["ville"]);
			$smarty->assign('telephone',$row["telephone"]);
			$smarty->assign('email',$row["email"]);
			$smarty->assign('id_statut_client',$row["id_statut_client"]);
			$smarty->assign('libelle_statut',$row["libelle_statut"]);
			$smarty->assign('commentaire',$row["commentaire"]);
			$smarty->assign('libelle_etat',$row["libelle_etat"]);
			$smarty->assign('date_creation',date("d-m-Y"));
			$smarty->assign('date_modification',date("d-m-Y"));
			$smarty->assign('id_membre_auteur',$_SESSION["id_membre_auteur"]);
			$smarty->assign('modifie_par',$row["nom_membre_auteur"]." ".$row["prenom_membre_auteur"]);
			$smarty->assign('action','copier-valider');
			$message_formulaire="Formulaire de duplication";

			// LISTE DES ADHESIONS
			$liste_adhesions = array();
			$row_adhesions["id_adhesion"] = '0';

			// NOUS TESTONS SI NOUS SOMMES ENCORE SUR L'ANCIEN EXERCICE OU SUR LE NOUVEAU
			if(date(m)>9)
			{
				$row_adhesions["annee"] = date("Y").' - '.(date("Y")+1);
			}
			else
			{
				$row_adhesions["annee"] = (date("Y")-1).' - '.date("Y");
			}
			$row_adhesions["montant"] = '0';
			array_push($liste_adhesions,$row_adhesions);
			$smarty->assign('liste_adhesions',$liste_adhesions);

	}
}
/* CAS MODIFIER */
elseif (isset($action_selectionne) AND $action_selectionne=="modifier")
{

	$row = ClientLire($connexion, $id_client_selectionne);
	if($row===false) echo "MODIFIER - DONNEES : Pb d'accès à la table clients";
	elseif($row!==null)
	{
			$smarty->assign('id_client',$row["id_client"]);
			$smarty->assign('association',$row["association"]);
			$smarty->assign('nom',$row["nom"]);
			$smarty->assign('prenom',$row["prenom"]);
			$smarty->assign('adresse1',$row["adresse1"]);
			$smarty->assign('adresse2',$row["adresse2"]);
			$smarty->assign('adresse3',$row["adresse3"]);
			$smarty->assign('cp',$row["cp"]);
			$smarty->assign('ville',$row["ville"]);
			$smarty->assign('telephone',$row["telephone"]);
			$smarty->assign('email',$row["email"]);
			$smarty->assign('id_statut_client',$row["id_statut_client"]);
			$smarty->assign('libelle_statut',$row["libelle_statut"]);
			$smarty->assign('commentaire',$row["commentaire"]);
			$smarty->assign('libelle_etat',$row["libelle_etat"]);
			$smarty->assign('date_creation',GestionDate($row["date_creation"],'0'));
			$smarty->assign('date_modification',GestionDate($row["date_modification"],'0'));
			$smarty->assign('id_membre_auteur',$row["id_membre_auteur"]);
			$smarty->assign('modifie_par',$row["nom_membre_auteur"]." ".$row["prenom_membre_auteur"]);
			$smarty->assign('action','modifier-valider');
			$message_formulaire="Formulaire de modification";

			// LISTE DES ADHESIONS
			$compteur = 0;
			$compteur_annee = 0;

			// NOUS TESTONS SI NOUS SOMMES ENCORE SUR L'ANCIEN EXERCICE OU SUR LE NOUVEAU
			if(date(m)>9)
			{
				$annee_fiscale_courante = date("Y");
				$debut_exercice_fiscal = date("Y").'-10-01 <br />';
				$fin_exercice_fiscal = (date("Y")+1).'-09-30  <br />';
			}
			else
			{
				$annee_fiscale_courante = date("Y")-1;
				$debut_exercice_fiscal = (date("Y")-1).'-10-01';
				$fin_exercice_fiscal = date("Y").'-09-30';
			}

			// Calcul de la date de l'adhésion la plus récente
			$annee_premiere_adhesion = "0";
			$liste_adhesions = array();
			$sql_adhesions_annee=$connexion->prepare("SELECT t1.id_client, t1.id_adhesion, t1.annee, t1.montant FROM clients_adhesions AS t1 WHERE (t1.id_client=:id_client) ORDER BY t1.annee DESC LIMIT 1");
			$sql_exec=$sql_adhesions_annee->execute([":id_client"=>$row["id_client"]]);
			if(!$sql_exec) echo "MODIFIER 2 - DONNEES : Pb d'accès à la table clients_adhesions annee";
			else
			{
				foreach ($sql_adhesions_annee->fetchAll() as $row_adhesions_annee)
				{
					$annee_premiere_adhesion=$row_adhesions_annee["annee"];
				}
			}

			// Calcul et affichage du nombre de lignes à créer
			if ($annee_premiere_adhesion=="0")
			{
				$nombre_lignes_a_creer = 2;
				$annee_premiere_adhesion = ($annee_fiscale_courante-2);
			}
			else
			{
				$nombre_lignes_a_creer = $annee_fiscale_courante - $annee_premiere_adhesion;
			}

			for ($i=$nombre_lignes_a_creer; $i>=1; $i--)
			{
				$row_adhesions["id_adhesion"]=0;
				$row_adhesions["annee"]=($annee_premiere_adhesion+$i).' - '.($annee_premiere_adhesion+$i+1);
				$row_adhesions["montant"]=0;

				// LISTE DES DONS
				$compteur_don = 0;
				$liste_dons = array();
				$connexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

				/* VERSION APRES CHANGEMENT DE l'ANNEE FISCALE PRISE EN COMPTE POUR LES DONS - DEBUT 2022-08-19 */
				$sql_dons=$connexion->prepare("SELECT SUM(t1.don) as total_don, t1.id_client, t1.annee_reservation FROM reservations AS t1 WHERE (t1.id_client=:id_client AND (t1.date_retour>=:debut_exercice_fiscal AND t1.date_retour<=:fin_exercice_fiscal) AND (t1.id_etat=1 OR t1.id_etat=2) ) GROUP BY t1.annee_reservation DESC");

				$sql_exec=$sql_dons->execute([":id_client"=>$row["id_client"], ":debut_exercice_fiscal"=>$debut_exercice_fiscal, ":fin_exercice_fiscal"=>$fin_exercice_fiscal]);

				if(!$sql_exec) echo "MODIFIER 2 - DONNEES : Pb d'accès à la table clients_dons";
				else
				{

					foreach ($sql_dons->fetchAll() as $row_dons)
					{
						$compteur_don++;
						$row_adhesions["don"] = $row_dons["total_don"];
					}
				}
				if($compteur_don==0)
				{
					$row_adhesions["don"] = '0';
				}

				array_push($liste_adhesions,$row_adhesions);

				$compteur++;
			}

			// Affichage des adhésions enregistrées
			$sql_adhesions=$connexion->prepare("SELECT t1.id_client, t1.id_adhesion, t1.annee, t1.montant FROM clients_adhesions AS t1 WHERE (t1.id_client=:id_client) ORDER BY t1.annee DESC");
			$sql_exec=$sql_adhesions->execute([":id_client"=>$row["id_client"]]);
			if(!$sql_exec) echo "MODIFIER 2 - DONNEES : Pb d'accès à la table clients_adhesions";
			else
			{
				foreach ($sql_adhesions->fetchAll() as $row_adhesions)
				{
					$debut_exercice_fiscal = $row_adhesions["annee"].'-10-01 <br />';
					$fin_exercice_fiscal = ($row_adhesions["annee"]+1).'-09-30  <br />';

					// LISTE DES DONS
					$compteur_don = 0;
					$liste_dons = array();
					$connexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

					/* VERSION APRES CHANGEMENT DE l'ANNEE FISCALE PRISE EN COMPTE POUR LES DONS - DEBUT 2022-08-19 */
					$sql_dons=$connexion->prepare("SELECT SUM(t1.don) as total_don, t1.id_client, t1.annee_reservation FROM reservations AS t1 WHERE (t1.id_client=:id_client AND (t1.date_retour>=:debut_exercice_fiscal AND t1.date_retour<=:fin_exercice_fiscal) AND (t1.id_etat=1 OR t1.id_etat=2) ) GROUP BY t1.annee_reservation DESC");

					$sql_exec=$sql_dons->execute([":id_client"=>$row["id_client"], ":debut_exercice_fiscal"=>$debut_exercice_fiscal, ":fin_exercice_fiscal"=>$fin_exercice_fiscal]);

					if(!$sql_exec) echo "MODIFIER 2 - DONNEES : Pb d'accès à la table clients_dons";
					else
					{
						$row_adhesions["annee"] = $row_adhesions["annee"].' - '.($row_adhesions["annee"]+1);

						foreach ($sql_dons->fetchAll() as $row_dons)
						{
							$compteur_don++;
							$row_adhesions["don"] = $row_dons["total_don"];
						}
					}
					if($compteur_don==0)
					{
						$row_adhesions["don"] = '0';
					}

					array_push($liste_adhesions,$row_adhesions);

					$compteur++;
				}
			}
			$smarty->assign('nombre_lignes_a_creer',$nombre_lignes_a_creer);

			$smarty->assign('liste_adhesions',$liste_adhesions);
	}
}
/* CAS AJOUTER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="ajouter-valider" AND $_SESSION["droit"]==1)
{
	// traitement permettant d'isoler le début de l'année affichée dans le formulaire
	$_POST["annee_0"] = substr($_POST["annee_0"], 0, 4);

	$id_client_selectionne = ClientAjouter($connexion, $_POST["association"], $_POST["nom"], $_POST["prenom"], $_POST["adresse1"], $_POST["adresse2"], $_POST["adresse3"], $_POST["cp"], $_POST["ville"], $_POST["telephone"], $_POST["email"], $_POST["commentaire"], uniqid(), $_POST["id_statut_client"], $_SESSION["id_membre_auteur"]);
	$sql_exec = ($id_client_selectionne !== false);

	if(!$sql_exec) echo "Ajouter-Valider : Pb d'accès à la table clients";
	else
	{
		$id_client_selectionne = $connexion->lastInsertId();

		$sql_exec = ClientAdhesionAjouter($connexion, $id_client_selectionne, $_POST["annee_0"], $_POST["montant_0"], $_SESSION["id_membre_auteur"]);

		if(!$sql_exec) echo "Ajouter-Valider 2 : Pb d'accès à la table clients_adhesions";

		$message_formulaire="Ajout enregistré";
	}
}
/* CAS COPIER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="copier-valider" AND $_SESSION["droit"]==1)
{
	// traitement permettant d'isoler le début de l'année affichée dans le formulaire
	$_POST["annee_0"] = substr($_POST["annee_0"], 0, 4);

	$id_client_selectionne = ClientCopier($connexion, $_POST["association"], $_POST["nom"], $_POST["prenom"], $_POST["adresse1"], $_POST["adresse2"], $_POST["adresse3"], $_POST["cp"], $_POST["ville"], $_POST["telephone"], $_POST["email"], $_POST["commentaire"], $_POST["id_statut_client"], $_SESSION["id_membre_auteur"]);
	$sql_exec = ($id_client_selectionne !== false);

	if(!$sql_exec) echo "Copier-Valider : Pb d'accès à la table clients";
	else
	{
		$id_client_selectionne = $connexion->lastInsertId();

		$sql_exec = ClientAdhesionAjouter($connexion, $id_client_selectionne, $_POST["annee_0"], $_POST["montant_0"], $_SESSION["id_membre_auteur"]);

		if(!$sql_exec) echo "Copier-Valider 2 : Pb d'accès à la table clients_adhesions";

		$message_formulaire="Ajout enregistré";
	}
}
/* CAS MODIFIER VALIDER */
elseif(isset($action_selectionne) AND $action_selectionne=="modifier-valider" AND $_SESSION["droit"]==1)
{
	// traitement permettant d'isoler le début de l'année affichée dans le formulaire
	$_POST["annee_0"] = substr($_POST["annee_0"], 0, 4);

	$sql_exec = ClientModifier($connexion, $_POST["id_client"], $_POST["association"], $_POST["nom"], $_POST["prenom"], $_POST["adresse1"], $_POST["adresse2"], $_POST["adresse3"], $_POST["cp"], $_POST["ville"], $_POST["telephone"], $_POST["email"], $_POST["commentaire"], $_POST["id_statut_client"], $_SESSION["id_membre_auteur"]);

	if(!$sql_exec) echo "Modifier-Valider : Pb d'accès à la table clients";
	else
	{
		if (isset($_POST["id_adhesion_0"]))
		{

			$compteur_0 = 0;
			$sql_adhesions=$connexion->prepare("SELECT t1.id_adhesion, t1.annee FROM clients_adhesions AS t1 WHERE (t1.id_adhesion=:id_adhesion) ORDER BY t1.annee DESC LIMIT 2");
			$sql_exec=$sql_adhesions->execute([":id_adhesion"=>$_POST["id_adhesion_0"]]);
			if(!$sql_exec) echo "MODIFIER 2 - DONNEES : Pb d'accès à la table clients_adhesions";
			else
			{
				foreach ($sql_adhesions->fetchAll() as $row_adhesions)
				{
					$compteur_0 ++;
					$sql_exec = ClientAdhesionModifier($connexion, $_POST["id_adhesion_0"], $_POST["montant_0"], $_SESSION["id_membre_auteur"]);

					if(!$sql_exec) echo "Modifier-Valider 3 : Pb d'accès à la table clients_adhesions";
				}
			}
			if($compteur_0==0)
			{

				$sql_exec = ClientAdhesionAjouter($connexion, $_POST["id_client"], $_POST["annee_0"], $_POST["montant_0"], $_SESSION["id_membre_auteur"]);

				if(!$sql_exec) echo "Ajouter-Valider 4 : Pb d'accès à la table clients_adhesions";
			}

		}
		if (isset($_POST["id_adhesion_1"]))
		{

			$compteur_1 = 0;
			$sql_adhesions=$connexion->prepare("SELECT t1.id_adhesion, t1.annee FROM clients_adhesions AS t1 WHERE (t1.id_adhesion=:id_adhesion) ORDER BY t1.annee DESC LIMIT 2");
			$sql_exec=$sql_adhesions->execute([":id_adhesion"=>$_POST["id_adhesion_1"]]);
			if(!$sql_exec) echo "MODIFIER 2 - DONNEES : Pb d'accès à la table clients_adhesions";
			else
			{
				foreach ($sql_adhesions->fetchAll() as $row_adhesions)
				{
					$compteur_1 ++;
					$sql_exec = ClientAdhesionModifier($connexion, $_POST["id_adhesion_1"], $_POST["montant_1"], $_SESSION["id_membre_auteur"]);

					if(!$sql_exec) echo "Modifier-Valider 3 : Pb d'accès à la table clients_adhesions";
				}
			}
			if($compteur_1==0)
			{

				$sql_exec = ClientAdhesionAjouter($connexion, $_POST["id_client"], $_POST["annee_1"], $_POST["montant_1"], $_SESSION["id_membre_auteur"]);

				if(!$sql_exec) echo "Ajouter-Valider 4 : Pb d'accès à la table clients_adhesions";
			}
		}

		$message_formulaire="Modification enregistrée";
	}
}

/* AFFICHAGE DES DONNEES APRES VALIDATION*/
if($action_selectionne=="ajouter-valider" OR $action_selectionne=="copier-valider" OR $action_selectionne=="modifier-valider" )
{
	header("Location:clients_liste.php?id_admin_menu=2");
	exit();
}

// création de la liste des etats
$liste_etats = EtatsLister($connexion);
if($liste_etats===false) echo "Modifier : Pb d'accès à la table etats";
else $smarty->assign('liste_etats',$liste_etats);

// création de la liste des statuts clients
$liste_statuts = StatutsClientsLister($connexion);
if($liste_statuts===false) echo "Modifier : Pb d'accès à la table clients_statuts";
else $smarty->assign('liste_statuts',$liste_statuts);

$smarty->assign('message_formulaire',$message_formulaire);

$smarty->display('clients_formulaire.tpl');
?>
