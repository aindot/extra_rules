<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a MEID (Mobile Equipment Identifier).
 * Used to uniquely identify CDMA mobile devices.
 */
class Meid implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs MEID validation for 14-hex or 18-decimal forms with Luhn.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtoupper(str_replace(['-', ' ', ':'], '', (string) $value));

        $state = $this->isValidHex($value) || $this->isValidDecimal($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.meid');
        }
    }

    /**
     * Checks a 14-character hexadecimal MEID with Luhn mod 16.
     */
    private function isValidHex(string $value): bool
    {
        if (! preg_match('/^[0-9A-F]{14}$/', $value)) {
            return false;
        }

        $sum = 0;
        $alt = false;
        for ($i = 13; $i >= 0; $i--) {
            $n = hexdec($value[$i]);
            if ($alt) {
                $n *= 2;
                if ($n > 15) {
                    $n = ($n % 16) + intdiv($n, 16);
                }
            }
            $sum += $n;
            $alt = ! $alt;
        }

        return $sum % 16 === 0;
    }

    /**
     * Checks an 18-digit decimal MEID with Luhn mod 10.
     */
    private function isValidDecimal(string $value): bool
    {
        if (! preg_match('/^\d{18}$/', $value)) {
            return false;
        }

        return $this->luhn10($value);
    }

    /**
     * Verifies a numeric value with the standard Luhn algorithm.
     */
    private function luhn10(string $value): bool
    {
        $str = '';
        foreach (str_split(strrev($value)) as $i => $d) {
            $str .= $i % 2 !== 0 ? $d * 2 : $d;
        }

        return array_sum(str_split($str)) % 10 === 0;
    }
}
