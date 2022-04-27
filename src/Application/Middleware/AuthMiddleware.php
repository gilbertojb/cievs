<?php

namespace Cievs\Application\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthMiddleware extends Middleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);

        if (! $this->container->get('auth')->check()) {
            $this->container->get('flash')->addMessage('error', 'Você precisa estar logado!');
            $url = $this->container->get('router')->urlFor('home');

            return $response->withStatus(302)->withHeader('Location', $url);
        }

        return $response;
    }
}
