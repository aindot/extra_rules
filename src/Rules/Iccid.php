<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Iccid implements ValidationRule
{
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace(['-', ' '], '', (string) $value);

        $state = is_numeric($value)
            && $this->hasValidLength($value)
            && $this->hasValidPrefix($value)
            && $this->checkChecksum($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    private function hasValidLength(string $id): bool
    {
        $length = strlen($id);

        return $length >= 18 && $length <= 22;
    }

    private function hasValidPrefix(string $id): bool
    {
        return str_starts_with($id, '89');
    }

    private function checkChecksum(string $value): bool
    {
        $str = '';
        foreach (str_split(strrev($value)) as $i => $d) {
            $str .= $i % 2 !== 0 ? $d * 2 : $d;
        }

        return array_sum(str_split($str)) % 10 === 0;
    }
}
