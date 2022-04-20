<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

session_start();

define('ROOT_PATH', realpath(__DIR__ . '/..'));

// Include the composer autoloader.
require ROOT_PATH . '/vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Set up settings
$settings = require ROOT_PATH . '/config/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require ROOT_PATH . '/config/dependencies.php';
$dependencies($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

$settings = $container->get('settings');

// Instantiate the app
$app = AppFactory::createFromContainer($container);
$app->setBasePath($settings['base_path']);

// Register middleware
$middleware = require ROOT_PATH . '/config/middlewares.php';
$middleware($app);

// Register routes
$routes = require ROOT_PATH . '/config/routes.php';
$routes($app);

// Add the routing middleware.
$app->addRoutingMiddleware();

// Add error handling middleware.
$app->addErrorMiddleware($settings['debug'], !$settings['debug'], false);

// Run the app
$app->run();