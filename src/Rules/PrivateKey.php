<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates PEM private key blocks.
 * Used for RSA, EC, DSA, OpenSSH, PKCS#8, encrypted, and PGP private keys.
 */
class PrivateKey implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs private key block validation with matching BEGIN/END labels.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);

        $state = (bool) preg_match(
            '/^-----BEGIN ((?:[A-Z0-9]+ )?PRIVATE KEY(?: BLOCK)?)-----[\r\n]+[A-Za-z0-9+\/=\r\n]+-----END \1-----$/',
            $value
        );

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.private_key');
        }
    }
}
