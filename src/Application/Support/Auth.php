<?php

namespace Cievs\Application\Support;

use Cievs\Domain\Model\User;
use Cievs\Domain\Repository\UserRepository;

class Auth
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function user(): ?User
    {
        $userId = $_SESSION['user'] ?? 0;
        return $this->repository->findById($userId);
    }

    public function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public function attempt(string $email, string $password): bool
    {
        $user = $this->repository->findByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            $_SESSION['user'] = $user->getId();
            return true;
        }

        return false;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
    }
}
