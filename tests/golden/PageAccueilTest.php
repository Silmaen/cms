<?php

/**
 * Golden master de la page d'accueil connectée (accueil.tpl → header.tpl avec la
 * navbar). Fige le rendu du menu, du fil d'ariane et du bandeau d'environnement.
 */
final class PageAccueilTest extends SmartyRenderTestCase
{
	public function testRenduPageAccueilConnecte(): void
	{
		// Session d'un utilisateur connecté (groupe 1 = admin → icônes d'admin visibles).
		$_SESSION = [
			'id_admin_menu_selectionne' => 0,
			'breadcrumb'                => 'Accueil',
			'titre_page_fr'             => 'Accueil',
			'prenom_utilisateur'        => 'Jean',
			'nom_utilisateur'           => 'Dupont',
			'id_utilisateur_groupe'     => 1,
		];

		$html = $this->rendre('accueil.tpl', [
			'liste_items_menu' => [
				['id_admin_menu' => 10, 'url' => 'clients_liste.php',  'titre_fr' => 'Clients'],
				['id_admin_menu' => 20, 'url' => 'articles_liste.php', 'titre_fr' => 'Articles'],
			],
			'message_formulaire' => 'Bonjour Jean.',
			'cdf_env'            => 'local',
			'cdf_env_libelle'    => 'LOCAL',
			'cdf_env_badge'      => true,
		]);

		$this->assertRenduFige('accueil', $html);
	}
}
