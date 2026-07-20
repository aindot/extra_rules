<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Isni implements ValidationRule
{
    private int $length = 16;

    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace('-', '', (string) $value);
        $value = str_replace(' ', '', $value);
        $value = strtoupper($value);

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
        return (bool) preg_match('/^\d{15}[\dX]$/', $id);
    }

    private function checkChecksum(string $id): bool
    {
        $check = 0;

        for ($i = 0; $i < $this->length; $i++) {
            $n = $id[$i] === 'X' ? 10 : (int) $id[$i];
            $check = (2 * $check + $n) % 11;
        }

        return $check === 1;
    }
}
