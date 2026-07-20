<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates JWT (JSON Web Token) structure.
 * Used to check header.payload.signature base64url shape, not signature trust.
 */
class Jwt implements ValidationRule
{
    /**
     * Runs JWT structural validation for three base64url segments.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);

        $state = (bool) preg_match(
            '/^[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]*$/',
            $value
        );

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }
}
