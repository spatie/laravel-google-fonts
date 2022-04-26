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
        protected bool $blocking = true,
    ) {
    }

    public function inline(): HtmlString
    {
        if (! $this->localizedCss) {
            return $this->link();
        }

        if ($this->blocking) {
            return new HtmlString(<<<HTML
                <style type="text/css">{$this->localizedCss}</style>
            HTML);
        }

        return new HtmlString(<<<HTML
            <style media="print" type="text/css" onload="this.onload=null;this.removeAttribute('media');">{$this->localizedCss}</style>
        HTML);
    }

    public function link(): HtmlString
    {
        $url = $this->url();

        if ($this->blocking) {
            return new HtmlString(<<<HTML
                <link href="{$url}" rel="stylesheet" type="text/css">
            HTML);
        }

        return new HtmlString(<<<HTML
            <link href="{$url}" rel="preload" as="style">
            <link href="{$url}" rel="stylesheet" media="print" type="text/css" onload="this.onload=null;this.removeAttribute('media');">
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
