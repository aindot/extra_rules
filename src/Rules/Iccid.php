<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an ICCID (Integrated Circuit Card Identifier).
 * Used as the unique serial number printed on a SIM card.
 */
class Iccid implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs ICCID validation on length, 89 prefix, and Luhn check digit.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace(['-', ' '], '', (string) $value);

        $state = is_numeric($value)
            && $this->hasValidLength($value)
            && $this->hasValidPrefix($value)
            && $this->checkChecksum($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.iccid');
        }
    }

    /**
     * Checks that the ICCID length is between 18 and 22 digits.
     */
    private function hasValidLength(string $id): bool
    {
        $length = strlen($id);

        return $length >= 18 && $length <= 22;
    }

    /**
     * Checks that the value starts with the telecom MII prefix 89.
     */
    private function hasValidPrefix(string $id): bool
    {
        return str_starts_with($id, '89');
    }

    /**
     * Verifies the ICCID using the Luhn algorithm.
     */
    private function checkChecksum(string $value): bool
    {
        $str = '';
        foreach (str_split(strrev($value)) as $i => $d) {
            $str .= $i % 2 !== 0 ? $d * 2 : $d;
        }

        return array_sum(str_split($str)) % 10 === 0;
    }
}
