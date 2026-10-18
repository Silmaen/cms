<?php

use PHPUnit\Framework\TestCase;

/**
 * Contrôles statiques des templates Smarty (www/templates/*.tpl).
 */
final class TemplatesTest extends TestCase
{
	/**
	 * Non-régression : articles_formulaire.tpl incluait header.tpl deux fois (barre de menu
	 * et titre affichés en double, deux <head>/<body> dans la page).
	 */
	public function testEnTeteInclusAuPlusUneFoisParTemplate(): void
	{
		foreach (glob(__DIR__ . '/../../www/templates/*.tpl') as $fichier) {
			$source = file_get_contents($fichier);
			$inclusions = preg_match_all('/include file="header(_mdp)?\.tpl"/', $source);
			$this->assertLessThanOrEqual(1, $inclusions, basename($fichier) . " inclut l'en-tête {$inclusions} fois");
		}
	}
}
