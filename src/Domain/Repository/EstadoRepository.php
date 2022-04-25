<?php

namespace Cievs\Domain\Repository;

interface EstadoRepository
{
    public function import(string $name, string $sigla, int $total): bool;
}
