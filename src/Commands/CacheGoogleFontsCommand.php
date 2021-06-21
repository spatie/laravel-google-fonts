<?php

namespace Spatie\GoogleFonts\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\GoogleFonts\Exceptions\CouldNotCacheFont;

class CacheGoogleFontsCommand extends Command
{
    public $signature = 'google-fonts:cache';

    public $description = 'Fetch Google Fonts and store them on a local disk';

    public function handle()
    {
        $this->info('Starting caching Google Fonts...');

        $disk = $this->getDisk();

        collect(config('google-fonts.fonts'))
            ->map(fn(string $fontUrl) => $this->fetchFont($fontUrl, $disk));

        $this->info('All done!');
    }

    protected function getDisk(): Filesystem
    {
        $diskName = config('google-fonts.disk');

        if (config("filesystems.disk.{$diskName}") === null) {
            CouldNotCacheFont::diskNotFound($diskName);
        }

        return Storage::disk($diskName);
    }

    protected function fetchFont(string $fontUrl, Filesystem $disk): void
    {
        $this->comment("Start fetching font {$fontUrl}...");

        $fileName = Hash::make($fontUrl) . '.css';

        $path = config('google-fonts.path');

        $disk->put("{$path}/{$fileName}", file_get_contents($path));
    }
}
