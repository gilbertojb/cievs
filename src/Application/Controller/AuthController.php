<?php

namespace Cievs\Application\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class AuthController extends BaseController
{
    public function getSignOut(Request $request, Response $response): Response
    {
        $this->auth->logout();
        return $response->withStatus(302)->withHeader('Location', $this->router->urlFor('home'));
    }

    public function getSignIn(Request $request, Response $response, array $args = []): Response
    {
        return $this->render($request, $response, 'auth/signin');
    }

    public function postSignIn(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $auth = $this->auth->attempt(
            $data['email'],
            $data['password']
        );

        if (! $auth) {
            $this->flash->addMessage('error', 'Usuário/Senha são inválidos');
            return $response->withStatus(302)->withHeader('Location', $this->router->urlFor('auth.signin'));
        }

        return $response->withStatus(302)->withHeader('Location', $this->router->urlFor('home'));
    }
}
