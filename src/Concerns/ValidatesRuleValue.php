<?php

namespace Aindot\ExtraRules\Concerns;

use Aindot\ExtraRules\RuleType;
use Illuminate\Contracts\Validation\ValidationRule;

trait ValidatesRuleValue
{
    /**
     * Checks whether a value passes the validation rule for the given type.
     */
    protected function passesRule(RuleType $type, mixed $value): bool
    {
        /** @var ValidationRule $rule */
        $rule = new ($type->ruleClass());
        $failed = false;

        $rule->validate('value', $value, function () use (&$failed): void {
            $failed = true;
        });

        return ! $failed;
    }
}
