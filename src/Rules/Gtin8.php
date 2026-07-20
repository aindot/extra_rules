<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an 8-digit GTIN-8 trade item number.
 * Used for small products and is structurally equivalent to EAN-8.
 */
class Gtin8 implements ValidationRule
{
    private int $length = 8;

    private array $weight = [
        3, 1, 3, 1, 3, 1, 3,
    ];

    /**
     * Runs GTIN-8 validation on length and check digit.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace(['-', ' '], '', (string) $value);

        $state = ctype_digit($value)
            && strlen($value) === $this->length
            && $this->checkChecksum($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    /**
     * Verifies the GTIN-8 check digit using GS1 weights.
     */
    private function checkChecksum(string $id): bool
    {
        $sum = 0;

        for ($i = 0; $i < 7; $i++) {
            $sum += (int) $id[$i] * $this->weight[$i];
        }

        return (int) $id[7] === ((10 - ($sum % 10)) % 10);
    }
}
