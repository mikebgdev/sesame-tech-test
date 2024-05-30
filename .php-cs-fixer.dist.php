<?php

return (new PhpCsFixer\Config())
    ->setUsingCache(false)
    ->setRules([
        '@PHP74Migration' => true,
        '@PhpCsFixer' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'strict_comparison' => true,
        'array_syntax' => ['syntax' => 'short'],
        'list_syntax' => ['syntax' => 'short'],
        'native_function_invocation' => [
            'exclude' => [],
            'include' => ['@all'],
            'scope' => 'all',
            'strict' => true,
        ],
        'native_constant_invocation' => [
            'exclude' => [
                'null',
                'false',
                'true',
            ],
            'fix_built_in' => true,
            'include' => [],
            'scope' => 'all',
            'strict' => false,
        ],
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'no_unreachable_default_argument_value' => true,
        'comment_to_phpdoc' => true,
        'phpdoc_to_comment' => ['ignored_tags' => ['todo', 'var']],
        'no_superfluous_phpdoc_tags' => false,
        'header_comment' => [
            'header' => <<< 'HEADER'
                This class is part of a software application developed by [name].

                The application is distributed under the terms of the MIT License.
                For more information, please see the LICENSE file included in the source code.
                HEADER,
            'location' => 'after_open',
            'separate' => 'bottom',
        ],
        'single_line_empty_body' => false,
        'phpdoc_line_span' => ['const' => 'single', 'property' => 'single', 'method' => 'multi'],
        'self_static_accessor' => true,
        'simplified_if_return' => true,
        'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
        'modernize_strpos' => false,
    ])
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/tests')
    );
