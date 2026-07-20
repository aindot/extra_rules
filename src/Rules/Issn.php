<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an 8-character ISSN (International Standard Serial Number).
 * Used to identify serial publications such as journals and magazines.
 */
class Issn implements ValidationRule
{
    use RejectsInvalidValue;

    private int $length = 8;

    /**
     * Runs ISSN validation on length, format, and check character.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace('-', '', (string) $value);
        $value = str_replace(' ', '', $value);
        $value = strtoupper($value);

        $state = $this->hasValidLength($value)
            && $this->hasValidFormat($value)
            && $this->checkChecksum($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.issn');
        }
    }

    /**
     * Checks that the value has exactly 8 characters.
     */
    private function hasValidLength(string $id): bool
    {
        return $this->length === strlen($id);
    }

    /**
     * Checks that the value is 7 digits plus a final digit or X.
     */
    private function hasValidFormat(string $id): bool
    {
        return (bool) preg_match('/^\d{7}[\dX]$/', $id);
    }

    /**
     * Verifies the ISSN check character with mod 11 weights.
     */
    private function checkChecksum(string $id): bool
    {
        $sum = 0;

        for ($i = 0; $i < 7; $i++) {
            $sum += (8 - $i) * (int) $id[$i];
        }

        $remainder = $sum % 11;
        $check = (11 - $remainder) % 11;
        $expected = $check === 10 ? 'X' : (string) $check;

        return $id[7] === $expected;
    }
}
