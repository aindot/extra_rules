<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class KuwaitCivilId implements ValidationRule
{
    private int $length = 12;

    private int $mod = 11;

    private array $weight = [
        2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2,
    ];

    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace('-', '', (string) $value);

        $state = is_numeric($value)
            && $this->hasValidLength($value)
            && $this->checkChecksum($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    private function hasValidLength(string $id): bool
    {
        return $this->length === strlen($id);
    }

    private function checkChecksum(string $id): bool
    {
        $sum = 0;
        for ($x = 0; $x < $this->length - 1; $x++) {
            $sum += substr($id, $x, 1) * $this->weight[$x];
        }

        return substr($id, -1) == ($this->mod - ($sum % $this->mod)) ? true : false;
    }
}
