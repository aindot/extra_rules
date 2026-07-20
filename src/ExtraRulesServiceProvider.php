<?php

namespace Aindot\ExtraRules;

use Aindot\ExtraRules\Commands\ExtraRulesCommand;
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
            ->hasCommand(ExtraRulesCommand::class)
            ->hasTranslations();
    }
}
