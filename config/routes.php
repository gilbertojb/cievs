<?php

declare(strict_types=1);

use Cievs\Application\Controller\AuthController;
use Cievs\Application\Controller\EstadosController;
use Cievs\Application\Controller\HomeController;

use Cievs\Application\Middleware\AuthMiddleware;

use Psr\Container\ContainerInterface;

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app, ContainerInterface $container) {
    $app->get('/', [HomeController::class, 'index'])->setName('home');

    $app->group('/auth', function (Group $group) {
        $group->get('/signin',  [AuthController::class, 'getSignIn'])->setName('auth.signin');
        $group->post('/signin', [AuthController::class, 'postSignIn']);
    });


    $app->group('/auth', function (Group $group) {
        $group->get('/signout', [AuthController::class, 'getSignOut'])->setName('auth.signout');
    })->add(new AuthMiddleware($container));

    $app->group('/estados', function (Group $group) {
        $group->get('',         [EstadosController::class, 'listagem'])->setName('estados.listagem');
        $group->get('/import',  [EstadosController::class, 'import'])->setName('estados.import');
        $group->post('/upload', [EstadosController::class, 'upload'])->setName('estados.upload');
    })->add(new AuthMiddleware($container));
};
