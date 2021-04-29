<?php

namespace Spatie\GoogleFonts\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\GoogleFonts\GoogleFontsServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [GoogleFontsServiceProvider::class];
    }
}
