<?php

namespace Matcha\Api\Model;

use Exception;
use Flight;
use JsonSerializable;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

abstract class Model implements JsonSerializable
{
    protected string $table = '';

    /**
     * @throws Exception
     */
    public function save(): false|int
    {
        $class = new ReflectionClass($this);
        if ($this->table != '') {
            $tableName = $this->table;
        } else {
            $tableName = strtolower($class->getShortName());
        }

        $propsToImplode = [];

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isInitialized($this)) {
                continue;
            }

            $propertyName = $property->getName();

            if ($propertyName != 'id') {
                $propsToImplode[] = '`' . $propertyName . '` = "' . $this->{$propertyName} . '"';
            }
        }

        $setClause = implode(',', $propsToImplode);

        if ($this->id > 0) {
            $sqlQuery = 'UPDATE `' . $tableName . '` SET ' . $setClause . ' WHERE id = ' . $this->id;
        } else {
            $sqlQuery = 'INSERT INTO `' . $tableName . '` SET ' . $setClause . ', id = ' . $this->id;
        }

        return Flight::db()->exec($sqlQuery);
    }

    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     *
     * @param array $object
     * @return Model
     * @throws ReflectionException
     */
    public static function morph(array $object): Model
    {
        $class = new \ReflectionClass(get_called_class());

        $entity = $class->newInstance();

        foreach($class->getProperties(ReflectionProperty::IS_PUBLIC) as $prop) {
            if (isset($object[$prop->getName()])) {
                $prop->setValue($entity, $object[$prop->getName()]);
            }
        }

        $entity->initialize();

        return $entity;
    }

}