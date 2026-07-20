<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an E.164 international phone number.
 * Used for globally unique telephone numbers including country code.
 */
class E164 implements ValidationRule
{
    /**
     * Runs E.164 validation for optional + and up to 15 digits.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = preg_replace('/[\s\-\(\)]/', '', (string) $value) ?? '';

        $state = (bool) preg_match('/^\+?[1-9]\d{1,14}$/', $value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }
}
