<?php

use PHPUnit\Framework\TestCase;

/**
 * Classe de base pour les tests qui touchent la base de données.
 *
 * Avant chaque test : (re)connexion à la base de TEST, rechargement d'un schéma
 * propre (isolation totale entre tests), et remise à zéro des superglobales
 * $_SESSION / $_POST / $_GET (les fonctions testées s'appuient dessus).
 *
 * La connexion est paramétrable par variables d'environnement (défauts = Docker
 * local, où les tests tournent DANS le conteneur web et joignent le service `db`) :
 *   CDF_TEST_DB_HOST (db) · CDF_TEST_DB_PORT (3306) · CDF_TEST_DB_USER (root)
 *   CDF_TEST_DB_PASS (root) · CDF_TEST_DB_NAME (comitefetes_test)
 * En CI, on pointe vers le service MySQL (127.0.0.1).
 */
abstract class DatabaseTestCase extends TestCase
{
	protected PDO $pdo;

	protected function setUp(): void
	{
		$host = getenv('CDF_TEST_DB_HOST') ?: 'db';
		$port = getenv('CDF_TEST_DB_PORT') ?: '3306';
		$user = getenv('CDF_TEST_DB_USER') ?: 'root';
		$pass = getenv('CDF_TEST_DB_PASS');
		$pass = ($pass === false) ? 'root' : $pass;
		$name = getenv('CDF_TEST_DB_NAME') ?: 'comitefetes_test';

		try {
			$pdo = new PDO(
				"mysql:host={$host};port={$port}",
				$user,
				$pass,
				[
					PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				]
			);
			$pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4");
			$pdo->exec("USE `{$name}`");
		} catch (\PDOException $e) {
			$this->markTestSkipped(
				"Base de test indisponible ({$host}:{$port}). "
				. "Lance `docker compose up -d` (ou fournis les variables CDF_TEST_DB_*). "
				. "Détail : " . $e->getMessage()
			);
		}

		$this->chargerSql($pdo, __DIR__ . '/../fixtures/schema.sql');

		$_SESSION = [];
		$_POST = [];
		$_GET = [];

		$this->pdo = $pdo;
	}

	/** Exécute un fichier SQL instruction par instruction (PDO::exec ne fait qu'une à la fois). */
	private function chargerSql(PDO $pdo, string $fichier): void
	{
		$sql = file_get_contents($fichier);
		// On retire d'abord les lignes de commentaire « -- … » (sinon elles se
		// colleraient à l'instruction suivante lors du découpage sur « ; »).
		$lignes = array_filter(
			preg_split('/\r?\n/', $sql),
			static fn($l) => strpos(trim($l), '--') !== 0
		);
		$sql = implode("\n", $lignes);

		foreach (array_filter(array_map('trim', explode(';', $sql))) as $instruction) {
			if ($instruction !== '') {
				$pdo->exec($instruction);
			}
		}
	}

	/** Petit utilitaire : compte les lignes d'une table. */
	protected function compter(string $table, string $where = '1'): int
	{
		return (int) $this->pdo->query("SELECT COUNT(*) FROM {$table} WHERE {$where}")->fetchColumn();
	}
}
