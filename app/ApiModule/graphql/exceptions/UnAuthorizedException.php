<?php

declare(strict_types=1);

namespace ApiModule\GraphQL\Exceptions;

use GraphQL\Error\ClientAware;

/**
 * Not logged in
 */
class UnAuthorizedException extends \Exception implements ClientAware
{
    const NAME = 'UNAUTHORIZED';
    const CODE = 401;

    protected $code = UnAuthorizedException::CODE;

    public function isClientSafe()
    {
        // todo
        return true;
    }

    public function getCategory()
    {
        return static::NAME;
    }
}
