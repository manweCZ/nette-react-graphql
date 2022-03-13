<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ClubType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'IDCLUB' => Type::string(),
                    'CODENUMBER' => Type::string(),
                    'NAME' => Type::string(),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
