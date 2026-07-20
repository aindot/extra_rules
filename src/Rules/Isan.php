<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Isan implements ValidationRule
{
    private const ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = strtoupper((string) $value);
        $value = preg_replace('/^ISAN\s*/', '', $value) ?? $value;
        $value = str_replace(['-', ' '], '', $value);

        $state = $this->isValidRoot($value) || $this->isValidFull($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    private function isValidRoot(string $value): bool
    {
        if (! preg_match('/^[0-9A-F]{16}[0-9A-Z]$/', $value)) {
            return false;
        }

        return $this->mod3736(substr($value, 0, 17));
    }

    private function isValidFull(string $value): bool
    {
        if (! preg_match('/^[0-9A-F]{16}[0-9A-Z][0-9A-F]{8}[0-9A-Z]$/', $value)) {
            return false;
        }

        $root = substr($value, 0, 16);
        $check1 = $value[16];
        $version = substr($value, 17, 8);
        $check2 = $value[25];

        return $this->mod3736($root.$check1)
            && $this->mod3736($root.$version.$check2);
    }

    private function mod3736(string $number): bool
    {
        $modulus = 36;
        $check = intdiv($modulus, 2);

        for ($i = 0; $i < strlen($number); $i++) {
            $n = strpos(self::ALPHABET, $number[$i]);
            if ($n === false) {
                return false;
            }
            $check = ((($check ?: $modulus) * 2) % ($modulus + 1) + $n) % $modulus;
        }

        return $check === 1;
    }
}
