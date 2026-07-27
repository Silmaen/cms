<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests de CARACTÉRISATION des fonctions « pures » de fonctions_general.php
 * (celles qui ne touchent ni à la base ni à $_SESSION).
 *
 * But : figer le comportement ACTUEL — y compris les bizarreries — afin que toute
 * modification future qui change ce comportement fasse « claquer » un test.
 * Ce ne sont PAS des tests de spécification : ils décrivent ce que le code FAIT
 * aujourd'hui, pas ce qu'il devrait idéalement faire.
 */
final class FonctionsGeneralPuresTest extends TestCase
{
	// ---------------------------------------------------------------- Hashage

	public function testHashageValeurExacteFigee(): void
	{
		// Empreinte actuelle = préfixe statique + sha256(mdp) + suffixe statique.
		// Toute modification de l'algo ou du sel (cf. roadmap) fera claquer ce test.
		$attendu = 'azd12s5qsd558qs41qs4qsd62s8'
				 . '2bb80d537b1da3e38bd30361aa855686bde0eacd7162fef6a25fe97bf527a25b'
				 . '2s4hx5d8z4123cbdr31sdfqsd54';
		$this->assertSame($attendu, GestionHashage('secret'));
	}

	public function testHashageStructureEtDeterminisme(): void
	{
		$h = GestionHashage('secret');
		$this->assertSame(118, strlen($h));                       // 27 + 64 (sha256) + 27
		$this->assertStringStartsWith('azd12s5qsd558qs41qs4qsd62s8', $h);
		$this->assertStringEndsWith('2s4hx5d8z4123cbdr31sdfqsd54', $h);
		$this->assertSame($h, GestionHashage('secret'));          // déterministe
		$this->assertNotSame($h, GestionHashage('secre'));        // sensible à l'entrée
	}

	// ------------------------------------------------------------------- Date

	public function testDateIsoVersFrancais(): void
	{
		$this->assertSame('19-03-2024', GestionDate('2024-03-19', '0'));
	}

	public function testDateFrancaisVersIso(): void
	{
		$this->assertSame('2024-03-19', GestionDate('19-03-2024', '1'));
	}

	public function testDateVideRenvoieFalse(): void
	{
		$this->assertFalse(GestionDate('', '0'));
	}

	public function testDateModeInconnuRenvoieFalse(): void
	{
		// Tout mode autre que '0' ou '1' tombe dans le else → false.
		$this->assertFalse(GestionDate('2024-03-19', '2'));
	}

	public function testDateInvalidePlante(): void
	{
		// Bizarrerie figée : createFromFormat() renvoie false sur une date invalide,
		// puis false->format() lève une \Error. On documente/verrouille ce plantage.
		$this->expectException(\Error::class);
		GestionDate('pasunedate', '0');
	}

	// ------------------------------------------------ Droits de visibilité (états)

	public function testEtatsGroupesConnus(): void
	{
		$this->assertSame('99', GestionUtilisateursEtats('1')); // admin : tout voir
		$this->assertSame('2',  GestionUtilisateursEtats('2')); // actif + archivé
		$this->assertSame('1',  GestionUtilisateursEtats('3')); // actif seulement
	}

	public function testEtatsGroupeInconnuRenvoieNull(): void
	{
		// Bizarrerie figée : aucun else → variable non définie → null.
		$this->assertNull(@GestionUtilisateursEtats('4'));
	}
}
