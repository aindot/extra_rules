<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a BIC/SWIFT business identifier code.
 * Used to identify banks and financial institutions in payments.
 */
class Bic implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs BIC validation for 8- or 11-character SWIFT format.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtoupper(str_replace([' ', '-'], '', (string) $value));

        $state = (bool) preg_match('/^[A-Z]{4}[A-Z]{2}[A-Z0-9]{2}([A-Z0-9]{3})?$/', $value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.bic');
        }
    }
}
