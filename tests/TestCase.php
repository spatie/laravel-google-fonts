<?php

namespace Spatie\GoogleFonts\Tests;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\GoogleFonts\GoogleFontsServiceProvider;

class TestCase extends Orchestra
{
    protected string $fontsUrl;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake(config('google-fonts.disk'));
    }

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

        config()->set('google-fonts.fonts', [
            'inter' => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap',
        ]);
        config()->set('google-fonts.disk', 'fonts');
        config()->set('google-fonts.path', '');
        config()->set('google-fonts.fallback', false);
    }

    public function disk(): Filesystem
    {
        $diskName = config('google-fonts.disk');

        return Storage::disk($diskName);
    }
}
