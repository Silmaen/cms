<?php

/**
 * Caractérisation de DupliquerSessionUtilisateur() : recopie les préférences
 * d'affichage (admin_utilisateurs_session) d'un utilisateur source vers un
 * nouvel utilisateur.
 */
final class DupliquerSessionUtilisateurTest extends DatabaseTestCase
{
    public function testCopieLesPreferencesVersLeNouvelUtilisateur(): void
    {
        // Deux préférences pour l'utilisateur source (3).
        $this->pdo->exec(
            "INSERT INTO admin_utilisateurs_session (id_utilisateur, id_admin_menu, colonne, sens_tri, tri_utilisateur) VALUES
                (3, 10, 'nom',  'ASC', 1),
                (3, 10, 'date', 'DESC', 0)"
        );

        $ok = DupliquerSessionUtilisateur($this->pdo, 3, 9);

        $this->assertTrue($ok);
        $this->assertSame(2, $this->compter('admin_utilisateurs_session', 'id_utilisateur=9'));

        // La colonne triée par l'utilisateur source est bien recopiée.
        $ligne = $this->pdo->query(
            "SELECT colonne, sens_tri FROM admin_utilisateurs_session WHERE id_utilisateur=9 AND tri_utilisateur=1"
        )->fetch();
        $this->assertSame('nom', $ligne['colonne']);
        $this->assertSame('ASC', $ligne['sens_tri']);
    }
}
