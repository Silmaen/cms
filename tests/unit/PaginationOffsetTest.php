<?php

use PHPUnit\Framework\TestCase;

/**
 * Caractérisation de PaginationOffset() (metier/commun.php).
 */
final class PaginationOffsetTest extends TestCase
{
	public function testPageAbsenteDonnePremierePage(): void
	{
		$r = PaginationOffset(null, 5, 25);
		$this->assertSame(1, $r['page']);
		$this->assertSame(0, $r['offset']);
	}

	public function testPageDonneeCalculeLOffset(): void
	{
		$r = PaginationOffset('2', 5, 25);
		$this->assertSame(2, $r['page']);
		$this->assertSame(25, $r['offset']);
	}

	public function testPageAuDelaDuMaxEstRamenee(): void
	{
		$r = PaginationOffset('10', 3, 25);
		$this->assertEquals(3, $r['page']);    // ramenée à 3
		$this->assertEquals(50, $r['offset']); // (3-1)*25
	}

	public function testBizarreriePageInvalideDonneOffsetNegatif(): void
	{
		// intval('abc') = 0 ; 0 n'est pas > nbPages → page 0 → offset négatif. Figé tel quel.
		$r = PaginationOffset('abc', 5, 25);
		$this->assertSame(0, $r['page']);
		$this->assertSame(-25, $r['offset']);
	}
}
