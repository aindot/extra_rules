<?php

return [
    'magic' => [
        'description' => 'Guess which validation categories an input belongs to',
        'no_matches' => 'No matching validation categories found.',
        'matched_single' => 'Matched 1 category:',
        'matched_multiple' => 'Matched :count categories:',
        'headers' => [
            'name' => 'Name',
            'key' => 'Key',
            'rule_class' => 'Rule class',
        ],
    ],
    'generate' => [
        'description' => 'Generate valid sample values for a validation rule',
        'generated' => 'Generated :count value(s) for [:rule]:',
    ],
    'redact' => [
        'description' => 'Detect and redact secrets in text',
        'no_matches' => 'No secrets detected.',
        'found' => 'Detected :count secret(s):',
        'one_source' => 'Provide exactly one input source: an argument, --file, or --stdin.',
        'unreadable_file' => 'Unable to read file [:file].',
    ],
];
