<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a 13-digit GTIN-13 (same structure as EAN-13).
 * Used in global trade to identify products, cases, and packaging.
 */
class Gtin13 implements ValidationRule
{
    use RejectsInvalidValue;

    private int $length = 13;

    private int $mod = 10;

    private array $weight = [
        1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3,
    ];

    /**
     * Runs GTIN-13 validation on length and check digit.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace('-', '', (string) $value);
        $value = str_replace(' ', '', $value);

        $state = is_numeric($value)
            && $this->hasValidLength($value)
            && $this->checkChecksum($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.gtin13');
        }
    }

    /**
     * Checks that the value has exactly 13 digits.
     */
    private function hasValidLength(string $id): bool
    {
        return $this->length === strlen($id);
    }

    /**
     * Verifies the GTIN-13 check digit using weighted positions.
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
