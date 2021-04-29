<?php

namespace Spatie\GoogleFonts;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\GoogleFonts\Commands\GoogleFontsCommand;

class GoogleFontsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-google-fonts')
            ->hasCommand(GoogleFontsCommand::class);
    }
}
