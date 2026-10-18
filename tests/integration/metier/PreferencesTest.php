<?php

/**
 * Préférences utilisateur (metier/preferences.php).
 */
final class PreferencesTest extends DatabaseTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		SchemaMettreAJour($this->pdo);
		$this->pdo->exec(
			"INSERT INTO admin_utilisateurs (id_utilisateur, nom_utilisateur, items_par_page) VALUES
				(1, 'Zola', 10000),
				(2, 'Camus', 10000)"
		);
	}

	public function testValeursParDefautSansPreferenceEnregistree(): void
	{
		$this->assertSame(['theme' => 'clair'], PreferencesLire($this->pdo, 1));
	}

	public function testEnregistrerPuisRelire(): void
	{
		$this->assertTrue(PreferenceEnregistrer($this->pdo, 1, 'theme', 'sombre'));
		$this->assertTrue(PreferenceEnregistrer($this->pdo, 1, 'theme', 'auto'));
		$this->assertSame('auto', PreferencesLire($this->pdo, 1)['theme']);
		// Une ligne par utilisateur et par clé ; l'autre utilisateur n'est pas touché.
		$this->assertSame(1, $this->compter('admin_utilisateurs_preferences'));
		$this->assertSame('clair', PreferencesLire($this->pdo, 2)['theme']);
	}

	public function testCleOuValeurInconnueRefusee(): void
	{
		$this->assertFalse(PreferenceEnregistrer($this->pdo, 1, 'theme', 'fluo'));
		$this->assertFalse(PreferenceEnregistrer($this->pdo, 1, 'inconnue', 'clair'));
		$this->assertSame(0, $this->compter('admin_utilisateurs_preferences'));
	}

	public function testValeurObsoleteEnBaseIgnoree(): void
	{
		$this->pdo->exec("INSERT INTO admin_utilisateurs_preferences (id_utilisateur, cle, valeur) VALUES (1, 'theme', 'fluo'), (1, 'retiree', 'x')");
		$this->assertSame(['theme' => 'clair'], PreferencesLire($this->pdo, 1));
	}

	public function testTableAbsenteDonneLesValeursParDefaut(): void
	{
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		$this->pdo->exec("DROP TABLE admin_utilisateurs_preferences");
		$this->assertSame(['theme' => 'clair'], PreferencesLire($this->pdo, 1));
	}

	public function testItemsParPageAppliqueATousLesEcransDeLUtilisateur(): void
	{
		$this->pdo->exec(
			"INSERT INTO admin_utilisateurs_session (id_utilisateur, id_admin_menu, colonne, items_par_page) VALUES
				(1, 1, 'nom', 100),
				(1, 2, 'nom', 300),
				(2, 1, 'nom', 100)"
		);

		$this->assertTrue(PreferencesItemsParPageEnregistrer($this->pdo, 1, '200'));
		$this->assertSame(200, PreferencesItemsParPageLire($this->pdo, 1));
		$this->assertSame(2, $this->compter('admin_utilisateurs_session', 'id_utilisateur=1 AND items_par_page=200'));
		// L'autre utilisateur garde ses réglages.
		$this->assertSame(10000, PreferencesItemsParPageLire($this->pdo, 2));
		$this->assertSame(1, $this->compter('admin_utilisateurs_session', 'id_utilisateur=2 AND items_par_page=100'));
	}

	public function testItemsParPageNonProposeRefuse(): void
	{
		$this->assertFalse(PreferencesItemsParPageEnregistrer($this->pdo, 1, 0));
		$this->assertFalse(PreferencesItemsParPageEnregistrer($this->pdo, 1, 42));
		$this->assertSame(10000, PreferencesItemsParPageLire($this->pdo, 1));
	}
}
