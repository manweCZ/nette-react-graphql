<?php
/**
 * User: User
 */

namespace App\Api\GraphQL\Query;

use ApiModule\GraphQL\Query\BaseQuery;
use ApiModule\GraphQL\Types\ExtType;
use GraphQL\Type\Definition\Type;
use JetBrains\PhpStorm\ArrayShape;
use Nette\Security\User;

class EchoQuery extends BaseQuery
{

    protected function isAuthorized(User $user): bool
    {
        return true;
    }

    /**
     * @param array $root
     * @param array $args
          #[ArrayShape(['string' => "\GraphQL\Type\Definition\ScalarType"])]
     * @param null  $context
     * @return string
     */
    protected function doResolve(array $root, array $args, $context = null): string
    {
        return isset($args['string']) ? "Echo: {$args['string']}" : "Hello world";
    }

    #[ArrayShape(['string' => "\GraphQL\Type\Definition\ScalarType"])]
    public function getArgs(): array
    {
        return [
            'string' => ExtType::string()
        ];
    }

    public function getType(): Type
    {
        return ExtType::string();
    }
}