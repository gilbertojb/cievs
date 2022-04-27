<?php

namespace Cievs\Domain\Repository;

use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Monolog\Logger;

abstract class Repository
{
    protected Connection $connection;

    protected ContainerInterface $container;

    protected Logger $logger;

    public function __construct(Connection $connection, ContainerInterface $container)
    {
        $this->connection = $connection;
        $this->container  = $container;

        $this->logger = $this->container->get('logger');
    }
}
