<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an 8-digit EAN-8 barcode.
 * Used on small retail products where a full EAN-13 does not fit.
 */
class Ean8 implements ValidationRule
{
    private int $length = 8;

    private int $mod = 10;

    private array $weight = [
        3, 1, 3, 1, 3, 1, 3,
    ];

    /**
     * Runs EAN-8 validation on length and check digit.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace('-', '', (string) $value);
        $value = str_replace(' ', '', $value);

        $state = is_numeric($value)
            && $this->hasValidLength($value)
            && $this->checkChecksum($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    /**
     * Checks that the value has exactly 8 digits.
     */
    private function hasValidLength(string $id): bool
    {
        return $this->length === strlen($id);
    }

    /**
     * Verifies the EAN-8 check digit using weighted positions.
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
