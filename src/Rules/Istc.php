<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Istc implements ValidationRule
{
    private int $length = 16;

    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtoupper((string) $value);
        $value = str_replace(['-', ' '], '', $value);

        $state = $this->hasValidLength($value)
            && $this->hasValidFormat($value)
            && $this->checkChecksum($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    private function hasValidLength(string $id): bool
    {
        return $this->length === strlen($id);
    }

    private function hasValidFormat(string $id): bool
    {
        return (bool) preg_match('/^[0-9A-F]{16}$/', $id);
    }

    private function checkChecksum(string $id): bool
    {
        $check = 0;

        for ($i = 0; $i < $this->length; $i++) {
            $check = (3 * $check + hexdec($id[$i])) % 17;
        }

        return $check === 1;
    }
}
