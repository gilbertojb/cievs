<?php

namespace Cievs\Application\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class AuthController extends BaseController
{
    public function signUp(Request $request, Response $response, array $args = []): Response
    {
        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            $this->flash->addMessage('info', 'You have been signed up');

            return $response->withStatus(302)->withHeader('Location', $this->router->urlFor('home'));
        }

        return $this->render($request, $response, 'auth/signup');
    }
}