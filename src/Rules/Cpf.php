<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a Brazilian CPF (Cadastro de Pessoas Físicas).
 * Used as the individual taxpayer registry number in Brazil.
 */
class Cpf implements ValidationRule
{
    /**
     * Runs CPF validation on length and both check digits.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = preg_replace('/\D/', '', (string) $value) ?? '';

        $state = strlen($value) === 11
            && ! preg_match('/^(\d)\1{10}$/', $value)
            && $this->checkDigits($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    /**
     * Verifies both CPF check digits with the official weights.
     */
    private function checkDigits(string $value): bool
    {
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $value[$i] * (10 - $i);
        }
        $d1 = ($sum * 10) % 11;
        $d1 = $d1 === 10 ? 0 : $d1;

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += (int) $value[$i] * (11 - $i);
        }
        $d2 = ($sum * 10) % 11;
        $d2 = $d2 === 10 ? 0 : $d2;

        return (int) $value[9] === $d1 && (int) $value[10] === $d2;
    }
}
