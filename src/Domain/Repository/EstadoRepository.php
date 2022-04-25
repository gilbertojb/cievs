<?php

namespace Cievs\Domain\Repository;

use Cievs\Domain\Model\Estado;
use Cievs\Domain\Repository\EstadoRepository;
use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Psr\Container\ContainerInterface;

class EstadoRepository
{
    private Connection $connection;

    private ContainerInterface $container;

    public function __construct(Connection $connection, ContainerInterface $container)
    {
        $this->connection = $connection;
        $this->container = $container;
    }

    public function listagem(): array
    {
        try {
            $estados = [];
            $result = $this->connection->fetchAllAssociative('SELECT * FROM estados');

            foreach ($result as $estado) {
                $estados[] = new Estado(
                    $estado['nome'],
                    $estado['sigla'],
                    $estado['total']
                );
            }

            return $estados;
        } catch (Exception $exception) {
            $logger = $this->container->get('logger');
            $logger->error($exception->getMessage());

            return [];
        }
    }

    public function import(string $nome, string $sigla, int $total): bool
    {
        try {
            $estado = $this->connection->fetchAssociative(
                "SELECT * FROM estados WHERE sigla = ?", [$sigla]
            );

            if ($estado) {
                $data = [
                    'nome'  => $nome,
                    'sigla' => $sigla,
                    'total' => $total + $estado['total']
                ];

                $this->connection->update('estados', $data, ['sigla' => $sigla]);
                return true;
            }

            $this->connection->insert('estados', ['nome' => $nome, 'sigla' => $sigla, 'total' => $total]);
            return true;
        } catch (Exception $exception) {
            $logger = $this->container->get('logger');
            $logger->error($exception->getMessage());

            return false;
        }
    }
}
