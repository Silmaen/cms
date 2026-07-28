<?php
//
// Logique métier : réservations de matériel.
//

/**
 * Lit une réservation et ses informations jointes (auteur, état, client, statut de
 * réservation, statut du client) pour l'affichage du formulaire. SQL repris à
 * l'identique des cas « copier », « modifier » et de l'affichage final après
 * validation (mise à plat des espaces, sans effet).
 *
 * @return mixed  false si échec ; null si absente ; sinon la ligne.
 */
function ReservationLire($connexion, $idReservation)
{
	$sql = $connexion->prepare("SELECT t1.id_reservation, t1.id_client, t1.date_depart, t1.date_retour, t1.commentaire, t1.don, t1.date_don, t1.date_creation, t1.date_modification, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat, t4.nom as nom_client, t4.prenom as prenom_client, t4.association, t4.ville as ville_client, t4.cp as cp_client, t4.telephone as telephone_client, t4.email as email_client, t4.adresse1 as adresse1_client, t4.adresse2 as adresse2_client, t4.adresse3 as adresse3_client, t4.commentaire as commentaire_client, t5.id_statut_reservation, t5.libelle_statut, t6.libelle_statut AS libelle_statut_client FROM reservations AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN clients AS t4 ON t4.id_client=t1.id_client LEFT JOIN reservations_statuts AS t5 ON t5.id_statut_reservation=t1.id_statut_reservation LEFT JOIN clients_statuts AS t6 ON t6.id_statut_client=t4.id_statut_client WHERE (t1.id_reservation= :id_reservation)");
	$ok = $sql->execute([":id_reservation" => $idReservation]);
	if (!$ok) {
		return false;
	}
	foreach ($sql->fetchAll() as $row) {
		return $row; // clé primaire → au plus une ligne
	}
	return null;
}

/**
 * Insère une réservation (cas « ajouter-valider » et « copier-valider »). Les dates
 * de création/modification et l'heure sont fixées au moment de l'appel ; la date de
 * don est fournie par l'appelant (elle diffère selon le cas). SQL à l'identique.
 *
 * @return string|false l'identifiant inséré (lastInsertId) ou false si échec.
 */
function ReservationAjouter($connexion, $idClient, $commentaire, $don, $dateDon, $dateDepart, $dateRetour, $anneeReservation, $idEtat, $idStatut, $idMembreAuteur)
{
	$sql = $connexion->prepare("INSERT INTO reservations (id_client, commentaire, don, date_don, date_depart, date_retour, date_creation, date_modification, heure_modification, annee_reservation, id_etat, id_statut_reservation, id_membre_auteur) VALUES (:id_client, :commentaire, :don, :date_don, :date_depart, :date_retour, :date_creation, :date_modification, :heure_modification, :annee_reservation, :id_etat, :id_statut_reservation, :id_membre_auteur)");
	$ok = $sql->execute([":id_client" => $idClient, ":commentaire" => $commentaire, ":don" => $don, ":date_don" => $dateDon, ":date_depart" => $dateDepart, ":date_retour" => $dateRetour, ":date_creation" => date("Y-m-d"), ":date_modification" => date("Y-m-d"), ":heure_modification" => date("H:i:s"), ":annee_reservation" => $anneeReservation, ":id_etat" => $idEtat, ":id_statut_reservation" => $idStatut, ":id_membre_auteur" => $idMembreAuteur]);
	if (!$ok) {
		return false;
	}
	return $connexion->lastInsertId();
}

/**
 * Met à jour une réservation (cas « modifier-valider » / « modifier-valider-rc »).
 * La date de modification et l'heure sont fixées à l'appel. SQL à l'identique.
 *
 * @return bool résultat de l'exécution.
 */
function ReservationModifier($connexion, $idReservation, $idClient, $commentaire, $don, $dateDon, $dateDepart, $dateRetour, $anneeReservation, $idEtat, $idStatut, $idMembreAuteur)
{
	$sql = $connexion->prepare("UPDATE reservations SET id_client=:id_client, commentaire=:commentaire, don=:don, date_don=:date_don, date_depart=:date_depart, date_retour=:date_retour, date_modification=:date_modification, heure_modification=:heure_modification, id_etat=:id_etat, annee_reservation=:annee_reservation, id_statut_reservation=:id_statut_reservation, id_membre_auteur=:id_membre_auteur WHERE (id_reservation=:id_reservation)");
	return $sql->execute([":id_client" => $idClient, ":commentaire" => $commentaire, ":don" => $don, ":date_don" => $dateDon, ":date_depart" => $dateDepart, ":date_retour" => $dateRetour, ":date_modification" => date("Y-m-d"), ":heure_modification" => date("H:i:s"), ":id_etat" => $idEtat, ":annee_reservation" => $anneeReservation, ":id_statut_reservation" => $idStatut, ":id_membre_auteur" => $idMembreAuteur, ":id_reservation" => $idReservation]);
}

/**
 * Insère une ligne article réservé (table reservations_articles). SQL à l'identique
 * (utilisé par ajouter/copier/modifier-valider).
 *
 * @return bool résultat de l'exécution.
 */
function ReservationArticleAjouter($connexion, $idReservation, $idArticle, $qteReservee, $idMembreAuteur)
{
	$sql = $connexion->prepare("INSERT INTO reservations_articles (id_reservation, id_article, quantite_reservee, id_membre_auteur) VALUES (:id_reservation, :id_article, :quantite_reservee, :id_membre_auteur)");
	return $sql->execute([":id_reservation" => $idReservation, ":id_article" => $idArticle, ":quantite_reservee" => $qteReservee, ":id_membre_auteur" => $idMembreAuteur]);
}

/**
 * Supprime toutes les lignes articles d'une réservation (avant réenregistrement,
 * cas modifier-valider). SQL repris à l'identique.
 *
 * @return bool résultat de l'exécution.
 */
function ReservationArticlesSupprimer($connexion, $idReservation)
{
	$sql = $connexion->prepare("DELETE FROM reservations_articles WHERE id_reservation=:id_reservation");
	return $sql->execute([":id_reservation" => $idReservation]);
}

/**
 * Liste les statuts de réservation, triés par libellé. SQL à l'identique.
 *
 * @return array|false false si la requête échoue ; sinon la liste des lignes.
 */
function StatutsReservationsLister($connexion)
{
	$resultat = $connexion->query("SELECT * FROM reservations_statuts ORDER BY libelle_statut ASC");
	if (!$resultat) {
		return false;
	}
	$liste = array();
	foreach ($resultat as $row) { array_push($liste, $row); }
	return $liste;
}
