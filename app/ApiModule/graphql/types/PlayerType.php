<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class PlayerType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'memberNumber' => Type::string(),
                    'firstName' => Type::string(),
                    'lastName' => Type::string(),
                    'price' => Type::float(),
                    'age' => Type::int(),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
