<?php

namespace Aindot\ExtraRules\Commands;

use Aindot\ExtraRules\ExtraRules;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Throwable;

class GenerateCommand extends Command
{
    public $signature = 'extra-rules:generate
                            {rule : Rule type to generate (e.g. imei, iban, uuid)}
                            {--count=1 : How many values to generate}';

    public $description = 'Generate valid sample values for a validation rule';

    public function handle(ExtraRules $extraRules): int
    {
        $rule = (string) $this->argument('rule');
        $count = max(1, (int) $this->option('count'));

        try {
            $values = $extraRules->generateMany($rule, $count);
        } catch (InvalidArgumentException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info(__('extra-rules::commands.generate.generated', [
            'count' => count($values),
            'rule' => $rule,
        ]));

        foreach ($values as $value) {
            $this->line($value);
        }

        return self::SUCCESS;
    }
}
