<?php

/**
 * Réinitialisation du mot de passe (metier/utilisateurs.php) : demande, vérification du
 * lien, réinitialisation. La base est d'abord mise à niveau (colonnes du jeton).
 */
final class MdpPerduTest extends DatabaseTestCase
{
	private const T0 = 1800000000;

	protected function setUp(): void
	{
		parent::setUp();
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		$this->assertTrue(SchemaMettreAJour($this->pdo));
		// id_etat 2 = archivé.
		$this->pdo->exec(
			"INSERT INTO admin_utilisateurs (id_utilisateur, nom_utilisateur, prenom_utilisateur, email_utilisateur, mdp_utilisateur, id_utilisateur_groupe, id_etat) VALUES
				(1, 'Zola', 'Emile',  'emile@exemple.fr',  'ancien', 1, 1),
				(2, 'Hugo', 'Victor', 'victor@exemple.fr', 'ancien', 1, 2)"
		);
	}

	private function ligne(int $id): array
	{
		return $this->pdo->query("SELECT * FROM admin_utilisateurs WHERE id_utilisateur=" . $id)->fetch();
	}

	public function testAdresseInconnueOuInvalideOuCompteInactif(): void
	{
		$this->assertNull(MdpPerduDemander($this->pdo, 'inconnu@exemple.fr', self::T0));
		$this->assertNull(MdpPerduDemander($this->pdo, 'pas-une-adresse', self::T0));
		$this->assertNull(MdpPerduDemander($this->pdo, "emile@exemple.fr\r\nBcc: x@y.z", self::T0));
		$this->assertNull(MdpPerduDemander($this->pdo, 'victor@exemple.fr', self::T0));
		$this->assertSame(0, $this->compter('admin_utilisateurs', 'jeton_mdp IS NOT NULL'));
	}

	public function testDemandeEmetUnJetonDontSeuleLEmpreinteEstStockee(): void
	{
		$demande = MdpPerduDemander($this->pdo, 'emile@exemple.fr', self::T0);
		$this->assertNotNull($demande);
		$this->assertSame('Emile', $demande['prenom_utilisateur']);
		$this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $demande['jeton']);

		$ligne = $this->ligne(1);
		$this->assertSame(hash('sha256', $demande['jeton']), $ligne['jeton_mdp']);
		$this->assertSame(date('Y-m-d H:i:s', self::T0), $ligne['date_jeton_mdp']);
	}

	public function testDelaiEntreDeuxDemandes(): void
	{
		$this->assertNotNull(MdpPerduDemander($this->pdo, 'emile@exemple.fr', self::T0));
		$this->assertNull(MdpPerduDemander($this->pdo, 'emile@exemple.fr', self::T0 + MdpDelaiEntreDemandes() - 1));
		// Un administrateur connecté peut renvoyer le lien sans attendre.
		$this->assertNotNull(MdpPerduDemander($this->pdo, 'emile@exemple.fr', self::T0 + 60, true));
		$this->assertNotNull(MdpPerduDemander($this->pdo, 'emile@exemple.fr', self::T0 + 60 + MdpDelaiEntreDemandes()));
	}

	public function testNouvelleDemandeInvalideLAncienLien(): void
	{
		$premiere = MdpPerduDemander($this->pdo, 'emile@exemple.fr', self::T0);
		$seconde = MdpPerduDemander($this->pdo, 'emile@exemple.fr', self::T0 + MdpDelaiEntreDemandes());
		$this->assertNotSame($premiere['jeton'], $seconde['jeton']);
		$this->assertNull(MdpJetonVerifier($this->pdo, 'emile@exemple.fr', $premiere['jeton'], self::T0 + MdpDelaiEntreDemandes()));
		$this->assertSame(1, MdpJetonVerifier($this->pdo, 'emile@exemple.fr', $seconde['jeton'], self::T0 + MdpDelaiEntreDemandes()));
	}

	public function testVerificationDuLien(): void
	{
		$jeton = MdpPerduDemander($this->pdo, 'emile@exemple.fr', self::T0)['jeton'];

		$this->assertSame(1, MdpJetonVerifier($this->pdo, 'emile@exemple.fr', $jeton, self::T0 + MdpJetonDureeValidite()));
		$this->assertNull(MdpJetonVerifier($this->pdo, 'emile@exemple.fr', $jeton, self::T0 + MdpJetonDureeValidite() + 1), 'lien expiré');
		$this->assertNull(MdpJetonVerifier($this->pdo, 'emile@exemple.fr', str_repeat('0', 64), self::T0), 'mauvais jeton');
		$this->assertNull(MdpJetonVerifier($this->pdo, 'emile@exemple.fr', '', self::T0), 'jeton vide');
		$this->assertNull(MdpJetonVerifier($this->pdo, 'victor@exemple.fr', $jeton, self::T0), 'autre compte');
		// L'empreinte stockée n'ouvre pas la réinitialisation.
		$this->assertNull(MdpJetonVerifier($this->pdo, 'emile@exemple.fr', $this->ligne(1)['jeton_mdp'], self::T0));
	}

	public function testCompteArchiveApresDemande(): void
	{
		$jeton = MdpPerduDemander($this->pdo, 'emile@exemple.fr', self::T0)['jeton'];
		$this->pdo->exec("UPDATE admin_utilisateurs SET id_etat=2 WHERE id_utilisateur=1");
		$this->assertNull(MdpJetonVerifier($this->pdo, 'emile@exemple.fr', $jeton, self::T0));
	}

	public function testReinitialisationConsommeLeJeton(): void
	{
		$jeton = MdpPerduDemander($this->pdo, 'emile@exemple.fr', self::T0)['jeton'];
		$this->assertTrue(MdpReinitialiser($this->pdo, 1, GestionHashage('Nouveau1')));

		$ligne = $this->ligne(1);
		$this->assertSame(GestionHashage('Nouveau1'), $ligne['mdp_utilisateur']);
		$this->assertNull($ligne['jeton_mdp']);
		$this->assertNull(MdpJetonVerifier($this->pdo, 'emile@exemple.fr', $jeton, self::T0), 'lien à usage unique');
		// La date reste : le délai entre demandes s'applique toujours.
		$this->assertNull(MdpPerduDemander($this->pdo, 'emile@exemple.fr', self::T0 + 60));
	}
}
