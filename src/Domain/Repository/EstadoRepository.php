<?php

namespace Cievs\Domain\Repository;

use Cievs\Domain\Model\Estado;
use Doctrine\DBAL\Exception;

class EstadoRepository extends Repository
{
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
            $this->logger->error($exception->getMessage());

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
            $this->logger->error($exception->getMessage());

            return false;
        }
    }
}
