<?php

namespace Spatie\GoogleFonts\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class CacheGoogleFontsCommand extends Command
{
    public $signature = 'google-fonts:cache';

    public $description = 'Fetch Google Fonts and store them on a local disk';

    public function handle()
    {
        $this->info('Starting caching Google Fonts...');



        $fontUrls = $this->findGoogleFontUrlsInBladeFiles();

        $this->info("Found {$fontUrls->count()} fonts...");

        $fontUrls->each(function(string $fontUrl) {
            $this->fetchFont($frontUrl);
        });



    }

    protected function findGoogleFontUrlsInBladeFiles(): Collection
    {

    }

    protected function fetchFont(string $frontUrl): void
    {
    }
}
