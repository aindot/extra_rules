<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Urn implements ValidationRule
{
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
