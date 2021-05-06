<?php

namespace Spatie\GoogleFonts;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Application;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\GoogleFonts\Commands\GoogleFontsCommand as FetchGoogleFontsCommand;

class GoogleFontsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('google-fonts')
            ->hasConfigFile()
            ->hasCommand(FetchGoogleFontsCommand::class);
    }

    public function register()
    {
        parent::register();

        $this->app->singleton(GoogleFonts::class, function (Application $app) {
            return new GoogleFonts(
                $app->make(FilesystemManager::class)->disk($app->config->get('google-fonts.disk')),
                $app->config->get('google-fonts.user_agent'),
                $app->config->get('google-fonts.fallback'),
            );
        });
    }
}
