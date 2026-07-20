<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an ISRC (International Standard Recording Code).
 * Used to uniquely identify sound recordings and sound videos.
 */
class Isrc implements ValidationRule
{
    /**
     * Runs ISRC validation against the 12-character country-registrant-year-designation format.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtoupper((string) $value);
        $value = str_replace(['-', ' ', ':'], '', $value);

        $state = (bool) preg_match('/^[A-Z]{2}[A-Z0-9]{3}\d{7}$/', $value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }
}
