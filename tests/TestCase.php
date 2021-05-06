<?php

namespace Spatie\GoogleFonts\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\GoogleFonts\GoogleFontsServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [GoogleFontsServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('filesystems.disks', array_merge(
            config('filesystems.disks'),
            [
                'fonts' => [
                    'driver' => 'local',
                    'root' => __DIR__.'/fonts',
                    'url' => env('APP_URL').'/storage',
                    'visibility' => 'public',
                ],
            ],
        ));

        config()->set('google-fonts.disk', 'fonts');
        config()->set('google-fonts.path', '');
        config()->set('google-fonts.fallback', false);
    }
}
