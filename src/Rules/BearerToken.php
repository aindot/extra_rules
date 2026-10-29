<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates an HTTP Bearer authorization token.
 * Used to check the "Bearer <token>" scheme and token character set.
 */
class BearerToken implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs bearer token validation for the Authorization header shape.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);

        $state = (bool) preg_match(
            '/\ABearer\s+[A-Za-z0-9\-._~+\/]+=*\z/i',
            $value
        );

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.bearer_token');
        }
    }
}
