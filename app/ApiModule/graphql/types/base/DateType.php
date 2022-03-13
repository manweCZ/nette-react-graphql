<?php

namespace ApiModule\GraphQL\Types;

use GraphQL\Error\InvariantViolation;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class DateType extends ScalarType
{
    /**
     * {@inheritdoc}
     */
    public $name = 'Date';

    /**
     * {@inheritdoc}
     */
    public $description = 'This scalar type represents date, represented as Y-m-d';


    /**
     * {@inheritdoc}
     */
    public function serialize($value): string
    {
        if (! $value instanceof \DateTimeInterface) {
            $printedValue = Utils::printSafe($value);
            throw new InvariantViolation('DateTime is not an instance of DateTimeImmutable: ' . $printedValue);
        }

        return $value->format('Y-m-d');
    }


    /**
     * {@inheritdoc}
     */
    public function parseValue($value): ?\DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat('Y-m-d', $value) ?: null;
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
