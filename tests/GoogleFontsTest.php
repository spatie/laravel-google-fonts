<?php

namespace Spatie\GoogleFonts\Tests;

use Illuminate\Support\Str;
use Spatie\GoogleFonts\GoogleFonts;
use Spatie\Snapshots\MatchesSnapshots;

class GoogleFontsTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function it_loads_google_fonts()
    {
        $fonts = app(GoogleFonts::class)->load($this->fontsUrl, forceDownload: true);

        $expectedFileName = '952ee985ef/fonts.css';

        $this->disk()->assertExists($expectedFileName);

        $fullCssPath = $this->disk()->path($expectedFileName);

        $this->assertMatchesFileSnapshot($fullCssPath);

        $woff2FileCount = collect($this->disk()->allFiles())
            ->filter(fn(string $path) => Str::endsWith($path, '.woff2'))
            ->count();

        $this->assertGreaterThan(0, $woff2FileCount);

        $this->assertMatchesHtmlSnapshot((string)$fonts->link());
        $this->assertMatchesHtmlSnapshot((string)$fonts->inline());
    }

    /** @test */
    public function it_falls_back_to_google_fonts()
    {
        config()->set('google-fonts.fallback', true);

        $fonts = app(GoogleFonts::class)->load('moo', forceDownload: true);

        $allFiles = $this->disk()->allFiles();

        $this->assertCount(0, $allFiles);

        $fallback = <<<HTML
            <link href="moo" rel="stylesheet" type="text/css">
        HTML;

        $this->assertEquals($fallback, (string)$fonts->link());
        $this->assertEquals($fallback, (string)$fonts->inline());
    }
}
