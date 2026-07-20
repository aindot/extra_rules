<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an Egyptian National ID number.
 * Used as the 14-digit civil identity number encoding birth data and governorate.
 */
class EgyptianNid implements ValidationRule
{
    use RejectsInvalidValue;

    private array $governorates = [
        '01', '02', '03', '04', '11', '12', '13', '14', '15', '16', '17', '18', '19',
        '21', '22', '23', '24', '25', '26', '27', '28', '29',
        '31', '32', '33', '34', '35', '88',
    ];

    /**
     * Runs Egyptian NID validation on length, century, date, and governorate.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = preg_replace('/\D/', '', (string) $value) ?? '';

        $state = strlen($value) === 14
            && ctype_digit($value)
            && $this->hasValidCentury($value)
            && $this->hasValidBirthDate($value)
            && $this->hasValidGovernorate($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.egyptian_nid');
        }
    }

    /**
     * Checks century digit 2 (1900s) or 3 (2000s).
     */
    private function hasValidCentury(string $value): bool
    {
        return in_array($value[0], ['2', '3'], true);
    }

    /**
     * Checks that digits 2-7 form a real birth date for the century.
     */
    private function hasValidBirthDate(string $value): bool
    {
        $year = ((int) $value[0] === 2 ? 1900 : 2000) + (int) substr($value, 1, 2);
        $month = (int) substr($value, 3, 2);
        $day = (int) substr($value, 5, 2);

        return checkdate($month, $day, $year);
    }

    /**
     * Checks that digits 8-9 are a known governorate or abroad code.
     */
    private function hasValidGovernorate(string $value): bool
    {
        return in_array(substr($value, 7, 2), $this->governorates, true);
    }
}
