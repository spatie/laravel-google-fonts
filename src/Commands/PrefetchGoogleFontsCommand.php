<?php

namespace Spatie\GoogleFonts\Commands;

use Illuminate\Console\Command;
use Spatie\GoogleFonts\GoogleFonts;

class PrefetchGoogleFontsCommand extends Command
{
    public $signature = 'google-fonts:prefetch';

    public $description = 'Fetch Google Fonts and store them on a local disk';

    public function handle()
    {
        $this->info('Starting caching Google Fonts...');

        collect(config('google-fonts.fonts'))
            ->each(function (string $url) {
                $this->info("Caching font `{$url}`...");

                return app(GoogleFonts::class)->load($url, forceDownload: true);
            });

        $this->info('All done!');
    }
}
