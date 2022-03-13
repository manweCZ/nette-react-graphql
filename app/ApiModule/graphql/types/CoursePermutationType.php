<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\ObjectType;

class CoursePermutationType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'name' => ExtType::nonNull(ExtType::string()),
                    'courses' => ExtType::listOf(ExtType::int()),
                    'coursesDetails' => ExtType::listOf($typesFactory->get(CourseType::class)),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
