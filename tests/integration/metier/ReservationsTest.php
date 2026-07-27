<?php

/**
 * Caractérisation des fonctions de metier/reservations.php.
 */
final class ReservationsTest extends DatabaseTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->pdo->exec("INSERT INTO etats (id_etat, libelle_etat) VALUES (1, 'Actif')");
		$this->pdo->exec("INSERT INTO reservations_statuts (id_statut_reservation, libelle_statut) VALUES (2, 'Validé'), (1, 'En attente')");
		$this->pdo->exec("INSERT INTO clients_statuts (id_statut_client, libelle_statut) VALUES (1, 'Particulier')");
		$this->pdo->exec("INSERT INTO admin_utilisateurs (id_utilisateur, nom_utilisateur, prenom_utilisateur, id_etat) VALUES (5, 'Roux', 'Ann', 1)");
		$this->pdo->exec("INSERT INTO clients (id_client, nom, prenom, id_statut_client, id_etat) VALUES (3, 'Petit', 'Léa', 1, 1)");
	}

	public function testReservationLireAvecJointures(): void
	{
		$this->pdo->exec("INSERT INTO reservations (id_reservation, id_client, date_depart, date_retour, id_etat, id_statut_reservation, id_membre_auteur) VALUES (100, 3, '2024-05-01', '2024-05-05', 1, 2, 5)");

		$row = ReservationLire($this->pdo, 100);
		$this->assertSame('Petit', $row['nom_client']);
		$this->assertSame('Actif', $row['libelle_etat']);
		$this->assertSame('Validé', $row['libelle_statut']);          // statut réservation
		$this->assertSame('Particulier', $row['libelle_statut_client']); // statut client (t6)
		$this->assertSame('Roux', $row['nom_membre_auteur']);
		$this->assertNull(ReservationLire($this->pdo, 999));
	}

	public function testReservationAjouterEtModifier(): void
	{
		$id = ReservationAjouter($this->pdo, 3, 'cmt', 10, '2024-05-05', '2024-05-01', '2024-05-05', 2024, 1, 2, 5);
		$this->assertNotFalse($id);
		$row = $this->pdo->query("SELECT * FROM reservations WHERE id_reservation=" . (int) $id)->fetch();
		$this->assertEquals(3, $row['id_client']);
		$this->assertEquals(10, $row['don']);
		$this->assertEquals(2024, $row['annee_reservation']);
		$this->assertSame(date('Y-m-d'), $row['date_creation']);

		$this->assertTrue((bool) ReservationModifier($this->pdo, $id, 3, 'cmt2', 20, '2024-06-05', '2024-06-01', '2024-06-05', 2025, 2, 1, 8));
		$row = $this->pdo->query("SELECT * FROM reservations WHERE id_reservation=" . (int) $id)->fetch();
		$this->assertSame('cmt2', $row['commentaire']);
		$this->assertEquals(20, $row['don']);
		$this->assertEquals(2025, $row['annee_reservation']);
		$this->assertEquals(2, $row['id_etat']);
	}

	public function testReservationArticlesAjoutEtSuppression(): void
	{
		$this->assertTrue((bool) ReservationArticleAjouter($this->pdo, 100, 3, 4, 5));
		$this->assertTrue((bool) ReservationArticleAjouter($this->pdo, 100, 7, 2, 5));
		$n = $this->pdo->query("SELECT COUNT(*) FROM reservations_articles WHERE id_reservation=100")->fetchColumn();
		$this->assertEquals(2, $n);

		$this->assertTrue((bool) ReservationArticlesSupprimer($this->pdo, 100));
		$n = $this->pdo->query("SELECT COUNT(*) FROM reservations_articles WHERE id_reservation=100")->fetchColumn();
		$this->assertEquals(0, $n);
	}

	public function testStatutsReservationsListerTrieParLibelle(): void
	{
		$libelles = array_map(static function ($r) { return $r['libelle_statut']; }, StatutsReservationsLister($this->pdo));
		$this->assertSame(['En attente', 'Validé'], $libelles);
	}
}
