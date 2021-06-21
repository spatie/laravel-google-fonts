<?php

namespace Spatie\GoogleFonts\Tests\Commands;

use Spatie\GoogleFonts\Commands\CacheGoogleFontsCommand;
use Spatie\GoogleFonts\Exceptions\CouldNotCacheFont;
use Spatie\GoogleFonts\Tests\TestCase;

class CacheGoogleFontsCommandTest extends TestCase
{
    /** @test */
    public function it_will_throw_an_exception_if_the_configured_disk_does_not_exist()
    {
        config()->set('google-fonts.disk', 'non-existing-disk');

        $this->expectException(CouldNotCacheFont::class);

        $this->artisan(CacheGoogleFontsCommand::class);
    }
}
