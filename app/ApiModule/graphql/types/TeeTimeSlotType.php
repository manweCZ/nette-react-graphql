<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Portiny\GraphQL\GraphQL\Type\Types;

class TeeTimeSlotType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'state' => ExtType::int(),
                    'user' => $typesFactory->get(UserType::class),
                    'reservationId' => ExtType::int(),
                    'reservation' => $typesFactory->get(TeeTimeReservationType::class),
                    'firstName' => ExtType::string(),
                    'lastName' => ExtType::string(),
                    'memberNumber' => ExtType::string(),
                    'datePaid' => Types::get(DateTimeType::class),
                    'paymentId' => Type::int(),
                    'priceInclVat' => Type::float(),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
