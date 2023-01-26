<?php
return [
    'messenger' => [
        'authentication' => env('MESSENGER_AUTHORIZATION'),
        'host' => env('MESSENGER_HOST'),
        'project_id' => env('MESSENGER_PROJECT_ID'),
        'messenger' => explode(',', env('MESSENGER_LIST', 'viber')),
        'sendAll' => env('MESSENGER_SEND_ALL', false),
        'callback_url' => env('MESSENGER_CALLBACK_URL'),
        'user_phone' => env('MESSENGER_USER_PHONE'),
    ],
    'smart-sender' => [
        'host' => env('SMART_SENDER_HOST'),
        'authentication' => env('SMART_SENDER_AUTHORIZATION'),
    ],
];
