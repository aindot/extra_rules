<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ImeiSv implements ValidationRule
{
    private int $length = 16;

    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace(['-', ' '], '', (string) $value);

        $state = is_numeric($value)
            && $this->hasValidLength($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    private function hasValidLength(string $id): bool
    {
        return $this->length === strlen($id);
    }
}
