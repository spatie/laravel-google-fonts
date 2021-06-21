<?php

namespace Spatie\GoogleFonts\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\GoogleFonts\Exceptions\CouldNotCacheFont;
use Spatie\GoogleFonts\GoogleFonts;

class CacheGoogleFontsCommand extends Command
{
    public $signature = 'google-fonts:cache';

    public $description = 'Fetch Google Fonts and store them on a local disk';

    public function handle()
    {
        $this->info('Starting caching Google Fonts...');

        collect(config('google-fonts.fonts'))
            ->each(function (string $url) {
                $this->info("Caching font `{$url}`...");

                return app(GoogleFonts::class)->load($url, forceFresh: true);
            });

        $this->info('All done!');
    }
}
