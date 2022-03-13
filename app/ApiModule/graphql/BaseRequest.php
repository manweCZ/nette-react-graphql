<?php

declare(strict_types=1);

namespace ApiModule\GraphQL\Query;

use ApiModule\GraphQL\TypesFactory;
use App\Model\Orm\Orm;
use Nette\DI\Container;
use Nette\Security\User;
use ReflectionClass;

abstract class BaseRequest
{
    protected static array|ReflectionClass $reflection = [];

    public function __construct(
        protected Orm $orm,
        protected TypesFactory $typesFactory,
        protected Container $diContainer,
    ) {
    }

    final public function resolve(array $root, array $args, $context = null)
    {
        if ($this->isAuthorized($this->getUser())) {
            return $this->doResolve($root, $args, $context);
        }
    }

    abstract protected function isAuthorized(User $user): bool;
    abstract protected function doResolve(array $root, array $args, $context = null);

    protected function getReflection(): ReflectionClass
    {
        $class = get_called_class();

        if (isset(static::$reflection[$class])) {
            return static::$reflection[$class];
        }
        return static::$reflection[$class] = new ReflectionClass($this);
    }

    protected function getUser(): User{
        return $this->diContainer->getByType(User::class);
    }
}
