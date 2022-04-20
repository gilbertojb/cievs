<?php

namespace Cievs\Application\Controller;

use Doctrine\DBAL\Connection;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Flash\Messages;
use Slim\Views\Twig;

abstract class BaseController
{
    protected Twig $view;

    protected Logger $logger;

    protected Messages $flash;

    protected Connection $database;

    public function __construct(ContainerInterface $container)
    {
        $this->view     = $container->get('view');
        $this->logger   = $container->get('logger');
        $this->database = $container->get('database');
        $this->flash    = $container->get('flash');
    }

    protected function render(Request $request, Response $response, string $viewName, array $params = []): Response
    {
        $params['flash'] = $this->flash->getMessage('info');

        $template = sprintf('views/%s.twig', $viewName);

        return $this->view->render($response, $template, $params);
    }
}