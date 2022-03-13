<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\InputObjectType;

class InputCourseRoundTypeType extends InputObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'id' => ExtType::int(),
                    'name' => ExtType::nonNull(ExtType::string()),
                    'courseCount' => ExtType::nonNull(ExtType::int()),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
