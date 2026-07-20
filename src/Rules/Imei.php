<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a 15-digit IMEI (International Mobile Equipment Identity).
 * Used to uniquely identify a mobile handset or cellular device.
 */
class Imei implements ValidationRule
{
    private int $length = 15;

    /**
     * Runs IMEI validation on length and Luhn check digit.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $state = is_numeric($value)
            && $this->hasValidLength($value)
            && $this->checkChecksum($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    /**
     * Checks that the value has exactly 15 digits.
     */
    private function hasValidLength(string $id): bool
    {
        return $this->length === strlen($id);
    }

    /**
     * Verifies the IMEI using the Luhn algorithm.
     */
    private function checkChecksum(string $value): bool
    {
        $str = '';
        foreach (
            str_split(strrev((string) $value)) as $i => $d
        ) {
            // double every alternate digit and find the sum
            $str .= $i % 2 !== 0 ? $d * 2 : $d;
        }

        return array_sum(str_split($str)) % 10 === 0;
    }
}
