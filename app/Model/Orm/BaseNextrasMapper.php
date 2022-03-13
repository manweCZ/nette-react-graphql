<?php
namespace App\Model\Orm;

class BaseNextrasMapper extends \Nextras\Orm\Mapper\Mapper{

    public function getConnection(): \Nextras\Dbal\IConnection{
        return $this->connection;
    }

    public function getSqlBuilder(): \Nextras\Dbal\QueryBuilder\QueryBuilder{
        return $this->builder();
    }
}