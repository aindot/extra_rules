<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an email address according to RFC 5322 standards.
 * Checks local-part and domain structure for valid email format.
 */
class Email implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Validates email format following RFC 5322.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $this->reject($fail, 'extra-rules::validation.email');
            return;
        }

        $value = trim($value);

        if ($value === '') {
            $this->reject($fail, 'extra-rules::validation.email');
            return;
        }

        if (!str_contains($value, '@')) {
            $this->reject($fail, 'extra-rules::validation.email');
            return;
        }

        [$localPart, $domain] = explode('@', $value, 2);

        if (!$this->validateLocalPart($localPart) || !$this->validateDomain($domain)) {
            $this->reject($fail, 'extra-rules::validation.email');
        }
    }

    /**
     * Validates the local-part (before @).
     */
    private function validateLocalPart(string $localPart): bool
    {
        if ($localPart === '') {
            return false;
        }

        if (strlen($localPart) > 64) {
            return false;
        }

        if ($localPart[0] === '.' || $localPart[-1] === '.') {
            return false;
        }

        if (str_contains($localPart, '..')) {
            return false;
        }

        $quoted = false;
        $quotedString = false;
        $atom = '';
        $lastWasDot = false;

        for ($i = 0; $i < strlen($localPart); $i++) {
            $char = $localPart[$i];

            if ($char === '"') {
                $quoted = !$quoted;
                if ($quoted) {
                    $quotedString = true;
                }
                continue;
            }

            if ($quoted) {
                if ($char === '\\' && $i + 1 < strlen($localPart)) {
                    $i++;
                    continue;
                }

                if ($char < chr(32) || $char === chr(127)) {
                    return false;
                }
                continue;
            }

            if ($char === '.') {
                if ($lastWasDot) {
                    return false;
                }
                $lastWasDot = true;
                continue;
            }

            $lastWasDot = false;

            if ($char === ' ') {
                continue;
            }

            if (!ctype_alnum($char) && !str_contains('!#$%&\'*+-/=?^_`{|}~', $char)) {
                return false;
            }
        }

        if ($lastWasDot) {
            return false;
        }

        return true;
    }

    /**
     * Validates the domain (after @).
     */
    private function validateDomain(string $domain): bool
    {
        if ($domain === '') {
            return false;
        }

        if (strlen($domain) > 253) {
            return false;
        }

        if ($domain[0] === '.' || $domain[-1] === '.') {
            return false;
        }

        $labels = explode('.', $domain);

        foreach ($labels as $label) {
            if ($label === '') {
                return false;
            }

            if (strlen($label) > 63) {
                return false;
            }

            if ($label[0] === '-' || $label[-1] === '-') {
                return false;
            }

            if (!preg_match('/^[a-zA-Z0-9-]+$/', $label)) {
                return false;
            }
        }

        $tld = end($labels);

        if (strlen($tld) < 2) {
            return false;
        }

        return true;
    }
}
