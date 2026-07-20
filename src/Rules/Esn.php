<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an ESN (Electronic Serial Number).
 * Used as a legacy identifier for older CDMA mobile devices.
 */
class Esn implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs ESN validation for 8-hex or 11-decimal forms.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtoupper(str_replace(['-', ' ', ':'], '', (string) $value));

        $state = (bool) preg_match('/^[0-9A-F]{8}$/', $value)
            || (bool) preg_match('/^\d{11}$/', $value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.esn');
        }
    }
}
