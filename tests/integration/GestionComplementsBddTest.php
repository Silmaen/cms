<?php

/**
 * Branches BDD encore non couvertes ailleurs :
 *  - GestionPaginationReservations (variante avec requête COUNT fournie) ;
 *  - GestionSuppression action 'supprimer-envoyer' ;
 *  - GestionTri branche « mise à jour des items par page ».
 */
final class GestionComplementsBddTest extends DatabaseTestCase
{
    public function testPaginationReservationsUtiliseLaRequeteFournie(): void
    {
        for ($i = 1; $i <= 7; $i++) {
            $this->pdo->exec("INSERT INTO clients (id_client, nom) VALUES ({$i}, 'C{$i}')");
        }
        $_SESSION['items_par_page'] = 3;

        GestionPaginationReservations($this->pdo, 'id_client', 'clients', 'SELECT COUNT(id_client) FROM clients');

        $this->assertEquals(3, $_SESSION['nombre_de_pages']); // ceil(7 / 3)
    }

    public function testSuppressionSupprimerEnvoyerPasseIdEtatA3(): void
    {
        $this->pdo->exec("INSERT INTO articles (id_article, designation, id_etat) VALUES (8, 'X', 1)");
        $_SESSION['id_membre_auteur'] = 1;

        GestionSuppression($this->pdo, 'id_article', 'articles', 8, 'supprimer-envoyer');

        $etat = (int) $this->pdo->query("SELECT id_etat FROM articles WHERE id_article=8")->fetchColumn();
        $this->assertSame(3, $etat);
        $this->assertSame('Suppression enregistrée', $_SESSION['message_formulaire']);
    }

    public function testTriMetAJourItemsParPage(): void
    {
        $_SESSION['id_utilisateur'] = 3;
        $_SESSION['id_admin_menu_selectionne'] = 10;
        $this->pdo->exec(
            "INSERT INTO admin_utilisateurs_session (id_utilisateur, id_admin_menu, colonne, ordre, sens_tri, tri_utilisateur, items_par_page)
             VALUES (3, 10, 'nom', 1, 'ASC', 1, 25)"
        );

        // colonne='' + sens_tri='' + items_par_page renseigné → branche « pagination ».
        GestionTri($this->pdo, '', '', 'nom', '50');

        $ipp = $this->pdo->query(
            "SELECT items_par_page FROM admin_utilisateurs_session WHERE id_utilisateur=3 AND id_admin_menu=10 AND colonne='nom'"
        )->fetchColumn();
        $this->assertEquals(50, $ipp);
    }
}
