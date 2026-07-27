<?php

/**
 * Caractérisation de GestionSuppression() : soft-delete via `id_etat`
 * (activer=1, archiver=2, supprimer=3), met à jour date_modification et
 * id_membre_auteur, et renseigne $_SESSION['message_formulaire'].
 */
final class GestionSuppressionTest extends DatabaseTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		// Un article actif (id_etat=1) à manipuler.
		$this->pdo->exec("INSERT INTO articles (id_article, designation, id_etat) VALUES (5, 'Barnum', 1)");
		$_SESSION['id_membre_auteur'] = 7;
	}

	private function etatArticle(int $id): int
	{
		return (int) $this->pdo->query("SELECT id_etat FROM articles WHERE id_article={$id}")->fetchColumn();
	}

	public function testArchiverPasseIdEtatA2(): void
	{
		GestionSuppression($this->pdo, 'id_article', 'articles', 5, 'archiver');

		$this->assertSame(2, $this->etatArticle(5));
		$this->assertSame('Archivage enregistré', $_SESSION['message_formulaire']);

		$row = $this->pdo->query("SELECT date_modification, id_membre_auteur FROM articles WHERE id_article=5")->fetch();
		$this->assertSame(date('Y-m-d'), $row['date_modification']);
		$this->assertEquals(7, $row['id_membre_auteur']);
	}

	public function testActiverPasseIdEtatA1(): void
	{
		GestionSuppression($this->pdo, 'id_article', 'articles', 5, 'activer');
		$this->assertSame(1, $this->etatArticle(5));
		$this->assertSame('Activation enregistrée', $_SESSION['message_formulaire']);
	}

	public function testSupprimerPasseIdEtatA3(): void
	{
		GestionSuppression($this->pdo, 'id_article', 'articles', 5, 'supprimer');
		$this->assertSame(3, $this->etatArticle(5));
		$this->assertSame('Suppression enregistrée', $_SESSION['message_formulaire']);
	}

	public function testSansItemNeToucheRienEtAnnule(): void
	{
		// $id_item null → isset() faux → aucune écriture, message « annulée ».
		GestionSuppression($this->pdo, 'id_article', 'articles', null, 'supprimer');
		$this->assertSame(1, $this->etatArticle(5)); // inchangé
		$this->assertSame('Suppression annulée', $_SESSION['message_formulaire']);
	}
}
