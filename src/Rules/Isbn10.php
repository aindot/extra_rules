<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Isbn10 implements ValidationRule
{
    private int $length = 10;

    private int $mod = 11;

    private array $weight = [
        10, 9, 8, 7, 6, 5, 4, 3, 2,
    ];

    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = str_replace('-', '', (string) $value);
        $value = str_replace(' ', '', (string) $value);

        $state =
        $this->hasValidLength($value)
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

        foreach (str_split($id) as $index => $digit) {

            if (is_numeric($digit)) {
                $sum += (10 - $index) * $digit;
            }

            if (strtolower($digit) === 'x') {
                $sum += 10;
            }
        }

        $digit = (11 - ($sum % 11)) % $this->mod;

        if ($digit === 0 || $digit === 10) {
            return true;
        }

        return false;
    }
}
