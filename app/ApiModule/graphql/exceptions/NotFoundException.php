<?php

declare(strict_types=1);

namespace ApiModule\GraphQL\Exceptions;

use GraphQL\Error\ClientAware;

/**
 * Resource not found
 */
class NotFoundException extends \Exception implements ClientAware
{
    const NAME = 'MISSING';
    const CODE = 404;

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
