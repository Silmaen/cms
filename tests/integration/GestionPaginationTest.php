<?php

/**
 * Caractérisation de GestionPagination() : calcule le nombre de pages
 * = ceil(nombre de lignes / items_par_page) dans $_SESSION['nombre_de_pages'].
 */
final class GestionPaginationTest extends DatabaseTestCase
{
	public function testCalculeLeNombreDePages(): void
	{
		for ($i = 1; $i <= 5; $i++) {
			$this->pdo->exec("INSERT INTO clients (id_client, nom) VALUES ({$i}, 'Client{$i}')");
		}
		$_SESSION['items_par_page'] = 2;

		GestionPagination($this->pdo, 'id_client', 'clients');

		// ceil(5 / 2) = 3
		$this->assertEquals(3, $_SESSION['nombre_de_pages']);
	}

	public function testTableVideDonneZeroPage(): void
	{
		$_SESSION['items_par_page'] = 10;
		GestionPagination($this->pdo, 'id_client', 'clients');
		$this->assertEquals(0, $_SESSION['nombre_de_pages']);
	}
}
