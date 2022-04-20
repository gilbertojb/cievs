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
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function render(Request $request, Response $response, string $viewName, array $params = []): Response
    {
        $params['info']    = $this->flash->getFirstMessage('info');
        $params['success'] = $this->flash->getFirstMessage('success');
        $params['error']   = $this->flash->getFirstMessage('error');
        $params['warning'] = $this->flash->getFirstMessage('warning');

        $template = sprintf('views/%s.twig', $viewName);

        return $this->view->render($response, $template, $params);
    }

    public function __get($property)
    {
        if ($this->container->get($property)) {
            return $this->container->get($property);
        }
    }
}