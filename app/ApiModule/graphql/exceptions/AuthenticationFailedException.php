<?php

namespace App\Api\GraphQL\Exceptions;

use GraphQL\Error\ClientAware;

class AuthenticationFailedException extends \Exception implements ClientAware
{
    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory(): string
    {
        return 'AuthenticationFailed';
    }
}
