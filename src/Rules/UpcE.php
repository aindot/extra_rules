<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a UPC-E barcode by expanding it to UPC-A.
 * Used on small retail packages as a compressed 6- or 8-digit UPC form.
 */
class UpcE implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs UPC-E validation after normalizing and expanding to UPC-A.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace(['-', ' '], '', (string) $value);

        if (! ctype_digit($value)) {
            $this->reject($fail, 'extra-rules::validation.upc_e');

            return;
        }

        $normalized = $this->normalize($value);
        $state = $normalized !== null && $this->isValidUpcE($normalized);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.upc_e');
        }
    }

    /**
     * Normalizes 6/7/8 digit input into number-system + 6-body + check form.
     */
    private function normalize(string $value): ?string
    {
        $length = strlen($value);

        if ($length === 6) {
            $check = $this->upcCheckDigit('0'.$this->expandBody($value));

            return '0'.$value.$check;
        }

        if ($length === 7) {
            $numberSystem = $value[0];
            $body = substr($value, 1, 6);
            $check = $this->upcCheckDigit($numberSystem.$this->expandBody($body));

            return $numberSystem.$body.$check;
        }

        if ($length === 8) {
            return $value;
        }

        return null;
    }

    /**
     * Validates number system, expands the body, and checks the UPC digit.
     */
    private function isValidUpcE(string $value): bool
    {
        if (! in_array($value[0], ['0', '1'], true)) {
            return false;
        }

        $body = substr($value, 1, 6);
        $check = $value[7];
        $expected = $this->upcCheckDigit($value[0].$this->expandBody($body));

        return $check === $expected;
    }

    /**
     * Expands a 6-digit UPC-E body into the 10-digit UPC-A middle segment.
     */
    private function expandBody(string $body): string
    {
        $digits = str_split($body);
        $last = $digits[5];

        if ($last >= '0' && $last <= '2') {
            return $digits[0].$digits[1].$last.'0000'.$digits[2].$digits[3].$digits[4];
        }

        if ($last === '3') {
            return $digits[0].$digits[1].$digits[2].'00000'.$digits[3].$digits[4];
        }

        if ($last === '4') {
            return $digits[0].$digits[1].$digits[2].$digits[3].'00000'.$digits[4];
        }

        return $digits[0].$digits[1].$digits[2].$digits[3].$digits[4].'0000'.$last;
    }

    /**
     * Calculates a UPC-A check digit for an 11-digit body.
     */
    private function upcCheckDigit(string $body11): string
    {
        $sum = 0;

        for ($i = 0; $i < 11; $i++) {
            $sum += (int) $body11[$i] * ($i % 2 === 0 ? 3 : 1);
        }

        return (string) ((10 - ($sum % 10)) % 10);
    }
}
