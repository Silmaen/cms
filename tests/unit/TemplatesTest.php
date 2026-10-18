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

	/**
	 * Tout bouton ou lien qui n'affiche qu'une icône porte une info-bulle (title) ou un
	 * aria-label. Le texte fourni par une variable Smarty compte comme un libellé.
	 */
	public function testBoutonsIconeOntUneInfoBulle(): void
	{
		foreach (glob(__DIR__ . '/../../www/templates/*.tpl') as $fichier) {
			// Balises Smarty neutralisées (« T ») : leur « > » fausserait la lecture des balises HTML.
			$source = preg_replace('/<!--\{.*?\}-->/s', 'T', file_get_contents($fichier));
			preg_match_all('/<(a|button)\b([^>]*)>(.*?)<\/\1>/s', $source, $balises, PREG_SET_ORDER);
			foreach ($balises as [$balise, , $attributs, $contenu]) {
				$texte = preg_replace('/<[^>]+>|&nbsp;|\s/', '', $contenu);
				if (strpos($contenu, '<i ') === false || $texte !== '') {
					continue;
				}
				$this->assertMatchesRegularExpression('/\b(title|aria-label)=/', $attributs, basename($fichier) . ' : ' . substr($balise, 0, 120));
			}
		}
	}
}
