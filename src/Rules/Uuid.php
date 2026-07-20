<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a UUID (Universally Unique Identifier).
 * Used for standard 8-4-4-4-12 hexadecimal resource identifiers.
 */
class Uuid implements ValidationRule
{
    /**
     * Runs UUID validation for canonical hyphenated hex form.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtolower(trim((string) $value));

        $state = (bool) preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            $value
        );

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }
}
