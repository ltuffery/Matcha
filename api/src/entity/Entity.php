<?php

namespace Matcha\Api\entity;

use Exception;
use Flight;
use PDO;
use ReflectionClass;

abstract class Entity
{

    public static PDO|null $db = null;
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

        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            $propsToImplode[] = '`' . $propertyName . '` = "' . $this->{$propertyName} . '"';
        }

        $setClause = implode(',', $propsToImplode);

        if ($this->id > 0) {
            $sqlQuery = 'UPDATE `' . $tableName . '` SET ' . $setClause . ' WHERE id = ' . $this->id;
        } else {
            $sqlQuery = 'INSERT INTO `' . $tableName . '` SET ' . $setClause . ', id = ' . $this->id;
        }

        $result = self::$db->exec($sqlQuery);

        if (self::$db->errorCode()) {
            throw new Exception(self::$db->errorInfo()[2]);
        }

        return $result;
    }

    /**
     *
     * @return Entity
     */
    public static function morph(array $object) {
        $class = new \ReflectionClass(get_called_class()); // this is static method that's why i use get_called_class

        $entity = $class->newInstance();

        foreach($class->getProperties(\ReflectionProperty::PUBLIC) as $prop) {
            if (isset($object[$prop->getName()])) {
                $prop->setValue($entity,$object[$prop->getName()]);
            }
        }

        $entity->initialize(); // soft magic

        return $entity;
    }

    public function __call($name, $arguments) {
        self::$db = Flight::db();
    }

    public static function __callStatic($name, $arguments) {
        self::$db = Flight::db();
    }
}