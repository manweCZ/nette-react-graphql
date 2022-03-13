<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use App\GraphqlModule\Graph\Types\Scalars\JsonType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Portiny\GraphQL\GraphQL\Type\Types;

class InputPlayerType extends InputObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'memberNumber' => Type::string(),
                    'firstName' => Type::string(),
                    'lastName' => Type::string(),
                    'age' => Type::int(),
                    'slotNumber' => Type::int(),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
