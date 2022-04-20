<?php

namespace Cievs\Domain\Repository;

interface UserRepository
{
    public function create(array $params): bool;
}