<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an 18-digit SSCC (Serial Shipping Container Code).
 * Used to identify logistics units such as pallets and shipments.
 */
class Sscc implements ValidationRule
{
    use RejectsInvalidValue;

    private int $length = 18;

    /**
     * Runs SSCC validation on length and GS1 check digit.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace(['-', ' '], '', (string) $value);

        $state = ctype_digit($value)
            && strlen($value) === $this->length
            && $this->checkChecksum($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.sscc');
        }
    }

    /**
     * Verifies the SSCC check digit using GS1 weights from the left.
     */
    private function checkChecksum(string $id): bool
    {
        $sum = 0;
        for ($i = 0; $i < 17; $i++) {
            $sum += (int) $id[$i] * ($i % 2 === 0 ? 3 : 1);
        }

        return (int) $id[17] === ((10 - ($sum % 10)) % 10);
    }
}
