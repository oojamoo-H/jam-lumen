<?php
return [
    'client' => 'rabbitmq',

    'rabbitmq' => [
        'host' => env('RABBITMQ_HOST', 'localhost'),
        'port' => env('RABBITMQ_PORT', 5672),
        'login' => env('RABBITMQ_USERNAME', 'guest'),
        'password' => env('RABBITMQ_PASSWORD', 'guest'),
        'read_timeout' => '',
        'connect_timeout' => ''
    ]
];
