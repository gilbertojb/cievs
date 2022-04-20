<?php

declare(strict_types=1);

use Cievs\Application\Middleware\CsrfViewMiddleware;
use Cievs\Application\Middleware\ValidationErrorsMiddleware;
use DI\ContainerBuilder;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestResponseArgs;

session_start();
define('ROOT_PATH', realpath(__DIR__ . '/..'));

// Include the composer autoloader.
require ROOT_PATH . '/vendor/autoload.php';

// Load env vars
$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(ROOT_PATH);
$dotenv->load();

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

$routeCollector = $app->getRouteCollector();
$routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());
$routeParser = $app->getRouteCollector()->getRouteParser();

$container->set('router', function () use ($routeParser) {
    return $routeParser;
});

// Register middleware
$middleware = require ROOT_PATH . '/config/middlewares.php';
$middleware($app);

// Register routes
$routes = require ROOT_PATH . '/config/routes.php';
$routes($app);

$app->add(new ValidationErrorsMiddleware($container));
$app->add(new CsrfViewMiddleware($container));

// Register CSRF Middleware To Be Executed On All Routes
$app->add('csrf');

// Add the routing middleware.
$app->addRoutingMiddleware();

// Add error handling middleware.
$app->addErrorMiddleware($settings['debug'], !$settings['debug'], false);

// Run the app
$app->run();