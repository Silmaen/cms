<?php
//
// Logique métier : inventaires.
//

/**
 * Renvoie l'inventaire le plus récent (par date_inventaire), ou null si aucun.
 *
 * SQL repris À L'IDENTIQUE du contrôleur articles_liste.php.
 *
 * @param bool $actifSeulement true → uniquement id_etat='1' (et inclut date_inventaire)
 * @return array|null la ligne (tableau associatif) ou null
 */
function InventaireLePlusRecent($connexion, $actifSeulement = false)
{
	if ($actifSeulement) {
		$sql = "SELECT id_inventaire, date_inventaire FROM inventaires WHERE id_etat='1' ORDER BY date_inventaire DESC LIMIT 1";
	} else {
		$sql = "SELECT id_inventaire FROM inventaires ORDER BY date_inventaire DESC LIMIT 1";
	}

	$resultat = $connexion->query($sql);
	if (!$resultat) {
		echo "INVENTAIRE : Pb d'accès à la table INVENTAIRES";
		return null;
	}
	foreach ($resultat as $row) {
		return $row; // LIMIT 1 → au plus une ligne
	}
	return null;
}

/**
 * Liste les inventaires visibles (id_etat <= $etatMax et <> 3), avec libellés de
 * statut et de type, triés puis paginés. SQL repris à l'identique du contrôleur.
 *
 * @return array liste de lignes (tableaux associatifs)
 */
function InventairesLister($connexion, $etatMax, $colonne, $sens, $offset, $itemsParPage)
{
	$liste = array();
	$sql = "SELECT t1.id_inventaire, t1.date_inventaire, t1.id_etat, t2.libelle_statut, t3.libelle_type FROM inventaires AS t1 LEFT JOIN inventaires_statuts AS t2 ON t2.id_statut_inventaire=t1.id_statut_inventaire LEFT JOIN inventaires_types AS t3 ON t3.id_type_inventaire=t1.id_type_inventaire WHERE (id_etat<=".$etatMax." AND id_etat<>3) ORDER BY ".$colonne." ".$sens." LIMIT ".$offset.", ".$itemsParPage;
	$resultat = $connexion->query($sql);
	if (!$resultat) {
		echo "LISTE : Pb d'accès à la table ITEMS";
		return $liste;
	}
	foreach ($resultat as $row) { array_push($liste, $row); }
	return $liste;
}

/**
 * Lit un inventaire et ses informations jointes (auteur, état, statut, type) pour
 * l'affichage du formulaire (cas « copier » et « modifier »). SQL à l'identique.
 *
 * @return mixed  false si échec ; null si absent ; sinon la ligne.
 */
function InventaireLire($connexion, $idInventaire)
{
	$sql = $connexion->prepare("SELECT t1.id_inventaire, t1.date_inventaire, t1.commentaire, t1.date_creation, t1.date_modification, t1.id_statut_inventaire, t4.libelle_statut, t1.id_type_inventaire, t5.libelle_type, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat FROM inventaires AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN inventaires_statuts AS t4 ON t4.id_statut_inventaire=t1.id_statut_inventaire LEFT JOIN inventaires_types AS t5 ON t5.id_type_inventaire=t1.id_type_inventaire WHERE (t1.id_inventaire=:id_inventaire)");
	$ok = $sql->execute([":id_inventaire" => $idInventaire]);
	if (!$ok) {
		return false;
	}
	foreach ($sql->fetchAll() as $row) {
		return $row; // clé primaire → au plus une ligne
	}
	return null;
}

/**
 * Détail d'un article dans un inventaire donné (quantités et commentaire).
 * SQL repris à l'identique (utilisé dans les boucles de construction de liste).
 *
 * @return array|false false si la requête échoue ; sinon la liste des lignes.
 */
function InventaireArticleDetail($connexion, $idInventaire, $idArticle)
{
	$sql = $connexion->prepare("SELECT t1.id_article, t1.quantite_precedent, t1.quantite_totale, t1.commentaire FROM inventaires_articles AS t1 LEFT JOIN inventaires AS t2 ON t2.id_inventaire=t1.id_inventaire WHERE t2.id_inventaire=:id_inventaire AND t1.id_article=:id_article");
	$ok = $sql->execute([":id_inventaire" => $idInventaire, ":id_article" => $idArticle]);
	if (!$ok) {
		return false;
	}
	return $sql->fetchAll();
}

/**
 * Insère un inventaire (cas « ajouter-valider »). Fixe id_etat='1' et les dates de
 * création/modification du jour. La date d'inventaire est fournie déjà formatée.
 * SQL repris à l'identique.
 *
 * @return string|false l'identifiant inséré (lastInsertId) ou false si échec.
 */
function InventaireAjouter($connexion, $commentaire, $dateInventaire, $idStatut, $idType, $idMembreAuteur)
{
	$sql = $connexion->prepare("INSERT INTO inventaires (commentaire, date_inventaire, date_creation, date_modification, id_statut_inventaire, id_type_inventaire, id_etat, id_membre_auteur) VALUES (:commentaire, :date_inventaire, :date_creation, :date_modification, :id_statut_inventaire, :id_type_inventaire, :id_etat, :id_membre_auteur)");
	$ok = $sql->execute([":commentaire" => $commentaire, ":date_inventaire" => $dateInventaire, ":date_creation" => date("Y-m-d"), ":date_modification" => date("Y-m-d"), ":id_statut_inventaire" => $idStatut, ":id_type_inventaire" => $idType, ":id_etat" => '1', ":id_membre_auteur" => $idMembreAuteur]);
	if (!$ok) {
		return false;
	}
	return $connexion->lastInsertId();
}

/**
 * Duplique un inventaire (cas « copier-valider »). N'écrit pas id_etat (colonne omise).
 * SQL repris à l'identique.
 *
 * @return string|false l'identifiant inséré (lastInsertId) ou false si échec.
 */
function InventaireCopier($connexion, $commentaire, $dateInventaire, $idStatut, $idType, $idMembreAuteur)
{
	$sql = $connexion->prepare("INSERT INTO inventaires (commentaire, date_inventaire, date_creation, date_modification, id_statut_inventaire, id_type_inventaire, id_membre_auteur) VALUES (:commentaire, :date_inventaire, :date_creation, :date_modification, :id_statut_inventaire, :id_type_inventaire, :id_membre_auteur)");
	$ok = $sql->execute([":commentaire" => $commentaire, ":date_inventaire" => $dateInventaire, ":date_creation" => date("Y-m-d"), ":date_modification" => date("Y-m-d"), ":id_statut_inventaire" => $idStatut, ":id_type_inventaire" => $idType, ":id_membre_auteur" => $idMembreAuteur]);
	if (!$ok) {
		return false;
	}
	return $connexion->lastInsertId();
}

/**
 * Met à jour un inventaire (cas « modifier-valider »). SQL repris à l'identique.
 *
 * @return bool résultat de l'exécution.
 */
function InventaireModifier($connexion, $idInventaire, $commentaire, $dateInventaire, $idStatut, $idType, $idMembreAuteur)
{
	$sql = $connexion->prepare("UPDATE inventaires SET commentaire=:commentaire, date_inventaire=:date_inventaire, date_modification=:date_modification, id_statut_inventaire=:id_statut_inventaire, id_type_inventaire=:id_type_inventaire, id_membre_auteur=:id_membre_auteur WHERE (id_inventaire=:id_inventaire)");
	return $sql->execute([":commentaire" => $commentaire, ":date_inventaire" => $dateInventaire, ":date_modification" => date("Y-m-d"), ":id_statut_inventaire" => $idStatut, ":id_type_inventaire" => $idType, ":id_membre_auteur" => $idMembreAuteur, ":id_inventaire" => $idInventaire]);
}

/**
 * Insère une ligne article dans un inventaire (table inventaires_articles).
 * SQL repris à l'identique (utilisé par ajouter/copier/modifier-valider).
 *
 * @return bool résultat de l'exécution.
 */
function InventaireArticleAjouter($connexion, $idInventaire, $idArticle, $qtePrecedent, $qteTotale, $commentaire, $idMembreAuteur)
{
	$sql = $connexion->prepare("INSERT INTO inventaires_articles (id_inventaire, id_article, quantite_precedent, quantite_totale, commentaire, id_membre_auteur) VALUES (:id_inventaire, :id_article, :quantite_precedent, :quantite_totale, :commentaire, :id_membre_auteur)");
	return $sql->execute([":id_inventaire" => $idInventaire, ":id_article" => $idArticle, ":quantite_precedent" => $qtePrecedent, ":quantite_totale" => $qteTotale, ":commentaire" => $commentaire, ":id_membre_auteur" => $idMembreAuteur]);
}

/**
 * Passe à l'état « archivé » (id_etat='2') les inventaires antérieurs à une date.
 * SQL repris à l'identique du contrôleur (cas ajouter/copier-valider).
 *
 * @return bool résultat de l'exécution.
 */
function InventaireMajEtatAnciens($connexion, $dateInventaire)
{
	$sql = $connexion->prepare("UPDATE inventaires SET id_etat='2' WHERE (date_inventaire<:date_inventaire)");
	return $sql->execute([":date_inventaire" => $dateInventaire]);
}

/**
 * Supprime toutes les lignes articles d'un inventaire (avant réenregistrement, cas
 * modifier-valider). SQL repris à l'identique.
 *
 * @return bool résultat de l'exécution.
 */
function InventaireArticlesSupprimer($connexion, $idInventaire)
{
	$sql = $connexion->prepare("DELETE FROM inventaires_articles WHERE id_inventaire=:id_inventaire");
	return $sql->execute([":id_inventaire" => $idInventaire]);
}

/**
 * Liste les statuts d'inventaire, triés par libellé. SQL à l'identique.
 *
 * @return array|false false si la requête échoue ; sinon la liste des lignes.
 */
function StatutsInventairesLister($connexion)
{
	$resultat = $connexion->query("SELECT * FROM inventaires_statuts ORDER BY libelle_statut ASC");
	if (!$resultat) {
		return false;
	}
	$liste = array();
	foreach ($resultat as $row) { array_push($liste, $row); }
	return $liste;
}

/**
 * Liste les types d'inventaire, triés par libellé. SQL à l'identique.
 *
 * @return array|false false si la requête échoue ; sinon la liste des lignes.
 */
function TypesInventairesLister($connexion)
{
	$resultat = $connexion->query("SELECT * FROM inventaires_types ORDER BY libelle_type ASC");
	if (!$resultat) {
		return false;
	}
	$liste = array();
	foreach ($resultat as $row) { array_push($liste, $row); }
	return $liste;
}
