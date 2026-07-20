<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a 17-character VIN (Vehicle Identification Number).
 * Used to uniquely identify motor vehicles with a North American check digit.
 */
class Vin implements ValidationRule
{
    use RejectsInvalidValue;

    private array $transliteration = [
        'A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8,
        'J' => 1, 'K' => 2, 'L' => 3, 'M' => 4, 'N' => 5, 'P' => 7, 'R' => 9,
        'S' => 2, 'T' => 3, 'U' => 4, 'V' => 5, 'W' => 6, 'X' => 7, 'Y' => 8, 'Z' => 9,
    ];

    private array $weights = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];

    /**
     * Runs VIN validation on format and position-9 check digit.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtoupper(str_replace(['-', ' '], '', (string) $value));

        $state = $this->hasValidFormat($value) && $this->checkChecksum($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.vin');
        }
    }

    /**
     * Checks 17 allowed VIN characters excluding I, O, and Q.
     */
    private function hasValidFormat(string $value): bool
    {
        return (bool) preg_match('/^[A-HJ-NPR-Z0-9]{17}$/', $value);
    }

    /**
     * Verifies the VIN check digit at position 9 using mod 11.
     */
    private function checkChecksum(string $value): bool
    {
        $sum = 0;
        for ($i = 0; $i < 17; $i++) {
            $char = $value[$i];
            $number = ctype_digit($char) ? (int) $char : $this->transliteration[$char];
            $sum += $number * $this->weights[$i];
        }

        $check = $sum % 11;
        $expected = $check === 10 ? 'X' : (string) $check;

        return $value[8] === $expected;
    }
}
