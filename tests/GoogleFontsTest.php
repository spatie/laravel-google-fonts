<?php

use Illuminate\Support\Str;
use Spatie\GoogleFonts\GoogleFonts;

use function Spatie\Snapshots\assertMatchesFileSnapshot;
use function Spatie\Snapshots\assertMatchesHtmlSnapshot;

it('loads google fonts', function ($arguments) {
    $fonts = app(GoogleFonts::class)->load($arguments, forceDownload: true);

    $expectedFileName = '952ee985ef/fonts.css';

    $this->disk()->assertExists($expectedFileName);

    $fullCssPath = $this->disk()->path($expectedFileName);

    assertMatchesFileSnapshot($fullCssPath);

    $woff2FileCount = collect($this->disk()->allFiles())
        ->filter(fn (string $path) => Str::endsWith($path, '.woff2'))
        ->count();

    expect($woff2FileCount)->toBeGreaterThan(0);

    assertMatchesHtmlSnapshot((string) $fonts->link());
    assertMatchesHtmlSnapshot((string) $fonts->inline());

    $expectedUrl = $this->disk()->url($expectedFileName);
    expect($fonts->url())->toEqual($expectedUrl);
})->with([
    'font_as_string' => 'inter',
    'font_as_array' => ['font' => 'inter'],
]);

it('falls back to google fonts', function ($arguments) {
    config()->set('google-fonts.fonts', ['cow' => 'moo']);
    config()->set('google-fonts.fallback', true);

    $fonts = app(GoogleFonts::class)->load($arguments, forceDownload: true);

    $allFiles = $this->disk()->allFiles();

    expect($allFiles)->toHaveCount(0);

    $fallback = <<<HTML
            <link href="moo" rel="stylesheet" type="text/css">
        HTML;

    expect([
        (string) $fonts->link(),
        (string) $fonts->inline(),
    ])->each->toEqual($fallback)
        ->and($fonts->url())->toEqual('moo');
})
    ->with([
        'font_as_string' => 'cow',
        'font_as_array' => ['font' => 'cow'],
    ]);
    ;

it('adds the nonce attribute when specified', function () {
    config()->set('google-fonts.fonts', ['cow' => 'moo']);
    config()->set('google-fonts.fallback', true);

    $fonts = app(GoogleFonts::class)->load(['font' => 'cow', 'nonce' => 'chicken'], forceDownload: true);

    expect([
        (string) $fonts->link(),
        (string) $fonts->inline(),
    ])->each->toContain('nonce="chicken"');
});
