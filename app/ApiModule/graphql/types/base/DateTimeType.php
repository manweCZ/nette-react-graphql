<?php

namespace ApiModule\GraphQL\Types;

use GraphQL\Error\InvariantViolation;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class DateTimeType extends ScalarType
{
    /**
     * {@inheritdoc}
     */
    public $name = 'DateTime';

    /**
     * {@inheritdoc}
     */
    public $description = 'This scalar type represents date and time, represented as Y-m-d H:i:s or for input type as Y-m-d H:i';


    /**
     * {@inheritdoc}
     */
    public function serialize($value): string
    {
        if (! $value instanceof \DateTimeInterface) {
            $printedValue = Utils::printSafe($value);
            throw new InvariantViolation('DateTime is not an instance of DateTimeImmutable: ' . $printedValue);
        }

        return $value->format('Y-m-d H:i:s');
    }


    /**
     * {@inheritdoc}
     */
    public function parseValue($value): ?\DateTimeImmutable
    {
        $dateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value) ?: null;
        if(!isset($dateTime)) {
            $dateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $value) ?: null;
        }
        return $dateTime;
    }


    /**
     * {@inheritdoc}
     */
    public function parseLiteral($valueNode, ?array $variables = null): ?\DateTimeImmutable
    {
        if ($valueNode instanceof StringValueNode) {
            return $this->parseValue($valueNode->value);
        }
        return null;
    }
}
