<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a card security code (CVV/CVC).
 * Used as the 3- or 4-digit verification value on payment cards.
 */
class Cvv implements ValidationRule
{
    /**
     * Runs CVV validation requiring 3 or 4 digits only.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $state = (bool) preg_match('/^\d{3,4}$/', (string) $value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }
}
