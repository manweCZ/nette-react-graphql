<?php

namespace App\Api\GraphQL\Exceptions;

use GraphQL\Error\ClientAware;
use JetBrains\PhpStorm\Pure;

class BadRequestException extends \Exception implements ClientAware
{
    const NAME = 'BAD_REQUEST';
    const CODE = 400;

    #[Pure] public function __construct($message)
    {
        parent::__construct($message, self::CODE);
    }

    public function isClientSafe()
    {
        return true;
    }

    public function getCategory()
    {
        return self::NAME;
    }
}
