<?php

namespace Aindot\ExtraRules;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Aindot\ExtraRules\Commands\ExtraRulesCommand;

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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_extra_rules_table')
            ->hasCommand(ExtraRulesCommand::class);
    }
}
