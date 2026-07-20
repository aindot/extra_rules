<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Issn implements ValidationRule
{
    private int $length = 8;

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
        return (bool) preg_match('/^\d{7}[\dX]$/', $id);
    }

    private function checkChecksum(string $id): bool
    {
        $sum = 0;

        for ($i = 0; $i < 7; $i++) {
            $sum += (8 - $i) * (int) $id[$i];
        }

        $remainder = $sum % 11;
        $check = (11 - $remainder) % 11;
        $expected = $check === 10 ? 'X' : (string) $check;

        return $id[7] === $expected;
    }
}
