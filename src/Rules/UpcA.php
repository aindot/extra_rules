<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a 12-digit UPC-A barcode.
 * Used primarily in North America to identify retail products.
 */
class UpcA implements ValidationRule
{
    use RejectsInvalidValue;

    private int $length = 12;

    /**
     * Runs UPC-A validation on length and check digit.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace(['-', ' '], '', (string) $value);

        $state = ctype_digit($value)
            && strlen($value) === $this->length
            && $this->checkChecksum($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.upc_a');
        }
    }

    /**
     * Verifies the UPC-A check digit with odd-position weight 3.
     */
    private function checkChecksum(string $id): bool
    {
        $sum = 0;
        for ($i = 0; $i < 11; $i++) {
            $sum += (int) $id[$i] * ($i % 2 === 0 ? 3 : 1);
        }

        return (int) $id[11] === ((10 - ($sum % 10)) % 10);
    }
}
