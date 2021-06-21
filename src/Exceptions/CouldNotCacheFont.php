<?php

namespace Spatie\GoogleFonts\Exceptions;

use Exception;

class CouldNotCacheFont extends Exception
{
    public static function diskNotFound(string $diskName): self
    {
        return new static("Could not find a disk named `{$diskName}` when caching Google Fonts. Make sure a disk with that name is set in the `filesystems.php` config file.");
    }
}
