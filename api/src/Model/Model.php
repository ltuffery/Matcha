<?php

namespace Matcha\Api\Model;

use Exception;
use Flight;
use Matcha\Api\Factory\Factory;
use PDO;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

abstract class Model
{
    public int $id = 0;
    protected string $table = '';

    protected static function db(): PDO
    {
        return Flight::db();
    }

    public static function getTable(): string
    {
        $class = new ReflectionClass(get_called_class());
        $table = $class->getDefaultProperties()['table'];

        return !empty($table) ? $table : strtolower($class->getShortName());
    }

    /**
     * @throws Exception
     */
    public function save(): Model
    {
        return $this->id > 0 ? $this->update() : $this->create();
    }

    public function update(): Model
    {
        $data = $this->getData();
        $sqlQuery = "UPDATE " . $this->getTable() 
        . " SET " . implode(
            ", ",
            array_map(
                fn ($k, $v) => $k . ' = "' . $v . '"',
                array_keys($data),
                array_values($data)
            )
        )
        . ' WHERE id = ' . $this->id;

        self::db()->exec($sqlQuery);
        $this->reload();

        return $this;
    }

    public function create(): Model
    {
        $data = $this->getData();
        $columns = array_keys($data);
        $values = array_map(fn ($v) => '"' . $v . '"', array_values($data));

        $columnString = implode(", ", $columns);
        $valueString = implode(", ", $values);

        $sqlQuery = "INSERT INTO {$this::getTable()}({$columnString}) VALUES({$valueString})";

        self::db()->exec($sqlQuery);
        $this->reload();

        return $this;
    }

    /**
     * Delete
     */
    public function delete(): void
    {
        $data = $this->getData();
        $where = array_map(fn ($k, $v) => $k . ' = "' . $v . '"', array_keys($data), array_values($data));

        self::db()->exec("DELETE FROM " . $this::getTable() . " WHERE " . implode(" AND ", $where));
    }

    /**
     * Reload the object with the most recent data from the database
     */
    private function reload(): void
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

    /**
     * Fills the object with the values provided as parameters
     * 
     * @param array $data
     * @return void
     */
    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Transforms an associative array into the calling object
     *
     * @param array $object
     * @return Model
     * @throws ReflectionException
     */
    public static function morph(array $object): Model
    {
        $class = new ReflectionClass(get_called_class());

        /** @var Model $entity */
        $entity = $class->newInstance();

        $entity->fill($object);

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
        $where = array_map(function ($k) {
            return $k . "=?";
        }, array_keys($options));

        $stmt = self::db()->prepare("SELECT * FROM " . self::getTable() . " WHERE " . implode(" AND ", $where));
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
    public static function all(?array $options = []): array
    {
        $query = "SELECT * FROM " . self::getTable();

        if (!empty($options)) {
            $where = array_map(fn ($k, $v) => $k . ' = "' . $v . '"', array_keys($options), array_values($options));

            $query .= " WHERE " . implode(" AND ", $where);
        }

        $stmt = self::db()->query($query);
        $users = array_map(fn ($item) => self::morph($item) , $stmt->fetchAll(PDO::FETCH_ASSOC));

        return $users;
    }

    public static function factory(): Factory
    {
        return new Factory(get_called_class());
    }

    public function getData(): array
    {
        $class = new ReflectionClass($this);
        $data = [];

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (!isset($this->{$property->getName()}) || $property->getName() == 'id') {
                continue;
            }

            $propertyName = $property->getName();
            $value = $this->{$propertyName};

            if (is_bool($this->{$propertyName})) {
                $value = (int)$this->{$propertyName};
            }

            $data[$propertyName] = $value;
        }

        return $data;
    }

}
