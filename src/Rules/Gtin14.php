<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a 14-digit GTIN-14 trade item number.
 * Used to identify trade items at case, carton, or pallet level.
 */
class Gtin14 implements ValidationRule
{
    private int $length = 14;

    /**
     * Runs GTIN-14 validation on length and GS1 check digit.
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
     * Verifies the GTIN-14 check digit using GS1 weights from the left.
     */
    private function checkChecksum(string $id): bool
    {
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $id[$i] * ($i % 2 === 0 ? 3 : 1);
        }

        return (int) $id[13] === ((10 - ($sum % 10)) % 10);
    }
}
