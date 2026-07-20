<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a 16-character ISNI (International Standard Name Identifier).
 * Used to uniquely identify contributors such as authors, artists, and organizations.
 */
class Isni implements ValidationRule
{
    use RejectsInvalidValue;

    private int $length = 16;

    /**
     * Runs ISNI validation on format and ISO 7064 Mod 11-2 check character.
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
            $this->reject($fail, 'extra-rules::validation.isni');
        }
    }

    /**
     * Checks that the value has exactly 16 characters.
     */
    private function hasValidLength(string $id): bool
    {
        return $this->length === strlen($id);
    }

    /**
     * Checks that the value is 15 digits plus a final digit or X.
     */
    private function hasValidFormat(string $id): bool
    {
        return (bool) preg_match('/^\d{15}[\dX]$/', $id);
    }

    /**
     * Verifies the ISNI check character with ISO 7064 Mod 11-2.
     */
    private function checkChecksum(string $id): bool
    {
        $check = 0;

        for ($i = 0; $i < $this->length; $i++) {
            $n = $id[$i] === 'X' ? 10 : (int) $id[$i];
            $check = (2 * $check + $n) % 11;
        }

        return $check === 1;
    }
}
