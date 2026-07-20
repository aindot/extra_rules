<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an ISWC (International Standard Work Code for compositions).
 * Used to uniquely identify compositions, not recordings.
 */
class Iswc implements ValidationRule
{
    /**
     * Runs ISWC validation on T-prefixed format and check digit.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtoupper((string) $value);
        $value = str_replace(['-', '.', ' '], '', $value);

        $state = $this->hasValidFormat($value)
            && $this->checkChecksum($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    /**
     * Checks that the value matches T followed by 10 digits.
     */
    private function hasValidFormat(string $id): bool
    {
        return (bool) preg_match('/^T\d{10}$/', $id);
    }

    /**
     * Verifies the ISWC check digit from the nine work identifier digits.
     */
    private function checkChecksum(string $id): bool
    {
        $digits = substr($id, 1, 9);
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += ($i + 1) * (int) $digits[$i];
        }

        $expected = (string) ($sum % 10);

        return $id[10] === $expected;
    }
}
