<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Disk
    |--------------------------------------------------------------------------
    |
    | The disk that will be used to store local Google Fonts. The public disk
    | is the default because it can be served over HTTP with storage:link.
    |
    */
    'disk' => 'public',

    /*
    |--------------------------------------------------------------------------
    | Path
    |--------------------------------------------------------------------------
    |
    | Prepend all files that are written to the selected disk with this path.
    | This allows separating the fonts from other data in the public disk.
    |
    */
    'path' => 'fonts',

    /*
    |--------------------------------------------------------------------------
    | Fallback
    |--------------------------------------------------------------------------
    |
    | When something goes wrong fonts are loaded directly from Google.
    | With fallback disabled, this package will throw an exception.
    |
    */
    'fallback' => true,

    /*
    |--------------------------------------------------------------------------
    | User Agent
    |--------------------------------------------------------------------------
    |
    | This user agent will be used to request the stylesheet from Google Fonts.
    | This is the Safari 14 user agent that only targets modern browsers. If
    | you want to target older browsers, use different user agent string.
    |
    */
    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Safari/605.1.15',

];
