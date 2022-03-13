<?php

namespace ApiModule\GraphQL\Types;

use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;

class AlternativeDateType extends ScalarType
{
    /**
     * {@inheritdoc}
     */
    public $name = 'AlternativeDate';

    /**
     * {@inheritdoc}
     */
    public $description = 'This scalar type represents info about alternative date';


    /**
     * {@inheritdoc}
     */
    public function serialize($value): string
    {
        $value['dateFrom'] = $value['dateFrom']->format('Y-m-d H:i:s');
        $value['dateTo'] = $value['dateTo']->format('Y-m-d H:i:s');

        return json_encode($value);
    }


    /**
     * {@inheritdoc}
     */
    public function parseValue($value): array
    {
        return json_decode($value, true);
    }


    /**
     * {@inheritdoc}
     */
    public function parseLiteral($valueNode, ?array $variables = null): ?string
    {
        if ($valueNode instanceof StringValueNode) {
            return $valueNode->value;
        }

        return null;
    }
}
