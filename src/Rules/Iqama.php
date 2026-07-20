<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a Saudi Iqama / National ID number.
 * Used for 10-digit Saudi citizen (1…) and resident Iqama (2…) identifiers.
 */
class Iqama implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs Iqama validation on prefix, length, and official checksum.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = preg_replace('/\D/', '', (string) $value) ?? '';

        $state = (bool) preg_match('/^[12]\d{9}$/', $value)
            && $this->checkChecksum($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.iqama');
        }
    }

    /**
     * Verifies the Saudi ID checksum by doubling digits in even positions.
     */
    private function checkChecksum(string $value): bool
    {
        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $digit = (int) $value[$i];

            if ($i % 2 === 0) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit = intdiv($digit, 10) + ($digit % 10);
                }
            }

            $sum += $digit;
        }

        return $sum % 10 === 0;
    }
}
