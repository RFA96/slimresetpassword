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
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Database configuration
        'db' => [
            'host' => '192.168.10.173',
            'user' => 'scoring_app',
            'pass' => 'scoring_app123',
            'dbname' => 'pefindo_pilot',
            'driver' => 'postgre'
        ]
    ],
];
