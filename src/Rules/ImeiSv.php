<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a 16-digit IMEISV (IMEI Software Version).
 * Used to identify a device plus its software version number (SVN).
 */
class ImeiSv implements ValidationRule
{
    private int $length = 16;

    /**
     * Runs IMEISV validation requiring exactly 16 numeric digits.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace(['-', ' '], '', (string) $value);

        $state = is_numeric($value)
            && $this->hasValidLength($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    /**
     * Checks that the value has exactly 16 digits.
     */
    private function hasValidLength(string $id): bool
    {
        return $this->length === strlen($id);
    }
}
