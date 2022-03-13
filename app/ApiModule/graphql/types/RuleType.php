<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use App\GraphqlModule\Graph\Types\Scalars\JsonType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Portiny\GraphQL\GraphQL\Type\Types;

class RuleType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'id' => Type::int(),
                    'parentId' => Type::int(),
                    'ruleType' => Type::string(),
                    'data' => Types::get(JsonType::class),
                    'priceValue' => Type::float(),
                    'priceType' => Type::int(),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
