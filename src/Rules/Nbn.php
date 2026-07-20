<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an NBN (National Bibliography Number).
 * Used by national libraries to identify bibliographic resources.
 */
class Nbn implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs NBN validation for urn:nbn: form or plain national forms.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);

        $state = $this->isUrnNbn($value) || $this->isPlainNbn($value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.nbn');
        }
    }

    /**
     * Checks the standard urn:nbn:<country>:... representation.
     */
    private function isUrnNbn(string $value): bool
    {
        return (bool) preg_match(
            '/^urn:nbn:[a-z]{2}[a-z0-9\-]*(:[a-z0-9()+,\-.:=@;$_!*\'%\/?#]+)+$/i',
            $value
        );
    }

    /**
     * Checks a plain NBN string that is not another URN scheme.
     */
    private function isPlainNbn(string $value): bool
    {
        if (preg_match('/^urn:/i', $value)) {
            return false;
        }

        return (bool) preg_match('/^[A-Za-z]{2}[A-Za-z0-9\-\.\/:]{2,}$/', $value);
    }
}
