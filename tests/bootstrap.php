<?php
// Bootstrap des tests.
//
// On charge la bibliothèque de fonctions transverses. Ce fichier ne fait que
// DÉFINIR des fonctions (aucun effet de bord au chargement : pas de session, pas
// de connexion), il est donc sûr à inclure ici.
require_once __DIR__ . '/../cgi-bin/config/fonctions_general.php';
require_once __DIR__ . '/../cgi-bin/config/environnement.php';

// Classes de base des tests (hors répertoires de suite → chargées explicitement).
require_once __DIR__ . '/support/DatabaseTestCase.php';
require_once __DIR__ . '/support/SmartyRenderTestCase.php';
