<?php

namespace Spatie\GoogleFonts\Tests\Commands;

use Illuminate\Support\Facades\Storage;
use Spatie\GoogleFonts\Commands\CacheGoogleFontsCommand;
use Spatie\GoogleFonts\Tests\TestCase;

class CacheGoogleFontsCommandTest extends TestCase
{
    /** @test */
    public function it_can_cache_configured_fonts()
    {
        $fontUrl = 'https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,700;1,400;1,700';

        $diskName = config('google-fonts.disk');

        config()->set('google-fonts.fonts', [
            'code' => $fontUrl,
        ]);

        Storage::fake($diskName);

        Storage::disk($diskName)->assertMissing('50c9ec21f5/fonts.css');

        $this->artisan(CacheGoogleFontsCommand::class);

        Storage::disk($diskName)->assertExists('50c9ec21f5/fonts.css');
    }
}
