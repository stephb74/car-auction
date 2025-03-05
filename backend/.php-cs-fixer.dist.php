<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12'                      => true,
        '@Symfony'                    => true,
        'array_syntax'                => ['syntax' => 'short'],
        'binary_operator_spaces'      => ['default' => 'align_single_space_minimal'],
        'blank_line_before_statement' => [
            'statements' => ['return']
        ],
        'cast_spaces'       => ['space' => 'single'],
        'no_unused_imports' => true,
    ])
    ->setFinder($finder);
