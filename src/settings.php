<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // DataBase setting
        // 'db' => [
        //     'host' => '127.0.0.1',
        //     'user' => 'root',
        //     'pass' => '0212',
        //     'dbname' => 'vshare'
        // ],

        // JWT token setting
        "jwt" => [
            "aud" => "www.yvenmediashare.com",
            "iss" => "Media Share Web of Yven Chang",
            // "iat" => 1416797419,
            // "exp" => 1448333419,
            // "sub" => "jrocket@example.com",
            // "logInAs" => "admin"
        ],
    ],
];
