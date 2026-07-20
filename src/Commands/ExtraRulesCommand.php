<?php

namespace Aindot\ExtraRules\Commands;

use Aindot\ExtraRules\ExtraRules;
use Aindot\ExtraRules\RuleType;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Throwable;

class ExtraRulesCommand extends Command
{
    public $signature = 'extra-rules:magic
                            {input : The value to classify}
                            {--rules= : Optional comma-separated rule names to check}';

    public $description = 'Guess which validation categories an input belongs to';

    public function handle(ExtraRules $extraRules): int
    {
        $input = (string) $this->argument('input');
        $rulesOption = $this->option('rules');

        try {
            $candidates = $this->parseRules(is_string($rulesOption) ? $rulesOption : null);
            $matches = $extraRules->magic($input, $candidates);
        } catch (InvalidArgumentException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        if ($matches === []) {
            $this->warn('No matching validation categories found.');

            return self::SUCCESS;
        }

        $this->info('Matched '.count($matches).' categor'.(count($matches) === 1 ? 'y' : 'ies').':');

        $this->table(
            ['Name', 'Key', 'Rule class'],
            array_map(
                fn (RuleType $type) => [$type->name, $type->value, class_basename($type->ruleClass())],
                $matches
            )
        );

        return self::SUCCESS;
    }

    /**
     * @return array<int, string>
     */
    private function parseRules(?string $rulesOption): array
    {
        if ($rulesOption === null || trim($rulesOption) === '') {
            return [];
        }

        return array_values(array_filter(array_map(
            static fn (string $rule): string => trim($rule),
            explode(',', $rulesOption)
        )));
    }
}
