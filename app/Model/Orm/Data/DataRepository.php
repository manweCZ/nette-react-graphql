<?php

namespace App\Model\Orm;


use BiteIT\BaseNextrasRepository;
use Nextras\Orm\Entity\IEntity;

/**
 * Class OrderRepository
 * @package BiteIT
 */
class DataRepository extends BaseNextrasRepository
{
    /**
     * Returns possible entity class names for current repository.
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [
            Data::class,
        ];
    }

    public function getEntityClassName(array $data): string
    {
        foreach (self::getEntityClassNames() as $cn){
            if($data['type'] === $cn::TYPE){
                return $cn;
            }
        }

        return parent::getEntityClassName($data);
    }

    public function persist(Data|IEntity $entity, bool $withCascade = true): IEntity
    {
        /* @var $entity Data */
        $entity->values = json_encode($entity->unpackedData);

        if (!$entity->isPersisted()) {
            if (isset($entity->dataParent) && $entity->dataParent) {
                $parentClause = " = {$entity->dataParent->id}";
            } else {
                $parentClause = " IS NULL";
            }

            $builder = $this->getMapper()->getSqlBuilder();
            $builder->select('MAX(ordering) AS o')->where('type = %s AND data_parent ' . $parentClause, $entity->type);
            $ord = $this->getMapper()->getConnection()->queryByQueryBuilder($builder)->fetch();

            if ($ord && isset($ord->o)) {
                $entity->ordering = intval($ord->o) + 1;
            } else {
                $entity->ordering = 0;
            }
        }

        return parent::persist($entity);
    }

}

