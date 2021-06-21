<?php

namespace Spatie\GoogleFonts\Tests\Commands;

use Illuminate\Support\Facades\Storage;
use Spatie\GoogleFonts\Commands\CacheGoogleFontsCommand;
use Spatie\GoogleFonts\Tests\TestCase;

class CacheGoogleFontsCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('google-fonts.fonts', [
            'code' => $this->fontsUrl,
        ]);
    }

    /** @test */
    public function it_can_cache_configured_fonts()
    {
        $this->disk()->assertMissing('952ee985ef/fonts.css');

        $this->artisan(CacheGoogleFontsCommand::class);

        $this->disk()->assertExists('952ee985ef/fonts.css');
    }

    /** @test */
    public function it_will_use_the_configured_path_when_fetching_fonts()
    {
        $path = 'my-path';

        config()->set('google-fonts.path', $path);

        $this->artisan(CacheGoogleFontsCommand::class);

        $this->disk()->assertExists("{$path}/952ee985ef/fonts.css");
    }
}
