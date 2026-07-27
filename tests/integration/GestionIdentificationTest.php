<?php

/**
 * Caractérisation de GestionIdentification() : vérifie l'e-mail + mot de passe
 * haché contre admin_utilisateurs, remplit $_SESSION et renvoie true/false.
 */
final class GestionIdentificationTest extends DatabaseTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		// Un utilisateur dont le mot de passe est stocké haché (comme en prod).
		$mdp = GestionHashage('secret');
		$this->pdo->exec(
			"INSERT INTO admin_utilisateurs
                (id_utilisateur, nom_utilisateur, prenom_utilisateur, email_utilisateur, mdp_utilisateur, id_utilisateur_groupe, items_par_page)
             VALUES (3, 'Dupont', 'Jean', 'jean@exemple.fr', '{$mdp}', 1, 50)"
		);
	}

	public function testIdentifiantsValidesRenvoieTrueEtRemplitSession(): void
	{
		$_POST = ['email' => 'jean@exemple.fr', 'mdp' => 'secret'];

		$this->assertTrue(GestionIdentification($this->pdo));
		$this->assertEquals(3, $_SESSION['id_utilisateur']);
		$this->assertEquals(3, $_SESSION['id_membre_auteur']);
		$this->assertSame('Dupont', $_SESSION['nom_utilisateur']);
		$this->assertSame('Jean', $_SESSION['prenom_utilisateur']);
		$this->assertEquals(1, $_SESSION['id_utilisateur_groupe']);
		$this->assertEquals(50, $_SESSION['items_par_page']);
	}

	public function testMauvaisMotDePasseRenvoieFalse(): void
	{
		$_POST = ['email' => 'jean@exemple.fr', 'mdp' => 'mauvais'];

		$this->assertFalse(GestionIdentification($this->pdo));
		$this->assertSame('', $_SESSION['id_utilisateur_groupe']);
		$this->assertNotEmpty($_SESSION['message_identification']);
	}

	public function testDejaConnecteRenvoieTrueSansRequete(): void
	{
		// Si la session porte déjà un id_utilisateur, la fonction court-circuite.
		$_SESSION['id_utilisateur'] = 99;
		$this->assertTrue(GestionIdentification($this->pdo));
		$this->assertSame(99, $_SESSION['id_utilisateur']); // inchangé
	}
}
