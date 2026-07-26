<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an IPv6 address with support for compressed notation (::).
 */
class IPv6 implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs IPv6 validation with support for mapped IPv4 addresses.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);

        $state = filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.ipv6');
        }
    }
}
