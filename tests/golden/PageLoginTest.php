<?php

/**
 * Golden master de la page de connexion (index.tpl, qui inclut header.tpl).
 * Session vide → la navbar du header est masquée : on fige le formulaire de login.
 */
final class PageLoginTest extends SmartyRenderTestCase
{
	public function testRenduPageLogin(): void
	{
		$html = $this->rendre('index.tpl', [
			'message_identification' => '',
			'cdf_env'         => 'local',
			'cdf_env_libelle' => 'LOCAL',
			'cdf_env_badge'   => true,
		]);

		$this->assertRenduFige('login', $html);
	}
}
