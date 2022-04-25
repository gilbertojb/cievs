<?php

declare(strict_types=1);

use Cievs\Application\Controller\EstadosController;
use Cievs\Application\Controller\HomeController;

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->get('/', [HomeController::class, 'index'])->setName('home');

    $app->group('/estados', function (Group $group) {
        $group->get('',        [EstadosController::class, 'listagem'])->setName('estados.listagem');
        $group->get('/import',  [EstadosController::class, 'import'])->setName('estados.import');
        $group->post('/upload', [EstadosController::class, 'upload'])->setName('estados.upload');
    });

    // $app->get('/estados',         [EstadosController::class, 'listagem'])->setName('estados.listagem');
    // $app->get('/estados/import',  [EstadosController::class, 'import'])->setName('estados.import');
    // $app->post('/estados/upload', [EstadosController::class, 'upload'])->setName('estados.upload');
};
