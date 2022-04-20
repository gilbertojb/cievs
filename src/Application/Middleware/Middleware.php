<?php

namespace Cievs\Application\Middleware;

use Psr\Container\ContainerInterface;

class Middleware
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}