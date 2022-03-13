<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class TimeZoneSettingType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'id' => Type::int(),
                    'name' => ExtType::string(),
                    'playerCount' => ExtType::int(),
                    'courseInterval' => ExtType::int(),
                    'basePrice' => ExtType::float(),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
