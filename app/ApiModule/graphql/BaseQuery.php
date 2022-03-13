<?php

declare(strict_types=1);

namespace ApiModule\GraphQL\Query;

use Portiny\GraphQL\Contract\Field\QueryFieldInterface;

abstract class BaseQuery extends BaseRequest implements QueryFieldInterface
{
    public function getName(): string
    {
        return substr($this->getReflection()->getShortName(), 0, -5);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return 'This query has no description.';
    }
}
