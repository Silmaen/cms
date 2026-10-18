<?php

/**
 * Fil d'ariane de header.tpl : un lien de retour sur les formulaires seulement ($breadcrumb
 * assigné par le contrôleur), rien sur les listes et l'accueil (il y répétait l'onglet
 * actif et le titre, ex. « // Réservations » sous « Liste des réservations »).
 */
final class FilArianeTest extends SmartyRenderTestCase
{
	private function sessionConnectee(): void
	{
		$_SESSION = [
			'id_admin_menu_selectionne' => 1,
			'nom_table'                 => 'reservations',
			'breadcrumb'                => 'Réservations', // posé par les listes, plus affiché
			'titre_page_fr'             => 'Liste des réservations',
			'id_utilisateur_groupe'     => 2,
		];
	}

	public function testListeSansFilDAriane(): void
	{
		$this->sessionConnectee();
		$html = $this->rendre('accueil.tpl', ['liste_items_menu' => []]);
		$this->assertStringNotContainsString('breadcrumb-item', $html);
		$this->assertStringNotContainsString('//&nbsp;', $html);
	}

	public function testFormulaireAvecLienDeRetour(): void
	{
		$this->sessionConnectee();
		$html = $this->rendre('accueil.tpl', ['liste_items_menu' => [], 'breadcrumb' => 'Retour à la liste']);
		$this->assertStringContainsString('href="reservations_liste.php?id_admin_menu=1"><i class="fas fa-arrow-left"></i>&nbsp; Retour à la liste</a>', $html);
	}
}
