<?php

namespace Aindot\ExtraRules;

use Aindot\ExtraRules\Commands\ExtraRulesCommand;
use Aindot\ExtraRules\Commands\GenerateCommand;
use Aindot\ExtraRules\Commands\RedactCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ExtraRulesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('extra-rules')
            ->hasCommands([
                ExtraRulesCommand::class,
                GenerateCommand::class,
                RedactCommand::class,
            ])
            ->hasTranslations();
    }
}
