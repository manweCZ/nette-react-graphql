<?php

declare(strict_types=1);

namespace ApiModule\GraphQL\Mutation;

use ApiModule\GraphQL\Query\BaseRequest;
use Portiny\GraphQL\Contract\Mutation\MutationFieldInterface;

abstract class BaseMutation extends BaseRequest implements MutationFieldInterface
{
    public function getName(): string
    {
        return substr($this->getReflection()->getShortName(), 0, -8);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return 'This mutation has no description.';
    }
}
