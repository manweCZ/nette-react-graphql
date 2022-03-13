<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Portiny\GraphQL\GraphQL\Type\Types;

class TeeTimeType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'id' => ExtType::int(),
                    'dateStartTime' => Types::get(DateTimeType::class),
                    'time' => ExtType::string(),
                    'bookedSlotsCount' => ExtType::int(),
                    'slots' => ExtType::listOf($typesFactory->get(TeeTimeSlotType::class)),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
