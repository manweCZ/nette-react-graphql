<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Portiny\GraphQL\GraphQL\Type\Types;

class InputCourseRoundType extends InputObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'id' => Type::int(),
                    'courseRoundTypeId' => ExtType::nonNull(ExtType::int()),
                    'priceListRuleId' => ExtType::nonNull(ExtType::int()),
                    'courses' => ExtType::listOf(ExtType::int()),
                    'date' => Types::get(DateType::class),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
