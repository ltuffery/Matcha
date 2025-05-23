<?php

namespace Matcha\Api\Model;

use Exception;
use Flight;
use Matcha\Api\Builder\QueryBuilder;
use Matcha\Api\Factory\Factory;
use Matcha\Api\Exceptions\UniqueConstraintException;
use PDO;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

abstract class Model
{
    public int $id = 0;
    protected string $table = '';
    protected array $uniques = [];

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
        $this->testValidator();

        return $this->id > 0 ? $this->update() : $this->create();
    }

    private function update(): Model
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

    private function create(): ?Model
    {
        if (!$this->canCreate()) {
            return null;
        }

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

    private function testValidator(): void
    {
        $reflexion = new ReflectionClass($this::class);

        foreach ($reflexion->getProperties() as $property) {
            $attributes = $property->getAttributes();

            if (count($attributes) == 0) {
                break;
            }

            foreach ($attributes as $attribute) {
                $instance = $attribute->newInstance();

                if (!$instance->assert($property->getValue($this))) {
                    throw new Exception("test");
                }
            }
        }
    }

    /**
     * @throws UniqueConstraintException
     */
    private function canCreate(): bool
    {
        if (empty($this->uniques)) {
            return true;
        }

        $data = $this->getData();
        $props = array_filter($data, function (mixed $value, string $key) {
            return is_int(array_search($key, $this->uniques));
        }, ARRAY_FILTER_USE_BOTH);
        $where = array_map(fn ($k, $v) => '`' . $k . '`="' . $v . '"', array_keys($props), array_values($props));

        $sqlQuery = "SELECT * FROM " . $this->getTable() . " WHERE " . implode($this->isAllPrimaryKey($where) ? " AND " : " OR ", $where);

        $stmt = self::db()->prepare($sqlQuery);

        $stmt->execute();
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fetch != null) {
            if (getenv("PHPUNIT_TEST")) {
                ob_end_clean();
            }

            $fields = array_filter($fetch, function (mixed $value, string $key) use ($data) {
                return in_array($key, $this->uniques) && $value == $data[$key];
            }, ARRAY_FILTER_USE_BOTH);

            throw new UniqueConstraintException(implode(", ", $fields) . " : Already exists!");
        }

        return true;
    }

    private function isAllPrimaryKey(array $array): bool
    {
        return count(array_filter($array, fn ($value) => str_contains($value, "_id"))) > 0;
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
            $data = $this->getData();
            $where = array_map(fn ($k, $v) => '`' . $k . '` = "' . $v . '"', array_keys($data), array_values($data));

            $sqlQuery = 'SELECT * FROM `' . $tableName . '` WHERE ' . implode(" AND ", $where);

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
     * @return ?Model
     * @throws ReflectionException
     * @throws Exception
     */
    public static function find(array $options): ?Model
    {
        $where = array_map(function ($k) {
            return $k . "=?";
        }, array_keys($options));

        $stmt = self::db()->prepare("SELECT * FROM " . self::getTable() . " WHERE " . implode(" AND ", $where));
        $stmt->execute(array_values($options));

        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        return self::morph($result);
    }

    /**
     * @param array $params
     * @return QueryBuilder
     */
    public static function where(array $params): QueryBuilder
    {
        $builder = new QueryBuilder(get_called_class());

        if (!is_array($params[0])) {
            return $builder->andWhere(...$params);
        }

        foreach ($params as $param) {
            if (is_array($param)) {
                $builder->andWhere($param[0], $param[1], $param[2]);
            }
        }

        return $builder;
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
        $users = array_map(fn ($item) => self::morph($item), $stmt->fetchAll(PDO::FETCH_ASSOC));

        return $users;
    }

    public static function factory(): Factory
    {
        $split = explode("\\", get_called_class());
        $className = end($split);
        $class = "Matcha\\Api\\Factory\\" . $className . "Factory";

        return new $class(get_called_class());
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
