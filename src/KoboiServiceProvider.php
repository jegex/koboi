<?php

namespace Jegex\Koboi;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Jegex\Koboi\Commands\KoboiCommand;

class KoboiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('koboi')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_koboi_table')
            ->hasCommand(KoboiCommand::class);
    }
}
