<?php

return [
    // ...existing code...

    /*
    |--------------------------------------------------------------------------
    | Livewire Assets URL
    |--------------------------------------------------------------------------
    |
    | This value sets the path to Livewire JavaScript assets, for cases where
    | your app's domain root is not the correct path. By default, Livewire
    | will load its JavaScript assets from the app's "relative root".
    |
    | Examples: "/assets", "myurl.com/app".
    |
    */

    'asset_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire App URL
    |--------------------------------------------------------------------------
    |
    | This value should be used if livewire assets are served from CDN.
    | Livewire will communicate with an app through this url.
    |
    | Examples: "https://my-app.com", "myurl.com/app".
    |
    */

    'app_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Livewire Inject Morph Marker
    |--------------------------------------------------------------------------
    |
    | Livewire will inject a marker in the DOM which is used to indicate which
    | DOM elements belong to Livewire. You can customize the marker HTML
    | tag that gets injected here, or set it to 'false' to disable injecting.
    |
    */

    'inject_morph_marker' => true,

    /*
    |--------------------------------------------------------------------------
    | Livewire Inject Assets
    |--------------------------------------------------------------------------
    |
    | By default, Livewire automatically injects its JavaScript and CSS into the
    | page when using the @livewireStyles and @livewireScripts directives. You
    | can disable this behavior by setting this option to false.
    |
    */

    'inject_assets' => true,

    /*
    |--------------------------------------------------------------------------
    | Livewire Inject Alpine
    |--------------------------------------------------------------------------
    |
    | Livewire automatically injects Alpine.js into the page when using
    | @livewireScripts directive. You can disable this behavior by
    | setting this to false.
    |
    */

    'inject_alpine' => true,

    // ...existing code...
];
