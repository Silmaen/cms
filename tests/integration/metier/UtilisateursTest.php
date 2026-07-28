<?php

/**
 * Caractérisation de UtilisateursLister() et UtilisateursGroupesLister()
 * (metier/utilisateurs.php).
 */
final class UtilisateursTest extends DatabaseTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->pdo->exec(
			"INSERT INTO admin_utilisateurs_groupes (id_utilisateur_groupe, libelle_utilisateur_groupe) VALUES
				(1, 'Administrateurs'),
				(2, 'Standard')"
		);
		// id_etat 3 = supprimé.
		$this->pdo->exec(
			"INSERT INTO admin_utilisateurs (id_utilisateur, nom_utilisateur, prenom_utilisateur, email_utilisateur, id_utilisateur_groupe, id_etat) VALUES
				(1, 'Zola',  'Emile',  'z@x', 1, 1),
				(2, 'Camus', 'Albert', 'c@x', 2, 1),
				(3, 'Hugo',  'Victor', 'h@x', 1, 3)"
		);
	}

	public function testListeFiltreEtatEtJointGroupe(): void
	{
		// etatMax=2 exclut l'utilisateur supprimé (id 3, id_etat=3) ; tri nom ASC.
		$liste = UtilisateursLister($this->pdo, 2, 'nom_utilisateur', 'ASC', 0, 100);
		$noms = array_map(static function ($r) { return $r['nom_utilisateur']; }, $liste);
		$this->assertSame(['Camus', 'Zola'], $noms);
		// La jointure ramène le libellé du groupe : Zola (id 1) → Administrateurs.
		$this->assertSame('Administrateurs', $liste[1]['libelle_utilisateur_groupe']);
	}

	public function testGroupesListeTriee(): void
	{
		$liste = UtilisateursGroupesLister($this->pdo, 'libelle_utilisateur_groupe', 'ASC', 0, 100);
		$libelles = array_map(static function ($r) { return $r['libelle_utilisateur_groupe']; }, $liste);
		$this->assertSame(['Administrateurs', 'Standard'], $libelles);
	}

	public function testGroupeLire(): void
	{
		$row = UtilisateursGroupeLire($this->pdo, 1);
		$this->assertSame('Administrateurs', $row['libelle_utilisateur_groupe']);
		$this->assertNull(UtilisateursGroupeLire($this->pdo, 999));
	}

	public function testGroupeMenusDroits(): void
	{
		$this->pdo->exec("INSERT INTO admin_menu (id_admin_menu, titre_fr) VALUES (10, 'Articles'), (11, 'Clients')");
		// Le groupe 1 a un droit sur le menu 10 seulement.
		$this->pdo->exec("INSERT INTO admin_menus_groupes (id_admin_menu, id_utilisateur_groupe, droit) VALUES (10, 1, 1)");

		$liste = UtilisateursGroupeMenusDroits($this->pdo, 1);
		// Jointure LEFT sur admin_menus_groupes filtrée par groupe → seul le menu 10 remonte.
		$this->assertCount(1, $liste);
		$this->assertEquals(10, $liste[0]['id_admin_menu']);
		$this->assertSame('Articles', $liste[0]['titre_menu']);
		$this->assertEquals(1, $liste[0]['droit']);
	}

	public function testGroupeAjouterEtModifier(): void
	{
		$id = UtilisateursGroupeAjouter($this->pdo, 'Bénévoles', 7);
		$this->assertNotFalse($id);
		$row = $this->pdo->query("SELECT * FROM admin_utilisateurs_groupes WHERE id_utilisateur_groupe=" . (int) $id)->fetch();
		$this->assertSame('Bénévoles', $row['libelle_utilisateur_groupe']);
		$this->assertEquals(7, $row['id_membre_auteur']);

		$this->assertTrue((bool) UtilisateursGroupeModifier($this->pdo, $id, 'Bénévoles actifs', 8));
		$row = $this->pdo->query("SELECT * FROM admin_utilisateurs_groupes WHERE id_utilisateur_groupe=" . (int) $id)->fetch();
		$this->assertSame('Bénévoles actifs', $row['libelle_utilisateur_groupe']);
		$this->assertEquals(8, $row['id_membre_auteur']);
	}

	public function testMenuGroupeDroitAjouterEtModifier(): void
	{
		$this->pdo->exec("INSERT INTO admin_menu (id_admin_menu, titre_fr) VALUES (20, 'Réservations')");

		$this->assertTrue((bool) MenuGroupeDroitAjouter($this->pdo, 20, 2, 1, 7));
		$row = $this->pdo->query("SELECT * FROM admin_menus_groupes WHERE id_admin_menu=20 AND id_utilisateur_groupe=2")->fetch();
		$this->assertEquals(1, $row['droit']);

		$this->assertTrue((bool) MenuGroupeDroitModifier($this->pdo, 20, 2, 0, 8));
		$row = $this->pdo->query("SELECT * FROM admin_menus_groupes WHERE id_admin_menu=20 AND id_utilisateur_groupe=2")->fetch();
		$this->assertEquals(0, $row['droit']);       // droit mis à jour
		$this->assertEquals(8, $row['id_membre_auteur']);
	}

	public function testUtilisateurLireAvecGroupeEtatEtAuteur(): void
	{
		$this->pdo->exec("INSERT INTO etats (id_etat, libelle_etat) VALUES (1, 'Actif')");
		// id 2 = Camus (groupe 2 Standard, etat 1) ; auteur = id 1 (Zola).
		$this->pdo->exec("UPDATE admin_utilisateurs SET id_etat=1, id_membre_auteur=1 WHERE id_utilisateur=2");

		$row = UtilisateurLire($this->pdo, 2);
		$this->assertSame('Camus', $row['nom_utilisateur']);
		$this->assertSame('Standard', $row['libelle_utilisateur_groupe']); // jointure groupe
		$this->assertSame('Actif', $row['libelle_etat']);                  // jointure etats
		$this->assertSame('Zola', $row['nom_membre_auteur']);              // jointure auteur (self-join)
		$this->assertNull(UtilisateurLire($this->pdo, 999));
	}

	public function testUtilisateursGroupesTousTrieParLibelle(): void
	{
		$liste = UtilisateursGroupesTous($this->pdo);
		$libelles = array_map(static function ($r) { return $r['libelle_utilisateur_groupe']; }, $liste);
		$this->assertSame(['Administrateurs', 'Standard'], $libelles);
	}

	public function testUtilisateurAjouter(): void
	{
		$id = UtilisateurAjouter($this->pdo, 'Sartre', 'Jean', '0102', 's@x', 'HACHE', 2, 'CLE123', 1);
		$this->assertNotFalse($id);
		$row = $this->pdo->query("SELECT * FROM admin_utilisateurs WHERE id_utilisateur=" . (int) $id)->fetch();
		$this->assertSame('Sartre', $row['nom_utilisateur']);
		$this->assertSame('HACHE', $row['mdp_utilisateur']);      // mot de passe stocké tel que fourni (déjà haché)
		$this->assertSame('CLE123', $row['cle_utilisateur']);
		$this->assertEquals(1, $row['id_etat']);                  // id_etat forcé à 1
		$this->assertSame(date('Y-m-d'), $row['date_creation']);
	}

	public function testUtilisateurModifierAvecEtSansMdp(): void
	{
		$mdpAvant = $this->pdo->query("SELECT mdp_utilisateur FROM admin_utilisateurs WHERE id_utilisateur=2")->fetchColumn();

		// Sans mot de passe : le hash ne doit pas changer.
		$this->assertTrue((bool) UtilisateurModifierSansMdp($this->pdo, 2, 'Camus2', 'Albert', '09', 'c2@x', 1, 1));
		$row = $this->pdo->query("SELECT * FROM admin_utilisateurs WHERE id_utilisateur=2")->fetch();
		$this->assertSame('Camus2', $row['nom_utilisateur']);
		$this->assertEquals($mdpAvant, $row['mdp_utilisateur']);  // mot de passe inchangé

		// Avec mot de passe : le hash est remplacé.
		$this->assertTrue((bool) UtilisateurModifier($this->pdo, 2, 'Camus3', 'Albert', '09', 'c3@x', 'NOUVEAU_HACHE', 1, 1));
		$row = $this->pdo->query("SELECT * FROM admin_utilisateurs WHERE id_utilisateur=2")->fetch();
		$this->assertSame('Camus3', $row['nom_utilisateur']);
		$this->assertSame('NOUVEAU_HACHE', $row['mdp_utilisateur']);
	}
}
