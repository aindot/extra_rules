<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a 16-character ISTC (International Standard Text Code).
 * Used to identify textual works shared across different editions and formats.
 */
class Istc implements ValidationRule
{
    use RejectsInvalidValue;

    private int $length = 16;

    /**
     * Runs ISTC validation on hex format and ISO 7064 Mod 16-3 check.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtoupper((string) $value);
        $value = str_replace(['-', ' '], '', $value);

        $state = $this->hasValidLength($value)
            && $this->hasValidFormat($value)
            && $this->checkChecksum($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.istc');
        }
    }

    /**
     * Checks that the value has exactly 16 characters.
     */
    private function hasValidLength(string $id): bool
    {
        return $this->length === strlen($id);
    }

    /**
     * Checks that the value is 16 hexadecimal characters.
     */
    private function hasValidFormat(string $id): bool
    {
        return (bool) preg_match('/^[0-9A-F]{16}$/', $id);
    }

    /**
     * Verifies the ISTC check character with ISO 7064 Mod 16-3.
     */
    private function checkChecksum(string $id): bool
    {
        $check = 0;

        for ($i = 0; $i < $this->length; $i++) {
            $check = (3 * $check + hexdec($id[$i])) % 17;
        }

        return $check === 1;
    }
}
