<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Imsi implements ValidationRule
{
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

    private function hasValidLength(string $id): bool
    {
        $length = strlen($id);

        return $length >= 14 && $length <= 15;
    }

    private function hasValidMcc(string $id): bool
    {
        $mcc = (int) substr($id, 0, 3);

        return $mcc >= 200 && $mcc <= 999;
    }
}
