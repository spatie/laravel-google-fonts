<?php

use Illuminate\Support\Str;
use Spatie\GoogleFonts\GoogleFonts;

use function Spatie\Snapshots\assertMatchesFileSnapshot;
use function Spatie\Snapshots\assertMatchesHtmlSnapshot;

it('loads google fonts', function () {
    $fonts = app(GoogleFonts::class)->load('inter', forceDownload: true);

    $expectedFileName = '952ee985ef/fonts.css';

    $this->disk()->assertExists($expectedFileName);

    $fullCssPath = $this->disk()->path($expectedFileName);

    assertMatchesFileSnapshot($fullCssPath);

    $woff2FileCount = collect($this->disk()->allFiles())
        ->filter(fn (string $path) => Str::endsWith($path, '.woff2'))
        ->count();

    expect($woff2FileCount)->toBeGreaterThan(0);

    assertMatchesHtmlSnapshot((string)$fonts->link());
    assertMatchesHtmlSnapshot((string)$fonts->inline());

    $expectedUrl = $this->disk()->url($expectedFileName);
    expect($fonts->url())->toEqual($expectedUrl);
});

it('falls back to google fonts', function () {
    config()->set('google-fonts.fonts', ['cow' => 'moo']);
    config()->set('google-fonts.fallback', true);

    $fonts = app(GoogleFonts::class)->load('cow', forceDownload: true);

    $allFiles = $this->disk()->allFiles();

    expect($allFiles)->toHaveCount(0);

    $fallback = <<<HTML
            <link href="moo" rel="stylesheet" type="text/css">
        HTML;

    expect([
        (string)$fonts->link(),
        (string)$fonts->inline()
    ])->each->toEqual($fallback)
        ->and($fonts->url())->toEqual('moo');
});
