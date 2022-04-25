<?php

declare(strict_types=1);

use Cievs\Application\Controller\HomeController;
use Slim\App;

return function (App $app) {
    $app->get('/',        [HomeController::class, 'index'])->setName('home');
    $app->get('/import',  [HomeController::class, 'import'])->setName('import');
    $app->post('/upload', [HomeController::class, 'upload'])->setName('upload');
};
