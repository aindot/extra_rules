<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an IMSI (International Mobile Subscriber Identity).
 * Used to identify a mobile network subscriber on a SIM/USIM.
 */
class Imsi implements ValidationRule
{
    /**
     * Runs IMSI validation on length and MCC range.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace(['-', ' '], '', (string) $value);

        $state = is_numeric($value)
            && $this->hasValidLength($value)
            && $this->hasValidMcc($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    /**
     * Checks that the IMSI length is 14 or 15 digits.
     */
    private function hasValidLength(string $id): bool
    {
        $length = strlen($id);

        return $length >= 14 && $length <= 15;
    }

    /**
     * Checks that the first three digits form a plausible MCC.
     */
    private function hasValidMcc(string $id): bool
    {
        $mcc = (int) substr($id, 0, 3);

        return $mcc >= 200 && $mcc <= 999;
    }
}
