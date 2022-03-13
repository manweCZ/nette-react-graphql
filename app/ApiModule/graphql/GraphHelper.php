<?php
namespace ApiModule\GraphQL;

use ApiModule\GraphQL\Types\ExtType;
use App\Model\Orm\BaseNextrasEntity;
use Nextras\Orm\Collection\DbalCollection;
use Nextras\Orm\Entity\Reflection\PropertyMetadata;
use Nextras\Orm\Model\MetadataStorage;
use Nextras\Orm\Relationships\ManyHasOne;
use Nextras\Orm\Relationships\OneHasMany;
use Portiny\GraphQL\GraphQL\Type\Scalar\EmailType;
use Portiny\GraphQL\GraphQL\Type\Types;

class GraphHelper
{
    private static $cache = [];

    /**
     * By default it removes all nested objects and arrays it is required to explicitly set it here
     * @param array $data
     * @param array $propertiesToRemove
     * @param array $allowedNestedObjects
     * @return array|BaseNextrasEntity
     */
    public static function resolvedDataFilterOutProperties(array $data, array $allowedNestedObjects = [], array $propertiesToRemove = [])
    {
        if (isset($data[0])) {
            return self::collectionFilterOutProperties($data, $propertiesToRemove, $allowedNestedObjects);
        }
        return self::entityFilterOutProperties($data, $propertiesToRemove, $allowedNestedObjects);
    }

    /**
     * @param DbalCollection|BaseNextrasEntity|array|OneHasMany $collection
     * @param array $properties
     * @param array $allowedNestedObjects
     * @return array
     */
    public static function collectionFilterOutProperties($collection, array $properties, array $allowedNestedObjects)
    {
        $res = [];
        /** @var BaseNextrasEntity $entity */
        foreach ($collection as $entity) {
            $res[] = self::entityFilterOutProperties($entity, $properties, $allowedNestedObjects);
        }
        return $res;
    }

    /**
     * @param BaseNextrasEntity|array $entity
     * @param array $properties
     * @param array $allowedNestedObjects
     * @return array
     */
    public static function entityFilterOutProperties($entity, array $properties, array $allowedNestedObjects)
    {
        if ($entity instanceof BaseNextrasEntity) {
            $entityArr = $entity->toArray();
        } else {
            $entityArr = $entity;
        }
        foreach ($properties as $key => $property) {
            if (is_array($property)) {
                $entityOrCollection = $entityArr[$key];
                if ($entityOrCollection instanceof BaseNextrasEntity || $entityOrCollection instanceof ManyHasOne || (!isset($entityOrCollection[0]) && count($entityOrCollection))) {
                    $entityArr[$key] = self::entityFilterOutProperties($entityOrCollection, $property, []);
                } else {
                    $entityArr[$key] = self::collectionFilterOutProperties($entityOrCollection, $property, []);
                }
            } else {
                if (isset($entityArr[$property])) {
                    $entityArr[$property] = null;
                }
            }
        }
        foreach ($entityArr as $key => $value) {
            $isObjectType = is_array($value);
            if (!$isObjectType) {
                continue;
            }
            $allowedObject = isset($allowedNestedObjects[$key]) ? $allowedNestedObjects[$key] : (in_array($key, $allowedNestedObjects) ? $key : null);
            if (!$allowedObject) {
                $entityArr[$key] = null;
                continue;
            }
            $newAllowedNestedObjects = is_array($allowedObject) ? $allowedObject : [];
            if (isset($value[0])) {
                continue;
            } else {
                $entityArr[$key] = self::entityFilterOutProperties($value, [], $newAllowedNestedObjects);
            }
        }
        return $entityArr;
    }

    /**
     * TODO uložit do cache
     * @param string $className
     * @param null|string[] $allowedProperties
     * @param array|null $propertiesToSkip
     * @return mixed
     */
    public static function getScalarFields(string $className, array $allowedProperties = null, array $propertiesToSkip = null): mixed
    {
        if (isset(self::$cache[$className])) {
            return self::$cache[$className];
        }
        $output = [];
        $metadata = MetadataStorage::get($className);
        /**
         * @var  $key
         * @var  PropertyMetadata $property
         */
        foreach ($metadata->getProperties() as $key => $property) {
            if (is_array($allowedProperties) && !in_array($key, $allowedProperties)) {
                continue;
            }

            if (is_array($propertiesToSkip) && in_array($key, $propertiesToSkip)) {
                continue;
            }

            if (!$property->types || $key === 'password') {
                continue;
            }

            if (isset($property->types['string']) && $property->types['string']) {
                if ($key === 'email' || $key === 'mail') {
                    $output[$key] = Types::get(EmailType::class);
                } else {
                    $output[$key] = ExtType::string();
                }
            }
            if (isset($property->types['int']) && $property->types['int']) {
                $output[$key] = ExtType::int();
            }
            if (isset($property->types['float']) && $property->types['float']) {
                $output[$key] = ExtType::float();
            }
            if (isset($property->types['double']) && $property->types['double']) {
                $output[$key] = ExtType::float();
            }
            if ((isset($property->types['bool']) && $property->types['bool']) || (isset($property->types['boolean']) && $property->types['boolean'])) {
                $output[$key] = ExtType::boolean();
            }
            if (isset($output[$key]) && !$property->isNullable) {
                $output[$key] = ['type' => ExtType::nonNull($output[$key])];
                if (isset($property->defaultValue) && (!is_null($property->defaultValue))) {
                    $output[$key]['description'] = 'Default Value is ' . (is_bool($property->defaultValue) ? ($property->defaultValue ? 'true' : 'false') : '"' . $property->defaultValue . '"');
                }
            }
        }
        self::$cache[$className] = $output;
        return $output;
    }
}
