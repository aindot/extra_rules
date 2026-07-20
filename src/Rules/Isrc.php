<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Isrc implements ValidationRule
{
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtoupper((string) $value);
        $value = str_replace(['-', ' ', ':'], '', $value);

        $state = (bool) preg_match('/^[A-Z]{2}[A-Z0-9]{3}\d{7}$/', $value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }
}
