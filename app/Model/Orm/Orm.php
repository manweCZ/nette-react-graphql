<?php

namespace App\Model\Orm;
use Nextras\Dbal\IConnection;
use Nextras\Orm\Model\Model;

/**
 * @property-read DataRepository $data
 */
class Orm extends Model
{

    public function getConnection(): IConnection
    {
        return $this->data->getMapper()->getConnection();
    }

}