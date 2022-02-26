<?php

namespace Spatie\GoogleFonts;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Fonts implements Htmlable
{
    public function __construct(
        protected string $googleFontsUrl,
        protected ?string $localizedUrl = null,
        protected ?string $localizedCss = null,
        protected bool $preferInline = false,
    ) {
    }

    public function inline(): HtmlString
    {
        if (! $this->localizedCss) {
            return $this->fallback();
        }

        return new HtmlString(<<<HTML
            <style>{$this->localizedCss}</style>
        HTML);
    }

    public function link(): HtmlString
    {
        if (! $this->localizedUrl) {
            return $this->fallback();
        }

        return new HtmlString(<<<HTML
            <link href="{$this->localizedUrl}" rel="stylesheet" type="text/css">
        HTML);
    }

    public function fallback(): HtmlString
    {
        return new HtmlString(<<<HTML
            <link href="{$this->googleFontsUrl}" rel="stylesheet" type="text/css">
        HTML);
    }

    public function url(): string
    {
        if (! $this->localizedUrl) {
            return $this->googleFontsUrl;
        }

        return $this->localizedUrl;
    }

    public function toHtml(): HtmlString
    {
        return $this->preferInline ? $this->inline() : $this->link();
    }
}
