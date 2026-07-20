<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an IBAN (International Bank Account Number).
 * Used to identify bank accounts across national borders.
 */
class Iban implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs IBAN validation on format and ISO 13616 mod-97 checksum.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtoupper(str_replace(' ', '', (string) $value));

        $state = $this->hasValidFormat($value) && $this->checkChecksum($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.iban');
        }
    }

    /**
     * Checks country code, length bounds, and alphanumeric BBAN structure.
     */
    private function hasValidFormat(string $value): bool
    {
        return (bool) preg_match('/^[A-Z]{2}\d{2}[A-Z0-9]{11,30}$/', $value);
    }

    /**
     * Verifies the IBAN check digits using mod 97 equal to 1.
     */
    private function checkChecksum(string $value): bool
    {
        $rearranged = substr($value, 4).substr($value, 0, 4);
        $numeric = '';

        for ($i = 0; $i < strlen($rearranged); $i++) {
            $char = $rearranged[$i];
            $numeric .= ctype_alpha($char) ? (string) (ord($char) - 55) : $char;
        }

        $remainder = 0;
        for ($i = 0; $i < strlen($numeric); $i++) {
            $remainder = ($remainder * 10 + (int) $numeric[$i]) % 97;
        }

        return $remainder === 1;
    }
}
