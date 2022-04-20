<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'base_path'      => '',                                          // Base path
            'debug'          => (getenv('APPLICATION_ENV') != 'production'), // Is debug mode
            'temporary_path' => ROOT_PATH . '/var/tmp',                      // Temporary directory
            'route_cache'    => ROOT_PATH . '/var/cache/routes',             // Route cache

            // View settings
            'view' => [
                'template_path' => ROOT_PATH . '/templates',
                'twig' => [
                    'cache'       => ROOT_PATH . '/var/cache/twig',
                    'debug'       => (getenv('APPLICATION_ENV') != 'production'),
                    'auto_reload' => true,
                ],
            ],

            // Database settings
            'database' => [
                'driver'    => getenv('DB_DRIVER'),
                'host'      => getenv('DB_HOST'),
                'dbname'    => getenv('DB_DATABASE'),
                'user'      => getenv('DB_USERNAME'),
                'password'  => getenv('DB_PASSWORD'),
                'port'      => getenv('DB_PORT'),
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'driverOptions' => [
                    PDO::ATTR_PERSISTENT         => false,                                          // Turn off persistent connections
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,                         // Enable exceptions
                    PDO::ATTR_EMULATE_PREPARES   => true,                                           // Emulate prepared statements
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,                               // Set default fetch mode to array
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'  // Set character set
                ],
            ],

            // monolog settings
            'logger' => [
                'name'  => 'app',
                'path'  =>  ROOT_PATH . '/var/log/app.log',
                'level' => (getenv('APPLICATION_ENV') != 'production') ? Logger::DEBUG : Logger::INFO,
            ]
        ],
    ]);

    if (getenv('APPLICATION_ENV') == 'production') { // Should be set to true in production
        $containerBuilder->enableCompilation(ROOT_PATH . '/var/cache');
    }
};
