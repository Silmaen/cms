<?php

/**
 * Caractérisation de FichiersJointsLire() (metier/commun.php).
 */
final class FichiersJointsTest extends DatabaseTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->pdo->exec(
			"INSERT INTO fichiers (id_fichier, id_menu, id_parent, nom_fichier, extension, poids, largeur) VALUES
				(1, 3, 10, 'photo.jpg', 'jpg', 1024, 800),
				(2, 3, 10, 'plan.png',  'png', 2048, 640),
				(3, 3, 99, 'autre.jpg', 'jpg',  512, 320),
				(4, 7, 10, 'menu7.jpg', 'jpg',  256, 160)"
		);
	}

	public function testLitLesFichiersDuBonMenuEtParent(): void
	{
		$liste = FichiersJointsLire($this->pdo, 3, 10);
		$this->assertCount(2, $liste); // seuls id_menu=3 ET id_parent=10
		$noms = array_map(static function ($r) { return $r['nom_fichier']; }, $liste);
		$this->assertContains('photo.jpg', $noms);
		$this->assertContains('plan.png', $noms);
	}

	public function testAucunFichierRenvoieListeVide(): void
	{
		$this->assertSame(array(), FichiersJointsLire($this->pdo, 3, 12345));
	}

	public function testEtatsListerTrieParLibelle(): void
	{
		$this->pdo->exec("INSERT INTO etats (id_etat, libelle_etat) VALUES (1, 'Actif'), (2, 'Archivé'), (3, 'Supprimé')");
		$liste = EtatsLister($this->pdo);
		$libelles = array_map(static function ($r) { return $r['libelle_etat']; }, $liste);
		$this->assertSame(['Actif', 'Archivé', 'Supprimé'], $libelles);
	}
}
