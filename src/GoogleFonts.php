<?php

namespace Spatie\GoogleFonts;

use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class GoogleFonts
{
    public function __construct(
        protected Filesystem $filesystem,
        protected string $path,
        protected bool $inline,
        protected bool $fallback,
        protected string $userAgent,
        protected array $fonts,
    ) {
    }

    public function load(string|array $options = [], bool $forceDownload = false): Fonts
    {
        ['font' => $font, 'nonce' => $nonce] = $this->parseOptions($options);

        if (! isset($this->fonts[$font])) {
            throw new RuntimeException("Font `{$font}` doesn't exist");
        }

        $url = $this->fonts[$font];

        try {
            if ($forceDownload) {
                return $this->fetch($url, $nonce);
            }

            $fonts = $this->loadLocal($url, $nonce);

            if (! $fonts) {
                return $this->fetch($url, $nonce);
            }

            return $fonts;
        } catch (Exception $exception) {
            if (! $this->fallback) {
                throw $exception;
            }

            return new Fonts(googleFontsUrl: $url, nonce: $nonce);
        }
    }

    protected function loadLocal(string $url, ?string $nonce): ?Fonts
    {
        if (! $this->filesystem->exists($this->path($url, 'fonts.css'))) {
            return null;
        }

        $localizedCss = $this->filesystem->get($this->path($url, 'fonts.css'));

        return new Fonts(
            googleFontsUrl: $url,
            localizedUrl: $this->filesystem->url($this->path($url, 'fonts.css')),
            localizedCss: $localizedCss,
            nonce: $nonce,
            preferInline: $this->inline,
        );
    }

    protected function fetch(string $url, ?string $nonce): Fonts
    {
        $css = Http::withHeaders(['User-Agent' => $this->userAgent])
            ->get($url)
            ->body();

        $localizedCss = $css;

        foreach ($this->extractFontUrls($css) as $fontUrl) {
            $localizedFontUrl = $this->localizeFontUrl($fontUrl);

            $this->filesystem->put(
                $this->path($url, $localizedFontUrl),
                Http::get($fontUrl)->body(),
            );

            $localizedCss = str_replace(
                $fontUrl,
                $this->filesystem->url($this->path($url, $localizedFontUrl)),
                $localizedCss,
            );
        }

        $this->filesystem->put($this->path($url, 'fonts.css'), $localizedCss);

        return new Fonts(
            googleFontsUrl: $url,
            localizedUrl: $this->filesystem->url($this->path($url, 'fonts.css')),
            localizedCss: $localizedCss,
            nonce: $nonce,
            preferInline: $this->inline,
        );
    }

    protected function extractFontUrls(string $css): array
    {
        $matches = [];
        preg_match_all('/url\((https:\/\/fonts.gstatic.com\/[^)]+)\)/', $css, $matches);

        return $matches[1] ?? [];
    }

    protected function localizeFontUrl(string $path): string
    {
        [$path, $extension] = explode('.', str_replace('https://fonts.gstatic.com/', '', $path));

        return implode('.', [Str::slug($path), $extension]);
    }

    protected function path(string $url, string $path = ''): string
    {
        $segments = collect([
            $this->path,
            substr(md5($url), 0, 10),
            $path,
        ]);

        return $segments->filter()->join('/');
    }

    protected function parseOptions(string|array $options): array
    {
        if (is_string($options)) {
            $options = ['font' => $options, 'nonce' => null];
        }

        return [
            'font' => $options['font'] ?? 'default',
            'nonce' => $options['nonce'] ?? null,
        ];
    }
}
