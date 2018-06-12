<?php

return [
    'cache-key' => 'user-api-access-token',
    'uri' => env('USER_PROVIDER_API_URL', 'localhost/api/v1/users'),
    'cache_ttl' => 2,
    'headers' => [
    ],
];
