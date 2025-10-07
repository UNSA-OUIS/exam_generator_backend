<?php

return [

    'paths' => [
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'api/*',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // Solo para desarrollo


    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
