<?php

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__.'/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
    'www' => [
        // database setting
        'db' => [
            'host' => '127.0.0.1',
            'user' => 'root',
            'pass' => '0212',
            'dbname' => 'vshare',
            'prefix' => 'v_',
        ],
        // JWT token setting
        'secret' => 'yvenchang',
        'jwt' => [
            'iss' => 'www.yvenchang.xyz',
        ],
    ],
];
