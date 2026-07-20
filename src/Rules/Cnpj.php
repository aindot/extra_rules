<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a Brazilian CNPJ (Cadastro Nacional da Pessoa Jurídica).
 * Used as the company taxpayer registry number in Brazil.
 */
class Cnpj implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs CNPJ validation on length and both check digits.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = preg_replace('/\D/', '', (string) $value) ?? '';

        $state = strlen($value) === 14
            && ! preg_match('/^(\d)\1{13}$/', $value)
            && $this->checkDigits($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.cnpj');
        }
    }

    /**
     * Verifies both CNPJ check digits with the official weights.
     */
    private function checkDigits(string $value): bool
    {
        $w1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $w2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $value[$i] * $w1[$i];
        }
        $d1 = $sum % 11;
        $d1 = $d1 < 2 ? 0 : 11 - $d1;

        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $value[$i] * $w2[$i];
        }
        $d2 = $sum % 11;
        $d2 = $d2 < 2 ? 0 : 11 - $d2;

        return (int) $value[12] === $d1 && (int) $value[13] === $d2;
    }
}
