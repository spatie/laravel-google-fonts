<?php

namespace Spatie\GoogleFonts\Commands;

use Illuminate\Console\Command;

class GoogleFontsCommand extends Command
{
    public $signature = 'laravel-google-fonts';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
