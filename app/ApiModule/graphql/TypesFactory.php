<?php

namespace ApiModule\GraphQL;

use ApiModule\GraphQL\Query\BaseQuery;
use Nette\DI\Container;

class TypesFactory
{
    private $types = [];
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function get(string $typeClassName)
    {
        if (isset($this->types[$typeClassName]))
            return $this->types[$typeClassName];

        return $this->types[$typeClassName] = new $typeClassName($this);
    }

    /**
     * @param string $queryClass
     * @return BaseQuery|object
     */
    public function getQuery(string $queryClass)
    {
        return $this->container->getByType($queryClass);
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }
}
