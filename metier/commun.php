<?php
//
// Helpers métier transverses, extraits des contrôleurs à COMPORTEMENT IDENTIQUE.
//

/**
 * Calcule la page courante et l'offset SQL d'une liste paginée.
 *
 * Comportement FIGÉ depuis les contrôleurs `*_liste.php` :
 *   - page absente            → page 1 ;
 *   - page > nombre de pages  → ramenée au nombre de pages ;
 *   - pas de borne basse      → une page ≤ 0 donne un offset négatif (bizarrerie conservée).
 *
 * @param string|int|null $pageDemandee valeur brute de $_GET['page'] (null si absente)
 * @param int|float       $nombreDePages
 * @param int|string      $itemsParPage
 * @return array{page:int|float, offset:int|float}
 */
function PaginationOffset($pageDemandee, $nombreDePages, $itemsParPage)
{
	if ($pageDemandee === null) {
		$page = 1;
	} else {
		$page = intval($pageDemandee);
		if ($page > $nombreDePages) {
			$page = $nombreDePages;
		}
	}

	return array(
		'page'   => $page,
		'offset' => ($page - 1) * $itemsParPage,
	);
}

/**
 * Liste les fichiers joints à un enregistrement (table `fichiers`), repérés par le
 * menu et l'identifiant parent. SQL repris à l'identique des contrôleurs de formulaire.
 *
 * @return array|false false si la requête échoue ; sinon la liste des lignes.
 */
function FichiersJointsLire($connexion, $idMenu, $idParent)
{
	$sql = $connexion->prepare("SELECT id_fichier, nom_fichier, extension, poids, largeur FROM fichiers WHERE (id_menu=:id_menu AND id_parent=:id_parent)");
	$ok = $sql->execute([":id_menu" => $idMenu, ":id_parent" => $idParent]);
	if (!$ok) {
		return false;
	}
	return $sql->fetchAll();
}

/**
 * Liste tous les états (table `etats`), triés par libellé. SQL repris à l'identique
 * des contrôleurs de formulaire.
 *
 * @return array|false false si la requête échoue ; sinon la liste des lignes.
 */
function EtatsLister($connexion)
{
	$resultat = $connexion->query("SELECT * FROM etats ORDER BY libelle_etat ASC");
	if (!$resultat) {
		return false;
	}
	$liste = array();
	foreach ($resultat as $row) { array_push($liste, $row); }
	return $liste;
}
