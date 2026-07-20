<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a 14-digit ITF-14 barcode number.
 * Used on shipping cartons and matches the GTIN-14 check-digit structure.
 */
class Itf14 implements ValidationRule
{
    use RejectsInvalidValue;

    private int $length = 14;

    /**
     * Runs ITF-14 validation on length and GS1 check digit.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace(['-', ' '], '', (string) $value);

        $state = ctype_digit($value)
            && strlen($value) === $this->length
            && $this->checkChecksum($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.itf14');
        }
    }

    /**
     * Verifies the ITF-14 check digit using GS1 weights from the left.
     */
    private function checkChecksum(string $id): bool
    {
        $sum = 0;

        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $id[$i] * ($i % 2 === 0 ? 3 : 1);
        }

        return (int) $id[13] === ((10 - ($sum % 10)) % 10);
    }
}
