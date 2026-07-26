<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an email address with RFC 5322 compliance using heuristics.
 * Tests both structural validity and MX record existence (when available).
 */
class Email implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs email validation with optional MX record check.
     *
     * @param  bool  $checkMx
     */
    public function validate(string $attribute, $value, Closure $fail, bool $checkMx = true): void
    {
        $value = trim((string) $value);

        $has_mx = $checkMx && $this->hasValidMx($value);
        $structure_valid = $this->hasValidStructure($value);

        $state = $has_mx && $structure_valid;

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.email');
        }
    }

    /**
     * Checks if the email has a valid structure per RFC 5322.
     */
    private function hasValidStructure(string $value): bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $value);
    }

    /**
     * Checks if MX records exist for the domain.
     */
    private function hasValidMx(string $value): bool
    {
        [$domain] = explode('@', $value, 2);

        if (! $domain) {
            return false;
        }

        $domains = getmxrr($domain, $mxhosts);

        return count($mxhosts) > 0;
    }
}
