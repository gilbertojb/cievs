<?php

namespace Cievs\Infra\Repository;

use Cievs\Domain\Repository\UserRepository;
use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Psr\Container\ContainerInterface;

class DatabaseUserRepository implements UserRepository
{
    private Connection $connection;

    private ContainerInterface $container;

    public function __construct(Connection $connection, ContainerInterface $container)
    {
        $this->connection = $connection;
        $this->container = $container;
    }

    public function create(array $params): bool
    {
        try {
            unset($params['csrf_name']);
            unset($params['csrf_value']);

            $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);

            $now = new DateTime();

            $params['created_at'] = $now->format('Y-m-d H:i:s');
            $params['updated_at'] = $now->format('Y-m-d H:i:s');

            $this->connection->insert('users', $params);

            return true;
        } catch (Exception $e) {
            $logger = $this->container->get('logger');
            $logger->error($e->getMessage());

            return false;
        }
    }
}