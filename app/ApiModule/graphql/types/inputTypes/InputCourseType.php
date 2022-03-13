<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class InputCourseType extends InputObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'id' => Type::int(),
                    'name' => ExtType::nonNull(ExtType::string()),
                    'duration' => ExtType::nonNull(ExtType::int()),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
