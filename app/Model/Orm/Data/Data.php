<?php

namespace App\Model\Orm;

use Nextras\Orm\Entity\ToArrayConverter;
use Nextras\Orm\Relationships\OneHasMany;

/**
 * @property int                     $id                  {primary}
 * @property Data|null               $dataParent          {m:1 Data::$children}
 * @property OneHasMany|Data[]       $children            {1:m Data::$dataParent}
 * @property string                  $type
 * @property string|null             $subtype
 * @property string                  $name
 * @property int                     $ordering            {default 0}
 * @property string                  $values              {default ''}
 * @property int                     $active              {default 1}
 * @property \DateTimeImmutable|null $dateCreated
 * @property \DateTimeImmutable|null $dateUpdated
 */
abstract class Data extends BaseNextrasEntity
{
    const TYPE = null;
    public ?array $unpackedData = null;

    public function & __get($name)
    {
        if (($isDynamicValue = (str_starts_with($name, '_'))) || $name !== 'values') {
            if ($isDynamicValue) {
                $name = substr($name, 1);
            }

            if ($this->unpackedData === null)
                $this->unpackedData = json_decode($this->values, true);

            if (isset($this->unpackedData[$name]))
                return $this->unpackedData[$name];

            if ($isDynamicValue) {
                $value = null;
                return $value;
            }
        }
        return parent::__get($name);
    }

    public function __set($name, $value): void
    {
        $isDynamicValue = (str_starts_with($name, '_'));
        if (!$isDynamicValue) {
            parent::__set($name, $value);
            return;
        }

        $name = substr($name, 1);

        if ($this->unpackedData === null && $this->isPersisted())
            $this->unpackedData = json_decode($this->values, true);

        $this->unpackedData[$name] = $value;
    }

    public function toArray(int $mode = ToArrayConverter::RELATIONSHIP_AS_IS): array
    {
        $data = parent::toArray($mode);
        if ($this->unpackedData === null)
            $this->unpackedData = json_decode($this->values, true);

        foreach ((array)$this->unpackedData as $k => $v) {
            $data['_' . $k] = $v;
        }

        return $data;
    }

}
