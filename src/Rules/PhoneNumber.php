<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a phone number with international support (E.164, national formats).
 * Handles various separators: spaces, dashes, parentheses, plus signs.
 */
class PhoneNumber implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs phone number validation with optional country code.
     *
     * @param  string|null  $countryCode ISO 3166-1 alpha-2 country code
     */
    public function validate(string $attribute, $value, Closure $fail, ?string $countryCode = null): void
    {
        $value = trim((string) $value);

        // Remove common separators
        $cleaned = preg_replace('/[\s\-()\.\+]/', '', $value);

        // Must start with optional + and have valid length
        $hasPlus = $value[0] === '+';
        $numericOnly = ctype_digit($cleaned);

        if (! $hasPlus && $countryCode === null) {
            // Without country code, must be valid length numeric
            $length = strlen($cleaned);
            $state = $numericOnly && $length >= 7 && $length <= 15;
        } else {
            // With country code prefix or E.164
            $state = $numericOnly && strlen($cleaned) >= 10 && strlen($cleaned) <= 16;
        }

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.phone_number');
        }
    }
}
