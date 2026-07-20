<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a 10-character ISBN-10 book number.
 * Used for older book identifiers issued before ISBN-13.
 */
class Isbn10 implements ValidationRule
{
    use RejectsInvalidValue;

    private int $length = 10;

    private int $mod = 11;

    private array $weight = [
        10, 9, 8, 7, 6, 5, 4, 3, 2,
    ];

    /**
     * Runs ISBN-10 validation on length and check character.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace('-', '', (string) $value);
        $value = str_replace(' ', '', (string) $value);

        $state
        = $this->hasValidLength($value)
            && $this->checkChecksum($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.isbn10');
        }
    }

    /**
     * Checks that the value has exactly 10 characters.
     */
    private function hasValidLength(string $id): bool
    {
        return $this->length === strlen($id);
    }

    /**
     * Verifies the ISBN-10 check digit (0-9 or X) with mod 11.
     */
    private function checkChecksum(string $id): bool
    {
        $sum = 0;

        foreach (str_split($id) as $index => $digit) {
            if (is_numeric($digit)) {
                $sum += (10 - $index) * $digit;
            }

            if (strtolower($digit) === 'x') {
                $sum += 10;
            }
        }

        $digit = (11 - ($sum % 11)) % $this->mod;

        if ($digit === 0 || $digit === 10) {
            return true;
        }

        return false;
    }
}
