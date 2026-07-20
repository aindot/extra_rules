<?php

namespace Aindot\ExtraRules;

use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

class ExtraRules
{
    public function __construct(
        private readonly Generator $generator = new Generator,
    ) {}

    /**
     * Guesses which validation categories an input belongs to.
     * Pass rule names/enums to limit candidates; empty array checks all rules.
     *
     * @param  array<int, string|RuleType>  $rules
     * @return list<RuleType>
     */
    public function magic(mixed $input, array $rules = []): array
    {
        $candidates = $this->resolveCandidates($rules);
        $matches = [];

        foreach ($candidates as $type) {
            if ($this->passes($type, $input)) {
                $matches[] = $type;
            }
        }

        return $matches;
    }

    /**
     * Generates a valid sample value for the given rule type.
     */
    public function generate(string|RuleType $rule): string
    {
        return $this->generator->make($rule);
    }

    /**
     * Generates multiple valid sample values for the given rule type.
     *
     * @return list<string>
     */
    public function generateMany(string|RuleType $rule, int $count = 1): array
    {
        return $this->generator->makeMany($rule, $count);
    }

    /**
     * Resolves the candidate rule set from strings, enums, or all rules.
     *
     * @param  array<int, string|RuleType>  $rules
     * @return list<RuleType>
     */
    private function resolveCandidates(array $rules): array
    {
        if ($rules === []) {
            return RuleType::cases();
        }

        $candidates = [];

        foreach ($rules as $rule) {
            if (! is_string($rule) && ! $rule instanceof RuleType) {
                throw new InvalidArgumentException('Rule candidates must be strings or RuleType enums.');
            }

            $candidates[] = RuleType::fromMixed($rule);
        }

        return array_values(array_unique($candidates, SORT_REGULAR));
    }

    /**
     * Checks whether the input passes a given rule type.
     */
    private function passes(RuleType $type, mixed $input): bool
    {
        /** @var ValidationRule $rule */
        $rule = new ($type->ruleClass());
        $failed = false;

        $rule->validate('value', $input, function () use (&$failed): void {
            $failed = true;
        });

        return ! $failed;
    }
}
