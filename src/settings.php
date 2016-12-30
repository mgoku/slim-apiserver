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

        // Medoo
        'medoo' => [
            // required
            'database_type' => 'mysql',
            'database_name' => 'slimapiserver',
            'server' => 'localhost',
            'username' => 'slimapiserver',
            'password' => 'slimapiserver',
            'charset' => 'utf8'
        ],

        // JWT
        'jwt' => [
            'admin' => 'qj2eDSPLp0TFKDv7qGN7SnG6a36lOBplEjdAiNyZkBtqJvDWpD1ncRSi6gxQAPWT',
            'mobile' => 'w3UK1JrlB2GIF3l48xmPSWFwGAm8NsqeIOAOhsJRK8YlifM1dWNVZ019Rz1p9Rzg'
        ],

        // Konfigurasi timezone
        'timezone' => 'Asia/Jakarta',
    ],
];
