<?php

namespace Aindot\ExtraRules\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Nbn implements ValidationRule
{
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);

        $state = $this->isUrnNbn($value) || $this->isPlainNbn($value);

        if (! $state) {
            $fail('the :attribute is invalid');
        }
    }

    private function isUrnNbn(string $value): bool
    {
        return (bool) preg_match(
            '/^urn:nbn:[a-z]{2}[a-z0-9\-]*(:[a-z0-9()+,\-.:=@;$_!*\'%\/?#]+)+$/i',
            $value
        );
    }

    private function isPlainNbn(string $value): bool
    {
        if (preg_match('/^urn:/i', $value)) {
            return false;
        }

        return (bool) preg_match('/^[A-Za-z]{2}[A-Za-z0-9\-\.\/:]{2,}$/', $value);
    }
}
