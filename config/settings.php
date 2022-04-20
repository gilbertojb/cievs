<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'base_path'      => '', // Base path
            'debug'          => (getenv('APPLICATION_ENV') != 'production'), // Is debug mode
            'temporary_path' => ROOT_PATH . '/var/tmp',                            // Temporary directory
            'route_cache'    => ROOT_PATH . '/var/cache/routes',                   // Route cache

            // View settings
            'view' => [
                'template_path' => ROOT_PATH . '/templates',
                'twig' => [
                    'cache'       => ROOT_PATH . '/var/cache/twig',
                    'debug'       => (getenv('APPLICATION_ENV') != 'production'),
                    'auto_reload' => true,
                ],
            ],

            // doctrine settings
            'doctrine' => [
                'meta' => [
                    'entity_path' => [
                        ROOT_PATH . '/src/Model/Entity'
                    ],
                    'auto_generate_proxies' => true,
                    'proxy_dir'             => ROOT_PATH . '/var/cache/proxies',
                    'cache'                 => null,
                ],
                'connection' => [
                    'driver'   => 'pdo_mysql',
                    'host'     => getenv('DB_HOST'),
                    'dbname'   => getenv('DB_NAME'),
                    'user'     => getenv('DB_USER'),
                    'password' => getenv('DB_PASS'),
                ]
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