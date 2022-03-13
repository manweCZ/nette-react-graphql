<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Portiny\GraphQL\GraphQL\Type\Types;

class TimeZoneType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'dateTimeStart' => Types::get(DateTimeType::class),
                    'dateTimeEnd' => Types::get(DateTimeType::class),
                    'courseInterval' => ExtType::int(),
                    'playerCount' => ExtType::int(),
                    'teeTimesCount' => ExtType::int(),
                    'basePrice' => ExtType::float(),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
