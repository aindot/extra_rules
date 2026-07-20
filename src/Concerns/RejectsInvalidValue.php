<?php

namespace Aindot\ExtraRules\Concerns;

use Closure;

trait RejectsInvalidValue
{
    /**
     * Fails validation using a translated package message.
     */
    protected function reject(Closure $fail, ?string $key = null): void
    {
        $result = $fail($key ?? 'extra-rules::validation.invalid');

        if (is_object($result) && method_exists($result, 'translate')) {
            $result->translate();
        }
    }
}
