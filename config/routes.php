<?php

declare(strict_types=1);

use Slim\App;

use Cievs\Controller\HomeController;

return function (App $app) {
    $app->get('/', [HomeController::class, 'index'])->setName('home');
};
