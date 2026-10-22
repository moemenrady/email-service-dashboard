<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MXroute API Configuration
    |--------------------------------------------------------------------------
    |
    | Credentials and server configuration for proxying requests to the MXroute
    | REST API. Sensitive values are strictly loaded from environment variables.
    |
    */

    'server' => env('MXROUTE_SERVER', 'fusion.mxrouting.net'),

    'username' => env('MXROUTE_USERNAME', 'ryvaaefaya'),

    'api_key' => env('MXROUTE_API_KEY', ''),

    'base_url' => env('MXROUTE_BASE_URL', 'https://api.mxroute.com'),

    'timeout' => env('MXROUTE_TIMEOUT', 15),

    'retry_attempts' => env('MXROUTE_RETRY_ATTEMPTS', 2),

    'retry_backoff_ms' => env('MXROUTE_RETRY_BACKOFF_MS', 500),

    /*
    | Default domain to manage if none assigned to the user
    */
    'default_domain' => env('MXROUTE_DEFAULT_DOMAIN', 'albrolosy.com'),
];
