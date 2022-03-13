<?php

namespace ApiModule\GraphQL\Types;

use ApiModule\GraphQL\TypesFactory;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class InputTimeZoneSettingType extends InputObjectType
{
    public function __construct(TypesFactory $typesFactory)
    {
        $config = [
            'fields' => function () use ($typesFactory) {
                $fields = [
                    'id' => ExtType::int(),
                    'priceListRule' => ExtType::nonNull(ExtType::int()),
                    'name' => ExtType::string(),
                    'playerCount' => ExtType::int(),
                    'courseInterval' => ExtType::int(),
                ];
                return $fields;
            }
        ];
        parent::__construct($config);
    }
}
