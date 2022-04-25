<?php

namespace Cievs\Domain\Model;

class Estado
{
    private string $nome;

    private string $sigla;

    private int $total;

    public function __construct(string $nome, string $sigla, int $total)
    {
        $this->nome  = $nome;
        $this->sigla = $sigla;
        $this->total = $total;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getSigla(): string
    {
        return $this->sigla;
    }

    public function getTotal(): string
    {
        return $this->total;
    }

    public function isOk(): bool
    {
        if ($this->getTotal() >= 50) {
            return false;
        }

        return true;
    }
}
