<?php

namespace Aindot\ExtraRules\Rules;

use Aindot\ExtraRules\Concerns\RejectsInvalidValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a CSS hexadecimal color value.
 * Used for #RGB, #RRGGBB, and optional alpha #RRGGBBAA forms.
 */
class HexColor implements ValidationRule
{
    use RejectsInvalidValue;

    /**
     * Runs hex color validation with an optional leading #.
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $value = trim((string) $value);

        $state = (bool) preg_match('/^#?([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6}|[0-9A-Fa-f]{8})$/', $value);

        if (! $state) {
            $this->reject($fail, 'extra-rules::validation.hex_color');
        }
    }
}
