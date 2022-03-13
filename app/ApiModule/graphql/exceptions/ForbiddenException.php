<?php

declare(strict_types=1);

namespace ApiModule\GraphQL\Exceptions;

use GraphQL\Error\ClientAware;

/**
 * Logged in but has no access
 */
class ForbiddenException extends \Exception implements ClientAware
{
    const NAME = 'FORBIDDEN';
    const CODE = 403;

    protected $code = ForbiddenException::CODE;

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
