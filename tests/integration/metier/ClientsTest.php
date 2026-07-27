<?php

/**
 * Caractérisation de ClientsLister() (metier/clients.php).
 */
final class ClientsTest extends DatabaseTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		// statut : 1,2 = particuliers ; 3,4,5 = associations/sociétés. id_etat 3 = supprimé.
		$this->pdo->exec(
			"INSERT INTO clients (id_client, association, nom, prenom, id_statut_client, id_etat) VALUES
				(1, '',       'Durand', 'Paul', 1, 1),
				(2, 'Asso B', 'X',      '',     3, 1),
				(3, 'Asso A', 'Y',      '',     4, 1),
				(4, '',       'Albert', 'Zoe',  2, 2),
				(5, '',       'Cache',  '',     1, 3)"
		);
	}

	public function testTriSimpleFiltreLEtat(): void
	{
		// colonne != 'association' → tri direct ; etatMax=2 exclut le client supprimé (id 5).
		$liste = ClientsLister($this->pdo, 2, 'nom', 'ASC', 0, 100);
		$noms = array_map(static function ($r) { return $r['nom']; }, $liste);
		$this->assertSame(['Albert', 'Durand', 'X', 'Y'], $noms);
	}

	public function testTriAssociationListeAssosPuisParticuliers(): void
	{
		// colonne == 'association' → assos (statuts 3/4/5) d'abord, puis particuliers (1/2).
		$liste = ClientsLister($this->pdo, 2, 'association', 'ASC', 0, 100);
		$ids = array_map(static function ($r) { return (int) $r['id_client']; }, $liste);
		// assos par association ASC : Asso A (3), Asso B (2) ; puis particuliers par nom : Albert (4), Durand (1).
		$this->assertSame([3, 2, 4, 1], $ids);
	}

	public function testClientLireAvecJointures(): void
	{
		$this->pdo->exec("INSERT INTO etats (id_etat, libelle_etat) VALUES (1, 'Actif')");
		$this->pdo->exec("INSERT INTO clients_statuts (id_statut_client, libelle_statut) VALUES (1, 'Particulier')");
		$this->pdo->exec("INSERT INTO admin_utilisateurs (id_utilisateur, nom_utilisateur, prenom_utilisateur, id_etat) VALUES (5, 'Dupont', 'Marie', 1)");
		$this->pdo->exec("UPDATE clients SET id_membre_auteur=5, id_statut_client=1 WHERE id_client=1");

		$row = ClientLire($this->pdo, 1);
		$this->assertSame('Durand', $row['nom']);
		$this->assertSame('Actif', $row['libelle_etat']);          // jointure etats
		$this->assertSame('Particulier', $row['libelle_statut']);  // jointure statut
		$this->assertSame('Dupont', $row['nom_membre_auteur']);    // jointure auteur (sur colonne)
		$this->assertNull(ClientLire($this->pdo, 999));
	}

	public function testClientLirePourCopieJointAuteurSurParametre(): void
	{
		// Bizarrerie d'origine : l'auteur est joint sur l'id passé, pas sur la colonne.
		$this->pdo->exec("INSERT INTO admin_utilisateurs (id_utilisateur, nom_utilisateur, prenom_utilisateur, id_etat) VALUES (8, 'Sessio', 'Nuser', 1)");
		$row = ClientLirePourCopie($this->pdo, 1, 8);
		$this->assertSame('Durand', $row['nom']);
		$this->assertSame('Sessio', $row['nom_membre_auteur']);    // auteur = paramètre (id 8), pas la colonne
	}

	public function testClientAjouterCopierModifier(): void
	{
		$id = ClientAjouter($this->pdo, 'AssoX', 'Neuf', 'Client', 'a1', 'a2', 'a3', '69000', 'Lyon', '04', 'e@x', 'cmt', 'CLE', 2, 7);
		$this->assertNotFalse($id);
		$row = $this->pdo->query("SELECT * FROM clients WHERE id_client=" . (int) $id)->fetch();
		$this->assertSame('Neuf', $row['nom']);
		$this->assertSame('CLE', $row['cle_client']);
		$this->assertEquals(1, $row['id_etat']);           // id_etat forcé à 1
		$this->assertSame(date('Y-m-d'), $row['date_creation']);

		// Copier : ni cle_client ni id_etat écrits (défauts de colonne).
		$idc = ClientCopier($this->pdo, 'AssoY', 'Copie', 'C', 'a1', 'a2', 'a3', '69', 'V', 'tel', 'e', 'c', 3, 7);
		$rowc = $this->pdo->query("SELECT * FROM clients WHERE id_client=" . (int) $idc)->fetch();
		$this->assertNull($rowc['cle_client']);
		$this->assertEquals(0, $rowc['id_etat']);          // défaut

		// Modifier : ne touche pas id_etat.
		$etatAvant = $row['id_etat'];
		$this->assertTrue((bool) ClientModifier($this->pdo, $id, 'AssoZ', 'NeufMod', 'C2', 'b1', 'b2', 'b3', '75', 'Paris', '01', 'e2', 'c2', 4, 8));
		$rowm = $this->pdo->query("SELECT * FROM clients WHERE id_client=" . (int) $id)->fetch();
		$this->assertSame('NeufMod', $rowm['nom']);
		$this->assertEquals(4, $rowm['id_statut_client']);
		$this->assertEquals($etatAvant, $rowm['id_etat']); // id_etat inchangé
	}

	public function testClientAdhesionAjouterEtModifier(): void
	{
		$this->assertTrue((bool) ClientAdhesionAjouter($this->pdo, 1, 2024, 15.5, 7));
		$row = $this->pdo->query("SELECT * FROM clients_adhesions WHERE id_client=1")->fetch();
		$this->assertEquals(2024, $row['annee']);
		$this->assertEquals(15.5, $row['montant']);

		$this->assertTrue((bool) ClientAdhesionModifier($this->pdo, $row['id_adhesion'], 20, 8));
		$row = $this->pdo->query("SELECT * FROM clients_adhesions WHERE id_adhesion=" . (int) $row['id_adhesion'])->fetch();
		$this->assertEquals(20, $row['montant']);
		$this->assertEquals(8, $row['id_membre_auteur']);
	}

	public function testStatutsClientsListerTrieParLibelle(): void
	{
		$this->pdo->exec("INSERT INTO clients_statuts (id_statut_client, libelle_statut) VALUES (1, 'Particulier'), (3, 'Association'), (4, 'Société')");
		$liste = StatutsClientsLister($this->pdo);
		$libelles = array_map(static function ($r) { return $r['libelle_statut']; }, $liste);
		$this->assertSame(['Association', 'Particulier', 'Société'], $libelles);
	}

	public function testClientsTousTrieParNomPuisPrenom(): void
	{
		// Le setUp insère 5 clients ; tri nom ASC, prénom ASC, sans filtre d'état.
		$liste = ClientsTous($this->pdo);
		$noms = array_map(static function ($r) { return $r['nom']; }, $liste);
		$this->assertSame(['Albert', 'Cache', 'Durand', 'X', 'Y'], $noms);
	}
}
