<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a ULID (Universally Unique Lexicographically Sortable Identifier).
 * Used as a 26-character sortable alternative to UUID.
 */
class Ulid implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs ULID validation against Crockford base32 and length 26.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtoupper(trim((string) $value));

        $state = (bool) preg_match('/^[0-9A-HJKMNP-TV-Z]{26}$/', $value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.ulid');
        }
    }
}
