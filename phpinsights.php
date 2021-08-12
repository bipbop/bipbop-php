<?php

declare(strict_types=1);

return [
    'preset' => 'default',
    'exclude' => [
        'phpinsights.php'
    ],
    'add' => [],
    'remove' => [
        // Allow non final classes
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses::class,
        // FIXME: This should be removed once we get rid of mixed types
        \SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff::class
    ],
    'config' => [
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class => [
            'lineLimit' => 100,
            'absoluteLineLimit' => 100,
            'ignoreComments' => false,
        ],
        // FIXME: This should be reduced to around 9
        \NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh::class => [
             'maxComplexity' => 12,
        ],
    ],
    'requirements' => [
        'min-quality' => 95,
        'min-complexity' => 50, // FIXME: This should be increased to around 80
        'min-architecture' => 95,
        'min-style' => 95,
        'disable-security-check' => false,
    ],

];
