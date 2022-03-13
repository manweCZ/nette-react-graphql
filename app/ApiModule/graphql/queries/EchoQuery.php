<?php
/**
 * User: User
 */

namespace App\Api\GraphQL\Query;

use ApiModule\GraphQL\Query\BaseQuery;
use ApiModule\GraphQL\Types\ExtType;
use GraphQL\Type\Definition\Type;
use Nette\Security\User;

class EchoQuery extends BaseQuery
{

    protected function isAuthorized(User $user): bool
    {
        return true;
    }

    protected function doResolve(array $root, array $args, $context = null)
    {
        return "Hello world";
    }

    public function getArgs(): array
    {
        return [];
    }

    public function getType(): Type
    {
        return ExtType::string();
    }
}