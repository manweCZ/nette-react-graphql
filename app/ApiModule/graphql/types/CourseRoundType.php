<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Portiny\GraphQL\GraphQL\Type\Types;

class CourseRoundType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'id' => ExtType::int(),
                    'name' => ExtType::string(),
                    'courseRoundType' => ExtType::int(),
                    'priceListRule' => ExtType::nonNull(ExtType::int()),
                    'courses' => ExtType::listOf($typesFactory->get(CourseType::class)),
                    'date' => Types::get(DateType::class),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
