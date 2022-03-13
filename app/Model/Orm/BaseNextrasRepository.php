<?php
namespace BiteIT;

use App\Model\Orm\BaseNextrasEntity;
use App\Model\Orm\BaseNextrasMapper;
use App\Model\Orm\Orm;
use Nette\Utils\DateTime;
use Nextras\Dbal\Drivers\Exception\QueryException;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Mapper\IMapper;
use Nextras\Orm\Model\IModel;
use Nextras\Orm\Repository\Repository;

abstract class BaseNextrasRepository extends Repository {

    public function persist(IEntity $entity, bool $withCascade = true): IEntity|BaseNextrasEntity
    {
        if($entity->getMetadata()->hasProperty('dateCreated') && !$entity->isPersisted() && !isset($entity->dateCreated)){
            $entity->dateCreated = new DateTime();
        }
        if($entity->getMetadata()->hasProperty('dateUpdated')){
            if($entity->isPersisted())
                $entity->dateUpdated = new DateTime();
            else if($entity->getMetadata()->getProperty('dateUpdated')->isNullable)
                $entity->dateUpdated = null;
        }

        return parent::persist($entity, $withCascade);
    }

    public function getById($id): IEntity|null|BaseNextrasEntity
    {
        return parent::getById($id);
    }

    public function getBy(array $conds): IEntity|null|BaseNextrasEntity
    {
        return parent::getBy($conds);
    }

    public function getModel(): IModel|Orm
    {
        return parent::getModel();
    }

    public function getMapper(): IMapper|BaseNextrasMapper
    {
        return parent::getMapper();
    }

    /**
     * Deletes all data in table
     * @throws \Nextras\Dbal\QueryException
     */
    public function deleteAll(){
        $this->getMapper()->getConnection()->query("DELETE FROM ".$this->getMapper()->getTableName());
        $this->flush();
    }

    public function getDataForSelect(string $idColumn, string $labelColumn, $where = null, $whereParams = []): array{
        $mapper = $this->getMapper();

        $params = array_merge([$mapper->getTableName()], $whereParams);

        if(stripos($labelColumn, ' as ') !== false)
            $label = explode(' as ', strtolower($labelColumn))[1];
        else
            $label = $labelColumn;


        try{
            return $mapper->getConnection()->queryArgs("
                SELECT $idColumn, $labelColumn FROM %table
                $where
            ", $params)->fetchPairs($idColumn, $label);
            }
        catch (\Exception){
            return [];
        }
    }

    /**
     * @param int $itemId
     * @param int|null $prevId
     * @param int|null $nextId
     * @param string|null $additionalCondition
     * @throws
     */
    public function reorder(int $itemId, int $prevId = null, int $nextId = null, string $additionalCondition = null)
    {
        $prevId = empty($prevId) ? null : $prevId;
        $nextId = empty($nextId) ? null : $nextId;

        $queryArray = [
            "SET @currentItemOrder=NULL",
            "SET @nextItemOrder=NULL",
            "SELECT @currentItemOrder:=ordering FROM {$this->getMapper()->getTableName()} WHERE id='%i'; ",
            "SELECT @prevItemOrder:=ordering FROM {$this->getMapper()->getTableName()} WHERE id='%?i'; ",
            "SELECT @nextItemOrder:=ordering FROM {$this->getMapper()->getTableName()} WHERE id='%?i'; ",
            "UPDATE {$this->getMapper()->getTableName()} SET ordering=ordering+1 WHERE ordering>=@nextItemOrder AND ordering<@currentItemOrder ".($additionalCondition? " AND $additionalCondition":"").";",
            "UPDATE {$this->getMapper()->getTableName()} SET ordering=ordering-1 WHERE ordering<=@prevItemOrder AND ordering>@currentItemOrder ".($additionalCondition? " AND $additionalCondition":"")."; ",
            "UPDATE {$this->getMapper()->getTableName()} SET ordering=" .
            "CASE " .
            "   WHEN @nextItemOrder IS NULL OR (@nextItemOrder IS NOT NULL AND @currentItemOrder < @nextItemOrder) THEN @prevItemOrder " .
            "   WHEN @prevItemOrder IS NULL OR (@prevItemOrder IS NOT NULL AND @currentItemOrder > @prevItemOrder) THEN @nextItemOrder " .
            "END WHERE id='%i';"
        ];

        $paramsArray = [
            2=> [$itemId],
            3=>[$prevId],
            4=>[$nextId],
            7=>[$itemId]
        ];

        $this->getMapper()->getConnection()->beginTransaction();
        foreach ($queryArray as $index => $query) {
            $this->getMapper()->getConnection()->queryArgs($query, $paramsArray[$index] ?? []);
        }
        $this->getMapper()->getConnection()->commitTransaction();
    }
}
