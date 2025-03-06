<?php

return [

    'apps' => [
        [
            'id' => env('REVERB_APP_ID', 'local'),
            'name' => env('APP_NAME', 'Laravel'),
            'key' => env('REVERB_APP_KEY', 'localkey'),
            'secret' => env('REVERB_APP_SECRET', 'localsecret'),
            'path' => env('REVERB_APP_PATH'),
            'capacity' => null,
            'enable_client_messages' => false,
            'enable_statistics' => true,
        ],
    ],

    'dashboard' => [
        'port' => env('REVERB_PORT', 6001),
    ],
];
