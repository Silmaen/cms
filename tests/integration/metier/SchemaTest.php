<?php

/**
 * Mise à jour du schéma à chaud (metier/schema.php).
 *
 * La fixture représente une base antérieure aux migrations (version 0). PDO est mis en
 * mode d'erreur silencieux, comme en production : le mécanisme s'appuie sur l'échec
 * (false) d'une lecture de `admin_schema` absente.
 */
final class SchemaTest extends DatabaseTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	}

	private function tableExiste(string $table): bool
	{
		return $this->pdo->query("SHOW TABLES LIKE '{$table}'")->rowCount() === 1;
	}

	public function testBaseAncienneEstEnVersionZero(): void
	{
		$this->assertFalse($this->tableExiste('admin_schema'));
		$this->assertSame(0, SchemaVersionBase($this->pdo));
	}

	public function testMiseAJourAppliqueToutesLesMigrations(): void
	{
		$this->assertTrue(SchemaMettreAJour($this->pdo));
		$this->assertSame(SchemaVersionCode(), SchemaVersionBase($this->pdo));
		$this->assertTrue($this->tableExiste('admin_utilisateurs_preferences'));
	}

	public function testMiseAJourEstIdempotente(): void
	{
		$this->assertTrue(SchemaMettreAJour($this->pdo));
		$this->assertTrue(SchemaMettreAJour($this->pdo));
		$this->assertSame(1, $this->compter('admin_schema'));
		$this->assertSame(SchemaVersionCode(), SchemaVersionBase($this->pdo));
	}

	public function testMigrationsRejouablesSurBaseDejaMigree(): void
	{
		// Sauvegarde dans un état intermédiaire : tables présentes, version perdue.
		SchemaMettreAJour($this->pdo);
		$this->pdo->exec("INSERT INTO admin_utilisateurs_preferences (id_utilisateur, cle, valeur) VALUES (1, 'theme', 'sombre')");
		$this->pdo->exec("DROP TABLE admin_schema");

		$this->assertTrue(SchemaMettreAJour($this->pdo));
		$this->assertSame(SchemaVersionCode(), SchemaVersionBase($this->pdo));
		// Les données existantes sont conservées.
		$this->assertSame(1, $this->compter('admin_utilisateurs_preferences'));
	}
}
