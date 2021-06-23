<?php

namespace Spatie\GoogleFonts\Tests\Commands;

use Spatie\GoogleFonts\Commands\FetchGoogleFontsCommand;
use Spatie\GoogleFonts\Tests\TestCase;

class FetchGoogleFontsCommandTest extends TestCase
{
    /** @test */
    public function it_can_fetch_configured_fonts()
    {
        $this->disk()->assertMissing('952ee985ef/fonts.css');

        $this->artisan(FetchGoogleFontsCommand::class);

        $this->disk()->assertExists('952ee985ef/fonts.css');
    }

    /** @test */
    public function it_will_use_the_configured_path_when_fetching_fonts()
    {
        $path = 'my-path';

        config()->set('google-fonts.path', $path);

        $this->artisan(FetchGoogleFontsCommand::class);

        $this->disk()->assertExists("{$path}/952ee985ef/fonts.css");
    }
}
