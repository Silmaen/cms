<?php

use PHPUnit\Framework\TestCase;

/**
 * Caractérisation de cdfDetecterEnvironnement() (cgi-bin/config/environnement.php) :
 * priorité à la variable CDF_ENV, sinon détection par nom d'hôte, sinon 'prod'.
 */
final class EnvironnementTest extends TestCase
{
	/** @var string|false */
	private $cdfEnvInitial;
	/** @var string|null */
	private $hoteInitial;

	protected function setUp(): void
	{
		$this->cdfEnvInitial = getenv('CDF_ENV');
		$this->hoteInitial   = $_SERVER['HTTP_HOST'] ?? null;
	}

	protected function tearDown(): void
	{
		// Restaure l'état initial (le conteneur définit CDF_ENV=local).
		if ($this->cdfEnvInitial === false) {
			putenv('CDF_ENV');
		} else {
			putenv('CDF_ENV=' . $this->cdfEnvInitial);
		}
		if ($this->hoteInitial === null) {
			unset($_SERVER['HTTP_HOST']);
		} else {
			$_SERVER['HTTP_HOST'] = $this->hoteInitial;
		}
	}

	private function detecter(?string $cdfEnv, ?string $hote): string
	{
		if ($cdfEnv === null) {
			putenv('CDF_ENV');
		} else {
			putenv('CDF_ENV=' . $cdfEnv);
		}
		if ($hote === null) {
			unset($_SERVER['HTTP_HOST']);
		} else {
			$_SERVER['HTTP_HOST'] = $hote;
		}
		return cdfDetecterEnvironnement();
	}

	public function testCdfEnvLEmporteSurLHote(): void
	{
		$this->assertSame('test', $this->detecter('test', 'www.cdf-genay.com'));
	}

	public function testProdParDefaut(): void
	{
		$this->assertSame('prod', $this->detecter(null, 'www.cdf-genay.com'));
		$this->assertSame('prod', $this->detecter(null, 'cdf-genay.com'));
	}

	public function testRecette(): void
	{
		$this->assertSame('recette', $this->detecter(null, 'recette.cdf-genay.com'));
	}

	public function testTest(): void
	{
		$this->assertSame('test', $this->detecter(null, 'test.cdf-genay.com'));
	}

	public function testLocal(): void
	{
		$this->assertSame('local', $this->detecter(null, 'localhost:8080'));
		$this->assertSame('local', $this->detecter(null, '127.0.0.1'));
	}

	public function testCliSansHoteRenvoieProd(): void
	{
		$this->assertSame('prod', $this->detecter(null, null));
	}

	public function testHoteInconnuRenvoieProd(): void
	{
		$this->assertSame('prod', $this->detecter(null, 'exemple.com'));
	}

	public function testCdfEnvInvalideEstIgnore(): void
	{
		// Valeur non reconnue → on retombe sur la détection par hôte.
		$this->assertSame('recette', $this->detecter('bidon', 'recette.cdf-genay.com'));
	}
}
