<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor')
    ->notPath([
        'config/bundles.php',
        'config/reference.php',
    ])
;

return (new PhpCsFixer\Config('ticket-master/php8.5'))
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,

        'php_unit_internal_class' => true,
        'phpdoc_to_param_type' => true,
        'phpdoc_to_return_type' => true,
        'void_return' => true,

        'array_syntax' => [
            'syntax' => 'short',
        ],
        'braces' => [
            'allow_single_line_closure' => true,
        ],
        'declare_strict_types' => true,
        'mb_str_functions' => true,
        'modernize_types_casting' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'break',
                'continue',
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'throw',
                'use',
            ],
        ],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['const', 'class', 'function'],
        ],
        'phpdoc_add_missing_param_annotation' => [
            'only_untyped' => true,
        ],
        'phpdoc_order' => [
            'order' => ['param', 'return', 'throws'],
        ],
        'phpdoc_to_comment' => false,
        'phpdoc_var_without_name' => false,
        'strict_comparison' => true,
        'strict_param' => true,
        'ternary_to_null_coalescing' => true,
        'visibility_required' => [
            'elements' => ['property', 'method', 'const'],
        ],
        'native_function_invocation' => [
            'include' => ['@compiler_optimized'],
            'scope' => 'namespaced',
        ],
        'self_accessor' => true,
        'single_line_throw' => true,
        'cast_spaces' => [
            'space' => 'none',
        ],
        'combine_consecutive_unsets' => true,
        'echo_tag_syntax' => true,
        'fully_qualified_strict_types' => [
            'leading_backslash_in_global_namespace' => true,
        ],
        'linebreak_after_opening_tag' => true,
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'no_alternative_syntax' => true,
        'no_null_property_initialization' => true,
        'no_php4_constructor' => true,
        'no_unset_on_property' => true,
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant',
                'property',
                'construct',
                'destruct',
                'method',
            ],
        ],
        'ordered_traits' => true,
        'php_unit_set_up_tear_down_visibility' => true,
        'php_unit_test_case_static_method_calls' => [
            'call_type' => 'this',
        ],
        'phpdoc_no_access' => true,
        'phpdoc_no_package' => true,
        'phpdoc_scalar' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'single_class_element_per_statement' => [
            'elements' => ['property'],
        ],
        'single_line_comment_style' => [
            'comment_types' => ['asterisk', 'hash'],
        ],
        'space_after_semicolon' => [
            'remove_in_empty_for_expressions' => true,
        ],
    ])
    ->setFinder($finder)
;
