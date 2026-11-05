<?php

namespace Spatie\GoogleFonts\Tests;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase as Orchestra;
use RuntimeException;
use Spatie\GoogleFonts\GoogleFontsServiceProvider;

class TestCase extends Orchestra
{
    protected string $fontsUrl;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake(config('google-fonts.disk'));

        Http::fake(function (Request $request): PromiseInterface {
            if ($request->url() === 'https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap') {
                return Http::response(file_get_contents(__DIR__ . '/fixtures/inter.css'));
            }

            if (str_starts_with($request->url(), 'https://fonts.gstatic.com/s/inter/v20/')) {
                return Http::response('font-bytes');
            }

            throw new RuntimeException("Unexpected HTTP request: {$request->url()}");
        });
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
                    'root' => __DIR__ . '/fonts',
                    'url' => env('APP_URL') . '/storage',
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
