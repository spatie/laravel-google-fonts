<?php

namespace Spatie\GoogleFonts\Tests\Commands;

use Spatie\GoogleFonts\Commands\PrefetchGoogleFontsCommand;
use Spatie\GoogleFonts\Tests\TestCase;

class PrefetchGoogleFontsCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('google-fonts.fonts', [
            'code' => $this->fontsUrl,
        ]);
    }

    /** @test */
    public function it_can_prefetch_configured_fonts()
    {
        $this->disk()->assertMissing('952ee985ef/fonts.css');

        $this->artisan(PrefetchGoogleFontsCommand::class);

        $this->disk()->assertExists('952ee985ef/fonts.css');
    }

    /** @test */
    public function it_will_use_the_configured_path_when_prefetching_fonts()
    {
        $path = 'my-path';

        config()->set('google-fonts.path', $path);

        $this->artisan(PrefetchGoogleFontsCommand::class);

        $this->disk()->assertExists("{$path}/952ee985ef/fonts.css");
    }
}
