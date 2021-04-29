<?php

namespace Spatie\GoogleFonts\Tests;

use Spatie\GoogleFonts\GoogleFonts;

class GoogleFontsTest extends TestCase
{
    /** @test */
    public function true_is_true()
    {
        dd(app(GoogleFonts::class)->inline('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300&display=swap'));
    }
}
