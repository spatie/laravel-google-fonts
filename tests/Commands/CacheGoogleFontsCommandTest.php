<?php

namespace Spatie\GoogleFonts\Tests\Commands;

use Illuminate\Support\Facades\Storage;
use Spatie\GoogleFonts\Commands\CacheGoogleFontsCommand;
use Spatie\GoogleFonts\Exceptions\CouldNotCacheFont;
use Spatie\GoogleFonts\Tests\TestCase;

class CacheGoogleFontsCommandTest extends TestCase
{
    /** @test */
    public function it_can_cache_configured_fonts()
    {
        $diskName = config('google-fonts.disk');

        config()->set("filesystems.disks.{$diskName}", 'dummy-value');

        Storage::fake($diskName);;

        config()->set('google-fonts.fonts', [
            'default' => 'https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,700;1,400;1,700',
        ]);

        $this->artisan(CacheGoogleFontsCommand::class);
    }

    /** @test */
    public function it_will_throw_an_exception_if_the_configured_disk_does_not_exist()
    {
        config()->set('google-fonts.disk', 'non-existing-disk');

        $this->expectException(CouldNotCacheFont::class);

        $this->artisan(CacheGoogleFontsCommand::class);
    }
}
