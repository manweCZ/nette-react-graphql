<?php

namespace ApiModule\GraphQL\Types;

use GraphQL\Error\InvariantViolation;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeList;
use GraphQL\Language\AST\NullValueNode;
use GraphQL\Language\AST\ObjectFieldNode;
use GraphQL\Language\AST\ObjectValueNode;
use GraphQL\Language\AST\VariableNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class ArrayType extends ScalarType
{
    /**
     * {@inheritdoc}
     */
    public $name = 'Array';

    /**
     * {@inheritdoc}
     */
    public $description = 'This scalar type represents array';


    /**
     * Neni hotovo !!
     *
     * {@inheritdoc}
     */
    public function serialize($value): string
    {
        if (!is_array($value)) {
            $printedValue = Utils::printSafe($value);
            throw new InvariantViolation('Array must be array: ' . $printedValue);
        }

        return json_encode($value);
    }


    /**
     * Neni hotovo !!
     *
     * {@inheritdoc}
     */
    public function parseValue($value): ?\DateTimeImmutable
    {
        return json_decode($value);
    }


    /**
     *
     * {@inheritdoc}
     */
    public function parseLiteral($valueNode, ?array $variables = null): ?array
    {
        if ($valueNode instanceof ObjectValueNode) {
            $node = $this->crawlNode($valueNode, $variables);
            return $node;
        }

        return null;
    }

    /**
     * @param Node|VariableNode|ObjectValueNode $objectValueNode
     * @param array|null $variables
     * @return array
     */
    protected function crawlNode(Node $objectValueNode, ?array $variables = null): array
    {
        $data = [];

        /** @var NodeList $nodeList */
        $nodeList = $objectValueNode->fields;

        /** @var ObjectFieldNode $item */
        foreach ($nodeList as $item) {

            if ($item->value instanceof ObjectValueNode) {
                $data[$item->name->value] = $this->crawlNode($item->value, $variables);
            } elseif($item->value instanceof NullValueNode){
                $data[$item->name->value] = null;
            } else {
                if(isset($variables) && count($variables)) {
                    $data[$item->name->value] = isset($variables[$item->value->name->value]) ? $variables[$item->value->name->value] : null;
                } elseif(isset($item->value->value)) {
                    $data[$item->name->value] = $item->value->value;
                }
            }

        }

        return $data;
    }
}
