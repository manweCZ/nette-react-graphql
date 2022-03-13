<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Portiny\GraphQL\GraphQL\Type\Types;

class CourseRoundsType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'date' => Types::get(DateType::class),
                    'courseRounds' => ExtType::listOf($typesFactory->get(CourseRoundType::class)),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
