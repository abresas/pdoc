<?php

/**
 * This configuration will be read and overlaid on top of the
 * default configuration. Command line arguments will be applied
 * after this file is read.
 */
return [
    'target_php_version' => null,
    'backward_compatibility_tests' => false,
    'quick_mode' => true,
    'analyze_signature_compatibility' => false,
    'minimum_severity' => 0,
    'allow_missing_properties' => false,
    'null_casts_as_any_type' => false,
    'null_casts_as_array' => false,
    'array_casts_as_null' => false,
    'scalar_implicit_cast' => false,
    'scalar_implicit_partial' => [],
    'ignore_undeclared_variables_in_global_scope' => false,
    'dead_code_detection' => false, // slow
    'simplify_ast' => false, // slow
    'directory_list' => [
        'lib/',
    ],
    'exclude_file_regex' => '/vendor\/mockery\/.*/',
    "exclude_analysis_directory_list" => [
        'vendor/',
    ],
];
