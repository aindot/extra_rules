<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a 12-digit Kuwait Civil ID number.
 * Used as the official personal identification number for residents of Kuwait.
 */
class KuwaitCivilId implements ValidationRule
{
    use RejectsInvalidValue;

    private int $length = 12;

    private int $mod = 11;

    private array $weight = [
        2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2,
    ];

    /**
     * Runs Kuwait Civil ID validation on length and check digit.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $state = is_numeric($value)
            && $this->hasValidLength($value)
            && $this->checkChecksum($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.kuwait_civil_id');
        }
    }

    /**
     * Checks that the value has exactly 12 digits.
     */
    private function hasValidLength(string $id): bool
    {
        return $this->length === strlen($id);
    }

    /**
     * Verifies the Civil ID check digit with the official weight table.
     */
    private function checkChecksum(string $id): bool
    {
        $sum = 0;
        for ($x = 0; $x < $this->length - 1; $x++) {
            $sum += substr($id, $x, 1) * $this->weight[$x];
        }

        return substr($id, -1) == ($this->mod - ($sum % $this->mod)) ? true : false;
    }
}
