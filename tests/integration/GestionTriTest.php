<?php

/**
 * Caractérisation de GestionTri() (chemin principal) : quand l'utilisateur
 * choisit une colonne + un sens, la fonction bascule cette colonne en colonne de
 * tri active (tri_utilisateur=1, sens_tri), remet les autres à 0, et publie le
 * choix dans $_SESSION.
 */
final class GestionTriTest extends DatabaseTestCase
{
    public function testChoisirUneColonneMetAJourLeTri(): void
    {
        $_SESSION['id_utilisateur'] = 3;
        $_SESSION['id_admin_menu_selectionne'] = 10;

        // Au départ, 'nom' est la colonne active ; 'date' ne l'est pas.
        $this->pdo->exec(
            "INSERT INTO admin_utilisateurs_session (id_utilisateur, id_admin_menu, colonne, ordre, sens_tri, tri_utilisateur, items_par_page) VALUES
                (3, 10, 'nom',  1, 'ASC', 1, 25),
                (3, 10, 'date', 2, 'ASC', 0, 25)"
        );

        // L'utilisateur trie désormais par 'date' en DESC.
        GestionTri($this->pdo, 'date', 'DESC', 'nom', '');

        // En base : 'date' devient active, 'nom' est désactivée.
        $date = $this->pdo->query("SELECT sens_tri, tri_utilisateur FROM admin_utilisateurs_session WHERE id_utilisateur=3 AND id_admin_menu=10 AND colonne='date'")->fetch();
        $nom  = $this->pdo->query("SELECT tri_utilisateur FROM admin_utilisateurs_session WHERE id_utilisateur=3 AND id_admin_menu=10 AND colonne='nom'")->fetch();
        $this->assertEquals(1, $date['tri_utilisateur']);
        $this->assertSame('DESC', $date['sens_tri']);
        $this->assertEquals(0, $nom['tri_utilisateur']);

        // Dans la session : le choix est publié pour l'affichage.
        $this->assertSame('date', $_SESSION['colonne']);
        $this->assertSame('DESC', $_SESSION['sens_tri']);
        $this->assertEquals(1, $_SESSION['tri_utilisateur']);
    }
}
