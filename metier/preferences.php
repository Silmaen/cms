<?php
//
// Logique métier : préférences de l'utilisateur connecté.
//
// Les préférences libres sont stockées en clé/valeur dans `admin_utilisateurs_preferences`
// (créée par migration, voir metier/schema.php). Une préférence absente vaut sa valeur
// par défaut, qui reproduit le comportement antérieur à l'écran de préférences.
//

/**
 * Préférences connues : clé → (valeur → libellé). La première valeur est la valeur par défaut.
 *
 * @return array<string,array<string,string>>
 */
function PreferencesChoix()
{
	return array(
		'theme' => array(
			'clair'  => 'Clair',
			'sombre' => 'Sombre',
			'auto'   => 'Automatique (suit le réglage de l\'appareil)',
		),
	);
}

/**
 * Nombres de lignes par page proposés (mêmes valeurs que le sélecteur des listes).
 *
 * @return array<int,string>
 */
function PreferencesItemsParPageChoix()
{
	return array(
		100   => '100 par page',
		200   => '200 par page',
		300   => '300 par page',
		10000 => 'Tous les items',
	);
}

/**
 * Lit les préférences d'un utilisateur, complétées par les valeurs par défaut.
 *
 * @return array<string,string> clé → valeur (toujours toutes les clés de PreferencesChoix()).
 */
function PreferencesLire($connexion, $idUtilisateur)
{
	$preferences = array();
	foreach (PreferencesChoix() as $cle => $valeurs) {
		$preferences[$cle] = array_key_first($valeurs);
	}

	$sql = $connexion->prepare("SELECT cle, valeur FROM admin_utilisateurs_preferences WHERE id_utilisateur=:id_utilisateur");
	if (!$sql || !$sql->execute([":id_utilisateur" => $idUtilisateur])) {
		return $preferences;
	}
	foreach ($sql->fetchAll() as $row) {
		// Une valeur inconnue (préférence retirée, valeur obsolète) est ignorée.
		if (isset($preferences[$row["cle"]]) && isset(PreferencesChoix()[$row["cle"]][$row["valeur"]])) {
			$preferences[$row["cle"]] = $row["valeur"];
		}
	}
	return $preferences;
}

/**
 * Enregistre une préférence, après contrôle de la clé et de la valeur.
 *
 * @return bool false si la clé ou la valeur est inconnue, ou si l'écriture échoue.
 */
function PreferenceEnregistrer($connexion, $idUtilisateur, $cle, $valeur)
{
	$choix = PreferencesChoix();
	if (!isset($choix[$cle][$valeur])) {
		return false;
	}
	$sql = $connexion->prepare("REPLACE INTO admin_utilisateurs_preferences (id_utilisateur, cle, valeur, date_modification) VALUES (:id_utilisateur, :cle, :valeur, NOW())");
	return $sql->execute([":id_utilisateur" => $idUtilisateur, ":cle" => $cle, ":valeur" => $valeur]);
}

/**
 * Nombre de lignes par page de l'utilisateur (colonne `admin_utilisateurs.items_par_page`).
 *
 * @return int|false false si la lecture échoue.
 */
function PreferencesItemsParPageLire($connexion, $idUtilisateur)
{
	$sql = $connexion->prepare("SELECT items_par_page FROM admin_utilisateurs WHERE id_utilisateur=:id_utilisateur");
	if (!$sql->execute([":id_utilisateur" => $idUtilisateur])) {
		return false;
	}
	return intval($sql->fetchColumn());
}

/**
 * Applique un nombre de lignes par page à l'utilisateur ET à tous ses écrans de liste
 * (`admin_utilisateurs_session`), où le sélecteur de chaque liste le mémorise.
 *
 * @return bool false si la valeur n'est pas proposée ou si l'écriture échoue.
 */
function PreferencesItemsParPageEnregistrer($connexion, $idUtilisateur, $itemsParPage)
{
	if (!isset(PreferencesItemsParPageChoix()[intval($itemsParPage)])) {
		return false;
	}
	$parametres = [":items_par_page" => intval($itemsParPage), ":id_utilisateur" => $idUtilisateur];
	$sql = $connexion->prepare("UPDATE admin_utilisateurs SET items_par_page=:items_par_page WHERE id_utilisateur=:id_utilisateur");
	if (!$sql->execute($parametres)) {
		return false;
	}
	$sql = $connexion->prepare("UPDATE admin_utilisateurs_session SET items_par_page=:items_par_page WHERE id_utilisateur=:id_utilisateur");
	return $sql->execute($parametres);
}
