<?php

declare(strict_types=1);

use Cievs\Application\Auth\Auth;
use DI\ContainerBuilder;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'logger' => function (ContainerInterface $container) {
            $settings = $container->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        'database' => function (ContainerInterface $container) {
            $settings = $container->get('settings');

            $config = new Configuration();

            return DriverManager::getConnection($settings['doctrine']['connection'], $config);
        },

        'auth' => function (ContainerInterface $container) {
            return new Auth();
        },

        'flash' => function (ContainerInterface $container) {
            return new Messages();
        },

        'view' => function (ContainerInterface $container) {
            $settings = $container->get('settings');

            $view = Twig::create($settings['view']['template_path'], $settings['view']['twig']);

            $view->getEnvironment()->addGlobal('auth', [
                'check' => $container->get('auth')->check(),
                'user'  => $container->get('auth')->user()
            ]);

            return $view;
        },

        'csrf' => function (ContainerInterface $container) {
            return new Guard(new ResponseFactory());
        },
    ]);
};