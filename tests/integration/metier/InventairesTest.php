<?php

/**
 * Caractérisation de InventaireLePlusRecent() (metier/inventaires.php).
 */
final class InventairesTest extends DatabaseTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->pdo->exec(
			"INSERT INTO inventaires (id_inventaire, date_inventaire, id_etat) VALUES
				(1, '2023-01-01', 1),
				(2, '2024-06-01', 2),
				(3, '2024-03-01', 1)"
		);
	}

	public function testPlusRecentTousEtats(): void
	{
		$r = InventaireLePlusRecent($this->pdo, false);
		$this->assertEquals(2, $r['id_inventaire']); // 2024-06-01 (peu importe l'état)
	}

	public function testPlusRecentActifSeulement(): void
	{
		$r = InventaireLePlusRecent($this->pdo, true);
		$this->assertEquals(3, $r['id_inventaire']);        // plus récent avec id_etat=1
		$this->assertArrayHasKey('date_inventaire', $r);    // inclut la date
	}

	public function testAucunInventaireRenvoieNull(): void
	{
		$this->pdo->exec("DELETE FROM inventaires");
		$this->assertNull(InventaireLePlusRecent($this->pdo, false));
	}

	public function testListeExclutSupprimesEtJointStatutType(): void
	{
		$this->pdo->exec("INSERT INTO inventaires_statuts (id_statut_inventaire, libelle_statut) VALUES (1, 'Ouvert')");
		$this->pdo->exec("INSERT INTO inventaires_types (id_type_inventaire, libelle_type) VALUES (1, 'Annuel')");
		// On repart d'inventaires propres, avec un supprimé (id_etat=3) qui doit être exclu.
		$this->pdo->exec("DELETE FROM inventaires");
		$this->pdo->exec(
			"INSERT INTO inventaires (id_inventaire, date_inventaire, id_etat, id_statut_inventaire, id_type_inventaire) VALUES
				(1, '2024-01-01', 1, 1, 1),
				(2, '2024-02-01', 3, 1, 1)"
		);

		$liste = InventairesLister($this->pdo, 99, 'date_inventaire', 'ASC', 0, 100);
		$this->assertCount(1, $liste);                          // l'inventaire supprimé (id 2) est exclu
		$this->assertEquals(1, $liste[0]['id_inventaire']);
		$this->assertSame('Ouvert', $liste[0]['libelle_statut']);
		$this->assertSame('Annuel', $liste[0]['libelle_type']);
	}

	public function testInventaireLireAvecJointures(): void
	{
		$this->pdo->exec("INSERT INTO etats (id_etat, libelle_etat) VALUES (1, 'Actif')");
		$this->pdo->exec("INSERT INTO inventaires_statuts (id_statut_inventaire, libelle_statut) VALUES (1, 'En attente')");
		$this->pdo->exec("INSERT INTO inventaires_types (id_type_inventaire, libelle_type) VALUES (1, 'Roulant')");
		$this->pdo->exec("INSERT INTO admin_utilisateurs (id_utilisateur, nom_utilisateur, prenom_utilisateur, id_etat) VALUES (5, 'Blanc', 'Luc', 1)");
		$this->pdo->exec("DELETE FROM inventaires");
		$this->pdo->exec("INSERT INTO inventaires (id_inventaire, date_inventaire, id_etat, id_statut_inventaire, id_type_inventaire, id_membre_auteur) VALUES (10, '2024-05-01', 1, 1, 1, 5)");

		$row = InventaireLire($this->pdo, 10);
		$this->assertSame('En attente', $row['libelle_statut']);
		$this->assertSame('Roulant', $row['libelle_type']);
		$this->assertSame('Actif', $row['libelle_etat']);
		$this->assertSame('Blanc', $row['nom_membre_auteur']);
		$this->assertNull(InventaireLire($this->pdo, 999));
	}

	public function testInventaireArticleDetail(): void
	{
		$this->pdo->exec("DELETE FROM inventaires");
		$this->pdo->exec("INSERT INTO inventaires (id_inventaire, date_inventaire, id_etat) VALUES (10, '2024-05-01', 1)");
		$this->pdo->exec("INSERT INTO inventaires_articles (id_inventaire, id_article, quantite_precedent, quantite_totale, commentaire) VALUES (10, 3, 5, 8, 'ok')");

		$rows = InventaireArticleDetail($this->pdo, 10, 3);
		$this->assertCount(1, $rows);
		$this->assertEquals(5, $rows[0]['quantite_precedent']);
		$this->assertEquals(8, $rows[0]['quantite_totale']);
		$this->assertSame(array(), InventaireArticleDetail($this->pdo, 10, 999)); // article absent
	}

	public function testInventaireAjouterCopierModifier(): void
	{
		$id = InventaireAjouter($this->pdo, 'cmt', '2024-06-01', 1, 2, 7);
		$this->assertNotFalse($id);
		$row = $this->pdo->query("SELECT * FROM inventaires WHERE id_inventaire=" . (int) $id)->fetch();
		$this->assertEquals(1, $row['id_etat']);               // id_etat forcé à 1
		$this->assertSame('2024-06-01', $row['date_inventaire']);

		$idc = InventaireCopier($this->pdo, 'cmt2', '2024-07-01', 1, 2, 7);
		$rowc = $this->pdo->query("SELECT * FROM inventaires WHERE id_inventaire=" . (int) $idc)->fetch();
		$this->assertNull($rowc['id_etat']);                   // colonne omise → NULL

		$this->assertTrue((bool) InventaireModifier($this->pdo, $id, 'cmt3', '2024-08-01', 3, 4, 8));
		$row = $this->pdo->query("SELECT * FROM inventaires WHERE id_inventaire=" . (int) $id)->fetch();
		$this->assertSame('cmt3', $row['commentaire']);
		$this->assertEquals(3, $row['id_statut_inventaire']);
		$this->assertEquals(1, $row['id_etat']);               // id_etat inchangé
	}

	public function testInventaireArticlesAjoutSuppressionEtMajEtat(): void
	{
		$this->pdo->exec("DELETE FROM inventaires");
		$this->pdo->exec("INSERT INTO inventaires (id_inventaire, date_inventaire, id_etat) VALUES (1, '2020-01-01', 1), (2, '2030-01-01', 1)");

		$this->assertTrue((bool) InventaireArticleAjouter($this->pdo, 1, 3, 2, 9, 'c', 7));
		$this->assertCount(1, InventaireArticleDetail($this->pdo, 1, 3));

		$this->assertTrue((bool) InventaireArticlesSupprimer($this->pdo, 1));
		$this->assertSame(array(), InventaireArticleDetail($this->pdo, 1, 3));

		// Archive les inventaires antérieurs à 2025 : seul id 1 (2020) passe à l'état 2.
		$this->assertTrue((bool) InventaireMajEtatAnciens($this->pdo, '2025-01-01'));
		$this->assertEquals(2, $this->pdo->query("SELECT id_etat FROM inventaires WHERE id_inventaire=1")->fetchColumn());
		$this->assertEquals(1, $this->pdo->query("SELECT id_etat FROM inventaires WHERE id_inventaire=2")->fetchColumn());
	}

	public function testStatutsEtTypesInventairesLister(): void
	{
		$this->pdo->exec("INSERT INTO inventaires_statuts (id_statut_inventaire, libelle_statut) VALUES (1, 'Validé'), (2, 'En attente')");
		$this->pdo->exec("INSERT INTO inventaires_types (id_type_inventaire, libelle_type) VALUES (1, 'Roulant'), (2, 'Annuel')");

		$statuts = array_map(static function ($r) { return $r['libelle_statut']; }, StatutsInventairesLister($this->pdo));
		$this->assertSame(['En attente', 'Validé'], $statuts);
		$types = array_map(static function ($r) { return $r['libelle_type']; }, TypesInventairesLister($this->pdo));
		$this->assertSame(['Annuel', 'Roulant'], $types);
	}
}
