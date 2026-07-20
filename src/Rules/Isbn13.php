<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a 13-digit ISBN-13 book number.
 * Used to identify books and related media in modern publishing.
 */
class Isbn13 implements ValidationRule
{
    private int $length = 13;

    private int $mod = 10;

    private array $weight = [
        1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3,
    ];

    /**
     * Runs ISBN-13 validation on length, 978/979 prefix, and check digit.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace('-', '', (string) $value);
        $value = str_replace(' ', '', (string) $value);

        $state = is_numeric($value)
            && $this->hasValidLength($value)
            && $this->checkChecksum($value)
            && $this->correctPrefix($value);

        if (! $state) {
            $fail('the :attribute is invalid');
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
     * Checks that the ISBN starts with the bookland prefix 978 or 979.
     */
    private function correctPrefix(string $id): bool
    {
        return str_starts_with($id, '978') || str_starts_with($id, '979');
    }

    /**
     * Verifies the ISBN-13 check digit using EAN-13 weights.
     */
    private function checkChecksum(string $id): bool
    {
        $sum = 0;

        for ($x = 0; $x < $this->length - 1; $x++) {
            $sum += substr($id, $x, 1) * $this->weight[$x];
        }

        $digit = ($this->mod - ($sum % $this->mod) % $this->mod);

        if ($digit > 9) {
            $digit = $digit % 10;
        }

        return substr($id, -1) == $digit ? true : false;
    }
}
