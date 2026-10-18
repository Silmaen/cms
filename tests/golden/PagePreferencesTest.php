<?php

/**
 * Golden master de l'écran « Mes préférences » (preferences.tpl), avec un thème sombre
 * en session (attribut data-theme du header) et une valeur de pagination héritée (50).
 */
final class PagePreferencesTest extends SmartyRenderTestCase
{
	public function testRenduPagePreferences(): void
	{
		$_SESSION = [
			'id_admin_menu_selectionne' => 0,
			'breadcrumb'                => 'Accueil',
			'titre_page_fr'             => 'Mes préférences',
			'prenom_utilisateur'        => 'Jean',
			'nom_utilisateur'           => 'Dupont',
			'id_utilisateur_groupe'     => 2,
			'theme'                     => 'sombre',
			'interface'                 => 'moderne',
		];

		$html = $this->rendre('preferences.tpl', [
			'liste_items_menu'     => [
				['id_admin_menu' => 10, 'url' => 'clients_liste.php', 'titre_fr' => 'Clients'],
			],
			'theme'                => 'sombre',
			'liste_themes'         => PreferencesChoix()['theme'],
			'interface'            => 'moderne',
			'liste_interfaces'     => PreferencesChoix()['interface'],
			'items_par_page'       => 50,
			'liste_items_par_page' => [50 => '50 par page'] + PreferencesItemsParPageChoix(),
			'message_formulaire'   => 'Préférences enregistrées',
			'cdf_env'              => 'local',
			'cdf_env_libelle'      => 'LOCAL',
			'cdf_env_badge'        => true,
		]);

		$this->assertRenduFige('preferences', $html);
	}
}
