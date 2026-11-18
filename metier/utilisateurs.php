<?php
//
// Logique métier : utilisateurs et groupes d'utilisateurs.
//

/**
 * Liste les utilisateurs visibles (id_etat <= $etatMax), avec le libellé de leur
 * groupe, triés puis paginés. SQL repris à l'identique du contrôleur.
 *
 * @return array liste de lignes (tableaux associatifs)
 */
function UtilisateursLister($connexion, $etatMax, $colonne, $sens, $offset, $itemsParPage)
{
	$liste = array();
	$sql = "SELECT t1.id_utilisateur, t1.nom_utilisateur, t1.prenom_utilisateur, t1.email_utilisateur, t1.mdp_utilisateur, t1.id_utilisateur_groupe, t1.id_etat, t1.cle_utilisateur, t1.email_utilisateur, t2.libelle_utilisateur_groupe FROM admin_utilisateurs AS t1 LEFT JOIN admin_utilisateurs_groupes AS t2 ON t1.id_utilisateur_groupe=t2.id_utilisateur_groupe WHERE id_etat<=".$etatMax." ORDER BY ".$colonne." ".$sens." LIMIT ".$offset.", ".$itemsParPage;
	$resultat = $connexion->query($sql);
	if (!$resultat) {
		echo "LISTE : Pb d'accès à la table ITEMS";
		return $liste;
	}
	foreach ($resultat as $row) { array_push($liste, $row); }
	return $liste;
}

/**
 * Liste les groupes d'utilisateurs, triés puis paginés (pas de filtre d'état).
 * SQL repris à l'identique du contrôleur.
 *
 * @return array liste de lignes (tableaux associatifs)
 */
function UtilisateursGroupesLister($connexion, $colonne, $sens, $offset, $itemsParPage)
{
	$liste = array();
	$sql = "SELECT t1.id_utilisateur_groupe, t1.libelle_utilisateur_groupe FROM admin_utilisateurs_groupes AS t1 ORDER BY ".$colonne." ".$sens." LIMIT ".$offset.", ".$itemsParPage;
	$resultat = $connexion->query($sql);
	if (!$resultat) {
		echo "LISTE : Pb d'accès à la table ITEMS";
		return $liste;
	}
	foreach ($resultat as $row) { array_push($liste, $row); }
	return $liste;
}

/**
 * Lit un groupe d'utilisateurs pour l'affichage du formulaire (cas « copier » et
 * « modifier »). SQL repris à l'identique (la différence d'espace `= :` entre les
 * deux contrôleurs d'origine est sans effet).
 *
 * @return mixed  false si la requête échoue ; null si le groupe n'existe pas ;
 *                sinon la ligne (tableau associatif).
 */
function UtilisateursGroupeLire($connexion, $idGroupe)
{
	$sql = $connexion->prepare("SELECT t1.* FROM admin_utilisateurs_groupes AS t1 WHERE (t1.id_utilisateur_groupe=:id_utilisateur_groupe)");
	$ok = $sql->execute([":id_utilisateur_groupe" => $idGroupe]);
	if (!$ok) {
		return false;
	}
	foreach ($sql->fetchAll() as $row) {
		return $row; // clé primaire → au plus une ligne
	}
	return null;
}

/**
 * Liste les menus avec le droit associé à un groupe donné (jointure
 * admin_menu / admin_menus_groupes). SQL repris à l'identique des cas « copier »
 * et « modifier ». (Le cas « ajouter » utilise une variante triée, laissée en place.)
 *
 * @return array|false false si la requête échoue ; sinon la liste des lignes.
 */
function UtilisateursGroupeMenusDroits($connexion, $idGroupe)
{
	$sql = $connexion->prepare("SELECT t2.droit, t2.id_utilisateur_groupe, t1.id_admin_menu, t1.titre_fr AS titre_menu FROM admin_menu AS t1 LEFT JOIN admin_menus_groupes AS t2 ON t2.id_admin_menu=t1.id_admin_menu WHERE t2.id_utilisateur_groupe=:id_utilisateur_groupe");
	$ok = $sql->execute([":id_utilisateur_groupe" => $idGroupe]);
	if (!$ok) {
		return false;
	}
	return $sql->fetchAll();
}

/**
 * Insère un groupe d'utilisateurs (cas « ajouter-valider » et « copier-valider »).
 * SQL repris à l'identique.
 *
 * @return string|false l'identifiant inséré (lastInsertId) ou false si échec.
 */
function UtilisateursGroupeAjouter($connexion, $libelle, $idMembreAuteur)
{
	$sql = $connexion->prepare("INSERT INTO admin_utilisateurs_groupes (libelle_utilisateur_groupe, id_membre_auteur) VALUES (:libelle_utilisateur_groupe, :id_membre_auteur)");
	$ok = $sql->execute([":libelle_utilisateur_groupe" => $libelle, ":id_membre_auteur" => $idMembreAuteur]);
	if (!$ok) {
		return false;
	}
	return $connexion->lastInsertId();
}

/**
 * Met à jour le libellé d'un groupe (cas « modifier-valider »). SQL à l'identique.
 *
 * @return bool résultat de l'exécution.
 */
function UtilisateursGroupeModifier($connexion, $idGroupe, $libelle, $idMembreAuteur)
{
	$sql = $connexion->prepare("UPDATE admin_utilisateurs_groupes SET libelle_utilisateur_groupe=:libelle_utilisateur_groupe,  id_membre_auteur=:id_membre_auteur WHERE (id_utilisateur_groupe=:id_utilisateur_groupe)");
	return $sql->execute([":id_utilisateur_groupe" => $idGroupe, ":libelle_utilisateur_groupe" => $libelle, ":id_membre_auteur" => $idMembreAuteur]);
}

/**
 * Insère le droit d'un groupe sur un menu (table admin_menus_groupes). SQL à l'identique.
 *
 * @return bool résultat de l'exécution.
 */
function MenuGroupeDroitAjouter($connexion, $idAdminMenu, $idGroupe, $droit, $idMembreAuteur)
{
	$sql = $connexion->prepare("INSERT INTO admin_menus_groupes (id_admin_menu, id_utilisateur_groupe, droit, id_membre_auteur) VALUES (:id_admin_menu, :id_utilisateur_groupe, :droit, :id_membre_auteur)");
	return $sql->execute([":id_admin_menu" => $idAdminMenu, ":droit" => $droit, ":id_utilisateur_groupe" => $idGroupe, ":id_membre_auteur" => $idMembreAuteur]);
}

/**
 * Met à jour le droit d'un groupe sur un menu (table admin_menus_groupes). SQL à l'identique.
 *
 * @return bool résultat de l'exécution.
 */
function MenuGroupeDroitModifier($connexion, $idAdminMenu, $idGroupe, $droit, $idMembreAuteur)
{
	$sql = $connexion->prepare("UPDATE admin_menus_groupes SET droit=:droit, id_membre_auteur=:id_membre_auteur WHERE (id_admin_menu=:id_admin_menu AND id_utilisateur_groupe=:id_utilisateur_groupe)");
	return $sql->execute([":id_admin_menu" => $idAdminMenu, ":droit" => $droit, ":id_utilisateur_groupe" => $idGroupe, ":id_membre_auteur" => $idMembreAuteur]);
}

/**
 * Lit un utilisateur et ses informations jointes (groupe, état, auteur) pour
 * l'affichage du formulaire (cas « copier » et « modifier »). SQL repris à
 * l'identique (mise à plat des sauts de ligne, sans effet).
 *
 * @return mixed  false si la requête échoue ; null si l'utilisateur n'existe pas ;
 *                sinon la ligne (tableau associatif).
 */
function UtilisateurLire($connexion, $idUtilisateur)
{
	$sql = $connexion->prepare("SELECT t1.id_utilisateur, t1.nom_utilisateur, t1.prenom_utilisateur, t1.telephone_utilisateur, t1.email_utilisateur, t1.mdp_utilisateur, t1.id_utilisateur_groupe, t1.date_creation, t1.date_modification, t1.id_membre_auteur, t2.id_utilisateur_groupe, t2.libelle_utilisateur_groupe, t3.id_etat, t3.libelle_etat, t4.nom_utilisateur AS nom_membre_auteur, t4.prenom_utilisateur AS prenom_membre_auteur FROM admin_utilisateurs AS t1 LEFT JOIN admin_utilisateurs_groupes AS t2 ON t1.id_utilisateur_groupe=t2.id_utilisateur_groupe LEFT JOIN etats AS t3 ON t3.id_etat=t1.id_etat LEFT JOIN admin_utilisateurs AS t4 ON t4.id_utilisateur=t1.id_membre_auteur WHERE (t1.id_utilisateur = :id_utilisateur)");
	$ok = $sql->execute([":id_utilisateur" => $idUtilisateur]);
	if (!$ok) {
		return false;
	}
	foreach ($sql->fetchAll() as $row) {
		return $row; // clé primaire → au plus une ligne
	}
	return null;
}

/**
 * Liste tous les groupes d'utilisateurs (id + libellé), triés par libellé, sans
 * pagination. SQL repris à l'identique des cas « ajouter »/« copier »/« modifier »
 * du formulaire utilisateur.
 *
 * @return array|false false si la requête échoue ; sinon la liste des lignes.
 */
function UtilisateursGroupesTous($connexion)
{
	$resultat = $connexion->query("SELECT id_utilisateur_groupe, libelle_utilisateur_groupe FROM admin_utilisateurs_groupes ORDER BY libelle_utilisateur_groupe ASC");
	if (!$resultat) {
		return false;
	}
	$liste = array();
	foreach ($resultat as $row) { array_push($liste, $row); }
	return $liste;
}

/**
 * Insère un utilisateur (cas « ajouter-valider »). Fixe id_etat='1' et les dates du
 * jour ; le mot de passe (déjà haché) et la clé sont fournis par l'appelant, comme
 * dans le contrôleur d'origine. SQL repris à l'identique.
 *
 * @return string|false l'identifiant inséré (lastInsertId) ou false si échec.
 */
function UtilisateurAjouter($connexion, $nom, $prenom, $telephone, $email, $mdpHache, $idGroupe, $cle, $idMembreAuteur)
{
	$sql = $connexion->prepare("INSERT INTO admin_utilisateurs (nom_utilisateur, prenom_utilisateur, telephone_utilisateur, email_utilisateur, mdp_utilisateur, id_utilisateur_groupe, id_etat, date_creation, date_modification, cle_utilisateur, id_membre_auteur) VALUES (:nom_utilisateur, :prenom_utilisateur, :telephone_utilisateur, :email_utilisateur, :mdp_utilisateur, :id_utilisateur_groupe, :id_etat, :date_creation, :date_modification, :cle_utilisateur, :id_membre_auteur)");
	$ok = $sql->execute([":nom_utilisateur" => $nom, ":prenom_utilisateur" => $prenom, ":telephone_utilisateur" => $telephone, ":email_utilisateur" => $email, ":mdp_utilisateur" => $mdpHache, ":id_utilisateur_groupe" => $idGroupe, ":id_etat" => '1', ":date_creation" => date("Y-m-d"), ":date_modification" => date("Y-m-d"), ":cle_utilisateur" => $cle, ":id_membre_auteur" => $idMembreAuteur]);
	if (!$ok) {
		return false;
	}
	return $connexion->lastInsertId();
}

/**
 * Met à jour un utilisateur AVEC son mot de passe (cas « modifier-valider » quand un
 * nouveau mot de passe est saisi). Le mot de passe est déjà haché. SQL à l'identique.
 *
 * @return bool résultat de l'exécution.
 */
function UtilisateurModifier($connexion, $idUtilisateur, $nom, $prenom, $telephone, $email, $mdpHache, $idGroupe, $idMembreAuteur)
{
	$sql = $connexion->prepare("UPDATE admin_utilisateurs SET nom_utilisateur=:nom_utilisateur, prenom_utilisateur=:prenom_utilisateur, telephone_utilisateur=:telephone_utilisateur, email_utilisateur=:email_utilisateur, mdp_utilisateur=:mdp_utilisateur, id_utilisateur_groupe=:id_utilisateur_groupe, date_modification=:date_modification, id_membre_auteur=:id_membre_auteur WHERE (id_utilisateur=:id_utilisateur)");
	return $sql->execute([":id_utilisateur" => $idUtilisateur, ":nom_utilisateur" => $nom, ":prenom_utilisateur" => $prenom, ":telephone_utilisateur" => $telephone, ":email_utilisateur" => $email, ":mdp_utilisateur" => $mdpHache, ":id_utilisateur_groupe" => $idGroupe, ":date_modification" => date("Y-m-d"), ":id_membre_auteur" => $idMembreAuteur]);
}

/**
 * Met à jour un utilisateur SANS toucher à son mot de passe (cas « modifier-valider »
 * quand le champ mot de passe est laissé vide). SQL repris à l'identique.
 *
 * @return bool résultat de l'exécution.
 */
function UtilisateurModifierSansMdp($connexion, $idUtilisateur, $nom, $prenom, $telephone, $email, $idGroupe, $idMembreAuteur)
{
	$sql = $connexion->prepare("UPDATE admin_utilisateurs SET nom_utilisateur=:nom_utilisateur, prenom_utilisateur=:prenom_utilisateur, telephone_utilisateur=:telephone_utilisateur, email_utilisateur=:email_utilisateur, id_utilisateur_groupe=:id_utilisateur_groupe, date_modification=:date_modification, id_membre_auteur=:id_membre_auteur WHERE (id_utilisateur=:id_utilisateur)");
	return $sql->execute([":id_utilisateur" => $idUtilisateur, ":nom_utilisateur" => $nom, ":prenom_utilisateur" => $prenom, ":telephone_utilisateur" => $telephone, ":email_utilisateur" => $email, ":id_utilisateur_groupe" => $idGroupe, ":date_modification" => date("Y-m-d"), ":id_membre_auteur" => $idMembreAuteur]);
}

///////////////////////////////////////
// Réinitialisation du mot de passe //
///////////////////////////////////////

/**
 * Durée de validité d'un lien de réinitialisation, en secondes.
 *
 * @return int
 */
function MdpJetonDureeValidite()
{
	return 3600;
}

/**
 * Délai minimal entre deux e-mails de réinitialisation pour un même compte, en secondes.
 *
 * @return int
 */
function MdpDelaiEntreDemandes()
{
	return 900;
}

/**
 * Le mot de passe respecte-t-il la politique affichée (8 caractères sans espace, au
 * moins une minuscule, une majuscule et un chiffre) ? Même règle que le `pattern` HTML.
 *
 * @return bool
 */
function MdpRespectePolitique($mdp)
{
	return preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])\S{8,}$/', $mdp) === 1;
}

/**
 * Prépare une demande « mot de passe perdu » : si l'adresse est celle d'un compte actif
 * et qu'aucun e-mail n'est parti depuis MdpDelaiEntreDemandes(), émet un nouveau jeton
 * aléatoire (seule son empreinte est stockée ; l'ancien lien devient invalide).
 * $forcer ignore le délai (envoi demandé par un administrateur connecté).
 *
 * @return array|null null si rien ne doit être envoyé ; sinon la ligne de l'utilisateur
 *                    (nom, prénom, e-mail) complétée du jeton en clair (`jeton`).
 */
function MdpPerduDemander($connexion, $email, $maintenant, $forcer = false)
{
	if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
		return null;
	}
	$sql = $connexion->prepare("SELECT id_utilisateur, nom_utilisateur, prenom_utilisateur, email_utilisateur, date_jeton_mdp FROM admin_utilisateurs WHERE (email_utilisateur = :email AND id_etat = 1) LIMIT 1");
	if (!$sql->execute([":email" => $email])) {
		return null;
	}
	$row = $sql->fetch();
	if (!$row) {
		return null;
	}
	if (!$forcer && $row["date_jeton_mdp"] !== null && $maintenant - strtotime($row["date_jeton_mdp"]) < MdpDelaiEntreDemandes()) {
		return null;
	}

	$jeton = bin2hex(random_bytes(32));
	$sql = $connexion->prepare("UPDATE admin_utilisateurs SET jeton_mdp = :jeton_mdp, date_jeton_mdp = :date_jeton_mdp WHERE (id_utilisateur = :id_utilisateur)");
	if (!$sql->execute([":jeton_mdp" => hash('sha256', $jeton), ":date_jeton_mdp" => date("Y-m-d H:i:s", $maintenant), ":id_utilisateur" => $row["id_utilisateur"]])) {
		return null;
	}
	$row["jeton"] = $jeton;
	return $row;
}

/**
 * Vérifie un lien de réinitialisation : compte actif, jeton correct et non expiré.
 *
 * @return int|null l'identifiant de l'utilisateur, ou null si le lien est invalide.
 */
function MdpJetonVerifier($connexion, $email, $jeton, $maintenant)
{
	if (!is_string($jeton) || $jeton === '') {
		return null;
	}
	$sql = $connexion->prepare("SELECT id_utilisateur, jeton_mdp, date_jeton_mdp FROM admin_utilisateurs WHERE (email_utilisateur = :email AND id_etat = 1 AND jeton_mdp IS NOT NULL)");
	if (!$sql->execute([":email" => $email])) {
		return null;
	}
	foreach ($sql->fetchAll() as $row) {
		if (hash_equals($row["jeton_mdp"], hash('sha256', $jeton)) && $maintenant - strtotime($row["date_jeton_mdp"]) <= MdpJetonDureeValidite()) {
			return intval($row["id_utilisateur"]);
		}
	}
	return null;
}

/**
 * Enregistre le nouveau mot de passe (déjà haché) et consomme le jeton : le lien ne
 * sert qu'une fois. La date d'émission est gardée pour le délai entre demandes.
 *
 * @return bool résultat de l'exécution.
 */
function MdpReinitialiser($connexion, $idUtilisateur, $mdpHache)
{
	$sql = $connexion->prepare("UPDATE admin_utilisateurs SET mdp_utilisateur = :mdp_utilisateur, jeton_mdp = NULL WHERE (id_utilisateur = :id_utilisateur)");
	return $sql->execute([":mdp_utilisateur" => $mdpHache, ":id_utilisateur" => $idUtilisateur]);
}
