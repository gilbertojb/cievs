<?php

namespace Cievs\Infra\Repository;

use Cievs\Domain\Repository\EstadoRepository;
use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Psr\Container\ContainerInterface;

class DatabaseEstadoRepository implements EstadoRepository
{
    private Connection $connection;

    private ContainerInterface $container;

    public function __construct(Connection $connection, ContainerInterface $container)
    {
        $this->connection = $connection;
        $this->container = $container;
    }

    public function import(string $name, string $sigla, int $total): bool
    {
        try {
            $this->connection->insert('estados', [
                'nome'  => $name,
                'sigla' => $sigla,
                'total' => $total,
            ]);

            return true;
        } catch (Exception $exception) {
            $logger = $this->container->get('logger');
            $logger->error($exception->getMessage());

            return false;
        }
    }
}
