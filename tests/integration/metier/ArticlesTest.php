<?php

/**
 * Caractérisation de ArticlesLister() (metier/articles.php).
 */
final class ArticlesTest extends DatabaseTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->pdo->exec(
			"INSERT INTO articles (id_article, designation, id_etat, ordre_article) VALUES
				(1, 'Chaise', 1, 1),
				(2, 'Table',  1, 2),
				(3, 'Banc',   2, 3),
				(4, 'Tente',  3, 4)"
		);
	}

	public function testFiltreParEtatMaxEtTriParDesignation(): void
	{
		// etatMax=2 → exclut l'article id_etat=3 (Tente). Tri designation ASC.
		$liste = ArticlesLister($this->pdo, 2, 'designation', 'ASC', 0, 10);
		$designations = array_map(static function ($r) { return $r['designation']; }, $liste);
		$this->assertSame(['Banc', 'Chaise', 'Table'], $designations);
	}

	public function testLimitEtOffset(): void
	{
		$premiers = ArticlesLister($this->pdo, 3, 'designation', 'ASC', 0, 2);
		$this->assertCount(2, $premiers);

		$suivants = ArticlesLister($this->pdo, 3, 'designation', 'ASC', 2, 2);
		$this->assertCount(2, $suivants);

		// Pas de recouvrement entre les deux pages.
		$this->assertNotEquals($premiers[0]['id_article'], $suivants[0]['id_article']);
	}

	public function testQuantiteTotale(): void
	{
		$this->pdo->exec("INSERT INTO inventaires (id_inventaire, date_inventaire, id_etat) VALUES (5, '2024-01-01', 1)");
		$this->pdo->exec("INSERT INTO inventaires_articles (id_inventaire, id_article, quantite_totale) VALUES (5, 1, 42)");

		$this->assertEquals(42, ArticleQuantiteTotale($this->pdo, 5, 1));
		$this->assertNull(ArticleQuantiteTotale($this->pdo, 5, 999)); // article absent de l'inventaire
	}

	public function testQuantiteReservee(): void
	{
		// Réservation couvrant le 2024-06-15 (date_depart < date < date_retour).
		$this->pdo->exec("INSERT INTO reservations (id_reservation, date_depart, date_retour) VALUES (1, '2024-06-01', '2024-06-30')");
		$this->pdo->exec("INSERT INTO reservations_articles (id_reservation, id_article, quantite_reservee) VALUES (1, 1, 3), (1, 1, 2)");

		$this->assertEquals(5, ArticleQuantiteReservee($this->pdo, 1, '2024-06-15')); // 3 + 2
		$this->assertNull(ArticleQuantiteReservee($this->pdo, 1, '2025-01-01'));      // hors période → aucune
	}

	public function testLireArticleAvecEtatEtAuteur(): void
	{
		$this->pdo->exec("INSERT INTO etats (id_etat, libelle_etat) VALUES (1, 'Actif')");
		$this->pdo->exec("INSERT INTO admin_utilisateurs (id_utilisateur, nom_utilisateur, prenom_utilisateur, id_etat) VALUES (7, 'Martin', 'Jean', 1)");
		$this->pdo->exec("UPDATE articles SET id_membre_auteur=7 WHERE id_article=1");

		$row = ArticleLire($this->pdo, 1);
		$this->assertSame('Chaise', $row['designation']);
		$this->assertSame('Actif', $row['libelle_etat']);            // jointure etats
		$this->assertSame('Martin', $row['nom_membre_auteur']);      // jointure admin_utilisateurs
		$this->assertSame('Jean', $row['prenom_membre_auteur']);
	}

	public function testLireArticleInexistantRenvoieNull(): void
	{
		$this->assertNull(ArticleLire($this->pdo, 999));
	}

	public function testAjouterInsereAvecEtatActif(): void
	{
		$id = ArticleAjouter($this->pdo, 'Nappe', 'Blanche', 9, 7);
		$this->assertNotFalse($id);

		$row = $this->pdo->query("SELECT * FROM articles WHERE id_article=" . (int) $id)->fetch();
		$this->assertSame('Nappe', $row['designation']);
		$this->assertSame('Blanche', $row['commentaire']);
		$this->assertEquals(1, $row['id_etat']);          // id_etat forcé à 1
		$this->assertEquals(9, $row['ordre_article']);
		$this->assertEquals(7, $row['id_membre_auteur']);
		$this->assertSame(date('Y-m-d'), $row['date_creation']);
	}

	public function testCopierNInscritPasIdEtat(): void
	{
		// La colonne id_etat est omise à l'INSERT → valeur par défaut de la table (0).
		$id = ArticleCopier($this->pdo, 'Copie', 'Cmt', 3, 7);
		$this->assertNotFalse($id);

		$row = $this->pdo->query("SELECT * FROM articles WHERE id_article=" . (int) $id)->fetch();
		$this->assertSame('Copie', $row['designation']);
		$this->assertEquals(0, $row['id_etat']);          // défaut de la colonne
	}

	public function testModifierMetAJourSansToucherEtatNiCreation(): void
	{
		$avant = $this->pdo->query("SELECT id_etat, date_creation FROM articles WHERE id_article=1")->fetch();

		$ok = ArticleModifier($this->pdo, 1, 'Chaise pliante', 'Neuve', 12, 7);
		$this->assertTrue((bool) $ok);

		$row = $this->pdo->query("SELECT * FROM articles WHERE id_article=1")->fetch();
		$this->assertSame('Chaise pliante', $row['designation']);
		$this->assertSame('Neuve', $row['commentaire']);
		$this->assertEquals(12, $row['ordre_article']);
		$this->assertEquals(7, $row['id_membre_auteur']);
		$this->assertEquals($avant['id_etat'], $row['id_etat']);              // id_etat inchangé
		$this->assertEquals($avant['date_creation'], $row['date_creation']);  // date_creation inchangée
	}
}
