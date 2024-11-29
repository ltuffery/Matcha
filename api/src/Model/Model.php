<?php

namespace Matcha\Api\Model;

use Exception;
use Flight;
use JsonSerializable;
use PDO;
use PDOException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

abstract class Model implements JsonSerializable
{
    protected string $table = '';

    protected static function db(): PDO
    {
        return Flight::db();
    }

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
            $sqlQuery = 'INSERT INTO `' . $tableName . '` SET ' . $setClause;
        }

        $result = self::db()->exec($sqlQuery);

        if ($result) {
            $this->reload();
        }

        return $result;
    }

    /**
     * Recharger l'objet avec les données les plus récentes de la base de données.
     */
    public function reload(): void
    {
        $id = $this->db()->lastInsertId();

        if ($id > 0) {
            $class = new ReflectionClass($this);
            $tableName = $this->table ?: strtolower($class->getShortName());

            $sqlQuery = 'SELECT * FROM `' . $tableName . '` WHERE id = ' . $id;

            $stmt = $this->db()->query($sqlQuery);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $this->fill($result);
            }
        }
    }

    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            if (!isset($this->{$key})) {
                continue;
            }

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
        $class = new ReflectionClass(get_called_class());

        $entity = $class->newInstance();

        foreach($class->getProperties(ReflectionProperty::IS_PUBLIC) as $prop) {
            if (isset($object[$prop->getName()])) {
                $entity->{$prop->getName()} = $object[$prop->getName()];
            }
        }

        return $entity;
    }

    /**
     *
     * @return Model|null
     * @throws ReflectionException
     * @throws Exception
     */
    public static function find(array $options): Model|null
    {
        $class = new ReflectionClass(get_called_class());
        $table = $class->getDefaultProperties()['table'];
        $where = array_map(function ($k) {
            return $k . "=?";
        }, array_keys($options));

        $stmt = self::db()->prepare("SELECT * FROM " . $table . " WHERE " . implode(" AND ", $where));
        $stmt->execute(array_values($options));

        $result = $stmt->fetch();

        if ($result == false) {
            return null;
        }

        return self::morph($result);
    }

    /**
     * Get all model
     * 
     * @return Model[]
     * @throws ReflectionException
     */
    public static function all(): array
    {
        $class = new ReflectionClass(get_called_class());
        $table = $class->getDefaultProperties()['table'];

        $stmt = self::db()->query("SELECT * FROM " . $table);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}