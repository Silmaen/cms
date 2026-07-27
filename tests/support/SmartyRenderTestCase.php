<?php

use PHPUnit\Framework\TestCase;

/**
 * Classe de base pour le GOLDEN MASTER des vues (templates Smarty).
 *
 * On rend un template avec des données FIXES, puis on compare le HTML produit à
 * un instantané (« snapshot ») versionné dans tests/snapshots/. Au premier passage
 * le snapshot est créé (le test est marqué « skipped » → à relancer + versionner) ;
 * ensuite, tout écart fait échouer le test.
 *
 * On rend les templates hors HTTP (pas de contrôleur, donc pas de exit()/BDD), ce
 * qui rend les tests déterministes. Smarty tolère les variables non définies (rendu
 * vide), donc les fixtures n'ont pas besoin d'être exhaustives.
 */
abstract class SmartyRenderTestCase extends TestCase
{
	/** @var Smarty */
	protected $smarty;

	public static function setUpBeforeClass(): void
	{
		require_once dirname(__DIR__, 2) . '/vendor/smarty/Smarty.class.php';
	}

	protected function setUp(): void
	{
		$racine  = dirname(__DIR__, 2);
		$compile = sys_get_temp_dir() . '/cdf_tests_templates_c';
		if (!is_dir($compile)) {
			mkdir($compile, 0777, true);
		}

		// Même configuration que cgi-bin/config/config_general.php (délimiteurs inclus).
		$this->smarty = new Smarty();
		$this->smarty->template_dir  = $racine . '/www/templates';
		$this->smarty->compile_dir   = $compile;
		$this->smarty->config_dir    = $racine . '/www/configs';
		$this->smarty->cache_dir     = $compile;
		$this->smarty->left_delimiter  = '<!--{';
		$this->smarty->right_delimiter = '}-->';

		$_SESSION = [];
		$_POST = [];
		$_GET = [];
	}

	protected function rendre(string $template, array $assigns = []): string
	{
		foreach ($assigns as $cle => $valeur) {
			$this->smarty->assign($cle, $valeur);
		}
		return $this->smarty->fetch($template);
	}

	protected function assertRenduFige(string $nom, string $html): void
	{
		$dossier = dirname(__DIR__) . '/snapshots';
		if (!is_dir($dossier)) {
			mkdir($dossier, 0777, true);
		}
		$fichier = $dossier . '/' . $nom . '.html';
		$html = rtrim($html) . "\n";

		if (!file_exists($fichier)) {
			file_put_contents($fichier, $html);
			$this->markTestSkipped(
				"Snapshot créé : tests/snapshots/{$nom}.html — relance les tests pour comparer, puis versionne le fichier."
			);
		}

		$this->assertSame(
			file_get_contents($fichier),
			$html,
			"Le rendu de « {$nom} » diffère du snapshot figé (tests/snapshots/{$nom}.html).\n"
			. "Si le changement est VOULU : supprime ce snapshot et relance pour le régénérer."
		);
	}
}
