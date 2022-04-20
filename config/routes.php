<?php

declare(strict_types=1);

use Cievs\Application\Controller\HomeController;
use Cievs\Application\Controller\AuthController;
use Slim\App;

return function (App $app) {
    $app->get('/', [HomeController::class, 'index'])->setName('home');

    $app->group('/auth', function ($group) {
        $group->get('/signup',  [AuthController::class, 'signUp'])->setName('auth.signup');
        $group->post('/signup', [AuthController::class, 'signUp']);

//        $group->get('/auth/signin', AuthController::class . ':getSignIn')->setName('auth.signin');
//        $group->post('/auth/signin', AuthController::class . ':postSignIn');
    });
};
