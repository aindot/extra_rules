<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an IPv4 address using strict octet validation.
 */
class IPv4 implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs IPv4 validation with strict octet range checking.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);

        $state = filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.ipv4');
        }
    }
}
