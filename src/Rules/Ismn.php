<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an ISMN (International Standard Music Number).
 * Used to identify printed music publications (scores and sheet music).
 */
class Ismn implements ValidationRule
{
    private int $length = 13;

    private int $mod = 10;

    private array $weight = [
        1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3,
    ];

    /**
     * Runs ISMN validation, converting legacy M-prefixed values to ISMN-13.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace('-', '', (string) $value);
        $value = str_replace(' ', '', $value);
        $value = strtoupper($value);

        if (str_starts_with($value, 'M')) {
            $value = '9790'.substr($value, 1);
        }

        $state = is_numeric($value)
            && $this->hasValidLength($value)
            && $this->correctPrefix($value)
            && $this->checkChecksum($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    /**
     * Checks that the value has exactly 13 digits after normalization.
     */
    private function hasValidLength(string $id): bool
    {
        return $this->length === strlen($id);
    }

    /**
     * Checks that the ISMN starts with the music prefix 9790.
     */
    private function correctPrefix(string $id): bool
    {
        return str_starts_with($id, '9790');
    }

    /**
     * Verifies the ISMN check digit using EAN-13 weights.
     */
    private function checkChecksum(string $id): bool
    {
        $sum = 0;

        for ($x = 0; $x < $this->length - 1; $x++) {
            $sum += substr($id, $x, 1) * $this->weight[$x];
        }

        $digit = ($this->mod - ($sum % $this->mod)) % $this->mod;

        return substr($id, -1) == $digit;
    }
}
