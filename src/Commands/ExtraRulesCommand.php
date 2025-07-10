<?php

namespace Aindot\ExtraRules\Commands;

use Illuminate\Console\Command;

class ExtraRulesCommand extends Command
{
    public $signature = 'extra-rules';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
