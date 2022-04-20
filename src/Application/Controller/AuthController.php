<?php

namespace Cievs\Application\Controller;

use Cievs\Domain\Repository\UserRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class AuthController extends BaseController
{
    protected ContainerInterface $container;

    protected UserRepository $repository;

    public function __construct(ContainerInterface $container, UserRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($container);
    }

    public function signUp(Request $request, Response $response, array $args = []): Response
    {
        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();

            if ($this->repository->create($data)) {
                $this->flash->addMessage('info', 'Conta criada com sucesso!');
                return $response->withStatus(302)->withHeader('Location', $this->router->urlFor('home'));
            }

            $this->flash->addMessage('error', 'Houve um erro ao tentar criar o usuário!');
            return $response->withStatus(302)->withHeader('Location', $this->router->urlFor('auth.signup'));
        }

        return $this->render($request, $response, 'auth/signup');
    }
}