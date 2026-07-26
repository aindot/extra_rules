<?php

return [
    'description' => 'Guess which validation categories an input belongs to',
    'headers' => [
        'name' => 'Rule Name',
        'key' => 'Rule Key',
        'rule_class' => 'Rule Class',
    ],
    'magic' => [
        'no_matches' => 'No matching validation rules found.',
        'matched_single' => 'Matched 1 validation rule.',
        'matched_multiple' => 'Matched :count validation rules.',
        'types' => [
            'name' => 'Rule Name',
            'key' => 'Rule Key',
            'rule_class' => 'Rule Class',
        ],
    ],
    'commands' => [
        'generate' => [
            'description' => 'Generate sample values for validation rules',
            'error_no_rule' => 'The rule type is required.',
            'count' => 'Generate :count sample values.',
            'header_type' => 'Rule type',
            'header_value' => 'Generated values',
        ],
        'validate' => [
            'description' => 'Validate a value against specific rules',
            'error_validation_failed' => 'Validation failed for :rule',
            'headers' => [
                'rule' => 'Rule',
                'passed' => 'Result',
            ],
        ],
    ],
];
