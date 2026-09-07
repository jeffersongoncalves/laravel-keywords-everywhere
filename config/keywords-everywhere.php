<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Keywords Everywhere API Token
    |--------------------------------------------------------------------------
    |
    | The Bearer token used to authenticate every request against the
    | Keywords Everywhere API. Generate one at
    | https://keywordseverywhere.com/dashboard.
    |
    | When this is null the client falls back to
    | config('services.keywords-everywhere.token').
    |
    */
    'token' => env('KEYWORDS_EVERYWHERE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL every endpoint is resolved against.
    |
    */
    'base_url' => env('KEYWORDS_EVERYWHERE_BASE_URL', 'https://api.keywordseverywhere.com/v1'),
];
