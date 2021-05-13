<?php

namespace Spatie\GoogleFonts;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
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

    public function packageRegistered()
    {
        $this->app->singleton(GoogleFonts::class, function (Application $app) {
            return new GoogleFonts(
                filesystem: $app->make(FilesystemManager::class)->disk($app->config->get('google-fonts.disk')),
                path: $app->config->get('google-fonts.path'),
                userAgent: $app->config->get('google-fonts.user_agent'),
                fallback: $app->config->get('google-fonts.fallback'),
            );
        });
    }

    public function packageBooted()
    {
        Blade::directive('googlefonts', function ($expression) {
            return "<?php echo app(Spatie\GoogleFonts\GoogleFonts::class)->load($expression)->toHtml(); ?>";
        });
    }
}
