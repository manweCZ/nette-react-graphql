<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\ObjectType;

class TeeTimeBoardType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'timeZones' => ExtType::listOf($typesFactory->get(TimeZoneType::class)),
                    'bookedTeetimes' => ExtType::listOf($typesFactory->get(TeeTimeType::class)),
                    'unavailableTeetimes' => ExtType::listOf(ExtType::string()),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
