<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Portiny\GraphQL\GraphQL\Type\Types;

class TeeTimeReservationType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'id' => ExtType::int(),
                    'dateCreated' => Types::get(DateTimeType::class),
                    'datePaid' => Types::get(DateTimeType::class),
                    'paymentId' => Type::int(),
                    'priceInclVat' => Type::float(),
                    'bookCart' => Type::boolean(),
                    'contactEmail' => Type::string(),
                    'contactPhone' => Type::string(),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
