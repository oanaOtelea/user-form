<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'images_manager' => [
            'uploads_path' => dirname(dirname(__DIR__)) . '/uploads',
            'image_driver' => 'gd',
            'image_sizes' => [
                'width' => 250,
                'height' => 250
            ]
        ],

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Database
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'project-fusio',
            'username' => 'root',
            'password' => 'developer010',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => ''
        ]
    ],
];
