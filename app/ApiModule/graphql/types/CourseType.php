<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\ObjectType;

class CourseType extends ObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'id' => ExtType::int(),
                    'name' => ExtType::nonNull(ExtType::string()),
                    'duration' => ExtType::nonNull(ExtType::int()),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
