<?php

declare(strict_types=1);

use Slim\App;
use Slim\Views\TwigMiddleware;

return function(App $app) {
    $container = $app->getContainer();

//    $app->add($container->get('session'));
    $app->add(TwigMiddleware::createFromContainer($app));
};