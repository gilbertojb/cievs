<?php

declare(strict_types=1);

use Cievs\Application\Support\Auth;
use Cievs\Domain\Repository\UserRepository;
use DI\ContainerBuilder;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;

use function DI\autowire;

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

        'flash' => function (ContainerInterface $container) {
            return new Messages();
        },

        'auth' => function (ContainerInterface $container) {
            return new Auth(new UserRepository(
                $container->get(Connection::class),
                $container
            ));
        },

        'view' => function (ContainerInterface $container) {
            $settings = $container->get('settings');
            $view = Twig::create($settings['view']['template_path'], $settings['view']['twig']);

            $view->getEnvironment()->addGlobal('auth', [
                'check' => $container->get('auth')->check(),
                'user'  => $container->get('auth')->user(),
            ]);

	        return $view;
        },

        'csrf' => function (ContainerInterface $container) {
            return new Guard(new ResponseFactory());
        },

        Connection::class => function (ContainerInterface $container) {
            $config = new Configuration();
            $connectionParams = $container->get('settings')['database'];

            return DriverManager::getConnection($connectionParams, $config);
        },
    ]);
};
