<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a MAC (Media Access Control) address.
 * Used to uniquely identify a network interface hardware address.
 */
class MacAddress implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs MAC address validation for colon, hyphen, or bare hex forms.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);

        $state = (bool) preg_match(
            '/^([0-9A-Fa-f]{2}([:-])){5}[0-9A-Fa-f]{2}$|^[0-9A-Fa-f]{12}$/',
            $value
        );

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.mac_address');
        }
    }
}
