<?php

namespace Aindot\ExtraRules\Commands;

use Aindot\ExtraRules\ExtraRules;
use Aindot\ExtraRules\RuleType;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class RedactCommand extends Command
{
    public $signature = 'extra-rules:redact
                            {input? : Text to scan}
                            {--file= : Read text from a file instead of the argument}
                            {--stdin : Read text from standard input}
                            {--types= : Optional comma-separated rule names to scan}
                            {--list : Only list detected categories, do not output text}';

    public $description = 'Detect and redact secrets in text';

    public function handle(ExtraRules $extraRules): int
    {
        $this->description = __('extra-rules::commands.redact.description');

        try {
            $text = $this->readInput();
            $types = $this->parseTypes();
            $matches = $extraRules->detect($text, $types);
        } catch (InvalidArgumentException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        if ($this->option('list')) {
            $this->listMatches($matches);

            return self::SUCCESS;
        }

        $this->writeText($extraRules->redact($text, $types));

        return self::SUCCESS;
    }

    /**
     * Reads text from the argument, --file, or --stdin.
     */
    private function readInput(): string
    {
        $file = $this->option('file');
        $file = is_string($file) ? $file : null;
        $stdin = (bool) $this->option('stdin');
        $argument = $this->argument('input');

        if (($file === null ? 0 : 1) + ($stdin ? 1 : 0) + ($argument === null ? 0 : 1) !== 1) {
            throw new InvalidArgumentException(__('extra-rules::commands.redact.one_source'));
        }

        if ($stdin) {
            $text = stream_get_contents(STDIN);

            return $text === false ? '' : $text;
        }

        if ($file !== null) {
            if (! is_file($file) || ! is_readable($file)) {
                throw new InvalidArgumentException(__('extra-rules::commands.redact.unreadable_file', ['file' => $file]));
            }

            $text = file_get_contents($file);

            if ($text === false) {
                throw new InvalidArgumentException(__('extra-rules::commands.redact.unreadable_file', ['file' => $file]));
            }

            return $text;
        }

        return (string) $argument;
    }

    /**
     * @return array<int, string>
     */
    private function parseTypes(): array
    {
        $typesOption = $this->option('types');

        if (! is_string($typesOption) || trim($typesOption) === '') {
            return [];
        }

        return array_values(array_filter(array_map(
            static fn (string $type): string => trim($type),
            explode(',', $typesOption)
        )));
    }

    /**
     * @param  list<array{type: RuleType, offset: int, length: int}>  $matches
     */
    private function listMatches(array $matches): void
    {
        if ($matches === []) {
            $this->warn(__('extra-rules::commands.redact.no_matches'));

            return;
        }

        $this->info(__('extra-rules::commands.redact.found', ['count' => count($matches)]));

        $counts = [];

        foreach ($matches as $match) {
            $key = $match['type']->value;
            $counts[$key] = ($counts[$key] ?? 0) + 1;
        }

        foreach ($counts as $key => $count) {
            $this->line("{$key}: {$count}");
        }
    }

    private function writeText(string $text): void
    {
        $this->output->write($text, false, OutputInterface::OUTPUT_RAW);

        if (! str_ends_with($text, "\n")) {
            $this->output->writeln('', OutputInterface::OUTPUT_RAW);
        }
    }
}
