<?php

namespace Spatie\GoogleFonts\Tests;

use Spatie\GoogleFonts\GoogleFonts;
use Spatie\Snapshots\MatchesSnapshots;

class GoogleFontsTest extends TestCase
{
    use MatchesSnapshots;

    protected string $fontsUrl;

    protected string $localPath;

    public function setUp(): void
    {
        parent::setUp();

        $this->fontsUrl = 'https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap';
        $this->localPath = __DIR__ . '/fonts/' . substr(md5($this->fontsUrl), 0, 10);

        // `shell_exec` is a lot more straightforward than `rmdir` with a non-empty directory
        shell_exec('rm -rf ' . $this->localPath);
    }

    /** @test */
    public function it_loads_google_fonts()
    {
        $fonts = app(GoogleFonts::class)->load($this->fontsUrl, forceFresh: true);

        $this->assertDirectoryExists($this->localPath);

        $this->assertMatchesFileSnapshot($this->localPath . '/'. 'fonts.css');

        $this->assertNotEmpty(glob($this->localPath . '/*.woff2'));

        $this->assertMatchesHtmlSnapshot((string) $fonts->link());
        $this->assertMatchesHtmlSnapshot((string) $fonts->inline());
    }

    /** @test */
    public function it_falls_back_to_google_fonts()
    {
        config()->set('google-fonts.fallback', true);

        $fonts = app(GoogleFonts::class)->load('moo', forceFresh: true);

        $this->assertDirectoryDoesNotExist($this->localPath);

        $fallback = <<<HTML
            <link href="moo" rel="stylesheet" type="text/css">
        HTML;

        $this->assertEquals($fallback, (string) $fonts->link());
        $this->assertEquals($fallback, (string) $fonts->inline());
    }
}
