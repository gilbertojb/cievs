<?php

namespace Cievs\Application\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class AuthController extends BaseController
{
    public function signUp(Request $request, Response $response, array $args = []): Response
    {
        return $this->render($request, $response, 'auth/signup');
    }
}