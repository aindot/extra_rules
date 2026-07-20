<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a payment card number with the Luhn algorithm.
 * Used to check credit and debit card PAN structure before processing.
 */
class CreditCard implements ValidationRule
{
    /**
     * Runs card number validation on length and Luhn check digit.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace([' ', '-'], '', (string) $value);

        $state = ctype_digit($value)
            && strlen($value) >= 13
            && strlen($value) <= 19
            && $this->luhn($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    /**
     * Verifies the card number using the Luhn algorithm.
     */
    private function luhn(string $value): bool
    {
        $str = '';
        foreach (str_split(strrev($value)) as $i => $d) {
            $str .= $i % 2 !== 0 ? $d * 2 : $d;
        }

        return array_sum(str_split($str)) % 10 === 0;
    }
}
