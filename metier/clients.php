<?php
//
// Logique métier : clients / adhérents.
//

/**
 * Liste les clients visibles (id_etat <= $etatMax), triés puis paginés.
 *
 * SQL repris À L'IDENTIQUE du contrôleur `clients_liste.php`, y compris le cas
 * particulier du tri par « association » (les associations/sociétés — statuts
 * 3, 4, 5 — sont listées d'abord, puis les particuliers — statuts 1, 2).
 *
 * @return array liste de lignes (tableaux associatifs)
 */
function ClientsLister($connexion, $etatMax, $colonne, $sens, $offset, $itemsParPage)
{
	$liste = array();

	if ($colonne == 'association') {
		// 1) associations et sociétés d'abord
		$sql = "SELECT * FROM clients WHERE id_etat<=".$etatMax." AND (id_statut_client='3' OR id_statut_client='4' OR id_statut_client='5') ORDER BY association ".$sens.", nom ASC LIMIT ".$offset.", ".$itemsParPage;
		$resultat = $connexion->query($sql);
		if (!$resultat) {
			echo "LISTE : Pb d'accès à la table ITEMS 1";
		} else {
			foreach ($resultat as $row) { array_push($liste, $row); }
		}

		// 2) puis les particuliers et admin_cdf
		$sql = "SELECT * FROM clients WHERE id_etat<=".$etatMax." AND (id_statut_client='1' OR id_statut_client='2') ORDER BY nom ASC, prenom ASC LIMIT ".$offset.", ".$itemsParPage;
		$resultat = $connexion->query($sql);
		if (!$resultat) {
			echo "LISTE : Pb d'accès à la table ITEMS 2";
		} else {
			foreach ($resultat as $row) { array_push($liste, $row); }
		}
	} else {
		$sql = "SELECT * FROM clients WHERE id_etat<=".$etatMax." ORDER BY ".$colonne." ".$sens." LIMIT ".$offset.", ".$itemsParPage;
		$resultat = $connexion->query($sql);
		if (!$resultat) {
			echo "LISTE : Pb d'accès à la table ITEMS 3";
		} else {
			foreach ($resultat as $row) { array_push($liste, $row); }
		}
	}

	return $liste;
}

/**
 * Lit un client et ses informations jointes (auteur, état, statut) pour l'affichage
 * du formulaire — cas « modifier ». SQL repris à l'identique (l'auteur est joint sur
 * la colonne id_membre_auteur du client).
 *
 * @return mixed  false si la requête échoue ; null si le client n'existe pas ;
 *                sinon la ligne (tableau associatif).
 */
function ClientLire($connexion, $idClient)
{
	$sql = $connexion->prepare("SELECT t1.id_client, t1.association, t1.nom, t1.prenom, t1.adresse1, t1.adresse2, t1.adresse3, t1.cp, t1.ville, t1.telephone, t1.email, t1.commentaire, t1.date_creation, t1.date_modification, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat, t4.id_statut_client, t4.libelle_statut FROM clients AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN clients_statuts AS t4 ON t4.id_statut_client=t1.id_statut_client WHERE (t1.id_client= :id_client)");
	$ok = $sql->execute([":id_client" => $idClient]);
	if (!$ok) {
		return false;
	}
	foreach ($sql->fetchAll() as $row) {
		return $row; // clé primaire → au plus une ligne
	}
	return null;
}

/**
 * Variante du cas « copier » : SQL repris à l'identique du contrôleur, où l'auteur
 * est joint sur l'id passé en paramètre (id_membre_auteur de session) et non sur la
 * colonne du client — bizarrerie d'origine conservée.
 *
 * @return mixed  false si échec ; null si absent ; sinon la ligne.
 */
function ClientLirePourCopie($connexion, $idClient, $idMembreAuteur)
{
	$sql = $connexion->prepare("SELECT t1.id_client, t1.association, t1.nom, t1.prenom, t1.adresse1, t1.adresse2, t1.adresse3, t1.cp, t1.ville, t1.telephone, t1.email, t1.commentaire, t1.date_creation, t1.date_modification, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat, t4.id_statut_client, t4.libelle_statut FROM clients AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=:id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN clients_statuts AS t4 ON t4.id_statut_client=t1.id_statut_client WHERE (t1.id_client= :id_client)");
	$ok = $sql->execute([":id_client" => $idClient, ":id_membre_auteur" => $idMembreAuteur]);
	if (!$ok) {
		return false;
	}
	foreach ($sql->fetchAll() as $row) {
		return $row;
	}
	return null;
}

/**
 * Insère un client (cas « ajouter-valider »). Fixe id_etat='1' et les dates du jour ;
 * la clé (uniqid) est fournie par l'appelant. SQL repris à l'identique.
 *
 * @return string|false l'identifiant inséré (lastInsertId) ou false si échec.
 */
function ClientAjouter($connexion, $association, $nom, $prenom, $adresse1, $adresse2, $adresse3, $cp, $ville, $telephone, $email, $commentaire, $cle, $idStatutClient, $idMembreAuteur)
{
	$sql = $connexion->prepare("INSERT INTO clients (association, nom, prenom, adresse1, adresse2, adresse3, cp, ville, telephone, email, commentaire, cle_client, date_creation, date_modification, id_etat, id_statut_client, id_membre_auteur) VALUES (:association, :nom, :prenom, :adresse1, :adresse2, :adresse3, :cp, :ville, :telephone, :email, :commentaire,  :cle_client, :date_creation, :date_modification, :id_etat, :id_statut_client, :id_membre_auteur)");
	$ok = $sql->execute([":association" => $association, ":nom" => $nom, ":prenom" => $prenom, ":adresse1" => $adresse1, ":adresse2" => $adresse2, ":adresse3" => $adresse3, ":cp" => $cp, ":ville" => $ville, ":telephone" => $telephone, ":email" => $email, ":commentaire" => $commentaire, ":cle_client" => $cle, ":date_creation" => date("Y-m-d"), ":date_modification" => date("Y-m-d"), ":id_etat" => '1', ":id_statut_client" => $idStatutClient, ":id_membre_auteur" => $idMembreAuteur]);
	if (!$ok) {
		return false;
	}
	return $connexion->lastInsertId();
}

/**
 * Duplique un client (cas « copier-valider »). Comme le contrôleur d'origine,
 * n'écrit ni cle_client ni id_etat (colonnes omises). SQL repris à l'identique.
 *
 * @return string|false l'identifiant inséré (lastInsertId) ou false si échec.
 */
function ClientCopier($connexion, $association, $nom, $prenom, $adresse1, $adresse2, $adresse3, $cp, $ville, $telephone, $email, $commentaire, $idStatutClient, $idMembreAuteur)
{
	$sql = $connexion->prepare("INSERT INTO clients (association, nom, prenom, adresse1, adresse2, adresse3, cp, ville, telephone, email, commentaire, date_creation, date_modification, id_statut_client, id_membre_auteur) VALUES (:association, :nom, :prenom, :adresse1, :adresse2, :adresse3, :cp, :ville, :telephone, :email, :commentaire, :date_creation, :date_modification, :id_statut_client, :id_membre_auteur)");
	$ok = $sql->execute([":association" => $association, ":nom" => $nom, ":prenom" => $prenom, ":adresse1" => $adresse1, ":adresse2" => $adresse2, ":adresse3" => $adresse3, ":cp" => $cp, ":ville" => $ville, ":telephone" => $telephone, ":email" => $email, ":commentaire" => $commentaire, ":date_creation" => date("Y-m-d"), ":date_modification" => date("Y-m-d"), ":id_statut_client" => $idStatutClient, ":id_membre_auteur" => $idMembreAuteur]);
	if (!$ok) {
		return false;
	}
	return $connexion->lastInsertId();
}

/**
 * Met à jour un client (cas « modifier-valider »). Ne touche ni id_etat ni
 * date_creation ni cle_client. SQL repris à l'identique.
 *
 * @return bool résultat de l'exécution.
 */
function ClientModifier($connexion, $idClient, $association, $nom, $prenom, $adresse1, $adresse2, $adresse3, $cp, $ville, $telephone, $email, $commentaire, $idStatutClient, $idMembreAuteur)
{
	$sql = $connexion->prepare("UPDATE clients SET association=:association, nom=:nom, prenom=:prenom, adresse1=:adresse1, adresse2=:adresse2, adresse3=:adresse3, cp=:cp, ville=:ville, telephone=:telephone, email=:email, commentaire=:commentaire,  date_modification=:date_modification, id_statut_client=:id_statut_client, id_membre_auteur=:id_membre_auteur WHERE (id_client=:id_client)");
	return $sql->execute([":id_client" => $idClient, ":association" => $association, ":nom" => $nom, ":prenom" => $prenom, ":adresse1" => $adresse1, ":adresse2" => $adresse2, ":adresse3" => $adresse3, ":cp" => $cp, ":ville" => $ville, ":telephone" => $telephone, ":email" => $email, ":commentaire" => $commentaire, ":date_modification" => date("Y-m-d"), ":id_statut_client" => $idStatutClient, ":id_membre_auteur" => $idMembreAuteur]);
}

/**
 * Insère une adhésion annuelle (table clients_adhesions). Dates du jour fixées.
 * SQL repris à l'identique (utilisé par ajouter/copier/modifier-valider).
 *
 * @return bool résultat de l'exécution.
 */
function ClientAdhesionAjouter($connexion, $idClient, $annee, $montant, $idMembreAuteur)
{
	$sql = $connexion->prepare("INSERT INTO clients_adhesions (id_client, annee, montant, date_creation, date_modification, id_membre_auteur) VALUES (:id_client, :annee, :montant, :date_creation, :date_modification, :id_membre_auteur)");
	return $sql->execute([":id_client" => $idClient, ":annee" => $annee, ":montant" => $montant, ":date_creation" => date("Y-m-d"), ":date_modification" => date("Y-m-d"), ":id_membre_auteur" => $idMembreAuteur]);
}

/**
 * Met à jour le montant d'une adhésion existante. SQL repris à l'identique.
 *
 * @return bool résultat de l'exécution.
 */
function ClientAdhesionModifier($connexion, $idAdhesion, $montant, $idMembreAuteur)
{
	$sql = $connexion->prepare("UPDATE clients_adhesions SET montant=:montant, date_modification=:date_modification, id_membre_auteur=:id_membre_auteur WHERE (id_adhesion=:id_adhesion)");
	return $sql->execute([":id_adhesion" => $idAdhesion, ":montant" => $montant, ":date_modification" => date("Y-m-d"), ":id_membre_auteur" => $idMembreAuteur]);
}

/**
 * Liste les statuts de client (table clients_statuts), triés par libellé. SQL repris
 * à l'identique du contrôleur de formulaire.
 *
 * @return array|false false si la requête échoue ; sinon la liste des lignes.
 */
function StatutsClientsLister($connexion)
{
	$resultat = $connexion->query("SELECT * FROM clients_statuts ORDER BY libelle_statut ASC");
	if (!$resultat) {
		return false;
	}
	$liste = array();
	foreach ($resultat as $row) { array_push($liste, $row); }
	return $liste;
}

/**
 * Liste tous les clients, triés par nom puis prénom (sans filtre d'état). SQL repris
 * à l'identique du formulaire inventaire (liste déroulante des clients).
 *
 * @return array|false false si la requête échoue ; sinon la liste des lignes.
 */
function ClientsTous($connexion)
{
	$resultat = $connexion->query("SELECT * FROM clients ORDER BY nom ASC, prenom ASC");
	if (!$resultat) {
		return false;
	}
	$liste = array();
	foreach ($resultat as $row) { array_push($liste, $row); }
	return $liste;
}
