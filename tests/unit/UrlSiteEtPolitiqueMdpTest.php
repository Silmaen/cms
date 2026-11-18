<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../cgi-bin/config/environnement.php';
require_once __DIR__ . '/../../metier/utilisateurs.php';

/**
 * cdfUrlSite() (cgi-bin/config/environnement.php) et MdpRespectePolitique()
 * (metier/utilisateurs.php) : fonctions pures du flux « mot de passe perdu ».
 */
final class UrlSiteEtPolitiqueMdpTest extends TestCase
{
	public function testUrlFixeParEnvironnementSansTenirCompteDeLHote(): void
	{
		// Un hôte forgé par le client ne doit jamais se retrouver dans le lien.
		$this->assertSame('https://www.cdf-genay.com', cdfUrlSite('prod', 'pirate.example'));
		$this->assertSame('https://recette.cdf-genay.com', cdfUrlSite('recette', 'pirate.example'));
		$this->assertSame('https://test.cdf-genay.com', cdfUrlSite('test', 'pirate.example'));
	}

	public function testUrlLocaleReprendLHote(): void
	{
		$this->assertSame('http://localhost:8080', cdfUrlSite('local', 'localhost:8080'));
		$this->assertSame('http://localhost:8080', cdfUrlSite('local'));
	}

	/**
	 * Tâche planifiée sur OVH : ni nom d'hôte ni CDF_ENV, l'environnement vient de
	 * config_secrets.php. Processus séparé : une constante ne se retire pas.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testCronSansHoteLitCdfEnvServeur(): void
	{
		putenv('CDF_ENV');
		unset($_SERVER['HTTP_HOST']);
		$this->assertSame('prod', cdfDetecterEnvironnement());
		define('CDF_ENV_SERVEUR', 'test');
		$this->assertSame('test', cdfDetecterEnvironnement());
		$this->assertSame('https://test.cdf-genay.com', cdfUrlSite(cdfDetecterEnvironnement()));
	}

	public function testPolitiqueMotDePasse(): void
	{
		$this->assertTrue(MdpRespectePolitique('Abcdefg1'));
		$this->assertFalse(MdpRespectePolitique('Abcdef1'), 'moins de 8 caractères');
		$this->assertFalse(MdpRespectePolitique('abcdefg1'), 'sans majuscule');
		$this->assertFalse(MdpRespectePolitique('ABCDEFG1'), 'sans minuscule');
		$this->assertFalse(MdpRespectePolitique('Abcdefgh'), 'sans chiffre');
		$this->assertFalse(MdpRespectePolitique('Abcd efg1'), 'avec espace');
	}
}
