<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a TD3 passport MRZ line-2 string with ICAO check digits.
 * Used to verify machine-readable passport data line integrity.
 */
class PassportMrz implements ValidationRule
{
    /**
     * Runs TD3 MRZ line-2 validation including field and composite checks.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtoupper(str_replace(' ', '', (string) $value));

        $state = strlen($value) === 44
            && (bool) preg_match('/^[A-Z0-9<]{44}$/', $value)
            && $this->checkDigit(substr($value, 0, 9), $value[9])
            && $this->checkDigit(substr($value, 13, 6), $value[19])
            && $this->checkDigit(substr($value, 21, 6), $value[27])
            && $this->checkDigit(substr($value, 28, 14), $value[42])
            && $this->checkDigit(substr($value, 0, 10).substr($value, 13, 7).substr($value, 21, 22), $value[43]);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    /**
     * Verifies an MRZ field check digit with weights 7, 3, 1.
     */
    private function checkDigit(string $data, string $check): bool
    {
        $weights = [7, 3, 1];
        $sum = 0;

        for ($i = 0; $i < strlen($data); $i++) {
            $sum += $this->charValue($data[$i]) * $weights[$i % 3];
        }

        return (string) ($sum % 10) === $check;
    }

    /**
     * Maps an MRZ character to its ICAO numeric value.
     */
    private function charValue(string $char): int
    {
        if ($char === '<') {
            return 0;
        }

        if (ctype_digit($char)) {
            return (int) $char;
        }

        return ord($char) - 55;
    }
}
