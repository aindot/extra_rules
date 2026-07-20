<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a URN (Uniform Resource Name).
 * Used as a persistent, location-independent resource identifier (urn:nid:nss).
 */
class Urn implements ValidationRule
{
    /**
     * Runs URN validation against the urn:<NID>:<NSS> pattern.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);

        $state = (bool) preg_match(
            '/^urn:[a-z0-9][a-z0-9\-]{0,31}:[a-z0-9()+,\-.:=@;$_!*\'%\/?#]+$/i',
            $value
        );

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }
}
