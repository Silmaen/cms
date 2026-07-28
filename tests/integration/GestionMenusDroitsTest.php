<?php

/**
 * Caractérisation de GestionMenusDroits() : lit le droit du groupe courant sur
 * le menu courant (depuis $_SESSION) et le range dans $_SESSION['droit'].
 */
final class GestionMenusDroitsTest extends DatabaseTestCase
{
    public function testRenseigneLeDroitDansLaSession(): void
    {
        $this->pdo->exec(
            "INSERT INTO admin_menus_groupes (id_admin_menu, id_utilisateur_groupe, droit)
             VALUES (10, 1, 2)"
        );
        $_SESSION['id_utilisateur_groupe'] = 1;
        $_SESSION['id_admin_menu_selectionne'] = 10;

        GestionMenusDroits($this->pdo);

        $this->assertEquals(2, $_SESSION['droit']);
    }
}
