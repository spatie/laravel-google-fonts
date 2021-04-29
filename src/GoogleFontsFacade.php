<?php

namespace Spatie\GoogleFonts;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\GoogleFonts\GoogleFonts
 */
class GoogleFontsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-google-fonts';
    }
}
