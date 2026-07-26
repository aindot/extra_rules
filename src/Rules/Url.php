<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a URL with http/https/ftp schemes and optional host/path/query/fragment.
 */
class Url implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs URL validation with strict scheme checking.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);

        $state = (bool) preg_match(
            '/^(?:https?:\/\/|ftp:\/\/)?(?:[a-zA-Z0-9.-]+(?:\.[a-zA-Z]{2,})+)\/?(?:[^\s"\']*)?$/i',
            $value
        );

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.url');
        }
    }
}
