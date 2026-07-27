<?php
//
// Règles de mise en forme du code PHP du projet.
//
// Volontairement MINIMAL : on normalise seulement l'indentation (tabulations,
// cf. .editorconfig) et les espaces (fins de ligne, lignes vides). On NE touche
// PAS aux accolades, aux guillemets, aux tableaux ni au contenu des chaînes
// (php-cs-fixer est token-aware : les chaînes — y compris les fichiers latin1 des
// impressions PDF — ne sont jamais modifiées).
//
// Lancer :   ./cs.sh              (corrige)
//            ./cs.sh --dry-run    (vérifie sans modifier — comme la CI)
//
// La version de l'outil est fixée dans .env.defaults (PHP_CS_FIXER_VERSION).

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/www', __DIR__ . '/cgi-bin', __DIR__ . '/metier', __DIR__ . '/tests'])
    ->exclude(['assets/vendor', 'templates_c', 'cache', '.bin'])
    ->notName('config_secrets*.php')
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(false)
    ->setUsingCache(false)
    ->setIndent("\t")
    ->setLineEnding("\n")
    ->setRules([
        'indentation_type'            => true,                     // indentation → tabulations
        'no_trailing_whitespace'      => true,                     // espaces/tabs en fin de ligne (hors chaînes)
        'no_whitespace_in_blank_line' => true,                     // lignes « vides » contenant des espaces/tabs
        'no_extra_blank_lines'        => ['tokens' => ['extra']],  // 2+ lignes vides consécutives → 1
        'single_blank_line_at_eof'    => true,                     // exactement une newline finale
    ])
    ->setFinder($finder);
