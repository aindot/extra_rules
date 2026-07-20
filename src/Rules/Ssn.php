<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a US Social Security Number format.
 * Used for structural SSN checks only, not membership verification.
 */
class Ssn implements ValidationRule
{
    /**
     * Runs SSN format validation and rejects known invalid area/group ranges.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);

        if (! preg_match('/^(\d{3})-?(\d{2})-?(\d{4})$/', $value, $m)) {
            $fail('the :attribute is invalid');

            return;
        }

        $area = (int) $m[1];
        $group = (int) $m[2];
        $serial = (int) $m[3];

        $state = $area !== 0
            && $area !== 666
            && $area < 900
            && $group !== 0
            && $serial !== 0;

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }
}
