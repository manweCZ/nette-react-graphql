<?php
namespace App\Model\Orm;

use BiteIT\BaseNextrasRepository;
use JetBrains\PhpStorm\Pure;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Repository\IRepository;

class BaseNextrasEntity extends Entity {

    public function load($data, $exclude = []){
        $isArray = is_array($data);
        foreach ($this->getMetadata()->getProperties() as $property)
        {
            if(in_array($property->name, $exclude)){
                continue;
            }

            if($data instanceof IEntity)
            {
                // dont copy relationships
                if($property->relationship || $property->isVirtual || $property->isReadonly){
                    continue;
                }
            }
            else
            {
                if ($property->isReadonly || ($property->isVirtual && !($this instanceof Data))) {
                    continue;
                }
                if ($isArray && !array_key_exists($property->name, $data)) {
                    continue;
                } else if (!$isArray && !property_exists($data, $property->name)) {
                    continue;
                }
            }


            $value = $isArray ? $data[$property->name] : ($data->{$property->name} ?? null);

            if(!$value && $property->isNullable)
                $value = null;

            $this->{$property->name} = $value;
        }
    }


    public function getRepository(): IRepository|BaseNextrasRepository
    {
        return parent::getRepository();
    }

    public function hasTrait($traitClassName): bool
    {
        return in_array($traitClassName, class_uses($this));
    }

    #[Pure] public function getSimpleIdentification(): ?string
    {
        if($this->isPersisted()){
            return "[{$this->getPersistedId()}] ".get_class($this);
        }
        return null;
    }
}

