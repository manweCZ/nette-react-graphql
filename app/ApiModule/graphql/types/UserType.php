<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Portiny\GraphQL\GraphQL\Type\Types;

class UserType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'firstName' => ExtType::string(),
                    'lastName' => ExtType::string(),
                    'membershipType' => ExtType::string(),
                    'golferIdentifier' => ExtType::string(),
                    'email' => ExtType::string(),
                    'phone' => ExtType::string(),
                    'gender' => ExtType::string(),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
