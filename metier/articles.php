<?php
//
// Logique métier : articles.
//

/**
 * Liste les articles visibles (id_etat <= $etatMax), triés puis paginés.
 *
 * SQL repris À L'IDENTIQUE du contrôleur `articles_liste.php`, y compris la
 * concaténation de $colonne / $sens : la sécurisation par liste blanche relève
 * du chantier ultérieur « sécurité SQL » (ce serait un changement de comportement).
 *
 * @return array liste de lignes (tableaux associatifs)
 */
function ArticlesLister($connexion, $etatMax, $colonne, $sens, $offset, $itemsParPage)
{
	$liste = array();
	$sql = "SELECT * FROM articles WHERE id_etat<=".$etatMax." ORDER BY ".$colonne." ".$sens." LIMIT ".$offset.", ".$itemsParPage;

	$resultat = $connexion->query($sql);
	if (!$resultat) {
		echo "LISTE : Pb d'accès à la table ITEMS";
		return $liste;
	}
	foreach ($resultat as $row) {
		array_push($liste, $row);
	}
	return $liste;
}

/**
 * Quantité totale d'un article dans un inventaire donné (table inventaires_articles).
 * SQL repris à l'identique du contrôleur.
 *
 * @return mixed  false si la requête échoue ; null si l'article n'est pas dans
 *                l'inventaire ; sinon la quantité totale (dernière ligne trouvée).
 */
function ArticleQuantiteTotale($connexion, $idInventaire, $idArticle)
{
	$sql = "SELECT t1.quantite_totale FROM inventaires_articles AS t1 LEFT JOIN inventaires AS t2 ON t2.id_inventaire=t1.id_inventaire WHERE t2.id_inventaire=".$idInventaire." AND t1.id_article=".$idArticle;
	$resultat = $connexion->query($sql);
	if (!$resultat) {
		echo "LISTE 2: Pb d'accès à la table ITEMS";
		return false;
	}
	$quantite = null;
	foreach ($resultat as $row) {
		$quantite = $row["quantite_totale"];
	}
	return $quantite;
}

/**
 * Quantité d'un article réservée à une date donnée (SUM sur reservations_articles).
 * SQL repris à l'identique du contrôleur.
 *
 * @return mixed  false si la requête échoue ; sinon la somme réservée (peut être null
 *                s'il n'y a aucune réservation sur la période).
 */
function ArticleQuantiteReservee($connexion, $idArticle, $date)
{
	$sql = $connexion->prepare("SELECT SUM(t1.quantite_reservee) as total_reserve FROM reservations_articles AS t1 LEFT JOIN reservations AS t2 ON t2.id_reservation=t1.id_reservation WHERE (t1.id_article=:id_article AND date_depart<'".$date."' AND t2.date_retour>'".$date."')");
	$ok = $sql->execute([":id_article" => $idArticle]);
	if (!$ok) {
		echo "STOCK : Pb d'accès à la table articles";
		return false;
	}
	$reserve = null;
	foreach ($sql->fetchAll() as $row) {
		$reserve = $row["total_reserve"];
	}
	return $reserve;
}

/**
 * Lit un article et ses informations jointes (auteur, état) pour l'affichage du
 * formulaire (cas « copier » et « modifier »). SQL repris à l'identique.
 *
 * @return mixed  false si la requête échoue ; null si l'article n'existe pas ;
 *                sinon la ligne (tableau associatif).
 */
function ArticleLire($connexion, $idArticle)
{
	$sql = $connexion->prepare("SELECT t1.id_article, t1.designation, t1.commentaire,  t1.date_creation, t1.date_modification, t1.ordre_article, t1.id_membre_auteur, t2.nom_utilisateur AS nom_membre_auteur, t2.prenom_utilisateur AS prenom_membre_auteur, t3.id_etat, t3.libelle_etat FROM articles AS t1 LEFT JOIN admin_utilisateurs AS t2 ON t2.id_utilisateur=t1.id_membre_auteur LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat WHERE (t1.id_article= :id_article)");
	$ok = $sql->execute([":id_article" => $idArticle]);
	if (!$ok) {
		return false;
	}
	foreach ($sql->fetchAll() as $row) {
		return $row; // clé primaire → au plus une ligne
	}
	return null;
}

/**
 * Insère un nouvel article (cas « ajouter-valider »). Fixe id_etat='1' et les dates
 * du jour. SQL et paramètres repris à l'identique du contrôleur.
 *
 * @return string|false l'identifiant inséré (lastInsertId) ou false si échec.
 */
function ArticleAjouter($connexion, $designation, $commentaire, $ordreArticle, $idMembreAuteur)
{
	$sql = $connexion->prepare("INSERT INTO articles (designation,commentaire, date_creation, date_modification, id_etat, ordre_article, id_membre_auteur) VALUES (:designation, :commentaire, :date_creation, :date_modification, :id_etat, :ordre_article, :id_membre_auteur)");
	$ok = $sql->execute([":designation" => $designation, ":commentaire" => $commentaire, ":date_creation" => date("Y-m-d"), ":date_modification" => date("Y-m-d"), ":id_etat" => '1', ":ordre_article" => $ordreArticle, ":id_membre_auteur" => $idMembreAuteur]);
	if (!$ok) {
		return false;
	}
	return $connexion->lastInsertId();
}

/**
 * Duplique un article (cas « copier-valider »). Comme le contrôleur d'origine,
 * n'écrit PAS id_etat (colonne omise → valeur par défaut de la table).
 *
 * @return string|false l'identifiant inséré (lastInsertId) ou false si échec.
 */
function ArticleCopier($connexion, $designation, $commentaire, $ordreArticle, $idMembreAuteur)
{
	$sql = $connexion->prepare("INSERT INTO articles (designation, commentaire, date_creation, date_modification, ordre_article, id_membre_auteur) VALUES (:designation, :commentaire, :date_creation, :date_modification, :ordre_article, :id_membre_auteur)");
	$ok = $sql->execute([":designation" => $designation, ":commentaire" => $commentaire, ":date_creation" => date("Y-m-d"), ":date_modification" => date("Y-m-d"), ":ordre_article" => $ordreArticle, ":id_membre_auteur" => $idMembreAuteur]);
	if (!$ok) {
		return false;
	}
	return $connexion->lastInsertId();
}

/**
 * Met à jour un article (cas « modifier-valider »). Ne touche pas à id_etat ni à
 * date_creation, comme le contrôleur d'origine. SQL repris à l'identique.
 *
 * @return bool résultat de l'exécution de la requête.
 */
function ArticleModifier($connexion, $idArticle, $designation, $commentaire, $ordreArticle, $idMembreAuteur)
{
	$sql = $connexion->prepare("UPDATE articles SET designation=:designation, commentaire=:commentaire, date_modification=:date_modification, ordre_article=:ordre_article, id_membre_auteur=:id_membre_auteur WHERE (id_article=:id_article)");
	return $sql->execute([":id_article" => $idArticle, ":designation" => $designation, ":commentaire" => $commentaire, ":date_modification" => date("Y-m-d"), ":ordre_article" => $ordreArticle, ":id_membre_auteur" => $idMembreAuteur]);
}
