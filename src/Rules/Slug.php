<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a URL-friendly slug string.
 * Used for lowercase paths made of words separated by single hyphens.
 */
class Slug implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs slug validation for lowercase alphanumeric hyphenated form.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $state = (bool) preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', (string) $value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.slug');
        }
    }
}
