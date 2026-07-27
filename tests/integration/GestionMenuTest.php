<?php

/**
 * Caractérisation de GestionMenu() : lit la table admin_menu et renvoie ses
 * entrées triées par `ordre` croissant.
 */
final class GestionMenuTest extends DatabaseTestCase
{
	public function testRenvoieLesEntreesTrieesParOrdre(): void
	{
		$this->pdo->exec(
			"INSERT INTO admin_menu (id_admin_menu, titre_fr, url, niveau, id_parent, ordre) VALUES
                (1, 'Clients',      'clients_liste.php',      1, 0, 20),
                (2, 'Articles',     'articles_liste.php',     1, 0, 10),
                (3, 'Réservations', 'reservations_liste.php', 1, 0, 30)"
		);

		$menu = GestionMenu($this->pdo);

		$this->assertCount(3, $menu);
		// Tri par `ordre` ASC : Articles (10) < Clients (20) < Réservations (30).
		$this->assertSame('Articles', $menu[0]['titre_fr']);
		$this->assertSame('Clients', $menu[1]['titre_fr']);
		$this->assertSame('Réservations', $menu[2]['titre_fr']);
		$this->assertSame('articles_liste.php', $menu[0]['url']);
	}

	public function testTableVideRenvoieTableauVide(): void
	{
		$this->assertSame([], GestionMenu($this->pdo));
	}
}
