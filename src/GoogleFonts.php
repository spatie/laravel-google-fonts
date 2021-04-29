<?php

namespace Spatie\GoogleFonts;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class GoogleFonts
{
    private const USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Safari/605.1.15';

    public function load(string $url): void
    {
        $css = Http::withHeaders(['User-Agent' => self::USER_AGENT])
            ->get($url)
            ->body();

        $css = $this->extractSrcUrls($css)->reduce(function (string $css, string $src) use ($url) {
            $filename = $this->filename($src);

            Storage::disk('public')->put(
                $this->path($url, $filename),
                Http::get($src)->body()
            );

            return str_replace($src, '/storage/google-fonts/' . $filename, $css);
        }, $css);

        Storage::disk('public')->put(
            $this->path($url, 'fonts.css'),
            $css
        );
    }

    public function inline(string $url): HtmlString
    {
        $this->load($url);

        return new HtmlString(
            '<style>' . Storage::disk('public')->get($this->path($url, 'fonts.css')) . '</style>'
        );
    }

    private function path(string $url, string $path = ''): string
    {
        return config('google-fonts.directory') . '/' .  Str::slug($url) . '/' . $path;
    }

    private function filename(string $path): string
    {
        [$path, $extension] = explode('.', str_replace('https://fonts.gstatic.com/', '', $path));

        return implode('.', [Str::slug($path), $extension]);
    }

    private function extractSrcUrls(string $css): Collection
    {
        $matches = [];
        preg_match_all('/url\((https:\/\/fonts.gstatic.com\/[^)]+)\)/', $css, $matches);

        return collect($matches[1] ?? []);
    }
}
