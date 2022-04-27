<?php

namespace Cievs\Domain\Repository;

use Cievs\Domain\Model\User;
use Doctrine\DBAL\Exception;

class UserRepository extends Repository
{
    public function findById(int $id): ?User
    {
        try {
            $user = $this->connection->fetchAssociative("SELECT * FROM users WHERE id = ?", [$id]);

            if ($user) {
                return new User(
                    $user['id'],
                    $user['name'],
                    $user['email']
                );
            }

            return null;
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            return null;
        }
    }

    public function findByEmail(string $email): ?User
    {
        try {
            $user = $this->connection->fetchAssociative("SELECT * FROM users WHERE email = ?", [$email]);

            if ($user) {
                return new User(
                    $user['id'],
                    $user['name'],
                    $user['email'],
                    $user['password']
                );
            }

            return null;
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            return null;
        }
    }
}
