<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a UAE Emirates ID number.
 * Used as the official 15-digit identity number for UAE residents.
 */
class EmiratesId implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs Emirates ID validation on 784 prefix, length, and Luhn check.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = preg_replace('/\D/', '', (string) $value) ?? '';

        $state = strlen($value) === 15
            && str_starts_with($value, '784')
            && $this->luhn($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.emirates_id');
        }
    }

    /**
     * Verifies the Emirates ID using the Luhn algorithm.
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
