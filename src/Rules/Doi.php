<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a Digital Object Identifier (DOI).
 * Used to identify digital works such as papers, datasets, and publications.
 */
class Doi implements ValidationRule
{
    /**
     * Runs DOI validation after normalizing URL or doi: prefixes.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);
        $value = preg_replace('#^https?://(dx\.)?doi\.org/#i', '', $value) ?? $value;
        $value = preg_replace('/^doi:\s*/i', '', $value) ?? $value;

        $state = (bool) preg_match('#^10\.\d{4,9}/\S+$#', $value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }
}
