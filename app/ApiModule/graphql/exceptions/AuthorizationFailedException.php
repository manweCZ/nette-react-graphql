<?php

namespace App\Api\GraphQL\Exceptions;

use GraphQL\Error\ClientAware;

class AuthorizationFailedException extends \Exception implements ClientAware
{
    public function isClientSafe()
    {
        return true;
    }

    public function getCategory()
    {
        return 'AuthorizationFailed';
    }
}
